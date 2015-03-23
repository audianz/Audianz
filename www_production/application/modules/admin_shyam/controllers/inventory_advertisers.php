<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Inventory_advertisers extends CI_Controller {          
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 10;
    	var $status     = "all";
	
	public function __construct() 
    {
		parent::__construct();
		
		/* Libraries */
		$this->load->library('email');
		
		/* Helpers */
		
		
		/* Models */
		$this->load->model("mod_advertiser"); //loc: inventory/models/mod_advertisers
		
		
		/* Classes */
    }
	
	/* Inventory Advertiser Landing Page */
	public function index() 
	{ 
		$this->adlist();
	}

    public function active($start=0){
		$this->status = "active";
		$this->adlist($start);
    }
	public function inactive($start=0){
		$this->status = "inactive";
		//$stat=1;
		$this->adlist($start);
    }
	
	/* Inventory Advertisers listing Page */
	public function adlist($start=0)
	{ 
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		//$list_data = $this->mod_advertiser->get_advertisers($this->status);
		/*$limit = $this->page_limit;
		$config['per_page'] = $limit;
		if($this->status=="all"){
			$config['base_url'] = site_url("admin/inventory_advertisers/adlist");
		}
		else
		{
			$config['base_url'] = site_url("admin/inventory_advertisers/active");
		}
		$config['uri_segment'] 	= 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);		*/
		$list_data = $this->mod_advertiser->get_advertisers($this->status);
		$data['advertiser_list']	=	$list_data;
		//echo "<pre>"; print_r($list_data); exit;
							
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_page_title');		
		
		
		$data['all_records']		= $this->mod_advertiser->get_num_advertisers();
		$data['active_records']		= $this->mod_advertiser->get_num_active_advertisers();
		$data['inactive_records']	= $this->mod_advertiser->get_num_inactive_advertisers();
		//$data['inactive_records']	= $data['all_records']-$data['active_records'];
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		-------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("advertiser/list",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/***********************************************************************************
	 *		Method Name	: add_fund
	 *		Param		: advertiser_id (numeric)
	 *		Description	: To Open Add fund process UI (Form) page		
	 *	
	 ***********************************************************************************/
	
	public function add_fund($advertiser_id){
		
		if(isset($advertiser_id) AND $advertiser_id > 0){
			
			
			
			$data['sel_advertiser']		= 	$advertiser_id;
			
			/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 	-------------------------------------------------------------*/
			
			if($this->uri->segment(3) != "process"){
					$link = breadcrumb();
					$data['breadcrumb'] 	= $link;
			}
			else
			{	
					$data['breadcrumb'] 	= "";
			}
			
		
			
			/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 	-------------------------------------------------------------*/
			$data['page_content']	= $this->load->view("advertiser/add_fund",$data,true);;
			$this->load->view('page_layout',$data);
			
		}
		else
		{
			redirect("admin/inventory_advertisers");
		}
				
	}
	
	/************************************************************************************
	 *		Method Name	: add_advertiser
	 *		Param		: -
	 *		Description	: To Open UI page for new advertiser account creation
	 *	
	 ***********************************************************************************/	
	
   public function add_advertiser(){


        /*-------------------------------------------------------------
		 	Page Title showed at the content section of page
	     -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_add_form_page_title');

        /*-------------------------------------------------------------
		 	Breadcrumb Setup Start
         --------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;


        $data['country'] = $this->mod_advertiser->list_country();

         /*-------------------------------------------------------------
	 		Embed current page content into template layout
         --------------------------------------------------------------*/
		$data['page_content']	=  $this->load->view("advertiser/add",$data,true);
		$this->load->view('page_layout',$data);
  }
  
  	/***********************************************************************************
	 *		Method Name	: edit
	 *		Param		: Adevrtiser Account ID (numeric)
	 *		Description	: To Open UI page for advertiser detail edit page		
	 *	
	 ***********************************************************************************/

  public function edit($advertiser_acc_id=false){
      if($advertiser_acc_id != false){

			  /*-------------------------------------------------------------
					Page Title showed at the content section of page
			  -------------------------------------------------------------*/
				$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_edit_form_page_title');
		
			   /*-------------------------------------------------------------
					Breadcrumb Setup Start
			   --------------------------------------------------------------*/
				$link = breadcrumb();
				$data['breadcrumb'] 	= $link;
		
				/*--------------------------------------------------------------
						 *  GET Advertiser details from DB based on selected Account ID
				 *-------------------------------------------------------------*/
		
						$data['advt_account_id'] = $advertiser_acc_id;
						$data['advertiser_data'] = $this->mod_advertiser->get_advertiser_details($advertiser_acc_id);
		
		
						$data['country'] = $this->mod_advertiser->list_country();
		
			   /*-------------------------------------------------------------
						Embed current page content into template layout
				--------------------------------------------------------------*/
				$data['page_content']	=  $this->load->view("advertiser/edit",$data,true);
				$this->load->view('page_layout',$data);
            }
            else
            {
                redirect("admin/inventory_advertisers");
            }
     }
	
	/**************************************************************************************************************
	 *		Method Name	: process
	 *		Param		: Module Name (String), Task Name (STRING), REFERENCE ID (numeric) [optional]
	 *		Description	: This method has been used to manage all the background process related to advertiser like
	 *					  Add, Edit	and delete records from DB and Add balance to advertiser account.
	 *	
	 *************************************************************************************************************/
	
    public function process($mod,$task,$refid=false){
         if($mod != '' AND $task != ''){
                switch(strtoupper($mod)){
                    case "ADVERTISER":
                            if($task=="add"){
							
                                $this->form_validation->set_rules('name', 'Name', 'required');
								$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
								$this->form_validation->set_rules('username', 'User Name', 'required');
								$this->form_validation->set_rules('password', 'Password', 'required');
								$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|matches[password]');
								$this->form_validation->set_rules('address', 'Address', 'required');	
								$this->form_validation->set_rules('city', 'City Name', 'required|alpha_dash_space_quotes');
								$this->form_validation->set_rules('state', 'State Name', 'required|alpha_dash_space_quotes');
								$this->form_validation->set_rules('country', 'Country Name', 'required');		
								$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|max_length[15]');
								$this->form_validation->set_rules('zip_code', 'Zip Code', 'required|numeric');							
								
								if($this->form_validation->run() == FALSE){
									$this->add_advertiser();
								}
								else
								{
									
										$inputData = array(
													"name"	=>$this->input->post("name"),
													"email"	=>$this->input->post("email"),
													"username"=>$this->input->post("username"),
													"password"=>$this->input->post("password"),
													"account"=>"ADVERTISER",
													"address"=>$this->input->post("address"),
													"city"=>$this->input->post("city"),
													"state"=>$this->input->post("state"),
													"country"=>$this->input->post("country"),
													"mobile"=>$this->input->post("mobile"),
													"zip_code"=>$this->input->post("zip_code")
												   );
												   
									  $this->mod_advertiser->add_advertiser($inputData);
									  
									  /*-----------------------------------------------------------------------
												SEND INVITATION EMAIL TO REGISTRED ADVERTISER
										------------------------------------------------------------------------*/
		
											$advertiser_email_id	=	$this->input->post("email");
											$admin_email			=	$this->mod_advertiser->get_admin_email();
											$subject				=	$this->lang->line('lang_add_advertiser_subject');
											$email_data				= 	array(
																			"advertiser_name"	=>	$this->input->post("name"),
																			"username"			=>	$this->input->post("username"),
																			"password"			=>	$this->input->post("password")
																		);
																	
										
											 $content				= $this->load->view('email/Administrator/add_advertiser',$email_data,TRUE);
											 $data['content']		=$content;
											 $mail_content			=$this->load->view('email/login/email_tpl', $data, TRUE);
											 $message              =$mail_content;
											 $config['protocol']   ="sendmail";
											 $config['wordwrap']   =TRUE;		
											 $config['mailtype'] 	='html';
											 $config['charset']	='UTF-8'; 
											$this->email->initialize($config);
											$this->email->from($admin_email);
											$this->email->to($advertiser_email_id);        
											$this->email->subject($subject);        
											$this->email->message($message);
											$this->email->send();
		
		
										/*----------End of Email Configuration and Sending Process---------------*/
									  
									  $this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_add_success'));
        							  redirect("admin/inventory_advertisers");
								}
				            }
							else if($task=="edit" AND $this->input->post('advertiser_account_id') != ''){
								$this->form_validation->set_rules('name', 'Name', 'required');
								$this->form_validation->set_rules('address', 'Address', 'required');	
								$this->form_validation->set_rules('city', 'City Name', 'required|alpha_dash_space_quotes');
								$this->form_validation->set_rules('state', 'State Name', 'required|alpha_dash_space_quotes');
								$this->form_validation->set_rules('country', 'Country Name', 'required');		
								$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|max_length[15]');
								$this->form_validation->set_rules('zip_code', 'Zip Code', 'required|numeric');							
								
								if($this->form_validation->run() == FALSE){
									$this->edit($this->input->post("advertiser_account_id"));
								}
								else
								{
									
										$inputData = array(
													"name"	=>$this->input->post("name"),
													"address"=>$this->input->post("address"),
													"city"=>$this->input->post("city"),
													"state"=>$this->input->post("state"),
													"country"=>$this->input->post("country"),
													"mobile"=>$this->input->post("mobile"),
													"zip_code"=>$this->input->post("zip_code")
												   );
												   
									  $this->mod_advertiser->edit_advertiser($inputData,$this->input->post("advertiser_account_id"));
									  $this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_edit_success'));


									  	/*-----------------------------------------------------------------------
												SEND INVITATION EMAIL TO REGISTRED ADVERTISER
										------------------------------------------------------------------------*/
		
											$advertiser_email_id	=	$this->input->post("email");

											
											
											$admin_email			=	$this->mod_advertiser->get_admin_email();
											$subject				=	$this->lang->line('lang_add_advertiser_subject');
											$email_data				= 	array(
																			"name"	=>$this->input->post("name"),
																			"address"=>$this->input->post("address"),
																			"city"=>$this->input->post("city"),
																			"state"=>$this->input->post("state"),
																			"country"=>$this->input->post("country"),
																			"mobile"=>$this->input->post("mobile"),
																			"zip_code"=>$this->input->post("zip_code")
																		);
																	
										
											 $content				= $this->load->view('email/Administrator/edit_advertiser',$email_data,TRUE);
											 $data['content']		=$content;
											 $mail_content			=$this->load->view('email/login/email_tpl', $data, TRUE);
											 $message              =$mail_content;

											 $config['protocol']   ="sendmail";
											 $config['wordwrap']   =TRUE;		
											 $config['mailtype'] 	='html';
											 $config['charset']	='UTF-8'; 
											$this->email->initialize($config);
											$this->email->from($admin_email);
											$this->email->to($advertiser_email_id);        
											$this->email->subject($subject);        
											$this->email->message($message);
											$this->email->send();

		                               $this->session->set_flashdata('success_message', 'Advertiser has been updated Successfully');
									  
        							  redirect("admin/inventory_advertisers");


								}
							}
							else if($task="rem"){
									if($refid != false){
										$this->mod_advertiser->del_advertiser($refid);
										$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_single_delete_success'));
										redirect('admin/inventory_advertisers');
									}
									else
									{
										$this->mod_advertiser->del_advertiser($this->input->post('sel_advertiser'));
										$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_multiple_delete_success'));
		 								redirect('admin/inventory_advertisers');
									}	
							}
				    break;
					
				case "FUND":
					if($task=="add"){
						$this->form_validation->set_rules('amount', 'Amount', 'greater_than[1]');
												
						
						if($this->form_validation->run() == FALSE){
							$this->session->set_userdata('error_message',$this->lang->line('label_enter_valid_amount').' (or) Please enter the amount greater than 1');
							$this->add_fund($this->input->post('sel_advertiser'));
						}
						else
						{
							$advertiser_id	=	$this->input->post('sel_advertiser');
							$fund			=	$this->input->post('amount');
							$date			=	date("Y-m-d");
							
							/*------------------------------------------------------------------------
									GET PREVIOUS FUND VALUE BASED ON SELECTED ADVERTISER
							--------------------------------------------------------------------------*/
							$existing_fund	=	$this->mod_advertiser->getFund($advertiser_id);
							
							$current_value	=($existing_fund + $fund);
						
							if($existing_fund != FALSE)
							{
								$this->mod_advertiser->update_fund($advertiser_id,$current_value);
							}
							else
							{
								$this->mod_advertiser->insert_fund($advertiser_id,$current_value);
							}
							
							$this->mod_advertiser->insert_paypal_fund($advertiser_id,$fund);
							
		
							$get_advertiser_det=$this->mod_advertiser->get_advertiser_det($advertiser_id);
							if($get_advertiser_det != false)
							{
								$advertiseremail =$get_advertiser_det[0] -> email;
								$advertiername=$get_advertiser_det[0] ->clientname;
							}	
							
							$admin_details			= $this->mod_advertiser->get_admin_details();
							if($admin_details!=false)					
							{
								$site_title = $admin_details->site_title;
							}
								
		
							/************************************************************
							 * 
							 * SEND EMAIL NOTIFICATION TO ADVERTISER ON ADDING FUND
							 * 
							 * ********************************************************/
							
							$email_data				= 	array(
																			"fund"	=>	$this->input->post("amount"),
																			"date"			=>	date("Y-m-d"),
																			"existing_fund"			=>$existing_fund	,
																			"current_value"		=> $current_value,
																			"advertiseremail"   =>$advertiseremail ,
																		"advertiername"    => $advertiername,
																		"site_title"		=>  $site_title
																		);
								$admin_email			=	$this->mod_advertiser->get_admin_email();
							
							 $subject				=	lang('lang_add_fund_subject').$site_title;			
							 $content				= $this->load->view('email/Administrator/add_fund',$email_data,TRUE);
			  				 $data['content']		=$content;
			  				 $mail_content			=$this->load->view('email/login/email_tpl', $data, TRUE);
							 $message              =$mail_content;
							 $config['protocol']   ="sendmail";
              				 $config['wordwrap']   =TRUE;		
			  				 $config['mailtype'] 	='html';
			  				 $config['charset']	='UTF-8'; 
							 $this->email->initialize($config);
							 $this->email->from($admin_email);
							 $this->email->to($advertiseremail); 
							 $this->email->bcc($admin_email);       
							 $this->email->subject($subject);        
							 $this->email->message($message);
							 $this->email->send();
							
							
							
							$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_add_fund_success').'<b>'.$advertiername.'('.$advertiseremail.')</b>');
							redirect("admin/inventory_advertisers");
						}
					}
				break;	
					
                }
            }
        }
		
		/***********************************************************************************
		 *		Method Name	: trackers
		 *		Param		: STATUS (String), ADVERTISER ID (numeric), START VALUE (numeric) 
		 *		Description	: To Open UI page for TRACKERS listing page		
		 *	
		 ***********************************************************************************/
		
		public function trackers($status="all",$advertiser_id=false,$start=0){
			if($advertiser_id != false){
				
				$data['sel_advertiser_id']		=	$advertiser_id;
				$data['sel_status']				=	$status;
				/*--------------------------------------------------------------
				  	GET NUMBER RECORDS BASED ON THEIR STATUS
				  ---------------------------------------------------------------*/
				
				$data['num_rec'] = $this->mod_advertiser->get_trackers_count($advertiser_id);
					
				/*-------------------------------------------------------------
					Page Title showed at the content section of page
			  	-------------------------------------------------------------*/
				$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_trackers_page_title');
		
			   /*-------------------------------------------------------------
					Breadcrumb Setup Start
			   --------------------------------------------------------------*/
				$link = breadcrumb();
				$data['breadcrumb'] 	= $link;
		
				/*--------------------------------------------------------------------
						 *  GET Trackers list from DB based on selected Advertiser
				 *--------------------------------------------------------------------*/
				
				$data['trackers_list']		=	$this->mod_advertiser->get_trackers_list($advertiser_id,$status);
				
				
				/*--------------------------------------------------------------
					Pagination  Config Setup
				 ---------------------------------------------------------------*/
				
				$limit = $this->page_limit;
				$config['base_url']	= site_url("admin/inventory_advertisers/trackers/$status/$advertiser_id");
				$config['per_page'] = $limit;
				$config['uri_segment'] 	= 6;
				$config['total_rows'] 	= count($data['trackers_list']);//'5';
				$config['next_link'] 	= $this->lang->line("pagination_next_link");
				$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
				$config['last_link'] 	= $this->lang->line("pagination_last_link");		
				$config['first_link'] 	= $this->lang->line("pagination_first_link");
				$this->pagination->initialize($config);		
				
				if($config['total_rows'] > $limit){
					$data['trackers_list'] = $this->mod_advertiser->get_trackers_list($advertiser_id,$status,$start,$limit);
				}
				
				$data['trackers_list']	=	$data['trackers_list'];
				
				
				
			   /*-------------------------------------------------------------
						Embed current page content into template layout
				--------------------------------------------------------------*/
				$data['page_content']	=  $this->load->view("advertiser/trackers_list",$data,true);
				$this->load->view('page_layout',$data);

				
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		
		/***********************************************************************************
		 *		Method Name	: add_trackers
		 *		Param		: ADVERTISER ID (numeric)
		 *		Description	: To Open UI page for ADD TRACKER  page		
		 *	
		 ***********************************************************************************/
		
		public function add_trackers($advertiser_id){
			if($advertiser_id != false){
				
				$data['sel_advertiser']		= 	$advertiser_id;
				
				/*-------------------------------------------------------------
				Breadcrumb Setup Start
				-------------------------------------------------------------*/
			
				
				$data['breadcrumb'] 	= '<a href="'.site_url('admin').'">'.$this->lang->line('label_dashboard').'</a>
										   <a href="'.site_url('admin/inventory_advertisers').'">'.$this->lang->line('label_advertisers').'</a>
										   <a href="'.site_url('admin/inventory_advertisers/trackers/all/'.$advertiser_id.'').'">'.$this->lang->line('label_trackers').'</a>
										   <span>'.$this->lang->line('label_add_tracker').'</span>';
				
				
				$data['tracker_status'] = $this->mod_advertiser->list_tracker_status();
				$data['tracker_type'] 	= $this->mod_advertiser->list_tracker_type();
			
				
				/*-------------------------------------------------------------
				Embed current page content into template layout
				-------------------------------------------------------------*/
				$data['page_content']	= $this->load->view("advertiser/add_tracker",$data,true);;
				$this->load->view('page_layout',$data);
				
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		
		/***********************************************************************************
		 *		Method Name	: edit_trackers
		 *		Param		: STATUS (String), TRACKER ID (numeric)
		 *		Description	: To Open UI page for TRACKER detail esit page		
		 *	
		 ***********************************************************************************/
		
		public function edit_tracker($status,$tracker_id){
			if($tracker_id != false){
				
				$data['sel_tracker_id']		= 	$tracker_id;
				
				$tempObj		= 	$this->mod_advertiser->get_tracker_det($tracker_id);
				
				if($tempObj != FALSE){
					$data['tracker_det'] = $tempObj[0];
				}
				/*-------------------------------------------------------------
				Breadcrumb Setup Start
				-------------------------------------------------------------*/
				
				$data['breadcrumb'] 	= '<a href="'.site_url('admin').'">'.$this->lang->line('label_dashboard').'</a>
										   <a href="'.site_url('admin/inventory_advertisers').'">'.$this->lang->line('label_advertisers').'</a>
										   <a href="'.site_url('admin/inventory_advertisers/trackers/'.$status.'/'.$tempObj[0]->clientid.'').'">'.$this->lang->line('label_trackers').'</a>
										   <span>'.$this->lang->line('label_edit_tracker').'</span>';
				
				$data['tracker_status'] = $this->mod_advertiser->list_tracker_status();
				$data['tracker_type'] 	= $this->mod_advertiser->list_tracker_type();
			
				
				/*-------------------------------------------------------------
				Embed current page content into template layout
				-------------------------------------------------------------*/
				$data['page_content']	= $this->load->view("advertiser/edit_tracker",$data,true);;
				$this->load->view('page_layout',$data);
				
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		/*--------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: tracker_process
		 	PARAM		: Task Name (STRING), ADVERTISER ID (numeric), REFERENCE ID (numeric) [optional]
	 	 	DESCRIPTION	: This method has been used to manage all the background process related to trackers like
	 	     			  Add, Edit	and delete records from DB.
		---------------------------------------------------------------------------------------------------------------*/
		
		public function tracker_process($task=false,$adv_id=false,$ref_id=false){
			if($task == false){
				$task	=	$this->input->post("task");
			}
			switch($task){
				case "add":
					$this->form_validation->set_rules('tracker_name', 'Tracker Name', 'required');
					//$this->form_validation->set_rules('description', 'Description', 'required');
					$this->form_validation->set_rules('tracker_status', 'Tracker Status', 'required');
					$this->form_validation->set_rules('conversion_type', 'Conversion Type', 'required');
					$this->form_validation->set_rules('append_code', 'Append Code', 'required');
					if($this->form_validation->run() == FALSE){
						$this->session->set_userdata('error_message',$this->lang->line('label_enter_all_fields'));
						$this->add_trackers($this->input->post('sel_advertiser'));
					}
					else
					{
						$trackerData	=	array(
													"trackername" 	=>	text_db($this->input->post('tracker_name')), 
													"description" 	=>	text_db($this->input->post('description')), 
													"clientid" 		=>	text_db($this->input->post('sel_advertiser')), 
													"status" 		=>	text_db($this->input->post('tracker_status')), 
													"type" 			=>	text_db($this->input->post('conversion_type')), 
													"appendcode" 	=>	text_db($this->input->post('append_code')), 
													"updated" 		=>	date("Y-m-d")
											);
						$tracker_id = $this->mod_advertiser->insert_tracker($trackerData);

						
						/* Add Tracker Append Code */
						$rank	= "1"; 						
						
						$trackerAppend	=	array(
												"tracker_id"		=>	$tracker_id,
												"rank"			=>	$rank,
												"tagcode"		=>	text_db($this->input->post('append_code'))										
											);
											
						$this->mod_advertiser->insert_tracker_append($trackerAppend);
						
						$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_add_tracker_success'));
						redirect("admin/inventory_advertisers/trackers/all/".$this->input->post('sel_advertiser')."");
					}
				break;
				case "edit":
					$this->form_validation->set_rules('tracker_name', 'Tracker Name', 'required');
					//$this->form_validation->set_rules('description', 'Description', 'required');
					$this->form_validation->set_rules('tracker_status', 'Tracker Status', 'required');
					$this->form_validation->set_rules('conversion_type', 'Conversion Type', 'required');
					$this->form_validation->set_rules('append_code', 'Append Code', 'required');
					if($this->form_validation->run() == FALSE){
						$this->session->set_userdata('error_message',$this->lang->line('label_enter_all_fields'));
						$this->add_trackers($this->input->post('sel_advertiser'));
					}
					else
					{
						$trackerData	=	array(
													"trackername" 	=>	text_db($this->input->post('tracker_name')), 
													"description" 	=>	text_db((($this->input->post('description')))), 
													"status" 		=>	text_db($this->input->post('tracker_status')), 
													"type" 			=>	text_db($this->input->post('conversion_type')), 
													"appendcode" 	=>	text_db($this->input->post('append_code')), 
											);
						$this->mod_advertiser->update_tracker($trackerData,$this->input->post('sel_tracker_id'));
						
						/* Update Tracker Append Code */
						$trackerAppend	=	array("tagcode"	=> text_db($this->input->post('append_code')));

						$this->mod_advertiser->update_tracker_append($trackerAppend,$this->input->post('sel_tracker_id'));
						
						$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_edit_tracker_success'));
						redirect("admin/inventory_advertisers/trackers/all/".$this->input->post('sel_advertiser')."");
					}
				break;
				case "rem": 
						if($ref_id != false){
							$this->mod_advertiser->del_tracker($ref_id);
							$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_single_delete_tracker_success'));
						}
						else
						{
							$this->mod_advertiser->del_tracker($this->input->post('sel_tracker'));
							$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_delete_tracker_success'));
						}
					
						
						redirect("admin/inventory_advertisers/trackers/all/".$adv_id."");
				break;
			}		
		}
		
		/*--------------------------------------------------------------------------------------------
		  					ACTIONS ARE RELATED TO TRACKERS LINKED CAMPAIGNS
		  --------------------------------------------------------------------------------------------*/
		  
		  
		  /*---------------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: trackers_linked_campaigns
		 	PARAM		: STATUS (STRING), ADVERTISER ID (numeric), TRACKER ID (numeric)
	 	 	DESCRIPTION	: This method has been used to open UI page for listing the linked campaigns based on selected tracker. 
			---------------------------------------------------------------------------------------------------------------------*/
		  
		  public function trackers_linked_campaigns($status, $advertiser_id, $tracker_id, $start=0){
			
				if($tracker_id != false){
				
				$data['sel_advertiser_id']		=$advertiser_id;
				$data['sel_tracker_id']			=$tracker_id;
				$data['sel_status']				=$status;
				
				/*-------------------------------------------------------------
					Page Title showed at the content section of page
			  	-------------------------------------------------------------*/
				$data['page_title'] 	=$this->lang->line('label_inventory_advertisers_trackers_linked_campaigns_page_title');
		
			   /*-------------------------------------------------------------
					Breadcrumb Setup Start
			   --------------------------------------------------------------*/
				
				$data['breadcrumb'] 	='<a href="'.site_url('admin').'">'.$this->lang->line('label_dashboard').'</a>
										  <a href="'.site_url('admin/inventory_advertisers').'">'.$this->lang->line('label_advertisers').'</a>
										  <a href="'.site_url('admin/inventory_advertisers/trackers/'.$status.'/'.$advertiser_id).'">'.$this->lang->line('label_trackers').'</a>
										  <span>'.$this->lang->line('label_inventory_advertisers_trackers_linked_campaigns_page_title').'</span>';

				/*---------------------------------------------------------------------------------------------------
						   				GET LINKED CAMPIGNS LIST FROM DB BASED ON SELECTED TRACKER
				 *---------------------------------------------------------------------------------------------------*/
				
				$data['campaigns_list']	=$this->mod_advertiser->get_trackers_linked_campaigns_list($advertiser_id,$tracker_id);
				
				//echo $this->db->last_query();
				
				/*--------------------------------------------------------------
					Pagination  Config Setup
				 ---------------------------------------------------------------*/
				
				$limit 					=25;//$this->page_limit;
				$config['base_url']		=site_url("admin/inventory_advertisers/trackers_linked_campaigns/$status/$advertiser_id/$tracker_id");
				$config['per_page'] 	=$limit;
				$config['uri_segment'] 	=7;
				$config['total_rows'] 	=count($data['campaigns_list']);//'5';
				$config['next_link'] 	=$this->lang->line("pagination_next_link");
				$config['prev_link'] 	=$this->lang->line("pagination_prev_link");		
				$config['last_link'] 	=$this->lang->line("pagination_last_link");		
				$config['first_link'] 	=$this->lang->line("pagination_first_link");
				$this->pagination->initialize($config);		
				
				if($config['total_rows'] >$limit){
					$data['campaigns_list'] =$this->mod_advertiser->get_trackers_linked_campaigns_list($advertiser_id, $tracker_id, $status, $start, $limit);
				}
				
				$data['campaigns_list']		=$data['campaigns_list'];
				
			   /*-------------------------------------------------------------
						Embed current page content into template layout
				--------------------------------------------------------------*/

				$data['page_content']		=$this->load->view("advertiser/trackers_campaigns_list", $data, true);
				$this->load->view('page_layout', $data);
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_campaigns_tracker_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		
		/*------------------------------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: trackers_linked_campaigns_time_settings
		 	PARAM		: STATUS (STRING), ADVERTISER ID (numeric), TRACKER ID (Numeric), CAMAPIGN ID (numeric)
	 	 	DESCRIPTION	: This method has been used to show UI page of Trackers Linked Campaign conversion time settings for clicks and views
		--------------------------------------------------------------------------------------------------------------------------------------*/
		
		public function trackers_linked_campaigns_time_settings($status,$advertiser_id,$tracker_id,$campaign_id){
			if($advertiser_id != false){
				
				$data['sel_advertiser_id']	=$advertiser_id;
				$data['sel_status']			=$status;
				$data['sel_tracker_id']		=$tracker_id;
				$data['sel_campaign_id']	=$campaign_id;
				$data['campaign_data']		=$this->mod_advertiser->get_campaigns_det($campaign_id);
				
				$view_window				=$data['campaign_data']->viewwindow;
				$click_window				=$data['campaign_data']->clickwindow;
	
				$data['view_conversion']	=$this->mod_advertiser->SecondsToTime($view_window);
				$data['click_conversion']	=$this->mod_advertiser->SecondsToTime($click_window);

				/*-------------------------------------------------------------
					Page Title showed at the content section of page
			  	-------------------------------------------------------------*/
				$data['page_title'] 		=$this->lang->line('label_inventory_advertisers_trackers_linked_campaigns_page_title');
				
				/*-------------------------------------------------------------
				Breadcrumb Setup Start
				-------------------------------------------------------------*/
				
				$data['breadcrumb']  ='<a href="'.site_url('admin').'">'.$this->lang->line('label_dashboard').'</a>
									   <a href="'.site_url('admin/inventory_advertisers').'">'.$this->lang->line('label_advertisers').'</a>
									   <a href="'.site_url('admin/inventory_advertisers/trackers/'.$status.'/'.$advertiser_id).'">'.$this->lang->line('label_trackers').'</a>
									   <a href="'.site_url('admin/inventory_advertisers/trackers_linked_campaigns/'.$status.'/'.$advertiser_id.'/'.$tracker_id).'">'.$this->lang->line('label_inventory_advertisers_trackers_linked_campaigns_page_title').'</a><span>'.$this->lang->line('label_tracker_campaign_time_settings_page_title').'</span>';

				/*-------------------------------------------------------------
				Embed current page content into template layout
				-------------------------------------------------------------*/
				$data['page_content']	= $this->load->view("advertiser/tracker_campaign_time_settings",$data,true);;
				$this->load->view('page_layout', $data);				
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		
		/*--------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: tracker_code
		 	PARAM		: STATUS (TRACKERS LISTING FILTER), ADVERTISER ID (numeric), TRACKER ID (numeric) 
	 	 	DESCRIPTION	: This method has been used to show Tracker code for selected tracker.
		---------------------------------------------------------------------------------------------------------------*/
		
		public function tracker_code($status, $advertiser_id=false, $tracker_id=false)
		{
			if($advertiser_id !=false AND $tracker_id !=false)
			{
				$data['sel_advertiser_id']	=$advertiser_id;
				$data['sel_status']			=$status;
				$data['sel_tracker_id']		=$tracker_id;

				$tempObj					=$this->mod_advertiser->get_tracker_det($tracker_id);

				if($tempObj != FALSE) { $data['tracker_det'] =$tempObj[0]; }

				/*-------------------------------------------------------------
					Page Title showed at the content section of page
			  	-------------------------------------------------------------*/
				$data['page_title'] =$this->lang->line('label_inventory_advertisers_trackers_code_page_title');

				/*-------------------------------------------------------------
				Breadcrumb Setup Start
				-------------------------------------------------------------*/

				$data['breadcrumb'] ='<a href="'.site_url('admin').'">'.$this->lang->line('label_dashboard').'</a>
									 <a href="'.site_url('admin/inventory_advertisers').'">'.$this->lang->line('label_advertisers').'</a>
									 <a href="'.site_url('admin/inventory_advertisers/trackers/'.$status.'/'.$advertiser_id).'">'.$this->lang->line('label_trackers').'</a>
									 <span>'.$this->lang->line('label_inventory_advertisers_trackers_code_page_title').'</span>';

				/*-------------------------------------------------------------
				Embed current page content into template layout
				-------------------------------------------------------------*/
				$data['page_content']	=$this->load->view("advertiser/tracker_code", $data, true);
				$this->load->view('page_layout', $data);
			}
			else
			{
				$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
				redirect("admin/inventory_advertisers");
			}
		}
		
		/*--------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: tracker_campaign_process
		 	PARAM		: Task Name (STRING) [GET DATA], ADVERTISER ID, TRACKER ID and CAMPAIGN ID (POST VARIABLES)
	 	 	DESCRIPTION	: This method has been used to manage all the background process related to trackers linked campaigns like
	 	     			  Link the existing campaigns into selected tracker and To configure View & Click time conversion 
		---------------------------------------------------------------------------------------------------------------*/
		  
		 public function tracker_campaign_process($task){
		 	
			switch($task){
				case "link":
									
					$clientid			=$this->input->post('sel_advertiser_id');
					$trackerid			=$this->input->post('sel_tracker_id');
					$date				=date("Y-m-d");
					$cid				=$this->input->post('sel_campaign');
					$del_cid			=($this->input->post('list_campid') !='')?$this->input->post('list_campid'):'';
										
					if($del_cid !='') {
								
								$c_id	=explode(",", $del_cid);
								$this->db->where_in('campaignid', $c_id);
								$this->db->delete('ox_campaigns_trackers');	
					//echo $this->db->last_query();
					//die();
					}

					if(is_array($cid) && count($cid) >0) :
					  $this->db->where_in('campaignid', $cid);
					  $this->db->delete('ox_campaigns_trackers');	
					endif;
					
					$this->db->select("status")->from("ox_trackers")->where(array("trackerid"=>$trackerid));
					$rs 		=$this->db->get()->row();
					$status		=$rs->status;
					
					if(is_array($cid) && count($cid) >0) :
					  foreach($cid as $id)
					  {
						$ins	=array("campaignid" =>$id, "trackerid" =>$trackerid, "status" =>$status);
						$this->mod_advertiser->insert_trackercampaign($ins);
					  }
					endif;
					
					$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_tracker_link_campaign_success'));
					redirect("admin/inventory_advertisers/trackers_linked_campaigns/".$this->input->post('sel_status')."/$clientid/$trackerid");
				break;
				
				case "time":
								
					$clientid			=text_db($this->input->post('sel_advertiser_id'));
					$trackerid			=text_db($this->input->post("sel_tracker_id"));
					$campaignid			=text_db($this->input->post('sel_campaign_id'));
					$date				=date("Y-m-d");
					
					$v_days				=text_db($this->input->post("view_days"));
					$v_hours			=text_db($this->input->post("view_hours"));
					$v_min				=text_db($this->input->post("view_minutes"));
					$v_sec				=text_db($this->input->post("view_seconds"));
					$c_days				=text_db($this->input->post("click_days"));	
					$c_hours			=text_db($this->input->post("click_hours"));
					$c_min				=text_db($this->input->post("click_minutes"));
					$c_sec				=text_db($this->input->post("click_seconds"));
					
					$years				=0;
					
					$view				=0;
					$click				=0;
			
					$view				=$this->mod_advertiser->TimeToSeconds($years, $v_days, $v_hours, $v_min, $v_sec);
					$click				=$this->mod_advertiser->TimeToSeconds($years, $c_days, $c_hours, $c_min, $c_sec);		
					
					$campaign_update	=array("viewwindow" =>$view, "clickwindow" =>$click);
					$where				=array("campaignid" =>$campaignid);
			
					if((is_numeric($view) || is_numeric($click)) && ($view >0 || $click >0))
					{
						$this->mod_advertiser->update_campaignsdata($campaign_update, $campaignid);
						$this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_tracker_link_campaign_time_success'));
					}
					else { 
							$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_tracker_link_campaign_time_fail'));
					}
					redirect("admin/inventory_advertisers/trackers_linked_campaigns/".$this->input->post('sel_status')."/$clientid/$trackerid");
				break;
			}		 	
		 			
					
		 } 
		  
		  
		 /*--------------------------------------------------------------------------------------------
		  					END OF TRACKERS LINKED CAMPAIGNS ACTIONS
		  --------------------------------------------------------------------------------------------*/ 
		
		/*--------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: username_check
		 	PARAM		: EMAIL (POST DATA)
	 	 	DESCRIPTION	: This method has been used to check whether the duplicate username exists or not in advertiser account.
		---------------------------------------------------------------------------------------------------------------*/
        
		public function username_check(){
			$query		=$this->db->where(array("username" =>text_db($this->input->post('username'))))->get('oxm_userdetails')->num_rows();
			if($query==0){
					echo "no";exit;						
			}
			else
			{
					echo "yes";exit;
			}
		}
		
		/*-----------------------------------------------------------------------------------------------------------------------	
		 	METHOD NAME	: email_check
		 	PARAM		: EMAIL (POST VARIABLE)
	 	 	DESCRIPTION	: This method has been used to check whether the duplicate email id exists or not in advertiser account.
		 ------------------------------------------------------------------------------------------------------------------------*/
		
		public function email_check(){
			$query		=$this->db->where(array("email" =>text_db($this->input->post('email'))))->get('oxm_userdetails')->num_rows();
			if($query==0){
					echo "no";exit;						
			}
			else
			{
					echo "yes";exit;
			}
		}
		
		
		public function test(){
			//echo $this->mod_advertiser->get_admin_email();
			print_r($this->session->userdata);
            //$data['page_content']	=  $this->load->view('test','',true);
	  		//$this->load->view('page_layout',$data);
        }
		

}

/* End of file advertisers.php */
/* Location: ./modules/inventory/controllers/advertiser.php */
