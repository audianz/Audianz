<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banners extends CI_Controller {  

	public function __construct()
	{
		parent::__construct();
				
		$this->load->model('mod_campaign');
		$this->load->model('mod_campaigns');
        	$this->load->model('mod_statistics');
		$this->load->model('mod_banner'); 
		$this->load->library('image_lib');               
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
	}
	
	/* Banners Page */
	public function index()
	{
		$this->session->set_flashdata('camp_error', $this->lang->line('label_advertiser_please_select_campaign'));
		redirect("advertiser/campaigns");
	}


	/* Banners Listing Page */	
	public function listing($campaign_id,$start=0)
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/		
		$link = breadcrumb();		
		$data['breadcrumb'] = $link;
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_inventory_banner_page_title');		
		/*-------------------------------------------------------------
		 	Authorisation Check
		 -------------------------------------------------------------*/		
		$adv_id = $this->session->userdata('session_advertiser_id');
		$dup_data = $this->mod_banner->check_adv_authorisation($adv_id,$campaign_id);
		if($dup_data!='')
		{
			$campaignid = $campaign_id;

			$where 	="ox_banners.campaignid ={$campaignid} AND (master_banner =-1 OR master_banner =-2 OR master_banner =-3)";
		
			/*--------------------------------------------------------------------
				Get the all banners matching above from db for listing
			---------------------------------------------------------------------*/
		
			$list_data = $this->mod_banner->get_banners($where,$adv_id);
			
			$data['banners_list']       =	$list_data;
			
			$data['campaignid']			=	$campaignid;
					
			/*-------------------------------------------------------------
			 	Total Counts for Active and Inactive Banners
			--------------------------------------------------------------*/               
			$where_tot = array('ox_banners.weight'=>1);
			$where_act = array('ox_banners.weight'=>1,'ox_banners.status'=>0);
			$where_inact = array('ox_banners.weight'=>1,'ox_banners.status'=>1);
		
			$tot_data = $this->mod_banner->get_banners($where_tot);
			$data['tot_data'] = count($tot_data);
		
			$active_data = $this->mod_banner->get_banners($where_act);
			$data['active_data'] = count($active_data);
		
			$inactive_data = $this->mod_banner->get_banners($where_inact);
			$data['inactive_data'] = count($inactive_data);

		        /*--------------------------------------------------------------------------
		         * Get Reports for each banners based on selected Campaigns and Advertiser
		         * -------------------------------------------------------------------------*/


		        $search_arr                         =   array();
		        $search_arr['from_date']            =	$this->mod_statistics->get_start_date($this->session->userdata('session_advertiser_account_id'));
		        $search_arr['to_date']              =	date("Y/m/d");
		        $search_arr['search_type']          =	"all";
		        $search_arr['sel_campaign_id']      =	$campaignid;

		        $data['stat_data'] = $this->mod_statistics->get_statistics_for_advertiser_campaigns($this->session->userdata("session_advertiser_id"),$search_arr);


			/*-------------------------------------------------------------
			 	Embed current page content into template layout
			 -------------------------------------------------------------*/
			 	
			$data['page_content']	= $this->load->view("advertiser/banners/banners_list",$data,true);
			$this->load->view('advertiser_layout',$data);
				
		}
		else	
		{	
			$data['page_content']	= $this->load->view("advertiser/banners/no_page",$data,true);
			$this->load->view('advertiser_layout',$data);
		}	
	}

       	/* Run/Activate the banner */
        public function run_banner()
        {
            $campaignid = $this->input->post('campaignid');
            $banners    = $_POST['bannerarr'];
	$adv_id = $this->session->userdata('session_advertiser_id');

            // Get campaign deatils
            $campaigns = $this->mod_campaign->retrieve_campaign($campaignid);
            if($campaigns!=false)
            {
                  foreach($banners as $bid)
                  {
						/* Advertiser Account Balance */
						$accBal = $this->mod_campaigns->check_advertiser_balance($adv_id);
						
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
							  $banner = $this->mod_banner->get_banners(array('bannerid' => $bid));
							  foreach($banner as $ban)
							  {
								  $adminstatus = $ban->adminstatus;
								  //$bannername = $ban->description;
							  }
							 if($adminstatus==0)
							 {	
								$data = array('status' => 0);
								$where = ("(bannerid =".$bid." OR master_banner=".$bid.")");
								$this->mod_banner->update_banner($data,$where);
								$this->session->set_flashdata('run_banner', $this->lang->line('label_banner_run_success'));
							 }
							 else
							 {
								$this->session->set_flashdata('banner_approval_error', $this->lang->line('label_banner_approval_error'));
							 }
						}
						else
						{
							$this->session->set_flashdata('banner_account_error', $this->lang->line('label_banner_admin_fund_error'));
						}

                  }

           }
           else
           {
               redirect('advertiser/banners');
           }
        }

        /* Pause/Deactivate the banner */
        public function pause_banner()
        {
            $campaignid = $this->input->post('campaignid');
            $banners    = $_POST['bannerarr'];

            // Get campaign deatils
            $campaigns = $this->mod_campaign->retrieve_campaign($campaignid);
            if($campaigns!=false)
            {
                        foreach($banners as $bid)
			{
                            $banner = $this->mod_banner->get_banners(array('bannerid' => $bid));
                            foreach($banner as $ban)
                             {
                                $adminstatus = $ban->adminstatus;
                                $status = $ban->status;
                                //$bannername = $ban->description;
                             }
                             if($adminstatus==0 and $status==0)
                             {
                                $where		=("(bannerid =".$bid." OR master_banner=".$bid.")"); // campaignid=".$campaignid." AND
                                $update_data	=array("status" =>'1');
                                $this->mod_banner->update_banner($update_data, $where);
                                $this->session->set_flashdata('pause_banner', $this->lang->line('label_banner_pause_success'));
                             }
                        }

           }
           else
           {
               $this->session->set_flashdata('banner_error', $this->lang->line('label_error_missing'));
               redirect('advertiser/banners');
           }
        }



	/* Delete Banner */
	public function delete_banner($bid=0)
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
			/* Delete Banner */
			$this->mod_banner->delete_banner($where_arr);
			/* Banner Deleted Successfully. Redirect to Banner List */
			$this->session->set_flashdata('banner_delete_success', $this->lang->line('label_banner_delete_success'));
			redirect('advertiser/banners');
		}
		else
		{
				/* Banner Deleted Failed. Redirect to Banner List */
				$this->session->set_flashdata('banner_error', $this->lang->line('label_error_missing'));
				redirect('advertiser/banners');
		}
	}
	
		/* Add New Banner */
	public function add_banner($campaignid=0)
	{
		/*-------------------------------------------------------------
		Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		/*-------------------------------------------------------------
		Assign campaign id
		-------------------------------------------------------------*/

		$data['campaignid'] = $campaignid;
		$data['mob_screens']	= $this->mod_banner->banner_screen();
		/*-------------------------------------------------------------
				Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['page_content'] = $this->load->view("advertiser/banners/add_banners",$data,true);
		$this->load->view('advertiser_layout',$data);
	}
	
	/* Filter Campaigns List Based on Advertiser Select  */
	public function filter_campaigns($advid=0)
	{
		if($advid!=0)
		{
			$where_adv = array('clientid'=>$advid);
			$camp_list = $this->mod_banner->filter_campaigns($where_adv);
			$data['campaigns']  = $camp_list;
			echo $this->load->view('admin/banners/filter_campaigns',$data);
		}
		else
		{
			$data['campaigns']  = 0;
			echo $this->load->view('admin/banners/filter_campaigns',$data);
		}	
	}
	
	/* Add New Banner Process */
	public function add_banner_process($campaignid=0)
	{
               $banner_type = $this->input->post('banner_type');

		switch($banner_type)
		{
			case 0:
			{
				$this->form_validation->set_rules('img_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('img_banner_url', 'Banner URL', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner();
				}
				else
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
							$this->session->set_flashdata('banner_error', $this->lang->line('label_image_display_errors'));
							redirect('advertiser/banners');
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
							$this->session->set_flashdata('banner_error', $this->lang->line('label_image_display_errors'));
							redirect('advertiser/banners');
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
							
							$this->session->set_flashdata('banner_error', $this->lang->line('label_image_display_errors'));
							redirect('advertiser/banners');
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
							$this->session->set_flashdata('banner_error',  $this->lang->line('label_image_display_errors'));
							redirect('advertiser/banners');
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
                                                        'status'        => mysql_real_escape_string($status),
							'master_banner'	=>mysql_real_escape_string($master_banner),
                                                        'adminstatus'        => mysql_real_escape_string($adminstatus),
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
							'status'        => mysql_real_escape_string($status),
							'master_banner'	=>mysql_real_escape_string($parent_id),
                                                        'adminstatus'        => mysql_real_escape_string($adminstatus),
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
							'url'			=>mysql_real_escape_string($banner_url),
							'filename'    	=>mysql_real_escape_string($medium_img_name),
							'bannertext'	=>mysql_real_escape_string($banner_content),
							'contenttype' 	=>mysql_real_escape_string($medium_img_type),
							'storagetype'	=>mysql_real_escape_string($storage_type),
							'width'		  	=>mysql_real_escape_string($size['child1']['width']),
							'height'	  	=>mysql_real_escape_string($size['child1']['height']),
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

						$where_place = array("placement_id"=>$campaign);
						$query = $this->db->get_where('ox_placement_zone_assoc',$where_place);
						if($query->num_rows()>0)
						{
							foreach($query->result() as $row)
							{
								$zoneid = $row->zone_id;
							}

							$ins_ad_zone = array("zone_id"=>$zoneid,"ad_id"=>$xsmall_img_id,"link_type"=>"1");
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}
						/* Add XSmall Iamge Banner - End */


						/* Tablet Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success_adv'));
						redirect('advertiser/banners/listing/'.$campaignid);

				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->add_banner();
				   }
				}
				break;
			}
			case 1:
			{
				$this->form_validation->set_rules('txt_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('txt_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('txt_banner_content', 'Banner Content', 'required');
				 if ($this->form_validation->run() == FALSE)
				{

				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner();
				}
				else
				{

					$advertiser 	= $this->input->post('advertiser');
					$campaign       = $campaignid;
					$banner_name 	= $this->input->post('txt_banner_name');
					$banner_url 	= $this->input->post('txt_banner_url');
					$banner_content = $this->input->post('txt_banner_content');

					/* Hard Coded Values */
					$storage_type	= "txt";
					$master_banner	= -1;
                                        $status         = 1;
					$adminstatus	= 1;

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
							'status'        => mysql_real_escape_string($status),
							'master_banner'	=>mysql_real_escape_string($master_banner),
                                                        'adminstatus'        => mysql_real_escape_string($adminstatus),
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
							//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
						}

						/* Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success_adv'));
						redirect('advertiser/banners/listing/'.$campaignid);

				   }
				   else
				   {
					   /* Text Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->add_banner();
				   }
				}
				break;
			}
			case 2:
			{
				$this->form_validation->set_rules('tablet_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('tab_banner_url', 'Banner URL', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->add_banner();
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign	= $campaignid;
					$banner_name 	= $this->input->post('tablet_banner_name');
					$banner_url 	= $this->input->post('tab_banner_url');
					$banner_size	= $this->input->post('banner_size');

					/* Split Banner Width and Height */
					list($width, $height)  =explode(" X ", $this->input->post('banner_size'));

					/* Hard Coded Values */
					$storage_type	= "web";
					$master_banner	= -3;
                                        $status         = 1;
					$adminstatus	= 1;

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
							$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
							redirect('advertiser/banners');
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
							'status'        => mysql_real_escape_string($status),
							'master_banner'	=>mysql_real_escape_string($master_banner),
                                                        'adminstatus'        => mysql_real_escape_string($adminstatus),
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
								//$this->db->insert('ox_ad_zone_assoc',$ins_ad_zone);
							}
						}

						/* Tablet Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_add_success_adv'));
						redirect('advertiser/banners/listing/'.$campaignid);

				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->add_banner();
				   }
				}
				break;
			}
		}

	}
        

	/* Edit Banner */
	public function edit_banner($campaignid=0,$banner_id=0)
	{
		/*-------------------------------------------------------------
			Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		$data['mob_screens']	= $this->mod_banner->banner_screen();
		/*-------------------------------------------------------------
			Get Advertiser List
		-------------------------------------------------------------*/
		$adv_list = $this->mod_campaign->get_advertiser_list();
		$data['advertiser'] = $adv_list;
		/*-------------------------------------------------------------
			Get Banner Details
		-------------------------------------------------------------*/
		$where_ban	 	= array('bannerid'=>$banner_id,'ox_banners.campaignid'=>$campaignid); 
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
		 if($banner_info!=false)
		 {
			$data['page_content'] = $this->load->view("advertiser/banners/edit_banners",$data,true);
			$this->load->view('advertiser_layout',$data);
		}
		else
		 {
			$data['page_content']	= $this->load->view("banners/no_page",$data,true);
			$this->load->view('advertiser_layout',$data);
		 }
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
	
	public function linked_zones($banner_id=0,$campaign_id=0){
	

		$link = breadcrumb();		
		$data['breadcrumb'] = $link;
		//GET BANNER DETAILS
		
		$banner_data	=	$this->mod_banner->get_banner_det($banner_id);
		if($banner_data != false)
		{
			$budget_val	=	$this->mod_banner->get_budget_value($banner_data->clientid,$banner_data->campaignid);
			
			if($budget_val != false)
			{
					
				//echo $this->lang->line('label_advertiser_banners_not_sufficient_amt');exit;
				if($budget_val > 0){
				
				/*-------------------------------------------------------------
					Breadcrumb Setup Start
				 -------------------------------------------------------------*/	
					
				
				
				$data['sel_banner_id'] = $banner_id;
				
				$data['linked_zones_list'] = $this->mod_banner->list_linked_zones($banner_id);
				
				$data['mapped_zones']	   = $this->mod_banner->getAssocZones($banner_id);
				
				$data['banner_det']		= $banner_data;	
				
				$data['campaign_id'] 		= $campaign_id;
				
				/*-------------------------------------------------------------
					Embed current page content into template layout
				 -------------------------------------------------------------*/
				$data['page_content']	= $this->load->view("advertiser/banners/linked_zones",$data,true);
				$this->load->view('advertiser_layout',$data);
				
				}
				else
				{
					$this->session->set_flashdata('banner_error',$this->lang->line('label_advertiser_banners_not_sufficient_amt'));
					redirect("advertiser/banners/listing/".$banner_data->campaignid);
				}
			}		
			else
			 {
				$data['page_content']	= $this->load->view("campaigns/no_page",$data,true);
				$this->load->view('advertiser_layout',$data);
			 }
		}
		else
		 {
			$data['page_content']	= $this->load->view("campaigns/no_page",$data,true);
			$this->load->view('advertiser_layout',$data);
		 }
	}
	
	public function linked_zones_process($sel_banner_id,$campaign_id){
		
		
		if(isset($_POST['sel_zone']) AND count($_POST['sel_zone'])){
		
			$cur_date 	=	date("Y-m-d H:i:s");
			
			$banner_id	=	$this->input->post("banner_id");	
			$sel_zone_id	=	$this->input->post("sel_zone");
		
			// Get child zones if exists
			
			$child_banners	=	$this->mod_banner->get_parent_child_banner_or_zones($banner_id,"banners");
			
			// Selected Zones
			$sel_banner_list	=	array();
			
			if($child_banners != false){
				foreach($child_banners as $bData){
					array_push($sel_banner_list,$bData->bannerid);
				}
			}
			else
			{
				array_push($sel_zone_list,$sel_zone_id);
			}
			
			// Delete previous linked zones for selected Banner and it's child. 
			
			$this->mod_banner->del_ads_placement_mapping($sel_banner_list);
			
			$sel_zone_list		=$this->input->post("sel_zone");
		
			if(is_array($sel_zone_list) AND count($sel_zone_list) > 0){
				
				foreach($sel_zone_list as $val)
				{
					// GET MASTER BANNER ID FROM BANNERS BASED ON SELECTED BANNER ID 
		 			
					$banner				=	$this->mod_banner->get_banner_data($banner_id);
					$master_banenr_id	=	$banner->master_banner;
					
					if($master_banenr_id == -2){
					
					 // Image Type Banner
						
						// INSERT PARENT BANNER ASSOC WITH SELECTED ZONE
						
						$insert_zone_assoc	=	array("zone_id" =>$val, "ad_id" =>$banner_id);
						$this->mod_banner->insert_zone_assoc($insert_zone_assoc);
						
						
						// GET ALL CHILD BANNERS
						$child_banners	=	$this->mod_banner->get_child_banner_or_zones($banner_id,"banners");
						
						$sel_banner_list	=	array();
						
						if($child_banners != false){
							foreach($child_banners as $bData){
								array_push($sel_banner_list,$bData->bannerid);
							}
						}
					
						
						// GET CAMPAIGN REVENUE TYPE
						$campaign_revenue_type	=	$banner->revenue_type;
						
						
						
						foreach($sel_banner_list as $bData){
							$banner_det	=	$this->mod_banner->get_banner_data($bData);
							// GET ZONE LIST AND IT'S CHILD ZONES
							
							//print_r($sel_banner_list);
							
							
							$zones_list = $this->mod_banner->get_child_banner_or_zones($val,"zones");
							foreach($zones_list as $zData){
								
								$zone_det	=	$this->mod_banner->get_zone_data($zData->zoneid);
								if($campaign_revenue_type==$zone_det->revenue_type && $banner_det->width==$zone_det->width && $banner_det->height===$zone_det->height)
								{		
										$insert_zone_assoc	=	array("zone_id" =>$zone_det->zoneid, "ad_id" =>$banner_det->bannerid);
										$this->mod_banner->insert_zone_assoc($insert_zone_assoc);
								}
							}	

						}
						
					}
					else
					{
					 // Other Type of Banners
						
						// GET CAMPAIGN REVENUE TYPE
						$campaign_revenue_type	=	$banner->revenue_type;
						$zones_list = $this->mod_banner->get_parent_child_banner_or_zones($val,"zones");
					
						foreach($zones_list as $zData){
								
							$zone_det	=	$this->mod_banner->get_zone_data($zData->zoneid);
							if($campaign_revenue_type==$zone_det->revenue_type && $banner->width==$zone_det->width && $banner->height===$zone_det->height)
							{
									$insert_zone_assoc	=	array("zone_id" =>$zone_det->zoneid, "ad_id" =>$banner->bannerid);
									$this->mod_banner->insert_zone_assoc($insert_zone_assoc);
							}
						}
						
						
					}
					
				}
				
			}
		
			  $this->session->set_flashdata('success_message', $this->lang->line('label_advertiser_banners_selected_zones_link_banner')."<b>".$banner->description."</b>");
			  redirect("advertiser/banners/linked_zones/".$this->input->post("banner_id").'/'.$campaign_id);
			
		}
		else
		{
			$banner_id	=	$this->input->post("banner_id");	
			
			// Get child zones if exists
			
			$child_banners	=	$this->mod_banner->get_parent_child_banner_or_zones($banner_id,"banners");
			
			// Selected Zones
			$sel_banner_list	=	array();
			
			if($child_banners != false){
				foreach($child_banners as $bData){
					array_push($sel_banner_list,$bData->bannerid);
				}
			}
			else
			{
				array_push($sel_zone_list,$sel_zone_id);
			}
			
			// Delete previous linked zones for selected Banner and it's child. 
			
			$this->mod_banner->del_ads_placement_mapping($sel_banner_list);
			
			
			$this->session->set_flashdata('success_message',$this->lang->line('label_advertiser_banners_previous_link_zones_removed'));
			redirect("advertiser/banners/linked_zones/".$this->input->post('banner_id'));
		}

	
}
	/* Modify Banner information */
	public function edit_banner_process($bid)
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
				$this->form_validation->set_rules('img_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('img_banner_url', 'Banner URL', 'required');
				if ($this->form_validation->run() == FALSE)
				{
                                    /* Form Validation is failed. Redirect to Add Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->edit_banner($bid);
				}
				else
				{
                    $advertiser 	= $this->input->post('advertiser');
					$campaign	= $this->input->post('ajax_camp_id');
					$banner_name 	= $this->input->post('img_banner_name');
					$banner_url 	= $this->input->post('img_banner_url');
					$banner_content	= $this->input->post('img_banner_txt');

					/* Hard Coded Values */
					$storage_type	= "web";
					
                                        
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
								$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
								redirect('advertiser/banners');
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
									unlink($this->config->item('ads_url').$ex_banner[$i]->filename);
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
								'height'	  	=>mysql_real_escape_string($size['master']['height'])
								
							);

							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_master_banner,$where_banner))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								'height'	  	=>mysql_real_escape_string($size['master']['height'])
								);
							$where_large = array('bannerid'=>$large_bid);

							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_large_banner,$where_large))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								'storagetype'	=>mysql_real_escape_string($storage_type)
								
							);

							$where_banner = array('bannerid'=>$bid);

							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_master_banner,$where_banner))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
							}

							/* Update Master Iamge Banner - End */

							/* Update Large Iamge Banner - Start */
							$update_large_banner = array(
								'campaignid'	=>mysql_real_escape_string($campaign),
								'description'	=>mysql_real_escape_string($banner_name),
								'url'			=>mysql_real_escape_string($banner_url),
								'bannertext'	=>mysql_real_escape_string($banner_content),
								'storagetype'	=>mysql_real_escape_string($storage_type)
								
							);

							$where_large = array('bannerid'=>$large_bid);

							/* Banner Information Update Method */
							if(!$this->mod_banner->update_banner($update_large_banner,$where_large))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
								redirect('advertiser/banners');
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
									unlink($this->config->item('ads_url').$ex_banner[$i]->filename);
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
								'height'	  	=>mysql_real_escape_string($size['child1']['height'])
								
							);

							$where_medium = array('bannerid'=>$medium_bid);

							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_medium_banner,$where_medium))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								'storagetype'	=>mysql_real_escape_string($storage_type)
								
							);

							$where_medium = array('bannerid'=>$medium_bid);

							/* Banner Update Method */
							if(!$this->mod_banner->update_banner($update_medium_banner,$where_medium))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
								redirect('advertiser/banners');
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
									unlink($this->config->item('ads_url').$ex_banner[$i]->filename);
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
								'height'	  	=>mysql_real_escape_string($size['child2']['height'])
								
							);

							$where_small = array('bannerid'=>$small_bid);

							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_small_banner,$where_small))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								'storagetype'	=>mysql_real_escape_string($storage_type)
								
							);

							$where_small = array('bannerid'=>$small_bid);

							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_small_banner,$where_small))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
								redirect('advertiser/banners');
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
									unlink($this->config->item('ads_url').$ex_banner[$i]->filename);
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
								'height'	  	=>mysql_real_escape_string($size['child3']['height'])
								
							);

							$where_xsmall = array('bannerid'=>$xsmall_bid);

							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_xsmall_banner,$where_xsmall))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
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
								'storagetype'	=>mysql_real_escape_string($storage_type)
								
							);
							/* Banner Update Method and Get Last Insert ID */
							$where_xsmall = array('bannerid'=>$xsmall_bid);

							/* Banner Update Method and Get Last Insert ID */
							if(!$this->mod_banner->update_banner($update_xsmall_banner,$where_xsmall))
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
							}
							/* Update XSmall Iamge Banner - End */
						}

						/* Tablet Banner added Successfully. Redirect to Banner List */
						$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_edit_success'));
						redirect('advertiser/banners/listing/'.$campaign);

				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->edit_banner($bid);
				   }
				}
				break;
			}

			case 1:
			{
				$this->form_validation->set_rules('txt_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('txt_banner_url', 'Banner URL', 'required');
				$this->form_validation->set_rules('txt_banner_content', 'Banner Content', 'required');
				 if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Edit Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->edit_banner($bid);
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign	= $this->input->post('ajax_camp_id');
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
							'master_banner'=>mysql_real_escape_string($master_banner)
							
						);
						$where_banner = array('bannerid'=>$bid);

						/* Banner Information Update Method */
                        if($this->mod_banner->update_banner($update_banner,$where_banner))
						{
							/* Banner updated Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
							redirect('advertiser/banners/listing/'.$campaign);
						}
						else
						{
							/* Banner Update Failed */
							$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
						    $this->edit_banner($bid);
						}
				   }
				   else
				   {
					   /* Text Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->edit_banner($bid);
				   }
				}
				break;
			}

			case 2:
			{
				$this->form_validation->set_rules('tablet_banner_name', 'Banner Name', 'required');
				$this->form_validation->set_rules('tab_banner_url', 'Banner URL', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				   /* Form Validation is failed. Redirect to Edit Banner Form */
				   $this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
				   $this->edit_banner($bid);
				}
				else
				{
					$advertiser 	= $this->input->post('advertiser');
					$campaign	= $this->input->post('ajax_camp_id');
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
								$this->session->set_flashdata('banner_error', $this->image_lib->display_errors());
								redirect('advertiser/banners');
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
								'master_banner'	=>mysql_real_escape_string($master_banner)
								
							);

							/* Banner ID for updating banner info */
							$where_banner = array('bannerid'=>$bid);
							$where_master = 0;

							/* Unlink Existing Image from Directory */
							/*$ex_banner = $this->mod_banner->get_banner_filename($where_banner,$where_master);
							unlink($this->config->item('ads_url').$ex_banner[0]->filename);*/

							/* Banner Information Update Method */
							if($this->mod_banner->update_banner($update_banner,$where_banner))
							{
								/* Banner updated Successfully. Redirect to Banner List */
								$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
								redirect('advertiser/banners/listing/'.$campaign);
							}
							else
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
							}

							/* Tablet Banner added Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_edit_success'));
							redirect('advertiser/banners/listing/'.$campaign);
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
								'master_banner'	=>mysql_real_escape_string($master_banner)
								
							);

							$where_banner = array('bannerid'=>$bid);
							/* Banner Information Update Method */
							if($this->mod_banner->update_banner($update_banner,$where_banner))
							{
								/* Banner updated Successfully. Redirect to Banner List */
								$this->session->set_flashdata('banner_edit_success', $this->lang->line('label_banner_edit_success'));
								redirect('advertiser/banners/listing/'.$campaign);
							}
							else
							{
								/* Banner Update Failed */
								$this->session->set_userdata('banner_error', $this->lang->line('label_error_missing'));
								$this->edit_banner($bid);
							}

							/* Tablet Banner updated Successfully. Redirect to Banner List */
							$this->session->set_flashdata('banner_add_success', $this->lang->line('label_banner_edit_success'));
							redirect('advertiser/banners/listing/'.$campaign);
						}
				   }
				   else
				   {
					   /* Banner Duplication Error */
					   $this->session->set_userdata('banner_duplicate', $this->lang->line('label_banner_duplicate_error'));
					   $this->edit_banner($bid);
				   }
				}
				break;
			}
		}
	}
}

/* End of file banners.php */
/* Location: ./modules/advertiser/banners.php */

