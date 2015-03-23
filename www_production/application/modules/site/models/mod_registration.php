<?php
class mod_registration extends CI_Model 
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
	
	function get_timezone() 
	{
    	 //$timezone_table=oxm_timezone;             
         $query=$this->db->get('oxm_timezone');
         return $query->result();
	}
	
	function get_country() 
	{
    	           
         $query=$this->db->get('oxm_country');
         return $query->result();
	}
	
	function get_approval_type()
	{
	   
		   $query	=$this->db->get('oxm_apptype');
                 
		 foreach($query->result() as $row)
		 {
		   $approval_type=$row->approval_type;
		 }
          return $approval_type;          
	}
	
	function get_acc_type($user_id=0)
	{
	   
		  
		   $this->db->select('account_type');
		   $this->db->where('user_id',$user_id);
		   $query	=$this->db->get('oxm_newusers');
                 
		 foreach($query->result() as $row)
		 {
		   $acc_type=$row->$account_type;
		 }
          return $acc_type;          
	}
	
	
	 	/**
	 * This method is used to add advertiser as an admin.This is used for APIs
	 * @author Eninov
	 */
	function register($data)
	{
		$cur_date=date("Y-m-d H:i:s");
		/*-------------------------------------------
			CREATE ACCOUNT  FOR ADVERTISER
		---------------------------------------------*/
		$acc_data		=	array(
				"account_type"	=>	text_db($data['account']),
				"account_name"	=>	text_db($data['username'])
		);
		$this->db->insert('ox_accounts',$acc_data);
		$account_id	=	$this->db->insert_id();

		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$userDet		=	array(
				"accountid"		=>	$account_id,
				"accounttype"   =>	text_db($data['account']),
				"username"		=>	text_db($data['username']),
				"email"			=>	text_db($data['email']),
				"password"		=>	md5($data['password']),
				"address"		=>	text_db($data['address']),
				"city"			=>	text_db($data['city']),
				"state"			=>	text_db($data['state']),
				"country"		=>	text_db($data['country']),
				"mobileno"		=>	text_db($data['mobile']),
				"postcode"		=>	text_db($data['zip_code'])
		);

		$this->db->insert('oxm_userdetails',$userDet);
		/*-------------------------------------------
			UPDATE CLIENTS TABLE
		---------------------------------------------*/
		$clientDet	=	array(
				"email"			=>	text_db($data['email']),
				"contact"		=>	text_db($data['name']),
				"clientname"	=>	text_db($data['name']),
				"agencyid"		=>	1,
				"account_id"	=>	$account_id
		);

		$this->db->insert('ox_clients',$clientDet);
		$clientid = $this->db->insert_id();
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/

		$oxUserDet	=	array(
				"contact_name"			=>	text_db($data['name']),
				"email_address"			=>	text_db($data['email']),
				"username"				=>	text_db($data['username']),
				"password"				=>	md5($data['password']),
				"default_account_id"	=>	$account_id,
				"date_created"	=>	$cur_date
		);
		$this->db->insert('ox_users',$oxUserDet);

		$ox_user_id	=	$this->db->insert_id();
		/*-------------------------------------------
			UPDATE USER ACCOUNT ASSOCIATION
		---------------------------------------------*/

		$ox_acc_user_assoc	=	array(
				"account_id"	=>	$account_id,
				"user_id"		=>	$ox_user_id,
				"linked"		=>	$cur_date
		);
		$this->db->insert('ox_account_user_assoc',$ox_acc_user_assoc);

		/*-------------------------------------------
			UPDATE USER PERMISSION
		---------------------------------------------*/

		$inserperm1	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'10'));
		$inserperm2	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'4'));
		$inserperm3	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'2'));
		$inserperm4	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'1'));
		$inserperm5	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'11'));

		return $clientid;
		/*------------------------------------------*/
	}

	
	
