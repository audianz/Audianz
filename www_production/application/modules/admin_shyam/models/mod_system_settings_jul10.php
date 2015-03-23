<?php
class Mod_system_settings extends CI_Model 
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
	
	// dynamically get the active and inactive and all the records
	function get_num_records($status ='all',$status_field_name,$tbl_name)
	{
		if($status==="active")
		{
			$this->db->where($status_field_name,'1');	
		}else if($status === "inactive"){
			$this->db->where($status_field_name,'0');	
		}
		$this->db->from($tbl_name);
		return $this->db->count_all_results();
		
	}	
	
// get the country all the records
	function get_country() 

	{
         $query=$this->db->get('oxm_country');
         return $query->result();

	}
	
	/************Device Manufacturer************/
	
	/***** Retreive  Device Manufactures *******/
	function get_device_manufacturers($status="all",$limit=0,$offset=0)
	{
			$this->db->select('manufacturer_id, manufacturer_name');
			if($status =="active")
			{
				$this->db->where('manufacturer_status','1');
			}else if($status==="inactive")
			{
				$this->db->where('manufacturer_status','0');
			}
			$this->db->order_by('manufacturer_id','DESC');
			if($limit ===0)
			{
				$query = $this->db->get('djx_device_manufacturer');
			}else
			{
				$query = $this->db->get('djx_device_manufacturer',$limit,$offset);
			}
			if($query->num_rows>0)
			{
				return $query->result();
			}else
			{
				return FALSE;
			}
	}

	/*********************Edit Device Manufacturer ***************/
	function get_device_manufacturer($manufacturer_id = false)
	{
		if($manufacturer_id !=false)
		{
			$this->db->select('manufacturer_name');
			$this->db->from('djx_device_manufacturer');
			$this->db->where('manufacturer_id', $manufacturer_id);
			$query = $this->db->get();
			
		if($query->num_rows >0)
		{
			$temp =  $query->result();
                        return $temp[0];
		}
		else
		{
			return FALSE;
		}
            }
            else
            {
                return FALSE;
            }
	}

	
	/********End:Device Manufacturer **********/
	/************Device Os Module *********/
	function get_device_os_list($status="all",$limit=0,$offset=0)
	{
			$this->db->select('os_id, os_platform');
			if($status =="active")
			{
				$this->db->where('os_status','1');
			}else if($status==="inactive")
			{
				$this->db->where('os_status','0');
			}
			$this->db->order_by('os_id','DESC');
			if($limit ===0)
			{
				$query = $this->db->get('djx_device_os');
			}else
			{
				$query = $this->db->get('djx_device_os',$limit,$offset);
			}
			if($query->num_rows>0)
			{
				return $query->result();
			}else
			{
				return FALSE;
			}
	}
	
	/*********************Edit Device  OS ***************/
	function get_device_os($os_id = false)
	{
		if($os_id !=false)
		{
			$this->db->select('os_platform,os_value');
			$this->db->from('djx_device_os');
			$this->db->where('os_id', $os_id);
			$query = $this->db->get();
			
		if($query->num_rows >0)
		{
			$temp =  $query->result();
                        return $temp[0];
		}
		else
		{
			return FALSE;
		}
            }
            else
            {
                return FALSE;
            }
	}
	
	/***************** End Device OS Module *****************/
	
	/***************** Geo Locations ***********************/
	
	/**********Retrieve the Geo Locqations Records ********/
	function get_geo_locations_list($status="all",$limit=0,$offset=0)
	{
			$this->db->select('djx_geographic_locations.code, djx_geographic_locations.name, djx_geographic_locations.status,djx_geographic_continents.continent_name');
			if($status =="active")
			{
				$this->db->where('djx_geographic_locations.status','1');
			}else if($status==="inactive")
			{
				$this->db->where('djx_geographic_locations.status','0');
			}
			$this->db->from('djx_geographic_locations');
			$this->db->join('djx_geographic_continents','djx_geographic_continents.continent_code = djx_geographic_locations.continent_code');
			$this->db->order_by('djx_geographic_locations.name','ASC');
			if($limit ===0)
			{
				$query = $this->db->get();
			}else
			{
				$this->db->limit($limit,$offset);
				$query = $this->db->get();
			}
			if($query->num_rows>0)
			{
				return $query->result();
			}else
			{
				return FALSE;
			}
	}
		
	/*********************Edit Geo Location ***************/
	function get_geo_location($loc_code = '')
	{
		if($loc_code !='')
		{
			$where_arr		=	array('code'=>$loc_code);
			$query = $this->db->get_where('djx_geographic_locations',$where_arr);
			
		if($query->num_rows >0)
		{
			$temp =  $query->result();
            return $temp[0];
		}
		else
		{
			return FALSE;
		}
            }
            else
            {
                return FALSE;
            }
	}
	
	/*******************Campaign Categories *****************/
	
	/***** Retreive  Campaign Categoriess *******/
	function get_campaign_categories($status="all",$limit=0,$offset=0)
	{
			//$this->db->select('category_id, category_name,added_date,updated_date');
			if($status =="active")
			{
				$this->db->where('status','1');
			}else if($status==="inactive")
			{
				$this->db->where('status','0');
			}
			$this->db->order_by('category_name','ASC');
			if($limit ===0)
			{
				$query = $this->db->get('djx_campaign_categories');
			}else
			{
				$query = $this->db->get('djx_campaign_categories',$limit,$offset);
			}
		
			if($query->num_rows>0)
			{
				return $query->result();
			}else
			{
				return FALSE;
			}
	}

	/*********************Edit Campaign Category ***************/
	function get_campaign_category($category_id = false)
	{
		if($category_id !=false)
		{
			$this->db->select('category_name, category_value');
			$this->db->from('djx_campaign_categories');
			$this->db->where('category_id', $category_id);
			$query = $this->db->get();
			
		if($query->num_rows >0)
		{
			$temp =  $query->result();
             return $temp[0];
		}
		else
		{
			return FALSE;
		}
            }
            else
            {
                return FALSE;
            }
	}

	/************Device Capability************/

	/***** Retreive  Device Capability *******/

	function get_device_capability($status="all",$limit=0,$offset=0)

	{

			$this->db->select('capability_id, capability_name,capability_value');

			if($status =="active")

			{

				$this->db->where('capability_status','1');

			}else if($status==="inactive")

			{

				$this->db->where('capability_status','0');

			}

			$this->db->order_by('capability_id','DESC');

			if($limit ===0)

			{

				$query = $this->db->get('djx_device_capability');

			}else

			{

				$query = $this->db->get('djx_device_capability',$limit,$offset);

			}

			if($query->num_rows>0)

			{

				return $query->result();

			}else

			{

				return FALSE;

			}

	}

	/*********************Edit Device capability ***************/
	function get_device_capabilitys($capability_id = false)
	{
		if($capability_id !=false)
		{

			$this->db->select('capability_name,capability_value');

			$this->db->from('djx_device_capability');

			$this->db->where('capability_id', $capability_id);

			$query = $this->db->get();

			

			if($query->num_rows >0)

			{

				$temp =  $query->result();

                 return $temp[0];

			}

			else

			{

				return FALSE;

			}

         }

         else
		 {

              return FALSE;

          }

	}

	/************** Retrieve Number of Records ********/

	function get_num_device_capability($status ='all')

	{

		if($status==="active")

		{

			$this->db->where('capability_status','1');	

		}else if($status === "inactive"){

			$this->db->where('capability_status','0');	

		}

		$this->db->from('djx_device_capability');

		return $this->db->count_all_results();

		

	}	

	

	

	/********End:Device Manufacturer **********/

	/************geo_operators************/

	

	/***** Retreive  geo_operators *******/

	function get_geo_operators($status="all",$limit=0,$offset=0)

	{

			//$this->db->select("telecom_name");

			$this->db->select("djx_telecom_circle.telecom_id,djx_telecom_circle.telecom_name,djx_telecom_circle.telecom_value");

			if($status ==="active")
			{
				$this->db->where('telecom_status','1');

			}
			else if($status==="inactive")
			{
				$this->db->where('telecom_status','0');
			}

			$this->db->from("djx_telecom_circle");
            $this->db->order_by('djx_telecom_circle.telecom_id','DESC'); 

			if($limit!=0)
			{
				$this->db->limit($limit,$offset);
			}

			$query= $this->db->get();
		    if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
	}

	/*********************Edit geo_operators ***************/

	function get_geo_operatorss($geo_operators_id = false)
	{
		if($geo_operators_id !=false)
		{
			$this->db->select('telecom_name,telecom_value');
			$this->db->from('djx_telecom_circle');
			$this->db->where('telecom_id', $geo_operators_id);
			$query = $this->db->get();
			
		if($query->num_rows >0)
		{
			$temp =  $query->result();
             return $temp[0];
		}

		else

		{

			return FALSE;

		}

            }

            else

            {

                return FALSE;

            }

	}

	
     	/************End  Geo operators************/
		
		
	/************client_profile************/

	

	/***** Retreive  client_profile *******/

	function get_client_profiles($limit=0,$offset=0)

	{

			//$this->db->select("telecom_name");

			$this->db->select('from,to,id');

			$this->db->from('djx_client_profile');
                        $this->db->order_by('id','desc'); 
			
			if($limit!=0)

			{

				$this->db->limit($limit,$offset);

			}

			 $query= $this->db->get();

			if($query->num_rows>0)

			{

				return $query->result();

			}else

			{

				return FALSE;

			}

	}

	

	
	

	/*********************Edit client_profile ***************/

	function get_client_profile($client_profile_id = false)

	{

		if($client_profile_id !=false)

		{

			$this->db->select('from,to');

			$this->db->from('djx_client_profile');

			$this->db->where('id', $client_profile_id);

			$query = $this->db->get();

			

		if($query->num_rows >0)

		{

			$temp =  $query->result();

                        return $temp[0];

		}

		else

		{

			return FALSE;

		}

            }

            else

            {

                return FALSE;

            }

	}

	
	/********End:client_profile **********/

	

}
