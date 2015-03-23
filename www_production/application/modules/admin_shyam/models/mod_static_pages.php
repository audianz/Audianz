<?php
class Mod_static_pages extends CI_Model
{
	
	//Retrieveing records from oxm_menu table
	function get_all_menu($filter, $offset=0,$limit=10) 
		{
			$this->db->select('*');
			
			$this->db->limit($limit,$offset);
			
			$query=$this->db->get('oxm_menu');
			
			return $query->result();
			
		}
	function get_all_parent_only($filter, $offset=0,$limit=10)
	{
		$this->db->select('*');
		
		$parent= '0';
		
		$this->db->where('parent_id',$parent);

		$this->db->order_by('id');
		
		$query=$this->db->get('oxm_menu');
			
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
		
	}	
	
	function get_parent_childs($id)
	{
		$this->db->select('id,menu_name');
		
		$this->db->where('parent_id',$id);
		
		$query=$this->db->get('oxm_menu');
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return 0;
		}
		
	}
	
		
		 //Getting menu name from oxm_menu table
		function get_menu_name()
			 {
			 	$this->db->select('id,menu_name');
				
			 	$parent= '0';
				
			 	$this->db->where('parent_id',$parent);
			 	
				$query = $this->db->get('oxm_menu');
				
				if($query->num_rows>0)
				{
					return $query->result();
				}
				else
				{
					return 0;
				}
			}
		
		//Updating oxm_pagedetails table 
		function update_page_details($menu_id)
			{
				$this->db->where('menu_id',$menu_id);
				
				$data = array(
								'menu_id'		=>	'',
								
								'status'		=> '0'
							);
											
				$this->db->update('oxm_pagedetails',$data);
				
			}	
			
	//getting parent menu and child menu details from oxm_menu table
		function get_parent_menu($menu_name)
			{
			
				$this->db->select('menuname');
				
				$this->db->where('menu_location',$menu_name);
				
				$query	=		$this->db->get('oxm_menu');
				
				return $query->result;
			}							
		
	//Getting menu name from oxm_menu
		function get_menu($menu_id) 
			{
				
				$this->db->where('id',$menu_id);
				
				$query=$this->db->get('oxm_menu');
				
				return $query->result();
				
			}
		
		//Retrieveing records from oxm_menu table
		function get_menus() 
			{
				
				$this->db->select('*');
				
				$parent= '0';
					
				$this->db->where('parent_id',$parent);
				
				$query = $this->db->get('oxm_menu');
				
				if($query->num_rows>0)
				{
					return $query->result();
				}
				else
				{
					return 0;
				}	
			}
		
			//Updating oxm_menu table
		function update_menu($data,$id) 
		{
			
			$this->db->where('id',$id);
			
			$this->db->update('oxm_menu',$data);
			
		}

		
		
		//Inserting a record into oxm_menu table
		function insert_menu($menu_name,$parent_id) 
		{
			$data =	array('menu_name' => $menu_name,'parent_id'=>$parent_id);
			
			$this->db->insert('oxm_menu',$data);
			
			if($this->db->affected_rows()  > 0 )
			{
				return true;
			}
			else
			{
				return false;
					
			}
		}
		
		//Deleting menu item from oxm_menu table via checkbox
		function delete_menu($data)
		{
		
			if(is_array($data))
				
				{
					
					foreach($data as $menu_id)
						{
						
							if($menu_id==1)
							{
								exit;
							}
							else
								{	
									
									$this->db->where( 'parent_id', $menu_id);
													
									$query = $this->db->get('oxm_menu');
									
									//If child menu exists				
									if($query->num_rows() > 0)
									{
									
										$child_menus = $query->result();
													
										foreach($child_menus as $child)
										{
											$child = $child->id;
											
											
											$this->db->where('id',$child);
											$this->db->delete('oxm_menu');
											$this->mod_static_pages->update_page_details($child);
										}
										
										$this->db->where('id',$menu_id);
										$this->db->delete('oxm_menu');
										$this->mod_static_pages->update_page_details($menu_id);
									
									}
									//If no child menus exists
									else
									{					
											$this->db->where('id',$menu_id);
											$this->db->delete('oxm_menu');
											$this->mod_static_pages->update_page_details($menu_id);
									}
									
								}	
						
						}
				}
			else
			
				{
						
						$this->db->where( 'parent_id', $data);
													
						$query = $this->db->get('oxm_menu');
						
						//If child menu exists				
						if($query->num_rows() > 0)
						{
						
						$child_menus = $query->result();
									
							foreach($child_menus as $child)
							{
								$child = $child->id;
								
								
								$this->db->where('id',$child);
								$this->db->delete('oxm_menu');
								$this->mod_static_pages->update_page_details($child);
							}
						}
						$this->db->where('id',$data);
						$this->db->delete('oxm_menu');
						$this->mod_static_pages->update_page_details($data);
						
				
				}		
		}

		//Retrieveing menuid for comparing
		function get_menu_id_for_compare() 
			{
				
				$this->db->select('menu_id');
				
				$query = $this->db->get(' oxm_pagedetails');
				
				if($query->num_rows>0)
				{
					return $query->result();
				}
				else
				{
					return 0;
				}	
			}

}
