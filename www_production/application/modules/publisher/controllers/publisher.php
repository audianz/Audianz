<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publisher extends CI_Controller {

	public function __construct() 
    {
		parent::__construct();
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{ ?>
			<script>window.location.href = "<?php echo site_url('site');?>"</script>
		<?php }
		//$this->load->model("mod_pub_notifications");
		
    }
	/* Publisher Page */
	public function index()
	{
		redirect("publisher/dashboard");		 		
	}
	
	public function publisher_notification()
	{
			
		if($this->session->userdata('session_publisher_id') !='')
		{
			//echo '<pre>';
			$today =	date("Y-m-d");
			//Advertisers Count
			//Today Pending Advertisers
			$clientid = $this->session->userdata('session_publisher_id');//Pass the Session Value
			
			$total_pay_list		=		$this->mod_pub_notifications->get_payment_list($clientid);
		
			
				$total_pay_list_count	=		count($total_pay_list);
			
	
					$data['payment_list']	=	$total_pay_list;

			
		echo $this->load->view('publisher/publisher_notification_area',$data);
		}else{
			echo 'Sorry, Your session has been expired so you will redirected to login page';
			exit;
		}					

			
	}
	
	
}

/* End of file publisher.php */
/* Location: ./modules/publisher/publisher.php */
