<?php

class Mod_trackers_status extends CI_Model 
{
	 var $table_name='djx_trackers_status';	
	
	
	 function __construct()
    	{
        	// Call the Model constructor
        	parent::__construct();
    	} 
	
	function get_all() 
		{
			
			$query=$this->db->get($this->table_name);
			
			return $query->result();
			
		}
			
	function insert_status($trackers_status, $trackers_value,$active='')
		{
			
			
			$data = array('name' =>$trackers_status, 'value' => $trackers_value,'is_active'=>$active);

			$this->db->insert($this->table_name, $data);
			
		}
		
	
	
	function get_edit($id) 
		{
		
			$this->db->select('is_active');
			
			$this->db->where('id',$id);
					
			$query=$this->db->get($this->table_name);
			
			$temp=$query->row();
			
			return $temp->is_active;
		
		}
	
	function update_status($id,$active)
		{
			if($active == 0)
			{
				$active=1;
			}
			else
			{
				$active=0;
			}
			
			$update = array('is_active' => $active);
			
			$this->db->where('id',$id);
					
			$this->db->update($this->table_name, $update);
							
		}
	
		
}
			