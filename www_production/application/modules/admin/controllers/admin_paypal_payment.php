<?php
/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * Paypal controller that provides functionality to the creation for PayPal forms, 
 * submissions, success and cancel requests, as well as IPN responses.
 *
 * The class requires the use of the PayPal_Lib library and config files.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */

class Admin_paypal_payment extends CI_Controller {

	public function __construct()
    {
		parent::__construct();

		/* Libraries */
		$this->load->library('email');
		$this->load->library('Paypal_Lib');
			
		/* Helpers */

		
		/* Models */

		$this->load->model("mod_approval_settings"); //loc:Admin/models/mod_approval_settings
		
		$this->load->model("mod_common_operations"); //loc:Admin/models/mod_common_operations
		

		/* Classes */
		
    }
	
	function index()
	{
	$this->auto_form();
	}
	
function form()
	{
		
	   $this->paypal_lib->add_field('business', $this->session->userdata('email'));
		//$this->paypal_lib->add_field('business', 'dinesh_1338204226_biz@dreamajax.com');
		$this->paypal_lib->add_field('return', site_url('admin/admin_paypal_payment/success'));
	    $this->paypal_lib->add_field('cancel_return', site_url('admin/admin_paypal_payment/cancel'));
	    $this->paypal_lib->add_field('notify_url', site_url('admin/admin_paypal_payemnt/ipn')); // <-- IPN url
	    $this->paypal_lib->add_field('amount', $this->session->userdata('amount'));

		$this->paypal_lib->add_field('item_name', 'Add Fund to AdNetwork');
	    $this->paypal_lib->add_field('item_number', '1');
		// if you want an image button use this:
		$this->paypal_lib->image('button_03.gif');
		
		// otherwise, don't write anything or (if you want to 
		// change the default button text), write this:
		// $this->paypal_lib->button('Click to Pay!');
	
	    $data['paypal_form'] = $this->paypal_lib->paypal_form();
		$this->view('admin/admin_paypal_payment/form', $data);
        
	}

	function auto_form()
	{
	    //Fetch the Session Varaiables
		$pub_id=$this->session->userdata('pub_id');
	    $pay_id=$this->session->userdata('pay_id');
	    $payment_id = base64_encode($pub_id.':'.$pay_id);
	    $fund=$this->session->userdata('amount');
	    //$business=$this->paypal_lib->add_field('business',$this->session->userdata('email'));
	    //$this->session->set_userdata('business',$business);
		$this->paypal_lib->add_field('business', $this->session->userdata('email'));
		$this->paypal_lib->add_field('return', site_url('admin/admin_paypal_payment/success/'));
	    $this->paypal_lib->add_field('cancel_return', site_url('admin/admin_paypal_payment/cancel'));
	   $this->paypal_lib->add_field('notify_url', site_url('admin/admin_paypal_payment/ipn'));
		//$this->paypal_lib->add_field('notify_url', site_url('admin/admin_paypal_payment/ipn/'.$pay_id.'/'.$pub_id.'/'.$fund)); // <-- IPN url
		//echo  site_url('admin/admin_paypal_payment/ipn/'.$pay_id.'/'.$pub_id.'/'.$fund);
		//exit;
	    $this->paypal_lib->add_field('custom', $payment_id); // <-- Verify return
	    
				$this->paypal_lib->add_field('item_name', 'Payment Pay to Publisher');
                $this->paypal_lib->add_field('item_number', '1');
		$this->paypal_lib->add_field('amount', $this->session->userdata('amount'));
		$data['button']=$this->paypal_lib->get_button_content();
		$data['form']=$this->paypal_lib->get_paypal_form_content();
		$this->load->view('admin/paypal_wait',$data);	    
		//$this->paypal_lib->paypal_auto_form();
	}
	
