<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trackers extends CI_Controller {   
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	/* Checks the Status of the Listing Records */
	var $status     = "all";
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Libraries */
		
		
		/* Helpers */
		
		
		/* Models */
		$this->load->model("mod_ip_trackers"); //loc:Settings/models/mod_system_settings
		$this->load->model("site/mod_site");
		
		/* Classes */
    }
	
	
	public function index()
	{
		$this->ip_trackers_list();
	}
	
	
	/* IP Locators List*/
	public function ip_trackers_list($start = 0) 
	{ 
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		$limit = $this->page_limit;
		$list_data = $this->mod_ip_trackers->get_geo_locations_list();
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("page/trackers/ip_trackers_list/");
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		//$list_data = $this->mod_ip_trackers->get_geo_locations_list($limit,$start);
		$data['geo_locations_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= 'Viewers Tracked List';
		
		
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['menu_list']	= $this->mod_site->get_menu();
		$data['page_content']	= $this->load->view("page/trackers/ip_trackers_list",$data,true);
		$this->load->view('site_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
}

/* End of file device_os.php */
/* Location: ./modules/system_settings/controllers/device_os.php */
