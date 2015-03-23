<?php
class mod_advertiser_notifications extends CI_Model 
{  
	function get_advertiser_notify($id =0)
	{
    	 $this->db->where(array('clientid' =>$id));
		 $this->db->where('clientid !=' ,'0');
         $query=$this->db->get('oxm_notification');
         //return $query->result();
		 $val	=$query->result();
         if($query->num_rows() >0) { return $val; } else { return NULL; } 
	}
			
	/*-------------------------------------------
		UPDATE Notify settings TABLE
	---------------------------------------------*/
	
	function notify_process($op='insert', $data, $where='')
	{
		if($op =='insert')
		{
			$this->db->insert('oxm_notification',$data);
			$ins_id	=	$this->db->insert_id();
			return $ins_id;
		}
		else if($op =='update')
		{
			$this->db->where($where);
			$this->db->update('oxm_notification', $data);
			return TRUE;
		}
	
	}	
}   
