<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publisher_notifications extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}
		
		$this->affiliateid	=$this->session->userdata('session_publisher_id'); //51;
		$this->affiliateemail	=$this->session->userdata('session_publisher_email'); //"test@gmail.com";
		/* Models */
		$this->load->model("mod_publisher_notifications", 'notifications');
		
		/* Libraries */
		 
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
		$id						=$this->affiliateid;
		$data['notify']			=$this->notifications->get_publisher_notify($id);

		$data['page_title'] 	= $this->lang->line('label_publisher_notify');	
		$data['offset']			=($start ==0)?1:($start + 1);
		
		$data['page_content']	=	$this->load->view('publisher_notify', $data, TRUE);
		$this->load->view('publisher_layout', $data);
	}

	/* Notification Setting Upload Process */
	
  function notification_process()
	{
		$id			=$this->affiliateid;
		$email			=$this->affiliateemail;
		$rs			=$this->notifications->get_publisher_notify($id);
		
		

		if($rs !=NULL || $rs !='')
		{
			$this->form_validation->set_rules('accbalance', 'Total revenue', 'integer|required|xss_clean');
			$this->form_validation->set_rules('dailyvalue', 'Daily revenue', 'integer|required|xss_clean');
			
			
			 if ($this->form_validation->run() == FALSE)
			{
			   /* Form Validation is failed. Redirect to Add Zone Form */
			   
				$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_daily_revenue_not_correct"));
				redirect('publisher/publisher_notifications');
			}			
			
			$accbalance		=$this->input->post("notify");
			$acc_bal		=$this->input->post('accbalance');
			$acc_bal		=strip_tags(trim($acc_bal));
		
			$budbalance		=$this->input->post("notify1");
			$bud_bal		=$this->input->post('dailyvalue');
			$bud_bal		=strip_tags(trim($bud_bal));
			$op				='update';
			
			//if the radio button is selected for total rev
			if($accbalance == "yes")
			{
				if((!preg_match('/^[0-9]+$/', $acc_bal)) || ($acc_bal == NULL) || ($acc_bal == ''))
				{
						$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_revenue"));
						redirect('publisher/publisher_notifications');
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
						$this->session->set_flashdata('errmessage2', $this->lang->line("label_err_daily_revenue"));
						redirect('publisher/publisher_notifications');
				}
			
				$bud_bal		=round($bud_bal);
			}
			else
			{
				$bud_bal		='';
			}
			
			$up				=array("accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal, "email"=>$email, "accmail_status" =>0, "budmail_status" =>0);
			$where			=array("publisherid" =>$id);
			$this->notifications->notify_process($op, $up, $where);
			
			$this->session->set_flashdata('message', $this->lang->line("label_publisher_notify_successfully"));
			redirect('publisher/publisher_notifications');
		}
		else
		{
			$accbalance			=$this->input->post("notify");
			$acc_bal			=$this->input->post('accbalance');
			$acc_bal			=strip_tags(trim($acc_bal));
			$budbalance			=$this->input->post("notify1");
			$bud_bal			=$this->input->post('dailyvalue');
			$bud_bal			=strip_tags(trim($bud_bal));
			$op					='insert';
			
			//if the radio button is selected for total rev
			if($accbalance == "yes")
			{
				
				if((!preg_match('/^[0-9]+$/', $acc_bal)) || ($acc_bal == NULL) || ($acc_bal == ''))
				{
						$this->session->set_flashdata('errmessage', $this->lang->line("label_err_total_revenue"));
						redirect('publisher/publisher_notifications');
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
						$this->session->set_flashdata('errmessage2', $this->lang->line("label_err_daily_revenue"));
						redirect('publisher/publisher_notifications');
				}
			
				$bud_bal		=round($bud_bal);
			}
			else
			{
				$bud_bal		='';
			}
			
			$ins				=array("publisherid"=>$id,"accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal, "email"=>$email, "accmail_status" =>0, "budmail_status" =>0);
			$this->notifications->notify_process($op, $ins);
	
			$this->session->set_flashdata('message', $this->lang->line("label_publisher_notify_successfully"));
			redirect('publisher/publisher_notifications');
	     }
  }
}

/* End of file site_settings.php */
/* Location: ./modules/admin/site_settings.php */
