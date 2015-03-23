<?php
class Mod_adv_dashboard extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	/*Spend on Day Basis */
	function amount_spend($adv_id='',$where_arr=0)
	{
		if($where_arr!=0 && $adv_id !='')
		{
			$this->db->where($where_arr);
			$this->db->where("ox_campaigns.clientid",$adv_id);
		}
		$this->db->select('sum(oxm_report.amount) as amount_spend');
		$this->db->from('oxm_report');
		$this->db->join('ox_banners','ox_banners.bannerid = oxm_report.bannerid');
		$this->db->join('ox_campaigns','ox_campaigns.campaignid= oxm_report.campaignid');
		$query = $this->db->get();
		return $query->result();
	}
	/* Revenue on Current Month */
	function month_revenue($where_arr=0,$where_pub=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("MONTH(date)",$where_arr);
		}

		if($where_pub!=0)
		{
			$this->db->where($where_pub);
		}

		$this->db->select('sum(oxm_report.publisher_amount) as month_rev');

		$this->db->join('oxm_report','oxm_report.zoneid = ox_zones.zoneid');

		$query = $this->db->get('ox_zones');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	/* Retrieve Campaign */
	function retrieve_campaign_list($adv_id ='',$camp_id=0)
	{
		if($adv_id !='')
		{
			if($camp_id!=0)
			{
				$this->db->where($camp_id);
			}

			$this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

			$this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid');

			$this->db->where('ox_clients.clientid',$adv_id);
				
			$this->db->order_by('ox_campaigns.campaignid','desc');

			$query = $this->db->get('ox_campaigns');

			return $query->result();
			 
		}else{
			exit;
		}
	}

	//Retreive Percentage and  Campaign count
	function retreive_campaign_count_percentage($adv_id)
	{
		if($adv_id)
		{
			$SQL = "SELECT count(*) AS CAMPCOUNT,(count(*)*100/(SELECT count(*) FROM ox_campaigns)) AS PERCENTAGE FROM ox_campaigns as oxc JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid JOIN oxm_budget as oxb ON oxb.campaignid=oxc.campaignid WHERE oxc.clientid='".$adv_id."'";
			$query = $this->db->query($SQL);
			return $query->result();
		}else{
			return 0;
		}
	}

	//Retreive Percentage and  Banner Count
	function retreive_banner_count_percentage($adv_id)
	{
		if($adv_id)
		{
			$SQL = "SELECT count(*) AS BANCOUNT,(count(*)*100/(SELECT count(*) FROM ox_banners)) AS PERCENTAGE FROM ox_banners as oxb JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid WHERE oxc.clientid='".$adv_id."' AND oxb.master_banner='-2' ";
			$query = $this->db->query($SQL);
			return $query->result();
		}else{
			return 0;
		}
	}

	/* Retrieve Banners List */
	function getBanners($adv_id ='',$where_arr=0)
	{
		if($adv_id !='')
		{
			if($where_arr!=0)
			{
				$this->db->where($where_arr);
			}

			$this->db->select('ox_banners.bannerid');

			$this->db->from('ox_banners');

			$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');
				
			$this->db->join('ox_clients','ox_clients.clientid= ox_campaigns.clientid');
			 
			$this->db->where('ox_clients.clientid',$adv_id);
				
			$query = $this->db->get();

			return $query->result();
		}else
		{
			echo $this->lang->line('label_not_valid_id');
			exit;
		}
	}

}
