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

        $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

        $this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid');

        $this->db->order_by('ox_campaigns.campaignid','desc');

        $query = $this->db->get('ox_campaigns');

        return $query->result();
    }
    /* Retrieve Campaigns List */
    function get_campaigns($filter, $where_arr, $offset=0,$limit=20)
    {
            if($where_arr!=0)
            {
                    $this->db->where($where_arr);
            }

            $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

            $this->db->order_by('ox_campaigns.campaignid','desc');

            $this->db->order_by('ox_campaigns.weight','asc');

            $this->db->limit($limit, $offset);

    $query = $this->db->get('ox_campaigns');

    return $query->result();
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
    /* Modify Campaign */
    function edit_campaign($edit_campaign,$where_camp=0)
    {
        if($where_camp!==0)
        {
                $this->db->where($where_camp);
        }
        if($this->db->update('ox_campaigns', $edit_campaign)>0)
        {
                //echo $this->db->last_query();exit;
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
    	echo $edit_campaign_budget;exit;
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
        $this->db->query("UPDATE ox_campaigns SET status='1' WHERE campaignid =".$camp_id);
        
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

        $this->db->order_by('djx_telecom_circle.telecom_countrycode','asc');

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
    
    /* Get Campaign Status */
    function get_campaign_status($where_arr=0)
    {
    	$this->db->where('da_inventory_campaign_status.campaign_status_value',$where_arr);
        
        $this->db->select('da_inventory_campaign_status.status');

        $query = $this->db->get('da_inventory_campaign_status');

        $result = $query->result();

        return $result[0]->status;
    }
}
