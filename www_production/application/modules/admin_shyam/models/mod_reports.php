<?php
class Mod_reports extends CI_Model 
{
	 function __construct()
    	{
        	// Call the Model constructor
        	parent::__construct();
    	} 


	function list_zones($where) 
		{
			$this->db->select('zonename,zoneid');
			
			$this->db->where($where);	
			
			$this->db->order_by('zoneid','ASC');
			
			$query = $this->db->get('ox_zones');
		
			return $query->result();			
		}
		
	function list_all_website() 
		{
			$this->db->select('name,affiliateid');
			
			$this->db->order_by('affiliateid','ASC');
			
			$query = $this->db->get('ox_affiliates');
		
			return $query->result();			
		}
	
	function list_all_advertiser() 
		{
			$this->db->select('clientname,clientid');	
			
			$this->db->order_by('clientid','ASC');
			
			$query = $this->db->get('ox_clients');
		
			return $query->result();			
		}
	function get_zone($zonelimit) 
		{
			$this->db->select('*');	
			
			$this->db->where('zoneid',$zonelimit);
			
			$query = $this->db->get('ox_zones');
			
			$temp = $query->row();
		
			return $temp->zonename;			
		}
	function adhourly_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select h.date_time,sum(h.impressions) as imp,sum(h.clicks) as cli,sum(h.conversions) as conv,h.total_revenue as rev,z.zoneid,z.affiliateid,a.affiliateid,h.zone_id from ox_affiliates a join ox_zones z join ox_data_summary_ad_hourly h  on z.affiliateid=a.affiliateid AND h.zone_id=z.zoneid where date(h.date_time) BETWEEN '$startdate' AND '$enddate' AND (z.zoneid='$zone' or z.master_zone='$zone') group by date(h.date_time)");
			
			return $qry;
			
		}
		
		function totalrevenue_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select sum(publisher_amount) as amount from oxm_report  where date BETWEEN '$startdate' AND '$enddate' AND zoneid IN (SELECT zoneid
FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone'))");
			
			return $qry;
		}
		
		function bucket_imp_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select sum(count) as counts,m.zone_id,m.interval_start,z.zoneid,z.affiliateid from ox_data_bkt_m m join ox_zones z on z.zoneid=m.zone_id where z.zoneid=m.zone_id and date(m.interval_start) BETWEEN '$startdate' AND '$enddate' AND m.zone_id IN (SELECT zoneid
FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone')) GROUP BY date(m.interval_start)");
			
			return $qry;
		}
		function bucket_cli_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select sum(count) as counts1,cl.creative_id,cl.interval_start,z.zoneid,z.affiliateid from ox_data_bkt_c cl join ox_zones z on  z.zoneid=cl.zone_id where date(cl.interval_start)BETWEEN '$startdate' AND '$enddate' AND  cl.zone_id IN (SELECT zoneid
FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone')) GROUP BY date(cl.interval_start)");
			
			return $qry;
		}
		
		
		function adhourly_c_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select date(s.date_time) AS date_time, sum(s.impressions) AS impressions, sum(s.clicks) AS clicks, s.country,z.zoneid,z.affiliateid,a.affiliateid,s.zone_id from ox_affiliates a join ox_zones z join ox_stats_country s on z.affiliateid=a.affiliateid AND s.zone_id=z.zoneid where date(s.date_time) BETWEEN '$startdate' AND '$enddate' AND (z.zoneid='$zone' or z.master_zone='$zone') group by date(s.date_time),s.country");
			
			return $qry;
		}
		
		function bucket_imp_c_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select sum(count) AS count, m.creative_id,date(m.interval_start) AS interval_start,m.zone_id,z.zoneid,m.country from ox_data_bkt_country_m m join ox_zones z on  z.zoneid=m.zone_id and (z.zoneid='$zone' or z.master_zone='$zone') where  date(m.interval_start) BETWEEN '$startdate' AND '$enddate' GROUP BY date(m.interval_start),m.country");
			
			return $qry;
		}
		
		function bucket_cli_c_data($zone,$startdate,$enddate)
		{
			$qry=mysql_query("select sum(count) AS count, cl.creative_id, date(cl.interval_start) AS interval_start,z.zoneid,cl.country from ox_data_bkt_country_c cl join ox_zones z on cl.zone_id=z.zoneid and (z.zoneid='$zone' or z.master_zone='$zone') where  date(cl.interval_start)BETWEEN '$startdate' AND '$enddate' GROUP BY date(cl.interval_start), cl.country");
			
			return $qry;
		}
			
}