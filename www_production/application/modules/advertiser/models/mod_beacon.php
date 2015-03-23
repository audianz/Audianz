<?php
/**
 *
 * @author soumya
 * This class is used to perfrom common database operations adding, updating,deleting and fetching beacon data
 */
class Mod_beacon extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}
	
	function get_beacon_list($client_id)
	{
		if($client_id!='')
		{
		//	$this->db->limit($limit, $start);
			$this->db->select("*");
			$this->db->where('Customer_ID',$client_id);
			$this->db->from("AZ_CLIENT_BEACON_MASTER");
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
	
	function retrieve_beacon_details($id)
	{
		if($id!='')
		{
			$this->db->select("*");
			$this->db->where('Beacon_Seq_ID',$id);
			$this->db->from("AZ_CLIENT_BEACON_MASTER");
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
	
	//To update beacon configurations
	function update_beacon_info($data,$id)
	{
		$where = "Beacon_Seq_ID =$id";
		$str = $this->db->update('AZ_CLIENT_BEACON_MASTER', $data, $where);
		
	}
	
	//To delete beacon
	function delete_beacon($id)
	{
		$this->db->where('Beacon_Seq_ID', $id);
		$this->db->delete('AZ_CLIENT_BEACON_MASTER');
	}
	
	//To get the list of client's campaigns
	function get_campaigns_list()
	{
		$client_id=$this->session->userdata('session_advertiser_id');
		$this->db->select("*");
		$this->db->where('clientid',$client_id);
		$this->db->from("ox_campaigns");
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
	
	//To update the beacon content for actions
	function update_beacon_content($id,$data)
	{
		$array=$_POST;
		$sql="REPLACE INTO `AZ_BEACON_PROXM_ACTIONS` ( `Beacon_Seq_ID` , `Beacon_UUID`,`Beacon_Major_ID`,`Beacon_Minor_ID`,`Proximity_Id`,`Campaign_Id`,`Remarks` ) VALUES ($id,'".$data[0]->Beacon_UUID."' , '".$data[0]->Beacon_Major_ID."','".$data[0]->Beacon_Minor_ID."','".$array['proximity']."','".$array['campaign']."','".$array['remarks']."')";  
		$result   = $this->db->query($sql);
	}
	
	//To Fetch already attached actions
	function retrieve_actions_list($id)
	{
		
		if($id!='')
		{
			$this->db->select("*");
			$this->db->where('Beacon_Seq_ID',$id);
			$this->db->from(" AZ_BEACON_PROXM_ACTIONS");
		}
		else if($id=='')
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
	
	function retrieve_campaign_details($id)
	{
		if($id!='')
		{
			$this->db->select("*");
			$this->db->where('campaignid',$id);
			$this->db->from("ox_campaigns");	
		}
			
		else if($id=='')
		{
			return false;
		}

		$query=$this->db->get();
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
		
	}
	
	//To change the Beacon status to active 
	function change_status_to_active($id)
	{
		$sql= "UPDATE `AZ_CLIENT_BEACON_MASTER` SET Beacon_Status=1 WHERE Beacon_Seq_ID=$id ";
		$result   = $this->db->query($sql);
		/* $status=1;
		$data=array('Beacon_Status'=>$status);
		$where = "Beacon_Seq_ID =$id ";
		$str =$this->db->update(' AZ_CLIENT_BEACON_MASTER',$data,$where); */
	}
	
	//To change the Beacon status to passsive 
	function change_status_to_passive($id)
	{
		$sql= "UPDATE `AZ_CLIENT_BEACON_MASTER` SET Beacon_Status=0 WHERE Beacon_Seq_ID=$id ";
		$result   = $this->db->query($sql);
		/*$status=0;
		$data=array('Beacon_Status'=>$status);
		$where = "Beacon_Seq_ID =$id ";
		$this->db->update(' AZ_CLIENT_BEACON_MASTER',$data,$where); */
	}
	
	//To remove previous action
	function delete_action($id,$pid)
	{
		$sql="DELETE FROM `AZ_BEACON_PROXM_ACTIONS` WHERE Beacon_Seq_ID=$id  AND Proximity_Id=$pid";
		$result   = $this->db->query($sql);
	}
	
	//To get and set action details on form of EDIT click
	function get_action_data($id,$pid)
	{
		$sql="SELECT * FROM `AZ_BEACON_PROXM_ACTIONS` WHERE Beacon_Seq_ID=$id  AND Proximity_Id=$pid";
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
	
		
	
	
}
	
?>
