<?php
class Mod_advertiser extends CI_Model { 


	/*function get_advertisers($status="all",$offset=0,$limit=false) {
		
				if($status=="active")
				{   
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid","left");
					//$this->db->where('oxcamp.status', '0');
					$this->db->order_by('accountid ','DESC');
					$this->db->group_by("oxc.clientid");
                }
				else if($status=="inactive")
                {
					$clientid='';

					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid",'left');
					//$this->db->where('oxcamp.status', '0');
					$this->db->group_by("oxc.clientid");
					$query = $this->db->get();

					$result=$query->result();
					
					foreach($result as $res) {$clientid.=$res->client_id.",";	}

					$clientids=rtrim($clientid,",");

					$client_ids=explode(",",$clientids);


 					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->where_not_in('oxc.clientid', $client_ids);
					$this->db->group_by("oxc.clientid");
					$this->db->order_by('accountid ','DESC');	
                }
				else if($status=="single")
				{
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid","left");
					$this->db->where('oxc.`clientid`',$offset);
					$this->db->group_by("oxc.clientid");
				}
                else
                {
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->group_by("oxc.clientid");
					$this->db->order_by('accountid ','DESC');
                }

                if($limit != false)
				{
                    $this->db->limit($limit, $offset);
                }
		
				$query = $this->db->get();
				//echo $this->db->last_query();
				//exit;
		
         if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}*/
	