	function cancel()
	{
		
		 log_message('info', 'Transaction Cancelled...');
            /*-------------------------------------------------------------
                Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view('admin/admin_paypal_payment/cancel',$data,true);
            $this->load->view('page_layout',$data);
	}
	
	function success()
	{
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST 
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.
	
		// You could also simply re-direct them to another page, or your own 
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code 
		// below).
		//$data['pp_info'] = $this->input->post();
		/*$clientid=$this->session->userdata('clientid');
			$name=$this->session->userdata('account_name');
			$fund=$this->session->userdata('amount');
			 $item=$this->session->userdata('itemname');
			 $itemno=$this->session->userdata('itemno');
			 $business=$this->session->userdata('business');
			 $date=date("Y-m-d");
			 $ins=array("clientid"=>$clientid,"username"=>$name,"Amount"=>$fund,"date"=>$date);
			 $this->dbproduct->insert_paypaldata($ins);
			 $payapl_id=mysql_query("select * from oxm_accbalance where clientid='$clientid'");
			 $rows=mysql_num_rows($payapl_id);
			 $amount_paypal=mysql_fetch_array($payapl_id);
			 if($rows>0)
			 {
			 $paypal_fund=$amount_paypal['accbalance']+$fund;
			 $ins=array("accbalance"=>$paypal_fund);
			 $where=array("clientid"=>$clientid);
			 $this->dbproduct->update_paypaldata($ins,$where);
			 //$this->load->view('advertiser/account/success');
			 }
			 else
			{
			 $ins=array("clientid"=>$clientid,"accbalance"=>$fund);
			 $this->dbproduct->insert_accbalancedata($ins);
			 //$this->load->view('advertiser/account/success');
			 }*/
		log_message('info', 'We are Success...');
		
         $data['pp_info'] = $this->input->post();
		
         /*-------------------------------------------------------------
                    Breadcrumb Setup Start
           -------------------------------------------------------------*/
         $link = breadcrumb();
         $data['breadcrumb'] = $link;
          /*-------------------------------------------------------------
                        Embed current page content into template layout
         -------------------------------------------------------------*/
         $data['page_content']	= $this->load->view('admin/admin_paypal_payment/success',$data,true);
         $this->load->view('page_layout',$data);			
	//$this->session->set_flashdata('success_message', $this->lang->line('notification_payment_approved'));			
}
	
