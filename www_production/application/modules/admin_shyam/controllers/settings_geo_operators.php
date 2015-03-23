<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_geo_operators extends CI_Controller {   
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	/* Checks the Status of the Listing Records */
	var $status     = "all";
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Libraries */
		 $this->load->helper('download');
		$this->load->library('csvreader');
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
		$list_data = $this->mod_system_settings->get_geo_operators($this->status);
		
		$config['per_page'] = $limit;
		
		if($this->status=="active"){
       		$config['base_url'] = site_url("admin/settings_geo_operators/active/");
        }
        else if($this->status == "inactive") /* Active Base Url */
        {
             $config['base_url'] = site_url("admin/settings_geo_operators/inactive/");
         }
		 else{ /* InActive Base Url */
			$config['base_url'] = site_url("admin/settings_geo_operators/device_list/");
		 }
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		//$list_data = $this->mod_system_settings->get_geo_operators($this->status,$limit,$start);
		$data['geo_operators_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_geo_operators_title');	
		
		
		/** Get the Number of Records ****/
		$status_field_name			='telecom_status'; // Provide The name of Status field
		$tbl_name					='djx_telecom_circle'; // Provide the table name
		
		$data['active_records']		= $this->mod_system_settings->get_num_records('active',$status_field_name,$tbl_name);
		$data['all_records']		= $this->mod_system_settings->get_num_records('all',$status_field_name,$tbl_name);
		$data['inactive_records']	= $this->mod_system_settings->get_num_records('inactive',$status_field_name,$tbl_name);
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("geo_operators/list_geo_operators",$data,true);
		$this->load->view('page_layout',$data);
		//redirect('settings/system/geo_operators')	;
	}
	
	
	/* Add geo_operators  */
	function add_geo_operators()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data1['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data1['page_title'] 	= $this->lang->line('label_add_geo_operators');	
		$data1['country']		=	$this->mod_system_settings->get_country();	
		$data1['page_content']	=	$this->load->view('geo_operators/add_geo_operators',$data1,true);
		$this->load->view('page_layout',$data1);
	}
	
	/* Adding New geo_operators Process*/
	function add_geo_operators_process()
	{
			/**** Adding Device Manufacturer ***/
			$geo_operators_name	= trim($this->input->post('geo_operators_name'));
			$geo_operators_country	= trim($this->input->post('country'));
			
			$this->form_validation->set_rules('geo_operators_name', 'geo_operators Name', 'required|callback_operator_name_check');
			$this->form_validation->set_rules('country', 'geo_operators country', 'required');
			
			if($this->form_validation->run() == FALSE)
			{
			     
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_geo_operators").'');
				$this->add_geo_operators();
			}
			else
			{
				
				$status	= 1;	
				$insert_data	=	array("telecom_name"  =>$geo_operators_name,
										  "telecom_countrycode" =>$geo_operators_country, 
										  "telecom_status" =>$status);	
				$tbl_name		=	'djx_telecom_circle';
				
				$this->mod_system_settings->insert_data($insert_data,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_operators_added_successfully").'');
				redirect('admin/settings_geo_operators');
				redirect('admin/settings_geo_operators');
			}
	}
	
		/* Edit Device geo_operators */
	function edit_geo_operators($geo_operators_id = false)
	{
		
		if($geo_operators_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data1['page_title'] 				= $this->lang->line('label_edit_geo_operators');	
			$data1['geo_operators_id']			=	$geo_operators_id;	
			$data1['geo_operators_data']		=	$this->mod_system_settings->get_geo_operatorss($geo_operators_id);
			//$data1['country']		=	$this->mod_system_settings->get_country();	
			$data1['page_content']	=	$this->load->view('geo_operators/edit_geo_operators',$data1,true);
	
			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_geo_operators');
		}
	}
	
	
	/*** Edit Device geo_operators Process ******/
	function edit_geo_operators_process()
	{
		
			$geo_operators_name	=	trim($this->input->post('geo_operators_name'));
			$geo_operators_value	=	trim($this->input->post('geo_operators_value'));
			$geo_operators_id		=$this->input->post('operators_id');
			$this->form_validation->set_rules('geo_operators_name', 'Geo operators Name','required|callback_edit_operator_name_check');
			$this->form_validation->set_rules('geo_operators_value', 'Geo operator value', 'required');
			
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_geo_operators").'');
				$this->edit_geo_operators($geo_operators_id);
			}
			else
			{
				$update_data	=	array("telecom_name" =>$geo_operators_name,
				                          "telecom_value" =>$geo_operators_value
				                          );
				$where_arr		=	array('telecom_id' =>$geo_operators_id);
				$tbl_name		=	'djx_telecom_circle';
				
				$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
				$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_operators_updated_successfully").'');
				redirect('admin/settings_geo_operators');
			}
	}
	
	/****Delete  Device geo_operators ********/
	public function delete_geo_operators($geo_operators_id=false)
	{
		
		  if($geo_operators_id != false)
            {
                    //$where_arr = array('geo_operatorsrer_id'=>$geo_operators_id);
                    /* Delete Device geo_operators */
					$id_field_name	=	'telecom_id';
					$tbl_name	=	'djx_telecom_circle';
					
                    $this->mod_system_settings->delete_data($geo_operators_id,$id_field_name,$tbl_name);
                    /* Device geo_operators Deleted Successfully. Redirect to Device geo_operatorsr List */
                    $this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_operators_deleted_successfully").'');
                    redirect('admin/settings_geo_operators');
            }
            else
            {
                    /* Device geo_operators is not deleted properly! Error  */
					$id_field_name	=	'telecom_id';
					$tbl_name			=	'djx_telecom_circle';
					
					$this->mod_system_settings->delete_data($this->input->post('sel_geo_operators'),$id_field_name,$tbl_name);
                  
                 	$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_operators_select_delete_successfully").'');
					redirect('admin/settings_geo_operators');
            }
	}
	
	
	/* Duplicate Check For Devicegeo_operators*/
	public function operator_name_check(){

            $this->db->select('*');		
			$this->db->where('telecom_name',$this->input->post('geo_operators_name'));
			$this->db->where('telecom_value',$this->input->post('geo_operators_value'));
			
			$query=$this->db->get('djx_telecom_circle')->num_rows();
			
                        
			if($query >0)
				{
					$this->form_validation->set_message('operator_name_check', $this->lang->line('lang_operator_already_exists_in_country'));
					return FALSE;	
				}
			else
					return true;
		
	       }
		   
      public function edit_operator_name_check()
	   {

                    
            $this->db->select('*');		
			$this->db->where('telecom_name',trim($this->input->post('geo_operators_name')));
			$this->db->where('telecom_value',trim($this->input->post('geo_operators_value')));
			$query=$this->db->get('djx_telecom_circle')->num_rows();
			
                        
			if($query >= 1 )
				{
					$this->form_validation->set_message('edit_operator_name_check', $this->lang->line('lang_operator_already_exists_in_country'));
					return FALSE;	
				}
			else
					return true;
		
	    }
		
		public function geo_operators_import_file()
		{	
			
			if($_FILES['geo_operators']['name'] != '')
			{
			
				$config['upload_path'] = $this->config->item('csv_geooperators');
				
				$config['allowed_types'] = 'application/csv|csv|xls|xlsx';
				$config['file_name'] = 'upload' . time() .$_FILES['geo_operators']['name'];
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload("geo_operators"))
				{ 
					$this->session->set_flashdata('error_msg', $this->upload->display_errors());
					redirect("admin/settings_geo_operators");		
				}
				else
				{
					$file_info 		=$this->upload->data();
					$csvfilepath 	=$this->config->item('read_csv_geooperators').$config['file_name'];
					$data	 		=$this->csvreader->parse_file($csvfilepath);
					$counter		='2';
					$error_telecom_name			=array();
					$error_telecom_countrycode	=array();
				
					if(count($data) > 0)
					{	
						foreach($data as $row)
						{	
							if(count($row) ==2 AND $row['telecom_name'] !='' AND $row['telecom_value'] !='')
							{	
								$status				=1; 	
								$telecom_name		=trim($row['telecom_name']);
								$telecom_countrycode=trim($row['telecom_value']);
								$insert_data		=array("telecom_name"  =>$telecom_name, "telecom_value" =>$telecom_countrycode, "telecom_status" =>$status);	
								$tbl_name			='djx_telecom_circle';
								
								$this->db->select('*');		
								$this->db->where('telecom_name', $telecom_name);
								$this->db->where('telecom_value', $telecom_countrycode);
								
								$query		=$this->db->get('djx_telecom_circle')->num_rows();
													 
								if($query >0)
								{
								  $error_telecom_name[]	=$counter;
								}
								else  
								{
									$this->mod_system_settings->insert_data($insert_data, $tbl_name);
									$this->session->set_flashdata('message', ''.$this->lang->line("notification_geo_operators_added_successfully").'');
								}
							 }
							$counter++;
						  }  
						
						if(count($error_telecom_name) >0)
						{
							$this->session->set_userdata('error_telecom_name', $error_telecom_name);
							$this->session->set_flashdata('operator_name_check', $this->lang->line('lang_operator_already_exists_in_upload'));
						}
						if(file_exists($this->config->item('csv_geooperators_path')."/".$config['file_name']))
						{	
								unlink($this->config->item('csv_geooperators_path')."/".$config['file_name']);
						}
						
						redirect('admin/settings_geo_operators');
						
					}
			
			     }	
			  }	
			  else
			  {
				$this->session->set_flashdata('operator_name_file_chk', $this->lang->line('pls_Enter_import _file'));
				redirect('admin/settings_geo_operators');
			  }			  
	}
	 public function geo_operators_download_file()
		{	
	 $data = file_get_contents("./assets/upload/admin/geooperators/operators_test.csv"); // Read the file's contents
     $name = 'sample.csv';
     force_download($name, $data); 
	    }
}
/* End of file advertisers.php */
/* Location: ./modules/system_settings/controllers/geo_operators.php */



