<?php

 class Mod_websites extends CI_Model 
{
	
	 var $check='';
	 
	 var $check1='0';
	 
	 function __construct()
    	{
        	// Call the Model constructor
        	parent::__construct();
    	} 

 //Lsiting publisher Site
	function get_site_list($filter, $id=0, $offset=0, $limit=50)
			{
				
				$this->db->limit($limit,$offset);
				$this->db->select('*');
				$this->db->from ('ox_affiliates as oxa');
				$this->db->join('oxm_userdetails as oxu', 'oxa.account_id=oxu.accountid');
				$this->db->where('oxu.websitename !=',$this->check);
				$this->db->where('oxu.accountid =', $id);
				$this->db->where('oxu.websitename !=',$this->check1);
				$this->db->order_by("oxu.id","desc");
				
				$query=$this->db->get();
				return $query->result();
			}
			
	//Listing countires
	function list_country()
		{
		
			$this->db->select('code,name');
			
			$this->db->order_by('name','ASC');
			
			$query=$this->db->get('djx_geographic_locations');	
			
			return $query->result();
		}	

	
//nserting records into respective tables	-ox_accounts	
	function insert_accounts($acc_type,$acc_name)
		{
			
			$data=array('account_id'=>'','account_type' =>$acc_type,'account_name'=>$acc_name);
				
			
			$this->db->insert('ox_accounts',$data);
			
			
				if($this->db->affected_rows() > 0)
					{
						return $this->db->insert_id();
					}
				else
					{
						return false;
					}	


		}	

	//getting a record from oxm_userdetails table for edit operation
	function get_site($id) 
		{
			
			$this->db->select('*');
			
			$this->db->from('oxm_userdetails as ou');
			
			$this->db->join('ox_affiliates as oa','oa.account_id=ou.accountid');
			
			$this->db->where('id',$id);
			
			$query=$this->db->get();
						
			 return $query->result();
							
		}

//Inserting records into respective tables	:oxm_userdetails		
	function insert_record($record)
		{
			
			$this->db->insert('oxm_userdetails',$record);
			
			if($this->db->affected_rows() > 0)
				{
				
					return true;
				}	
			else
				{
					return false;
				}
						
		
		}
//Deleting records from respective tables:oxm_userdetails	
	function delete_site($id)
		{
			
			$this->db->where('id',$id);
			
			$this->db->delete('oxm_userdetails');
			
		}

//Deleting records from respective tables:ox_users		
	function delete_ox_users($id)
		{
			
			$this->db->where('default_account_id',$id);
			
			$this->db->delete('ox_users');
			
		}	
		
				
	//Deleting records from publisher's associated tables	
	function delete_assoc_tables($id)
		{
			
			$tables	=	array('ox_account_user_permission_assoc','ox_affiliates','ox_account_user_assoc','ox_accounts');
			
			$this->db->where('account_id',$id);
			
			$this->db->delete($tables);
		
		}	
//Inserting records into ox_accounts table		
	function insert_affiliate_record($data)
		{
		
			$this->db->insert('ox_affiliates',$data);	
			
			if($this->db->affected_rows() > 0)
				{
					return true;
				}	
			else
				{
					return false;
				}
		
		 
		 }

//Inserting records into ox_users table		 
	function insert_users_record($data)
		{
		
			$this->db->insert('ox_users',$data);	
			
			if($this->db->affected_rows() > 0)
				{
					return $this->db->insert_id();
				}	
			else
				{
					return false;
				}
		
		 
		 }
//Inserting records into ox_account_user_assoc				 
	function insert_assoc_record($data)
		{
		
			$this->db->insert('ox_account_user_assoc',$data);	
			
			if($this->db->affected_rows() > 0)
				{
					return true;
				}	
			else
				{
					return false;
				}	
					
		 
		 }	 	 	 
//Inserting records into ox_account_user_permission_assoc table				 
	function insert_permission_record($data)
		{
		
			$this->db->insert('ox_account_user_permission_assoc ',$data);						
			
		}
		
	//Updating ox_userdetails table
	function update_userdetails_record($id,$data)
		{
			
			$this->db->where('id',$id);
			
			$this->db->update('oxm_userdetails',$data);				 
			
		}
		
		//Updating ox_affiliates table	
	function update_affiliate_record($id,$data)
		{
			
			$this->db->where('account_id',$id);
			
			$this->db->update('ox_affiliates',$data);		 
			
		}
	
	//Updating ox_users table		
	function update_users_record($id,$data)
		{
			
			$this->db->where('default_account_id',$id);
			
			$this->db->update('ox_users',$data);		 
			
		}
		
