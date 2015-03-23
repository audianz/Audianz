<?php
class Mod_campaign extends CI_Model 
{ 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    /* Retrieve Campaign */
    function retrieve_campaign($camp_id=0)
    {
        if($camp_id!=0)
        {
                $this->db->where($camp_id);
        }
        
        $this->db->where('ox_clients.clientid !=', '1');

        $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

        $this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid','left');

        $this->db->order_by('ox_campaigns.campaignid','desc');

        $query = $this->db->get('ox_campaigns');

        return $query->result();
    }
    /* Retrieve Campaigns List */
    function get_campaigns($filter, $where_arr, $offset=0,$limit=FALSE)
    {
            if($where_arr!=0)
            {
                    $this->db->where($where_arr);
            }
		
            $this->db->where('ox_clients.clientid !=', '1');
            
            $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');
            
            $this->db->order_by('ox_campaigns.campaignid','desc');

            $this->db->order_by('ox_campaigns.weight','asc');

		if($limit!= FALSE)
		
		$this->db->limit($limit, $offset);

		$query = $this->db->get('ox_campaigns');

		return $query->result();
    }

    /* Retrieve Campaigns List */
    function get_campaigns_count($filter, $where_arr=0)
    {
            $this->db->where($where_arr);

            $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');
            
            $this->db->where('ox_clients.clientid !=', '1');
            
            $this->db->order_by('ox_campaigns.campaignid','desc');

            $this->db->order_by('ox_campaigns.weight','asc');

            $query = $this->db->get('ox_campaigns');

            //echo $this->db->last_query();exit;

            return $query->result();
    }

    function delete_campaign_reports($where_arr=0)
    {
        if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
        $this->db->delete('oxm_report');

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }

    /* Retrieve Banners List */
    function getBanners($where_arr=0)
    {
        if($where_arr!=0)
        {
                $this->db->where($where_arr);
        }
        
        $this->db->select('ox_banners.bannerid');

        $query = $this->db->get('ox_banners');

        return $query->result();
    }
    
    
    
