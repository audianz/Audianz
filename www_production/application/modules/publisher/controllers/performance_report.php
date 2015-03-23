<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Performance_report extends CI_Controller
	{
		var $page_limit	=	10;
		public function __construct() 
		{ 
			parent::__construct();
			
			/* Login Check */
			$check_status = publisher_login_check();	
			if($check_status==FALSE)
			{
				redirect('site');
			}
			
			$this->accountid	=$this->session->userdata("session_publisher_account_id"); //51;
				
			/* Models */
			$this->load->model("statistics/mod_publisher");
			$this->load->model("statistics/mod_websites");
			$this->load->model("mod_reports");
			//$this->load->model("statistics/mod_statistics");
			//$this->load->model("statistics/mod_zones");			
			}
			
					
		
					
		public function index($start=0) //view_date_wise($start=0)
		{
			/*   for all zone's by datewise    */
			
			$search_arr	=array();
			
						
			$search_arr['search_type']			=$this->input->post('search_field');
			$chk	=($this->input->post('search_field') =='')?"all":$this->input->post('search_field');
				
				switch($chk){
					case "today":
							$search_arr['from_date']			=date("Y/m/d");
							$search_arr['to_date']				=date("Y/m/d");
							break;	
					case "yesterday":
							$search_arr['from_date']			=date('Y-m-d', strtotime('Yesterday'));
							$search_arr['to_date']				=date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							

                                                        $search_arr['from_date']			=	date('Y-m-d', strtotime('last monday', strtotime('next monday')));
							$search_arr['to_date']				=	date("Y/m/d");
							break;
					case "last7days":
							$search_arr['from_date']			=date("Y/m/d",strtotime('Today - 7 Day'));
							$search_arr['to_date']				=date("Y/m/d");
							break;
					case "thismonth":
							$search_arr['from_date']			=date('Y/m/d', mktime(0, 0, 0, date('m'), 1, date('Y')));
							$search_arr['to_date']				=date("Y/m/d");
							break;
					case "lastmonth":
							$search_arr['from_date']			=date('Y/m/d', mktime(0, 0, 0, (date('m') - 1), 1, date('Y')));
							$search_arr['to_date']				=date('Y/m/d', mktime(0, 0, 0, date('m'), 0, date('Y')));
							break;
					case "specific_date":
							$search_arr['from_date']			=date("Y/m/d",strtotime($this->input->post('from_date')));
							$search_arr['to_date']				=date("Y/m/d",strtotime($this->input->post('to_date')));
							break;
					default:
							$search_arr['from_date']			=$this->mod_reports->get_start_date($this->accountid);
							$search_arr['to_date']				=date("Y/m/d");
							break;		
				}
				
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
		
				
			$limit	=	10;
			
			
			if($this->accountid) {
			
			$start_date		=	($search_arr['from_date']=='')?date("Y/m/d"):$search_arr['from_date'];
			$end_date		=	($search_arr['to_date']=='')?date("Y/m/d"):$search_arr['to_date'];
			$search_type	=	($this->input->post('search_type') =='')?"all":$this->input->post('search_type');
			$parent			=	"ZONE"; //$this->input->post('parent');
			$ref_id			=	$this->accountid; //$this->input->post('ref_id');
			
			$search_arr['from_date'] 				= date("Y-m-d",strtotime($start_date));
			$search_arr['to_date'] 					= date("Y-m-d",strtotime($end_date));
			$search_arr['search_type'] 				= $search_type;
			$search_arr['sel_publisher_id'] 		= $ref_id;
			$search_arr['parent'] 					= $parent;
			
			
			$data['stat_data'] = $this->mod_publisher->get_statistics_for_publisher_zones_datewise($search_arr,$start,$limit);
			
			$data['stat_adv_det'] = $this->mod_websites->get_publisher($ref_id);
						
				//CREATE SESSION FOR CURRENTLY SEARCHED CONDITIONS
				
				$this->session->set_userdata('statistics_search_arr',$search_arr);
				
				/*-------------------------------------------------------------
		 			Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= breadcrumb();
				$data['breadcrumb'] 			= $link;
	
				/*------------------------------------------------------------
					Embed current page content into template layout
				-------------------------------------------------------------*/
				
				$data['page_content']		=		 $this->load->view("publisher/statistics/date_wise_list", $data, true);
				$this->load->view('publisher_layout',$data);

				}
				else
				{
					redirect('publisher');
				}

			

	}
	
	}				
		
