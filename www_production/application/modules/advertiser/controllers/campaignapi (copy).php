<?php
class Campaignapi extends CI_Controller
{
       public  $logger = null;
	
        public function __construct()
	{
		parent::__construct();
                $this->load->library('logger');
                $this->logger = new Logger();
		$this->load->model('mod_campaigns');
                $this->load->model('mod_storefront');
                $this->load->model('mod_statistics');
                $this->load->model('mod_optimisation');
	}

	/* Dashboard Page */
	public function index()
	{       
		$postdata= array_merge($_GET, $_POST, $_FILES);
		if(!isset($postdata['data']) or empty($postdata['data']))
		{

			$this->logger->logerror('Campaignapi::index()  request data is null');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>NULL_DATA,
					ERR_STR =>NULL_DATA_STR
			);
			echo json_encode($response);
			return;
		}

		$jsonStr = $postdata['data'];
		$attributes = json_decode($jsonStr,true);

		if(isset($attributes['api']))
		{
			switch($attributes['api'])
			{
				case 'add_campaign_request':
					$this->handleAddCampaignRequest($attributes);
					break;

				case 'fetch_promote_plan':
					
					$this->handleFetchPromotePlan($attributes);
					break;
                case 'fetch_campaign_request':
                    $this->handleFetchCampaignList($attributes);
                    break;
                case 'fetch_stat_request':
                    $this->handleFetchStatData($attributes);
                    break;
                case 'update_campaign_status':
					$this->handleUpdateCampaignStatus($attributes);
                    break;
                case 'add_order_detail_request':
                    $this->handleAddOrderDetail($attributes);
                    break;
				default:
					//echo "default";
					break;
			}
		}
		else
		{
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>INVALID_API,
					ERR_STR =>INVALID_API_STR
			);
			echo json_encode($response);
			return;
		}


	}

    private function handleAddOrderDetail($attributes)
    {
      $this->logger->logdebug("Campaignapi ::handleAddOrderDetail entered");
       if(!empty($attributes['order_id']) && !empty($attributes['sub_order_id']) && !empty($attributes['order_amount']) 
              && !empty($attributes['cust_id']) && !empty($attributes['cust_email']) && !empty($attributes['cust_mobile']) && isset($attributes['isFreePlan']))
       {
          $row = array(
                      'order_id'=>$attributes['order_id'],
                      'sub_order_id'=>$attributes['sub_order_id'],
                      'product_sku'=>$attributes['product_sku'],
                      'order_amount'=>$attributes['order_amount'],
                      'currency'=>$attributes['currency'],
                      'order_date'=>$attributes['order_date'],
                      'order_status'=>$attributes['order_status'],
                      'cust_id'=>$attributes['cust_id'],
                      'cust_name'=>$attributes['cust_name'],
                      'cust_email'=>$attributes['cust_email'],
                      'cust_mobile'=>$attributes['cust_mobile'],
                      'cust_country'=>$attributes['cust_country'],
                      'cust_pincode'=>$attributes['cust_pincode'],
                      'invoice_id'=>$attributes['invoice_id'],
                      'isFreePlan'=>$attributes['isFreePlan']
                   );

              $result = $this->mod_campaigns->addOrder($row);
              if($result)
              {
                    $currdate = date("Y-m-d");
                    $this->db->select('activate_time');
                    $this->db->where('order_id',$attributes['order_id']);
                    $this->db->where('clientid',$attributes['cust_id']);
                    $query = $this->db->get('ox_campaigns');
                    if($query->num_rows>0)
                    {
                        $rs = $query->row();
                        $campstart = $rs->activate_time;
                    }
                
               /*   $startvalue  = $this->mod_campaigns->compare_startdate_today($campstart, $currdate);
                    
                    if($startvalue>0)
		    {
			$status = '2';
			//$inactive = '1';
		    }
		    else
		    {
			$status = '0';
			//$inactive = '0';
                        
		    }    */

                    $status = 5; //Compliance
                    $res = $this->mod_campaigns->updateCampaignStatus($attributes['order_id'],$attributes['cust_id'],$status);
                    
                    if($res)
                    {
                          $data = array('clientid'=>$attributes['cust_id']);
                          $this->handleFetchCampaignList($data);
                    }
                    else
                    {
                       $this->logger->logerror("Campaignapi ::handleAddOrderDetail database error");
                       $response = array(
                          STATUS=>ERR_STATUS,
                          ERR_CODE_KEY =>DB_ERR,
                          ERR_STR =>DB_ERR_STR
                        );
                       echo json_encode($response);
                       return;
                   }
		  
              }
              else
              {
                  $this->logger->logerror("Campaignapi ::handleAddOrderDetail database error");
                  $response = array(
                    STATUS=>ERR_STATUS,
                    ERR_CODE_KEY =>DB_ERR,
                    ERR_STR =>DB_ERR_STR
                   );
                   echo json_encode($response);
                   return;
              }

       }
       else
       {
           $this->logger->logerror("Campaignapi ::handleAddOrderDetail Required parameters are missing");
           $response = array(
                STATUS=>ERR_STATUS,
                ERR_CODE_KEY =>PARAM_REQ,
                ERR_STR =>PARAM_REQ_STR
            );
            echo json_encode($response);
            return;
       }
   }
    private function handleUpdateCampaignStatus($attributes)
    {
        $this->logger->logdebug("Campaignapi ::handleUpdateCampaignStatus entered");
        if(!empty($attributes['clientid']) && !empty($attributes['campaignid']) && isset($attributes['camp_status']))
        {
             $result=false;
            switch($attributes['camp_status'])
            {
                case STATUS_PAUSE:
                   $result = $this->mod_campaigns->pause_campaign($attributes['campaignid']);
                    break;
                case STATUS_RUN:
                    $result = $this->mod_campaigns->run_campaign($attributes['campaignid']);
                    break;
                default:
                   break;
            }

            if($result)
            {
                $response = array(
                    STATUS=>SUCCESS_STATUS,
                    'campaignid'=>$attributes['campaignid'],
                    'camp_status'=>$attributes['camp_status']
                );
                echo json_encode($response);
                return;
            }
            else
            {
               $this->logger->logerror("Campaignapi ::handleUpdateCampaignStatus database error");
                $response = array(
                    STATUS=>ERR_STATUS,
                    ERR_CODE_KEY =>DB_ERR,
                    ERR_STR =>DB_ERR_STR
                );
                echo json_encode($response);
                return;
            }
        }
        else{
              $this->logger->logerror("Campaignapi ::handleUpdateCampaignStatus required parameters are missing");
            $response = array(
                STATUS=>ERR_STATUS,
                ERR_CODE_KEY =>PARAM_REQ,
                ERR_STR =>PARAM_REQ_STR
            );
            echo json_encode($response);
            return;
        }
    }


     /**
     * This method is used to fetch campaign statistics datewise
     * @param $attributes
     */
    private function handleFetchStatData($attributes)
    {
        if(!empty($attributes['clientid']))
        {


            $query     = $this->db->select('account_id')->get('ox_clients');

            if(count($query)>0)
            {
                $resultset = $query->row();
                $search_arr	=	array();
                $search_arr['sel_advertiser_id']	=	$attributes['clientid'];

                $account_id = $resultset->account_id;
                $search_arr['from_date']			= $this->mod_statistics->get_start_date($account_id);
                $search_arr['to_date']				= date("Y/m/d");


                $result = $this->mod_campaigns->get_campaign_report_for_advertiser_datewise($search_arr);

                if($result!=null and count($result)>0)
                {
                    $response = array(
                        STATUS=>SUCCESS_STATUS,
                        'stat_list'=>$result
                    );
                    echo json_encode($response);
                    return;

                }
                else{
                    $response = array(
                        STATUS=>ERR_STATUS,
                        ERR_CODE_KEY =>NULL_DATA,
                        ERR_STR =>NULL_DATA_STR
                    );
                    echo json_encode($response);
                    return;
                }

            }
            else{
                $response = array(
                    STATUS=>ERR_STATUS,
                    ERR_CODE_KEY =>DB_ERR,
                    ERR_STR =>DB_ERR_STR
                );
                echo json_encode($response);
                return;
            }




        }
        else
        {
            $response = array(
                STATUS=>ERR_STATUS,
                ERR_CODE_KEY =>PARAM_REQ,
                ERR_STR =>PARAM_REQ_STR
            );
            echo json_encode($response);
            return;
        }
    }



    /**
     * This method is used to fetch data of campaigns
     * such as impression,clicks,start date and status
     * @param $data
     */
    public function handleFetchCampaignList($data)
    {
        $this->logger->logdebug("Campaignapi ::handleUpdateCampaignStatus entered");
        if(!empty($data['clientid']))
        {
            $camplist = $this->mod_campaigns->getCampaignData($data['clientid']);
            $result = array();

            $count =0;
            if(count($camplist)>0)
            {
                $usd_rate =0;
                $resultset   = $this->mod_campaigns->getCampCPM();
		if($resultset!=false)
		{
		     $usd_rate = $resultset->usd_rate;
		}
		
                foreach($camplist as $cmp)
                {
                    $buck_imp      = $this->mod_campaigns->get_budget_impressions($cmp->clientid,$cmp->campaignid);
                    $buck_cli = $this->mod_campaigns->get_budget_clicks($cmp->clientid,$cmp->campaignid);
                    $common_stat = $this->mod_campaigns->get_common_stats($cmp->campaignid);
                    $location = $this->mod_campaigns->getCampLocation($cmp->campaignid);
                    $impressions = $common_stat[0]->impress+$buck_imp[0]->count;
                    $clicks = $common_stat[0]->clicks+$buck_cli[0]->count;
         
                    $result[$count]['camp_id'] = $cmp->campaignid;
                    $result[$count]['clientid'] = $cmp->clientid;
                    $result[$count]['camp_name'] = $cmp->campaignname;
                    $result[$count]['promo_msg'] = $cmp->promote_msg;
                    $result[$count]['start_date'] = empty($cmp->activate_time)?0:strtotime($cmp->activate_time);
                    $result[$count]['end_date'] =empty($cmp->expire_time)?0:strtotime($cmp->expire_time);
                    $result[$count]['total_imp'] = $cmp->total_imp;
                    $result[$count]['shown_imp'] = $impressions;
                    $result[$count]['clicks']    = $clicks;
                    $actions = $this->mod_campaigns->getClickToAction($cmp->campaignid);
                    //$this->logger->logdebug("Click to actions are ".print_r($actions,true));
                    $result[$count]['call'] = empty($actions->click_to_call)?0:$actions->click_to_call;
                    $result[$count]['web'] = empty($actions->click_to_web)?0:$actions->click_to_web;
                    $result[$count]['map'] = empty($actions->click_to_map)?0:$actions->click_to_map;
                    $result[$count]['camp_status'] = $cmp->status;
                    $result[$count]['order_id']= $cmp->order_id;
                    $result[$count]['lat']=$location->lat;
                    $result[$count]['lon']=$location->lon;
                    $total_amount = ($usd_rate*$cmp->total_budget);
                    $result[$count]['order_amount'] = round(round($total_amount,1));
                    $count++;
                }

                $response = array(
                    STATUS=>SUCCESS_STATUS,
                    'camp_list'=>$result
                );
                echo json_encode($response);
                return;


            }
            else{
                   $this->logger->logerror("Campaignapi ::handleFetchCampaignList() camp list is null");
                $response = array(
                    STATUS=>ERR_STATUS,
                    ERR_CODE_KEY =>NULL_DATA,
                    ERR_STR =>NULL_DATA_STR
                );
                echo json_encode($response);
                return;
            }

        }
        else
        {
            $this->logger->logerror('Campaignapi::handleFetchCampaignList()  required parameters are missing');
            $response = array(
                STATUS=>ERR_STATUS,
                ERR_CODE_KEY =>PARAM_REQ,
                ERR_STR =>PARAM_REQ_STR
            );
            echo json_encode($response);
            return;
        }
    }
    /**
     * This method is used to Fetch Prmotion plans
     * @param $attributes
     */
	public function handleFetchPromotePlan($attributes)
	{
               $this->logger->logdebug('Campaignapi::handleFetchPromotePlan()  entered');
 		if(!empty($attributes['clientid']))
	   	{
			$this->db->select('*');
			$query=$this->db->get('merchant_promotion_plan');
			$plans =null;
			if($query->num_rows()>0)
			{
				$rows = $query->result_array();
				$i=0;	
				foreach($rows as $r)
				{
					$plans[$i] =array('id'=>(int)$r[id],'viewer'=>(int)$r[viewer],'inr_price'=>(int)$r[inr_price],
						'message'=>$r['message'],'free_plan'=>$r['free_plan']);
					$i= $i+1;
				}		
			}
		        else
			{
                                $this->logger->logerror('Campaignapi::handleFetchPromotePlan()  database error');
				$response = array(
							STATUS=>ERR_STATUS,
							ERR_CODE_KEY =>DB_ERR,
							ERR_STR =>DB_ERR_STR
					);
					echo json_encode($response);
					return;
			}	
			
			$response = array(
					STATUS=>SUCCESS_STATUS,
					'plans'=>$plans				
					);
			echo json_encode($response);
			return;
			
	   	}
		else
		{
			$this->logger->logerror('Campaignapi::handleFetchPromotePlan()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);
			return;
		}
	}

	public function handleAddCampaignRequest($attributes)
	{

		$this->logger->logdebug('Campaignapi::handleAddCampaignRequest()  entered');	
		if(!empty($attributes['clientid']) and !empty($attributes['camp_name']) and !empty($attributes['lat']) and !empty($attributes['lon'])  and !empty($attributes['promote_msg'])  and !empty($attributes['startTime']) and !empty($attributes['accbal']) and !empty($attributes['click_to_action']) )
		{
			
			$advertiser = $attributes['clientid'];
			
			$userExist  = $this->mod_campaigns->advExist($advertiser);
			if(!$userExist)
			{
				$this->logger->logerror('Campaignapi::handleAddCampaignRequest()  Invalid user');
				$response = array(
						STATUS=>ERR_STATUS,
						ERR_CODE_KEY =>INVALID_USER,
						ERR_STR =>INVALID_USER_STR
				);
				echo json_encode($response);
				return;
			}			

			$campstart=date('Y-m-d H:i:s',$attributes['startTime']/1000);//$attributes['startTime'];
			$campend=NULL;
			$revenue=CPM_RATE; //CPM Rate
			$campname = $attributes['camp_name'];
			$weight=3;
            $status = 4; // Unpaid Status
			$promote_msg = $attributes['promote_msg'];			
		    $web_url = $attributes['web_url'];	
			$balance = $attributes['accbal'];
			
			// convert Account Balance in USD

			$resultset   = $this->mod_campaigns->getCampCPM();
			
			if($resultset!=false)
			{
		        	$advbal = $balance/$resultset->usd_rate;
				$revenue = $resultset->cpm_rate;
			
			}
			else
			{
                           $this->logger->logerror('Campaignapi::handleAddCampaignRequest()  database error');
			    $response = array(
							STATUS=>ERR_STATUS,
							ERR_CODE_KEY =>DB_ERR,
							ERR_STR =>DB_ERR_STR
					);
					echo json_encode($response);
					return;	
			}
			
			
			$no_of_days   = 1;
			$rs           = $this->mod_campaigns->getCampDays($attributes['accbal']);

                        $no_of_days = $rs->days_to_run;
                        $total_imp  = $rs->viewer;
			$budget = $advbal/$no_of_days;  // To calculate Daily budget for campaign
			$sdate=1;
			$edate=0;
			$pmodel=1;  //CPM

			/* Check Duplication for Campaign Name based on Client ID */
			$where_camp = array('campaignname'=>$campname,'clientid'=>$advertiser);
			$camp_check  = $this->mod_campaigns->check_campaign_duplication($where_camp);

			if(count($camp_check)==0)
			{

				$currdate = date("Y-m-d");
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

						if($startvalue>0)
						{
						//	$status = '2';
							$inactive = '1';
						}
						else
						{
						//	$status = '0';
							$inactive = '0';
						}

				}
                               
                                /*   Generate OrderId and insert to campaign table and set status to unpaid (4) */
                                $order_id = $this->mod_campaigns->generateOrderId();
                                if(!$order_id)
                                {
                                     $this->logger->logerror('Campaignapi::handleAddCampaignRequest()  database error generating orderid');
                                     $response = array(
								STATUS=>ERR_STATUS,
								ERR_CODE_KEY =>DB_ERR,
								ERR_STR =>DB_ERR_STR
						);
						echo json_encode($response);
						return;
                                }

				/* Add Campaign Parameters */
				$add_campaign = array(
						'campaignname'=>mysql_real_escape_string($campname),
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
						'catagory' =>"",
						'rtb' =>0,
						'promote_msg'=>$promote_msg,
                                                'web_url'=>$web_url,
                                                'total_budget'=>$advbal,
                                                'total_imp'=>$total_imp,
                                                'order_id'=>$order_id
				);
				/* Campaign Insert Method and Get Last Insert ID */
				$last_camp_id = $this->mod_campaigns->add_new_campaign($add_campaign);

				if($last_camp_id)
				{

					/* Add Campaign Budget Parameters */
					$add_campaign_budget = array(
							'clientid'=>$advertiser,
							'campaignid'=>$last_camp_id,
							'budget'=>$budget,
							'dailybudget'=>$budget,
							'currentdate'=>date('Y-m-d')
					);
					/* Campaign Budget Insert  */
					if($this->mod_campaigns->add_campaign_budget($add_campaign_budget))
					{

						$advFund = $this->mod_campaigns->getFund($advertiser);
				
						$current_value =$advFund+$advbal;
						
						if($advFund!=false)
						{
						
								$this->mod_campaigns->update_fund($advertiser,$current_value);
						}
						else
						{
						
								$this->mod_campaigns->insert_fund($advertiser,$current_value);
						}

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
								//$status = '1';
								$inactive = '1';
							}
							else
							{
								$campStat = $this->mod_campaigns->check_campaign_status($last_camp_id);
								$campStat = $campStat[0];
								if($campStat->status==0 && $campStat->inactive==0)
								{
								//	$status = '0';
									$inactive = '0';
								}
								else
								{
								//	$status = $campStat->status;
									$inactive = $campStat->inactive;
								}
							}
						}
						else
						{
							//$status = '1';
							$inactive = '1';
						}

						$update_status = array(
								'inactive'=>$inactive
						);
							
						$where_camp = array('campaignid'=>$last_camp_id,'clientid'=>$advertiser);
						$camp_update = $this->mod_campaigns->edit_campaign($update_status,$where_camp);
                                                $mobile = $this->mod_campaigns->getAdvMobile($advertiser);
						$table_row =array();
						$row = array("lat"=>$attributes['lat'],
						      	     "lon"=>$attributes['lon'],
                                                             "tel"=>$mobile,
							     "clientid"=>$advertiser		
							);
							
						$tbl_name			='storefrontdata';
						array_push($table_row,$row);
					        $storeId = $this->mod_storefront->insert_data($table_row,$tbl_name,$advertiser);

                                                // Insert store id and campaign id in relationship table
                                                $table_name = 'campaign_location_table';
                                                $row = array('client_id'=>$advertiser,'campaign_id'=>$last_camp_id,'store_id'=>$storeId);
                                                $this->db->insert($table_name,$row);
                                                if($this->db->affected_rows()<1)
                                                {
                                                      $response = array(
                                                                         STATUS=>ERR_STATUS,
                                                                         ERR_CODE_KEY =>DB_ERR,
                                                                         ERR_STR =>DB_ERR_STR
                                                                      );
                                                      echo json_encode($response);
                                                      return;
                                                }
                                                
                                                /**
						 * Distribute Networks wise impressions
						 */
						$mix_data=$this->mod_optimisation->camp_optimization_list();

						if($mix_data)
						{
							for($i=0;$i<count($mix_data);$i++)
							{
								$imp=($total_imp * $mix_data[$i]->Mix_percent)/100 ;
								$res=$this->mod_optimisation->enter_target_impressions($last_camp_id,$mix_data[$i]->Mix_id,$imp);
							}
						}
						else
						{
							$this->logger->logerror('Campaignapi::handleAddCampaignRequest()  Database Error in mix disribution');
							$response = array(
									STATUS=>ERR_STATUS,
									ERR_CODE_KEY =>DB_ERR,
									ERR_STR =>DB_ERR_STR
							);
							echo json_encode($response);
							return;
						}

  
                                                /** Set click to action   **/
                                                    
                                                $target_features = array(
                          				  'campaignid'=>$last_camp_id,
                    				          'devices'=>'',
                            				  'manufacturer'=>'',
                            				  'capability'=>'',
                         			  	 'locations'=>'',
                          			 	 'gender'=>'all',
                           				 'device_type'=>'all',
                            				'manufacturer_type'=>'all',
                            				'capability_type'=>'all',
                            				'location_type'=>'geographic_all',
                            				'gender_type'=>'all',
                            				'ages_type'=>'all',
                            				'model'=>'',
                            				'model_type'=>0,
                            				'carrier_type'=>'all',
                            				'carriers' =>'',
                           				'network_country'=>'',
                          				 'enable_loc'=>'spec',
                            				'landing_page'=>$attributes['click_to_action'],
                            				'intermediate'=>0

                        			);
                                                 $add_target 	= $this->mod_campaigns->insert_targeting_limitation($target_features);

                                             if($add_target=='TRUE')
                                             {
                                                 $this->mod_campaigns->add_campaign_limitation($last_camp_id,$attributes['click_to_action']);
                                                 $data = array('clientid'=>$advertiser,'order_id'=>$order_id);
                                                 $response = array(
								STATUS=>SUCCESS_STATUS,
								'order_id'=>$order_id,
                                                                'amount'=>$attributes['accbal']
						);
						echo json_encode($response);
						return;
                                               //  $this->handleFetchCampaignList($data);
                                             }
                                             else{
                                                   $response = array(
                                                                   STATUS=>ERR_STATUS,
                                                                   ERR_CODE_KEY =>DB_ERR,
                                                                   ERR_STR =>DB_ERR_STR
                                                              );
                                                 }

					}
					else
					{
						$response = array(
								STATUS=>ERR_STATUS,
								ERR_CODE_KEY =>DB_ERR,
								ERR_STR =>DB_ERR_STR
						);
						echo json_encode($response);
						return;
					}
				}
				else
				{
					$response = array(
							STATUS=>ERR_STATUS,
							ERR_CODE_KEY =>DB_ERR,
							ERR_STR =>DB_ERR_STR
					);
					echo json_encode($response);
					return;
				}

			}
			else
			{
				$this->logger->logerror('Campaignapi::handleAddCampaignRequest()  Duplicate campaign');
				$response = array(
						STATUS=>ERR_STATUS,
						ERR_CODE_KEY =>DUPLICATE_CMP,
						ERR_STR =>DUPLICATE_CMP_STR
				);

				echo json_encode($response);
				return;
			}

		}
		else
		{
			$this->logger->logerror('Campaignapi::handleAddCampaignRequest()  required parameters are missing');
			$response = array(
					STATUS=>ERR_STATUS,
					ERR_CODE_KEY =>PARAM_REQ,
					ERR_STR =>PARAM_REQ_STR
			);
			echo json_encode($response);
			return;
		}


	}


	/* Add New Banner Process */
	public function add_banner_process($campaignid=0)
	{
		$banner_type = $this->input->post('banner_type');
		print_r($_POST);return;
		switch($banner_type)
		{
			case 0:
				{

					$advertiser 	= $this->input->post('advertiser');
					$campaign	= $campaignid;
					$banner_name 	= $this->input->post('img_banner_name');
					$banner_url 	= $this->input->post('img_banner_url');
					$banner_content = $this->input->post('img_banner_txt');

					/* Hard Coded Values */
					$storage_type	= "web";
					$master_banner	= -2;
					$status         = 1;
					$adminstatus	= 1;

					/* Get Image Banner  Sizes*/
					$img_banner_sizes	= $this->mod_banner->getBannerSizes();
					foreach($img_banner_sizes as $bs)
					{
						$size[$bs->screen]['width'] 	= $bs->width;
						$size[$bs->screen]['height']	= $bs->height;
					}

					/* Check Duplication for Banner Name based on Campaign ID */
					$where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
					$where_not_in	= 0;
					$banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
					if($banner_check==FALSE)
					{
						/* Master/Child 0(Large) Image Upload */
						$config['width'] 		    = $size['master']['width'];
						$config['height'] 		    = $size['master']['height'];

							
							
						$banner_image="";// name of banner
						$large_img_type		=""; //png
						$large_img_name 	= $banner_image;


						/* Child 1(Medium) Image Upload */

						$config['width'] 		    = $size['child1']['width'];
						$config['height'] 		    = $size['child1']['height'];
						$banner_image  ="";

						$medium_img_type	= "";
						$medium_img_name 	= $banner_image;


						/* Child 2(Small) Image Upload */
						$banner_image               = "";//
						$config['width'] 		    = $size['child2']['width'];
						$config['height'] 		    = $size['child2']['height'];

						$small_img_type		= "";//
						$small_img_name 	= $banner_image;


						/* Child 3(XSmall) Image Upload */
						$banner_image               ="";//
						$config['width'] 		    = $size['child3']['width'];
						$config['height'] 		    = $size['child3']['height'];

						$xsmall_img_type	= "";//
						$xsmall_img_name 	= $banner_image;


						/* Add Master Image Banner - Start */
						$add_master_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'		=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($large_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		=>mysql_real_escape_string($size['master']['width']),
								'height'	=>mysql_real_escape_string($size['master']['height']),
								'status'        => mysql_real_escape_string($status),
								'master_banner'	=>mysql_real_escape_string($master_banner),
								'adminstatus'   => mysql_real_escape_string($adminstatus),
								'updated'	=>date('Y-m-d H:i:s')
						);
						/* Banner Insert Method and Get Last Insert ID */
						$parent_id = $this->mod_banner->add_banner($add_master_banner);

						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}

							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$parent_id,"link_type"=>"1");
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
						/* Add Master Image Banner - End */

						/* Add Large Image Banner - Start */
						$add_large_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($large_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		=>mysql_real_escape_string($size['master']['width']),
								'height'	=>mysql_real_escape_string($size['master']['height']),
								'status'        => mysql_real_escape_string($status),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'   => mysql_real_escape_string($adminstatus),
								'updated'	=>date('Y-m-d H:i:s')

						);
						/* Banner Insert Method and Get Last Insert ID */
						$large_img_id = $this->mod_banner->add_banner($add_large_banner);

						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}

							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$large_img_id,"link_type"=>"1");
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
						/* Add Large Iamge Banner - End */

						/* Add Medium Iamge Banner - Start */
						$add_medium_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'		=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($medium_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($medium_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		=>mysql_real_escape_string($size['child1']['width']),
								'height'	=>mysql_real_escape_string($size['child1']['height']),
								'status'        => mysql_real_escape_string($status),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'        => mysql_real_escape_string($adminstatus),
								'updated'	=>date('Y-m-d H:i:s')

						);
						/* Banner Insert Method and Get Last Insert ID */
						$medium_img_id = $this->mod_banner->add_banner($add_medium_banner);

						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}

							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$medium_img_id,"link_type"=>"1");
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
						/* Add Medium Iamge Banner - End */

						/* Add Small Iamge Banner - Start */
						$add_small_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($small_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($small_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child2']['width']),
								'height'	  	=>mysql_real_escape_string($size['child2']['height']),
								'status'        => mysql_real_escape_string($status),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'        => mysql_real_escape_string($adminstatus),
								'updated'	=>date('Y-m-d H:i:s')

						);
						/* Banner Insert Method and Get Last Insert ID */
						$small_img_id = $this->mod_banner->add_banner($add_small_banner);

						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}

							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$small_img_id,"link_type"=>"1");
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
						/* Add Small Iamge Banner - End */

						/* Add XSmall Iamge Banner - Start */
						$add_xsmall_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($xsmall_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($xsmall_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child3']['width']),
								'height'	  	=>mysql_real_escape_string($size['child3']['height']),
								'status'        => mysql_real_escape_string($status),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'        => mysql_real_escape_string($adminstatus),
								'updated'	=>date('Y-m-d H:i:s')

						);
						/* Banner Insert Method and Get Last Insert ID */
						$xsmall_img_id = $this->mod_banner->add_banner($add_xsmall_banner);

						/* Add XSmall Iamge Banner - End */


						/* Tablet Banner added Successfully. Redirect to Banner List */
							
					}
					else
					{
						/* Banner Duplication Error */
					}

					break;
				}
					
		}

	}

	/**
	 * This method is used to create Image Banner using text
	 */
	public function  createBannerImage($width=0,$height=0,$text=null)
	{
	
		// Create the image
		$im = imagecreatetruecolor(320,50);
	
		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 320, 50, $white);

		// The text to draw
		$text = 'Get 50% discount @titan';
		// Replace path by your own font path
		$font = $this->config->item('fonts_url').'droidsans-webfont.ttf';

		// Add some shadow to the text
		imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

		// Add the text
		imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

		// Using imagepng() results in clearer text compared with imagejpeg()
		$imgname = md5(rand(999,9999));
		$path = $this->config->item('ads_url').$imgname.'.png';
		//echo "path is ".$path;return;
		$res = imagepng($im,$path);
		if($res)
			echo "image saved";
		else
			echo "imgae error";
		imagedestroy($im);

	}



}
?>
