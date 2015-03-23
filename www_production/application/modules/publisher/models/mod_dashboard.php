<?php
class Mod_dashboard extends CI_Model 
{
		function __construct()
        {
            // Call the Model constructor
            parent::__construct();
        }
        /* Zone Count */
        function get_zone_count($pubid=0)
        {
            if($pubid!=0)
            {
                $this->db->where("affiliateid",$pubid);
            }
            $this->db->where_in("master_zone",array('-1','-2','-3'));

            $this->db->select('count(zoneid) as countzones');

            $query = $this->db->get('ox_zones');
            
            //echo $this->db->last_query();exit;
            
            $result = $query->result();
            
            if($result[0]->countzones!=0)
            {
            	return $result[0]->countzones;
            }
            else
            {
            	return 0;
            }
        }
        
        /* Linked Campaigns Count */
        function get_linked_campaigns($pubid=0)
        {
            if($pubid!=0)
            {
                $this->db->where("ox_zones.affiliateid",$pubid);
            }
            
            $this->db->select('ox_banners.campaignid');
	
	    $this->db->join('ox_ad_zone_assoc','ox_ad_zone_assoc.zone_id=ox_zones.zoneid');
	    
	    $this->db->join('ox_banners','ox_banners.bannerid=ox_ad_zone_assoc.ad_id');		
	    
	    $this->db->group_by('ox_banners.campaignid');

            $query = $this->db->get('ox_zones');
            
            //echo $this->db->last_query();exit;
            
            $rows = $query->num_rows();
            
            //$result = $query->result();
            
            if($rows>0)
            {
            	return $rows;
            }
            else
            {
            	return 0;
            }
        
        }
        
        /* Linked Banners Count */
        function get_linked_banners($pubid=0)
        {
        	if($pubid!=0)
            	{
                	$this->db->where("affiliateid",$pubid);
            	}

	    	$this->db->where_in("ox_zones.master_zone",array('-1','-2','-3'));
            
            	$this->db->select('count(ox_ad_zone_assoc.ad_id) as countlinkbanner');
	
	    	$this->db->join('ox_ad_zone_assoc','ox_ad_zone_assoc.zone_id=ox_zones.zoneid');	

            	$query = $this->db->get('ox_zones');
            
            	//echo $this->db->last_query();exit;
            
            	$result = $query->result();
            
            	if($result[0]->countlinkbanner!=0)
            	{
            		return $result[0]->countlinkbanner;
            	}
            	else
            	{
            		return 0;
            	}
        }
        
