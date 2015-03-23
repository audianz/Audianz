<?php
class Mod_approval_settings extends CI_Model 
{

	//Get the Advertisers
	function get_advertisers_list($limit=0,$offset=0)
	{
		$this->db->select('user_id,contact_name,email_address');
		$where_arr			=array("account_type" =>"ADVERTISER");
		
		if($limit ===0)
		{
				$query = $this->db->get_where('oxm_newusers',$where_arr);
		}else
		{
				$query = $this->db->get_where('oxm_newusers',$where_arr,$limit,$offset);
		}
		//	$query	=	$this->db->get('oxm_newusers');
		
		if($query->num_rows() >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;	
		}
		
	}

 function get_publisher_email($pub_id) 
	{
    	           
         $resObj = $this->db->where('affiliateid',$pub_id)->get('ox_affiliates');
                      
         if($resObj->num_rows >0)
           {
                        
            $temp = $resObj->result();
            return $temp[0]->email;
           }
                                
         else
           {
                        
            return FALSE;
           }
	}
	
	//Get the Publishers
	function get_publishers_list($limit=0,$offset=0)
	{
		$this->db->select('user_id,contact_name,site_url,email_address');
		$where_arr			=array("account_type" =>"TRAFFICKER");
		
		if($limit ===0)
		{
				$query = $this->db->get_where('oxm_newusers',$where_arr);
		}else
		{
				$query = $this->db->get_where('oxm_newusers',$where_arr,$limit,$offset);
		}
		//	$query	=	$this->db->get('oxm_newusers');
		
		if($query->num_rows() >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;	
		}
		
	}

	//Get the Banners
	function get_banners_list($limit=0,$offset=0)
	{
		$this->db->select('bannerid,storagetype,filename,width,height,bannertext,description');
		$this->db->where('adminstatus',1);
		$this->db->where_in('master_banner',array(-1,-2,-3));
		
		if($limit ===0)
		{
				$query = $this->db->get('ox_banners');
		}else
		{
				$query = $this->db->get('ox_banners',$limit,$offset);
		}
		//	$query	=	$this->db->get('oxm_newusers');
		
		if($query->num_rows() >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;	
		}
	
	}
	
	// Retrieve the Clients , campaign and banner data for mapping banners
	function get_client_campaign_data($banner_id=FALSE)
	{
		$this->db->select('ox_clients.email,ox_clients.contact,ox_banners.description,ox_campaigns.campaignid');
		$this->db->from('ox_banners');
		$this->db->join('ox_campaigns','ox_campaigns.campaignid = ox_banners.campaignid');
		$this->db->join('ox_clients','ox_clients.clientid = ox_campaigns.clientid');
		$this->db->where('ox_banners.bannerid',$banner_id);
		$this->db->limit(1);
		return $this->db->get()->row();
	}
	
	function get_publisher_share() 
	{
    	           
         $query=$this->db->get('oxm_publisher_share');
         return $query->result();
	}
	
	function get_approvals_type() 
	{
    	           
         $query=$this->db->get('oxm_apptype');
         return $query->result();
	}
	function get_minimum_rate() 
	{
    	           
         $query=$this->db->get('oxm_withdraw');
         return $query->result();
	}
	
	 
	/*-------------------------------------------
		UPDATE Publisher Share TABLE
	---------------------------------------------*/
	function publisher_share_check_id()
	{
	
	$this->db->select('*');
	$query=$this->db->get('oxm_publisher_share');	
	if($query->num_rows>0)
	{	
		$temp=$query->row();
		return $temp->id;	
	}
	else
	{
		return 0;
	}	
	}
		
	function publisher_share_insert($data)
	{
	
	$userDet		=	array("publishershare" => mysql_real_escape_string($data['publisher_share']));
	$this->db->insert('oxm_publisher_share',$userDet);	
	}	

