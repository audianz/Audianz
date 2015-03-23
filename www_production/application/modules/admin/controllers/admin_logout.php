<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_logout extends CI_Controller 
{

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_login');
    }
	/* Dashboard Page */
	public function index()
	
	{
		
		$this->mod_login->update_record_log_details($this->session->userdata('log_id'));
		$this->session->sess_destroy();
		
		redirect("admin/login");
	}
	
	
}
	


/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
