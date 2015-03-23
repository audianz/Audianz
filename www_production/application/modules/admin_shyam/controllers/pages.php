<?php
class Pages extends CI_Controller {   
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	
	public function __construct() 
    {  
		parent::__construct();
		/* Models */
		$this->load->model("mod_pages"); //loc: admin/models/mod_pages
		$this->load->model("mod_static_pages"); //loc: admin/models/mod_pages
	 
    }
		
		/* Pages Landing Page */
	public function index() 
	{ 
		$this->listing();	
	}
	
	/* static_pages/pages listing Page */
	public function listing() 
	{ 
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Fetch pages list from database 
		 -------------------------------------------------------------*/
		$data['pages_list']		=		$this->mod_pages->get_all();
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 		=		$this->lang->line('label_static_pages_page_title');
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']		= 		$this->load->view("pages/pages_lists",$data,true);
		$this->load->view('page_layout',$data);
	}

	

	
	public function add_page($start=0) 
	{ 
	
		$limit=50;
		/*-------------------------------------------------------------
				Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link 				= breadcrumb();
		$data['breadcrumb'] 		= $link;
		
		$parent_item			=		$this->mod_pages->get_parent_item();
		$data['parent_item']		=		$parent_item;
		
		$list_data_parent 		= 		$this->mod_static_pages->get_all_parent_only(50,$start,$limit);		
		$data['menu_list_parent']	=		$list_data_parent;
		

		$menu_id 			= 		$this->mod_static_pages->get_menu_id_for_compare();	
		$data['menu_id']		= 		$menu_id;

		
		/*-------------------------------------------------------------
				Embed current page content into template layout
		-------------------------------------------------------------*/
		$data['page_content']		= 		$this->load->view("pages/add_page",$data,true);
		$this->load->view('page_layout',$data);
	
	}
	
	public function insert_page() 
	{ 
			
			//Setting rules for server-side validation
			$this->form_validation->set_rules('page_name', 'Page Name', 'required|trim|is_unique[oxm_pagedetails.page_title]|max_length[20]|min_length[3]');
			$this->form_validation->set_rules('menu_name', 'Menu location', 'required');
			$this->form_validation->set_rules('meta_keyword', 'Meta Keyword','trim');
			$this->form_validation->set_rules('page_content', 'Page Content','trim|required');
        		if ($this->form_validation->run() == FALSE)
            		{
					   /* Form Validation  failed. Redirect to Add page Form */
					   $this->session->set_userdata('notification_message', ''.$this->lang->line("label_website_insertion_failed_msg").'');
					   $this->add_page();
            		}
           
				else
					{
				//Getting data from user
				$page_name			=		$this->input->post('page_name');
				$status				=		$this->input->post('status');
				$menu_id			=		$this->input->post('menu_name');
				$menu_name			=		$this->mod_pages->get_menu_name($menu_id);
				$keyword			=		mysql_real_escape_string($this->input->post('meta_keyword'));
				$text				=			$this->input->post('text');
				$page_content		=			$this->input->post('page_content');
				$status				=			$this->input->post('status');
				$data				=			array(
														'pageid'					=>	'',
														'status'					=>	$status,
														'page_title'				=>	$page_name,
														'description'				=>	$page_content,
														'keywords'					=>	$keyword,
														'menu_id'					=>	$menu_id
														);			
																		
			
				$this->mod_pages->insert($data);
				
				$this->session->set_flashdata('pages_success_message',$this->lang->line('label_static_page_page_add_sucess'));
			
       
				redirect('admin/pages');
				}
			
		
		
	}
	

		/* Inventory/Edit page controller */
	
	public function edit_page($page_id=0) 
	{ 
		$start=0;
		$limit=50;
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb'] 	= $link;
		
			if($page_id!=0)
			{
		/*-------------------------------------------------------------
		 	Get the page details from db
		 -------------------------------------------------------------*/
				$data['page_details']		=		$this->mod_pages->get_menu_name_page($page_id);
				
				//$data['parent_item']	=		$this->mod_pages->get_parent_item($page_id);
				
				//$data['record'] 		= 		$this->mod_pages->edit_page($page_id);
				
				$list_data_parent 			= 		$this->mod_static_pages->get_all_parent_only(50,$start,$limit);		
				$data['menu_list_parent']	=		$list_data_parent;
				
				$menu_id 			= 		$this->mod_static_pages->get_menu_id_for_compare();	
				$data['menu_id']		= 		$menu_id;
				
			}
		
		else
			{
				$this->listing();	
       
			}		
			
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']			= 		$this->load->view("pages/edit_page",$data,true);
		$this->load->view('page_layout',$data);
		
	}
	
	public function page_status_activate($page_id)
	{
		
		$this->mod_pages->status_activate($page_id);
		
		//Setting flash data after successfully changing the status of the page
		$this->session->set_flashdata('pages_status_message',$this->lang->line('label_page_update_status_msg'));
		redirect('admin/pages');
		
	}	
	
