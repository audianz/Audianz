<?php
/**
 *
 * @author shyam
 * This class is used to perfrom common database operations adding, updating,deleting
 */
class Mod_storefront extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}

	function insert_data($table_row,$tbl_name,$client_id)
	{
		if(!empty($table_row))
		{		
			foreach ($table_row as $insert_data)
			{
				$this->db->insert($tbl_name,$insert_data);
			}
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		}
	}

	/**
	 * The function is used to get the list of storefront data uploaded.
	 * @return boolean
	 */
	function get_storefront_list($client_id)

	{
		if($client_id!='')
		{
		//	$this->db->limit($limit, $start);
			$this->db->select("*");
			$this->db->where('clientid',$client_id);
			$this->db->from("storefrontdata");
			$this->db->order_by('storefrontdata.poi_name','ASC');
		}
		else if($client_id=='')
		{
			return false;
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

	/**
	 * This functin is used to retrive the radius of logged in advertiser. If no radius is set,
	 * it will return default radius value 5.
	 * @param unknown $poi_name
	 * @return string
	 */
	function defaultRadius($adv_id)
	{
		$default_radius=5;

		$this->db->select('radius');
		$this->db->from('storefront_radius as rad');
		$this->db->where('rad.advid',$adv_id);

		$query=$this->db->get();

		if($query->num_rows()==0)
		{
			$insert_data=array('advid'=>$adv_id,'radius'=>$default_radius);

			$this->db->insert('storefront_radius',$insert_data);
			if($this->db->affected_rows()>0)
			{
				//return $default_radius;
				$this->db->select('radius');
				$this->db->from('storefront_radius as rad');
				$this->db->where('rad.advid',$adv_id);
					
				$query=$this->db->get();
					
				if($query->num_rows()>0)
				{
					return $query->result();

				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->db->select('radius');
			$this->db->from('storefront_radius as rad');
			$this->db->where('rad.advid',$adv_id);

			$query=$this->db->get();

			if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}

		}

	}

	function getCount($client_id)
	{
		//	$this->db->where('clientid',$client_id);
		$this->db->select('*');
		$this->db->where('clientid',$client_id);
		$query = $this->db->get('storefrontdata');
		$count = $query->num_rows();
		return $count;

	}



	function check_poi_name_exist($poi_name)
	{
		$this->db->select('*');
		$this->db->from('storefrontdata as store');
		$this->db->where('store.poi_name', $poi_name);

		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			//	return "1";
			return "0";
		}
		else
		{
			return "0";
		}
	}

	/**
	 * This function is used to update the value of radius.For new advertiser it will insert the radius
	 *
	 * @param unknown $val
	 * @param unknown $tbl_name
	 * @return boolean
	 */
	function updateRecord($val,$adv_id)
	{
		$tbl_name='storefront_radius';
		$this->db->select('*');
		$this->db->from('storefront_radius as rad');
		$this->db->where('rad.advid',$adv_id);
		$query=$this->db->get();

		//If there is no advertiser in table insert a new record
		if($query->num_rows()==0)
		{
			$insert_data=array('advid'=>$adv_id,'radius'=>$val);

			$this->db->insert($tbl_name,$insert_data);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}

		//Update the existing radius of advertiser

		else
		{
			$data=array('radius'=>$val);
			$this->db->where('advid',$adv_id);
			$this->db->update($tbl_name,$data);

			if($this->db->affected_rows()>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	/* -----------------------------Added by Soumya ----------------------------------  */
	
		
	function retrieve_store($id=0)
    {
		$clientid = $this->session->userdata('session_advertiser_id');
		
		if($id!=0)
        {
         $sql="SELECT * FROM `storefrontdata` WHERE id=$id AND clientid=$clientid";
         $result =null;
         $query = $this->db->query($sql);
		if($query->num_rows()>0)
		{
			$result=$query->result_array();
		}
		return $result;
		}
	}
	
	function update_store_info($data,$table_name,$id)
	{
		
		$clientid = $this->session->userdata('session_advertiser_id');
		$where = "clientid = $clientid AND id =$id";
		$str = $this->db->update('storefrontdata', $data, $where);
	}
	
	function add_store($insert_data,$tbl_name,$client_id)
	{
		$this->db->insert('storefrontdata',$insert_data);
		
	}
	
	//To remove store from list
	function delete_store($id)
	{
		echo "called";
		$this->db->where('id', $id);
		$this->db->delete('storefrontdata');
	}
	
	//To get map search data
	function map_search_store($loc)
	{	
		if($loc!=NULL)
        {
			$sql="SELECT * FROM `storefrontdata` WHERE poi_name='$loc'";
			//echo $sql;
			
			$result =null;
			$query = $this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
			return $result;
		} 
	}
	
	//To get the stores attached to beacons
	function beacon_related_stores()
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="SELECT * FROM `AZ_CLIENT_BEACON_MASTER` JOIN `storefrontdata` ON AZ_CLIENT_BEACON_MASTER.Beacon_Install_Location_ID = storefrontdata.id
				WHERE AZ_CLIENT_BEACON_MASTER.Customer_ID =$clientid ";
		$query=$this->db->query($sql);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * Insert location related to campaign 
	 * */
	 function insert_camp_location($client,$camp,$store)
	 {
		 $sql="INSERT INTO `campaign_location_table` (`id`, `client_id`, `campaign_id`, `store_id`) 
			VALUES (NULL, '".$client."', '".$camp."', '".$store."')";
			$query=$this->db->query($sql);
	}

}