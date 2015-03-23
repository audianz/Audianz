<?php
class mod_site_settings extends CI_Model 
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
	
	function get_site_settings() 
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
		UPDATE SITE SETTINGS TABLE
	---------------------------------------------*/
	
	function site_settings_update($data)
	{
	
	
	$id =2;

	$userDet		=	array(
							"site_title"		=>text_db($data['site_title']),
							"tag_line"		=>text_db($data['tagline']),
							//"time_zone"		=>text_db($data['time_zone']),
							"site_url"		=>text_db($data['site_url']),
							"logo"		        =>text_db($data['image_upload']),
							"Email"		        =>text_db($data['email']),
							"address "		=>text_db($data['address']),
							"city"		    	=>text_db($data['city']),
							"state"		        =>text_db($data['state']),
							"country"		=>text_db($data['country'])
												
							);
        $useremail		=	array(
							
							"email_address"		        =>text_db($data['email'])
							
												
							);
	$this->db->where("accountid",$id);
	$update = $this->db->get('oxm_admindetails');
            $row = $update->row_array();
              if($update->num_rows() > 0) {

                  
                  if($row['logo'] !='')
                 {
		  if($data['image_upload'] !='')
                  {
                   
                		 unlink($this->config->item('admin_upload_url').$row['logo']); 
				 unlink($this->config->item('admin_login_logo_url').$row['logo']);               
                		 unlink($this->config->item('admin_site_logo_url').$row['logo']);

         }
else
{
$userDet		=	array(
							"site_title"		=>text_db($data['site_title']),
							"tag_line"		=>text_db($data['tagline']),
							//"time_zone"		=>text_db($data['time_zone']),
							"site_url"		=>text_db($data['site_url']),
							"logo"		        =>$row['logo'] ,
							"Email"		        =>text_db($data['email']),
							"address "		=>text_db($data['address']),
							"city"		    	=>text_db($data['city']),
							"state"		        =>text_db($data['state']),
							"country"		=>text_db($data['country'])
												
							);
}	
         } 
        
                           
        $this->db->update('oxm_admindetails',$userDet);
        $this->db->where("default_account_id",$id);
	$this->db->update('ox_users',$useremail);
         }	 
         }    
		
	
	function change_password_process($data)
	{
	    $username		=       $this->session->userdata('mads_sess_admin_username');
		$old		=	$data['oldpwd'];
		$new		=	$data['newpwd'];
	   //$username=$this->session->userdata('admin_email');
	     $this->db->select('username,password');
		 $this->db->where('username',$username);
		 $query=$this->db->get('oxm_admindetails');
		 foreach($query->result() as $row)
		 {
		  
		  $old_password=$row->password;
		    
			if( $old ==$old_password)
				{
				 
				 $userDet=array('password'=>$new);
				 $this->db->where("username",$username);
	             $this->db->update('oxm_admindetails',$userDet);
				 $this->db->where("username",$username);
	             $this->db->update('ox_users',$userDet);
				 
				 return TRUE;	
				}
		    else
				{
				return FALSE;
				}
		}
	}
	
	function delete_admin_logo()
	{
		$id =2;
		$userDet = array("logo" => '');
       
		$this->db->where("accountid",$id);
		$query = $this->db->get('oxm_admindetails');
           	$row = $query->row_array();
              	if($query->num_rows() > 0) 
				{
                                         
					  if($row['logo'] !='')
					 {
                   					if(file_exists($this->config->item('admin_upload_url').$row['logo']))
							{
                		 				unlink($this->config->item('admin_upload_url').$row['logo']); 
								if(file_exists($this->config->item('admin_login_logo_url').$row['logo']))
								{				 				
									unlink($this->config->item('admin_login_logo_url').$row['logo']);               
									if(file_exists($this->config->item('admin_site_logo_url').$row['logo']))
									{                		 			
										unlink($this->config->item('admin_site_logo_url').$row['logo']);
									}
									else
									{
										$this->session->set_flashdata('message_error', $this->lang->line("label_upload_path_error"));
										redirect("admin/site_settings");
									}
								}
								else
								{
									$this->session->set_flashdata('message_error', $this->lang->line("label_upload_path_error"));
									redirect("admin/site_settings");
								}
							}
							else
							{
								$this->session->set_flashdata('message_error', $this->lang->line("label_upload_path_error"));
								redirect("admin/site_settings");
							}
						$this->db->update('oxm_admindetails',$userDet);	
					}
				}
	}
}   
