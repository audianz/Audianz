<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Dashboard extends CI_Controller { 

	public function __construct()
        {
		parent::__construct(); 
		$this->load->model("mod_dashboard");
                $this->load->model("mod_campaign");
                $this->load->model("mod_banner");
                $this->load->model("mod_zones");
                $this->load->model('mod_stat_global');
        }
	
	/* Dashboard Page */
	public function index()
	{
                redirect('admin/inventory_campaigns');return; 
		/* Breadcrumb Setup Start */		
		$link = breadcrumb_home();
		
		$data['breadcrumb'] = $link;
		/* Breadcrumb Setup End */

		/**************** Monthly Report(Shotcut) - Start **********/
		
		
		$data['this_month_stat']	= $this->mod_dashboard->get_monthly_report('current_month');
		
		$data['overall_stat']		= $this->mod_dashboard->get_overall_report();
		

		$data['daily_stat']		= $this->mod_dashboard->get_date_report('today');
		
		$data['six_months_stat']	= $this->mod_dashboard->get_monthly_report('past_six_months');
		
		$data['weekly_stat']	=	 $this->mod_dashboard->get_date_report('weekly');
		
		/*Top 5 Campaigns and  Top 5 Banners */
		
		$search_arr['limit']	=	'5';
		
		$data['top_campaigns']	=		$this->mod_dashboard->get_top_performers($search_arr);
		
		$data['top_banners']	=		$this->mod_dashboard->get_statistics_for_top_banners($search_arr);
		
	            /**************** Daily Report- Start *****************/
		
		/* Current Date for Report Generate */
                $today = date("Y-m-d");
		/* Get Unique Impressions */
                $record = $this->mod_dashboard->get_clickimp_count($today);
		if(count($record)>0)
                {
                    $impression = $this->mod_dashboard->get_unique_imp($today);
                    $data['unique_imp'] = $impression;
                }
                else
                {
                    $impression = $this->mod_dashboard->get_unique_imp_nocount(array("ox_data_bkt_unique_m.interval_start"=>$today));                    
                    $data['unique_imp'] = $impression;
                }
                /* Get Unique Clicks */
               $record = $this->mod_dashboard->get_clickcli_count($today);
		if(count($record)>0)
                {
                    $clicks = $this->mod_dashboard->get_unique_click($today);
                    $data['unique_click'] = $clicks;
                }
                else
                {
                    $clicks = $this->mod_dashboard->get_unique_click_nocount($today);
                    $data['unique_click'] = $clicks;
                }

                /* Get Budget Impressions */
  	          $budimp = $this->mod_dashboard->get_budget_impressions($today);
    	           $budgetimp = $budimp[0]->budgetimp;
                
                /* Get Budget Clicks */
                $budclick = $this->mod_dashboard->get_budget_clicks($today);
                $budgetclik = $budclick[0]->budgetclick;
                /* Get Last Hourly Data */
          /*       $hourly = $this->mod_dashboard->get_ad_hourly_data($today);
                $impressions	 = $hourly[0]->hourimp+$budgetimp;
                $clicks          = $hourly[0]->hourclick+$budgetclik;
                if($clicks!='' || $impressions!='')
                {
                    $ctr_per         = ($clicks/$impressions)*100;
                }
                else
                {
                    $ctr_per = 0;
                }
                $ctr             = ($impressions!="" && $clicks!="")?number_format($ctr_per,2):'0';

                $revenue    = $this->mod_dashboard->get_revenue($today);
                $total=number_format($revenue[0]->rev,2);
                $final_total=($total!="")?$total:'$0';

                $data['impressions']    = $impressions;
                $data['clicks']         = $clicks;
                $data['ctr']            = $ctr;
                $data['revenue']        = $final_total; 
               
                /**************** Daily Report- End *****************/

                /************ Overall Report - Start ****************/

                /* Get Unique Impressions */
    /*		$orecord = $this->mod_dashboard->get_clickimp_count();
       
       	 	 if(count($orecord)>0)
            	  { */
         //           $oimpression = $this->mod_dashboard->get_unique_imp();
           //         $data['o_unique_imp'] = $oimpression;
              /*	 }
                else
                {
                    $oimpression = $this->mod_dashboard->get_unique_imp_nocount();
                    $data['o_unique_imp'] = $oimpression;
                }

                 /* Get Unique Clicks */
              /*  $ocrecord = $this->mod_dashboard->get_clickcli_count();
                if(count($ocrecord)>0)
                {
                    $oclicks = $this->mod_dashboard->get_unique_click();
                    
                    $data['o_unique_click'] = $oclicks;
                }
                else
                {
                    $oclicks = $this->mod_dashboard->get_unique_click_nocount();
                    $data['o_unique_click'] = $oclicks;
                }
                /* Get Budget Impressions */
                $obudimp = $this->mod_dashboard->get_budget_impressions();
                $obudgetimp = $obudimp[0]->budgetimp;

                /* Get Budget Clicks */
                $obudclick = $this->mod_dashboard->get_budget_clicks();
                $obudgetclik = $obudclick[0]->budgetclick;

                /* Get Last Hourly Data */
                $ohourly = $this->mod_dashboard->get_ad_hourly_data();
                $oaimpressions	 = $ohourly[0]->hourimp+$obudgetimp;
                $oaclicks          = $ohourly[0]->hourclick+$obudgetclik;
                if($oaclicks!='' || $oaimpressions!='')
                {
                    $octr_per         = ($oaclicks/$oaimpressions)*100;
                }
                else
                {
                    $octr_per = 0;
                }
                $octr             = ($oaimpressions!="" && $oaclicks!="")?number_format($octr_per,2):'0';

                $orevenue    = $this->mod_dashboard->get_revenue();
                $ototal=number_format($orevenue[0]->rev,2);
                $all_total=($ototal!="")?$ototal:'$0';

		$map = $this->mod_stat_global->get_statistics_country_wise();
		
		$data['map'] = $map;

                $data['o_impressions']    = $oaimpressions;
                $data['o_clicks']         = $oaclicks;
                $data['o_ctr']            = $octr;
                $data['o_revenue']        = $all_total;

                /************ Overall Report - End ****************/

                /************** Campaign Count ********************/
                $countcamp = $this->mod_campaign->retrieve_campaign_count();
                $data['countcamp'] = $countcamp;

                /************** Banner Count ********************/
                $countbanner = $this->mod_banner->get_banners_count();
                $data['countbanner'] = $countbanner;
                
                /************** Website Count ********************/
		$countsite = $this->mod_zones->get_website_count();
		$data['countsites'] = $countsite;

                /************** Zones Count ********************/
                $countzones = $this->mod_zones->get_zone_count();
                if($countzones!='')
                {
                	$data['countzones'] = $countzones;
                }
                else
                {
                	$data['countzones'] = 0;
                }

                /************** Website Count ********************/
                //$countbanner = $this->mod_banner->get_banners();
                //$data['countbanner'] = $countbanner;
				
		/********************Total Registered Users****************************/
		$users_list_count	=	$this->mod_dashboard->get_users_count();
		$data['users_list_count']	=	$users_list_count[0]->total;
		
		//Total Approved and Pending Users
		$approved_ad_list_count	=		$this->mod_dashboard->get_users_count('Advertiser','Approved');
		$data['approved_ad_list_count']		=		$approved_ad_list_count[0]->total;
		
		$pending_ad_list_count		=		$this->mod_dashboard->get_users_count('Advertiser','Pending');
		$data['pending_ad_list_count']		=		$pending_ad_list_count[0]->total;	
			
		$approved_pub_list_count	=	 	$this->mod_dashboard->get_users_count('Publisher','Approved');		
		$data['approved_pub_list_count']		=		$approved_pub_list_count[0]->total;
			
		$pending_pub_list_count		=		$this->mod_dashboard->get_users_count('Publisher','Pending');
		$data['pending_pub_list_count']		=		$pending_pub_list_count[0]->total;
		
		/***********************End of Total Registered Users ****************/
		
		/**********************Total  Registered Users Today **************/
		$users_list_count_today	=	$this->mod_dashboard->get_users_count_today();
		$data['users_list_count_today']	=	$users_list_count_today[0]->total;
		
		//Total Approved and Pending Users
		$approved_ad_list_count_today	=		$this->mod_dashboard->get_users_count_today('Advertiser');
		$data['approved_ad_list_count_today']		=		$approved_ad_list_count_today[0]->total;
		
		$approved_pub_list_count_today	=	 	$this->mod_dashboard->get_users_count_today('Publisher');		
		$data['approved_pub_list_count_today']		=		$approved_pub_list_count_today[0]->total;
			
		/********************************End of Registered Users today*******************/
		
		/******************************* Click to action data **************************/
		
		$data['click_to_action_daily']		=$this->mod_dashboard->get_click_to_action_for_admin('today');
		$data['click_to_action_monthly']	=$this->mod_dashboard->get_click_to_action_for_admin('current_month');
		$data['click_to_action_overall']	=$this->mod_dashboard->get_click_to_action_for_admin('overall');
		$data['click_to_action_pastdays']	=$this->mod_dashboard->get_week_click_to_action_list('last_seven_days');
		
		
		
		/******************************* End of Click to action data **************************/
		
		
		/**************************Users to be Approved ***************************/
		$ad_approval_list		=		$this->mod_dashboard->get_user_approval_list('ADVERTISER');
		
		$data['ad_approval_list']	=	$ad_approval_list;
		 
		/************************** Monthly Revenue for Pie Chart ***************************/
		$pie_rev = $this->mod_dashboard->get_past_month_revenues();
		
		$data['pieval'] = $pie_rev;
		
		
		$this->load->view('admin/dashboard',$data);
	}

		
	
	/* Administrator Account Settings */
	public function account_settings()
	{
		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
		
		$this->load->view('account',$data);
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
	/* Change Administrator Password */
	public function change_password()
	{
	
		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
	
		$this->load->view('changepwd',$data);
	}
	/* Create Breadcrumb */
	
}

/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
