<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approvals extends CI_Controller {

	/*******Page Limit : Number of Records shown at the pagination *********/ 
	var $page_limit	=	'5';
	var $status     = "all";
	
	public function __construct()
    {
		parent::__construct();

		/* Libraries */
		$this->load->library('email');
		
		/* Helpers */

		
		/* Models */

		$this->load->model("mod_approval_settings"); //loc:Admin/models/mod_approval_settings
		
		$this->load->model("mod_common_operations"); //loc:Admin/models/mod_common_operations
		

		/* Classes */
		
    }
	
	/* Dashboard Page */
	public function index()
	{
		
		/********Calling Advertisers  function *********/
		redirect('admin/approvals/advertisers');
	}

	//Unviewed Advertisers List
	  public function unviewed_advertisers($start=0){
		$this->status = "unviewed";
		$this->advertisers($start);
    	}

	/*Advertisers Module */
	function advertisers($start =0)
	{
	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		if($this->status =="all")
		{
			$list_data = $this->mod_approval_settings->get_advertisers_list();
		}else if($this->status=="unviewed"){
			$limit = $start = 0;
			$list_data	=	 $this->mod_approval_settings->get_unviewed_list($limit,$start,'ADVERTISER');
		}
		$limit = $this->page_limit;
		
		$config['per_page'] = $limit;
		if($this->status=="all"){
			$config['base_url'] = site_url("admin/approvals/advertisers/");
		}
		else
		{
			$config['base_url'] = site_url("admin/approvals/unviewed_advertisers");
		}
		
		 
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		if($this->status=='all')
		{
			$list_data = $this->mod_approval_settings->get_advertisers_list($limit,$start);
			$data['advertisers_list']	=	$list_data;
		}else if($this->status =="unviewed")
		{
				$data['advertisers_list']= $this->mod_approval_settings->get_unviewed_list($limit,$start,'ADVERTISER');
		}
		
		$data['allrecords']		=	 $this->mod_approval_settings->get_advertisers_list();
		if($data['allrecords']!='')
		{
			$data['allrecords']	= count($data['allrecords']);
		}else{
			$data['allrecords']=0;
		}
		//$data['allrecords']= ;
		$data['unviewed_advertisers_list']= $this->mod_approval_settings->get_unviewed_list($limit=0,$start=0,'ADVERTISER');
		
		if($data['unviewed_advertisers_list']!='')
		{
			$data['unviewed_list']=count($data['unviewed_advertisers_list']);	
		}else{
			$data['unviewed_list']=0;
		}
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_approval_advertisers_title');
		
			/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("approvals/advertisers/advertisers_list",$data,true);
		$this->load->view('page_layout',$data);
			
	}

	//Unviewed Publishers List
	public function unviewed_publishers($start=0){
		$this->status = "unviewed";
		$this->publishers($start);
    	}
	
		/*Publishers Module */
	function publishers($start =0)
	{
		//echo 'test advertiser';
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		if($this->status =="all")
		{
		$list_data = $this->mod_approval_settings->get_publishers_list();
		}else if($this->status=="unviewed"){
			$limit = $start = 0;
			$list_data	=	 $this->mod_approval_settings->get_unviewed_list($limit,$start,'TRAFFICKER');
		}
		$limit = $this->page_limit;
		
		$config['per_page'] = $limit;
		
		if($this->status=="all"){
		$config['base_url'] = site_url("admin/approvals/publishers/");
		 }else
		{
			$config['base_url'] = site_url("admin/approvals/unviewed_publishers");
		}
		 
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		if($this->status=='all')
		{
		$list_data = $this->mod_approval_settings->get_publishers_list($limit,$start);
		$data['publishers_list']	=	$list_data;
		}else if($this->status =="unviewed")
		{
				$data['publishers_list']= $this->mod_approval_settings->get_unviewed_list($limit,$start,'TRAFFICKER');
				
		}	
		
		$data['allrecords']		=	 $this->mod_approval_settings->get_publishers_list();
		if($data['allrecords']!='')
		{
			$data['allrecords']	= count($data['allrecords']);
		}else{
			$data['allrecords']=0;
		}
		//$data['allrecords']= ;
		$data['unviewed_publishers_list']= $this->mod_approval_settings->get_unviewed_list($limit=0,$start=0,'TRAFFICKER');
		
		if($data['unviewed_publishers_list']!='')
		{
			$data['unviewed_list']=count($data['unviewed_publishers_list']);	
		}else{
			$data['unviewed_list']=0;
		}
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_approval_publishers_title');
		
			/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("approvals/publishers/publishers_list",$data,true);
		$this->load->view('page_layout',$data);
			
	}
	
	// Approval and Deletion Process
	function process()
	{
		//echo "Delete Chk data".$this->input->post('approval_chkdata');
		//exit;
		//$approval_chkdata	=	$this->input->post('approval_chkdata');
		
		/*if(isset($approval_chkdata) && $approval_chkdata !='')
		{
				if($approval_chkdata ==="approve")
				{
					echo 	$approval_chkdata;	
				}else if($approval_chkdata ==="delete")
				{
				   echo 'fff';
				}
		}*/
		
		
		$action = $this->uri->segment(4)!="" ? $this->uri->segment(4) :0;
	   
		switch($action)
		{
			//Advertisers approval Process
			case "advertisers":
				$type	=	$this->uri->segment(5)!=""? $this->uri->segment(5):'';
				$sel_user_id		=	$this->input->post('sel_approval_advertisers');
				//$user_id	=	$this->input->post('uid');
				
				// Checks the Type of Operation
				if($type =="approve")
				{
					//checks whether it is multiple approval or single approval
					if(is_array($sel_user_id))
					{
						foreach($sel_user_id as $user_id)
						{
							//Fetch the Record from new users
							//$new_users	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();


							$approved_check = $this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
							
							if($approved_check>0)
							{
							$this->approval_advertiser($user_id);
							}
						} //end of foreach
						
						$this->session->set_flashdata('success_message', $this->lang->line('notification_advertiser_sel_approved'));
        				redirect("admin/approvals/advertisers");
						//print_r($user_id);
					}//End of Multiple Approval
					else{
							
							$user_id		=$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
							//Fetch the Record from new users
							$approved_check = $this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
							
							if($approved_check>0)
							{
							$this->approval_advertiser($user_id);
							}else{
								$this->session->set_flashdata('error_message', $this->lang->line('notification_advertiser_already_approved'));
        						redirect("admin/approvals/advertisers");
							}
							$this->session->set_flashdata('success_message', $this->lang->line('notification_advertiser_approved'));
        					redirect("admin/approvals/advertisers");
					}						
				}else if($type=="reject")
				{
					
					//Enter the field namd and table name for deletion purpose
					$id_field_name	=	'user_id';
					$tbl_name			=	'oxm_newusers';
					$user_id					=	$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
						
					//Checks Whether it is an array od elements
					if(is_array($sel_user_id))
					{
						foreach($sel_user_id as $user_id)
						{
						//Set the Read Status to 1	
						$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}							
							$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
						}
						$this->session->set_flashdata('success_message', $this->lang->line('notification_advertiser_sel_rejected'));
        				redirect("admin/approvals/advertisers");
					}
					else if($user_id !=''){
						//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}						
						$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
						
						$this->session->set_flashdata('success_message', $this->lang->line('notification_advertiser_rejected'));
        				redirect("admin/approvals/advertisers");
					}else{
						$this->session->set_flashdata('error_message', $this->lang->line('notification_advertiser_not_rejected'));
        				redirect("admin/approvals/advertisers");
					}
				}//end of advertiser Rejection
			break;
			//Publishers approval Process
			case "publishers":
				$type	=	$this->uri->segment(5)!=""? $this->uri->segment(5):'';
				$sel_user_id		=	$this->input->post('sel_approval_publishers');
				//$user_id	=	$this->input->post('uid');
				
				// Checks the Type of Operation
				if($type =="approve")
				{
					//checks whether it is multiple approval or single approval
					if(is_array($sel_user_id))
					{
						foreach($sel_user_id as $user_id)
						{
							
							//Fetch the Record from new users
							//$new_users	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();
							$approved_check = $this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
							if($approved_check>0)
							{
								$this->approval_publisher($user_id);
							}
						} //end of foreach
						
						$this->session->set_flashdata('success_message', $this->lang->line('notification_publisher_sel_approved'));
        				redirect("admin/approvals/publishers");
						//print_r($user_id);
					}//End of Multiple Approval
					else{
							
							$user_id		=$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
							
							$approved_check = $this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->num_rows();
							
							if($approved_check>0)
							{
								$this->approval_publisher($user_id);
							}else{
								$this->session->set_flashdata('error_message', $this->lang->line('notification_publisher_already_approved'));
        						redirect("admin/approvals/publishers");
							}
							//end of if condition
							$this->session->set_flashdata('success_message', $this->lang->line('notification_publisher_approved'));
        					redirect("admin/approvals/publishers");
					}						
				}else if($type=="reject")
				{
					//Enter the field namd and table name for deletion purpose
					$id_field_name	=	'user_id';
					$tbl_name			=	'oxm_newusers';
					$user_id					=	$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
						
					//Checks Whether it is an array od elements
					if(is_array($sel_user_id))
					{
						foreach($sel_user_id as $user_id)
						{
							//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}							
							$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
						}
						$this->session->set_flashdata('success_message', $this->lang->line('notification_publisher_sel_rejected'));
        				redirect("admin/approvals/publishers");
					}
					else if($user_id !=''){
						
						//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
						$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
						
						$this->session->set_flashdata('success_message', $this->lang->line('notification_publisher_rejected'));
        				redirect("admin/approvals/publishers");
					}else{
						$this->session->set_flashdata('error_message', $this->lang->line('notification_publisher_not_rejected'));
        				redirect("admin/approvals/publishers");
					}
				}//end of publisher Rejection
			break;
			
			//Approvals:banners
			case "banners":
					$type	=	$this->uri->segment(5)!=""? $this->uri->segment(5):'';
					$sel_ban_id		=	$this->input->post('sel_approval_banners');
					
					
					// Checks the Type of Operation
					if($type =="approve")
					{
						//checks whether it is multiple approval or single approval
						if(is_array($sel_ban_id))
						{
							foreach($sel_ban_id as $ban_id)
							{
								//Fetch the Record from new banners
								$this->approval_banner($ban_id);
								
							} //end of foreach
							
							$this->session->set_flashdata('success_message', $this->lang->line('notification_banner_sel_approved'));
							redirect("admin/approvals/banners");
							//print_r($user_id);
						}//End of Multiple Approval
						else{
								
								$ban_id		=$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
								
								$this->approval_banner($ban_id);
								//end of if condition
								$this->session->set_flashdata('success_message', $this->lang->line('notification_banner_approved'));
								redirect("admin/approvals/banners");
						}						
					}else if($type=="reject")
					{
						
						$banner_id					=	$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
							
						//Checks Whether it is an array od elements
						if(is_array($sel_ban_id))
						{
							foreach($sel_ban_id as $banner_id)
							{
								$this->reject_banner($banner_id);
								
								//$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
							}
							$this->session->set_flashdata('success_message', $this->lang->line('notification_banner_sel_rejected'));
							redirect("admin/approvals/banners");
						}
						else if($banner_id !=''){
							//$this->mod_common_operations->delete_data($user_id,$id_field_name,$tbl_name);
							
							$this->reject_banner($banner_id);
							
							$this->session->set_flashdata('success_message', $this->lang->line('notification_banner_rejected'));
							redirect("admin/approvals/banners");
						}else{
							$this->session->set_flashdata('error_message', $this->lang->line('notification_banner_not_deleted'));
							redirect("admin/approvals/banners");
						}
					}//end of Banner Rejection
				break;
				
				//Approvals:Payments
			case "payments":
					$type	=	$this->uri->segment(5)!=""? $this->uri->segment(5):'';
					
					
					// Checks the Type of Operation
					if($type =="approve")
					{
						$pay_appr_id		=$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
						//checks whether it is multiple approval or single approval
						if($pay_appr_id !='')
						{
							$this->approval_payment($pay_appr_id);
							//end of if condition
							$this->session->set_flashdata('success_message', $this->lang->line('notification_payment_approved'));
							redirect("admin/approvals/payments");
						}						
					}else if($type=="reject")
					{
						
						$pay_rej_id					=	$this->uri->segment(6)!="" ? $this->uri->segment(6) :'';
						$tbl_name					=	"oxm_admin_payment";
						
						
						//Checks Whether it is an array od elements
						if($pay_rej_id !='')
						{
							
							
							list($pub_id,$pay_id)	=	explode('-',$pay_rej_id);
							
							
							//Getting the Payment type,email and amount from oxm_admin_payment
							$payment_details	=	$this->db->select('paymenttype,name,email,amount ')->get_where('oxm_admin_payment',array('publisherid'=>$pub_id,
																																						'id'=>$pay_id))->row();
				
				
							$payment_type		=	$payment_details->paymenttype; //Payment Type eg:paypal
							$email			=	$payment_details->email; // Email
							$amount			=	$payment_details->amount; //Amount it is paid					
							
							$mail	=	$this->db->get_where('ox_affiliates',array('affiliateid'=>$pub_id))->row();
							
		
							if($mail !='')
							{
										
								// Retreive the pub details.
								$pub_email	=	$mail->email;
								$contact	=	$mail->contact;
								$name	=	$mail->name;
								$cur_date	=	date("Y-m-d H:i:s");
								

								/*-----------------------------------------------------------------------
										SEND INTIMATION BY EMAIL TO PUBLISHER 
								------------------------------------------------------------------------*/

								$admin_email		=	$this->mod_common_operations->get_admin_email();
								
								$email_data		= 	array(	"name"		=>$contact,
													"date" 		=>$cur_date,
													"website"	=>$name,
													"amount"	=>$amount	
													);
																			
								
								$content				=$this->load->view('email/Administrator/reject_payment_publisher',$email_data,TRUE);
								$subject				=$this->lang->line('label_payment_reject_subject');
								$data['content']		=$content;
								$mail_content			=$this->load->view('email/registration/email_tpl', $data, TRUE);
								$message               	=$mail_content;
								$config3['protocol'] 	="sendmail";
								$config3['wordwrap'] 	= TRUE;		
								$config3['mailtype']		='html';
								$config3['charset']  	='UTF-8';        
								$this->email->initialize($config3);
								$this->email->from($admin_email);
								$this->email->to($pub_email);        
								$this->email->subject($subject);        
								$this->email->message($message);
								$this->email->send();
							
							}
							//Delete the data based on condition
							$this->db->delete($tbl_name,array('publisherid'=>$pub_id,
																				 'id'=>$pay_id));
							$this->session->set_flashdata('success_message', $this->lang->line('notification_payment_rejected'));
							redirect("admin/approvals/payments");
						}else{
							$this->session->set_flashdata('error_message', $this->lang->line('notification_payment_not_deleted'));
							redirect("admin/approvals/payments");
						}
					}//end of Banner Rejection
				break;	
					
			
		}//End : Switch cases
		
		
	}//End of Process Function
	
	public function approval_publisher($user_id='')
	{
			//Fetch the Record from new users
			$new_users	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();
							
			if($user_id !='')
			{
								
					// Retreive the new users into their respective variables.
					$email			=	$new_users->email_address;
					$website_url	=	$new_users->site_url;
					$category		=	explode(",", $new_users->category);
					$name			=	$new_users->contact_name;
					$username		=	$new_users->username;
					$password		=	$new_users->password;
					$account		=	$new_users->account_type;
					$address		=	$new_users->address;
					$city				=	$new_users->city;
					$state			=	$new_users->state;
					$country		=	$new_users->country;
					$mobile			=	$new_users->mobileno;
					$zip				=	$new_users->postcode;
					$cur_date			=	date("Y-m-d H:i:s");
					$user_ref_id		=	$new_users->user_ref_id;
						$new_password= base64_decode($password);	
					$md5_password=md5($new_password);			
					//Insert the account details into ox_accounts 
					$insert_account_data	=	array(
													"account_type"=>$account,
													"account_name"=>$username
													);
																		
					$account_tbl_name	=	'ox_accounts';
								
					$last_insert_id	=	$this->mod_common_operations->insert_data($insert_account_data,$account_tbl_name);//Insert the data and get the last inserted id
								
					//Insert the user details into oxm_userdetails 
					$insert_user_details_data 	=	array(
																		"accountid" =>$last_insert_id, 
																		"accounttype" =>$account, 
																		"username"=>$username,
																		"websitename"=>$website_url,
																		"email"=>$email,
																		"password"=>$md5_password,
																		"address"=>$address,
																		"city"=>$city,
																		"state"=>$state,
																		"country"=>$country,
																		"mobileno"=>$mobile,
																		"postcode"=>$zip
																);
																		
					$user_details_tbl_name	=	'oxm_userdetails';
								
					$this->mod_common_operations->insert_data($insert_user_details_data,$user_details_tbl_name);
								
					//Insert the publishers details into ox_affiliates
					$insert_affiliate_data	=	array(
																	"agencyid" =>1, 
																	"email"=>$email,
																	"contact"=>$name,
																	"name"=>$website_url,
																	"updated"=>$cur_date,
																	"account_id"=>$last_insert_id,
																	"oac_category_id" =>implode(",", $category)
																);
																		
					$affiliate_tbl_name		=	'ox_affiliates';
								
					$this->mod_common_operations->insert_data($insert_affiliate_data,$affiliate_tbl_name);
								 
					 //Insert the user details into ox_users
					$insert_user_data		=	array(
																	"contact_name"=>$name,
																	"email_address"=>$email,
																	"username"=>$username,
																	"password"=>$md5_password,
																	"default_account_id"=>$last_insert_id,
																	"date_created"=>$cur_date
															);
																		
					$user_tbl_name		=	'ox_users';
								
					$user_lastid	=	$this->mod_common_operations->insert_data($insert_user_data,$user_tbl_name);	// Insert the  given and get the user id
								
					//Insert the account user association details into ox_account_user_assoc
					$insert_auassoc_data	=	array(
																	"account_id"=>$last_insert_id,
																	"user_id"=>$user_lastid,
																	"linked"=>$cur_date
															);
																		
					$auassoc_tbl_name		= 'ox_account_user_assoc';
								 
					$this->mod_common_operations->insert_data($insert_auassoc_data,$auassoc_tbl_name);
								
					$permission_array	=	array("10","4","2","1","11");
					foreach($permission_array as $permission_id)
					{
							$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$last_insert_id, "user_id"=>$user_lastid, "permission_id"=>$permission_id));
					}

					/***** Make an Entry to this table for advertiser and publisher relation */
					$relquery = $this->db->get_where('djx_advertiser_publisher_rel',array('pub_id'=>$user_id,'pub_status'=>0))->num_rows();
					$rel_tbl_name	=	'djx_advertiser_publisher_rel';
					if($relquery>0)
					{
							$update_data = array('pub_id'=>$user_lastid,'pub_status'=>1);
							
							$where_arr  = array('pub_id'=>$user_id);
							
							$this->mod_common_operations->update_data($update_data,$where_arr,$rel_tbl_name);
					}else{
							$insert_data		=	array('pub_id'=>$user_lastid,'adv_id'=>$user_ref_id,'pub_status'=>1,'adv_status'=>0);
							$this->mod_common_operations->insert_data($insert_data,$rel_tbl_name);
					}

					//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
								
					 /*-----------------------------------------------------------------------
					SEND INTIMATION EMAIL TO REGISTRED ADVERTISER
								------------------------------------------------------------------------*/
		
						$publisher_email_id	=	$email;
					$admin_email			=	$this->mod_common_operations->get_admin_email();
					$subject					=	$this->lang->line('label_approval_subject');
					$email_data			= 	array(
																"name"	=>	$name, "username" =>$username,"password" =>$new_password
														);
																	
					
				      $content		= $this->load->view('email/Administrator/approve_publisher',$email_data,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
						 $message                   	=$mail_content;
			 //$toemail=$registration['email'];
             $config['protocol'] = "sendmail";
             $config['wordwrap'] = TRUE;		
		$config['mailtype'] 		='html';
		$config['charset']			='UTF-8';        
		$this->email->initialize($config);
					$this->email->from($admin_email);
					$this->email->to($publisher_email_id);        
					$this->email->subject($subject);        
					$this->email->message($message);
					$this->email->send();
					
					//End of Sending Email Notification to the User
								
					//Delete the User details at oxm_newusers after making an approval
					$this->db->delete('oxm_newusers', array("user_id"=>$user_id));
				}			
	
		
	}

	//Advertiser Approval
	public function approval_advertiser($user_id ='')
	{
			$new_users	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id))->row();
							
			if($user_id !='')
			{
								
					// Retreive the new users into their respective variables.
					$email		=	$new_users->email_address;
					$username	=	$new_users->username;
					$password	=	$new_users->password;
					$account	=	$new_users->account_type;
					$address	=	$new_users->address;
					$city			=	$new_users->city;
					$state		=	$new_users->state;
					$country	=	$new_users->country;
					$mobile		=	$new_users->mobileno;
					$zip			=	$new_users->postcode;
					$name		=	$new_users->contact_name;
					$cur_date		=	date("Y-m-d H:i:s");					
					$user_ref_id		=	$new_users->user_ref_id;
                    $new_password= base64_decode($password);	
					$md5_password=md5($new_password);	
					//Insert the account details into ox_accounts 
					$insert_account_data	=	array(
																"account_type"=>$account,
																"account_name"=>$username
															);
																		
					$account_tbl_name	=	'ox_accounts';
								
					$last_insert_id	=	$this->mod_common_operations->insert_data($insert_account_data,$account_tbl_name);//Insert the data and get the last inserted id
								
					//Insert the user details into oxm_userdetails 
					$insert_user_details_data 	=	array(
																	"accountid" =>$last_insert_id, 
																	"accounttype" =>$account, 
																	"username"=>$username,
																	"email"=>$email,
																	"password"=>$md5_password,
																	"address"=>$address,
																	"city"=>$city,
																	"state"=>$state,
																	"country"=>$country,
																	"mobileno"=>$mobile,
																	"postcode"=>$zip
																);
																			
					$user_details_tbl_name	=	'oxm_userdetails';
								
					$this->mod_common_operations->insert_data($insert_user_details_data,$user_details_tbl_name);
								
					//Insert the client details into ox_clients
					$insert_client_data		=	array(
																"agencyid" =>1, 
																"email"=>$email,
																"contact"=>$name,
																"clientname"=>$username,
																"account_id"=>$last_insert_id
															);
																		
					$client_tbl_name		=	'ox_clients';
								
					$this->mod_common_operations->insert_data($insert_client_data,$client_tbl_name);
								 
					 //Insert the user details into ox_users
					$insert_user_data		=	array(
																"contact_name"=>$name,
																"email_address"=>$email,
																"username"=>$username,
																"password"=>$md5_password,
																"default_account_id"=>$last_insert_id,
																"date_created"=>$cur_date
															);
																		
					$user_tbl_name		=	'ox_users';
								
					$user_lastid	=	$this->mod_common_operations->insert_data($insert_user_data,$user_tbl_name);	// Insert the  given and get the user id
								
					//Insert the account user association details into ox_account_user_assoc
					
					$insert_auassoc_data	=	array(
																"account_id"=>$last_insert_id,
																"user_id"=>$user_lastid,
																"linked"=>$cur_date
															);
																		
					$auassoc_tbl_name		= 'ox_account_user_assoc';
								 
					$this->mod_common_operations->insert_data($insert_auassoc_data,$auassoc_tbl_name);
								
					$permission_array	=	array("10","4","2","1","11");
					foreach($permission_array as $permission_id)
					{
						$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$last_insert_id, "user_id"=>$user_lastid, "permission_id"=>$permission_id));
					}

					
					/***** Make an Entry to this table for advertiser and publisher relation */
					$relquery = $this->db->get_where('djx_advertiser_publisher_rel',array('adv_id'=>$user_id,'adv_status'=>0))->num_rows();
					$rel_tbl_name	=	'djx_advertiser_publisher_rel';
					if($relquery>0)
					{
							$update_data = array('adv_id'=>$user_lastid,'adv_status'=>1);
							
							$where_arr  = array('adv_id'=>$user_id);
							
							$this->mod_common_operations->update_data($update_data,$where_arr,$rel_tbl_name);
					}else{
							$insert_data		=	array('adv_id'=>$user_lastid,'pub_id'=>$user_ref_id,'adv_status'=>1,'pub_status'=>0);
							$this->mod_common_operations->insert_data($insert_data,$rel_tbl_name);
					}
					
				//Set the Read Status to 1	
					$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
								
					 /*-----------------------------------------------------------------------
							SEND INTIMATION EMAIL TO REGISTRED ADVERTISER
					------------------------------------------------------------------------*/
			        $advertiser_email_id	=	$email;
					
					$admin_email			=	$this->mod_common_operations->get_admin_email();
					$subject					=	$this->lang->line('label_approval_subject');
					$email_data			= 	array(
																"name"	=>	$name, "username" =>$username,"password" =>$new_password
														);
																	
					
				      $content		= $this->load->view('email/Administrator/approve_advertiser',$email_data,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
						 $message                   	=$mail_content;
			  //$toemail=$registration['email'];
             $config['protocol'] = "sendmail";
             $config['wordwrap'] = TRUE;		
		$config['mailtype'] 		='html';
		$config['charset']			='UTF-8';        
		$this->email->initialize($config);
					$this->email->from($admin_email);
					$this->email->to($advertiser_email_id);        
					$this->email->subject($subject);        
					$this->email->message($message);
					$this->email->send();
					
								
					//End of Sending Email Notification to the User
								
					//Delete the User details at oxm_newusers after making an approval
					$this->db->delete('oxm_newusers', array("user_id"=>$user_id));
			}//end of if condition
	
	}
	
	/*Banners Module */
	function banners($start =0)
	{
		//echo 'test advertiser';
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		$limit = $this->page_limit;
		$list_data = $this->mod_approval_settings->get_banners_list();
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("admin/approvals/banners/");
		 
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_approval_settings->get_banners_list($limit,$start);
		$data['banners_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_approval_banners_title');
		
			/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("approvals/banners/banners_list",$data,true);
		$this->load->view('page_layout',$data);
			
	}
	
	//approval of Banner 
	function approval_banner($banner)
	{
		if($banner!='')
		{
			$banner_id = $banner;
		}
		else
		{
			$banner_id = 0;	
		}
		
		//Update the status to 0 and adminstatus to 0  at ox_banners
		$set_data	=	array('adminstatus'=>0,'status'=>0);
		$this->db->where('bannerid',$banner_id);
		$this->db->or_where('master_banner',$banner_id);
		$this->db->update('ox_banners',$set_data);
		
		//To Retreive the Campaign  and For Mapping Zones
		$dbclientdata	=	$this->mod_approval_settings->get_client_campaign_data($banner_id);
		
		//For Mailing Purpose
		$client_email	=	$dbclientdata->email;
		$client_name	=	$dbclientdata->contact;
		$banner_name	=	$dbclientdata->description;
		
		$campaign_id	=	$dbclientdata->campaignid;
		
		$placement_zone	=	$this->db->select('zone_id')->get_where('ox_placement_zone_assoc',array('placement_id'=>$campaign_id));
		//echo $this->db->last_query();
		//print_r($placement_zone);
	
		if($placement_zone->num_rows() >0)
		{
			foreach($placement_zone->result() as $campaign)
			{
				
				//Retrieve the Zone id from campaign
				$zoneid 	=	$campaign->zone_id;
				
				//Retreive the Master Zone with Respective of Zone id
				$masterzone_result	=	$this->db->select('master_zone')->get_where('ox_zones',array('zoneid'=>$zoneid))->row();
				
				
				$masterzone	=	$masterzone_result->master_zone;
					
				//In case MasterZone is Text Banner
				if($masterzone==="-1")
				{
				
					//check  whether banner is found at ox_banners				
					$text_ban	=	$this->db->select('bannerid')->get_where('ox_banners',array(
																																	'bannerid'=>$banner_id,
																																	'height'=>0,
																																	'width'=>0,
																																	'master_banner'=>'-1'
																															));																									
					
					//If the Text Banner is found in the particular bannerid
					if($text_ban->num_rows() >0)
					{
							//Insert Zoneid and bannerid  into ox_ad_zone_assoc for mapping purpose

							$insert_data	=	array(
															'zone_id'=>$zoneid,
															'ad_id'=>$banner_id
														);	
							$tbl_name		=	'ox_ad_zone_assoc';
							
							//Inserts the data into respective table
							$this->mod_common_operations->insert_data($insert_data,$tbl_name);
							
					}
					
					
				
				}else if($masterzone==="-2"){   // loops when masterzone is a image banner
						
						//checks Whether it is Master Banner
						$image_master_ban		=	$this->db->select('bannerid')->get_where('ox_banners',array(
																														'bannerid'=>$banner_id,
																														'master_banner'=>'-2'
																										));
						
						//If the Image Banner is Found in the particular bannerid
						if($image_master_ban->num_rows>0)
						{
								//Insert Zoneid and bannerid  into ox_ad_zone_assoc for mapping purpose
								$insert_data	=	array(
															'zone_id'=>$zoneid,
															'ad_id'=>$banner_id
														);	
								$tbl_name		=	'ox_ad_zone_assoc';
							
								//Inserts the data into respective table
								$this->mod_common_operations->insert_data($insert_data,$tbl_name);
						
						}
						
						//Checks for Child Banners for MasterZones
						
						$child_zones	=	$this->db->select('zoneid,width,height')	->get_where('ox_zones',array('master_zone'=>$zoneid));
						
						
						foreach($child_zones->result() as $child_image)
						{
							//Retreive the Child banners based on height and width
							$child_banner	=	$this->db->select('bannerid')->get_where('ox_banners',array('master_banner'=>$banner_id,
																												'width'=>$child_image->width,
																												'height'=>$child_image->height));

							//Checks Whether Child Image banner is available at the particular banner id
							if($child_banner->num_rows>0)
							{
								//Insert Zoneid and bannerid  into ox_ad_zone_assoc for mapping purpose
								/*$insert_data	=	array(
															'zone_id'=>$child_image->zoneid,
															'ad_id'=>$child_banner->bannerid
														);	
								$tbl_name		=	'ox_ad_zone_assoc';*/
							
								//Inserts the data into respective table
								//$this->mod_common_operations->insert_data($insert_data,$tbl_name);
								foreach($child_banner->result() as $child_ban)
								{
									//Insert Zoneid and bannerid  into ox_ad_zone_assoc for mapping purpose
									$insert_data	=	array(
																'zone_id'=>$child_image->zoneid,
																'ad_id'=>$child_ban->bannerid
															);	
									$tbl_name		=	'ox_ad_zone_assoc';
								
									//Inserts the data into respective table
									$this->mod_common_operations->insert_data($insert_data,$tbl_name);
								}
						
							}
						
						}//End of Foreach
						
				}	//End of Image Banner MasterZone
				else if($masterzone==="-3"){  //MasterZone is Tablet Banner
						
						//Retreive the Zones with respect to zone id
						$tablet_zones		=	$this->db->select('width,height')->get_where('ox_zones',array('zoneid'=>$zoneid));
						
						foreach($tablet_zones->result() as $tablet_image)
						{
							
							// Retreives the tablet banners at ox_banners based on width and height condition
							$tablet_banner	=	$this->db->select('bannerid')->get_where('ox_banners',array('master_banner' =>'-3',
																																			 'width'=>$tablet_image->width,
																																			 'height'=>$tablet_image->height,					
																																			 'bannerid'=>$banner_id));
							
							if($tablet_banner->num_rows()>0)
							{
								//Insert Zoneid and bannerid  into ox_ad_zone_assoc for mapping purpose
								$insert_data	=	array(
															'zone_id'=>$zoneid,
															'ad_id'=>$banner_id
														);	
								$tbl_name		=	'ox_ad_zone_assoc';
							
								//Inserts the data into respective table
								$this->mod_common_operations->insert_data($insert_data,$tbl_name);
							}
							
						
						}//End of For each
				
				}
				
				
			}
		
		}
		//exit;
		 /*-----------------------------------------------------------------------
			SEND APPROVED EMAIL TO APPROVED BANNERS TO CLIENTS
			------------------------------------------------------------------------*/
		
		$client_email_id	=	$client_email;
			
					
					$admin_email			=	$this->mod_common_operations->get_admin_email();
					$subject					=	$this->lang->line('label_approval_ banner_activation_subject');
					$email_data			= 	array(
												"client_name"	=>	$client_name,
												"banner_name"		=>	$banner_name
											);
																	
					
				      $content		= $this->load->view('email/Administrator/approve_banner',$email_data,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
						 $message                   	=$mail_content;
			  //$toemail=$registration['email'];
             $config['protocol'] = "sendmail";
             $config['wordwrap'] = TRUE;		
		$config['mailtype'] 		='html';
		$config['charset']			='UTF-8';        
		$this->email->initialize($config);
					$this->email->from($admin_email);
					$this->email->to($client_email_id);        
					$this->email->subject($subject);        
					$this->email->message($message);
					$this->email->send();
								
		//End of Sending Email Notification to the client
	} // End of Banner Approval Functionality
	
	//Rejection of  Banner functionality
	function reject_banner($banner_id =FALSE)
	{
		$dbclientdata	=	$this->mod_approval_settings->get_client_campaign_data($banner_id);
		
		//For Mailing Purpose
		$client_email	=	$dbclientdata->email;
		$client_name	=	$dbclientdata->contact;
		$banner_name	=	$dbclientdata->description;
		
		$campaign_id	=	$dbclientdata->campaignid;
		
		//Rejecting Purpose
		$this->db->where('bannerid',$banner_id);
		$this->db->or_where('master_banner',$banner_id);
		$this->db->delete('ox_banners');
		
		
		 /*-----------------------------------------------------------------------
			SEND REJECTION EMAIL TO REJECTED PAYMENT
			------------------------------------------------------------------------*/
		
		
		$client_email_id	=	$client_email;
			
					
					$admin_email			=	$this->mod_common_operations->get_admin_email();
					$subject					=	$this->lang->line('label_approval_subject');
					$email_data			= 	array(
												"client_name"	=>	$client_name,
												"banner_name"		=>	$banner_name,
												"amount"	=>$amount
											);
																	
					
				      $content		= $this->load->view('email/Administrator/reject_banner',$email_data,TRUE);
			  $data['content']	=$content;
			  $mail_content		=$this->load->view('email/registration/email_tpl', $data, TRUE);
						 $message                   	=$mail_content;
			 // $toemail=$registration['email'];
             $config['protocol'] = "sendmail";
             $config['wordwrap'] = TRUE;		
		$config['mailtype'] 		='html';
		$config['charset']			='UTF-8';        
		$this->email->initialize($config);
					$this->email->from($admin_email);
					$this->email->to($client_email_id);        
					$this->email->subject($subject);        
					$this->email->message($message);
					$this->email->send();						
		//End of Sending Email Notification to the client
		
	}
	
	
	//Publisher Share
	public function publisher_share()
	{
	     
	     
 		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
	    $data['getrecord']		=   $this->mod_approval_settings->get_publisher_share();
		$data['page_content']	=	$this->load->view('approvals/publisher_share/publisher_share',$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Approval Type */
	public function approvals_type()
	{
	     
	     
 		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
	     $data['getrecord']		=   $this->mod_approval_settings->get_approvals_type();
		$data['page_content']	=	$this->load->view('approvals/approvals_type/approvals_type',$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Minimum Rate */
	public function minimum_rate()
	{
	     
	     
 		/* Breadcrumb Setup Start */
		
		$link = breadcrumb();
		
		$data['breadcrumb'] = $link;
		
		/* Breadcrumb Setup End */
	     $data['getrecord']		=   $this->mod_approval_settings->get_minimum_rate();
		 $data['page_content']	=	$this->load->view('approvals/minimum_rate/minimum_rate',$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Update Publisher Update Process */
	 function publisher_share_update()
	 {  	
                   $id=$this->mod_approval_settings->publisher_share_check_id();
                  
		 $publisher_share	=	text_db($this->input->post("publisher_share"));
		 $this->form_validation->set_rules('publisher_share','publisher share','required|numeric');
			 
		if($this->form_validation->run() == FALSE)
		{
			   	  
				$this->session->set_userdata('notification_message', $this->lang->line("label_fill_publisher_share").'');
				redirect("admin/approvals/publisher_share");
		}
		else
		{	


				$publisher_share = trim($publisher_share);
				if ((preg_match('/^\d{2}$/', round($publisher_share))) || (preg_match('/^\d{1}$/', round($publisher_share))) || (ceil($publisher_share) == "100") || (round($publisher_share) == "0")) 
				{
								
						$publisher_share = trim($publisher_share);
					
				}
				else
				{
					$this->session->set_userdata('notification_message', $this->lang->line("label_err_publisher_share"));
					redirect("admin/approvals/publisher_share");
				
				}

                       if($id==0)
                       {
                        $inputData = array(
								"publisher_share"	=>	$publisher_share,
							   );
			
			$this->mod_approval_settings->publisher_share_insert($inputData);
                        $this->session->set_flashdata('message', $this->lang->line("label_publisher_share_updated_success"));
			redirect("admin/approvals/publisher_share");
                        }
                    else
                     {  
	 		$inputData = array(
								"publisher_share"	=>	$publisher_share,
							   );
			
			$this->mod_approval_settings->publisher_share_update($inputData,$id);
			$this->session->set_flashdata('message', $this->lang->line("label_publisher_share_updated_success"));
			redirect("admin/approvals/publisher_share");
                      }
		 }            
    }
	
	
	/*Approval Type Update */
	 function approvals_type_update()
	 {  	
		  
			$apptype	=	trim($this->input->post("apptype"));
								 
			$this->form_validation->set_rules('apptype','publisher share','required|numeric');
			            		 
			if($this->form_validation->run() == FALSE)
			{
			   	  
				$this->session->set_userdata('notification_message', $this->lang->line("label_fill_approvals_type").'');
				redirect("approvals_settings/approvals_type");
			}
			else
			{	
				$inputData = array(
								"apptype"	=>	$apptype,
							   );
			
				$this->mod_approval_settings->approvals_type_update($inputData);
				$this->session->set_flashdata('message', $this->lang->line("label_publisher_register_user_verify_updated_success"));
				redirect("admin/approvals/approvals_type");
		     }
    }
	
	/*Minimum Rate Update Process */	
	function minimum_rate_update()
	{  	
		$Amount	=	trim($this->input->post("Amount"));
								 
		$this->form_validation->set_rules('Amount','Minimum Amount','required');
			            		 
		if($this->form_validation->run() == FALSE)
		{
			   	  
				$this->session->set_userdata('notification_message', $this->lang->line("label_fill_minimum_amount").'');
				redirect("admin/approvals/minimum_rate");
		}
		else
		{	
			$inputData = array(
								"Amount"	=>	$Amount,
							   );
							   
			$id=$this->mod_approval_settings->check_minimum_rate();
			$this->mod_approval_settings->minimum_rate_update($inputData,$id);
			$this->session->set_flashdata('message', $this->lang->line("label_publisher_min_amt_updated_success"));
			redirect("admin/approvals/minimum_rate");
		}            
    }
	
	/* Payment Functionality */
	function payments($start =0)
	{
		//echo 'test advertiser';
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
				
		$limit = $this->page_limit;
		$list_data = $this->mod_approval_settings->get_payment_list();
		
		$config['per_page'] = $limit;
		$config['base_url'] = site_url("admin/approvals/payments/");
		 
		$config['uri_segment'] = 4;
		$config['total_rows'] 	= count($list_data);//'5';
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		$list_data = $this->mod_approval_settings->get_payment_list($limit,$start);
		$data['payments_list']	=$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_approval_payments_title');
		
			/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($start ==0)?1:($start + 1);
		$data['page_content']	= $this->load->view("approvals/payments/payment_list",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	//Approval Payment Process
	function approval_payment($pay_appr_id ='')
	{
			
			if($pay_appr_id !='')
			{
				//Separating and Getting the Publisher id and payment id
				list($pub_id,$pay_id)	=	explode('-',$pay_appr_id);
				
				//Getting the Payment type,email and amount from oxm_admin_payment
				$payment_details	=	$this->db->select('paymenttype,name,email,amount ')->get_where('oxm_admin_payment',array('publisherid'=>$pub_id,
																																						'id'=>$pay_id))->row();
				
				
				$payment_type		=	$payment_details->paymenttype; //Payment Type eg:paypal
				$email			=	$payment_details->email; // Email
				$amount			=	$payment_details->amount; //Amount it is paid
				
				$mail	=	$this->db->get_where('ox_affiliates',array('affiliateid'=>$pub_id))->row();
							
					if($mail !='')
					{
								
						// Retreive the pub details.
						$pub_email	=	$mail->email;
						$contact	=	$mail->contact;
						$name	=	$mail->name;
						$cur_date	=	date("Y-m-d H:i:s");

						/*-----------------------------------------------------------------------
								SEND INTIMATION BY EMAIL TO PUBLISHER 
						------------------------------------------------------------------------*/

						$admin_email		=	$this->mod_common_operations->get_admin_email();
						
						$email_data		= 	array(	"name"		=>$contact,
											"date" 		=>$cur_date,
											"website"	=>$name,
											"amount"	=>$amount	
											);
																	
						$subject				=$this->lang->line('label_payment_approval_subject');
						$content				=$this->load->view('email/Administrator/approve_payment_publisher',$email_data,TRUE);
						$data['content']		=$content;
						$mail_content			=$this->load->view('email/registration/email_tpl', $data, TRUE);
						
						$message               	=$mail_content;
						$config2['protocol'] 	="sendmail";
						$config2['wordwrap'] 	= TRUE;		
						$config2['mailtype']		='html';
						$config2['charset']  	='UTF-8';
						 
						$this->email->initialize($config2);
						$this->email->from($admin_email);
						$this->email->to($pub_email);        
						$this->email->subject($subject);        
						$this->email->message($message);
						$this->email->send();
					
					}				
		
				//checks whether Payment type is Paypal or not
				if($payment_type !='paypal')
				{
					
						$today	=date('Y-m-d H:i:s');					
						$update_data		=	array('status'=>1,'clearing_date'=>$today);
						$where_arr	=	array('publisherid'=>$pub_id,'id'=>$pay_id);
						$tbl_name	=	'oxm_admin_payment';
					

						//Update the  oxm_admin_payment status to 1.
						$this->mod_common_operations->update_data($update_data,$where_arr,$tbl_name);
					
					
						$insert_c_data	=	array('client_id'=>2,'payment_request_id'=>$pay_id,'payment_gross'=>$amount,'payer_status'=>'verified','payment_status'=>'Completed','payment_paid_date'=>$today);
						$tbl_name	=	'oxm_paypal_report';
						//Update the  oxm_admin_payment status to 1.
						$this->mod_common_operations->insert_data($insert_c_data,$tbl_name);
					
					
					
					
					//Notification Payment 
					$this->session->set_flashdata('success_message', $this->lang->line('notification_payment_sel_approved'));
					redirect("admin/approvals/payments");
					
				}else{
					
					$this->session->set_userdata('pub_id',$pub_id);
					$this->session->set_userdata('pay_id',$pay_id);
					$this->session->set_userdata('amount',$amount);
					$this->session->set_userdata('email',$email);
					redirect("admin/admin_paypal_payment/auto_form");
				
				}
			}
	}
	
		//view details about advertisers and Publishers
	function view($user_type='',$user_id='')
	{
			if($user_id !='' && $user_type !='')
			{
					if($user_type ==='advertiser' || $user_type ==='trafficker')
					{
						
						$user_type	=	strtoupper($user_type); //Pass User type 
						$user_details	=		$this->mod_approval_settings->get_user_approval_details($user_id,$user_type);
						$data['user_details']	=	$user_details[0];
						
						$read_status	=	$this->db->select('read_status')->get_where('oxm_approval_notification',array('approval_user_id '=>$user_id))->row();
					
						//Update the  Read STatus to 1 for notification purpose
						if($read_status->read_status	=='0')
						{
								$this->db->update('oxm_approval_notification',array('read_status'=>1),array('approval_user_id' => $user_id));
						}
						
						$this->load->view('admin/approval_user_details',$data);
					}
			}else
			{
				redirect('admin/approvals');
			}
	}
	
	public function show_text_banner($banner_id=0){
		
		$bannerid				=	$banner_id;
		$showtb					=	$this->mod_approval_settings->retrieve_textbanner(array("bannerid"=>$banner_id));
		if($showtb != FALSE){
			$data['textbanner']	=	$showtb[0]->bannertext;
		}
		else
		{
			$data['textbanner']	=	'';
		}
		$this->load->view('admin/approvals/banners/show_text_banner',$data);
	}
	
}

/* End of file approvals.php */
/* Location: ./modules/admin/approvals.php */

