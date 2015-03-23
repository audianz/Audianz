<?php
class Mod_common_operations extends CI_Model 
{

	// Common Operations For Single Table - Adding,Updating and Deleting
	/******** Insert the given data to dynamic Table ******/
	function insert_data($insert_data,$tbl_name)
	{
		if(!empty($insert_data))
		{
			$this->db->insert($tbl_name,$insert_data);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();	
			}else{
				return FALSE;
			}
		}
	}
	
	/************Update the given data to table dynamically ****/
	function update_data($update_data,$where_arr,$tbl_name)
	{
		if(!empty($update_data))
		{
			$this->db->where($where_arr);
			$this->db->update($tbl_name,$update_data);
			
			if($this->db->affected_rows()>0)
			{
				return TRUE;	
			}else{
				return FALSE;
			}
		}
	}
	
	/*********************Delete the given data to table dynamically ***************/
    function delete_data($sel_ids,$id_field_name,$tbl_name)
    {
		if(is_array($sel_ids)){
			//Multiple Device Manufacturer Delete
			foreach($sel_ids as $selected_id)
			{
					$this->db->where($id_field_name,$selected_id);
        			$this->db->delete($tbl_name);
			}
		}
		else
		{
			// Single Device Manufacturer Delete
			$this->db->where($id_field_name,$sel_ids);
        	$this->db->delete($tbl_name);
		}
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
    }
	
	/************************Get Admin Email Details*****************/
	function get_admin_email(){
		$resObj = $this->db->where('default_account_id',2)->get('ox_users');
		if($resObj->num_rows >0){
			$temp = $resObj->result();
			return $temp[0]->email_address;
		}
		else
		{
			return FALSE;
		}
	}
	
		
/************************Get Admin site title Details*****************/
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
}
