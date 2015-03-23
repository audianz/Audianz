<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_campaign_categories extends CI_Controller {   
	 
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
		$this->campaign_category_listing();
	}
	
	/**** Active List *****/
	 public function active($start=0)
	{
		$this->status = "active";
		$this->campaign_category_listing($start);
    }
	
	/**** InActive List *****/
	 public function inactive($start=0)
	{
		$this->status = "inactive";
		$this->campaign_category_listing($start);
    }
	
	/* System Settings Campaign Categories Landing Page */
	public function campaign_category_listing($start = 0) 
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
		$list_data = $this->mod_system_settings->get_campaign_categories($this->status);
		
		$config['per_page'] = $limit;
		//$config['base_url'] = site_url("system_settings/device_manufacturers/device_list/");
		 if($this->status=="active"){
       		$config['base_url'] = site_url("admin/settings_campaign_categories/active/");
        }
        else if($this->status == "inactive") /* Active Base Url */
        {
             $config['base_url'] = site_url("admin/settings_campaign_categories/inactive/");
         }
		 else{ /* InActive Base Url */
		 	$config['base_url'] = site_url("admin/settings_campaign_categories/campaign_category_listing/");
		 }
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_system_settings->get_campaign_categories($this->status,$limit,$start);
		$data['campaign_categories_list']	=	$list_data;
		

		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_campaign_categories_title');	
		
		
		/** Get the Number of Records ****/
		
		$status_field_name			=	'status'; // Provide The name of Status field
		$tbl_name						=	'djx_campaign_categories'; // Provide the table name
		
		$data['active_records']		= $this->mod_system_settings->get_num_records('active',$status_field_name,$tbl_name);
		$data['all_records']			= $this->mod_system_settings->get_num_records('all',$status_field_name,$tbl_name);
		$data['inactive_records']	= $this->mod_system_settings->get_num_records('inactive',$status_field_name,$tbl_name);
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("campaign_categories/campaign_categories_list",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Add Campaign Category  */
	function add_campaign_category()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_campaign_category');	
		
		
		$data1['page_content']	=	$this->load->view('campaign_categories/add_campaign_category',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New Campaign Category Process*/
	function add_campaign_category_process()
	{
			/**** Adding Campaign Category  ***/
			$campaign_category	=	trim(mysql_real_escape_string($this->input->post('campaign_category')));
			//$category_value	=	trim(mysql_real_escape_string($this->input->post('category_value')));
			$this->form_validation->set_rules('campaign_category', 'Category Name', 'required|alpha_numeric|is_unique[djx_campaign_categories.category_name]');
			//$this->form_validation->set_rules('category_value', 'Category Value', 'required|is_unique[djx_campaign_categories.category_value]');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_campaign_category").'');
				$this->add_campaign_category();
			}
			else
			{
				$position	=	0;
				$status	= 1;	
				$insert_data	=	array("category_name" =>$campaign_category, 
										//"category_value" =>$category_value, 
										"status" =>$status, 
										"added_date"=>date("Y-m-d h:i:s"));	
				$tbl_name		=	'djx_campaign_categories';
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_added_successfully").'');
				redirect('admin/settings_campaign_categories');
			}
	}
	
		/* Edit Campaign Category  */
	function edit_campaign_category($category_id = false)
	{
		
		if($category_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 				= $this->lang->line('label_edit_campaign_category');	
			$data1['category_id']				=	$category_id;	
			$data1['campaign_categories_data']		=	$this->mod_system_settings->get_campaign_category($category_id);
			
			
			$data1['page_content']	=	$this->load->view('campaign_categories/edit_campaign_category',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_campaign_categories');
		}
	}
	
	
	/*** Edit Campaign Categories Process ******/
	function edit_campaign_category_process()
	{
		/**** Editing Campaign Category ***/
			$category_name	=	trim(mysql_real_escape_string($this->input->post('campaign_category')));
			//$category_value	=	trim(mysql_real_escape_string($this->input->post('category_value')));
			$this->form_validation->set_rules('campaign_category', 'Category Name', 'required|alpha_numeric|callback_category_dup_edit_check');
			//$this->form_validation->set_rules('category_value', 'Category Value', 'required');

			$category_id		=	$this->input->post('cat_id');
			
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_campaign_categories").'');
				$this->edit_campaign_category($category_id);
			}
			else
			{
				// Passing the updated data to model
				$update_data	=	array("category_name" =>$category_name, 
									//"category_value" =>$category_value, 
									"updated_date"=>date("Y-m-d h:i:s"));
				$where_arr		=	array("category_id"=>$category_id);
				$tbl_name		=	'djx_campaign_categories';
				
				$this->mod_system_settings->update_data($update_data, $where_arr,$tbl_name);
				
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_updated_successfully").'');
				redirect('admin/settings_campaign_categories');
			}
	}
	
	/****Delete  Campaign Category ********/
	public function delete_campaign_categories($category_id=false)
	{
		  if($category_id != false)
            {
                    //$where_arr = array('manufacturer_id'=>$manurfacturer_id);
                    /* Delete Campaign Category */
					$id_field_name	=	'category_id'; // Please Provide the table id field name
					$tbl_name			=	'djx_campaign_categories'; // Table Name is to be given
                    $this->mod_system_settings->delete_data($category_id,$id_field_name,$tbl_name);
					
                    /*  Deleted Successfully. Redirect to Device Manufacturer List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_deleted_successfully").'');
                    redirect('admin/settings_campaign_categories');
            }
            else
            {
                    
					/* Campaign Category is not deleted properly! Error  */
					$id_field_name	=	'category_id'; // Please Provide the table id field name
					$tbl_name		=	'djx_campaign_categories'; // Table Name is to be given
					$this->mod_system_settings->delete_data($this->input->post('sel_campaign_categories'),$id_field_name,$tbl_name);
					
                      /* Deleted Successfully. Redirect to Device Manufacturer List */
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_select_delete_successfully").'');
					redirect('admin/settings_campaign_categories');
            }
	}
	
	
	/* Duplicate Check For Campaign Category*/
	public function category_name_check(){
		$query		=$this->db->where(array("category_name" =>trim($this->input->post('category_name'))))->get('djx_campaign_categories')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}
	
	/***change Status of Geo Location */
	public function change_status($category_id=false)
	{
		if($category_id)
		{
			$status	= '!status';
			$where_arr			=	array('category_id'=>$category_id);
			$query	=	$this->db->query("UPDATE djx_campaign_categories SET status = !status WHERE category_id='".$category_id."'");
			
			if($query ==TRUE)
			{
				$this->db->select('status');
				$status	=	$this->db->get_where('djx_campaign_categories',$where_arr)->row();
				if($status->status ==1)
				{
					$this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_activated_successfully").'');
					redirect('admin/settings_campaign_categories');	
				}
				else{
					$this->session->set_flashdata('message', ''.$this->lang->line("notification_campaign_category_inactivated_successfully").'');
					redirect('admin/settings_campaign_categories');
				}	
			}
			
		}
	}
	
	/* duplicate Check For Editing Process*/
	public function category_dup_edit_check($str)
	{
		$query	=	$this->db->select('count(*) as count')->get_where('djx_campaign_categories',array('category_name'=> $str,'category_id !='=>$this->input->post('cat_id')))->result();

		if($query[0]->count >=1)
		{
			$this->form_validation->set_message('category_dup_edit_check',''.$this->lang->line("notification_category_exist").'');
			return FALSE;
		}else{
			return TRUE;
		}	
	}

}

/* End of file campaign_categories.php */
/* Location: ./modules/system_settings/controllers/device_manufacturers.php */
