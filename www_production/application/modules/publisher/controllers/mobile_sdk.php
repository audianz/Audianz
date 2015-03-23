<?php
class Mobile_sdk extends CI_Controller {  
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	var $linked_type = 0;
	
	
	public function __construct() 
    	{    
		parent::__construct();	
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}	
		
		if($this->session->userdata('session_publisher_id') == '' OR $this->session->userdata('session_user_type') != 'TRAFFICKER'){
			redirect("site");
		}
		
		/* Models */ 
		$this->load->model("mod_zones");
		$this->load->model("statistics/mod_publisher");
		$this->load->helper('download');
		
    	}
		
	/* Zones Landing Page */
	public function index() 
	{ 
		$this->listing();	
	}
	
	/* Zones listing Page */
	public function listing($start=0) 
	{ 		
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		$limit	 = 	$this->page_limit;
		$affiliateid = $this->session->userdata('session_publisher_id');// SET SESSION VALUE
		
					
		$where 	="affiliateid ={$affiliateid} AND (master_zone =-1 OR master_zone =-2 OR master_zone =-3)";				
		/*--------------------------------------------------------------------
			Get the all zones matching above from db for listing
		---------------------------------------------------------------------*/				
		$list_data = $this->mod_zones->list_zones($where);		
		
		
		/*--------------------------------------------------------------
		 				Pagination  Config Setup
		 ---------------------------------------------------------------*/		
		$config['per_page'] 			= 	$limit;
		$config['base_url'] 			= 	site_url("publisher/zones/listing/");
		$config['uri_segment'] 			= 	4;
		$config['total_rows'] 			= 	count($list_data);
		$config['next_link'] 			= 	$this->lang->line("pagination_next_link");
		$config['prev_link'] 			= 	$this->lang->line("pagination_prev_link");		
		$config['last_link'] 			= 	$this->lang->line("pagination_last_link");		
		$config['first_link'] 			= 	$this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);	
		
		/*--------------------------------------------------------------------
		 			Get the all zones from db for pagination
		 ---------------------------------------------------------------------*/		
		$list_data = $this->mod_zones->list_zones($where,$start,$limit=false);
				
		$data['zones_list']				=	$list_data;
		$data['affiliateid']			=	$affiliateid;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		 
		$data['page_title'] 	=	$this->lang->line('label_inventory_zones_page_title');
		
		//GET IMP,CLIKS AND REVENUE for lsited zones
		$search_arr							= 	array();
		$search_arr['from_date']			=	$this->mod_publisher->get_start_date($this->session->userdata('session_publisher_account_id'));
		$search_arr['to_date']				=	date("Y/m/d");
		$search_arr['search_type']			=	"all";
		
		$pub_account_id						=	$this->session->userdata('session_publisher_account_id'); // SET SESSION VARIABLE
		
			
		$data['stat_list']	=	$this->mod_publisher->get_publishers($pub_account_id,$search_arr);
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	=	$this->load->view("publisher/zones/mobile_sdk_list",$data,true);
			
		$this->load->view('publisher_layout',$data);
	}
	
	
	

	/* admin/inventory_zones/Edit zones controller */
	
	public function edit_mobile_sdk($zoneid=0) 
	{ 
		
		
		$affiliateid = $this->session->userdata('session_publisher_id');
		
		/*-----------------------------------------------------------
		 			Breadcrumb Setup Start
		 ------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Authorisation Check
		 -------------------------------------------------------------*/		
		$dup_data = $this->mod_zones->check_pub_authorisation($affiliateid,$zoneid);
		if($dup_data!='')
		{
			/*-----------------------------------------------------------
			 	Get the zones data from db by zoneid for edit
			 ------------------------------------------------------------*/
		
			$record = $this->mod_zones->get_edit_zones($affiliateid,$zoneid);
			$data['record'] = $record;
			
			/*---------------------------------------------------------------
			 	Get the pricing model from db for listing all in select box
			 ----------------------------------------------------------------*/
		
			$model_list = $this->mod_zones->get_pricing_model_list();
	       		$data['mlist'] = $model_list;
		
			/*-----------------------------------------------------------
			 	Get the pricing model from db for selecting in select box
			 ------------------------------------------------------------*/
	
			$model = $this->mod_zones->get_pricing_model($zoneid);
			$data['model'] = $model;
		
			$data['affiliateid']= $this->session->userdata('session_publisher_id');//$affiliateid;
			$data['zoneid']=$zoneid;
		
		
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
			$file_path = 'assets/mobilesdk/';
			$files = scandir($file_path);        
	
			$files_array = array();        
	
			foreach($files as $key=>$file_name) {
	
				$file_name = trim($file_name);
	
				if($file_name != '.' || $file_name != '..') {
					if((is_file($file_path.$file_name))) {
						array_push($files_array,$file_name);
					}
				}
			}
	
			$data['files'] = $files_array;
			
			$data['page_content']	= $this->load->view("zones/edit_mobile_sdk",$data,true);
			$this->load->view('publisher_layout',$data);
		}
		else
		{
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
	
			$data['page_content']	= $this->load->view("zones/no_page",$data,true);
			$this->load->view('publisher_layout',$data);	
		}
		
		
	}
	
	 function download_zip() {

        $this->load->library('zip');
        $file_path = 'assets/mobilesdk/';
        $zip_file_name = $_POST['file_name'];

        $selected_files = $_POST['files'];

        foreach($selected_files as $key=>$file){
            $this->zip->read_file($file_path.$file);
        }

        $this->zip->download($zip_file_name);

    }
	
	public function download()
	{
	$data = file_get_contents("assets/mobilesdk/MobileSDKAndroid.zip"); // Read the file's contents
	$name = 'MobileSDKAndroid.zip';
	force_download($name, $data); 
	}
	public function downloadios()
	{
	$data = file_get_contents("assets/mobilesdk/mJAXMobileAppAdsIOS.zip"); // Read the file's contents
	$name = 'mJAXMobileAppAdsIOS.zip';
	force_download($name, $data); 
	}
	

}	

/* End of file zones.php */
/* Location: ./modules/publisher/controllers/zones.php */

