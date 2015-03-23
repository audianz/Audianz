<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Advertiser extends CI_Controller {          

	public function __construct()
    	{
		parent::__construct();
		/* Models */
		$this->load->model("mod_adv_notifications");
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{ ?>
			<script>window.location.href = "<?php echo site_url('site');?>"</script>	
		<?php }
    	}
	
	public function index()
	{
		redirect("advertiser/dashboard");		
	}

	public  function advertiser_notification()
	{

		if($this->session->userdata('session_advertiser_id'))
		{
			//echo '<pre>';
			$today =	date("Y-m-d");
			//Advertisers Count
			//Today Pending Advertisers
			$clientid = $this->session->userdata('session_advertiser_id');//'14';//Pass the Session Value
			
			$total_ban_list		=		$this->mod_adv_notifications->get_banner_list($clientid);
	
			//print_r($total_ban_list);	
			$total_ban_list_count	=		count($total_ban_list);
			
			if($total_ban_list_count>0 && isset($total_ban_list_count))
			{
					
					$banner_list = array();
					foreach($total_ban_list  as $id=>$value)
					{
							$item = array();
							
							
								$last_ban_add_entered_year		=		$value['YEAR'];
								$last_ban_add_entered_month		=		$value['MONTH'];
								$last_ban_add_entered_day		=		$value['DAY'];
								$last_ban_add_entered_hour		=		$value['HOUR'];
								$last_ban_add_entered_minute		=		$value['MINUTE'];
								
								if($value['master_banner'] =='-1')
								{
									$item['ban_type']	=	'Text';
								}else if($value['master_banner'] =='-2')
								{
									$item['ban_type']	=	'Image';	
								}else if($value['master_banner'] =='-3')
								{
									$item['ban_type']	=	'Tablet';
								}else{
									$item['ban_type']	=	'Unknown';
								}								
																
								$item['campaignid']	=	$value['campaignid'];								
								$item['banner_id']	=	$value['bannerid'];
								$item['adminstatus']	= $value['adminstatus'];
								$item['banner_name']=	$value['bannername'];
								
						$item['duration']	=	 get_notify_duration($last_ban_add_entered_minute,$last_ban_add_entered_hour,$last_ban_add_entered_day,$last_ban_add_entered_month,$last_ban_add_entered_year);
						
								array_push($banner_list,$item);
								
								//$data['pending_banner_list_test']	=	 array_merge($data['pending_banner_list'],$pending_banner_list);
							
							//print_r($data['pending_banner_list_test']);
							//echo '<br/>';
					}			
				$data['banner_list']	=	$banner_list;
			}else{
				$data['banner_list']	=FALSE;
			}
			
		echo $this->load->view('advertiser/advertiser_notification_area',$data);
		}else{
			echo 'Sorry, Your session has been expired so you will redirected to login page';
			exit; 
		}	

			}	
}	
