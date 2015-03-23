<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Dashboard extends CI_Controller { 

	public function __construct()
    {
		parent::__construct();
		$this->load->model('mod_adv_dashboard'); 
		$this->load->model('statistics/mod_statistical_report' , 'stat_report');		
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	/* Dashboard Page */
	public function index()
	{
                redirect('advertiser/campaigns');return;
		/* Affiliate ID */
		//$this->session->userdata('affiliateid');
		
		/* Breadcrumb Setup Start */		
		$link = breadcrumb_home();
		
		$data['breadcrumb'] = $link;
		/* Breadcrumb Setup End */
		$adv_id= $this->session->userdata('session_advertiser_id');//'14';	
		$todate = date("Y-m-d");
		
		/* Amount Spend on Today */
		$where_arr	=	array('date'=>$todate);
		$today = $this->mod_adv_dashboard->amount_spend($adv_id,$where_arr);
		if($today[0]->amount_spend!='')
		{
			$data['spend_today'] = $today[0]->amount_spend;
		}
		else
		{
			$data['spend_today'] = 0;
		}
	
		/* YESTERDAY'S SPEND */
		$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
		$where_arr	=	array('date'=>$yesterday);
		$spend_yesterday = $this->mod_adv_dashboard->amount_spend($adv_id,$where_arr);
		$spend_yesterday = ($spend_yesterday[0]->amount_spend!="")?$spend_yesterday[0]->amount_spend:'0';
		$data['spend_yesterday'] = $spend_yesterday;

		/* CURRENT MONTH SPEND */	
		
		$thismonth = date('m');
		$where_arr	=	array('Month(date)'=>$thismonth);
		$spend_month = $this->mod_adv_dashboard->amount_spend($adv_id,$where_arr);
		$spend_tismonth = ($spend_month[0]->amount_spend!="")?$spend_month[0]->amount_spend:'0';
		$data['spend_tismonth'] = $spend_tismonth;
		

		/* Monthly Statistics Report For Advertiser */
		$search_arr['sel_advertiser_id']	= $adv_id;

		$data['this_month_stat']			= $this->stat_report->get_monthly_report_for_advertiser($search_arr,'current_month');


		$data['daily_stat']					= $this->stat_report->get_date_report_for_advertiser($search_arr,'today');
		
		$data['pastsdays_stat']				= $this->stat_report->get_date_report_for_advertiser($search_arr,'last_seven_days');
	
	
		$data['six_monthly_stat']			= $this->stat_report->get_monthly_report_for_advertiser($search_arr,'past_six_months');
		
		
		
		$data['click_to_action_monthly']	= $this->stat_report->get_click_to_action_for_advertiser($search_arr,'current_month');
		
		$data['click_to_action_daily']		= $this->stat_report->get_click_to_action_for_advertiser($search_arr,'today');
		
		$data['click_to_action_pastdays']	= $this->stat_report->get_week_click_to_action_for_advertiser($search_arr,'last_seven_days');
	
			
			/* Global Map Stats */
		
		$map = $this->stat_report->get_statistics_country_wise($search_arr);
		
		$data['map']			= $map;
		
		/*Network Stats */
		
		/*Retreive Camp[aigns  Count  For Individual Advertiser */
		//$campaign_list 	=	$this->mod_adv_dashboard->retrieve_campaign_list($adv_id);
		$campaign_list	=	$this->mod_adv_dashboard->retreive_campaign_count_percentage($adv_id);	

		$data['camp_count'] = $campaign_list[0]->CAMPCOUNT;
		$data['camp_percentage']	= $campaign_list[0]->PERCENTAGE;
		
		//echo "Campaigns<pre>"; print_r($campaign_list);
		
		/*Retreive Banners Count For Individual Advertiser */
		//$banner_list		=		$this->mod_adv_dashboard->getBanners($adv_id);
		$banner_list	=	$this->mod_adv_dashboard->retreive_banner_count_percentage($adv_id);	
		$data['ban_count']		=		$banner_list[0]->BANCOUNT;
		$data['ban_percentage'] = $banner_list[0]->PERCENTAGE;
		
		//echo "<br>Banners<pre>"; print_r($banner_list); exit;
		
		/*Top 5 Campaigns and  Top 5 Banners */
		$search_arr['limit']	=	'5';

		$data['top_campaigns']	=		$this->stat_report->get_top_performers_for_advertiser($search_arr);

		
		
		$data['top_banners']	=		$this->stat_report->get_statistics_for_top_banners($adv_id,$search_arr);
		
		$this->load->view('advertiser/dashboard',$data);
	}
	
	/* Example Page Layout */
	public function layout()
	{
		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
		
		$this->load->view('adveriser_layout',$data);
	}
}

/* End of file dashboard.php */
/* Location: ./modules/publisher/dashboard.php */
