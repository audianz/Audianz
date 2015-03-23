<?php
class Menus extends CI_Controller
{
	
	var $page_limit = 50; 
	
	public function __construct() 
    {  
				
			parent::__construct();
		
			/* Libraries */
		
			/* Helpers */
		
			/* Models */
			
			$this->load->model("mod_static_pages"); //loc: Static_page/models/mod_static_page
		
		
		/* Classes */
    	}
	
		/* Static_Pages: Menu  Landing Page */
	public function index() 
	{ 
		
			$this->view();		
		
	}
	
	/* Static_pages menu listing Page */
	
	public function view($start=0) 
	{ 
	
			/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link 							= breadcrumb();
			
			$data['breadcrumb'] 	= $link;
			
			/*End of Breadcrumb*/
	
			/*--------------------------------------------------------------
				Pagination  Config Setup
			 ---------------------------------------------------------------*/
					
			$list_data 						=		 $this->mod_static_pages->get_all_menu(50);
			$limit 							= 		$this->page_limit;
			$config['per_page'] 		= 		$limit;
			$config['base_url']		 	= 		site_url("static_pages/menus/view");
			$config['uri_segment'] 	= 		4;
			$config['total_rows'] 		= 		count($list_data);
			$config['next_link'] 			= 		$this->lang->line("pagination_next_link");
			$config['prev_link'] 			= 		$this->lang->line("pagination_prev_link");		
			$config['last_link'] 			= 		$this->lang->line("pagination_last_link");		
			$config['first_link'] 			= 		$this->lang->line("pagination_first_link");
			$this->pagination->initialize($config);		
			/*$list_data 						= 		$this->mod_static_pages->get_all_menu(50,$start,$limit);		
			$data['menu_list']			=		$list_data;*/
			
			$list_data_parent 			= 		$this->mod_static_pages->get_all_parent_only(50,$start,$limit);		
			$data['menu_list_parent']			=		$list_data_parent;
			
			
			/*-------------------------------------------------------------
				Page Title showed at the content section of page
			 -------------------------------------------------------------*/
			$data['page_title'] 			= 		$this->lang->line('lang_static_page_menu_title');		
			
			
			/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			$data['page_content']		= 		$this->load->view("menus/menus_list",$data,true);
			
			$this->load->view('page_layout',$data);
	
	}
	
	public function add_menu()
	{
		
		/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link 							= breadcrumb();
			
			$data['breadcrumb'] 	= $link;
			
			/*End of Breadcrumb*/
		
		
			$data['fieldset']					=		$this->lang->line('lang_static_page_feildset');
			//$data['secondary_menu']		=		$this->mod_static_pages->get_menu_items();
			
			$data['menu_name']	=		$this->mod_static_pages->get_menu_name();
			
			/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			$data['page_content']		= 		$this->load->view("menus/menu_add",$data,true);
			
			$this->load->view('page_layout',$data);
		
	}
	
	public function insert_menu()
	{
		
		/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link 							= breadcrumb();
			
			$data['breadcrumb'] 	= $link;
			
			/*End of Breadcrumb*/
			
			$data['fieldset']			=		$this->lang->line('lang_static_page_feildset');
			
			//Settings rules for server-side validation
			$this->form_validation->set_rules('menu_name','Menu Name','required|callback_insert_menu_name_check|min_length[3]|max_length[20]');
			 
			if($this->form_validation->run() == false)
			
				{
					$this->add_menu();
				}
			else
				{
					$menu_name			=		mysql_real_escape_string($this->input->post("menu_name"));
					$parent_menu_id		=		mysql_real_escape_string($this->input->post("parent_menu_id"));
					
					
					if($parent_menu_id=="")
					{
						$this->mod_static_pages->insert_menu($menu_name,'0');
					}
					else
					{
						$this->mod_static_pages->insert_menu($menu_name,$parent_menu_id);
					}
					//Setting flash message after successfull insertion
					$this->session->set_flashdata('menu_insert_message',$this->lang->line('lang_static_page_insert_menu'));
					redirect("admin/menus");
				
				}
			
		
	}
	
