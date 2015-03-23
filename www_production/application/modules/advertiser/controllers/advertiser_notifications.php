<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertiser_notifications extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->clientid		=$this->session->userdata('session_advertiser_id'); //'2';
		$this->clientemail	=$this->session->userdata('session_advertiser_email'); //"test@gmail.com";
		
		/* Models */
		$this->load->model("mod_advertiser_notifications", 'notifications');		
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	/* Notification Settings Page */
	
	/* Notification Setting Landing Page */
	public function index()
	{
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
         
	    $start					=0;
		$id						=$this->clientid;
		$data['notify']			=$this->notifications->get_advertiser_notify($id);

		$data['page_title'] 	= $this->lang->line('label_advertiser_notify');	
		$data['offset']			=($start ==0)?1:($start + 1);
		
		$data['page_content']	=	$this->load->view('advertiser_notify', $data, TRUE);
		//print_r($data['notify']); die();
		$this->load->view('advertiser_layout', $data);
	}

	/* Notification Setting Upload Process */
	
  function notification_process()
	{
		$id			=$this->clientid;
		$email		=$this->clientemail;
		$rs			=$this->notifications->get_advertiser_notify($id);

		if($rs !=NULL || $rs !='')
		{
			/*$this->form_validation->set_rules('accbalance', 'Total revenue', 'integer|required|xss_clean');
			$this->form_validation->set_rules('dailyvalue', 'Daily revenue', 'integer|required|xss_clean');
			
			
			 if ($this->form_validation->run() == FALSE)
			{
			   /* Form Validation is failed. Redirect to Add Zone Form
			   
				$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_daily_revenue_not_correct"));
				redirect('advertiser/advertiser_notifications');
			}*/			
			
			$accbalance	= $this->input->post("notify");
			$acc_bal	= $this->input->post('accbalance');
			$acc_bal		=strip_tags(trim($acc_bal));
			
			$budbalance	= $this->input->post("notify1");
			$bud_bal	= $this->input->post('dailyvalue');
			$bud_bal		=strip_tags(trim($bud_bal));
			$op		= 'update';

			//if the radio button is selected for total rev
			if($accbalance == "yes")
			{
				if((!preg_match('/^[0-9]+$/', $acc_bal)) || ($acc_bal == NULL) || ($acc_bal == ''))
				{
						$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_balance"));
						redirect('advertiser/advertiser_notifications');
				}
				$acc_bal		=round($acc_bal);
			}
			else
			{	
				$acc_bal		='';
			}
			
			//if the radio button is selected for daily rev
			if($budbalance == "yes")
			{
				if((!preg_match('/^[0-9]+$/', $bud_bal)) || ($bud_bal == NULL) || ($bud_bal == ''))
				{
						$this->session->set_flashdata('errmessage2', $this->lang->line("label_err_daily_balance"));
						redirect('advertiser/advertiser_notifications');
				}
			
				$bud_bal		=round($bud_bal);
			}
			else
			{
				$bud_bal		='';
			}

			$up				=array("accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal, "email"=>$email, "accmail_status" =>0, "budmail_status" =>0);
			$where			=array("clientid" =>$id);
			//print_r($up); die();
			$this->notifications->notify_process($op, $up, $where);
			
			$this->session->set_flashdata('message', $this->lang->line("label_advertiser_notify_successfully"));
			redirect('advertiser/advertiser_notifications');
		}
		else
		{
			
			$accbalance	= $this->input->post("notify");
			$acc_bal	= $this->input->post('accbalance');
			$acc_bal	= strip_tags(trim($acc_bal));

			$budbalance	= $this->input->post("notify1");
			$bud_bal	= $this->input->post('dailyvalue');
			$bud_bal	= strip_tags(trim($bud_bal));
			$op		= 'insert';
			
			//if the radio button is selected for total rev
			if($accbalance == "yes")
			{
				
				if((!preg_match('/^[0-9]+$/', $acc_bal)) || ($acc_bal == NULL) || ($acc_bal == ''))
				{
						$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_balance"));
						redirect('advertiser/advertiser_notifications');
				}
				$acc_bal		=round($acc_bal);
			}
			else
			{
				$acc_bal		='';
			}
			
			//if the radio button is selected for daily rev
			if($budbalance == "yes")
			{
				if((!preg_match('/^[0-9]+$/', $bud_bal)) || ($bud_bal == NULL) || ($bud_bal == ''))
				{
						$this->session->set_flashdata('errmessage2', $this->lang->line("label_err_daily_balance"));
						redirect('advertiser/advertiser_notifications');
				}
			
				$bud_bal		=round($bud_bal);
			}
			else
			{
				$bud_bal		='';
			}
			
			$ins		= array("clientid"=>$id,"accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal, "email"=>$email, "accmail_status" =>0, "budmail_status" =>0);			
			//print_r($ins); die();
			$this->notifications->notify_process($op, $ins);	
			$this->session->set_flashdata('message', $this->lang->line("label_advertiser_notify_successfully"));
			redirect('advertiser/advertiser_notifications');
	     }
  }
}

/* End of file site_settings.php */
/* Location: ./modules/admin/site_settings.php */
