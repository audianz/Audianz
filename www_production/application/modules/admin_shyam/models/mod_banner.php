<?php
class Mod_banner extends CI_Model 
{ 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    /* Get Revenue Type */
	function get_revenue_type($rid=0)
	{
            	if($rid!=0)
        {
                $this->db->where("revenue_type_value",$rid);
        }

		$this->db->select('revenue_type');
		
		$query = $this->db->get('da_inventory_revenue_type');

                $result = $query->result();                
		
		return $result[0]->revenue_type;
	}
	/* Retrieve Banners List */
    function get_banners($where_arr=0, $offset=0,$limit=FALSE)
    {
			$master_in = array('-1','-2','-3');
			
            if($where_arr!=0)
            {
                    $this->db->where($where_arr);
            }
			$this->db->where_in('ox_banners.master_banner', $master_in);
			
			$this->db->select('ox_banners.*, ox_campaigns.*, ox_data_bkt_m.count as bktm, ox_data_summary_ad_hourly.impressions as adhm, ox_data_bkt_c.count as bktc, ox_data_summary_ad_hourly.clicks as adhc, ox_data_summary_ad_hourly.total_revenue, ox_banners.status as banstatus');
			
			$this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');
			
			$this->db->join('oxm_accbalance','oxm_accbalance.clientid = ox_campaigns.clientid','left');	
			
			$this->db->join('ox_data_bkt_m', 'ox_data_bkt_m.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_bkt_c', 'ox_data_bkt_c.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_bkt_a', 'ox_data_bkt_a.creative_id = ox_banners.bannerid','left');	
			
			$this->db->join('ox_data_summary_ad_hourly', 'ox_data_summary_ad_hourly.ad_id = ox_banners.bannerid','left');

			$this->db->group_by('ox_banners.bannerid');
			
			$this->db->order_by('ox_banners.bannerid','desc');
		
            $this->db->order_by('ox_data_summary_ad_hourly.total_revenue','desc');

		if($limit!=0)
		{
		    #$this->db->limit($limit, $offset);
		}
	    $query = $this->db->get('ox_banners');

		if($query->num_rows()>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}

	    
    }
    
    /* Retrieve Banners List */
    function get_banners_count()
    {
			$master_in = array('-1','-2','-3');
			            
			$this->db->where_in('ox_banners.master_banner', $master_in);
			
			$query = $this->db->get('ox_banners');

			if($query->num_rows()>0)
			{
				return $query->num_rows();
			}
			else
			{
				return FALSE;
			}
	}
    
