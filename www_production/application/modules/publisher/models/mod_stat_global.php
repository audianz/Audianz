<?php
class Mod_stat_global extends CI_Model {
		
			
		// GET STATISTICS BASED ON COUNTRY WISE
		
		function get_statistics_country_wise($search_array=0,$start=0,$limit=false){
			
			$result = array();
			
			$SQL = "SELECT 
						oxct.country_name as country_name,
						h.country as country_code,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_affiliates AS oxaf
						JOIN ox_zones AS oxz ON oxaf.affiliateid = oxz.affiliateid
						";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.zone_id = oxz.zoneid AND date(h.date_time)  BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."') ";
			}
			else
			{
				$SQL .=  " JOIN ox_stats_country AS h ON ( h.zone_id = oxz.zoneid)";
			}
			
			$SQL	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = h.country";
						
			if(isset($search_array['sel_ref_id']) AND $search_array['sel_ref_id'] > 0){
				$SQL  .= " WHERE oxaf.account_id='".$search_array['sel_ref_id']."'"; 
			}
			
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
							FROM ox_affiliates AS oxaf
							JOIN ox_zones AS oxz ON oxaf.affiliateid = oxz.affiliateid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON (odbm.zone_id = oxz.zoneid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_country_m AS odbm ON odbm.zone_id = oxz.zoneid";
			}
			
			$SQL_BKT_IMP	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
			
			if(isset($search_array['sel_ref_id']) AND $search_array['sel_ref_id'] > 0){
				$SQL_BKT_IMP  .= " WHERE oxaf.account_id='".$search_array['sel_ref_id']."'"; 
			}
						
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
								FROM ox_affiliates AS oxaf
								JOIN ox_zones AS oxz ON oxaf.affiliateid = oxz.affiliateid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON (odbm.zone_id = oxz.zoneid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_country_c AS odbm ON odbm.zone_id = oxz.zoneid";
			}
			
			$SQL_BKT_CLK	.= " JOIN oxm_country AS oxct ON oxct.counrty_code = odbm.country";
			
			if(isset($search_array['sel_ref_id']) AND $search_array['sel_ref_id'] > 0){
				$SQL_BKT_CLK  .= " WHERE oxaf.account_id='".$search_array['sel_ref_id']."'"; 
			}
								
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
		
}
