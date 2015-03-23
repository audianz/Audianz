<?php
class Mod_optimisation extends CI_Model
{ 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
       
    }

		 /* function to get the page details */
	 function prox_page_details()
	 {
		$sql="SELECT * FROM `PROXIMITY_IMAGES` ";
		$query=$this->db->query($sql);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
	 }
	 
	 /* Add Proximity Campaign */
	function add_prox_camp($data,$path)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="INSERT INTO `AZ_PROXIMITY_CAMPAIGN`
			 (`client_id`,`camp_name`,`activate_time`,`end_time`,`pricing_model`,`message`,`budget`,`landing_image`,`weight`,`status_startdate`,`status_enddate`) 
			 VALUES ($clientid,'$data[campname]','$data[campstart]','$data[campend]','$data[pricing_model]','$data[message]','$data[budget]','$path','$data[weight]','$data[start_date]','$data[end_date]')";
		
		//echo $sql;
		$query=$this->db->query($sql);
		return $query;
	}
	
	/* Function to get the prox camp_list */
	function prox_camp_list($clientid)
	{
		$sql="SELECT * FROM `AZ_PROXIMITY_CAMPAIGN` WHERE client_id=$clientid ";
		//echo $sql;
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
			 return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	/*Page image detail list acc. to client for page 4 */
	function prox_page_list()
	 {
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="SELECT * FROM `PROX_IMAGES` WHERE client_id=$clientid";
		$query=$this->db->query($sql);
		if($query->num_rows>0)
		{
			return $query->result();
		}
		
		else
		{
			return FALSE;
		}
	 }
	 
	/* 
	 * To update page images details 
	 */ 
	function update_page($data)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$flag=0;
		//print_r($data);
		for($i=0;$i<count($data);$i++)
		{
			$page=$data[$i]->page;
			$path=$data[$i]->path;
			if($data[$i]->image==1)
			{
				$sql1="UPDATE `PROX_IMAGES`  SET image1='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query1=$this->db->query($sql1);
				
				if($query1==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==2)
			{
				$sql2="UPDATE `PROX_IMAGES`  SET image2='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query2=$this->db->query($sql2);
				if($query2==FALSE)
				{
					$flag=1;
				}
			}
			else 
			if($data[$i]->image==3)
			{
				$sql3="UPDATE `PROX_IMAGES`  SET image3='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query3=$this->db->query($sql3);
				if($query3==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==4)
			{
				$sql4="UPDATE `PROX_IMAGES`  SET image4='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query4=$this->db->query($sql4);
				if($query4==FALSE)
				{
					$flag=1;
				}
			}
			else
			if($data[$i]->image==5)
			{
				$sql5="UPDATE `PROX_IMAGES`  SET image5='$path' WHERE  page_id=$page AND  client_id=$clientid ";
				$query5=$this->db->query($sql5);
				if($query5==FALSE)
				{
					$flag=1;
				}
			}
		}
	}
	
	/* 
	 * To get proximity campaign details for editing
	 */
	function get_prox_camp($id)
	{
		$sql="SELECT * FROM `AZ_PROXIMITY_CAMPAIGN` WHERE campaign_id=$id";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
			 return $query->result();
		}
		else
		{
			return FALSE;
		}	
	}
	
	/* Update the proximity camp after edit process */
	function update_prox_camp($id,$data)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$sql="REPLACE INTO `AZ_PROXIMITY_CAMPAIGN`
			 (`campaign_id`,`client_id`,`camp_name`,`activate_time`,`end_time`,`pricing_model`,`message`,`budget`,`landing_image`,`weight`,`status_startdate`,`status_enddate`) 
			 VALUES ('$id',$clientid,'$data[campname]','$data[campstart]','$data[campend]','$data[pricing_model]','$data[message]','$data[budget]','$path','$data[weight]','$data[start_date]','$data[end_date]') ";
			 
		$query=$this->db->query($sql);
		return $query;

	}
	
	/* Delete Proximity campaigns */
	function delete_prox_camp($id)
	{
		$this->db->where('campaign_id', $id);
		$this->db->delete('AZ_PROXIMITY_CAMPAIGN');
	}
	
	/* To add keywords to campaign */
	function add_keywords($data)
	{
		 $this->db->insert('Keywords_targetting', $data);

        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
    
   /* To get networks mix for campaign optimization */
	function camp_optimization_list()
	{
		$sql="SELECT * FROM `Campaign_Optimize_Mix` ";
		$query=$this->db->query($sql);
		if($query->num_rows()>0)
		{
		      return $query->result();

		}
		else
		{
			return false;
		}
	}

	/* To insert the target impressions for each network  */
	function enter_target_impressions($camp,$mixId,$impression)
	{
		$sql="REPLACE INTO `Campaign_Target_Impressions_Mix` ( `Campaign_id`, `Mix_id`, `Target_impressions`, `Impressions_served`, `Clicks`)
			 VALUES ('".$camp."', '".$mixId."', '".$impression."', '0', '0')";
		$query=$this->db->query($sql);	 
		
        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
	}
	
	/* Delete keywords for selected campaign */
	function delete_camp_keywords($where=' ')
	{
		if($where !=0) 
		{
			$this->db->delete('Keywords_targetting', $where);
			$status	=$this->db->affected_rows();
             if($status >0)
             {
                    return true;
             }
             else
             {
                    return false;
             }
         }
        
      }
      
      /* To delete the target impressions for each network  */
      function deleteTargetImpression($where=' ')
      {
		  if($where !=0) 
		{
			$this->db->delete('Campaign_Target_Impressions_Mix', $where);
			$status	=$this->db->affected_rows();
             if($status >0)
             {
                    return true;
             }
             else
             {
                    return false;
             }
         }
        
      }
      
      /* To get the keywords for specific campaign */
      function get_keywords_list($camp_id)
      {
		 $sql="SELECT * FROM `Keywords_targetting` WHERE camp_id=$camp_id " ;
		 $query=$this->db->query($sql);
		 if($query->num_rows()>0)
			{
				return $query->result();

			}
			else
			{
				return false;
			}
	  }
	  
	  /* Update Target Impressions */
	  function update_target_impressions($camp,$mixId,$impression)
	{
		$sql=" UPDATE `Campaign_Target_Impressions_Mix` SET Target_impressions=$impression 
			   WHERE Campaign_id=$camp AND Mix_id=$mixId ";
		$query=$this->db->query($sql);	 
		
        if($this->db->affected_rows()>0)
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
	}
	

}

?>
