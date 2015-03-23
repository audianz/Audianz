<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Statistics_advertiser extends CI_Controller 
	{ 
		var $page_limit	=	10; 
		
		public function __construct() 
   			{
					parent::__construct();
					
					$this->load->model('mod_advertiser');
					$this->load->model('mod_statistics');
					$this->load->model('mod_stat_excel');
					$this->load->model('mod_campaign');
					$this->load->model("mod_zones");
					
					/*Libraries */
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
				
				if($this->input->post('sel_advertiser_id') != ''){
					$search_arr['sel_advertiser_id']	=	$this->input->post('sel_advertiser_id');
				}
				
				if($this->input->post('sel_publisher_id') != ''){
					$search_arr['sel_publisher_id']	=	$this->input->post('sel_publisher_id');
				}
				
				$search_arr['search_type']			=	($this->input->post('search_field')=='')?"all":$this->input->post('search_field');
				
				switch($search_arr['search_type']){
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
				$link 					= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				$data['advertiser_combo'] = $this->mod_campaign->get_advertiser_list();
				
				 
				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();
				
				if($this->input->post('sel_advertiser_id') != ''){
					
					$data['advertiser_list'] = $this->mod_advertiser->get_advertisers("single",$this->input->post('sel_advertiser_id'));
				}
				else
				{
					
					$data['advertiser_list'] = $this->mod_advertiser->get_advertisers("all");
				}
				
				
				//$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser($search_arr,$start,$limit);
				/*--------------------------------------------------------------
					Pagination  Config Setup
				 ---------------------------------------------------------------*/
						
				$stat_data = $this->mod_statistics->get_statistics_for_advertiser($search_arr);
				//echo "<pre>"; print_r($stat_data); exit;
				//$limit = $this->config->item('page_limit');
				/*$config['per_page'] = $limit;
				$config['base_url'] = site_url("admin/statistics_advertiser/view");
				$config['uri_segment'] 	= 4;
				$config['total_rows'] 	= count($list_data['stat_list']);//'5';
				$config['next_link'] 	= $this->lang->line("pagination_next_link");
				$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
				$config['last_link'] 	= $this->lang->line("pagination_last_link");		
				$config['first_link'] 	= $this->lang->line("pagination_first_link");
				$this->pagination->initialize($config);		
				$stat_data = $this->mod_statistics->get_statistics_for_advertiser($search_arr,$start,$limit);*/
				$data['stat_data']	=	$stat_data;
				
			//	print_r($data['stat_data']);return ;
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("statistics/advertiser/list",$data,true);
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
			
			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 				= $search_type;
            $search_arr['parent'] 				= $parent;
			$search_arr['sel_advertiser_id'] 		= $ref_id;
			

			switch($parent){
				case "ADV": //ADVERTISER SECTION
										$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser_datewise($search_arr,$start,$limit);
									
										$data['stat_adv_det'] = $this->mod_advertiser->get_advertisers("single",$ref_id);
				break;
				case "CAMP": //CAMPAIGNS SECTION
										$data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_datewise($search_arr,$start,$limit);
										$camp_data    = $this->mod_advertiser->get_campaigns_det($ref_id);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
				case "BAN": //BANNERS SECTION
                                        $data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_banners_datewise($search_arr,$start,$limit);
										$banner_data    = $this->mod_statistics->get_banner_det($ref_id);
                                        $data['stat_banner_data']      =   $banner_data;
                                        $camp_data    = $this->mod_advertiser->get_campaigns_det($banner_data->campaignid);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
			}
			
			//customized  by dinesh.a for displaying 0 values
			$date_arr = array();
			$date_key = array();
				while (strtotime($start_date) <= strtotime($end_date))
				{
					$start_date = date('m/d/Y',strtotime($start_date));
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
					$start_date = date ("m/d/Y", strtotime("+1 day", strtotime($start_date)));
				}
				
				$final_date = array_combine($date_key,$date_arr);

				
				
				
				$stat_data_value = array();
				$stat_data_key = array();
				foreach($data['stat_data']['stat_list'] as $date_key=>$stat_data)
				{
					
					array_push($stat_data_key, date('m/d/Y',strtotime($date_key)));
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
				$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);

				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();

				$data['breadcrumb'] 	= $link;
				
				$data['advertiser_combo'] = $this->mod_campaign->get_advertiser_list();
				 
				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();
				
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("statistics/advertiser/date_wise_list",$data,true);
				$this->load->view('page_layout',$data);
			
		}
		else
		{
			redirect('admin/statistics_advertiser');
		}
	}


        public function view_hour_wise($start=0){

		$limit	=	10;

		if($this->input->post('parent') != ''){

			$start_date		=	$this->input->post('start_date');
			$end_date		=	$this->input->post('end_date');
			$search_type            =	$this->input->post('search_type');
                        $sel_date		=	$this->input->post('sel_date');
			$parent			=	$this->input->post('parent');
			$ref_id			=	$this->input->post('ref_id');

			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 				= $search_type;
                        $search_arr['sel_date'] 				= date("Y-m-d",strtotime($sel_date));
			$search_arr['parent']					= $parent;
			$search_arr['sel_advertiser_id'] 		= $ref_id;
			switch($parent){
				case "ADV": //ADVERTISER SECTION
					
					$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser_hourwise($search_arr,$start,$limit);
					$data['stat_adv_det'] = $this->mod_advertiser->get_advertisers("single",$ref_id);
				break;
				case "CAMP": //CAMPAIGNS SECTION
					
					$data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_hourwise($search_arr,$start,$limit);
					$camp_data    = $this->mod_advertiser->get_campaigns_det($ref_id);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
				case "BAN": //BANNERS SECTION
					
					$data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_banners_hourwise($search_arr,$start,$limit);
					$banner_data    = $this->mod_statistics->get_banner_det($ref_id);
                                        $data['stat_banner_data']      =   $banner_data;
                                        $camp_data    = $this->mod_advertiser->get_campaigns_det($banner_data->campaignid);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
			}
			

                        
                                //CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS

				$this->session->set_userdata('statistics_search_arr',$search_arr);

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

				$data['advertiser_combo'] = $this->mod_campaign->get_advertiser_list();

				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();


				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
                
				$data['page_content']		=		 $this->load->view("statistics/advertiser/hour_wise_list",$data,true);
				$this->load->view('page_layout',$data);

		}
		else
		{
			redirect('admin/statistics_advertiser');
		}
	}



	
			
			public function view_more_details(){
				
				$adv_id  = $this->input->post("advertiser_id");
				
				$search_arr	=	array();
				
				if(count($this->session->userdata('statistics_search_arr')) > 0){
					$search_arr = $this->session->userdata('statistics_search_arr');
				}
				
				if($adv_id != false){
					
					$data['campaigns_list'] = $this->mod_statistics->get_campaigns($adv_id);
					
					
					$data['reports']	=	$this->mod_statistics->get_statistics_for_advertiser_campaigns($adv_id,$search_arr);
					
					$this->load->view('statistics/advertiser/campaigns_banners',$data);
				}
				else
				{
					echo '';
					exit;
				}
			}
	
	//Export Advertisrs in Excel
	public function export_advertisers_excel()
	{
			// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				
				$search_arr	=	 $this->session->userdata('statistics_search_arr');
				
				$data['advertiser_list'] = $this->mod_advertiser->get_advertisers("all");

				$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser($search_arr);
				 
				$this->mod_stat_excel->export_advertisers_excel($data);
				
				
	}				
	
	public function export_view_date_wise($start=0){
	
		
		$limit	=	10;
		$search_values=$this->session->userdata('statistics_search_arr');
		$start_date = $search_values['from_date'];
		$end_date =  $search_values['to_date'];
		/*print_r($search_values);
		exit;*/
		if($search_values['parent']!= ''){
			
			
			
			
			switch($search_values['parent']){
				case "ADV": //ADVERTISER SECTION
					$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser_datewise($search_values,$start,$limit);
					$data['stat_adv_det'] = $this->mod_advertiser->get_advertisers("single",$search_values['sel_advertiser_id']);
				break;
				case "CAMP": //CAMPAIGNS SECTION
					$data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_datewise($search_values,$start,$limit);
					$camp_data    = $this->mod_advertiser->get_campaigns_det($search_values['sel_advertiser_id']);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
				case "BAN": //BANNERS SECTION
                                        $data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_banners_datewise($search_values,$start,$limit);
					$banner_data    = $this->mod_statistics->get_banner_det($search_values['sel_advertiser_id']);
                                        $data['stat_banner_data']      =   $banner_data;
                                        $camp_data    = $this->mod_advertiser->get_campaigns_det($banner_data->campaignid);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
			}
			
             //customized  by dinesh.a for displaying 0 values
			$date_arr = array();
			$date_key = array();
				while (strtotime($start_date) <= strtotime($end_date)) {
					$start_date = date('d-m-Y',strtotime($start_date));
					$date_values = array($start_date=>array('IMP'=>0,
																							'CON'=>0,
																							'CLK'=>0,
																							'SPEND'=>0,
																							'CTR'=>number_format(0.00,2)
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
					array_push($stat_data_key,$date_key);
					array_push($stat_data_value,$stat_data);
				}
				
				$final_stat_data = array_combine($stat_data_key,$stat_data_value);
				
			
				$data['stat_data']['stat_list'] = array_merge($final_date,$final_stat_data);              
				
				
				//$data['name'] = $this->mod_advertiser->get_advertiser_details($id);
				
				
				$data['advertiser_combo'] = $this->mod_campaign->get_advertiser_list();
				 
				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();
				
				$data['search_date']=$search_values;
				
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$this->mod_stat_excel->export_date_wise($data);
			
		}
		
	}
	
	public function export_hour_wise($start=0)
	{
		$limit	=	10;
		$search_hour=$this->session->userdata('statistics_search_arr');
		
		if($search_hour['parent']!= '')
		{

			switch($search_hour['parent']){
				case "ADV": //ADVERTISER SECTION
					$data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser_hourwise($search_hour,$start,$limit);
					$data['stat_adv_det'] = $this->mod_advertiser->get_advertisers("single",$search_hour['sel_advertiser_id']);
				break;
				case "CAMP": //CAMPAIGNS SECTION
					$data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_hourwise($search_hour,$start,$limit);
					$camp_data    = $this->mod_advertiser->get_campaigns_det($search_hour['sel_advertiser_id']);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
				case "BAN": //BANNERS SECTION
                                        $data['stat_data']      = $this->mod_statistics->get_statistics_for_advertiser_campaigns_banners_hourwise($search_hour,$start,$limit);
					$banner_data    = $this->mod_statistics->get_banner_det($search_hour['sel_advertiser_id']);
                                        $data['stat_banner_data']      =   $banner_data;
                                        $camp_data    = $this->mod_advertiser->get_campaigns_det($banner_data->campaignid);
                                        $data['stat_camp_data']      =   $camp_data;
                                        $data['stat_adv_det']   = $this->mod_advertiser->get_advertisers("single",$camp_data->clientid);
				break;
			}


				$data['advertiser_combo'] = $this->mod_campaign->get_advertiser_list();

				$data['publisher_combo'] = $this->mod_zones->get_affiliates_list();
				
				$data['search_hour']=$search_hour;
				
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
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$this->mod_stat_excel->export_hour_wise($data);
				
				

		}
		
	}
	
		public function test(){
			
		$search_arr		= 	$this->session->set_userdata('statistics_search_arr');
			
		$t = $this->mod_statistics->get_monthly_report_for_advertiser($search_arr);
		echo "<pre>";
		print_r($t);
		}		
	
			
	}		
