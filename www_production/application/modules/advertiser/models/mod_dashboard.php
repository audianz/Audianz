<?php
class Mod_dashboard extends CI_Model 
{
		function __construct()
        {
            // Call the Model constructor
            parent::__construct();
        }
        /*Spend on Day Basis */
        function amount_spend($adv_id='',$where_arr=0)
        {
			if($where_arr!=0 && $adv_id !='')
            {
               	$this->db->where($where_arr);
				$this->db->where("ox_campaigns.clientid",$adv_id);
            }
			$this->db->select('sum(oxm_report.amount) as amount_spend');
			$this->db->from('oxm_report');
			$this->db->join('ox_banners','ox_banners.bannerid = oxm_report.bannerid');
			$this->db->join('ox_campaigns','ox_campaigns.campaignid= oxm_report.campaignid');
			$query = $this->db->get();
			return $query->result();
		}
		/* Revenue on Current Month */
		function month_revenue($where_arr=0,$where_pub=0)
        {
            if($where_arr!=0)
            {
                 $this->db->where("MONTH(date)",$where_arr);
            }
            
            if($where_pub!=0)
            {
                 $this->db->where($where_pub);
            }

            $this->db->select('sum(oxm_report.publisher_amount) as month_rev');

            $this->db->join('oxm_report','oxm_report.zoneid = ox_zones.zoneid');

            $query = $this->db->get('ox_zones');
            
            //echo $this->db->last_query();exit;

            return $query->result();
        }
  
  		    /* Retrieve Campaign */
    function retrieve_campaign_list($adv_id ='',$camp_id=0)
    {
		if($adv_id !='')
		{
        if($camp_id!=0)
        {
                $this->db->where($camp_id);
        }

        $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

        $this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid');
	
		$this->db->where('ox_clients.clientid',$adv_id);	
			
        $this->db->order_by('ox_campaigns.campaignid','desc');

        $query = $this->db->get('ox_campaigns');

        return $query->result();
    	
		}else{
			exit;
		}
	}

    /* Retrieve Banners List */
    function getBanners($adv_id ='',$where_arr=0)
    {
		if($adv_id !='')
		{
        	if($where_arr!=0)
        	{
                $this->db->where($where_arr);
        	}
        
        	$this->db->select('ox_banners.bannerid');
	
			$this->db->from('ox_banners');
	
    		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');
			
			$this->db->join('ox_clients','ox_clients.clientid= ox_campaigns.clientid');
			    
			$this->db->where('ox_clients.clientid',$adv_id);
			
			$query = $this->db->get();

        	return $query->result();
		}else
		{
			echo 'Not a Valid Advertiser id';
			exit;
		}
    }
  
}
