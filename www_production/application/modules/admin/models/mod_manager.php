<?php
class Mod_manager extends CI_Model { 


	function get_managers($status="all",$offset=0,$limit=false){
		
				/*if($status=="active") 
				{
                       $this->db->select();            
                }
				else if($status=="inactive") 
				{
                        $this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxc.`contact` as contact_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance');
                        $this->db->from('ox_clients as oxc');
                        $this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
                        $this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
                        $this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
                        $this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid","left");
                        $this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid","left");
			$this->db->or_where('oxbal.accbalance', 0);
                        $this->db->or_where('oxcamp.status !=', 0);
			//$this->db->or_where('oxcamp.inactive !=', 0);
                        $this->db->or_where('oxban.status', 1);
			$this->db->order_by('oxc.clientid','DESC');
                        $this->db->group_by("oxc.clientid");
            
                }
				else if($status=="single"){
						$this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxc.`contact` as contact_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance');
                        $this->db->from('ox_clients as oxc');
                        $this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
                        $this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
                        $this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
                        $this->db->join('ox_campaigns as oxcamp', "oxcamp.clientid=oxc.clientid","left");
                        $this->db->join('ox_banners as oxban', "oxcamp.campaignid=oxban.campaignid","left");
						$this->db->where('oxc.`clientid`',$offset);
						$this->db->order_by('oxc.clientid','DESC');
						$this->db->group_by("oxc.clientid");
				}
                else
                {
                        $this->db->select('oxc.`clientid` as client_id,oxc.`clientname` as advertiser_name,oxc.`contact` as contact_name,oxu.`username` as user_name,oxc.`email` as email, oxc.account_id,oxbal.accbalance  as acc_balance');
                        $this->db->from('ox_clients as oxc');
                        $this->db->join('oxm_userdetails as oxu', "oxu.accountid = oxc.account_id AND accounttype='ADVERTISER'");
                        $this->db->join('ox_accounts as oxacc', "oxacc.account_id=oxc.account_id");
                        $this->db->join('oxm_accbalance as oxbal', "oxc.clientid = oxbal.clientid","left");
						$this->db->group_by("oxc.clientid");
						$this->db->order_by('oxc.clientid','DESC');
                }

                if($limit != false)
                {
                    #$this->db->limit($limit, $offset);
                }*/
                
        $this->db->select('oxa.name, oxa.account_id, oxu.username, oxa.email, oxa.active, oxa.agencyid, oxma.block_user');
        
        $this->db->from('ox_agency as oxa');
        
        $this->db->join('ox_users as oxu', 'oxu.default_account_id=oxa.account_id');
        
        $this->db->join('oxm_admindetails as oxma', 'oxma.accountid=oxu.default_account_id');
        
        $this->db->join('ox_accounts as oxacc', 'oxacc.account_id=oxu.default_account_id');
        
        $this->db->where('oxa.account_id !=','2');
                
		$query = $this->db->get();
		
		//echo $this->db->last_query();exit;
		
        if($query->num_rows>0)
		{
			return $query->result();
		} 
		else
		{
			return FALSE;
		}
	}
	
