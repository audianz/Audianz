<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_client_profile extends CI_Controller {   
	 
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
		$list_data = $this->mod_system_settings->get_client_profiles();
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("admin/settings_client_profile/device_list/");
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_system_settings->get_client_profiles($limit,$start);
		$data['client_profile_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_client_profile_title');	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("client_profile/list_client_profile",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/client_profile')	;
	}
	
	
	/* Add client_profile  */
	function add_client_profile()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_client_profile');	
		$data1['page_content']	=	$this->load->view('client_profile/add_client_profile',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New client_profile Process*/
	function add_client_profile_process()
	{
			/**** Adding Device Manufacturer ***/
			$client_profile_start	=	trim($this->input->post('client_profile_start'));
			$client_profile_to				=	trim($this->input->post('client_profile_to'));
			//$client_profile_id		=	$this->input->post('client_id');
			$this->form_validation->set_rules('client_profile_start', 'starting Age', 'required|callback_site_check');
			$this->form_validation->set_rules('client_profile_to', 'Ending Age', 'required|callback_site_check');
			
			
			if($this->form_validation->run() == FALSE)
			{
			    
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_client_profile").'');
				$this->add_client_profile();
			}
			else
			{
				
					
				$insert_data	=	array("from"  =>$client_profile_start,
										  "to"    =>$client_profile_to 
										  );
				$tbl_name		=	'djx_client_profile';	
										  
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_client_profile_added_successfully").'');
				redirect('admin/settings_client_profile');
			}
	}
	
		/* Edit Device client_profile */
	function edit_client_profile($client_profile_id = false)
	{
		
		if($client_profile_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 				= $this->lang->line('label_edit_client_profile');	
			$data1['client_profile_id']			=	$client_profile_id;	
			$data1['client_profile_data']		=	$this->mod_system_settings->get_client_profile($client_profile_id);
			$data1['page_content']	=	$this->load->view('client_profile/edit_client_profile',$data1,true);
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_client_profile');
		}
	}
	
	
	/*** Edit Device client_profile Process ******/
	function edit_client_profile_process()
	{
		
			$client_profile_start	=	trim($this->input->post('client_profile_start'));
			$client_profile_to				=	trim($this->input->post('client_profile_to'));
			$client_profile_id		=	$this->input->post('client_id');
			$this->form_validation->set_rules('client_profile_start', 'starting Age', 'required|callback_edit_site_check');
			$this->form_validation->set_rules('client_profile_to', 'Ending Age', 'required|callback_edit_site_check');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_client_profile").'');
				$this->edit_client_profile($client_profile_id);
			}
			else
			{
				$update_data	=	array("from" =>$client_profile_start,
				                          		"to"   =>$client_profile_to
				                         		);	
				$where_arr		=	array('id' =>$client_profile_id);
				$tbl_name		=	'djx_client_profile';
									 
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_client_profile_updated_successfully").'');
				redirect('admin/settings_client_profile');
			}
	}
	
	/****Delete  Device client_profile ********/
	public function delete_client_profile($client_profile_id=false)
	{
		  if($client_profile_id != false)
            {
                    //$where_arr = array('client_profilerer_id'=>$client_profile_id);
                    /* Delete Device client_profile */
					
					$id_field_name	=	'id';
					$tbl_name			=	'djx_client_profile';
					
                    $this->mod_system_settings->delete_data($client_profile_id,$id_field_name,$tbl_name);
                    /* Device client_profile Deleted Successfully. Redirect to Device client_profiler List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_client_profile_deleted_successfully").'');
                    redirect('admin/settings_client_profile');
            }
            else
            {
                   /* Device client_profile is not deleted properly! Error  */
					$id_field_name	=	'id';
					$tbl_name			=	'djx_client_profile';
					                    
					$this->mod_system_settings->delete_data($this->input->post('sel_client_profile'),$id_field_name,$tbl_name);
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_client_profile_select_delete_successfully").'');
					redirect('admin/settings_client_profile');
            }
	}
	
	
	/* Duplicate Check For Deviceclient_profile*/
	public function client_profile_name_check(){
		$query		=$this->db->where(array("client_profile_name" =>trim($this->input->post('client_profile_name'))))->get('djx_client_profiler')->num_rows();
		if($query==0){
				echo "no";exit;						
		}
		else
		{
				echo "yes";exit;
		}
	}
        public function site_check()
	 	{
			
			
                       
                        $this->db->select('*');		
			$this->db->where('from',$this->input->post('client_profile_start'));
			
			$this->db->where('to',$this->input->post('client_profile_to'));
			
			//$this->db->where('id',$this->input->post('client_id'));
			
			$query=$this->db->get('djx_client_profile')->num_rows();
			
                        
			if($query > 0 )
				{
					$this->form_validation->set_message('site_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
					return true;
		}	
			
	public function edit_site_check()
	 	{
			
			
                       
                        $this->db->select('*');		
			$this->db->where('from',$this->input->post('client_profile_start'));
			
			$this->db->where('to',$this->input->post('client_profile_to'));
			
			$this->db->where('id !=',$this->input->post('client_id'));
			
			$query=$this->db->get('djx_client_profile')->num_rows();
			
                        
			if($query == 1 )
				{
					$this->form_validation->set_message('edit_site_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
					return true;
		}	
}

/* End of file advertisers.php */
/* Location: ./modules/system_settings/controllers/client_profile.php */