	function publisher_share_update($data,$id)
	{
       
	$userDet		=	array("publishershare" => mysql_real_escape_string($data['publisher_share']));
	$this->db->where("id",$id);
	$this->db->update('oxm_publisher_share',$userDet);	
	}	
	/*-------------------------------------------
		UPDATE approvals_type TABLE
	---------------------------------------------*/
	
	function approvals_type_update($data)
	{
	
	
	$approvals_type_id =1;
	$userDet		=	array(
							"approval_type" =>	mysql_real_escape_string($data['apptype']),
															
							);
	$this->db->where("id",$approvals_type_id);
	$this->db->update('oxm_apptype',$userDet);	
	}	
	 /*-------------------------------------------
		UPDATE Minimum Rate TABLE
	---------------------------------------------*/
	//check oxm_withdraw table is not empty
	function check_minimum_rate()
	{
	
	$this->db->select('*');
	$query = $this->db->get('oxm_withdraw');	
	if($query->num_rows()>0)
	{
		$temp=$query->row();
		return $temp->id;
	}
	else
	{
		return 0;
	}
	}
	
	function minimum_rate_update($data,$id)
	{
	
	if($id != 0)
	{
		$userDet		=	array(
								"Amount" =>	mysql_real_escape_string($data['Amount']),								
								);
		$this->db->where("id",$id);
		$this->db->update('oxm_withdraw',$userDet);
	}
	else
	{	
		$userDet		=	array(
							"Amount" =>	mysql_real_escape_string($data['Amount']),								
							);
		$this->db->insert('oxm_withdraw',$userDet);
	}	
	}	
	
		//Get the Payments List
	function get_payment_list($limit=0,$offset=0)
	{
		$this->db->select('oxm_admin_payment.email,oxm_admin_payment.id,oxm_admin_payment.publisherid,oxm_admin_payment.paymenttype,oxm_admin_payment.date,
		oxm_admin_payment.amount,oxm_admin_payment.name,oxm_admin_payment.name,oxm_userdetails.paypalid');
		$this->db->from('oxm_admin_payment');
		$this->db->join('ox_affiliates', 'oxm_admin_payment.publisherid = ox_affiliates.affiliateid','left');
		$this->db->join('oxm_userdetails','oxm_userdetails.accountid = ox_affiliates.account_id','left');
        $this->db->where('oxm_admin_payment.status',0);
		if($limit ===0)
		{
				$query = $this->db->get();
		}else
		{
				$query = $this->db->limit($limit,$offset);
				$query=	$this->db->get();
		}
		//	$query	=	$this->db->get('oxm_newusers');
		
		if($query->num_rows() >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;	
		}
		
	}

	//Retreive all details of Advertiser and Tracffiker
	function  get_user_approval_details($user_id='',$user_type='')
	{
			if($user_id!='')
			{
						$query	=	$this->db->get_where('oxm_newusers',array('user_id'=>$user_id,'account_type'=>$user_type));
						return $query->result();
			}else
			{
				return FALSE;
			}
	
	}

	//Retreive unviewed Advertisers
	function get_unviewed_list($limit,$start,$acc_type='')
	{
		if($acc_type!='')
		{
				$this->db->where('oxm_newusers.account_type',$acc_type);
				$this->db->select('oxm_newusers.user_id,oxm_newusers.contact_name,oxm_newusers.email_address,oxm_newusers.site_url,');		
				$this->db->join('oxm_approval_notification','oxm_approval_notification.approval_user_id = oxm_newusers.user_id');
				$this->db->where('oxm_approval_notification.read_status',0);
				if($limit>0)
				{
					$this->db->limit($limit,$start);
				}
				$query = $this->db->get('oxm_newusers');
				if($query->num_rows() >0)
				{
					return $query->result();
				}
				else
				{
					return FALSE;	
				}
		}else{
				return FALSE;
		}
	}
	
	function retrieve_textbanner($where)
	{
		
		$this->db->where($where);
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
}
