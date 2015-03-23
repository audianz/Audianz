<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Smaato_delivery_report extends CI_Controller 
	{ 
		var $page_limit	=	10; 
		
		public function __construct() 
   			{
					parent::__construct();
					
					$this->load->model('mod_advertiser');
					//$this->load->model('mod_smaato_stats');
					$this->load->model('mod_stat_excel');
					$this->load->model('mod_campaign');
					$this->load->model("mod_zones");
					$this->load->model('mod_smaato_stats');
					
					/*Libraries */
					$this->load->library("PHPExcel");
					$this->load->library("PHPExcel/IOFactory"); 
				
			}
			
		public function index()		
			{
				$this->stats();
			}
			
		public function stats()
		{
			//Array ( [search_field] => specific_date [from_date] => 02/07/2012 [sel_advertiser_id] => [sel_publisher_id] => ) 
				
				$limit	=	10;
				
				// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				/*if($this->input->post('sel_advertiser_id') != ''){
					$search_arr['sel_advertiser_id']	=	$this->input->post('sel_advertiser_id');
				}
				
				if($this->input->post('sel_publisher_id') != ''){
					$search_arr['sel_publisher_id']	=	$this->input->post('sel_publisher_id');
				}*/
				
				$search_arr['search_type']			=	($this->input->post('search_field')=='')?"today":$this->input->post('search_field');
				
				switch($search_arr['search_type']){
					case "all":
							$search_arr['from_date']			=	$this->mod_smaato_stats->get_start_date();//date("Y/m/d");
							$search_arr['to_date']				=	date("Y/m/d");
							break;	
					case "yesterday":
							$search_arr['from_date']			=	date('Y-m-d', strtotime('Yesterday'));
							$search_arr['to_date']				=	date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							
							$start_date		=	date('Y-m-d', strtotime('this week Monday'));
							$start_date1	=	date('Y-m-d', strtotime('last Monday'));
							$end_date		=	date('Y-m-d');
							
							$startDate		=	mktime(0,0,0,$start_date[1],$start_date[2],$start_date[0]);
							$endDate		=	mktime(0,0,0,$end_date[1],$end_date[2],$end_date[0]);
							
							$diff			=	$endDate-$startDate;
							
							$fullDays = floor($diff/(60*60*24));
							
							if($fullDays >0)
							{
								$search_arr['from_date']			=	$start_date;
								$search_arr['to_date']				=	$end_date;
							}
							else
							{
								$search_arr['from_date']			=	$start_date1;
								$search_arr['to_date']				=	$end_date;
							}
							break;
					case "last7days":
							$search_arr['from_date']			=	date("Y/m/d",strtotime('Today - 7 Day'));
							$search_arr['to_date']				=	date("Y/m/d");
							break;
					case "thismonth":
							$search_arr['from_date']			=	date('Y/m/d', mktime(0, 0, 0, date('m'), 1, date('Y')));
							$search_arr['to_date']				=	date("Y/m/d");
							break;
					case "lastmonth":
							$search_arr['from_date']			=	date('Y/m/d', mktime(0, 0, 0, (date('m') - 1), 1, date('Y')));
							$search_arr['to_date']				=	date('Y/m/d', mktime(0, 0, 0, date('m'), 0, date('Y')));
							break;
					case "specific_date":
							$search_arr['from_date']			=	date("Y/m/d",strtotime($this->input->post('from_date')));
							$search_arr['to_date']				=	date("Y/m/d",strtotime($this->input->post('to_date')));
							break;		
					default:
							$search_arr['from_date']			=	date("Y/m/d"); //$this->mod_smaato_stats->get_start_date();
							$search_arr['to_date']				=	date("Y/m/d");
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				$data['search_type'] = $search_arr['search_type'];
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 					= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				
					
				$stat_data = $this->mod_smaato_stats->get_stats($search_arr);
				$data['stat_data']	=	$stat_data;
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("statistics/smaato/list",$data,true);
				$this->load->view('page_layout',$data);
		}
			
		public function detail_report($date=false)
			{
				
				//Array ( [search_field] => specific_date [from_date] => 02/07/2012 [sel_advertiser_id] => [sel_publisher_id] => ) 
				
				$limit	=	10;
				$data['date'] = $date;
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 					= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
								
				$stat_data = $this->mod_smaato_stats->get_details($date);
				$data['stat_data']	=	$stat_data;
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("statistics/smaato/detail_list",$data,true);
				$this->load->view('page_layout',$data);
					
	}		
	
}		
