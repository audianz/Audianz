<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Changepassword extends CI_Controller {

	public function __construct()
    	{
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_changepassword");
		  $this->load->library('email');
		/* Libraries */
	
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    	}
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
			$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
	
		
		
	 	if($this->session->userdata('session_user_type') =='ADVERTISER')
		{
		    $data['page_content']	=	$this->load->view('changepwd', $data, true);
			$this->load->view('advertiser_layout', $data);
		}
		else
		{
			redirect('login/login');
		}
	}
	
   	function change_password_process()
	{
		$inputData = array(
					   "oldpwd" =>md5($this->input->post("oldpwd")),
					   "newpwd" =>$this->input->post("newpwd")	
				      );
						   
		$password_change	=$this->mod_changepassword->change_password_process($inputData);
		if($password_change != FALSE)
		{
			/*-----------------------------------------------------------------------*/
			/*	SEND  EMAIL TO  CHANGE PASSWORD										 */
			/*-----------------------------------------------------------------------*/
		     
			$content		= $this->load->view('email/login/change_password', $password_change, TRUE);
			$data['content']	=$content;
			$mail_content		=$this->load->view('email/login/email_tpl', $data, TRUE);
		   	$admin_email          	=$this->mod_changepassword->get_admin_email();
	   		$subject              	=$this->lang->line('site_title').$this->lang->line('lang_forget_password_subject_adv');
	  		$message              	=$mail_content;
		      	$toemail		=$password_change['email'];
                                       
              		$config['protocol']   	="sendmail";
              		$config['wordwrap']   	=TRUE;		
			$config['mailtype'] 	='html';
			$config['charset']	='UTF-8';        
			  
			$this->email->initialize($config);
			$this->email->from($admin_email );
			$this->email->to($toemail);        
			$this->email->subject($subject);        
			$this->email->message($message);
			$this->email->send();	
		
			$this->session->set_flashdata('message', $this->lang->line('label_passwd_change_success'));
		}
		else
		{
			$this->session->set_flashdata('notification_message', $this->lang->line('label_old_passwd_incorrect'));
		}	   
		                  
		redirect("advertiser/changepassword");
	  }		
	  
	
}

/* End of file myaccount.php */
/* Location: ./modules/admin/myaccount.php */

