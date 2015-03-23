<?php
class Mod_statistics extends CI_Model {  
		
		function get_statistics_for_advertiser($search_array,$start=0,$limit=false){
			
		//	print_r($search_array); 
						
			$result = array();  
			
			$SQL = "SELECT oxcl.clientid, 
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL .=" GROUP BY oxcl.clientid";
			
						
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 
				 foreach($stat_summary as $data){
					$result[$data['clientid']]	=	array(
															"IMP"=>$data['IMP'],
															"CON"=>$data['CONVERSIONS'],
															"CLK"=>$data['CLICKS'],
															"SPEND"=>0
													);
				 }
			  } 
				
			$temp = $result;
		
			// GETTING IMPRESSIONS
			
			$SQL_BKT_IMP	=	"SELECT 
										oxcl.clientid, 
										ifnull(sum( odbm.`count` ),0) AS IMP
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON ( odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_IMP .=" GROUP BY oxcl.clientid";
			
			$query_imp = $this->db->query($SQL_BKT_IMP);
				
				
			  if($query_imp->num_rows>0)
				{
					 $stat_summary_imp =  $query_imp->result_array();
				 
				 	foreach($stat_summary_imp as $data_imp){
						$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['clientid']]['IMP'];
						$result[$data_imp['clientid']]['IMP']	=$tot_imp;
					}
				}
				
				
			// GETTING CLICKS
			
			$SQL_BKT_CLK	=	"SELECT 
										oxcl.clientid, 
										ifnull(sum( odbc.`count` ),0) AS CLICKS
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON ( odbc.creative_id = oxb.bannerid AND date(odbc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CLK .=" GROUP BY oxcl.clientid";
			
			$query_clk = $this->db->query($SQL_BKT_CLK);
				
				
			  if($query_clk->num_rows>0)
				{
					 $stat_summary_clk =  $query_clk->result_array();
				 
				 	foreach($stat_summary_clk as $data_clk){
						$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['clientid']]['CLK'];
						$result[$data_clk['clientid']]['CLK']	=$tot_clicks;
					}
				}
				
					// GETTING CONVERSIONS
			
			$SQL_BKT_CON	=	"SELECT 
										oxcl.clientid, 
										ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON ( odba.creative_id = oxb.bannerid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CON .=" GROUP BY oxcl.clientid";
			
			$query_con = $this->db->query($SQL_BKT_CON);
				
				
			  if($query_con->num_rows>0)
				{
					 $stat_summary_con =  $query_con->result_array();
				 
				 	foreach($stat_summary_con as $data_con){
						$tot_conversions		=	$data_con['CONVERSIONS'] + $temp[$data_con['clientid']]['CON'];
						$result[$data_con['clientid']]['CON']	=$tot_conversions;
					}
				}
			
			// GETTING SPEND AMOUNT
			
			$SQL_BKT_SPEND	=	"SELECT 
										oxcl.clientid, 
										FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
					                    ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
					                    ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
					                    ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_SPEND .=" GROUP BY oxcl.clientid";
			
			$query_spend = $this->db->query($SQL_BKT_SPEND);
				
			
				
			  if($query_spend->num_rows>0)
				{
					 $stat_summary_spend =  $query_spend->result_array();
				 
				 	foreach($stat_summary_spend as $data_spend){
						$tot_spend		=	$data_spend['SPEND'] + 0;
						$tot_call		=	$data_spend['CALL'];
						$tot_web		=	$data_spend['WEB'];
						$tot_map		=	$data_spend['MAP'];
						
						$result[$data_spend['clientid']]['SPEND']	=$tot_spend;
						$result[$data_spend['clientid']]['CALL']	=$tot_call;
						$result[$data_spend['clientid']]['WEB']		=$tot_web;
						$result[$data_spend['clientid']]['MAP']		=$tot_map;
						
					}
				}
				
				//CALCULATE CTR 
				
				
				$final_result 	= array();
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

						$final_result[$key]	=	array(
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
									$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");
									$final_tot['CALL']	+=  $resObj['CALL'];
									$final_tot['WEB']	+=  $resObj['WEB'];
									$final_tot['MAP']	+=  $resObj['MAP'];


					
					}
				}
				
				if($final_tot['IMP'] > 0)
				$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,2,".",",");
				else 
				$final_tot['CTR']	=  0.00;
				
