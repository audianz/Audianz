<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaigns extends CI_Controller {
	/* Page Limit */	
	var $page_limit = 10;
	var $status     = "all";
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('mod_campaigns');
		$this->load->model('mod_tracker','trackers');
		$this->clientid	=$this->session->userdata('session_advertiser_id'); 
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
	}
	
	/* Campaigns Page */
	public function index()
	{
		$this->listing();		
	}

	/* Campaigns Listing Page */	
	public function listing($start=0)
	{
                //$adv_id = 14;
                $adv_id = $this->session->userdata('session_advertiser_id');
                $data['advertiser'] = $adv_id;
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;

		

        /*-------------------------------------------------------------
		 	Retrieve Campaigns List
		 -------------------------------------------------------------*/
                $where = array('ox_campaigns.clientid'=>$adv_id);
                $list_data = $this->mod_campaigns->retrieve_campaigns($where);
				if($list_data!=false)
				{
                	$data['camps'] = $list_data;
				}
				else
				{
					$data['camps']=0;
				}

                /*-------------------------------------------------------------
                        Daily Budget and Account Balance for Advertiser
                -------------------------------------------------------------*/

                /* Check Account Balance for Advertiser */
                $accbal = $this->mod_campaigns->get_account_balance($adv_id);
                if($accbal!='')
                {
                    $data['acBal'] = $accbal;
                }
                else
                {
                    $data['acBal'] = 0;
                    
                }

                /*-------------------------------------------------------------
                        Get Impressions, Clicks and CTR Values
                -------------------------------------------------------------*/
				if($list_data!=false)
				{
					for($i=0;$i<count($list_data);$i++)
					{
						$campid = $list_data[$i]->campaignid;
						/* Budget Impressions */
						$buck_imp[] = $this->mod_campaigns->get_budget_impressions($adv_id,$campid);
						/* Budget Clicks */
						$buck_cli[] = $this->mod_campaigns->get_budget_clicks($adv_id,$campid);
						/* Common Stats */
						$common_stat[] = $this->mod_campaigns->get_common_stats($campid);
						
						
					}
				}

		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("advertiser/campaigns/list",$data,true);
		$this->load->view('advertiser_layout',$data);
	
	}

        /* Add Campaign */
        public function add()
        {
            $adv_id = $this->session->userdata('session_advertiser_id');
            $data['advertiser'] = $adv_id;
            /*-------------------------------------------------------------
                    Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;

	    /*-------------------------------------------------------------
		 	Get Categories List
            -------------------------------------------------------------*/
            $category_list = $this->mod_campaigns->getCategory();
            $data['category_list'] = $category_list;
			
			/*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view("advertiser/campaigns/add",$data,true);
            $this->load->view('advertiser_layout', $data);
    }

    /* Campaign Status - Change */
    public function status($campid=0)
    {
        if($campid)
        {
            $this->mod_campaigns->change_campaign_status($campid);
            redirect('advertiser/campaigns');
        }
        else
        {
            redirect('advertiser/campaigns');
        }
    }

   /* Campaign Add Process */
    public function add_process()
    {
		$adv_id = $this->session->userdata('session_advertiser_id');
        $pmodel = $this->input->post('pricing_model');
        $sdate = $this->input->post('start_date');
        $edate = $this->input->post('end_date');
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
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		//$this->form_validation->set_rules('category', 'Category', 'required');
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

        if ($this->form_validation->run() == FALSE)
        {
           /* Form Validation is failed. Redirect to Add Campaign Form */
           $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
           $this->add();
        }
		else
        {
            if($_POST)
            {
               $campname    = $this->input->post('campname');
               $advertiser  = $adv_id;
               $weight      = $this->input->post('weight');
               $budget      = $this->input->post('budget');
			    $category	= $this->input->post('category');
			    
			    

               /* Check Duplication for Campaign Name based on Client ID */
               $where_camp = array('campaignname'=>$campname,'clientid'=>$advertiser);
               $camp_check  = $this->mod_campaigns->check_campaign_duplication($where_camp);
               if(count($camp_check)==0)
               {
                   $currdate = date("Y-m-d");
                   /* Define Campaign Status based on Start Date and End Date */
                   if($campend=='')
                   {
                       $startvalue  = $this->mod_campaigns->compare_startdate_today($campstart, $currdate);
                       $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
						if($accBal!=false)
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
							   $status = '2';
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
							$status     = "1";
							$inactive   = "1";
						}
                   }
                   else
                   {
                        $startvalue  = $this->mod_campaigns->compare_startdate_today($campstart, $currdate);
                        $endvalue    = $this->mod_campaigns->compare_enddate_today($campend, $currdate);
                         $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
						if($accBal!=false)
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
                                $status = '2';
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
			'rtb' => $realtime
                    );
                    /* Campaign Insert Method and Get Last Insert ID */
                    $last_camp_id = $this->mod_campaigns->add_new_campaign($add_campaign);

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
                        if($this->mod_campaigns->add_campaign_budget($add_campaign_budget))
                        {
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
                            
							if(count($accBal) > 0)
						    	$adBbal = $accBal[0]->accbalance;
							else
								$adBbal = 0;
                            if($adBbal>0)
                            {
                               if($budget==0)
                               {
                                    $status = '1';
                                    $inactive = '1';
                               }
                               else
                               {
                                    $campStat = $this->mod_campaigns->check_campaign_status($last_camp_id);
                                    $campStat = $campStat[0];
                                    if($campStat->status==0 && $campStat->inactive==0)
                                    {
                                        $status = '0';
                                        $inactive = '0';
                                    }
                                    else
                                    {
                                        $status = $campStat->status;
                                        $inactive = $campStat->inactive;
                                    }
                               }
                            }
                            else
                            {
                                $status = '1';
                                $inactive = '1';
                            }
                            //echo 'condition3~'.$status.'-'.$inactive;exit;
                            $update_status = array(
                                'status'=>$status,
                                'inactive'=>$inactive
                             );

                            $where_camp = array('campaignid'=>$last_camp_id,'clientid'=>$advertiser);
                            $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                            /* Form added Successfully. Redirect to Campaign List */
                            $this->session->set_flashdata('form_add_success', $this->lang->line('label_campaign_add_success'));
                            redirect('advertiser/campaigns');
                        } else {
                            /* Form Validation is failed. Redirect to Add Campaign Form */
                            $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                            $this->add();
                        }


                    } else {
                        /* Form Validation is failed. Redirect to Add Campaign Form */
                        $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                        $this->add();
                    }

               }
               else
               {
                   /* Campaign Duplication Error */
                   $this->session->set_userdata('camp_duplicate', $this->lang->line('label_camp_duplicate_error'));
                   $this->add();
               }
            }
        }
    }

    /* Edit Campaign */
    public function edit($camp_id=0)
    {
        $clientid = $this->session->userdata('session_advertiser_id');
			$data['advertiser'] = $clientid;
			/*-------------------------------------------------------------
					Breadcrumb Setup Start
			 -------------------------------------------------------------*/
			$link = breadcrumb();
			$data['breadcrumb'] = $link;
	
			/*-------------------------------------------------------------
						Get Campaign Details
			-------------------------------------------------------------*/
			if($camp_id !=0)
			{
				$where_camp = array('ox_campaigns.campaignid'=>$camp_id, 'ox_campaigns.clientid'=>$clientid);
				$campaign = $this->mod_campaigns->retrieve_campaign($where_camp);
				$link_check  = $this->mod_campaigns->check_campaign_linking_count($camp_id);
				$data['link_count'] = $link_check;
				$data['campinfo'] = $campaign;
				$data['campaign_id'] = $camp_id;
			}
			else
			{
				$data['link_count'] =0;
				$data['campinfo'] 	=0;
				$data['campaign_id']=0;
			}
	
			 /*-------------------------------------------------------------
		 	Get Categories List
            -------------------------------------------------------------*/
            $category_list = $this->mod_campaigns->getCategory();
            $data['category_list'] = $category_list;		
			
			/*-------------------------------------------------------------
					Embed current page content into template layout
			 -------------------------------------------------------------*/
			 if($campaign!=false)
			 {
				$data['page_content']	= $this->load->view("advertiser/campaigns/edit",$data,true);
				$this->load->view('advertiser_layout',$data);
			 }
			 else
			 {
			 	$data['page_content']	= $this->load->view("campaigns/no_page",$data,true);
				$this->load->view('advertiser_layout',$data);
			 }
		
    }

    /* Edit Campaign Process */
    public function edit_process($campid)
    {
    	$adv_id = $this->session->userdata('session_advertiser_id');
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
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		//$this->form_validation->set_rules('category', 'Category', 'required');
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
        if ($this->form_validation->run() == FALSE)
        {
           /* Form Validation is failed. Redirect to Edit Campaign Form */
           $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
		   $this->edit($campid);
        }
		else
        {
            if($_POST)
            {
	       if($ex_pmodel !=$pmodel)
	       {	
		   			      
	       		$link_check  = $this->mod_campaigns->check_campaign_linking_count($campid);
				
	       		if($link_check > 0)
	       		{
	       			$link_camps = $this->mod_campaigns->check_linked_campdata_count($campid);
					
	       			if($link_camps>0)
	       			{
	       				for($i=0;$i<count($link_camps);$i++)
	       				{
	       					$campaign_id = $link_camps[$i]->campaignid;
	       					$banner_id = $link_camps[$i]->bannerid;
	       					$zone_id = $link_camps[$i]->zoneid;
	       					$this->mod_campaigns->unlink_campaign_placement_zone_assoc($campaign_id,$zone_id);
	       					$this->mod_campaigns->unlink_campaign_ad_zone_assoc($banner_id,$zone_id);
	       				}	       				
	       			}	       			
	       			
	       		}
			
	       }
               $campname    = $this->input->post('campname');
               $advertiser  = $adv_id;
               $weight      = $this->input->post('weight');
               $budget      = $this->input->post('budget');
			   $category	=$this->input->post("category");
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
					   
					   $campaign_rtb = $this->mod_campaigns->get_campaign_rtb($campid);
					   if($campaign_rtb!=$realtime)
					   {
						/* Remove Targeting */
						if($campaign_rtb==0 && $realtime==1)
						{
							/* Check Delivery Targeting */
						    	$check_camp = $this->mod_campaigns->check_delivery_targeting($campid);
						    	if($check_camp>0)
						    	{						    		
								$where_target = array('campaignid'=>$campid);
							    	$this->db->delete('djx_campaign_limitation', $where_target);
						    	}
						}

						elseif($campaign_rtb==1 && $realtime==0)
						{
							/* Check Delivery Targeting */
						    	$check_camp = $this->mod_campaigns->check_delivery_targeting($campid);

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

						   $link_check  = $this->mod_campaigns->check_campaign_linking_count($campid);
							if($link_check > 0)
							{
								$link_camps = $this->mod_campaigns->check_linked_campdata_count($campid);
								if($link_camps>0)
								{
									for($i=0;$i<count($link_camps);$i++)
									{
										$campaign_id = $link_camps[$i]->campaignid;
										$banner_id = $link_camps[$i]->bannerid;
										$zone_id = $link_camps[$i]->zoneid;
										$this->mod_campaigns->unlink_campaign_placement_zone_assoc($campaign_id,$zone_id);
										$this->mod_campaigns->unlink_campaign_ad_zone_assoc($banner_id,$zone_id);
									}	       				
								}	       			
							}
					   }
               /* Check Duplication for Campaign Name based on Client ID */
               $where_camp = array('campaignid'=>$campid,'clientid'=>$advertiser);
               $camp_check  = $this->mod_campaigns->check_campaign_duplication($where_camp);
               if(count($camp_check)<=1)
               {

                   /* Check Duplication for Campaign Name based on Client ID */
                   $where_camp = array('campaignid'=>$campid,'clientid'=>$advertiser);
                   $camp_check  = $this->mod_campaigns->check_campaign_duplication($where_camp);
                   if(count($camp_check)<=1)
                   {

                       /* Define Campaign Status based on Start Date and End Date */
                       if($campend=='')
                       {
                            $startvalue  = $this->mod_campaigns->compare_startdate_today($campstart, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
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
                            $startvalue  = $this->mod_campaigns->compare_startdate_today($campstart, $currdate);
                            $endvalue    = $this->mod_campaigns->compare_enddate_today($campend, $currdate);
                            /* Update Campaign Status based on Advertiser Balance */
                            $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
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
                    $edit_campaign = array(
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
			'catagory' => @implode(",", $category),
			'rtb' => $realtime
                    );
                    /* Campaign Insert Method and Get Last Insert ID */
                    $camp_update = $this->mod_campaigns->edit_campaign($edit_campaign,$where_camp);

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
                        'currentdate'=>date('Y-m-d')
                        );
                        /* Campaign Budget Edit. Redirection to Campaign List */
                        $where_campbudget = array('campaignid'=>$campid,'clientid'=>$advertiser);
                        if($this->mod_campaigns->edit_campaign_budget($edit_campaign_budget,$where_campbudget)){
                            /* Form Modified Successfully. Redirect to Campaign List */
                            $this->session->set_flashdata('form_edit_success', $this->lang->line('label_campaign_edit_success'));
                            redirect('advertiser/campaigns');
                        } else {
                            /* Form Validation is failed. Redirect to Add Campaign Form */
                            $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                            $this->edit($campid);
                        }


                    } else {
                        /* Form Validation is failed. Redirect to Add Campaign Form */
                        $this->session->set_userdata('camp_error', $this->lang->line('label_error_missing'));
                        $this->edit($campid);
                    }

               }
               else
               {
                   /* Campaign Duplication Error */
                   $this->session->set_userdata('camp_duplicate', $this->lang->line('label_duplicate_error'));
                   $this->edit($campid);
               }
            }
        }
        }
    }
	
	/* Function for check the assoc zone for the campaign*/
	   function check_mapping_zone($camp_id=0,$bannerid=0)
	   {
	   		$where_arr = array('campaignid' => $camp_id);
			$banners = $this->mod_campaigns->getBanners($where_arr);
			if($banners!=false)
			{
				foreach($banners as $banner)
				{
					$where_ban = array('ad_id' => $banner->bannerid);
					$zones_assoc = $this->mod_campaigns->get_ad_zone_assoc($where_ban);
				}
			}
			else
			{
				$zones_assoc = false;
			}
	   		$where_camp = array('placement_id' => $camp_id);
			
			$placement_assoc = $this->mod_campaigns->get_placement_zone_Assoc($where_camp);
			if($placement_assoc!=false or $zones_assoc!=false)
			{
				echo "yes";
				exit;
			}
			else
			{
				echo "no";
				exit;
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
                    $this->mod_campaigns->pause_campaign($camp[$m]);
                    /* Campaign Paused Successfully. Redirect to Campaign List */                    
                }
                $this->session->set_flashdata('pause_campaign', $this->lang->line('label_pause_success'));
            }
            else
            {
                    /* Campaign Pause Failed. Redirect to Campaign List */
                    $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                    redirect('advertiser/campaigns');
            }
    }

    /* Run/Activate Campaign */
    public function run_campaign($campid=0)
    {
        $adv_id = $this->session->userdata('session_advertiser_id');
        $advertiser = $adv_id;
        $today = date("Y-m-d");
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
                    /* Advertiser Account Balance */
                    $accBal = $this->mod_campaigns->check_advertiser_balance($advertiser);
                    
                    if(count($accBal)>0)
                    {
                        $adBbal = $accBal[0]->accbalance;
                    }
                    else
                    {
                        $adBbal = 0;
                    }
                    
                    /* Campaign Daily Budget */
                    $dailyBudget = $this->mod_campaigns->campaign_daily_budget($camp[$m]);
                    if(count($dailyBudget)>0)
                    {
                        $dailyBudget = $dailyBudget[0]->dailybudget;
                    }
                    else
                    {
                        $dailyBudget = 0;
                    }
                    /* Campaign Amount */
                    $campaign_amt = $this->mod_campaigns->campaign_amount($advertiser, $camp[$m], $today);
                    if(count($campaign_amt)>0)
                    {
                        $campaign_amount = $campaign_amt[0]->amount;
                    }
                    else
                    {
                        $campaign_amount = 0;
                    }
                    /* Campaign Run/Pause Dates */
                    $camp_dates = $this->mod_campaigns->get_campaign($camp[$m]);
                    if(count($camp_dates)>0)
                    {
                        $activate_date      = $camp_dates[0]->activate_time;
                        $expire_date        = $camp_dates[0]->expire_time;
                        $status_startdate   = $camp_dates[0]->status_startdate;
                        $status_enddate     = $camp_dates[0]->status_enddate;
					}
                    else
                    {
                        $activate_date      = '';
                        $expire_date        = '';
                        $status_startdate   = '';
                        $status_enddate     = '';
                    }

                    if($adBbal >0)
                    {
                    	if($campaign_amount <=$dailyBudget)
                        {
								if($status_enddate ==1)
                            	{
									$startvalue  = $this->mod_campaigns->compare_startdate_today($activate_date, $today);
									$endvalue    = $this->mod_campaigns->compare_enddate_today($expire_date, $today);
				
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

                                $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                                /* Campaign Run Successfully. Redirect to Campaign List */                                
                                //redirect('advertiser/campaigns');
                            }
                            else
                            {	
                                $startvalue  = $this->mod_campaigns->compare_startdate_today($activate_date, $today);
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

                                $where_camp = array('campaignid'=>$camp[$m],'clientid'=>$advertiser);
                                $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                                /* Campaign Run Successfully. Redirect to Campaign List */                               
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
                            $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                            /* Campaign Run Successfully. Redirect to Campaign List */
                            $this->session->set_flashdata('budget_completed', $this->lang->line('label_daily_budget_completed'));
                            //redirect('advertiser/campaigns');
                        }
                    }
                    else
                    {
                    	if($status_enddate==1)
                        {
                            $startvalue  = $this->mod_campaigns->compare_startdate_today($activate_date, $today);
                            $endvalue    = $this->mod_campaigns->compare_enddate_today($expire_date, $today);
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
                            $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                            /* Campaign Run failed. Redirect to Campaign List */                            
                            //redirect('advertiser/campaigns');
                        }
                        else
                        {
                            $startvalue  = $this->mod_campaigns->compare_startdate_today($activate_date, $today);
                            if($startvalue>0)
                            {
                                $status="2";
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
                            $camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);

                            /* Campaign Run failed. Redirect to Campaign List */
                            
                            //redirect('advertiser/campaigns');
                        }
                    }

                    /* Campaign Activated Successfully. Redirect to Campaign List */
                }
                $this->session->set_flashdata('run_campaign', $this->lang->line('label_run_success'));
                //redirect('advertiser/campaigns');
            }
            else
            {
                    /* Campaign Activate Failed. Redirect to Campaign List */
                    $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                    redirect('advertiser/campaigns');
            }
    }

    /* Delete Campaign */
    public function delete_campaign($campid=0)
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
                $banners = $this->mod_campaigns->getBanners($where_arr);
                /* Delete Budget Table Campaign/Banner data */
                foreach($banners as $bid){
                        $id	= $bid->bannerid;

                        $wherecre = array("creative_id"=>$id);
                        $this->mod_campaigns->deleteBKTA($wherecre);
                        $this->mod_campaigns->deleteBKTC($wherecre);
                        $this->mod_campaigns->deleteBKTM($wherecre);
                        $this->mod_campaigns->deleteBKTUC($wherecre);
                        $this->mod_campaigns->deleteBKTUM($wherecre);

                        $wheread = array("ad_id"=>$id);
                        $this->mod_campaigns->deleteBKTH($wheread);
                        $this->mod_campaigns->deleteBKTAD($wheread);
                        $this->mod_campaigns->deleteZoneAssoc($wheread);
                        $where_ban = array('bannerid'=>$id);
                        $where_master = array('bannerid'=>$id);
                        $this->mod_campaigns->delete_banner($where_ban,$where_master);
                 }

                 /* Delete Campaign Reports */
                    $this->mod_campaigns->delete_campaign_reports($where_arr);
                /* Delete Campign Budget */
                    $this->mod_campaigns->delete_campaign_budget($where_arr);
                /* Delete Campaign Targeting Features */    
            		$this->mod_campaigns->delete_targeting_limitation($where_arr);
            		$this->mod_campaigns->delete_campaign_limitation($where_arr);    
                /* Delete Campaign */
                    $this->mod_campaigns->delete_campaign($where_arr);
                
                    
                
                /* Campaign Deleted Successfully. Redirect to Campaign List */
            }

            $this->session->set_flashdata('form_delete_success', $this->lang->line('label_delete_camp_success'));
            //redirect('advertiser/campaigns');
        }
        /* Delete Campaigns using Action Link */
        else if($campid != false)
        {
            $where_arr = array('campaignid'=>$campid);
            /* Get Banner data for Selected Campaign */
            $banners = $this->mod_campaigns->getBanners($where_arr);
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
                $this->mod_campaigns->delete_campaign_reports($where_arr);
            /* Delete Campign Budget */
                $this->mod_campaigns->delete_campaign_budget($where_arr);
            /* Delete Campaign Targeting Features */    
    		$this->mod_campaigns->delete_targeting_limitation($where_arr);
    		$this->mod_campaigns->delete_campaign_limitation($where_arr);    
            /* Delete Campaign */
                $this->mod_campaigns->delete_campaign($where_arr);

            /* Campaign Deleted Successfully. Redirect to Campaign List */
            $this->session->set_flashdata('form_delete_success', $this->lang->line('label_delete_camp_success'));
            redirect('advertiser/campaigns');
        }
        else
        {
                /* Campaign Deleted Failed. Redirect to Campaign List */
                $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                redirect('advertiser/campaigns');
        }
    }

    	/*Targeting For Individual Campaign
	Mobile Targeting Features For Campaigns */
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
                $target = $this->mod_campaigns->check_targeting_campaign($campid);
                $data['target'] = $target;
				
                /*-------------------------------------------------------------
		 	Device OS List
		-------------------------------------------------------------*/
                $deviceos = $this->mod_campaigns->get_device_os();
                $data['deviceos'] = $deviceos;
                
                /*-------------------------------------------------------------
		 	Device Manufacturer List
		-------------------------------------------------------------*/
                $devicemanuf = $this->mod_campaigns->get_device_manufacturer();
                $data['devicemanuf'] = $devicemanuf;
                /*-------------------------------------------------------------
		 	Device Manufacturer List
		-------------------------------------------------------------*/
                $devicecap = $this->mod_campaigns->get_device_capabilty();
                $data['devicecap'] = $devicecap;
                /*-------------------------------------------------------------
		 	Geography Location List
		-------------------------------------------------------------*/
                $geolocation = $this->mod_campaigns->get_geo_location();
                $data['geolocation'] = $geolocation;
                
                /*-------------------------------------------------------------
		 	Age Group List
		-------------------------------------------------------------*/
                $agegroup = $this->mod_campaigns->get_age_group();
                $data['agegroup'] = $agegroup;
                
                 /*-------------------------------------------------------------
	 	Geography Operator List
		-------------------------------------------------------------*/
                $geooperatornew = $this->mod_campaigns->get_geo_operator_carrier();
                $data['geooperator_carrier'] = $geooperatornew;	
                /*-------------------------------------------------------------
            
		 	Embed current page content into template layout
		-------------------------------------------------------------*/
                $chktarget = $this->mod_campaigns->check_targeting_campaign($campid);

		/*******************************************
			Get Campaigns Details
		*********************************************/	
                $camp_summary	=	$this->mod_campaigns->get_campaign_summary($campid);
				//print_r($camp_summary);
				//exit;
		$data['camp_summary']	=	$camp_summary[0];
                
                if(count($chktarget>0)  &&  ($chktarget != FALSE))
                {
                    $data['page_content']	= $this->load->view("advertiser/campaigns/edit_targeting",$data,true);
                    $this->load->view('advertiser_layout',$data);
                }
                else
                {
                    $data['page_content']	= $this->load->view("advertiser/campaigns/targeting",$data,true);
                    $this->load->view('advertiser_layout',$data);
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
                $model_type     = $this->input->post('mobdevice');
                $gender         = $this->input->post('gender');
                $age            = $this->input->post('agegroup');
                $model_name     = $this->input->post('device_name');
                $carrier		= $this->input->post('nwcarrier');
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
                                
                
               /* if($carrier=='mobileCarrierLimitation')
                {
					$country = $this->input->post('country_operator');
					if($country!='')
					{
						$carrier_content = $this->input->post('destination_mobi');
						if(is_array($carrier_content) && count($carrier_content)>0)
						{
							$carrier_content = implode(',',$carrier_content);
							$limitations .=" AND MAX_checkMobileCarrierLimitation_mobileCarrierLimitation('".$country."|".$carrier_content."','=~')";
							$plugins     .="mobileCarrierLimitation".",";
						}
						else{
							$carrier_content ='';
						}
					}
					else{
						$carrier_content = '';
					}
                }
                else{
						$carrier_content = '';
						$country = '';
					}*/
					
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
               
			
                /* Delivery Targeting - Device Model */
        if((!empty($model_type) && $model_type !='0') && !empty($model_name))
		{
			$limitations .=" AND model('".$model_name."','".$model_type."')";
			$plugins     .="model".",";
		}
                
                //print_r($age_content);exit;
                
                /* Checking Campaign ID Availablity */
                $campcheck = $this->mod_campaigns->check_targeting_campaign($campid);
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
				if(count($campcheck>0)  && ($campcheck != FALSE))
				{

                    /* Array Parameters for Processing Tageting Features */
                    $target_features = array(
                        'devices'=>$os_content,
                        'manufacturer'=>$manu_content,
                        'capability'=>$cap_content,
                        'locations'=>$loc_content,
                        'gender'=>$gender,
                        'ages'=>$age_content,
                        'device_type'=>$os,
                        'manufacturer_type'=>$manufacturer,
                        'capability_type'=>$capabilty,
                        'location_type'=>$location,
                        'gender_type'=>$gender,
                        'ages_type'=>$age,
                        'model'=>$model_name,
                        'model_type'=>$model_type,
                        'carrier_type'=>$carrier,
                        'carriers' => $car_value,
                        'network_country' => $c_name
                    );
                    
                    
                    $where_camp = array('campaignid'=>$campid);

                    $update_target = $this->mod_campaigns->update_targeting_limitation($target_features,$where_camp);

                    if($update_target=='TRUE')
                    {
                    	/* Check Delivery Targeting */
                    	$check_camp = $this->mod_campaigns->check_delivery_targeting($campid);
                    	if($check_camp>0)
                    	{
                    		/* Update Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');	
		            	$plugins    	= rtrim($plugins,",");
						
				$deliver_target	= array('compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
				$where_camp_deliver = array('campaignid'=>$campid);
		            	$this->mod_campaigns->update_delivery_targeting($deliver_target, $where_camp_deliver);
                    	}
                    	else
                    	{
                    		/* Add Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
		            	$plugins    	= rtrim($plugins,",");
						
				
						
						
				$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
				$this->mod_campaigns->add_delivery_targeting($deliver_target);		            	
                    	}
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('advertiser/campaigns');
                    }
                    else
                    {
                        /* Campaign Targeting Update Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('advertiser/campaigns');
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
                            'gender'=>$gender,
                            'ages'=>$age_content,
                            'device_type'=>$os,
                            'manufacturer_type'=>$manufacturer,
                            'capability_type'=>$capabilty,
                            'location_type'=>$location,
                            'gender_type'=>$gender,
                            'ages_type'=>$age,
                            'model'=>$model_name,
                            'model_type'=>$model_type,
                            'carrier_type'=>$carrier,
							'carriers' => $car_value,
							'network_country' => $c_name
                        );
                    $add_target = $this->mod_campaigns->insert_targeting_limitation($target_features);

                    if($add_target=='TRUE')
                    {                    
                    	/* Add Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');
                    	$plugins    	= rtrim($plugins,",");
			$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations, 'acl_plugins' =>$plugins, 'status' =>'1');
			$this->mod_campaigns->add_delivery_targeting($deliver_target);
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('advertiser/campaigns');
                    }
                    else
                    {
                         /* Campaign Targeting Insert Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('advertiser/campaigns');
                    }
                }
            }
            else
            {
                /* Campaign Targeting Failed. Redirect to Campaign List */
                $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                redirect('advertiser/campaigns');
            }
            
        }

		/* Trackers Module for Advertiser level and Campaign level */
	
	public function trackers($campaignid=false, $start=0) //$status="all", $advertiser_id=0, $start=0)
	{
		$advertiser_id	=$this->clientid;
		$status			="all";
		
		if($campaignid ==false)
		{
				$data['sel_advertiser_id']		=$advertiser_id;
				$data['sel_status']			=$status;
				/*--------------------------------------------------------------
				  	GET NUMBER RECORDS BASED ON THEIR STATUS
				  ---------------------------------------------------------------*/
				
				$data['num_rec'] = $this->trackers->get_trackers_count($advertiser_id);
					
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
				
				$data['trackers_list']		=	$this->trackers->get_trackers_list($advertiser_id, $status);
				
				
				/*--------------------------------------------------------------
					Pagination  Config Setup
				 ---------------------------------------------------------------*/
				
				$limit 					=$this->page_limit;
				$config['base_url']		=site_url("advertiser/campaigns/trackers");
				$config['per_page'] 	=$limit;
				$config['uri_segment'] 	=6;
				$config['total_rows'] 	=count($data['trackers_list']);//'5';
				$config['next_link'] 	=$this->lang->line("pagination_next_link");
				$config['prev_link'] 	=$this->lang->line("pagination_prev_link");		
				$config['last_link'] 	=$this->lang->line("pagination_last_link");		
				$config['first_link'] 	=$this->lang->line("pagination_first_link");
				
				$this->pagination->initialize($config);		
				
				if($config['total_rows'] > $limit)
				{
					$data['trackers_list'] 	=$this->trackers->get_trackers_list($advertiser_id,$status,$start,$limit);
				}
				
				$data['trackers_list']		=$data['trackers_list'];

			   /*-------------------------------------------------------------
						Embed current page content into template layout
				--------------------------------------------------------------*/
				$data['page_content']		= $this->load->view("advertiser/trackers_list", $data, true);
				$this->load->view('advertiser_layout', $data);
		}
		else if($campaignid !=false)
		{
			$data['sel_advertiser_id']		=	$advertiser_id;
			$data['sel_campaign_id']		=	$campaignid;
			$data['sel_status']				=	$status;
			
			/* --------------------------------------------------------------
			  	GET NUMBER RECORDS BASED ON THEIR STATUS
			/* ---------------------------------------------------------------*/
				
			$data['num_rec'] = $this->trackers->get_campaign_trackers_count($advertiser_id, $campaignid);
					
			/* -------------------------------------------------------------
				Page Title showed at the content section of page
			/* -------------------------------------------------------------*/
			
			$data['page_title'] 	= $this->lang->line('label_inventory_campaign_trackers_page_title');
		
			/* -------------------------------------------------------------
				Breadcrumb Setup Start
			/* --------------------------------------------------------------*/
			
			$link = breadcrumb();
			$data['breadcrumb'] 	= $link;
		
			/* --------------------------------------------------------------------
				*  GET Trackers list from DB based on selected Advertiser
			/* --------------------------------------------------------------------*/
				
			$data['trackers_list']		=	$this->trackers->get_campaign_trackers_list($advertiser_id, $campaignid, $status);
				
				
			/* --------------------------------------------------------------
				Pagination  Config Setup
			/* ---------------------------------------------------------------*/
				
			$limit 					=$this->page_limit;
			$config['base_url']		=site_url("advertiser/campaigns/trackers/$campaignid/$start");
			$config['per_page'] 	=$limit;
			$config['uri_segment'] 	=6;
			$config['total_rows'] 	=count($data['trackers_list']);//'5';
			$config['next_link'] 	=$this->lang->line("pagination_next_link");
			$config['prev_link'] 	=$this->lang->line("pagination_prev_link");		
			$config['last_link'] 	=$this->lang->line("pagination_last_link");		
			$config['first_link'] 	=$this->lang->line("pagination_first_link");
				
			$this->pagination->initialize($config);		
				
			if($config['total_rows'] > $limit)
			{
				$data['trackers_list'] 	=$this->trackers->get_campaign_trackers_list($advertiser_id, $campaignid, $status, $start, $limit);
			}
				
			$data['trackers_list']		=$data['trackers_list'];

			/* -------------------------------------------------------------
				Embed current page content into template layout
			/* --------------------------------------------------------------*/
			
			$data['page_content']		= $this->load->view("advertiser/campaign_trackers_list", $data, true);
			$this->load->view('advertiser_layout', $data);
		}
		else
		{
			$this->session->set_flashdata('error_message', $this->lang->line('label_advertiser_trackers_advid_notfound'));
			redirect("advertiser/dashboard");
		}
	}		
	
	/* Trackers Module for Advertiser level and Campaign level */

	
	public function duplicate($campaign_id=FALSE){
			
			if($campaign_id != FALSE){
			
				/*-------------------------------------------------------------
						Breadcrumb Setup Start
				-------------------------------------------------------------*/
				$link = breadcrumb();
				$data['breadcrumb'] 		= $link;
				
				$data['sel_campaign_id']	=	$campaign_id;
				
				$clientid 			= $this->session->userdata('session_advertiser_id');
				
				$where_arr					=	array("`ox_campaigns`.campaignid"=>$campaign_id, "`ox_campaigns`.clientid"=>$clientid);
				
				$temp	=	$this->mod_campaigns->retrieve_campaign($where_arr);
				
				if($temp!=false){//(count($temp) > 0){
					$data['campaign_name']		=  $temp[0]->campaignname;	
				}
				else
				{
					$data['campaign_name']		=  "";	
					//redirect('advertiser/campaigns');
				}
				
				
				/*-------------------------------------------------------------
						Embed current page content into template layout
				 -------------------------------------------------------------*/
				 if($temp!=false)
				 {
				$data['page_content']	= $this->load->view("advertiser/campaigns/duplicate_campaign",$data,true);
				$this->load->view('advertiser_layout',$data);
				}
				else
				 {
					$data['page_content']	= $this->load->view("campaigns/no_page",$data,true);
					$this->load->view('advertiser_layout',$data);
				 }
					
			}
			else
			{
				redirect("advertiser/campaigns");
			}
		}
		
		public function duplicate_campaign_process(){
			if($this->input->post('sel_campaign_id') != ''){
				
				$clientid 			= $this->session->userdata('session_advertiser_id');
				$campaign_id		=	$this->input->post('sel_campaign_id');
				$dup_campaign_name	= 	$this->input->post('campaign_name');
				
				 /* Check Duplication for Campaign Name based on Client ID */
               $where_camp = array('campaignname'=>$dup_campaign_name,'clientid'=>$clientid);
               $camp_check  = $this->mod_campaigns->check_campaign_duplication($where_camp);
               if(count($camp_check)==0)
               {
				
				$where_arr					=	array("`ox_campaigns`.campaignid"=>$campaign_id);
				
				$t	=	$this->mod_campaigns->retrieve_campaign($where_arr);
				
				$campaign_data	= $t[0];
				
				$new_campaign	=	array(
											"campaignname"		=>	$dup_campaign_name,
											"clientid"			=>	$clientid,
											"activate_time"		=>	$campaign_data->activate_time,
											"expire_time"		=>	$campaign_data->expire_time,
											"revenue_type"		=>	$campaign_data->revenue_type,
											"status"			=>	$campaign_data->status,
											"inactive"			=>	$campaign_data->inactive,
											"status_startdate"	=>	$campaign_data->status_startdate,
											"status_enddate"	=>	$campaign_data->status_enddate,
											"revenue"			=>	$campaign_data->revenue
									);
				
				 $new_campaign_id = $this->mod_campaigns->add_new_campaign($new_campaign);
				 $old_campaign_id	=	$campaign_id;				
								
				/* Add Campaign Budget Parameters */
				
				$add_campaign_budget = array(
										'clientid'			=>	$clientid,
										'campaignid'		=>	$new_campaign_id,
										'dailybudget'		=>	$campaign_data->dailybudget,
										"budget"			=>	$campaign_data->budget,
										'currentdate'		=>	date('Y-m-d')
										);
				
				/* Add Campaign Budget  */
				$this->mod_campaigns->add_campaign_budget($add_campaign_budget);
				
				
				//DUPLICATE PLACEMENT ZONE ASSOCIATIONS
				
				$this->mod_campaigns->duplicate_placement_zone_assoc($old_campaign_id,$new_campaign_id);
				
				// COPY TARGETTING
				
				$this->mod_campaigns->duplicate_targetting($old_campaign_id,$new_campaign_id);
				
				// DUPLICATE CAMPAIGNS LIMITATIONS
				
				$this->mod_campaigns->duplicate_campaign_limitations($old_campaign_id,$new_campaign_id);
				
				// DUPLICATE BANNERS AND IT'S CHILD
				
				$this->mod_campaigns->duplicate_banners($old_campaign_id,$new_campaign_id);
				
				
				$this->session->set_flashdata('form_add_success', "New Campaign:: <b>".$dup_campaign_name."</b> have  been created..");
				redirect("advertiser/campaigns");
				
				}
				else
				{
					/* Campaign Duplication Error */
                   $this->session->set_userdata('camp_duplicate', $this->lang->line('label_camp_duplicate_error'));
				   $this->duplicate($campaign_id);
				}
			}
			else
			{
				redirect("advertiser/campaigns");
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
                $target = $this->mod_campaigns->check_rtb_targeting_campaign($campid);
                $data['target'] = $target;
               
                /*-------------------------------------------------------------
		 	Geography Location List
		-------------------------------------------------------------*/
                $geolocation = $this->mod_campaigns->get_geo_location();
                $data['geolocation'] = $geolocation;
               
		
		 /*-------------------------------------------------------------
		 	Embed current page content into template layout
		-------------------------------------------------------------*/
                $chktarget = $this->mod_campaigns->check_rtb_targeting_campaign($campid);

		/*******************************************
			Get Campaigns Details
		*********************************************/	
                $camp_summary	=	$this->mod_campaigns->get_campaign_summary($campid);
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
                    $data['page_content']	= $this->load->view("advertiser/campaigns/edit_rtb_targeting",$data,true);
                    $this->load->view('advertiser_layout',$data);
                }
                else
                {
                    $data['page_content']	= $this->load->view("advertiser/campaigns/rtb_targeting",$data,true);
                    $this->load->view('advertiser_layout',$data);
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
                $campcheck = $this->mod_campaigns->check_rtb_targeting_campaign($campid);
                
                /* Update Targeting Features */
                if(count($campcheck)>0){
                    /* Array Parameters for Processing Tageting Features */
                    $target_features = array(
                        'type' =>'country',
                        'data' => $loc_content
                  );
                  
                  
                   $where_camp = array('campaign_id'=>$campid);
					
                    $update_target = $this->mod_campaigns->update_rtb_targeting_limitation($target_features,$where_camp);
                    
                    /* Checking Campaign ID Availablity */
					$campcheck_targ_limit = $this->mod_campaigns->check_targeting_campaign($campid);
					
					if(!empty($campcheck_targ_limit)){

						$target_features_camp = array('locations'=>$loc_content,'location_type'=>$location);
					  
						$where_camp_limit = array('campaignid'=>$campid);
					  
						$this->mod_campaigns->update_targeting_limitation($target_features_camp,$where_camp_limit);
					}
					else
					{
						$target_features_limit = array('campaignid'=>$campid,'locations'=>$loc_content,'location_type'=>$location);

						$add_target = $this->mod_campaigns->insert_targeting_limitation($target_features_limit);
					}
                    
                    if($update_target=='TRUE')
                    {
                    	/* Check Delivery Targeting */
                    	$check_camp = $this->mod_campaigns->check_rtb_delivery_targeting($campid);
                    	if($check_camp>0)
                    	{
                    		/* Update Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
							$plugins    	= rtrim($plugins,",");
							
							$deliver_target	= array('compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
							$where_camp_deliver = array('campaign_id'=>$campid);
							$this->mod_campaigns->update_rtb_delivery_targeting($deliver_target, $where_camp_deliver);
							
							/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
							
							$deliver_target_camp	= array('compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
							$where_camp_deliver_camp = array('campaignid'=>$campid);
							$this->mod_campaigns->update_delivery_targeting($deliver_target_camp, $where_camp_deliver_camp);
                    	}
                    	else
                    	{
                    		/* Add Banner Delivery Part Targeting Values */
							$limitations = ltrim($limitations,' AND');	
							$plugins    	= rtrim($plugins,",");
							$deliver_target	= array('campaign_id'=>$campid, 'compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
							$this->mod_campaigns->add_rtb_delivery_targeting($deliver_target);
							
							/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
							$deliver_target	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
							$this->mod_campaigns->add_delivery_targeting($deliver_target);		            			            	
                    	}
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('advertiser/campaigns');
                    }
                    else
                    {
                        /* Campaign Targeting Update Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('advertiser/campaigns');
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

                    $add_target = $this->mod_campaigns->insert_rtb_targeting_limitation($target_features);
                    
                    $target_features_limit = array('campaignid'=>$campid,'locations'=>$loc_content,'location_type'=>$location);

                    $add_target = $this->mod_campaigns->insert_targeting_limitation($target_features_limit);

                    if($add_target=='TRUE')
                    {                    
                    	/* Add Banner Delivery Part Targeting Values */
						$limitations = ltrim($limitations,' AND');
                    	$plugins    	= rtrim($plugins,",");
						$deliver_target	= array('campaign_id'=>$campid, 'compiled_limitation' =>$limitations, 'acl_plugins' =>$plugins);
						$this->mod_campaigns->add_rtb_delivery_targeting($deliver_target);
						
						/* Delivery Targeting - Geographic Locations */
							$limitations_camp = ltrim($limitations_camp,' AND');
							$plugins_camp     = rtrim($plugins_camp,",");
						$deliver_target_camp	= array('campaignid'=>$campid, 'compiledlimitation' =>$limitations_camp, 'acl_plugins' =>$plugins_camp, 'status' =>'1');
						$this->mod_campaigns->add_delivery_targeting($deliver_target_camp);
                    
                        /* Campaign Target completed Successfully. Redirect to Campaign List */
                        $this->session->set_flashdata('form_target_success', $this->lang->line('label_target_camp_success'));
                        redirect('advertiser/campaigns');
                    }
                    else
                    {
                         /* Campaign Targeting Insert Failed. Redirect to Campaign List */
                        $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                        redirect('advertiser/campaigns');
                    }
                }
            }
            else
            {
                /* Campaign Targeting Failed. Redirect to Campaign List */
                $this->session->set_flashdata('camp_error', $this->lang->line('label_error_missing'));
                redirect('advertiser/campaigns');
            }
            
        }
        
        /* GET REGION*/
		public function region()
		{
			if($this->input->post('country_code')!='')
			{
				$country = $this->input->post('country_code');

				$region_list = $this->mod_campaigns->get_region_operator($country);	
				
				$data['region']  = $region_list;
				
				if($this->input->post('selected_country_code')!='' && $this->input->post('selected_country_code')!='0')
				{
						if($country ==$this->input->post('selected_country_code'))
						{
							$data['selected']	=	$this->input->post('selected_carriers');
						}
				}

				echo $this->load->view('advertiser/campaigns/geo_country_network',$data,true);
			}
		}

}

/* End of file campaigns.php */
/* Location: ./modules/advertiser/campaigns.php */

