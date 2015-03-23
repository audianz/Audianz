<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_mobile_screens extends CI_Controller {   
	
	
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
		$data['page_title'] 	= $this->lang->line('label_mobile_screen_size');	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		 $data['mobile_screen_list']	=	$this->db->order_by('id','ASC')->get('oxm_mobilescreensizesettings')->result();
		 
		$data['page_content']	= $this->load->view("mobile_screens/mobile_screens",$data,true);
		
		
		
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
	
	
	/* Updating Mobile Screen Size Settings*/
	function update_process()
	{
			
			//Form validation Rules
			$this->form_validation->set_rules('master1_width', 'Master Width', 'required|numeric');
			$this->form_validation->set_rules('master1_height', 'Master Height', 'required|numeric');
			
			$this->form_validation->set_rules('child1_width', 'Child1 Width', 'required|numeric');
			$this->form_validation->set_rules('child1_height', 'Child1 Height', 'required|numeric');
			
			$this->form_validation->set_rules('child2_width', 'Child2 Width', 'required|numeric');
			$this->form_validation->set_rules('child2_height', 'Child2 Height', 'required|numeric');
			
			$this->form_validation->set_rules('child3_width', 'Child3 Width', 'required|numeric');
			$this->form_validation->set_rules('child3_height', 'Child3 Height', 'required|numeric');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata('notification_message', ''.$this->lang->line("notification_fill_mobile_screen").'');
				redirect('admin/settings_mobile_screens');
			}else{
					
					/**** Mobile Screen Size ***/
				//-------M-aster Width and Height------
					$mw	  =trim(mysql_real_escape_string($this->input->post('master1_width')));
					$mh	  =trim(mysql_real_escape_string($this->input->post('master1_height')));
					
					
					$tbl_name		=	'oxm_mobilescreensizesettings';
					$data	  =array("width" =>$mw, "height" =>$mh);
					$where	  =array("id" =>'1');
					$this->mod_system_settings->update_data($data, $where,$tbl_name);
					
					//------Child1 Width and Height-------- \\
					$c1w	  =trim(mysql_real_escape_string($this->input->post('child1_width')));
					$c1h	  =trim(mysql_real_escape_string($this->input->post('child1_height')));
					
					$data1	  =array("width" =>$c1w, "height" =>$c1h);
					$where	  =array("id" =>'2');
					$this->mod_system_settings->update_data($data1, $where,$tbl_name);
					//------C1---------\\
					
					//------Child2 Width and Height-------- \\
					$c2w	  =trim(mysql_real_escape_string($this->input->post('child2_width')));
					$c2h	  =trim(mysql_real_escape_string($this->input->post('child2_height')));
					
					$data2	  =array("width" =>$c2w, "height" =>$c2h);
					$where	  =array("id" =>'3');
					$this->mod_system_settings->update_data($data2, $where,$tbl_name);
					//------C2---------\\
		
					//------Child3 Width nad Height-------- \\
					$c3w	  =trim(mysql_real_escape_string($this->input->post('child3_width')));
					$c3h	  =trim(mysql_real_escape_string($this->input->post('child3_height')));
					
					$data3	  =array("width" =>$c3w, "height" =>$c3h);
					$where	  =array("id" =>'4');
					$this->mod_system_settings->update_data($data3, $where,$tbl_name);
					//------C3---------\\
					
					$this->session->set_userdata('message', ''.$this->lang->line("notification_mobile_screen_updated_successfully").'');
					//$this->index();
					redirect('admin/settings_mobile_screens');
			}
	}
	
	
}

/* End of file device_os.php */
/* Location: ./modules/system_settings/controllers/device_os.php */
