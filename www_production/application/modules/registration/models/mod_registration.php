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
function registration_insert($data)
	{
	
	$type= $data['account_type'];
	$pub_username=$data['username']. "_pub" ;
	$pwd=md5($data['password']);
	$advertiser='ADVERTISER';
	$publisher='TRAFFICKER';
        $current_date= date("Y:m:d H:i");
	  	$useradv		=	array(
	   
		                "account_type"		    =>	mysql_real_escape_string($publisher),	
						"contact_name"		    =>	mysql_real_escape_string($data['name']),
						"site_url"		    =>	mysql_real_escape_string($data['website']),		
						"username"		    =>	mysql_real_escape_string($pub_username),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"		        	=>mysql_real_escape_string($data['email']),
						"address "			=>mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
                                                "date_added "      =>  mysql_real_escape_string($current_date),
																
						);
	$userDet		=	array(
	   
		                "account_type"		    =>	mysql_real_escape_string($data['account_type']),	
						"contact_name"		    =>	mysql_real_escape_string($data['name']),
						"site_url"		    =>	mysql_real_escape_string($data['website']),		
						"username"		    =>	mysql_real_escape_string($data['username']),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"	=>mysql_real_escape_string($data['email']),
						"address "			=>mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
						 "date_added "      =>  mysql_real_escape_string($current_date),										
						);
	$userox		=	array(
							
					 "account_type"		    =>	mysql_real_escape_string($advertiser),	
						"contact_name"		    =>	mysql_real_escape_string($data['name']),
						"username"		    =>	mysql_real_escape_string($data['username']),
						"password"		    =>	mysql_real_escape_string($pwd),
						"email_address"		        	=>mysql_real_escape_string($data['email']),
						"address "			=>mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobile']),
						"postcode"		    =>	mysql_real_escape_string($data['zip']),
						"date_added "      =>  mysql_real_escape_string($current_date),
							);
							 $registration = array('username' => $data['username'], 'both' => $pub_username, 'password' => $data['password'],
				 						  'email' => $data['email'] ,'account_type' =>$data['account_type'] ,'name'=> $data['name']
																			  );
   if($type == 'TRAFFICKER' )
	 
	 {    	
           $this->db->insert('oxm_newusers', $userDet);
		    $pub_id_oly = $this->db->insert_id();
	     $approval_pub_id_oly		=	array(
							
					 	"approval_user_id"	  =>	$pub_id_oly,
						"read_status"		    =>0); 
		  $this->db->insert('oxm_approval_notification',$approval_pub_id_oly);   		 
	      return $registration;	
	}	
	
	else if($type == 'ADVERTISER/TRAFFICKER')
	{
	  $this->db->insert('oxm_newusers', $useradv); 
	   $adv_id =  $this->db->insert_id();
	    $approval		=	array(
							
					 	"approval_user_id"	  =>	$adv_id ,
						"read_status"		    =>0);
	  $this->db->insert('oxm_approval_notification', $approval);   
	  $this->db->insert('oxm_newusers', $userox);
	   $pub_id = $this->db->insert_id();
	     $approval_pub		=	array(
							
					 	"approval_user_id"	  =>	$pub_id ,
						"read_status"		    =>0);
	  $this->db->insert('oxm_approval_notification', $approval_pub);
	    $this->db->set('user_ref_id', $adv_id );
      $this->db->where('user_id', $pub_id);
      $this->db->update('oxm_newusers');  
	    $this->db->set('user_ref_id', $pub_id );
      $this->db->where('user_id', $adv_id);
      $this->db->update('oxm_newusers');  
	   return $registration;	
    }
	
	else if ($type == 'ADVERTISER')
	{
	      $this->db->insert('oxm_newusers', $userox);
		  $adv_id_oly = $this->db->insert_id();
	     $approval_adv_id_oly		=	array(
							
					 	"approval_user_id"	  =>	$adv_id_oly ,
						"read_status"		    =>0); 
		  $this->db->insert('oxm_approval_notification',$approval_adv_id_oly);   				
		  return $registration;
    }
	else
	{
	return FALSE;
	}
}   
}
