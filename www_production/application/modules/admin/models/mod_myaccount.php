<?php
class mod_myaccount extends CI_Model 
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

}   
