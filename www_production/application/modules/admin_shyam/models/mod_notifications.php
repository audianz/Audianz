<?php
class Mod_notifications extends CI_Model
{
        function __construct()
        {
            // Call the Model constructor
            parent::__construct();
	    //$this->load->model('admin/mod_dashboard','dashboard');
        }
        
	// To  Retreive the Banner Approval List
	 function get_ban_approval_list_count($where_arr=0)
     {
	 	$this->db->select('count(*) as total');
		$this->db->where('adminstatus',1);
		$this->db->where_in('master_banner',array(-1,-2,-3));
		$query = $this->db->get('ox_banners');

		//	$query	=	$this->db->get('oxm_newusers');
		
		if($query->num_rows() >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;	
		}
	 }

	 /* Retrieve Campaign List*/
    function retrieve_campaigns_count($duration='')
    {
		$today = date('Y-m-d');
        if($duration!='')
        {
                $this->db->select('count(*) as  total_camp');
				$this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');
				$this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid');
				$this->db->order_by('ox_campaigns.campaignid','desc');
				switch($duration)
				{
					case 'today':
						$this->db->where('date(ox_campaigns.updated)',$today);
						break;
				}
				$query = $this->db->get('ox_campaigns');
				return $query->result();
    	}
	}
	
	
		/* Retrieve Banners List */
    function get_banners_count($where_arr=0)
    {
			$master_in = array('-1','-2','-3');
			
            if($where_arr!=0 && is_array($where_arr))
            {
                    $this->db->where($where_arr);
            }
		
			$this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');	
			
			$this->db->join('ox_data_bkt_m', 'ox_data_bkt_m.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_bkt_c', 'ox_data_bkt_c.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_bkt_a', 'ox_data_bkt_a.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_summary_ad_hourly', 'ox_data_summary_ad_hourly.ad_id = ox_banners.bannerid','left');	
			
			$this->db->where_in('ox_banners.master_banner', $master_in);
			
			$this->db->where('ox_banners.adminstatus',0);
			
			$this->db->group_by('ox_banners.bannerid');
			
			$this->db->order_by('ox_banners.bannerid','desc');
		
            $this->db->order_by('ox_data_summary_ad_hourly.total_revenue','desc');
	
    		$query = $this->db->get('ox_banners');

    		return $query->num_rows();
    }	
	
		function get_zones_count($where_arr=0)
		{
			$today = date('Y-m-d');
				$master_in = array('-1','-2','-3');
				$this->db->where('date(ox_zones.updated)',$today);	
				$this->db->where_in('master_zone', $master_in);
				
				$query = $this->db->get('ox_zones');
				return $query->num_rows();
		}
		
		//Retrieve Unviewable Users Approvals 
		function get_pending_approvals($where_arr=0)
		{
			if($where_arr !='0' && is_array($where_arr))
			{
					$this->db->where($where_arr);
			}
			$this->db->select('TIMESTAMPDIFF(YEAR,date_added,NOW()) AS YEAR,TIMESTAMPDIFF(MONTH,date_added,NOW()) AS MONTH,TIMESTAMPDIFF(DAY,date_added,NOW()) AS DAY,TIMESTAMPDIFF(HOUR,date_added,NOW())-TIMESTAMPDIFF(DAY,date_added,NOW())*24 AS HOUR,TIMESTAMPDIFF(MINUTE,date_added,NOW())-TIMESTAMPDIFF(HOUR,date_added,NOW())*60 AS MINUTE');
			//$this->db->select('TIMESTAMPDIFF(MICROSECOND,date_added,NOW()) AS MICROSECONDS');
			$this->db->join('oxm_approval_notification','oxm_approval_notification.approval_user_id = oxm_newusers.user_id');
			$this->db->order_by('oxm_newusers.date_added','desc');
			$query	=	$this->db->get('oxm_newusers');
			return $query->result();
		}
		
