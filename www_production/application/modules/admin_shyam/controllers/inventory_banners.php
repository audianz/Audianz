<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_Banners extends CI_Controller {
	/* Page Limit */	
	var $page_limit = 10;
	
	public function __construct()
	{
		parent::__construct();
				
		$this->load->model('mod_campaign');
		$this->load->model('mod_banner'); 
		$this->load->model('mod_statistics');
		$this->load->library('image_lib');               
		//$this->load->library('ftp');
	}
	
	/* Banners Page */
	public function index()
	{
		$this->listing();		
	}

	/* Banners Listing Page */	
	public function listing($stat='',$start=0)
	{
		
		

		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/		
		$link = breadcrumb();		
		$data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
		 	Banner Based on Campaign Id
		 -------------------------------------------------------------*/
                $data['sel_camp'] = 0;
                $data['sel_stat'] = $stat;
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
		 
		$limit = $this->page_limit;	
                if($stat=='active')
                {
                        $status = 0;
                        $where_arr = array('ox_banners.status'=>$status);               
}
                elseif($stat=='inactive')
                {
                        $status = 1;
                        $where_arr = array('ox_banners.status'=>$status);
			
                }
                else
                {
                        $stat = 'all';
                        $where_arr = array();
                }
                
               
		$list_data = $this->mod_banner->get_banners($where_arr);
		
		//echo $this->db->last_query();exit;
		
		/*--------------------------------------------------------------------------
          	* Get Reports for each banners based on selected Campaigns and Advertiser
          	* -------------------------------------------------------------------------*/


		$search_arr                         =   array();
		$search_arr['from_date']            =	$this->mod_statistics->get_start_date();
		$search_arr['to_date']              =	date("Y/m/d");
		$search_arr['search_type']          =	"all";
		
		$data['stat_data'] = $this->mod_statistics->get_statistics_for_banners($search_arr);
		
		/* Total Banners Count */	
		/*$data['tot_list'] = $list_data;
				
		$config['per_page'] 	= $limit;
		$config['base_url'] 	= site_url("admin/inventory_banners/listing/".$stat);
                $config['uri_segment'] 	= 5;                
		$config['total_rows'] 	= count($list_data);
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");		
		$config['last_link'] 	= $this->lang->line("pagination_last_link");		
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);*/		
		$page_data = $this->mod_banner->get_banners($where_arr);
		$data['banner_list']	=	$page_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_banner_page_title');		
		
		/*-------------------------------------------------------------
		 	Total Counts for Active and Inactive Banners
		--------------------------------------------------------------*/   
		            
                $where_tot = array();
                $where_act = array('ox_banners.status'=>0);
		$where_inact = array('ox_banners.status'=>1);
		
		
		$tot_data = $this->mod_banner->get_banners($where_tot);
		if($tot_data!=FALSE)
		{
			$data['tot_data'] = count($tot_data);
		}
		else
		{
			$data['tot_data'] = 0;
		}
		
		$active_data = $this->mod_banner->get_banners($where_act);
		if($active_data!=FALSE)
		{
			$data['active_data'] = count($active_data);
		}
		else
		{
			$data['active_data'] = 0;
		}
		
		$inactive_data = $this->mod_banner->get_banners($where_inact);

		if($inactive_data!=FALSE)
		{
			$data['inactive_data'] = count($inactive_data);
		}
		else
		{
			$data['inactive_data'] = 0;
		}
		

		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("admin/banners/list",$data,true);
		$this->load->view('page_layout',$data);
	
	}

        /* Banners Listing By Campaign ID */
	public function listing_camp($stat='',$campid='',$adv='',$start=0)
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
		 	Banner Based on Campaign Id
		 -------------------------------------------------------------*/
                $data['sel_camp'] = $campid;
				$data['sel_adv'] = $adv;
                $data['sel_stat'] = $stat;
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
		$limit = $this->page_limit;
		if($campid!='')
                {
                    if($stat=='active')
                    {
                            $status = 0;
                            $where_arr = array('ox_banners.campaignid'=>$campid,'ox_banners.status'=>$status);
                    }
                    elseif($stat=='inactive')
                    {
                            $status = 1;
                            $where_arr = array('ox_banners.campaignid'=>$campid,'ox_banners.status'=>$status);
                    }
                    else
                    {
                            $stat = 'all';
                            $where_arr = array('ox_banners.campaignid'=>$campid);
                    }
                }
                else
                {
                    if($stat=='active')
                    {
                            $status = 0;
                            $where_arr = array('ox_banners.status'=>$status);
                    }
                    elseif($stat=='inactive')
                    {
                            $status = 1;
                            $where_arr = array('ox_banners.status'=>$status);
                    }
                    else
                    {
                            $stat = 'all';
                            $where_arr = array();
                    }
                }
                //print_r($where_arr);exit;
		$list_data = $this->mod_banner->get_banners($where_arr);
		
		/*--------------------------------------------------------------------------
          	* Get Reports for each banners based on selected Campaigns and Advertiser
          	* -------------------------------------------------------------------------*/


		$search_arr                         =   array();
		$search_arr['from_date']            =	$this->mod_statistics->get_start_date();
		$search_arr['to_date']              =	date("Y/m/d");
		$search_arr['search_type']          =	"all";
		
		$data['stat_data'] = $this->mod_statistics->get_statistics_for_banners($search_arr);
		
		/* Total Banners Count */
		$data['tot_list'] = $list_data;

		$config['per_page'] 	= $limit;
		if($campid!='')
                {
                    $config['base_url'] 	= site_url("admin/inventory_banners/listing_camp/".$stat."/".$campid);
                    $config['uri_segment'] 	= 6;
                }
                else
                {
                    $config['base_url'] 	= site_url("admin/inventory_banners/listing_camp/".$stat);
                    $config['uri_segment'] 	= 5;
                }
		$config['total_rows'] 	= count($list_data);
		$config['next_link'] 	= $this->lang->line("pagination_next_link");
		$config['prev_link'] 	= $this->lang->line("pagination_prev_link");
		$config['last_link'] 	= $this->lang->line("pagination_last_link");
		$config['first_link'] 	= $this->lang->line("pagination_first_link");
		$this->pagination->initialize($config);
		$page_data = $this->mod_banner->get_banners($where_arr,$start,$limit);
		$data['banner_list']	=	$page_data;

		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_banner_page_title');

		/*-------------------------------------------------------------
		 	Total Counts for Active and Inactive Banners
		--------------------------------------------------------------*/
                if($campid!='')
                {
                    $where_tot = array('ox_banners.campaignid'=>$campid);
                    $where_act = array('ox_banners.campaignid'=>$campid,'ox_banners.status'=>0);
                    $where_inact = array('ox_banners.campaignid'=>$campid,'ox_banners.status'=>1);
                }
                else
                {
                    $where_tot = array();
                    $where_act = array('ox_banners.status'=>0);
                    $where_inact = array('ox_banners.status'=>1);
                }

		$tot_data = $this->mod_banner->get_banners($where_tot);
		if($tot_data!=FALSE)
		{
			$data['tot_data'] = count($tot_data);
		}
		else
		{
			$data['tot_data'] = 0;
		}
		
		$active_data = $this->mod_banner->get_banners($where_act);
		if($active_data!=FALSE)
		{
			$data['active_data'] = count($active_data);
		}
		else
		{
			$data['active_data'] = 0;
		}
		
		$inactive_data = $this->mod_banner->get_banners($where_inact);

		if($inactive_data!=FALSE)
		{
			$data['inactive_data'] = count($inactive_data);
		}
		else
		{
			$data['inactive_data'] = 0;
		}

		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content']	= $this->load->view("admin/banners/list",$data,true);
		$this->load->view('page_layout',$data);

	}

	/* Pause/De-Activate Banner */
	public function pause_banner($bid=0)
	{
		   /* Pause Banner using Checkboxes */
			if($bid==false)
			{
				$banner = $_POST['bannerarr'];
				if($banner[0]=='checkall')
				{
					/* Remove Check All option */
					$banrem = array_shift($banner);
				}
				$count = count($banner);
				for($m=0;$m<$count;$m++)
				{
					/* Pause Banner */
					$this->mod_banner->pause_banner($banner[$m]);
					/* Banner Paused Successfully. Redirect to Banner List */
				}
				$this->session->set_flashdata('pause_banner', $this->lang->line('label_banner_pause_success'));
			}
			else
			{
					/* Banner Pause Failed. Redirect to Banner List */
					$this->session->set_flashdata('banner_error', $this->lang->line('label_error_missing'));
					redirect('admin/inventory_banners');
			}
	}

	/* Run/Activate Banner */
	public function run_banner($bid=0)
	{
			$run_array=array();
		/* Activate Banner using Checkboxes */
			if($bid==false)
			{
				$banner = $_POST['bannerarr'];
				if($banner[0]=='checkall')
				{
					/* Remove Check All option */
					$banrem = array_shift($banner);
				}
				$count = count($banner);
				for($m=0;$m<$count;$m++)
				{
					$banner_details=$this->mod_banner->get_banner_details($banner[$m]);
					if($banner_details[0]->adminstatus == 0)					
					{
						/* Activate Banner */
						$this->mod_banner->run_banner($banner[$m]);
						$this->session->set_flashdata('run_banner', $this->lang->line('label_banner_run_success'));
					}
					else
					{
						$run_array=array_push($run_array,$banner[$m]);
					}
					/* Banner Activated Successfully. Redirect to Banner List */
				}
				
					
								
				if(count($run_array)>0)
				{
					$this->session->set_userdata('run_banner_err_data', $run_array);
					$this->session->set_flashdata('run_banner_err', 'Failed');
				}			
			}
			else
			{
					/* Banner Activate Failed. Redirect to Banner List */
					$this->session->set_flashdata('banner_error', $this->lang->line('label_error_missing'));
					redirect('admin/inventory_banners');
			}
	}

	/* Delete Banner */
	public function delete_banner($bid=0,$sel_camp=0,$sel_adv=0)
	{
		/* Delete Banner using Checkboxes */
		if($bid==false)
		{
			$banner = $_POST['bannerarr'];
                        if($banner[0]=='checkall')
			{
				$banrem = array_shift($banner);                    
			}
			$count = count($banner);
			for($m=0;$m<$count;$m++)
			{
                                /* Check Banner is Master or Child */
                                $master = $this->mod_banner->check_master_banner($banner[$m]);
                                if($master==1)
                                {
                                    /* Retrieve Child Banners */
                                    $child = $this->mod_banner->get_child_banners($banner[$m]);
                                    if(count($child)>0)
                                    {
                                        //print_r($child);exit;
                                        /* Delete Child Banner one by one */
                                        for($ch=0;$ch<count($child);$ch++)
                                        {
                                            $wherechild = array("creative_id"=>$child[$ch]->bannerid);
                                            $this->mod_campaign->deleteBKTA($wherechild);
                                            $this->mod_campaign->deleteBKTC($wherechild);
                                            $this->mod_campaign->deleteBKTM($wherechild);
                                            $this->mod_campaign->deleteBKTUC($wherechild);
                                            $this->mod_campaign->deleteBKTUM($wherechild);

                                            $wherecad = array("ad_id"=>$child[$ch]->bannerid);
                                            $this->mod_campaign->deleteBKTH($wherecad);
                                            $this->mod_campaign->deleteBKTAD($wherecad);
                                            $this->mod_campaign->deleteZoneAssoc($wherecad);
                                            /* Delete Campaign Reports */
                                            $where_arr = array('bannerid'=>$child[$ch]->bannerid);
                                            $this->mod_banner->delete_banner_reports($where_arr);
                                        }
                                    }
                                }
                                /* Delete Parent Banners */
				$wherecre = array("creative_id"=>$banner[$m]);
                                $this->mod_campaign->deleteBKTA($wherecre);
                                $this->mod_campaign->deleteBKTC($wherecre);
                                $this->mod_campaign->deleteBKTM($wherecre);
                                $this->mod_campaign->deleteBKTUC($wherecre);
                                $this->mod_campaign->deleteBKTUM($wherecre);

                                $wheread = array("ad_id"=>$banner[$m]);
                                $this->mod_campaign->deleteBKTH($wheread);
                                $this->mod_campaign->deleteBKTAD($wheread);
                                $this->mod_campaign->deleteZoneAssoc($wheread);
                                
                                $where_arr = array('bannerid'=>$banner[$m]);
                                $where_master = array('master_banner'=>$banner[$m]);
                                /* Delete Campaign Reports */
                                $this->mod_banner->delete_banner_reports($where_arr);
				/* Delete Banner */
				$this->mod_banner->delete_banner($where_arr,$where_master);
				/* Banner Deleted Successfully. Redirect to Banner List */                    
			}
			$this->session->set_flashdata('banner_delete_success', $this->lang->line('label_banner_delete_success'));
			//redirect('admin/inventory_banners');
		}
		/* Delete Banner using Action Link */
		else if($bid != false)
		{
                        /* Check Banner is Master or Child */
                        $master = $this->mod_banner->check_master_banner($bid);
                        if($master==1)
                        {
                            /* Retrieve Child Banners */
                            $child = $this->mod_banner->get_child_banners($bid);
                            if(count($child)>0)
                            {
                                //print_r($child);exit;
                                /* Delete Child Banner one by one */
                                for($ch=0;$ch<count($child);$ch++)
                                {
                                    $wherechild = array("creative_id"=>$child[$ch]->bannerid);
                                    $this->mod_campaign->deleteBKTA($wherechild);
                                    $this->mod_campaign->deleteBKTC($wherechild);
                                    $this->mod_campaign->deleteBKTM($wherechild);
                                    $this->mod_campaign->deleteBKTUC($wherechild);
                                    $this->mod_campaign->deleteBKTUM($wherechild);

                                    $wherecad = array("ad_id"=>$child[$ch]->bannerid);
                                    $this->mod_campaign->deleteBKTH($wherecad);
                                    $this->mod_campaign->deleteBKTAD($wherecad);
                                    $this->mod_campaign->deleteZoneAssoc($wherecad);
                                    /* Delete Campaign Reports */
                                    $where_arr = array('bannerid'=>$child[$ch]->bannerid);
                                    $this->mod_banner->delete_banner_reports($where_arr);
                                }
                            }
                        }

			$wherecre = array("creative_id"=>$bid);
                        $this->mod_campaign->deleteBKTA($wherecre);
                        $this->mod_campaign->deleteBKTC($wherecre);
                        $this->mod_campaign->deleteBKTM($wherecre);
                        $this->mod_campaign->deleteBKTUC($wherecre);
                        $this->mod_campaign->deleteBKTUM($wherecre);

                        $wheread = array("ad_id"=>$bid);
                        $this->mod_campaign->deleteBKTH($wheread);
                        $this->mod_campaign->deleteBKTAD($wheread);
                        $this->mod_campaign->deleteZoneAssoc($wheread);

                        $where_arr = array('bannerid'=>$bid);
                        $where_master = array('master_banner'=>$bid);
                        /* Delete Campaign Reports */
                        $this->mod_banner->delete_banner_reports($where_arr);
			/* Delete Banner */
			$this->mod_banner->delete_banner($where_arr,$where_master);
			/* Banner Deleted Successfully. Redirect to Banner List */
			$this->session->set_flashdata('banner_delete_success', $this->lang->line('label_banner_delete_success'));
			if($sel_camp == '0' || $sel_adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$sel_camp."/".$sel_adv);
								}
			
		}
		else
		{
				/* Banner Deleted Failed. Redirect to Banner List */
				$this->session->set_flashdata('banner_error', $this->lang->line('label_error_missing'));
				if($sel_camp == '0' || $sel_adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$sel_camp."/".$sel_adv);
								}
				
		}
	}
	
	/* Add New Banner */
	public function add_banner($campid=0,$adv=0)
	{
		/*-------------------------------------------------------------
		Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		 $data['sel_camp'] = $campid;
		 $data['sel_adv'] = $adv;
		 
		 $data['sel_camp_type'] = $this->input->post('sel_camp_type');
		 
		/*-------------------------------------------------------------
		Get Advertiser List
		-------------------------------------------------------------*/
		$adv_list = $this->mod_campaign->get_advertiser_list();
		$data['advertiser'] = $adv_list;
		$data['mob_screens']	= $this->mod_banner->banner_screen();
		/*-------------------------------------------------------------*/
		
		if($adv!=0)
		{
			$where_adv = array('clientid'=>$adv);
			$camp_list = $this->mod_banner->filter_campaigns($where_adv);
			$data['campaigns']  = $camp_list;
	
		}
		

	/*-------------------------------------------------------------
				Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content'] = $this->load->view("admin/banners/add",$data,true);
		
		echo $this->load->view('page_layout',$data,true);
		
		exit;
	}
	
	public function test()
	{
		$this->add_banner();	
	}
	
	
	/* Filter Campaigns List Based on Advertiser Select  */
	public function filter_campaigns($advid=0,$sel_camp_id=FALSE)
	{
		if($advid!=0)
		{
			$where_adv = array('clientid'=>$advid);
			$camp_list = $this->mod_banner->filter_campaigns($where_adv);
			$data['campaigns']  = $camp_list;
			$data['sel_camp_id'] = $sel_camp_id;
			echo $this->load->view('admin/banners/filter_campaigns',$data);
		}
		else
		{
			$data['campaigns']  = 0;
			echo $this->load->view('admin/banners/filter_campaigns',$data);
		}	
	}
	
	/* Add New Banner Process */
	public function add_banner_process($campid=0,$adv=0)
	{	
		
		
		$banner_type = $this->input->post('banner_type');
		
		switch($banner_type)
		{
			case 0:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaign_type', 'Campaign', 'required');
				$this->form_validation->set_rules('img_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('img_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('banner_type', 'Banner Type', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner($campid,$adv);
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign		= $this->input->post('campaign_type');
					$banner_name 	= $this->input->post('img_banner_name');
					$banner_url 	= $this->input->post('img_banner_url');
					$banner_content = $this->input->post('img_banner_txt');
					
					/* Hard Coded Values */
					$storage_type	= "web";
					$master_banner	= -2;
					$admin_status	= 0;

					$where_camp	= array('campaignid'=>$campaign);
					$camp_status	= $this->mod_banner->camp_status($where_camp);
					if($camp_status==0)
					{
						$banstatus	=	0;
					}
					else
					{
						$banstatus	=	1;
					}

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
							$banner_image               = rand(999, 9999)."-".$_FILES['large_banner']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['large_banner']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $size['master']['width'];
							$config['height'] 		    = $size['master']['height'];
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								//$this->image_lib->display_errors()
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $campid == '')
								{
									$this->add_banner();
									
								}
								else
								{
									if($adv == '0' || $adv == '')
									{
										$this->add_banner();
										//redirect('admin/inventory_banners');
									}else{
										$this->add_banner($campid,$adv);
										//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
									}
								}
							}
							else
							{
								$this->image_lib->clear();	
								$large_img_type		= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$large_img_name 	= $banner_image;						
							}
							
							/* Child 1(Medium) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['medium_banner']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['medium_banner']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $size['child1']['width'];
							$config['height'] 		    = $size['child1']['height'];
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $campid == '')
								{
									$this->add_banner();
									
								}
								else
								{
									if($adv == '0' || $adv == '')
									{
										$this->add_banner();
										//redirect('admin/inventory_banners');
									}else{
										$this->add_banner($campid,$adv);
										//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
									}
								}
							}
							else
							{
								$this->image_lib->clear();
								$medium_img_type	= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$medium_img_name 	= $banner_image;							
							}
						
							/* Child 2(Small) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['small_banner']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['small_banner']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $size['child2']['width'];
							$config['height'] 		    = $size['child2']['height'];
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $campid == '')
								{
									$this->add_banner();
									
								}
								else
								{
									if($adv == '0' || $adv == '')
									{
										$this->add_banner();
										//redirect('admin/inventory_banners');
									}else{
										$this->add_banner($campid,$adv);
										//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
									}
								}
							}
							else
							{
								$this->image_lib->clear();
								$small_img_type		= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$small_img_name 	= $banner_image;							
							}
						
							/* Child 3(XSmall) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['x_small_banner']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['x_small_banner']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $size['child3']['width'];
							$config['height'] 		    = $size['child3']['height'];
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $campid == '')
								{
									$this->add_banner();
									
								}
								else
								{
									if($adv == '0' || $adv == '')
									{
										$this->add_banner();
										//redirect('admin/inventory_banners');
									}else{
										$this->add_banner($campid,$adv);
										//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
									}
								}
							}
							else
							{
								$this->image_lib->clear();
								$xsmall_img_type	= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$xsmall_img_name 	= $banner_image;						
							}
						
							/* Add Master Iamge Banner - Start */
							$add_master_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($large_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['master']['width']),
								'height'	  	=>mysql_real_escape_string($size['master']['height']),
								'master_banner'	=>mysql_real_escape_string($master_banner),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
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
								$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
							}						
							/* Add Master Iamge Banner - End */
							
							/* Add Large Iamge Banner - Start */
							$add_large_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($large_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['master']['width']),
								'height'	  	=>mysql_real_escape_string($size['master']['height']),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
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
								$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
							}						
							/* Add Large Iamge Banner - End */
							
							/* Add Medium Iamge Banner - Start */
							$add_medium_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($medium_img_name),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($medium_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child1']['width']),
								'height'	  	=>mysql_real_escape_string($size['child1']['height']),
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
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
								$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
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
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
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
								$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
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
								'master_banner'	=>mysql_real_escape_string($parent_id),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
								'updated'	=>date('Y-m-d H:i:s')
							);
							/* Banner Insert Method and Get Last Insert ID */
							$xsmall_img_id = $this->mod_banner->add_banner($add_xsmall_banner);
							
							$where_place = array("placement_id"=>$campaign);
							$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
							if($query->num_rows()>0)
							{
								foreach($query->result() as $row)
								{
									$zoneid = $row->zone_id;
								}
								
								$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$xsmall_img_id,"link_type"=>"1");							
								$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
							}						
							/* Add XSmall Iamge Banner - End */
							
												
							/* Tablet Banner added Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
							if($campid == '0' || $campid == '')
								{
									if($adv == '0' || $adv == '')
									{
										redirect('admin/inventory_banners');
									}
									else
									{
										redirect('admin/inventory_banners');
									}
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							
				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->add_banner($campid,$adv);
				   }
				}
				break;
			}
			case 1:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaign_type', 'Campaign', 'required');
				$this->form_validation->set_rules('txt_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('txt_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('txt_banner_content', 'Banner Content', 'required');
				$this->form_validation->set_rules('banner_type', 'Banner Type', 'required');
				 if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner($campid,$adv);
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign		= $this->input->post('campaign_type');
					$banner_name 	= $this->input->post('txt_banner_name');
					$banner_url 	= $this->input->post('txt_banner_url');
					$banner_content = $this->input->post('txt_banner_content');
					
					/* Hard Coded Values */
					$storage_type	= "txt";
					$master_banner	= -1;
					$admin_status	= 0;

					$where_camp	= array('campaignid'=>$campaign);
					$camp_status	= $this->mod_banner->camp_status($where_camp);
					if($camp_status==0)
					{
						$banstatus	=	0;
					}
					else
					{
						$banstatus	=	1;
					}
					
					/* Check Duplication for Banner Name based on Campaign ID */
					    $where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
					    $where_not_in	= 0;
					    $banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
					    if($banner_check==FALSE)
					    {
						/* Add Banner Parameters */
						$add_banner = array(
							'campaignid'=>mysql_real_escape_string($campaign),
							'description'=>mysql_real_escape_string($banner_name),
							'url'=>mysql_real_escape_string($banner_url),
							'bannertext'=>mysql_real_escape_string($banner_content),
							'contenttype' =>mysql_real_escape_string($storage_type),
							'storagetype'=>mysql_real_escape_string($storage_type),
							'master_banner'=>mysql_real_escape_string($master_banner),
							'adminstatus'=>mysql_real_escape_string($admin_status),
							'status'		=>mysql_real_escape_string($banstatus),
							'updated'	=>date('Y-m-d H:i:s')
						);
						/* Banner Insert Method and Get Last Insert ID */
                        $parent_id = $this->mod_banner->add_banner($add_banner);
						
						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}
							
							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$parent_id,"link_type"=>"1");							
							$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
											
						/* Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
						if($campid == '0' || $campid == '')
						{
							if($adv == '0' || $adv == '')
							{
								redirect('admin/inventory_banners');
							}
							else
							{
								redirect('admin/inventory_banners');
							}
						}
						else
						{
							redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
						}
						
				   }
				   else
				   {
					   /* Text Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->add_banner($campid,$adv);
				   }
				}
				break;
			}
			case 2:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaign_type', 'Campaign', 'required');
				$this->form_validation->set_rules('tablet_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('tab_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('banner_type', 'Banner Type', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner($campid,$adv);
				}
				else
				{
					
					/* Check File Permisson of Uploading Directory */
					
						$advertiser 	= $this->input->post('advertiser');
						$campaign		= $this->input->post('campaign_type');
						$banner_name 	= $this->input->post('tablet_banner_name');
						$banner_url 	= $this->input->post('tab_banner_url');
						$banner_size	= $this->input->post('banner_size');
						
						
						
						/* Split Banner Width and Height */
						list($width, $height)  =explode(" X ", $this->input->post('banner_size'));					
						
						/* Hard Coded Values */
						$storage_type	= "web";
						$master_banner	= -3;
						$admin_status	= 0;
						

						$where_camp	= array('campaignid'=>$campaign);
						$camp_status	= $this->mod_banner->camp_status($where_camp);
						if($camp_status==0)
						{
							$banstatus	=	0;
						}
						else
						{
							$banstatus	=	1;
						}

						/* Check Duplication for Banner Name based on Campaign ID */
						$where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
						$where_not_in	= 0;
						$banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
						if($banner_check==FALSE)
						{
							$banner_image               = rand(999, 9999)."-".$_FILES['tab_banner_image']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['tab_banner_image']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $width;
							$config['height'] 		    = $height;
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $campid == '')
								{
									
									$this->add_banner();
									
									
								}
								else
								{
									if($adv == '0' || $adv == '')
									{
										$this->add_banner($campid);
										//redirect('admin/inventory_banners/add_banner'.$campid);
									}else{
										$this->add_banner($campid,$adv);
										//redirect('admin/inventory_banners/add_banner/'.$campid."/".$adv);
									}
								}
							}
							else
							{
								$this->image_lib->clear();
								$img_type		= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$tab_img_name 	= $banner_image;
							}	
						
							/* Add Banner Parameters */
							$add_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($tab_img_name),
								'contenttype' 	=>mysql_real_escape_string($img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($width),
								'height'	  	=>mysql_real_escape_string($height),
								'master_banner'	=>mysql_real_escape_string($master_banner),
								'adminstatus'	=>mysql_real_escape_string($admin_status),
								'status'		=>mysql_real_escape_string($banstatus),
								'updated'	=>date('Y-m-d H:i:s')
							);
							/* Banner Insert Method and Get Last Insert ID */
							$parent_id = $this->mod_banner->add_banner($add_banner);
							
							$where_place = array("placement_id"=>$campaign);
							$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
							if($query->num_rows()>0)
							{
								foreach($query->result() as $row)
								{
									$zoneid = $row->zone_id;
								}
								
								$qry = mysql_query ("select b.width,b.height,c.revenue_type,z.height,z.zoneid,z.width,z.revenue_type from ox_campaigns c join ox_banners b join ox_zones z on z.width=b.width and z.height=b.height and c.revenue_type=z.revenue_type where z.zoneid='".$zoneid."' and b.bannerid='".$parent_id."'");

								if(mysql_num_rows($qry)>0)
								{	
									$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$parent_id,"link_type"=>"1");							
									$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
								}
							}
												
							/* Tablet Banner added Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
							if($campid == '0' || $campid == '')
							{
								if($adv == '0' || $adv == '')
								{
									redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners');
								}
							}
							else
							{
								redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
							}
							
					   }
					   else
					   {
						   /* Banner Duplication Error */
						   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
						   $this->add_banner($campid,$adv);
					   }
										
				}
				break;
			}
		}

	}

	/* Edit Banner */
	public function edit_banner($banner_id=0,$campid=0,$adv=0)
	{

		$data['banner_id'] = $banner_id;
		$data['sel_camp'] = $campid;
		$data['sel_adv'] = $adv;
		/*-------------------------------------------------------------
			Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		/*-------------------------------------------------------------
			Get Advertiser List
		-------------------------------------------------------------*/
		$adv_list = $this->mod_campaign->get_advertiser_list();
		$data['advertiser'] = $adv_list;
		$data['mob_screens']	= $this->mod_banner->banner_screen();
		/*-------------------------------------------------------------
			Get Banner Details
		-------------------------------------------------------------*/
		$where_ban	 	= array('bannerid'=>$banner_id); 
		$banner_info	= $this->mod_banner->edit_banner($where_ban);
		$data['bannercontent'] = $banner_info;
		/*-------------------------------------------------------------
			Get Banner Image Names Details
		-------------------------------------------------------------*/
		$where_master	= array('master_banner'=>$banner_id);
		$bannerimage 	= $this->mod_banner->get_banner_filename($where_ban,$where_master);
		$data['bannerimage'] = $bannerimage;
		/*-------------------------------------------------------------
				Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content'] = $this->load->view("admin/banners/edit",$data,true);
		echo $this->load->view('page_layout',$data,true);
		exit;
	}

	/* Filter Campaigns List Based on Advertiser Select  */
	public function filter_campaigns_edit($advid=0,$campid=0)
	{
		if($advid!=0 && $campid!=0)
		{
			$where_arr = array('clientid'=>$advid);
			$camp_list = $this->mod_banner->filter_campaigns($where_arr);
			$data['campaigns']  = $camp_list;
			$data['bannercamp']  = $campid;
			echo $this->load->view('admin/banners/filter_campaigns_edit',$data);
		}
		else
		{
			$data['campaigns']  = 0;
			$data['bannercamp']  = 0;
			echo $this->load->view('admin/banners/filter_campaigns_edit',$data);
		}
	}

	/* Modify Banner information */
	public function edit_banner_process($bid=0,$campid=0,$adv=0)
	{
		$banner_type = $this->input->post('bannertypeID');
                $large_bid = '';
                $medium_bid = '';
                $small_bid = '';
                $xsmall_bid = '';
		switch($banner_type)
		{
			case 0:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaignID', 'Campaign', 'required');
				$this->form_validation->set_rules('img_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('img_banner_url', 'Banner URL', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
                                    /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
				}
				else
				{
                                        $advertiser 	= $this->input->post('advertiser');
					$campaign	= $this->input->post('campaignID');
					$banner_name 	= $this->input->post('img_banner_name');
					$banner_url 	= $this->input->post('img_banner_url');
					$banner_content	= $this->input->post('img_banner_txt');
					
					/* Hard Coded Values */
					$storage_type	= "web";
					$admin_status	= 0;
					
					/* Get Image Banner  Sizes*/
					$img_banner_sizes	= $this->mod_banner->getBannerSizes();
				   	foreach($img_banner_sizes as $bs) 
				   	{
   						$size[$bs->screen]['width'] 	= $bs->width;
						$size[$bs->screen]['height']	= $bs->height;
   					}
					
					/* Check Duplication for Banner Name based on Campaign ID */
				    $where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
				    $where_not_in	= array('bannerid',$bid);
				    $banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
				    if($banner_check==FALSE)
				    {
						/* Get Child Banner IDs */
						$where_child = array('master_banner'=>$bid);
						$child = $this->mod_banner->get_banner_child($where_child);
						
                                                for($c=0;$c<count($child);$c++)
						{						
							if($child[$c]->width==$size['master']['width'])
							{
								$large_bid = $child[$c]->bannerid;
							}
							elseif($child[$c]->width==$size['child1']['width'])
							{
								$medium_bid = $child[$c]->bannerid;
							}
							elseif($child[$c]->width==$size['child2']['width'])
							{
								$small_bid = $child[$c]->bannerid;
							}
							elseif($child[$c]->width==$size['child3']['width'])
							{
								$xsmall_bid = $child[$c]->bannerid;
							}
						}
						if($_FILES['large_banner']['name'])
						{
							/* Master/Child 0(Large) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['large_banner']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['large_banner']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $size['master']['width'];
							$config['height'] 		    = $size['master']['height'];
			
							$this->image_lib->initialize($config);
							
							
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								
								if($campid == '0' || $adv == '0')
								{
									$this->edit_banner($bid);
								   //redirect('admin/inventory_banners');
								}
								else
								{
									$this->edit_banner($bid,$campid,$adv);
									//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								$this->image_lib->clear();	
								$large_img_type		= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);								
							}
							
							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = array('master_banner'=>$bid);
							
							/* Unlink Existing Image from Directory */	
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							for($i=0;$i<count($ex_banner);$i++)
							{
								if($ex_banner[$i]->width==$size['master']['width'])
								{
									unlink('./openx-server/www/images/'.$ex_banner[$i]->filename);
								}
							}*/
							
							/* Update Master Iamge Banner - Start */
							$update_master_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($banner_image),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['master']['width']),
								'height'	  	=>mysql_real_escape_string($size['master']['height']),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);							

							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_master_banner,$where_banner))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}							
							/* Update Master Iamge Banner - End */
							
							/* Update Large Iamge Banner - Start */
							$update_large_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($banner_image),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($large_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['master']['width']),
								'height'	  	=>mysql_real_escape_string($size['master']['height']),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_large = array('bannerid'=>$large_bid);
														
							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_large_banner,$where_large))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							/* Update Large Iamge Banner - End */
							
						}
						else
						{
							/* Update Master Iamge Banner - Start */
							$update_master_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_banner = array('bannerid'=>$bid);
							
							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_master_banner,$where_banner))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							
							/* Update Master Iamge Banner - End */
							
							/* Update Large Iamge Banner - Start */
							$update_large_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_large = array('bannerid'=>$large_bid);
							
							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_large_banner,$where_large))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							
							/* Update Large Iamge Banner - End */
						}
						
						if($_FILES['medium_banner']['name'])
						{
							/* Child 1(Medium) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['medium_banner']['name'];
							$config1['image_library']   = 'gd2';
							$config1['allowed_types']   = 'gif|jpg|png|jpeg';
							$config1['max_size']	    = '2000';
							$config1['source_image']	= $_FILES['medium_banner']['tmp_name'];
							$config1['new_image']       = $this->config->item('ads_url').$banner_image;
							$config1['maintain_ratio']  = FALSE;
							$config1['width'] 		    = $size['child1']['width'];
							$config1['height'] 		    = $size['child1']['height'];
			
							$this->image_lib->initialize($config1);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								   //redirect('admin/inventory_banners');
								}
								else
								{
									$this->edit_banner($bid,$campid,$adv);
									//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								$this->image_lib->clear();
								$medium_img_type	= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);								
							}
							
							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = array('master_banner'=>$bid);
							
							/* Unlink Existing Image from Directory */	
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							for($i=0;$i<count($ex_banner);$i++)
							{
								if($ex_banner[$i]->width==$size['child1']['width'])
								{
									unlink('./openx-server/www/images/'.$ex_banner[$i]->filename);
								}
							}*/
							
							/* Update Medium Iamge Banner - Start */
							$update_medium_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($banner_image),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($medium_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child1']['width']),
								'height'	  	=>mysql_real_escape_string($size['child1']['height']),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_medium = array('bannerid'=>$medium_bid);
							
							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_medium_banner,$where_medium))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							/* Update Medium Iamge Banner - End */
						
						}
						else
						{
							/* Update Medium Iamge Banner - Start */
							$update_medium_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_medium = array('bannerid'=>$medium_bid);
							
							/* Banner Update Method */
							if(!$this->mod_banner->update_banner($update_medium_banner,$where_medium))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}							
							/* Update Medium Iamge Banner - End */
						}	
					
						if($_FILES['small_banner']['name'])
						{
							/* Child 2(Small) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['small_banner']['name'];
							$config2['image_library']   = 'gd2';
							$config2['allowed_types']   = 'gif|jpg|png|jpeg';
							$config2['max_size']	    = '2000';
							$config2['source_image']	= $_FILES['small_banner']['tmp_name'];
							$config2['new_image']       = $this->config->item('ads_url').$banner_image;
							$config2['maintain_ratio']  = FALSE;
							$config2['width'] 		    = $size['child2']['width'];
							$config2['height'] 		    = $size['child2']['height'];
			
							$this->image_lib->initialize($config2);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $adv == '0')
								{
									$this->edit_banner($bid);
								   //redirect('admin/inventory_banners');
								}
								else
								{
									$this->edit_banner($bid,$campid,$adv);
									//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								$this->image_lib->clear();
								$small_img_type		= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);								
							}
							
							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = array('master_banner'=>$bid);
							
							/* Unlink Existing Image from Directory */	
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							for($i=0;$i<count($ex_banner);$i++)
							{
								if($ex_banner[$i]->width==$size['child2']['width'])
								{
									unlink('./openx-server/www/images/'.$ex_banner[$i]->filename);
								}
							}*/
							
							/* Update Small Iamge Banner - Start */
							$update_small_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($banner_image),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($small_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child2']['width']),
								'height'	  	=>mysql_real_escape_string($size['child2']['height']),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_small = array('bannerid'=>$small_bid);
							
							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_small_banner,$where_small))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
													
							/* Update Small Iamge Banner - End */
							
						}
						else
						{
							/* Update Small Iamge Banner - Start */
							$update_small_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_small = array('bannerid'=>$small_bid);
							
							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_small_banner,$where_small))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
													
							/* Update Small Iamge Banner - End */
						}
						
						if($_FILES['x_small_banner']['name'])
						{
							/* Child 3(XSmall) Image Upload */
							$banner_image               = rand(999, 9999)."-".$_FILES['x_small_banner']['name'];
							$config3['image_library']   	= 'gd2';
							$config3['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config3['max_size']	    	= '2000';
							$config3['source_image']		= $_FILES['x_small_banner']['tmp_name'];
							$config3['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config3['maintain_ratio']  	= FALSE;
							$config3['width'] 		    = $size['child3']['width'];
							$config3['height'] 		    = $size['child3']['height'];
			
							$this->image_lib->initialize($config3);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Deleted Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $adv == '0')
								{
									$this->edit_banner($bid);
								   //redirect('admin/inventory_banners');
								}
								else
								{
									$this->edit_banner($bid,$campid,$adv);
									//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								$this->image_lib->clear();
								$xsmall_img_type	= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);								
							}
							
							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = array('master_banner'=>$bid);
							
							/* Unlink Existing Image from Directory */	
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							for($i=0;$i<count($ex_banner);$i++)
							{
								if($ex_banner[$i]->width==$size['child3']['width'])
								{
									unlink('./openx-server/www/images/'.$ex_banner[$i]->filename);
								}
							}*/
							
							/* Update XSmall Iamge Banner - Start */
							$update_xsmall_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($banner_image),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'contenttype' 	=>mysql_real_escape_string($xsmall_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($size['child3']['width']),
								'height'	  	=>mysql_real_escape_string($size['child3']['height']),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_xsmall = array('bannerid'=>$xsmall_bid);
							
							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_xsmall_banner,$where_xsmall))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							/* Update XSmall Iamge Banner - End */
							
						}
						else
						{
							/* Update XSmall Iamge Banner - Start */
							$update_xsmall_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							/* Banner Update Method and Get Last Insert ID */
							$where_xsmall = array('bannerid'=>$xsmall_bid);
							
							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_xsmall_banner,$where_xsmall))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							/* Update XSmall Iamge Banner - End */
						}
						
						/* Tablet Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
						if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
						
				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
				   }
				}
				break;	
			}
			
			case 1:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaignID', 'Campaign', 'required');
				$this->form_validation->set_rules('txt_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('txt_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('txt_banner_content', 'Banner Content', 'required');
				
				 if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Edit Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign		= $this->input->post('campaignID');
					$banner_name 	= $this->input->post('txt_banner_name');
					$banner_url 	= $this->input->post('txt_banner_url');
					$banner_content = $this->input->post('txt_banner_content');
					
					/* Hard Coded Values */
					$storage_type	= "txt";
					$master_banner	= -1;
					$admin_status	= 0;
					
					/* Check Duplication for Banner Name based on Campaign ID */
				    $where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
				    $where_not_in	= array('bannerid'=>$bid);
				    $banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
				    if($banner_check==FALSE)
				    {
						/* Update Banner Parameters */
						$update_banner = array(
							'campaignid'=>mysql_real_escape_string($campaign),
							'description'=>mysql_real_escape_string($banner_name),
							'url'=>mysql_real_escape_string($banner_url),
							'bannertext'=>mysql_real_escape_string($banner_content),
							'contenttype' =>mysql_real_escape_string($storage_type),
							'storagetype'=>mysql_real_escape_string($storage_type),
							'master_banner'=>mysql_real_escape_string($master_banner),
							'adminstatus'=>mysql_real_escape_string($admin_status)
						);
						$where_banner = array('bannerid'=>$bid);
						
						/* Banner Information Update Method */
                        if($this->mod_banner->update_banner($update_banner,$where_banner))
						{
							/* Banner updated Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
							if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
						}
						else
						{
							/* Banner Update Failed */
							$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
						    if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
						}
				   }
				   else
				   {
					   /* Text Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
				   }
				}
				break;	
			}
			
			case 2:
			{
				$this->form_validation->set_rules('advertiser', 'Advertiser', 'required');
				$this->form_validation->set_rules('campaignID', 'Campaign', 'required');
				$this->form_validation->set_rules('tablet_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('tab_banner_url', 'Banner URL', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Edit Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign		= $this->input->post('campaignID');
					$banner_name 	= $this->input->post('tablet_banner_name');
					$banner_url 	= $this->input->post('tab_banner_url');
					$banner_size	= $this->input->post('banner_size');
					
					/* Split Banner Width and Height */
					list($width, $height)  =explode(" X ", $this->input->post('banner_size'));					
					
					/* Hard Coded Values */
					$storage_type	= "web";
					$master_banner	= -3;
					$admin_status	= 0;
					
					/* Check Duplication for Banner Name based on Campaign ID */
				    $where_banner	= array('description'=>$banner_name,'campaignid'=>$campaign);
				    $where_not_in	= array('bannerid'=>$bid);
				    $banner_check  	= $this->mod_banner->check_banner_duplication($where_banner,$where_not_in);
				    if($banner_check==FALSE)
				    {
						if($_FILES['tab_banner_image']['name'])
						{	
							$banner_image               = rand(999, 9999)."-".$_FILES['tab_banner_image']['name'];
							$config['image_library']   	= 'gd2';
							$config['allowed_types']   	= 'gif|jpg|png|jpeg';
							$config['max_size']	    	= '2000';
							$config['source_image']		= $_FILES['tab_banner_image']['tmp_name'];
							$config['new_image']       	= $this->config->item('ads_url').$banner_image;
							$config['maintain_ratio']  	= FALSE;
							$config['width'] 		    = $width;
							$config['height'] 		    = $height;
			
							$this->image_lib->initialize($config);
													 
							if(!$this->image_lib->resize())
							{
								/* Banner Upload Failed. Redirect to Banner List */
								$this->session->set_userdata('banner_error', lang('label_image_display_errors'));
								if($campid == '0' || $adv == '0')
								{
								    $this->edit_banner($bid);
								   //redirect('admin/inventory_banners');
								}
								else
								{
									$this->edit_banner($bid,$campid,$adv);
									//redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								$this->image_lib->clear();
								$tab_img_type	= $this->mod_banner->staticGetContentTypeByExtension($banner_image, $alt=false);
								$tab_img_name 	= $banner_image;
							}
							
							/* Update Banner Parameters */
							$update_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'filename'    	=>mysql_real_escape_string($tab_img_name),
								'contenttype' 	=>mysql_real_escape_string($tab_img_type),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($width),
								'height'	  	=>mysql_real_escape_string($height),
								'master_banner'	=>mysql_real_escape_string($master_banner),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = 0;
							
							/* Unlink Existing Image from Directory */	
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							unlink('./openx-server/www/images/'.$ex_banner[0]->filename);*/
							
							/* Banner Information Update Method */
							if($this->mod_banner->update_banner($update_banner,$where_banner))
							{
								/* Banner updated Successfully. Redirect to Banner List */
								$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
								if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							
							/* Tablet Banner added Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
							if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
						}
						else
						{
							/* Update Banner Parameters */
							$update_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'storagetype'	=>mysql_real_escape_string($storage_type),
								'width'		  	=>mysql_real_escape_string($width),
								'height'	  	=>mysql_real_escape_string($height),
								'master_banner'	=>mysql_real_escape_string($master_banner),
								'adminstatus'	=>mysql_real_escape_string($admin_status)
							);
							
							$where_banner = array('bannerid'=>$bid);
							/* Banner Information Update Method */
							if($this->mod_banner->update_banner($update_banner,$where_banner))
							{
								/* Banner updated Successfully. Redirect to Banner List */
								$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
								if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
							}
							else
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								if($campid == '0' || $adv == '0')
								{
								   $this->edit_banner($bid);
								}
								else
								{
								   $this->edit_banner($bid,$campid,$adv);
								}
							}
							
							/* Tablet Banner updated Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success'));
							if($campid == '0' || $adv == '0')
								{
								   redirect('admin/inventory_banners');
								}
								else
								{
									redirect('admin/inventory_banners/listing_camp/all/'.$campid."/".$adv);
								}
						}
				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					if($campid == '0' || $adv == '0')
					{
					   $this->edit_banner($bid);
					}
					else
					{
					   $this->edit_banner($bid,$campid,$adv);
					}
				   }
				}
				break;	
			}
		}
	}        
}

/* End of file inventory_banners.php */
/* Location: ./modules/admin/inventory_banners.php */
