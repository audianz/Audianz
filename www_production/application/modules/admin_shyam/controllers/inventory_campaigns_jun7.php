<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_campaigns extends CI_Controller {
	/* Page Limit */	
	var $page_limit = 10;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mod_campaign');
                $this->load->model('mod_banner');
	}
	
	/* Campaigns Page */
	public function index()
	{
		$this->listing();		
	}

	/* Campaigns Listing Page */	
	public function listing($status='',$start=0)
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
                 /*-------------------------------------------------------------
		 	Campaigns Based on Advertiser Id
		 -------------------------------------------------------------*/
                $data['sel_adv'] = 0;
                $data['sel_stat'] = $status;
                /*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
		$limit = $this->page_limit;		
		if($status!='' && $status!='all' )
		{
			if($status=='active')
                        {
                            $cstat=0;
                        }
                        elseif($status=='inactive')
                        {
                            $cstat=1;
                        }
                        elseif($status=='awaiting')
                        {
                            $cstat=2;
                        }
			elseif($status=='completed')
                        {
                            $cstat=3;
                        }
                        else
                        {
                            $cstat=0;
                        }
			$where_arr = array('status'=>$cstat);
			$where = 0;
		}
		else {
			$where_arr = 0;
			$where = 0;
			$status = 'all';			
		}

        /* Hard Coded value for select all campaigns*/
		$filter = 2;
		
		$list_data = $this->mod_campaign->get_campaigns($filter,$where_arr);
		/* Total Campaigns Count */	
		$data['tot_list'] = $list_data;
			
		/*$config['per_page']     = $limit;
		$config['base_url']     = site_url("admin/inventory_campaigns/listing/".$status);
        $config['uri_segment']  = 5;
		$config['total_rows'] 	= count($list_data);
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);		*/
		$list_data = $this->mod_campaign->get_campaigns($filter,$where_arr);
		$data['campaign_list']	=	$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_page_title');		
		
		/*-------------------------------------------------------------
		 	Total Counts for Total, Active and Inactive Campaigns
		--------------------------------------------------------------*/
		$where_tot = array();
		$tot_data = $this->mod_campaign->get_campaigns($filter,$where_tot);
		$data['tot_data'] = count($tot_data);
		$where_act = array('status'=>0);
		$active_data = $this->mod_campaign->get_campaigns_count($filter,$where_act);
		$data['active_data'] = count($active_data);

		$where_inact = array('status'=>1);
		$inactive_data = $this->mod_campaign->get_campaigns_count($filter,$where_inact);
		$data['inactive_data'] = count($inactive_data);

                $where_awt = array('status'=>2);
		$awaiting_data = $this->mod_campaign->get_campaigns_count($filter,$where_awt);
		$data['awaiting_data'] = count($awaiting_data);

		$where_comp = array('status'=>3);
		$completed_data = $this->mod_campaign->get_campaigns_count($filter,$where_comp);
		$data['completed_data'] = count($completed_data);
		
		
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("admin/campaign/list",$data,true);
		$this->load->view('page_layout',$data);
	// print_r($data);
	// exit;
	}

        /* Campaigns Listing Page based on Advertiser ID */
	public function listing_adv($status='', $adv='', $start=0)
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
		 	Campaigns Based on Advertiser Id
		 -------------------------------------------------------------*/
                $data['sel_adv'] = $adv;
                $data['sel_stat'] = $status;
                /*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
		$limit = $this->page_limit;
		if($status!='' && $status!='all' )
		{
			if($status=='active')
                        {
                            $cstat=0;
                        }
                        elseif($status=='inactive')
                        {
                            $cstat=1;
                        }
                        elseif($status=='awaiting')
                        {
                            $cstat=2;
                        }
			elseif($status=='completed')
                        {
                            $cstat=3;
                        }
                        else
                        {
                            $cstat=0;
                        }
			$where_arr = array('status'=>$cstat);
			$where = array();
		}
		else {
			$where_arr = array();
			$where = array();
			$status = 'all';
		}

                if($adv!='')
                {
                    $where_arr['ox_campaigns.clientid'] = $adv;
                    $where['ox_campaigns.clientid'] = $adv;
                }

                /* Hard Coded value for select all campaigns*/
		$filter = 2;

		$list_data = $this->mod_campaign->get_campaigns($filter,$where_arr);
		/* Total Campaigns Count */
		$data['tot_list'] = $list_data;

		$config['per_page']     = $limit;
		$config['base_url']     = site_url("admin/inventory_campaigns/listing_adv/".$status."/".$adv);
                /* Get Pagination Values using URI Segment */
		$config['uri_segment']  = 6;
		$config['total_rows'] 	= count($list_data);
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");
		$config['last_link'] 	= $this->lang->line("pagination_last_link");
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);
		$list_data = $this->mod_campaign->get_campaigns($filter,$where_arr,$start,$limit);
		$data['campaign_list']	=	$list_data;

		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_advertisers_page_title');

		/*-------------------------------------------------------------
		 	Total Counts for Total, Active and Inactive Campaigns
		--------------------------------------------------------------*/
		$where_tot = array('ox_clients.clientid'=>$adv);
		$tot_data = $this->mod_campaign->get_campaigns_count($filter,$where_tot);
		$data['tot_data'] = count($tot_data);
		$where_act = array('ox_campaigns.status'=>0,'ox_clients.clientid'=>$adv);
		$active_data = $this->mod_campaign->get_campaigns_count($filter,$where_act);
		$data['active_data'] = count($active_data);

		$where_inact = array('ox_campaigns.status'=>1,'ox_clients.clientid'=>$adv);
		$inactive_data = $this->mod_campaign->get_campaigns_count($filter,$where_inact);
		$data['inactive_data'] = count($inactive_data);

        	$where_awt = array('ox_campaigns.status'=>2,'ox_clients.clientid'=>$adv);
		$awaiting_data = $this->mod_campaign->get_campaigns_count($filter,$where_awt);
		$data['awaiting_data'] = count($awaiting_data);

		$where_comp = array('ox_campaigns.status'=>3,'ox_clients.clientid'=>$adv);
		$awaiting_data = $this->mod_campaign->get_campaigns_count($filter,$where_comp);
		$data['completed_data'] = count($awaiting_data);

		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("admin/campaign/list",$data,true);
		$this->load->view('page_layout',$data);

	}

	/* Get Active Campaigns List	
	public function active() 
	{
		$this->status = '0';
		$this->listing(0,$this->status);	
	}*/
	/* Get Inactive Campaigns List	
	public function inactive() 
	{
		$this->status = '1';
		$this->listing(0,$this->status);	
	} */
        
        /* Add New Campaign */
        public function add_campaign($advid=0)
        {
            /*-------------------------------------------------------------
		 	Breadcrumb Setup Start
            -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
		 	Get Advertiser ID from Advertiser List
            -------------------------------------------------------------*/
            $data['adv_id'] = $advid;
            /*-------------------------------------------------------------
		 	Get Advertiser List
            -------------------------------------------------------------*/
            $adv_list = $this->mod_campaign->get_advertiser_list();
            $data['advertiser'] = $adv_list;
            $data['advertisername'] = '';
			
			 /*-------------------------------------------------------------
		 	Get Categories List
            -------------------------------------------------------------*/
            $category_list = $this->mod_campaign->getCategory();
            $data['category_list'] = $category_list;
			
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content'] = $this->load->view("admin/campaign/add", $data,true);
            $this->load->view('page_layout',$data);
        }

        /* Add New Campaign Process */
        public function add_campaign_process($advid=0)
        {
			if($advid == 0 || $advid == '')
			{
				$advid='';
			}
			
	    	$pmodel = $this->input->post('pricing_model');
            $sdate = $this->input->post('start_date');
            $edate = $this->input->post('end_date');
            
			/* Form Validations for Add Campaign */
            if($sdate==0)
			{
                $campstart = date('Y-m-d H:i:s');
            }
			else
			{
                $this->form_validation->set_rules('campstart', 'Campaign Start Date', 'required');
                $campstart   = $this->input->post('campstart');
                $campstart = date('Y-m-d H:i:s', strtotime($campstart));
            }
            
			if($edate ==0)
			{
                $campend = NULL;
            }
			else
			{
                $this->form_validation->set_rules('campend', 'Campaign End Date', 'required');
                $campend    = $this->input->post('campend');
                $campend    = date('Y-m-d H:i:s', strtotime($campend));
            }
			
            $this->form_validation->set_rules('campname', 'Campaign Name', 'required');
            $this->form_validation->set_rules('advertiser', 'Advertiser Name', 'required');
	    	$this->form_validation->set_rules('start_date', 'Start Date', 'required');
	    	$this->form_validation->set_rules('end_date', 'End Date', 'required');
			//$this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('pricing_model', 'Pricing Model', 'required');
            $this->form_validation->set_rules('weight', 'Campaign Weight', 'required|numeric');
            $this->form_validation->set_rules('budget', 'Campaign Budget', 'required|numeric');
            if($pmodel ==1)
			{
                $this->form_validation->set_rules('impression', 'Rate per Impression', 'required|numeric');
                $revenue  =$this->input->post('impression');
            }
			else if($pmodel ==2)
			{
                $this->form_validation->set_rules('clicks', 'Rate per Clicks', 'required|numeric');
                $revenue  =$this->input->post('clicks');
            }
			else if ($pmodel==3)
			{
                $this->form_validation->set_rules('conversion', 'Rate per Conversion', 'required|numeric');
                $revenue  =$this->input->post('conversion');
            }
            if ($this->form_validation->run() == FALSE)
            {
               /* Form Validation is failed. Redirect to Add Campaign Form */
               $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
               $this->add_campaign($advid);
            }
            else
            {
                if($_POST)
                {
                   $campname    = $this->input->post('campname');
                   $advertiser  = $this->input->post('advertiser');
                   $weight      = $this->input->post('weight');
                   $budget      = $this->input->post('budget');
				   $category	= $this->input->post('category');
                   $currdate    = date("Y-m-d");
                   
                   // Realtime Bidding Concept
					 $rtb 		= $this->input->post('rtb');
					if($rtb=='on')
					   {
							$realtime = 1;
					   }
					   else
					   {
							$realtime = 0;
					   }
                   
				   /* Check Duplication for Campaign Name based on Client ID */
                   $where_camp = array('campaignname'=>$campname,'clientid'=>$advertiser);
                   $camp_check  = $this->mod_campaign->check_campaign_duplication($where_camp);
                   if(count($camp_check)==0)
                   {
                       /* Define Campaign Status based on Start Date and End Date */
                       if($campend=='')
                       {
                            $startvalue  = $this->mod_campaign->compare_startdate_today($campstart, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaign->check_advertiser_balance($advertiser);
                            if(count($accBal)>0)
                            {
                            	$adBbal = $accBal[0]->accbalance;
                            }
                            else
                            {
                            	$adBbal = 0;
                            }
                            if($adBbal>0)
                            {
	                        if($startvalue>0)
                                {
                                    $status     = "2";
                                    $inactive   = "1";
                                }
                                else
                                {
                                    $status     = "0";
                                    $inactive   = "0";
                                }
                            }
                            else
                            {
                                $status     = "1";
                                $inactive   = "1";
                            }
                       }
                       else
                       {
                            $startvalue  = $this->mod_campaign->compare_startdate_today($campstart, $currdate);
                            $endvalue    = $this->mod_campaign->compare_enddate_today($campend, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaign->check_advertiser_balance($advertiser);
                            if(count($accBal)>0)
                            {
                            	$adBbal = $accBal[0]->accbalance;
                            }
                            else
                            {
                            	$adBbal = 0;
                            }                            
                            if($adBbal>0)
                            {
                            	//echo "test1";exit;
								if($startvalue>0 && $endvalue>0)
                                {
                                    $status = '2';
                                    $inactive = '1';
                                }
                                elseif($startvalue<0 && $endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '1';
                                }
                                elseif($endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '1';
                                }
                                else
                                {
                                    $status = '0';
                                    $inactive = '0';
                                }
                            }
                            else
                            {
                            	//echo "test2";exit;
                                $status = '1';
                                $inactive = '1';
                            }                            
                       }

                       /* Add Campaign Parameters */
                        $add_campaign = array(
                            'campaignname'=>mysql_escape_string($campname),
                            'clientid'=>$advertiser,
                            'status_startdate'=>$sdate,
                            'status_enddate'=>$edate,
                            'activate_time'=>$campstart,
                            'expire_time'=>$campend,
                            'revenue_type'=>$pmodel,
                            'weight'=>$weight,
                            'revenue'=>$revenue,
                            'status'=>$status,
                            'inactive'=>$inactive,
                            'updated'=>date('Y-m-d H:i:s'),
							'catagory' => @implode(",", $category),
							'rtb'	=>	$realtime
                        );
                        /* Campaign Insert Method and Get Last Insert ID */
                        $last_camp_id = $this->mod_campaign->add_new_campaign($add_campaign);

                        if($last_camp_id){
							
							if($realtime == 1)
							{
								/* Add Campaign Budget Parameters */
								$add_rtb_data = array(
								'campaign_id'=>$last_camp_id,
								'bid_rate' => $revenue
								);
								$this->db->insert('aff_rtb',$add_rtb_data);
							}

                            /* Add Campaign Budget Parameters */
                            $add_campaign_budget = array(
                            'clientid'=>$advertiser,
                            'campaignid'=>$last_camp_id,
                            'budget'=>$budget,
                            'dailybudget'=>$budget,
                            'currentdate'=>date('Y-m-d')
                            );
                            /* Campaign Budget Insert Redirection to Campaign List */
                            if($this->mod_campaign->add_campaign_budget($add_campaign_budget))
				{
                                /* Form added Successfully. Redirect to Campaign List */
                                $this->session->set_flashdata('form_add_success', $this->lang->line('label_campaign_add_success'));
								if($advid == 0 || $advid == '')
								{
									redirect('admin/inventory_campaigns');
								}
								else
								{
									redirect('admin/inventory_campaigns/listing_adv/all/'.$advid);
								}
                                
                            } 
							else 
							{
                                /* Form Validation is failed. Redirect to Add Campaign Form */
                                $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                                $this->add_campaign($advid);
                            }

                            
                        } else 
						{
                            /* Form Validation is failed. Redirect to Add Campaign Form */
                            $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                            $this->add_campaign($advid);
                        }

                   }
                   else
                   {
                       /* Campaign Duplication Error */
                       $this->session->set_userdata('camp_duplicate', $this->lang->line('label_camp_duplicate_error'));
                       $this->add_campaign($advid);
                   }
                }                
            }

        }

        /* Edit Campaigns */
        public function edit_campaign($camp_id=0,$sel_adv=0)
        {
            /*-------------------------------------------------------------
		 	Breadcrumb Setup Start
            -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
	    $data['sel_adv']   = $sel_adv;
            /*-------------------------------------------------------------
		 	Get Campaign Details
            -------------------------------------------------------------*/
            if($camp_id!=0)
            {
                $where_camp = array('ox_campaigns.campaignid'=>$camp_id);
                $campaign = $this->mod_campaign->retrieve_campaign($where_camp);
                $link_check  = $this->mod_campaign->check_campaign_linking_count($camp_id);
				$data['link_count'] = $link_check;
                $data['campinfo'] = $campaign;
                $data['campaign_id'] = $camp_id;
            }
            else
            {
		$data['link_count']=0;
                $data['campinfo'] = 0;
                $data['campaign_id'] = 0;
            }
            /*-------------------------------------------------------------
		 	Get Advertiser List
            -------------------------------------------------------------*/
            $adv_list = $this->mod_campaign->get_advertiser_list();
            $data['advertiser'] = $adv_list;
			
			 /*-------------------------------------------------------------
		 	Get Categories List
            -------------------------------------------------------------*/
            $category_list = $this->mod_campaign->getCategory();
            $data['category_list'] = $category_list;
	
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content'] = $this->load->view("admin/campaign/edit",$data,true);
            $this->load->view('page_layout',$data);
        }

        /* Modify Campaign information */
        public function edit_campaign_process($campid=0,$sel_adv=0)
        {
	    if($sel_adv == 0 || $sel_adv == '')
	    {
		$sel_adv='';
	    }
            $pmodel = $this->input->post('pricing_model');
            $ex_pmodel = $this->input->post('ex_camp_rev_type');
            $sdate = $this->input->post('start_date');
            $edate = $this->input->post('end_date');

            /* Form Validations for Add Campaign */
            if($sdate==0){
                $campstart = date('Y-m-d H:i:s');
            } else {
                $this->form_validation->set_rules('campstart', 'Campaign Start Date', 'required');
                $campstart   = $this->input->post('campstart');
                $campstart = date('Y-m-d H:i:s', strtotime($campstart));
            }
            if($edate==0){
                $campend = NULL;
            } else {
                $this->form_validation->set_rules('campend', 'Campaign End Date', 'required');
                $campend    = $this->input->post('campend');
                $campend    = date('Y-m-d H:i:s', strtotime($campend));
            }
            
            $this->form_validation->set_rules('campname', 'Campaign Name', 'required');
           // $this->form_validation->set_rules('advertiser', 'Advertiser Name', 'required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
	    	$this->form_validation->set_rules('end_date', 'End Date', 'required');
		//	$this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('pricing_model', 'Pricing Model', 'required');
            $this->form_validation->set_rules('weight', 'Campaign Weight', 'required|numeric');
            $this->form_validation->set_rules('budget', 'Campaign Budget', 'required|numeric');
            if($pmodel==1){
                $this->form_validation->set_rules('impression', 'Rate per Impression', 'required|numeric');
                $revenue  = $this->input->post('impression');
            } else if($pmodel==2){
                $this->form_validation->set_rules('clicks', 'Rate per Clicks', 'required|numeric');
                $revenue  = $this->input->post('clicks');
            } else if ($pmodel==3) {
                $this->form_validation->set_rules('conversion', 'Rate per Conversion', 'required|numeric');
                $revenue  = $this->input->post('conversion');
            }
            if($this->form_validation->run() == FALSE)
            {
               /* Form Validation is failed. Redirect to Edit Campaign Form */
               $this->session->set_userdata('validation_error', validation_errors());
               $this->edit_campaign($campid,$sel_adv);               
            }
            else
            {
            	if($_POST)
                {
                   $campname    = $this->input->post('campname');
                   $advertiser  = $this->input->post('advertiser');
                   $weight      = $this->input->post('weight');
                   $budget      = $this->input->post('budget');
				   $category	=$this->input->post('category');
                   $currdate    = date("Y-m-d");
                   
                    // Realtime Bidding Concept
					 $rtb 		= $this->input->post('rtb');
					if($rtb=='on')
					   {
							$realtime = 1;
					   }
					   else
					   {
							$realtime = 0;
					   }
					   
					   $campaign_rtb = $this->mod_campaign->get_campaign_rtb($campid);
					   if($campaign_rtb!=$realtime)
					   {

						/* Remove Targeting */
						if($campaign_rtb==0 && $realtime==1)
						{
							/* Check Delivery Targeting */
						    	$check_camp = $this->mod_campaign->check_delivery_targeting($campid);
						    	if($check_camp>0)
						    	{						    		
								$where_target = array('campaignid'=>$campid);
							    	$this->db->delete('djx_campaign_limitation', $where_target);
						    	}
						}

						elseif($campaign_rtb==1 && $realtime==0)
						{
							/* Check Delivery Targeting */
						    	$check_camp = $this->mod_campaign->check_delivery_targeting($campid);
						    	$where_target = array('campaignid'=>$campid);

						    	if($check_camp>0)
						    	{								
								$this->db->delete('djx_campaign_limitation', $where_target);
						    	}
							$this->db->delete('djx_targeting_limitations', $where_target);

							$where_rtb = array('campaign_id'=>$campid);
							$this->db->delete('aff_rtb',$where_rtb);
				    			$this->db->delete('aff_bid_acls',$where_rtb);	
						}

						   $link_check  = $this->mod_campaign->check_campaign_linking_count($campid);
							if($link_check > 0)
							{
								$link_camps = $this->mod_campaign->check_linked_campdata_count($campid);
								if($link_camps>0)
								{
									for($i=0;$i<count($link_camps);$i++)
									{
										$campaign_id = $link_camps[$i]->campaignid;
										$banner_id = $link_camps[$i]->bannerid;
										$zone_id = $link_camps[$i]->zoneid;
										$this->mod_campaign->unlink_campaign_placement_zone_assoc($campaign_id,$zone_id);
										$this->mod_campaign->unlink_campaign_ad_zone_assoc($banner_id,$zone_id);
									}	       				
								}	       			
							}
					   }
					   
                   /* Unlink Zones with Selected Campaign While Revenue Type Changed */
                   if($ex_pmodel!=$pmodel)
				   {	       
						$link_check  = $this->mod_campaign->check_campaign_linking_count($campid);
						if($link_check > 0)
						{
							$link_camps = $this->mod_campaign->check_linked_campdata_count($campid);
							if($link_camps>0)
							{
								for($i=0;$i<count($link_camps);$i++)
								{
									$campaign_id = $link_camps[$i]->campaignid;
									$banner_id = $link_camps[$i]->bannerid;
									$zone_id = $link_camps[$i]->zoneid;
									$this->mod_campaign->unlink_campaign_placement_zone_assoc($campaign_id,$zone_id);
									$this->mod_campaign->unlink_campaign_ad_zone_assoc($banner_id,$zone_id);
								}	       				
							}	       			
						}
					}
                   
                   /* Check Duplication for Campaign Name based on Client ID */
                   $where_camp = array('campaignid'=>$campid,'clientid'=>$advertiser);
                   $camp_check  = $this->mod_campaign->check_campaign_duplication($where_camp);
                   if(count($camp_check)<=1)
                   {

                       /* Define Campaign Status based on Start Date and End Date */
                       if($campend=='')
                       {
                            $startvalue  = $this->mod_campaign->compare_startdate_today($campstart, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaign->check_advertiser_balance($advertiser);
                            if(count($accBal)>0)
                            {
                            	$adBbal = $accBal[0]->accbalance;
                            }
                            else
                            {
                            	$adBbal = 0;
                            }
                            
                            if($adBbal>0)
                            {
                                if($startvalue>0)
                                {
                                    $status     = "2";
                                    $inactive   = "1";
                                }
                                else
                                {
                                    $status     = "0";
                                    $inactive   = "0";
                                }
                            }
                            else
                            {
                                $status     = "1";
                                $inactive   = "1";
                            }
                       }
                       else
                       {
                            $startvalue  = $this->mod_campaign->compare_startdate_today($campstart, $currdate);
                            $endvalue    = $this->mod_campaign->compare_enddate_today($campend, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaign->check_advertiser_balance($advertiser);
                            if(count($accBal)>0)
                            {
                            	$adBbal = $accBal[0]->accbalance;
                            }
                            else
                            {
                            	$adBbal = 0;
                            }
                            
                            if($adBbal>0)
                            {
							if($startvalue>0 && $endvalue>0)
                                {
                                    $status = '2';
                                    $inactive = '1';
                                }
                                elseif($startvalue<0 && $endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '1';
                                }
                                elseif($endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '1';
                                }
                                else
                                {
                                    $status = '0';
                                    $inactive = '0';
                                }
                            }
                            else
                            {
                                $status = '1';
                                $inactive = '1';
                            }
                       }

                       /* Edit Campaign Parameters */
                        $edit_campaign =array(
                            'campaignname'=>$campname,
                            'clientid'=>$advertiser,
                            'status_startdate'=>$sdate,
                            'status_enddate'=>$edate,
                            'activate_time'=>$campstart,
                            'expire_time'=>$campend,
                            'revenue_type'=>$pmodel,
                            'weight'=>$weight,
                            'revenue'=>$revenue,
                            'status'=>$status,
                            'inactive'=>$inactive,
							'catagory' =>@implode(",", $category),
							'rtb'=>$realtime
                        );
						
                        /* Campaign Insert Method and Get Last Insert ID */
                        $camp_update = $this->mod_campaign->edit_campaign($edit_campaign,$where_camp);

                        if($camp_update){
							
						if($campaign_rtb==1 && $realtime == 1)
						{
							 $where_rtb = array('campaign_id'=>$campid);
							/* Add Campaign Budget Parameters */
							$add_rtb_data = array(
							'bid_rate' => $revenue
							);
							$this->db->update('aff_rtb', $add_rtb_data, $where_rtb);
						}
						elseif($campaign_rtb==0 && $realtime == 1)
						{
							$where_rtb = array('campaign_id'=>$campid,
									'bid_rate' => $revenue
									);
							$this->db->insert('aff_rtb',$where_rtb);
						}

                            /* Add Campaign Budget Parameters */
                            $edit_campaign_budget = array(
                            'clientid'=>$advertiser,
                            'campaignid'=>$campid,
                            'budget'=>$budget,
                            'dailybudget'=>$budget,
                            'currentdate'=>date('Y-m-d'));
                            /* Campaign Budget Edit. Redirection to Campaign List */
                            $where_campbudget = array('campaignid'=>$campid,'clientid'=>$advertiser);
                            if($this->mod_campaign->edit_campaign_budget($edit_campaign_budget,$where_campbudget)){
                                /* Form Modified Successfully. Redirect to Campaign List */
                                $this->session->set_flashdata('form_edit_success', $this->lang->line('label_campaign_edit_success'));
				if($sel_adv == 0 || $sel_adv == '')
				{
					redirect('admin/inventory_campaigns');
				}
				else
				{                                
					redirect('admin/inventory_campaigns/listing_adv/all/'.$sel_adv);
				}
                            } else {
                                /* Form Validation is failed. Redirect to Add Campaign Form */
								$this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                                $this->edit_campaign($campid,$sel_adv);
                            }


                        } else {
                            /* Form Validation is failed. Redirect to Add Campaign Form */
                            $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                            $this->edit_campaign($campid,$sel_adv);
                        }

                   }
                   else
                   {
                       /* Campaign Duplication Error */
                       $this->session->set_userdata('camp_duplicate', $this->lang->line('label_duplicate_error'));
                       $this->edit_campaign($campid,$sel_adv);
                   }
                }
            }

        }

        /* Pause/De-Activate Campaign */
        public function pause_campaign($campid=0)
        {
               /* Pause Campaigns using Checkboxes */
                if($campid==false)
                {
                    $camp = $_POST['campaignarr'];
                    if($camp[0]=='checkall')
                    {
                        /* Remove Check All option */
                        $camprem = array_shift($camp);
                    }
                    $count = count($camp);
                    for($m=0;$m<$count;$m++)
                    {
                        /* Pause Campaign */
                        $this->mod_campaign->pause_campaign($camp[$m]);
                        /* Campaign Paused Successfully. Redirect to Campaign List */
                    }
                    $this->session->set_flashdata('pause_campaign', $this->lang->line('label_pause_success'));
                }
                else
                {
                        /* Campaign Pause Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                }
        }

        /* Run/Activate Campaign */
        public function run_campaign($campid=0)
        {
            /* Activate Campaigns using Checkboxes */
                if($campid==false)
                {
                    $camp = $_POST['campaignarr'];
                    if($camp[0]=='checkall')
                    {
                        /* Remove Check All option */
                        $camprem = array_shift($camp);
                    }
                    $count = count($camp);
                    for($m=0;$m<$count;$m++)
                    {
                         /* Campaign Run/Pause Dates */
                        $camp_dates = $this->mod_campaign->get_campaign($camp[$m]);
                        $today = date("Y-m-d");
                        if(count($camp_dates)>0)
                        {
                            $activate_date      = $camp_dates[0]->activate_time;
                            $expire_date        = $camp_dates[0]->expire_time;
                            $status_startdate   = $camp_dates[0]->status_startdate;
                            $status_enddate     = $camp_dates[0]->status_enddate;
                            $advertiser         = $camp_dates[0]->clientid;
                        }
                        else
                        {
                            $activate_date      = '';
                            $expire_date        = '';
                            $status_startdate   = '';
                            $status_enddate     = '';
                            $advertiser         = '';
                        }
                        /* Advertiser Account Balance */
                        $accBal = $this->mod_campaign->check_advertiser_balance($advertiser);
                        if(count($accBal)>0)
                        {
                            $adBbal = $accBal[0]->accbalance;
                        }
                        else
                        {
                            $accBal = 0;
                        }
                        /* Campaign Daily Budget */
                        $dailyBudget = $this->mod_campaign->campaign_daily_budget($camp[$m]);
                        if(count($dailyBudget)>0)
                        {
                            $dailyBudget = $dailyBudget[0]->dailybudget;
                        }
                        else
                        {
                            $dailyBudget = 0;
                        }
                        /* Campaign Amount */
                        $campaign_amt = $this->mod_campaign->campaign_amount($advertiser, $camp[$m], $today);
                        if(count($campaign_amt)>0)
                        {
                            $campaign_amount = $campaign_amt[0]->amount;
                        }
                        else
                        {
                            $campaign_amount = 0;
                        }
                        if($accBal>0)
                        {
                            if($campaign_amount<=$dailyBudget)
                            {
                                if($status_enddate==1)
                                {
                                    $startvalue  = $this->mod_campaign->compare_startdate_today($activate_date, $today);
                                    $endvalue    = $this->mod_campaign->compare_enddate_today($expire_date, $today);
                                    if($startvalue>0 && $endvalue>0)
                                    {
					$status = '2';
                                        $inactive = '2';
                                        $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                    }
                                    elseif($startvalue<0 && $endvalue<0)
                                    {
					$status = '3';
                                        $inactive = '2';
                                        $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                    }
                                    elseif($endvalue<0)
									{
										$status = '3';
										$inactive = '2';
										$this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
									}
                                    else
                                    {
										$status = '0';
                                        $inactive = '0';
                                        $this->session->set_flashdata('run_campaign', $this->lang->line('label_campaign_run_success'));
                                    }

                                    $update_status = array(
                                        'status'=>$status,
                                        'inactive'=>$inactive
                                     );
                                     //print_r($update_status);exit;
                                    $where_camp = array('campaignid'=>$camp[$m]);
									$camp_update = $this->mod_campaign->edit_campaign($update_status,$where_camp);
									/* Campaign Run Successfully. Redirect to Campaign List */
                                    //$this->session->set_flashdata('run_campaign', $this->lang->line('label_campaign_run_success'));
                                    //redirect('advertiser/campaigns');
                                }
                                else
                                {
                                    $startvalue  = $this->mod_campaign->compare_startdate_today($activate_date, $today);
                                    if($startvalue>0)
                                    {
                                        $status="2";
                                        $inactive="2";
                                        $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                    }
                                    else
                                    {
                                        $status="0";
                                        $inactive="0";
                                        $this->session->set_flashdata('run_campaign', $this->lang->line('label_campaign_run_success'));
                                    }

                                    $update_status = array(
                                        'status'=>$status,
                                        'inactive'=>$inactive
                                     );
									//print_r($update_status);
                                    $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                    $camp_update = $this->mod_campaign->edit_campaign($update_status,$where_camp);

                                    /* Campaign Run Successfully. Redirect to Campaign List */
                                    //$this->session->set_flashdata('run_campaign', $this->lang->line('label_campaign_run_success'));
                                    //redirect('advertiser/campaigns');

                                }
                            }
                            else
                            {
                                $update_status = array(
                                    'status'=>'1',
                                    'inactive'=>'1'                                    
                                 );

                                $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                $camp_update = $this->mod_campaign->edit_campaign($update_status,$where_camp);

                                /* Campaign Run Successfully. Redirect to Campaign List */
                                $this->session->set_flashdata('budget_completed', $this->lang->line('label_daily_budget_completed'));
                                //redirect('advertiser/campaigns');
                            }
                        }
                        else
                        {
                            if($status_enddate==1)
                            {
                                $startvalue  = $this->mod_campaign->compare_startdate_today($activate_date, $today);
                                $endvalue    = $this->mod_campaign->compare_enddate_today($expire_date, $today);
				if($startvalue>0 && $endvalue>0)
                                {
                                    $status = '1';
                                    $inactive = '2';
                                    $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                }                                
				elseif($startvalue<0 && $endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '2';
                                    $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                }
                                elseif($endvalue<0)
                                {
                                    $status = '3';
                                    $inactive = '2';
                                    $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                }
                                else
                                {
                                    $status = '1';
                                    $inactive = '2';
                                    $this->session->set_flashdata('block_run_campaign', $this->lang->line('label_acc_bal_low'));
                                }

                                $update_status = array(
                                    'status'=>$status,
                                    'inactive'=>$inactive
                                 );

                                $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                $camp_update = $this->mod_campaign->edit_campaign($update_status,$where_camp);

                                /* Campaign Run failed. Redirect to Campaign List */
                                //$this->session->set_flashdata('block_run_campaign', $this->lang->line('label_acc_bal_low'));
                                //redirect('advertiser/campaigns');
                            }
                            else
                            {
                                $startvalue  = $this->mod_campaign->compare_startdate_today($activate_date, $today);
                                if($startvalue>0)
                                {
                                    $status="1";
                                    $inactive="2";
                                    $this->session->set_flashdata('complete_campaign', $this->lang->line('label_run_failed'));
                                }
                                else
                                {
                                    $status="1";
                                    $inactive="0";
                                    $this->session->set_flashdata('block_run_campaign', $this->lang->line('label_acc_bal_low'));
                                }

                                $update_status = array(
                                    'status'=>$status,
                                    'inactive'=>$inactive
                                 );
                                
                                $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                $camp_update = $this->mod_campaign->edit_campaign($update_status,$where_camp);

                                /* Campaign Run failed. Redirect to Campaign List */
                                //$this->session->set_flashdata('block_run_campaign', $this->lang->line('label_acc_bal_low'));
                                //redirect('advertiser/campaigns');
                            }							
						}
	
						/* Activate Campaign */
						//$this->mod_campaign->run_campaign($camp[$m]);                         	
                        /* Campaign Activated Successfully. Redirect to Campaign List */
                    }
                    $this->session->set_flashdata('run_campaign', $this->lang->line('label_run_success'));
                }
                else
                {
                        /* Campaign Activate Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                }
        }

        /* Delete Campaign */
        public function delete_campaign($campid=0,$sel_adv=0)
        {
			/* Delete Campaigns using Checkboxes */
            if($campid==false)
            {
                $camp = $_POST['campaignarr'];
                if($camp[0]=='checkall')
                {
                    $camprem = array_shift($camp);                    
                }
                $count = count($camp);
                for($m=0;$m<$count;$m++)
                {
                    $where_arr = array('campaignid'=>$camp[$m]);
                    /* Get Banner data for Selected Campaign */
                    $banners = $this->mod_campaign->getBanners($where_arr);
                    /* Delete Budget Table Campaign/Banner data */
                    foreach($banners as $bid)
                    {
                            $id	= $bid->bannerid;

                            $wherecre = array("creative_id"=>$id);
                            $this->mod_campaign->deleteBKTA($wherecre);
                            $this->mod_campaign->deleteBKTC($wherecre);
                            $this->mod_campaign->deleteBKTM($wherecre);
                            $this->mod_campaign->deleteBKTUC($wherecre);
                            $this->mod_campaign->deleteBKTUM($wherecre);

                            $wheread = array("ad_id"=>$id);
                            $this->mod_campaign->deleteBKTH($wheread);
                            $this->mod_campaign->deleteBKTAD($wheread);
                            $this->mod_campaign->deleteZoneAssoc($wheread);
                            $where_ban = array('bannerid'=>$id);
                            $this->mod_banner->delete_banner($where_ban);
                     }

                     /* Delete Campaign Reports */
                        $this->mod_campaign->delete_campaign_reports($where_arr);
                    /* Delete Campign Budget */
                        $this->mod_campaign->delete_campaign_budget($where_arr);
                    /* Delete Campaign Targeting Features */    
                    	$this->mod_campaign->delete_targeting_limitation($where_arr);
                    	$this->mod_campaign->delete_campaign_limitation($where_arr);
                    /* Delete Campaign */
                        $this->mod_campaign->delete_campaign($where_arr);
                        
                    /* Campaign Deleted Successfully. Redirect to Campaign List */                    
                }

                $this->session->set_flashdata('form_delete_success', $this->lang->line('label_delete_camp_success'));
                //redirect('admin/inventory_campaigns');
            }
            /* Delete Campaigns using Action Link */
            else if($campid != false)
            {
                $where_arr = array('campaignid'=>$campid);
                /* Get Banner data for Selected Campaign */
                $banners = $this->mod_campaign->getBanners($where_arr);
                /* Delete Budget Table Campaign/Banner data */
                foreach($banners as $bid){
                        $id = $bid->bannerid;

                        $wherecre = array("creative_id"=>$id);
                        $this->mod_campaign->deleteBKTA($wherecre);
                        $this->mod_campaign->deleteBKTC($wherecre);
                        $this->mod_campaign->deleteBKTM($wherecre);
                        $this->mod_campaign->deleteBKTUC($wherecre);
                        $this->mod_campaign->deleteBKTUM($wherecre);

                        $wheread = array("ad_id"=>$id);
                        $this->mod_campaign->deleteBKTH($wheread);
                        $this->mod_campaign->deleteBKTAD($wheread);
                        $this->mod_campaign->deleteZoneAssoc($wheread);
                        $where_ban = array('bannerid'=>$id);
                        $this->mod_banner->delete_banner($where_ban);
                 }

                /* Delete Campaign Reports */
                $this->mod_campaign->delete_campaign_reports($where_arr);
                /* Delete Campaign Targeting Features */    
            	$this->mod_campaign->delete_targeting_limitation($where_arr);
            	$this->mod_campaign->delete_campaign_limitation($where_arr);
                /* Delete Campign Budget */
                $this->mod_campaign->delete_campaign_budget($where_arr);                
                /* Delete Campaign */
                $this->mod_campaign->delete_campaign($where_arr);
            	
                /* Campaign Deleted Successfully. Redirect to Campaign List */
                $this->session->set_flashdata('form_delete_success', $this->lang->line('label_delete_camp_success'));
                if($sel_adv == 0 || $sel_adv == '')
		    {
			redirect('admin/inventory_campaigns');
		    }
	 	    else
		    {
			redirect('admin/inventory_campaigns/listing_adv/all/'.$sel_adv);
		    }
            }
            else
            {
                    /* Campaign Deleted Failed. Redirect to Campaign List */
                    $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
		    if($sel_adv == 0 || $sel_adv != '')
		    {
			redirect('admin/inventory_campaigns');
		    }
	 	    else
		    {
			redirect('admin/inventory_campaigns/listing_adv/all/'.$sel_adv);
		    }            
		}
        }

        /* Mobile Targeting Features For Campaigns */
        public function targeting($campid=0)
        {
                /*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
		 	Campaigns Based on Advertiser Id
		-------------------------------------------------------------*/
                $data['sel_camp'] = $campid;
                /*-------------------------------------------------------------
		 	Check and Retrieve Targeting Features
		 -------------------------------------------------------------*/
                $target = $this->mod_campaign->check_targeting_campaign($campid);
                $data['target'] = $target;
               // echo "<pre>"; print_r($target); exit;
                /*-------------------------------------------------------------
		 	Device OS List
		-------------------------------------------------------------*/
                $deviceos = $this->mod_campaign->get_device_os();
                $data['deviceos'] = $deviceos;
                //print_r($data['deviceos']);exit;
                /*-------------------------------------------------------------
		 	Device Manufacturer List
		-------------------------------------------------------------*/
                $devicemanuf = $this->mod_campaign->get_device_manufacturer();
                $data['devicemanuf'] = $devicemanuf;
                /*-------------------------------------------------------------
		 	Device Manufacturer List
		-------------------------------------------------------------*/
                $devicecap = $this->mod_campaign->get_device_capabilty();
                $data['devicecap'] = $devicecap;
                /*-------------------------------------------------------------
		 	Geography Location List
		-------------------------------------------------------------*/
                $geolocation = $this->mod_campaign->get_geo_location();
                $data['geolocation'] = $geolocation;
                /*-------------------------------------------------------------
		 	Geography Operator List
		-------------------------------------------------------------*/
                $geooperator = $this->mod_campaign->get_geo_operator();
                $data['geooperator'] = $geooperator;
                /*-------------------------------------------------------------
		 	Age Group List
		-------------------------------------------------------------*/
                $agegroup = $this->mod_campaign->get_age_group();
                $data['agegroup'] = $agegroup;
               

              /*-------------------------------------------------------------
	 		Geography Operator List
		-------------------------------------------------------------*/
                $geooperatornew = $this->mod_campaign->get_geo_operator_carrier();
                $data['geooperator_carrier'] = $geooperatornew;		
		
		 /*-------------------------------------------------------------
		 	Embed current page content into template layout
		-------------------------------------------------------------*/
                $chktarget = $this->mod_campaign->check_targeting_campaign($campid);

		/*******************************************
			Get Campaigns Details
		*********************************************/	
                $camp_summary	=	$this->mod_campaign->get_campaign_summary($campid);
                if(count($camp_summary)>0)
                {
			$data['camp_summary']	= $camp_summary[0];
		}
		else
		{
			$data['camp_summary']	= 0;
		}
               
                if(count($chktarget)>0)
                {
                    $data['page_content']	= $this->load->view("admin/campaign/edit_targeting",$data,true);
                    $this->load->view('page_layout',$data);
                }
                else
                {
                    $data['page_content']	= $this->load->view("admin/campaign/targeting",$data,true);
                    $this->load->view('page_layout',$data);
                }
        }
        
        /* Update Targeting Limitation Process */
        public function targeting_limitation_process()
        {

            $campid         	= $this->input->post('campaign');
            /* Variables to update targeting in Banner Delivery Part */
	    $limitations 	= "";
 	    $plugins     	= ""; 	    
 	    
            if($campid!='')
            {
                $os             = $this->input->post('os');
                $manufacturer   = $this->input->post('manufacturer');
                $capabilty      = $this->input->post('capabilty');
                $location       = $this->input->post('geolocation');
                $operator       = $this->input->post('geooperator');
                $model_type     = $this->input->post('mobdevice');
                $gender         = $this->input->post('gender');
                $age            = $this->input->post('agegroup');
                $model_name     = $this->input->post('device_name');
                $carrier		= $this->input->post('nwcarrier');
				//$carrier	    = $this->input->post('geooperatorcarrier');
                /* Device OS List */
                if($os=='device_os'){
                    $os_content = $this->input->post('destination_os');
					if(is_array($os_content) && count($os_content)>0)
					{
                    $os_content = implode(',',$os_content);
                                     
                    /* Delivery Targeting - OS */
                    $limitations .= $os."('".$os_content."','=~')";
                    $plugins     .= $os.",";
					}
					else { $os_contentt = ''; }
                }
                else{
                    $os_content  = "";                    
                }
                /* Device Manufacturer List */
                if($manufacturer=='device_manufacturer'){
                    $manu_content = $this->input->post('destination_manu');
					if(is_array($manu_content) && count($manu_content)>0)
					{
					$manu_content = implode(',',$manu_content);
					
					  /* Delivery Targeting - Manufacturer */
                    $limitations .= " AND ".$manufacturer."('".$manu_content."','=~')";
                    $plugins     .= $manufacturer.",";
					}
					else { $manu_content = ''; }
					
					       
                }
                else{
                    $manu_content = '';
                }
                /* Device Capbility List */
                if($capabilty=='device_capability'){
                    $cap_content = $this->input->post('destination_cap');
						if(is_array($cap_content) && count($cap_content)>0)
					{
                    $cap_content = implode(',',$cap_content);
					
					  /* Delivery Targeting - Manufacturer */
                    $limitations .= " AND ".$capabilty."('".$cap_content."','=~')";
                    $plugins     .= $capabilty.",";
					}
					else { $cap_content= ''; }
                }
                else{
                    $cap_content = '';
                }
                
                /* Geo Location List */
                if($location=='geographic_locations'){
                    $loc_content = $this->input->post('destination_loc');
						if(is_array($loc_content) && count($loc_content)>0)
					{
                    $loc_content = implode(',',$loc_content);
                    
                    /* Delivery Targeting - Geographic Locations */
                    $limitations .= " AND ".$location."('".$loc_content."','=~')";
                    $plugins     .= $location.",";
						}
					else { $loc_content= ''; }
                }
                else{
                    $loc_content = '';
                }
                /* Geo Operator List */
                if($operator=='geographic_operators'){
                    $ope_content = $this->input->post('destination_ope');
					if(is_array($ope_content) && count($ope_content)>0)
					{
                    $ope_content = implode(',',$ope_content);
                    
                    /* Delivery Targeting - Geographic Operators */
                    $limitations .= " AND ".$operator."('".$ope_content."','=~')";
                    $plugins     .= $operator.",";
						}
					else { $ope_content= ''; }
                }
                else{
                    $ope_content = 'all';
                }
                
                if($carrier=='mobileCarrierLimitation')
                {
					$carriers = $this->input->post('network_carriers');
					
					if(!empty($carriers))
					{
						foreach($carriers as $c)
						{
							$arr[] = explode(':', $c);
						}
						for($i=0; $i<count($arr); $i++)
						{
							$result_car[$arr[$i][0]][] = $arr[$i][1];
						}
						
						if(is_array($result_car) && count($result_car)>0)
							{
								foreach($result_car as $key=>$value)
								{
									$carrier_value[] = implode(',',$value);
									$country_name[] = $key;
									
									$carrier_content = implode(',',$value);
									$country = $key;
									$limitations .=" AND MAX_checkMobileCarrierLimitation_mobileCarrierLimitation('".$country."|".$carrier_content."','=~')";
								}
								$plugins     .="mobileCarrierLimitation".",";
							}
							else{
								$carrier_content ='';
								$carrier_value = array();
								$country_name = array();
							}
					}
					else{
						$carrier_content = '';
						$carrier_value = array();
						$country_name = array();
					}
                }
                else{
						$carrier_content = '';
						$carrier_value = array();
						$country_name = array();
					}
                
                /* Age Group List */
                if($age=='all'){
                    $age_content = 'a';
                }
                else{
                    	$age_content = $this->input->post('selage');
			if(count($age_content)>0 && is_array($age_content))
			{
				$age_content = implode(',',$age_content);
			}
			else
			{
				$age_content = 'a';
			}
               }     
                /* Delivery Targeting - Ages */
                $limitations .=" AND demographics('".$gender."|".$age_content."','=~')";
		$plugins     .="demographics".",";

               /* Carrier Limitations*/
              /*  if($carrier=='all'){
                    $carrier_content = '';
                }
                elseif($carrier=='specific'){
                    $carrier_content = $this->input->post('carrier');
                    $carrier_content = implode(',',$carrier_content);
               } 
			   		   
                if(!empty($carrier_content))
				{
					/* Delivery Targeting - CARRIER */
				//	$limitations .=" AND geographic_operators('".$carrier_content."','=~')";
				//	$plugins     .="geographic_operators".",";
				//}
			
                /* Delivery Targeting - Device Model */
        if((!empty($model_type) && $model_type !='0') && !empty($model_name))
		{
			$limitations .=" AND model('".$model_name."','".$model_type."')";
			$plugins     .="model".",";
		}
                
               
                
                /* Checking Campaign ID Availablity */
                $campcheck = $this->mod_campaign->check_targeting_campaign($campid);
                if(!empty($country_name)&&!empty($carrier_value))
                {
					$c_name = implode(',', $country_name);
					$car_value = implode(',', $carrier_value);
				}
				else
				{
					$c_name = '';
					$car_value = '';
				}
                /* Update Targeting Features */
                if(count($campcheck)>0){
                    /* Array Parameters for Processing Tageting Features */
                    $target_features = array(
                        'devices'=>$os_content,
                        'manufacturer'=>$manu_content,
                        'capability'=>$cap_content,
                        'locations'=>$loc_content,
                        'operators'=>$ope_content,
                        'gender'=>$gender,
                        'ages'=>$age_content,
                        'device_type'=>$os,
                        'manufacturer_type'=>$manufacturer,
                        'capability_type'=>$capabilty,
                        'location_type'=>$location,
                        'operator_type'=>$operator,
                        'gender_type'=>$gender,
                        'ages_type'=>$age,
                        'model'=>$model_name,
                        'model_type'=>$model_type,
                        'carrier_type'=>$carrier,
						'carriers' => $car_value,
						'network_country' => $c_name
                    );
                    
                    
                    $where_camp = array('campaignid'=>$campid);

                    $update_target = $this->mod_campaign->update_targeting_limitation($target_features,$where_camp);

                    if($update_target=='TRUE')
                    {
                    	/* Check Delivery Targeting */
                    	$check_camp = $this->mod_campaign->check_delivery_targeting($campid);
                    	if($check_camp>0)
                    	{
                    		/* Update Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');	
		            	$plugins    	= rtrim($plugins,",");
						
				$deliver_target	= array('compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
				$where_camp_deliver = array('campaignid'=>$campid);
		            	$this->mod_campaign->update_delivery_targeting($deliver_target, $where_camp_deliver);
                    	}
                    	else
                    	{
                    		/* Add Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
		            	$plugins    	= rtrim($plugins,",");
				$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
				$this->mod_campaign->add_delivery_targeting($deliver_target);		            	
                    	}
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('admin/inventory_campaigns');
                    }
                    else
                    {
                        /* Campaign Targeting Update Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                    }
                }
                /* Inserting Targeting Features */
                else
                {
					if(!empty($country_name)&&!empty($carrier_value))
					{
						$c_name = implode(',', $country_name);
						$car_value = implode(',', $carrier_value);
					}
					else
					{
						$c_name = '';
						$car_value = '';
					}
                    /* Array Parameters for Processing Tageting Features */
                       $target_features = array(
                            'campaignid'=>$campid,
                            'devices'=>$os_content,
                            'manufacturer'=>$manu_content,
                            'capability'=>$cap_content,
                            'locations'=>$loc_content,
                            'operators'=>$ope_content,
                            'gender'=>$gender,
                            'ages'=>$age_content,
                            'device_type'=>$os,
                            'manufacturer_type'=>$manufacturer,
                            'capability_type'=>$capabilty,
                            'location_type'=>$location,
                            'operator_type'=>$operator ,
                            'gender_type'=>$gender,
                            'ages_type'=>$age,
                            'model'=>$model_name,
                            'model_type'=>$model_type,
                            'carrier_type'=>$carrier,
							'carriers' => $car_value,
							'network_country' => $c_name
                        );

                    $add_target = $this->mod_campaign->insert_targeting_limitation($target_features);

                    if($add_target=='TRUE')
                    {                    
                    	/* Add Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');
                    	$plugins    	= rtrim($plugins,",");
			$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
			$this->mod_campaign->add_delivery_targeting($deliver_target);
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('admin/inventory_campaigns');
                    }
                    else
                    {
                         /* Campaign Targeting Insert Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                    }
                }
            }
            else
            {
                /* Campaign Targeting Failed. Redirect to Campaign List */
                $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                redirect('admin/inventory_campaigns');
            }
            
        }
        
        /* Mobile Targeting Features For Campaigns */
        public function rtb_targeting($campid=0,$rtb=0)
        {
         /*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
		 	Campaigns Based on Advertiser Id
		-------------------------------------------------------------*/
                $data['sel_camp'] = $campid;
                /*-------------------------------------------------------------
		 	Check and Retrieve Targeting Features
		 -------------------------------------------------------------*/
                $target = $this->mod_campaign->check_rtb_targeting_campaign($campid);
                $data['target'] = $target;
               
                /*-------------------------------------------------------------
		 	Geography Location List
		-------------------------------------------------------------*/
                $geolocation = $this->mod_campaign->get_geo_location();
                $data['geolocation'] = $geolocation;
               
		
		 /*-------------------------------------------------------------
		 	Embed current page content into template layout
		-------------------------------------------------------------*/
                $chktarget = $this->mod_campaign->check_rtb_targeting_campaign($campid);

		/*******************************************
			Get Campaigns Details
		*********************************************/	
                $camp_summary	=	$this->mod_campaign->get_campaign_summary($campid);
                if(count($camp_summary)>0)
                {
					$data['camp_summary']	= $camp_summary[0];
				}
				else
				{
					$data['camp_summary']	= 0;
				}
               
                if(count($chktarget)>0)
                {
                    $data['page_content']	= $this->load->view("admin/campaign/edit_rtb_targeting",$data,true);
                    $this->load->view('page_layout',$data);
                }
                else
                {
                    $data['page_content']	= $this->load->view("admin/campaign/rtb_targeting",$data,true);
                    $this->load->view('page_layout',$data);
                }
        }

        
         /* Update Targeting Limitation Process */
        public function rtb_targeting_limitation_process()
        {

            $campid         	= $this->input->post('campaign');
            /* Variables to update targeting in Banner Delivery Part */
	    $limitations 	= "";
 	    $plugins     	= "";
 	    
 	    $limitations_camp	=	"";
 	    $plugins_camp	=	"";	    
 	    
            if($campid!='')
            {
                $location       = $this->input->post('geolocation');
                
                              
                /* Geo Location List for aff_rtb table */
                if($location=='geographic_locations'){
                    $loc_content = $this->input->post('destination_loc');
						if(is_array($loc_content) && count($loc_content)>0)
					{
                    $loc_content = implode(',',$loc_content);
                    
						/* Delivery Targeting - Geographic Locations */
						$limitations .= " AND country('".$loc_content."','=~')";
						$plugins     .= "country";
						
						/* Delivery Targeting - Geographic Locations */
						$limitations_camp .= " AND ".$location."('".$loc_content."','=~')";
						$plugins_camp     .= $location.",";
						}
					else { $loc_content= ''; }
                }
                else{
                    $loc_content = '';
                }
                
                                 
                /* Checking Campaign ID Availablity */
                $campcheck = $this->mod_campaign->check_rtb_targeting_campaign($campid);
                
                /* Update Targeting Features */
                if(count($campcheck)>0){
                    /* Array Parameters for Processing Tageting Features */
                    $target_features = array(
                        'type' =>'country',
                        'data' => $loc_content
                  );
                  
                  
                   $where_camp = array('campaign_id'=>$campid);
					
                    $update_target = $this->mod_campaign->update_rtb_targeting_limitation($target_features,$where_camp);

					 /* Checking Campaign ID Availablity */
					$campcheck_targ_limit = $this->mod_campaign->check_targeting_campaign($campid);
					
					if(!empty($campcheck_targ_limit)){

						$target_features_camp = array('locations'=>$loc_content,'location_type'=>$location);
					  
						$where_camp_limit = array('campaignid'=>$campid);
					  
						$this->mod_campaign->update_targeting_limitation($target_features_camp,$where_camp_limit);
					}
					else
					{
						$target_features_limit = array('campaignid'=>$campid,'locations'=>$loc_content,'location_type'=>$location);

						$add_target = $this->mod_campaign->insert_targeting_limitation($target_features_limit);
					}
                    
                    if($update_target=='TRUE')
                    {
                    	/* Check Delivery Targeting */
                    	$check_camp = $this->mod_campaign->check_rtb_delivery_targeting($campid);
                    	if($check_camp>0)
                    	{
                    		/* Update Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
							$plugins    	= rtrim($plugins,",");
							
							$deliver_target	= array('compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
							$where_camp_deliver = array('campaign_id'=>$campid);
							$this->mod_campaign->update_rtb_delivery_targeting($deliver_target, $where_camp_deliver);
							
							/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
							
							$deliver_target_camp	= array('compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
							$where_camp_deliver_camp = array('campaignid'=>$campid);
							$this->mod_campaign->update_delivery_targeting($deliver_target_camp, $where_camp_deliver_camp);
                    	}
                    	else
                    	{
                    		/* Add Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
							$plugins    	= rtrim($plugins,",");
							$deliver_target	= array('campaign_id'=>$campid, 'compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
							$this->mod_campaign->add_rtb_delivery_targeting($deliver_target);
							
							/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
							$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
							$this->mod_campaign->add_delivery_targeting($deliver_target);		            			            	
                    	}
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('admin/inventory_campaigns');
                    }
                    else
                    {
                        /* Campaign Targeting Update Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                    }
                }
                /* Inserting Targeting Features */
                else
                {
                    /* Array Parameters for Processing Tageting Features */
                       $target_features = array(
							'campaign_id'=>$campid,
							'type' =>'country',
							'data' => $loc_content
						 );

                    $add_target = $this->mod_campaign->insert_rtb_targeting_limitation($target_features);
                    
                    $target_features_limit = array('campaignid'=>$campid,'locations'=>$loc_content,'location_type'=>$location);

                    $add_target = $this->mod_campaign->insert_targeting_limitation($target_features_limit);

                    if($add_target=='TRUE')
                    {                    
                    	/* Add Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');
                    	$plugins    	= rtrim($plugins,",");
						$deliver_target	= array('campaign_id'=>$campid, 'compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
						$this->mod_campaign->add_rtb_delivery_targeting($deliver_target);
						
						/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
						$deliver_target_camp	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
						$this->mod_campaign->add_delivery_targeting($deliver_target_camp);
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('admin/inventory_campaigns');
                    }
                    else
                    {
                         /* Campaign Targeting Insert Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('admin/inventory_campaigns');
                    }
                }
            }
            else
            {
                /* Campaign Targeting Failed. Redirect to Campaign List */
                $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                redirect('admin/inventory_campaigns');
            }
            
        }
        
        /* GET REGION*/
		public function region()
		{
			if($this->input->post('country_code')!='')
			{
				$country = $this->input->post('country_code');

				$region_list = $this->mod_campaign->get_region_operator($country);	
				
				$data['region']  = $region_list;
				
				if($this->input->post('selected_country_code')!='' && $this->input->post('selected_country_code')!='0')
				{
						if($country ==$this->input->post('selected_country_code'))
						{
							$data['selected']	=	$this->input->post('selected_carriers');
						}
				}

				echo $this->load->view('admin/campaign/geo_country_network',$data,true);
			}
		}
}

/* End of file inventory_campaigns.php */
/* Location: ./modules/admin/inventory_campaigns.php */