    /* Retrieve Advertiser List */
    function get_advertiser_list()
    {
        $this->db->select('ox_clients.clientid,ox_clients.contact,ox_users.contact_name');

        $this->db->where('ox_accounts.account_type','ADVERTISER');

        $this->db->join('ox_accounts','ox_accounts.account_id=ox_clients.account_id');

        $this->db->join('ox_users','ox_users.default_account_id=ox_clients.account_id');

        $this->db->order_by('ox_clients.contact','asc');

        $query = $this->db->get('ox_clients');

        return $query->result();
    }
    /* Checking Duplication for Campaign Creation */
    function check_campaign_duplication($where_camp)
    {
        if($where_camp!=0)
        {
            $this->db->where($where_camp);
        }
        
        $query = $this->db->get('ox_campaigns');

        return $query->result();
    }
    /* Create New Campaign */
    function add_new_campaign($add_campaign)
    {
        $this->db->insert('ox_campaigns', $add_campaign);

        if($this->db->affected_rows()>0)
        {
                return $this->db->insert_id();
        }
        else
        {
                return FALSE;
        }
    }
    /* Add Daily Budget for Created Campaigns */
    function add_campaign_budget($add_campaign_budget)
    {
        $this->db->insert('oxm_budget', $add_campaign_budget);

        if($this->db->affected_rows()>0)
        {
                return $this->db->insert_id();
        }
        else
        {
                return FALSE;
        }
    }
    /************** Delete Banner Data for Selected Campaign-Start ****************/
    function deleteBKTA($where='')
    {
           if($where !='')
           {
                $this->db->delete('ox_data_bkt_a', $where);
                $status	=$this->db->affected_rows();
                if($status >0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
    }

    function deleteBKTC($where='')
    {
       if($where !='')
       {
            $this->db->delete('ox_data_bkt_c', $where);
            $status	=$this->db->affected_rows();
             if($status >0){
                    return true;
             }
             else{
                    return false;
             }
       }
    }

    function deleteBKTUC($where='')
    {
       if($where !=0) {
         $this->db->delete('ox_data_bkt_country_c', $where);
             $status	=$this->db->affected_rows();
             if($status >0){
                    return true;
             }
             else{
                    return false;
             }
            }
       }

    function deleteBKTM($where='')
    {
       if($where !='')
       {
            $this->db->delete('ox_data_bkt_m', $where);
            $status	=$this->db->affected_rows();
             if($status >0){
                    return true;
             }
             else{
                    return false;
             }
       }
    }

    function deleteBKTUM($where='')
    {
       if($where !='') {
             $this->db->delete('ox_data_bkt_country_m', $where);
             $status	=$this->db->affected_rows();
             if($status >0){
                    return true;
             }
             else{
                    return false;
             }
            }
       }
       function deleteBKTH($where='')
       {
            if($where !='')
            {
                $this->db->delete('ox_data_summary_ad_hourly', $where);
                 $status	=$this->db->affected_rows();
                 if($status >0){
                        return true;
                 }
                 else{
                        return false;
                 }
            }
        }

	function deleteBKTAD($where='')
        {
	   if($where !='')
           {
		 $this->db->delete('ox_data_intermediate_ad', $where);
		 $status	=$this->db->affected_rows();

		 if($status >0){
		 	return true;
		 }
		 else{
		 	return false;
		 }
            }
	}

	function deleteZoneAssoc($where='')
        {
	   if($where !='')
           {
		$this->db->delete('ox_ad_zone_assoc', $where);
		$status	=$this->db->affected_rows();
		 if($status >0){
		 	return true;
		 }
		 else{
		 	return false;
		 }
            }
	 }
    /************** Delete Banner Data for Selected Campaign-End ****************/

    /* Delete Campaign */
    function delete_campaign($where_arr=0)
    {
        if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
        $this->db->delete('ox_campaigns');

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    /* Delete Campaign Budget */
    function delete_campaign_budget($where_arr=0)
    {
        if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
        $this->db->delete('oxm_budget');

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    /* Delete Campaign Targeting Features */
    function delete_targeting_limitation($where_arr=0)
    {
    	if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
        $this->db->delete('djx_targeting_limitations');

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    
    function delete_campaign_limitation($where_arr=0)
    {
    	if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
        $this->db->delete('djx_campaign_limitation');

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    
    /* Modify Campaign */
    function edit_campaign($edit_campaign,$where_camp=0)
    {
        if($where_camp!==0)
        {
                $this->db->where($where_camp);
        }
        if($this->db->update('ox_campaigns', $edit_campaign)>0)
        {
                //echo $this->db->last_query();
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    /* Modify Daily Budget for Created Campaigns */
    function edit_campaign_budget($edit_campaign_budget,$where_campbudget=0)
    {
        if($where_campbudget!==0)
        {
                $this->db->where($where_campbudget);
        }
        if($this->db->update('oxm_budget', $edit_campaign_budget)>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }

    /* De-Activate(or)Pause Campaign */
    function pause_campaign($camp_id)
    {
        $this->db->query("UPDATE ox_campaigns SET status='1', inactive='2' WHERE campaignid =".$camp_id);
        
        if($this->db->affected_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
    }

    /* Activate(or)Run Campaign */
    function run_campaign($camp_id)
    {
        $this->db->query("UPDATE ox_campaigns SET status='0' WHERE campaignid =".$camp_id);

        if($this->db->affected_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
    }

    /* Get Device OS List */
    function get_device_os()
    {
        $this->db->where('djx_device_os.os_status','1');

        $this->db->order_by('djx_device_os.os_platform','asc');

        $query = $this->db->get('djx_device_os');

        //echo $this->db->last_query();exit;

        return $query->result();
    }

    /* Get Device Manufacturer List */
    function get_device_manufacturer()
    {
        $this->db->where('djx_device_manufacturer.manufacturer_status','1');

        $this->db->order_by('djx_device_manufacturer.manufacturer_name','asc');

        $query = $this->db->get('djx_device_manufacturer');

        //echo $this->db->last_query();exit;

        return $query->result();
    }

    /* Get Device Capability List */
    function get_device_capabilty()
    {
        $this->db->where('djx_device_capability.capability_status','1');

        $this->db->order_by('djx_device_capability.capability_name','asc');

        $query = $this->db->get('djx_device_capability');

        return $query->result();
    }

    /* Get Geography/Operator List */
    function get_geo_operator()
    {
        $this->db->where('djx_telecom_circle.telecom_status','1');

        $this->db->order_by('djx_telecom_circle.telecom_name','asc');

        $query = $this->db->get('djx_telecom_circle');

        return $query->result();
    }

    /* Get Geography/Location List */
    function get_geo_location()
    {
        $this->db->order_by('djx_geographic_locations.code','asc');

        $query = $this->db->get('djx_geographic_locations');

        return $query->result();
    }

    /* Get Age Group List */
    function get_age_group()
    {
        $this->db->order_by('djx_client_profile.from','asc');

        $query = $this->db->get('djx_client_profile');

        return $query->result();
    }

    /* Check Campaign for Targeting Limitations */
    function check_targeting_campaign($where_camp)
    {
        if($where_camp!=0)
        {
            $this->db->where('djx_targeting_limitations.campaignid',$where_camp);
        }

        $query = $this->db->get('djx_targeting_limitations');
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    /* Get Geography/Operator List */
    function get_geo_operator_carrier()
    {
        $this->db->select('id,country,country_code,carriername');
		$this->db->order_by('djx_carrier_detail.country','asc');
		$query = $this->db->get('djx_carrier_detail');
		$countries=$query->result();
		return $countries;		
		
    }

    /* Campaign Targeting - Insert */
    function insert_targeting_limitation($target_features)
    {
        $this->db->insert('djx_targeting_limitations', $target_features);

        if($this->db->affected_rows()>0)
        {
                //echo $this->db->last_query();exit;
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }

    /* Campaign Targeting - Update */
    function update_targeting_limitation($target_features,$where_camp)
    {
        if($where_camp!==0)
        {
                $this->db->where($where_camp);
        }
        if($this->db->update('djx_targeting_limitations', $target_features)>0)
        {
                //echo $this->db->last_query();exit;
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }

    	/*Retreive the  Campaign Summary*/
	function  get_campaign_summary($campid=FALSE)
	{
		if($campid)
		{
			$select_arr	=		array('ox_campaigns.campaignname','ox_campaigns.activate_time','ox_campaigns.expire_time','ox_campaigns.status_startdate','ox_campaigns.status_enddate','oxm_budget.dailybudget');
			$this->db->select($select_arr);
			$this->db->join('oxm_budget','oxm_budget.campaignid=ox_campaigns.campaignid');
			$this->db->where('ox_campaigns.campaignid',$campid);
			$query	=		$this->db->get('ox_campaigns');
			return $query->result();
		}
	}

    /* Call Function for Start Date and Current Date Comparision */
    function compare_startdate_today($activate_time,$currenttime)
    {
        $starttime = explode(" ",$activate_time);
        $stime = explode("-",$starttime[0]);
        $ctime = explode('-',$currenttime);
        $start_date = mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
        $end_date = mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
        $data = $start_date-$end_date;
        $date_diff = floor($data/(60*60*24));
        return $date_diff;
    }

    /* Call Function for End Date and Current Date Comparision */
    function compare_enddate_today($expire_time,$currenttime)
    {
        $split_expire = explode(" ",$expire_time);
        $sdate = explode('-',$currenttime);
        $edate = explode('-',$split_expire[0]);
        $s_date = mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
        $e_date = mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
        $data = $e_date-$s_date;
        $date_diff = floor($data/(60*60*24));
        return $date_diff;
    }

    /* Checking Advertiser Account Balance */
    function check_advertiser_balance($clientid=0)
    {
        if($clientid!=0)
        {
                $this->db->where('clientid',$clientid);
        }

        $this->db->select('oxm_accbalance.accbalance');

        $query = $this->db->get('oxm_accbalance');
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    /* Get Campaign Status */
    function get_campaign_status($where_arr=0)
    {
    	$this->db->where('da_inventory_campaign_status.campaign_status_value',$where_arr);
        
        $this->db->select('da_inventory_campaign_status.status');

        $query = $this->db->get('da_inventory_campaign_status');

        $result = $query->result();

        return $result[0]->status;
    }

    /* Get Revenue Type */
    function get_revenue_type($where_arr=0)
    {
        if($where_arr!=0)
        {
                $this->db->where('da_inventory_revenue_type.revenue_type_value',$where_arr);
        }

        $this->db->select('da_inventory_revenue_type.revenue_type');

        $query = $this->db->get('da_inventory_revenue_type');

        $result = $query->result();

        return $result[0]->revenue_type;
    }

    /* Daily Campaign Budget  */
    function campaign_daily_budget($camp_id=0)
    {
        if($camp_id!=0)
        {
                $this->db->where('oxm_budget.campaignid',$camp_id);
        }

        $this->db->select('oxm_budget.dailybudget');

        $query = $this->db->get('oxm_budget');

        //echo $this->db->last_query();exit;

        return $query->result();
    }

    /* Campaign Amount */
    function campaign_amount($adv=0, $camp=0, $date=0)
    {
        if($adv!=0)
        {
                $this->db->where('oxm_report.clientid',$adv);
        }
        if($camp!=0)
        {
                $this->db->where('oxm_report.campaignid',$camp);
        }
        if($date!=0)
        {
                $this->db->where('oxm_report.date',$date);
        }

        $this->db->select('oxm_report.amount');

        $query = $this->db->get('oxm_report');

        //echo $this->db->last_query();exit;

        return $query->result();
    }

    /* Get Campaign Run/Pause Dates */
    function get_campaign($camp_id=0)
    {
        if($camp_id!=0)
        {
                $this->db->where('ox_campaigns.campaignid',$camp_id);
        }

        $query = $this->db->get('ox_campaigns');

        //echo $this->db->last_query();exit;

        return $query->result();
    }
    
    /* Get Count of Campaigns linking with Zones */
	function check_campaign_linking_count($campid)
	{
		$qry ="SELECT c.clientid, s.clientname, b.bannerid, b.description, b.campaignid, c.campaignname, b.width, b.height, z.zonename, z.revenue_type, z.width, z.height, z.zonename, z.zoneid, c.campaignid, c.revenue_type 
		FROM ox_campaigns c 
		JOIN ox_banners b 
		JOIN ox_zones z 
		JOIN ox_clients s 
			ON z.height = b.height AND z.width = b.width 
			WHERE c.campaignid = b.campaignid AND c.campaignid='".$campid."' AND c.revenue_type=z.revenue_type AND c.clientid = s.clientid AND (b.master_banner=-1 OR b.master_banner=-2 OR b.master_banner=-3) 
			GROUP BY c.campaignid";
			
		return $this->db->query($qry)->num_rows();
	}
	/* List Camapign Data */
	public function check_linked_campdata_count($campid=0, $limit=10, $offset=0)
	{
		$qry ="SELECT c.clientid, s.clientname, b.bannerid, b.description, b.campaignid, c.campaignname, b.width, b.height, z.master_zone, z.revenue_type, z.width, z.height, z.zonename, z.zoneid, c.campaignid, c.revenue_type 
		FROM ox_campaigns c 
		JOIN ox_banners b 
		JOIN ox_zones z JOIN ox_clients s 
			ON z.height =b.height AND z.width =b.width 
			WHERE c.campaignid =b.campaignid AND c.campaignid='".$campid."' AND c.revenue_type=z.revenue_type AND c.clientid =s.clientid AND (b.master_banner=-1 OR b.master_banner=-2 OR b.master_banner=-3) 
			GROUP BY c.campaignid LIMIT ".$offset.", ".$limit;
		
		$query = $this->db->query($qry);
		
		if($query->num_rows >0)
		{
			//return $query->num_rows;
			return $query->result();
		}
		else
		{
			return false;
		}
   	}
   	
   	/* Unlink Campaign Data */
   	function unlink_campaign_placement_zone_assoc($where_camp=0,$where_zone=0)
   	{
   		if($where_camp!==0 && $where_zone!=0)
		{
		        $this->db->where('placement_id', $where_camp);
		
			$this->db->where('zone_id', $where_zone);
		}
		
		$this->db->delete('ox_placement_zone_assoc');

		if($this->db->affected_rows()>0)
		{
		        return TRUE;
		}
		else
		{
		        return FALSE;
		}	
   	}
   	
   	/* Unlink Banner Data */
   	function unlink_campaign_ad_zone_assoc($where_banner=0,$where_zone=0)
   	{
   		if($where_banner!==0 && $where_zone!=0)
		{
		        $this->db->where('ad_id', $where_banner);
		
			$this->db->where('zone_id', $where_zone);
		}
		
		$this->db->delete('ox_ad_zone_assoc');

		if($this->db->affected_rows()>0)
		{
		        return TRUE;
		}
		else
		{
		        return FALSE;
		}	
   	}
   	
   	/* Check Delivery Targeting */
   	function check_delivery_targeting($where_camp=0)
   	{
   		if($where_camp!=0)
		{
		    $this->db->where('campaignid',$where_camp);
		}
		
		$query = $this->db->get('djx_campaign_limitation');

		return $query->num_rows();
   	}
   	
   	/* Add Banner Delivery Targeting */
    	function add_delivery_targeting($delivar_target)
    	{
    		$this->db->insert('djx_campaign_limitation', $delivar_target);

		if($this->db->affected_rows()>0)
		{
		        return $this->db->insert_id();
		}
		else
		{
		        return FALSE;
		}
    	}
    
    	/* Update Banner Delivery Targeting */
    	function update_delivery_targeting($delivar_target,$where_camp)
    	{
    		if($where_camp!==0)
		{
		        $this->db->where($where_camp);
		}
		if($this->db->update('djx_campaign_limitation', $delivar_target)>0)
		{
		        //echo $this->db->last_query();exit;
		        return TRUE;
		}
		else
		{
		        return FALSE;
		}    		
    	}


	/* Get Category list */
	function getCategory($where ='')
	{
		if($where !='')
		{
			$this->db->where($where);
		}
		
		$query 		=$this->db->get('djx_campaign_categories');
		
		if($query->num_rows >0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}



}