<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_geo_locations extends CI_Controller {   
	 
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
		$this->geo_location_listing();
	}
	
	/**** Active List *****/
	 public function active($start=0)
	{
		$this->status = "active";
		$this->geo_location_listing($start);
    }
	
	/**** InActive List *****/
	 public function inactive($start=0)
	{
		$this->status = "inactive";
		$this->geo_location_listing($start);
    }
	
	/* Geo Locations List*/
	public function geo_location_listing($start = 0) 
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
		$list_data = $this->mod_system_settings->get_geo_locations_list($this->status);
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("admin/settings_geo_locations/geo_location_listing/");
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		//$list_data = $this->mod_system_settings->get_geo_locations_list($this->status,$limit,$start);
		$data['geo_locations_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_geo_locations');	
		
		
		/** Get the Number of Records ****/
		
		$status_field_name				=	'status'; // Provide The name of Status field
		$tbl_name						=	'djx_geographic_locations'; // Provide the table name
		
		$data['active_records']		= $this->mod_system_settings->get_num_records('active',$status_field_name,$tbl_name);
		$data['all_records']			= $this->mod_system_settings->get_num_records('all',$status_field_name,$tbl_name);
		$data['inactive_records']	= $this->mod_system_settings->get_num_records('inactive',$status_field_name,$tbl_name);
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("geo_locations/geo_locations_list",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Add Geo Locations  */
	function add_geo_location()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 		= $this->lang->line('label_add_geo_location');	
		
		$data1['continent_list']  	=	$this->db->get('djx_geographic_continents')->result();
		
		
		$data1['page_content']	=	$this->load->view('geo_locations/add_geo_location',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New Geo Location  Process*/
	function add_geo_location_process()
	{
			/**** Adding Geo Location ***/
			$location_name		=	trim(mysql_real_escape_string($this->input->post('location_name')));
			$country_code		=	trim(mysql_real_escape_string(strtoupper($this->input->post('country_code'))));
			
			if($this->input->post('continent') != ''):
			list($continent_code,$continent_name)		=	explode('||',trim(mysql_real_escape_string($this->input->post('continent'))));
			endif;
			
			/*********Form Validation **********/
			$this->form_validation->set_rules('location_name', 'Location Name', 'required|alpha_numeric|callback_location_dup_check');
			$this->form_validation->set_rules('country_code', 'Country Code', 'required|alpha|is_unique[djx_geographic_locations.code]');
			$this->form_validation->set_rules('continent', 'Continent', 'required');

			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_geo_locations").'');
				$this->add_geo_location();
			}
			else
			{
				$status	= 1;	
				$insert_data	=	array("name" =>$location_name, "code" =>$country_code, "continent" =>$continent_name,"continent_code" => $continent_code, "status" =>$status);	
				$tbl_name		=	'djx_geographic_locations';
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_added_successfully").'');
				redirect('admin/settings_geo_locations');
			}
	}
	
		/* Edit Geo Location  */
	function edit_geo_location($loc_code = '')
	{
		
		if($loc_code !='')
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 	= $this->lang->line('label_edit_geo_location');	
			$data1['code']			=	strtoupper($loc_code);
			$data1['continent_list']  	=	$this->db->get('djx_geographic_continents')->result();	
			$data1['loc_data']		=	$this->mod_system_settings->get_geo_location(strtoupper($loc_code));
			
			
			$data1['page_content']	=	$this->load->view('geo_locations/edit_geo_location',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('system_settings/device_manufacturers');
		}
	}
	
	
	/*** Edit Geo Location Process ******/
	function edit_geo_location_process()
	{
		/**** editing Device OS ***/
			$location_name		=	trim(mysql_real_escape_string($this->input->post('location_name')));
			
			if($this->input->post('continent') != ''):
			list($continent_code,$continent_name)  =	explode('||',trim(mysql_real_escape_string($this->input->post('continent'))));
			endif;
			
			$code				=	$this->input->post('code');
			
			//Form validation Rules
			$this->form_validation->set_rules('location_name', 'Location Name', 'required|alpha_numeric|callback_location_dup_check');
			$this->form_validation->set_rules('continent', 'Continent', 'required');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_geo_locations").'');
				$this->edit_geo_location($code);
			}
			else
			{
				// Passing the updated data to model
				$update_data	=	array("name" =>$location_name, "continent" => $continent_name, "continent_code"=>$continent_code);
				$where_arr		=	array("code"=>$code);
				$tbl_name		=	'djx_geographic_locations';
				
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_updated_successfully").'');
				redirect('admin/settings_geo_locations');
			}
	}
	
	/****Delete  Geo Location ********/
	public function delete_geo_location($loc_code='')
	{
		  if($loc_code != '')
            {
                    /* Delete Geo Location */
					$loc_code = strtoupper($loc_code);
					$id_field_name	=	'code'; // Please Provide the table id field name
					$tbl_name			=	'djx_geographic_locations'; // Table Name is to be given
                    $this->mod_system_settings->delete_data($loc_code,$id_field_name,$tbl_name);
                    
                    /* Device Manufacturer Deleted Successfully. Redirect to Device Manufacturer List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_deleted_successfully").'');
                    redirect('admin/settings_geo_locations');
            }
            else
            {
                    /* Delete Selected Device OS */
					$id_field_name	=	'code'; // Please Provide the table id field name
					$tbl_name			=	'djx_geographic_locations'; // Table Name is to be given
                    $this->mod_system_settings->delete_data($this->input->post('sel_geo_locations'),$id_field_name,$tbl_name);
                  //  $this->session->set_flashdata('delete_device_manufacturer', '<div class="notification msgalert"><a class="close"></a><p>Device Manufacturer Has Not Deleted Properly</p></div>');
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_select_delete_successfully").'');
					redirect('admin/settings_geo_locations');
            }
	}
	
	
	/* Duplicate Check For Geo Locations*/
	public function geo_location_check(){
		$query		=$this->db->where(array("name" =>trim($this->input->post('geo_location'))))->get('djx_geographic_locations')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}
	
	/* Duplicate Check For Code*/
	public function code_check(){
		$query		=$this->db->where(array("code" =>trim(strtoupper($this->input->post('code')))))->get('djx_geographic_locations')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}
	
	/***change Status of Geo Location */
	public function change_status($loc_code='')
	{
		if($loc_code!='')
		{
			$status	= '!status';
			$where_arr			=	array('code'=>strtoupper($loc_code));
			$query	=	$this->db->query("UPDATE djx_geographic_locations SET status = !status WHERE code='".strtoupper($loc_code)."'");
			
			if($query ==TRUE)
			{
				$this->db->select('status');
				$status	=	$this->db->get_where('djx_geographic_locations',$where_arr)->row();
				if($status->status ==1)
				{
					$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_activated_successfully").'');
					redirect('admin/settings_geo_locations');	
				}
				else{
					$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_location_inactivated_successfully").'');
					redirect('admin/settings_geo_locations');
				}	
			}
			
		}
	}
	
	/* duplicate Check For Editing Process*/
	public function location_dup_check($str)
	{
		list($continent_code,$continent_name)  =	explode('||',trim(mysql_real_escape_string($this->input->post('continent'))));		
		$query	=	$this->db->select('count(*) as count')->get_where('djx_geographic_locations',array('name'=> $str,'continent'=>$continent_name,'code !='=>strtoupper($this->input->post('country_code'))))->result();

		if($query[0]->count >=1)
		{
			$this->form_validation->set_message('location_dup_check',''.$this->lang->line("notification_geo_location_exist").'');
			return FALSE;
		}else{
			return TRUE;
		}	
	}
	
}

/* End of file device_os.php */
/* Location: ./modules/system_settings/controllers/device_os.php */
