<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class  Logout extends CI_Controller 
{

	public function __construct() 
    {
		parent::__construct();
		
    }
	/* Dashboard Page */
	public function index()
	
	{
		
		
		$this->session->sess_destroy();
		redirect('site');
	}
	
	
}
	


/* End of file dashboard.php */
/* Location: ./modules/dashboard/dashboard.php */
