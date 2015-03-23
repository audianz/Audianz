<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Performance_report extends CI_Controller { 

	public function __construct()
    { 
		parent::__construct();
		
		
		/* Models */
		$this->load->model("mod_statistics");
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	public function index(){
		
				
				$limit	=	10;
				
				// GETTING SEARCH FIELDS
				
				$search_arr	=	array();
				
				$search_arr['sel_advertiser_id']	=	$this->session->userdata('session_advertiser_id');
				
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
							$search_arr['from_date']			= $this->mod_statistics->get_start_date($this->session->userdata('session_advertiser_account_id'));
							$search_arr['to_date']				= date("Y/m/d");	
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				//echo "<pre>"; print_r($search_arr); exit;
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 					= breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				
				$data['stat_data'] = $this->mod_statistics->get_perfomance_report_for_advertiser_datewise($search_arr);
				
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("report/date_wise_list",$data,true);
				$this->load->view('advertiser_layout',$data);
		
		
	}
}

/* End of file site_settings.php */
/* Location: ./modules/admin/site_settings.php */