	/* Check Banner Name Duplication for Selected Campaign ID */
	function check_banner_duplication($where_arr=0,$where_not_in=0)
	{
		if($where_arr!=0)
        {
            $this->db->where($where_arr);
        }
		
		if($where_not_in!=0)
		{
			$this->db->where_not_in('bannerid',$where_not_in);
		}
        
		if($where_not_in!=0)
		{
			$this->db->where_not_in('master_banner',$where_not_in);
		}
		
        $query = $this->db->get('ox_banners');
		
		//echo $this->db->last_query();exit;	
		
        if($query->num_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
	}

	function camp_status($where)
	{
		$this->db->select('status');
		$this->db->where($where);
		$query	=	$this->db->get('ox_campaigns');
		if($query->num_rows()>0)
		{
		   	$result= $query->result();
			return $result[0]->status;
		}
		else
		{
		  	return FALSE;
		}

	}
	
	/* Get Campaign List using Advertisre ID */
    function filter_campaigns($where_arr)
    {
        if($where_arr!=0)
        {
            $this->db->where($where_arr);
        }
		
		$this->db->select('campaignid,campaignname');
        
        $query = $this->db->get('ox_campaigns');

        return $query->result();
    }
	
	/* Get Banner Content for Edit/Modification */
	function edit_banner($where_arr=0)
    {
        if($where_arr!=0)
        {
            $this->db->where($where_arr);
        }
		
		$this->db->select('ox_banners.*,ox_campaigns.clientid as clid,ox_campaigns.campaignid as cmid');
		
		$this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');
		
		$this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');
		
        $query = $this->db->get('ox_banners');

        //echo $this->db->last_query();exit;
		
		return $query->result();
    }
	
	/* Update/Modify Banners */
	function update_banner($txt_banner_arr,$where_txt_banner=0)
	{	
		if($where_txt_banner!==0)
		{
			$this->db->where($where_txt_banner);
		}
		if($this->db->update('ox_banners', $txt_banner_arr)>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
	
	/* De-Activate(or)Pause Banners */
    function pause_banner($bid)
    {
        $this->db->query("UPDATE ox_banners SET status='1' WHERE bannerid =".$bid);
        
        if($this->db->affected_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
    }
	
	/* Activate(or)Run Banners */
    function run_banner($bid)
    {
        $this->db->query("UPDATE ox_banners SET status='0' WHERE bannerid =".$bid);
        
        if($this->db->affected_rows()>0)
        {
           return TRUE;
        }
        else
        {
          return FALSE;
        }
    }
	
	/* Add Banner */		
	function add_banner($data)
	{	
		if(!empty($data)) 
		{		
		 	$status = $this->db->insert("ox_banners",$data);
			
			//echo $this->db->last_query();
			
			if($status)
			{
				return $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}
	}

    /* Check Master Banner */
    function check_master_banner($bid=0)
    {
        if($bid!=0)
        {
            $this->db->where('ox_banners.bannerid',$bid);
        }

        $this->db->where('master_banner', '-2');

        $query = $this->db->get('ox_banners');

        //echo $this->db->last_query();exit;

        //return $query->result();
        return $query->num_rows();
    }

    /* Get Child Banners */
    function get_child_banners($bid=0)
    {
        if($bid!=0)
        {
            $this->db->where('ox_banners.master_banner',$bid);
        }

        $this->db->select('ox_banners.bannerid');

        $query = $this->db->get('ox_banners');

        //echo $this->db->last_query();exit;

        return $query->result();

    }

    function delete_banner_reports($where_arr=0)
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

    /* Delete Banner */
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
	
	/* Map Banner with Zone using Ad Zone Association  */
	function map_zone_tabletbanner($zoneid=0, $parentid=0)
	{
		echo "select b.width,b.height,c.revenue_type,z.height,z.zoneid,z.width,z.revenue_type 
		from ox_campaigns c join ox_banners b join ox_zones z on z.width=b.width and z.height=b.height and c.revenue_type=z.revenue_type 
		where z.zoneid='$zoneid' and b.bannerid='$parentid'";exit;
		
		$query = mysql_query(
		"select b.width,b.height,c.revenue_type,z.height,z.zoneid,z.width,z.revenue_type 
		from ox_campaigns c join ox_banners b join ox_zones z on z.width=b.width and z.height=b.height and c.revenue_type=z.revenue_type 
		where z.zoneid='$zoneid' and b.bannerid='$parentid'");
		
        return $query->result();		
	}
	
	/* Get Mobile Screen Sizes for Image Banner Upload */
	function getBannerSizes($where=''){
	
		//Build contents query
		if($where !=='')
		{
		$this->db->where($where);
		}
		
		$query = $this->db->get('oxm_mobilescreensizesettings');
		
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	/* Get Banner File Name for Unlink/Delete File */
	function get_banner_filename($where_arr=0,$where_master=0)
	{
		if($where_arr!=0)
        {
            $this->db->where($where_arr);
        }
		if($where_master!=0)
		{
			$this->db->or_where($where_master); 
		}
        
		$this->db->select('filename,campaignid,width');
		
		$this->db->order_by('bannerid','desc');
		
        $query = $this->db->get('ox_banners');

        return $query->result();
	}
	
	/* Get Child Banner IDs */
	function get_banner_child($where_child=0)
	{
		if($where_child!=0)
        {
            $this->db->where($where_child);
        }
		
		$this->db->select('bannerid,width');
		
		$this->db->order_by('bannerid','asc');
		
        $query = $this->db->get('ox_banners');

        return $query->result();
	}
	
	
	/* Get Banner Campaign ID for Edit Banner */
	function get_banner_campaign($where_arr=0)
	{
		if($where_arr!=0)
        {
            $this->db->where($where_arr);
        }
		
		$this->db->select('campaignid');
		
		$query = $this->db->get('ox_banners');
		
		echo $this->db->last_query();exit;

        return $query->result();
	}
	
	/* Get Content Type for Uploading File */
	function staticGetContentTypeByExtension($fileName, $alt=false)
    {
        $contentType = '';
        $ext = substr($fileName, strrpos($fileName, '.') + 1);
        switch (strtolower($ext)) {
            case 'jpeg': $contentType = 'jpeg'; break;
            case 'jpg':  $contentType = 'jpeg'; break;
            case 'png':  $contentType = 'png';  break;
            case 'gif':  $contentType = 'gif';  break;
            case 'swf':  $contentType = $alt ? '' : 'swf';  break;
            case 'dcr':  $contentType = $alt ? '' : 'dcr';  break;
            case 'rpm':  $contentType = $alt ? '' : 'rpm';  break;
            case 'mov':  $contentType = $alt ? '' : 'mov';  break;
        }
        return $contentType;
    }
	
	 //get banner details for checking status
    function get_banner_details($id=0)
	{
		if($id!=0)
        	{
            		$this->db->where('bannerid',$id);
        	}
		
		$this->db->select('*');
		
		$query = $this->db->get('ox_banners');
		
			
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
		/*----------------------------------------------
	 * GET ADMIN EMAIL ID
	 * ---------------------------------------------*/
	function get_admin_email()
	{
		$resObj = $this->db->where('default_account_id',2)->get('ox_users');
		if($resObj->num_rows >0)
		{
			$temp = $resObj->result();
			return $temp[0]->email_address;
		}
		else
		{
			return FALSE;
		}
	}
	/*----------------------------------------------
	 * GET ADVERTISRES EMAIL ID
	 * ---------------------------------------------*/
	function get_advertiser_det($advertiser_id)
	{
		
		$this->db->where("clientid", $advertiser_id); 
		
		$query = $this->db->select('*')->get('ox_clients');
		
		if($query->num_rows >0)
		{
		
			$t= $query->result_array();
			return $t[0];
		}
		else
		{
			return FALSE;
		}
    }   
	/*----------------------------------------------
	 * GET CAMPAIGNS DETAILS
	 * ---------------------------------------------*/
	function get_campaigns_det($campaign_id)
	{
		
		$this->db->where("campaignid", $campaign_id); 
		
		$query = $this->db->select('campaignname')->get('ox_campaigns');
		
		if($query->num_rows >0)
		{
		
			$t= $query->result_array();
			return $t[0];
		}
		else
		{
			return FALSE;
		}
    }
    public function banner_screen()
    {
		$this->db->select('*');
		$query = $this->db->get('oxm_mobilescreensizesettings');
		return $query->result_array();
		
	}
}
