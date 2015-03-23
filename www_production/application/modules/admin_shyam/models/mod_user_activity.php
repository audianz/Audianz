<?php
class mod_user_activity extends CI_Model 
{  
	function get_activity_tasks() 
	{
    	// $this->db->where(array('task_status' =>'1'));
		 $this->db->where('task_status !=', '0');  
         $query=$this->db->get('djx_activity_tasks');
         return $query->result();
	}
	
	function get_activity_tasks_settings($userid=0) 
	{
		 $this->db->select('task_id, activity_user_id, activity_user_type');
    	 $this->db->where(array('activity_user_id' =>$userid));
         $query=$this->db->get('djx_activity_settings');
		 $val	=$query->result_array();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}
	
	function get_selected_tasks($userid=0) 
	{
		 $this->db->select('task_id');
    	 $this->db->where(array('activity_user_id' =>$userid));
         $query=$this->db->get('djx_activity_settings');
		 $val	=$query->result_array();
         if($query->num_rows() >0) { return $val[0]; } else { return NULL; } 
	}
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
	
	/*-------------------------------------------
		UPDATE MYaccount SETTINGS TABLE
	---------------------------------------------*/
	
	function activity_process($data, $userid='0', $activity_user_type ="MANAGER")
	{
		$this->db->select('task_id,activity_user_type');
		$this->db->from('djx_activity_settings');
		$this->db->where(array('activity_user_type'=> $activity_user_type,'activity_user_id' =>$userid));
		$query = $this->db->get();
			
		if($query->num_rows() ==0)
		{
			$this->db->insert('djx_activity_settings', $data);
			return $this->db->insert_id(); 
		}
		else 
		{ 
			$this->db->where(array("activity_user_type" =>$activity_user_type, 'activity_user_id' =>$userid));
	        $this->db->update('djx_activity_settings', $data);
			return TRUE;
		}	
	}	

	/* ############################### For Activity Mails ########################################### */
	
	function get_activity_tasks_income($type='MANAGER', $userid=0, $date) 
	{
		 if($type =='MANAGER') { $this->db->select('(SUM(amount) - SUM(publisher_amount)) AS income'); $this->db->where(array('date' =>$date)); }
		 if($type =='ADVERTISER') { $this->db->select('SUM(amount) AS income'); $this->db->where(array('date' =>$date,'clientid' =>$userid)); }
		 if($type =='TRAFFICKER') { $this->db->select('SUM(publisher_amount) AS income'); $this->db->where(array('date' =>$date,'publisherid' =>$userid)); }
		 
         $query=$this->db->get('oxm_report');
		 $val	=$query->row_array();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}

	function get_activity_tasks_admin_approvals($date) 
	{
		 $this->db->select('contact_name, email_address, username, account_type');
    	 $this->db->where(array('date(date_created)' =>$date));
         $query=$this->db->get('oxm_newusers');
		 $val	=$query->result();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}

	function get_activity_tasks_admin_advertiser($date) 
	{
		 $this->db->select('clientname,contact,email');
    	 $this->db->where(array('date(updated)' =>$date));
         $query=$this->db->get('ox_clients');
		 $val	=$query->result();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}

	function get_activity_tasks_admin_publisher($date) 
	{
		 $this->db->select('name,contact,email');
    	 $this->db->where(array('date(updated)' =>$date));
         $query=$this->db->get('ox_affiliates');
		 $val	=$query->result();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}
    
