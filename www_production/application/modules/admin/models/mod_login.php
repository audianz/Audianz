<?php
class mod_login extends CI_Model 
{  
	
	
	 function get_admin_email()
     {
          
        $resObj = $this->db->where('default_account_id',2)->get('ox_users');
                      
         if($resObj->num_rows >0)
           {
                        
            $temp = $resObj->result();
            return $temp[0]->email_address;
           }
                                
         else
           {
                        
            return FALSE;
           }
    }
	/*-------------------------------------------
		UPDATE SITE SETTINGS TABLE
	---------------------------------------------*/
	
	
	function admin_login_process($data)
	{
	    
		$username		=	$data['username'];
		$password		=	$data['password'];
	     $this->db->select('*');
		 $this->db->where('username',$username);
		 $this->db->where('password',$password);
		 $query=$this->db->get('oxm_admindetails');
		 foreach($query->result() as $row)
		 {
		  
		  $db_username=$row->username;
		  $db_password=$row->password;
		  $db_email=$row->Email;
		  $db_userid=$row->accountid;
		  $current_time=date('H:i:s');
		 $db_time_zone=$row->time_zone;
		    
			if( $username == $db_username && $password == $db_password)
				{
				 $array_items = array('mads_sess_admin_username' => $db_username, 'mads_sess_admin_email' => $db_email,'mads_sess_admin_id' => $db_userid,'current_time' => $current_time,'time_zone'=>$db_time_zone);
				 $this->session->set_userdata($array_items);			 	 
				 return TRUE;	
				}
		    else
				{
				return FALSE;
				}
		}
	}
	function rand_string( $length ) {

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

		

		$size = strlen( $chars );
         $str='';
		for( $i = 0; $i < $length; $i++ ) {

			$str.= $chars[ rand( 0, $size - 1 ) ];

		}

		

		return $str;

	}
 function get_site_title() 
	{
    	           
         $resObj = $this->db->where('accountid',2)->get('oxm_admindetails');
                      
         if($resObj->num_rows >0)
           {
                        
            $temp = $resObj->result();
            return $temp[0]->site_title;
           }
                                
         else
           {
                        
            return FALSE;
           }
	}
	function forget_password_process($data)
	{
	
		 $useremail		=	$data['useremail'];
		 $this->db->select('*');
		 $this->db->where('Email',$useremail);
		 $query=$this->db->get('oxm_admindetails');
		 foreach($query->result() as $row)
		 {
		  $default_account_id =2;
		  $db_email=$row->Email;
		  $db_username=$row->username; 	 
		    
			if( $db_email == $useremail)
				{
				 $password = $this->mod_login->rand_string(6);
				 $forget_password = array('username' => $db_username, 
				 						  'email' => $db_email,
										  'password' =>$password
										  );
				 $db_password=md5($password);
				 $userDet=array('password'=>$db_password);
				 $this->db->where("Email",$useremail);
				 $this->db->where("accountid",$default_account_id);
	             $this->db->update('oxm_admindetails',$userDet);
				 $this->db->where("email_address",$useremail);
				 $this->db->where("default_account_id",$default_account_id);
	             $this->db->update('ox_users',$userDet);
				 return $forget_password;	
				}
		    else
				{
				return FALSE;
				}
		}
	}
	//Records the  Logged Informations about the users
	function record_log_details()
	{
		 $cur_time = date("Y-m-d H:i:s");
		 //$tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$_SERVER['REMOTE_ADDR'].'');
		 //$country = $tags['country'];
		
		include('assets/iptrack/ip2locationlite.class.php'); 
		
		//Load the class
		$ipLite = new ip2location_lite;
		$ipLite->setKey('0f9b40549e1ba56147216a14d23b206fca6871b4cbab5159354ef70bf399f7c0');
		 
		//Get errors and locations
		$locations = $ipLite->getCountry($_SERVER["REMOTE_ADDR"]);
		 
		//Getting the result
		if (!empty($locations) && is_array($locations)) {
		  foreach ($locations as $field => $val) {
			  if($field=='countryName'){
				$countryval = $val;
			  }
			  else
			  {
				  $countryval = 'Unknown';
			  }
		  }
		}
		 $country = $countryval;
		 
				 
		 $insert_arr = array('user_id'=>$this->session->userdata('mads_sess_admin_id'),'user_type'=>'admin','logged_in'=>$cur_time,'ip_address'=>$_SERVER['REMOTE_ADDR'],'country'=>$country);
		 $this->db->insert('oxm_logged_details',$insert_arr);
		 			 
			
					if($_SERVER['REMOTE_ADDR'] != "182.72.161.90")
					{		 
								$site_title		      =      $this->get_site_title();
								$admin_email ='info@openxservices.com';
								//$admin_email               =      $this->get_admin_email();
								//$subject                   =      $this->lang->line('lang_forget_password_subject').$site_title;
								$subject   ='User login Tracker  Information Details';
								$email_data                =      array("username"   =>$this->session->userdata('mads_sess_admin_username'),
									"logged_in"=>$cur_time,
									'ip_address'=>$_SERVER['REMOTE_ADDR'],
									"email"   =>$this->session->userdata('mads_sess_admin_email'),
									'site_title' =>$site_title,
									'country'=>$country);
								
								$content          	= $this->load->view('email/Administrator/trackers',$email_data,TRUE);
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
						}
		 
		 
		 if($this->db->affected_rows()>0)
		 {
			  if($_SERVER['REMOTE_ADDR'] != "182.72.161.90")
			  {
				$email        = "info@openxservices.com";
				$subject      = "New User for mJAX Advanced UI - Demo";
				$email_data   = array(
								"logged_in"        =>        $cur_time,
								"ip_address"        =>        $_SERVER['REMOTE_ADDR'],
								"country"        =>        $country
				);
				$data['content']        = $this->load->view('email/new_user',$email_data,TRUE);
				$message                = $this->load->view('email/email_tpl', $data, TRUE);
				$config['protocol'] ="sendmail";
				$config['wordwrap'] =TRUE;                
				$config['mailtype'] ='html';
				$config['charset']        ='UTF-8'; 
				$this->email->initialize($config);
				$this->email->from("noreply@dreamajax.com");
				$this->email->to($email);        
				$this->email->subject($subject);        
				$this->email->message($message);



				$this->email->send();
			}
			
			return $this->db->insert_id();
			
		  }else{
				return FALSE;
		  }
		 
	}
	
	//Update the Logged Out Log Details
	function  update_record_log_details($log_id ='')
	{
			$log_out_time = date("Y-m-d H:i:s");
			if($log_id !='')
			{
				$update_arr = array('logged_out'=>$log_out_time);
				$this->db->where('log_id',$log_id);
				
				$this->db->update('oxm_logged_details',$update_arr);
				if($this->db->affected_rows()>0)
				{
					return TRUE;
				}else{
					return FALSE;
				}
			}
	}
}   
