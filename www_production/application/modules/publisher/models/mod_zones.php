<?php
class Mod_zones extends CI_Model
{ 
		var $table='ox_zones';
		
		/* Authorisation Check */
		function check_pub_authorisation($affid, $zoneid)
		{
			if($affid!=0)
			{
			    $this->db->where('affiliateid',$affid);
			}
			if($zoneid!=0)
			{
			    $this->db->where('zoneid',$zoneid);
			}
	
			$query = $this->db->get('ox_zones');
	
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
	
			 /* Retrieve Affiliates id from db*/ 
			 
			 function get_affiliateid($where,$offset=0, $limit=false)
			{
					
					$this->db->select('*');
					
					if($limit != false)
					{
						$this->db->limit($limit, $offset);
					}
					
					$this->db->where($where);
					
					$query =$this->db->get('ox_affiliates');
					
					if($query->num_rows >0)
					{
						return $query->result();
					}
					else
					{
						return 0;
					}
			
			}
			
			function get_affiliate($affiliateid)
			{
			
				$this->db->select('*');
				
				
				$this->db->where('affiliateid', $affiliateid);
				
				$query =$this->db->get('ox_affiliates');
				
				if($query->num_rows >0)
				{
					return $query->result();
				}
				else
				{
					return 0;
				}
			}
			
			/* Retrieve zones list from db*/
			
			function list_zones($where, $offset=0, $limit=false)
			{
				
					if($limit != false){
						$this->db->limit($limit, $offset);
					}
					$this->db->where($where);
					
					$this->db->order_by('zoneid','desc');
					
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
			
			/* Retrieve zones from db to edit*/

			function get_edit_zones($affiliateid,$zoneid)
			{
			
					$this->db->select('*');
					
					$this->db->where( 'zoneid', $zoneid);
					
					$this->db->where('affiliateid',$affiliateid);
				
					$query = $this->db->get($this->table);
					if($query->num_rows()>0)
					{
						return $query->result();
					}
					else
					{
						return 0;
					}
					
			 }
			
			/*function edit_zones2($affiliateid)
			{
			
					$this->db->select('*');
					
					$this->db->where( 'affiliateid', $affiliateid);
				
					$query = $this->db->get('ox_affiliates');
					
					return $query->result();
					
			 }*/
			 
			 /* update zones to db*/
			 
			function update_zones($zonename, $pricing, $zoneid,$revenue_type,$rtb=0)
			{
			
					$data = array(	
								'zonename' =>mysql_real_escape_string($zonename),
								'pricing' =>mysql_real_escape_string($pricing),
								'rtb'	  =>mysql_real_escape_string($rtb),
								'updated'=>mysql_real_escape_string(date("Y-m-d H:i:s")),
								'revenue_type'=>$revenue_type
								);
					
					$this->db->where( 'zoneid', $zoneid);
					$this->db->or_where('master_zone', $zoneid);
	
					$this->db->update($this->table, $data);
				
					
			 }
			
			/* delete zones from db*/

			
			function delete_zones($zoneid)
			{
			
					if(is_array($zoneid))
				
					{
					
						foreach($zoneid as $zid)
						
						{
						
							
							
							//GET ALL CHILD ZONE IF EXISTS
							
							$this->db->where( 'master_zone', $zid);
							$query = $this->db->get('ox_zones');
							
							if($query->num_rows() > 0){
								$child_zones = $query->result();
								
								// REMOVE ALL MAPPAINGS BASED ON CHILD ZONES OF SELECTED ZONE ID
								
								foreach($child_zones as $czdata){
									$c_zone_id	=	$czdata->zoneid;
									
									$tables = array(
											'ox_data_bkt_a', 
											'ox_data_bkt_c', 
											'ox_data_bkt_m',
											'ox_data_summary_ad_hourly',
											'ox_data_bkt_country_c',
											'ox_data_bkt_country_m',
											'ox_data_intermediate_ad',
											'ox_ad_zone_assoc',
											'ox_placement_zone_assoc'
									   );
									$this->db->where('zone_id', $c_zone_id);
									$this->db->delete($tables);
									
									$this->db->where('zoneid',$c_zone_id);
									$this->db->delete('oxm_report');
									
								}
							}
							// REMOVE ALL MAPPAINGS BASED ON SELECTED ZONE ID
							
							$tables = array(
											'ox_data_bkt_a', 
											'ox_data_bkt_c', 
											'ox_data_bkt_m',
											'ox_data_summary_ad_hourly',
											'ox_data_bkt_country_c',
											'ox_data_bkt_country_m',
											'ox_data_intermediate_ad',
											'ox_ad_zone_assoc',
											'ox_placement_zone_assoc'
									   );
							$this->db->where('zone_id', $zid);
							$this->db->delete($tables);
							
							$this->db->where('zoneid',$zid);
							$this->db->delete('oxm_report');
							
							// DELETE SELECTED ZONE DETAILS AND ITS CHILD ZONES
							
							$this->db->where('zoneid', $zid);
							$this->db->or_where('master_zone', $zid);
							$this->db->delete($this->table);
							
							//DELETE ZONE AND ITS CHILD MAPPING 
							$this->db->where('masterzoneid',$zid);
							$this->db->delete('djx_mobilezones');
							
						}
					}
					
					else
					{
					
					
						//GET ALL CHILD ZONE IF EXISTS
							
							$this->db->where( 'master_zone', $zoneid);
							$query = $this->db->get('ox_zones');
							
							if($query->num_rows() > 0){
								$child_zones = $query->result();
								
								// REMOVE ALL MAPPAINGS BASED ON CHILD ZONES OF SELECTED ZONE ID
								
								foreach($child_zones as $czdata){
									$c_zone_id	=	$czdata->zoneid;
									
									$tables = array(
											'ox_data_bkt_a', 
											'ox_data_bkt_c', 
											'ox_data_bkt_m',
											'ox_data_summary_ad_hourly',
											'ox_data_bkt_country_c',
											'ox_data_bkt_country_m',
											'ox_data_intermediate_ad',
											'ox_ad_zone_assoc',
											'ox_placement_zone_assoc'
									   );
									$this->db->where('zone_id', $c_zone_id);
									$this->db->delete($tables);
									
									$this->db->where('zoneid',$c_zone_id);
									$this->db->delete('oxm_report');
									
								}
							}
						
							// REMOVE ALL MAPPAINGS BASED ON SELECTED ZONE ID
							
							$tables = array(
											'ox_data_bkt_a', 
											'ox_data_bkt_c', 
											'ox_data_bkt_m',
											'ox_data_summary_ad_hourly',
											'ox_data_bkt_country_c',
											'ox_data_bkt_country_m',
											'ox_data_intermediate_ad',
											'ox_ad_zone_assoc',
											'ox_placement_zone_assoc'
									   );
							$this->db->where('zone_id', $zoneid);
							$this->db->delete($tables);
							
							$this->db->where('zoneid',$zoneid);
							$this->db->delete('oxm_report');
							
							// DELETE SELECTED ZONE DETAILS AND ITS CHILD ZONES							
							$this->db->where('zoneid', $zoneid);
							$this->db->or_where('master_zone', $zoneid);
							$this->db->delete($this->table);
							
							//DELETE ZONE AND ITS CHILD MAPPING 
							$this->db->where('masterzoneid',$zoneid);
							$this->db->delete('djx_mobilezones');
					
					}
					
			}	
			
			/* Retrieve Affiliates List */
			
			function get_affiliates_list()
			{
					$this->db->select('affiliateid,contact');
			
					$this->db->order_by('ox_affiliates.affiliateid','asc');
			
					$query = $this->db->get('ox_affiliates');
			
					return $query->result();
			}
			
			
			
			/* Retrieve pricing model List */
			
			function get_pricing_model_list()
			{
					$this->db->select('revenue_id,revenue_type,revenue_type_value');
			
					$this->db->order_by('da_inventory_revenue_type.revenue_id','asc');
			
					$query = $this->db->get('da_inventory_revenue_type');
			
					return $query->result();
			}
			
			/* Retrieve pricing model to edit */
			
			function get_pricing_model($zoneid)
			{
			
					$this->db->select('revenue_type,pricing');
			
					$this->db->where('zoneid',$zoneid);
			
					$query = $this->db->get('ox_zones');
			
					return $query->result();
					
			}

			function get_width_height($zoneid)
			{
			
					$this->db->select('width,height');
			
					$this->db->where('zoneid',$zoneid);
			
					$query = $this->db->get('ox_zones');

					if($query->num_rows()>0)
					{
						$result	= $query->result();
						return $result[0];
					}
					else
					{
						return FALSE;
					}
					
			}
			
			
			
			function get_model($model_value)
			{
					$this->db->select('revenue_type');
					$this->db->where('revenue_type_value',$model_value);
					$query = $this->db->get('da_inventory_revenue_type');
					$temp		=	$query->row();
					
					return $temp->revenue_type;
			}
			
			/* Insert zone data */
			
			function insert_zonedata($in_data)
			{ 
				
				$this->db->insert('ox_zones',$in_data);
			
				if($this->db->affected_rows()>0)
				{
					return $this->db->insert_id();
				}
				else
				{
					return false;
				}
	
			}
			
			function get_bansize($bsize)
			{
					$this->db->select('*');
			
					$this->db->where('id',$bsize);
			
					$query = $this->db->get('djx_banner_sizes');
							
					return $query->result();
			}
			
			
			/* Get Bannersize */
			
			function get_banner_sizes($where='')
			{
			
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
			
			/* Insert zone master */
			
			function insert_zonemaster($ins)
			{
			
			$this->db->insert('djx_mobilezones',$ins);
			
				if($this->db->affected_rows()>0)
				{
			
					return $this->db->insert_id();
				}
				
				else
				{
			
					return FALSE;
				}
			}
			
			function list_linked_campaign_data($affiliateid=0, $zoneid=0, $limit=FALSE, $offset=0)
			{
				$t = $this->get_records($select='affiliateid,width,height,delivery,revenue_type,rtb', "ox_zones", array("zoneid"=>$zoneid));
				
				$category = $this->db->select('oac_category_id')->get_where('ox_affiliates',array('affiliateid'=>$t->affiliateid))->row();
				$cat = explode(',',$category->oac_category_id);
				//echo "<pre>"; print_r($cat); exit;
				if(!empty($category->oac_category_id))
				{
					$this->db->select("oxc.clientid,oxcl.clientname,oxb.bannerid,oxb.description,oxb.campaignid,oxc.campaignname,oxb.width,oxb.height,oxz.master_zone,oxz.revenue_type,oxz.zonename,oxz.zoneid,");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->where('oxz.zoneid',$zoneid);
					$this->db->where('oxz.affiliateid',$affiliateid);
					$this->db->where('oxb.adminstatus','0');
					if($t->delivery  == 3){
						$this->db->where_in('master_banner',array(-1));
					}
					else
					{
						$this->db->where_in('master_banner',array(-2,-3));
					}

					if($t->revenue_type==1 || $t->revenue_type==2)
					{
						if($t->rtb==1)
						{
							$this->db->where('oxc.rtb',1);
						}
						else
						{
							$this->db->where('oxc.rtb',0);
						}
					}
					else
					{
						$this->db->where('oxc.rtb',0);
					}
					$this->db->where_in('oxc.catagory',$cat);
					$this->db->group_by("oxc.campaignid");
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				else
				{
					$this->db->select("oxc.clientid,oxcl.clientname,oxb.bannerid,oxb.description,oxb.campaignid,oxc.campaignname,oxb.width,oxb.height,oxz.master_zone,oxz.revenue_type,oxz.zonename,oxz.zoneid,");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->where('oxz.zoneid',$zoneid);
					$this->db->where('oxz.affiliateid',$affiliateid);
					$this->db->where('oxb.adminstatus','0');
					if($t->delivery  == 3){
						$this->db->where_in('master_banner',array(-1));
					}
					else
					{
						$this->db->where_in('master_banner',array(-2,-3));
					}

					if($t->revenue_type==1 || $t->revenue_type==2)
					{
						if($t->rtb==1)
						{
							$this->db->where('oxc.rtb',1);
						}
						else
						{
							$this->db->where('oxc.rtb',0);
						}
					}
					else
					{
						$this->db->where('oxc.rtb',0);
					}
					$this->db->where('oxc.catagory IS NULL');
					$this->db->group_by("oxc.campaignid");
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				$query = $this->db->get();
				
				if($limit != FALSE){
					$this->db->limit($limit, $offset);
				}	
				
				if($query->num_rows >0)
				{
			
					return $query->result();
				}
				else
				{
			
					return 0;
				}
			}
			
			function list_linked_banner_data($affiliateid=0, $zoneid=0, $limit=FALSE, $offset=0)
			{
				$t = $this->get_records($select='affiliateid,width,height,delivery,revenue_type,rtb', "ox_zones", array("zoneid"=>$zoneid));
				
				$category = $this->db->select('oac_category_id')->get_where('ox_affiliates',array('affiliateid'=>$t->affiliateid))->row();
				$cat = explode(',',$category->oac_category_id);
				//echo "<pre>"; print_r($cat); exit;
				if(!empty($category->oac_category_id))
				{
					$this->db->select("oxc.clientid,oxcl.clientname,oxb.bannerid,oxb.description,oxb.campaignid,oxc.campaignname,oxb.width,oxb.height,oxz.master_zone,oxz.revenue_type,oxz.zonename,oxz.zoneid,");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->where('oxz.zoneid',$zoneid);
					$this->db->where('oxz.affiliateid',$affiliateid);
					$this->db->where('oxb.adminstatus','0');
					if($t->delivery  == 3){
						$this->db->where_in('master_banner',array(-1));
					}
					else
					{
						$this->db->where_in('master_banner',array(-2,-3));
					}

					if($t->revenue_type==1 || $t->revenue_type==2)
					{
						if($t->rtb==1)
						{
							$this->db->where('oxc.rtb',1);
						}
						else
						{
							$this->db->where('oxc.rtb',0);
						}
					}
					else
					{
						$this->db->where('oxc.rtb',0);
					}
					$this->db->where_in('oxc.catagory',$cat);
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				else
				{
					$this->db->select("oxc.clientid,oxcl.clientname,oxb.bannerid,oxb.description,oxb.campaignid,oxc.campaignname,oxb.width,oxb.height,oxz.master_zone,oxz.revenue_type,oxz.zonename,oxz.zoneid,");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb', "oxc.campaignid = oxb.campaignid");
					$this->db->join('ox_zones as oxz', "oxz.height = oxb.height AND oxz.width=oxb.width");
					$this->db->join('ox_clients as oxcl', "oxc.clientid = oxcl.clientid AND oxc.revenue_type=oxz.revenue_type");
					$this->db->where('oxz.zoneid',$zoneid);
					$this->db->where('oxz.affiliateid',$affiliateid);
					$this->db->where('oxb.adminstatus','0');
					if($t->delivery  == 3){
						$this->db->where_in('master_banner',array(-1));
					}
					else
					{
						$this->db->where_in('master_banner',array(-2,-3));
					}
					if($t->revenue_type==1 || $t->revenue_type==2)
					{
						if($t->rtb==1)
						{
							$this->db->where('oxc.rtb',1);
						}
						else
						{
							$this->db->where('oxc.rtb',0);
						}
					}
					else
					{
						$this->db->where('oxc.rtb',0);
					}

					$this->db->where('oxc.catagory IS NULL');
					$this->db->order_by('oxc.campaignid ','DESC');
				}
				$query = $this->db->get();
	
				if($limit != FALSE){
					$this->db->limit($limit, $offset);
				}	
		
				if($query->num_rows >0)
				{
			
					return $query->result();
				}
				else
				{
			
					return 0;
				}
			}
			
			
			function getAssoc($zone_id)
			{
		
				
		
				$this->db->select('zone_id, ad_id,oxb.campaignid')->from('ox_ad_zone_assoc as oxaza');
				$this->db->join('ox_banners as oxb','oxaza.ad_id=oxb.bannerid');
				$this->db->where("zone_id",$zone_id);
				$query =$this->db->get();

				//echo $this->db->last_query();exit;
			
				if($query->num_rows >0)
				{
					$i=1;
				   
					foreach($query->result() as $assoc_list)
					{
					  $ids['zone'][$i]  		=$assoc_list->zone_id;
					  $ids['ad'][$i] 			=$assoc_list->ad_id;
					  $ids['campaign_id'][$i] 	=$assoc_list->campaignid;
					  $i++;
					}
				}
				
				else
				{
			
					$ids =false;
				}
		
				return $ids;
			}
			
			function get_parent_child_zones($zone_id){
					$this->db->select("zoneid");
					$this->db->where('master_zone',$zone_id);
					$this->db->or_where('zoneid',$zone_id);
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
			
			function del_ads_placement_mapping($sel_zone_list)
			{
				 $this->db->where_in('zone_id',$sel_zone_list);				 
				 $this->db->delete("ox_ad_zone_assoc");
				
				 $this->db->where_in('zone_id',$sel_zone_list);				 
				 $this->db->delete("ox_placement_zone_assoc");
				 
				 return TRUE;
			}
			
			function insert_zone_placement_assoc($ins)
			{
				$this->db->insert('ox_placement_zone_assoc',$ins);
				if($this->db->affected_rows()>0)
				{
					return $this->db->insert_id();
				}
				else
				{
					return false;
				}
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
			
			function get_child_zones($zone_id){
					$this->db->select("zoneid,zonename,height,width,revenue_type");
					$this->db->where('master_zone',$zone_id);
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
			
			function get_banners_list($_id,$ref=false){
					if($ref != false AND $ref=="banners"){
						$this->db->select("bannerid,width,height,master_banner");
						$this->db->where('bannerid',$_id);
						$this->db->or_where('master_banner',$_id);
						$query = $this->db->get('ox_banners');
					}
					else
					{
						$this->db->select("bannerid,width,height,master_banner");
						$this->db->where('campaignid',$_id);
						$query = $this->db->get('ox_banners');
					}
					
					if($query->num_rows>0)
					{
						return $query->result();
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
			
			
		   function get_linked_banners_by_campaigns($zone_id){
				
				$this->db->select("oxb.bannerid,oxc.campaignid,oxc.campaignname,oxb.description,oxb.filename,oxb.storagetype,oxb.bannertext,oxb.imageurl");
				$this->db->from('ox_ad_zone_assoc as oxza');
				$this->db->join('ox_banners as oxb','oxza.ad_id=oxb.bannerid');
				$this->db->join('ox_campaigns as oxc','oxb.campaignid=oxc.`campaignid`');
				$this->db->where("zone_id",$zone_id);
				$this->db->where("link_type !=",0);
				$this->db->where("adminstatus",0);
				$query =$this->db->get();
				
				if($query->num_rows >0)
				{
					return $query->result();
				}
				else
				{
					return false;
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
			
		 function campaign_revenue_type($banner_id){
					$this->db->select("oxc.revenue_type");
					$this->db->from('ox_campaigns as oxc');
					$this->db->join('ox_banners as oxb','oxc.campaignid = oxb.campaignid');
					$this->db->where('oxb.bannerid',$banner_id);
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
		 
		 //Getting delivery value from ox_zones

			function get_delivery($id)

			{

				$this->db->select('delivery');

				

				$this->db->where('zoneid',$id);

				

				$query=$this->db->get('ox_zones');

				

				foreach($query->result() as $row)
				

					return $row->delivery;

			}		

		 //Getting delivery value from ox_zones

			function get_invocation_delivery($where)
			{
				$this->db->select('delivery');
				$this->db->where($where);
				$query	=$this->db->get('ox_zones');
				if($query->num_rows >0)
				{
					foreach($query->result() as $row)
					return $row->delivery;
				}
				else
				{
					return false;
				}
			}		

			//Getting master zone value from ox_zones			
			
			function get_master_zone($id)
			
			{
			
				$this->db->select('master_zone');
			
				
			
				$this->db->where('zoneid',$id);
			
				
			
				$query=$this->db->get('ox_zones');
			
				
			
				foreach($query->result() as $row)

			
					
			
					return $row->master_zone;
			
			}		

			/* Checking zones existing list for particular publisher from db*/
			
			function check_zones_for_affiliateid($where, $offset=0, $limit=false)
			{

					if($limit != false)
					{
						$this->db->limit($limit, $offset);
					}
					$this->db->where($where);
					
					$this->db->order_by('zoneid','desc');
					
					$query = $this->db->get('ox_zones');
					
					if($query->num_rows>0)
					{
						return TRUE;
					}
					else
					{
						return FALSE;
					}
			}

		public function bid_amount_details()
		{
			$this->db->select('bid');
			$this->db->where('pubid',$this->session->userdata('session_publisher_id'));
			$query=$this->db->get('oxm_pub_bidding');
			$t=$query->result();
			if($query->num_rows()>0)
			{
				return $t;
			}
			else
			{
				return FALSE;
			}
		}
		public function rtb_update_bidding()
		{
			$do=$this->multiple_entries_check();//multiple entries check
		
			if($do >= 1)
			{
				$data=array('bid'=>$this->input->post('rtb_rate'));
				$this->db->where('pubid',$this->session->userdata('session_publisher_id'));
				$this->db->update('oxm_pub_bidding',$data); 
				$this->session->set_flashdata('success', $this->lang->line('label_realtime_bid_rate_update_success'));
				return TRUE;
			}
			else
			{	
				$data=array('bid'=>$this->input->post('rtb_rate'),'pubid'=>$this->session->userdata('session_publisher_id'));
						
				$this->db->insert('oxm_pub_bidding',$data); 

				$this->session->set_flashdata('success', $this->lang->line('label_realtime_bid_rate_add_success'));			
			}
		}
	
		public function multiple_entries_check()
		{
			$this->db->select('*');
			$this->db->where('pubid',$this->session->userdata('session_publisher_id'));
			$query=$this->db->get('oxm_pub_bidding');
			$do=$query->num_rows();
			return $do;
		}
		public function banner_screen()
    {
		$this->db->select('*');
		$query = $this->db->get('oxm_mobilescreensizesettings');
		return $query->result_array();
		
	}
		
} 

