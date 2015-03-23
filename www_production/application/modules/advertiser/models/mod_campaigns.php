<?php
class Mod_campaigns extends CI_Model
{ 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    /* Retrieve Campaign */
    function retrieve_campaigns($adv_id=0)
    {
        if($adv_id!=0)
        {
                $this->db->where($adv_id);
        }

	$this->db->where('ox_clients.clientid !=', '1');

        $this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');

        $this->db->join('oxm_budget', 'oxm_budget.campaignid = ox_campaigns.campaignid');

        $this->db->order_by('ox_campaigns.campaignid','desc');

        $query = $this->db->get('ox_campaigns');

		
		if($this->db->affected_rows()>0)
        {
			return $query->result();
		}
		else
		{
			return FALSE;
		}
    }
	
	
	/*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO  PROCESS FOR API REQUESTS
	 ---------------------------------------------------------------------------------*/


    /**
        *  This method is used to insert value in djx_campaign_limitation
      */

     function add_campaign_limitation($campid,$action)
       {
            $table = 'djx_campaign_limitation';
            $compiledlimitation = "device_location(a,b) AND redirect('$action','=~') AND demographics('all|a','=~')";
            $acl_plugins="device_location,redirect,demographics";
            $data  = array('campaignid'=>$campid,
                           'compiledlimitation'=>$compiledlimitation,
                           'acl_plugins'=>$acl_plugins,
                           'status'=>1  
                        );
	    $this->db->insert($table, $data);
	    if($this->db->affected_rows()>0)
	    {
        	  return TRUE;    
            }
            else
            {
        	 return FALSE;
            }
       }  



      /*
      *  This method is used to get Advertiser mobile number
     */

     function getAdvMobile($clientid)
     {
         if($clientid!=null)
         {
             $sql 	="SELECT mobileno FROM `oxm_userdetails` WHERE accountid=(select account_id from ox_clients where clientid=$clientid)";
	     $query = $this->db->query($sql);
	     if($query->num_rows()>0)
             {
                $result = $query->row();
                return $result->mobileno;
             }
             else
             {
                return false;
             }
         }
         else
         {
                return false;
         }
     }
 

      /**
        * This method is used to insert order details 
        */

        
        function updateCampaignStatus($orderId, $customerId, $status)
        {
	     $sql="UPDATE `ox_campaigns`  SET status='$status' WHERE  clientid=$customerId AND order_id=$orderId";
	     $query=$this->db->query($sql);
	     if($this->db->affected_rows()>0)
             {
                 return TRUE;
             }
             else
             {
                 return FALSE;
             }
         } 


       /**
        * This method is used to insert order details 
        */
       function addOrder($data)
       {
	    $table = 'merchant_order_table';
	    $this->db->insert($table, $data);
	    if($this->db->affected_rows()>0)
	    {
        	  return TRUE;    
            }
            else
            {
        	 return FALSE;
            }
        }

