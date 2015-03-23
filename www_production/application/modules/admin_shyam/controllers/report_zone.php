<?php
class Report_zone extends CI_Controller {  
	 
	 	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	
	public function __construct() 
    {  
		parent::__construct();		
		
		/* Models */
		$this->load->model("mod_reports"); //loc: Reports/models/mod_reports
        $this->load->helper('url');
        $this->load->helper('csv');
				
    }
		
	/* Inventory zones Landing Page */
	public function index() 
	{ 
		$this->view();	
	}
	
	/* Inventory zones listing Page */
	public function view() 
	{ 
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		/*--------------------------------------------------------------------
		 	 	Get zone name to list in select boxes
		 ---------------------------------------------------------------------*/
		
		$where 	="master_zone =-1 OR master_zone =-2 OR master_zone =-3";
		
		$list_zones = $this->mod_reports->list_zones($where);
				
		$data['zones_list']	=$list_zones;
		
		$data['date']=date("m/d/Y");	
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		 
		$data['page_title'] 	=	$this->lang->line('label_inventory_zones_page_title');
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	=	$this->load->view("reports/report_zone",$data,true);
		$this->load->view('page_layout',$data);
	}

	public function selectday()
	{		
		/*-------------------------------------------------------------
			Breadcrumb Setup Start
		-------------------------------------------------------------*/
		
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
			
		$where 	="master_zone =-1 OR master_zone =-2 OR master_zone =-3";
			
		$list_zones = $this->mod_reports->list_zones($where);
					
		$data['zones_list']	=$list_zones;
		
		$dateval=($this->uri->segment(4)=="")?"specificdate":$this->uri->segment(4);
		
		
		if($dateval=='today')
		{
		
		$start=date('m/d/Y');
		$end=date('m/d/Y');
		
		}
		else if($dateval=='yesterday')
		{
		$start=date('m/d/Y', strtotime('Yesterday'));
		$end=date('m/d/Y');
		}
		else if($dateval=='thisweek')
		{
		$sdate=date('m/d/Y', strtotime('this week Monday'));
		$sdate1=date('m/d/Y', strtotime('last Monday'));
		$edate=date('m/d/Y');
		$sdatearray=explode('/',$sdate);
		$edatearray=explode('/',$edate);
		$start_date1=mktime(0,0,0,$sdatearray[1],$sdatearray[2],$sdatearray[0]);
		$end_date1=mktime(0,0,0,$edatearray[1],$edatearray[2],$edatearray[0]);
		$diff=$end_date1-$start_date1;
	
		$fullDays = floor($diff/(60*60*24));
		if($fullDays >0)
		{
		$start=$sdate;
		$end=$edate;
		}
		else
		{
		$start=$sdate1;
		$end=$edate;
		}
		}
		else if($dateval=='lastweek')
		{
		$sdate=date('m/d/Y', strtotime('last Monday'));
		$edate=date('m/d/Y', strtotime('last Sunday'));
		$start_date1=mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
		$end_date1=mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
		$diff=$end_date1-$start_date1;
		$fullDays = floor($diff/(60*60*24));
		if($fullDays >0)
		{
		$start=$sdate;
		$end=$edate;
		}
		else
		{
		$start=$edate;
		$end=$sdate;
		}
		}
		else if($dateval=='last7days')
		{
		$start=date('m/d/Y', strtotime('Today - 7 Day'));
		$end=date('m/d/Y');
		}
		else if($dateval=='thismonth')
		{
		$start=date('m/d/Y', mktime(0, 0, 0, date('m'), 1, date('Y')));
		$end=date('m/d/Y');
		}
		else if($dateval=='lastmonth')
		{
		$start=date('m/d/Y', mktime(0, 0, 0, (date('m') - 1), 1, date('Y')));
		$end=date('m/d/Y', mktime(0, 0, 0, date('m'), 0, date('Y')));
		}
		else if($dateval=='all')
		{
		$start='01/01/2011';
		$end=date('m/d/Y');
		}
		else if($dateval=='specificdate')
		{
		$start=$this->input->post("period_start");
		$end=$this->input->post("period_end");
		}
		$data['dateval']=$dateval;
		$data['start']=$start;
		$data['end']=$end;
		
		$data['page_content']=$this->load->view("reports/report_zone",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Inventory/Add new zones controller */
	
	public function zone_generate() 
	{ 
	
		$dateval=$this->input->post('date');
		$zone=$this->input->post('zonelimit');
		$filter=$this->input->post('country');
		
		if($dateval == 'specificdate')
		{
			$start=$this->input->post('period_start');
			$end=$this->input->post('period_end');
		}
		else
		{
			$start=$this->input->post('start');
			$end=$this->input->post('end');
		}
		
		if($filter=="")
		{
			$sdate=explode('/',$start);
			$edate=explode('/',$end);
			$start_date=mktime(0,0,0,$sdate[0],$sdate[1],$sdate[2]);
			$end_date=mktime(0,0,0,$edate[0],$edate[1],$edate[2]);
			$date=$end_date-$start_date;
			$fullDays = floor($date/(60*60*24));
			
			$index=7;
			for($i=0;$i<=$fullDays;$i++)
			{
			$startdate=date('Y-m-d H:i:s',mktime(0,0,0,$sdate[0],$sdate[1]+$i,$sdate[2]));
			$enddate=date('Y-m-d H:i:s',mktime(23,59,59,$sdate[0],$sdate[1]+$i,$sdate[2]));

			$query=mysql_query("select h.date_time,sum(h.impressions),sum(h.clicks),sum(h.conversions),h.total_revenue,z.zoneid,z.affiliateid,a.affiliateid,h.zone_id from ox_affiliates a join ox_zones z join ox_data_summary_ad_hourly h  on z.affiliateid=a.affiliateid AND h.zone_id=z.zoneid where date(h.date_time) BETWEEN '$startdate' AND '$enddate' AND (z.zoneid='$zone' or z.master_zone='$zone') group by date(h.date_time)");
			
			$adhourly=mysql_fetch_array($query);
			
			$ad_imp=($adhourly['sum(h.impressions)']!="")?$adhourly['sum(h.impressions)']:'0';
			$ad_cli=($adhourly['sum(h.clicks)']!="")?$adhourly['sum(h.clicks)']:'0';
			$ad_con=($adhourly['sum(h.conversions)']!="")?$adhourly['sum(h.conversions)']:'0';
			
			
			$totalrevenue=mysql_query("select sum(publisher_amount) from oxm_report  where date BETWEEN '$startdate' AND '$enddate' AND zoneid IN (SELECT zoneid
			FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone'))");
			
			$tot_rev=mysql_fetch_array($totalrevenue);
			$adv_amount=($tot_rev['sum(publisher_amount)']!="")?$tot_rev['sum(publisher_amount)']:'0';
			
			$bucket_imp=mysql_query("select sum(count),m.zone_id,m.interval_start,z.zoneid,z.affiliateid from ox_data_bkt_m m join ox_zones z on z.zoneid=m.zone_id where z.zoneid=m.zone_id and date(m.interval_start) BETWEEN '$startdate' AND '$enddate' AND m.zone_id IN (SELECT zoneid
			FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone')) GROUP BY date(m.interval_start)");
			
			
			$bu_impr=mysql_fetch_array($bucket_imp);
			$buck_imp=($bu_impr['sum(count)']!="")?$bu_impr['sum(count)']:'0';
			
			$bucket_cli=mysql_query("select sum(count),cl.creative_id,cl.interval_start,z.zoneid,z.affiliateid from ox_data_bkt_c cl join ox_zones z on  z.zoneid=cl.zone_id where date(cl.interval_start)BETWEEN '$startdate' AND '$enddate' AND  cl.zone_id IN (SELECT zoneid
			FROM ox_zones WHERE (zoneid = '$zone'OR master_zone = '$zone')) GROUP BY date(cl.interval_start)");
			
			
			
			
			$bu_cli=mysql_fetch_array($bucket_cli);
			$buck_cli=($bu_cli['sum(count)']!="")?$bu_cli['sum(count)']:'0';
			
			$selectdate=substr($startdate,0,10);
			$imp=$ad_imp+$buck_imp;
			$cli=$ad_cli+$buck_cli;
			$con=$ad_con;
			$revenue="$".number_format($adv_amount,2);
			if($cli != 0 && $imp != 0)
			{
				$ctr=($cli/$imp);
			}
			else
			{
				$ctr=0;
			}
			$ctr1=number_format($ctr,2)."%";
			echo $selectdate." ";
			echo $imp." ";
			echo $cli." ";
			echo $con." ";
			echo $ctr1." ";
			echo $revenue."\n";
			
			
			 /*$worksheet->write($index,3, $selectdate);
			$worksheet->write($index, 4, $imp);
			$worksheet->write($index, 5, $cli);
			$worksheet->write($index, 6, $con);
			$worksheet->write($index, 7, $ctr1);
			$worksheet->write($index, 8, $revenue);
			$index++;*/
			
			   
			
			
						
			}//end of for loop
		}//end of empty filter check
					
		elseif($filter==1)
		{
				$sdate=explode('/',$start);
				$edate=explode('/',$end);
				$start_date=mktime(0,0,0,$sdate[0],$sdate[1],$sdate[2]);
				$end_date=mktime(0,0,0,$edate[0],$edate[1],$edate[2]);
				$date=$end_date-$start_date;
				$fullDays = floor($date/(60*60*24));
				
				$index=7;
				for($i=0;$i<=$fullDays;$i++)
				{
				$startdate=date('Y-m-d H:i:s',mktime(0,0,0,$sdate[0],$sdate[1]+$i,$sdate[2]));
				$enddate=date('Y-m-d H:i:s',mktime(23,59,59,$sdate[0],$sdate[1]+$i,$sdate[2]));
				
				$selectdate=substr($startdate,0,10);
				
				$query=mysql_query("select date(s.date_time) AS date_time, sum(s.impressions) AS impressions, sum(s.clicks) AS clicks, s.country,z.zoneid,z.affiliateid,a.affiliateid,s.zone_id from ox_affiliates a join ox_zones z join ox_stats_country s on z.affiliateid=a.affiliateid AND s.zone_id=z.zoneid where date(s.date_time) BETWEEN '$startdate' AND '$enddate' AND (z.zoneid='$zone' or z.master_zone='$zone') group by date(s.date_time),s.country");
				
				
				while($adhourly=mysql_fetch_array($query))
				{
					$_array[$adhourly['date_time']][$adhourly['country']]['cn'][]		=$adhourly['country'];
					$_array[$adhourly['date_time']][$adhourly['country']]['date'][]		=$adhourly['date_time'];
					$_array[$adhourly['date_time']][$adhourly['country']]['imp'][]		=$adhourly['impressions'];
					$_array[$adhourly['date_time']][$adhourly['country']]['clk'][]		=$adhourly['clicks'];
				}
				
				
				$tblqry="select sum(count) AS count, m.creative_id,date(m.interval_start) AS interval_start,m.zone_id,z.zoneid,m.country from ox_data_bkt_country_m m join ox_zones z on  z.zoneid=m.zone_id and (z.zoneid='$zone' or z.master_zone='$zone') where  date(m.interval_start) BETWEEN '$startdate' AND '$enddate' GROUP BY date(m.interval_start),m.country";
				
				$tbl_imp=mysql_query($tblqry);
				while($bu_impr=mysql_fetch_array($tbl_imp))
				{
				
					if (array_key_exists($bu_impr['interval_start'], $_array))
					{
						if(array_key_exists($bu_impr['country'], $_array[$bu_impr['interval_start']]))
						{
							
							$_array[$bu_impr['interval_start']][$bu_impr['country']]['imp'][0]		=($_array[$bu_impr['interval_start']][$bu_impr['country']]['imp'][0]) + $bu_impr['count'];
						
						}
						else
						{
							$_array[$bu_impr['interval_start']][$bu_impr['country']]['cn'][]		=$bu_impr['country'];
							$_array[$bu_impr['interval_start']][$bu_impr['country']]['imp'][]		=$bu_impr['count'];
							$_array[$bu_impr['interval_start']][$bu_impr['country']]['clk'][]		=0;
						}
					}
					else
					{
						$_array[$bu_impr['interval_start']][$bu_impr['country']]['cn'][]		=$bu_impr['country'];
						$_array[$bu_impr['interval_start']][$bu_impr['country']]['date'][]		=$bu_impr['interval_start'];
						$_array[$bu_impr['interval_start']][$bu_impr['country']]['imp'][]		=$bu_impr['count'];
						$_array[$bu_impr['interval_start']][$bu_impr['country']]['clk'][]		=0;
					}
				
				}
				
				$qry2="select sum(count) AS count, cl.creative_id, date(cl.interval_start) AS interval_start,z.zoneid,cl.country from ox_data_bkt_country_c cl join ox_zones z on cl.zone_id=z.zoneid and (z.zoneid='$zone' or z.master_zone='$zone') where  date(cl.interval_start)BETWEEN '$startdate' AND '$enddate' GROUP BY date(cl.interval_start), cl.country";
				
				$tbl_cli	=mysql_query($qry2);
				while($bu_cli=mysql_fetch_array($tbl_cli))
				{
				
					if (array_key_exists($bu_cli['interval_start'], $_array))
					{
						if(array_key_exists($bu_cli['country'], $_array[$bu_cli['interval_start']]))
						{
							$_array[$bu_cli['interval_start']][$bu_cli['country']]['clk'][0]		=($_array[$bu_cli['interval_start']][$bu_cli['country']]['clk'][0]) + $bu_cli['count'];	
						}
						else
						{
							$_array[$bu_cli['interval_start']][$bu_cli['country']]['cn'][]		=$bu_cli['country'];
							$_array[$bu_cli['interval_start']][$bu_cli['country']]['imp'][]		=0;
							$_array[$bu_cli['interval_start']][$bu_cli['country']]['clk'][]		=$bu_cli['count'];
						}
					}
					else
					{
						$_array[$bu_cli['interval_start']][$bu_cli['country']]['cn'][]		=$bu_cli['country'];
						$_array[$bu_cli['interval_start']][$bu_cli['country']]['date'][]	=$bu_cli['interval_start'];
						$_array[$bu_cli['interval_start']][$bu_cli['country']]['imp'][]		=0;
						$_array[$bu_cli['interval_start']][$bu_cli['country']]['clk'][]		=$bu_cli['count'];
					}
				
				}
				$st=substr($startdate,0,10);
				$ed=substr($enddate,0,10);
				if(!empty($_array))
				{
					foreach ($_array as $keyone=>$tempone) 
					{
									
						foreach ($tempone as $keytwo=>$temptwo) 
						{
								$selectdate=$keyone;				
								$query=mysql_query("select * from oxm_country where counrty_code='$keytwo'");
								$qry=mysql_fetch_array($query);
								$ad_country=$qry['country_name'];
								$cli=$temptwo['clk'][0];
								$imp=$temptwo['imp'][0];
								if($cli != 0 && $imp != 0)
								{
									$ctr=($cli/$imp)*100;
								}
								else
								{
									$ctr=0;
								}
								
								$ctr1=number_format($ctr,2)."%";
								echo $selectdate." ";
								echo $imp." ";
								echo $cli." ";
								echo $ctr1." ";
								echo $ad_country." ";
								echo "\n";
								/*$worksheet->write($index,3, $selectdate);
								 $worksheet->write($index, 4, $imp);
								 $worksheet->write($index, 5, $cli);
								 $worksheet->write($index, 6, $ctr1);
								  $worksheet->write($index, 7, $ad_country);*/
								
						}
							
					}
						/*	
						
						
						
						$data['selectdate']=$selectdate
						$data['imp']=$imp;
						$data['cli']=$cli;
						$data['con']=$con;
						$data['ctrl']=$ctrl;
						$data['revenue']=$revenue;
						
						
						
							
					
					
					$data['dateval']=$dateval;
					$data['filter']=$filter;
					$data['start']=$start;
					$data['end']=$end;
					$data['zone']=$zone;
				
				*/
				
				
				}
				elseif(empty($_array))
				{	
					if($i==0)
					{
					echo "There are no statstics for this zone";
					}
					/*$content="There are no statstics for this zone on ".$st."--".$ed;
					$worksheet->write(7,3, $content);*/
				}
			}
		}
		
		
		
		
	}/* End of file generate */
	
	
        function create_csv()
		{

            $quer = $this->db->get('ox_zones')->result_array();
		  //$quer = $this->db->get('ox_zones');
            
           //query_to_csv($quer,TRUE,'Orders_'.date('dMy').'.csv');   
        
		array_to_csv($quer,'Orders_'.date('dMy').'.csv');
            
        }

}

/* End of file zones.php */
/* Location: ./modules/admin/controllers/report_zones.php */
