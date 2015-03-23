<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Payments extends CI_Controller {

	public function __construct()
        {
		parent::__construct();
		$this->load->library("PHPExcel");
		$this->load->library("PHPExcel/IOFactory");
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}
		
		$this->load->model('mod_payments');
		$this->load->model('mod_account');
		
        }
	
	public function index()
	{
            //$pub_id = '1';
            $pub_id = $this->session->userdata('session_publisher_id');

            /*-------------------------------------------------------------
                    Unpaid Earnings
             -------------------------------------------------------------*/
             $report_amt = $this->mod_payments->get_report_pub_amt($pub_id);
             
             
             if(count($report_amt)>0)
             {
             	$report_rev = $report_amt[0]->reportrev;
             }
             else
             {
             	$report_rev = 0;
             }

             $paid_amt  = $this->mod_payments->get_admin_payment($pub_id);
             if(count($paid_amt)>0)
             {
             	$admin_payment = $paid_amt[0]->adminpayment;
             }
             else
             {
             	$admin_payment = 0;
             }	
	     
             $unpaid = $report_rev-$admin_payment;
             $data['unpaid'] = $unpaid;
	     $this->session->set_userdata('unpaid',$unpaid);

             /*-------------------------------------------------------------
                      Withdraw Limit
             -------------------------------------------------------------*/
             $withdraw  = $this->mod_payments->withdraw_limit();
             $withdraw_limit = $withdraw[0]->Amount;
             $data['withdraw_limit'] = $withdraw_limit;
             /*-------------------------------------------------------------
                    Last Issued Payment
             -------------------------------------------------------------*/
             $last_issued = $this->mod_payments->get_last_issued_payment($pub_id);
             if($last_issued>0)
             {
                $last_issued = $last_issued[0];
                $data['issued'] = $last_issued;
             }
             else
             {
                 $data['issued'] = '';
             }
             /*-------------------------------------------------------------
                    Get Available Dates of Months
             -------------------------------------------------------------*/
            $avail_months = $this->mod_payments->get_available_months($pub_id);
            $data['monthAvail']  = $avail_months;
            
        
            
            /*-------------------------------------------------------------
                    Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view("publisher/payments",$data,true);
            $this->load->view('publisher_layout',$data);
	}

        public function payment_history($paidId=0)
        {
            //$pub_id = '1';
            $pub_id = $this->session->userdata('session_publisher_id');
            $tot_issued = $this->mod_payments->get_last_issued_payment($pub_id,$paidId);
            $data['tot_issued'] = $tot_issued;
            $this->load->view("publisher/payment_history",$data);
        }

	public function apply_payment_option()
		{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb']		= $link;
		
		// GET PUBLISHER DETAILS
		$acc_data = $this->mod_account->get_myaccount();
		$data['pub_data'] = $acc_data[0];
		$data['page_content']	=$this->load->view('payment_options',$data,true);
		$this->load->view('publisher_layout',$data);
		}
		
		
	public function cheque_payment_process()
		{
			
			//form validation
			$this->form_validation->set_rules('c_name','Name','required');
			$this->form_validation->set_rules('c_email','Email','required|valid_email');
			$this->form_validation->set_rules('c_acc_no','Account number','required|alpha_numeric');
			$this->form_validation->set_rules('c_amount','Amount','required|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('payment_error1',$this->lang->line('lang_publisher_updation_failed'));
				redirect('publisher/payments/apply_payment_option#tabs1');
	
			}
			else
			{	
				
	     			$unpaid = $this->session->userdata('unpaid');
				$amount	= strip_quotes(trim($this->input->post('c_amount')));	
				
				if($amount > $unpaid)
				{
				$this->session->set_userdata('payment_error1',$this->lang->line('lang_payment_option_insufficient_avail'));
				redirect('publisher/payments/apply_payment_option#tabs1');
				}
				$name 			= $this->input->post('c_name');
			
				$accno 			= $this->input->post('c_acc_no');
				$email			= $this->input->post('c_email');
				$type			= $this->input->post('type');
				$date			= date("Y-m-d H:i:s");
				$id_no			= '0';
				$id 			= $this->session->userdata('session_publisher_id');
								
				//$id=1;
				$sum_amount		= $this->mod_payments->get_sum_amount($id);//Publisher total revenue
				$paid_amount	= $this->mod_payments->get_paid_amount($id);//Admin already paid revenue from total revenue
				$final_amount	= $sum_amount-$paid_amount;//balance to publisher
				
				$admin_amount 	= $this->mod_payments->get_admin_amount();//limit set by admin
				
				if(($amount <= $final_amount) && ($admin_amount <= $final_amount))
				{
					$ins = array("publisherid"	=> $id,
								"name"			=> $name,
								"accountno"		=> $accno,
								"email"			=> $email,
								"paymenttype"	=> $type,
								"date"			=> $date,
								"amount"		=> $amount,
								"status"		=> '0',
								"id_no"			=>	$id_no
								);
					
					$this->mod_payments->add_payment_data($ins);
					
					// UPDATE oxm_userdetails table
						$this->db->where("accountid",$this->session->userdata('session_publisher_account_id'));
						$this->db->update("oxm_userdetails",array("bank_account_no"=>$accno));
					// End of Update
					
					$this->session->set_flashdata('payment_success',$this->lang->line('lang_publisher_option_payment_accept_success'));
					redirect('publisher/payments');
				}
				else
				{
					$this->session->set_flashdata('payment_failure',$this->lang->line('lang_publisher_option_payment_accept_failure'));
					redirect('publisher/payments');
				}
			}
		}
		
	public function paypal_payment_process()
		{
			
			//form validation
			$this->form_validation->set_rules('p_name','Name','required');
			$this->form_validation->set_rules('p_email','Email','required|valid_email');
			$this->form_validation->set_rules('p_amount','Amount','required|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('payment_error2',$this->lang->line('lang_publisher_updation_failed'));
				redirect('publisher/payments/apply_payment_option#tabs2');

			}
			else
			{
				$unpaid = $this->session->userdata('unpaid');
				$amount	= strip_quotes(trim($this->input->post('p_amount')));	
				
				if($amount > $unpaid)
				{
				$this->session->set_userdata('payment_error1',$this->lang->line('lang_payment_option_insufficient_avail'));
				redirect('publisher/payments/apply_payment_option#tabs2');
				}
				$name 			= $this->input->post('p_name');
				$accno 			= '0';
				$email			= $this->input->post('p_email');
				$type			= $this->input->post('type');
				$date			= date("Y-m-d H:i:s");
				$id_no			= '0';
				$id 			= $this->session->userdata('session_publisher_id');
				echo $amount;exit;
				//$id=1;
				$sum_amount		= $this->mod_payments->get_sum_amount($id);//Publisher total revenue
				$paid_amount	= $this->mod_payments->get_paid_amount($id);//Admin already paid revenue from total revenue
				$final_amount	= $sum_amount-$paid_amount;//balance to publisher
				
				$admin_amount 	= $this->mod_payments->get_admin_amount();//limit set by admin
				
				if(($amount <= $final_amount) && ($admin_amount <= $final_amount))
				{
					$ins = array("publisherid"	=> $id,
								"name"			=> $name,
								"accountno"		=> $accno,
								"email"			=> $email,
								"paymenttype"	=> $type,
								"date"			=> $date,
								"amount"		=> $amount,
								"status"		=> '0',
								"id_no"			=>	$id_no
								);
					
					$this->mod_payments->add_payment_data($ins);
					
					// UPDATE oxm_userdetails table
						$this->db->where("accountid",$this->session->userdata('session_publisher_account_id'));
						$this->db->update("oxm_userdetails",array("paypalid"=>$email));
					// End of Update
					
					$this->session->set_flashdata('payment_success',$this->lang->line('lang_publisher_option_payment_accept_success'));
					redirect('publisher/payments');
				}
				else
				{
					$this->session->set_flashdata('payment_failure',$this->lang->line('lang_publisher_option_payment_accept_failure'));
					redirect('publisher/payments');
				}
			}
		}
		
	public function wire_payment_process()
		{
			//form validation
			$this->form_validation->set_rules('w_name','Name','required');
			$this->form_validation->set_rules('w_email','Email','required|valid_email');
			$this->form_validation->set_rules('w_acc_no','Account number','required|alpha_numeric');
			$this->form_validation->set_rules('w_id_no','I.D number','required|alpha_numeric');
			$this->form_validation->set_rules('w_amount','Amount','required|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('payment_error3',$this->lang->line('lang_publisher_updation_failed'));
				redirect('publisher/payments/apply_payment_option#tabs3');

			}
			else
			{
				$name 			= $this->input->post('w_name');
				$name_check 	= $this->mod_payments->check_user($name);//check if user exists
				if($name_check == 0)
				{
					$this->session->set_userdata('payment_error3',$this->lang->line('lang_publisher_option_user_not_exists'));
					redirect('publisher/payments/apply_payment_option#tabs3');
				}
				$unpaid = $this->session->userdata('unpaid');
				$amount	= strip_quotes(trim($this->input->post('w_amount')));	
				
				if($amount > $unpaid)
				{
				$this->session->set_userdata('payment_error1',$this->lang->line('lang_payment_option_insufficient_avail'));
				redirect('publisher/payments/apply_payment_option#tabs3');
				}
				$accno 			= $this->input->post('w_acc_no');
				$email			= $this->input->post('w_email');
				$type			= $this->input->post('type');
				$date			= date("Y-m-d H:i:s");
				$id_no			= $this->input->post('w_id_no');
				$id 			= $this->session->userdata('session_publisher_id');
				//$id=1;
				$sum_amount		= $this->mod_payments->get_sum_amount($id);//Publisher total revenue
				$paid_amount	= $this->mod_payments->get_paid_amount($id);//Admin already paid revenue from total revenue
				$final_amount	= $sum_amount-$paid_amount;//balance to publisher
				
				$admin_amount 	= $this->mod_payments->get_admin_amount();//limit set by admin
				
				if(($amount <= $final_amount) && ($admin_amount <= $final_amount))
				{
					$ins = array("publisherid"	=> $id,
								"name"			=> $name,
								"accountno"		=> $accno,
								"email"			=> $email,
								"paymenttype"	=> $type,
								"date"			=> $date,
								"amount"		=> $amount,
								"status"		=> '0',
								"id_no"			=>	$id_no
								);
					
					$this->mod_payments->add_payment_data($ins);
					
					// UPDATE oxm_userdetails table
						$this->db->where("accountid",$this->session->userdata('session_publisher_account_id'));
						$this->db->update("oxm_userdetails",array("wire_account_no"=>$accno));
					// End of Update
					
					$this->session->set_flashdata('payment_success',$this->lang->line('lang_publisher_option_payment_accept_success'));
					redirect('publisher/payments');
				}
				else
				{
					$this->session->set_flashdata('payment_failure',$this->lang->line('lang_publisher_option_payment_accept_failure'));
					redirect('publisher/payments');
				}
			}
		}
		//Codeigniter's callback function to check for duplication
	public function name_check()
		{
		
		$this->db->where('contact',$this->input->post('c_name'));
		
		$query	=	$this->db->get('ox_affiliates')->num_rows();
		
		if($query>0)
			{
				return true;
				
			}	
		else	
			{
				$this->form_validation->set_message('name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
				return  false;
			}
		}
	public function name_check_paypal()
		{
		
		$this->db->where('contact',$this->input->post('p_name'));
		
		$query	=	$this->db->get('ox_affiliates')->num_rows();
		
		if($query>0)
			{
				return true;
				
			}	
		else	
			{
				$this->form_validation->set_message('name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
				return  false;
			}
		}
	public function name_check_wire()
		{

		
		$this->db->where('contact',$this->input->post('w_name'));
		
		$query	=	$this->db->get('ox_affiliates')->num_rows();
		
		if($query>0)
			{
				return true;
				
			}	
		else	
			{
				
				$this->form_validation->set_message('name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
				return  false;
			}
		}
		
		public function export_excel_payments()
	{
		            
		//$pub_id = '1';
            $pub_id = $this->session->userdata('session_publisher_id');

            /*-------------------------------------------------------------
                    Unpaid Earnings
             -------------------------------------------------------------*/
             $report_amt = $this->mod_payments->get_report_pub_amt($pub_id);
             if(count($report_amt)>0)
             {
             	$report_rev = $report_amt[0]->reportrev;
             }
             else
             {
             	$report_rev = 0;
             }

             $paid_amt  = $this->mod_payments->get_admin_payment($pub_id);
             if(count($paid_amt)>0)
             {
             	$admin_payment = $paid_amt[0]->adminpayment;
             }
             else
             {
             	$admin_payment = 0;
             }	
	     
             $unpaid = $report_rev-$admin_payment;
             $data['unpaid'] = $unpaid;
             /*-------------------------------------------------------------
                      Withdraw Limit
             -------------------------------------------------------------*/
             $withdraw  = $this->mod_payments->withdraw_limit();
             $withdraw_limit = $withdraw[0]->Amount;
             $data['withdraw_limit'] = $withdraw_limit;
             /*-------------------------------------------------------------
                    Last Issued Payment
             -------------------------------------------------------------*/
             $last_issued = $this->mod_payments->get_last_issued_payment($pub_id);
             if($last_issued>0)
             {
                $last_issued = $last_issued[0];
                $data['issued'] = $last_issued;
             }
             else
             {
                 $data['issued'] = '';
             }
             /*-------------------------------------------------------------
                    Get Available Dates of Months
             -------------------------------------------------------------*/
            $avail_months = $this->mod_payments->get_available_months($pub_id);
            $data['monthAvail']  = $avail_months;
           
            $this->mod_payments->export_publisher_payment_details($data);
	}
	
}

/* End of file payments.php */
/* Location: ./modules/publisher/controllers/payments.php */

