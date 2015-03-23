<?php
class Refer_friends extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}
		
		$this->load->library('email');
		
	}
	
	/* Pages Landing Page */
	public function index()
	{
		 if($this->session->userdata('session_publisher_id') == '' OR $this->session->userdata('session_user_type') != 'TRAFFICKER')
		{
		        if($this->session->userdata('session_user_type') =='ADVERTISER')
                        {
				redirect("login");
			}
			else
			{
				redirect("admin/login");			
			}
		}
		
		$this->refer_friends();	
	}
	
	public function refer_friends()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					=	breadcrumb();
		$data['breadcrumb'] 	=	$link;
		
		//Get publisher name and email id from session
		$data['name']			=	$this->session->userdata('session_publisher_name');
		$data['email']			=	$this->session->userdata('session_publisher_email');
		
		$data['page_content']	=	$this->load->view("refer_friends",$data,true);
		$this->load->view('publisher_layout',$data);	
	}
	
	public function send_email()
	{
		/* Form Validation */
		$this->form_validation->set_rules('to','Send email','required');
		$this->form_validation->set_rules('body_content','Body content','required');
		
		
		
		 if ($this->form_validation->run() == FALSE)
		{
		   /* Form Validation is failed. Redirect to Add Zone Form */
		   
		  $this->session->set_userdata('refer_friend_error', $this->lang->line('label_missed'));
		  $this->refer_friends();
		}
		
		
		else
		{
			$to				=trim($this->input->post('to'));
			$to_email		=explode(',', $to);
			$count_email	=count($to_email);
			$body			=trim($this->input->post('body_content'));
			$to_send_email	=array();
			$to_email_error	=array();
			
			//If a single email entered
			if($count_email == 1)
			{	
				if(! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", trim($to_email[0])))
				{
					array_push($to_email_error,$to_email[0]);
					$this->session->set_userdata('error_email',$to_email_error);
					$this->session->set_flashdata('error_email_msg', $this->lang->line('label_error_email'));
					redirect('publisher/refer_friends');
				}
				else
				{
					array_push($to_send_email,trim($to_email[0]));
				}
			}
			else
			{
				//IF multiple email entered
				for($j=0;$j<$count_email;$j++)
				{	
					if(! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", trim($to_email[$j])))
					{
						array_push($to_email_error, trim($to_email[$j]));
					}
					else
					{
						array_push($to_send_email, trim($to_email[$j]));
					}
				}
			}
			
			$refer_array	= array( 
									'from'			=> $this->session->userdata('session_publisher_email'),
									'name'			=> $this->session->userdata('session_publisher_name'),
									'to' 			=> $to_send_email,
									'subject' 		=> $this->lang->line('label_refer_friends_subject_content').$this->lang->line('site_title'),
									'body' 			=> $body				
									);
					
				$this->session->set_userdata('error_email',$to_email_error);

				if($refer_array != FALSE)
				{
					for($i = 0;$i < count($refer_array['to']);$i++)
					{
					
						$refer_array_send	= array( 
									'from'			=> $this->session->userdata('session_publisher_email'),
									'name'			=> $this->session->userdata('session_publisher_name'),
									'to' 			=> $refer_array['to'][$i],
									'subject' 		=> $this->lang->line('label_refer_friends_subject_content').$this->lang->line('site_title'),
									'body' 			=> $body				
									);
									
						
						$content			= $this->load->view('refer_friends/refer_friends_msg', $refer_array_send, TRUE);
						$data['content']	= $content;
						$mail_content		= $this->load->view('refer_friends/email_tpl', $data, TRUE);
						
						$from				= $refer_array_send['from'];
						$name				= $refer_array_send['name'];			
						$subject            = $refer_array_send['subject'];
						$message            = $mail_content;
						$toemail			= $refer_array_send['to'];
						
						$config['protocol']	= "sendmail";
						$config['wordwrap'] = TRUE;		
						$config['mailtype'] = 'html';
						$config['charset']	= 'UTF-8';          
						
						$this->email->initialize($config);
						$this->email->from($from, $name);
						$this->email->to($toemail);        
						$this->email->subject($subject);        
						$this->email->message($message);
						$this->email->send();
					
					}
					
					if(count($to_send_email)>0)
					{
						$this->session->set_flashdata('success_email_message', $this->lang->line('label_success_email_message'));
					}
					
					if(count($to_email_error)>0)
					{
						$this->session->set_userdata('error_email', $to_email_error);
						$this->session->set_flashdata('error_email_msg', $this->lang->line('label_error_email'));
						redirect('publisher/refer_friends');
					}
					
					redirect('publisher/refer_friends');
				}
		
				else
				{
					redirect('publisher/refer_friends');
				}	
		}
	}
}
?>
