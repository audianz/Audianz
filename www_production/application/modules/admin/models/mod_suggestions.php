<?php
class mod_suggestions extends CI_Model 
{  

	function get_admin_email()
	{
		$query	=$this->db->get('oxm_admindetails');
		$rs		=$query->result();
		return  $rs[0]->Email;
	}
	
	function get_advertiser_info($id=0)
	{
		$query	=$this->db->get_where('ox_clients', array('clientid' =>$id));
		return $query->result();
	}

	function get_publisher_info($id =0)
	{
		$query	=$this->db->get_where('ox_affiliates', array('affiliateid' =>$id));
		return $query->result();
	}

	function get_read($type=0, $id=false) 
	{
 		if($id ==false) { $count	= $this->db->count_all_results('oxm_suggestions'); } // ->where('suggestion_status', $type)
		else { 
				$where	=array('suggestion_reciever' => $id); //'suggestion_status' =>$type, 
				$count	= $this->db->where($where)->count_all_results('oxm_suggestions');
		}
		return ($count)?$count:0;
	}
	
	function get_tabread($type=0, $id=false) 
	{
 		if($id ==false) { $count	= $this->db->count_all_results('oxm_suggestions'); } // ->where('suggestion_status', $type)
		else { 
				$where	=array('suggestion_status' =>$type, 'suggestion_reciever' => $id); // ,'suggestion_repliedid' => '0'
				$count	= $this->db->where($where)->count_all_results('oxm_suggestions');
		}
		return ($count)?$count:0;
	}

	function get_reply_status($id=false) 
	{
 		if($id !=false) 
		{
			$where	=array('suggestion_repliedid' => $id); 
			$count	= $this->db->where($where)->count_all_results('oxm_suggestions');
		}
		return ($count)?$count:0;
	}

	function get_tablist($id =false) 
	{
		if($id ==false) { $query		=$this->db->get_where('oxm_suggestions', array('suggestion_status' =>0)); } // , 'suggestion_repliedid' => '0'
		else { 
				$where	=array('suggestion_status' =>0, 'suggestion_reciever' => $id); // , 'suggestion_repliedid' => '0'
				
				$this->db->select('*');
				$this->db->where($where);
				$this->db->order_by("suggestion_date", "desc");
				$this->db->limit('5');
				$query	=$this->db->get('oxm_suggestions');
		}
		
        return $query->result();
	}

	function get_read_listing($where=false) 
	{
		
		if($where ==false) { 
			return false;
		}
		else 
		{ 
			$this->db->select('oxs.suggestion_id as suggestion_id, oxs.suggestion_sender as suggestion_sender, oxs.suggestion_reciever as suggestion_reciever, oxs.suggestion_subject as subject, oxs.suggestion_content as content, oxs.suggestion_sendertype as type, oxs.suggestion_repliedid as replied, oxs.suggestion_date as suggestion_date');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->order_by('oxs.suggestion_date', 'DESC'); //, 'oxs.suggestion_date'=> 'DESC'));
		    $this->db->where($where);
			$query = $this->db->get();
        	return $query->result();
		}
		
	}

