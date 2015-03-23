<?php
class Mod_smaato_stats extends CI_Model {
	
		function get_start_date(){
			$query = $this->db->query("SELECT date(`date_created`) as start_date FROM `ox_users` ORDER BY `date_created` ASC LIMIT 0,1");
			if($query->num_rows > 0)
			 {
				 $t = $query->result_array();
				 return $t[0]['start_date'];
			 }
			 else
			 {
			 	return date("Y-m-d");
			 }	 
		}
	
		function get_stats($filter,$start=0,$limit=false)
		{
			$camp_sql = "
			SELECT
				 w.win_count,
				 w.win_price,
				 IFNULL(COUNT(req.id),0) as req_count,
				 DATE(req.datetime) as dbdate,
				 r.res_count,
				 r.bid_price,
				 r.sbid_price,
				 r.admin_price
			FROM djax_smaato_bid_request as req
			LEFT JOIN  
			(
				SELECT date(res.datetime) as rdate, res.adid, IFNULL(COUNT(res.response_id),0) as res_count, IFNULL(SUM(res.advertiser_bid_price),0) as bid_price, IFNULL(SUM(res.smaato_bid_price),0) as sbid_price, IFNULL(SUM(res.admin_rev),0) as admin_price FROM aff_smaato_response as res WHERE date(res.datetime) BETWEEN '".$filter['from_date']."' AND '".$filter['to_date']."' GROUP BY date(res.datetime)
			) as r ON r.rdate=DATE(req.datetime)
			LEFT JOIN  
			(
				SELECT date(win.datetime)as wdate, win.adid, COUNT(win.id) as win_count, SUM(win.price) as win_price FROM aff_smaato_win_notice as win WHERE date(win.datetime) BETWEEN '".$filter['from_date']."' AND '".$filter['to_date']."' GROUP BY date(win.datetime)
			) as w ON w.wdate=DATE(req.datetime)
			
			WHERE date(req.datetime) BETWEEN '".$filter['from_date']."' AND '".$filter['to_date']."' GROUP BY date(req.datetime)";
			
			
			$query	=	$this->db->query($camp_sql); 	//echo $this->db->last_query();exit;	
			
			if($query->num_rows() >0)
			{        
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		}
		
		function get_details($date)
		{
			$camp_sql = "
			SELECT
				 ban.client_name,
				 ban.camp_name,
				 ban.ban_name,
				 w.win_count,
				 w.win_price,
				res.adid, IFNULL(COUNT(res.response_id),0) as res_count, IFNULL(SUM(res.advertiser_bid_price),0) as bid_price, IFNULL(SUM(res.smaato_bid_price),0) as sbid_price, IFNULL(SUM(res.admin_rev),0) as admin_price
			FROM aff_smaato_response as res
			LEFT JOIN
			(
				SELECT oxcl.clientname as client_name, oxc.campaignname as camp_name, oxb.description as ban_name, oxb.bannerid
				FROM ox_banners as oxb
				LEFT JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid
				LEFT JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid
			) as ban ON ban.bannerid=res.adid
			LEFT JOIN  
			(
				SELECT win.adid, COUNT(win.id) as win_count, SUM(win.price) as win_price FROM aff_smaato_win_notice as win WHERE DATE(win.datetime)='".$date."' GROUP BY win.adid
			) as w ON w.adid=res.adid
			WHERE res.adid>0 AND DATE(res.datetime)='".$date."' GROUP BY res.adid";
			
			$query	=	$this->db->query($camp_sql);
			
			//echo $this->db->last_query();exit;	
			
			if($query->num_rows() >0)
			{        
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		}	
		
}
