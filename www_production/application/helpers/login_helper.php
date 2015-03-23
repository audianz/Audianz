<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CodeIgniter Login Check Helpers
 *
 * @package		CodeIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Dreamajax Technologies
 * @link		http://www.dreamajax.com
 */

// ------------------------------------------------------------------------

/**
 * Form Declaration
 *
 * Creates Login Check for URI
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */
if ( ! function_exists('login'))
{
	/* Check Logged Users Session Data and Return Value for allowing them to access application */
	function advertiser_login_check()
	{
		 $CI =& get_instance();
		 $CI->load->library('session');
		 
		 $advname 	= $CI->session->userdata('session_advertiser_name');
		 $advid 	= $CI->session->userdata('session_advertiser_id');
		 $advcont	= $CI->session->userdata('session_advertiser_contact');
		 $advemail	= $CI->session->userdata('session_advertiser_email');
		 $advacc	= $CI->session->userdata('session_advertiser_account_id');
		 $advtime 	= $CI->session->userdata('current_advertiser_time');
		 $usrtype	= $CI->session->userdata('session_user_type');
		 
		 if(!empty($advname) && !empty($advid) && !empty($advcont) && !empty($advemail) && !empty($advacc) && !empty($advtime) && !empty($usrtype))
		 {
			 return TRUE;
		 }
		 else
		 {
			return FALSE;	
		 }
	}
	
	function publisher_login_check()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		
		$pubname 	= $CI->session->userdata('session_publisher_name');
		$pubid 		= $CI->session->userdata('session_publisher_id');
		$pubcont	= $CI->session->userdata('session_publisher_contact');
		$pubemail	= $CI->session->userdata('session_publisher_email');
		$pubacc		= $CI->session->userdata('session_publisher_account_id');
		$pubtime 	= $CI->session->userdata('publisher_current_time');
		$usrtype	= $CI->session->userdata('session_user_type');
		
		if(!empty($pubname) && !empty($pubid) && !empty($pubcont) && !empty($pubemail) && !empty($pubacc) && !empty($pubtime) && !empty($usrtype))
		{
				return TRUE;
		}
		else
		{
				return FALSE;
		}
	}	
	
	function admin_login_check()
	{
		$CI =& get_instance();
		$CI->load->library('session');
	
		$admin_url = explode('/',$CI->uri->uri_string);
		//print_r($admin_url);
		$username 	= $CI->session->userdata('mads_sess_admin_username');
		$email 		= $CI->session->userdata('mads_sess_admin_email');
		$userid		= $CI->session->userdata('mads_sess_admin_id');
		$cur_time	= $CI->session->userdata('current_time');
		
		if(!empty($username) && !empty($email) && !empty($userid) && !empty($cur_time))
		{
				return TRUE;
		}
		else if($admin_url[0]=="admin")
		{
		  if(isset($admin_url[1]))
		  {  
		  	/*if(($admin_url[1]!="login" && $admin_url[1]!="admin_paypal_payment"))
		    	{
				
				
				redirect('admin/login');
				 		    	
			}else if(isset($admin_url[2]) && $admin_url[2]!="ipn" && $admin_url[2] !="admin_login_process" && $admin_url[2]!="forgot_password" && $admin_url[2]!="forgot_password_process")
			{
				

				redirect('admin/login');	
			}*/

			$allowvar_arr = array('login','ipn');

			$check_arr	=	array_intersect($admin_url,$allowvar_arr);
			if(empty($check_arr))
			{ ?>
				<script>window.location.href = "<?php echo site_url('admin/login');?>"</script>
				<?php //redirect('admin/login','refresh'); ?>

			<?php }else{
				return TRUE;			
			}

		   }	
		}
	}
	
	if ( ! function_exists('get_site_data'))
	{	
		function get_site_data()
		{
			$CI =& get_instance();
			$CI->load->library('session');
			
			$CI->db->select('site_title,site_url,tag_line,logo');
			
			$t = $CI->db->get('oxm_admindetails');
			
			$data = $t->result();
						
			$CI->session->set_userdata('site_data',$data[0]);
		}	
	}
}	

/* End of file login_helper.php */
/* Location: ./application/helpers/login_helper.php */
