<?php
class Mod_publisher extends CI_Model { 
		
		function get_statistics_for_publisher($search_array,$start=0,$limit=false){ 
			
			$result = array();
			
			$SQL =  "SELECT oxmu.accountid,oxa.affiliateid,oxz.zonename, 
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
						";
						
		if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != '')
		{ 
			$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON ( h.zone_id =oxz.zoneid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON h.zone_id = oxz.zoneid";
			}
			
			$SQL .= " GROUP BY oxmu.id";
			
				$query=$this->db->query($SQL);		
			
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 
				 foreach($stat_summary as $data){
					$result[$data['accountid']]	=	array(
															"UNIMP"=>0,
															"UNCLK"=>0,
															"UNCON"=>0,
															
															"IMP"=>$data['IMP'],
															"CON"=>$data['CONVERSIONS'],
															"CLK"=>$data['CLICKS'],
															"zonename"=>$data['zonename'],
															"affiliateid"=>$data['affiliateid']
													);
				 }
			  } 
				
			$temp = $result;
			
			// GET IMPRESSIONS
			
			$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,  
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
				$SQL_BKT .=  "	LEFT JOIN ox_data_bkt_m AS odbm ON odbm.zone_id = oxz.zoneid";
			}	
			
			$SQL_BKT .= " GROUP BY oxmu.id";
				
				$query1=$this->db->query($SQL_BKT);					
			
			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					$tot_imp							=$data1['IMP'] + $temp[$data1['accountid']]['IMP'];
					$result[$data1['accountid']]['IMP']	=$tot_imp;
					}
				}
			
				$temp = $result;
				
				// GET CLICKS
				
				$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,  									
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
				$SQL_BKT .=  " LEFT JOIN ox_data_bkt_c AS odbc ON odbc.zone_id = oxz.zoneid";
			}	
			
			$SQL_BKT .= " GROUP BY oxmu.id";
				
				$query1=$this->db->query($SQL_BKT);					
			
			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					$tot_clicks		=	$data1['CLICKS'] + $temp[$data1['accountid']]['CLK'];
					$result[$data1['accountid']]['CLK']	= $tot_clicks;
					 }
				}
				
				$temp = $result;
				
					//GETTING  CONVERSIONS
		$SQL_BKT_CON = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,  									
										ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
			
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.zone_id = oxz.zoneid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.zone_id = oxz.zoneid";
			}	
			
			$SQL_BKT_CON .= " GROUP BY oxmu.id";
				
			
				$query1=$this->db->query($SQL_BKT_CON);					
			
			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();
				 
				 	foreach($stat_summary1 as $data1){
					
					$tot_conversions		=	$data1['CONVERSIONS'] + $temp[$data1['accountid']]['CON'];
					$result[$data1['accountid']]['CON']	= $tot_conversions;
					 }
				}
				
				$temp = $result;
				
				// GET REVENUE
				
				$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,  
										FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										";
										
		if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
					$SQL_BKT .=  " LEFT JOIN oxm_report AS oxmr ON (oxmr.zoneid = oxz.zoneid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT .=  " LEFT JOIN oxm_report AS oxmr ON oxmr.zoneid = oxz.zoneid";
			}	
			
			$SQL_BKT .= " GROUP BY oxmu.id";
				
				$query1=$this->db->query($SQL_BKT);					
			
			  if($query1->num_rows>0)
				{
					 $stat_summary1 =  $query1->result_array();
				 
				 	foreach($stat_summary1 as $data1){				
					
						$result[$data1['accountid']]['SPEND']	=	$data1['SPEND'];
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
										"CTR"=>number_format($CTR,2,'.',','),
										"zonename"=>$resObj['zonename'],
										"affiliateid"=>$resObj['affiliateid']
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
		
	public function get_publishers($adv_id, $search_array){
	
		
	
	$result = array();
	$zones	=array();
			
			$SQL = "SELECT oxmu.accountid,oxa.affiliateid,oxz.zonename,zoneid,master_zone,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid";
						
								
											
		  if(count($search_array) > 0 AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON (h.zone_id = oxz.zoneid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " LEFT JOIN ox_data_summary_ad_hourly AS h ON (h.zone_id = oxz.zoneid)";
			}
			
			$SQL .=" WHERE oxmu.accountid=".$adv_id." ";
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
				$SQL_BKT .= " where oxmu.accountid='".$adv_id."'";
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
				$SQL_BKT .= " where oxmu.accountid='".$adv_id."'";
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

			//GET CONVERSIONS
				
				$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,oxz.zoneid,master_zone,
										ifnull(count( odba.`server_conv_id` ),0) AS CONVERSIONS
										FROM oxm_userdetails AS oxmu
										JOIN ox_affiliates AS oxa ON oxa.account_id =  oxmu.accountid
										JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
										
										 ";
			if(count($search_array) > 0  AND $search_array['search_type'] != "all" AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT .=  " JOIN ox_data_bkt_a AS odba ON (odba.zone_id = oxz.zoneid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT .=  " JOIN ox_data_bkt_a AS odba ON (odba.zone_id = oxz.zoneid)";
			}						
				$SQL_BKT .= " where oxmu.accountid='".$adv_id."'";
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
					
							$tot_conversions				=	$data1['CONVERSIONS'] + $temp[$data1['zoneid']]['CON'];
							$tot_imp_zone			=	0  + $temp[$data1['zoneid']]['IMP'];
							$tot_clicks			=	0  + $temp[$data1['zoneid']]['CLK'];						

							$zones[$data1['zoneid']]['zoneid']			=  $data1['zoneid'];	
							$zones[$data1['zoneid']]['master_zone']		=  $data1['master_zone'];
							$zones[$data1['zoneid']]['zonename']		=  $data1['zonename'];
							$zones[$data1['zoneid']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['zoneid']]['CON']				=  $tot_conversions;
							$zones[$data1['zoneid']]['CLK']				=  $tot_clicks;
					   } 
					   else
					   {
					 	 	$tot_conversions				=	$data1['CONVERSIONS'] + $zones[$data1['master_zone']]['CON'];
							$tot_imp_zone			=	0  				 + $zones[$data1['master_zone']]['IMP'];
							$tot_clicks			=	0  				 + $zones[$data1['master_zone']]['CLK'];
							
							$zones[$data1['master_zone']]['IMP']				=  $tot_imp_zone;
							$zones[$data1['master_zone']]['CON']		= $tot_conversions;						$zones[$data1['master_zone']]['CLK']				=  $tot_clicks;
					  }	
					
					 }
				}
			
			$temp = $zones;
			
			// GET REVENUE
			
			$SQL_BKT = 			"SELECT 
										oxmu.accountid,oxa.affiliateid,oxz.zonename,oxz.zoneid,master_zone, 
											FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
										FORMAT(ifnull(sum( oxmr.`publisher_amount` ),0),2) AS PUBSHARE
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
				$SQL_BKT .= " where oxmu.accountid='".$adv_id."'";
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
								$zones[$data1['zoneid']]['PUBSHARE']			=   number_format($data1['PUBSHARE'],2,'.',',');
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
								$zones[$data1['master_zone']]['PUBSHARE']		=  number_format($data1['PUBSHARE']+ $zones[$data1['master_zone']]['PUBSHARE'],2,'.',',');
					 		$zones[$data1['master_zone']]['CTR']				=  $CTR_zone;
					  }	
					
					
															
					 }
				}
			
				return $zones;
	
	
	}
	

 //  GET STATISTICS FOR PUBLISHER AND ZONES BASED ON DATEWISE
 
