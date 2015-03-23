<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller 
{

	public function __construct() 
    {
		parent::__construct();
		$this->load->model('mod_page');
    }
	/* Dashboard Page */
	public function index()
	{
		redirect("/");
	}
	
	public function view($page_id=false){
		if($page_id != false){
			// Display Page Content
			
			$data['menu_list']	= $this->mod_page->get_menu();						
			$data['page']		= $this->mod_page->get_page_content($page_id);			
			$this->load->view('view_page',$data); 
			
		}
		else
		{
			redirect("/");
		}
	}
	  
  }