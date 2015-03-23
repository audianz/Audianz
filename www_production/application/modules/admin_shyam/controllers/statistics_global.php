<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Statistics_global extends CI_Controller 
	{ 
		var $page_limit	=	10; 
		
		public function __construct() 
   			{
					parent::__construct();
					
					$this->load->model('mod_advertiser');
					$this->load->model('mod_statistics');
					$this->load->model('mod_stat_global');
					$this->load->model('mod_stat_excel');
					$this->load->model('mod_campaign');
					$this->load->model("mod_zones");
					$this->load->library("PHPExcel");
					$this->load->library("PHPExcel/IOFactory"); 
				
			}
			
		public function index()		
			{
				$this->view();
			}
			
		public function view($start=0)
			{
				
				//Array ( [search_field] => specific_date [from_date] => 02/07/2012 [sel_advertiser_id] => [sel_publisher_id] => ) 
				
				$limit	=	10;
				
				// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				$search_arr['search_type']			=	($this->input->post('search_field')=='')?"all":$this->input->post('search_field');
				
				switch($this->input->post('search_field')){
					case "today":
							$search_arr['from_date']			=	date("Y/m/d");
							$search_arr['to_date']				=	date("Y/m/d");
							break;	
					case "yesterday":
							$search_arr['from_date']			=	date('Y-m-d', strtotime('Yesterday'));
							$search_arr['to_date']				=	date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							
							$search_arr['from_date']			=	date('Y-m-d', strtotime('last monday', strtotime('next monday')));
						$search_arr['to_date']				=	date("Y/m/d");
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
					case "country":
							redirect("admin/statistics_global/view_date_country_wise");
							break;
					default:
							$search_arr['from_date']			=	$this->mod_statistics->get_start_date();
							$search_arr['to_date']				=	date("Y/m/d");
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				$data['search_type'] = $search_arr['search_type'];
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				$data['stat_data'] = $this->mod_stat_global->get_statistics_datewise($search_arr,$start,$limit);
			
			
				$start_date = $search_arr['from_date'];
				$end_date = $search_arr['to_date'];

				
			
				//customized  by dinesh.a for displaying 0 values
				$date_arr = array();
				$date_key = array();
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date('Y-m-d',strtotime($start_date));
					$date_values = array($start_date=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'SPEND'=>0,
																							'CALL'=>0,
																							'WEB'=>0,
																							'MAP'=>0,	
																							'CTR'=>number_format(0.00,2)
																							)
												);
					foreach($date_values as $key =>$date)
					{						
						array_push($date_key,$key);
						array_push($date_arr,$date);
					}
					$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
				}
				
				$final_date = array_combine($date_key,$date_arr);
				krsort($final_date);

				$stat_data_value = array();
				$stat_data_key = array();
				$final_date=array();
				$final_stat_data=array();
				foreach($data['stat_data']['stat_list'] as $date_key=>$stat_data)
				{
					array_push($stat_data_key,$date_key);
					array_push($stat_data_value,$stat_data);
				}
					
				if($stat_data_key != FALSE || $stat_data_value != FALSE)
				{
					$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				}
				else
				{
					$final_stat_data = '';
				}
				//check For all Statistics
				
				
				
				if($search_arr['search_type']=='all')
				{
					$data['stat_data']['stat_list'] = $final_stat_data;
				}else{
					if($final_date != FALSE || $final_stat_data != FALSE)
					{
						$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);
					}
					else
					{
						$data['stat_data']['stat_list'] = '';
					}
					
				}
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
		
				$data['page_content']		=		 $this->load->view("statistics/global/date_wise_list",$data,true);
				$this->load->view('page_layout',$data);
					
	}		
	
	public function view_date_country_wise($start=0){
	
		//get_statistics_date_country_wise	
		
		/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		
		$limit	=	10;
				
				// GETTING SEARCH FIELDS
				
		$search_arr	=	array();
				
		$search_arr['search_type']			=	($this->input->post('search_field')=='')?"all":$this->input->post('search_field');
				
		switch($this->input->post('search_field')){
					case "today":
							$search_arr['from_date']			=	date("Y/m/d");
							$search_arr['to_date']				=	date("Y/m/d");
							break;	
					case "yesterday":
							$search_arr['from_date']			=	date('Y-m-d', strtotime('Yesterday'));
							$search_arr['to_date']				=	date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							
							$search_arr['from_date']			=	date('Y-m-d', strtotime('last monday', strtotime('next monday')));
						$search_arr['to_date']				=	date("Y/m/d");
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
							$search_arr['from_date']			=	$this->mod_statistics->get_start_date();
							$search_arr['to_date']				=	date("Y/m/d");
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				//echo "<pre>"; print_r($search_arr); exit;
		
		
		$data['stat_data'] 	=	$this->mod_stat_global->get_statistics_date_country_wise($search_arr);
		
		/*------------------------------------------------------------
					Embed current page content into template layout
		-------------------------------------------------------------*/
		$data['page_content']		=		 $this->load->view("statistics/global/date_country_wise_list",$data,true);
		$this->load->view('page_layout',$data);
		
		
	}

	//view Hour wise Statistics
	public function view_global_hour_wise($start=0){
		

		$limit	=	10;

		if($this->input->post('sel_date') != ''){

			$start_date		=	$this->input->post('start_date');
			$end_date		=	$this->input->post('end_date');
			$search_type            =	$this->input->post('search_type');
                        $sel_date		=	$this->input->post('sel_date');
			
			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 				= $search_type;
            $search_arr['sel_date'] 				= date("Y-m-d",strtotime($sel_date));
				
				
               //CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS

				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				$data['stat_data'] = $this->mod_stat_global->get_statistics_hourwise($search_arr,$start,$limit);

				//customised by dinesh.a for displaying null values at unavailable hours
				$time_key = array();
				$time_arr = array();
				$hour = 0;
				while($hour < 24)
				{
   	 				$timeperiod = date('H:i:s',mktime($hour,0,0));
					$date_time_values = array($timeperiod=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'CTR'=>number_format(0.00,2)
																							)
												);
					foreach($date_time_values as $key =>$time)
					{						
						array_push($time_key,$key);
						array_push($time_arr,$time);
					}
					$hour++;
				}
				
				$final_time = array_combine($time_key,$time_arr);
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $time_key=>$stat_data)
				{
					array_push($stat_data_key,$time_key);
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
			
				$data['stat_data']['stat_list'] = array_merge($final_time,$final_stat_data);
				

				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 	= $link;

				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/

				$data['page_content']		=		 $this->load->view("statistics/global/hour_wise_list",$data,true);
				$this->load->view('page_layout',$data);

		}
		else
		{
			redirect('admin/statistics_global');
		}
	
	}
	

    public function country_wise_statistics($start=0){
	
		//get_statistics_date_country_wise	
		
		/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		
		$limit	=	10;
				
				// GETTING SEARCH FIELDS
				
		$search_arr	=	array();
				
		$search_arr['search_type']			=	($this->input->post('search_field')=='')?"all":$this->input->post('search_field');
				
		switch($this->input->post('search_field')){
					case "today":
							$search_arr['from_date']			=	date("Y/m/d");
							$search_arr['to_date']				=	date("Y/m/d");
							break;	
					case "yesterday":
							$search_arr['from_date']			=	date('Y-m-d', strtotime('Yesterday'));
							$search_arr['to_date']				=	date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							
							$search_arr['from_date']			=	date('Y-m-d', strtotime('last monday', strtotime('next monday')));
						$search_arr['to_date']				=	date("Y/m/d");
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
							$search_arr['from_date']			=	$this->mod_statistics->get_start_date();
							$search_arr['to_date']				=	date("Y/m/d");
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
		
		
		
		$data['stat_data'] 	=	$this->mod_stat_global->get_statistics_country_wise($search_arr);
		
		/*------------------------------------------------------------
					Embed current page content into template layout
		-------------------------------------------------------------*/
		$data['page_content']		=		 $this->load->view("statistics/global/country_wise_list",$data,true);
		$this->load->view('page_layout',$data);
		
		
	}


	public function export_global_statistics($start=0)
	{
			
		$limit	=	10;
		
		// GETTING Search Values
		$search_global=$this->session->userdata('statistics_search_arr');
		$data['stat_data'] = $this->mod_stat_global->get_statistics_datewise($search_global,$start,$limit);
	
		$data['search_global']=$search_global;
		$start_date = $search_global['from_date'];
				$end_date = $search_global['to_date'];

				
			
				//customized  by dinesh.a for displaying 0 values
				$date_arr = array();
				$date_key = array();
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date('Y-m-d',strtotime($start_date));
					$date_values = array($start_date=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'SPEND'=>0,
																							'CALL'=>0,
																							'WEB'=>0,
																							'MAP'=>0,
																							'CTR'=>number_format(0.00,2)
																							)
												);
					foreach($date_values as $key =>$date)
					{						
						array_push($date_key,$key);
						array_push($date_arr,$date);
					}
					$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
				}
				
				$final_date = array_combine($date_key,$date_arr);
				krsort($final_date);
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $date_key=>$stat_data)
				{
					array_push($stat_data_key,$date_key);
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
				//check For all Statistics
				
				if($search_global['search_type']=='all')
				{
					$data['stat_data']['stat_list'] = $final_stat_data;
				}else{
					$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);
				}
		
		$this->mod_stat_excel->export_global_data($data);
					
	}//End of export_global_statistics controller

	public function export_date_country_wise($start=0)
	{
	
		
		$limit	=	10;
		
		// GETTING Search Values
		$search_date_country=$this->session->userdata('statistics_search_arr');
		$data['stat_data'] 	=	$this->mod_stat_global->get_statistics_date_country_wise($search_date_country);
		$data['search_date_country']=$search_date_country;
		$this->mod_stat_excel->export_date_country_wise($data);
		
	}//End of export_date_country_wise controller
			
	public function export_country_wise($start=0)
	{
		$limit	=	10;
		
		// GETTING Search Values
		$search_country=$this->session->userdata('statistics_search_arr');
		$data['stat_data'] 	=	$this->mod_stat_global->get_statistics_country_wise($search_country);
		$data['search_country']=$search_country;
		$this->mod_stat_excel->export_country_wise($data);
		
	}//End of export_country_wise controller
			
	public function export_global_hour_wise($start=0)
	{
			
		$limit	=	10;
		
		//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS

				$search_global_hour=$this->session->userdata('statistics_search_arr');
				$data['search_global_hour']=$search_global_hour;
				$data['stat_data'] = $this->mod_stat_global->get_statistics_hourwise($search_global_hour,$start,$limit);
				
		
		//customised by dinesh.a for displaying null values at unavailable hours
				$time_key = array();
				$time_arr = array();
				$hour = 0;
				while($hour < 24)
				{
   	 				$timeperiod = date('H:i:s',mktime($hour,0,0));
					$date_time_values = array($timeperiod=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'CTR'=>number_format(0.00,2)
																							)
												);
					foreach($date_time_values as $key =>$time)
					{						
						array_push($time_key,$key);
						array_push($time_arr,$time);
					}
					$hour++;
				}
				
				$final_time = array_combine($time_key,$time_arr);
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $time_key=>$stat_data)
				{
					array_push($stat_data_key,$time_key);
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
			
				$data['stat_data']['stat_list'] = array_merge($final_time,$final_stat_data);
				
				$this->mod_stat_excel->export_global_hour_wise($data);
					
	}//End of export_global_hour_wise controller
			
			
	}		
