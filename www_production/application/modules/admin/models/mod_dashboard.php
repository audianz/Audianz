<?php
class Mod_dashboard extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_clickimp_count($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_unique.date_time)",$where_arr);
		}

		$this->db->select('ox_campaigns.clientid,ox_banners.bannerid,ox_unique.creative_id,ox_unique.clicks,ox_unique.viewer_id,ox_unique.date_time,ox_unique.impressions,ox_banners.campaignid,ox_campaigns.campaignid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->join('ox_unique','ox_unique.creative_id = ox_banners.bannerid');

		$this->db->join('ox_data_bkt_unique_m','ox_data_bkt_unique_m.creative_id = ox_banners.bannerid');

		$this->db->where('ox_unique.impressions !=','0');

		$this->db->group_by(array('ox_unique.viewer_id','ox_unique.creative_id','ox_unique.zone_id','date(ox_unique.date_time)'));

		$query = $this->db->get('ox_banners');

		$result = $query->result();

		//echo $this->db->last_query();//exit;

		return $result;
	}

	function get_unique_imp($where_arr=0)
	{
		// UNIOIN QUERY
		if($where_arr!=0)
		{
			$query = mysql_query("select u.viewer_id,u.creative_id,DATE(u.date_time),b.campaignid,c.campaignid  from ox_unique u join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=u.creative_id where DATE(u.date_time)='$where_arr' and b.master_banner in('-1','-2','-3') UNION select m.viewer_id,m.creative_id,DATE(m.interval_start),b.campaignid,c.campaignid from ox_data_bkt_unique_m m join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid  and b.bannerid=m.creative_id where DATE(m.interval_start)='$where_arr' and b.master_banner in('-1','-2','-3')");

		}
		else
		{
			$query = mysql_query("select u.viewer_id,u.creative_id,DATE(u.date_time),b.campaignid,c.campaignid  from ox_unique u join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=u.creative_id  and b.master_banner in('-1','-2','-3') UNION select m.viewer_id,m.creative_id,DATE(m.interval_start),b.campaignid,c.campaignid from ox_data_bkt_unique_m m join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid  and b.bannerid=m.creative_id and b.master_banner in('-1','-2','-3')");
		}

		$rows = mysql_num_rows($query);
		if($rows>0)
		{
			return $rows;
		}
		else
		{
			return 0;
		}

		//return $query->result();
	}


	/*Getting IMpression */
	function get_impressions($data)
	{
		$SQL=" select sum(impressions) as imp from ox_data_summary_ad_hourly";
		$SQL	.=	" WHERE month(date_time)=$data";
			
		$query=$this->db->query($SQL);
		if($query->num_rows() > 0)
		{
			$temp=$query->result();
			return $temp[0]->imp;
		}
	}

	/*GETTING UNIQUE IMPRESSION */

	function get_unq_imp($data)
	{
		$SQL=" select sum(impressions) as unq_imp from ox_unique";
		$SQL	.=	" WHERE month(date_time)=$data";
			
		$query=$this->db->query($SQL);
		if($query->num_rows() > 0)
		{
			$temp=$query->result();
			return $temp[0]->unq_imp;
		}
	}

	/*Getting Clicks*/
	function get_clicks($data)
	{
		$SQL=" select sum(clicks) as clicks from ox_data_summary_ad_hourly";
		$SQL	.=	" WHERE month(date_time)=$data";
			
		$query=$this->db->query($SQL);
		if($query->num_rows() > 0)
		{
			$temp=$query->result();
			return $temp[0]->clicks;
		}
			
	}

	/*Getting Conversion*/
	function get_conversion($data)
	{
		$SQL=" select sum(conversions) as conversion from ox_data_summary_ad_hourly";
		$SQL	.=	" WHERE month(date_time)=$data";
			
		$query=$this->db->query($SQL);
		if($query->num_rows() > 0)
		{
			$temp=$query->result();
			return $temp[0]->conversion;
		}
			
	}



	/*Getting Unique Click*/
	function get_unique_clk($data)
	{
		$SQL=" select sum(clicks) as unq_clk from ox_unique";
		$SQL	.=	" WHERE month(date_time)=$data";
			
		$query=$this->db->query($SQL);
		if($query->num_rows() > 0)
		{
			$temp=$query->result();
			return $temp[0]->unq_clk;
		}
			
	}

	function get_unique_imp_nocount($where_arr=0)
	{
		 
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}

		$master = array('-1','-2','-3');

		$this->db->select('ox_campaigns.clientid,ox_banners.bannerid,ox_data_bkt_unique_m.creative_id,ox_data_bkt_unique_m.viewer_id,ox_data_bkt_unique_m.interval_start,ox_data_bkt_unique_m.count,ox_banners.campaignid,ox_campaigns.campaignid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->join('ox_data_bkt_unique_m','ox_data_bkt_unique_m.creative_id = ox_banners.bannerid');

		$this->db->group_by('date(ox_data_bkt_unique_m.interval_start)');

		$this->db->where_in('ox_banners.master_banner', $master);

		$query = $this->db->get('ox_banners');

		//echo $this->db->last_query();exit;

		if($query->num_rows()>0)
		{
			//return $query->result();
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}

	function get_clickcli_count($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_unique.date_time)",$where_arr);
		}

		$master = array('-1','-2','-3');

		$this->db->select('ox_campaigns.clientid,ox_banners.bannerid,ox_unique.creative_id,ox_unique.clicks,ox_unique.viewer_id,ox_unique.date_time,ox_unique.creative_id,ox_banners.campaignid,ox_campaigns.campaignid');

		$this->db->join('ox_unique','ox_unique.creative_id = ox_banners.bannerid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->where('ox_unique.clicks !=','0');

		$this->db->group_by(array('ox_unique.viewer_id,ox_unique.creative_id,date(ox_unique.date_time)'));

		$this->db->where_in('ox_banners.master_banner', $master);

		$query = $this->db->get('ox_banners');

		return $query->result();
	}

	function get_unique_click($where_arr=0)
	{
		// UNIOIN QUERY
		if($where_arr!=0)
		{
			$query = $this->db->query("select u.viewer_id,u.creative_id,month(u.date_time),b.campaignid,c.campaignid from ox_unique u join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=u.creative_id  where DATE(u.date_time)='$where_arr' and b.master_banner in('-1','-2','-3') UNION select m.viewer_id,m.creative_id,DATE(m.interval_start),c.campaignid,b.campaignid from ox_data_bkt_unique_c m join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=m.creative_id where DATE(m.interval_start)='$where_arr' and b.master_banner in('-1','-2','-3')");
		}
		else
		{
			$query = $this->db->query("select u.viewer_id,u.creative_id,month(u.date_time),b.campaignid,c.campaignid from ox_unique u join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=u.creative_id and b.master_banner in('-1','-2','-3') UNION select m.viewer_id,m.creative_id,DATE(m.interval_start),c.campaignid,b.campaignid from ox_data_bkt_unique_c m join ox_campaigns c join ox_banners b on b.campaignid=c.campaignid and b.bannerid=m.creative_id and b.master_banner in('-1','-2','-3')");
		}

		//return $query->result();
		return $query->num_rows();
	}

	function get_unique_click_nocount($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_data_bkt_unique_c.interval_start)",$where_arr);
		}

		$this->db->select('ox_campaigns.clientid,ox_banners.bannerid,ox_data_bkt_unique_c.creative_id,count(ox_data_bkt_unique_c.viewer_id),ox_data_bkt_unique_c.interval_start,ox_banners.campaignid,ox_campaigns.campaignid');

		$this->db->join('ox_data_bkt_unique_c','ox_data_bkt_unique_c.creative_id = ox_banners.bannerid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->group_by(array('ox_data_bkt_unique_c.viewer_id,ox_data_bkt_unique_c.creative_id,ox_data_bkt_unique_c.zone_id,date(ox_data_bkt_unique_c.interval_start)'));

		$query = $this->db->get('ox_banners');
		if($query->num_rows()>0)
		{
			//return $query->result();
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}

	function get_budget_impressions($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_data_bkt_m.interval_start)",$where_arr);
		}

		$this->db->select('sum(ox_data_bkt_m.count) as budgetimp,ox_data_bkt_m.creative_id,ox_data_bkt_m.interval_start,ox_banners.bannerid,ox_campaigns.clientid,ox_campaigns.campaignid,ox_banners.campaignid');

		$this->db->join('ox_data_bkt_m','ox_data_bkt_m.creative_id = ox_banners.bannerid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$query = $this->db->get('ox_banners');

		//$query = $this->db->query("select sum(count) as budgetimp,m.creative_id,m.interval_start,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_m m join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=m.creative_id where DATE(m.interval_start)='$where_arr'");

		return $query->result();
	}

	function get_budget_clicks($where_arr=0)
	{

		$master = array('-1','-2','-3');
		 
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_data_bkt_m.interval_start)",$where_arr);
		}

		$this->db->select('sum(ox_data_bkt_m.count) as budgetclick,ox_data_bkt_m.creative_id,ox_data_bkt_m.interval_start,ox_banners.bannerid,ox_campaigns.clientid,ox_campaigns.campaignid,ox_banners.campaignid');

		$this->db->join('ox_data_bkt_m','ox_data_bkt_m.creative_id = ox_banners.bannerid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->where_in('ox_banners.master_banner', $master);

		$query = $this->db->get('ox_banners');
	  
		//echo $this->db->last_query();exit;

		return $query->result();
	}

	/* Unique Impressions Count */
	function unique_imp_count($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}
			
		$this->db->select('ox_zones.affiliateid,ox_zones.zoneid,ox_unique.zone_id,ox_unique.clicks,ox_unique.viewer_id,ox_unique.date_time,ox_unique.creative_id,ox_unique.impressions');
			
		$this->db->join('ox_unique','ox_unique.zone_id = ox_zones.zoneid');
			
		$this->db->where('ox_unique.impressions !=','0');

		$this->db->group_by(array('ox_unique.viewer_id,ox_unique.zone_id,date(ox_unique.date_time)'));

		$query = $this->db->get('ox_zones');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	function get_ad_hourly_data($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(ox_data_summary_ad_hourly.date_time)",$where_arr);
		}

		$master = array('-1','-2','-3');

		$this->db->select('ox_campaigns.campaignname,ox_banners.description,ox_data_summary_ad_hourly.date_time,sum(ox_data_summary_ad_hourly.impressions) as hourimp,sum(ox_data_summary_ad_hourly.clicks) as hourclick,sum(ox_data_summary_ad_hourly.total_revenue),ox_banners.bannerid,ox_campaigns.clientid,ox_data_summary_ad_hourly.ad_id,ox_banners.campaignid,ox_campaigns.campaignid');

		$this->db->join('ox_data_summary_ad_hourly','ox_data_summary_ad_hourly.ad_id = ox_banners.bannerid');

		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');

		$this->db->where_in('ox_banners.master_banner', $master);

		$query = $this->db->get('ox_banners');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	function get_revenue($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("DATE(date)",$where_arr);
		}

		$this->db->select('sum(amount) as rev');

		$query = $this->db->get('oxm_report');

		return $query->result();
	}

	function get_month_revenue($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where("MONTH(date)",$where_arr);
		}

		$this->db->select('sum(amount) as rev');

		$query = $this->db->get('oxm_report');

		return $query->result();
	}

	/* COMMON STATISTICS */
	function get_common_stats($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}

		$this->db->select('sum(ox_data_summary_ad_hourly.impressions) as comimp,sum(ox_data_summary_ad_hourly.clicks) as comclick,sum(ox_data_summary_ad_hourly.conversions) as comconv,sum(ox_data_summary_ad_hourly.total_revenue) as totrev,ox_data_summary_ad_hourly.ad_id,ox_data_summary_ad_hourly.date_time,ox_data_summary_ad_hourly.zone_id,ox_zones.affiliateid,ox_zones.zoneid,ox_banners.bannerid');

		$this->db->join('ox_zones','ox_zones.zoneid = ox_data_summary_ad_hourly.zone_id');
		 
		$this->db->join('ox_banners','ox_banners.bannerid = ox_data_summary_ad_hourly.ad_id');

		$query = $this->db->get('ox_data_summary_ad_hourly');
		 
		return $query->result();
	}

	/* BUCKET IMPRESSIONS */
	function bucket_impression($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}

		$this->db->select('sum(ox_data_bkt_m.count) as bktimp_count,ox_data_bkt_m.zone_id,ox_data_bkt_m.interval_start,ox_zones.zoneid,ox_zones.affiliateid');

		$this->db->join('ox_zones','ox_zones.zoneid = ox_data_bkt_m.zone_id');

		$query = $this->db->get('ox_data_bkt_m');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	/* BUCKET CLICKS */
	function bucket_click($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}

		$this->db->select('sum(ox_data_bkt_c.count) as bktclicks,ox_data_bkt_c.creative_id,ox_data_bkt_c.interval_start,ox_zones.zoneid,ox_zones.affiliateid');

		$this->db->join('ox_zones','ox_zones.zoneid = ox_data_bkt_c.zone_id');

		$query = $this->db->get('ox_data_bkt_c');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	/* Unique Impressions */
	function unique_impressions($where_date=0)
	{
		//print_r($where_arr);exit;
		if($where_date!=0)
		{
			$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id  AND month(u.date_time) = '".$where_date."' and z.master_zone in('-1','-2','-3') UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_m m,ox_zones z where z.zoneid=m.zone_id AND month(m.interval_start) = '".$where_date."' and z.master_zone in('-1','-2','-3')");
				
		}
		else
		{
			$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id and z.master_zone in('-1','-2','-3') UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_m m,ox_zones z where z.zoneid=m.zone_id and z.master_zone in('-1','-2','-3')");
		}

		$rows = mysql_num_rows($query);
		if($rows>0)
		{
			return $rows;
		}
		else
		{
			return 0;
		}
	}
	/* Unique Click Count */
	function unique_click_count($where_arr=0)
	{
		if($where_arr!=0)
		{
			$this->db->where($where_arr);
		}
			
		$this->db->select('ox_zones.affiliateid,ox_zones.zoneid,ox_unique.zone_id,ox_unique.clicks,ox_unique.viewer_id,ox_unique.date_time,ox_unique.creative_id,ox_unique.clicks');
			
		$this->db->join('ox_unique','ox_unique.zone_id = ox_zones.zoneid');
			
		$this->db->where('ox_unique.clicks !=','0');

		$this->db->group_by(array('ox_unique.viewer_id,ox_unique.zone_id,date(ox_unique.date_time)'));

		$query = $this->db->get('ox_zones');

		//echo $this->db->last_query();exit;

		return $query->result();
	}

	/* Unique Clicks */
	function unique_clicks($where_date=0)
	{
		if($where_date!=0)
		{
			$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id AND month(u.date_time) = '".$where_date."' UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_c m,ox_zones z where z.zoneid=m.zone_id AND month(m.interval_start) = '".$where_date."'");
				
		}
		else
		{
			$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_c m,ox_zones z where z.zoneid=m.zone_id");
		}

		//echo $this->db->last_query();exit;

		return mysql_num_rows($query);
	}



	// Get Last Six Month Revenue
	function get_past_month_revenues()
	{
		$query = $this->db->query("SELECT sum(oxm_report.publisher_amount) as month_rev, MONTH(date) as month, YEAR(date) as year FROM ox_zones JOIN oxm_report ON oxm_report.zoneid = ox_zones.zoneid WHERE date >= date_sub(date, INTERVAL 6 MONTH) GROUP BY MONTH(date);");

		return $query->result();
	}


	/***Retreive the Total Number of Publishers and Advertisers *******/
	function get_users_count($user_type ='',$user_mode='')
	{
		switch($user_type)
		{
			//In case of Advertiser
			case "Advertiser":
				//check Whetheer the Approved Users or Pending Users Count is need
				if($user_mode ==='Approved')
				{
					$query	=		$this->db->select('count(*) as total')->get_where('oxm_userdetails',array('accounttype'=>'ADVERTISER'));
				}elseif($user_mode ==='Pending')
				{
					$query	=		$this->db->select('count(*) as total')->get_where('oxm_newusers',array('account_type'=>'ADVERTISER'));
				}
				break;
				//In case of Publisher
			case "Publisher":
				//check Whetheer the Approved Users or Pending Users Count is need
				if($user_mode ==='Approved')
				{
					$query	=		$this->db->select('count(*) as total')->get_where('oxm_userdetails',array('accounttype'=>'TRAFFICKER'));
				}elseif($user_mode ==='Pending')
				{
					$query	=		$this->db->select('count(*) as total')->get_where('oxm_newusers',array('account_type'=>'TRAFFICKER'));
				}
				break;
				//In case of Total Users
			default:
				//Get the Total number of registered users
				$query	=		$this->db->select('count(*) as total')->get('oxm_userdetails');
				break;
		}
		return	$query->result();
	}

	/***Retreive the Total Number of Publishers and Advertisers Registered Today *******/
	function get_users_count_today($user_type ='',$user_mode='')
	{
		$today =	date("Y-m-d");
		switch($user_type)
		{
			//In case of Advertiser
			case "Advertiser":

				$where_arr		=		array('ox_accounts.account_type' => 'ADVERTISER', 'DATE(ox_users.date_created)' =>$today);
				$this->db->select('count(*) as total');
				$this->db->from('ox_users');
				$this->db->join('ox_accounts','ox_accounts.account_id  = ox_users.default_account_id ');
				$this->db->where($where_arr);
				$query	=		$this->db->get();

				break;
				//In case of Publisher
			case "Publisher":
					
				$where_arr		=		array('ox_accounts.account_type' => 'TRAFFICKER', 'DATE(ox_users.date_created)' =>$today);
				$this->db->select('count(*) as total');
				$this->db->from('ox_users');
				$this->db->join('ox_accounts','ox_accounts.account_id  = ox_users.default_account_id ');
				$this->db->where($where_arr);
				$query	=		$this->db->get();
				break;
				//In case of Total Users
			default:
				//Get the Total number of registered users
				$where_arr		=		array( 'DATE(ox_users.date_created)' =>$today);
				$this->db->select('count(*) as total');
				$this->db->from('ox_users');
				$this->db->join('ox_accounts','ox_accounts.account_id  = ox_users.default_account_id ');
				$this->db->where($where_arr);
				$query	=		$this->db->get();
					
				break;
		}
		return	$query->result();
	}

	// To Retrieve the Advertisers and Publishers who are waiting for approval
	function get_user_approval_list($user_account_type ='')
	{
		$where_arr	=	array('oxm_newusers.account_type'=>'ADVERTISER','oxm_approval_notification.read_status' =>0);
		$this->db->select('oxm_newusers.*');
		$this->db->from('oxm_newusers');
		$this->db->join('oxm_approval_notification','oxm_approval_notification.approval_user_id=oxm_newusers.user_id');
		$this->db->where($where_arr);
		$query	=	$this->db->get();
		return $query->result();
	}

	// Retrieve click to call,click to web and click to map for last week
	function get_week_click_to_action_list($stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',DATE(date) AS 'date'
					from oxm_report As r ";
			
				switch ($stat_type)
				{
					case 'last_seven_days':
						$SQL.="where DATE(date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}

				$SQL .=" GROUP BY date(date)";

				$query = $this->db->query($SQL);

				if($query->num_rows>0)
				{
					$stat_summary =  $query->result_array();

					foreach($stat_summary as $data)
					{

						$result[$data['date']]	=	array(
								"CALL"=>$data['CALL'],
								"WEB"=>$data['WEB'],
								"MAP"=>$data['MAP']

						);

					}
					return $result;

				}
				else
				{
					$result=0;
					return $result;
				}
			

		}
	}

	// Retrieve click to call,click to web and click to map for current month and daily
	function get_click_to_action_for_admin($stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',date
					from oxm_report As r ";

			
				switch ($stat_type)
				{
					case 'current_month':
						$SQL.=" where  month(date)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'today':
						$SQL.="where  DATE(date)=DATE(now())";
						break;
					case 'overall':
						$SQL.="";

				}

				$query = $this->db->query($SQL);

				if($query->num_rows>0)
				{
					$result =  $query->result_array();
					return $result[0];

				}
				else
				{
					$result=0;
					return $result;
				}
			
		}

	}



	// Retreive Impressions,clicks and Conversions for the monthly basis
	function get_monthly_report($stat_type =FALSE){
			
		//print_r($search_array);
		if($stat_type)
		{
			$result = array();
				
			$SQL = "SELECT oxcl.clientid  AS CLIENTID,
					ifnull(sum( h.impressions ),0) AS IMP,
					month(h.date_time) as db_month,
					ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
					ifnull(sum( h.`clicks` ),0) AS CLICKS
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
				

			$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";

				
			switch($stat_type)
			{
				case 'current_month':
					$SQL .=" WHERE month(h.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL .=" WHERE date(h.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}

			$SQL .=" GROUP BY month(h.date_time)";
				

			$query = $this->db->query($SQL);
				

			if($query->num_rows>0)
			{
				$stat_summary =  $query->result_array();
					
				foreach($stat_summary as $data){

					$result[$data['db_month']]	=	array(
							"IMP"=>$data['IMP'],
							"CON"=>$data['CONVERSIONS'],
							"CLK"=>$data['CLICKS'],
							"CLIENTID"=>$data['CLIENTID'],
							"SPEND"=>0
					);
						
				}
			}

			//exit;

			$temp = $result;

				
			// GETTING IMPRESSIONS
				
			$SQL_BKT_IMP	=	"SELECT
					oxcl.clientid,
					month(odbm.interval_start) as db_month,
					ifnull(sum( odbm.`count` ),0) AS IMP
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";

			$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";

				
			switch($stat_type)
			{
				case 'current_month':
					$SQL_BKT_IMP .=" WHERE month(odbm.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL_BKT_IMP .=" WHERE date(odbm.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}

			$SQL_BKT_IMP .=" GROUP BY month(odbm.interval_start)";
				
			$query_imp = $this->db->query($SQL_BKT_IMP);


			if($query_imp->num_rows>0)
			{
				$stat_summary_imp =  $query_imp->result_array();
					
				foreach($stat_summary_imp as $data_imp){
						
					if(isset($temp[$data_imp['db_month']]['IMP'])){
						$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_month']]['IMP'];
						$result[$data_imp['db_month']]['IMP']	=$tot_imp;
					}else{
						$tot_imp		=	$data_imp['IMP'];
						$result[$data_imp['db_month']]['IMP']	=	$tot_imp;
						$result[$data_imp['db_month']]['CLK']	=	0;
						$result[$data_imp['db_month']]['CON']	=	0;
						$result[$data_imp['db_month']]['SPEND']	=	0;
					}
						
				}
			}

			$temp= $result;
				
			// GETTING CLICKS
				
			$SQL_BKT_CLK	=	"SELECT
					oxcl.clientid,
					month(odbc.interval_start) as db_month,
					ifnull(sum( odbc.`count` ),0) AS CLICKS
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
				
			$SQL_BKT_CLK .=  "JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
				
			switch($stat_type)
			{
				case 'current_month':
					$SQL_BKT_CLK .=" WHERE month(odbc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL_BKT_CLK .=" WHERE date(odbc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}

			$SQL_BKT_CLK .=" GROUP BY month(odbc.interval_start)";
				
				
			$query_clk = $this->db->query($SQL_BKT_CLK);


			if($query_clk->num_rows>0)
			{
				$stat_summary_clk =  $query_clk->result_array();
					
				foreach($stat_summary_clk as $data_clk){

					if(isset($temp[$data_clk['db_month']]['CLK'])){
						$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['db_month']]['CLK'];
						$result[$data_clk['db_month']]['CLK']	=$tot_clicks;
					}else{
						$tot_clicks		=	$data_clk['CLICKS'];
							
						$result[$data_clk['db_month']]['IMP']	=	0;
						$result[$data_clk['db_month']]['CLK']	=	$tot_clicks;
						$result[$data_clk['db_month']]['CON']	=	0;
						$result[$data_clk['db_month']]['SPEND']	=	0;
					}
						
				}
			}
				
			$temp= $result;

			//GETTING  CONVERSIONS
			$SQL_BKT_CON=	"SELECT
					oxcl.clientid,
					month(odba.date_time) as db_month,
					ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
				
			$SQL_BKT_CON .=  "JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
				
			switch($stat_type)
			{
				case 'current_month':
					$SQL_BKT_CON .=" WHERE month(odba.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL_BKT_CON .=" WHERE date(odba.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}

			$SQL_BKT_CON .=" GROUP BY month(odba.date_time)";
				
				
			$query_con = $this->db->query($SQL_BKT_CON);
				
			if($query_con->num_rows>0)
			{
				$stat_summary_con =  $query_con->result_array();
					
				foreach($stat_summary_con as $data_con){

					if(isset($temp[$data_con['db_month']]['CON'])){
						$tot_cons	=	$data_con['CONVERSIONS'] + $temp[$data_con['db_month']]['CON'];
						$result[$data_con['db_month']]['CON']	=$tot_cons;
					}else{
						$tot_cons		=	$data_con['CONVERSIONS'];
							
						$result[$data_con['db_month']]['IMP']	=	0;
						$result[$data_con['db_month']]['CLK']	=	0;
						$result[$data_con['db_month']]['CON']	=	$tot_cons;
						$result[$data_con['db_month']]['SPEND']	=	0;
					}
						
				}
			}
				
			$temp= $result;
				
				
			// GETTING SPEND AMOUNT
				
			$SQL_BKT_SPEND	=	"SELECT
					oxcl.clientid,
					month(oxmr.date) as db_month,
					FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";

			$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";

				
			switch($stat_type)
			{
				case 'current_month':
					$SQL_BKT_SPEND .=" WHERE month(oxmr.date)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL_BKT_SPEND .=" WHERE date(oxmr.date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}

				

			$SQL_BKT_SPEND .=" GROUP BY month(oxmr.date)";
				
			$query_spend = $this->db->query($SQL_BKT_SPEND);


			if($query_spend->num_rows>0)
			{
				$stat_summary_spend =  $query_spend->result_array();

				foreach($stat_summary_spend as $data_spend){
					$tot_spend		=	$data_spend['SPEND'];
					if(isset($temp[$data_spend['db_month']])){
						//$tot_spend		=	$data_spend['SPEND'] + 0;
						$result[$data_spend['db_month']]['SPEND']	=$tot_spend;
					}else{
						$result[$data_spend['db_month']]['IMP']		=	0;
						$result[$data_spend['db_month']]['CLK']		=	0;
						$result[$data_spend['db_month']]['CON']		=	0;
						$result[$data_spend['db_month']]['SPEND']	=	$tot_spend;
					}
				}
			}

			$temp= $result;

			//ASSIGN UIMP and UCLICKS as default 0
				
			$new_temp = array();
			foreach($temp as $key => $stat_data){
				$new_temp[$key]['IMP'] 		= $stat_data['IMP'];
				$new_temp[$key]['CLK'] 		= $stat_data['CLK'];
				$new_temp[$key]['CON'] 		= $stat_data['CON'];
				$new_temp[$key]['SPEND'] 	= $stat_data['SPEND'];
				$new_temp[$key]['UIMP'] 	= 0;
				$new_temp[$key]['UCLK'] 	= 0;
			}
				
			$temp 	= $new_temp;
			$result = $new_temp;

			// GET UNIQUE IMPRESSIONS

			$SQL = "SELECT db_month,count(db_month) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
				
			switch($stat_type)
			{
				case 'current_month':
					$SQL .=" WHERE oxu.impressions>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL.=" WHERE oxu.impressions>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}


			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
				
			$SQL .=" UNION (SELECT month(oxum.interval_start) as db_month,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_banners as oxb ON (oxum.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			switch($stat_type)
			{
				case 'current_month':
					$SQL .=" WHERE MONTH(oxum.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL.=" WHERE date(oxum.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}


				
				
				
			$SQL .=" GROUP BY oxum.`viewer_id`,oxum.`creative_id`,oxum.zone_id,month(oxum.interval_start))) as UIMP GROUP BY db_month";
				
			$query = $this->db->query($SQL);
				
			if($query->num_rows>0)
			{
				$stat_summary =  $query->result_array();

				foreach($stat_summary as $unique_data){

					if(isset($result[$unique_data['db_month']])){
							
						$result[$unique_data['db_month']]['IMP']		= $temp[$unique_data['db_month']]['IMP'];
						$result[$unique_data['db_month']]['CLK']		= $temp[$unique_data['db_month']]['CLK'];
						$result[$unique_data['db_month']]['CON']		= $temp[$unique_data['db_month']]['CON'];
						$result[$unique_data['db_month']]['SPEND']	= $temp[$unique_data['db_month']]['SPEND'];

						$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= 0;

							
					}
					else
					{
						$result[$unique_data['db_month']]['IMP']		= 0;
						$result[$unique_data['db_month']]['CLK']		= 0;
						$result[$unique_data['db_month']]['CON']		= 0;
						$result[$unique_data['db_month']]['SPEND']	= 0;

						$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= 0;
					}
						
						
				}
			}

			$temp = $result;
				

			//GET UNIQUE CLICKS

			$SQL = "SELECT db_month,count(db_month) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.clicks),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
				
			switch($stat_type)
			{
				case 'current_month':
					$SQL .=" WHERE oxu.clicks>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL.=" WHERE oxu.clicks>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}


				
				
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
				
			$SQL .=" UNION (SELECT month(oxuc.interval_start) as db_month,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_banners as oxb ON (oxuc.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			switch($stat_type)
			{
				case 'current_month':
					$SQL .=" WHERE MONTH(oxuc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL.=" WHERE date(oxuc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
					break;
			}


				
				
				
			$SQL .=" GROUP BY oxuc.`viewer_id`,oxuc.`creative_id`,oxuc.zone_id,month(oxuc.interval_start))) AS UCLK GROUP BY db_month";
				
			$query = $this->db->query($SQL);
				
			if($query->num_rows>0)
			{
				$stat_summary =  $query->result_array();

				foreach($stat_summary as $unique_data){

					if(isset($result[$unique_data['db_month']])){
							
						$result[$unique_data['db_month']]['IMP']		= $temp[$unique_data['db_month']]['IMP'];
						$result[$unique_data['db_month']]['CLK']		= $temp[$unique_data['db_month']]['CLK'];
						$result[$unique_data['db_month']]['CON']		= $temp[$unique_data['db_month']]['CON'];
						$result[$unique_data['db_month']]['SPEND']	= $temp[$unique_data['db_month']]['SPEND'];

						$result[$unique_data['db_month']]['UIMP']	= $temp[$unique_data['db_month']]['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK'];

							
					}
					else
					{
						$result[$unique_data['db_month']]['IMP']		= 0;
						$result[$unique_data['db_month']]['CLK']		= 0;
						$result[$unique_data['db_month']]['CON']		= 0;
						$result[$unique_data['db_month']]['SPEND']	= 0;

						$result[$unique_data['db_month']]['UIMP']	= 0;
						$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK'];
					}
						
						
				}
			}

			$temp = $result;
				

			//CALCULATE CTR


			$final_result 	= array();
			$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CTR"=>0.00,"UIMP"=>'0',"UCLK"=>'0');
			if(count($result) > 0){
				foreach($result as $key => $resObj){

					if($resObj['IMP'] > 0){
						$CTR		=	($resObj['CLK']/$resObj['IMP'])*100;
						$CTR		=	number_format($CTR,2,'.',',');
					}
					else
					{
						$CTR		= 	0.00;
					}

					$final_result[$key]	=	array(
							"IMP"=>$resObj['IMP'],
							"CON"=>$resObj['CON'],
							"CLK"=>$resObj['CLK'],
							"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
							"CTR"=>number_format($CTR,2,'.',','),
							"UIMP"=>$resObj['UIMP'],
							"UCLK"=>$resObj['UCLK']
					);

					$final_tot['IMP']	+=  $resObj['IMP'];
					$final_tot['CON']	+=  $resObj['CON'];
					$final_tot['CLK']	+=  $resObj['CLK'];
					$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");
					$final_tot['UIMP']	+=  $resObj['UIMP'];
					$final_tot['UCLK']	+=  $resObj['UCLK'];

						
				}
			}

			if($final_tot['IMP'] > 0)
				$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
			else
				$final_tot['CTR']	=  0.00;

			asort($final_result);

			$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
			//print_r($out);exit;
			return $out;
		}
	}

	function get_overall_report()
	{
		$result = array();
			
		$SQL = "SELECT oxcl.clientid  AS CLIENTID,
				ifnull(sum( h.impressions ),0) AS IMP,
				month(h.date_time) as db_month,
				ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
				ifnull(sum( h.`clicks` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			

		$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";

			


		$SQL .=" GROUP BY month(h.date_time)";
			

		$query = $this->db->query($SQL);
			

		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();
				
			foreach($stat_summary as $data){

				$result[$data['db_month']]	=	array(
						"IMP"=>$data['IMP'],
						"CON"=>$data['CONVERSIONS'],
						"CLK"=>$data['CLICKS'],
						"CLIENTID"=>$data['CLIENTID'],
						"SPEND"=>0
				);
					
			}
		}

		//exit;

		$temp = $result;

			
		// GETTING IMPRESSIONS
			
		$SQL_BKT_IMP	=	"SELECT
				oxcl.clientid,
				month(odbm.interval_start) as db_month,
				ifnull(sum( odbm.`count` ),0) AS IMP
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";

			


		$SQL_BKT_IMP .=" GROUP BY month(odbm.interval_start)";
			
		$query_imp = $this->db->query($SQL_BKT_IMP);


		if($query_imp->num_rows>0)
		{
			$stat_summary_imp =  $query_imp->result_array();
				
			foreach($stat_summary_imp as $data_imp){
					
				if(isset($temp[$data_imp['db_month']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_month']]['IMP'];
					$result[$data_imp['db_month']]['IMP']	=$tot_imp;
				}else{
					$tot_imp		=	$data_imp['IMP'];
					$result[$data_imp['db_month']]['IMP']	=	$tot_imp;
					$result[$data_imp['db_month']]['CLK']	=	0;
					$result[$data_imp['db_month']]['CON']	=	0;
					$result[$data_imp['db_month']]['SPEND']	=	0;
				}
					
			}
		}

		$temp= $result;
			
		// GETTING CLICKS
			
		$SQL_BKT_CLK	=	"SELECT
				oxcl.clientid,
				month(odbc.interval_start) as db_month,
				ifnull(sum( odbc.`count` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			
		$SQL_BKT_CLK .=  "JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			


		$SQL_BKT_CLK .=" GROUP BY month(odbc.interval_start)";
			
			
		$query_clk = $this->db->query($SQL_BKT_CLK);


		if($query_clk->num_rows>0)
		{
			$stat_summary_clk =  $query_clk->result_array();
				
			foreach($stat_summary_clk as $data_clk){

				if(isset($temp[$data_clk['db_month']]['CLK'])){
					$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['db_month']]['CLK'];
					$result[$data_clk['db_month']]['CLK']	=$tot_clicks;
				}else{
					$tot_clicks		=	$data_clk['CLICKS'];
						
					$result[$data_clk['db_month']]['IMP']	=	0;
					$result[$data_clk['db_month']]['CLK']	=	$tot_clicks;
					$result[$data_clk['db_month']]['CON']	=	0;
					$result[$data_clk['db_month']]['SPEND']	=	0;
				}
					
			}
		}
			
		$temp= $result;

		//GETTING CONVERSIONS

		//GETTING  CONVERSIONS
		$SQL_BKT_CON=	"SELECT
				oxcl.clientid,
				month(odba.date_time) as db_month,
				ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			
		$SQL_BKT_CON .=  "JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";

		$SQL_BKT_CON .=" GROUP BY month(odba.date_time)";
			
			
		$query_con = $this->db->query($SQL_BKT_CON);
			
		if($query_con->num_rows>0)
		{
			$stat_summary_con =  $query_con->result_array();
				
			foreach($stat_summary_con as $data_con){

				if(isset($temp[$data_con['db_month']]['CON'])){
					$tot_cons	=	$data_con['CONVERSIONS'] + $temp[$data_con['db_month']]['CON'];
					$result[$data_con['db_month']]['CON']	=$tot_cons;
				}else{
					$tot_cons		=	$data_con['CONVERSIONS'];
						
					$result[$data_con['db_month']]['IMP']	=	0;
					$result[$data_con['db_month']]['CLK']	=	0;
					$result[$data_con['db_month']]['CON']	=	$tot_cons;
					$result[$data_con['db_month']]['SPEND']	=	0;
				}
					
			}
		}
			
		$temp= $result;
			

			
		// GETTING SPEND AMOUNT
			
		$SQL_BKT_SPEND	=	"SELECT
				oxcl.clientid,
				month(oxmr.date) as db_month,
				FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";

		$SQL_BKT_SPEND .=" GROUP BY month(oxmr.date)";
			
		$query_spend = $this->db->query($SQL_BKT_SPEND);


		if($query_spend->num_rows>0)
		{
			$stat_summary_spend =  $query_spend->result_array();

			foreach($stat_summary_spend as $data_spend){
				$tot_spend		=	$data_spend['SPEND'];
				if(isset($temp[$data_spend['db_month']])){
					//$tot_spend		=	$data_spend['SPEND'] + 0;
					$result[$data_spend['db_month']]['SPEND']	=$tot_spend;
				}else{
					$result[$data_spend['db_month']]['IMP']		=	0;
					$result[$data_spend['db_month']]['CLK']		=	0;
					$result[$data_spend['db_month']]['CON']		=	0;
					$result[$data_spend['db_month']]['SPEND']	=	$tot_spend;
				}
			}
		}

		$temp= $result;

		//ASSIGN UIMP and UCLICKS as default 0
			
		$new_temp = array();
		foreach($temp as $key => $stat_data){
			$new_temp[$key]['IMP'] 		= $stat_data['IMP'];
			$new_temp[$key]['CLK'] 		= $stat_data['CLK'];
			$new_temp[$key]['CON'] 		= $stat_data['CON'];
			$new_temp[$key]['SPEND'] 	= $stat_data['SPEND'];
			$new_temp[$key]['UIMP'] 	= 0;
			$new_temp[$key]['UCLK'] 	= 0;
		}
			
		$temp 	= $new_temp;
		$result = $new_temp;

		// GET UNIQUE IMPRESSIONS

		$SQL = "SELECT db_month,count(db_month) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
			
		$SQL .=" WHERE oxu.impressions>0";

		$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
			
		$SQL .=" UNION (SELECT month(oxum.interval_start) as db_month,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_banners as oxb ON (oxum.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";


			
			
			
		$SQL .=" GROUP BY oxum.`viewer_id`,oxum.`creative_id`,oxum.zone_id,month(oxum.interval_start))) as UIMP GROUP BY db_month";
			
		$query = $this->db->query($SQL);
			
		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();

			foreach($stat_summary as $unique_data){

				if(isset($result[$unique_data['db_month']])){
						
					$result[$unique_data['db_month']]['IMP']		= $temp[$unique_data['db_month']]['IMP'];
					$result[$unique_data['db_month']]['CLK']		= $temp[$unique_data['db_month']]['CLK'];
					$result[$unique_data['db_month']]['CON']		= $temp[$unique_data['db_month']]['CON'];
					$result[$unique_data['db_month']]['SPEND']	= $temp[$unique_data['db_month']]['SPEND'];

					$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
					$result[$unique_data['db_month']]['UCLK']	= 0;

						
				}
				else
				{
					$result[$unique_data['db_month']]['IMP']		= 0;
					$result[$unique_data['db_month']]['CLK']		= 0;
					$result[$unique_data['db_month']]['CON']		= 0;
					$result[$unique_data['db_month']]['SPEND']	= 0;

					$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
					$result[$unique_data['db_month']]['UCLK']	= 0;
				}
					
					
			}
		}

		$temp = $result;
			

		//GET UNIQUE CLICKS

		$SQL = "SELECT db_month,count(db_month) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.clicks),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
			
		$SQL .=" WHERE oxu.clicks>0";
			
		$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
			
		$SQL .=" UNION (SELECT month(oxuc.interval_start) as db_month,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_banners as oxb ON (oxuc.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";


			
		$SQL .=" GROUP BY oxuc.`viewer_id`,oxuc.`creative_id`,oxuc.zone_id,month(oxuc.interval_start))) AS UCLK GROUP BY db_month";

		$query = $this->db->query($SQL);
			
		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();

			foreach($stat_summary as $unique_data){

				if(isset($result[$unique_data['db_month']])){
						
					$result[$unique_data['db_month']]['IMP']		= $temp[$unique_data['db_month']]['IMP'];
					$result[$unique_data['db_month']]['CLK']		= $temp[$unique_data['db_month']]['CLK'];
					$result[$unique_data['db_month']]['CON']		= $temp[$unique_data['db_month']]['CON'];
					$result[$unique_data['db_month']]['SPEND']	= $temp[$unique_data['db_month']]['SPEND'];

					$result[$unique_data['db_month']]['UIMP']	= $temp[$unique_data['db_month']]['UIMP'];
					$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK'];

						
				}
				else
				{
					$result[$unique_data['db_month']]['IMP']		= 0;
					$result[$unique_data['db_month']]['CLK']		= 0;
					$result[$unique_data['db_month']]['CON']		= 0;
					$result[$unique_data['db_month']]['SPEND']	= 0;

					$result[$unique_data['db_month']]['UIMP']	= 0;
					$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK'];
				}
					
					
			}
		}

		$temp = $result;
			

		//CALCULATE CTR


		$final_result 	= array();
		$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CTR"=>0.00,"UIMP"=>'0',"UCLK"=>'0');
		if(count($result) > 0){
			foreach($result as $key => $resObj){

				if($resObj['IMP'] > 0){
					$CTR		=	($resObj['CLK']/$resObj['IMP'])*100;
					$CTR		=	number_format($CTR,2,'.',',');
				}
				else
				{
					$CTR		= 	0.00;
				}

				$final_result[$key]	=	array(
						"IMP"=>$resObj['IMP'],
						"CON"=>$resObj['CON'],
						"CLK"=>$resObj['CLK'],
						"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
						"CTR"=>number_format($CTR,2,'.',','),
						"UIMP"=>$resObj['UIMP'],
						"UCLK"=>$resObj['UCLK']
				);

				$final_tot['IMP']	+=  $resObj['IMP'];
				$final_tot['CON']	+=  $resObj['CON'];
				$final_tot['CLK']	+=  $resObj['CLK'];
				$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");
				$final_tot['UIMP']	+=  $resObj['UIMP'];
				$final_tot['UCLK']	+=  $resObj['UCLK'];

					
			}
		}

		if($final_tot['IMP'] > 0)
			$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
		else
			$final_tot['CTR']	=  0.00;

		asort($final_result);

		$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
		//print_r($out);exit;
		return $out;
			

	}


	function get_date_report($stat_type=FALSE)
	{

		$result = array();
			
		$SQL = "SELECT oxcl.clientid AS CLIENTID,
				ifnull(sum( h.impressions ),0) AS IMP,
				date(h.date_time) as db_date,
				ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
				ifnull(sum( h.`clicks` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			

		$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";

		if($stat_type=="weekly")
		{
			$SQL .=" WHERE date(h.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL .=" WHERE date(h.date_time)=CURDATE()";
		}
			
			

		$SQL .=" GROUP BY date(h.date_time)";
			

		$query = $this->db->query($SQL);
			

		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();
				
			foreach($stat_summary as $data){

				$result[$data['db_date']]	=	array(
						"IMP"=>$data['IMP'],
						"CON"=>$data['CONVERSIONS'],
						"CLK"=>$data['CLICKS'],
						"CLIENTID"=>$data['CLIENTID'],
						"SPEND"=>0
				);
					
			}
		}

		//exit;

		$temp = $result;

		// GETTING IMPRESSIONS
			
		$SQL_BKT_IMP	=	"SELECT
				oxcl.clientid,
				date(odbm.interval_start) as db_date,
				ifnull(sum( odbm.`count` ),0) AS IMP
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";

		if($stat_type=="weekly")
		{
			$SQL .=" WHERE date(odbm.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL_BKT_IMP .=" WHERE date(odbm.interval_start)=CURDATE()";
		}

		$SQL_BKT_IMP .=" GROUP BY date(odbm.interval_start)";
			
		$query_imp = $this->db->query($SQL_BKT_IMP);


		if($query_imp->num_rows>0)
		{
			$stat_summary_imp =  $query_imp->result_array();
				
			foreach($stat_summary_imp as $data_imp){
					
				if(isset($temp[$data_imp['db_date']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
					$result[$data_imp['db_date']]['IMP']	=$tot_imp;
				}else{
					$tot_imp		=	$data_imp['IMP'];
					$result[$data_imp['db_date']]['IMP']	=	$tot_imp;
					$result[$data_imp['db_date']]['CLK']	=	0;
					$result[$data_imp['db_date']]['CON']	=	0;
					$result[$data_imp['db_date']]['SPEND']	=	0;
				}
					
			}
		}
		$temp = $result;

		// GETTING CLICKS
			
		$SQL_BKT_CLK	=	"SELECT
				oxcl.clientid,
				date(odbc.interval_start) as db_date,
				ifnull(sum( odbc.`count` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			
		$SQL_BKT_CLK .=  "JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			
		if($stat_type=="weekly")
		{
			$SQL_BKT_CLK .=" WHERE date(odbc.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL_BKT_CLK .=" WHERE date(odbc.interval_start)=CURDATE()";
		}
			
		$SQL_BKT_CLK .=" GROUP BY date(odbc.interval_start)";
			
		$query_clk = $this->db->query($SQL_BKT_CLK);


		if($query_clk->num_rows>0)
		{
			$stat_summary_clk =  $query_clk->result_array();
				

				
			foreach($stat_summary_clk as $data_clk){
				if(isset($temp[$data_clk['db_date']]['CLK'])){
					$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['db_date']]['CLK'];
					$result[$data_clk['db_date']]['CLK']	=$tot_clicks;
				}else{
					$tot_clicks		=	$data_clk['CLICKS'];
						
					$result[$data_clk['db_date']]['IMP']	=	0;
					$result[$data_clk['db_date']]['CLK']	=	$tot_clicks;
					$result[$data_clk['db_date']]['CON']	=	0;
					$result[$data_clk['db_date']]['SPEND']	=	0;
				}
					
			}
		}
			
		$temp = $result;
			
		//GETTING  CONVERSIONS
		$SQL_BKT_CON=	"SELECT
				oxcl.clientid,
				date(odba.date_time) as db_date,
				ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			
		$SQL_BKT_CON .=  "JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			
		if($stat_type=="weekly")
		{
			$SQL_BKT_CON .=" WHERE date(odba.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL_BKT_CON .=" WHERE date(odba.date_time)=CURDATE()";
		}

		$SQL_BKT_CON .=" GROUP BY date(odba.date_time)";
			
			
		$query_con = $this->db->query($SQL_BKT_CON);
			
		if($query_con->num_rows>0)
		{
			$stat_summary_con =  $query_con->result_array();
				
			foreach($stat_summary_con as $data_con){

				if(isset($temp[$data_con['db_date']]['CON'])){
					$tot_cons	=	$data_con['CONVERSIONS'] + $temp[$data_con['db_date']]['CON'];
					$result[$data_con['db_date']]['CON']	=$tot_cons;
				}else{
					$tot_cons		=	$data_con['CONVERSIONS'];
						
					$result[$data_con['db_date']]['IMP']	=	0;
					$result[$data_con['db_date']]['CLK']	=	0;
					$result[$data_con['db_date']]['CON']	=	$tot_cons;
					$result[$data_con['db_date']]['SPEND']	=	0;
				}
					
			}
		}
			
		$temp= $result;

			
		// GETTING SPEND AMOUNT
			
		$SQL_BKT_SPEND	=	"SELECT
				oxcl.clientid,
				date(oxmr.date) as db_date,
				FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";

		if($stat_type=="weekly")
		{
			$SQL_BKT_SPEND .=" WHERE date(oxmr.date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL_BKT_SPEND .=" WHERE date(oxmr.date)=CURDATE()";
		}
			
		$SQL_BKT_SPEND .=" GROUP BY date(oxmr.date)";
			
		$query_spend = $this->db->query($SQL_BKT_SPEND);


		if($query_spend->num_rows>0)
		{
			$stat_summary_spend =  $query_spend->result_array();

			foreach($stat_summary_spend as $data_spend){
				$tot_spend		=	$data_spend['SPEND'];
				if(isset($temp[$data_spend['db_date']])){
					//$tot_spend		=	$data_spend['SPEND'] + 0;
					$result[$data_spend['db_date']]['SPEND']	=$tot_spend;
				}else{
					$result[$data_spend['db_date']]['IMP']		=	0;
					$result[$data_spend['db_date']]['CLK']		=	0;
					$result[$data_spend['db_date']]['CON']		=	0;
					$result[$data_spend['db_date']]['SPEND']	=	$tot_spend;
						

				}
			}
		}
		$temp = $result;


		//ASSIGN UIMP and UCLICKS as default 0
			
		$new_temp = array();
		foreach($temp as $key => $stat_data){
			$new_temp[$key]['IMP'] 		= $stat_data['IMP'];
			$new_temp[$key]['CLK'] 		= $stat_data['CLK'];
			$new_temp[$key]['CON'] 		= $stat_data['CON'];
			$new_temp[$key]['SPEND'] 	= $stat_data['SPEND'];
			$new_temp[$key]['UIMP'] 	= 0;
			$new_temp[$key]['UCLK'] 	= 0;
		}
			
		$temp 	= $new_temp;
		$result = $new_temp;

			
		// GET UNIQUE IMPRESSIONS

		$SQL = "SELECT db_date,count(db_date) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
			
		if($stat_type=="weekly")
		{
			$SQL .=" WHERE oxu.impressions>0 AND date(oxu.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL .=" WHERE oxu.impressions>0 AND date(oxu.date_time)=CURDATE()";
		}
		$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.`date_time`))";
			
		$SQL .=" UNION (SELECT date(oxum.interval_start) as db_date,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_banners as oxb ON (oxum.`creative_id`=oxb.bannerid) JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

		if($stat_type=="weekly")
		{
			$SQL .=" WHERE date(oxum.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL .=" WHERE date(oxum.interval_start)=CURDATE()";
		}
			
		$SQL .=" GROUP BY oxum.`viewer_id`,oxum.`creative_id`,oxum.zone_id,date(oxum.`interval_start`))) AS UIMP GROUP BY db_date";
			
		$query = $this->db->query($SQL);
			
		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();
			foreach($stat_summary as $unique_data){

				if(isset($result[$unique_data['db_date']])){
						
					$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
					$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
					$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
					$result[$unique_data['db_date']]['SPEND']	= $temp[$unique_data['db_date']]['SPEND'];

					$result[$unique_data['db_date']]['UIMP']	= $unique_data['UIMP'];
					$result[$unique_data['db_date']]['UCLK']	= 0;

						
				}
				else
				{
					$result[$unique_data['db_date']]['IMP']		= 0;
					$result[$unique_data['db_date']]['CLK']		= 0;
					$result[$unique_data['db_date']]['CON']		= 0;
					$result[$unique_data['db_date']]['SPEND']	= 0;

					$result[$unique_data['db_date']]['UIMP']	= $unique_data['UIMP'];
					$result[$unique_data['db_date']]['UCLK']	= 0;
				}
					
					
			}
		}

		$temp = $result;

		//GET UNIQUE CLICKS

		$SQL = "SELECT db_date,count(db_date) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.clicks),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_banners as oxb ON (oxu.`creative_id`=oxb.bannerid) JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
			
		if($stat_type=="weekly")
		{
			$SQL .=" WHERE oxu.clicks>0 AND date(oxu.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL .=" WHERE oxu.clicks>0 AND date(oxu.date_time)=CURDATE()";
		}
			
		$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.`date_time`))";
			
		$SQL .=" UNION (SELECT date(oxuc.interval_start) as db_date,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_banners as oxb ON (oxuc.`creative_id`=oxb.bannerid) JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

		if($stat_type=="weekly")
		{
			$SQL .=" WHERE date(oxuc.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
		}else{
			$SQL .=" WHERE date(oxuc.interval_start)=CURDATE()";
		}
			
		$SQL .=" GROUP BY oxuc.`viewer_id`,oxuc.`creative_id`,oxuc.zone_id,date(oxuc.`interval_start`))) AS UCLK GROUP BY db_date";
			
		$query = $this->db->query($SQL);
			
		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();

			foreach($stat_summary as $unique_data){
				if(isset($result[$unique_data['db_date']])){
						
					$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
					$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
					$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
					$result[$unique_data['db_date']]['SPEND']	= $temp[$unique_data['db_date']]['SPEND'];

					$result[$unique_data['db_date']]['UIMP']	= $temp[$unique_data['db_date']]['UIMP'];
					$result[$unique_data['db_date']]['UCLK']	= $unique_data['UCLK'];

						
				}
				else
				{
					$result[$unique_data['db_date']]['IMP']		= 0;
					$result[$unique_data['db_date']]['CLK']		= 0;
					$result[$unique_data['db_date']]['CON']		= 0;
					$result[$unique_data['db_date']]['SPEND']	= 0;

					$result[$unique_data['db_date']]['UIMP']	= 0;
					$result[$unique_data['db_date']]['UCLK']	= $unique_data['UCLK'];
				}
					
					
			}
		}

		$temp = $result;
		//CALCULATE CTR


		$final_result 	= array();
		$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CTR"=>0.00,"UIMP"=>0,"UCLK"=>0);
		if(count($result) > 0){
			foreach($result as $key => $resObj){

				if($resObj['IMP'] > 0){
					$CTR		=	($resObj['CLK']/$resObj['IMP'])*100;
					$CTR		=	number_format($CTR,2,'.',',');
				}
				else
				{
					$CTR		= 	0.00;
				}

				$final_result[$key]	=	array(
						"IMP"=>$resObj['IMP'],
						"CON"=>$resObj['CON'],
						"CLK"=>$resObj['CLK'],
						"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
						"CTR"=>number_format($CTR,2,'.',','),
						"UIMP"=>$resObj['UIMP'],
						"UCLK"=>$resObj['UCLK']
				);

				$final_tot['IMP']	+=  $resObj['IMP'];
				$final_tot['CON']	+=  $resObj['CON'];
				$final_tot['CLK']	+=  $resObj['CLK'];
				$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");
				$final_tot['UIMP']	+=  $resObj['UIMP'];
				$final_tot['UCLK']	+=  $resObj['UCLK'];
			}
		}

		if($final_tot['IMP'] > 0)
			$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
		else
			$final_tot['CTR']	=  0.00;

		asort($final_result);

		$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

		return $out;
			
	}
		
	function get_top_performers($search_array)
	{
		$limit = $search_array['limit'];
		$result = array();
			
		$SQL = "SELECT oxcl.clientid AS CLIENTID,
				ifnull(sum( h.impressions ),0) AS IMP,
				oxc.campaignname as camp_name,
				ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
				ifnull(sum( h.`clicks` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			

		$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";

			
		$SQL .=" GROUP BY oxc.campaignid";
			

		$query = $this->db->query($SQL);
			

		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();
				
			foreach($stat_summary as $data){

				$result[$data['camp_name']]	=	array(
						"IMP"=>$data['IMP'],
						"CON"=>$data['CONVERSIONS'],
						"CLK"=>$data['CLICKS'],
						"CLIENTID"=>$data['CLIENTID'],
						"SPEND"=>0
				);
					
			}
		}

		//exit;

		$temp = $result;

		// GETTING IMPRESSIONS
			
		$SQL_BKT_IMP	=	"SELECT
				oxcl.clientid,
				oxc.campaignname as camp_name,
				ifnull(sum( odbm.`count` ),0) AS IMP
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";

			
		$SQL_BKT_IMP .=" GROUP BY oxc.campaignid";
			
		$query_imp = $this->db->query($SQL_BKT_IMP);


		if($query_imp->num_rows>0)
		{
			$stat_summary_imp =  $query_imp->result_array();
				
			foreach($stat_summary_imp as $data_imp){
					
				if(isset($temp[$data_imp['camp_name']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['camp_name']]['IMP'];
					$result[$data_imp['camp_name']]['IMP']	=$tot_imp;
				}else{
					$tot_imp		=	$data_imp['IMP'];
					$result[$data_imp['camp_name']]['IMP']	=	$tot_imp;
					$result[$data_imp['camp_name']]['CLK']	=	0;
					$result[$data_imp['camp_name']]['CON']	=	0;
					$result[$data_imp['camp_name']]['SPEND']	=	0;
				}
					
			}
		}
		$temp = $result;

		// GETTING CLICKS
			
		$SQL_BKT_CLK	=	"SELECT
				oxcl.clientid,
				oxc.campaignname as camp_name,
				ifnull(sum( odbc.`count` ),0) AS CLICKS
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";
			
		$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			
		if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				
			$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";

		}

		$SQL_BKT_CLK .=" GROUP BY oxc.campaignid";
			
		$query_clk = $this->db->query($SQL_BKT_CLK);


		if($query_clk->num_rows>0)
		{
			$stat_summary_clk =  $query_clk->result_array();
				
			foreach($stat_summary_clk as $data_clk){

				if(isset($temp[$data_clk['camp_name']]['CLK'])){
					$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['camp_name']]['CLK'];
					//echo $tot_clicks;

					$result[$data_clk['camp_name']]['CLK']	=$tot_clicks;
				}else{
					$tot_clicks		=	$data_clk['CLICKS'];
						
					$result[$data_clk['camp_name']]['IMP']	=	0;
					$result[$data_clk['camp_name']]['CLK']	=	$tot_clicks;
					$result[$data_clk['camp_name']]['CON']	=	0;
					$result[$data_clk['camp_name']]['SPEND']	=	0;
				}
					
			}
		}
			
		$temp = $result;

		// GETTING SPEND AMOUNT
			
		$SQL_BKT_SPEND	=	"SELECT
				oxcl.clientid,
				oxc.campaignname as camp_name,
				FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
				FROM ox_clients AS oxcl
				JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
				";

		$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";

			

		$SQL_BKT_SPEND .=" GROUP BY oxc.campaignid";
			
		$query_spend = $this->db->query($SQL_BKT_SPEND);


		if($query_spend->num_rows>0)
		{
			$stat_summary_spend =  $query_spend->result_array();

			foreach($stat_summary_spend as $data_spend){
				$tot_spend		=	$data_spend['SPEND'];
				if(isset($temp[$data_spend['camp_name']])){
					//$tot_spend		=	$data_spend['SPEND'] + 0;
					$result[$data_spend['camp_name']]['SPEND']	=$tot_spend;
				}else{
					$result[$data_spend['camp_name']]['IMP']		=	0;
					$result[$data_spend['camp_name']]['CLK']		=	0;
					$result[$data_spend['camp_name']]['CON']		=	0;
					$result[$data_spend['camp_name']]['SPEND']	=	$tot_spend;
						

				}
			}
		}
		$temp = $result;
			


		//CALCULATE CTR


		$final_result 	= array();
		$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00);
		if(count($result) > 0){
			foreach($result as $key => $resObj){

				if($resObj['IMP'] > 0){
					$CTR		=	($resObj['CLK']/$resObj['IMP'])*100;
					$CTR		=	number_format($CTR,2,'.',',');
				}
				else
				{
					$CTR		= 	0.00;
				}

				$final_result[$key]	=	array(
						"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
						"CTR"=>number_format($CTR,2,'.',','),
						"IMP"=>$resObj['IMP'],
						"CLK"=>$resObj['CLK']
				);
					
				$final_tot['IMP']	+=  $resObj['IMP'];
				$final_tot['CLK']	+=  $resObj['CLK'];
				$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");
			}
		}

		if($final_tot['IMP'] > 0)
			$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
		else
			$final_tot['CTR']	=  0.00;

		//$final_result = array_reverse($final_result,true);
		arsort($final_result);

		$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);



		return $out;
	}
		
	function get_statistics_for_top_banners($search_array,$start=0,$limit=false)
	{
		$limit = $search_array['limit'];
			
		$result = array();
			
		$campaigns	=	array();
		$banners	=	array();
			
		$campaigns_final = array();
		$banners_final = array();
			
		$SQL = "SELECT
				oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
				ifnull(sum( h.impressions ),0) AS IMP,
				ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
				ifnull(sum( h.`clicks` ),0) AS CLICKS
				FROM ox_campaigns AS oxc
				JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid";
			

		$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";

		$SQL .= " WHERE oxc.clientid!=1";
			

		$SQL .=" GROUP BY oxb.bannerid ";

			
		$query = $this->db->query($SQL);
			


		if($query->num_rows>0)
		{
			$stat_summary =  $query->result_array();

			foreach($stat_summary as $data){
					
				if(isset($campaigns[$data['campaignid']])){

						
					$campaigns[$data['campaignid']]	= array(
							"campaignid"=>$data['campaignid'],
							"campaignname"=>$data['campaignname'],
							"IMP"=>$data['IMP'] + $campaigns[$data['campaignid']]['IMP'],
							"CON"=>$data['CONVERSIONS'] + $campaigns[$data['campaignid']]['CON'],
							"CLK"=>$data['CLICKS'] + $campaigns[$data['campaignid']]['CLK']
					);

				}
				else
				{

					$campaigns[$data['campaignid']]	= array(
							"campaignid"=>$data['campaignid'],
							"campaignname"=>$data['campaignname'],
							"IMP"=>$data['IMP'],
							"CON"=>$data['CONVERSIONS'],
							"CLK"=>$data['CLICKS']
					);
				}

				//print_r($campaigns);exit;
				if(!isset($banners[$data['campaignid']][$data['bannerid']]) AND ($data['master_banner'] == -1 || $data['master_banner'] == -2 || $data['master_banner'] == -3)){
					$banners[$data['campaignid']][$data['bannerid']]	=	array();
				}



				if($data['master_banner'] == -1 || $data['master_banner'] == -2 || $data['master_banner'] == -3){
						
					$banners[$data['campaignid']][$data['bannerid']]['bannerid']		=  $data['bannerid'];
					$banners[$data['campaignid']][$data['bannerid']]['master_banner']	=  $data['master_banner'];
					$banners[$data['campaignid']][$data['bannerid']]['description']		=  $data['description'];
					$banners[$data['campaignid']][$data['bannerid']]['IMP']				=  $data['IMP'];
					$banners[$data['campaignid']][$data['bannerid']]['CON']				=  $data['CONVERSIONS'];
					$banners[$data['campaignid']][$data['bannerid']]['CLK']				=  $data['CLICKS'];
						
						
						
				}
				else
				{
						
					$banners[$data['campaignid']][$data['master_banner']]['IMP']				=  $data['IMP'] + $banners[$data['campaignid']][$data['master_banner']]['IMP'];
					$banners[$data['campaignid']][$data['master_banner']]['CON']				=  $data['CONVERSIONS'] + $banners[$data['campaignid']][$data['master_banner']]['CON'];
					$banners[$data['campaignid']][$data['master_banner']]['CLK']				=  $data['CLICKS'] + $banners[$data['campaignid']][$data['master_banner']]['CLK'];
						
				}

			}
				

				
		}


		$prev_campaigns = $campaigns;
		$prev_banners 	= $banners;
			
		// GET IMPRESSIONS
			
		$SQL_BKT_IMP	=	"SELECT
				oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
				ifnull(sum(odbm.count),0) as IMP
				FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
				";

		$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";

		$SQL_BKT_IMP .= " WHERE oxc.clientid!=1";

		$SQL_BKT_IMP .=" GROUP BY oxb.bannerid";

		$query_imp = $this->db->query($SQL_BKT_IMP);

		if($query_imp->num_rows>0)
		{
			$stat_summary_imp =  $query_imp->result_array();

				
			foreach($stat_summary_imp as $data1){
					
					
					
					
				if(isset($campaigns_final[$data1['campaignid']])){
						
					$tot_imp		=	$data1['IMP'] + $campaigns_final[$data1['campaignid']]['IMP'];
					$tot_cliks		=	0 + $campaigns_final[$data1['campaignid']]['CLK'];

						
						
					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $campaigns_final[$data1['campaignid']]['CON'],
							"CLK"=>$tot_cliks
					);
				}
				else
				{

					$tot_imp		=	$data1['IMP'] + $prev_campaigns[$data1['campaignid']]['IMP'];

						
					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $prev_campaigns[$data1['campaignid']]['CON'],
							"CLK"=>0 + $prev_campaigns[$data1['campaignid']]['CLK']
					);
				}

				// BANNERS SECTION
				if(!isset($banners_final[$data1['campaignid']][$data1['bannerid']]) AND ($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3)){
					$banners_final[$data1['campaignid']][$data1['bannerid']]	=	array();
				}


				if($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3){
						
					$tot_imp_banner			=	$data1['IMP']  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['IMP'];

					$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];
				}
				else
				{
					$tot_imp_banner			=	$data1['IMP']  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];

					$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
				}




			}
		}
			
			
		$prev_campaigns = $campaigns_final;

		$prev_banners 	= $banners_final;

		$campaigns_final	=	array();
		$banners_final	=	array();

		// GET CLICKS
			

		$SQL_BKT_CLK	=	"SELECT
				oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
				ifnull(sum(odbc.count),0) as CLICKS
				FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
				";


		$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";

		$SQL_BKT_CLK .= " WHERE oxc.clientid!=1";

		$SQL_BKT_CLK .=" GROUP BY oxb.bannerid";

		$query1 = $this->db->query($SQL_BKT_CLK);

		if($query1->num_rows>0)
		{
			$stat_summary1 =  $query1->result_array();

			// print_r($stat_summary1);
				
			foreach($stat_summary1 as $data1){
					
					
					
					
				if(isset($campaigns_final[$data1['campaignid']])){
						
					$tot_clicks		=	$data1['CLICKS'] + $campaigns_final[$data1['campaignid']]['CLK'];
						
					$tot_imp		=	0 + $campaigns_final[$data1['campaignid']]['IMP'];

					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $campaigns_final[$data1['campaignid']]['CON'],
							"CLK"=>$tot_clicks
					);
				}
				else
				{

					$tot_clicks		=	$data1['CLICKS'] + $prev_campaigns[$data1['campaignid']]['CLK'];
					$tot_imp		=	0 + $prev_campaigns[$data1['campaignid']]['IMP'];

					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $prev_campaigns[$data1['campaignid']]['CON'],
							"CLK"=>$tot_clicks
					);
				}

				// BANNERS SECTION
				if(!isset($banners_final[$data1['campaignid']][$data1['bannerid']]) AND ($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3)){
					$banners_final[$data1['campaignid']][$data1['bannerid']]	=	array();
				}


				if($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3){
						
					$tot_clicks_banner		=	$data1['CLICKS'] + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];
					$tot_imp_banner			=	0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['IMP'];

						
						
					$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;
						
						
						
				}
				else
				{
					$tot_clicks_banner		=	$data1['CLICKS'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
					$tot_imp_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];


						
					$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;
				}




			}

		}
			
		$prev_campaigns = $campaigns_final;
		$prev_banners 	= $banners_final;

		$campaigns_final	=	array();
		$banners_final		=	array();
			
		// GET SPEND OXM REPORT TABLE

		$SQL_BKT_SPEND	=	"SELECT
				oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
				FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
				FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
				";


		$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";

		$SQL_BKT_SPEND .= " WHERE oxc.clientid!=1";

		$SQL_BKT_SPEND .=" GROUP BY oxb.bannerid";

		$query1 = $this->db->query($SQL_BKT_SPEND);

		if($query1->num_rows>0)
		{
			$stat_summary1 =  $query1->result_array();
				
			foreach($stat_summary1 as $data1){
					
					
					
					
				if(isset($campaigns_final[$data1['campaignid']])){
						
					$tot_clicks		=	0 + $campaigns_final[$data1['campaignid']]['CLK'];
					$tot_imp		=	0 + $campaigns_final[$data1['campaignid']]['IMP'];

					if($tot_imp > 0){
						$CTR		=	($tot_clicks/$tot_imp)*100;
						$CTR		=	number_format($CTR,2,'.',',');
					}
					else
					{
						$CTR		= 	0.00;
					}
						
						
					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $campaigns_final[$data1['campaignid']]['CON'],
							"CLK"=>$tot_clicks,
							"SPEND"=>number_format($data1['SPEND'] + $campaigns_final[$data1['campaignid']]['SPEND'],2,'.',','),
							"CTR"=>number_format($CTR,2,'.',',')
					);
				}
				else
				{

					$tot_clicks		=	0 + $prev_campaigns[$data1['campaignid']]['CLK'];
					$tot_imp		=	0 + $prev_campaigns[$data1['campaignid']]['IMP'];

					if($tot_imp > 0){
						$CTR		=	($tot_clicks/$tot_imp)*100;
						$CTR		=	number_format($CTR,2,'.',',');
					}
					else
					{
						$CTR		= 	0.00;
					}
					$campaigns_final[$data1['campaignid']]	= array(
							"campaignid"=>$data1['campaignid'],
							"campaignname"=>$data1['campaignname'],
							"IMP"=>$tot_imp,
							"CON"=>0 + $prev_campaigns[$data1['campaignid']]['CON'],
							"CLK"=>$tot_clicks,
							"SPEND"=>number_format($data1['SPEND'],2,'.',','),
							"CTR"=>number_format($CTR,2,'.',',')
					);
				}

				// BANNERS SECTION
				if(!isset($banners_final[$data1['campaignid']][$data1['bannerid']]) AND ($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3)){
					$banners_final[$data1['campaignid']][$data1['bannerid']]	=	array();
				}


				if($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3){
						
					$tot_clicks_banner		=	0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];
					$tot_imp_banner			=	0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['IMP'];

					if($tot_imp_banner > 0){
						$CTR_banner		=	($tot_clicks_banner/$tot_imp_banner)*100;
						$CTR_banner		=	number_format($CTR_banner,2,'.',',');
					}
					else
					{
						$CTR_banner		= 	0.00;
					}
						
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CTR']				=  $CTR_banner;
					$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
					$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;

					$banners_final[$data1['campaignid']][$data1['bannerid']]['SPEND']			=  number_format($data1['SPEND'],2,'.',',');
						
						
						
				}
				else
				{
					$tot_clicks_banner		=	0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
					$tot_imp_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];

					if($tot_imp_banner > 0){
						$CTR_banner		=	($tot_clicks_banner/$tot_imp_banner)*100;
						$CTR_banner		=	number_format($CTR_banner,2,'.',',');
					}
					else
					{
						$CTR_banner		= 	0.00;
					}
						
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CTR']		=  $CTR_banner;
					$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
					$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;

					$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  number_format($data1['SPEND'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND'],2,'.',',');
				}




			}
		}
			
		$reports = array("reports_campaigns"=>$campaigns_final,"reports_banners"=>$banners_final);
			
			
		//print_r($reports);
			
		$banners_result = array();
		foreach($reports['reports_banners'] as $ban_id =>$report_banners)
		{
			foreach($report_banners as $ban_id => $banners_value)
			{
				$banners_value = array_reverse($banners_value,true);
				array_push($banners_result,$banners_value);
			}
		}
			
		//print_r($banners_result);
			
		arsort($banners_result);
			
		return $banners_result;
	}

}
