<?php
class Mod_pages extends CI_Model
{ 
		
			 
			 
			 function get_all()
			{
					
					$this->db->select('page_title,status,description,pageid');
					
					$this->db->order_by("pageid", "asc");
					
					$query =$this->db->get('oxm_pagedetails');
					
					return $query->result();
			}
			
			//Getting a record from oxm_pagedetails table for edit operation
			function edit_page($id)
			{
			
					$this->db->where( 'pageid', $id);
				
					$query = $this->db->get('oxm_pagedetails');
					
					return $query->result();
					
			 }
			 
			 //Updating oxm_pagedetails table 
			function update_page($data,$page_id)
			{
			
					$this->db->where( 'pageid', $page_id);
	
					$this->db->update('oxm_pagedetails', $data);
				
					
			 }
			
			//Inserting Record into oxm_pagedetails table 
			function insert($page_data)
			{
			
				$this->db->insert('oxm_pagedetails', $page_data);
				
				if($this->db->affected_rows() > 0)
				{
				
					return true;
				}	
			else
				{
					return false;
				}
				
					
			 }
			 
			 //Activatiing status  in oxm_pagedetails table 
			 function status_activate($page_id)
			{
			
					$this->db->where('pageid',$page_id);
					
					$data 	= 		array(
													'status' => 1
												);
					
					$this->db->update('oxm_pagedetails', $data);
				
					
			 }
			 
			  //Dectivatiing status  in oxm_pagedetails table 
			 function status_deactivate($page_id)
			{
			
					$this->db->where('pageid',$page_id);
					
					$data 	= 		array(
													'status' => 0
												);
					
					$this->db->update('oxm_pagedetails', $data);
				
					
			 }
			
			//Deleting a record from oxm_pagedetails table 
			function delete($page_id)
			{
					
					$this->db->where( 'pageid', $page_id);
	
					$this->db->delete('oxm_pagedetails');
				
					
			 }
			 
			 //Deleting a record from oxm_pagedetails via checkbox
			 
			 function delete_chk_box($page_id)
			 {
			 
			 		if(is_array($page_id))
						{
							foreach($page_id as $id)
								{
									
									$this->mod_pages->delete($id);
								
								}	
						}
					
					else
						{
							$this->mod_pages->delete($page_id);
							
						}
				}					
					
			 
			 //Getting menu name from oxm_menu table
			 function get_menu_name($menu_id)
			 {
			 
			 	$this->db->select('*');
					
				$this->db->where('id',$menu_id);

				$query	=		$this->db->get('oxm_menu');
				
				$temp=$query->row();
				
				return $temp->menu_name;
				
			}
			
			function get_menu_name_page($pageid)
			{
				$this->db->select('*');
					
				$this->db->where('pageid',$pageid);

				$query	= $this->db->get('oxm_pagedetails');
				
				return $query->result();
				
			}
			
			function get_parent_item()
			 {
			 
			 	$this->db->select('page_title,pageid');
				
				$query = $this->db->get('oxm_pagedetails');
				
				return $query->result();
				
			}
			
		} 