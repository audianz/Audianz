<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	public function __construct() 
    {
		parent::__construct();
		$this->content	=$this->income	=$this->advertiser	=$this->publisher	=$this->campaigns	=$this->banners	=$this->zones	=$this->approvals='';
		
		$this->load->library('email'); /* Library for email functionalities */
		$this->load->model("mod_user_activity", 'crontap'); // Model for Cronjob :: loc:Admin/models/mod_cronjob	
    }
	
	public function index()
	{
		$userid	=0;
		$tasks	=$this->crontap->get_activity_tasks_settings($userid);
		$date	=date("Y-m-d");
		foreach ($tasks as $obj =>$set) :
			$task			=explode(",", $set['task_id']);
			$user_id		=$set['activity_user_id'];
			$account_type	=$set['activity_user_type'];
	
		    foreach ($task as $id) :
				switch($id)
				{
					case 1 : 
							  {
								$type				='MANAGER';
								$amount				=$this->crontap->get_activity_tasks_income($type, $user_id, $date);
								if($amount['income'] !==0) :
								$data['income']		=$amount['income'];
								$data['userid']		=$user_id;
								$data['type']		=$account_type;
								$data['date']		=$date;
								$this->income		=$this->load->view('activity/mailtbl/income_tbl', $data, TRUE); 
								endif;

							    break;
							  }

					case 2 : 
							  {
								$records			=$this->crontap->get_activity_tasks_admin_advertiser($date);
								if($records !=NULL) :
								$data['rs']			=$records;
								$data['date']		=$date;
								$this->advertiser	=$this->load->view('activity/mailtbl/advertisers_tbl', $data, TRUE); 
								endif;

							    break;
							  }
							  
					case 3 : 
							  {
								$records			=$this->crontap->get_activity_tasks_admin_publisher($date);
								if($records !=NULL) :
								$data['rs']			=$records;
								$data['date']		=$date;
								$this->publisher	=$this->load->view('activity/mailtbl/publishers_tbl', $data, TRUE); 
								endif;
							    break;
							  }
					
					case 4 : 
							  {
								$records			=$this->crontap->get_activity_tasks_campaigns($type='MANAGER', $user_id=0, $date);
								$data['rs']			=$records;
								$data['date']		=$date;
								$this->campaigns	=$this->load->view('activity/mailtbl/campaigns_tbl', $data, TRUE); 

							    break;
							  }
					
					case 5 : 
							  {
								$records			=$this->crontap->get_activity_tasks_banners($type='MANAGER', $user_id=0, $date);
								$data['rs']			=$records;
								$data['date']		=$date;
								$this->banners		=$this->load->view('activity/mailtbl/banners_tbl', $data, TRUE); 

							    break;
							  }
					
					case 6 : 
							  {
								$records			=$this->crontap->get_activity_tasks_zones($type='MANAGER', $user_id=0, $date);
								if($records !=NULL) :
								$data['rs']			=$records;
								$data['date']		=$date;
								$this->zones			=$this->load->view('activity/mailtbl/zones_tbl', $data, TRUE); 
								endif;
							    break;
							  }			
					
					default  : break;
				}

			endforeach;
			
			// Mail Function to send Appropreate user's mail id
			
		endforeach;
		
		$this->content	.=$this->income;
		$this->content	.=$this->advertiser;
		$this->content	.=$this->publisher;
		$this->content	.=$this->campaigns;
		$this->content	.=$this->banners;
		$this->content	.=$this->zones;
		
		//***	***\\
		
			$records			=$this->crontap->get_activity_tasks_admin_approvals($date);
			if($records !=NULL) :
			$data['rs']			=$records;
			$data['date']		=$date;
			$this->approvals	=$this->load->view('activity/mailtbl/approvals_tbl', $data, TRUE); 
			endif;
			
			$this->content	.=$this->approvals;
		//***	***\\
		
		$this->prepare_mail_template();
	}
	
	function prepare_mail_template()
	{
	$this->load->helper('file');
		$data['content']	=$this->content;
		$mail_content		=$this->load->view('activity/email_tpl', $data, TRUE);
		$this->content		=$mail_content;
		
		
		$this->mail_process();
	}
	
	function mail_process()
	{
	
	   $admin_email               	=$this->crontap->get_admin_email();
	   $subject                   	=$this->lang->line('lang_activity_subject_email');
	   $message                   	=$this->content;
	   $config['protocol'] 			="sendmail";
       $config['wordwrap'] 			=TRUE;		
	   $config['mailtype'] 			='html';
	   $config['charset']			='UTF-8';        
	   $this->email->initialize($config);
	   $this->email->from($admin_email ,$this->lang->line('label_dreamads'));
	   $this->email->to($admin_email);        
	   $this->email->subject($subject);        
	   $this->email->message($message);
	   $this->email->send();	
	}
	
	
	
}

/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
