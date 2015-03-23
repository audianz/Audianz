<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_device_manufacturers extends CI_Controller {   
	 
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
		$this->load->model("mod_system_settings"); //loc:Settings/models/mod_system_settings
		
		
		/* Classes */
    }
	
	
	public function index()
	{
		$this->device_listing();
	}
	
	/**** Active List *****/
	 public function active($start=0)
	{
		$this->status = "active";
		$this->device_listing($start);
    }
	
	/**** InActive List *****/
	 public function inactive($start=0)
	{
		$this->status = "inactive";
		$this->device_listing($start);
    }
	
	/* System Settings Landing Page */
	public function device_listing($start = 0) 
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
		$list_data = $this->mod_system_settings->get_device_manufacturers($this->status);
		
		$config['per_page'] = $limit;
		//$config['base_url'] = site_url("system_settings/device_manufacturers/device_list/");
		 if($this->status=="active"){
       		$config['base_url'] = site_url("admin/settings_device_manufacturers/active/");
        }
        else if($this->status == "inactive") /* Active Base Url */
        {
             $config['base_url'] = site_url("admin/settings_device_manufacturers/inactive/");
         }
		 else{ /* InActive Base Url */
		 	$config['base_url'] = site_url("admin/settings_device_manufacturers/device_listing/");
		 }
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_system_settings->get_device_manufacturers($this->status,$limit,$start);
		$data['device_manufacturer_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_device_manufacturer_title');	
		
		
		/** Get the Number of Records ****/
		
		$status_field_name			=	'manufacturer_status'; // Provide The name of Status field
		$tbl_name						=	'djx_device_manufacturer'; // Provide the table name
		
		$data['active_records']		= $this->mod_system_settings->get_num_records('active',$status_field_name,$tbl_name);
		$data['all_records']			= $this->mod_system_settings->get_num_records('all',$status_field_name,$tbl_name);
		$data['inactive_records']	= $this->mod_system_settings->get_num_records('inactive',$status_field_name,$tbl_name);
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("device_manufacturer/device_manufacturers_list",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Add Device Manufacturer  */
	function add_device_manufacturer()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_device_manufacturer');	
		
		
		$data1['page_content']	=	$this->load->view('device_manufacturer/add_device_manufacturer',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New Manufacturer Process*/
	function add_device_manufacturer_process()
	{
			/**** Adding Device Manufacturer ***/
			$manufacturer_name	=	trim($this->input->post('man_name'));
			$this->form_validation->set_rules('man_name', 'Device Manufacturer Name', 'required|alpha_dash_space_symbols|is_unique[djx_device_manufacturer.manufacturer_name]');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_device_manufacturer").'');
				$this->add_device_manufacturer();
			}
			else
			{
				$position	=	0;
				$status	= 1;	
				$insert_data	=	array("manufacturer_name" =>$manufacturer_name, "manufacturer_position" =>$position, "manufacturer_status" =>$status);	
				$tbl_name		=	'djx_device_manufacturer';
				
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_manufacturer_added_successfully").'');
				redirect('admin/settings_device_manufacturers');
			}
	}
	
		/* Edit Device Manufacturer  */
	function edit_device_manufacturer($manufacturer_id = false)
	{
		
		if($manufacturer_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 				= $this->lang->line('label_edit_device_manufacturer');	
			$data1['manufacturer_id']			=	$manufacturer_id;	
			$data1['manufacturer_data']		=	$this->mod_system_settings->get_device_manufacturer($manufacturer_id);
			
			
			$data1['page_content']	=	$this->load->view('device_manufacturer/edit_device_manufacturer',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_device_manufacturers');
		}
	}
	
	
	/*** Edit Device Manufacturer Process ******/
	function edit_device_manufacturer_process()
	{
		/**** Adding Device Manufacturer ***/
			$manufacturer_name	=	trim(mysql_real_escape_string($this->input->post('man_name')));
			$manufacturer_id		=	$this->input->post('man_id');
			$this->form_validation->set_rules('man_name', 'Device Manufacturer Name', 'required|alpha_dash_space_symbols|callback_manufacturer_dup_edit_check');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_device_manufacturer").'');
				$this->edit_device_manufacturer($manufacturer_id);
			}
			else
			{
				// Passing the updated data to model
				$update_data	=	array("manufacturer_name" =>$manufacturer_name);
				$where_arr		=	array("manufacturer_id"=>$manufacturer_id);
				$tbl_name		=	'djx_device_manufacturer';
				
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_manufacturer_updated_successfully").'');
				redirect('admin/settings_device_manufacturers');
			}
	}
	
	/****Delete  Device Manufacturer ********/
	public function delete_device_manufacturer($manufacturer_id=false)
	{
		  if($manufacturer_id != false)
            {
                    //$where_arr = array('manufacturer_id'=>$manurfacturer_id);
                    /* Delete Device Manufacturer */
					$id_field_name	=	'manufacturer_id'; // Please Provide the table id field name
					$tbl_name			=	'djx_device_manufacturer'; // Table Name is to be given
                    $this->mod_system_settings->delete_data($manufacturer_id,$id_field_name,$tbl_name);
					
                    /*  Deleted Successfully. Redirect to Device Manufacturer List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_device_manufacturer_deleted_successfully").'');
                    redirect('admin/settings_device_manufacturers');
            }
            else
            {
                    /* Device Manufacturer is not deleted properly! Error  */
					$id_field_name	=	'manufacturer_id'; // Please Provide the table id field name
					$tbl_name		=	'djx_device_manufacturer'; // Table Name is to be given
					$this->mod_system_settings->delete_data($this->input->post('sel_device_manufacturer'),$id_field_name,$tbl_name);
					
                      /* Deleted Successfully. Redirect to Device Manufacturer List */
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_manufacturer_select_delete_successfully").'');
					redirect('admin/settings_device_manufacturers');
            }
	}
	
	
	/* Duplicate Check For Device Manufacturer*/
	public function manufacturer_name_check(){
		$query		=$this->db->where(array("manufacturer_name" =>trim($this->input->post('manufacturer_name'))))->get('djx_device_manufacturer')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}

	/* duplicate Check For Editing Process*/
	public function manufacturer_dup_edit_check($str)
	{
		$query	=	$this->db->select('count(*) as count')->get_where('djx_device_manufacturer',array('manufacturer_name'=> $str,'manufacturer_id !='=>$this->input->post('man_id')))->result();

		if($query[0]->count >=1)
		{
			$this->form_validation->set_message('manufacturer_dup_edit_check',''.$this->lang->line("notification_device_manufacturer_exist").'');
			return FALSE;
		}else{
			return TRUE;
		}	
	}
	
}

/* End of file device_manufacturers.php */
/* Location: ./modules/system_settings/controllers/device_manufacturers.php */