        /* Revenue on Day Basis */
        function revenue($where_arr=0,$pubid=0)
        {
	    if($where_arr!=0)
		    {
                $this->db->where("date",$where_arr);
            }
			            
            if($pubid!=0)
            {
                $this->db->where("publisherid",$pubid);
            }

            $this->db->select('sum(oxm_report.publisher_amount) as today_rev');

            $this->db->join('oxm_report','oxm_report.zoneid = ox_zones.zoneid');

            $query = $this->db->get('ox_zones');
            
            //echo $this->db->last_query();exit;

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
        /* Unpaid Earnings */
        function unpaid_earnings($where_arr=0)
        {
			if($where_arr!=0)
            {
                 $this->db->where("oxm_admin_payment.publisherid",$where_arr);
                 
                 $this->db->where("oxm_report.publisherid",$where_arr);
            }

            $this->db->select('sum(oxm_report.publisher_amount) as pub_amt, sum(oxm_admin_payment.amount) as adm_amt');

            $this->db->join('oxm_report','oxm_report.publisherid = oxm_admin_payment.publisherid');
            
            $this->db->where('oxm_admin_payment.status','1');

            $query = $this->db->get('oxm_admin_payment');
            
            //echo $this->db->last_query();exit;

            return $query->result();
	    }
	    /* Last Issued Payment */
	    function last_issued_payment($where_arr=0)
	    {
		if($where_arr!=0)
            	{
                	 $this->db->where("oxm_admin_payment.publisherid",$where_arr);
            	}

            	$this->db->where('oxm_admin_payment.status','1');
            	
            	$this->db->order_by('oxm_admin_payment.clearing_date','DESC');

            	$query = $this->db->get('oxm_admin_payment');
            
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
		/* Unique Impressions */
		function unique_impressions($where_aff=0,$where_date=0)
		{
		//print_r($where_arr);exit;
		if($where_aff!=0&&$where_date!=0)
		    {
		    	$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id and z.affiliateid= '".$where_aff."' AND month(u.date_time) = '".$where_date."' UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_m m,ox_zones z where z.zoneid=m.zone_id and z.affiliateid= '".$where_aff."' AND month(m.interval_start) = '".$where_date."'");		    	
					
		    }
		    else
		    {
		    	$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_m m,ox_zones z where z.zoneid=m.zone_id");
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
		
		function get_unique_imp_nocount($where_arr=0)
        {
            if($where_arr!=0)
            {
                $this->db->where($where_arr);
            }

            $this->db->select('ox_zones.affiliateid,ox_zones.zoneid,ox_data_bkt_unique_m.zone_id,ox_data_bkt_unique_m.viewer_id,ox_data_bkt_unique_m.creative_id');

            $this->db->join('ox_data_bkt_unique_m','ox_data_bkt_unique_m.zone_id = ox_zones.zoneid');

            $this->db->group_by(array('ox_data_bkt_unique_m.viewer_id,ox_data_bkt_unique_m.creative_id,ox_data_bkt_unique_m.zone_id,date(ox_data_bkt_unique_m.interval_start)'));

            $query = $this->db->get('ox_zones');
            
            return $query->num_rows();
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
		function unique_clicks($where_aff=0,$where_date=0)
		{
		    if($where_aff!=0&&$where_date!=0)
		    {
					$query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id and z.affiliateid= '".$where_aff."' AND month(u.date_time) = '".$where_date."' UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_c m,ox_zones z where z.zoneid=m.zone_id and z.affiliateid= '".$where_aff."' AND month(m.interval_start) = '".$where_date."'");
					
		    }
		    else
		    {
		        $query = mysql_query("select u.viewer_id,u.zone_id,month(u.date_time) from ox_unique u,ox_zones z where z.zoneid=u.zone_id UNION select m.viewer_id,m.zone_id,month(m.interval_start) from ox_data_bkt_unique_c m,ox_zones z where z.zoneid=m.zone_id");
		    }
		    
		    //echo $this->db->last_query();exit;
		    
		    return mysql_num_rows($query);
		}
		
		function get_unique_click_nocount($where_arr=0)
		{
			if($where_arr!=0)
		    {
		        $this->db->where($where_arr);
		    }

		    $this->db->select('ox_zones.affiliateid,ox_zones.zoneid,ox_data_bkt_unique_c.zone_id,ox_data_bkt_unique_c.viewer_id,ox_data_bkt_unique_c.creative_id');

		    $this->db->join('ox_data_bkt_unique_c','ox_data_bkt_unique_c.zone_id = ox_zones.zoneid');

		    $this->db->group_by(array('ox_data_bkt_unique_c.viewer_id,ox_data_bkt_unique_c.creative_id,ox_data_bkt_unique_c.zone_id,date(ox_data_bkt_unique_c.interval_start)'));

		    $query = $this->db->get('ox_zones');
		    
		    return $query->num_rows();
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
		
		/* Publisher Revenue */
		function publisher_revenue($where_arr=0)
		{
			if($where_arr!=0)
            {
                $this->db->where($where_arr);
            }

            $this->db->select('sum(oxm_report.publisher_amount) as pubrev,ox_zones.zoneid,oxm_report.zoneid,oxm_report.publisher_amount,oxm_report.date');

            $this->db->join('oxm_report','oxm_report.zoneid = ox_zones.zoneid');
            
            $query = $this->db->get('ox_zones');
            
            //echo $this->db->last_query();exit;
            
            return $query->result();
		}
		
		
		// Get Last Six Month Revenue
		function get_past_month_revenues($where_user)
		{
			$query = $this->db->query("SELECT sum(oxm_report.publisher_amount) as month_rev, MONTH(date) as month, YEAR(date) as year FROM ox_zones JOIN oxm_report ON oxm_report.zoneid = ox_zones.zoneid WHERE ox_zones.affiliateid=".$where_user." and date >= date_sub(date, INTERVAL 6 MONTH) GROUP BY MONTH(date);");
            
            return $query->result();
		}
		
		
		/*Getting IMpression */
		function get_impressions($data)
		{
			$SQL=" select sum(impressions) as imp from ox_data_summary_ad_hourly";
			$SQL	.=	" WHERE month(date_time)=$data";
			//print_r($SQL);exit;
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
		
		
		
		
	public function get_date_report_pub($acc_id,$search_array){
	
	$result = array();
	$zones	=array();
			
			$SQL = "SELECT oxmu.accountid,oxa.affiliateid,oxz.zonename,zoneid,master_zone,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid";
						
								
											
		  if(count($search_array) > 0 AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON (h.zone_id = oxz.zoneid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON (h.zone_id = oxz.zoneid)";
			}
			
			$SQL .=" WHERE oxmu.accountid=".$acc_id." ";
			$SQL .= " GROUP BY oxz.zoneid";
			
				
			$query=$this->db->query($SQL);						
				
				
				 
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 
				 //print_r($stat_summary);
				
				 foreach($stat_summary as $data){
					
					
					
					if(!isset($zones[$data['zoneid']]) AND $data['master_zone']=="-1" OR $data['master_zone']=="-2" OR $data['master_zone']=="-3"){
						$zones[$data['zoneid']]	=	array();
					}
					
					if($data['master_zone']=="-1" OR $data['master_zone']=="-2" OR $data['master_zone']=="-3"){
						$zones[$data['zoneid']]['IMP']			=	$data['IMP'];
						$zones[$data['zoneid']]['CON']			=	$data['CONVERSIONS'];
						$zones[$data['zoneid']]['CLK']			=	$data['CLICKS'];
						$zones[$data['zoneid']]['zonename']		=	$data['zonename'];
						$zones[$data['zoneid']]['affiliateid']	=	$data['affiliateid'];
						$zones[$data['zoneid']]['zoneid']		=	$data['zoneid'];
						$zones[$data['zoneid']]['master_zone']	=	$data['master_zone'];
						$zones[$data['zoneid']]['accountid']	=	$data['accountid'];
					}
					else
					{
						$zones[$data['master_zone']]['IMP']			+=	$data['IMP'];
						$zones[$data['master_zone']]['CON']			+=	$data['CONVERSIONS'];
						$zones[$data['master_zone']]['CLK']			+=	$data['CLICKS'];
					}
					
				 }
			  } 
				
			$temp = $zones;
			
			
			// GET IMPRESSIONS
			
			$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,oxz.zoneid,master_zone ,
										ifnull(sum( odbm.`count` ),0) AS IMP
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										
										 ";
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT .=  " LEFT JOIN ox_data_bkt_m AS odbm ON (odbm.zone_id = oxz.zoneid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT .=  "	LEFT JOIN ox_data_bkt_m AS odbm ON (odbm.zone_id = oxz.zoneid)";
			}						
				$SQL_BKT .= " where oxmu.accountid='".$acc_id."'";
				$SQL_BKT .= " GROUP BY oxz.zoneid";
				
				$query=$this->db->query($SQL_BKT);
											
			   if($query->num_rows>0)
				{
					 $stat_summary1 =  $query->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					
					 if(!isset($zones[$data1['zoneid']]) AND ($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3)){
							$zones[$data1['zoneid']]	=	array();
					  }
						
						
						if($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3){
					
							$tot_imp_zone			=	$data1['IMP']  + $temp[$data1['zoneid']]['IMP'];
						
							$zones[$data1['zoneid']]['zoneid']			=  $data1['zoneid'];	
							$zones[$data1['zoneid']]['master_zone']		=  $data1['master_zone'];
							$zones[$data1['zoneid']]['zonename']		=  $data1['zonename'];
							$zones[$data1['zoneid']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['zoneid']]['CON']				=  0 + $temp[$data1['zoneid']]['CON'];
							$zones[$data1['zoneid']]['CLK']				=  0 + $temp[$data1['zoneid']]['CLK'];
					   } 
					   else
					   {
					 	 	$tot_imp_zone			=	$data1['IMP']  + $zones[$data1['master_zone']]['IMP'];
							
							$zones[$data1['master_zone']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['master_zone']]['CON']				=  0 + $zones[$data1['master_zone']]['CON'];
							$zones[$data1['master_zone']]['CLK']				=  0 + $zones[$data1['master_zone']]['CLK'];
					  }	
					
					
		        	 }
				}
				
				$temp = $zones;
				
				//GET CLICKS
				
				$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,oxz.zoneid,master_zone,
										ifnull(sum( odbc.`count` ),0) AS CLICKS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										
										 ";
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT .=  " LEFT JOIN ox_data_bkt_c AS odbc ON (odbc.zone_id = oxz.zoneid AND date(odbc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT .=  " LEFT JOIN ox_data_bkt_c AS odbc ON (odbc.zone_id = oxz.zoneid)";
			}						
				$SQL_BKT .= " where oxmu.accountid='".$acc_id."'";
				$SQL_BKT .= " GROUP BY oxz.zoneid";
				
				$query=$this->db->query($SQL_BKT);
											
			   if($query->num_rows>0)
				{
					 $stat_summary1 =  $query->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					  if(!isset($zones[$data1['zoneid']]) AND ($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3)){
							$zones[$data1['zoneid']]	=	array();
					  }
						
						
						if($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3){
					
							$tot_clicks				=	$data1['CLICKS'] + $temp[$data1['zoneid']]['CLK'];
							$tot_imp_zone			=	0  + $temp[$data1['zoneid']]['IMP'];
						
							$zones[$data1['zoneid']]['zoneid']			=  $data1['zoneid'];	
							$zones[$data1['zoneid']]['master_zone']		=  $data1['master_zone'];
							$zones[$data1['zoneid']]['zonename']		=  $data1['zonename'];
							$zones[$data1['zoneid']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['zoneid']]['CON']				=  0 + $temp[$data1['zoneid']]['CON'];
							$zones[$data1['zoneid']]['CLK']				=  $tot_clicks;
					   } 
					   else
					   {
					 	 	$tot_clicks				=	$data1['CLICKS'] + $zones[$data1['master_zone']]['CLK'];
							$tot_imp_zone			=	0  				 + $zones[$data1['master_zone']]['IMP'];
							
							$zones[$data1['master_zone']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['master_zone']]['CON']				=  0 + $zones[$data1['master_zone']]['CON'];
							$zones[$data1['master_zone']]['CLK']				=  $tot_clicks;
					  }	
					
					 }
				}
			
			$temp = $zones;
			
			// GET REVENUE
			
			$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,oxz.zoneid,master_zone, 
										FORMAT(ifnull(sum( oxmr.`publisher_amount` ),0),2) AS SPEND
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										
										 ";
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT .=  " LEFT JOIN oxm_report AS oxmr ON (oxmr.zoneid = oxz.zoneid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT .=  " LEFT JOIN oxm_report AS oxmr ON (oxmr.zoneid = oxz.zoneid)";
			}						
				$SQL_BKT .= " where oxmu.accountid='".$acc_id."'";
				$SQL_BKT .= " GROUP BY oxz.zoneid";
				
				$query=$this->db->query($SQL_BKT);
											
			   if($query->num_rows>0)
				{
					 $stat_summary1 =  $query->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					
					 if(!isset($zones[$data1['zoneid']]) AND ($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3)){
							$zones[$data1['zoneid']]	=	array();
					  }
						
						
						if($data1['master_zone'] == -1 || $data1['master_zone'] == -2 || $data1['master_zone'] == -3){
					
							$tot_clicks				=	0 + $temp[$data1['zoneid']]['CLK'];
							$tot_imp_zone			=	0  + $temp[$data1['zoneid']]['IMP'];
							
							if($tot_imp_zone > 0){
								$CTR_zone		=	($tot_clicks/$tot_imp_zone)*100;
								$CTR_zone		=	number_format($CTR_zone,2,'.',',');
							}
							else
							{
								$CTR_zone		= 	0.00;
							}
						
							$zones[$data1['zoneid']]['zoneid']			=  $data1['zoneid'];	
							$zones[$data1['zoneid']]['master_zone']		=  $data1['master_zone'];
							$zones[$data1['zoneid']]['zonename']		=  $data1['zonename'];
							$zones[$data1['zoneid']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['zoneid']]['CON']				=  0 + $temp[$data1['zoneid']]['CON'];
							$zones[$data1['zoneid']]['CLK']				=  $tot_clicks;
							$zones[$data1['zoneid']]['SPEND']			=   number_format($data1['SPEND'],2,'.',',');
							$zones[$data1['zoneid']]['CTR']				=  $CTR_zone;
					   } 
					   else
					   {
					 	 	$tot_clicks				=	0 + $zones[$data1['master_zone']]['CLK'];
							$tot_imp_zone			=	0 + $zones[$data1['master_zone']]['IMP'];
							
							
							if($tot_imp_zone > 0){
								$CTR_zone		=	($tot_clicks/$tot_imp_zone)*100;
								$CTR_zone		=	number_format($CTR_zone,2,'.',',');
							}
							else
							{
								$CTR_zone		= 	0.00;
							}
							
							$zones[$data1['master_zone']]['IMP']		=  $tot_imp_zone;
							$zones[$data1['master_zone']]['CON']		=  0 + $zones[$data1['master_zone']]['CON'];
							$zones[$data1['master_zone']]['CLK']		=  $tot_clicks;
							$zones[$data1['master_zone']]['SPEND']		=  number_format($data1['SPEND']+ $zones[$data1['master_zone']]['SPEND'],2,'.',',');
					 		$zones[$data1['master_zone']]['CTR']				=  $CTR_zone;
					  }	
					
					
															
					 }
				}
			
				return $zones;
	}
	
	function  get_monthly_report_for_publisher($search_array,$stat_type=FALSE)
	{
			 
			
			//print_r($search_array); 
			if($stat_type)
			{			
			$result = array();  
			
			$SQL = "SELECT oxmu.accountid,oxa.affiliateid,oxz.zonename, 
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS,
						month(h.date_time) as db_month
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
						";
			

				$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON h.zone_id = oxz.zoneid";

			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
			
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND month(h.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(h.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
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
															"REV"=>0
													);
			
				 }
			  }
	
			//exit;
				
			$temp = $result;
		
			
			// GETTING IMPRESSIONS
			
			$SQL_BKT_IMP	=	"SELECT 
										oxmu.accountid, 
										month(odbm.interval_start) as db_month,
										ifnull(sum( odbm.`count` ),0) AS IMP
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";

				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.zone_id = oxz.zoneid";

			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_IMP .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND month(odbm.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL_BKT_IMP .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'AND date(odbm.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
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
						$result[$data_imp['db_month']]['REV']	=	0;
					}
					
					}
				}
				