	function get_sent_messages($where=false) 
	{
		if($where ==false)
		{	
			return false;
		}
		else 
		{
			$this->db->select('oxc.clientid as senderid, oxc.clientname as sender, oxc.email as sendermail, oxs.suggestion_id as suggestion_id, oxs.suggestion_reciever as suggestion_reciever, oxs.suggestion_subject as subject, oxs.suggestion_content as content, oxs.suggestion_sendertype as type, oxs.suggestion_repliedid as replied,  oxs.suggestion_date as suggestion_date');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->join('ox_clients as oxc', "oxs.suggestion_sender = oxc.clientid"); 
			$this->db->order_by('oxs.suggestion_date', 'DESC');
		    $this->db->where($where);
			$query = $this->db->get();
        	return $query->result();
		}
		
	}
	function get_sent_messages_count($where=false, $limit=3, $offset=0) 
	{
		if($where ==false)
		{	
			return false;
		}
		else 
		{
			$this->db->select('oxc.clientid as senderid, oxc.clientname as sender, oxc.email as sendermail, oxs.suggestion_id as suggestion_id, oxs.suggestion_reciever as suggestion_reciever, oxs.suggestion_subject as subject, oxs.suggestion_content as content, oxs.suggestion_sendertype as type, oxs.suggestion_repliedid as replied,  oxs.suggestion_date as suggestion_date');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->join('ox_clients as oxc', "oxs.suggestion_reciever = oxc.clientid"); 
			$this->db->order_by('oxs.suggestion_date', 'DESC');
		    $this->db->where($where);
			$this->db->where(array('oxs.suggestion_sender' =>2));
			$this->db->limit($limit, $offset);
			$query = $this->db->get();
        	return $query->num_rows();
		}
		
	}
	function get_sent_messages_content($where=false) 
	{
		if($where ==false)
		{	
			return false;
		}
		else 
		{
			$this->db->select('oxs.suggestion_subject as subject, oxs.suggestion_content as content');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->where($where);
			$query = $this->db->get();
        	return $query->result();
		}
		
	}
	
	function get_read_advertiser_listing($where=false) 
	{
		if($where ==false) {	
			return false;
		}
		else 
		{
			$this->db->select('oxc.clientid as senderid, oxc.clientname as sender, oxc.email as sendermail, oxs.suggestion_id as suggestion_id, oxs.suggestion_reciever as suggestion_reciever, oxs.suggestion_subject as subject, oxs.suggestion_content as content, oxs.suggestion_sendertype as type, oxs.suggestion_repliedid as replied,  oxs.suggestion_date as suggestion_date');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->join('ox_clients as oxc', "oxs.suggestion_reciever = oxc.clientid"); // AND oxs.suggestion_repliedid=0"); // AND oxs.suggestion_status=0");
			$this->db->order_by('oxs.suggestion_date', 'DESC'); //, 'oxs.suggestion_date'=> 'DESC'));
		    $this->db->where($where);
			$this->db->where(array('oxs.suggestion_sender' =>2, 'oxs.suggestion_sendertype' =>'MANAGER'));
			$query = $this->db->get();
        	return $query->result();
		}
		
	}
	
	function get_read_publisher_listing($where=false, $limit=3, $offset=0) 
	{
		
		if($where ==false) {	
			return false;
		}
		else 
		{ 
			$this->db->select('oxc.affiliateid as senderid, oxc.contact as sender, oxc.email as sendermail, oxs.suggestion_id as suggestion_id, oxs.suggestion_reciever as suggestion_reciever, oxs.suggestion_subject as subject, oxs.suggestion_content as content, oxs.suggestion_sendertype as type, oxs.suggestion_repliedid as replied,  oxs.suggestion_date as suggestion_date');
			$this->db->from('oxm_suggestions as oxs');
			$this->db->join('ox_affiliates as oxc', "oxs.suggestion_reciever = oxc.affiliateid"); // AND oxs.suggestion_repliedid=0"); // AND oxs.suggestion_status=0");
			$this->db->order_by('oxs.suggestion_status', 'ASC'); //, 'oxs.suggestion_date'=> 'DESC'));
		    $this->db->where($where);
$this->db->where(array('oxs.suggestion_sender' =>2, 'oxs.suggestion_sendertype' =>'MANAGER'));
			$this->db->limit($limit, $offset);
			$query = $this->db->get();
        	return $query->result();
		}
		
	}
	
   function insert($data)
   {
   	 $this->db->insert("oxm_suggestions", $data);
	 $status =$this->db->affected_rows();
	 if($status >0) { return '1'; } else { return '0'; }
   }	
	
   function set_read_status($id=0)
   {
     $this->db->where('suggestion_id', $id);
   	 $this->db->update("oxm_suggestions", array('suggestion_status' =>'1'));
	 $status =$this->db->affected_rows();
	 if($status >0) { return '1'; } else { return '0'; }
   }	
	
} // suggestions class end
