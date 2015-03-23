<?php
class mod_publisher_notifications extends CI_Model 
{  
	function get_publisher_notify($id =0)
	{
    	 $this->db->where(array('publisherid' =>$id));
		 $this->db->where('publisherid !=' ,'0');
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