	function ipn()
	{
		// Payment has been received and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc. You can access a slew of information via the ipn_data() array.
 
		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.
 
		// For this example, we'll just email ourselves ALL the data.
		
		 //print_r($this->paypal_lib->ipn_data);
	
			$to	=$this->mod_common_operations->get_admin_email();
			//$to    = 'dineshangappa@gmail.com,nelsonpremj@gmail.com';
			log_message('info', 'We are in IPN...');
			$this->session->set_userdata('Validate_ipn','Validate IPN is Found');		
		
			if ($this->paypal_lib->validate_ipn()) 
			{
				list($pub_id,$pay_id)	=	explode(':',base64_decode($this->paypal_lib->ipn_data['custom']));
				  /* Store Paypal IPN data into Database */
                        $admin_id     = '2';
			$payment_request_id	= $pay_id;
                        $payer_email    = $this->paypal_lib->ipn_data['payer_email'];
                        $receiver_email = $this->paypal_lib->ipn_data['receiver_email'];
                        $txn_id         = $this->paypal_lib->ipn_data['txn_id'];
                        $ipn_track_id   = $this->paypal_lib->ipn_data['ipn_track_id'];
                        $payment_gross  = $this->paypal_lib->ipn_data['payment_gross'];
                        $mc_currency    = $this->paypal_lib->ipn_data['mc_currency'];
                        $item_name      = $this->paypal_lib->ipn_data['item_name'];
                        $item_number    = $this->paypal_lib->ipn_data['item_number'];
                        $quantity       = $this->paypal_lib->ipn_data['quantity'];
                        $payer_status   = $this->paypal_lib->ipn_data['payer_status'];
                        $payment_status = $this->paypal_lib->ipn_data['payment_status'];
                        $payment_date   = $this->paypal_lib->ipn_data['payment_date'];
                        $country_code   = $this->paypal_lib->ipn_data['address_country_code'];
			$paid_date	= date('Y-m-d H:i:s');			
			
                        $this->session->set_userdata('paid_amount',$payment_gross);

                        $update_order = array(
                            'client_id'     =>  $admin_id,
			    'payment_request_id'=> $payment_request_id,
                            'payer_email'   =>  $payer_email,
                            'receiver_email'=>  $receiver_email,
                            'txn_id'        =>  $txn_id,
                            'ipn_track_id'  =>  $ipn_track_id,
                            'payment_gross' =>  $payment_gross,
                            'mc_currency'   =>  $mc_currency,
                            'item_name'     =>  $item_name,
                            'item_number'   =>  $item_number,
                            'quantity'      =>  $quantity,
                            'payer_status'  =>  $payer_status,
                            'payment_status'=>  $payment_status,
                            'payment_date'  =>  $payment_date,
                            'address_country_code'=> $country_code,
			    'payment_paid_date'=>$paid_date 
                        );
                        log_message('info',$update_order);
						$tbl_name	=	'oxm_paypal_report';
						
                        $last_order_id = $this->mod_common_operations->insert_data($update_order,$tbl_name);
				if(!empty($last_order_id ))
				{
				
				$payer_email= $this->paypal_lib->ipn_data['payer_email'] . ' on '.date('m/d/Y') . ' at ' . date('g:i A') . "\n\n";
				$body ='';
			   	 foreach ($this->paypal_lib->ipn_data as $key=>$value)
			     $body .= "<b>$key</b>: $value<br/>";
				 $data['payer_email']= $payer_email;
				 $data['body_information']=$body;
				 $content		= $this->load->view('email/registration/payment',$data,TRUE);
			     $data['content']	=$content;
			     $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
				 $message                   	=$mail_content;
				 $subject                   	=$this->lang->line('payment_email_subject');
				 $config['protocol'] = "sendmail";
                 $config['wordwrap'] = TRUE;		
		         $config['mailtype'] 		='html';
		         $config['charset']			='UTF-8'; 		
				// load email lib and email results
 				$this->email->initialize($config);
				$this->email->to($to);

				$this->email->from($this->paypal_lib->ipn_data['payer_email'], $this->paypal_lib->ipn_data['payer_name']);
				$this->email->subject($subject);
				$this->email->message($message);	
				$this->email->send();
				
				$pub_email = $this->mod_approval_settings->get_publisher_email($pub_id);
				$to_test    =$pub_email;
                                $data['payment_gross']=$payment_gross;
                                $content	= $this->load->view('email/registration/payment_receiver_email',$data,TRUE);
                                $data['content']	=$content;
				$mail_content	=$this->load->view('email/registration/email_tpl', $data, TRUE);
							
				 $message       =$mail_content;
                                 $subject                   	=$this->lang->line('payment_email_subject').' '.date('m/d/Y');
				 $config1['protocol'] = "sendmail";
                		 $config['wordwrap'] = TRUE;		
		         	 $config1['mailtype'] 		='html';
		         	 $config1['charset']			='UTF-8'; 		
				// load email lib and email results
 				$this->email->initialize($config1);
				$this->email->to($to_test);


				$this->email->from($this->paypal_lib->ipn_data['payer_email'], $this->paypal_lib->ipn_data['payer_name']);
				$this->email->subject($subject);
				$this->email->message($message);	
				$this->email->send();
				log_message('info','send mail');
				$update_data		=	array('status'=>1,'clearing_date'=>$paid_date);
				
				
				$where_arr	=	array('publisherid'=>$pub_id,'id'=>$pay_id);
				$tbl_name	=	'oxm_admin_payment';
				//Update the  oxm_admin_payment status to 1.
				$this->mod_common_operations->update_data($update_data,$where_arr,$tbl_name);
				}
				}else{
			
				$this->session->set_flashdata('error_message',$this->lang->line('notification_payment_rejected'));
				redirect('admin/approvals/payments');
				
			}
			//exit;
		
	}
}
?>
