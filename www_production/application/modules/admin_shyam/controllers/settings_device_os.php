<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_device_os extends CI_Controller {   
	 
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
		$this->os_listing();
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
	
	/* Device Os List*/
	public function os_listing($start = 0) 
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
		$list_data = $this->mod_system_settings->get_device_os_list($this->status);
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("admin/settings_device_os/os_listing/");
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_system_settings->get_device_os_list($this->status,$limit,$start);
		$data['device_os_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_device_os');	
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("device_os/device_os_list",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Add Device Os  */
	function add_device_os()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_device_os');	
		
		
		$data1['page_content']	=	$this->load->view('device_os/add_device_os',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New Device OS  Process*/
	function add_device_os_process()
	{
			/**** Adding Device OS ***/
			$device_os				=	trim(mysql_real_escape_string($this->input->post('device_os')));
			$device_os_value	=	trim(mysql_real_escape_string($this->input->post('device_os_value')));
			
			/*********Form Validation **********/
			$this->form_validation->set_rules('device_os', 'Device Os', 'required|alpha_dash_space_symbols|is_unique[djx_device_os.os_platform]');
			$this->form_validation->set_rules('device_os_value', 'Device Os Value', 'required|alpha_dash_space_symbols');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_device_os").'');
				$this->add_device_os();
			}
			else
			{
				$os_status	= 1;	
				$insert_data	=	array("os_platform" =>$device_os, "os_value" =>$device_os_value, "os_status" =>$os_status);	
				$tbl_name		=	'djx_device_os';
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_os_added_successfully").'');
				redirect('admin/settings_device_os');
			}
	}
	
		/* Edit Device OS  */
	function edit_device_os($os_id = false)
	{
		
		if($os_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 	= $this->lang->line('label_edit_device_os');	
			$data1['os_id']			=	$os_id;	
			$data1['os_data']		=	$this->mod_system_settings->get_device_os($os_id);
			
			
			$data1['page_content']	=	$this->load->view('device_os/edit_device_os',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('system_settings/device_manufacturers');
		}
	}
	
	
	/*** Edit Device OS Process ******/
	function edit_device_os_process()
	{
		/**** editing Device OS ***/
			$os_platform	=	trim(mysql_real_escape_string($this->input->post('device_os')));
			$os_value		=	trim(mysql_real_escape_string($this->input->post('device_os_value')));
			$os_id		=	$this->input->post('os_id');
			
			//Form validation Rules
			$this->form_validation->set_rules('device_os', 'Device OS Platform', 'required|alpha_numeric_dash_space|callback_os_dup_edit_check');
			$this->form_validation->set_rules('device_os_value', 'Device OS Value', 'required|alpha_numeric_dash_space');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_device_os").'');
				$this->edit_device_os($os_id);
			}
			else
			{
				// Passing the updated data to model
				$update_data	=	array("os_platform" =>$os_platform, "os_value" => $os_value);
				$where_arr		=	array("os_id"=>$os_id);
				$tbl_name		=	'djx_device_os';
				
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_os_updated_successfully").'');
				redirect('admin/settings_device_os');
			}
	}
	
	/****Delete  Device OS ********/
	public function delete_device_os($os_id=false)
	{
		  if($os_id != false)
            {
                    /* Delete Device OS */
					$id_field_name	=	'os_id'; // Please Provide the table id field name
					$tbl_name			=	'djx_device_os'; // Table Name is to be given
                    $this->mod_system_settings->delete_data($os_id,$id_field_name,$tbl_name);
                    
                    /* Device Manufacturer Deleted Successfully. Redirect to Device Manufacturer List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_device_os_deleted_successfully").'');
                    redirect('admin/settings_device_os');
            }
            else
            {
                    /* Delete Selected Device OS */
					$id_field_name	=	'os_id'; // Please Provide the table id field name
					$tbl_name			=	'djx_device_os'; // Table Name is to be given

                    $this->mod_system_settings->delete_data($this->input->post('sel_device_os'),$id_field_name,$tbl_name);
                  //  $this->session->set_flashdata('delete_device_manufacturer', '<div class="notification msgalert"><a class="close"></a><p>Device Manufacturer Has Not Deleted Properly</p></div>');
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_device_os_select_delete_successfully").'');
					redirect('admin/settings_device_os');
            }
	}
	
	
	/* Duplicate Check For DeviceOS*/
	public function device_os_check(){
		$query		=$this->db->where(array("os_platform" =>trim($this->input->post('device_os'))))->get('djx_device_os')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}
	
	/* duplicate Check For Editing Process*/
	public function os_dup_edit_check($str)
	{
		$query	=	$this->db->select('count(*) as count')->get_where('djx_device_os',array('os_platform'=> $str,'os_id !='=>$this->input->post('os_id')))->result();

		if($query[0]->count >=1)
		{
			$this->form_validation->set_message('os_dup_edit_check',''.$this->lang->line("notification_device_os_exist").'');
			return FALSE;
		}else{
			return TRUE;
		}	
	}
}

/* End of file device_os.php */
/* Location: ./modules/system_settings/controllers/device_os.php */
