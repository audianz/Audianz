<?php
/**
 *
 * @author soumya
 * This class is used to perform common database operations of fetching,updating,adding & deleting 
   data related to proximity data.
 */
class Mod_proximity extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}
	
	/* Function to fetch page image details */
	function get_page_details()
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="SELECT * FROM `AZ_PROXIMITY_PAGES` WHERE client_id=$clientid";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	}
	
	/* Function to insert page data */
	function insert_camp($data,$path)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="INSERT INTO `PROX_CAMP` (`client_id`, `camp_name`, `start_date`, `end_date`, `category`, `landing_text`, `notification_msg`, `landing_image`) 
		VALUES ('$clientid','".$data['camp_name']."','".$data['start_date']."','".$data['end_date']."','".$data['category']."','".$data['landing_text']."' ,'".$data['msg']."','".$path."') ";		
		$query=$this->db->query($sql);
	
	}
	
	/* Function for fetching the last row */
	function fetch_last_row()
	{
		$sql="SELECT * FROM `PROX_CAMP` ORDER BY 'camp _id' DESC LIMIT 1";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	}
	
	/* To get networks mix for campaign optimization */
	function camp_optimization_list()
	{
		$sql="SELECT * FROM `Campaign_Optimize_Mix` ";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	}

	/* To get the specific Network data using mix_id */
	function get_network_data($id)
	{
		$sql="SELECT * FROM `Campaign_Optimize_Mix` WHERE Mix_id=$id ";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	}
	
	/* To update the edited data of network */
	function edit_network($data)
	{
		$sql="REPLACE INTO `Campaign_Optimize_Mix` (`Mix_id`,`Network_type`,`Network`,`Mix_percent`)
			 VALUES ('".$data[mix_id]."','".$data[type]."','".$data[network]."','".$data[mix_percent]."') ";
		$query=$this->db->query($sql);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	
	/* To add new network */
	function add_network($data)
	{
		$sql="REPLACE INTO `Campaign_Optimize_Mix` (`Network_type`,`Network`,`Mix_percent`)
			 VALUES ('".$data[type]."','".$data[network]."','".$data[mix_percent]."') ";
		$query=$this->db->query($sql);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	/* To remove network from the list */
	function remove_network($id)
	{
		$sql="DELETE FROM `Campaign_Optimize_Mix` WHERE Mix_id=$id";
		$query=$this->db->query($sql);
		
	}
	
	
	

}

?>
