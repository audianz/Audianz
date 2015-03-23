<?php
class Mod_campaign_status extends CI_Model 
{
	 var $table_name='da_inventory_campaign_status';	
	 
	
	 function __construct()
    	{
        	// Call the Model constructor
        	parent::__construct();
    	} 

 //Listing campaign status from da_inventory_campaign_status table
	function list_campaign_status() 
		{
			
			$this->db->order_by('campaign_status_value','ASC');
			
			$query			=			$this->db->get($this->table_name);
			
			return $query->result();
			
		}
		
//Getting id from da_inventory_campaign_status table for edit operation	
	function edit_campaign_status($id) 
		{
			
				 $this->db->where('campaign_status_id',$id);
						
				 $query		=			$this->db->get($this->table_name);
			
				 return $query->result();
							
		}
		
//Inserting new record into da_inventory_campaign_status table
	
	function insert_campaign_status($status)
		{
			
			$this->db->select_max('campaign_status_value');
			
			$query					=		$this->db->get('da_inventory_campaign_status');
			
			foreach($query->result() as $max)
				{
				
					$max_value			=		$max->campaign_status_value;
			
				}
			
			$data 			= 	array(
												'campaign_status_id' =>''	,
													
   												'status' => $status,
												
												'campaign_status_value'=>$max_value + 1
   	 						  				);

			$this->db->insert($this->table_name, $data);
			
		}
	
 //updating da_inventory_campaign_status table 	
	function update_campaign_status($id,$status)
		{
			
				$update 		= 	array(
												'status' => $status,
   	 						  					);
												
					$this->db->where('campaign_status_id',$id);
					
					$this->db->update($this->table_name, $update);
			
		}							
		
	//Deleting a record from da_inventory_campaign_status table 	
	function delete_campaign_status($id)
		{
		
		$this->db->where('campaign_status_id',$id);
		
		$this->db->delete($this->table_name);
		
		}
			
}
			