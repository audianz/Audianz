<?php 
class Mod_statistical_report extends CI_Model{

	// Retreive Impressions,clicks and Conversions for the monthly basis
	function get_monthly_report_for_advertiser($search_array,$stat_type =FALSE){
			
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
					
				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND month(h.date_time)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(h.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND month(odbm.interval_start)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odbm.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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

			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'  AND month(odbc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odbc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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
					$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND month(odba.date_time)=MONTH(CURRENT_TIMESTAMP)";
					break;
				case 'past_six_months':
					$SQL_BKT_CON .=" WHERE  oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odba.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND month(oxmr.date)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxmr.date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}

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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.impressions>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL.=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.impressions>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}


			}

			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";

			$SQL .=" UNION (SELECT month(oxum.interval_start) as db_month,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_banners as oxb ON (oxum.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND MONTH(oxum.interval_start)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL.=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxum.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.clicks>0 AND MONTH(oxu.date_time)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL.=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.clicks>0 AND date(oxu.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
						break;
				}


			}

			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,month(oxu.date_time))";

			$SQL .=" UNION (SELECT month(oxuc.interval_start) as db_month,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_banners as oxb ON (oxuc.`creative_id`=oxb.bannerid)  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'current_month':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND MONTH(oxuc.interval_start)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'past_six_months':
						$SQL.=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxuc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()";
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

			return $out;
		}
	}

	// Retrieve click to call,click to web and click to map for last week
	function get_week_click_to_action_for_advertiser($search_array,$stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',DATE(date) AS 'date'
					from oxm_report As r ";
			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != '')
			{
				switch ($stat_type)
				{
					case 'last_seven_days':
						$SQL.="where clientid=".$search_array['sel_advertiser_id']." AND DATE(date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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
	function get_click_to_action_for_advertiser($search_array,$stat_type=FALSE)
	{
		if($stat_type)
		{
			$SQL="SELECT ifnull(sum(r.click_to_call),0) AS 'CALL',
					ifnull(sum(r.click_to_web),0) AS 'WEB',
					ifnull(sum(r.click_to_map),0) AS 'MAP',date
					from oxm_report As r ";

			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != '')
			{
				switch ($stat_type)
				{
					case 'current_month':
						$SQL.="where clientid=".$search_array['sel_advertiser_id']." AND month(date)=MONTH(CURRENT_TIMESTAMP)";
						break;
					case 'today':
						$SQL.="where clientid=".$search_array['sel_advertiser_id']."  AND DATE(date)=DATE(now())";
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
	// DATEWISE REPORT BASED ON CONDITION
	function get_date_report_for_advertiser($search_array,$stat_type='')
	{
		//print_r($search_array);

		if($stat_type !='')
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
				if($stat_type ==='today')
				{
					$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(h.date_time)=CURDATE()";
				}else if($stat_type==="last_seven_days")
				{
					$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(h.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odbm.interval_start)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odbm.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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

			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
					
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'  AND date(odbc.interval_start)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odbc.interval_start)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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

			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){
					
				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'  AND date(odba.date_time)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL_BKT_CON .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(odba.date_time)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
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


			if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxmr.date)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxmr.date)  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}


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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.impressions>0 AND date(oxu.date_time)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.impressions>0 AND date(oxu.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
			}

			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.`date_time`))";

			$SQL .=" UNION (SELECT date(oxum.interval_start) as db_date,oxum.viewer_id,oxum.creative_id,ifnull(sum(oxum.count),0) as vcount  FROM `ox_data_bkt_unique_m` as oxum JOIN ox_zones as oxz ON oxz.zoneid=oxum.`zone_id` JOIN ox_banners as oxb ON (oxum.`creative_id`=oxb.bannerid) JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxum.interval_start)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxum.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
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

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.clicks>0 AND date(oxu.date_time)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND oxu.clicks>0 AND date(oxu.date_time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
			}

			$SQL .=" GROUP BY oxu.viewer_id,oxu.creative_id,oxu.zone_id,date(oxu.`date_time`))";

			$SQL .=" UNION (SELECT date(oxuc.interval_start) as db_date,oxuc.viewer_id,oxuc.creative_id,ifnull(sum(oxuc.count),0) as vcount  FROM `ox_data_bkt_unique_c` as oxuc JOIN ox_zones as oxz ON oxz.zoneid=oxuc.`zone_id` JOIN ox_banners as oxb ON (oxuc.`creative_id`=oxb.bannerid) JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";

			if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

				switch($stat_type)
				{
					case 'today':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxuc.interval_start)=CURDATE()";
						break;
					case 'last_seven_days':
						$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' AND date(oxuc.interval_start) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()";
						break;
				}
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
			
		$SQL .= " WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";

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
			
		$SQL_BKT_IMP .= " WHERE oxcl.clientid='".$search_array['sel_advertiser_id']."'";

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
			
		$SQL_BKT_CLK .= " WHERE oxcl.clientid='".$search_array['sel_advertiser_id']."'";

		$SQL_BKT_CLK .=" GROUP BY DATE( odbm.country )";
			
		$SQL_BKT_CLK .=" ORDER BY DATE( odbm.interval_start ) DESC";
			
		$query1 = $this->db->query($SQL_BKT_CLK);


		if($query1->num_rows>0)
		{
			$stat_clk =  $query1->result_array();

			foreach($stat_clk as $data_clk){
					
					
				if(isset($temp[$data_clk['country_code']]['CLK'])){
					$tot_clicks		=	$data_clk['CLK'] + $temp[$data_clk['country_code']]['CLK'];
					$result[$data_clk['country_code']]['CLK']	=$tot_clicks;
				}
				else
				{
					$tot_clicks		=	$data_clk['CLK'];

					$result[$data_clk['country_code']]['IMP']			=	0;
					$result[$data_clk['country_code']]['CLK']			=	$tot_clicks;
					$result[$data_clk['country_code']]['COUNTRY']		=	$data_clk['country_name'];
					$result[$data_clk['country_code']]['COUNTRY_CODE']	=	$data_clk['country_code'];

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

	// Getting Top Campaigns and Top Banners
	function get_top_performers_for_advertiser($search_array)
	{
		//print_r($search_array);

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

			
		if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){

			$SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";

		}

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

			
		if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

			$SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";

		}

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

				if(isset($temp[$data_imp['camp_name']]['CLK'])){
					$tot_clicks		=	$data_clk['CLICKS'] + $temp[$data_clk['camp_name']]['CLK'];
					//echo $tot_clicks;

					$result[$data_imp['camp_name']]['CLK']	=$tot_clicks;
				}else{
					$tot_clicks		=	$data_imp['CLK'];

					$result[$data_imp['camp_name']]['IMP']	=	0;
					$result[$data_imp['camp_name']]['CLK']	=	$tot_clicks;
					$result[$data_imp['camp_name']]['CON']	=	0;
					$result[$data_imp['camp_name']]['SPEND']	=	0;
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

			
		if(count($search_array) > 0 AND isset($search_array['sel_advertiser_id']) AND $search_array['sel_advertiser_id'] != ''){

			$SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."' ";

		}

		$SQL_BKT_SPEND .=" GROUP BY date(oxmr.date)";
			
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
						"CTR"=>number_format($CTR,2,'.',','),
						"IMP"=>$resObj['IMP'],
						"CLK"=>$resObj['CLK'],
						"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
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

		$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);

		return $out;
	}

	/* Retreive Top 5 Banners */
	function get_statistics_for_top_banners($adv_id,$search_array,$start=0,$limit=false){

		//print_r($search_array);
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

			
		$SQL .=" WHERE oxc.clientid='".$adv_id."'";

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
				array_push($banners_result,$banners_value);

		}
			
		arsort($banners_result);

		return $banners_result;

	}


}
