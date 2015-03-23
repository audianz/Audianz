<?php
class Siteapi extends CI_Controller
{

        public function __construct()
	{
		parent::__construct();
		$this->load->model('mod_registration');
		$this->load->model('mod_site');
                $this->load->library('email');
        }
	
        /* Dashboard Page */
	public function index()
	{
		$postdata= array_merge($_GET, $_POST, $_FILES);
			
		if(!isset($postdata['data']) or empty($postdata['data']))
		{
			
			log_message('ERROR','Siteapi::index()  request data is null');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>NULL_DATA,
					ERR_STR =>NULL_DATA_STR
			);
			echo json_encode($response);  
			return;
		}

		$jsonStr = $postdata['data'];
		$attributes = json_decode($jsonStr,true);

		if(isset($attributes['api']))
		{
			switch($attributes['api'])
			{
				case 'register_request':
					$this->handleRegisterRequest($attributes);
				break;
				case 'login_request':
					$this->handleLoginRequest($attributes);
				break;
				case 'update_register_request':
					$this->handleUpdateRegister($attributes);
				break;
                                case 'forget_pass_request':
                                        $this->handleForgetPass($attributes);
                                break;
				default:
				//echo "default";
				break;
			}
		}
		else
		{
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>INVALID_API,
					ERR_STR =>INVALID_API_STR
			);
			echo json_encode($response);  
			return;
		} 		
		
		
	}

	public function handleRegisterRequest($attributes)
	{
		if(!empty($attributes['name'])   and !empty($attributes['emailid']) and !empty($attributes['password']) and !empty($attributes['mobile']))
		{

			if($this->user_name_check($attributes['emailid']))
			{
                                  
				$inputData = array("name" => $attributes['name'],
						"email" => $attributes['emailid'],
						"username"=>$attributes['emailid'],
						"password"=>$attributes['password'],
						"account"=>"ADVERTISER",
						"address"=>"",
						"city"=>"",
						"state"=>"",
						"country"=>"",
						"mobile"=>$attributes['mobile'],
						"zip_code"=>""
						);
				$clientid = $this->mod_registration->register($inputData);
				if($clientid)
				{
					//Added by soumya
					 $content		= $this->load->view('email/registration/registration',$registration,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
		   	  $admin_email               	=$this->mod_registration->get_admin_email();
	   		  $subject                   	=$this->lang->line('registration_subject').$this->lang->line('site_title');
	  		  $message                   	=$mail_content;
		      $toemail=$attributes['emailid'];
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
					
					
					
					
					
					
						$response = array(
						STATUS=>SUCCESS_STATUS,
						"clientid"=>$clientid,
						"business_name"=>$attributes['name'],
						"username"=>$attributes['emailid'],
						"emailid"=>$attributes['emailid'],
						"password"=>$attributes['password'],
						"mobile"=>$attributes['mobile'],
						"address"=>null,
						"state"=>null,
						"city"=>null,
						"zip"=>null
						);
					echo json_encode($response);  
					return;
				}  
				else
				{
					$response = array(
						STATUS=>ERR_STATUS,
						ERR_CODE_KEY =>DB_ERR,
						ERR_STR =>DB_ERR_STR
						);
					echo json_encode($response);  
					return;
				}
		
			
			}
			else
			{
				log_message('ERROR','Siteapi::index() Already registered');
				$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>ALREADY_REGD,
					ERR_STR =>ALREADY_REGD_STR
				);
				echo json_encode($response);  
				return;
			}		
		}
		else
		{
		
			log_message('ERROR','Siteapi::handleRegisterRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);  
			return;

		}  
 	} 

	public function handleUpdateRegister($attributes)
	{

		
		if(isset($attributes['clientid']) and isset($attributes['email']) and isset($attributes['password']) )
		{
			$row_userdetails = array(
				'password'=>md5($attributes['password']),			
				'address'=>$attributes['address'],
				'city'=>$attributes['city'],
				'state'=>$attributes['state'],
				'postcode'=>$attributes['zip'],
                                'mobileno'=>$attributes['mobileno'],
                                'websitename'=>$attributes['web_url']
				);
			$whereDetails  = array('email'=>$attributes['email']);
                        $this->db->update('oxm_userdetails',$row_userdetails,$whereDetails);
			if($this->db->affected_rows()>=0)
			{
				$data = array('password'=>md5($attributes['password']),'contact_name'=>$attributes['name']);
				$this->db->where('email_address',$attributes['email']);
			       
                                $this->db->update('ox_users',$data);
                           
				// Update ox_clients
				$data_ox = array('contact'=>$attributes['name'],'clientname'=>$attributes['business_name']);

				$this->db->where('email',$attributes['email']);
				$this->db->update('ox_clients',$data_ox);
                          	$response = array(
						STATUS=>SUCCESS_STATUS,
						"clientid"=>$attributes['clientid'],
						"business_name"=>$attributes['business_name'],
						"name"=>$attributes['name'],
						"address"=>$attributes['address'],
						"city"=>$attributes['city'],
						"state"=>$attributes['state'],
						"zip"=>$attributes['zip'],
						"password"=>$attributes['password'],
                                                "mobile"=>$attributes['mobileno'],
                                                "website"=>$attributes['web_url']

						);
					echo json_encode($response);  
					return;	
			}
			else
			{
				$response = array(
						STATUS=>ERR_STATUS,
						ERR_CODE_KEY =>DB_ERR,
						ERR_STR =>DB_ERR_STR
						);
					echo json_encode($response);  
					return;
			}				


		}
		else
		{
			log_message('ERROR','Siteapi::handleUpdateRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);  
			return;
		}
	}

	public function handleLoginRequest($attributes)
	{

                if(isset($attributes['emailid']) and isset($attributes['password']))
		{
			$this->db->select('*');
			$this->db->where('email',$attributes['emailid']);
			$this->db->where('password',md5($attributes['password']));
			$query = $this->db->get('oxm_userdetails')->num_rows();

			if($query > 0)
            {
                $query = "SELECT oxc.clientid,oxc.clientname,oxc.contact,oxc.email,oxu.mobileno,oxu.websitename,oxu.address,oxu.state,oxu.city,oxu.postcode
                                   FROM  ox_clients oxc,  oxm_userdetails oxu
                                   WHERE oxc.email = oxu.email
                                   AND oxu.email = '".$attributes[emailid]."'";

                    $usersQuery =$this->db->query($query);
                    if($usersQuery->num_rows > 0)
                    {
                        $users = $usersQuery->row_array();
                        $response = array(
                            STATUS=>SUCCESS_STATUS,
                            "clientid"=>$users['clientid'],
                            "business_name"=>$users['clientname'],
                            "name"=>$users['contact'],
                            "emailid"=>$users['email'],
                            "password"=>$attributes['password'],
                            "mobile"=>$users['mobileno'],
                            "address"=>$users['address'],
                            "state"=>$users['state'],
                            "city"=>$users['city'],
                            "website"=>$users['websitename'],
                            "zip"=>$users['postcode']
                        );

                        echo json_encode($response);
                        return;
                    }
                    else
                    {
                        $response = array(
                            STATUS=>ERR_STATUS,
                            ERR_CODE_KEY =>NULL_DATA,
                            ERR_STR =>NULL_DATA_STR
                        );
                        echo json_encode($response);
                        return;
                    }

			}
			else
			{
				$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>INVALID_USER,
					ERR_STR =>INVALID_USER_STR
			);
			echo json_encode($response);
			return;
			}

		}
		else
		{
			log_message('ERROR','Siteapi::handleLoginRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);
			return;
		}

		/* if(isset($attributes['emailid']) and isset($attributes['password']))
		{
			$this->db->select('*');
			$this->db->where('email',$attributes['emailid']);
			$this->db->where('password',md5($attributes['password']));
			$query = $this->db->get('oxm_userdetails')->num_rows();
			
			if($query > 0)
			{
			
				$this->db->select('clientid,clientname');
				$this->db->where('email',$attributes['emailid']);
				$query = $this->db->get('ox_clients');
                                $result =$query->row_array();
				$advid = $result['clientid'];
				$busi_name = $result['clientname'];
				
			
				$response = array(
						STATUS=>SUCCESS_STATUS,
						'clientid'=>$advid,
						'business_name'=>$busi_name
						);
				
					echo json_encode($response);  
					return;
			}
			else
			{
				$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>INVALID_USER,
					ERR_STR =>INVALID_USER_STR
			);
			echo json_encode($response);  
			return;
			}
		
		}
		else
		{
			log_message('ERROR','Siteapi::handleLoginRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);  
			return;
		}   */
	}
	public function user_name_check($username)
	{

		$this->db->select('*');
		$this->db->where('email_address',$username);
		$query=$this->db->get('oxm_newusers')->num_rows();
		$this->db->select('*');
		$this->db->where('email_address',$username);
		$query1=$this->db->get('ox_users')->num_rows();
		 
		if($query > 0 || $query1 >0 )
		{
			return FALSE;
		}
		else
		{
				
			return true;
		}
	}

	



	public function handleForgetPass($attributes)
	{
		//$attributes['emailid']=$_GET['email'];
                if(isset($attributes['emailid']))
                {
		$da=$this->mod_site->check_adver($attributes['emailid']);
		if($da!=' ')
		{
			$pass=	$this->mod_site->forget_password_process($attributes['emailid']);
	
		}
		
			if($pass!=FALSE)
			{

				$content			=$this->load->view('email/login/forget_password',$pass,TRUE);
				$data['content']	=$content;
		    		$mail_content		=$this->load->view('email/login/email_tpl', $data, TRUE);
		     		$admin_email        =$this->mod_site->get_admin_email();
	   		 	$subject            =$this->lang->line('site_title').$this->lang->line('lang_forget_password_subject');
	  		 	$message            =$mail_content;
				$toemail	     =$pass['email'];
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
						$response = array(
                                                     STATUS=>SUCCESS_STATUS);
                                       echo json_encode($response);
                                       return;
				}
				else
				{
					$response = array(
					                STATUS=>ERR_STATUS,
					                ERR_CODE_KEY =>EMAIL_SEND_FAILED,
					                ERR_STR =>EMAIL_SEND_FAILED_STR
			                               );
			               echo json_encode($response);  
			               return;
				}
			    
			}
			
			else
		      {
			   log_message('ERROR','Siteapi::handleForgetPassRequest() Invalid email address ');
                            $response = array(
					      STATUS=>ERR_STATUS,
					      ERR_CODE_KEY =>INVALID_EMAIL,
					      ERR_STR =>INVALID_EMAIL_STR
			                   );
			               echo json_encode($response);  
			               return;
		      }

 		}
                else
                {
                    log_message('ERROR','Siteapi::handleForgetPassRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);
			return;
                }
	}
	
}
?>

