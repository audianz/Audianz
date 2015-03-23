<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_dashboard');
		$this->load->model('mod_notifications');
		
    }
	/* Dashboard Page */
	public function index()
	{
		$this->session->set_userdata('total_notify_count',25);
		redirect("admin/dashboard");		 
	//	redirect("admin/pages");
	}
	
	public function admin_notification($notify_type = '')
	{
		
		if($this->session->userdata('mads_sess_admin_id')!='')
		{ 
			
			if($notify_type !='' && isset($notify_type))
			{
			//echo '<pre>';
			$today =	date("Y-m-d");
			//Advertisers Count
			//Today Pending Advertisers
			$pending_ad_list_count		=		$this->mod_dashboard->get_users_count('Advertiser','Pending');
			$data['total_pending_ad_list_count']		=		$pending_ad_list_count[0]->total;
			
			//Advertiser   Registered today
			$approved_ad_list_count_today	=		$this->mod_dashboard->get_users_count_today('Advertiser');
			$data['approved_ad_list_count_today']		=		$approved_ad_list_count_today[0]->total;

			if($data['total_pending_ad_list_count']>0)
			{			
				$where_type = 'ADVERTISER';
				$last_duration_adv_app = $this->mod_notifications->get_last_duration_app($where_type);
				//print_r($last_duration_adv_app);
				//exit;
				$duration_adv_app_count	=	count($last_duration_adv_app);
				if($duration_adv_app_count>0)
				{
						$last_adv_app_entered_year		=		$last_duration_adv_app[0]->YEAR;
						$last_adv_app_entered_month		=		$last_duration_adv_app[0]->MONTH;
						$last_adv_app_entered_day		=		$last_duration_adv_app[0]->DAY;
						$last_adv_app_entered_hour		=		$last_duration_adv_app[0]->HOUR;
						$last_adv_app_entered_minute		=		$last_duration_adv_app[0]->MINUTE;
						
						$data['last_adv_entered_app']	=	 get_notify_duration($last_adv_app_entered_minute,$last_adv_app_entered_hour,$last_adv_app_entered_day,$last_adv_app_entered_month,$last_adv_app_entered_year);
				}

			}
			if($data['approved_ad_list_count_today']>0 )
			{
					$where_type = 'ADVERTISER';
				$last_duration_adv = $this->mod_notifications->get_last_duration_app_added($where_type);
				$duration_adv_count	=	count($last_duration_adv);
				if($duration_adv_count>0)
				{

						$last_adv_entered_hour		=		$last_duration_adv[0]->HOUR;
						$last_adv_entered_minute		=		$last_duration_adv[0]->MINUTE;
						
						$data['last_adv_entered_add']	=	 get_notify_duration($last_adv_entered_minute,$last_adv_entered_hour);
			}
			
			}
			
			//Publishers Count
			//Total Pending Publishers
			$pending_pub_list_count		=		$this->mod_dashboard->get_users_count('Publisher','Pending');
			$data['total_pending_pub_list_count']		=		$pending_pub_list_count[0]->total;
			//exit;
			//Publisher   Registered today
			$approved_pub_list_count_today	=	 	$this->mod_dashboard->get_users_count_today('Publisher');		
			$data['approved_pub_list_count_today']		=		$approved_pub_list_count_today[0]->total;
			
			if($data['total_pending_pub_list_count']>0)
			{
				$where_type = 'TRAFFICKER';
				$last_duration_pub = $this->mod_notifications->get_last_duration_app($where_type);
				
				$duration_pub_count	=	count($last_duration_pub);
				if($duration_pub_count>0)
				{
						$last_pub_entered_year		=		$last_duration_pub[0]->YEAR;
						$last_pub_entered_month		=		$last_duration_pub[0]->MONTH;
						$last_pub_entered_day		=		$last_duration_pub[0]->DAY;
						$last_pub_entered_hour		=		$last_duration_pub[0]->HOUR;
						$last_pub_entered_minute		=		$last_duration_pub[0]->MINUTE;
						
						$data['last_pub_entered_app']	=	 get_notify_duration($last_pub_entered_minute,$last_pub_entered_hour,$last_pub_entered_day,$last_pub_entered_month,$last_pub_entered_year);
				}

			}
			
			if($data['approved_pub_list_count_today']>0 )
			{
					$where_type = 'TRAFFICKER';
				$last_duration_pub = $this->mod_notifications->get_last_duration_app_added($where_type);
				
				//print_r($last_duration_pub);
				//exit;
				
				$duration_pub_count	=	count($last_duration_pub);
				if($duration_pub_count>0)
				{
						$last_pub_entered_hour		=		$last_duration_pub[0]->HOUR;
						$last_pub_entered_minute		=		$last_duration_pub[0]->MINUTE;
						
						$data['last_pub_entered_add']	=	 get_notify_duration($last_pub_entered_minute,$last_pub_entered_hour);
				}

			}
			
			//Banners List
			//Total Pending Banners
			$pending_ban_list_count	=	$this->mod_notifications->get_ban_approval_list_count();
			$data['total_pending_ban_list_count']		=		$pending_ban_list_count[0]->total;
			
			//Total Banners Added Today
			$where_arr	=		array('date(ox_banners.updated)'=>$today);
			$data['approved_ban_list_count_today']	=		$this->mod_notifications->get_banners_count($where_arr);
			
			if($data['total_pending_ban_list_count']>0)
			{
					
					$where_type = 'Pending';
					$last_duration_ban = $this->mod_notifications->get_last_duration_ban($where_type);
					
				$duration_ban_count	=	count($last_duration_ban);
				if($duration_ban_count>0)
				{
						$last_ban_entered_year		=		$last_duration_ban[0]->YEAR;
						$last_ban_entered_month		=		$last_duration_ban[0]->MONTH;
						$last_ban_entered_day		=		$last_duration_ban[0]->DAY;
						$last_ban_entered_hour		=		$last_duration_ban[0]->HOUR;
						$last_ban_entered_minute		=		$last_duration_ban[0]->MINUTE;
						
						$data['last_ban_entered_pending']	=	 get_notify_duration($last_ban_entered_minute,$last_ban_entered_hour,$last_ban_entered_day,$last_ban_entered_month,$last_ban_entered_year);
				}
					
			}
			
			if($data['approved_ban_list_count_today']>0)
			{
						$where_type = 'Approved';
					$last_duration_ban = $this->mod_notifications->get_last_duration_ban($where_type);
					
				$duration_ban_count	=	count($last_duration_ban);
				if($duration_ban_count>0)
				{
						$last_ban_entered_year		=		$last_duration_ban[0]->YEAR;
						$last_ban_entered_month		=		$last_duration_ban[0]->MONTH;
						$last_ban_entered_day		=		$last_duration_ban[0]->DAY;
						$last_ban_entered_hour		=		$last_duration_ban[0]->HOUR;
						$last_ban_entered_minute		=		$last_duration_ban[0]->MINUTE;
						
						$data['last_ban_entered_app']	=	 get_notify_duration($last_ban_entered_minute,$last_ban_entered_hour,$last_ban_entered_day,$last_ban_entered_month,$last_ban_entered_year);
			}
			}
			//Payment Yet To Be Approved
			$pending_payment_list	=	 $this->mod_notifications->get_pending_payment_list();
			
			//echo count($pending_payment_list);
			if($pending_payment_list !=FALSE)
			{

			//count the Number of  Pending Payment Approvals
				$data['pending_payment_list_count']	=		count($pending_payment_list);
			}else{
			
			$data['pending_payment_list_count']	= 0;	
			}
			

			if($data['pending_payment_list_count']>0)
			{
					$last_pay_entered_year		=		$pending_payment_list[0]->YEAR;
					$last_pay_entered_month		=		$pending_payment_list[0]->MONTH;
					$last_pay_entered_day		=		$pending_payment_list[0]->DAY;
					$last_pay_entered_hour		=		$pending_payment_list[0]->HOUR;
					$last_pay_entered_minute	=	$pending_payment_list[0]->MINUTE;
											
					$data['last_pay_entered_app']	=	 get_notify_duration($last_pay_entered_minute,$last_pay_entered_hour,$last_pay_entered_day,$last_pay_entered_month,$last_pay_entered_year);
			}
			
			//Total Campaigns Added today
			$today_campaign_count	=		$this->mod_notifications->retrieve_campaigns_count('today');
			$data['today_campaign_count']						=		$today_campaign_count[0]->total_camp;
			
			if($data['today_campaign_count']>0)
			{
					$type = 'campaigns';
					$tbl_name = 'ox_campaigns';
					$campaign_last_entered		=		$this->mod_notifications->get_last_duration_camp_zones($type,$tbl_name);
					
					$last_campaign_entered_hour			=		$campaign_last_entered[0]->HOUR;
					$last_campaign_entered_minute		=		$campaign_last_entered[0]->MINUTE;
			
					$data['last_campaign_entered_today']	=	 get_notify_duration($last_campaign_entered_minute,$last_campaign_entered_hour);
			}
			
			//Total Zones Added Today
			$data['today_zones_count']	=		$this->mod_notifications->get_zones_count();
			
			if($data['today_zones_count']>0)
			{
					$type = 'zones';
					$tbl_name = 'ox_zones';
					$zone_last_entered		=		$this->mod_notifications->get_last_duration_camp_zones($type,$tbl_name);
					
					$last_zone_entered_hour			=		$zone_last_entered[0]->HOUR;
					$last_zone_entered_minute		=		$zone_last_entered[0]->MINUTE;
			
					$data['last_zone_entered_today']	=	 get_notify_duration($last_zone_entered_minute,$last_zone_entered_hour);
			}

			//Admin Approval Advertiser Not Viewed
			$where_arr	=	array('oxm_newusers.account_type'=>'ADVERTISER','oxm_approval_notification.read_status'=>0);
			$adv_unviewed_list_count		=		$this->mod_notifications->get_pending_approvals($where_arr);
			$data['total_unviewed_adv']	=	count($adv_unviewed_list_count);
			//print_r($adv_unviewed_list_count);
			//exit;			
			//Retreive the  duration contents
			if($data['total_unviewed_adv'] >0)
			{
				$last_adv_entered_year		=		$adv_unviewed_list_count[0]->YEAR;
				$last_adv_entered_month		=		$adv_unviewed_list_count[0]->MONTH;
				$last_adv_entered_day		=		$adv_unviewed_list_count[0]->DAY;
				$last_adv_entered_hour		=		$adv_unviewed_list_count[0]->HOUR;
				$last_adv_entered_minute		=		$adv_unviewed_list_count[0]->MINUTE;
			
			$data['last_adv_entered']	=	 get_notify_duration($last_adv_entered_minute,$last_adv_entered_hour,$last_adv_entered_day,$last_adv_entered_month,$last_adv_entered_year);
			
			}
			
			//Admin Approval Publisher Not Viewed
			$where_arr	=	array('oxm_newusers.account_type'=>'TRAFFICKER','oxm_approval_notification.read_status'=>0);
			$pub_unviewed_list_count		=		$this->mod_notifications->get_pending_approvals($where_arr);
			$data['total_unviewed_pub']	=	count($pub_unviewed_list_count);
			//print_r($adv_unviewed_list_count);
			//Retreive the  duration contents
			if($data['total_unviewed_pub'] >0)
			{
				$last_pub_entered_year		=		$pub_unviewed_list_count[0]->YEAR;
				$last_pub_entered_month		=		$pub_unviewed_list_count[0]->MONTH;
				$last_pub_entered_day		=		$pub_unviewed_list_count[0]->DAY;
				$last_pub_entered_hour		=		$pub_unviewed_list_count[0]->HOUR;
				$last_pub_entered_minute		=		$pub_unviewed_list_count[0]->MINUTE;
			
			$data['last_pub_entered']	=	 get_notify_duration($last_pub_entered_minute,$last_pub_entered_hour,$last_pub_entered_day,$last_pub_entered_month,$last_pub_entered_year);
			}
			
			
			if($notify_type =='approval')
			{
				echo $this->load->view('admin/admin_notification_area',$data);
			}else if($notify_type=='today')
			{
				echo $this->load->view('admin/admin_today_notification_area',$data);
			}
	}else{
			redirect('admin/dashboard');
	}
}else{
	echo 'Sorry You will be Redirected to Login Shortly';
	exit;
}
}	
}

/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
