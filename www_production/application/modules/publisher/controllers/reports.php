<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}
		
		$this->load->model("mod_reports");
		$this->load->library('Paypal_Lib');
		$this->load->library("PHPExcel");
		$this->load->library("PHPExcel/IOFactory"); 
    }
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
            	 $search_arr	=	array();
		$data['search_type']=($this->input->post('search_field')=='')?"all":$this->input->post('search_field');

		$data['advertiser']	=$this->mod_reports->advertiser();
		//$data['campaign']      =$this->mod_reports->campaign();
		
		$data['page_content']  =$this->load->view('reports',$data,true);
	  	$this->load->view('publisher_layout',$data);
	}

	function showcampaign()
	{
		$advid=$this->input->post("advid");
		$data['campaign']=$this->mod_reports->campaign($advid);
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
							$data['from_date']			=$this->mod_reports->get_start_date($this->session->userdata('session_publisher_account_id'));
							$data['to_date']				=	date("Y/m/d");
							break;		
	}
	$data['advid']=$this->input->post('advertiser');
	$data['campaignid']=$this->input->post('campaign');

	
	$this->mod_reports->export_report_details_excel($data);
	}


   
	
}

/* End of file myaccount.php */
/* Location: ./modules/admin/myaccount.php */
