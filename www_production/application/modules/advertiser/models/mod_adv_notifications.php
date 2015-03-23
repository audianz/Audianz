<?php
class Mod_adv_notifications extends CI_Model 
{
		function __construct()
        {
            // Call the Model constructor
            parent::__construct();
        }
        /*Get Banner Notification List */
        function get_banner_list($adv_id='')
        {
			if($adv_id !='')
			{
					$master_banner = array('-1','-2','-3');
					$sql	=	 "SELECT oxb.campaignid as campaignid,oxb.bannerid as bannerid,oxb.adminstatus as adminstatus,oxb.description as bannername,oxb.master_banner as master_banner, ifnull(TIMESTAMPDIFF(YEAR, oxb.updated, NOW()),0) AS YEAR, ifnull(TIMESTAMPDIFF(MONTH, oxb.updated, NOW()),0) AS MONTH, ifnull(TIMESTAMPDIFF(DAY,oxb.updated,NOW()),0)AS DAY, ifnull(TIMESTAMPDIFF(HOUR, oxb.updated, NOW())-TIMESTAMPDIFF(DAY, oxb.updated, NOW())*24,0) AS HOUR, ifnull(TIMESTAMPDIFF(MINUTE,oxb.updated, NOW())-TIMESTAMPDIFF(HOUR, oxb.updated, NOW())*60,0) AS MINUTE";
					$sql .= "  FROM ox_banners as oxb";
					$sql .= "  JOIN ox_campaigns as oxc ON oxc.campaignid=oxb.campaignid";
					$sql .= " JOIN ox_clients as oxcl ON oxcl.clientid=oxc.clientid";
					$sql .= "  WHERE oxcl.clientid =".$adv_id."";
					$sql .= "  AND  oxb.master_banner IN ('-1','-2','-3')";
					$sql	.= " ORDER BY oxb.adminstatus DESC, oxb.updated DESC  LIMIT 5"; 
					
					$query	= $this->db->query($sql);
					
					return $query->result_array();
			}else{
				return FALSE;
			}
		}
 	 
  		// Retreive the total pending  bannerlist
		function get_total_count_pend_banners($adv_id='')
		{
				if($adv_id !='')
				{
						$total_pend_banner	=	0;
						$banner_list = $this->get_banner_list($adv_id);
						for($i=0;$i<count($banner_list);$i++)
						{
								if($banner_list[$i]['adminstatus']==1)
								{
										$total_pend_banner++;
								}
						}
					return $total_pend_banner;
				}
		}
}
