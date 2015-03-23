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
	/* Authorisation Check */
	function check_adv_authorisation($adv, $camp)
	{
		if($adv!=0)
		{
		    $this->db->where('clientid',$adv);
		}
		if($camp!=0)
		{
		    $this->db->where('campaignid',$camp);
		}
		
		$query = $this->db->get('ox_campaigns');
		
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
	
	/* Retrieve Banners List */
    function get_banners($where=0, $adv_id=0, $offset=0,$limit=false)
    {
		if($limit != false)
		{
			$this->db->limit($limit, $offset);
		}
		$this->db->where($where);
		$adv_id = $this->session->userdata('session_advertiser_id');
		//$this->db->select('ox_banners.*');
		$this->db->select('ox_banners.*,ox_campaigns.clientid as clid,ox_campaigns.campaignid as cmid');
		
		$this->db->where('ox_campaigns.clientid', $adv_id);
		$this->db->join('ox_campaigns', 'ox_campaigns.campaignid = ox_banners.campaignid');
		
		$this->db->join('ox_clients', 'ox_clients.clientid = ox_campaigns.clientid');
		
		$this->db->order_by('ox_banners.campaignid','asc');
		$query = $this->db->get('ox_banners');
		
		//echo $this->db->last_query();exit;
		
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return 0;
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
		if($this->db->affected_rows()>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
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
			
			
			function get_banner_det($banner_id){
				
				$this->db->select("oxb.description,oxb.width,oxb.height,oxb.bannerid,oxb.description as banner_name,oxc.campaignid,oxc.campaignname,oxc.clientid,master_banner,oxc.revenue_type");
				$this->db->from("ox_banners as oxb");
				$this->db->join("ox_campaigns as oxc","oxb.campaignid=oxc.campaignid AND oxb.master_banner IN (-1,-2,-3)");
				$this->db->where("oxb.bannerid",$banner_id);
				$query = $this->db->get();
							
				if($query->num_rows >0)
				{
					$temp = $query->result();
					return $temp[0];
				}
				else
				{
			
					return false;
				}
			
			}
			
			function get_banner_data($banner_id){
				
				$this->db->select("oxb.description,oxb.width,oxb.height,oxb.bannerid,oxb.description as banner_name,oxc.campaignid,oxc.campaignname,oxc.clientid,master_banner,oxc.revenue_type");
				$this->db->from("ox_banners as oxb");
				$this->db->join("ox_campaigns as oxc","oxb.campaignid=oxc.campaignid");
				$this->db->where("oxb.bannerid",$banner_id);
				$query = $this->db->get();
							
				if($query->num_rows >0)
				{
					$temp = $query->result();
					return $temp[0];
				}
				else
				{
			
					return false;
				}
			
			}
			
			function get_zone_data($_id){
				
				$this->db->select("*");
				$this->db->from("ox_zones");
				$this->db->where("zoneid",$_id);
				$query = $this->db->get();
							
				if($query->num_rows >0)
				{
					$temp = $query->result();
					return $temp[0];
				}
				else
				{
			
					return false;
				}
			
			}
			
			function get_budget_value($client_id,$campaignid)
			{
				
				$this->db->select("budget");
				$this->db->from("oxm_budget");
				$this->db->where("clientid",$client_id);
				$this->db->where("campaignid",$campaignid);
				$query = $this->db->get();
				if($query->num_rows >0)
				{
					$temp = $query->result();
					return $temp[0]->budget;
				}
				else
				{
			
					return FALSE;
				}
			}
			
			function get_parent_child_banner_or_zones($_id,$type){
					
					if($type == "banners"){
						$this->db->select("bannerid");
						$this->db->where('master_banner',$_id);
						$this->db->or_where('bannerid',$_id);
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
					else
					{
						$this->db->select("zoneid");
						$this->db->where('master_zone',$_id);
						$this->db->or_where('zoneid',$_id);
						$query = $this->db->get('ox_zones');
						
						if($query->num_rows>0)
						{
							return $query->result();
						}
						else
						{
							return FALSE;
						}
					}
					
			}
			
			function get_child_banner_or_zones($_id,$type){
					
					if($type == "banners"){
						$this->db->select("bannerid");
						$this->db->where('master_banner',$_id);
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
					else
					{
						$this->db->select("zoneid");
						$this->db->where('master_zone',$_id);
						$query = $this->db->get('ox_zones');
						
						if($query->num_rows>0)
						{
							return $query->result();
						}
						else
						{
							return FALSE;
						}
					}
					
			}
			
			function del_ads_placement_mapping($sel_banner_list)
			{
				 
				 $this->db->where_in('ad_id',$sel_banner_list);				 
				 $this->db->delete("ox_ad_zone_assoc");
				
				 return TRUE;
			}
			
			
			 function get_revenue_type_for_zones($zone_id){
		 			$this->db->select("oxz.revenue_type");
					$this->db->from('ox_zones as oxz');
					$this->db->where('oxz.zoneid',$zone_id);
					$query = $this->db->get();
					if($query->num_rows>0)
					{
						$t	=	$query->result();
						return $t[0]->revenue_type;
					}
					else
					{
						return FALSE;
					}
		 }
		 
		 function insert_zone_assoc($ins)
			{
				$this->db->insert('ox_ad_zone_assoc',$ins);
				if($this->db->affected_rows()>0)
				{
					return $this->db->insert_id();
				}
				else
				{
					return false;
				}
			}
			
			
			function getAssocZones($_id)
			{
		
				
		
				$this->db->select('zone_id, ad_id')->from('ox_ad_zone_assoc as oxaza');
				$this->db->join('ox_banners as oxb','oxaza.ad_id=oxb.bannerid');
				$this->db->where("ad_id",$_id);
				$query =$this->db->get();

				//echo $this->db->last_query();
			
				if($query->num_rows >0)
				{
		   			$i=1;
				   
				   	foreach($query->result() as $assoc_list)
					{
					  $ids['zone'][$i]  		=$assoc_list->zone_id;
					  $i++;
					}
				}
				
				else
				{
			
					$ids =false;
				}
		
				return $ids;
    		}
			
			function get_records($select='*', $from, $where=''){
				//Build contents query
		
				$this->db->select($select)->from($from);
				if($where !='') { $this->db->where($where); }
				$query =$this->db->get();
		
				if($query->num_rows >0)
				{

					return $query->row();
				}
				else
				{
					return false;
				}
			}
			
			function list_linked_zones($bannerid)
			{
				$t = $this->get_records($select='campaignid', "ox_banners", array("bannerid"=>$bannerid));
				
				$category = $this->db->select('catagory')->get_where('ox_campaigns',array('campaignid'=>$t->campaignid))->row();
				$cat = explode(',',$category->catagory);
				
				if(!empty($category->catagory))
				{
			
					$this->db->select("oxc.clientid,
									   oxcl.clientname,
									   oxb.campaignid,
									   oxc.campaignname,
									   oxb.width,
									   oxb.description as banner_name,
									   oxb.height,
									   oxz.master_zone,
									   oxz.revenue_type,
									   oxz.zonename,
									   oxz.zoneid,
									   oxc.revenue_type as campaign_revenue_type,
									   rt.revenue_type as revenue_type_name,
									   oxa.name as publisher_name");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_affiliates as oxa', "oxa.affiliateid = oxz.affiliateid");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->join('da_inventory_revenue_type as rt', "rt.revenue_type_value =  oxc.revenue_type");
					$this->db->where('oxb.bannerid',$bannerid);
					$this->db->where_in('master_zone',array(-1,-2,-3));
					$this->db->where_in('oxa.oac_category_id',$cat); 
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				else
				{
					$this->db->select("oxc.clientid,
									   oxcl.clientname,
									   oxb.campaignid,
									   oxc.campaignname,
									   oxb.width,
									   oxb.description as banner_name,
									   oxb.height,
									   oxz.master_zone,
									   oxz.revenue_type,
									   oxz.zonename,
									   oxz.zoneid,
									   oxc.revenue_type as campaign_revenue_type,
									   rt.revenue_type as revenue_type_name,
									   oxa.name as publisher_name");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_affiliates as oxa', "oxa.affiliateid = oxz.affiliateid");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->join('da_inventory_revenue_type as rt', "rt.revenue_type_value =  oxc.revenue_type");
					$this->db->where('oxb.bannerid',$bannerid);
					$this->db->where_in('master_zone',array(-1,-2,-3));
					$this->db->where('oxa.oac_category_id IS NULL');
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				$query = $this->db->get();
				//echo $this->db->last_query(); exit;
			
				if($query->num_rows >0)
				{
			
					return $query->result();
				}
				else
				{
			
					return false;
				}
   			}

   	public function banner_screen()
    {
		$this->db->select('*');
		$query = $this->db->get('oxm_mobilescreensizesettings');
		return $query->result_array();
		
	}		
}