				$temp= $result;
			
			// GETTING CLICKS
			
			$SQL_BKT_CLK	=	"SELECT 
										oxmu.accountid, 
										month(odbc.interval_start) as db_month,
										ifnull(sum( odbc.`count` ),0) AS CLICKS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
			
				$SQL_BKT_CLK .=  "JOIN ox_data_bkt_c AS odbc ON odbc.zone_id = oxz.zoneid";
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_CLK .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND month(odbc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL_BKT_CLK .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odbc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
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
						$result[$data_clk['db_month']]['REV']	=	0;
						}
					
					}
				}
			
			$temp= $result;
			
			// GETTING Conversions
			
			$SQL_BKT_CON	=	"SELECT 
										oxmu.accountid, 
										month(odba.date_time) as db_month,
										ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
			
				$SQL_BKT_CON .=  "JOIN ox_data_bkt_a AS odba ON odba.zone_id = oxz.zoneid";
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_CON .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND month(odba.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL_BKT_CON .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odba.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
			}
						
			$SQL_BKT_CON .=" GROUP BY month(odba.date_time)";
			
			
			$query_con = $this->db->query($SQL_BKT_CON);
		
                        
                         if($query_con->num_rows>0)
                                {
                                         $stat_summary_con =  $query_con->result_array();
                                 
                                         foreach($stat_summary_con as $data_con){
                                                
                                                if(isset($temp[$data_con['db_month']]['CON'])){
                                                $tot_cons        =        $data_con['CONVERSIONS'] + $temp[$data_con['db_month']]['CON'];
                                                $result[$data_con['db_month']]['CON']        =$tot_cons;
                                                }else{
                                                        $tot_cons                =        $data_con['CONVERSIONS'];
                                        
                                                $result[$data_con['db_month']]['IMP']        =        0;
                                                $result[$data_con['db_month']]['CLK']        =        0;
                                                $result[$data_con['db_month']]['CON']        =        $tot_cons;
                                                $result[$data_con['db_month']]['REV']        =        0;
                                                }
                                        
                                        }
                                }
                        
                        $temp= $result;
	
			
			// GETTING REVENUE AMOUNT
			
			$SQL_BKT_REV	=	"SELECT 
										oxmu.accountid,
										month(oxmr.date) as db_month,
										FORMAT(ifnull(sum( oxmr.`publisher_amount` ),0),2) AS REV
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
		
			$SQL_BKT_REV .=  " JOIN oxm_report AS oxmr ON   oxmr.zoneid = oxz.zoneid";
		
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_REV .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND month(oxmr.date)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL_BKT_REV .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxmr.date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
			}
						
			 $SQL_BKT_REV .=" GROUP BY month(oxmr.date)";
			
			$query_rev = $this->db->query($SQL_BKT_REV);
				
				
			  if($query_rev->num_rows>0)
				{
					 $stat_summary_rev =  $query_rev->result_array();
				 	
				 	foreach($stat_summary_rev as $data_rev){
						$tot_rev		=	$data_rev['REV'];
						if(isset($temp[$data_rev['db_month']])){
						//$tot_spend		=	$data_spend['SPEND'] + 0;
						$result[$data_rev['db_month']]['REV']	=$tot_rev;
						}else{
							$result[$data_rev['db_month']]['IMP']		=	0;
						$result[$data_rev['db_month']]['CLK']		=	0;
						$result[$data_rev['db_month']]['CON']		=	0;
						$result[$data_rev['db_month']]['REV']	=	$tot_rev;
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
				$new_temp[$key]['REV'] 	= $stat_data['REV'];
				$new_temp[$key]['UIMP'] 	= 0;
				$new_temp[$key]['UCLK'] 	= 0;
			}
			
			$temp 	= $new_temp;
			$result = $new_temp;
				
			// GET UNIQUE IMPRESSIONS
				
			$SQL = "SELECT db_month,count(db_month) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.impressions>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.impressions>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
			}
			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
			
			$SQL .=" UNION (SELECT month(oxum.interval_start) as db_month,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND MONTH(oxum.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxum.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
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
						$result[$unique_data['db_month']]['REV']	= $temp[$unique_data['db_month']]['REV'];
						
						$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= 0; 
						
							
					}
					else
					{
						$result[$unique_data['db_month']]['IMP']		= 0;
						$result[$unique_data['db_month']]['CLK']		= 0;
						$result[$unique_data['db_month']]['CON']		= 0;
						$result[$unique_data['db_month']]['REV']	= 0;
						
						$result[$unique_data['db_month']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= 0; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;
			
		
			//GET UNIQUE CLICKS
		
				$SQL = "SELECT db_month,count(db_month) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  month(oxu.date_time) as db_month,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.clicks>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.clicks>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
			}
			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";
			
			$SQL .=" UNION (SELECT month(oxuc.interval_start) as db_month,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND MONTH(oxuc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
					break;
					case 'past_six_months':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxuc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}
				
				
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
						$result[$unique_data['db_month']]['REV']	= $temp[$unique_data['db_month']]['REV'];
						
						$result[$unique_data['db_month']]['UIMP']	= $temp[$unique_data['db_month']]['UIMP'];
						$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK']; 
						
							
					}
					else
					{
						$result[$unique_data['db_month']]['IMP']		= 0;
						$result[$unique_data['db_month']]['CLK']		= 0;
						$result[$unique_data['db_month']]['CON']		= 0;
						$result[$unique_data['db_month']]['REV']	= 0;
						
						$result[$unique_data['db_month']]['UIMP']	= 0;
						$result[$unique_data['db_month']]['UCLK']	= $unique_data['UCLK']; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;
			
	
				//CALCULATE CTR 
				
				
				$final_result 	= array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"REV"=>0.00,"CTR"=>0.00,"UIMP"=>'0',"UCLK"=>'0');
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
										"REV"=>number_format($resObj['REV'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',','),
										"UIMP"=>$resObj['UIMP'],
										"UCLK"=>$resObj['UCLK']
									);

									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['REV']	+=  number_format($resObj['REV'],2,".",",");
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
		
	
	}

	// Retrieve click to call,click to web and click to map for last week
	function get_week_click_to_action_for_publisher($search_array,$stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',DATE(date) AS 'date'
					from oxm_report As r ";
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != '')
			{
				switch ($stat_type)
				{
					case 'last_seven_days':
						$SQL.="where publisherid=".$search_array['sel_publisher_id']." AND DATE(date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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
	}
	
	// Retrieve click to call,click to web and click to map for current month
	function get_click_to_action_for_publisher($search_array,$stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',date
					from oxm_report As r ";
	
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != '')
			{
				switch ($stat_type)
				{
					case 'current_month':
						$SQL.="where publisherid=".$search_array['sel_publisher_id']." AND month(date)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'today':
						$SQL.="where publisherid=".$search_array['sel_publisher_id']."  AND DATE(date)=DATE(now())";
						break;
							
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
	
	}
	
	
	//Get  Weekly and  Daily Statistics
	function get_date_report_for_publisher($search_array,$stat_type=FALSE)
	{
			
			 
			
			//print_r($search_array); 
			if($stat_type)
			{			
			$result = array();  
			
			$SQL = "SELECT oxmu.accountid,oxa.affiliateid,oxz.zonename, 
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS,
						date(h.date_time) as db_date
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
						";
			

				$SQL .=  "  JOIN ox_data_summary_ad_hourly AS h ON h.zone_id = oxz.zoneid";

			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
			
				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(h.date_time)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(h.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
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
															"REV"=>0
													);
			
				 }
			  }
	
			//exit;
				
			$temp = $result;
		
			
			// GETTING IMPRESSIONS
			
			$SQL_BKT_IMP	=	"SELECT 
										oxmu.accountid, 
										date(odbm.interval_start) as db_date,
										ifnull(sum( odbm.`count` ),0) AS IMP
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";

				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.zone_id = oxz.zoneid";

			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_IMP .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(odbm.interval_start)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL_BKT_IMP .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'AND date(odbm.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
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
						$result[$data_imp['db_date']]['REV']	=	0;
					}
					
					}
				}
				
				$temp= $result;
			
			// GETTING CLICKS
			
			$SQL_BKT_CLK	=	"SELECT 
										oxmu.accountid, 
										date(odbc.interval_start) as db_date,
										ifnull(sum( odbc.`count` ),0) AS CLICKS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
			
				$SQL_BKT_CLK .=  "JOIN ox_data_bkt_c AS odbc ON odbc.zone_id = oxz.zoneid";
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_CLK .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odbc.interval_start)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL_BKT_CLK .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odbc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
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
						$result[$data_clk['db_date']]['REV']	=	0;
						}
					
					}
				}
			
			$temp= $result;
			
			
			
			// GETTING CONVERSIONS
			
			$SQL_BKT_CON	=	"SELECT 
										oxmu.accountid, 
										date(odba.date_time) as db_date,
										ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
			
				$SQL_BKT_CON .=  "JOIN ox_data_bkt_a AS odba ON odba.zone_id = oxz.zoneid";
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_CON .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odba.date_time)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL_BKT_CON .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."'  AND date(odba.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
			}
						
			$SQL_BKT_CON .=" GROUP BY date(odba.date_time)";
			
			
			$query_con = $this->db->query($SQL_BKT_CON);
		
                        
                         if($query_con->num_rows>0)
                                {
                                         $stat_summary_con =  $query_con->result_array();
                                 
                                         foreach($stat_summary_con as $data_con){
                                                
                                                if(isset($temp[$data_con['db_date']]['CON'])){
                                                $tot_cons        =        $data_con['CONVERSIONS'] + $temp[$data_con['db_date']]['CON'];
                                                $result[$data_con['db_date']]['CON']        =$tot_cons;
                                                }else{
                                                        $tot_cons                =        $data_con['CONVERSIONS'];
                                        
                                                $result[$data_con['db_date']]['IMP']        =        0;
                                                $result[$data_con['db_date']]['CLK']        =        0;
                                                $result[$data_con['db_date']]['CON']        =        $tot_cons;
                                                $result[$data_con['db_date']]['REV']        =        0;
                                                }
                                        
                                        }
                                }
                        
                        $temp= $result;
						
			
	
			
			// GETTING REVENUE AMOUNT
			
			$SQL_BKT_REV	=	"SELECT 
										oxmu.accountid,
										date(oxmr.date) as db_date,
										FORMAT(ifnull(sum( oxmr.`publisher_amount` ),0),2) AS REV
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
		
			$SQL_BKT_REV .=  " JOIN oxm_report AS oxmr ON   oxmr.zoneid = oxz.zoneid";
		
			
			if(count($search_array) > 0 AND isset($search_array['sel_publisher_id']) AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_REV .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND DATE(oxmr.date)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL_BKT_REV .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxmr.date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
			}
						
			 $SQL_BKT_REV .=" GROUP BY date(oxmr.date)";
			
			$query_rev = $this->db->query($SQL_BKT_REV);
				
				
			  if($query_rev->num_rows>0)
				{
					 $stat_summary_rev =  $query_rev->result_array();
				 	
				 	foreach($stat_summary_rev as $data_rev){
						$tot_rev		=	$data_rev['REV'];
						if(isset($temp[$data_rev['db_date']])){
						//$tot_spend		=	$data_spend['SPEND'] + 0;
						$result[$data_rev['db_date']]['REV']	=$tot_rev;
						}else{
							$result[$data_rev['db_date']]['IMP']		=	0;
						$result[$data_rev['db_date']]['CLK']		=	0;
						$result[$data_rev['db_date']]['CON']		=	0;
						$result[$data_rev['db_date']]['REV']	=	$tot_rev;
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
				$new_temp[$key]['REV'] 	= $stat_data['REV'];
				$new_temp[$key]['UIMP'] 	= 0;
				$new_temp[$key]['UCLK'] 	= 0;
			}
			
			$temp 	= $new_temp;
			$result = $new_temp;
				
			// GET UNIQUE IMPRESSIONS
				
			$SQL = "SELECT db_date,count(db_date) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.impressions>0 AND DATE(oxu.date_time)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.impressions>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
			}
			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.date_time))";
			
			$SQL .=" UNION (SELECT date(oxum.interval_start) as db_date,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND DATE(oxum.interval_start)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxum.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
			}
			
			
			$SQL .=" GROUP BY oxum.`viewer_id`,oxum.`creative_id`,oxum.zone_id,date(oxum.interval_start))) as UIMP GROUP BY db_date";
			
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();

				 foreach($stat_summary as $unique_data){
				 	
					if(isset($result[$unique_data['db_date']])){
					
						$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
						$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
						$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
						$result[$unique_data['db_date']]['REV']	= $temp[$unique_data['db_date']]['REV'];
						
						$result[$unique_data['db_date']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_date']]['UCLK']	= 0; 
						
							
					}
					else
					{
						$result[$unique_data['db_date']]['IMP']		= 0;
						$result[$unique_data['db_date']]['CLK']		= 0;
						$result[$unique_data['db_date']]['CON']		= 0;
						$result[$unique_data['db_date']]['REV']	= 0;
						
						$result[$unique_data['db_date']]['UIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_date']]['UCLK']	= 0; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;
			
		
			//GET UNIQUE CLICKS
		
				$SQL = "SELECT db_date,count(db_date) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.clicks>0 AND DATE(oxu.date_time)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND oxu.clicks>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
			}
			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.date_time))";
			
			$SQL .=" UNION (SELECT date(oxuc.interval_start) as db_date,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND DATE(oxuc.interval_start)=CURDATE()";
					break;
					case 'last_seven_days':
						$SQL.=" WHERE oxa.affiliateid='".$search_array['sel_publisher_id']."' AND date(oxuc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
				
				
			}
			
			
			$SQL .=" GROUP BY oxuc.`viewer_id`,oxuc.`creative_id`,oxuc.zone_id,date(oxuc.interval_start))) AS UCLK GROUP BY db_date";
			
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();

				 foreach($stat_summary as $unique_data){
				 	
					if(isset($result[$unique_data['db_date']])){
					
						$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
						$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
						$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
						$result[$unique_data['db_date']]['REV']	= $temp[$unique_data['db_date']]['REV'];
						
						$result[$unique_data['db_date']]['UIMP']	= $temp[$unique_data['db_date']]['UIMP'];
						$result[$unique_data['db_date']]['UCLK']	= $unique_data['UCLK']; 
						
							
					}
					else
					{
						$result[$unique_data['db_date']]['IMP']		= 0;
						$result[$unique_data['db_date']]['CLK']		= 0;
						$result[$unique_data['db_date']]['CON']		= 0;
						$result[$unique_data['db_date']]['REV']	= 0;
						
						$result[$unique_data['db_date']]['UIMP']	= 0;
						$result[$unique_data['db_date']]['UCLK']	= $unique_data['UCLK']; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;
			
	
				//CALCULATE CTR 
				
				
				$final_result 	= array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"REV"=>0.00,"CTR"=>0.00,"UIMP"=>'0',"UCLK"=>'0');
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
										"REV"=>number_format($resObj['REV'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',','),
										"UIMP"=>$resObj['UIMP'],
										"UCLK"=>$resObj['UCLK']
									);

									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['REV']	+=  number_format($resObj['REV'],2,".",",");
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
		}
}