	function get_advertisers($status="all",$offset=0,$limit=false) {
		
				if($status=="active")
				{   
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid");
					$this->db->order_by('accountid ','DESC');
					$this->db->group_by("oxc.clientid");
                }
				else if($status=="inactive")
                {
					$clientid='';

					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid");
					$this->db->group_by("oxc.clientid");
					$query = $this->db->get();

					$result=$query->result();
					
					foreach($result as $res) {$clientid.=$res->client_id.",";	}

					$clientids=rtrim($clientid,",");

					$client_ids=explode(",",$clientids);


 					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->where_not_in('oxc.clientid', $client_ids);
					$this->db->group_by("oxc.clientid");
					$this->db->order_by('accountid ','DESC');	
                }
				else if($status=="single")
				{
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid","left");
					$this->db->where('oxc.`clientid`',$offset);
					$this->db->group_by("oxc.clientid");
				}
                else
                {
					$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
					$this->db->group_by("oxc.clientid");
					$this->db->order_by('accountid ','DESC');
                }

                if($limit != false)
				{
                    $this->db->limit($limit, $offset);
                }
		
				$query = $this->db->get();
				//echo $this->db->last_query();
				//exit;
		
         if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	function get_num_advertisers(){
					
		$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id, oxbal.accbalance as acc_balance');
		$this->db->from('ox_clients as oxc');
		$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
		$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
		$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
		$this->db->group_by("oxc.clientid");
		$query = $this->db->get();
		//print_r($query->result());exit;
		//echo $query->num_rows;exit;
		if($query->num_rows>0)
		{
			return $query->num_rows;
		} 
		else
		{
			return 0;
		}
	}
	
       function get_advertiser_details($advertiser_acc_id=false){
            if($advertiser_acc_id != false){
                $this->db->select('*');
                $this->db->from('ox_clients as oxc');
                $this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
                $this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
                $this->db->where("oxu.accountid",$advertiser_acc_id);
                $query = $this->db->get();
               
		if($query->num_rows >0)
		{
			$temp =  $query->result();
                        return $temp[0];
		}
		else
		{
			return FALSE;
		}
            }
            else
            {
                return FALSE;
            }
        }
        
     
	
	 	function get_advertiser_det($advertiser_id)
		{
		
		$this->db->where("clientid", $advertiser_id); 
		
		$query = $this->db->select('*')->get('ox_clients');
		
		if($query->num_rows >0)
		{
		
			return $query->result();
					}
		else
		{
			return FALSE;
		}
    }   

	function add_advertiser($data){
		
		$cur_date=date("Y-m-d H:i:s");
		/*-------------------------------------------
			CREATE ACCOUNT  FOR ADVERTISER
		---------------------------------------------*/
		$acc_data		=	array(
							"account_type"	=>	text_db($data['account']),
							"account_name"	=>	text_db($data['username'])
							);
		$this->db->insert('ox_accounts',$acc_data);
		$account_id	=	$this->db->insert_id();		
				
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$userDet		=	array(
								"accountid"		=>	$account_id, 
								"accounttype"   =>	text_db($data['account']),
								"username"		=>	text_db($data['username']),
								"email"			=>	text_db($data['email']),
								"password"		=>	md5($data['password']),
								"address"		=>	text_db($data['address']),
								"city"			=>	text_db($data['city']),
								"state"			=>	text_db($data['state']),
								"country"		=>	text_db($data['country']),
								"mobileno"		=>	text_db($data['mobile']),
								"postcode"		=>	text_db($data['zip_code'])
						  	);
		
		$this->db->insert('oxm_userdetails',$userDet);		
		/*-------------------------------------------
			UPDATE CLIENTS TABLE
		---------------------------------------------*/
		$clientDet	=	array(
							"email"			=>	text_db($data['email']),
							"contact"		=>	text_db($data['username']),
							"clientname"	=>	text_db($data['name']),
							"agencyid"		=>	1,
							"account_id"	=>	$account_id
							);
		
		$this->db->insert('ox_clients',$clientDet);
		
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/
		
		$oxUserDet	=	array(
							"contact_name"			=>	text_db($data['name']),
							"email_address"			=>	text_db($data['email']),
							"username"				=>	text_db($data['username']),
							"password"				=>	md5($data['password']),
							"default_account_id"	=>	$account_id,
							"date_created"	=>	$cur_date
							);
		$this->db->insert('ox_users',$oxUserDet);
		
		$ox_user_id	=	$this->db->insert_id();
		/*-------------------------------------------
			UPDATE USER ACCOUNT ASSOCIATION
		---------------------------------------------*/
		
		$ox_acc_user_assoc	=	array(
							"account_id"	=>	$account_id,
							"user_id"		=>	$ox_user_id,
							"linked"		=>	$cur_date
							);
		$this->db->insert('ox_account_user_assoc',$ox_acc_user_assoc);
		
		/*-------------------------------------------
			UPDATE USER PERMISSION
		---------------------------------------------*/
		
		$inserperm1	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'10'));
		$inserperm2	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'4')); 
		$inserperm3	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'2')); 
		$inserperm4	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'1')); 
		$inserperm5	=$this->db->insert('ox_account_user_permission_assoc',array("account_id"=>$account_id, "user_id"=>$ox_user_id, "permission_id"=>'11'));
				
		/*------------------------------------------*/
		
	}
	
	function edit_advertiser($data,$advertiser_account_id){
		
		/*-------------------------------------------
			UPDATE USER DETAILS TABLE
		---------------------------------------------*/
		$userDet		=	array(
								"address"		=>	text_db($data['address']),
								"city"			=>	text_db($data['city']),
								"state"			=>	text_db($data['state']),
								"country"		=>	text_db($data['country']),
								"mobileno"		=>	text_db($data['mobile']),
								"postcode"		=>	text_db($data['zip_code'])
						  	);
		$this->db->where("accountid",$advertiser_account_id);
		$this->db->update('oxm_userdetails',$userDet);		
		/*-------------------------------------------
			UPDATE CLIENTS TABLE
		---------------------------------------------*/
		$clientDet	=	array(
							"contact"	=>	text_db($data['name']),
							);
		$this->db->where("account_id",$advertiser_account_id);
		$this->db->update('ox_clients',$clientDet);
		
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/
		
		$oxUserDet	=	array(
							"contact_name"			=>	text_db($data['name']),
							"default_account_id"	=>	$advertiser_account_id
							);
		$this->db->where("default_account_id",$advertiser_account_id);					
		$this->db->update('ox_users',$oxUserDet);
	
		/*-----------------------------------------------------------------------
					SEND INVITATION EMAIL TO REGISTRED ADVERTISER
		------------------------------------------------------------------------*/
		
		
		
		
		/*----------End of Email Configuration and Sending Process---------------*/
		
				
		/*------------------------------------------*/
		
	}
	
	function get_num_active_advertisers(){
	
		$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance,oxc.`contact` as contact_name');
					$this->db->from('ox_clients as oxc');
					$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
					$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
					$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid");
					$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
					$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid");
					//$this->db->where('oxcamp.status', '0');
					//$this->db->order_by('accountid ','DESC');
					$this->db->group_by("oxc.clientid");
		$query = $this->db->get();
	
        if($query->num_rows>0)
		{
			return $query->num_rows;
		} 
		else
		{
			return 0;
		}
	}

	function get_num_inactive_advertisers(){
	
		$clientid='';

		$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance');
		$this->db->from('ox_clients as oxc');
		$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
		$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
		$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid");
		$this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid");
		$this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid");
		//$this->db->where('oxcamp.status', '0');
		$this->db->group_by("oxc.clientid");
		$query = $this->db->get();

		$result=$query->result();
		
		foreach($result as $res) {$clientid.=$res->client_id.",";	}

		$clientids=rtrim($clientid,",");

		$client_ids=explode(",",$clientids);


		$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance');
		$this->db->from('ox_clients as oxc');
		$this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
		$this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
		$this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
		$this->db->where_not_in('oxc.clientid', $client_ids);
		$this->db->group_by("oxc.clientid");
		$this->db->order_by('accountid ','DESC');
		$query = $this->db->get();
		//print_r($query->result());exit;
		//echo $query->num_rows;exit;
		if($query->num_rows>0)
		{
			return $query->num_rows;
		} 
		else
		{
			return 0;
		}
	}
	
	public function del_advertiser($sel_ids){
		if(is_array($sel_ids)){
			//echo "Multiple Advertiser Delete";
			
			foreach($sel_ids as $account_id){
				$this->remove_advertiser($account_id);
			}
		}
		else
		{
			//echo "Single Advertiser Delete";
			$this->remove_advertiser($sel_ids);
			
		}
	}
	
	
	/*Get admin details*/
	
	function get_admin_details()
	{
		$query = $this->db->get('oxm_admindetails');
		
		//echo $this->db->last_query();
		
		if($query->num_rows>0)
		{
			return $query->row();
		} 
		else
		{
			return FALSE;
		}
	}
	
	function get_admin_email(){
		$resObj = $this->db->where('default_account_id',2)->get('ox_users');
		if($resObj->num_rows >0){
			$temp = $resObj->result();
			return $temp[0]->email_address;
		}
		else
		{
			return FALSE;
		}
	}
	
	function remove_advertiser($account_id){
		
			$this->db->select('clientid');
			$resObj = $this->db->where(array("account_id" =>$account_id))->get('ox_clients');
			$temp = $resObj->result();
			
			//echo $this->db->last_query();
			
			/*----------------------------------------------------------------------------------------------------------
							DELETE ALL THE BANNERS AND IT'S STATISCAL SUMMRY  RELATED TO PARTICUALR ADVERTISER
			-----------------------------------------------------------------------------------------------------------*/
			
			if(count($temp) > 0){
			
			$client_id = $temp[0]->clientid;
			$banner_ids = $this->getBannersid($client_id);
			
			
			
				if($banner_ids != FALSE){
					foreach($banner_ids as $bid){
						
						$banner_id	=$bid->bannerid;					
						
						$tables = array('ox_data_bkt_a', 'ox_data_bkt_c', 'ox_data_bkt_m','ox_data_bkt_country_c','ox_data_bkt_country_m','ox_stats_country');
						$this->db->where('creative_id', $banner_id);
						$this->db->delete($tables);
						
						$tables = array('ox_data_summary_ad_hourly', 'ox_data_intermediate_ad', 'ox_ad_zone_assoc');
						$this->db->where('ad_id', $banner_id);
						$this->db->delete($tables);
						
						$this->db->where('clientid', $client_id);
						$this->db->delete('oxm_report'); 
						
						
						$this->db->where('bannerid', $banner_id);
						$this->db->delete('ox_banners');
						
						
					}
				  }	
				  
			
				/*---------------------------------------------------------------------------------
							DELETE ALL THE CAMPAIGNS RELATED TO SELECTED ADVERTISER
				----------------------------------------------------------------------------------*/				
				$this->db->where('clientid', $client_id);
				$this->db->delete('ox_campaigns');	
				
				$this->db->where('clientid', $client_id);
				$this->db->delete('ox_clients');			
				
				}  
				
				/*---------------------------------------------------------------------------------
							DELETE ALL THE ASSOCIATION RELATED TO SELETED ADVERTISER
				----------------------------------------------------------------------------------*/
								
				$tables = array('ox_account_user_permission_assoc', 'ox_account_user_assoc', 'ox_accounts');
				$this->db->where('account_id', $account_id);
				$this->db->delete($tables);	
				
				
				
				$this->db->where('accountid', $account_id);
				$this->db->delete('oxm_userdetails');
				
				$this->db->where('default_account_id', $account_id);
				$this->db->delete('ox_users');
				
				
				
			
		
	}
	
	
	function getBannersid($cid=0){
		//Build contents query		
		//$qry   ="SELECT B.bannerid FROM ox_banners B, ox_campaigns C WHERE B.campaignid =C.campaignid AND C.clientid=".$cid; 
		$this->db->select("B.bannerid");
		$this->db->from('ox_banners as B'); 
		$this->db->join("ox_campaigns as C",'B.campaignid = C.campaignid');
		$this->db->where('C.clientid',$cid);
		
		$query = $this->db->get();
		
		if($query->num_rows >0)
		{
			$banners	=$query->result();
		}
		else
		{
			$banners	=FALSE;
		}
		return $banners;
    }
	
    public function list_country()
	{
	    $this->db->select('code, name');
		$this->db->order_by('name','ASC');
		$query = $this->db->get('djx_geographic_locations');

		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
     }
	 
	 public function list_tracker_status()
		{
			$this->db->select('value, name');
			$this->db->where('is_active','1');
			$this->db->order_by('name','ASC');
			$query = $this->db->get('djx_trackers_status');
	
			if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		 }
		 
	public function list_tracker_type()
		{
			$this->db->select('value, name');
			$this->db->where('is_active','1');
			$this->db->order_by('name','ASC');
			$query = $this->db->get('djx_trackers_type');
	
			if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		 } 
		 
	
	 
	 /*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO FUND PROCESS FOR SELECTED ADVERTISER
	 ---------------------------------------------------------------------------------*/
	 
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
	 
	function insert_paypal_fund($clientid, $amount)
	{
		$data = array(
						"clientid" =>$clientid, 
						"Amount" =>$amount, 
						"date" =>date("Y-m-d")
				);
	
		$this->db->insert('oxm_paypal',$data);
		if($this->db->affected_rows()>0)
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
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
	 
	
	 /*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO TRACKERS PROCESS FOR SELECTED ADVERTISER
	 ---------------------------------------------------------------------------------*/
	
	function get_tracker_det($track_id){
		
		$this->db->where("trackerid", $track_id); 
		
		$query = $this->db->select('*')->get('ox_trackers');
		
		if($query->num_rows >0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
    }
	
	function get_trackers_list($adv_id=false,$status="all",$offset=0,$limit=false){
		
		$this->db->select("oxt.trackerid,oxt.trackername,oxt.description,oxts.name as tracker_status,oxtt.name as tracker_type");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value',"left");
		$this->db->join("djx_trackers_type as oxtt",'oxt.type=oxtt.value',"left");
		if($adv_id!=false){ 

			$this->db->where('oxc.clientid',$adv_id);
		}
		
		if($status!="all") 
		{
			$this->db->where('oxts.name',$status);
		}

		if($limit != false){
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
		
	
	}
	
	function get_trackers_count($adv_id=false){
		
		$this->db->select("oxt.status as status,oxts.name,count(*) as value");
		$this->db->from('ox_trackers as oxt'); 
		$this->db->join("ox_clients as oxc",'oxt.clientid=oxc.clientid');
		$this->db->join("djx_trackers_status as oxts",'oxt.status=oxts.value');
		$this->db->group_by("oxt.status");
		if($adv_id != false){
			$this->db->where('oxc.clientid',$adv_id);
		}
		
		$query = $this->db->get();
	
		$track_status = $this->list_tracker_status();
		foreach($track_status as $obj){
			$res_array[strtoupper($obj->name)]	=	 0;
		}
	
	
		if($query->num_rows>0)
		{
		
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= $count->value;
			}
		}
		else
		{
			$query = $this->db->select('name')->get('djx_trackers_status');
			$t = $query->result();
			foreach($t as $count){
				$res_array[strtoupper($count->name)]	= 0;
			}
		} 
		
		//print_r($res_array);
		
		return $res_array;				
	}
	
	public function insert_tracker($data){
		
			$this->db->insert('ox_trackers',$data);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		
	}	 
	
	public function update_tracker($data,$tracker_id){
		
			$this->db->where('trackerid',$tracker_id);
			$this->db->update('ox_trackers',$data);
			if($this->db->affected_rows()>0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		
	}
	
	public function update_tracker_append($data,$tracker_id){
			
	
			$this->db->where('tracker_id',$tracker_id);
			
			$this->db->update('ox_tracker_append',$data);
			
			if($this->db->affected_rows()>0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		
	}
	
	public function insert_tracker_append($data)
	{
		$this->db->insert('ox_tracker_append',$data);
		
		if($this->db->affected_rows()>0)
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
		 
	
	public function del_tracker($sel_ids){
		
		
		if(is_array($sel_ids)){
			// echo "Multiple Tracker Delete";
			// exit;
			foreach($sel_ids as $account_id){
				$this->remove_tracker($account_id);
				$this->remove_tracker_append($account_id);
			}
		}
		else
		{
			//echo "Single Tracker Delete";
			// exit;
			$this->remove_tracker($sel_ids);
			$this->remove_tracker_append($sel_ids);			
		}
	}
	
	public function remove_tracker($id){
	
			$this->db->where('trackerid',$id);
			
			$this->db->delete('ox_trackers');
			
			if($this->db->affected_rows()>0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
	
	}
	
	public function remove_tracker_append($id)
	{
		$this->db->where('tracker_id',$id);
			
		$this->db->delete('ox_tracker_append');
		
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*-------------------------------------------------------------------------------
	 		FUNCTIONS RELATED TO TRACKERS PROCESS FOR SELECTED ADVERTISER
	 ---------------------------------------------------------------------------------*/
	
	function get_campaigns_det($campaign_id){
		
		$this->db->where("campaignid", $campaign_id); 
		
		$query = $this->db->select('*')->get('ox_campaigns');
		
		if($query->num_rows >0)
		{
			$t	=	$query->result();
			return $t[0];
		}
		else
		{
			return FALSE;
		}
    }

	function get_trackers_linked_campaigns_list($adv_id, $tracker_id, $status="all", $offset=0, $limit=false){
		
		/*$this->db->select("oxc.campaignid as campaign_id,oxc.campaignname as campaign_name,oxc.viewwindow as views,oxc.clickwindow as clicks,IF((oxct.campaignid >0),1,0) as linked_campaign");
		$this->db->from('ox_campaigns as oxc'); 
		$this->db->join("ox_campaigns_trackers as oxct","oxc.campaignid=oxct.campaignid  AND oxct.trackerid=$tracker_id",'left');*/
		
		$query = "SELECT oxc.campaignid as campaign_id, oxc.campaignname as campaign_name, oxc.viewwindow as views, oxc.clickwindow as clicks, IF(oxct.campaignid >0,'1','0') as linked_campaign FROM (ox_campaigns as oxc) LEFT JOIN ox_campaigns_trackers as oxct ON (oxc.campaignid=oxct.campaignid AND oxct.trackerid=$tracker_id) WHERE oxc.clientid =  '$adv_id'";
				
		$query .= " GROUP BY campaign_id";
		$query .= " ORDER BY linked_campaign DESC, campaign_id ASC";

		if($limit != false){
			$query .= " LIMIT $offset,$limit"; //$this->db->limit($limit, $offset);
		}
		
		
		$query = $this->db->query($query);
		
		if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	/*Linked campaigns */
	function insert_trackercampaign($ins)
		{
			$this->db->insert('ox_campaigns_trackers',$ins);
			if($this->db->affected_rows()>0)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
	}
	
	function TimeToSeconds($years=0, $days=0, $hours=0, $min=0, $sec=0)
	{
	  $seconds	=0;
	
	  if(is_numeric($years) && $years >0)
	  {
		$seconds += ($years * 31556926);
	  } 
	  if(is_numeric($days) && $days >0)
	  {
		$seconds += ($days * 86400);
	  }
	  if(is_numeric($hours) && $hours >0)
	  {
		$seconds += ($hours * 3600);
	  }
	  if(is_numeric($min) && $min >0)
	  {
		$seconds += ($min * 60);
	  }  
	  if(is_numeric($sec) && $sec >0)
	  {
		$seconds += ($sec);
	  }
	  
	return $seconds;
	}
	
	function SecondsToTime($time)
	{
	  if(is_numeric($time))
	  {
		$value = array("years" => 0, "days" => 0, "hours" => 0, "minutes" => 0, "seconds" => 0);
		if($time >= 31556926)
		{
		  $value["years"] = floor($time/31556926);
		  $time = ($time%31556926);
		}
		if($time >= 86400){
		  $value["days"] = floor($time/86400);
		  $time = ($time%86400);
		}
		if($time >= 3600){
		  $value["hours"] = (floor($time/3600)); // <10)?"0".floor($time/3600):floor($time/3600);
		  $time = ($time%3600);
		}
		if($time >= 60){
		  $value["minutes"] = (floor($time/60)); // <10)?"0".floor($time/60):floor($time/60);
		  $time = ($time%60);
		}
		$value["seconds"] = (floor($time)); // <10)?"0".floor($time):floor($time);
		return (array) $value;
	  }
	  else { return (bool) FALSE; }
	}
	
	
	function update_campaignsdata($updat_arr, $campaign_id)
	{
	
		$this->db->where('campaignid',$campaign_id);
		
		$this->db->update('ox_campaigns', $updat_arr);
		
		return TRUE;
	}
	
	
}