function get_statistics_for_publisher_zones_datewise($search_array,$start=0,$limit=false){
			
			$result = array();
			
			$SQL = "SELECT 
						date(h.date_time) as db_date,
						oxmu.accountid, 
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM oxm_userdetails AS oxmu
						JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
						JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
						";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON (  h.zone_id = oxz.zoneid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.zone_id = oxz.zoneid)";
			}
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				$SQL .=" WHERE oxmu.accountid='".$search_array['sel_publisher_id']."'";
			}
						
			$SQL .=" GROUP BY date(h.date_time)";
			
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();
				 foreach($stat_summary as $data){
					$result[$data['db_date']]	=	array(
															"UNIMP"	=>0,
															"UNCLK"	=>0,
															"UNCON"	=>0,
															
															"IMP"	=>$data['IMP'],
															"CON"	=>$data['CONVERSIONS'],
															
															"CLK"	=>$data['CLICKS'],
															"SPEND"	=>0,
															"CALL"=>0,
															"WEB"=>0,
															"MAP"=>0,
													);
				 }
			  } 
				
			$temp = $result;
			
			//GET BKT IMPRESSIONS
		
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxmu.accountid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM oxm_userdetails AS oxmu
							JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
							JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
							 ";
							 
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.zone_id = oxz.zoneid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.zone_id = oxz.zoneid";
			}
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				$SQL_BKT_IMP .=" WHERE oxmu.accountid='".$search_array['sel_publisher_id']."'";
			}
						
			$SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
			
			$query1 = $this->db->query($SQL_BKT_IMP);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){

					if(isset($temp[$data_imp['db_date']]['IMP']))
					{
						$tot_imp								=$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
						$result[$data_imp['db_date']]['IMP']	=$tot_imp;
						$result[$data_imp['db_date']]['SPEND']	=0;
						$result[$data_imp['db_date']]['CALL']	=0;
						$result[$data_imp['db_date']]['WEB']	=0;
						$result[$data_imp['db_date']]['MAP']	=0;
						$result[$data_imp['db_date']]['PUBSHARE']	=0;
						$result[$data_imp['db_date']]['UNIMP']	=0;
						$result[$data_imp['db_date']]['UNCLK']	=0;
						$result[$data_imp['db_date']]['UNCON']	=0;
					}
					else
					{
						$tot_imp								=$data_imp['IMP'];
						$result[$data_imp['db_date']]['IMP']	=$tot_imp;
						$result[$data_imp['db_date']]['CLK']	=0;
						$result[$data_imp['db_date']]['CON']	=0;
						$result[$data_imp['db_date']]['SPEND']	=0;
						$result[$data_imp['db_date']]['CALL']	=0;
						$result[$data_imp['db_date']]['WEB']	=0;
						$result[$data_imp['db_date']]['MAP']	=0;
						$result[$data_imp['db_date']]['PUBSHARE']	=0;
						$result[$data_imp['db_date']]['UNIMP']	=0;
						$result[$data_imp['db_date']]['UNCLK']	=0;
						$result[$data_imp['db_date']]['UNCON']	=0;						
					}
					
					
				}
				
			}
			
			$temp = $result;
						
			//GET BKT CLICKS
		
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxmu.accountid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM oxm_userdetails AS oxmu
							JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
							JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.zone_id = oxz.zoneid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.zone_id = oxz.zoneid";
			}
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				$SQL_BKT_CLK .=" WHERE oxmu.accountid='".$search_array['sel_publisher_id']."'";
			}
						
			$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
			$query1 = $this->db->query($SQL_BKT_CLK);
				
				
			if($query1->num_rows>0)
			{
				$stat_imp =  $query1->result_array();
				
				foreach($stat_imp as $data_imp){
					
					
					if(isset($temp[$data_imp['db_date']]['CLK']))
					{
						$tot_clicks								=$data_imp['CLK'] + $temp[$data_imp['db_date']]['CLK'];
						$result[$data_imp['db_date']]['CLK']	=$tot_clicks;
						$result[$data_imp['db_date']]['SPEND']	=0;
						$result[$data_imp['db_date']]['CALL']	=0;
						$result[$data_imp['db_date']]['WEB']	=0;
						$result[$data_imp['db_date']]['MAP']	=0;
						$result[$data_imp['db_date']]['PUBSHARE']	=0;
						
						$result[$data_imp['db_date']]['UNIMP']	=0;
						$result[$data_imp['db_date']]['UNCLK']	=0;
						$result[$data_imp['db_date']]['UNCON']	=0;
					}
					else
					{
					$tot_clicks		=	$data_imp['CLK'];
					
						$result[$data_imp['db_date']]['IMP']	=0;
						$result[$data_imp['db_date']]['CLK']	=$tot_clicks;
						$result[$data_imp['db_date']]['CON']	=0;
						$result[$data_imp['db_date']]['SPEND']	=0;
						$result[$data_imp['db_date']]['CALL']	=0;
						$result[$data_imp['db_date']]['WEB']	=0;
						$result[$data_imp['db_date']]['MAP']	=0;
						$result[$data_imp['db_date']]['PUBSHARE']	=0;
						$result[$data_imp['db_date']]['UNIMP']	=0;
						$result[$data_imp['db_date']]['UNCLK']	=0;
						$result[$data_imp['db_date']]['UNCON']	=0;
					
					}
				}
				
			}
			
			$temp = $result;
			
			
			//GET BKT CONVERSIONS
		
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, oxmu.accountid, IFNULL( count( odba.`server_conv_id` ) , 0 ) AS CON
							FROM oxm_userdetails AS oxmu
							JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
							JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
							 ";	
			
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.zone_id = oxz.zoneid AND date(odba.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON odba.zone_id = oxz.zoneid";
			}
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				$SQL_BKT_CON .=" WHERE oxmu.accountid='".$search_array['sel_publisher_id']."'";
			}
						
			$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";
			
			$query1 = $this->db->query($SQL_BKT_CON);
				
				
			if($query1->num_rows>0)
			{
				$stat_con =  $query1->result_array();
				
				foreach($stat_con as $data_con){
					
					
					if(isset($temp[$data_con['db_date']]['CON']))
					{
						$tot_conversions								=$data_con['CON'] + $temp[$data_con['db_date']]['CON'];
						$result[$data_con['db_date']]['CON']	=$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=0;
						$result[$data_con['db_date']]['CALL']	=0;
						$result[$data_con['db_date']]['WEB']	=0;
						$result[$data_con['db_date']]['MAP']	=0;
						$result[$data_con['db_date']]['PUBSHARE']	=0;
						$result[$data_con['db_date']]['UNIMP']	=0;
						$result[$data_con['db_date']]['UNCLK']	=0;
						$result[$data_con['db_date']]['UNCON']	=0;
					}
					else
					{
					$tot_conversions		=	$data_con['CON'];
					
						$result[$data_con['db_date']]['IMP']	=0;
						$result[$data_con['db_date']]['CLK']	=0;
						$result[$data_con['db_date']]['CON']	=$tot_conversions;
						$result[$data_con['db_date']]['SPEND']	=0;
						$result[$data_con['db_date']]['CALL']	=0;
						$result[$data_con['db_date']]['WEB']	=0;
						$result[$data_con['db_date']]['MAP']	=0;
						$result[$data_con['db_date']]['PUBSHARE']	=0;
						$result[$data_con['db_date']]['UNIMP']	=0;
						$result[$data_con['db_date']]['UNCLK']	=0;
						$result[$data_con['db_date']]['UNCON']	=0;
					
					}
				}
				
			}
			
			$temp = $result;
			
			
		/**************************************	UNIQUE IMPRESSIONS AND CLICKS BY DATEWISE	***************************************************/

			// GET UNIQUE IMPRESSIONS
				
			$SQL = "SELECT db_date,count(db_date) as UIMP,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
						$SQL .=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND oxu.impressions>0 AND date(oxu.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."'";
			}else{
						$SQL.=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND oxu.impressions>0";
						
			}

			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.date_time))";
			
			$SQL .=" UNION (SELECT date(oxum.interval_start) as db_date,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
						if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
						$SQL .=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND date(oxum.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."'";
			}else{

						$SQL.=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."'";
				}
			
			
			$SQL .=" GROUP BY oxum.`viewer_id`,oxum.`creative_id`,oxum.zone_id,date(oxum.interval_start))) as UIMP GROUP BY db_date";
			
			$query = $this->db->query($SQL);
			
			 if($query->num_rows>0)
			 {
				 $stat_summary =  $query->result_array();

				 foreach($stat_summary as $unique_data){
				 	
					if(isset($result[$unique_data['db_date']])){
					
						$result[$unique_data['db_date']]['SPEND']	=0;
						$result[$unique_data['db_date']]['CALL']	=0;
						$result[$unique_data['db_date']]['WEB']	=0;
						$result[$unique_data['db_date']]['MAP']	=0;
						$result[$unique_data['db_date']]['PUBSHARE']	=0;
						$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
						$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
						$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
					
						
						$result[$unique_data['db_date']]['UNIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_date']]['UNCLK']	= 0; 
						
							
					}
					else
					{
						$result[$unique_data['db_date']]['IMP']		= 0;
						$result[$unique_data['db_date']]['CLK']		= 0;
						$result[$unique_data['db_date']]['CON']		= 0;
						$result[$unique_data['db_date']]['SPEND']	=0;
						$result[$unique_data['db_date']]['CALL']	=0;
						$result[$unique_data['db_date']]['WEB']	=0;
						$result[$unique_data['db_date']]['MAP']	=0;
						$result[$unique_data['db_date']]['PUBSHARE']	=0;
						
						$result[$unique_data['db_date']]['UNIMP']	= $unique_data['UIMP'];
						$result[$unique_data['db_date']]['UNCLK']	= 0; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;

		
			//GET UNIQUE CLICKS
		
				$SQL = "SELECT db_date,count(db_date) as UCLK,ifnull(sum(vcount),0) as vcount FROM ((SELECT  date(oxu.date_time) as db_date,oxu.viewer_id,oxu.creative_id,ifnull(sum(oxu.impressions),0) as vcount FROM `ox_unique` as oxu JOIN ox_zones as oxz ON oxz.zoneid=oxu.`zone_id` JOIN ox_affiliates AS oxa ON oxa.affiliateid = oxz.affiliateid";
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
						$SQL .=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND oxu.clicks>0 AND DATE(oxu.date_time)BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."'";
				}
				else{
						$SQL.=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND oxu.clicks>0";
			
				}
				
			}
			
			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.date_time))";
			
			$SQL .=" UNION (SELECT date(oxuc.interval_start) as db_date,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_affiliates as oxa ON oxa.affiliateid=oxz.affiliateid";
						
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				
				if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
						$SQL .=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND DATE(oxuc.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."'";
				}else{
					
						$SQL.=" WHERE oxa.account_id ='".$search_array['sel_publisher_id']."' AND date(oxuc.interval_start)";
					
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
						$result[$unique_data['db_date']]['SPEND']	=0;
						$result[$unique_data['db_date']]['CALL']	=0;
						$result[$unique_data['db_date']]['WEB']	=0;
						$result[$unique_data['db_date']]['MAP']	=0;
						$result[$unique_data['db_date']]['PUBSHARE']	=0;
						
						$result[$unique_data['db_date']]['UNIMP']	= $temp[$unique_data['db_date']]['UNIMP'];
						$result[$unique_data['db_date']]['UNCLK']	= $unique_data['UCLK']; 
						
							
					}
					else
					{
						$result[$unique_data['db_date']]['IMP']		= 0;
						$result[$unique_data['db_date']]['CLK']		= 0;
						$result[$unique_data['db_date']]['CON']		= 0;
						$result[$unique_data['db_date']]['SPEND']	=0;
						$result[$unique_data['db_date']]['CALL']	=0;
						$result[$unique_data['db_date']]['WEB']	=0;
						$result[$unique_data['db_date']]['MAP']	=0;
						$result[$unique_data['db_date']]['PUBSHARE']	=0;
						
						$result[$unique_data['db_date']]['UNIMP']	= 0;
						$result[$unique_data['db_date']]['UNCLK']	= $unique_data['UCLK']; 
					}
				 
													
				 }
			  } 
				
			$temp = $result;
			


			//print_r($temp);die();
						
		/**************************************	UNIQUE IMPRESSIONS AND CLICKS BY DATEWISE	***************************************************/
			
			
			
			//GET SPEND OXM REPORT TABLE
		
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxmu.accountid, 
							FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
					ifnull(sum(oxmr.click_to_call),0) AS 'CALL',
				   ifnull(sum(oxmr.click_to_web),0) AS 'WEB', 
					ifnull(sum(oxmr.click_to_map),0) AS 'MAP',
								FORMAT(ifnull(sum( oxmr.`publisher_amount` ),0),2) AS PUBSHARE
							FROM oxm_userdetails AS oxmu
							JOIN ox_affiliates AS oxa ON oxa.account_id = oxmu.accountid
							JOIN ox_zones AS oxz ON oxz.affiliateid = oxa.affiliateid
							 ";

			if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){ 
				
				$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON ( oxmr.zoneid = oxz.zoneid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
			}
			else
			{
				$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON  oxmr.zoneid = oxz.zoneid";
			}
			
			if(isset($search_array['sel_publisher_id'])  AND $search_array['sel_publisher_id'] != ''){
				$SQL_BKT_SPEND .=" WHERE oxmu.accountid='".$search_array['sel_publisher_id']."'";
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
						
						$result[$data_spend['db_date']]['PUBSHARE']	=$data_spend['PUBSHARE'];
						
						$result[$data_spend['db_date']]['CON']		=0;
						
						$result[$data_spend['db_date']]['IMP']		=0 + $temp[$data_spend['db_date']]['IMP'];
						$result[$data_spend['db_date']]['CLK']		=0 + $temp[$data_spend['db_date']]['CLK'];
						$result[$data_spend['db_date']]['CON']		=0 + $temp[$data_spend['db_date']]['CON'];
						
						$result[$data_spend['db_date']]['UNIMP']	=0 + $temp[$data_spend['db_date']]['UNIMP'];
						$result[$data_spend['db_date']]['UNCLK']	=0 + $temp[$data_spend['db_date']]['UNCLK'];
						$result[$data_spend['db_date']]['UNCON']	=0 + $temp[$data_spend['db_date']]['UNCON'];
					}
					else
					{
						$result[$data_spend['db_date']]['IMP']		=0;
						$result[$data_spend['db_date']]['CLK']		=0;
						$result[$data_spend['db_date']]['CON']		=0;
						$result[$data_spend['db_date']]['SPEND']	= $tot_spend;
						
						$result[$data_spend['db_date']]['CALL']	= $tot_call;
						$result[$data_spend['db_date']]['WEB']	= $tot_web;
						$result[$data_spend['db_date']]['MAP']	= $tot_map;
						
						$result[$data_spend['db_date']]['PUBSHARE']	= $data_spend['PUBSHARE'];
						$result[$data_spend['db_date']]['UNIMP']	=0 + $temp[$data_spend['db_date']]['UNIMP'];
						$result[$data_spend['db_date']]['UNCLK']	=0 + $temp[$data_spend['db_date']]['UNCLK'];
						$result[$data_spend['db_date']]['UNCON']	=0 + $temp[$data_spend['db_date']]['UNCON'];
					}
				}
			}

			$temp = $result;
				
			$final_result 	=array();
				$final_tot 		=array("UNIMP"=>0, "UNCLK"=>0, "UNCON"=>0, "IMP"=>0, "CON"=>0,"PUBSHARE"=>0, "CLK"=>0, "SPEND"=>0,'CALL'=>0,'WEB'=>0,'MAP'=>0,"CTR"=>0);				
				if(count($result) > 0) {
					foreach($result as $key => $resObj) {
						
						if($resObj['IMP'] > 0) {
							$CTR		=	($resObj['CLK']/$resObj['IMP'])*100;
							$CTR		=	number_format($CTR,2,'.',',');
						}
						else
						{
							$CTR		= 	0.00;
						} 
						
						$t	=	array(
										"date"=>date("d-m-Y",strtotime($key)),
										"UNIMP"=>$resObj['UNIMP'],
										"UNCLK"=>$resObj['UNCLK'],
										"UNCON"=>$resObj['UNCON'],										
										"IMP"=>$resObj['IMP'],
										"CON"=>$resObj['CON'],
										"CLK"=>$resObj['CLK'],
										"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
										"CALL"=>$resObj['CALL'],
										"WEB"=>$resObj['WEB'],
										"MAP"=>$resObj['MAP'],
										"PUBSHARE"=>number_format($resObj['PUBSHARE'],2,'.',','),
										"CTR"=>number_format($CTR,2,'.',',')
									);
						
									$final_tot['IMP']	+=  $resObj['IMP'];
									$final_tot['UNIMP']	+=  $resObj['UNIMP'];
									$final_tot['UNCON']	+=  $resObj['UNCON'];
									$final_tot['CON']	+=  $resObj['CON'];
									$final_tot['UNCLK']	+=  $resObj['UNCLK'];
									$final_tot['CLK']	+=  $resObj['CLK'];
									$final_tot['SPEND']	+=  $resObj['SPEND'];
									$final_tot['CALL']	+=  $resObj['CALL'];
									$final_tot['WEB']	+=  $resObj['WEB'];
									$final_tot['MAP']	+=  $resObj['MAP'];
									$final_tot['PUBSHARE']	+=  $resObj['PUBSHARE'];
									
												
						array_push($final_result,$t);
					}
					
					$final_tot['CTR']	=  ($final_tot['CLK']/$final_tot['IMP'])*100;;
					
				}
				
				asort($final_result);
				
				$out = array("stat_list"=>$final_result, "tot_val"=>$final_tot);
				
				return $out;
				 
		}


		
		function get_start_date($account_id){
			$query = $this->db->query("SELECT date(`date_created`) as start_date FROM `ox_users` WHERE default_account_id='".$account_id."'");
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



}
