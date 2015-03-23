<?php
class Zones extends CI_Controller {  
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 5; 
	var $linked_type = 0;
	
	
	public function __construct() 
    	{    
		parent::__construct();	
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}	
		
		if($this->session->userdata('session_publisher_id') == '' OR $this->session->userdata('session_user_type') != 'TRAFFICKER'){
			redirect("site");
		}
		
		/* Models */ 
		$this->load->model("mod_zones");
		$this->load->model("statistics/mod_publisher");
		
    	}
		
	/* Zones Landing Page */
	public function index() 
	{ 
		$this->listing();	
	}
	
	/* Zones listing Page */
	public function listing($start=0) 
	{ 		
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		$limit	 = 	$this->page_limit;
		$affiliateid = $this->session->userdata('session_publisher_id');// SET SESSION VALUE
		
					
		$where 	="affiliateid ={$affiliateid} AND (master_zone =-1 OR master_zone =-2 OR master_zone =-3)";				
		/*--------------------------------------------------------------------
			Get the all zones matching above from db for listing
		---------------------------------------------------------------------*/				
		$list_data = $this->mod_zones->list_zones($where);		
		
		
		/*--------------------------------------------------------------
		 				Pagination  Config Setup
		 ---------------------------------------------------------------*/		
		$config['per_page'] 			= 	$limit;
		$config['base_url'] 			= 	site_url("publisher/zones/listing/");
		$config['uri_segment'] 			= 	4;
		$config['total_rows'] 			= 	count($list_data);
		$config['next_link'] 			= 	$this->lang->line("pagination_next_link");
		$config['prev_link'] 			= 	$this->lang->line("pagination_prev_link");		
		$config['last_link'] 			= 	$this->lang->line("pagination_last_link");		
		$config['first_link'] 			= 	$this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);	
		
		/*--------------------------------------------------------------------
		 			Get the all zones from db for pagination
		 ---------------------------------------------------------------------*/		
		$list_data = $this->mod_zones->list_zones($where,$start,$limit=false);
				
		$data['zones_list']				=	$list_data;
		$data['affiliateid']			=	$affiliateid;
		
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		 
		$data['page_title'] 	=	$this->lang->line('label_inventory_zones_page_title');
		
		//GET IMP,CLIKS AND REVENUE for lsited zones
		$search_arr							= 	array();
		$search_arr['from_date']			=	$this->mod_publisher->get_start_date($this->session->userdata('session_publisher_account_id'));
		$search_arr['to_date']				=	date("Y/m/d");
		$search_arr['search_type']			=	"all";
		
		$pub_account_id						=	$this->session->userdata('session_publisher_account_id'); // SET SESSION VARIABLE
		
			
		$data['stat_list']	=	$this->mod_publisher->get_publishers($pub_account_id,$search_arr);
		
		
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	=	$this->load->view("publisher/zones/zones_list",$data,true);
		$this->load->view('publisher_layout',$data);
	}

	
	/* Inventory/Add new zones controller */	
	public function add_zones($affiliateid=0) 
	{	
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		$data['affiliateid']	=	$affiliateid;
		
		$data['mob_screens']	= $this->mod_zones->banner_screen();
		/*--------------------------------------------------------------------
		 	Get the website(advertiser) name from db for using in select box
		 ---------------------------------------------------------------------

		$aff_list = $this->mod_zones->get_affiliates_list();
        	$data['aff'] = $aff_list;*/
		
		/*------------------------------------------------------------------------
		 	Get the pricing model from db for using in select box
		 ---------------------------------------------------------------------------*/

		$price_list = $this->mod_zones->get_pricing_model_list();
		$data['price_model'] = $price_list;
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		 
		$data['page_content']	= $this->load->view("zones/add_zones",$data,true);
		$this->load->view('publisher_layout',$data);
	
	}
	
	public function insert($affiliateid=0) 
	{ 
		
				
		//Test Data
		$affiliateid = $this->session->userdata('session_publisher_id'); 
		
		/* Form Validation */
			
		$this->form_validation->set_rules('zonename', 'Zone Name', 'required|is_unique[ox_zones.zonename]');
		$this->form_validation->set_rules('pricing_model', 'Pricing Model', 'required');
		
		
		
		 if ($this->form_validation->run() == FALSE)
		{
		   /* Form Validation is failed. Redirect to Add Zone Form */
		   
		  $this->session->set_userdata('zone_error', $this->lang->line('label_something_missed'));
		   $this->add_zones($affiliateid=0);
		}
		else
		{
		
						
						$date				=	date("Y-m-d H:i:s");
						$zone_name			=	$this->input->post('zonename');	
						$width			=	0;
						$height			=	0;
						$revenue_type		=	$this->input->post('pricing_model');
						$rtb			=	$this->input->post('rtb');
						$revenue_type_name  = 	$this->mod_zones->get_model($revenue_type);
						$delivery			=	($this->input->post("zone_type")==1 OR $this->input->post("zone_type")==3)?0:3;
						$master_zone		=	-2;
						$def_zone_type		=	 3;
						
						
						
						switch($this->input->post("zone_type")){
							case "1":
								$master_zone	=	-2;
								break;
							case "2":
								$master_zone	=	-1;
								break;
							case "3":
								$master_zone	=	-3;
								break;
						}
							
							$zone_data_arr = array(
											"affiliateid"	=> $affiliateid,
											"zonename"		=> text_db($zone_name),
											"delivery"		=> $delivery,
											"zonetype"		=> $def_zone_type,
											"updated"		=> $date,
											"width"			=> $width,
											"height"		=> $height,
											"revenue_type"	=> $revenue_type,
											"rtb"		=> $rtb,
											"master_zone"	=> $master_zone,
											"pricing"		=> $revenue_type_name
										   );
						
							if($master_zone ==-3) // TABLET ZONE
							{
								$banner_size	=	$this->input->post('zone_size');
								
								/*--------------------------------------------------------------
											Insert the get banner size from db
								----------------------------------------------------------------*/
								$bansize=$this->mod_zones->get_bansize($banner_size);
								
								foreach($bansize as $value)
								{
									$zone_data_arr['width']		=	$value->width;
									$zone_data_arr['height']	=	$value->height;
									
									}			
													
								$this->mod_zones->insert_zonedata($zone_data_arr);
							}
							else if($master_zone ==-1) // TEXT ZONE
							{
											
								$this->mod_zones->insert_zonedata($zone_data_arr);
							}
							else if($master_zone ==-2)  // IMAGE BANNER ZONE
							 {
								   		/*--------------------------------------------------------------
													Insert the get banner size from db
										----------------------------------------------------------------*/
								   		
										$sizes	=	$this->mod_zones->get_banner_sizes();
										
										
										foreach($sizes as $bs) 
										 {
											$size[$bs->screen]['width'] 	=$bs->width;
											$size[$bs->screen]['height']	=$bs->height;
										 }
										 
										 //INSERT MASTER ZONE RECORD
										 
										 $master_zone_data				=	 $zone_data_arr;
										 
										 $master_zone_data['width']		=	$size['master']['width'];
										 $master_zone_data['height']	=	$size['master']['height'];
								
									     $parent_zone_id = 	$this->mod_zones->insert_zonedata($master_zone_data);
										 
										 //INSERT ZONE 
										 
										 $zone_rec_1				=	 $zone_data_arr;
										 
										 $zone_rec_1['width']		=	$size['master']['width'];
										 $zone_rec_1['height']		=	$size['master']['height'];
										 $zone_rec_1['master_zone']	=	$parent_zone_id;
										 
										 $_zone_id_1 = $this->mod_zones->insert_zonedata($zone_rec_1);	
										 
										  //INSERT ZONE CHILD 1
										 
										 $zone_child_1				=	 $zone_data_arr;
										 
										 $zone_child_1['width']		=	$size['child1']['width'];
										 $zone_child_1['height']		=	$size['child1']['height'];
										 $zone_child_1['master_zone']	=	$parent_zone_id;
										
										 $_zone_id_2 =  $this->mod_zones->insert_zonedata($zone_child_1);	
									
										  //INSERT ZONE CHILD 2
										 
										 $zone_child_2				=	 $zone_data_arr;
										 
										 $zone_child_2['width']		=	$size['child2']['width'];
										 $zone_child_2['height']		=	$size['child2']['height'];
										 $zone_child_2['master_zone']	=	$parent_zone_id;
										 $_zone_id_3 =  $this->mod_zones->insert_zonedata($zone_child_2);	

										  //INSERT ZONE  CHILD 3
										 
										 $zone_child_3				=	 $zone_data_arr;
										 
										 $zone_child_3['width']		=	$size['child3']['width'];
										 $zone_child_3['height']		=	$size['child3']['height'];
										 $zone_child_3['master_zone']	=	$parent_zone_id;
										 $_zone_id_4 =  $this->mod_zones->insert_zonedata($zone_child_3);	
									
										 // INSERT MOBILE ZONES AND ITS CHILD MAPPING
										
										$m_zones     =array(
																"masterzoneid"=>$parent_zone_id,
																"mz1"=>$_zone_id_1,
																"mz2"=>$_zone_id_2,
																"mz3"=>$_zone_id_3,
																"mz4"=>$_zone_id_4
														);
											
									  $this->mod_zones->insert_zonemaster($m_zones);	
				}
									
				$this->session->set_flashdata('zones_success_message',$this->lang->line('label_inventory_zone_added_success_msg'));
				
				redirect("publisher/zones");				
		}
		
	}
	

	/* admin/inventory_zones/Edit zones controller */
	
	public function edit_zones($zoneid=0) 
	{ 
		
		
		$affiliateid = $this->session->userdata('session_publisher_id');
		$data['mob_screens']	= $this->mod_zones->banner_screen();
		/*-----------------------------------------------------------
		 			Breadcrumb Setup Start
		 ------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
		
		/*-------------------------------------------------------------
		 	Authorisation Check
		 -------------------------------------------------------------*/		
		$dup_data = $this->mod_zones->check_pub_authorisation($affiliateid,$zoneid);
		if($dup_data!='')
		{
			/*-----------------------------------------------------------
			 	Get the zones data from db by zoneid for edit
			 ------------------------------------------------------------*/
		
			$record = $this->mod_zones->get_edit_zones($affiliateid,$zoneid);
			$data['record'] = $record;
			
			/*---------------------------------------------------------------
			 	Get the pricing model from db for listing all in select box
			 ----------------------------------------------------------------*/
		
			$model_list = $this->mod_zones->get_pricing_model_list();
	       		$data['mlist'] = $model_list;
		
			/*-----------------------------------------------------------
			 	Get the pricing model from db for selecting in select box
			 ------------------------------------------------------------*/
	
			$model = $this->mod_zones->get_pricing_model($zoneid);
			$data['model'] = $model;
		
			$data['affiliateid']= $this->session->userdata('session_publisher_id');//$affiliateid;
			$data['zoneid']=$zoneid;
		
		
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
		
			$data['page_content']	= $this->load->view("zones/edit_zones",$data,true);
			$this->load->view('publisher_layout',$data);
		}
		else
		{
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
	
			$data['page_content']	= $this->load->view("zones/no_page",$data,true);
			$this->load->view('publisher_layout',$data);	
		}
		
		
	}

	/* admin/inventory_zones/Update zones controller */
	
	public function update_zones($zoneid=0) 
	{ 
			

			
			$affiliateid    = 	$this->session->userdata('session_publisher_id');

			/* Form Validation */
				
		
			$this->form_validation->set_rules('zonename', 'Zone Name', 'required|callback_name_check');
            		$this->form_validation->set_rules('pricing_model', 'Pricing Model', 'required');
			
			
			
			 if ($this->form_validation->run() == FALSE)
            {
					   /* Form Validation is failed. Redirect to update Zone Form */
						$this->session->set_userdata('zone_error', $this->lang->line('label_something_missed'));
											    	
						$this->edit_zones($zoneid,$affiliateid);
            }
           
			else
			{	
								
				//$date		=	date("Y-m-d H:i:s");
				$zonename	=	$this->input->post('zonename');
				$zoneid		=	$this->input->post('zoneid');
				$rtb		=	$this->input->post('rtb');
				$model_value	=	$this->input->post('pricing_model');
				
				/*--------------------------------------------------------------------
		 			Get the revenue type from db as per selected in select box
		 		---------------------------------------------------------------------*/
				
				$pricing			= $this->mod_zones->get_model($model_value);
				
								
				/*-----------------------------------------------------------
		 				Updates the zones data to db 
		 		------------------------------------------------------------*/
				
				if($this->input->post('pricing_model') != $this->input->post('prev_pricing_model')){
					
					// Remove All Previously Linked Campaigns and Banners while pricing model changing
					
					$this->mod_zones->del_ads_placement_mapping($zoneid);
					
				}	
				
				$this->mod_zones->update_zones($zonename, $pricing, $zoneid,$model_value,$rtb);
				
				$this->session->set_flashdata('zones_success_message',$this->lang->line('label_inventory_zone_update_success_msg'));
				
				redirect('publisher/zones');
				
			}
	
	}
	
	/* publisher/zones/Delete zones controller */
	
	public function delete_zones($zoneid=0) 
	{ 
		if($zoneid !=0)
		{
		
			/*-----------------------------------------------------------
							delete the zones data from db 
			 ------------------------------------------------------------*/
					
			$data['record']=$this->mod_zones->delete_zones($zoneid);
			
			$this->session->set_flashdata('zones_success_message',$this->lang->line('label_inventory_zone_deleted_success_msg'));
			
			redirect('publisher/zones');
		}
		else
		{
			
						
						$zoneid=$this->input->post('check');
						
						$this->mod_zones->delete_zones($zoneid);
						
						$this->session->set_flashdata('zones_success_message', $this->lang->line('label_inventory_selected_zone_deleted_success_msg'));
						
						redirect('publisher/zones');
		}
	}
	
	
	/* Checks for duplication of data */
	 public function username_check()
                         
	{
						
                 
         $query=$this->db->where(array("zonename" =>$this->input->post('zonename')))->get('ox_zones')->num_rows();
                
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
	
	/* Checks for duplication of data 
	 public function username_check2()
                         
	{
						
                 
        	$zonename = $this->input->post('zonename');
			
		$zoneid = $this->input->post('zoneid');
			
		$array = array('zoneid !=' => $zoneid, 'zonename' => $zonename);
			
		$this->db->where($array);
			
		$query=$this->db->get('ox_zones')->num_rows();
		
		if($query >= 1)
				
				{
					echo "no";
					exit;                                                
				}
			
		else
				{
				
				echo "yes";
				exit;
				}

	
	}*/ 
	
	public function name_check()
	{
			
			
			$zonename = $this->input->post('zonename');
			
			$zoneid = $this->input->post('zoneid');
			
			$array = array('zoneid !=' => $zoneid, 'zonename' => $zonename, 'master_zone !=' => $zoneid);
			
			$this->db->where($array);
			
			$query=$this->db->get('ox_zones')->num_rows();

			if($query >= 1)
					
					{
							$this->form_validation->set_message('name_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
							return FALSE;        
					
					}
					
			else
					{
					
						return true;
					
					}		
					
	}
	public function linked_by_banners($zoneid=0, $affiliateid=0, $offset=0){
		$this->linked_type = 1;
		$this->linkedbycampaigns($zoneid, $affiliateid, $offset);
	}
	
	
	/* Publisher/zones/linkedbycampaigns controller*/ 
	public function linkedbycampaigns($zoneid=0, $affiliateid=0, $offset=0) 
	{ 

		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['linked_type'] 	= 	$this->linked_type;
		$data['breadcrumb'] 	= 	$link;
		$data['sel_zone_id'] 	= 	$zoneid;

		/*---------------------------------------------------------------------------------
			Checking affiliateid for authentication
		-----------------------------------------------------------------------------------*/	
		$aff=$this->session->userdata('session_publisher_id');
		if($affiliateid == $aff)
		{	
		
			/*---------------------------------------------------------------------------------
				Checking zones exist for particular affiliateid from db for authentication
			-----------------------------------------------------------------------------------*/	

			$affiliateid = $this->session->userdata('session_publisher_id');// SET SESSION VALUE
	
			$where 	="affiliateid ={$affiliateid} AND (master_zone =-1 OR master_zone =-2 OR master_zone =-3)";				
				
			$list_data = $this->mod_zones->check_zones_for_affiliateid($where);	


			if($list_data == FALSE)
			{
				$data['page_content']	= $this->load->view("zones/no_page",$data,true);
				$this->load->view('publisher_layout',$data);
			}
	
	

	
	
			/*-------------------------------------------------------------
			 	Authorisation Check
			 -------------------------------------------------------------*/		
			$dup_data = $this->mod_zones->check_pub_authorisation($affiliateid,$zoneid);
			if($dup_data!='')
			{
	
				$limit					=	$this->page_limit;
				$campaigns_list 	=	$this->mod_zones->list_linked_campaign_data($affiliateid,$zoneid);
				$banners_list 		=	$this->mod_zones->list_linked_banner_data($affiliateid,$zoneid);
				$data['campaigns_list'] = $campaigns_list;
				$data['banners_list']	= $banners_list;
				$data['associated']		=	$this->mod_zones->getAssoc($zoneid);
				$data['affiliateid']	=	$affiliateid;
				$data['zoneid']			=	$zoneid;
				$data['linked_banners_list']	=	$this->mod_zones->get_linked_banners_by_campaigns($zoneid);


				/*-------------------------------------------------------------
				 	Embed current page content into template layout
				 -------------------------------------------------------------*/

				$data['page_content']	= $this->load->view("publisher/zones/viewlinkedcampaigns",$data,true);
				$this->load->view('publisher_layout',$data);
			}
			else
			{
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
	
			$data['page_content']	= $this->load->view("zones/no_page",$data,true);
			$this->load->view('publisher_layout',$data);
			}
		}
		else
		{
			/*-----------------------------------------------------------
			 	Embed current page content into template layout
			 ------------------------------------------------------------*/
	
			$data['page_content']	= $this->load->view("zones/no_page",$data,true);
			$this->load->view('publisher_layout',$data);
		}
		
	}
	
	public function show_text_banner($banner_id=0){
		$bannerid				=	$banner_id;
		$showtb					=	$this->mod_zones->retrieve_textbanner(array("bannerid"=>$banner_id));
		if($showtb != FALSE){
			$data['textbanner']	=	$showtb[0]->bannertext;
		}
		else
		{
			$data['textbanner']	=	'';
		}
		$this->load->view('publisher/zones/show_text_banner',$data);
	}
	
	public function process($task){
	
		if($task=="linkedbycampaigns"){
			
			$this->linked_type = 0;
			
			$cur_date 	=	date("Y-m-d H:i:s");
			
			$affiliate_id	=	$this->input->post("affiliateid");	
			$sel_zone_id	=	$this->input->post("sel_zone_id");
		
			// Get child zones if exists
			
			$child_zones	=	$this->mod_zones->get_parent_child_zones($sel_zone_id);
			
			// Selected Zones
			$sel_zone_list	=	array();
			
			if($child_zones != false){
				foreach($child_zones as $cData){
					array_push($sel_zone_list,$cData->zoneid);
				}
			}
			else
			{
				array_push($sel_zone_list,$sel_zone_id);
			}
			
			// Delete previous linked ads and Placements for selected zones
			
			$this->mod_zones->del_ads_placement_mapping($sel_zone_list);
				
			$sel_campaign_list		=$this->input->post("sel_campaign");
			
			if(is_array($sel_campaign_list) AND count($sel_campaign_list) > 0){
			
			foreach($sel_campaign_list as $val)
				{
					
					//UPDATE ZONE AND PLACEMENT MAPPING
					
					$insert_data =	array("zone_id" =>$sel_zone_id, "placement_id" =>$val);
					$this->mod_zones->insert_zone_placement_assoc($insert_data);
				
					// GET REVENUE TYPE FROM CAMPAIGN AND MASTER ZONE ID FROM ZONE 
		 
					$where			=	array("campaignid" =>$val);
					$from			=	"ox_campaigns";
					$select			=	"revenue_type";
					$cam			=	$this->mod_zones->get_records($select, $from, $where);
					$cam_type		=	$cam->revenue_type;
					
					$where			=	array("zoneid" =>$sel_zone_id);
					$from			=	"ox_zones";
					$select			=	"master_zone";
					$zone			=	$this->mod_zones->get_records($select, $from, $where);
					$master_zone_id	=	$zone->master_zone;
					
				if($master_zone_id ==	-2)
				{
					// LINK ALL THE CHILD ZONES INTO SELECTED CAMPAIGNS (BANNERS UNDER SELECTED CAMPAIGNS)
					
					$child_zones_list	=	$this->mod_zones->get_child_zones($sel_zone_id);
					if($child_zones_list != FALSE)
					foreach($child_zones_list as $zone_data)
					{
						$zone_name		=$zone_data->zonename;
						$zone_height	=$zone_data->height;
						$zone_width 	=$zone_data->width;
						$zone_type		=$zone_data->revenue_type;
						$child_zone_id  =$zone_data->zoneid;
						
						
						
						// GET ALL BANNERS UNDER SELECTED CAMPAIGNS
						
						
						$banners_list	=	$this->mod_zones->get_banners_list($val);
	
						foreach($banners_list as $banner_data)
						{
							$banner_width	=$banner_data->width;
							$banner_height	=$banner_data->height;
							$ban_id			=$banner_data->bannerid;
							$master_banner  =$banner_data->master_banner;
							
							if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
							{
							  if($master_banner ==	-2) 
							  {
								$insert_zone_assoc	=	array("zone_id" =>$sel_zone_id, "ad_id" =>$ban_id);
							  }
							  else
							  {
								$insert_zone_assoc 	=	array("zone_id" =>$child_zone_id, "ad_id" =>$ban_id);
							  }
							  $zone_assoc	=$this->mod_zones->insert_zone_assoc($insert_zone_assoc);
							}
						  }
					}
				 }
				 else
				 {
	
					$where			=	array("zoneid" =>$sel_zone_id);
					$from			=	"ox_zones";
					$select			=	"*";
					$zone_rs			= $this->mod_zones->get_records($select, $from, $where);
					
					if($zone_rs != false){
						
						$zone_name		=$zone_rs->zonename;
						$zone_height	=$zone_rs->height;
						$zone_width 	=$zone_rs->width;
						$zone_type		=$zone_rs->revenue_type;
						$child_zone_id  =$zone_rs->zoneid;
					}
					
					// GET ALL BANNERS UNDER SELECTED CAMPAIGNS
						
					
					$banners_list	=	$this->mod_zones->get_banners_list($val);
		
					foreach($banners_list as $banner_data)
					{
						$banner_width	=$banner_data->width;
						$banner_height	=$banner_data->height;
						$ban_id			=$banner_data->bannerid;
						
						if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
						{
						  
						  $insert_zone_assoc	=	array("zone_id" =>$sel_zone_id, "ad_id" =>$ban_id);
						 
						  $zone_assoc	=$this->mod_zones->insert_zone_assoc($insert_zone_assoc);
						}
					 }
					
				 }
			  } 
			  
			  $this->session->set_flashdata('success_message', $this->lang->line('label_zone_linked_to_selected_campaign'));
			  redirect("publisher/zones/linkedbycampaigns/".$sel_zone_id.'/'.$affiliate_id);
		}
		else{
			  $this->session->set_flashdata('success_message', $this->lang->line('label_zone_linked_campaign_unlinked'));
			  redirect("publisher/zones/linkedbycampaigns/".$sel_zone_id.'/'.$affiliate_id);	
		}

		}
		else if($task=="linkedbybanners"){
			
			$this->linked_type = 1;	
			
			$cur_date 	=	date("Y-m-d H:i:s");
			
			$affiliate_id	=	$this->input->post("affiliateid");	
			$sel_zone_id	=	$this->input->post("sel_zone_id");
		
			// Get child zones if exists
			
			$child_zones	=	$this->mod_zones->get_parent_child_zones($sel_zone_id);
			
			// Selected Zones
			$sel_zone_list	=	array();
			
			if($child_zones != false){
				foreach($child_zones as $cData){
					array_push($sel_zone_list,$cData->zoneid);
				}
			}
			else
			{
				array_push($sel_zone_list,$sel_zone_id);
			}
			
			// Delete previous linked ads and Placements for selected zones
			
			$this->mod_zones->del_ads_placement_mapping($sel_zone_list);
				
			$sel_banner_list		=$this->input->post("sel_banner");
		
			if(is_array($sel_banner_list) AND count($sel_banner_list) > 0){
			
			foreach($sel_banner_list as $val)
				{
					
					// GET REVENUE TYPE FROM CAMPAIGN AND MASTER ZONE ID FROM ZONE 
		 			
					$cam_type = $this->mod_zones->campaign_revenue_type($val);
					
					
					
					$where			=	array("zoneid" =>$sel_zone_id);
					$from			=	"ox_zones";
					$select			=	"master_zone";
					$zone			=	$this->mod_zones->get_records($select, $from, $where);
					$master_zone_id	=	$zone->master_zone;
					
					$child_zones_list	=	$this->mod_zones->get_child_zones($sel_zone_id);
					
				if($master_zone_id ==	-2 AND $child_zones_list != FALSE)
				{
					// LINK ALL THE CHILD ZONES INTO SELECTED BANNERS
					foreach($child_zones_list as $zone_data)
					{
						$zone_name		=$zone_data->zonename;
						$zone_height	=$zone_data->height;
						$zone_width 	=$zone_data->width;
						$zone_type		=$zone_data->revenue_type;
						$child_zone_id  =$zone_data->zoneid;
						
						
						
						// GET ALL BANNERS UNDER SELECTED BANNERS
						
						
						$banners_list	=	$this->mod_zones->get_banners_list($val,"banners");
	
						foreach($banners_list as $banner_data)
						{
							$banner_width	=$banner_data->width;
							$banner_height	=$banner_data->height;
							$ban_id			=$banner_data->bannerid;
							$master_banner  =$banner_data->master_banner;
							
							if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
							{
							  if($master_banner ==	-2) 
							  {
								$insert_zone_assoc	=	array("zone_id" =>$sel_zone_id, "ad_id" =>$ban_id);
							  }
							  else
							  {
								$insert_zone_assoc 	=	array("zone_id" =>$child_zone_id, "ad_id" =>$ban_id);
							  }
							  $zone_assoc	=$this->mod_zones->insert_zone_assoc($insert_zone_assoc);
							}
						  }
					}
				 }
				 else
				 {
	
					$where			=	array("zoneid" =>$sel_zone_id);
					$from			=	"ox_zones";
					$select			=	"*";
					$zone_rs			= $this->mod_zones->get_records($select, $from, $where);
					if($zone_rs != FALSE){
						$zone_name		=$zone_rs->zonename;
						$zone_height	=$zone_rs->height;
						$zone_width 	=$zone_rs->width;
						$zone_type		=$zone_rs->revenue_type;
						$child_zone_id  =$zone_rs->zoneid;
					}
					
					
					// GET ALL BANNERS UNDER SELECTED CAMPAIGNS
						
					
					$banners_list	=	$this->mod_zones->get_banners_list($val,"banners");
		
					foreach($banners_list as $banner_data)
					{
						$banner_width	=$banner_data->width;
						$banner_height	=$banner_data->height;
						$ban_id			=$banner_data->bannerid;
						
						if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
						{
						  
						  $insert_zone_assoc	=	array("zone_id" =>$sel_zone_id, "ad_id" =>$ban_id);
						 
						  $zone_assoc	=$this->mod_zones->insert_zone_assoc($insert_zone_assoc);
						}
					 }
					
				 }
			  } 
			  
			  $this->session->set_flashdata('success_message1', $this->lang->line('label_zone_linked_to_selected_banners'));
			  redirect("publisher/zones/linked_by_banners/".$sel_zone_id.'/'.$affiliate_id);
		}
		else{
			  $this->session->set_flashdata('success_message1', $this->lang->line('label_zone_linked_banners_unlinked'));
			  redirect("publisher/zones/linked_by_banners/".$sel_zone_id.'/'.$affiliate_id);	
		}
		
		}
		else
		{
			redirect("publisher/zones");
		}
	
	}
	
	public function invocation($zoneid=0) 
	{ 
		$affiliateid = $this->session->userdata('session_publisher_id');
		
		$where	=array("zoneid" =>$zoneid, "affiliateid" =>$affiliateid);
		$status	=$this->mod_zones->get_invocation_delivery($where);
		if($status ===false)
		{
			  $this->session->set_flashdata('zones_success_message', $this->lang->line('label_inventory_zone_invocation_wrong_msg'));
			  redirect("publisher/zones");	
		}
		
		/*-------------------------------------------------------------

			Breadcrumb Setup Start

		 -------------------------------------------------------------*/

		$link = breadcrumb();

		$data['breadcrumb'] 	= $link;	

		/*-------------------------------------------------------------

		 	Embed current page content into template layout

		 -------------------------------------------------------------*/

		 $delivery		=		$this->mod_zones->get_delivery($zoneid);
		
		 $master_zone	=		$this->mod_zones->get_master_zone($zoneid);

		 $data['zoneid']		=		$zoneid;

		 //echo $delivery.'!@#$'.$master_zone;exit;

			if($delivery=="0")
			{
				if($master_zone ==-3) // Tablet Zone

					{
						$data['zonedata']	=	$this->mod_zones->get_width_height($zoneid);

						$data['tags']   				=		"js";

						

						$data['page_content']			= 		$this->load->view("publisher/zones/tablet_code",$data,true);

						

						$this->load->view('publisher_layout',$data);

					}

			

			if($master_zone ==-2) // Mobile Img Zone
			{
				$data['tags']   			=		"image";
				$data['page_content']		= 		$this->load->view("publisher/zones/image_code",$data,true);
				$this->load->view('publisher_layout',$data);

			}

			}						

			if($delivery=="3")
			{
					$data['tags']   							=		"image";
					$data['page_content']					= 		$this->load->view("publisher/zones/invcode_text",$data,true);
					$this->load->view('publisher_layout',$data); // Text zone
			}		
	}

	public function rtb_bidding()
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;

		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['bid_rate']	=	$this->mod_zones->bid_amount_details();

		$data['page_content']	=	$this->load->view("publisher/rtb_bidding",$data,true);
		$this->load->view('publisher_layout',$data);
		
	}

	public function rtb_bidding_update()
	{
		$this->form_validation->set_rules('rtb_rate', 'RTB Bid Rate', 'trim|required|is_numeric');
		if($this->form_validation->run() == FALSE)
		{
			//echo validation_errors();exit;
			$this->rtb_bidding();
		}
		else
		{
			$this->mod_zones->rtb_update_bidding();			
			redirect("publisher/zones/rtb_bidding");
		}			
	}
}	

/* End of file zones.php */
/* Location: ./modules/publisher/controllers/zones.php */