				asort($final_result);

				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				//echo '<pre>';
				//print_r($out);
				//exit;
				
				
				return $out;
				 
		}
		
		public function get_campaigns($adv_id){
			
			$campaigns	=	array();
			$banners	=	array();
			
			$query	=	$this->db->query("SELECT 
											oxc.campaignid,
											campaignname,
											bannerid,
											description,
											master_banner 
										 from 
										 	ox_campaigns as oxc 
										 JOIN 
										 	ox_banners as oxb ON oxb.campaignid=oxc.campaignid  
										where 
											clientid='".$adv_id."' AND master_banner IN (-1,-2,-3)");
											
			 if($query->num_rows > 0)
			 {
				 $campaigns_list =  $query->result_array();
				 foreach($campaigns_list as $data){
				 	$campaigns[$data['campaignid']]	= $data['campaignname'];
					
					if(!isset($banners[$data['campaignid']])){
						$banners[$data['campaignid']]	=	array();
					}
						array_push($banners[$data['campaignid']],array("bannerid"=>$data['bannerid'],"description"=>$data['description']));								 
				 }
				
				$temp = array("campaigns"=>$campaigns,"banners"=>$banners); 
				
				return $temp;
				
			 }
			 else
			 {
			 	return false;
			 }	 								
	}	
	
	function get_statistics_for_advertiser_campaigns($adv_id,$search_array,$start=0,$limit=false){
			
			
			$result = array();
			
			$campaigns	=	array();
			$banners	=	array();
			
			$campaigns_final	=	array();
			$banners_final		=	array();
			
			$SQL = "SELECT
						oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_campaigns AS oxc 
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid";
			
				
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
			
			$SQL .=" WHERE oxc.clientid='".$adv_id."'";
						
			$SQL .=" GROUP BY oxb.bannerid";			
			
			
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
																	"CLK"=>$data['CLICKS'] + $campaigns[$data['campaignid']]['CLK'],
																	"SPEND"=>0,
						 											"CALL"=>0,
						 											"WEB"=>0,
						 											"MAP"=>0
															   );
						
					 }
					 else
					 {
					 	 $campaigns[$data['campaignid']]	= array(
						 											"campaignid"=>$data['campaignid'],
																	"campaignname"=>$data['campaignname'],
																	"IMP"=>$data['IMP'],
																	"CON"=>$data['CONVERSIONS'],
																	"CLK"=>$data['CLICKS'],
																	"SPEND"=>0,
					 	 											"CALL"=>0,
					 	 											"WEB"=>0,
					 	 											"MAP"=>0
															   );										
					 }	
					 
					 
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
		
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON ( odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
						
			$SQL_BKT_IMP .=" WHERE oxc.clientid='".$adv_id."'";
						
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
																		"CLK"=>$tot_cliks ,
																		"SPEND"=>0,
						    											"CALL"=>0,
						    											"WEB"=>0,
						    											"MAP"=>0
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
																	"CLK"=>0 + $prev_campaigns[$data1['campaignid']]['CLK'],
																	"SPEND"=>0,
																	"SPEND"=>0,
																	"SPEND"=>0,
																	"SPEND"=>0
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
				
				
			// GET CLICKS
			
				
			$SQL_BKT_CLK	=	"SELECT 
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										ifnull(sum(odbc.count),0) as CLICKS
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid  
										";
		
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON ( odbc.creative_id = oxb.bannerid AND date(odbc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			}
						
			$SQL_BKT_CLK .=" WHERE oxc.clientid='".$adv_id."'";
						
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
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0,
																	"CALL"=>0,
																	"WEB"=>0,
																	"MAP"=>0
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
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0,
						 											"CALL"=>0,
						 											"WEB"=>0,
						 											"MAP"=>0
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
				
		// GET CONVERSIONS
			
				
			$SQL_BKT_CON	=	"SELECT 
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										ifnull(count(odba.server_conv_id),0) as CONVERSIONS
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid  
										";
		
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON ( odba.creative_id = oxb.bannerid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}
						
			$SQL_BKT_CON .=" WHERE oxc.clientid='".$adv_id."'";
						
			$SQL_BKT_CON .=" GROUP BY oxb.bannerid";
				
			$query1 = $this->db->query($SQL_BKT_CON);
				
			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();
				 		 
					// print_r($stat_summary1);
					
					foreach($stat_summary1 as $data1){
					
					
					
					
					if(isset($campaigns_final[$data1['campaignid']])){
					 	
							$tot_conversions		=	$data1['CONVERSIONS'] + $campaigns_final[$data1['campaignid']]['CON'];
							
							$tot_clicks =  0 + $campaigns_final[$data1['campaignid']]['CLK'];
							$tot_imp		=	0 + $campaigns_final[$data1['campaignid']]['IMP'];
						
							$campaigns_final[$data1['campaignid']]	= array(
						 											"campaignid"=>$data1['campaignid'],
																	"campaignname"=>$data1['campaignname'],
																	"IMP"=>$tot_imp,
																	"CON"=>$tot_conversions,
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0,
																	"CALL"=>0,
																	"WEB"=>0,
																	"MAP"=>0
															   );
					 }
					 else
					 {
					 	 
							$tot_clicks		=	$data1['CLICKS'] + $prev_campaigns[$data1['campaignid']]['CLK'];
						 	$tot_imp		=	0 + $prev_campaigns[$data1['campaignid']]['IMP'];
							$tot_conversions		=	$data1['CONVERSIONS'] + $campaigns_final[$data1['campaignid']]['CON'];
						
						 $campaigns_final[$data1['campaignid']]	= array(
						 											"campaignid"=>$data1['campaignid'],
																	"campaignname"=>$data1['campaignname'],
																	"IMP"=>$tot_imp,
																	"CON"=>$tot_conversions,
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0,
						 											"CALL"=>0,
						 											"WEB"=>0,
						 											"MAP"=>0
															   );										
				   	 }	
					 
					 // BANNERS SECTION
					  if(!isset($banners_final[$data1['campaignid']][$data1['bannerid']]) AND ($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3)){
							$banners_final[$data1['campaignid']][$data1['bannerid']]	=	array();
					  }
						
						
						if($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3){
					
							$tot_conversions_banner		=	$data1['CONVERSIONS'] + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
						 	$tot_imp_banner			=	0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['IMP'];
						     $tot_clicks_banner		=	 0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];	
													
							
							$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];	
							$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  $tot_conversions_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;															
																			
																			
																			
					   } 
					   else
					   {
					 		$tot_conversions_banner		=	$data1['CONVERSIONS'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
						 	$tot_imp_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];
							$tot_clicks_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
						
							
							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  $tot_conversions_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;	
					  }	
					 
					 
					 
				
					 }
					 
				}
			
				$prev_campaigns = $campaigns_final;
				$prev_banners 	= $banners_final;
			
				// GET SPEND OXM REPORT TABLE
				
				$SQL_BKT_SPEND	=	"SELECT 
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
										ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
						                ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
						                ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid  
										";
		
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";
			}
						
			$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$adv_id."'";
						
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
						 		                                    "CALL"=>$data1['CALL']+$campaigns_final[$data1['campaignid']]['CALL'],
						 											"WEB"=>$data1['WEB']+$campaigns_final[$data1['campaignid']]['WEB'],
						 											"MAP"=>$data1['MAP']+$campaigns_final[$data1['campaignid']]['MAP'],
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
						 											"CALL"=>$data1['CALL'],
						 											"WEB"=>$data1['WEB'],
						 											"MAP"=>$data1['MAP'],
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
							
							
							$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];	
							$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;		
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CTR']				=  $CTR_banner;		
							$banners_final[$data1['campaignid']][$data1['bannerid']]['SPEND']			=  number_format($data1['SPEND'],2,'.',',');															
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CALL']			=  $data1['CALL'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['WEB']			=  $data1['WEB'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['MAP']			=  $data1['MAP'];
																			
																			
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
							
							
							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;	
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CTR']		=  $CTR_banner;		
							$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  number_format($data1['SPEND'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND'],2,'.',',');
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CALL']		=  $data1['CALL'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['CALL'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['WEB']		=  $data1['WEB'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['WEB'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['MAP']		=  $data1['MAP'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['MAP'];
							
					  }	
					 
					 
					 
				
					 }
				}
			
			 $reports = array("reports_campaigns"=>$campaigns_final,"reports_banners"=>$banners_final);
			 		 
			 return $reports;
		
		}
		
		
		function get_statistics_for_advertiser_datewise($search_array,$start=0,$limit=false){
			
					
			$result = array();
			
			$SQL = "SELECT 
						date(h.date_time) as db_date,
						oxcl.clientid, 
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
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
															"SPEND"=>0,
															"CALL"=>0,
															"WEB"=>0,
															"MAP"=>0
													);
				 }
			  } 
				
			$temp = $result;
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
			
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
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
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
		
			$SQL_BKT_CON = "SELECT DATE( odba.date_time) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";
			
			$query1 = $this->db->query($SQL_BKT_CON);
			//echo $this->db->last_query(); exit;	
				
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
					$tot_conversions		=	$data_con['CON'];
					
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
		
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid, 
					          round(IFNULL(sum( oxmr.`amount` ),0),2) AS SPEND,
							  IFNULL(sum( oxmr.`click_to_call` ),0) AS 'CALL',
							  IFNULL(sum( oxmr.`click_to_web` ),0) AS 'WEB',
					          IFNULL(sum( oxmr.`click_to_map` ),0) AS 'MAP'
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date)";
			
			$query1 = $this->db->query($SQL_BKT_SPEND);
		//echo $this->db->last_query(); exit;	
				
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
					//echo "<pre>"; print_r($result);
					
					
				}
				
			}
			//exit;
			
			//echo "<pre>"; print_r($result); exit;
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
						
						$t[date("d-m-Y",strtotime($key))]	=	array(
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
									
									
						array_push($final_key_result,date("d-m-Y",strtotime($key)));						
						array_push($final_result,$t[date("d-m-Y",strtotime($key))]);
					}
					
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=  0.00;
					
				}
				
				asort($final_result);
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
				 
		}


                function get_statistics_for_advertiser_hourwise($search_array,$start=0,$limit=false){


			$result = array();

			$SQL = "SELECT
						time(h.date_time) as db_date,
                                                FORMAT(ifnull(sum( h.`total_revenue` ),0),2) AS SPEND,
						oxcl.clientid,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";

			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) = '".$search_array['sel_date']."')";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
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
                                                                                                                        "SPEND"=>$data['SPEND'],
													);
				 }
			  }

			$temp = $result;

			

                        //GET BKT IMPRESSIONS

			$SQL_BKT_IMP = "SELECT TIME( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."')";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_IMP .=" GROUP BY time( odbm.interval_start )";

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
					}


				}

			}

			$temp = $result;

			//GET BKT CLICKS

			$SQL_BKT_CLK = "SELECT time(odbm.interval_start) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."'  )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_CLK .=" GROUP BY time(odbm.interval_start)";

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
						$result[$data_imp['db_date']]['SPEND']	=       0;

					}


				}

			}

			$temp = $result;

			//GET BKT CONVERSIONS

			$SQL_BKT_CON = "SELECT time(odba.date_time) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) = '".$search_array['sel_date']."'  )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_CON .=" GROUP BY time(odba.date_time)";

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
					$tot_conversions		=	$data_con['CON'];

						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	0;
						$result[$data_con['db_date']]['CON']	=	$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=       0;

					}


				}

			}

			$temp = $result;

			
				$final_result 	= array();
				$final_key_result = array();
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

						/*$t	=	array(
										"date"=>$key,
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);
						*/
						$t[$key] = array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  $resObj['SPEND'];
							
						array_push($final_key_result,$key);						
						array_push($final_result,$t[$key]);

						//array_push($final_result,$t);
					}
					
					$final_result =  array_combine($final_key_result,$final_result);

					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=	0.00;
				}

				asort($final_result);

				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

				return $out;

		}
		
		
		function get_statistics_for_advertiser_campaigns_datewise($search_array,$start=0,$limit=false){
			
					
			$result = array();
			
			$SQL = "SELECT 
						date(h.date_time) as db_date,
						oxcl.clientid, 
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
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
													);
				 }
			  } 
				
			$temp = $result;
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
			
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
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
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
		
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";
			
			$query1 = $this->db->query($SQL_BKT_CON);
				
				
			if($query1->num_rows>0)
			{
				$stat_con =  $query1->result_array();
				
				foreach($stat_con as $data_con){
					
					if(isset($temp[$data_con['db_date']]['CON'])){
						$tot_conversions	=	$data_con['CON'] + $temp[$data_con['db_date']]['CON'];
						$result[$data_con['db_date']]['CON']	=$tot_conversions;
					}
					else
					{
					$tot_conversions		=	$data_con['CON'];
					
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
		
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid, 
							FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
					        ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
					        ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
							ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP',
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
			
			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date )";
			
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
						
						$t[date("d-m-Y",strtotime($key))]	=	array(
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
									
												
						array_push($final_key_result,date("d-m-Y",strtotime($key)));						
						array_push($final_result,$t[date("d-m-Y",strtotime($key))]);
					}
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=	0.00;
					
					
					
				}
				
				asort($final_result);
				
				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);
				
				return $out;
				 
		}

                function get_statistics_for_advertiser_campaigns_banners_datewise($search_array,$start=0,$limit=false){


			$result = array();

			//GET CHILD BANNERS ID

                        $child_banners_id   =   $this->get_child_banners_id($search_array['sel_advertiser_id']);
                        
                        $SQL = "SELECT
						date(h.date_time) as db_date,
						oxcl.clientid,
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
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
															"SPEND"=>0,
															"CALL"=>0,
															"WEB"=>0,
															"MAP"=>0
													);
				 }
			  }

			$temp = $result;

			//GET BKT IMPRESSIONS

			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";

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

			//GET BKT CLICKS

			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";

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

			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";

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
					$tot_conversions		=	$data_con['CON'];

						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	$tot_conversions;
						$result[$data_con['db_date']]['CON']	=	0;
						$result[$data_con['db_date']]['SPEND']	=	0;
						$result[$data_con['db_date']]['CALL']	=	0;
						$result[$data_con['db_date']]['WEB']	=	0;
						$result[$data_con['db_date']]['MAP']	=	0;

					}


				}

			}

			$temp = $result;


			//GET SPEND OXM REPORT TABLE

			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid, FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date )";

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

						$t[date("d-m-Y",strtotime($key))]		=	array(
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


						array_push($final_key_result,date("d-m-Y",strtotime($key)));						
						array_push($final_result,$t[date("d-m-Y",strtotime($key))]);
					}
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=	0.00;

				}

				asort($final_result);

				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

				return $out;

		}


                function get_statistics_for_advertiser_campaigns_hourwise($search_array,$start=0,$limit=false){


			$result = array();

			$SQL = "SELECT
						time(h.date_time) as db_date,
						oxcl.clientid,
                                                 FORMAT(ifnull(sum( h.`total_revenue` ),0),2) AS SPEND,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";

			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) = '".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
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
                                                                                                                        "SPEND"=>$data['SPEND']
													);
				 }
			  }

			$temp = $result;


			//GET BKT IMPRESSIONS

			$SQL_BKT_IMP = "SELECT time( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_IMP .=" GROUP BY time( odbm.interval_start )";

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
					}


				}

			}

			$temp = $result;

			//GET BKT CLICKS

			$SQL_BKT_CLK = "SELECT time( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_CLK .=" GROUP BY time( odbm.interval_start )";

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

					}


				}

			}

			$temp = $result;
			
				//GET BKT CONVERSIONS

			$SQL_BKT_CON = "SELECT time(odba.date_time) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) = '".$search_array['sel_date']."'  )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxc.campaignid='".$search_array['sel_advertiser_id']."'";
			}

			$SQL_BKT_CON .=" GROUP BY time(odba.date_time)";

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
					$tot_conversions		=	$data_con['CON'];

						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	0;
						$result[$data_con['db_date']]['CON']	=	$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=       0;

					}


				}

			}

			$temp = $result;
			


				$final_result 	= array();
				$final_key_result = array();
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

						$t[$key]	=	array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);

									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  $resObj['SPEND'];


						array_push($final_key_result,$key);						
						array_push($final_result,$t[$key]);
					}
					
					$final_result =  array_combine($final_key_result,$final_result);
					
					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=	0.00;

				}

				asort($final_result);

				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

				return $out;

		}

                function get_statistics_for_advertiser_campaigns_banners_hourwise($search_array,$start=0,$limit=false){


			$result = array();

			//GET CHILD BANNERS ID

                        $child_banners_id   =   $this->get_child_banners_id($search_array['sel_advertiser_id']);

                        $SQL = "SELECT
						time(h.date_time) as db_date,
						oxcl.clientid,
                                                FORMAT(ifnull(sum( h.`total_revenue` ),0),2) AS SPEND,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";

			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) = '".$search_array['sel_date']."' )";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
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
                                                                                                                        "SPEND"=>$data['SPEND']
													);
				 }
			  }

			$temp = $result;

			//GET BKT IMPRESSIONS

			$SQL_BKT_IMP = "SELECT TIME( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."')";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_IMP .=" GROUP BY time( odbm.interval_start )";

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
					}


				}

			}

			$temp = $result;

			//GET BKT CLICKS

			$SQL_BKT_CLK = "SELECT time( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date'])  AND $search_array['sel_date'] != '' ){

				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) = '".$search_array['sel_date']."')";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_CLK .=" GROUP BY time( odbm.interval_start )";

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

					}


				}

			}

			$temp = $result;


			//GET BKT CONVERSIONS

			$SQL_BKT_CON = "SELECT time(odba.date_time) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


			if(count($search_array) > 0  AND isset($search_array['sel_date']) AND $search_array['sel_date'] != ''){

				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) = '".$search_array['sel_date']."'  )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxb.bannerid IN (".$child_banners_id.")";
			}

			$SQL_BKT_CON .=" GROUP BY time(odba.date_time)";

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
					$tot_conversions		=	$data_con['CON'];

						$result[$data_con['db_date']]['IMP']	=	0;
						$result[$data_con['db_date']]['CLK']	=	0;
						$result[$data_con['db_date']]['CON']	=	$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=       0;

					}


				}

			}

			$temp = $result;


				$final_result 	= array();
				$final_key_result = array();
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

						$t[$key]	=	array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);

									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  $resObj['SPEND'];


						array_push($final_key_result,$key);						
						array_push($final_result,$t[$key]);
					}
					$final_result =  array_combine($final_key_result,$final_result);

					if($final_tot['IMP'] > 0)
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;
					else
					$final_tot['CTR']	=	0.00;

				}

				asort($final_result);

				$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

				return $out;

		}
		
		/*function get_start_date(){
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
		}*/
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
		//Build contents query
                            //$qry   ="SELECT B.bannerid FROM ox_banners B, ox_campaigns C WHERE B.campaignid =C.campaignid AND C.clientid=".$cid;
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
				
				
				function get_monthly_report_for_advertiser($search_array,$start=0,$limit=false){
			
			//print_r($search_array); 
						
			$result = array();  
			
			$SQL = "SELECT oxcl.clientid, 
						ifnull(sum( h.impressions ),0) AS IMP,
						month(h.date_time) as db_month,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
			
			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
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
													);
				 }
			  } 
				
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
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON ( odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_IMP .=" GROUP BY month(odbm.interval_start)";
			
			$query_imp = $this->db->query($SQL_BKT_IMP);
				
				
			  if($query_imp->num_rows>0)
				{
					 $stat_summary_imp =  $query_imp->result_array();
				 
				 	foreach($stat_summary_imp as $data_imp){
						$tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_month']]['IMP'];
						$result[$data_imp['db_month']]['IMP']	=$tot_imp;
					}
				}
				
				
			// GETTING CLICKS
			
			$SQL_BKT_CLK	=	"SELECT 
										oxcl.clientid, 
										month(odbc.interval_start) as db_month,	
										ifnull(sum( odbc.`count` ),0) AS CLICKS
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON ( odbc.creative_id = oxb.bannerid AND date(odbc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_CLK .=" GROUP BY month(odbc.interval_start)";
			
			$query_clk = $this->db->query($SQL_BKT_CLK);
				
				
			  if($query_clk->num_rows>0)
				{
					 $stat_summary_clk =  $query_clk->result_array();
				 
				 	foreach($stat_summary_clk as $data_clk){
						$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['db_month']]['CLK'];
						$result[$data_imp['db_month']]['CLK']	=$tot_clicks;
					}
				}
			
			// GETTING SPEND AMOUNT
			
			$SQL_BKT_SPEND	=	"SELECT 
										oxcl.clientid, 
										month(oxmr.date) as db_month,
										FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
										FROM ox_clients AS oxcl
										JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
										JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
										";
		
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";
			}
			
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
			}
						
			$SQL_BKT_SPEND .=" GROUP BY month(oxmr.date)";
			
			$query_spend = $this->db->query($SQL_BKT_SPEND);
				
				
			  if($query_spend->num_rows>0)
				{
					 $stat_summary_spend =  $query_spend->result_array();
				 
				 	foreach($stat_summary_spend as $data_spend){
						$tot_spend		=	$data_spend['SPEND'] + 0;
						$result[$data_spend['db_month']]['SPEND']	=$tot_spend;
					}
				}
				
				//CALCULATE CTR 
				
				
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

						$final_result[$key]	=	array(
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);

									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  number_format($resObj['SPEND'],2,".",",");


					
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
		
		 function get_statistics_for_banners($search_array,$start=0,$limit=false){


			//print_r($search_array);

			$result = array();

			$campaigns	=	array();
			$banners	=	array();
			$campaigns_final	=	array();
			$banners_final	=	array();

			$SQL = "SELECT
						oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_campaigns AS oxc
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid";


			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
			}
			
			 $SQL .= " WHERE oxc.campaignid NOT IN (1,2)";  	

			$SQL .=" GROUP BY oxb.bannerid";
			
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
																	"CLK"=>$data['CLICKS'] + $campaigns[$data['campaignid']]['CLK'],
																	"SPEND"=>0
															   );

					 }
					 else
					 {
					 	 $campaigns[$data['campaignid']]	= array(
						 											"campaignid"=>$data['campaignid'],
																	"campaignname"=>$data['campaignname'],
																	"IMP"=>$data['IMP'],
																	"CON"=>$data['CONVERSIONS'],
																	"CLK"=>$data['CLICKS'],
																	"SPEND"=>0
															   );
					 }


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
							$banners[$data['campaignid']][$data['bannerid']]['SPEND']			=  0;



					   }
					   else
					   {
							$banners[$data['campaignid']][$data['master_banner']]['IMP']				=  $data['IMP'] + $banners[$data['campaignid']][$data['master_banner']]['IMP'];
							$banners[$data['campaignid']][$data['master_banner']]['CON']				=  $data['CONVERSIONS'] + $banners[$data['campaignid']][$data['master_banner']]['CON'];
							$banners[$data['campaignid']][$data['master_banner']]['CLK']				=  $data['CLICKS'] + $banners[$data['campaignid']][$data['master_banner']]['CLK'];
							$banners[$data['campaignid']][$data['master_banner']]['SPEND']				= 0;
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


			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON ( odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " LEFT JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
			}

			$SQL_BKT_IMP .=" WHERE oxc.campaignid NOT IN (1,2)";

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
																		"CLK"=>$tot_cliks,
																		"SPEND"=>0
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
																	"CLK"=>0 + $prev_campaigns[$data1['campaignid']]['CLK'],
																	"SPEND"=>0
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
							$banners_final[$data1['campaignid']][$data1['bannerid']]['SPEND']			=  0;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];
					   }
					   else
					   {
					 	 	$tot_imp_banner			=	$data1['IMP']  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];

							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  0;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
					  }




					 }
				}


				$prev_campaigns = $campaigns_final;
				$prev_banners 	= $banners_final;

			
			// GET CLICKS


			$SQL_BKT_CLK	=	"SELECT
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										ifnull(sum(odbc.count),0) as CLICKS
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
										";


			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON ( odbc.creative_id = oxb.bannerid AND date(odbc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.creative_id = oxb.bannerid";
			}

			$SQL_BKT_CLK .=" WHERE oxc.campaignid NOT IN (1,2)";


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
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0
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
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0
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
							$banners_final[$data1['campaignid']][$data1['bannerid']]['SPEND']			=  0;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;



					   }
					   else
					   {
					 		$tot_clicks_banner		=	$data1['CLICKS'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];
						 	$tot_imp_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];



							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  0;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;
					  }




					 }

				}

				$prev_campaigns = $campaigns_final;
				$prev_banners 	= $banners_final;
			
			
				
			// GET CONVERSIONS


			$SQL_BKT_CON	=	"SELECT
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										ifnull(count(odba.server_conv_id),0) as CONVERSIONS
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
										";


			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON ( odba.creative_id = oxb.bannerid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.creative_id = oxb.bannerid";
			}

			$SQL_BKT_CON .=" WHERE oxc.campaignid NOT IN (1,2)";

      
			$SQL_BKT_CON .=" GROUP BY oxb.bannerid";

			$query1 = $this->db->query($SQL_BKT_CON);

			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();

					// print_r($stat_summary1);

					foreach($stat_summary1 as $data1){




					if(isset($campaigns_final[$data1['campaignid']])){

							$tot_conversions	=	$data1['CONVERSIONS'] + $campaigns_final[$data1['campaignid']]['CON'];

							$tot_imp		=	0 + $campaigns_final[$data1['campaignid']]['IMP'];
							
							$tot_clicks		=	0 + $campaigns_final[$data1['campaignid']]['CLK'];

							$campaigns_final[$data1['campaignid']]	= array(
						 											"campaignid"=>$data1['campaignid'],
																	"campaignname"=>$data1['campaignname'],
																	"IMP"=>$tot_imp,
																	"CON"=>$tot_conversions,
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0
															   );
					 }
					 else
					 {

							$tot_conversions		=	$data1['CONVERSIONS'] + $prev_campaigns[$data1['campaignid']]['CON'];
						 	$tot_imp		=	0 + $prev_campaigns[$data1['campaignid']]['IMP'];
							$tot_clicks		=	0 + $prev_campaigns[$data1['campaignid']]['CLK'];

						 $campaigns_final[$data1['campaignid']]	= array(
						 											"campaignid"=>$data1['campaignid'],
																	"campaignname"=>$data1['campaignname'],
																	"IMP"=>$tot_imp,
																	"CON"=>$tot_conversions,
																	"CLK"=>$tot_clicks,
																	"SPEND"=>0
															   );
				   	 }

					 // BANNERS SECTION
					  if(!isset($banners_final[$data1['campaignid']][$data1['bannerid']]) AND ($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3)){
							$banners_final[$data1['campaignid']][$data1['bannerid']]	=	array();
					  }


						if($data1['master_banner'] == -1 || $data1['master_banner'] == -2 || $data1['master_banner'] == -3){

							$tot_conversions_banner		=	$data1['CONVERSIONS'] + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
						 	$tot_imp_banner			=	0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['IMP'];
							$tot_clicks_banner			=	0  + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CLK'];



							$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  $tot_conversions_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['SPEND']			=  0;



					   }
					   else
					   {
					 		$tot_conversions_banner		=	$data1['CONVERSIONS'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
						 	$tot_imp_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['IMP'];
							$tot_clicks_banner			=	0  + $banners_final[$data1['campaignid']][$data1['master_banner']]['CLK'];


							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  $tot_conversions_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  0;
					  }




					 }

				}

				$prev_campaigns = $campaigns_final;
				$prev_banners 	= $banners_final;


		
				// GET SPEND OXM REPORT TABLE

				$SQL_BKT_SPEND	=	"SELECT
										oxc.campaignid,campaignname,oxb.bannerid,description,master_banner,
										FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
										FROM ox_campaigns as oxc JOIN ox_banners as oxb ON oxb.campaignid=oxc.campaignid
										";


			if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.bannerid = oxb.bannerid";
			}

			$SQL_BKT_SPEND .=" WHERE oxc.campaignid NOT IN (1,2)";


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


							$banners_final[$data1['campaignid']][$data1['bannerid']]['bannerid']		=  $data1['bannerid'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['master_banner']	=  $data1['master_banner'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['description']		=  $data1['description'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['IMP']				=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CON']				=  0 + $prev_banners[$data1['campaignid']][$data1['bannerid']]['CON'];
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CLK']				=  $tot_clicks_banner;
							$banners_final[$data1['campaignid']][$data1['bannerid']]['CTR']				=  $CTR_banner;
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


							$banners_final[$data1['campaignid']][$data1['master_banner']]['IMP']		=  $tot_imp_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CON']		=  0 + $banners_final[$data1['campaignid']][$data1['master_banner']]['CON'];
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CLK']		=  $tot_clicks_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['CTR']		=  $CTR_banner;
							$banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND']		=  number_format($data1['SPEND'] + $banners_final[$data1['campaignid']][$data1['master_banner']]['SPEND'],2,'.',',');
					  }




					 }
				}

			  //$reports = array("reports_campaigns"=>$campaigns_final,"reports_banners"=>$banners_final);
			 
			  $reports = $banners_final;

			 return $reports;

		}
		
}
