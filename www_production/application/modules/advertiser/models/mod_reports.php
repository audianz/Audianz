<?php
class Mod_reports extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function website()
	{
		/*$this->db->select('*');
		 $query=$this->db->get('ox_affiliates');
		if($query->num_rows>0)
		{
		return $query->result();
		}
		else
		{
		return FALSE;
		}*/
		$clientid	=$this->session->userdata('session_advertiser_id');
		$SQL="SELECT oxa.affiliateid,oxa.name  FROM ox_affiliates AS oxa
		JOIN ox_zones AS oxz ON oxa.affiliateid=oxz.affiliateid
		JOIN ox_ad_zone_assoc AS oaza ON oaza.zone_id=oxz.zoneid
		JOIN ox_banners AS oxb ON oxb.bannerid=oaza.ad_id
		JOIN ox_campaigns AS oxc ON oxc.campaignid=oxb.campaignid
		WHERE oxc.clientid=$clientid
		GROUP BY oxa.affiliateid";
			
		$query=$this->db->query($SQL);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function get_website_campaign($website_id=false)
	{
		$clientid	=$this->session->userdata('session_advertiser_id');
		$SQL="SELECT oxc.campaignid,oxc.campaignname  FROM ox_campaigns AS oxc

		JOIN ox_banners AS oxb ON oxb.campaignid=oxc.campaignid
		JOIN ox_ad_zone_assoc AS oaza ON oaza.ad_id =oxb.bannerid
		JOIN ox_zones AS oxz ON oxz.zoneid =oaza.zone_id
		JOIN ox_affiliates AS oxa ON oxa.affiliateid =oxz.affiliateid
		WHERE oxc.clientid=$clientid AND oxa.affiliateid=$website_id
		GROUP BY oxc.campaignid";

		$query=$this->db->query($SQL);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function campaign($clientid)
	{
		$this->db->select('*');
		$this->db->where('clientid',$clientid);
		$query=$this->db->get('ox_campaigns');
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}

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




	//Export to Excel Payments
	function export_report_details_excel($data)
	{
		
		$clientid	=$this->session->userdata('session_advertiser_id');

		$filename = str_replace(' ','_',$this->session->userdata('session_advertiser_contact'));
		$filename .= "_".$this->lang->line('label_report_adv_history').date('d.m.Y');

		//$filename = 'payments';
		// Create new PHPExcel object
		//echo date('H:i:s') . " Create new PHPExcel object\n";
		$objPHPExcel = new PHPExcel();
		$workSheet	=	 $objPHPExcel->getActiveSheet();
		// Set properties
		//echo date('H:i:s') . " Set properties\n";
		$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
		$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history'));
		$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history'));
		$objPHPExcel->getSecurity()->setLockWindows(true);
		$objPHPExcel->getSecurity()->setLockStructure(true);
		$objPHPExcel->getSecurity()->setWorkbookPassword("PHPExcel");
			
		//Set default style
		$workSheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
		$workSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			
			
		// Set column width
			
		$workSheet->getColumnDimension('B')->setAutoSize(true);
		$workSheet->getColumnDimension('C')->setWidth(20);
		$workSheet->getColumnDimension('D')->setWidth(20);
		$workSheet->getColumnDimension('E')->setWidth(20);
		$workSheet->getColumnDimension('F')->setWidth(20);


		//Merge Cells for Heading
		$workSheet->mergeCells('B3:F4');
		$workSheet->getRowDimension(9)->setRowHeight(20);
		//$workSheet->getRowDimension(6)->setRowHeight(20);
			

		//Leading heading style
		$workSheet->duplicateStyleArray(
				array(
						'font'	=> array(
								'bold'	=> false,
								'italic'=> false,
								'size'	=> 16,
								'color'	=>array('rgb' => '333')
						),
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
								'wrap'       => true
						)
				),
				'B3:C4'
		);
			
		//Style for table heading
		$tabletitleArray	= array(
				'font' 		=> array(
						'bold'		=> true,
						'italic'	=> false,
						'size'		=> 12,
						'color'		=>array('rgb' => 'ffffff')
				),
				'fill'		=> array(
						'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
						'rotation'		=> 90,
						'startcolor'	=> array('rgb' => '4E5A7A')
				)
		);
		//Alignment for table heading
		$tableDataArray	= array(
				'font'	=> array(
						'bold'	=> false,
						'italic'=> false,
						'size'	=> 10.5,
						'color'	=>array('rgb' => '333')
				),
				'alignment'	=> array(
						'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
						'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'wrap'			=> true
				),
				'fill'		=> array(
						'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
						'rotation'		=> 90,
						'startcolor'	=> array('rgb' => 'DDE4F4')
				)
					
		);
			
		//Style for date
		$dateArray	= array(
				'font'=>array('bold'=>true),
				'alignment' =>array(
						'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
				)
		);

		$borderArray = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '4E5A7A'),
						)
				)
		);
		/*Style for Debits*/
		$debitArray	=		array(
				'font'=>array('size'=>10.5)
		);

		$fromdate=$data['from_date'];
		$todate=$data['to_date'];
			
		$websiteid=$data['websiteid'];
		$campaignid=$data['campaignid'];
		$clientid	=$this->session->userdata('session_advertiser_id');

		$this->db->where('campaignid',$campaignid);

		$query=$this->db->get('ox_campaigns');
		if($query->num_rows>0)
		{
			$qry=$query->row();
			($qry->campaignname!='')?$cname=$qry->campaignname:$cname=$this->lang->line('label_reports_all_campaign');
		}
		else
		{
			$cname=$this->lang->line('label_reports_all_campaign');
		}


		$this->db->where('affiliateid',$websiteid);
		$query=$this->db->get('ox_affiliates');
		if($query->num_rows>0)
		{
			$qry1=$query->row();
			($qry1->name!='')?$webname=$qry1->name:$webname=$this->lang->line('label_reports_all_website');
		}
		else
		{
			$webname=$this->lang->line('label_reports_all_website');
		}
			


			

		$workSheet->getStyle('B9:L9')->applyFromArray($tabletitleArray);

			
		//set default heading and location
		$objPHPExcel->setActiveSheetIndex(0);
			
		$workSheet->SetCellValue('B3', $this->lang->line('label_reports_adv'));
		$workSheet->mergeCells('B6:C7');
		$workSheet->SetCellValue('B6', $this->lang->line('label_website_name').$webname);
		$workSheet->mergeCells('F6:G7');
		$workSheet->SetCellValue('F6', $this->lang->line('label_campaign_name').$cname);


		$workSheet->SetCellValue('B9',$this->lang->line('label_reports_date'));
		$workSheet->SetCellValue('C9',$this->lang->line('label_reports_imp'));
		$workSheet->SetCellValue('D9',$this->lang->line('label_reports_uniimp'));
		$workSheet->SetCellValue('E9',$this->lang->line('label_reports_clk'));
		$workSheet->SetCellValue('F9', $this->lang->line('label_reports_uniclk'));
		$workSheet->SetCellValue('G9', $this->lang->line('label_reports_conv'));
		$workSheet->SetCellValue('H9', $this->lang->line('label_reports_call'));
		$workSheet->SetCellValue('I9', $this->lang->line('label_reports_web'));
		$workSheet->SetCellValue('J9', $this->lang->line('label_reports_map'));
		$workSheet->SetCellValue('K9', $this->lang->line('label_reports_ctr'));
		$workSheet->SetCellValue('L9', $this->lang->line('label_reports_spend'));
			
			


		$result=array();

		if($websiteid!='all' && $campaignid!='all')
		{
			$SQL = "SELECT
			date(h.date_time) as db_date,
			ifnull(sum( h.impressions ),0) AS IMP,
			ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
			ifnull(sum( h.`clicks` ),0) AS CLICKS
			FROM ox_data_summary_ad_hourly AS h
			where zone_id in (select zoneid from ox_zones where affiliateid='$websiteid')
			and ad_id in (select bannerid from ox_banners where campaignid ='$campaignid')
			and date(h.date_time) BETWEEN '".$fromdate."' AND '".$todate."'
			";
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
			FROM `ox_data_bkt_m` AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners WHERE campaignid ='$campaignid' )
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
			FROM ox_data_bkt_c AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid ='$campaignid')
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			
			";
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date,IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
			FROM ox_data_bkt_a AS odba
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid ='$campaignid')
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date( odba.date_time) BETWEEN '".$fromdate."' AND '".$todate."'
					
			";
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date,
			FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
			ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
			ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
			ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
			FROM oxm_report AS oxmr
			where oxmr.zoneid in(select zoneid from ox_zones where affiliateid='$websiteid')
			and clientid='$clientid'
			and campaignid='$campaignid'
			and date(oxmr.date) BETWEEN '".$fromdate."' AND '".$todate."'
			
			";
			$SQL_UNIQUE = "SELECT date(oxu.date_time) as db_date,
			ifnull(sum( oxu.impressions ),0) AS UIMP,
			ifnull(sum( oxu.`clicks` ),0) AS UCLICKS
			FROM ox_unique AS oxu
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid ='$campaignid')
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date(oxu.date_time) BETWEEN '".$fromdate."' AND '".$todate."'
			";

			$SQL_BKT_UNIIMP = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS UIMP
			FROM ox_data_bkt_unique_m AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid ='$campaignid')
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";

			$SQL_BKT_UNICLK = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS UCLICKS
			FROM ox_data_bkt_unique_c AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid ='$campaignid')
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			and date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";
		}
		else if($websiteid=='all' && $campaignid!='all')
		{
			$SQL = "SELECT
			date(h.date_time) as db_date,
			oxcl.clientid,
			ifnull(sum( h.impressions ),0) AS IMP,
			ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
			ifnull(sum( h.`clicks` ),0) AS CLICKS
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid,
			FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
			ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
			ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
			ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_UNIQUE = "SELECT date(oxu.date_time) as db_date,
			oxcl.clientid,
			ifnull(sum( oxu.impressions ),0) AS UIMP,
			ifnull(sum( oxu.`clicks` ),0) AS UCLICKS
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
			$SQL_BKT_UNIIMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS UIMP
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'

			";

			$SQL_BKT_UNICLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS UCLICKS
			FROM ox_clients AS oxcl
			JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
			JOIN ox_banners AS oxb ON oxb.campaignid = '$campaignid'
			";
		}
		else if($websiteid!='all' && $campaignid=='all')
		{
			$SQL = "SELECT
			date(h.date_time) as db_date,
			ifnull(sum( h.impressions ),0) AS IMP,
			ifnull(sum( h.`conversions` ),0) AS CONVERSIONS,
			ifnull(sum( h.`clicks` ),0) AS CLICKS
			FROM ox_data_summary_ad_hourly AS h
			where zone_id in (select zoneid from ox_zones where affiliateid='$websiteid')
			and ad_id in (select bannerid from ox_banners where campaignid in
			(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			and date(h.date_time) BETWEEN '".$fromdate."' AND '".$todate."'
			";
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
			FROM `ox_data_bkt_m` AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
			FROM ox_data_bkt_c AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			
			";
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date,IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
			FROM ox_data_bkt_a AS odba
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(odba.date_time ) BETWEEN '".$fromdate."' AND '".$todate."'
					
			";
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date,
			FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
			ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
			ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
			ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
			FROM oxm_report AS oxmr
			where oxmr.zoneid in(select zoneid from ox_zones where affiliateid='$websiteid')
			and clientid='$clientid'
			AND date(oxmr.date) BETWEEN '".$fromdate."' AND '".$todate."'
			
			";
			$SQL_UNIQUE = "SELECT date(oxu.date_time) as db_date,
			ifnull(sum( oxu.impressions ),0) AS UIMP,
			ifnull(sum( oxu.`clicks` ),0) AS UCLICKS
			FROM ox_unique AS oxu
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(oxu.date_time) BETWEEN '".$fromdate."' AND '".$todate."'
			";

			$SQL_BKT_UNIIMP = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS UIMP
			FROM ox_data_bkt_unique_m AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";

			$SQL_BKT_UNICLK = "SELECT DATE( odbm.interval_start ) AS db_date,IFNULL( SUM( odbm.`count` ) , 0 ) AS UCLICKS
			FROM ox_data_bkt_unique_c AS odbm
			WHERE creative_id in(SELECT bannerid FROM ox_banners 
			WHERE campaignid in(SELECT campaignid FROM `ox_campaigns` WHERE clientid='$clientid'))
			AND zone_id in(SELECT zoneid FROM ox_zones WHERE affiliateid='$websiteid')
			AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."'
			";
		}
		else
		{
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
			$SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$fromdate."' AND '".$todate."' ) and oxc.clientid='$clientid'";
			
			$SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."' ) and oxc.clientid='$clientid'";
				
			$SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."' )  and oxc.clientid='$clientid'";
				
			$SQL_BKT_CON = "SELECT DATE( odba.date_time ) AS db_date, oxcl.clientid, IFNULL( COUNT( odba.`server_conv_id` ) , 0 ) AS CON
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_BKT_CON .=  " JOIN ox_data_bkt_a AS odba ON (odba.creative_id = oxb.bannerid AND date(odba.date_time) BETWEEN '".$fromdate."' AND '".$todate."' ) and oxc.clientid='$clientid'";
				
			$SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid,
					FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
					ifnull(sum( oxmr.`click_to_call` ),0) AS 'CALL',
					ifnull(sum( oxmr.`click_to_web` ),0) AS 'WEB',
					ifnull(sum( oxmr.`click_to_map` ),0) AS 'MAP'
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$fromdate."' AND '".$todate."' )  and oxc.clientid='$clientid'";
				
			$SQL_UNIQUE = "SELECT date(oxu.date_time) as db_date,
					oxcl.clientid,
					ifnull(sum( oxu.impressions ),0) AS UIMP,
					ifnull(sum( oxu.`clicks` ),0) AS UCLICKS
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_UNIQUE .=  " JOIN ox_unique AS oxu ON ( oxu.creative_id = oxb.bannerid AND date(oxu.date_time) BETWEEN '".$fromdate."' AND '".$todate."' )  and oxc.clientid='$clientid'";
				
			$SQL_BKT_UNIIMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS UIMP
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
					";
			$SQL_BKT_UNIIMP .=  " JOIN ox_data_bkt_unique_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."' )  and oxc.clientid='$clientid'";
				
			$SQL_BKT_UNICLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS UCLICKS
					FROM ox_clients AS oxcl
					JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
					JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						
					";
			$SQL_BKT_UNICLK .=  " JOIN ox_data_bkt_unique_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$fromdate."' AND '".$todate."' )  and oxc.clientid='$clientid'";
				

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
						"MAP"=>0,
						"UCLK"=>0,
						"UIMP"=>0,
				);
			}
		}



		$temp = $result;

			
		/*Impressions */
			

			

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
					$result[$data_imp['db_date']]['UCLK']	=	0;
					$result[$data_imp['db_date']]['UIMP']	=	0;
				}
					
					
			}

		}
			
		$temp=$result;

			

		/*Clicks */


		
		
		$SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
			
		$query2 = $this->db->query($SQL_BKT_CLK);

		if($query2->num_rows>0)
		{
			$stat_imp =  $query2->result_array();

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
					$result[$data_imp['db_date']]['UCLK']	=	0;
					$result[$data_imp['db_date']]['UIMP']	=	0;
						
				}
					
					
			}

		}
			
		$temp = $result;



		/*Conversions */

	
				
		$SQL_BKT_CON .=" GROUP BY DATE( odba.date_time )";
			
		$query3 = $this->db->query($SQL_BKT_CON);

		if($query3->num_rows>0)
		{
			$stat_con =  $query3->result_array();

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
					$result[$data_con['db_date']]['UCLK']	=	0;
					$result[$data_con['db_date']]['UIMP']	=	0;
						
				}
					
					
			}

		}
			
		$temp = $result;

		/*Spend */


		
		$SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date )";
			
		$query4 = $this->db->query($SQL_BKT_SPEND);


		if($query4->num_rows>0)
		{
			$stat_imp =  $query4->result_array();

			foreach($stat_imp as $data_spend){
				$tot_spend		=	$data_spend['SPEND'];
				$tot_call		=	$data_spend['CALL'];
				$tot_web		=	$data_spend['WEB'];
				$tot_map		=	$data_spend['MAP'];
					
				if(isset($temp[$data_spend['db_date']])){
					$result[$data_spend['db_date']]['SPEND']	=$tot_spend;
					$result[$data_spend['db_date']]['CALL']		=$tot_call;
					$result[$data_spend['db_date']]['WEB']		=$tot_web;
					$result[$data_spend['db_date']]['MAP']		=$tot_map;
				}
				else
				{
					$result[$data_spend['db_date']]['IMP']		=	0;
					$result[$data_spend['db_date']]['CLK']		=	0;
					$result[$data_spend['db_date']]['CON']		=	0;
					$result[$data_spend['db_date']]['UCLK']		=	0;
					$result[$data_spend['db_date']]['UIMP']		=	0;
					$result[$data_spend['db_date']]['SPEND']	=	$tot_spend;
					$result[$data_spend['db_date']]['CALL']		=	$tot_call;
					$result[$data_spend['db_date']]['WEB']		=	$tot_web;
					$result[$data_spend['db_date']]['MAP']		=	$tot_map;
						
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
			$new_temp[$key]['CALL'] 	= $stat_data['CALL'];
			$new_temp[$key]['WEB'] 		= $stat_data['WEB'];
			$new_temp[$key]['MAP'] 		= $stat_data['MAP'];
			$new_temp[$key]['UIMP'] 	= 0;
			$new_temp[$key]['UCLK'] 	= 0;
		}
			
		$temp 	= $new_temp;
		$result = $new_temp;


		
		$SQL_UNIQUE .=" GROUP BY date(oxu.date_time),`viewer_id` ,  `creative_id` ,  `zone_id` ";



		$query5 = $this->db->query($SQL_UNIQUE);

			
		if($query5->num_rows>0)
		{
			$stat_summary =  $query5->result_array();

			foreach($stat_summary as $unique_data){

					
				if(isset($result[$unique_data['db_date']])){
						
					$result[$unique_data['db_date']]['IMP']		= $temp[$unique_data['db_date']]['IMP'];
					$result[$unique_data['db_date']]['CLK']		= $temp[$unique_data['db_date']]['CLK'];
					$result[$unique_data['db_date']]['CON']		= $temp[$unique_data['db_date']]['CON'];
					$result[$unique_data['db_date']]['SPEND']	= $temp[$unique_data['db_date']]['SPEND'];
					$result[$unique_data['db_date']]['CALL']	= $temp[$unique_data['db_date']]['CALL'];
					$result[$unique_data['db_date']]['WEB']		= $temp[$unique_data['db_date']]['WEB'];
					$result[$unique_data['db_date']]['MAP']		= $temp[$unique_data['db_date']]['MAP'];

					$result[$unique_data['db_date']]['UIMP']	+= ($unique_data['UIMP'] > 0 )?1:0;
					$result[$unique_data['db_date']]['UCLK']	+= ($unique_data['UCLICKS'] > 0 )?1:0;

						
				}
				else
				{
					$result[$unique_data['db_date']]['IMP']		= 0;
					$result[$unique_data['db_date']]['CLK']		= 0;
					$result[$unique_data['db_date']]['CON']		= 0;
					$result[$unique_data['db_date']]['SPEND']	= 0;
					$result[$unique_data['db_date']]['CALL']	= 0;
					$result[$unique_data['db_date']]['WEB']		= 0;
					$result[$unique_data['db_date']]['MAP']		= 0;

					$result[$unique_data['db_date']]['UIMP']	= ($unique_data['UIMP'] > 0 )?1:0;
					$result[$unique_data['db_date']]['UCLK']	= ($unique_data['UCLICKS'] > 0 )?1:0;
				}
					
					
			}
		}

		$temp = $result;

	
		
		$SQL_BKT_UNIIMP .=" GROUP BY DATE( odbm.interval_start ),`viewer_id` ,  `creative_id` ,  `zone_id`";

		$query6 = $this->db->query($SQL_BKT_UNIIMP);


		if($query6->num_rows>0)
		{
			$stat_imp =  $query6->result_array();

			foreach($stat_imp as $data_imp){
					
				$temp_loop = 0;
					
				if(isset($result[$data_imp['db_date']])){

					$temp_loop ++;

					// Already exists
					$result[$data_imp['db_date']]['IMP']		= $temp[$data_imp['db_date']]['IMP'];
					$result[$data_imp['db_date']]['CLK']		= $temp[$data_imp['db_date']]['CLK'];
					$result[$data_imp['db_date']]['CON']		= $temp[$data_imp['db_date']]['CON'];
					$result[$data_imp['db_date']]['SPEND']		= $temp[$data_imp['db_date']]['SPEND'];
					$result[$data_imp['db_date']]['CALL']		= $temp[$data_imp['db_date']]['CALL'];
					$result[$data_imp['db_date']]['WEB']		= $temp[$data_imp['db_date']]['WEB'];
					$result[$data_imp['db_date']]['MAP']		= $temp[$data_imp['db_date']]['MAP'];
						
					if($temp_loop != 0){
						$result[$data_imp['db_date']]['UIMP']		+=  ($data_imp['UIMP'] > 0 )?1:0;
					}
					else
					{
						$result[$data_imp['db_date']]['UIMP']		= $temp[$data_imp['db_date']]['UIMP'] + ($data_imp['UIMP'] > 0 )?1:0;
					}

					$result[$data_imp['db_date']]['UCLK']		= $temp[$data_imp['db_date']]['UCLK'];
				}
				else
				{
					// New Item
					$result[$data_imp['db_date']]['IMP']		= 0;
					$result[$data_imp['db_date']]['CLK']		= 0;
					$result[$data_imp['db_date']]['CON']		= 0;
					$result[$data_imp['db_date']]['SPEND']		= 0;
					$result[$data_imp['db_date']]['CALL']		= 0;
					$result[$data_imp['db_date']]['WEB']		= 0;
					$result[$data_imp['db_date']]['MAP']		= 0;
					$result[$data_imp['db_date']]['UIMP']		= ($data_imp['UIMP'] > 0 )?1:0;
					$result[$data_imp['db_date']]['UCLK']		= 0;
				}
					
			}

		}
			
		$temp = $result;

				
		$SQL_BKT_UNICLK .=" GROUP BY DATE( odbm.interval_start ),`viewer_id` ,  `creative_id` ,  `zone_id`";
			
		$query7 = $this->db->query($SQL_BKT_UNICLK);

		if($query7->num_rows>0)
		{
			$stat_imp =  $query7->result_array();
        
			foreach($stat_imp as $data_imp){
					
				$temp_loop = 0;
					
				if(isset($result[$data_imp['db_date']])){

					$temp_loop ++;

					// Already exists
					$result[$data_imp['db_date']]['IMP']		= $temp[$data_imp['db_date']]['IMP'];
					$result[$data_imp['db_date']]['CLK']		= $temp[$data_imp['db_date']]['CLK'];
					$result[$data_imp['db_date']]['CON']		= $temp[$data_imp['db_date']]['CON'];
					$result[$data_imp['db_date']]['SPEND']		= $temp[$data_imp['db_date']]['SPEND'];
					$result[$data_imp['db_date']]['CALL']		= $temp[$data_imp['db_date']]['CALL'];
					$result[$data_imp['db_date']]['WEB']		= $temp[$data_imp['db_date']]['WEB'];
					$result[$data_imp['db_date']]['MAP']		= $temp[$data_imp['db_date']]['MAP'];
					$result[$data_imp['db_date']]['UIMP']		= $temp[$data_imp['db_date']]['UIMP'];


					if($temp_loop != 0){
						$result[$data_imp['db_date']]['UCLK']		+=  ($data_imp['UCLICKS'] > 0 )?1:0;
					}
					else
					{
						$result[$data_imp['db_date']]['UCLK']		= $temp[$data_imp['db_date']]['UCLK']  + ($data_imp['UCLICKS'] > 0 )?1:0;
					}

				}
				else
				{
					// New Item
					$result[$data_imp['db_date']]['IMP']		= 0;
					$result[$data_imp['db_date']]['CLK']		= 0;
					$result[$data_imp['db_date']]['CON']		= 0;
					$result[$data_imp['db_date']]['SPEND']		= 0;
					$result[$data_imp['db_date']]['CALL']		= 0;
					$result[$data_imp['db_date']]['WEB']		= 0;
					$result[$data_imp['db_date']]['MAP']		= 0;
					$result[$data_imp['db_date']]['UIMP']		= 0;
					$result[$data_imp['db_date']]['UCLK']		= ($data_imp['UCLICKS'] > 0 )?1:0;
				}
					
			}

		}

		$temp = $result;
		$final_result 	= array();
		$final_tot 		= array("IMP"=>0,"CLK"=>0,"CON"=>0,"SPEND"=>0.00,"CALL"=>0,"WEB"=>0,"MAP"=>0,"CTR"=>0.00,"UIMP"=>0,"UCLK"=>0);
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
						"date"=>date("Y-m-d",strtotime($key)),
						"IMP"=>$resObj['IMP'],
						"CON"=>$resObj['CON'],
						"CLK"=>$resObj['CLK'],
						"SPEND"=>number_format($resObj['SPEND'],2,'.',','),
						"CALL"=>$resObj['CALL'],
						"WEB"=>$resObj['WEB'],
						"MAP"=>$resObj['MAP'],
						"CTR"=>number_format($CTR,2,'.',','),
						"UIMP"=>$resObj['UIMP'],
						"UCLK"=>$resObj['UCLK']
				);

				$final_tot['IMP']	+=  $resObj['IMP'];
				$final_tot['CON']	+=  $resObj['CON'];
				$final_tot['CLK']	+=  $resObj['CLK'];
				$final_tot['SPEND']	+=  $resObj['SPEND'];
				$final_tot['CALL']	+=  $resObj['CALL'];
				$final_tot['WEB']	+=  $resObj['WEB'];
				$final_tot['MAP']	+=  $resObj['MAP'];
				$final_tot['UIMP']	+=  $resObj['UIMP'];
				$final_tot['UCLK']	+=  $resObj['UCLK'];
					

				array_push($final_result,$t);
			}
				
			if($final_tot['IMP'] > 0)
				$final_tot['CTR']	=  number_format(($final_tot['CLK']/$final_tot['IMP'])*100,"2",".",",");
			else
				$final_tot['CTR']	=  0.00;
				
		}

		$temp = $final_result;

		asort($final_result);

		$out = array("stat_list"=>$final_result,"tot_val"=>$final_tot);


		$diff = abs(strtotime($todate) - strtotime($fromdate));

		$date_diff = floor($diff/(60*60*24));


			
		if(count($final_result)>0)
		{
			$col=0;
			$row=10;
			$row_start	=	10;
			$camp_title_style = array();
			$camp_style	=	array();




			foreach($final_result as $adv)
			{
				$workSheet->getStyle('B'.$row.':L'.$row)->getFont()->setBold(true);
				$workSheet->setCellValueByColumnAndRow(1,$row,$adv['date']);
				$workSheet->setCellValueByColumnAndRow(2,$row,$adv['IMP']);
				$workSheet->setCellValueByColumnAndRow(3,$row,$adv['UIMP']);
				$workSheet->setCellValueByColumnAndRow(4,$row,$adv['CLK']);
				$workSheet->setCellValueByColumnAndRow(5,$row,$adv['UCLK']);
				$workSheet->setCellValueByColumnAndRow(6,$row,$adv['CON']);
				$workSheet->setCellValueByColumnAndRow(7,$row,$adv['CALL']);
				$workSheet->setCellValueByColumnAndRow(8,$row,$adv['WEB']);
				$workSheet->setCellValueByColumnAndRow(9,$row,$adv['MAP']);
				$workSheet->setCellValueByColumnAndRow(10,$row,$adv['CTR']."%");
				$workSheet->setCellValueByColumnAndRow(11,$row,"$".$adv['SPEND']);
				$row++;
			}

			$row=$row+1;

			$workSheet->getStyle('B'.$row.':L'.$row)->getFont()->setBold(true);
			$workSheet->setCellValueByColumnAndRow(1,$row,"Total");
			$workSheet->setCellValueByColumnAndRow(2,$row,$final_tot['IMP']);
			$workSheet->setCellValueByColumnAndRow(3,$row,$final_tot['UIMP']);
			$workSheet->setCellValueByColumnAndRow(4,$row,$final_tot['CLK']);
			$workSheet->setCellValueByColumnAndRow(5,$row,$final_tot['UCLK']);
			$workSheet->setCellValueByColumnAndRow(6,$row,$final_tot['CON']);
			$workSheet->setCellValueByColumnAndRow(7,$row,$final_tot['CALL']);
			$workSheet->setCellValueByColumnAndRow(8,$row,$final_tot['WEB']);
			$workSheet->setCellValueByColumnAndRow(9,$row,$final_tot['MAP']);
			$workSheet->setCellValueByColumnAndRow(10,$row,$final_tot['CTR']."%");
			$workSheet->setCellValueByColumnAndRow(11,$row,"$".$final_tot['SPEND']);
				
				

		}
		else
		{
			$col=0;
			$row=12;
			$camp_title_style = array();
			$camp_style	=	array();
			$content="There are no statistics for this Campaign & Publisher on ".$fromdate."--".$todate;
			$workSheet->SetCellValue('C12', $content);
		}




			
		// Save Excel 2003 file
		//echo date('H:i:s') . " Write to Excel2003 format\n";

		header('Content-Type: application/vnd.ms-excel');
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$filename.'.xls');
		header('Cache-Control: max-age=0');
		//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			

		//ob_end_clean();
		$objWriter->save('php://output');
			
			
	}
}
