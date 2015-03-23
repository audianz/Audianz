<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Dashboard extends CI_Controller { 

	public function __construct()
    {
		parent::__construct();
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}		
		$this->load->model('mod_dashboard'); 	
		$this->load->model('mod_stat_global');	
		$this->load->model('mod_payments');	
    }
	
	/* Dashboard Page */
	public function index()
	{
		/* Affiliate ID */
		
		$pub_id = $this->session->userdata('session_publisher_id');
		
		/* Breadcrumb Setup Start */		
		$link = breadcrumb_home();
		
		$data['breadcrumb'] = $link;
		/* Breadcrumb Setup End */
		
		$todate = date("Y-m-d");
		
		/* Revenue on Today */
		$today = $this->mod_dashboard->revenue($todate,$pub_id);
		if($today[0]->today_rev!='')
		{
			$data['today'] = $today[0]->today_rev;
		}
		else
		{
			$data['today'] = 0;
		}
		
		
		 /* Current Date for Report Generate */
                $today = date("Y-m-d");
                
               
		/* Daily Statistics */
		$search_arr['sel_publisher_id'] = $pub_id;
		$accid = $this->session->userdata('session_publisher_account_id');
		$search_arr['from_date'] = date("Y/m/d");
		$search_arr['to_date']	= date("Y/m/d");
		$search_arr['search_type'] = 'today';
		$data['daily_stat'] =	$this->mod_dashboard->get_date_report_pub($accid, $search_arr);
		//print_r($data['daily_stat']);exit;
		
			
		/*-------------------------------------------------------------
				Unpaid Earnings
		 -------------------------------------------------------------*/
		 $report_amt = $this->mod_payments->get_report_pub_amt($pub_id);
		 if(count($report_amt)>0)
		 {
			$report_rev = $report_amt[0]->reportrev;
		 }
		 else
		 {
			$report_rev = 0;
		 }

		 $paid_amt  = $this->mod_payments->get_admin_payment($pub_id);
		 if(count($paid_amt)>0)
		 {
			$admin_payment = $paid_amt[0]->adminpayment;
		 }
		 else
		 {
			$admin_payment = 0;
		 }	
	 
		 $unpaid = $report_rev-$admin_payment;
		 $data['unpaidAmt'] = $unpaid;
		
		/* $unpaid = $this->mod_dashboard->unpaid_earnings($pub_id);
		$unpaidAmt = $unpaid[0]->pub_amt+$unpaid[0]->adm_amt;
		$data['unpaidAmt'] = $unpaidAmt; */
		
		/* Last Issued Payment */
		$last_payment = $this->mod_dashboard->last_issued_payment($pub_id);
		if(count($last_payment)>0)
		{
			$last_pay_amt = $last_payment[0]->amount;
			$data['last_pay_amt'] = $last_pay_amt;
			
			$last_pay_date = $last_payment[0]->clearing_date;
			$data['last_pay_date'] = $last_pay_date;			
		}
		else
		{
			$data['last_pay_amt'] = '0';
			$data['last_pay_date'] = '';
		}
		/* YESTERDAY'S EARNING */
		$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
		$revenue_yesterday = $this->mod_dashboard->revenue($yesterday,$pub_id);
		$rev_yesterday = ($revenue_yesterday[0]->today_rev!="")?$revenue_yesterday[0]->today_rev:'0';
		$data['rev_yesterday'] = $rev_yesterday;
		/* CURRENT MONTH EARNINGS */	
		$thismonth = date('m');
		$where_pub = array('ox_zones.affiliateid'=>$pub_id);
		$revenue_month = $this->mod_dashboard->month_revenue($thismonth,$where_pub);
		$rev_tismonth = ($revenue_month[0]->month_rev!="")?$revenue_month[0]->month_rev:'0';
		$data['rev_tismonth'] = $rev_tismonth;
		/* LAST MONTH EARNINGS */	
		$lastmonthrev = date("m", strtotime("-1 month"));
		$revenue_lmonth = $this->mod_dashboard->month_revenue($lastmonthrev,$where_pub);
		$rev_prevmonth = ($revenue_lmonth[0]->month_rev!="")?$revenue_lmonth[0]->month_rev:'0';
		$data['rev_prevmonth'] = $rev_prevmonth;
		/* UNIQUE IMPRESSIONS */
		$where_imp = array("ox_zones.affiliateid"=>$pub_id,"month(ox_unique.date_time)"=>date('m'));
		$unique_imp_c = $this->mod_dashboard->unique_imp_count($where_imp);
		if(count($unique_imp_c)>0)
		{
				$where_affid = $pub_id;
				$where_date = date('m');
				$unique_imp = $this->mod_dashboard->unique_impressions($where_affid,$where_date);
		}
		else
		{
				$where_imp_nc = array("ox_zones.affiliateid"=>$pub_id,"month(ox_data_bkt_unique_m.interval_start)"=>date('m'));
				$unique_imp = $this->mod_dashboard->get_unique_imp_nocount($where_imp_nc);
		}
		$data['unique_imp'] = $unique_imp;
		/* UNIQUE CLICKS */
		$where_click = array("ox_zones.affiliateid"=>$pub_id,"month(ox_unique.date_time)"=>date('m'));
		$unique_click_c = $this->mod_dashboard->unique_click_count($where_click);
		if(count($unique_click_c)>0)
		{
				$where_affid = $pub_id;
				$where_date = date('m');
				$unique_click = $this->mod_dashboard->unique_clicks($where_affid,$where_date);
		}
		else
		{
				$where_click_nc = array("ox_zones.affiliateid"=>$pub_id,"month(ox_data_bkt_unique_c.interval_start)"=>date('m'));
				$unique_click = $this->mod_dashboard->get_unique_click_nocount($where_click_nc);
		}
		/* COMMON STATS */
		$where_com = array("ox_zones.affiliateid"=>$pub_id,"month(ox_data_summary_ad_hourly.date_time)"=>date('m'));
		$common_stat = $this->mod_dashboard->get_common_stats($where_com);
		
		/* BUCKET IMPRESSIONS */
		$where_bkt_imp = array("ox_zones.affiliateid"=>$pub_id,"month(ox_data_bkt_m.interval_start)"=>date('m'));
		$buk_imp = $this->mod_dashboard->bucket_impression($where_bkt_imp);
		
		/* BUCKET CLICKS */
		$where_bkt_click = array("ox_zones.affiliateid"=>$pub_id,"month(ox_data_bkt_c.interval_start)"=>date('m'));
		$buk_click = $this->mod_dashboard->bucket_click($where_bkt_click);
		
		$tot_imp = $buk_imp[0]->bktimp_count+$common_stat[0]->comimp;
		$tot_click = $buk_click[0]->bktclicks+$common_stat[0]->comclick;
		
		/* CTR */
		if($tot_click!=''&&$tot_imp!='')
		{
			$ctrval = ($tot_click/$tot_imp)*100;
		}
		else
		{
			$ctrval = 0;
		}
		
		/* Monthly Revenue for Pie Chart */
		$where_pie_user = $pub_id;
		$pie_rev = $this->mod_dashboard->get_past_month_revenues($where_pie_user);
		$data['pieval'] = $pie_rev;
		
		/* Global Map Stats */
		
		$search_array['sel_ref_id'] = $this->session->userdata('session_publisher_account_id');
		
		$map = $this->mod_stat_global->get_statistics_country_wise($search_array);
		
		$data['map']			= $map;
		
		$data['unique_imp'] 	= $unique_imp;
		$data['unique_click'] 	= $unique_click;
		$data['o_impressions'] 	= $tot_imp;
		$data['o_clicks'] 		= $tot_click;
		
		if($ctrval==0)
		{		
			$ctr = 0;
		}
		else
		{
			$ctr = number_format($ctrval,2)."%";
		}		
		$data['o_ctr'] = $ctr;
		
		
		/*-------------------------------------------------------------
				Get Zone Count by Pub ID
		 -------------------------------------------------------------*/
		$zones = $this->mod_dashboard->get_zone_count($pub_id);
		$data['zones'] = $zones;
		/*-------------------------------------------------------------
				Get Total Zones
		 -------------------------------------------------------------*/
		$totzones = $this->mod_dashboard->get_zone_count();
		$data['totzones'] = $totzones; 
		//echo $zones.'-'.$totzones; exit;
		
		/*-------------------------------------------------------------
				Get Linked Campaigns by Pub ID
		 -------------------------------------------------------------*/
		 $linkcamp = $this->mod_dashboard->get_linked_campaigns($pub_id);
		 $data['linkcamps'] = $linkcamp;
		 
		 /*-------------------------------------------------------------
				Get Linked Campaigns
		 -------------------------------------------------------------*/
		 $totlinkcamp = $this->mod_dashboard->get_linked_campaigns();
		 $data['totlinkcamps'] = $totlinkcamp;
		 
		 /*-------------------------------------------------------------
				Get Linked Banners by Pub ID
		 -------------------------------------------------------------*/
		 $linkbanner = $this->mod_dashboard->get_linked_banners($pub_id);
		 $data['linkbanners'] = $linkbanner;
		 
		 /*-------------------------------------------------------------
				Get Linked Banners
		 -------------------------------------------------------------*/
		 $totlinkbanner = $this->mod_dashboard->get_linked_banners();
		 $data['totlinkbanners'] = $totlinkbanner;
		
		/* Monthly Statistics Report For Advertiser */
		$search_arr['sel_publisher_id']	=	$pub_id;
	

		$data['this_month_stat']	=		$this->mod_dashboard->get_monthly_report_for_publisher($search_arr,'current_month');

		$data['daily_stat']					=		$this->mod_dashboard->get_date_report_for_publisher($search_arr,'today');
		
		$data['pastsdays_stat']		=		$this->mod_dashboard->get_date_report_for_publisher($search_arr,'last_seven_days');

		$data['six_monthly_stat']	=		$this->mod_dashboard->get_monthly_report_for_publisher($search_arr,'past_six_months');
        
		$data['click_to_action_monthly']	= $this->mod_dashboard->get_click_to_action_for_publisher($search_arr,'current_month');
		
		$data['click_to_action_daily']		= $this->mod_dashboard->get_click_to_action_for_publisher($search_arr,'today');
		
		$data['click_to_action_pastdays']	= $this->mod_dashboard->get_week_click_to_action_for_publisher($search_arr,'last_seven_days');
		
		
		$this->load->view('publisher/dashboard',$data);
	}
	
	/* Example Page Layout */
	public function layout()
	{
		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
		
		$this->load->view('layout',$data);
	}
}

/* End of file dashboard.php */
/* Location: ./modules/publisher/dashboard.php */
