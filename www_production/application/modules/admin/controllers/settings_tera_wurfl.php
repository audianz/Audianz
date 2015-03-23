<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_tera_wurfl extends CI_Controller {   
	
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Libraries */
		
		
		/* Helpers */
		
		
		/* Models */
		$this->load->model("mod_system_settings"); //loc:Settings/models/mod_system_settings
		
		
		/* Classes */
    }
	
	/* Geo Locations List*/
	public function index() 
	{ 
					
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_tera_wurl_title');	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$tera_wurl_list			=	$this->db->get('oxm_terawurfl')->result();
		 
		if($tera_wurl_list != FALSE)
		{ 
			$data['tera_wurl_list']	=	$tera_wurl_list[0];
		}
		else
		{
			$data['tera_wurl_list']	=	'';	
		} 
		$data['page_content']	= $this->load->view("tera_wurfl/tera_wurfl",$data,true);
		
		
		
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Updating Mobile Screen Size Settings*/
	function update_process()
	{
			
			//Form validation Rules
			$this->form_validation->set_rules('tera_wurl_path', 'Tera Wurl Path', 'required|callback_valid_urls');
			if($this->form_validation->run() == FALSE)
			{
				//$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_mobile_screen").'');
				$this->index();
			}else{
					
				$tera_wurl_path	  =trim(mysql_real_escape_string($this->input->post('tera_wurl_path')));
				$query = $this->db->query('SELECT * FROM `oxm_terawurfl');
				if($query->num_rows() > 0)
				{	
				$tbl_name		=	'oxm_terawurfl';
				$data	  =array("terawurfl_path" =>$tera_wurl_path);
				$where	  =array("path_id" =>'1');
				$this->mod_system_settings->update_data($data, $where,$tbl_name);
				}
				else
				{
				$tbl_name		=	'oxm_terawurfl';
				$data	  =array("terawurfl_path" =>$tera_wurl_path);
				$this->mod_system_settings->insert_data($data,$tbl_name);
				}	
				$this->session->set_userdata('message', ''.$this->lang->line("notification_tera_wurl_updated_successfully").'');
					//$this->index();
				redirect('admin/settings_tera_wurfl');
			}
	}
	
	function valid_urls($str) {

		if(!filter_var($str, FILTER_VALIDATE_URL))
		{

			$this->form_validation->set_message('valid_urls', $this->lang->line("label_incorrect_url"));
			return FALSE;
  		}
		else {
			return TRUE;
     	}
	}  
	
	
}

/* End of file device_os.php */
/* Location: ./modules/system_settings/controllers/device_os.php */