		//Retreive last Duration of Added or Pending Advertiser
		function get_last_duration_app($where_type =0)
		{
			if($where_type !='0')
			{
			$today =	date("Y-m-d");

				$sql 	= "SELECT  ifnull(TIMESTAMPDIFF(YEAR,oxnu.date_added,NOW()),0) AS YEAR,ifnull(TIMESTAMPDIFF(MONTH,oxnu.date_added,NOW()),0) AS MONTH,ifnull(TIMESTAMPDIFF(DAY,oxnu.date_added,NOW()),0) AS DAY,ifnull(TIMESTAMPDIFF(HOUR,oxnu.date_added,NOW())-TIMESTAMPDIFF(DAY,oxnu.date_added,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,oxnu.date_added,NOW())-TIMESTAMPDIFF(HOUR,oxnu.date_added,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, oxnu.date_added, NOW()),0) AS MICROSECONDS";
				
				$sql	.= " FROM oxm_newusers as oxnu";
				$sql	.= " WHERE  oxnu.account_type= '".$where_type."'";
				//$sql	.= " UNION (SELECT ifnull(TIMESTAMPDIFF(YEAR,oxu.date_created,NOW()),0) AS YEAR,ifnull(TIMESTAMPDIFF(MONTH,oxu.date_created,NOW()),0) AS MONTH,ifnull(TIMESTAMPDIFF(DAY,oxu.date_created,NOW()),0) AS DAY,ifnull(TIMESTAMPDIFF(HOUR,oxu.date_created,NOW())-TIMESTAMPDIFF(DAY,oxu.date_created,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,oxu.date_created,NOW())-TIMESTAMPDIFF(HOUR,oxu.date_created,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, oxu.date_created, NOW()),0) AS MICROSECONDS";
				
				//$sql	.=  " FROM ox_users as oxu JOIN ox_accounts as oxa ON oxa.account_id=oxu.default_account_id";
				//$sql	.=  " WHERE oxa.account_type = '".$where_type."')";
				 $sql.= " ORDER BY oxnu.date_added DESC";
				$query 	=		$this->db->query($sql);
				return $query->result();
		}else{
			return FALSE;
		}
	}
	
	//Retreive Today Advertiser and Publisher List
	function get_last_duration_app_added($where_type = 0)
	{
		if($where_type !='0')
		{
			$sql	= " SELECT ifnull(TIMESTAMPDIFF(HOUR,oxu.date_created,NOW())-TIMESTAMPDIFF(DAY,oxu.date_created,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,oxu.date_created,NOW())-TIMESTAMPDIFF(HOUR,oxu.date_created,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, oxu.date_created, NOW()),0) AS MICROSECONDS";
				
				$sql	.=  " FROM ox_users as oxu JOIN ox_accounts as oxa ON oxa.account_id=oxu.default_account_id";
				$sql	.=  " WHERE oxa.account_type = '".$where_type."'";
				$sql .= " ORDER BY oxu.date_created DESC";
								
				$query 	=		$this->db->query($sql);
				return $query->result();
		}else{
				return FALSE;
		}
	}	
	
	//Retreive Last Entered Banner
	function get_last_duration_ban($where_type=0)
	{
			$ban_sql	=	"SELECT  ifnull(TIMESTAMPDIFF(YEAR,updated,NOW()),0) AS YEAR,ifnull(TIMESTAMPDIFF(MONTH,updated,NOW()),0) AS MONTH,ifnull(TIMESTAMPDIFF(DAY,updated,NOW()),0) AS DAY,ifnull(TIMESTAMPDIFF(HOUR,updated,NOW())-TIMESTAMPDIFF(DAY,updated,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,updated,NOW())-TIMESTAMPDIFF(HOUR,updated,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, updated, NOW()),0) AS MICROSECONDS";
			$ban_sql .= " FROM ox_banners WHERE  master_banner IN (-1,-2,-3)";
			if($where_type =='Approved')
			{
				$ban_sql .=" AND adminstatus=0";
			}else if($where_type =='Pending')
			{
				$ban_sql .=" AND adminstatus=1";
			}
			$ban_sql .= " ORDER  BY  updated DESC";
			$ban_query	=	$this->db->query($ban_sql);
			return $ban_query->result();
	}
	
	//Retreive Last Payment Banner
	function get_pending_payment_list()
	{
		    $pay_sql = "SELECT ifnull(TIMESTAMPDIFF(YEAR,date,NOW()),0) AS YEAR,ifnull(TIMESTAMPDIFF(MONTH,date,NOW()),0) AS MONTH,ifnull(TIMESTAMPDIFF(DAY,date,NOW()),0) AS DAY,ifnull(TIMESTAMPDIFF(HOUR,date,NOW())-TIMESTAMPDIFF(DAY,date,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,date,NOW())-TIMESTAMPDIFF(HOUR,date,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, date, NOW()),0) AS MICROSECONDS";
			$pay_sql .=" FROM oxm_admin_payment WHERE status=0";
			$pay_sql  .= " ORDER BY date DESC";	
			
			$pay_query	=	 $this->db->query($pay_sql);
			
			if($pay_query->result())
			{
				return $pay_query->result();
			}else{
				return FALSE;
			}
	}
	
