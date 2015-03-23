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
    	           
         $query=$this->db->get('oxm_admindetails');
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
	
	
	$id =2;
	$userDet		=	array(
							
							"username"		=>text_db($data['username']),
							"Email"		        =>text_db($data['email']),
							"address "		=>text_db($data['address']),
							"city"		    	=>text_db($data['city']),
							"state"		        =>text_db($data['state']),
							"country"		=>text_db($data['country']),
							"mobileno"		=>text_db($data['mobileno']),
							"paypalid"	        =>text_db($data['paypalid']),
						"bank_acctype"		   	=>text_db($data['bank_acctype']),
						"tax"		    		=>text_db($data['tax'])
							);
$useremail		=	array(
							"username"		=>text_db($data['username']),
							"email_address"		        =>text_db($data['email'])
							
												
							);
	if(isset($data['avatar']))
	{
		$userDet["admin_avatar"] = mysql_real_escape_string($data['avatar']);
	}
	if(isset($data['avatar']))
	{	
	//Unlink process
	$this->db->select('admin_avatar');
	$this->db->where("accountid",$id);
	$query=$this->db->get('oxm_admindetails');
	
		if($query->num_rows()>0)
		{
			$temp=$query->row();
			$avatar_image=$temp->admin_avatar;
				if($avatar_image != '')
				{
								  
					if(file_exists($this->config->item('admin_img_url').$avatar_image))
					{	
							unlink($this->config->item('admin_img_url').$avatar_image);
					}
					else
					{
						$this->session->set_flashdata('message_error', $this->lang->line('label_upload_path_error'));
						redirect("admin/myaccount");
					}
				}
					
		}
	}	
             
	$this->db->where("accountid",$id);
	$this->db->update('oxm_admindetails',$userDet);
        $this->db->where("default_account_id",$id);
	$this->db->update('ox_users',$useremail);
	 $this->session->set_userdata('mads_sess_admin_username',$data['username']);
         $this->session->set_userdata('mads_sess_admin_email',$data['email']);	
	}	
	
	function myaccount_delete_avatar()
	{
		$id=2;	
		
		
		//Unlink process
		$this->db->select('admin_avatar');
		$this->db->where("accountid",$id);
		$query=$this->db->get('oxm_admindetails');
	
		if($query->num_rows()>0)
		{
			$temp=$query->row();
			$avatar_image=$temp->admin_avatar;
			if($avatar_image != '')
			{
				if(file_exists($this->config->item('admin_img_url').$avatar_image))
				{	
						unlink($this->config->item('admin_img_url').$avatar_image);
				}
				else
				{
					$this->session->set_flashdata('message_error', $this->lang->line('label_upload_path_error'));
					redirect("admin/myaccount");
				}
			}
		}
		
		$userDet		=	array("admin_avatar"		=>	'');
		$this->db->where("accountid",$id);
		$query=$this->db->update('oxm_admindetails',$userDet);
	}
}   