function registration_insert($data)
	{
	
	$type= $data['account_type'];
	$pub_username=$data['username']. "_pub" ;
	$pwd=base64_encode($data['password']);
	$advertiser='ADVERTISER';
	$publisher='TRAFFICKER';
        $current_date= date("Y:m:d H:i");
	  	$useradv		=	array(
	   
		                "account_type"		=>	mysql_real_escape_string($publisher),	
						"contact_name"		=>	mysql_real_escape_string($data['name']),
						"site_url"		    =>	mysql_real_escape_string($data['website']),		
						"username"		    =>	mysql_real_escape_string($pub_username),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"		=>	mysql_real_escape_string($data['email']),
						"address "			=>	mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
                        "date_added "       =>  mysql_real_escape_string($current_date)										
						);

	$userDet		=	array(
	   
		                "account_type"		=>	mysql_real_escape_string($data['account_type']),	
						"contact_name"		=>	mysql_real_escape_string($data['name']),
						"site_url"		    =>	mysql_real_escape_string($data['website']),		
						"username"		    =>	mysql_real_escape_string($data['username']),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"		=>	mysql_real_escape_string($data['email']),
						"address "			=>	mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
						"date_added "       =>  mysql_real_escape_string($current_date),
						"category"			=>	$data['category']									
						);
						
	$userox		=	array(
							
					 	"account_type"		=>	mysql_real_escape_string($advertiser),	
						"contact_name"		=>	mysql_real_escape_string($data['name']),
						"username"		    =>	mysql_real_escape_string($data['username']),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"		=>  mysql_real_escape_string($data['email']),
						"address "			=>  mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
						"date_added"        =>  mysql_real_escape_string($current_date),
							);
						
   if($type == 'TRAFFICKER' )
	 
	 {    	
           $this->db->insert('oxm_newusers', $userDet);
		   $pub_id_oly = $this->db->insert_id();
	       $approval_pub_id_oly		=array("approval_user_id" =>$pub_id_oly, "read_status" =>0); 
		 
		  $this->db->insert('oxm_approval_notification',$approval_pub_id_oly); 
		  $registration = array('username' => $data['username'], 'both' => $pub_username, 'password' => $data['password'],
				'email' => $data['email'] ,'account_type' =>$data['account_type'] ,'name'=> $data['name'],'approval_type'=>'','user_id' =>$pub_id_oly,'user_ref_id'=>0,'site_title'=>'' );
	      return $registration;	
	}	
	
	else if($type == 'ADVERTISER/TRAFFICKER')
	{
	  $this->db->insert('oxm_newusers', $useradv); 
	  $adv_id =  $this->db->insert_id();
	  $approval		=array("approval_user_id" =>$adv_id , "read_status" =>0);
	  $this->db->insert('oxm_approval_notification', $approval);   
	  $this->db->insert('oxm_newusers', $userox);
	  $pub_id = $this->db->insert_id();
	  $approval_pub		=array("approval_user_id" =>	$pub_id, "read_status" =>0);
	  $this->db->insert('oxm_approval_notification', $approval_pub);
	  $this->db->set('user_ref_id', $adv_id );
      $this->db->where('user_id', $pub_id);
      $this->db->update('oxm_newusers');  
	  $this->db->set('user_ref_id', $pub_id );
      $this->db->where('user_id', $adv_id);
      $this->db->update('oxm_newusers');  
	 $registration = array('username' => $data['username'], 'both' => $pub_username, 'password' => $data['password'],
		'email' => $data['email'] ,'account_type' =>$data['account_type'] ,'name'=> $data['name'],'approval_type'=>'','user_id' =>$pub_id ,'user_ref_id'=>$adv_id,'site_title'=>'' );
	   return $registration;	
    }
	
	else if ($type == 'ADVERTISER')
	{
	      $this->db->insert('oxm_newusers', $userox);
		  $adv_id_oly = $this->db->insert_id();
	      $approval_adv_id_oly		=	array("approval_user_id" =>$adv_id_oly , "read_status" =>0); 
		  $this->db->insert('oxm_approval_notification', $approval_adv_id_oly); 
		  $registration = array('username' => $data['username'], 'both' => $pub_username, 'password' => $data['password'],
		'email' => $data['email'] ,'account_type' =>$data['account_type'] ,'name'=> $data['name'],'approval_type'=>'','user_id' =>$adv_id_oly	 ,'user_ref_id'=>0,'site_title'=>''
																			  );		
		  return $registration;
    }
	else
	{
	return FALSE;
	}
} 
	public function activate_publisher($user_id='')
	{
			//Fetch the Record from new users
			$user	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
			$new_users	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();
							
			if($user_id !='' && $user >0)
			{					
					// Retreive the new users into their respective variables.
					$email			=	$new_users->email_address;
					$website_url	=	$new_users->site_url;
					$category		=	explode(",", $new_users->category);
					$name			=	$new_users->contact_name;
					$username		=	$new_users->username;
					$password		=	$new_users->password;
					$account		=	$new_users->account_type;
					$address		=	$new_users->address;
					$city			=	$new_users->city;
					$state			=	$new_users->state;
					$country		=	$new_users->country;
					$mobile			=	$new_users->mobileno;
					$zip			=	$new_users->postcode;
					$cur_date		=	date("Y-m-d H:i:s");
					$user_ref_id	=	$new_users->user_ref_id;
					$new_password	= 	base64_decode($password);	
					$md5_password	=	md5($new_password);	
					//Insert the account details into ox_accounts 
					$insert_account_data	=array("account_type"=>$account, "account_name"=>$username);
																		
					$account_tbl_name		='ox_accounts';
								
					$last_insert_id			=$this->mod_common_operations->insert_data($insert_account_data,$account_tbl_name);//Insert the data and get the last inserted id
								
					//Insert the user details into oxm_userdetails 
					$insert_user_details_data 	=	array(
																		"accountid" =>$last_insert_id, 
																		"accounttype" =>$account, 
																		"username"=>$username,
																		"websitename"=>$website_url,
																		"email"=>$email,
																		"password"=>$md5_password,
																		"address"=>$address,
																		"city"=>$city,
																		"state"=>$state,
																		"country"=>$country,
																		"mobileno"=>$mobile,
																		"postcode"=>$zip
																);
																		
					$user_details_tbl_name	=	'oxm_userdetails';
								
					$this->mod_common_operations->insert_data($insert_user_details_data,$user_details_tbl_name);
								
					//Insert the publishers details into ox_affiliates
					$insert_affiliate_data	=	array(
																	"agencyid" =>1, 
																	"email"=>$email,
																	"contact"=>$name,
																	"name"=>$website_url,
																	"updated"=>$cur_date,
																	"account_id"=>$last_insert_id,
																	"oac_category_id" =>implode(",", $category)
																);
																		
					$affiliate_tbl_name		=	'ox_affiliates';
								
					$this->mod_common_operations->insert_data($insert_affiliate_data,$affiliate_tbl_name);
								 
					 //Insert the user details into ox_users
					$insert_user_data		=	array(
																	"contact_name"=>$name,
																	"email_address"=>$email,
																	"username"=>$username,
																	"password"=>$md5_password,
																	"default_account_id"=>$last_insert_id,
																	"date_created"=>$cur_date
															);
																		
					$user_tbl_name		=	'ox_users';
								
					$user_lastid	=	$this->mod_common_operations->insert_data($insert_user_data,$user_tbl_name);	// Insert the  given and get the user id
								
					//Insert the account user association details into ox_account_user_assoc
					$insert_auassoc_data	=	array(
																	"account_id"=>$last_insert_id,
																	"user_id"=>$user_lastid,
																	"linked"=>$cur_date
															);
																		
					$auassoc_tbl_name		= 'ox_account_user_assoc';
								 
					$this->mod_common_operations->insert_data($insert_auassoc_data,$auassoc_tbl_name);
								
					$permission_array	=	array("10","4","2","1","11");
					foreach($permission_array as $permission_id)
					{
							$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$last_insert_id, "user_id"=>$user_lastid, "permission_id"=>$permission_id));
					}

					/***** Make an Entry to this table for advertiser and publisher relation */
					$relquery = $this->db->get_where('djx_advertiser_publisher_rel',array('pub_id'=>$user_id,'pub_status'=>0))->num_rows();
					$rel_tbl_name	=	'djx_advertiser_publisher_rel';
					if($relquery>0)
					{
							$update_data = array('pub_id'=>$user_lastid,'pub_status'=>1);
							
							$where_arr  = array('pub_id'=>$user_id);
							
							$this->mod_common_operations->update_data($update_data,$where_arr,$rel_tbl_name);
					}else{
							$insert_data		=	array('pub_id'=>$user_lastid,'adv_id'=>$user_ref_id,'pub_status'=>1,'adv_status'=>0);
							$this->mod_common_operations->insert_data($insert_data,$rel_tbl_name);
					}

					//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
								
					 /*-----------------------------------------------------------------------
					SEND INTIMATION EMAIL TO REGISTRED ADVERTISER
								------------------------------------------------------------------------*/
		
					$publisher_email_id		=$email;
                    $site_title		      			=$this->mod_common_operations->get_site_title();
					$admin_email				=$this->mod_common_operations->get_admin_email();
					$subject						=$this->lang->line('label_approval_subject');
					$email_data					=array("name"	=>	$name, "username" =>$username,"password" =>$new_password,'site_title' =>$site_title);
																	
				    $content						=$this->load->view('email/Administrator/approve_publisher',$email_data,TRUE);
			  		$data['content']			=$content;
			  		$mail_content				=$this->load->view('email/registration/email_tpl', $data, TRUE);
					$message                   	=$mail_content;
			  
					$config['protocol'] 		="sendmail";
					$config['wordwrap'] 		=TRUE;		
					$config['mailtype'] 		='html';
					$config['charset']			='UTF-8';        

					$this->email->initialize($config);
					$this->email->from($admin_email);
					$this->email->to($publisher_email_id);        
					$this->email->subject($subject);        
					$this->email->message($message);
					$this->email->send();
					
					//End of Sending Email Notification to the User
								
					//Delete the User details at oxm_newusers after making an approval
					$this->db->delete('oxm_newusers', array("user_id"=>$user_id));
				}			
			   else
			   {
			   		redirect(site_url());
			   }
	}

	//Advertiser Approval
	public function activate_advertiser($user_id ='')
	{
			$user			=$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
			$new_users	=$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();
							
			if($user_id !='' && $user >0)
			{
								
					// Retreive the new users into their respective variables.
					$email		=	$new_users->email_address;
					$username	=	$new_users->username;
					$password	=	$new_users->password;
					$account	=	$new_users->account_type;
					$address	=	$new_users->address;
					$city			=	$new_users->city;
					$state		=	$new_users->state;
					$country	=	$new_users->country;
					$mobile		=	$new_users->mobileno;
					$zip			=	$new_users->postcode;
					$name		=	$new_users->contact_name;
					$cur_date		=	date("Y-m-d H:i:s");					
					$user_ref_id		=	$new_users->user_ref_id;
                     $new_password= base64_decode($password);	
					$md5_password=md5(	$new_password);	
					//Insert the account details into ox_accounts 
					$insert_account_data	=	array(
																"account_type"=>$account,
																"account_name"=>$username
															);
																		
					$account_tbl_name	=	'ox_accounts';
								
					$last_insert_id	=	$this->mod_common_operations->insert_data($insert_account_data,$account_tbl_name);//Insert the data and get the last inserted id
								
					//Insert the user details into oxm_userdetails 
					$insert_user_details_data 	=	array(
																	"accountid" =>$last_insert_id, 
																	"accounttype" =>$account, 
																	"username"=>$username,
																	"email"=>$email,
																	"password"=>$md5_password,
																	"address"=>$address,
																	"city"=>$city,
																	"state"=>$state,
																	"country"=>$country,
																	"mobileno"=>$mobile,
																	"postcode"=>$zip
																);
																			
					$user_details_tbl_name	=	'oxm_userdetails';
								
					$this->mod_common_operations->insert_data($insert_user_details_data,$user_details_tbl_name);
								
					//Insert the client details into ox_clients
					$insert_client_data		=	array(
																"agencyid" =>1, 
																"email"=>$email,
																"contact"=>$name,
																"clientname"=>$username,
																"account_id"=>$last_insert_id
															);
																		
					$client_tbl_name		=	'ox_clients';
								
					$this->mod_common_operations->insert_data($insert_client_data,$client_tbl_name);
								 
					 //Insert the user details into ox_users
					$insert_user_data		=	array(
																"contact_name"=>$name,
																"email_address"=>$email,
																"username"=>$username,
																"password"=>$md5_password,
																"default_account_id"=>$last_insert_id,
																"date_created"=>$cur_date
															);
																		
					$user_tbl_name		=	'ox_users';
								
					$user_lastid	=	$this->mod_common_operations->insert_data($insert_user_data,$user_tbl_name);	// Insert the  given and get the user id
								
					//Insert the account user association details into ox_account_user_assoc
					
					$insert_auassoc_data	=	array(
																"account_id"=>$last_insert_id,
																"user_id"=>$user_lastid,
																"linked"=>$cur_date
															);
																		
					$auassoc_tbl_name		= 'ox_account_user_assoc';
								 
					$this->mod_common_operations->insert_data($insert_auassoc_data,$auassoc_tbl_name);
								
					$permission_array	=	array("10","4","2","1","11");
					foreach($permission_array as $permission_id)
					{
						$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$last_insert_id, "user_id"=>$user_lastid, "permission_id"=>$permission_id));
					}

					
					/***** Make an Entry to this table for advertiser and publisher relation */
					$relquery = $this->db->get_where('djx_advertiser_publisher_rel',array('adv_id'=>$user_id,'adv_status'=>0))->num_rows();
					$rel_tbl_name	=	'djx_advertiser_publisher_rel';
					if($relquery>0)
					{
							$update_data = array('adv_id'=>$user_lastid,'adv_status'=>1);
							
							$where_arr  = array('adv_id'=>$user_id);
							
							$this->mod_common_operations->update_data($update_data,$where_arr,$rel_tbl_name);
					}else{
							$insert_data		=	array('adv_id'=>$user_lastid,'pub_id'=>$user_ref_id,'adv_status'=>1,'pub_status'=>0);
							$this->mod_common_operations->insert_data($insert_data,$rel_tbl_name);
					}
					
				//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
								
					 /*-----------------------------------------------------------------------
							SEND INTIMATION EMAIL TO REGISTRED ADVERTISER
					------------------------------------------------------------------------*/
			        $advertiser_email_id	=	$email;
					 $site_title		      =      $this->mod_common_operations->get_site_title();
					$admin_email			=	$this->mod_common_operations->get_admin_email();
					$subject					=	$this->lang->line('label_approval_subject');
					$email_data			= 	array(
																"name"	=>	$name, "username" =>$username,"password" =>$new_password,'site_title' =>$site_title

														);
																	
					
								$content		= $this->load->view('email/Administrator/approve_advertiser',$email_data,TRUE);
								$data['content']	=$content;
								$mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
								$message                   	=$mail_content;
								
								$config['protocol'] = "sendmail";
								$config['wordwrap'] = TRUE;		
								$config['mailtype'] 		='html';
								$config['charset']			='UTF-8';        
								$this->email->initialize($config);
								$this->email->from($admin_email);
								$this->email->to($advertiser_email_id);        
								$this->email->subject($subject);        
								$this->email->message($message);
								$this->email->send();
					
								
					//End of Sending Email Notification to the User
								
					//Delete the User details at oxm_newusers after making an approval
					$this->db->delete('oxm_newusers', array("user_id"=>$user_id));
			}//end of if condition
			else
			{
			  		redirect(site_url());
			}
	}

	/* Get Category list */
	function getCategory($where ='')
	{
		if($where !='')
		{
			$this->db->where($where);
		}
		
		$query 		=$this->db->get('djx_campaign_categories');
		
		if($query->num_rows >0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

}
