<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smaato_share_settings extends CI_Controller {
	
	public function __construct() 
    {
		parent::__construct();	
		
		/* Models */
		$this->load->model('mod_system_settings');
		
		/* Libraries */		
		
	}
	
	public function index()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= 	$link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= 	"Smaato Share";	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$smaato_share		=	$this->db->get('oxm_smaatoshare')->result();
		
		if($smaato_share != FALSE)
		{ 
			$data['smaato_share']	=	$smaato_share[0];
		}
		else
		{
			$data['smaato_share']	=	'';	
		} 
		$data['page_content']	= $this->load->view("share/smaato_share",$data,true);
				
		$this->load->view('page_layout',$data);
		//redirect('settings/system/device_manufacturers')	;
	}
		
	public function update_process()
	{
		//Form validation Rules
		$this->form_validation->set_rules('smaato_share', 'Smaato Share', 'trim|required|is_numeric');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{				
			$smaato_share	=	trim($this->input->post('smaato_share'));
			$query 				= 	$this->db->query('SELECT * FROM `oxm_smaatoshare');
			if($query->num_rows() > 0)
			{	
				$tbl_name	=	'oxm_smaatoshare';
				$data	  	=	array("smaato_share" =>$smaato_share);
				$where	  	=	array("id" =>'1');
				$this->mod_system_settings->update_data($data, $where,$tbl_name);
			}
			else
			{
				$tbl_name	=	'oxm_smaatoshare';
				$data	  	=	array("smaato_share" =>$smaato_share);
				$this->mod_system_settings->insert_data($data,$tbl_name);
			}	
			$this->session->set_userdata('message', 'Smaato Share has been updated successfully');
				
			redirect('admin/smaato_share_settings');
		}		
	}
}

/* End of file publisher_share.php */
/* Location: ./application/modules/admin/controllers/publisher_share.php */
