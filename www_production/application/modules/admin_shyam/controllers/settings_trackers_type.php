<?php


class Settings_trackers_type extends CI_Controller {   
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_trackers_type"); //loc: inventory/models/mod_trackers_type
		
    }
	
	public function index() 
	{ 
		$this->view();		
	}
	
	/* Inventory Trackers_status listing Page */
	
	public function view($start=0) 
	{ 

		/*-------------------------------------------------------------
		 	Get all data's from database
		 -------------------------------------------------------------*/
		
		$data['type']=$this->mod_trackers_type->get_all();
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;	
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/	
		 
		$data['page_title'] 	= $this->lang->line('label_inventory_trackers_type_page_title');	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("default_settings/trackers/trackers_type",$data,true);
		$this->load->view('page_layout',$data);
	}

	/* Add trackers_type */

	public function add_type()
	
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;	
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("default_settings/trackers/add_trackers_type",$data,true);
		$this->load->view('page_layout',$data);
		
	}
		
	/* Insert trackers_type data*/	
		
	public function insert_type()
	
	{
		/* Form validation */
		
		$this->form_validation->set_rules('trackers_type','Trackers type name','trim|required|is_unique[djx_trackers_type.name]|alpha_numeric');
		$this->form_validation->set_rules('trackers_value','Trackers value','trim|required|numeric');
		
		if($this->form_validation->run()==FALSE)
			{
				/* Form validation fails */
				
				$this->add_type();
			}
		
		else		
		
			{
				$trackers_type=$this->input->post('trackers_type');
				$trackers_value=$this->input->post('trackers_value');
				$active=$this->input->post('active');
				
				/*-------------------------------------------------------------
		 				Fetch Tracker type from the db
		 		-------------------------------------------------------------*/
				
				$this->mod_trackers_type->insert_type($trackers_type, $trackers_value,$active);
				
				/*-------------------------------------------------------------
						Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link = breadcrumb();
				$data['breadcrumb'] 	= $link;
				
			/*End of BreadCrumb*/	
				
				$this->session->set_flashdata('trackers_success_message',$this->lang->line('label_trackers_type_add_success_msg'));
				redirect("admin/settings_trackers_type");	
				
			}
			
		}
		
	/* Edit trackers_type */	
		
	public function edit_type($id=0)
	
	{
		$active=$this->mod_trackers_type->get_edit($id);
		
		/*-------------------------------------------------------------
				Update the Tracker type to the db
		-------------------------------------------------------------*/
		
		if($active == 1)
				{
					$data['record']=$this->mod_trackers_type->update_type($id,$active);
					$this->session->set_flashdata('trackers_success_message',$this->lang->line('trackers_type_inactivated_successfully'));
				}
				else
				{
					$data['record']=$this->mod_trackers_type->update_type($id,$active);
					$this->session->set_flashdata('trackers_success_message',$this->lang->line('trackers_type_activated_successfully'));
				}
		redirect("admin/settings_trackers_type");
		
	}
	
	/* Checks for duplication of data */
	public function trackers_type_check()
                         
	{
						
                 
         $query=$this->db->where(array("name" =>$this->input->post('trackers_type')))->get('djx_trackers_type')->num_rows();
                
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
/* End of file trackers_type.php */
/* Location: ./modules/admin/controllers/trackers_type.php */		