	public function page_status_deactivate($page_id)
	{
		
		$this->mod_pages->status_deactivate($page_id);
		
		//Setting flash data after successfully deactivating the status of the page
		$this->session->set_flashdata('pages_status_deactivate_message',$this->lang->line('label_page_update_status_deactivated_msg'));
		redirect('admin/pages');
		
	}	
	
	public function update_page() 
	{ 
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 							= breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		
		//Setting Rules for server-side validation
		$this->form_validation->set_rules('page_name', 'Page Name', 'required|trim|callback_alpha_check|callback_name_check|max_length[20]|min_length[3]');
		$this->form_validation->set_rules('meta_keyword', 'Meta Field', 'trim');
		$this->form_validation->set_rules('menu_name', 'Menu location', 'required');
		$this->form_validation->set_rules('page_content', 'Page Content','trim|required');
		if ($this->form_validation->run() == FALSE)
            {
				   /* Form Validation is failed. Redirect to edit page Form */
				$this->session->set_userdata('notification_message', ''.$this->lang->line("label_website_update_failed_msg").'');
				$pageid	=		$this->input->post('id');
				$this->edit_page($pageid);
            }
           
			else
			{
				//Getting Updated data from user
				
				$upd_page_name				=			$this->input->post('page_name');
				$upd_status						=			$this->input->post('status');
				$upd_menu_name				=			$this->input->post('menu_name');
				$upd_keyword					=			mysql_real_escape_string($this->input->post('meta_keyword'));
				$upd_page_content			=			$this->input->post('page_content');
				$id									=			$this->input->post('id');
				$data									=			array(
																			'status'					=>	$upd_status,
																			'page_title'				=>	$upd_page_name,
																			'description'			=>	$upd_page_content,
																			'keywords'				=>	$upd_keyword,
																			'menu_id'				=>	$upd_menu_name
																			);
																	
				$this->mod_pages->update_page($data,$id);
				
				$this->session->set_flashdata('pages_update_message',$this->lang->line('label_page_update_successfully_msg'));
				
				redirect('admin/pages');
			}
	}
	
	

		/* Inventory/Edit page controller */
	
	public function delete_page($page_id=0) 
	{ 
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		$data['breadcrumb'] 	= $link;

		/*-------------------------------------------------------------
		 	Get the page details from db
		 -------------------------------------------------------------*/
		 if($page_id!=0)

			{
					$data['record'] = $this->mod_pages->delete($page_id);
					
					$this->session->set_flashdata('pages_success_message',$this->lang->line('label_page_delete1_success_msg'));
						
					redirect('admin/pages');
			}
			
		else
			
			{
					$page_id	=		$this->input->post("pages");
					
					$this->mod_pages->delete_chk_box($page_id);
					
					$this->session->set_flashdata('pages_delete_message',$this->lang->line('label_page_delete_success_msg'));
						
					redirect('admin/pages');
					
			}			
				   
	}
	
	/* Checks for page _name duplication in client side */
	 public function page_name_check()
                         
	{
		$query=$this->db->where(array("page_title" =>$this->input->post('page_name')))->like('page_title',$this->input->post('page_name'))->get('oxm_pagedetails')->num_rows();
                
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
	

//Codeigniter's callback function to check for invalid characters
	function alpha_check()
		{
				$feild		=		$this->input->post('page_name');
				if( ! preg_match('/^[a-zA-Z\s\-]+$/',$feild))
					{
						$this->form_validation->set_message('alpha_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}				
			}
			
	//Codeigniter's callback function to check for invalid characters
	function meta_check()
		{
				$feild		=		$this->input->post('meta_keyword');
				if( ! preg_match('/^[0-9a-zA-Z\s]+$/',$feild))
					{
						$this->form_validation->set_message('meta_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}				
			}				
	
	//Codeigniter's callback function to check for duplication
	function name_check()
		{
			$this->db->where('pageid !=',$this->input->post('id'));
			
			$this->db->where('page_title',$this->input->post('page_name'));
			
			$query	=	$this->db->get('oxm_pagedetails')->num_rows();
			
			if($query==1)
				{
					$this->form_validation->set_message('name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return  false;
				}	
			else	
				{
					return true;
				}
			}	
	
	public function edit_name_check()
	 		{
			
			$this->db->where('page_title',$this->input->post('page_name'));
			
			$this->db->where('pageid !=',$this->input->post('page_id'));
			
			$query=$this->db->get('oxm_pagedetails')->num_rows();
			
			if($query==1 )
				{
					echo 'yes';
					return FALSE;	
				}
			else
				{	
					echo 'no';
					return true;
				}
			}

}

/* End of file pages.php */
/* Location: ./modules/admin/controllers/pages.php */