		//Updating ox_accounts table
	function update_accounts($id,$name)
		{
			
			$this->db->where('account_id',$id);
			
			$this->db->update('ox_accounts',$name);			 
			
		}
		
		//Getting zone name from ox_zones table
		
//Getting admin email for sending notification mail	
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
														
//Getting account_id from oxm_userdetails table
	function get_account_id($where_arr)
		{
		
			
			$this->db->select('accountid');
			
			$this->db->where('id',$where_arr);
			
			$id=$this->db->get('oxm_userdetails');
			
			if($id->num_rows > 0)
				{
					
					$temp=$id->result();
					
					return $temp[0]->accountid;
				}
			
			else
				{
				
					return false;
				
				}
				
			}		
						

	//Function to check publisher-site duplication		
	function site_check($site='',$id='')
		{
			
			$this->db->where('websitename',$this->input->post('website_url'));
			
			$this->db->where('accountid !=',$this->input->post('account_id'));
			
			$query=$this->db->get('oxm_userdetails')->num_rows();
			
			return $query;
		
		}			
			
	//Starting of overall delete function
	function delete_publisher_site($delete_id)
		{
			
			if(is_array($delete_id))
				
			{	
				
					
					foreach($delete_id as $site_id)
						
						{
						
							$account_id=$this->get_account_id($site_id);
								
							$aff_id=$this->get_affiliateid($account_id);
								
							$zoneid=$this->get_zone_id($aff_id);
								
									foreach($zoneid as $zone_id)
										
										{
											$id	=	$zone_id->zoneid;
											$this->mod_websites->delete_related_bucket_tables($id);
										
										}
										
								$this->delete_zones($aff_id);		
								
								$this->mod_websites->delete_assoc_tables($account_id);
									
								$this->mod_websites->delete_ox_users($account_id);
								
								$this->mod_websites->delete_site($site_id);
								
								
							}
						
				 }			
		}
		//getting affiliate id from ox_affiliates table relating oxm_userdetails table for delete purpose
		function get_affiliate_id()
			{
			
				$this->db->select('*');
				
				$this->db->from ('ox_affiliates');
				
				$this->db->join('oxm_userdetails', 'ox_affiliates.account_id=oxm_userdetails.accountid');
				
				$query=$this->db->get();
				
				return $query->result();
			}
			
		
		//getting publisher name
		function get_publisher($id)
			{
				$this->db->select('username');
				
				$this->db->where('accountid',$id);
				
				$query=$this->db->get('oxm_userdetails');
				
				if($query->num_rows() > 0)
					
					{
						
						$temp=$query->result();
						
						$publisher=$temp[0]->username;	
						
						return $publisher;
					}
					
				else
				
					return false;
			}
			
			//getting zonename
			function get_zone_name($id)
				{
					$this->db->distinct('zonename');
					
					$this->db->where('affiliateid',$id);
					
					$query=$this->db->get('ox_zones');
					
					if($query->num_rows() > 0)
						{
						
							$temp=$query->result();
							
							$zone_name=$temp[0]->zonename;
							
							return $zone_name;
						}
				}			
		
		
		
		//getting affiliate id from ox_affiliate table refering account_id feild	
		function get_affiliateid($id)
			{
				$this->db->select('*');
				
				$this->db->where('account_id',$id);
				
				$query=$this->db->get('ox_affiliates');
				
				if($query->num_rows() > 0)
					
					{
						
						$temp=$query->result();
						
						$affiliate_id=$temp[0]->affiliateid;	
						
						return $affiliate_id;
					}
					
				else
				
					return false;
			}
			
		//getting zoneid from ox_zones for delete operation
		function get_zone_id($affiliate_id)
			{
				$this->db->select('zoneid,zonename'); 
				
				$this->db->where('affiliateid',$affiliate_id);
				
				$query=$this->db->get('ox_zones');
				
				$result	= 	$query->result();
				
				return $result;
			}
			
					
		function delete_related_bucket_tables($zoneid)
			{
		
				$tables	=	array('ox_data_bkt_a','ox_data_bkt_c','ox_data_bkt_country_c','ox_data_bkt_m','ox_data_bkt_country_m',																																						                    'ox_data_summary_ad_hourly','ox_data_intermediate_ad','ox_ad_zone_assoc');
				
				$this->db->where('zone_id',$zoneid);
				
				$this->db->delete($tables);
			
			}	
			
		
		//deleting a record from ox_zone  table based on affiliate_id	
		function delete_zones($affiliate_id)
			{	
				
				$this->db->where('affiliateid',$affiliate_id);
				
				$this->db->delete('ox_zones');
				
			}	
		//Ending of overall delete option	
			
}			

			