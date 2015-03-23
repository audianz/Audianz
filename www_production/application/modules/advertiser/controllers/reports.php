<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
    {
		parent::__construct();

	
		/* Models */
		$this->load->model("mod_reports");
		$this->load->library('Paypal_Lib');
		$this->load->library("PHPExcel");
		$this->load->library("PHPExcel/IOFactory"); 
		
		/* Login Check */
		$check_status = advertiser_login_check();	

		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
		$clientid	=$this->session->userdata('session_advertiser_id'); 
          	
	  	$search_arr	=	array();
		$data['search_type']=($this->input->post('search_field')=='')?"all":$this->input->post('search_field');

		$data['website']	=$this->mod_reports->website();
		
		$data['campaign']      =$this->mod_reports->campaign($clientid);
		$data['page_content']  =$this->load->view('reports',$data,true);
		$this->load->view('advertiser_layout',$data);
	}
	
	/* Site Setting Landing Page */
	public function get_website_campaign()
	{
		$website=$this->input->post("website");
		$data['campaign']      =$this->mod_reports->get_website_campaign($website);
		$data['page_content']  =$this->load->view('reports_filter',$data,true);
		exit;
	}

	function Export_excel_reports()
	{
		
	$day=$this->input->post('search_field');
	switch($day)
	{
					case "today":
							$data['from_date']			=	date("Y/m/d");
							$data['to_date']				=	date("Y/m/d");
							break;	
					case "yesterday":
							$data['from_date']			=	date('Y-m-d', strtotime('Yesterday'));
							$data['to_date']				=	date('Y-m-d', strtotime('Yesterday'));
							break;
					case "thisweek":							
					/*		$start_date		=	date('Y-m-d', strtotime('this week Monday'));
							$start_date1	=	date('Y-m-d', strtotime('last Monday'));
							$end_date		=	date('Y-m-d');
							
							$startDate		=	mktime(0,0,0,$start_date[1],$start_date[2],$start_date[0]);
							$endDate		=	mktime(0,0,0,$end_date[1],$end_date[2],$end_date[0]);
							
							$diff			=	$endDate-$startDate;
							
							$fullDays = floor($diff/(60*60*24));
							
							if($fullDays >0)
							{
								$data['from_date']			=	$start_date;
								$data['to_date']				=	$end_date;
							}
							else
							{
								$data['from_date']			=	$start_date1;
								$data['to_date']				=	$end_date;
							}   */
                                                           $data['from_date']			=	date('Y-m-d', strtotime('last monday', strtotime('next monday')));
							$data['to_date']				=	date("Y/m/d");
                                                            
							break;
					case "last7days":
							$data['from_date']			=	date("Y/m/d",strtotime('Today - 7 Day'));
							$data['to_date']				=	date("Y/m/d");
							break;
					case "thismonth":
							$data['from_date']			=	date('Y/m/d', mktime(0, 0, 0, date('m'), 1, date('Y')));
							$data['to_date']				=	date("Y/m/d");
							break;
					case "lastmonth":
							$data['from_date']			=	date('Y/m/d', mktime(0, 0, 0, (date('m') - 1), 1, date('Y')));
							$data['to_date']				=	date('Y/m/d', mktime(0, 0, 0, date('m'), 0, date('Y')));
							break;
					case "specific_date":
							$data['from_date']			=	date("Y/m/d",strtotime($this->input->post('from_date')));
							$data['to_date']				=	date("Y/m/d",strtotime($this->input->post('to_date')));
							break;		
					default:
							$data['from_date']			=$this->mod_reports->get_start_date($this->session->userdata('session_advertiser_account_id'));
							$data['to_date']				=	date("Y/m/d");
							break;		
}
	$data['websiteid']=$this->input->post('website');
	$data['campaignid']=$this->input->post('campaign');
	
	$this->mod_reports->export_report_details_excel($data);
	}

  
	
	
}

/* End of file Reports.php */
/* Location: ./modules/admin/Reports.php */
