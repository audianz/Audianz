<?php
class Mod_stat_global extends CI_Model {  
		
		
		
		
		function get_statistics_datewise($search_array,$start=0,$limit=false){
			
			
					
			$result = array();
			
			$SQL = "SELECT 
						date(h.date_time) as db_date,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
						
			$SQL .=" GROUP BY date(h.date_time)";
			
			 $SQL .=" ORDER BY date(h.date_time) DESC";
			
						
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 foreach($stat_summary as $data){
					$result[$data['db_date']]	=	array(
															"IMP"=>$data['IMP'],
															"CON"=>$data['CONVERSIONS'],
															"CLK"=>$data['CLICKS'],
															"SPEND"=>0,
															"CALL"=>0,
															"WEB"=>0,
															"MAP"=>0
													);
				 }
			  } 
				
			$temp = $result;
			
			
			//print_r($result);
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date,  IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
			
			$SQL .=" ORDER BY date(h.date_time) DESC";
			
			
			$query1 = $this->db->query($SQL_BKT_IMP);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
						$result[$data_imp['db_date']]['IMP']	=$tot_imp;
					}
					else
					{
					$tot_imp		=	$data_imp['IMP'];
						$result[$data_imp['db_date']]['IMP']	=	$tot_imp;
						$result[$data_imp['db_date']]['CLK']	=	0;
						$result[$data_imp['db_date']]['CON']	=	0;
						$result[$data_imp['db_date']]['SPEND']	=	0;
						$result[$data_imp['db_date']]['CALL']	=	0;
						$result[$data_imp['db_date']]['WEB']	=	0;
						$result[$data_imp['db_date']]['MAP']	=	0;
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			//print_r($result);
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}
								
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
			$SQL_BKT_CLK .=" ORDER BY DATE( odbm.interval_start ) DESC";
			
			$query1 = $this->db->query($SQL_BKT_CLK);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['CLK'])){
						$tot_clicks		=	$data_imp['CLK'] + $temp[$data_imp['db_date']]['CLK'];
						$result[$data_imp['db_date']]['CLK']	=$tot_clicks;
					}
					else
					{
					$tot_clicks		=	$data_imp['CLK'];
					
						$result[$data_imp['db_date']]['IMP']	=	0;
						$result[$data_imp['db_date']]['CLK']	=	$tot_clicks;
						$result[$data_imp['db_date']]['CON']	=	0;
						$result[$data_imp['db_date']]['SPEND']	=	0;
						$result[$data_imp['db_date']]['CALL']	=	0;
						$result[$data_imp['db_date']]['WEB']	=	0;
						$result[$data_imp['db_date']]['MAP']	=	0;
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
				//GET BKT CONVERSIONS
		
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}
								
			$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";
			
			$SQL_BKT_CON .=" ORDER BY DATE( odba.date_time ) DESC";
			
			$query1 = $this->db->query($SQL_BKT_CON);
				
				
			if($query1->num_rows>0)
			{
				$stat_con =  $query1->result_array();
				
				foreach($stat_con as $data_con){
					
					
					if(isset($temp[$data_con['db_date']]['CON'])){
						$tot_conversions		=	$data_con['CON'] + $temp[$data_con['db_date']]['CON'];
						$result[$data_con['db_date']]['CON']	=$tot_conversions;
					}
					else
					{
					$tot_conversions	=	$data_con['CON'];
					
						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	0;
						$result[$data_con['db_date']]['CON']	=	$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=	0;
						$result[$data_con['db_date']]['CALL']	=	0;
						$result[$data_con['db_date']]['WEB']	=	0;
						$result[$data_con['db_date']]['MAP']	=	0;
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			
			//GET SPEND OXM REPORT TABLE
		
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
					        ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
					        ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
							ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'	
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON  oxmr.bannerid = oxb.bannerid";
			}
		
						
			$SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date )";
			
			$SQL_BKT_SPEND .=" ORDER BY DATE( oxmr.date ) DESC";
			
			$query1 = $this->db->query($SQL_BKT_SPEND);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_spend){
					$tot_spend		=	$data_spend['SPEND'];
					$tot_call		=	$data_spend['CALL'];
					$tot_web		=	$data_spend['WEB'];
					$tot_map		=	$data_spend['MAP'];
					
					if(isset($temp[$data_spend['db_date']])){
						$result[$data_spend['db_date']]['SPEND']	=$tot_spend;
						$result[$data_spend['db_date']]['CALL']	=$tot_call;
						$result[$data_spend['db_date']]['WEB']	=$tot_web;
						$result[$data_spend['db_date']]['MAP']	=$tot_map;
					}
					else
					{
						$result[$data_spend['db_date']]['IMP']		=	0;
						$result[$data_spend['db_date']]['CLK']		=	0;
						$result[$data_spend['db_date']]['CON']		=	0;
						$result[$data_spend['db_date']]['SPEND']	=	$tot_spend;
						$result[$data_spend['db_date']]['CALL']	=	$tot_call;
						$result[$data_spend['db_date']]['WEB']	=	$tot_web;
						$result[$data_spend['db_date']]['MAP']	=	$tot_map;
					
					}
					
					
					
				}
				
			}
			
			
				$final_result 	= array();
				$final_key_result = array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CALL"=>0,"WEB"=>0,"MAP"=>0,"CTR"=>0.00);				
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
						
						$t[date("Y-m-d",strtotime($key))]	=	array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CALL"=>$resObj['CALL'],
										"WEB"=>$resObj['WEB'],
										"MAP"=>$resObj['MAP'],
										"CTR"=>number_format($CTR,2,'.',',')
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  $resObj['SPEND'];
									$final_tot['CALL']	+=  $resObj['CALL'];
									$final_tot['WEB']	+=  $resObj['WEB'];
									$final_tot['MAP']	+=  $resObj['MAP'];
									
												
						array_push($final_key_result,date("Y-m-d",strtotime($key)));						
						array_push($final_result,$t[date("Y-m-d",strtotime($key))]);
					}
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=  0.00;
					
				}
				
				
				krsort($final_result);
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
				 
		}
		

		// GET STATISTICS BASED ON COUNTRY WISE
		
		function get_statistics_country_wise($search_array=0,$start=0,$limit=false){
			
			$result = array();
			
			$SQL = "SELECT 
						oxct.country_name as country_name,
						h.country as country_code,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.creative_id = oxb.bannerid AND date(h.date_time)  BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."') ";
			}
			else
			{
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.creative_id = oxb.bannerid)";
			}
			
			$SQL	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = h.country";
						
			$SQL .=" GROUP BY h.country";
			
		    $SQL .=" ORDER BY date(h.date_time) DESC";
			
			
		
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 foreach($stat_summary as $data){
					$result[$data['country_code']]	=	array(
															"IMP"=>$data['IMP'],
															"CLK"=>$data['CLICKS'],
															"COUNTRY"=>$data['country_name'],
															"COUNTRY_CODE"=>$data['country_code']
													);
				 }
			  } 
				
			$temp = $result;
			
						
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT 
							IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP,
							oxct.country_name as country_name,
							odbm.country as country_code
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			$SQL_BKT_IMP	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.country )";
			
			$SQL_BKT_IMP .=" ORDER BY date(odbm.interval_start) DESC";
			
			
			$query1 = $this->db->query($SQL_BKT_IMP);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['country_code']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['country_code']]['IMP'];
						$result[$data_imp['country_code']]['IMP']	=$tot_imp;
					}
					else
					{
						$tot_imp		=	$data_imp['IMP'];
						$result[$data_imp['country_code']]['IMP']			=	$tot_imp;
						$result[$data_imp['country_code']]['CLK']			=	0;
						$result[$data_imp['country_code']]['COUNTRY']		=	$data_imp['country_name'];
						$result[$data_imp['country_code']]['COUNTRY_CODE']	=	$data_imp['country_code'];
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT 
								IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK,
								oxct.country_name as country_name,
								odbm.country as country_code
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			$SQL_BKT_CLK	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
								
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.country )";
			
			$SQL_BKT_CLK .=" ORDER BY DATE( odbm.interval_start ) DESC";
			
			$query1 = $this->db->query($SQL_BKT_CLK);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['country_code']]['CLK'])){
						$tot_clicks		=	$data_imp['CLK'] + $temp[$data_imp['country_code']]['CLK'];
						$result[$data_imp['country_code']]['CLK']	=$tot_clicks;
					}
					else
					{
					$tot_clicks		=	$data_imp['CLK'];
					
						$result[$data_imp['country_code']]['IMP']			=	0;
						$result[$data_imp['country_code']]['CLK']			=	$tot_clicks;
						$result[$data_imp['country_code']]['COUNTRY']		=	$data_imp['country_name'];
						$result[$data_imp['country_code']]['COUNTRY_CODE']	=	$data_imp['country_code'];
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			
			
			
			
				$final_result 	= array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CTR"=>0.00);				
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
						
						$t	=	array(
										"IMP"=>$resObj['IMP'],
										"CLK"=>$resObj['CLK'],
										"CTR"=>number_format($CTR,2,'.',','),
										"COUNTRY"		=>	$resObj['COUNTRY'],
										"COUNTRY_CODE"	=>	$resObj['COUNTRY_CODE']
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									
									
												
						array_push($final_result,$t);
					}
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
					else
					$final_tot['CTR']	=  0.00;
					
				}
				
				
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
				 
		}		
			
			
			
		// GET STATISTICS BASED DATE WISE - COUNTRY WISE
		
		function get_statistics_date_country_wise($search_array,$start=0,$limit=false){
			
			$result = array();
			
			$SQL = "SELECT 
						date(h.date_time) as db_date,
						oxct.country_name as country_name,
						h.country as country_code,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.creative_id = oxb.bannerid AND date(h.date_time)  BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."') ";
			}
			else
			{
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.creative_id = oxb.bannerid)";
			}
			
			$SQL	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = h.country";
						
			$SQL .=" GROUP BY date(h.date_time)";
			
		    $SQL .=" ORDER BY date(h.date_time) DESC";
			
			
						
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 foreach($stat_summary as $data){
					$result[$data['db_date']]	=	array(
															"IMP"=>$data['IMP'],
															"CLK"=>$data['CLICKS'],
															"COUNTRY"=>$data['country_name'],
															"COUNTRY_CODE"=>$data['country_code']
													);
				 }
			  } 
				
			$temp = $result;
			
						
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT 
							DATE( odbm.interval_start ) AS db_date,  
							IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP,
							oxct.country_name as country_name,
							odbm.country as country_code
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			$SQL_BKT_IMP	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
			
			$SQL_BKT_IMP .=" ORDER BY date(odbm.interval_start) DESC";
			
			
			$query1 = $this->db->query($SQL_BKT_IMP);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
						$result[$data_imp['db_date']]['IMP']	=$tot_imp;
					}
					else
					{
						$tot_imp		=	$data_imp['IMP'];
						$result[$data_imp['db_date']]['IMP']			=	$tot_imp;
						$result[$data_imp['db_date']]['CLK']			=	0;
						$result[$data_imp['db_date']]['COUNTRY']		=	$data_imp['country_name'];
						$result[$data_imp['db_date']]['COUNTRY_CODE']	=	$data_imp['country_code'];
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT 
								DATE( odbm.interval_start ) AS db_date, 
								IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK,
								oxct.country_name as country_name,
								odbm.country as country_code
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			$SQL_BKT_CLK	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
								
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
			$SQL_BKT_CLK .=" ORDER BY DATE( odbm.interval_start ) DESC";
			
			$query1 = $this->db->query($SQL_BKT_CLK);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['CLK'])){
						$tot_clicks		=	$data_imp['CLK'] + $temp[$data_imp['db_date']]['CLK'];
						$result[$data_imp['db_date']]['CLK']	=$tot_clicks;
					}
					else
					{
					$tot_clicks		=	$data_imp['CLK'];
					
						$result[$data_imp['db_date']]['IMP']			=	0;
						$result[$data_imp['db_date']]['CLK']			=	$tot_clicks;
						$result[$data_imp['db_date']]['COUNTRY']		=	$data_imp['country_name'];
						$result[$data_imp['db_date']]['COUNTRY_CODE']	=	$data_imp['country_code'];
					
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			
			
			
			
				$final_result 	= array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CTR"=>0.00);				
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
						
						$t	=	array(
										"date"=>date("d-m-Y",strtotime($key)),
										"IMP"=>$resObj['IMP'],
										"CLK"=>$resObj['CLK'],
										"CTR"=>number_format($CTR,2,'.',','),
										"COUNTRY"		=>	$resObj['COUNTRY'],
										"COUNTRY_CODE"	=>	$resObj['COUNTRY_CODE']
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									
									
												
						array_push($final_result,$t);
					}
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
					else
					$final_tot['CTR']	=  0.00;
					
				}
				
				
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
				 
		}
		
		
				
		// END OF FUNCTION FOR GETTING STATISTICS BASED COUNTRY WISE
		
		function get_start_date(){
			$query = $this->db->query("SELECT date(`date_time`) as start_date FROM `ox_data_summary_ad_hourly` ORDER BY `date_time` ASC LIMIT 0,1");
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

		function get_banner_det($banner_id){
	
			$this->db->where("bannerid", $banner_id);

			$query = $this->db->select('*')->get('ox_banners');

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
	
		function get_child_banners_id($cid=0){
					
			$this->db->select("bannerid");
			$this->db->from('ox_banners');
			$this->db->where('bannerid',$cid);
			$this->db->or_where('master_banner',$cid);

			$query = $this->db->get();

			if($query->num_rows >0)
			{
					$banners	=$query->result();
					$str    = array();
					foreach($banners as $bObj){
						$str[]  =  $bObj->bannerid;
					}

				  return implode($str,",");
			}
			else
			{
					return FALSE;
			}
		}
		
		function get_statistics_hourwise($search_array,$start=0,$limit=false){
			
					
			$result = array();
			
			$SQL = "SELECT 
						time(h.date_time) as db_date,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date']!=''){ 
				
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time)='".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
						
			$SQL .=" GROUP BY time(h.date_time)";
			
			 			
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 foreach($stat_summary as $data){
					$result[$data['db_date']]	=	array(
															"IMP"=>$data['IMP'],
															"CON"=>$data['CONVERSIONS'],
															"CLK"=>$data['CLICKS'],
													);
				 }
			  } 
				
			$temp = $result;
			
			
			//print_r($result);
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT TIME( odbm.interval_start ) AS db_date,  IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date']!=''){  
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) ='".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
						
			$SQL_BKT_IMP .=" GROUP BY TIME( odbm.interval_start )";
			
			$query1 = $this->db->query($SQL_BKT_IMP);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['IMP'])){
					$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
						$result[$data_imp['db_date']]['IMP']	=$tot_imp;
					}
					else
					{
					$tot_imp		=	$data_imp['IMP'];
						$result[$data_imp['db_date']]['IMP']	=	$tot_imp;
						$result[$data_imp['db_date']]['CLK']	=	0;
						$result[$data_imp['db_date']]['CON']	=	0;
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			//print_r($result);
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT TIME( odbm.interval_start ) AS db_date, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date']!=''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) ='".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}
								
			$SQL_BKT_CLK .=" GROUP BY TIME( odbm.interval_start )";
			
			
			$query1 = $this->db->query($SQL_BKT_CLK);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['CLK'])){
						$tot_clicks		=	$data_imp['CLK'] + $temp[$data_imp['db_date']]['CLK'];
						$result[$data_imp['db_date']]['CLK']	=$tot_clicks;
					}
					else
					{
					$tot_clicks		=	$data_imp['CLK'];
					
						$result[$data_imp['db_date']]['IMP']	=	0;
						$result[$data_imp['db_date']]['CLK']	=	$tot_clicks;
						$result[$data_imp['db_date']]['CON']	=	0;
					}
					
					
				}
				
			}
			
			$temp = $result;
			
				//GET BKT CONVERSIONS
		
			$SQL_BKT_CON = "SELECT TIME( odba.date_time ) AS db_date, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date']!=''){ 
				
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) = '".$search_array['sel_date']."'  )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}
								
			$SQL_BKT_CON .=" GROUP BY TIME( odba.date_time )";
			
			
			$query1 = $this->db->query($SQL_BKT_CON);
				
				
			if($query1->num_rows>0)
			{
				$stat_con =  $query1->result_array();
				
				foreach($stat_con as $data_con){
					
					
					if(isset($temp[$data_con['db_date']]['CON'])){
						$tot_conversions		=	$data_con['CON'] + $temp[$data_con['db_date']]['CON'];
						$result[$data_con['db_date']]['CON']	=$tot_conversions;
					}
					else
					{
					$tot_conversions	=	$data_con['CON'];
					
						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	0;
						$result[$data_con['db_date']]['CON']	=	$tot_conversions;
					}
					
					
				}
				
			}
			
			$temp = $result;
			
			
				$final_result 	= array();
				$final_key_result = array();
				$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"CTR"=>0.00);				
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
						
						$t[$key]	=	array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"CTR"=>number_format($CTR,2,'.',',')
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
												
						array_push($final_key_result,$key);						
						array_push($final_result,$t[$key]);
					}
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=  0.00;
					
				}
				
				
				//krsort($final_result);
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
		}
		
}