      /**
       * This function is used to generate an order ID 
       */
	function generateOrderId()
	{
		$sql="SELECT * FROM `merchant_order_generation_table` ";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
			$data= $query->result();
			$orderId=$data[0]->order_id_last_seq  + $data[0]->increment_by ;
			$sql="UPDATE `merchant_order_generation_table` SET order_id_last_seq='$orderId' WHERE starting_number= '".$data[0]->starting_number."' ";
			$query=$this->db->query($sql);
			if($this->db->affected_rows()>0)
			{
				return $orderId;
			}
			else
			{
				return FALSE;
			}

		}
		else
		{
			$starting_number = 100;
			$incr_by = 10;
			$sql="INSERT INTO `merchant_order_generation_table` (`starting_number`, `increment_by`, `order_id_last_seq`) VALUES ('".$starting_number."', '".$increment_by."', '".$starting_number."') ";
			$query=$this->db->query($sql);
			if($this->db->affected_rows()>0)
			{
				return $starting_number;
			}
			else
			{
				return FALSE;
			}
		}
	}



      public function getCampLocation($campid=0)
      {
           if($campid!=0)
           {
                 $sql = "SELECT lat, lon FROM `storefrontdata` WHERE id IN 
                         ( SELECT store_id FROM campaign_location_table WHERE campaign_id =$campid)";
                 $query = $this->db->query($sql);
                 if($query->num_rows>0)
                 {
                      $result = $query->row();
                      return $result;
                 }
                 else
                 {
                         return false;
                 }
                  
           }
           else
           {
                     return false;
           }
         
      }


    /**
     * This method is used to get all campaign data of given advertiser
     * @param $advid
     */ 

    public function getCampaignData($advid)
    {   
        $unpaid = 4;
        $this->db->where('clientid',$advid);
       // $this->db->where('status <>',$unpaid);
        $query  = $this->db->get('ox_campaigns');
        $res = false;
        if($query->num_rows>0)
        {
            $res = $query->result(); 

        }
        return $res;
    }

    function get_campaign_report_for_advertiser_datewise($search_array)
    {


        $result = array();

        $SQL = "SELECT
						date(h.date_time) as db_date,
						oxcl.clientid,
						ifnull(sum( h.impressions ),0) AS IMP,
						ifnull(sum( h.`clicks` ),0) AS CLICKS
						FROM ox_clients AS oxcl
						JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
						JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
						";

        if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

            $SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid AND date(h.date_time) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
        }
        else
        {
            $SQL .=  " JOIN ox_data_summary_ad_hourly AS h ON ( h.ad_id = oxb.bannerid)";
        }

        if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
            $SQL .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
        }

        $SQL .=" GROUP BY date(h.date_time)";


        $query = $this->db->query($SQL);

        if($query->num_rows>0)
        {

            $stat_summary =  $query->result_array();

            foreach($stat_summary as $data){
                $result[$data['db_date']]	=	array(
                    "IMP"=>$data['IMP'],
                    "CLK"=>$data['CLICKS'],
                    "CALL"=>0,
                    "WEB"=>0,
                    "MAP"=>0
                );
            }
        }

        $temp = $result;

        //GET BKT IMPRESSIONS

        $SQL_BKT_IMP = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS IMP
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


        if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

            $SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
        }
        else
        {
            $SQL_BKT_IMP .=  " JOIN ox_data_bkt_m AS odbm ON odbm.creative_id = oxb.bannerid";
        }

        if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
            $SQL_BKT_IMP .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
        }
        $SQL_BKT_IMP .=" GROUP BY DATE( odbm.interval_start )";
        $query1 = $this->db->query($SQL_BKT_IMP);

        if($query1->num_rows>0)
        {
            $stat_imp =  $query1->result_array();

            foreach($stat_imp as $data_imp){

                if(isset($temp[$data_imp['db_date']]['IMP']))
                {
                    $tot_imp		=	$data_imp['IMP'] + $temp[$data_imp['db_date']]['IMP'];
                    $result[$data_imp['db_date']]['IMP']	=$tot_imp;
                }
                else
                {
                    $tot_imp		=	$data_imp['IMP'];
                    $result[$data_imp['db_date']]['IMP']	=	$tot_imp;
                    $result[$data_imp['db_date']]['CLK']	=	0;
                    $result[$data_imp['db_date']]['CALL']	=	0;
                    $result[$data_imp['db_date']]['WEB']	=	0;
                    $result[$data_imp['db_date']]['MAP']	=	0;
                }
            }
        }

        $temp = $result;

        //GET BKT CLICKS

        $SQL_BKT_CLK = "SELECT DATE( odbm.interval_start ) AS db_date, oxcl.clientid, IFNULL( SUM( odbm.`count` ) , 0 ) AS CLK
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";

        if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

            $SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON (odbm.creative_id = oxb.bannerid AND date(odbm.interval_start) BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
        }
        else
        {
            $SQL_BKT_CLK .=  " JOIN ox_data_bkt_c AS odbm ON odbm.creative_id = oxb.bannerid";
        }
        if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
            $SQL_BKT_CLK .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
        }
        $SQL_BKT_CLK .=" GROUP BY DATE( odbm.interval_start )";
        $query1 = $this->db->query($SQL_BKT_CLK);

        if($query1->num_rows>0)
        {
            $stat_imp =  $query1->result_array();
            foreach($stat_imp as $data_imp){
                if(isset($temp[$data_imp['db_date']]['CLK'])){
                    $tot_clicks		=	$data_imp['CLK'] + $temp[$data_imp['db_date']]['CLK'];
                    $result[$data_imp['db_date']]['CLK']	=$tot_clicks;
                }
                else
                {
                    $tot_clicks		=	$data_imp['CLK'];
                    $result[$data_imp['db_date']]['IMP']	=	0;
                    $result[$data_imp['db_date']]['CLK']	=	$tot_clicks;
                    $result[$data_imp['db_date']]['CALL']	=	0;
                    $result[$data_imp['db_date']]['WEB']	=	0;
                    $result[$data_imp['db_date']]['MAP']	=	0;
                }
            }
        }
        $temp = $result;


        //GET SPEND OXM REPORT TABLE

        $SQL_BKT_SPEND = "SELECT DATE( oxmr.date ) AS db_date, oxcl.clientid, FORMAT(ifnull(sum( oxmr.`amount` ),0),2) AS SPEND,
							ifnull(sum(oxmr.click_to_call),0) AS 'CALL',
							ifnull(sum(oxmr.click_to_web),0) AS 'WEB',
							ifnull(sum(oxmr.click_to_map),0) AS 'MAP'
							FROM ox_clients AS oxcl
							JOIN ox_campaigns AS oxc ON oxc.clientid = oxcl.clientid
							JOIN ox_banners AS oxb ON oxb.campaignid = oxc.campaignid
							 ";


        if(count($search_array) > 0  AND isset($search_array['from_date']) AND isset($search_array['to_date']) AND $search_array['from_date'] != '' AND $search_array['to_date'] != ''){

            $SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON ( oxmr.bannerid = oxb.bannerid AND oxmr.date BETWEEN '".$search_array['from_date']."' AND '".$search_array['to_date']."' )";
        }
        else
        {
            $SQL_BKT_SPEND .=  " JOIN oxm_report AS oxmr ON  oxmr.bannerid = oxb.bannerid";
        }

        if(isset($search_array['sel_advertiser_id'])  AND $search_array['sel_advertiser_id'] != ''){
            $SQL_BKT_SPEND .=" WHERE oxc.clientid='".$search_array['sel_advertiser_id']."'";
        }

        $SQL_BKT_SPEND .=" GROUP BY DATE( oxmr.date )";

        $query1 = $this->db->query($SQL_BKT_SPEND);


        if($query1->num_rows>0)
        {
            $stat_imp =  $query1->result_array();

            foreach($stat_imp as $data_spend){
                $tot_call		=	$data_spend['CALL'];
                $tot_web		=	$data_spend['WEB'];
                $tot_map		=	$data_spend['MAP'];
                if(isset($temp[$data_spend['db_date']])){
                    $result[$data_spend['db_date']]['CALL']		=	$tot_call;
                    $result[$data_spend['db_date']]['WEB']		=	$tot_web;
                    $result[$data_spend['db_date']]['MAP']		=	$tot_map;
                }
                else
                {
                    $result[$data_spend['db_date']]['IMP']		=	0;
                    $result[$data_spend['db_date']]['CLK']		=	0;
                    $result[$data_spend['db_date']]['CON']		=	0;
                    $result[$data_spend['db_date']]['CALL']		=	$tot_call;
                    $result[$data_spend['db_date']]['WEB']		=	$tot_web;
                    $result[$data_spend['db_date']]['MAP']		=	$tot_map;

                }



            }

        }

        $temp = $result;


        $final_result 	= array();

        if(count($result) > 0){
            foreach($result as $key => $resObj){
                $t	=	array(
                    "date"=>strtotime($key),
                    "clientid"=>$search_array['sel_advertiser_id'],
                    "imp"=>$resObj['IMP'],
                    "click"=>$resObj['CLK'],
                    "cal"=>$resObj['CALL'],
                    "web"=>$resObj['WEB'],
                    "map"=>$resObj['MAP']
                );

                array_push($final_result,$t);
            }
        }

        $temp = $final_result;

        asort($final_result);



        return $final_result;
    }


    public  function getClickToAction($camp_id)
    {
        $this->db->where('campaignid',$camp_id);
        $this->db->select_sum('click_to_call');
        $this->db->select_sum('click_to_web');
        $this->db->select_sum('click_to_map');
        $query = $this->db->get('oxm_report');
        $res = false;
        if($query->num_rows>0)
        {
            $res = $query->row();
        }
        return $res;
    }
	function advExist($adv_id)
	{
		$this->db->where("clientid",$adv_id);
		$query  = $this->db->select('clientid')->get('ox_clients');

		if($query->num_rows >0)
			return true;
		else
			return false;
	}	
	
	 function getFund($adv_id){
		
		$this->db->where("clientid", $adv_id); 
		
		$query = $this->db->select('accbalance')->get('oxm_accbalance');
		
		if($query->num_rows >0)
		{
			$rs		=$query->row();
			$fund	=$rs->accbalance;
		}
		else
		{
			$fund	=FALSE;
		}
		return $fund;
    }

    function insert_fund($clientid, $amount)
    {
		if(!empty($amount)) {
		 
		 $data = array("clientid"=>$clientid,"accbalance"=>text_db($amount));
		 
		 $this->db->insert("oxm_accbalance",$data);
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	}
   
    function update_fund($clientid, $amount)
    {
		if(!empty($amount)) {
		 
		 $this->db->where("clientid", $clientid);
		 $data = array("accbalance"=>text_db($amount));
		 
		 $this->db->update("oxm_accbalance",$data);
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	} 



    function getCampDays($inr_price)
    {
        $this->db->where("inr_price", $inr_price);
		$query = $this->db->select('days_to_run,viewer')->get('merchant_promotion_plan');
		$rs = false;
		if($query->num_rows >0)
		{
			$rs		    = $query->row();
		}

		return $rs;

    }


    function getCampCPM()
    {
                
		$query = $this->db->select('usd_rate,cpm_rate')->get('merchant_usd_cpm_table');
		
		if($query->num_rows >0)
		{
			$rs		= $query->row();
				
		}
		else
		{
			$rs	=FALSE;
		}
		return $rs;

    }


/*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO FUND PROCESS FOR API REQUESTS  ENDS
	 ---------------------------------------------------------------------------------*/
	
	
	
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

    /* Get Campaign Status */
    function get_campaign_status($where_arr=0)
    {
        $this->db->where('da_inventory_campaign_status.campaign_status_value',$where_arr);
        
        $this->db->select('da_inventory_campaign_status.status');

        $query = $this->db->get('da_inventory_campaign_status');

        $result = $query->result();
        
        return $result[0]->status;
    }
  /* Retrieve Banners List */
    function get_total_liked_banners($camp_id=0)
    {
		if($camp_id!=0)
        {
			$sql 	="select * from ox_banners where campaignid=$camp_id   AND (master_banner =-1 OR master_banner =-2 OR master_banner =-3)";
			$query = $this->db->query($sql);
			return $query->num_rows();
          }
          return false;
	}
    /* Get Advertiser Account Balance */
    function get_account_balance($adv_id=0)
    {
        if($adv_id!=0)
        {
                $this->db->where('oxm_accbalance.clientid',$adv_id);
        }

        $this->db->select('oxm_accbalance.accbalance');

        $query = $this->db->get('oxm_accbalance');

        $result = $query->result();
	
	if(count($result)>0)
	{	
        	return $result[0]->accbalance;
        }
        else
        {
        	return 0;
        }
    }

    /* Get Campaign Amount */
    function get_campaign_amount($camp_id=0)
    {
        if($camp_id!=0)
        {
                $this->db->where('oxm_report.campaignid',$camp_id);
        }

        $this->db->select_sum('oxm_report.amount');

        $this->db->where('oxm_report.date',date("Y-m-d"));

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        if($result[0]->amount!='')
        {
            return $result[0]->amount;
        }
        else
        {
            return 0;
        }
    }

    /* Get Daily Campaign Budget */
    function get_campaign_budget($advertiser=0,$camp_id=0)
    {
        if($advertiser!=0)
        {
            $this->db->where('oxm_budget.clientid',$advertiser);
        }
        if($camp_id!=0)
        {
                $this->db->where('oxm_budget.campaignid',$camp_id);
        }

        $this->db->select('oxm_budget.dailybudget');

        $query = $this->db->get('oxm_budget');

        $result = $query->result();
        //echo $this->db->last_query();exit;

        if(count($result)>0)
        {
            return $result[0]->dailybudget;
        }
        else
        {
            return 0;
        }
    }

    /* Get Budget Impressions */
    function get_budget_impressions($adv=0,$camp=0)
    {
        if($adv!=0)
        {
            $this->db->where('ox_campaigns.clientid',$adv);
        }
        if($camp!=0)
        {
                $this->db->where('ox_banners.campaignid',$camp);
        }

        $this->db->select_sum('ox_data_bkt_m.count');

        $this->db->join('ox_banners', 'ox_banners.bannerid = ox_data_bkt_m.creative_id');

        $this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');

        $query = $this->db->get('ox_data_bkt_m');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        //print_r($result);

        return $result;
        
    }

    /* Get Budget Clicks */
    function get_budget_clicks($adv=0,$camp=0)
    {
        if($adv!=0)
        {
            $this->db->where('ox_campaigns.clientid',$adv);
        }
        if($camp!=0)
        {
                $this->db->where('ox_banners.campaignid',$camp);
        }

        $this->db->select_sum('ox_data_bkt_c.count');

        $this->db->join('ox_banners', 'ox_banners.bannerid = ox_data_bkt_c.creative_id');

        $this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');

        $query = $this->db->get('ox_data_bkt_c');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        return $result;
    }
	
	 /* Get Bucket Conversions */
    function get_bucket_conversions($adv=0,$camp=0)
    {
        if($adv!=0)
        {
            $this->db->where('ox_campaigns.clientid',$adv);
        }
        if($camp!=0)
        {
                $this->db->where('ox_banners.campaignid',$camp);
        }

        $this->db->select('ox_data_bkt_a.server_conv_id');

        $this->db->join('ox_banners', 'ox_banners.bannerid = ox_data_bkt_a.creative_id');

        $this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');

        $query = $this->db->get('ox_data_bkt_a');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        return $result;
    }

    /* Get Common Stats for Impression and Clicks */
    function get_common_stats($campid=0)
    {
        if($campid!=0)
        {
                $this->db->where('ox_banners.campaignid',$campid);
        }

        $this->db->select('sum(ox_data_summary_ad_hourly.total_revenue) as total,sum(ox_data_summary_ad_hourly.impressions) as impress,sum(ox_data_summary_ad_hourly.clicks) as clicks,sum(ox_data_summary_ad_hourly.conversions ) as conversions');

        $this->db->join('ox_data_summary_ad_hourly', 'ox_data_summary_ad_hourly.ad_id = ox_banners.bannerid');

        $query = $this->db->get('ox_banners');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        return $result;
    }

    /* Change Campaign Status */
    function change_campaign_status($campid=0)
    {
        $this->db->query("update ox_campaigns set inactive= !inactive where campaignid =".$campid);
        
        if($this->db->affected_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
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
        return $query->result();
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
		if($this->db->affected_rows()>0)
		{
			return $query->result();
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

    /* Check Campaign Status */
    function check_campaign_status($camp_id)
    {
        if($camp_id!=0)
        {
                $this->db->where('ox_campaigns.campaignid',$camp_id);
        }

        $this->db->select('ox_campaigns.status,ox_campaigns.inactive');

        $query = $this->db->get('ox_campaigns');

        //echo $this->db->last_query();exit;

        return $query->result();
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

    /************** Delete Banner Data for Selected Campaign-Start ****************/
    function delete_banner($where_arr=0,$where_master=0)
    {
        if($where_arr!==0)
        {
                $this->db->where($where_arr);
        }
		if($where_master!=0)
		{
			$this->db->or_where($where_master);
		}
        $this->db->delete('ox_banners');

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
    	$campid=0;
    	if($where_arr!==0)
        {
                $this->db->where($where_arr);
                $campid=$where_arr['campaignid'];
        }
        $this->db->delete('djx_targeting_limitations');

        if($campid!=0)
        {
        	$this->db->where('camp_id',$campid);
        	$this->db->delete('djx_intermediate_landing');
        }
        
        
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
    
    
    function check_landing_campaign($campid)
    {
    	if($campid!=0)
    	{
    		$this->db->where('djx_intermediate_landing.camp_id',$campid);
    	}
    	
    	$query = $this->db->get('djx_intermediate_landing');
    	
    	//echo $this->db->last_query();exit;
    	if($query->num_rows()>0)
    	{
    		//echo $this->db->last_query();exit;
    		return $query->result();
    	}
    	else
    	{
    		return FALSE;
    	}
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
       if($query->num_rows()>0)
        {
                //echo $this->db->last_query();exit;
               return $query->result();
        }
        else
        {
                return FALSE;
        }
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

    /* intermediary landing -Update Added by shyam  */
    
    function update_rectbanner($row,$where_data)
    {
    	if($where_data!='')
    	{
    		$this->db->where($where_data);
    	}
    	if($this->db->update('djx_intermediate_landing',$row)>0)
    	{
    		
    		return TRUE;
    	}
    	else
    	{
    		return FALSE;
    	}
    }
    
    /* intermediary landing -insert Added by shyam  */
    
    function insert_rectbanner($row,$campid)
    {
    	static $count=0;

   	 	if(!empty($row))
		{
			// Empty the table before inserting new records
			if($count==0)
			{
				$this->db->where('camp_id',$campid);
				$this->db->delete('djx_intermediate_landing');
				$count=$count+1;
			}

			$this->db->insert('djx_intermediate_landing',$row);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		}
    	
    	
    }
    /*  ************ function to get rect banner sizes ***************  */ 
    
    function get_rectbanner_sizes($rectbanner='')
    {
    	if($rectbanner!='')
    	{
    		$this->db->where('rectbanner',$rectbanner);
    	}
    	
    	$query     =$this->db->get('oxm_rectbannersizes');
    	
    	if($query->num_rows>0)
    	{
            return $query->result();		
    	}
    	else
    	{
    		return false;
    	}
    }
   
    /* intermediary landing -Retrieve records Added by shyam  */
    
    
    function get_intermediate_landing($campid='')
    {
    	
    	if($campid !='')
    	{
    		$this->db->where('camp_id',$campid);
    	}
    	
    	$query = $this->db->get('djx_intermediate_landing');

        if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return 0;
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
	
	function duplicate_placement_zone_assoc($_old_id,$_new_id){
		// GET previous Placement Zone Association for selected campaigns
		$this->db->select("*");
		$this->db->where("placement_id",$_old_id);
		$query = $this->db->get("ox_placement_zone_assoc");
		$result	=	$query->result();
		
		foreach($result as $obj){
			$this->db->insert("ox_placement_zone_assoc",array("zone_id"=>$obj->zone_id,"placement_id"=>$_new_id));
		}
		return TRUE;
	}
	
	function duplicate_targetting($_old_id,$_new_id){
		// GET previous Placement Zone Association for selected campaigns
		$this->db->select("*");
		$this->db->where("campaignid",$_old_id);
		$query = $this->db->get("djx_targeting_limitations");
		$result	=	$query->result();
		foreach($result as $target){
			$target_data =		array(
										'devices' 		=>	$target->devices, 
										'locations'		=>	$target->locations, 
										'operators' 	=>	$target->operators, 
										'ages' 			=>	$target->ages, 
										'gender' 		=>	$target->gender, 
										'device_type' 	=>	$target->device_type, 
										'location_type' =>	$target->location_type, 
										'operator_type' =>	$target->operator_type, 
										'gender_type' 	=>	$target->gender_type, 
										'ages_type' 	=>	$target->ages_type, 
										'model' 		=>	$target->model, 
										'model_type' 	=>	$target->model_type,
										'manufacturer'	=>  $target->manufacturer,
										'capability'    =>	$target->capability,
										'manufacturer_type' => $target->manufacturer_type,
										'capability_type'  => $target->capability_type,
										'campaignid' 	=>	$_new_id
								);
									
			$this->db->insert("djx_targeting_limitations",$target_data);
		}
		return TRUE;
	}
	
	function duplicate_campaign_limitations($_old_id,$_new_id){
		
		// GET PREVIOUS CAMPAIGNS LIMITATIONS
		
		$this->db->select("*");
		$this->db->where("campaignid",$_old_id);
		$query = $this->db->get("djx_campaign_limitation");
		$result	=	$query->result();
		foreach($result as $obj){
			$limitations_data =		array(
										'compiledlimitation'	=>	$obj->compiledlimitation, 
										'acl_plugins'			=>	$obj->acl_plugins, 
										'status' 				=>	$obj->status, 
										'campaignid' 			=>	$_new_id
								);
			$this->db->insert("djx_campaign_limitation",$limitations_data);
		}
		return TRUE;
	}
	
	function duplicate_banners($_old_id,$_new_id){
		
		// GET PREVIOUS BANNETS
		
		$this->db->select("*");
		$this->db->where("campaignid",$_old_id);
		$this->db->where_in("master_banner",array(-1,-2,-3));
		$query = $this->db->get("ox_banners");
		$result	=	$query->result();
		foreach($result as $obj){

			$banner_data=array(
					'campaignid'		=>	$_new_id,
					'contenttype'		=>	$obj->contenttype,
					'pluginversion'		=>	$obj->pluginversion,
					'storagetype'		=>	$obj->storagetype,
					'filename'			=>	$obj->filename,
					'imageurl'			=>	$obj->imageurl,
					'htmltemplate'		=>	$obj->htmltemplate,
					'htmlcache'			=>	$obj->htmlcache,
					'width'				=>	$obj->width,
					'height'			=>	$obj->height,
					'weight'			=>	$obj->weight,
					'seq'				=>	$obj->seq,
					'url'				=>	$obj->url,
					'alt'				=>	$obj->alt,
					'statustext'		=>	$obj->statustext,
					'bannertext'		=>	$obj->bannertext,
					'description'		=>	$obj->description,
					'adserver'			=>	$obj->adserver,
					'block'				=>	$obj->block,
					'capping'			=>	$obj->capping,
					'session_capping'	=>	$obj->session_capping,
					'compiledlimitation'=>	$obj->compiledlimitation,
					'acl_plugins'		=>	$obj->acl_plugins,
					'append'			=>	$obj->append,
					'bannertype'		=>	$obj->bannertype,
					'alt_contenttype'	=>	$obj->alt_contenttype,
					'comments'			=>	$obj->comments,
					'updated'			=>	$obj->updated,
					'acls_updated'		=>	$obj->acls_updated,
					'parameters'		=>	$obj->parameters,
					'an_banner_id'		=>	$obj->an_banner_id,
					'ext_bannertype'	=>	$obj->ext_bannertype,
					'master_banner'		=>	$obj->master_banner,
					'adminstatus'		=>	$obj->adminstatus
					);
			$this->db->insert("ox_banners",$banner_data);
			
			$new_banner_id	=	$this->db->insert_id();
			
			//UPDATE AD ZONE ASSOCIATION
			$this->db->where("ad_id",$obj->bannerid);
			$ad_query=$this->db->get('ox_ad_zone_assoc');
			
			if($ad_query->num_rows()>0)
			{
						foreach($ad_query->result() as $adquery)
						{
							
							$ad_data=array(
										'zone_id'			=>	$adquery->zone_id,
										'ad_id'				=>	$new_banner_id,
										'priority'			=>	$adquery->priority,
										'link_type'			=>	$adquery->link_type,
										'priority_factor'	=>	$adquery->priority_factor,
										'to_be_delivered'	=>	$adquery->to_be_delivered
							);
							
							$this->db->insert("ox_ad_zone_assoc",$ad_data);
						}	
			}
			
		  //DUPLICATE IT'S CHILD BANNERS
			
		  if($obj->master_banner == -2){ // IMAGE BANNER
		  
		  		$this->db->select("*");
				$this->db->where("campaignid",$_old_id);
				$this->db->where_in("master_banner",$obj->bannerid);
				$query = $this->db->get("ox_banners");
				$result	=	$query->result();
				foreach($result as $chobj){
		
					$banner_data=array(
							'campaignid'		=>	$_new_id,
							'contenttype'		=>	$chobj->contenttype,
							'pluginversion'		=>	$chobj->pluginversion,
							'storagetype'		=>	$chobj->storagetype,
							'filename'			=>	$chobj->filename,
							'imageurl'			=>	$chobj->imageurl,
							'htmltemplate'		=>	$chobj->htmltemplate,
							'htmlcache'			=>	$chobj->htmlcache,
							'width'				=>	$chobj->width,
							'height'			=>	$chobj->height,
							'weight'			=>	$chobj->weight,
							'seq'				=>	$chobj->seq,
							'url'				=>	$chobj->url,
							'alt'				=>	$chobj->alt,
							'statustext'		=>	$chobj->statustext,
							'bannertext'		=>	$chobj->bannertext,
							'description'		=>	$chobj->description,
							'adserver'			=>	$chobj->adserver,
							'block'				=>	$chobj->block,
							'capping'			=>	$chobj->capping,
							'session_capping'	=>	$chobj->session_capping,
							'compiledlimitation'=>	$chobj->compiledlimitation,
							'acl_plugins'		=>	$chobj->acl_plugins,
							'append'			=>	$chobj->append,
							'bannertype'		=>	$chobj->bannertype,
							'alt_contenttype'	=>	$chobj->alt_contenttype,
							'comments'			=>	$chobj->comments,
							'updated'			=>	$chobj->updated,
							'acls_updated'		=>	$chobj->acls_updated,
							'parameters'		=>	$chobj->parameters,
							'an_banner_id'		=>	$chobj->an_banner_id,
							'ext_bannertype'	=>	$chobj->ext_bannertype,
							'master_banner'		=>	$new_banner_id,
							'adminstatus'		=>	$chobj->adminstatus
							);
							
					$this->db->insert("ox_banners",$banner_data);
					
					$new_child_banner_id	=	$this->db->insert_id();
					
					//UPDATE AD ZONE ASSOCIATION
					$this->db->where("ad_id",$chobj->bannerid);
					$ad_query=$this->db->get('ox_ad_zone_assoc');
					
					if($ad_query->num_rows()>0)
					{
								foreach($ad_query->result() as $adquery)
								{
									
									
									$ad_data=array(
												'zone_id'			=>	$adquery->zone_id,
												'ad_id'				=>	$new_child_banner_id,
												'priority'			=>	$adquery->priority,
												'link_type'			=>	$adquery->link_type,
												'priority_factor'	=>	$adquery->priority_factor,
												'to_be_delivered'	=>	$adquery->to_be_delivered
									);
									
									$this->db->insert("ox_ad_zone_assoc",$ad_data);
								}	
					}
		  
		 	 }	
		}
	}
	
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
	
	/* Get Campaign RTB */
	function get_campaign_rtb($camp_id=0)
	{
		if($camp_id!=0)
		{
		        $this->db->where('ox_campaigns.campaignid',$camp_id);
		}
		
		$this->db->select('rtb');

		$query = $this->db->get('ox_campaigns');

		//echo $this->db->last_query();exit;
		
		$result = $query->result();

		return $result[0]->rtb;
	}
	
	 /* Check Campaign for Targeting Limitations */
    function check_rtb_targeting_campaign($where_camp)
    {
        if($where_camp!=0)
        {
            $this->db->where('aff_rtb.campaign_id',$where_camp);
        }
		$this->db->join('djx_targeting_limitations','djx_targeting_limitations.campaignid=aff_rtb.campaign_id','left');
		
        $query = $this->db->get('aff_rtb');
        
        //echo $this->db->last_query();exit;
        return $query->result();
    }
    
    /* Campaign Targeting - Update */
    function update_rtb_targeting_limitation($target_features,$where_camp)
    {
        if($where_camp!==0)
        {
                $this->db->where($where_camp);
        }
        if($this->db->update('aff_rtb', $target_features)>0)
        {
                //echo $this->db->last_query();exit;
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    
    /*** Check Delivery Targeting */
   	function check_rtb_delivery_targeting($where_camp=0)
   	{
   		if($where_camp!=0)
		{
		    $this->db->where('aff_bid_acls.campaign_id',$where_camp);
		}
		
		$this->db->join('djx_campaign_limitation','djx_campaign_limitation.campaignid=aff_bid_acls.campaign_id');
		
		$query = $this->db->get('aff_bid_acls');

		return $query->num_rows();
   	}
   	
   	/* Update Banner Delivery Targeting */
    	function update_rtb_delivery_targeting($delivar_target,$where_camp)
    	{
    		if($where_camp!==0)
		{
		        $this->db->where($where_camp);
		}
		if($this->db->update('aff_bid_acls', $delivar_target)>0)
		{
		        //echo $this->db->last_query();exit;
		        return TRUE;
		}
		else
		{
		        return FALSE;
		}    		
    	}
    	
    	/* Add Banner Delivery Targeting */
    	function add_rtb_delivery_targeting($delivar_target)
    	{
    		$this->db->insert('aff_bid_acls', $delivar_target);

		if($this->db->affected_rows()>0)
		{
		        return $this->db->insert_id();
		}
		else
		{
		        return FALSE;
		}
    	}
    	
    	 /* Campaign Targeting - Insert */
    function insert_rtb_targeting_limitation($target_features)
    {
        $this->db->insert('aff_rtb', $target_features);

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

	function get_region_operator($country=false)
	 {
		 if($country!=false)
		 {
			 $this->db->where('country', $country);
		 }
		 $this->db->order_by('carriername','ASC');
		 $query = $this->db->get('ox_carrier_detail');
		 
		 if($query->num_rows()>0)
		 {
			 return $query->result();
		 }
		else
		{
			return FALSE;
		}
	 }
	 
	  /* Get Geography/Operator List */
    function get_geo_operator_carrier()
    {
        $this->db->select('id,country,carriername');
        $this->db->group_by('country');
		$this->db->order_by('ox_carrier_detail.country','asc');
		$query = $this->db->get('ox_carrier_detail');
		$countries=$query->result();
		return $countries;		
	 }

}

/*----------------------------------------- Added by Soumya -----------------*/

	 /* function to get the page details */
	 function prox_page_details()
	 {
		$sql="SELECT * FROM `PROXIMITY_IMAGES` ";
		$query=$this->db->query($sql);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
	 }
	 
	 /* Add Proximity Campaign */
	function add_prox_camp($data,$path)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="INSERT INTO `AZ_PROXIMITY_CAMPAIGN`
			 (`client_id`,`camp_name`,`activate_time`,`end_time`,`pricing_model`,`message`,`budget`,`landing_image`,`weight`,`status_startdate`,`status_enddate`) 
			 VALUES ($clientid,'$data[campname]','$data[campstart]','$data[campend]','$data[pricing_model]','$data[message]','$data[budget]','$path','$data[weight]','$data[start_date]','$data[end_date]')";
		
		//echo $sql;
		$query=$this->db->query($sql);
		return $query;
	}
	
	/* Function to get the prox camp_list */
	function prox_camp_list($clientid)
	{
		$sql="SELECT * FROM `AZ_PROXIMITY_CAMPAIGN` WHERE client_id=$clientid ";
		//echo $sql;
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
			 return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	/*Page image detail list acc. to client for page 4 */
	function prox_page_list()
	 {
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="SELECT * FROM `PROX_IMAGES` WHERE client_id=$clientid";
		$query=$this->db->query($sql);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
	 }
	 
	/* 
	 * To update page images details 
	 */ 
	function update_page($data)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$flag=0;
		//print_r($data);
		for($i=0;$i<count($data);$i++)
		{
			$page=$data[$i]->page;
			$path=$data[$i]->path;
			if($data[$i]->image==1)
			{
				$sql1="UPDATE `PROX_IMAGES`  SET image1='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query1=$this->db->query($sql1);
				
				if($query1==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==2)
			{
				$sql2="UPDATE `PROX_IMAGES`  SET image2='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query2=$this->db->query($sql2);
				if($query2==FALSE)
				{
					$flag=1;
				}
			}
			else 
			if($data[$i]->image==3)
			{
				$sql3="UPDATE `PROX_IMAGES`  SET image3='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query3=$this->db->query($sql3);
				if($query3==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==4)
			{
				$sql4="UPDATE `PROX_IMAGES`  SET image4='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query4=$this->db->query($sql4);
				if($query4==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==5)
			{
				$sql5="UPDATE `PROX_IMAGES`  SET image5='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query5=$this->db->query($sql5);
				if($query5==FALSE)
				{
					$flag=1;
				}
			}
		}
	}
	
	/* 
	 * To get proximity campaign details for editing
	 */
	function get_prox_camp($id)
	{
		$sql="SELECT * FROM `AZ_PROXIMITY_CAMPAIGN` WHERE campaign_id=$id";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
			 return $query->result();
		}
		else
		{
			return FALSE;
		}	
	}
	
	/* Update the proximity camp after edit process */
	function update_prox_camp($id,$data)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="REPLACE INTO `AZ_PROXIMITY_CAMPAIGN`
			 (`campaign_id`,`client_id`,`camp_name`,`activate_time`,`end_time`,`pricing_model`,`message`,`budget`,`landing_image`,`weight`,`status_startdate`,`status_enddate`) 
			 VALUES ('$id',$clientid,'$data[campname]','$data[campstart]','$data[campend]','$data[pricing_model]','$data[message]','$data[budget]','$path','$data[weight]','$data[start_date]','$data[end_date]') ";
			 
		$query=$this->db->query($sql);
		return $query;

	}
	
	/* Delete Proximity campaigns */
	function delete_prox_camp($id)
	{
		$this->db->where('campaign_id', $id);
		$this->db->delete('AZ_PROXIMITY_CAMPAIGN');
	}
	
	/* To add keywords to campaign */
	function add_keywords($data)
	{
		 $this->db->insert('Keywords_targetting', $data);

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
    
   /* To get networks mix for campaign optimization */
	function camp_optimization_list()
	{
		$sql="SELECT * FROM `Campaign_Optimize_Mix` ";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	}

	/* To insert the target impressions for each network  */
	function enter_target_impressions($camp,$mixId,$impression)
	{
		$sql="REPLACE INTO `Campaign_Target_Impressions_Mix` ( `Campaign_id`, `Mix_id`, `Target_impressions`, `Impressions_served`, `Clicks`)
			 VALUES ('".$camp."', '".$mixId."', '".$impression."', '0', '0')";
		$query=$this->db->query($sql);	 
		
        if($this->db->affected_rows()>0)
        {
                //echo $this->db->last_query();exit;
                echo "true";
                return TRUE;
        }
        else
        {
                return FALSE;
        }
	}
	
	/* Delete keywords for selected campaign */
	function delete_camp_keywords($where=' ')
	{
		if($where !=0) 
		{
			$this->db->delete('Keywords_targetting', $where);
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
      
      /* To delete the target impressions for each network  */
      function deleteTargetImpression($where=' ')
      {
		  if($where !=0) 
		{
			$this->db->delete('Campaign_Target_Impressions_Mix', $where);
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
      
      /* To get the keywords for specific campaign */
      function get_keywords_list($camp_id)
      {
		 $sql="SELECT * FROM `Keywords_targetting` WHERE camp_id=$camp_id " ;
		 $query=$this->db->query($sql);
		 if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	  }
	  
	  /* Update Target Impressions */
	  function update_target_impressions($camp,$mixId,$impression)
	{
		$sql=" UPDATE `Campaign_Target_Impressions_Mix` SET Target_impressions=$impression 
			   WHERE Campaign_id=$camp AND Mix_id=$mixId ";
		$query=$this->db->query($sql);	 
		
        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
	}
	
	/* *
	 * author:Soumya
	 * This method is used to update campaign details in case of compliance status
	 * */
	 function update_campaign_details($camp_id,$data)
	 {
		if($camp_id!==0)
		{
		         $this->db->where('ox_campaigns.campaignid',$camp_id);
		}
		if($this->db->update('ox_campaigns', $data)>0)
		{
		        //echo $this->db->last_query();exit;
		        return TRUE;
		}
		else
		{
		        return FALSE;
		}    	
	 }
	 
	 


?>
