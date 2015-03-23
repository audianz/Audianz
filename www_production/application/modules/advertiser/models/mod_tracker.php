<?php
class Mod_tracker extends CI_Model { 
	 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	 public function list_tracker_status()
		{
			$this->db->select('value, name');
			$this->db->where('is_active','1');
			$this->db->order_by('name','ASC');
			$query = $this->db->get('djx_trackers_status');
	
			if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		 }
		 
	public function list_tracker_type()
		{
			$this->db->select('value, name');
			$this->db->where('is_active','1');
			$this->db->order_by('name','ASC');
			$query = $this->db->get('djx_trackers_type');
	
			if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		 }	 
	
	 /*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO TRACKERS PROCESS FOR SELECTED ADVERTISER
	 ---------------------------------------------------------------------------------*/
	
	function get_tracker_det($track_id){
		
		$this->db->where("trackerid", $track_id); 
		
		$query = $this->db->select('*')->get('ox_trackers');
		
		if($query->num_rows >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
    }
	
	function get_trackers_list($adv_id=false, $status="all", $offset=0, $limit=false){
		
		$this->db->select("oxt.trackerid,oxt.trackername,oxt.description,oxts.name as tracker_status,oxtt.name as tracker_type");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value',"left");
		$this->db->join("djx_trackers_type as oxtt",'oxt.type=oxtt.value',"left");
		if($adv_id!=false){ 

			$this->db->where('oxc.clientid',$adv_id);
		}
		
		if($status!="all") 
		{
			$this->db->where('oxts.name',$status);
		}

		if($limit != false){
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	function get_campaign_trackers_list($adv_id=false, $campid=false, $status="all", $offset=0, $limit=false){
		
		$this->db->select("oxt.trackerid,oxt.trackername,oxt.description,oxts.name as tracker_status,oxtt.name as tracker_type");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("ox_campaigns_trackers as oxct",'oxt.trackerid=oxct.trackerid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value AND oxt.status=oxct.status',"left");
		$this->db->join("djx_trackers_type as oxtt",'oxt.type=oxtt.value',"left");
		if($adv_id!=false){ 

			$this->db->where('oxc.clientid',$adv_id);
		}
		
		if($campid != false){
			$this->db->where('oxct.campaignid', $campid);
		}
		
		if($status!="all") 
		{
			$this->db->where('oxts.name',$status);
		}

		if($limit != false){
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	function get_trackers_count($adv_id=false){
		
		$this->db->select("oxt.status as status,oxts.name,count(*) as value");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value');
		$this->db->group_by("oxt.status");
		if($adv_id != false){
			$this->db->where('oxc.clientid',$adv_id);
		}
		
		$query = $this->db->get();
	
		$track_status = $this->list_tracker_status();
		foreach($track_status as $obj){
			$res_array[strtoupper($obj->name)]	=	 0;
		}
	
	
		if($query->num_rows>0)
		{
		
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= $count->value;
			}
		}
		else
		{
			$query = $this->db->select('name')->get('djx_trackers_status');
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= 0;
			}
		} 
		
		return $res_array;				
	}
	
	function get_campaign_trackers_count($adv_id=false, $campid=false){
		
		$this->db->select("oxt.status as status,oxts.name,count(*) as value");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("ox_campaigns_trackers as oxct",'oxt.trackerid=oxct.trackerid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value AND oxt.status=oxct.status');
		$this->db->group_by("oxt.status");
		if($adv_id != false){
			$this->db->where('oxc.clientid', $adv_id);
		}
		if($campid != false){
			$this->db->where('oxct.campaignid', $campid);
		}

		$query = $this->db->get();
	
		$track_status = $this->list_tracker_status();
		foreach($track_status as $obj){
			$res_array[strtoupper($obj->name)]	=	 0;
		}
	
	
		if($query->num_rows>0)
		{
		
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= $count->value;
			}
		}
		else
		{
			$query = $this->db->select('name')->get('djx_trackers_status');
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= 0;
			}
		} 
		
		return $res_array;				
	}
	
	public function insert_tracker($data){
		
			$this->db->insert('ox_trackers',$data);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		
	}	 
	
	public function update_tracker($data,$tracker_id){
		
			$this->db->where('trackerid',$tracker_id);
			$this->db->update('ox_trackers',$data);
			if($this->db->affected_rows()>0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		
	}	 
	
	public function del_tracker($sel_ids){
		
		
		if(is_array($sel_ids)){
			// echo "Multiple Tracker Delete";
			// exit;
			foreach($sel_ids as $account_id){
				$this->remove_tracker($account_id);
			}
		}
		else
		{
			//echo "Single Tracker Delete";
			// exit;
			$this->remove_tracker($sel_ids);
			
		}
	}
	
	public function remove_tracker($id){
	
			$this->db->where('trackerid',$id);
			
			$this->db->delete('ox_trackers');
			
			if($this->db->affected_rows()>0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
	
	}
	
	/*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO TRACKERS PROCESS FOR SELECTED ADVERTISER
	 ---------------------------------------------------------------------------------*/
	
	function get_campaigns_det($campaign_id){
		
		$this->db->where("campaignid", $campaign_id); 
		
		$query = $this->db->select('*')->get('ox_campaigns');
		
		if($query->num_rows >0)
		{
			$t	=	$query->result();
			return $t[0];
		}
		else
		{
			return FALSE;
		}
    }

	function get_trackers_linked_campaigns_list($adv_id,$tracker_id,$status="all",$offset=0,$limit=false){
		
		/*$this->db->select("oxc.campaignid as campaign_id,oxc.campaignname as campaign_name,oxc.viewwindow as views,oxc.clickwindow as clicks,IF((oxct.campaignid >0),1,0) as linked_campaign");
		$this->db->from('ox_campaigns as oxc'); 
		$this->db->join("ox_campaigns_trackers as oxct","oxc.campaignid=oxct.campaignid  AND oxct.trackerid=$tracker_id",'left');*/
		
		$query = "SELECT 
							`oxc`.`campaignid` as campaign_id, 
							`oxc`.`campaignname` as campaign_name, 
							`oxc`.`viewwindow` as views, 
							`oxc`.`clickwindow` as clicks, 
							IF(oxct.campaignid >0,'1','0') as linked_campaign
					FROM 
							(`ox_campaigns` as oxc) 
					LEFT JOIN 
							ox_campaigns_trackers as oxct ON (oxc.campaignid=oxct.campaignid  AND oxct.trackerid=$tracker_id)
					WHERE 
							`oxc`.`clientid` =  '$adv_id'
				 ";
		
		if($limit != false){
			$query .= " LIMIT $offset, $limit"; //$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->query($query);
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	/*Linked campaigns */
	function insert_trackercampaign($ins)
		{
			$this->db->insert('ox_campaigns_trackers',$ins);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
	}
	
	function TimeToSeconds($years=0, $days=0, $hours=0, $min=0, $sec=0)
	{
	  $seconds	=0;
	
	  if(is_numeric($years) && $years >0)
	  {
		$seconds += ($years * 31556926);
	  } 
	  if(is_numeric($days) && $days >0)
	  {
		$seconds += ($days * 86400);
	  }
	  if(is_numeric($hours) && $hours >0)
	  {
		$seconds += ($hours * 3600);
	  }
	  if(is_numeric($min) && $min >0)
	  {
		$seconds += ($min * 60);
	  }  
	  if(is_numeric($sec) && $sec >0)
	  {
		$seconds += ($sec);
	  }
	  
	return $seconds;
	}
	
	function SecondsToTime($time)
	{
	  if(is_numeric($time))
	  {
		$value = array("years" => 0, "days" => 0, "hours" => 0, "minutes" => 0, "seconds" => 0);
		if($time >= 31556926)
		{
		  $value["years"] = floor($time/31556926);
		  $time = ($time%31556926);
		}
		if($time >= 86400){
		  $value["days"] = floor($time/86400);
		  $time = ($time%86400);
		}
		if($time >= 3600){
		  $value["hours"] = (floor($time/3600)); // <10)?"0".floor($time/3600):floor($time/3600);
		  $time = ($time%3600);
		}
		if($time >= 60){
		  $value["minutes"] = (floor($time/60)); // <10)?"0".floor($time/60):floor($time/60);
		  $time = ($time%60);
		}
		$value["seconds"] = (floor($time)); // <10)?"0".floor($time):floor($time);
		return (array) $value;
	  }
	  else { return (bool) FALSE; }
	}
	
	
	function update_campaignsdata($updat_arr, $campaign_id)
	{
	
		$this->db->where('campaignid',$campaign_id);
		
		$this->db->update('ox_campaigns', $updat_arr);
		
		return TRUE;
	}
}
