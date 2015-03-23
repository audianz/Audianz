<?php
class Mod_operator_settings extends CI_Model 
{ 
   
   ////////// Common Function ////////////////////////
   	public function count_rows($table='', $where='')
	{
	    if($where !='') { $this->db->where($where); }
		return $this->db->get($table)->num_rows();
	}
	
	public function list_data($table='', $where='', $limit=10, $offset=0)
	{
	    if($where !='') { $this->db->where($where); }
		$this->db->limit($limit, $offset);
		$query = $this->db->get($table);
		
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
   }

	public function list_country()
	{
	    $this->db->select('code, name');
		$this->db->order_by('name','ASC');
		$query = $this->db->get('djx_geographic_locations');
		
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
   }


   ////////// Geo Operator Function ////////////////////////  
   
   	function getGeooperator($where =0){
	
		//Build contents query
		if($where !==0)
		{
		$this->db->where($where);
		}
		
		$query = $this->db->get('djx_carrier_detail');
		
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function addGeooperator($data =''){
	
		if(!empty($data)) {
		
		 $status       =$this->db->insert("djx_carrier_detail", $data);
			 if($status){
			 	return $this->db->insert_id();
			 }
			 else{
			 	return false;
			 }
		 }
	}
	
	function updateGeooperator($data ='', $tid=''){
		if(!empty($data)) {
		 $this->db->where('id', $tid);
		 $this->db->update("djx_carrier_detail", $data);
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	}
	
	function deleteGeooperator($tid =''){
		if($tid !='') {
		 $this->db->where('id', $tid);
		 $this->db->delete('djx_carrier_detail'); 
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	}

	////////// Geo Operators Function ////////////////////////  

}