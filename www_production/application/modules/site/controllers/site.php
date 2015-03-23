<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller 
{
     
      public function login_check()
        {
                $is_advertiser_in=$this->session->userdata('session_advertiser_name');
                 $is_publisher_in=$this->session->userdata('session_publisher_name');
                if($is_advertiser_in)
                {
                        echo site_url().'/advertiser/dashboard';
                        
                        return false;
                } 
                else if ($is_publisher_in)
                   {
                   echo site_url().'/publisher/dashboard';
                        
                        return false;
                    }
              else
                  {

        
                   }
                                   
        }
	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_site');
		$this->load->model('mod_registration');
		$this->load->library('email');	
             	
    }
	/* Dashboard Page */
	public function index($page_id=false)
	{
		$page_id = 1;
		$data['menu_list']	= $this->mod_site->get_menu();						
		$data['page']		= $this->mod_site->get_page_content($page_id);			
		/*-------------------------------------------------------------
			Embed current page content into template layout
		 -------------------------------------------------------------*/
		//echo "test";
                     //  exit;


		/*	$email='soumya.dixit@eninov.com';
		$da=$this->mod_site->check_adver($email);
		if($da!=' ')
		{
			$pass=	$this->mod_site->forget_password_process($email);
			//print_r($pass);
		}
		
			if($pass!=FALSE)
			{

				$content			=$this->load->view('email/login/forget_password',$pass,TRUE);
				$data['content']	=$content;
		    	$mail_content		=$this->load->view('email/login/email_tpl', $data, TRUE);
				print_r($mail_content);
		     	$admin_email        =$this->mod_site->get_admin_email();
		     	//echo $admin_email;
	   		 	$subject            =$this->lang->line('site_title').$this->lang->line('lang_forget_password_subject');
	  		 	$message            =$mail_content;
				$toemail			=$pass['email'];
                $config['protocol'] ="sendmail";
                $config['wordwrap'] =TRUE;		
				$config['mailtype']	='html';
				$config['charset']	='UTF-8';        
				$this->email->initialize($config);
				$this->email->from($admin_email ,$this->lang->line('site_title'));
				$this->email->to($toemail);        
				$this->email->subject($subject);        
				$this->email->message($message);
				$this->email->send();		
				if( $this->email->send()==TRUE)
				{
						echo "Generated";
				}
				else
				{
					echo "Generate Error";
					//show_error($this->email->print_debugger());
				} 
			    //$this->session->set_userdata('eilmessage', $this->lang->line('lang_forget_password_information'));
				//$this->load->view('forgot_password');
			}
			
			else
			{
				$this->session->set_userdata('message', $this->lang->line('invalid_user_emailid'));
				echo $this->session->userdata('message');
				//$this->index();
				//$this->load->view('forgot_password'); 
			}   
			
			*/
	



		$data['page_content']	=	$this->load->view("site/home",$data,true);
		$this->load->view('site_layout',$data);	
	}
	
	public function view($page_id=false)
	{
		$page = explode('-',$page_id);
		if($page_id != false && $page[0]!=24){
			// Display Page Content
			
			$data['menu_list']	= $this->mod_site->get_menu();						
			$data['page']		= $this->mod_site->get_page_content($page_id);
						
			/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			
			$data['page_content']	=	$this->load->view("site/view_page",$data,true);
			$this->load->view('site_layout',$data);			
			
			
		}
		else
		{
			redirect("site");
		}
	}
	
	function registration($ac_type='')
		{
			if(strtoupper($ac_type) =="ADV")
			{
				$data['acc_type']	="ADVERTISER";			        
			}
			else if(strtoupper($ac_type) =="PUB")
			{
				$data['acc_type']	="TRAFFICKER";			        
			}
			else
			{
				$data['acc_type']	="";	
			}	
			
			// Display Page Content
			$data['menu_list']		=$this->mod_site->get_menu();
			$data['time_zone']		=$this->mod_registration->get_timezone();
		    $data['country']		=$this->mod_registration->get_country();
			
			/*-------------------------------------------------------------
				Get Categories List
			-------------------------------------------------------------*/
			$category_list 			=$this->mod_registration->getCategory();
			$data['category_list']  =$category_list;
			
			/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			 $data['page_content']	=	$this->load->view("site/registration", $data,true);
			
			$this->load->view('site_layout',$data);				
	}
		function registration_success()
		{
			// Display Page Content
			$data['menu_list']	= $this->mod_site->get_menu();
			$data['time_zone']		 =	$this->mod_registration->get_timezone();
		    $data['country']		     =	$this->mod_registration->get_country();
			/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			
			 $data['page_content']	=	$this->load->view("site/registration_success",$data,true);
			
			$this->load->view('site_layout',$data);				
	}
	
	function approval_process($user_id='0',$user_ref_id='0',$account_type='0')
	{
		// Display Page Content
		$data['menu_list']	= $this->mod_site->get_menu();

		
	 if($user_id ==0 ){redirect("site");}
	 switch($account_type)
				{
					case 'ADVERTISER' : 
							  {
							     $this->mod_registration->activate_advertiser($user_id);
								 break;
							  }

					case 'TRAFFICKER' : 
							  {
								  $this->mod_registration->activate_publisher($user_id);
								  break;
							  }
							  
					case 'ADVERTISER/TRAFFICKER' : 
							  {
									$acc_type=$this->mod_registration->get_acc_type();
									if($acc_type == 'ADVERTISER')
									{
										 $this->mod_registration->activate_advertiser($user_id);
										 $this->mod_registration->activate_publisher($user_id,$account_type);
									 }
									 else
									 {
										  $this->mod_registration->activate_publisher($user_id);
										  $this->mod_registration->activate_advertiser($user_id,$account_type);
									  }
					            }
				
					default  : break;
				}

		/*-------------------------------------------------------------
			Embed current page content into template layout
		-------------------------------------------------------------*/
		$this->session->set_flashdata('message',"Your account has been activated successfully. Login to continue.");

	    redirect('site/registration_success');
	 		
					
	}
	function registration_process()
		{
	
		 $account_type	=	trim($this->input->post("account_type"));
		 $name		=   trim($this->input->post("name"));
		 $username		=	trim($this->input->post("username"));
		 $password	=	trim($this->input->post("password"));
		 $email	=	trim($this->input->post('email'));
		 $website 	=	trim($this->input->post("website"));
		 $category 	=	$this->input->post("category");
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
		//$this->form_validation->set_rules('category', 'Category', 'required');
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('state', 'state', 'required');
		$this->form_validation->set_rules('country', 'country', 'required');
		$this->form_validation->set_rules('mobile', 'mobileno', 'required|numeric');
	
		if($this->form_validation->run() == FALSE)
		{
		
		$data['acc_type']		= $this->input->post("account_type");

		// Display Page Content
		$data['menu_list']	= $this->mod_site->get_menu();
		$data['time_zone']	=$this->mod_registration->get_timezone();
		$data['country']	=$this->mod_registration->get_country();
		
		/*-------------------------------------------------------------
			Get Categories List
        -------------------------------------------------------------*/
        $category_list 			=$this->mod_registration->getCategory();
        $data['category_list']  =$category_list;
		
		/*-------------------------------------------------------------
			Embed current page content into template layout
		 -------------------------------------------------------------*/
		 $data['page_content']	=	$this->load->view("site/registration",$data,true);

		$this->load->view('site_layout',$data);	

	}	
	else
	{	 
	
		$inputData = array(
		                "account_type"=>$account_type,
						"name"	=>	$name,
						"username"		=> $username,
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
						"category"		=>@implode(",", $category)								
					   );
                 
		$registration=$this->mod_registration->registration_insert($inputData);
		
			if($registration != FALSE)
			{
			$approval_type=$this->mod_registration->get_approval_type();
			$registration['approval_type']=$approval_type;
				 /*-----------------------------------------------------------------------
				SEND  EMAIL TO  CHANGE PASSWORD
				------------------------------------------------------------------------*/
//		$content		= $this->load->view('email/registration/registration',$registration,TRUE);
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
		if($approval_type == 1)
		{
		$this->session->set_flashdata('message', $this->lang->line('registration_email_message1')." ".$toemail ." ". $this->lang->line('registration_email_message2'));
	    redirect("site/registration_success");
		}
		else
		{
		$this->session->set_flashdata('message', $this->lang->line('registration_admin_message'));
		redirect("site/registration_success");
		}
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
