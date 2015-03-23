<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_site_settings");
		
		/* Libraries */
		$this->load->library('image_lib'); 
		$this->load->library('email');
    }
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
         
	    $data['time_zone']		=	$this->mod_site_settings->get_timezone();
		$data['country']		=	$this->mod_site_settings->get_country();
		$data['getrecord']		=   $this->mod_site_settings->get_site_settings();
		
		
		$data['page_content']	=	$this->load->view('site_settings',$data,true);
		$this->load->view('page_layout',$data);
	}

	
	
	/* Change Administrator Password */
	public function change_password()
	{
	     
	     
 		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
	
		$data['page_content']	=	$this->load->view('changepwd',$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Site Setting Upload Process */
	function site_settings_update()
	{
		     
		$config['upload_path'] = $this->config->item('admin_upload_url'); /* NB! create this dir! */ 
		$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';  
		$config['max_size']  = '0';  
		$config['max_width']  = '0';  
		$config['max_height']  = '0';  
		
		$this->load->library('upload', $config);  
		if ( ! $this->upload->do_upload('image_upload'))
		{
			$error = array('error' => $this->upload->display_errors());
		}
		$filename					=	$this->upload->data();
		$error = array('error' => $this->upload->display_errors());				
		$source_image				= 	$config['upload_path'].$filename['file_name'];
		
		$config2['image_library'] 	= 	'gd2';
		$config2['source_image'] 	= 	$source_image;
		$config2['new_image'] 		= 	$this->config->item('admin_login_logo_url');
		$config2['maintain_ratio'] 	= 	FALSE;
		$config2['width'] 			= 	275;
		$config2['height']			=	103;
		
		
		$config3['image_library'] 	= 	'gd2';
		$config3['source_image'] 	= 	$source_image;
		$config3['new_image'] 		= 	$this->config->item('admin_site_logo_url');
		$config3['maintain_ratio'] 	= 	FALSE;
		$config3['width'] 			= 	187;
		$config3['height']			=	54;
		
		$this->upload->initialize($config);
		$this->image_lib->initialize($config2);
		$this->image_lib->resize();
		$this->image_lib->clear();
		unset($config2);
		$this->image_lib->initialize($config3);
		$this->image_lib->resize();
		
		 $site_title	=	text_db($this->input->post("site_title"));
		 $tagline		=   text_db($this->input->post("tagline"));
		 $time_zone		=	text_db($this->input->post("time_zone"));
		 $site_url		=	text_db($this->input->post("site_url"));
		 $image_upload	=	text_db($filename['file_name']);
		 $email			=	text_db($this->input->post("email"));
		 $address		=	text_db($this->input->post("address"));
		 $city			=	text_db($this->input->post("city"));
		 $state			=	text_db($this->input->post("state"));
		 $country		=	text_db($this->input->post("country"));
						 
		$this->form_validation->set_rules('site_title', 'site title', 'required');
		//$this->form_validation->set_rules('tagline', 'tag line', 'required');
		//$this->form_validation->set_rules('time_zone', 'time zone', 'required');
		//$this->form_validation->set_rules('site_url', 'site url', 'required');
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('state', 'state', 'required');
		$this->form_validation->set_rules('country', 'country', 'required');
	
	if($this->form_validation->run() == FALSE)
	{
		  
		//$this->session->set_userdata('notification_message', $this->lang->line("label_fill_device_capability").'');
		$this->index();
		//redirect("admin/site_settings");
		
		
	}
	else
	{	  
		$inputData = array(
						"site_title"		=>	$site_title,
						"tagline"		=>  $tagline,
						//"time_zone"		=>	$time_zone,
						"site_url"		=>	$site_url,
						"image_upload"		=>	$image_upload,
						"email"			=>	$email,
						"address"		=>	$address,
						"city"			=>	$city,
						"state"			=>	$state,
						"country"		=>	$country									
					   );

	
		$this->mod_site_settings->site_settings_update($inputData);
		$this->session->set_flashdata('message', $this->lang->line("lang_site_settings_updated_successfully"));
		redirect("admin/site_settings");
	 }		
			
            
  }
  function change_password_process()
		{
                       
		     $oldpwd		=	md5($this->input->post("oldpwd"));
			 $newpwd	=   md5($this->input->post("newpwd"));
			 $confirmpwd	=	md5($this->input->post("confirmpwd"));
			 
			 $this->form_validation->set_rules('oldpwd', 'old password', 'required');
			 $this->form_validation->set_rules('newpwd', 'new password', 'required');
		     $this->form_validation->set_rules('confirmpwd', 'confirm password', 'required');
			 if($this->form_validation->run() == FALSE)
			    {
		  		$this->change_password();
		        }
		    else 
		     {	 
			$inputData = array(
							"oldpwd"	=>$oldpwd,
							"newpwd"	=> $newpwd
								
						   );
						   
			 $password_change=$this->mod_site_settings->change_password_process($inputData);
				if($password_change == TRUE)
				{
				 /*-----------------------------------------------------------------------
				SEND  EMAIL TO  CHANGE PASSWORD
				------------------------------------------------------------------------*/
		
			  
			   $admin_email               =      $this->mod_site_settings->get_admin_email();
			   $subject                   =      $this->lang->line('lang_site_settings_subject_email');
                      
			   $email_data                =      array("username"        => $this->session->userdata('mads_sess_admin_username'),
														"password"       =>        $this->input->post("newpwd")
													   );
																								
			  $content          	= $this->load->view('email/Administrator/change_password',$email_data,TRUE);
			  				$data['content']		=$content;
			  				 $mail_content			=$this->load->view('email/login/email_tpl', $data, TRUE);
							 $message              =$mail_content;
							 $config['protocol']   ="sendmail";
              						 $config['wordwrap']   =TRUE;		
			  				 $config['mailtype'] 	='html';
			  				 $config['charset']	='UTF-8';        
			  	
							$this->email->initialize($config);
							$this->email->from($admin_email);
							$this->email->to($admin_email);        
							$this->email->subject($subject);        
							$this->email->message($message);
							$this->email->send();
			
				$this->session->set_flashdata('message', $this->lang->line('lang_site_settings_password_successfully'));
				}
				else
					{
					$this->session->set_flashdata('errormessage', $this->lang->line('lang_site_settings_password_incorrect'));
					}	   
			                    
					redirect("admin/site_settings/change_password");
	  		}
	 }
	function delete_admin_logo()
	{
		$this->mod_site_settings->delete_admin_logo();
		$this->session->set_flashdata('message', $this->lang->line("lang_site_setting_logo_successfully"));
		redirect("admin/site_settings");	
	}
}    
/* End of file site_settings.php */
/* Location: ./modules/admin/site_settings.php */
