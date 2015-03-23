<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends CI_Controller 
{

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_registration');
		$this->load->library('email');
    }
	/* Dashboard Page */
	public function index()
	
	{
		
		  $data['time_zone']		 =	$this->mod_registration->get_timezone();
		  $data['country']		     =	$this->mod_registration->get_country();
		$this->load->view('registration',$data); 
		
	}
	
	public function forgot_password()
	{
		
		
		$this->load->view('forgot_password'); 
		
	}
	
	function registration_process()
		{
		
		
		  $account_type	=	trim($this->input->post("account_type"));
		 $name		=   trim($this->input->post("name"));
		 $username		=	trim($this->input->post("username"));
		 $password	=	trim($this->input->post("password"));
		 $email	=	trim($this->input->post('email'));
		 $website 	=	trim($this->input->post("website"));
		 $address		=	trim($this->input->post("address"));
		 $city			=	trim($this->input->post("city"));
		 $state			=	trim($this->input->post("state"));
		 $country		=	trim($this->input->post("country"));
		 $mobile	=	trim($this->input->post("mobile"));
		 $zip	=	trim($this->input->post("zip"));
		 $bothaccount	=	trim($this->input->post("bothaccount"));
		 $website_avd=	trim($this->input->post("website_avd"));
		 
				
		$this->form_validation->set_rules('name', 'Name"', 'required');
		 $this->form_validation->set_rules('username', 'Username', 'required|callback_user_name_check');
	     $this->form_validation->set_rules('password', 'password', 'required');
	     $this->form_validation->set_rules('email', 'email','required|callback_user_email_check');
		 if($website != '')
		 {
		 $this->form_validation->set_rules('website', 'website', 'required|callback_website_check');
		 }
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('state', 'state', 'required');
		$this->form_validation->set_rules('country', 'country', 'required');
		$this->form_validation->set_rules('mobile', 'mobileno', 'required|numeric');
	
		if($this->form_validation->run() == FALSE)
	{
		 
	$this->index();
	}	
	else
	{	 
	
		$inputData = array(
		                "account_type"=>$account_type,
						"name"	=>	$name,
						"username"		=> $username	,
						"password"		=>	$password,
						"email"		=>	$email,
						"website"	=>	$website,
						"address"			=>	$address,
						"city"			=>	$city,
						"state"			=>	$state,
						"country"		=>	$country,
						"address"			=>	$address,
						"mobile"			=>	$mobile,
						"zip"			=>	$zip,
														
					   );
	
		$registration=$this->mod_registration->registration_insert($inputData);
		
			if($registration != FALSE)
			{
			
				 /*-----------------------------------------------------------------------
				SEND  EMAIL TO  CHANGE PASSWORD
				------------------------------------------------------------------------*/
		      $content		= $this->load->view('email/registration/registration',$registration,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
		   	  $admin_email               	=$this->mod_registration->get_admin_email();
	   		  $subject                   	=$this->lang->line('registration_subject').$this->lang->line('site_title');
	  		 $message                   	=$mail_content;
		     $toemail=$registration['email'];
             $config['protocol'] = "sendmail";
                $config['wordwrap'] = TRUE;		
		$config['mailtype'] 		='html';
		$config['charset']			='UTF-8';        
		$this->email->initialize($config);
		$this->email->from($admin_email ,$this->lang->line('site_title'));
		$this->email->to($toemail);        
		$this->email->subject($subject);        
		$this->email->message($message);
		$this->email->send();	
		$this->session->set_flashdata('message', $this->lang->line('label_reg_updated_success'));
		redirect("registration/registration");
		}
 }		
			
    }  
	
	/* Duplicate Check For username*/
	public function user_name_check()
	{
 							
								$this->db->select('*');	
								$this->db->where('username',$this->input->post('username'));
								$query=$this->db->get('oxm_newusers')->num_rows();
								$this->db->select('*');	
								$this->db->where('username',$this->input->post('username'));
								$query1=$this->db->get('ox_users')->num_rows();
                   
			if($query > 0 || $query1 >0 )
			   {
			
				
					$this->form_validation->set_message('user_name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
			   {
			
					return true;
		        }
	                     }
						 
						 public function website_check()
	{
 							
							
							   $pub_website= "http://".$this->input->post('website') ;
							  
							   
								$this->db->select('*');	
								$this->db->where('site_url',$this->input->post('website'));
								$query=$this->db->get('oxm_newusers')->num_rows();
								$this->db->select('*');	
								$this->db->where('name',$pub_website);
			   				$query1=$this->db->get('ox_affiliates')->num_rows();
						
                   
			if($query > 0 || $query1 >0 )
			   {
						
					$this->form_validation->set_message('website_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
			   {  
			         
					return true;
		        }
	                     }
						 
						 public function user_email_check()
	{
 							
								$this->db->select('*');	
								$this->db->where('email_address',$this->input->post('email'));
								$query=$this->db->get('oxm_newusers')->num_rows();
								$this->db->select('*');	
								$this->db->where('email_address',$this->input->post('email'));
								$query1=$this->db->get('ox_users')->num_rows();
                   
			if($query > 0 || $query1 >0 )
			   {
				
					$this->form_validation->set_message('user_email_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
			   {
					return true;
		        }
	                     }
					
	      
  }
