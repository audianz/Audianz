<?php


class  Settings_revenue_type extends CI_Controller {   
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_revenue"); //loc: admin/models/mod_advertisers
		
    }
	
	public function index() 
	{ 
		$this->view();		
	}
	
	/* Inventory Revenue Type listing Page */
	
	public function view($start=0) 
	{ 
	
		$data['type']	=	$this->mod_revenue->list_revenue_type();
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		 
		$link 							= breadcrumb();
		
		$data['breadcrumb'] 	= 	$link;
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']		= 	$this->load->view("default_settings/campaigns/list_type",$data,true);
		
		$this->load->view('page_layout',$data);
	}

	public function add_type()
	
	{
	/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 								= breadcrumb();
		
		$data['breadcrumb'] 		= $link;
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout.
		 -------------------------------------------------------------*/
		 
		$data['page_content']		= 	$this->load->view("default_settings/campaigns/add_revenue_type",$data,true);
		
		$this->load->view('page_layout',$data);
		
		}
		
		public function edit_type($id	=	0)
	
	{
 
		$revenue_id		=		(($id==0)?$this->uri->segment(4):$id);
		
		$data['record']	=		$this->mod_revenue->edit_revenue_type($revenue_id);
		
	/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		 
		$link 							= breadcrumb();
		
		$data['breadcrumb'] 	= $link;
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']		= 		$this->load->view("default_settings/campaigns/edit_revenue_type",$data,true);
		
		$this->load->view('page_layout',$data);
		
		}
		
		public function insert_type()
	
		{		
				//Setting  Validation rules for sever-side
				
				$this->form_validation->set_rules('new_revenue_type','Revenue Type','trim|required|is_unique[da_inventory_revenue_type.revenue_type]|callback_alpha_check');
				
				if($this->form_validation->run()==FALSE)
					
				{
					
					//Setting user data message after unsuccessfull insertion and redirecting to add_type page
					$this->session->set_userdata('notification_message', ''.$this->lang->line("label_inventory_enter_revenue_server_type").'');
					
					$this->add_type();
			
				}
		
				else		
		
				{
					$revenue_type	=		$this->input->post('new_revenue_type');
					
					$this->mod_revenue->insert_type($revenue_type);
					
					//Setting flash data message after successfull insertion and redirecting to revenue_type page
					$this->session->set_flashdata('revenue_success_message',$this->lang->line('inventory_revenue_add_success_msg'));
					
					redirect("admin/settings_revenue_type");	
				
				}
			
		}

		
		public function update_type()
		
		{
				$id=$this->input->post('revenue_id');
	
				//Setting  Validation rules for sever-side
				
				$this->form_validation->set_rules('new_revenue_type','Revenue Type','trim|required|callback_type_check|callback_alpha_check');
		
				if($this->form_validation->run()	==	FALSE)
				{
				
					//Setting user data message after unsuccessfull updation and redirecting it back to edit_type page
					$this->session->set_userdata('notification_message', ''.$this->lang->line("label_inventory_enter_revenue_types").'');
					
					$this->edit_type($id);
				}
			
				else
		
				{
			
					$revenue_type		=		$this->input->post('new_revenue_type');
					
					$revenue_id			=		$this->input->post('revenue_id');
					
					$this->mod_revenue->update_type($revenue_id,$revenue_type);
					
					//Setting user data message after successfull updation and redirecting to revenue_type page
					$this->session->set_flashdata('revenue_update_message',$this->lang->line('inventory_revenue_update_success_msg'));
					
					redirect("admin/settings_revenue_type");		
				}
				
		}
		
		public function delete_type()
		
			{
			
					$id		=		$this->uri->segment(4);
					
					$this->mod_revenue->delete_type($id);
		
					//Setting flash data message after successfull delete operation and redirecting to revenue_type page
					$this->session->set_flashdata('revenue_delete_message',$this->lang->line('inventory_revenue_update_success_msg'));
					
					redirect("admin/settings_revenue_type");		
					
			}
		
		/*Code Igniter's Call Back Function to avoid Revenue Type duplication during update*/
	 	public function type_check()
	 	
			{
			
			
					$this->db->where('revenue_type',$this->input->post('new_revenue_type')); 
					
					$this->db->where('revenue_id !=',$this->input->post('revenue_id'));
					
					$query=$this->db->get(' da_inventory_revenue_type')->num_rows();
					
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
				$feild		=		$this->input->post('new_revenue_type');
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
			
			
			/*Client side validation : Revenue Type duplication check function*/	
					
		public function revenue_type_check()
			 {
		 
		 		$query		=		$this->db->where(array("revenue_type" =>$this->input->post('revenue_type')))->get('da_inventory_revenue_type')->num_rows();
		
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
			
		public function revenue_check()
	 		{
			
			$this->db->where('revenue_type',$this->input->post('revenue_type'));
			
			$this->db->where('revenue_id !=',$this->input->post('id'));
			
			$query=$this->db->get('da_inventory_revenue_type')->num_rows();
			
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
	