	public function edit_menu($id=0)
	{
		
		/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link 					= breadcrumb();
			
			$data['breadcrumb'] 	= $link;
			
			/*End of Breadcrumb*/
			
			$data['record']		=	$this->mod_static_pages->get_menu($id);
			$data['menu']		=	$this->mod_static_pages->get_menus();
			
		/*-------------------------------------------------------------
				Embed current page content into template layout
			 -------------------------------------------------------------*/
			$data['page_content']		= 		$this->load->view("menus/menu_edit",$data,true);
			
			$this->load->view('page_layout',$data);
		
	}
	
	public function update_menu()
	{
		
		/*-------------------------------------------------------------
				Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link 					= breadcrumb();
			
			$data['breadcrumb'] 	= $link;
			
			/*End of Breadcrumb*/
			
			
			$this->form_validation->set_rules('menu_name','Menu Name','required|callback_menu_name_check|min_length[3]|max_length[20]');
			
			if($this->form_validation->run() == false)
			
				{
					$id	=		$this->input->post('menu_id');
					
					$this->edit_menu($id);
				}
			else
				
				{
					$menu_id			=		$this->input->post("menu_id");
					
					$parent_menu		=		mysql_real_escape_string($this->input->post("parent_menu"));
					
					$menu_name			=		mysql_real_escape_string($this->input->post("menu_name"));
					
					if($parent_menu == '')
					{
						$parent		=	$this->mod_static_pages->get_menu($menu_id);
						$parent_menu=	$parent[0]->parent_id;
					}
					
					$data					=		array(		'menu_name'			=>		$menu_name,
																'parent_id'			=>		$parent_menu
															);	
					
					$this->mod_static_pages->update_menu($data,$menu_id);
					
					//Setting flash message after successfull updation
					
					$this->session->set_flashdata('menu_update_message',$this->lang->line('lang_static_page_update_menu'));
					
					redirect("admin/menus");
				}	
	}

	
		public function delete_menu($id=0)
	{
		
		
			
			if($id!=0)
				{
					
					$this->mod_static_pages->delete_menu($id);
					
					//Setting flash message after successfull deletion
					
					$this->session->set_flashdata('menu_delete_message',$this->lang->line('lang_static_page_delete_menu'));
					
					redirect("admin/menus");
					
				}
			else
				{
					
					
					$name=$this->input->post('delete_menu');
					
					$this->mod_static_pages->delete_menu($name);
					
					//Setting flash message after successfull deletion via checkbox
					
					$this->session->set_flashdata('menu_delete_message',$this->lang->line('lang_static_page_delete_menu'));
					
					redirect("admin/menus");
				
				}	
				
		}		
					
					
				
		//CodeIgniter's callback function to check menu name duplication
		public function menu_name_check()
	 	{
			
			$this->db->where('menu_name',$this->input->post('menu_name'));
			
			$this->db->where('id !=',$this->input->post('menu_id'));
			
			$query=$this->db->get('oxm_menu')->num_rows();
			
			if($query==1 )
				{
					$this->form_validation->set_message('menu_name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
				
				return true;
		}
		
		
		//Insert menu name check
		public function insert_menu_name_check()
	 	{
			
			$this->db->where('menu_name',$this->input->post('menu_name'));
			
			$query=$this->db->get('oxm_menu')->num_rows();
			
			
			
			if($query > 0 )
			{
				$this->form_validation->set_message('insert_menu_name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
				
				return FALSE;	
			}
			else
			{	
				return true;
			}
		}
			
		//CLeint side menu name duplication check function
		public function name_check()
	 	{
			
			
			$this->db->where('menu_name',$this->input->post('menu_name'));
			
		 	$query=$this->db->get('oxm_menu')->num_rows();
			
			if($query==0 )
				
				{
					return 'no';	
					exit;
				
				}
				
			else
				{
					return 'yes';
					exit;	
				
			}	
		}							
	
	
	//Codeigniter's callback function to check for invalid characters
	function alphacheck()
		{
				$feild		=		$this->input->post('menu_name');
				if( ! preg_match('/^[a-zA-Z\s]+$/',$feild))
					{
						$this->form_validation->set_message('alphacheck', 'Entered %s must contain only alphabets and spaces');
						return  false;
					}	
						
				else
					{
						return true;
						
					}				
			}
	
	
	/* Checks for page _name duplication in client side */
	 public function page_name_check()
                         
	{
		$query=$this->db->where(array("menu_name" =>$this->input->post('menu')))->like('menuname',$this->input->post('menu'))->get('oxm_menu')->num_rows();
                
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
