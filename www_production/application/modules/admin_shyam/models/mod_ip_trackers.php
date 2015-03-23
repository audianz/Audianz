<?php
class Mod_ip_trackers extends CI_Model 
{  	
	public function get_geo_locations_list($limit=0,$offset=0){
			$this->db->select('oxm_logged_details.*,oxm_admindetails.username');
			$this->db->join('oxm_admindetails','oxm_admindetails.accountid=oxm_logged_details.user_id','left');
			$this->db->where('ip_address !=','182.72.161.90');
			 $this->db->order_by('oxm_logged_details.logged_in','DESC');
			if($limit==0)
			{
					$query = $this->db->get('oxm_logged_details');
			}else{
					$query = $this->db->get('oxm_logged_details',$limit,$offset);
			}
			if($query->num_rows()>0)
			{
				return $query->result();
			}else{
				return FALSE;		
			}	
	}

	
	
}
