<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_websites extends CI_Controller {   
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Libraries */
		$this->load->library("email");
		/* Helpers */
				
		/* Models */
		$this->load->model("mod_websites"); //loc: inventory/models/mod_websites
			
		/* Classes */
    }
	
	/* Inventory Website Landing Page */
	public function index() 
	{ 
		$this->view();		
	}
	
	/* Inventory Website listing Page */
	
	public function view($start =0) 
	{ 
	
		/*-------------------------------------------------------------
			Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link 					= breadcrumb();
		
		$data['breadcrumb'] 	= $link;
		
		/*End of Breadcrumb*/
	
		/*--------------------------------------------------------------
			Pagination  Config Setup
		 ---------------------------------------------------------------*/
		$list_data									=$this->mod_websites->get_site_list(5);	
		/*$limit 										=$this->page_limit;
		$config['per_page'] 						=$limit;
		$config['base_url']		 					=site_url("admin/inventory_websites/view");
		$config['uri_segment'] 						=4;
		$config['total_rows'] 						=count($list_data);
		$config['next_link']						=$this->lang->line("pagination_next_link");
		$config['prev_link']						=$this->lang->line("pagination_prev_link");		
		$config['last_link'] 						=$this->lang->line("pagination_last_link");		
		$config['first_link'] 						=$this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);		*/
		$list_data 									=$this->mod_websites->get_site_list(5);		
		$data['website_list']						=$list_data;	
		
					
		/*-------------------------------------------------------------
			Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 			= 		$this->lang->line('label_inventory_websites_page_title');		
		
		/*-------------------------------------------------------------
			Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']		= 		$this->load->view("websites/websites_list",$data,true);
		
		$this->load->view('page_layout',$data);
	}
		
		
		public function add_site($start=0) 
			{ 
	
				/*-------------------------------------------------------------
					Breadcrumb Setup Start
				 -------------------------------------------------------------*/
				$link 							= 	breadcrumb();
				
				$data['breadcrumb'] 			= 	$link;
				
				/*End Of BreadCrumb*/
				$data['country']				=	$this->mod_websites->list_country();
				
				$data['start']					=	$start;					
							
				 /*-------------------------------------------------------------
				Get Categories List
				-------------------------------------------------------------*/
				$category_list = $this->mod_websites->getCategory();
				$data['category_list'] = $category_list;
				
				/*-------------------------------------------------------------
						Embed current page content into template layout
				 -------------------------------------------------------------*/
					$data['page_content']		=	 $this->load->view("websites/add_website",$data,true);
					
					$this->load->view('page_layout',$data);
					
			}
	
		public function edit_site($site_id) 
			{ 
			
					/*-------------------------------------------------------------
						Breadcrumb Setup Start
					 -------------------------------------------------------------*/
					$link 						= 		breadcrumb();
					
					$data['breadcrumb'] 		= 		$link;
					
					/*End of Breadcrumb*/
					$data['record']				=		$this->mod_websites->get_site($site_id);
					
					$data['country']			=		$this->mod_websites->list_country();
					
					 /*-------------------------------------------------------------
					Get Categories List
					-------------------------------------------------------------*/
					$category_list = $this->mod_websites->getCategory();
					$data['category_list'] = $category_list;
				
					/*-------------------------------------------------------------
						Embed current page content into template layout
					 -------------------------------------------------------------*/
					 
					$data['page_content']		= 		$this->load->view("websites/edit_website",$data,true);
					$this->load->view('page_layout',$data);	
			}
		
		public function insert_site()
		{
	
					$cur_date=date("Y-m-d H:i:s");
					/*-------------------------------------------------------------
						Breadcrumb Setup Start
					 -------------------------------------------------------------*/
					$link 					= breadcrumb();
					
					$data['breadcrumb'] 	= $link;
					
					/*End of Breadcrumb*/
					
					//Setting  rules for server side  validation						
					$this->form_validation->set_rules('website_url', 'website_url', 'trim|required|is_unique[oxm_userdetails.websitename]|valid_url');
					$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[oxm_userdetails.email]');
					$this->form_validation->set_rules('username', 'User Name','trim|required|max_length[20]|is_unique[oxm_userdetails.username]|is_unique[ox_users.username]|callback_name_valid|min_length[3]');
					$this->form_validation->set_rules('password', 'Password', 'required');
					$this->form_validation->set_rules('confirmpwd', 'Confirm password', 'required|matches[password]');
					$this->form_validation->set_rules('publisher_share', 'Publisher_Share', 'required|callback_numeric_check');
					//$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('address', 'Address', 'trim|required|callback_address_check');
					$this->form_validation->set_rules('city', 'City', 'trim|required|callback_alpha_check');
					$this->form_validation->set_rules('state', 'State', 'trim|required|callback_state_check');
					$this->form_validation->set_rules('country', 'Country', 'required');		
					$this->form_validation->set_rules('mobileno', 'Mobile number', 'required|callback_mobile_no_check');
					$this->form_validation->set_rules('zipcode', 'Zip Code', 'required|callback_zip_code_check');
		
					if($this->form_validation->run() == FALSE)
							{	
								
								$this->session->set_userdata('notification_message', ''.$this->lang->line("label_website_insertion_failed_msg").'');
								
								$this->add_site();
							}
				else
							{					
				 /*Getting data's from user */
		  						$name			=mysql_real_escape_string($this->input->post('username'));
								$website_url	=mysql_real_escape_string($this->input->post('website_url'));
								$email			=mysql_real_escape_string($this->input->post('email'));
								$password		=mysql_real_escape_string($this->input->post('password'));
								$cfmpassword	=mysql_real_escape_string($this->input->post('confirmpwd'));
								$publisher_share=$this->input->post('publisher_share');
								$category		=$this->input->post('category');
								$address		=mysql_real_escape_string(nl2br(($this->input->post('address'))));
								$city			=mysql_real_escape_string($this->input->post('city'));
								$state			=mysql_real_escape_string($this->input->post('state'));
								$country		=$this->input->post('country');
								$mobileno		=$this->input->post('mobileno');
								$zipcode		=$this->input->post('zipcode');
								$account_type	="TRAFFICKER";
								$date			=date("Y-m-d H:i:s");
								$a				=1;
										
					/*Getting account_id from ox_accounts table*/
						
								$data['lastid']	=	$this->mod_websites->insert_accounts($account_type,$name);
						
								$lastid	=	$data['lastid'];
						
					
						/*Gathering data's for oxm_userdetails table*/
						
								$record_array 	=	array(
														
															'accountid'			=>	$lastid,
															'username'			=>	$name,
															'websitename'		=>	$website_url,
															'email'				=>	$email,
															'password'			=>	md5($password),
															'address'			=>	$address,
															'city'				=>	$city,
															'state'				=>	$state,
															'country'			=>	$country,
															'mobileno'			=>	$mobileno,
															'postcode'			=>	$zipcode,
															'accounttype'		=>	$account_type,
															);
						
								$this->mod_websites->insert_record($record_array);
						
						
						/*Gathering data's for ox_affiliates table*/
						
								$affiliate_record_array	=array(
																'name'				=>	$website_url,
																'contact'			=>	$name,
																'email'				=>	$email,
																'agencyid'			=>	$a,
																'updated'			=>	$date,
																'account_id'		=>	$lastid,
																'publishershare'	=>	$publisher_share,
																'oac_category_id'	=> @implode(",", $category)
															  );
						
								$this->mod_websites->insert_affiliate_record($affiliate_record_array);	
						
						
						/*Gathering data's for ox_users table*/
						
								$users_array	=	array(
															
																	'contact_name'			=>	$name,
																	'email_address'			=>	$email,
																	'username'					=>	$name,
																	'password'					=>	md5($password),
																	'default_account_id'		=>	$lastid,
																	'date_created'	=>	$cur_date
															);
						
								$user_lastid=$this->mod_websites->insert_users_record($users_array);	
						
						
						/*Gathering data's for ox_account_user_assoc table*/
						
								$assoc_record_array		=	array(
																		
																		'account_id'	=>	$lastid,
																		'user_id'			=>	$user_lastid,
																		'linked'			=>	$date
																		
																		);
						
								$this->mod_websites->insert_assoc_record($assoc_record_array);	
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=10 */
						
								$permission_record_array=array(
																		
																		'account_id'		=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'10'
																	
																		);
						
								$this->mod_websites->insert_permission_record($permission_record_array);
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=7 */
						
								$permission_record_array=array(
																		
																		'account_id'		=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'7'
																		
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);		
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=5 */
						
								$permission_record_array=array(
																	   
																	   'account_id'			=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'5'
																	
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);	
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=6 */
						
								$permission_record_array=array(
																		
																		'account_id'		=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'6'
																		
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=9 */
						
								$permission_record_array=array(
								
																		'account_id'		=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'9'
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);	
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=8 */
						
								$permission_record_array=array(
																		
																		'account_id'			=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'8'
																	
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);	
						
						
						/*Gathering data's for ox_account_user_permission_assoc table with permission_id=11 */
						
								$permission_record_array=array(
								
																		'account_id'		=>	$lastid,
																		'user_id'				=>	$user_lastid,
																		'permission_id'		=>	'11'
																			
																			);
						
								$this->mod_websites->insert_permission_record($permission_record_array);	
						
			/*-----------------------------------------------------------------------
			Send Notification mail to registered Publisher
			------------------------------------------------------------------------*/
	
							$publisher_email_id			=$this->input->post("email");
							$admin_email				=$this->mod_websites->get_admin_email();
							$subject					=$this->lang->line('lang_add_website_subject');
							$email_data					=array(
																"websitename"	=>$this->input->post("website_url"),
																"username"		=>$this->input->post("username"),
																"password"		=>$this->input->post("password")
															  );
													
							$message						=$this->load->view('email/Administrator/add_website',$email_data,TRUE);
							$config['mailtype'] 			='html';
							$config['charset'] 				='UTF-8';	
							$this->email->initialize($config);
							$this->email->from($admin_email);
							$this->email->to($publisher_email_id);        
							$this->email->subject($subject);        
							$this->email->message($message);
							$this->email->send();
	
					
							/*----------End of Email Configuration and Sending Process---------------*/
								//Setting flash data message after successfull insertion  and redirecting to website_list page
								$this->session->set_flashdata('site_success_message',$this->lang->line('inventory_website_add_success_msg'));
								
								redirect("admin/inventory_websites");
					}	
			
		
}
	  
	   public function delete_site($site_id=false,$account_id=false)
	 	{
		
		//Normal delete operation 
			if($site_id!=false && $account_id!=false)
				 	{
						
						$aff_id=$this->mod_websites->get_affiliateid($account_id);
						
						$zoneid=$this->mod_websites->get_zone_id($aff_id);
						
						if(is_array($zoneid) && count($zoneid) > 0)
							{
								foreach($zoneid as $zone_id)
									{
									$id	=	$zone_id->zoneid;
									$this->mod_websites->delete_related_bucket_tables($id);
									
									}
							$this->mod_websites->delete_zones($aff_id);
								
							}
						
						  
						$this->mod_websites->delete_assoc_tables($account_id);
						$this->mod_websites->delete_site($site_id);
						
						$this->mod_websites->delete_ox_users($account_id);
						
						$this->session->set_flashdata('site_delete_message', $this->lang->line('inventory_website_delete_msg'));
						 
						redirect("admin/inventory_websites");
						} 
                	
               
						
				else
						{	
						
						//Getting account_id from check box 
						$test=$this->input->post('delete_site');
						
						$this->mod_websites->delete_publisher_site($test);
						
						//Setting flash data message after successfull delete operation and redirecting to website_list page
						$this->session->set_flashdata('site_delete_message', $this->lang->line('inventory_website_delete_msg'));
						
						redirect("admin/inventory_websites");
							
							}
						
	 }	
	 
	 public function update_site()
	  {
	  	
			/*Setting rules for server side validation*/
			   
			$upd_id=$this->input->post('account_id');
			$this->form_validation->set_rules('website_url', 'website url', 'trim|required|callback_site_check|valid_url');
			$this->form_validation->set_rules('publisher_share', 'Publisher_Share', 'required|callback_numeric_check');
			//$this->form_validation->set_rules('category', 'Category', 'required');	
			$this->form_validation->set_rules('address', 'Address', 'trim|required|callback_address_check');
			$this->form_validation->set_rules('city', 'City', 'trim|required|callback_alpha_check');
			$this->form_validation->set_rules('state', 'State', 'trim|required|callback_state_check');
			$this->form_validation->set_rules('country', 'Country', 'required');		
			$this->form_validation->set_rules('mobileno', 'Mobile number', 'required|callback_mobile_no_check');
			$this->form_validation->set_rules('zipcode', 'Zip Code', 'required|callback_zip_code_check');
								
			if($this->form_validation->run() == FALSE)
			{
				//Setting flash data message after unsuccessfull update operation and redirecting it back to edit_website page
				$this->session->set_userdata('notification_message', ''.$this->lang->line("label_website_update_failed_msg").'');
				
				$id		=		$this->input->post('update_id');
				$this->edit_site($id);
			}
			else
			{
				 /*Getting data's from edit website page upd = updated   */
				$account_type			="TRAFFICKER";
				$upd_date				=date("Y-m-d H:i:s");
				$a						=1;
				$upd_website_url		=mysql_real_escape_string($this->input->post('website_url'));
				$upd_publisher_share	=$this->input->post('publisher_share');
				$category				=$this->input->post('category');
				$upd_address			=mysql_real_escape_string(nl2br($this->input->post('address')));
				$upd_city				=mysql_real_escape_string($this->input->post('city'));
				$upd_state				=mysql_real_escape_string($this->input->post('state'));
				$upd_country			=$this->input->post('country');
				$upd_mobileno			=$this->input->post('mobileno');
				$upd_zipcode			=$this->input->post('zipcode');
				$id						=$this->input->post("update_id");
				$account_id				=$this->input->post("account_id");
		
				/* Gathering updated data's from user for oxm_userdetails table */
		
				$record_array	=	array(	
											'websitename'	=>$upd_website_url,
											'address'		=>$upd_address,
											'city'			=>$upd_city,
											'state' 		=>$upd_state,
											'country'		=>$upd_country,
											'mobileno'		=>$upd_mobileno,
											'postcode'	 	=>$upd_zipcode
										 );
		
				$this->mod_websites->update_userdetails_record($id,$record_array);
		
		
		/*Gathering updated data for ox_affiliates table*/
		
				$affiliate_record_array	=	array(
													'name'					=>$upd_website_url,
													'updated'				=>$upd_date,
													'publishershare'		=>$upd_publisher_share,
													'oac_category_id'		=>@implode(",", $category)
												 );
		
				$this->mod_websites->update_affiliate_record($account_id,$affiliate_record_array);	
				
				//Setting flash data message after successfull update operation and redirecting to website_list page
				$this->session->set_flashdata('site_update_message',$this->lang->line('inventory_website_update_msg') );
				redirect("admin/inventory_websites");
			}
		
		}
		
		
		/*-------------------------------------------------------------------------------
	 			STARTING OF CLIENT-SIDE VALIDATION FUNCTION TO CHECK FOR DUPLICATION
	    ---------------------------------------------------------------------------------*/
			
		public function username_check()
				{
					$accounttype	=	"TRAFFICKER"; 
					
					$query			=	$this->db->where(array("username" =>$this->input->post('username'),"accounttype"=>$accounttype))->get('oxm_userdetails')->num_rows();
					
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
		
		public function user_check()
				{
					$query		=	$this->db->where(array("username" =>$this->input->post('username')))->get('ox_users')->num_rows();
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
		
		public function website_check()
			 {
		 
		 		$accounttype		=	"TRAFFICKER"; 
				
				$query				=	$this->db->where(array("websitename" =>$this->input->post('website_url'),"accounttype"=>$accounttype))->get('oxm_userdetails')->num_rows();
			
				if($query==0 )
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
					
		public function email_check()
			 {
				
				$accounttype		=	"TRAFFICKER"; 
				
		 		$query				=	$this->db->where(array("email" =>$this->input->post('email'),"accounttype"=>$accounttype))->get('oxm_userdetails')->num_rows();
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
		 
		public function edit_site_check()
	 		{
			
			$accounttype		=	"TRAFFICKER";
			
			$this->db->where('websitename',$this->input->post('website_url'));
			
			$this->db->where('accountid !=',$this->input->post('account_id'));
			
			$this->db->where('accounttype',$accounttype);
			
			$query=$this->db->get('oxm_userdetails')->num_rows();
			
			if($query==1 )
				{
					echo 'no';
					return FALSE;	
				}
			else
				{	
					echo 'yes';
					return true;
				}
			}
			
			/*-------------------------------------------------------------------------------
	 			ENDING OF CLIENT-SIDE VALIDATION FUNCTION TO CHECK FOR DUPLICATION
	    ---------------------------------------------------------------------------------*/
			
			
			
			
			
		/*-------------------------------------------------------------------------------
	 				STARTING OF CODEIGNITER'S CALLBACK FUNCTION 
	 ---------------------------------------------------------------------------------*/
		
		public function site_check()
	 	{
			
			$accounttype		=		"TRAFFICKER"; 
			
			$this->db->where('websitename',$this->input->post('website_url'));
			
			$this->db->where('accountid !=',$this->input->post('account_id'));
			
			$this->db->where('accounttype',$accounttype);
			
			$query=$this->db->get('oxm_userdetails')->num_rows();
			
			if($query==1 )
				{
					$this->form_validation->set_message('site_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
					return FALSE;	
				}
			else
					return true;
		}	
						
	
	function alpha_check()
		{
				$feild		=		$this->input->post('city');
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
			
		function name_valid()
		{
				$feild		=		$this->input->post('username');
				if( ! preg_match('/^[0-9a-zA-Z\s\_]+$/',$feild))
					{
						$this->form_validation->set_message('name_valid', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}
			}		
			
		function state_check()
		{
				$feild		=		$this->input->post('state');
				if( ! preg_match('/^[a-zA-Z\s]+$/',$feild))
					{
						$this->form_validation->set_message('state_check', $this->lang->line('label_entered').'%s'. $this->lang->line('label_must_contain_only_alpha_and_spaces'));
						return  false;
					}	
				else
					{
						return true;
					}
			}			
					
		function numeric_check()
		{
				$feild		=		$this->input->post('publisher_share');
				if( ! preg_match('/^[0-9\.]+$/',$feild))
					{
						$this->form_validation->set_message('numeric_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}					
	}
	
		function zip_code_check()
		{
				$feild		=		$this->input->post('zipcode');
				if( ! preg_match('/^[0-9\-]+$/',$feild))
					{
						$this->form_validation->set_message('zip_code_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}					
		}
	
		function mobile_no_check()
		{
				$feild		=		$this->input->post('mobileno');
				if( ! preg_match('/^[0-9\-]+$/',$feild))
					{
						$this->form_validation->set_message('mobile_no_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}					
		}
		
		function address_check()
		{
				$feild		=		$this->input->post('address');
				if( ! preg_match('/^[0-9A-Za-z\-\#\s\.\,]+$/',$feild))
					{
						$this->form_validation->set_message('address_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_contains_invalid_characters'));
						return  false;
					}	
				else
					{
						return true;
					}					
		}	
		

		/*-------------------------------------------------------------------------------
	 				ENDING OF CODEIGNITER'S CALLBACK FUNCTION 
	 ---------------------------------------------------------------------------------*/
		

}

/* End of file websites.php */
/* Location: ./modules/admin/controllers/inventory_websites.php */
