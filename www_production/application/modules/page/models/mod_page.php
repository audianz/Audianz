<?php
class mod_page extends CI_Model 
{  	
	public function get_menu(){
		
		$menu_list	= array();
		$child_list	= array();
		
		$this->db->select("id,menu_name,parent_id,pageid");	
		$this->db->from("`oxm_menu` as oxm");
		$this->db->join("oxm_pagedetails as oxp","oxm.id = oxp.menu_id AND oxp.status=1","left");
		$this->db->order_by("oxm.parent_id");
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach($query->result() as $data){
				if($data->parent_id == 0)
				{
					$menu_list[$data->id] = array(
													"id"=>$data->id,
													"name"=>$data->menu_name,
													"pid"=>$data->parent_id,
													"page_id"=>$data->pageid,
													"child"=>'');
				}
				else
				{
					if(isset($child_list[$data->parent_id])){
						array_push($child_list[$data->parent_id],array("id"=>$data->id,"name"=>$data->menu_name,"page_id"=>$data->pageid,"pid"=>$data->parent_id));
						$menu_list[$data->parent_id]["child"] = $child_list[$data->parent_id];
					}
					else
					{
						$child_list[$data->parent_id][] = array("id"=>$data->id,"name"=>$data->menu_name,"page_id"=>$data->pageid,"pid"=>$data->parent_id);
						$menu_list[$data->parent_id]["child"] =  $child_list[$data->parent_id];;
					}
					
				}
			
				
			}
		}
		
			return $menu_list;
	}
	
	public function get_page_content($page_id){
		$this->db->where("pageid",$page_id);
		$query = $this->db->get("oxm_pagedetails");
		
		if($query->num_rows() > 0){
			$temp	= $query->result_array();
			return $temp[0];
		}
		else
		{
			return FALSE;
		}
		
	}
}