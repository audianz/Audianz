<?php

class  Settings_campaign_status extends CI_Controller {   
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_campaign_status"); //loc: admin/models/mod_advertisers
		
    }
	
	public function index() 
	{ 
		$this->view();		
	}
	
	/* Inventory Campaign status listing Page */
	public function view($start=0) 
	{ 
	
		$data['type']=$this->mod_campaign_status->list_campaign_status();
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		 
		$link		 					= 		breadcrumb();
		
		$data['breadcrumb'] 	= 		$link;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		 
		$data['page_title'] 		= 		$this->lang->line('label_inventory_campaign_status_title');		
		
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	  = 	$this->load->view("default_settings/campaigns/list_status",$data,true);
		
		$this->load->view('page_layout',$data);
	}
	
	public function add_status()
	
	{
	/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		 
		$link		 					= 		breadcrumb();
		
		$data['breadcrumb'] 	= 		$link;
		
	
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	= $this->load->view("default_settings/campaigns/add_campaign_status",$data,true);
		
		$this->load->view('page_layout',$data);
		
		}
		
		public function edit_status($id=0)
	
	{
	
		
		
	/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link		 					= 		breadcrumb();
		
		$data['breadcrumb'] 	= 		$link;
		
		/*End of BreadCrumb*/
		
		$campaign_id				=		(($id==0)?$this->uri->segment(4):$id);
		
		$data['record']			=		$this->mod_campaign_status->edit_campaign_status($campaign_id);
		
	
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']		= 	$this->load->view("default_settings/campaigns/edit_campaign_status",$data,true);
		
		$this->load->view('page_layout',$data);
		
		}
		
			public function insert_status()
	
		{
	
		/*Setting validation rules for server side validation*/
		
		$this->form_validation->set_rules('new_status','Campaign Status','trim|required|is_unique[da_inventory_campaign_status.status]|callback_alpha_check');
		if($this->form_validation->run()==FALSE)
			
			{
					//Setting user data message after unsuccessfull insertion and redirecting to campaign_add_status page
					$this->session->set_userdata('notification_message', ''.$this->lang->line("label_inventory_campaign_enter_server_status").'');
					
					$this->add_status();
			
			}
		
		else
			
			{		
		
					$campaign_status		=		$this->input->post('new_status');
					
					$this->mod_campaign_status->insert_campaign_status($campaign_status);
				
					//Setting user data message after successfull insertio and redirecting to campaign_status page
					$this->session->set_flashdata('campaign_success_message',$this->lang->line('inventory_campaign_add_success_msg'));
					
					redirect("admin/settings_campaign_status");	
		
			}
		
		}	

		
		public function update_status()
		{
			
		$id			=		$this->input->post('campaign_id');
		
		/*Setting validation rules for server side validation*/
		
		$this->form_validation->set_rules('new_status','Campaign Status','trim|required|callback_type_check|callback_alpha_check');
		
		if($this->form_validation->run()==FALSE)
			{
					//Setting user data message after unsuccessfull  updation and redirecting it back to edit_status  page
					
					$this->session->set_userdata('notification_message', ''.$this->lang->line("label_inventory_campaign_enter_server_status").'');
					
					$this->edit_status($id);
			
			}
			
		else
		
			{
			
					$status		=		$this->input->post('new_status');
					
					$id			=		$this->input->post('campaign_id');
					
					$this->mod_campaign_status->update_campaign_status($id,$status);
					
					//Setting user data message after successfull update operation and redirecting to campaign_status page
					$this->session->set_flashdata('campaign_update_message',$this->lang->line('inventory_campaign_update_success_msg'));
					
					redirect("admin/settings_campaign_status/");	
		
			}
		
		}
		
		public function delete_status()
		{
			
					$id	=		$this->uri->segment(4);
					
					$this->mod_campaign_status->delete_campaign_status($id);
					
					//Setting user data message after successfull delete operation and redirecting to campaign_status page
					$this->session->set_flashdata('campaign_delete_message',$this->lang->line('inventory_campaign_delete_success_msg'));
					
					redirect("admin/settings_campaign_status");
		
		}


		
			
		/*-------------------------------------------------------------------------------
	 				STARTING OF CODEIGNITER'S CALLBACK FUNCTIONS
	 ---------------------------------------------------------------------------------*/
		
		public function type_check()
	 	{
			
			
			$this->db->where('status',$this->input->post('new_status')); 
			
			$this->db->where('campaign_status_id !=',$this->input->post('campaign_id'));
			
			$query=$this->db->get('da_inventory_campaign_status')->num_rows();
			
			if($query==1 )
				
				{
						$this->form_validation->set_message('type_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
						
						return FALSE;	
				
				}
				
			else
				
						return true;
					
				
			}
			
		function alpha_check()
		{
				$feild		=		$this->input->post('new_status');
				if( ! preg_match('/^[a-zA-Z\s]+$/',$feild))
					{
						$this->form_validation->set_message('alpha_check', $this->lang->line('label_entered').'%s'. $this->lang->line('label_must_contain_only_alpha_and_spaces'));
						return  false;
					}	
				else
					{
						return true;
					}
			}	
			
		/*Client-side validation check to avoid status duplication*/
		
		public function campaign_status_check()
			 {
		 
		 		$query		=		$this->db->where(array("status" =>$this->input->post('new_status')))->get('da_inventory_campaign_status')->num_rows();
		
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
			
		public function status_check()
	 		{
			
			$this->db->where('status',$this->input->post('camp_status'));
			
			$this->db->where('campaign_status_id !=',$this->input->post('camp_id'));
			
			$query=$this->db->get('da_inventory_campaign_status')->num_rows();
			
			if($query==1 )
				{
					echo 'yes';
					exit;
				}
			else
				{	
					echo 'no';
					exit;
				}
			}			
	
}
