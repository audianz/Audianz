<?php
class Mod_revenue extends CI_Model 
{
	 var $table_name='da_inventory_revenue_type';	
	 
	
	 function __construct()
    	{
        	// Call the Model constructor
        	parent::__construct();
    	} 

//Getting id from da_inventory_revenue_type table for edit operation	
	function edit_revenue_type($id) 
		{
			
				 $this->db->where('revenue_id',$id);
						
				 $query=$this->db->get($this->table_name);
			
				 return $query->result();
							
		}
	
 //Listing revenue type from da_inventory_revenue_type table		
	function list_revenue_type() 
		{
			
			$this->db->order_by('revenue_type_value','ASC');
			
			$query					=		$this->db->get($this->table_name);
			
			return $query->result();
			
		}
		
//Inserting new record into da_inventory_revenue_type table
	function insert_type($type)
		{
			$this->db->select_max('revenue_type_value');
			
			$query					=		$this->db->get('da_inventory_revenue_type');
			
			foreach($query->result() as $max)
				{
				
					$max_value			=		$max->revenue_type_value;
			
				}
			
			$revenue_type		=		$type;
			
			$data 					= 		array	(
															'revenue_id' =>''	,	
															
															'revenue_type' => $revenue_type,
															
															'revenue_type_value'=>$max_value + 1
   	 						  							);

			$this->db->insert('da_inventory_revenue_type', $data);
			
		}
	
 //updating da_inventory_revenue_type table 		
	function update_type($id,$data)
		{
			
					$update 		= 		array(
															'revenue_type' => $data,
   	 						  						  );

					
					$this->db->where('revenue_id',$id);
					
					$this->db->update($this->table_name, $update);
			
		}							

//Deleting a record from da_inventory_revenue_type table 			
	function delete_type($id)
		{
		
		$this->db->where('revenue_id',$id);
		
		$this->db->delete($this->table_name);
		
		}
		
}
			