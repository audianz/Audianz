<?php 

class Settings_trackers_status extends CI_Controller {   
	
	public function __construct() 
    {  
		parent::__construct(); 
		
		/* Models */
		$this->load->model("mod_trackers_status"); //loc: inventory/models/mod_trackers_status
		
    }
	
	public function index() 
	{ 
		$this->view();		
	}
	
	/* Trackers_status listing Page */
	
	public function view($start=0) 
	{ 

		
		/*-------------------------------------------------------------
		 	Get all data's from database
		 -------------------------------------------------------------*/
		
		$data['status']=$this->mod_trackers_status->get_all();
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;	
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/	
		 
		$data['page_title'] 	= $this->lang->line('label_inventory_trackers_status_page_title');	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("default_settings/trackers/trackers_status",$data,true);
		$this->load->view('page_layout',$data);
	}

	/* Add trackers_status */
	
	public function add_status()
	
	{
	/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
	
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("default_settings/trackers/add_trackers_status",$data,true);
		$this->load->view('page_layout',$data);
		
	}

	/* Insert trackers_status data*/		
		
	public function insert_status()
	
	{ 	
		/* Form validation */
		
		$this->form_validation->set_rules('trackers_status','Trackers status name','trim|required|is_unique[djx_trackers_status.name]|alpha_numeric');
		$this->form_validation->set_rules('trackers_value','Trackers value','trim|required|numeric');
		
		if($this->form_validation->run()==FALSE)
			{
				/* Form validation fails */
				
				$this->add_status();
			}
		
		else		
		
			{
				$trackers_status=$this->input->post('trackers_status');
				$trackers_value=$this->input->post('trackers_value');
				$active=$this->input->post('active');
				
				/*-------------------------------------------------------------
		 				Fetch Tracker status from the db
		 		-------------------------------------------------------------*/
				
				$this->mod_trackers_status->insert_status($trackers_status, $trackers_value,$active);
				/*-------------------------------------------------------------
						Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link = breadcrumb();
				$data['breadcrumb'] 	= $link;
				
				/*End of BreadCrumb*/	
				
				$this->session->set_flashdata('trackers_success_message',$this->lang->line('label_trackers_status_add_success_msg'));
				redirect("admin/settings_trackers_status");	
				
			}
			
	}
	
	/* Edit trackers_status */
	
	public function edit_status($id=0)
	
	{
		$active=$this->mod_trackers_status->get_edit($id);
		
		/*-------------------------------------------------------------
				Update the Tracker status to the db
		-------------------------------------------------------------*/
					
		if($active == 1)
				{
					
					$data['record']=$this->mod_trackers_status->update_status($id,$active);
					$this->session->set_flashdata('trackers_success_message',$this->lang->line('trackers_status_inactivated_successfully'));
				}
				else
				{
					$data['record']=$this->mod_trackers_status->update_status($id,$active);
					$this->session->set_flashdata('trackers_success_message',$this->lang->line('trackers_status_activated_successfully'));
				}
				
		redirect("admin/settings_trackers_status");
		
	}
	
	
	
	/* Checks for duplication of data */
	
	 public function trackers_status_check()
                         
	{
						
                 
         $query=$this->db->where(array("name" =>$this->input->post('trackers_status')))->get('djx_trackers_status')->num_rows();
                
         if($query==0)
            {
					echo "no";
					exit;                                                
			}
			
		else
			{
			
			echo "yes";
			exit;
			}

	
	}
		
}

/* End of file trackers_status.php */
/* Location: ./modules/admin/controllers/trackers_status.php */	
