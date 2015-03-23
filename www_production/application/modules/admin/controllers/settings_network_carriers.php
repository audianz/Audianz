<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_network_carriers extends CI_Controller {

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
		$list_data = $this->mod_system_settings->get_network_carriers($this->status);

		$config['per_page'] = $limit;

		if($this->status=="active"){
			$config['base_url'] = site_url("admin/settings_network_carriers/active/");
		}
		else if($this->status == "inactive") /* Active Base Url */
		{
			$config['base_url'] = site_url("admin/settings_network_carriers/inactive/");
		}
		else{ /* InActive Base Url */
			$config['base_url'] = site_url("admin/settings_network_carriers/device_list/");
		}
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");
		$config['last_link'] 	= $this->lang->line("pagination_last_link");
		$config['first_link'] 	= $this->lang->line("pagination_first_link");

		$this->pagination->initialize($config);

		//$list_data = $this->mod_system_settings->get_geo_operators($this->status,$limit,$start);
		$data['carriers_list']	=	$list_data;
			
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
		$data['page_content']	= $this->load->view("network_carriers/list_carriers",$data,true);
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
	function edit_carriers($carrier_id = false)
	{

		if($carrier_id !=false)
		{
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			-------------------------------------------------------------*/
			$link = breadcrumb();
			$data1['breadcrumb'] 	= $link;
				
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			-------------------------------------------------------------*/
			$data1['page_title'] 				= "Mobile Carrier Details";
			$data1['carrier_id']			=	$carrier_id;
			$data1['carriers_data']		=	$this->mod_system_settings->get_carriers($carrier_id);
			//$data1['country']		=	$this->mod_system_settings->get_country();
			$data1['page_content']	=	$this->load->view('network_carriers/edit_carriers',$data1,true);

			$this->load->view('page_layout',$data1);
		}
		else
		{
			redirect('admin/settings_geo_operators');
		}
	}

	function edit_start_ip_unique($value, $params)
	{
		$this->form_validation->set_message('edit_start_ip_unique', "Sorry, that %s is already being used.");

		list($table, $field, $current_id) = explode(".", $params);

		$query = $this->db->select()->from($table)->where($field, $value)->limit(1)->get();

		if ($query->row() && $query->row()->id != $current_id)
		{
			return FALSE;
		}
	}

	function edit_end_ip_unique($value, $params)
	{
		$this->form_validation->set_message('edit_end_ip_unique', "Sorry, that %s is already being used.");

		list($table, $field, $current_id) = explode(".", $params);

		$query = $this->db->select()->from($table)->where($field, $value)->limit(1)->get();

		if ($query->row() && $query->row()->id != $current_id)
		{
			return FALSE;
		}
	}

	/*** Edit Device geo_operators Process ******/
	function edit_carrier_process()
	{

		$carrier_name	=	trim($this->input->post('carrier_name'));
		$country	=	trim($this->input->post('country'));
		$start_ip	=	trim($this->input->post('start_ip'));
		$end_ip	=	trim($this->input->post('end_ip'));
		$carrier_id		=$this->input->post('carrier_id');
		$this->form_validation->set_rules('carrier_name', 'Network Carrier Name','required|callback_operator_name_check');
		$this->form_validation->set_rules('country', 'Country', 'required');
		$this->form_validation->set_rules('start_ip', 'Start IP Address', 'required|callback_edit_start_ip_unique[ox_carrier_detail.start_ip.'. $carrier_id .']');
		$this->form_validation->set_rules('end_ip', 'End IP Address', 'required|callback_edit_end_ip_unique[ox_carrier_detail.end_ip.'. $carrier_id .']');
			
		if($this->form_validation->run() == FALSE)
		{
			//$this->session->set_userdata('notification_message', ''.$this->lang->line("label_fill_geo_operators").'');
			$this->edit_carriers($carrier_id);
		}
		else
		{
			$update_data	=	array("carriername" =>$carrier_name,
					"country" =>$country,
					"start_ip" => $start_ip,
					"end_ip" => $end_ip
			);
			$where_arr		=	array('id' =>$carrier_id);
			$tbl_name		=	'ox_carrier_detail';

			$this->mod_system_settings->update_data($update_data,$where_arr,$tbl_name);
			$this->session->set_flashdata('message', 'Network carrier has been updated successfully');
			redirect('admin/settings_network_carriers');
		}
	}

	/****Delete  Device geo_operators ********/
	public function delete_carriers($carrier_id=false)
	{

		//$where_arr = array('geo_operatorsrer_id'=>$geo_operators_id);
		/* Delete Device geo_operators */
		$id_field_name	=	'id';
		$tbl_name	=	'ox_carrier_detail';
			
		$this->mod_system_settings->delete_data($carrier_id,$id_field_name,$tbl_name);
		/* Device geo_operators Deleted Successfully. Redirect to Device geo_operatorsr List */
		$this->session->set_flashdata('message', 'Network Carrier has been deleted successfully');
		redirect('admin/settings_network_carriers');
		 

	}


	/* Duplicate Check For Devicegeo_operators*/
	public function operator_name_check(){

		$this->db->select('*');
		$this->db->where('carriername',$this->input->post('carrier_name'));
		$this->db->where('country',$this->input->post('country'));
			
		$query=$this->db->get('ox_carrier_detail')->num_rows();
			

		if($query >0)
		{
			$this->form_validation->set_message('operator_name_check', "The %s already exists for this country");
			return FALSE;
		}
		else
			return true;

	}
	 
	public function edit_start_ip_check()
	{


		$this->db->select('*');
		$this->db->where('start_ip',trim($this->input->post('start_ip')));
		$query=$this->db->get('ox_carrier_detail')->num_rows();
		 
		if($query !=1 )
		{
			$this->form_validation->set_message('edit_start_ip_check', "The %s already exists");
			return FALSE;
		}
		else
			return true;

	}
	 
	public function edit_end_ip_check()
	{


		$this->db->select('*');
		$this->db->where('end_ip',trim($this->input->post('end_ip')));
		$query=$this->db->get('ox_carrier_detail')->num_rows();

			
		if($query > 1 )
		{
			$this->form_validation->set_message('edit_end_ip_check', "The %s already exists");
			return FALSE;
		}
		else
			return true;
			
	}

	public function carriers_import_file()
	{
		$allowed_types = array('application/csv','application/x-csv','text/x-csv','text/csv');
		if($_FILES['carriers']['name'] != '')
		{
			if(in_array($_FILES['carriers']['type'],$allowed_types))
			{
				$config['upload_path'] = $this->config->item('csv_geooperators');
					
				$config['allowed_types'] = '*';
				$config['file_name'] = 'upload' . time() .$_FILES['carriers']['name'];
					
				$this->load->library('upload', $config);
					
				if(!$this->upload->do_upload("carriers"))
				{
					$this->session->set_flashdata('error_msg', $this->upload->display_errors());
					redirect("admin/settings_network_carriers");
				}
				else
				{
					$file_info 		= $this->upload->data();
					$csvfilepath 	= $this->config->item('read_csv_geooperators').$config['file_name'];
					$data	 		= $this->csvreader->parse_file($csvfilepath);
					$counter		= '1';
					$error_head_field_details = array();
					$error_carrier_name			= array();
					$error_start_ip			= array();
					$error_end_ip			= array();
					$error_telecom_countrycode	=array();
						
					if(count($data) > 0)
					{
						foreach($data as $row)
						{
							if(count($row) ==4 AND $row['start_ip'] !='' AND $row['end_ip'] !='' AND $row['country'] !='' AND $row['carriername'] !='')
							{
								$start_ip		=$row['start_ip'];
								$end_ip = $row['end_ip'];
								$country = trim($row['country']);
								$carriername = trim($row['carriername']);
								$insert_data		=array("start_ip"  =>$start_ip, "end_ip" =>$end_ip, "country" =>$country, "carriername"=> $carriername);
								$tbl_name			='ox_carrier_detail';
									
								$carriername_exists = $this->mod_system_settings->check_carrier_name_exist($carriername, $country);
								$start_ip_exist = $this->mod_system_settings->check_start_ip_exist($start_ip);
								$end_ip_exist = $this->mod_system_settings->check_end_ip_exist($end_ip);
									
								
									
								if($carriername_exists==0)
								{
									if($start_ip_exist==0)
									{
										if($end_ip_exist==0)
										{
											$this->mod_system_settings->insert_data($insert_data, $tbl_name);
											$this->session->set_flashdata('message', 'Network carrier has been added successfully');
										}
										else
										{
											$error_end_ip[]	=$counter;
										}
									}
									else
									{
										$error_start_ip[]	=$counter;
									}
								}
								else
								{
									$error_carrier_name[]	=$counter;
								}
							}
							else
							{
								$error_head_field_details[]=$counter;
							}
							$counter++;
						}
						if(count($error_carrier_name) >0)
						{
							$this->session->set_userdata('error_carrier_name', $error_carrier_name);
							$this->session->set_flashdata('error_carrier_name_msg', 'Carrier Name already exist in the following row -');
						}
						if(count($error_start_ip) >0)
						{
							$this->session->set_userdata('error_start_ip', $error_start_ip);
							$this->session->set_flashdata('error_start_ip_msg', 'Start IP address already exist in the following row -');
						}
						if(count($error_end_ip) >0)
						{
							$this->session->set_userdata('error_end_ip', $error_end_ip);
							$this->session->set_flashdata('error_end_ip_msg', 'End IP Address already exist in the following row -');
						}
						if(count($error_head_field_details) >0)
						{
							$this->session->set_userdata('error_head_field_details', $error_head_field_details);
							$this->session->set_flashdata('error_head_field_details_msg', 'Some fields are missing in the uploaded file.');
						}
						if(file_exists($this->config->item('csv_geooperators_path')."/".$config['file_name']))
						{
							unlink($this->config->item('csv_geooperators_path')."/".$config['file_name']);
						}
							
						redirect('admin/settings_network_carriers');
							
					}

				}
			}
			else
			{
				$this->session->set_flashdata('error_msg', 'Please upload csv files only.');
				redirect("admin/settings_network_carriers");
			}
		}
		else
		{
			$this->session->set_flashdata('operator_name_file_chk', $this->lang->line('pls_Enter_import _file'));
			redirect('admin/settings_network_carriers');
		}
	}

	public function geo_operators_download_file()
	{
	 $data = file_get_contents("./assets/upload/admin/geooperators/network_carrier_detail_sample.csv"); // Read the file's contents
	 $name = 'sample.csv';
	 force_download($name, $data);
	}
}
/* End of file advertisers.php */
/* Location: ./modules/system_settings/controllers/geo_operators.php */



