<?php
class mod_site extends CI_Model 
{  	
	public function get_menu(){
		
		$menu_list	= array();
		$child_list	= array();
		
		$this->db->select("id,menu_name,parent_id,pageid");	
		$this->db->from("`oxm_menu` as oxm");
		$this->db->join("oxm_pagedetails as oxp","oxm.id = oxp.menu_id AND oxp.status=1","left");
		$this->db->order_by("oxm.parent_id");
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach($query->result() as $data){
				if($data->parent_id == 0)
				{
					$menu_list[$data->id] = array(
													"id"=>$data->id,
													"name"=>$data->menu_name,
													"pid"=>$data->parent_id,
													"page_id"=>$data->pageid,
													"child"=>'');
				}
				else
				{
					if(isset($child_list[$data->parent_id])){
						array_push($child_list[$data->parent_id],array("id"=>$data->id,"name"=>$data->menu_name,"page_id"=>$data->pageid,"pid"=>$data->parent_id));
						$menu_list[$data->parent_id]["child"] = $child_list[$data->parent_id];
					}
					else
					{
						$child_list[$data->parent_id][] = array("id"=>$data->id,"name"=>$data->menu_name,"page_id"=>$data->pageid,"pid"=>$data->parent_id);
						$menu_list[$data->parent_id]["child"] =  $child_list[$data->parent_id];;
					}
					
				}
			
				
			}
		}
		
			return $menu_list;
	}
	
	public function get_page_content($page_id)
	{
		$this->db->where("pageid",$page_id);
		$query = $this->db->get("oxm_pagedetails");
		
		if($query->num_rows() > 0){
			$temp	= $query->result_array();
			return $temp[0];
		}
		else
		{
			return FALSE;
		}
		
	}
	
	public function get_child_parent($childID=0)
	{
		//echo $childID;exit;
		if($childID!=0)
		{
		
			$this->db->where('oxm_pagedetails.pageid',$childID);		
		
			$this->db->where('oxm_menu.parent_id !=','0');
		
			$this->db->select('oxm_menu.parent_id');
		
			$this->db->join('oxm_menu','oxm_menu.id=oxm_pagedetails.menu_id');
				
			$query = $this->db->get('oxm_pagedetails');
		
			if($query->num_rows()>0)
			{
				$child_parent	= $query->result_array();
				$cparent = $child_parent[0]['parent_id'];
			
				if($cparent!=0)
				{
					$this->db->where('oxm_pagedetails.menu_id',$cparent);
				}
			
				$this->db->select('oxm_pagedetails.pageid');
			
				$query1 = $this->db->get('oxm_pagedetails');
				if($query1->num_rows()>0)
				{
					$parentc	= $query1->result_array();
					$parent = $parentc[0]['pageid'];
					return $parent;
				}
				else
				{
					return 0;
				}
			
			}
			else
			{
				return -1;
			}		
		}
		else
		{
			return 'home';
		} 
	}

     	/**
	 * This method is used to check for valid email of adveriser
	 */
	
	public function check_adver($email)
	{
		$this->db->where('email',$email);
		$this->db->where('accounttype','ADVERTISER');
		$this->db->from("oxm_userdetails");
		$query= $this->db->get();
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	
	}
	
	
	
	function forget_password_process($email)
	{
		$useremail=$email;
		$where_arr=array('ox_users.email_address' => $useremail	);
		$this->db->select('oxm_userdetails.email,oxm_userdetails.accountid,ox_users.username,ox_users.password' );
		$this->db->from('oxm_userdetails');
		$this->db->join('ox_accounts',  "ox_accounts.account_id =oxm_userdetails.accountid  AND ox_accounts.account_type = 'ADVERTISER'");
		$this->db->join('ox_users', "ox_users.default_account_id=oxm_userdetails.accountid");
		$this->db->where($where_arr);
		$query	=$this->db->get();
			
		foreach($query->result() as $row)
		{
			$default_account_id 	=$row->accountid;
			$db_email				=$row->email;
			$db_username			=$row->username;

			if( $db_email == $useremail)
			{
				$password = $this->mod_site->rand_string(6);
				$forget_password = array('username' => $db_username,
						'email' => $db_email,
						'password' =>$password );

				$db_password=md5($password);
				$userDet=array('password'=>$db_password);
				$this->db->where("email",$useremail);
				$this->db->where("accountid",$default_account_id);
				$this->db->update('oxm_userdetails',$userDet);
				$this->db->where("email_address",$useremail);
				$this->db->where("default_account_id",$default_account_id);
				$this->db->update('ox_users',$userDet);
				return $forget_password;

			}
			else
			{
				return FALSE;
			}

		}
	}


	/**
	 ** This method is used to get admin email address
	 ** @author Soumya
	 **/
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


       //To auto-create password
	function rand_string( $length ) {

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen( $chars );
		$str='';
		for( $i = 0; $i < $length; $i++ ) {

			$str.= $chars[ rand( 0, $size - 1 ) ];

		}
		return $str;

	} 

}