	//Retreive Last Campaign and Zones Duration
	 function get_last_duration_camp_zones($type='',$tbl_name='')
	 {
	 		if($type!='' && $tbl_name!='')
			{
					$sql = "SELECT ifnull(TIMESTAMPDIFF(HOUR,updated,NOW())-TIMESTAMPDIFF(DAY,updated,NOW())*24,0) AS HOUR,ifnull(TIMESTAMPDIFF(MINUTE,updated,NOW())-TIMESTAMPDIFF(HOUR,updated,NOW())*60,0) AS MINUTE,ifnull(TIMESTAMPDIFF(MICROSECOND, updated, NOW()),0) AS MICROSECONDS";
					$sql .=	" FROM ".$tbl_name."";
					if($type=="zones")
					{
							$sql .="  WHERE master_zone IN (-1,-2,-3)";
					}
					$sql .=" ORDER  BY updated DESC LIMIT 1";
					$query = $this->db->query($sql);
					if($query->result())
					{
							return $query->result();
					}else{
							return FALSE;
					}
			
			}else{
					return FALSE;
			}	
	 }
	 
	 function get_notify($type='')
	 {
	 		if($type !='')
			{
	 		$today = date('Y-m-d');
			//Today Pending Advertisers
$this->load->model('admin/mod_dashboard','dashboard');
			$pending_ad_list_count		=		$this->dashboard->get_users_count('Advertiser','Pending');
			$total_pending_ad_list_count		=		$pending_ad_list_count[0]->total;
			
			//Total Pending Publishers
			$pending_pub_list_count		=		$this->dashboard->get_users_count('Publisher','Pending');
			$total_pending_pub_list_count		=		$pending_pub_list_count[0]->total;
			
			//Total Pending Banners
			$pending_ban_list_count	=	$this->get_ban_approval_list_count();
			$total_pending_ban_list_count		=		$pending_ban_list_count[0]->total;
			
			//Total Campaigns Added today
			$today_campaign_count	=		$this->retrieve_campaigns_count('today');
			$today_campaign_count						=		$today_campaign_count[0]->total_camp;
			
			//Total Banners Added Today
			$where_arr	=		array('date(ox_banners.updated)'=>$today);
			$today_banner_count =		$this->get_banners_count($where_arr);
			
			//Total Zones Added Today
			$today_zones_count	=		$this->get_zones_count();
			

			
			//Advertiser   Registered today
			$approved_ad_list_count_today	=		$this->dashboard->get_users_count_today('Advertiser');
			$approved_ad_list_count_today		=		$approved_ad_list_count_today[0]->total;
				
			//Publisher   Registered today
			$approved_pub_list_count_today	=	 	$this->dashboard->get_users_count_today('Publisher');		
			$approved_pub_list_count_today		=		$approved_pub_list_count_today[0]->total;
			
			//Payment Yet To Be Approved
			$pending_payment_list	=	 $this->get_pending_payment_list();
			if($pending_payment_list !=FALSE)
			{						
				$pending_payment_list_count	=		count($pending_payment_list);
			}else{
				$pending_payment_list_count =0;
			}

			//Admin Approval Advertiser Not Viewed
			$where_arr	=	array('oxm_newusers.account_type'=>'ADVERTISER','oxm_approval_notification.read_status'=>0);
			$adv_unviewed_list_count		=		$this->get_pending_approvals($where_arr);
			$total_unviewed_adv	=	count($adv_unviewed_list_count);
			
			//Admin Approval Publisher Not Viewed
			$where_arr	=	array('oxm_newusers.account_type'=>'TRAFFICKER','oxm_approval_notification.read_status'=>0);
			$pub_unviewed_list_count		=		$this->get_pending_approvals($where_arr);
			$total_unviewed_pub	=	count($pub_unviewed_list_count);
			
			if($type =='Approval')
			{
				
			//Approval Notifications
			$total_notify_approval 	=		$total_pending_ad_list_count + $total_pending_pub_list_count+$total_pending_ban_list_count+												$pending_payment_list_count+$total_unviewed_adv+$total_unviewed_pub;
			return $total_notify_approval;
			}else if($type =='Today')
			{
			//Today Notifications
			$total_notify_today	=	 	$today_campaign_count+$today_banner_count+$today_zones_count+$approved_ad_list_count_today+$approved_pub_list_count_today;
			return $total_notify_today;
			}else{
				return FALSE;
			}	
		}else{
				return FALSE;
		}
			
			
	 }
}
