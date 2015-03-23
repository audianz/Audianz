<?php
class mod_changepassword extends CI_Model 
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

	function change_password_process($data)
	{
	   // $username	='admin';
		$old		=$data['oldpwd'];
		$new		=$data['newpwd'];
        $type		=$this->session->userdata('session_user_type');
                  
	  
		   
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
		   $email		=$row->email;
                   
                    
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

