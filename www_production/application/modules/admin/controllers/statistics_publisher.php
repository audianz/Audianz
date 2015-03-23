<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Statistics_publisher extends CI_Controller
	{
		var $page_limit	=	10;
		public function __construct() 
			{
				parent::__construct();
				
					/* Models */
				$this->load->model("mod_publisher");
				$this->load->model("mod_websites");
				$this->load->model("mod_statistics");
				$this->load->model("mod_zones");
				$this->load->model("mod_stat_excel");
				/* Libraries */					
				$this->load->library("PHPExcel");
				$this->load->library("PHPExcel/IOFactory");
				
			
			}
			
		//Statistics_publisher landing page	
		public function index()		
			{
				$this->view();
			}
			
		public function view($start=0)
			{
				
				// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				if($this->input->post('sel_advertiser_id') != ''){
					$search_arr['sel_advertiser_id']	=	$this->input->post('sel_advertiser_id');
				}
				
				if($this->input->post('sel_publisher_id') != ''){
					$search_arr['sel_publisher_id']	=	$this->input->post('sel_publisher_id');
				}
				
				$search_arr['search_type']			=($this->input->post('search_field')=='')?"all":$this->input->post('search_field');
				
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
				$data['search_type'] = $search_arr['search_type'];
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				
				/*--------------------------------------------------------------
					Pagination  Config Setup
				 ---------------------------------------------------------------*/
				
				
				$limit 							= 		$this->page_limit;
				$config['per_page'] 		= 		$limit;
				$config['base_url'] 			= 		site_url("admin/statistics_publisher/view");
				$config['uri_segment'] 	= 		4;
				//$config['total_rows'] 	= 		count($list_data);//'5';
				$config['next_link'] 			= 		$this->lang->line("pagination_next_link");
				$config['prev_link'] 			= 		$this->lang->line("pagination_prev_link");		
				$config['last_link'] 			= 		$this->lang->line("pagination_last_link");		
				$config['first_link'] 			= 		$this->lang->line("pagination_first_link");
				$this->pagination->initialize($config);		
				$data['date']							=		'today';
				$data['start_date']					=		date('Y-m-d');
				$data['end_date']					=		date('Y-m-d');
				$data['publisher_list'] = $this->mod_websites->get_site_list("all");
				//echo "<pre>"; print_r($search_arr); exit;
				
				$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher($search_arr,$start,$limit);
			
				
				
				
			/*-------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				$data['page_content']		=		 $this->load->view("statistics/publisher/list",$data,true);
				$this->load->view('page_layout',$data);
			}
			
		
		public function view_date_wise($start=0){
		
		$limit	=	10;
		
		if($this->input->post('parent') != ''){
			
			$start_date		=	$this->input->post('start_date');
			$end_date		=	$this->input->post('end_date');
			$search_type	=	$this->input->post('search_type');
			$parent			=	$this->input->post('parent');
			$ref_id			=	$this->input->post('ref_id');
			$zone_id		=	$this->input->post('zone_id');
			
			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 			= $search_type;
			$search_arr['sel_publisher_id'] 		= $ref_id;
			$search_arr['sel_zone_id'] 			= $zone_id;
			$search_arr['parent'] 				= $parent;
			$search_arr['sel_zone_id']                 	= $zone_id;

			switch($parent){
				case "PUB": //PUBLISHER SECTION
					
					$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_datewise($search_arr,$start,$limit);
					$data['stat_adv_det'] = $this->mod_websites->get_publisher($ref_id);
					
				break;
				case "ZONE": //ZONES SECTION
				$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_zones_datewise($search_arr,$start,$limit);
				
				$data['stat_adv_det'] = $this->mod_websites->get_publisher($ref_id);
				$data['zone_list'] = $this->mod_websites->get_zone_name($zone_id);	
				
				break;
				
			}
			
			
			//customized  by dinesh.a for displaying 0 values
			$date_arr = array();
			$date_key = array();
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date('m/d/Y',strtotime($start_date));
					$date_values = array($start_date=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'SPEND'=>0,
																							'CALL'=>0,
																						    'WEB'=>0,
																							'MAP'=>0, 
																							'CTR'=>number_format(0.00,2),
																							'PUBSHARE'=>0
																							)
												);
					foreach($date_values as $key =>$date)
					{						
						array_push($date_key,$key);
						array_push($date_arr,$date);
					}
					$start_date = date ("d-m-Y", strtotime("+1 day", strtotime($start_date)));
				}
				
				$final_date = array_combine($date_key,$date_arr);
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $date_key=>$stat_data)
				{
					array_push($stat_data_key,date('m/d/Y',strtotime($date_key)));
					array_push($stat_data_value,$stat_data);
				}
				if(!empty($stat_data_key)&&!empty($stat_data_value))
				{
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				}
				else
				{
					$final_stat_data = array();
				}
				
			
				$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);
			
			
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("statistics/publisher/date_wise_list",$data,true);
				$this->load->view('page_layout',$data);
			
		}
		else
		{
			redirect('admin/statistics_publisher');
		}
	}
			
	public function view_hour_wise($start=0){

		$limit	=	10;

		if($this->input->post('parent') != ''){

			$start_date								=	$this->input->post('start_date');
			$end_date								=	$this->input->post('end_date');
			$search_type            				=	$this->input->post('search_type');
            $sel_date								=	$this->input->post('sel_date');
			$parent									=	$this->input->post('parent');
			$ref_id									=	$this->input->post('ref_id');
			$zone_id								=	$this->input->post('zone_id');
			
			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 				= $search_type;
            $search_arr['sel_date'] 				= date("Y-m-d",strtotime($sel_date));
			$search_arr['sel_publisher_id'] 		= $ref_id;
			$search_arr['sel_zone_id']              = $zone_id;
			$search_arr['parent']                 	= $parent;
			
           // print_r($search_arr);return ;
			switch($parent){
				case "PUB": //PUBLISHER SECTION
					
					$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_hourwise($search_arr,$start,$limit);
					$data['stat_adv_det'] = $this->mod_websites->get_publisher($ref_id);
					
				break;
				case "ZONE": //ZONES SECTION
				$data['stat_adv_det'] = $this->mod_websites->get_publisher($ref_id);
				$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_hourwise($search_arr,$start,$limit);
				$data['zone_list'] = $this->mod_websites->get_zone_name($zone_id);
				
				
                                       
				break;
				
			}

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
																							'SPEND'=>0,
																							'CTR'=>number_format(0.00,2),
																							'PUBSHARE'=>0
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


                        
                                //CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS

				$this->session->set_userdata('statistics_search_arr',$search_arr);


				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 	= $link;

				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();


				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/

				$data['page_content']		=		 $this->load->view("statistics/publisher/hour_wise_list",$data,true);
				$this->load->view('page_layout',$data);

		}
		else
		{
			redirect('admin/statistics_publisher');
		}
	}
			
			
			public function view_more_details(){
				
				$adv_id  = $this->input->post("account_id");
				
				$search_arr	=	array();
				
				if(count($this->session->userdata('statistics_search_arr')) > 0){
					$search_arr = $this->session->userdata('statistics_search_arr');
				}
				
				if($adv_id != false){
					
					$data['publisher_list'] = $this->mod_publisher->get_publishers($adv_id,$search_arr);
				
					$this->load->view('statistics/publisher/publisher_zones',$data);
				}
				else
				{
					echo '';
					exit;
				}
			}

			//Export Websites or Publishers Statistiscal Report to Excel			
			public function export_publishers_excel()
			{
					// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				
				$search_arr	=	 $this->session->userdata('statistics_search_arr');
				
				$data['publisher_list'] = $this->mod_websites->get_site_list("all");
				
				$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher($search_arr);
				

				 $this->mod_stat_excel->export_publishers_excel($data);
			}
			
			public function export_publisher_date_wise($start=0)
			{
				
				$limit	=	10;
				
				$search_values_pub=$this->session->userdata('statistics_search_arr');
				$start_date = $search_values_pub['from_date'];
				$end_date =  $search_values_pub['to_date'];
				
				
				if($search_values_pub['parent']!= '')
				{
				
					switch($search_values_pub['parent'])
					{
						case "PUB": //PUBLISHER SECTION
							
							$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_datewise($search_values_pub,$start,$limit);
							$data['stat_adv_det'] = $this->mod_websites->get_publisher($search_values_pub['sel_publisher_id']);
							
						break;
						case "ZONE": //ZONES SECTION
						$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_zones_datewise($search_values_pub,$start,$limit);
						$data['stat_adv_det'] = $this->mod_websites->get_publisher($search_values_pub['sel_publisher_id']);
						$data['zone_list'] = $this->mod_websites->get_zone_name($search_values_pub['sel_zone_id']);
						

						
						break;
						
					}
					
					//customized  by dinesh.a for displaying 0 values
					$date_arr = array();
					$date_key = array();
					while (strtotime($start_date) <= strtotime($end_date))
					{
						$start_date = date('d-m-Y',strtotime($start_date));
						$date_values = array($start_date=>array('IMP'=>0,
											'CON'=>0,
											'CLK'=>0,
											'SPEND'=>0,
											'CTR'=>number_format(0.00,2),
											'PUBSHARE'=>0));
						foreach($date_values as $key =>$date)
						{						
							array_push($date_key,$key);
							array_push($date_arr,$date);
						}
						$start_date = date ("d-m-Y", strtotime("+1 day", strtotime($start_date)));
					}
				
				$final_date = array_combine($date_key,$date_arr);
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $date_key=>$stat_data)
				{
					array_push($stat_data_key,$date_key);
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
			
				$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);
				$data['search_date']=$search_values_pub;
				
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
					$this->mod_stat_excel->export_publisher_date_wise($data);	
					
				}
				
			}//End of export_publisher_date_wise
			
			public function export_publisher_hour_wise($start=0)
			{
		
				$limit	=	10;
				
				$search_hour_pub=$this->session->userdata('statistics_search_arr');
				
				if($search_hour_pub['parent']!= '')
				{
				
						switch($search_hour_pub['parent']){
							case "PUB": //PUBLISHER SECTION
								
								$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_hourwise($search_hour_pub,$start,$limit);
								$data['stat_adv_det'] = $this->mod_websites->get_publisher($search_hour_pub['sel_publisher_id']);
								
							break;
							case "ZONE": //ZONES SECTION
							$data['stat_adv_det'] = $this->mod_websites->get_publisher($search_hour_pub['sel_publisher_id']);
							$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_hourwise($search_hour_pub,$start,$limit);
							$data['zone_list'] = $this->mod_websites->get_zone_name($search_hour_pub['sel_zone_id']);							
							
							
												   
							break;
						
					}
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
																							'SPEND'=>0,
																							'CTR'=>number_format(0.00,2),
																							'PUBSHARE'=>0
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
				
		
				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();
				$data['search_hour']=$search_hour_pub;
						
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
					$this->mod_stat_excel->export_publisher_hour_wise($data);
		
				}
				
			}//End of export_publisher_hour_wise	
			
	}				
		