	function get_activity_tasks_campaigns($type='MANAGER', $userid=0, $date)
	{    
	 
		 if($type =='MANAGER') :
		 $this->db->select('ox_clients.contact,ox_clients.email,ox_campaigns.campaignid,ox_campaigns.campaignname,ox_campaigns.revenue,
		 ox_campaigns.activate_time,ox_campaigns.expire_time,da_inventory_revenue_type.revenue_type');
		 $this->db->from('ox_campaigns');
		 $this->db->join('ox_clients','ox_clients.clientid = ox_campaigns.clientid');
		 $this->db->join('da_inventory_revenue_type', "da_inventory_revenue_type.revenue_type_value = ox_campaigns.revenue_type");
    	 $this->db->where_in("date(ox_campaigns.updated)='".$date."'");
         $this->db->order_by('ox_campaigns.campaignid','ASC');
         else :
		 $this->db->select('ox_clients.contact,ox_clients.email,ox_campaigns.campaignid,ox_campaigns.campaignname,ox_campaigns.revenue,
		 ox_campaigns.activate_time,ox_campaigns.expire_time,da_inventory_revenue_type.revenue_type');
		 $this->db->from('ox_campaigns');
		 $this->db->join('ox_clients','ox_clients.clientid = ox_campaigns.clientid');
		 $this->db->join('da_inventory_revenue_type', "da_inventory_revenue_type.revenue_type_value = ox_campaigns.revenue_type");
    	 $this->db->where_in("date(ox_campaigns.updated)='".$date."'");
         $this->db->order_by('ox_campaigns.campaignid','ASC');
		 endif;
		$query 		=$this->db->get();
		
        if($query->num_rows >0)
		{
			return $query->result();
		} 
		else
		{
			return NULL;
		}
	}
	
function get_activity_tasks_zones($type='MANAGER', $userid=0, $date) 
	{
	
		$masters	=array('-1','-2','-3');
		if($type =='MANAGER') :
			$this->db->select('a.contact as contact, z.zoneid as zoneid, z.zonename, z.zonetype as zonetype, z.delivery as delivery, z.width as width, z.height as height, t.revenue_type as revenuetype');
			$this->db->from('ox_zones as z');
			$this->db->join('ox_affiliates as a', "a.affiliateid = z.affiliateid AND date(z.updated)='".$date."'");
			$this->db->join('da_inventory_revenue_type as t', "t.revenue_type_value = z.revenue_type");
			$this->db->where_in('z.master_zone', $masters);
			$this->db->order_by('z.affiliateid','ASC');
			$this->db->group_by("z.affiliateid");
		else :
			$this->db->select('a.contact as contact, z.zoneid as zoneid, z.zonename, z.zonetype as zonetype, z.delivery as delivery, z.width as width, z.height as height,t.revenue_type as revenuetype');
			$this->db->from('ox_zones as z');
			$this->db->join('ox_affiliates as a', "a.affiliateid = z.affiliateid AND date(z.updated)='".$date."' AND z.affiliateid=".$userid);
			$this->db->join('da_inventory_revenue_type as t', "t.revenue_type_value = z.revenue_type");
			$this->db->where_in('z.master_zone', $masters);
			$this->db->order_by('z.affiliateid','ASC');
			$this->db->group_by("z.affiliateid");
		endif;
		 
		$query 		=$this->db->get();
		
        if($query->num_rows >0)
		{
			return $query->result();
		} 
		else
		{
			return NULL;
		}
	}

	function get_activity_tasks_banners($type='MANAGER', $userid=0, $date) 
	{
		$masters	=array('-1','-2','-3');
		if($type =='MANAGER') :
			$this->db->select('c.contact as contact, b.bannerid as bannerid, b.description, b.url as url, b.width as width, b.height as height, t.revenue_type as revenuetype');
			$this->db->from('ox_banners as b');
			$this->db->join('ox_campaigns as xc', "xc.campaignid = b.campaignid AND date(b.updated)='".$date."'");
			$this->db->join('ox_clients as c', "c.clientid = xc.clientid");
			$this->db->join('da_inventory_revenue_type as t', "t.revenue_type_value = xc.revenue_type");
			$this->db->where_in('b.master_banner', $masters);
			$this->db->order_by('b.bannerid','ASC');
			$this->db->group_by("xc.clientid");
		else :
			$this->db->select('c.contact as contact, b.bannerid as bannerid, b.description, b.url as url, b.width as width, b.height as height, t.revenue_type as revenuetype');
			$this->db->from('ox_banners as b');
			$this->db->join('ox_campaigns as xc', "xc.campaignid = b.campaignid AND date(b.updated)='".$date."' AND xc.clientid=".$userid);
			$this->db->join('ox_clients as c', "c.clientid = xc.clientid");
			$this->db->join('da_inventory_revenue_type as t', "t.revenue_type_value = xc.revenue_type");
			$this->db->where_in('b.master_banner', $masters);
			$this->db->order_by('b.bannerid','ASC');
			$this->db->group_by("xc.clientid");
		endif;
		 
		$query 		=$this->db->get();
		
        if($query->num_rows >0)
		{
			return $query->result();
		} 
		else
		{
			return NULL;
		}
	}


	/* ############################### For Activity Mails ########################################### */



}   