	function get_manager($manager_id)
	{
		if($manager_id)
		{
			$this->db->where('ox_agency.agencyid',$manager_id);
		}
		$this->db->join('ox_agency','ox_agency.account_id=ox_users.default_account_id');
		
		$this->db->join('ox_accounts', 'ox_accounts.account_id=ox_agency.account_id');
		//$this->db->where('ox_users.admin_account_id', $this->session->userdata('sessaccadminid'));
		$this->db->where('ox_accounts.account_type', 'MANAGER');
		
		//$this->db->order_by('ox_users.contact_name','DESC');
		
		//$query = $this->db->get_where('ox_users', array('admin_account_id'=>$this->session->userdata('sessaccadminid'),'ox_users.account_type'=>'MANAGER'));
		$query = $this->db->get('ox_users');
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	

	function insert_manager($data){
		
		$cur_date=date("Y-m-d H:i:s");
		/*-------------------------------------------
			CREATE ACCOUNT  FOR MANAGER
		---------------------------------------------*/
		$acc_data		=	array(
							"account_type"	=>	'MANAGER',
							"account_name"	=>	text_db($data['username'])
							);
		$this->db->insert('ox_accounts',$acc_data);
		$account_id	=	$this->db->insert_id();		
				
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$userDet		=	array(
								"accountid"		=>	$account_id,
								"username"		=>	text_db($data['username']),
								"email"			=>	$data['username'].'@testmail.com',
								"password"		=>	md5($data['password']),
								"accounttype"		=>	'MANAGER'
						  	);
		
		$this->db->insert('oxm_userdetails',$userDet);		
		
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$userDet		=	array(
								"accountid"		=>	$account_id,
								"username"		=>	text_db($data['username']),
								"email"			=>	$data['username'].'@testmail.com',
								"password"		=>	md5($data['password'])
						  	);
		
		$this->db->insert('oxm_admindetails',$userDet);
		
		/*-------------------------------------------
			UPDATE CLIENTS TABLE
		---------------------------------------------*/
		$manDet	=	array(
							"email"			=>	$data['username'].'@testmail.com',
							"contact"		=>	text_db($data['username']),
							"name"	=>	text_db($data['name']),
							"account_id"	=>	$account_id,
							"updated" => $cur_date
							);
		
		$this->db->insert('ox_agency',$manDet);
		$agency_id	=	$this->db->insert_id();	
		
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/
		
		$oxUserDet	=	array(
							"contact_name"			=>	text_db($data['name']),
							"email_address"			=>	$data['username'].'@testmail.com',
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
		
		
		/* CREATING ADVERTISER FOR NEW MANAGER */
		
		$adv_acc_data		=	array(
							"account_type"	=>	'ADVERTISER',
							"account_name"	=>	text_db($data['username']).'_adv'
							);
		$this->db->insert('ox_accounts',$adv_acc_data);
		$adv_account_id	=	$this->db->insert_id();		
				
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$advuserDet		=	array(
								"accountid"		=>	$adv_account_id,
								"username"		=>	text_db($data['username']).'_adv',
								"email"			=>	$data['username'].'_adv@testmail.com',
								"password"		=>	md5($data['username'].'_adv'),
								"accounttype"		=>	'ADVERTISER'
						  	);
		
		$this->db->insert('oxm_userdetails',$advuserDet);		
		
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$clientuserDet		=	array(
								"agencyid"	=> $agency_id,
								"account_id"		=>	$adv_account_id,
								"clientname"		=>	text_db($data['name']),
								"contact"			=>	text_db($data['username']).'_adv',
								"updated"		=>	$cur_date,
								"email" => $data['username'].'_adv@testmail.com'
								
						  	);
		
		$this->db->insert('ox_clients',$clientuserDet);
		
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/
		
		$oxadvUserDet	=	array(
							"contact_name"			=>	text_db($data['name']),
							"email_address"			=>	$data['username'].'_adv@testmail.com',
							"username"				=>	text_db($data['username']).'_adv',
							"password"				=>	md5($data['username'].'_adv'),
							"default_account_id"	=>	$adv_account_id,
							"date_created"	=>	$cur_date
							);
		$this->db->insert('ox_users',$oxadvUserDet);
		
		$ox_adv_user_id	=	$this->db->insert_id();
		/*-------------------------------------------
			UPDATE USER ACCOUNT ASSOCIATION
		---------------------------------------------*/
		
		$ox_adv_acc_user_assoc	=	array(
							"account_id"	=>	$adv_account_id,
							"user_id"		=>	$ox_adv_user_id,
							"linked"		=>	$cur_date
							);
		
		$this->db->insert('ox_account_user_assoc',$ox_adv_acc_user_assoc);
		
		/* CREATING PUBLISHER FOR NEW MANAGER */
		
		$pub_acc_data		=	array(
							"account_type"	=>	'TRAFFICKER',
							"account_name"	=>	text_db($data['username']).'_pub'
							);
		$this->db->insert('ox_accounts',$pub_acc_data);
		$pub_account_id	=	$this->db->insert_id();		
				
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$pubuserDet		=	array(
								"accountid"		=>	$pub_account_id,
								"username"		=>	text_db($data['username']).'_pub',
								"websitename" => "http://www.".$data['username'].".com",
								"email"			=>	$data['username'].'_pub@testmail.com',
								"password"		=>	md5($data['username'].'_pub'),
								"accounttype"		=>	'TRAFFICKER'
						  	);
		
		$this->db->insert('oxm_userdetails',$pubuserDet);		
		
		/*-------------------------------------------
			CREATE RECORDS IN USER DETAILS TABLE
		---------------------------------------------*/
		$affuserDet		=	array(
								"agencyid"	=> $agency_id,
								"account_id"		=>	$pub_account_id,
								"name"		=>	text_db($data['name']),
								"contact"			=>	text_db($data['username']).'_pub',
								"website" => "http://www.".$data['username'].".com",
								"updated"		=>	$cur_date,
								"publishershare" => '20',
								"email" => $data['username'].'_pub@testmail.com',
								
						  	);
		
		$this->db->insert('ox_affiliates',$affuserDet);
		
		/*-------------------------------------------
			UPDATE OX USERS TABLE
		---------------------------------------------*/
		
		$oxaffUserDet	=	array(
							"contact_name"			=>	text_db($data['name']),
							"email_address"			=>	$data['username'].'_pub@testmail.com',
							"username"				=>	text_db($data['username']).'_pub',
							"password"				=>	md5($data['username'].'_pub'),
							"default_account_id"	=>	$pub_account_id,
							"date_created"	=>	$cur_date
							);
		$this->db->insert('ox_users',$oxaffUserDet);
		
		$ox_pub_user_id	=	$this->db->insert_id();
		/*-------------------------------------------
			UPDATE USER ACCOUNT ASSOCIATION
		---------------------------------------------*/
		
		$ox_aff_acc_user_assoc	=	array(
							"account_id"	=>	$pub_account_id,
							"user_id"		=>	$ox_pub_user_id,
							"linked"		=>	$cur_date
							);
		
		$this->db->insert('ox_account_user_assoc',$ox_aff_acc_user_assoc);
	}
	
	//Update Managers
	function update_manager($data)
	{
		$current_date = date('Y-m-d H:i:s');
		//update the given values into the array
		$contact_name = $data['name'];
		
		//Update the values at the ox_agency
		$update_age_array = array('name'=>$contact_name,
								'contact'=>$contact_name,
								'updated'=>$current_date);
		
		$where_age_arr = array('agencyid'=>$this->input->post('manager_id'));
		
		$this->db->update('ox_agency',$update_age_array,$where_age_arr);
				
		//Retreive the account id from ox_agency table
		$result = $this->db->select('account_id')->get_where('ox_agency',array('agencyid'=>$this->input->post('manager_id')))->row();
		
		$account_id = $result->account_id;
		
		
		//Update the values at the ox_accounts
		$update_acc_array = array('account_name'=>$contact_name);
		
		$where_acc_arr = array('account_id'=>$account_id);
		
		$this->db->update('ox_accounts',$update_acc_array,$where_acc_arr);
		
		//Update the values at ox_users
		$update_user_array = array('contact_name'=>$contact_name,
												'username'=>$this->input->post('username'),
										);
		
		$where_user_arr = array('default_account_id'=>$account_id);
		
		$this->db->update('ox_users',$update_user_array,$where_user_arr);
		 
		 return TRUE;
	}
	
	//Delete Managers
	function delete_manager($manager_id=FALSE)
	{
		if($manager_id)
		{
			$field_name = 'agencyid';
			
			//delete_data($manager_id,$field_name,$this->agency_tbl_name);
			//Retreive the account id from ox_agency table
			$result = $this->db->select('account_id')->get_where('ox_agency',array('agencyid' => $manager_id))->row();
			$adv = $this->db->select('account_id')->get_where('ox_clients',array('agencyid' => $manager_id))->row();
			$pub = $this->db->select('account_id')->get_where('ox_affiliates',array('agencyid' => $manager_id))->row();
			
			$account_id = $result->account_id;
			
			$where_agency = array('agencyid' => $manager_id);
			
			$this->db->delete('ox_agency', $where_agency);
			
			/* clients & affiliates delete */
			
			$this->db->delete('ox_clients',array('account_id'=>$adv->account_id));
			$this->db->delete('ox_accounts', array('account_id'=>$adv->account_id));
			$this->db->delete('ox_account_user_assoc', array('account_id'=>$adv->account_id));
			$this->db->delete('ox_users', array('default_account_id'=>$adv->account_id));
			$this->db->delete('oxm_userdetails', array('accountid '=> $adv->account_id));
			
			$this->db->delete('ox_affiliates',array('account_id'=>$pub->account_id));
			$this->db->delete('ox_accounts', array('account_id'=>$pub->account_id));
			$this->db->delete('ox_account_user_assoc', array('account_id'=>$pub->account_id));
			$this->db->delete('ox_users', array('default_account_id'=>$pub->account_id));
			$this->db->delete('oxm_userdetails', array('accountid '=> $pub->account_id));
			
			
			
			$where_account = array('account_id '=> $account_id);
			
			$this->db->delete('ox_accounts', $where_account);
			
			$this->db->delete('ox_account_user_assoc', $where_account);
			
			$where_user = array('default_account_id'=>$account_id);
			
			$this->db->delete('ox_users', $where_user);
			
			$where_acc = array('accountid '=> $account_id);
			
			$this->db->delete('oxm_userdetails', $where_acc);
			
			$this->db->delete('oxm_admindetails', $where_acc);
			
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
}
