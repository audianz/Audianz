<?php
class Mod_login extends CI_Model 
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
	
	
	function login_process($data,$type)
	{
	    
	     if($type =='TRAFFICKER') :
		$username		=	$data['username'];
		$password		=	$data['password'];
		$where_arr	=	array('ox_users.username' => $username,'ox_users.password'=> $password);
	   $this->db->select('ox_affiliates.name, ox_affiliates.affiliateid, ox_affiliates.contact,ox_affiliates.email,ox_affiliates.account_id,ox_users.username,ox_users.password' );
			$this->db->from('ox_affiliates');
			$this->db->join('ox_accounts', "ox_affiliates.account_id = ox_accounts.account_id  AND ox_accounts.account_type = 'TRAFFICKER'");
			$this->db->join('ox_users', "ox_users.default_account_id=ox_accounts.account_id");
			$this->db->where($where_arr);
			$query	=$this->db->get();
			 
		 foreach($query->result() as $row)
		 {
		  
		  $db_name=$row->name;
		  $db_affiliateid=$row->affiliateid;
		  $db_contact=$row->contact;
		  $db_email=$row->email;
		  $db_account_id=$row->account_id;
		  $db_username =$row->username;
		  $db_password =$row->password ;
	          $type='TRAFFICKER';
		  $current_time=date('H:i:s');

		    
			if( $username == $db_username && $password == $db_password)
				{
				 $array_items = array('session_publisher_name' => $db_username, 'session_publisher_id' => $db_affiliateid,'session_publisher_contact' => $db_contact,'session_publisher_email' =>$db_email,'session_publisher_account_id' =>$db_account_id ,'publisher_current_time' => $current_time,'session_user_type' => $type);
				 $this->session->set_userdata($array_items);			 	 
				$login_type = array('type' => 'TRAFFICKER',
                                                    'email' => $db_email,
						    'password' =>$password
						   );			 	 
				 return $login_type;
				}
		    else
				{
				return FALSE;
				}
				  } 
		 elseif ($type =='ADVERTISER') :
		
		$username		=	$data['username'];
		$password		=	$data['password'];
		$where_arr	=	array('ox_users.username' => $username,'ox_users.password'=> $password);
	    $this->db->select('ox_clients.clientname, ox_clients.clientid , 		   ox_clients.contact,ox_clients.email,ox_clients.account_id,ox_users.username,ox_users.password' );
			$this->db->from('ox_clients');
			$this->db->join('ox_accounts',  "ox_accounts.account_id = ox_clients.account_id  AND ox_accounts.account_type = 'ADVERTISER'");
			$this->db->join('ox_users', "ox_users.default_account_id=ox_clients.account_id");
			$this->db->where($where_arr);
			$query	=$this->db->get();
			
		 foreach($query->result() as $row)
		 {
		  
		  $db_name=$row->clientname;
		  $db_clientid=$row->clientid;
		  $db_contact=$row->contact;
		  $db_email=$row->email;
		  $db_account_id=$row->account_id;
		  $db_username =$row->username;
		  $db_password =$row->password ;
		  $type='ADVERTISER';
		  $current_time=date('H:i:s');
		 
		    
			if( $username == $db_username && $password == $db_password)
				{
				 $array_items = array('session_advertiser_name' => $db_username, 'session_advertiser_id' =>$db_clientid,'session_advertiser_contact' => $db_contact,'session_advertiser_email' =>$db_email,'session_advertiser_account_id' =>$db_account_id ,'current_advertiser_time' => $current_time,'session_user_type' => $type);
				 $this->session->set_userdata($array_items);
				$login_type = array('type' => 'ADVERTISER',
                                                    'email' => $db_email,
						    'password' =>$password
						   );			 	 
				 return $login_type;
				}
		    else
				{
				return FALSE;
				}
		}
		endif;
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
	function forget_password_process($data, $type)
	{
	     if($type =='TRAFFICKER') :
		 $useremail		=$data['useremail'];
		 $where_arr		=array('ox_users.email_address' => $useremail);
	  	 
		 $this->db->select('oxm_userdetails.email,oxm_userdetails.accountid,ox_users.username,ox_users.password' );
		 $this->db->from('oxm_userdetails');
		 $this->db->join('ox_accounts',  "ox_accounts.account_id =oxm_userdetails.accountid  AND ox_accounts.account_type = 'TRAFFICKER'");
		 $this->db->join('ox_users', "ox_users.default_account_id=oxm_userdetails.accountid");
		 $this->db->where($where_arr);
		 $query	=$this->db->get();
			 
		 foreach($query->result() as $row)
		 {
		  $default_account_id =$row->accountid;
		  $db_email=$row->email;
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
				 $this->db->where("email",$useremail);
				 $this->db->where("accountid",$default_account_id);
	             $this->db->update('oxm_userdetails',$userDet);
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
		elseif ($type =='ADVERTISER') :
		
		    $useremail		=$data['useremail'];
		 	$where_arr		=array('ox_users.email_address' => $useremail	);
	  		$this->db->select('oxm_userdetails.email,oxm_userdetails.accountid,ox_users.username,ox_users.password' );
			$this->db->from('oxm_userdetails');
			$this->db->join('ox_accounts',  "ox_accounts.account_id =oxm_userdetails.accountid  AND ox_accounts.account_type = 'ADVERTISER'");
			$this->db->join('ox_users', "ox_users.default_account_id=oxm_userdetails.accountid");
			$this->db->where($where_arr);
			$query	=$this->db->get();
			 
		 foreach($query->result() as $row)
		 {
		  	$default_account_id 	=$row->accountid;
		  	$db_email				=$row->email;
		  	$db_username			=$row->username; 	 
		    
			if( $db_email == $useremail)
			{
			  $password = $this->mod_login->rand_string(6);
			  $forget_password = array('username' => $db_username, 
				 						  'email' => $db_email,
										  'password' =>$password
										  );
				
			  $db_password=md5($password);
			  $userDet=array('password'=>$db_password);
			  $this->db->where("email",$useremail);
			  $this->db->where("accountid",$default_account_id);
	          $this->db->update('oxm_userdetails',$userDet);
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

		endif;	
	}
	
	function change_password_process($data)
	{
	   // $username	='admin';
		$old		=$data['oldpwd'];
		$new		=$data['newpwd'];
        $type		=$this->session->userdata('session_user_type');
       
	       if($type =='TRAFFICKER')
           {    
	   			$username	=$this->session->userdata('session_publisher_name');
	   			$account_id	=$this->session->userdata('session_publisher_account_id');
           }
		   
           if($type =='ADVERTISER')
           {    
	   			$username	=$this->session->userdata('session_advertiser_name');
	   			$account_id	=$this->session->userdata('session_advertiser_account_id');
           }
		   
	   	   $where_arr	=	array('username' =>$username, 'accountid' =>$account_id);
	     	
		   $this->db->select('username, password, email');
		   $this->db->where($where_arr);
		   $query	=$this->db->get('oxm_userdetails');
                 
		 foreach($query->result() as $row)
		 {
		   $old_password	=$row->password;
		   $email			=$row->email;
                  
			if($old ==$old_password)
			{
				  $change_password =array('username' =>$username, 
				 						  'email' => $email,
										  'password' =>$new
										  );
                  $new_password		=md5($new);
				  $userDet			=array('password' =>$new_password);
				  $this->db->where($where_arr);
	              $this->db->update('oxm_userdetails',$userDet);
				  
				  $where			=array('username' =>$username,'default_account_id' =>$account_id);
				  $this->db->where($where); 
	              $this->db->update('ox_users', $userDet);
				 
				 return $change_password;	
			}
		    else
			{
				return FALSE;
			}
		}
	}
}      
