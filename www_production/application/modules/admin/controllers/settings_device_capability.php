<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_device_capability extends CI_Controller {   
	 
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
		$this->device_list();
	}
	
	/**** Active List *****/
	 public function active($start=0)
	{
		$this->status = "active";
		$this->device_list($start);
    }
	
	/**** InActive List *****/
	 public function inactive($start=0)
	{
		$this->status = "inactive";
		$this->device_list($start);
    }
	
	/* System Settings Landing Page */
	public function device_list($start = 0) 
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
		$list_data = $this->mod_system_settings->get_device_capability($this->status);
		
		$config['per_page'] = $limit;
		
		 if($this->status=="active"){
       		$config['base_url'] = site_url("admin/settings_device_capability/active/");
        }
        else if($this->status == "inactive") /* Active Base Url */
        {
             $config['base_url'] = site_url("admin/settings_device_capability/inactive/");
         }
		 else{ /* InActive Base Url */
		 	$config['base_url'] = site_url("admin/settings_device_capability/device_list/");
		 }
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_system_settings->get_device_capability($this->status,$limit,$start);
		$data['device_capability_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_device_capability_title');	
		
		
		/** Get the Number of Records ****/
		$status_field_name			=	'capability_status'; // Provide The name of Status field
		$tbl_name						=	'djx_device_capability'; // Provide the table name
		
		$data['active_records']		= $this->mod_system_settings->get_num_records('active',$status_field_name,$tbl_name);
		$data['all_records']			= $this->mod_system_settings->get_num_records('all',$status_field_name,$tbl_name);
		$data['inactive_records']	= $this->mod_system_settings->get_num_records('inactive',$status_field_name,$tbl_name);
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("device_capability/list_device_capability",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_capability')	;
	}
	
	
	/* Add Device Manufacturer  */
	function add_device_capability()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_device_capability');	
		
		
		$data1['page_content']	=	$this->load->view('device_capability/add_device_capability',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New capability Process*/
	function add_device_capability_process()
	{
			/**** Adding Device Manufacturer ***/
			$capability_name	=	mysql_escape_string($this->input->post('capability_name'));
			$capability_value	=	mysql_escape_string($this->input->post('capability_value'));
			$this->form_validation->set_rules('capability_name', 'Device capability Name', 'required|alpha_dash_space_symbols|callback_capability_name_check');
			$this->form_validation->set_rules('capability_value', 'Device Capability value', 'required|alpha_dash_space_symbols');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_device_capability").'');
				$this->add_device_capability();
			}
			else
			{
				
				$status	= 1;	
				$insert_data	=	array("capability_name" =>$capability_name,
										  "capability_value" =>$capability_value, 
										  "capability_status" =>$status);	
				$tbl_name		=	'djx_device_capability';
				
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_capability_added_successfully").'');
				redirect('admin/settings_device_capability');
			}
	}
	
		/* Edit Device capability */
	function edit_device_capability($capability_id = false)
	{
		
		if($capability_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 				= $this->lang->line('label_edit_device_capability');	
			$data1['capability_id']			=	$capability_id;	
			$data1['capability_data']		=	$this->mod_system_settings->get_device_capabilitys($capability_id);
				
			$data1['page_content']	=	$this->load->view('device_capability/edit_device_capability',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_device_capability');
		}
	}
	
	
	/*** Edit Device Capability Process ******/
	function edit_device_capability_process()
	{
		/**** Adding Device Manufacturer ***/
			$capability_name	=	mysql_escape_string($this->input->post('capability_name'));
			$capability_value	=	mysql_escape_string($this->input->post('capability_value'));
			$capability_id		=	$this->input->post('cap_id');
			$this->form_validation->set_rules('capability_name', 'Device capability Name', 'required|alpha_numeric_dash_space|callback_capability_name_check');
			$this->form_validation->set_rules('capability_value', 'Device capability value', 'required|alpha_numeric_dash_space');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_device_capability").'');
				$this->edit_device_capability($capability_id);
			}
			else
			{
				$update_data	=	array("capability_name" =>$capability_name,
				                          "capability_value" =>$capability_value
				                          );	
				$where_arr		=	array('capability_id' =>$capability_id);
				$tbl_name		=	'djx_device_capability';
				
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_capability_updated_successfully").'');
				redirect('admin/settings_device_capability');
			}
	}
	
	/****Delete  Device capability ********/
	public function delete_device_capability($capability_id=false)
	{
		  if($capability_id != false)
            {
                    //$where_arr = array('capabilityrer_id'=>$capability_id);
                    /* Delete Device capability */
					$id_field_name	=	'capability_id';
					$tbl_name			=	'djx_device_capability';
					
                    $this->mod_system_settings->delete_data($capability_id,$id_field_name,$tbl_name);
                    /* Device capability Deleted Successfully. Redirect to Device capabilityr List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_device_capability_deleted_successfully").'');
                    redirect('admin/settings_device_capability');
            }
            else
            {
                    /* Device capability is not deleted properly! Error  */
					$id_field_name	=	'capability_id';
					$tbl_name			=	'djx_device_capability';
					
					$this->mod_system_settings->delete_data($this->input->post('sel_device_capability'),$id_field_name,$tbl_name);
                  
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_capability_select_delete_successfully").'');
					redirect('admin/settings_device_capability');
            }
	}
	
	
	/* Duplicate Check For Devicecapability*/
	public function capability_name_check(){
                        
                          
                        $this->db->select('*');		
			$this->db->where('capability_name',trim($this->input->post('capability_name')));
                        $this->db->where('capability_value',trim($this->input->post('capability_value')));
			$this->db->where('capability_id !=',$this->input->post('cap_id'));
			
			$query=$this->db->get('djx_device_capability')->num_rows();
			
                        
			if($query == 1 )
				{
					$this->form_validation->set_message('capability_name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
					return true;
		
	                     }
	
}

/* End of file advertisers.php */
/* Location: ./modules/system_settings/controllers/device_capability.php */
