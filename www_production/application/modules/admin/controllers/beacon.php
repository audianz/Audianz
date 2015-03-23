<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beacon extends CI_Controller {

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_beacon');
		
    }
	/* Dashboard Page */
	public function index()
	{
		$this->setup_master();
		
	}
	
	public function setup_master()
	{
		//$data['page_content']	= $this->load->view("admin/beacon/beacon_setup_master",$data,true);
		$this->load->view('admin/beacon/beacon_layout',$data);
	}
	
	public function store_setup()
	{
		$data=$_POST;
		$res=$this->mod_beacon->store_setup_data($data);
		$this->session->set_userdata('setup', 'Beacon setup details submitted successfully.');
		redirect('admin/beacon'); 
	}

	public function get_list ()
	{
		$data['list']=$this->mod_beacon->get_master_setup_list();
		$this->load->view('admin/beacon/setup_list',$data);
			
	}

}

?>

