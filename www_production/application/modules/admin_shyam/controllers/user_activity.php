<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_activity extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		/* Models */
		$this->load->model("mod_user_activity", 'activity');
		
		/* Libraries */
		 
    }
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
         
	    $start=0;
		$data['task']			=$this->activity->get_activity_tasks();
		$ids					=$this->activity->get_selected_tasks('0');

		$data['selecttask']	=explode(",",$ids['task_id']);
		$data['page_title'] 	= $this->lang->line('label_Activities');	
		$data['offset']			=($start ==0)?1:($start + 1);
		
		 $data['page_content']	=	$this->load->view('activity/activity_list',$data,true);
		 $this->load->view('page_layout',$data);
		//$this->load->view('activity/emailview/income');
	}

	/* Site Setting Upload Process */
	
  function activity_process()
	{
		$userid		=$this->input->post('userid');
		$usertype	=$this->input->post('usertype');
		$tasklist	=implode(',',$this->input->post('activity'));
	
		$data		=array('task_id' =>$tasklist, 'activity_user_type' =>$usertype, 'activity_user_id' =>$userid);
		$status		=$this->activity->activity_process($data);
		$this->session->set_flashdata('message', $this->lang->line("label_activity_successfully"));
		redirect('admin/user_activity');
	}	
}

/* End of file site_settings.php */
/* Location: ./modules/admin/site_settings.php */
