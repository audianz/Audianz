<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_login');
		$this->load->library('email');
    }
	/* Dashboard Page */

    //public function login_check()
       // {
           //     $is_logged_in=$this->session->userdata('mads_sess_admin_username');

             //   if($is_logged_in)
               // {
                       
                      //   echo site_url().'/admin/dashboard';

                      //  return false;
              //  }                 
       // }

	public function index()
	
	{
		if($this->session->userdata('mads_sess_admin_username') !='' and $this->session->userdata('mads_sess_admin_email') !='')
			  {
		  
			   redirect("admin/dashboard");
		   	  }
		else
                      {
       
			$this->load->view('login');
                      }  
		
	}
	
	public function forgot_password()
	{
		
		
		$this->load->view('forgot_password'); 
		
	}
	
	function admin_login_process()
		{
		      
			
                       
		   	$inputData = array(
							"username"	=>	trim($this->input->post("username")),
							"password"	=>  md5($this->input->post("password"))
   						     );

                      
                         
					   
			$admin_login=$this->mod_login->admin_login_process($inputData);
			
			if($admin_login == TRUE)
			{
                           if($this->input->post('remember')== '1')
                                        {
                                                $expire=time()+60*60*24*30;
                                                setcookie("cookie_dreamads_user", $this->input->post('username'), $expire);
                                                setcookie("cookie_dreamads_user_pwd", $this->input->post('password'), $expire);
						//setcookie("cookie_dreamads_user_pwd", $this->input->post('password'), $expire,"/");
                                        }
                                        else
                                        {
                                                $expire=time()-60*60*24*30;
                                                setcookie("cookie_dreamads_user", '', $expire);
                                                setcookie("cookie_dreamads_user_pwd", '', $expire);
                                        }     
				//$log_id = $this->mod_login->record_log_details();
				//$this->session->set_userdata('log_id',$log_id);						
			 redirect("admin/dashboard"); 
		 					
			}
			else
			{
			 
				$this->session->set_userdata('message', $this->lang->line('label_invalid_user_password'));
				//$this->index();
				$this->load->view('login'); 
			}	   
			                    
			
	  }		
	  function forget_password_process()
		{
		
			$inputData = array(
							"useremail"	=>	trim($this->input->post("useremail"))
   						     );
					   
			$admin_login=$this->mod_login->forget_password_process($inputData);
		
			if($admin_login != FALSE)
			{
			
			    
			   $new_password=$admin_login['password'];
			   $user_name=$admin_login['username'];
			   
			   $admin_email               =      $this->mod_login->get_admin_email();
			   $subject                   =      $this->lang->line('lang_add_website_subject');
			   $email_data                =      array("username"   =>        $user_name,
		                                                  "password"    =>        $new_password
								 );
                          	
				$content          	= $this->load->view('email/Administrator/forget_password',$email_data,TRUE);
			  				$data['content']		=$content;
			  				 $mail_content			=$this->load->view('email/login/email_tpl', $data, TRUE);
							 $message              =$mail_content;
							 $config['protocol']   ="sendmail";
              						 $config['wordwrap']   =TRUE;		
			  				 $config['mailtype'] 	='html';
			  				 $config['charset']	='UTF-8';        
			  	
							$this->email->initialize($config);
							$this->email->from($admin_email);
							$this->email->to($publisher_email_id);        
							$this->email->subject($subject);        
							$this->email->message($message);
							$this->email->send();
			
				$this->session->set_userdata('eamilmessage', $this->lang->line('label_user_emailid_information'));
				$this->load->view('login');;
			}
		 					
			
			else
			{
			    
				$this->session->set_userdata('message', $this->lang->line('invalid_user_emailid'));
				//$this->index();
				$this->load->view('forgot_password'); 
			}	   
			                    
			
	  }		
}
	


/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
