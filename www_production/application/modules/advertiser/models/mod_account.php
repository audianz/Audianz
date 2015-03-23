<?php
class mod_account extends CI_Model 
{  
	function get_timezone() 
	{
    	 //$timezone_table=oxm_timezone;             
         $query=$this->db->get('oxm_timezone');
         return $query->result();
	}
	
	function get_country() 
	{
    	           
         $query=$this->db->get('djx_geographic_locations');
         return $query->result();
	}
	
	function get_myaccount() 
	{
			$id=$this->session->userdata('session_advertiser_account_id');

			$where_arr	=	array('oxm_userdetails.accountid' => $id);
			$this->db->select('ox_clients.report , ox_clients.reportinterval ,ox_clients.reportdeactivate,oxm_userdetails.*' );
			$this->db->from('ox_clients');
			$this->db->join('oxm_userdetails',  "oxm_userdetails.accountid = ox_clients.account_id ");
			$this->db->join('ox_users', "ox_users.default_account_id=ox_clients.account_id");
			$this->db->where($where_arr);
			$query=$this->db->get();

			return $query->result();
			
		
		
	}
	
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
		UPDATE MY Account TABLE
	---------------------------------------------*/
	
	function myaccount_update($data)
	{
	
	
	$id=$this->session->userdata('session_advertiser_account_id');   
	$userDet		=	array(
							
						//"username"		    =>	mysql_real_escape_string($data['username']),
						"email"		        	=>mysql_real_escape_string($data['email']),
						"address "			=>mysql_real_escape_string($data['address']),
						"city"		    	=>	mysql_real_escape_string($data['city']),
						"state"		        =>	mysql_real_escape_string($data['state']),
						"country"		    =>	mysql_real_escape_string($data['country']),
						"mobileno"		    =>	mysql_real_escape_string($data['mobileno']),
						"paypalid"		    =>	mysql_real_escape_string($data['paypalid']),
						"bank_acctype"		    =>	mysql_real_escape_string($data['bank_acctype']),
						"tax"		    =>	mysql_real_escape_string($data['tax'])
												
							);

	if(isset($data['avatar'])){
			$userDet["avatar"] = 	mysql_real_escape_string($data['avatar']);
	}

	$userox		=	array(
							
						//"username"		    =>	mysql_real_escape_string($data['username']),
						"email_address"		    =>mysql_real_escape_string($data['email']),
							
							);
    $userclient  =  	array(
"email"		    =>mysql_real_escape_string($data['email']),
							
						"reportdeactivate"		    =>	mysql_real_escape_string($data['reportdeactivate']),
					"report"		    =>mysql_real_escape_string($data['report']),
						"reportinterval"		    =>mysql_real_escape_string($data['reportinterval']),
							
							);
		if(isset($data['avatar']))
		{
		
		//Unlink process
		$this->db->select('avatar');
		$this->db->where("accountid",$id);
		$query=$this->db->get('oxm_userdetails');
	
			if($query->num_rows()>0)
			{
				$temp=$query->row();
				$avatar_image=$temp->avatar;
				if($avatar_image != '')
				{
					if(file_exists($this->config->item('user_img_url').$avatar_image))
					{	
						unlink($this->config->item('user_img_url').$avatar_image);
					}
					else
					{
						$this->session->set_flashdata('message_error', $this->lang->line('label_upload_path_error'));
						redirect("advertiser/myaccount");
					}
				}
			} 
		}             

	$this->db->where("accountid",$id);
	$this->db->update('oxm_userdetails',$userDet);
        $this->db->where("default_account_id",$id);
	$this->db->update('ox_users',$userox);
	$this->db->where("account_id",$id);
	$this->db->update('ox_clients',$userclient);
         //$this->session->set_userdata('session_advertiser_name',$data['username']);
          $this->session->set_userdata('session_advertiser_email',$data['email']);	
	}

	function myaccount_delete_avatar()
	{
		$id=$this->session->userdata('session_advertiser_account_id');	
		
		
		
		//Unlink process
		$this->db->select('avatar');
		$this->db->where("accountid",$id);
		$query=$this->db->get('oxm_userdetails');
	
		if($query->num_rows()>0)
		{
			$temp=$query->row();
			$avatar_image=$temp->avatar;
			if($avatar_image != '')
			{
				if(file_exists($this->config->item('user_img_url').$avatar_image))
				{	
					unlink($this->config->item('user_img_url').$avatar_image);
				}
				else
				{
					$this->session->set_flashdata('message_error', $this->lang->line('label_upload_path_error'));
					redirect("advertiser/myaccount");
				}
			}
		}
		
		$userDet		=	array("avatar"		=>	'');
		$this->db->where("accountid",$id);
		$query=$this->db->update('oxm_userdetails',$userDet);
	}	
	

}   
