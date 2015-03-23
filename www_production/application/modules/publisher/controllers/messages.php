<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->sessid	=$this->session->userdata('session_publisher_id');
		$this->load->library('email');
		
		/* Models */
		//$this->load->model("mod_suggestions",'suggestions');
    }
	
	/* Suggestion Landing Page */
	public function index()
	{
		redirect('publisher/messages/messages_list');
	}
  
    function tabview($status=false, $limit=3)
	{
		$data['rs']	=$this->mod_suggestions->get_tablist($this->sessid);
		$content	=$this->load->view('messages/tabview', $data, TRUE);
		echo $content;
	} 

    function messages_list($type=0, $start =0)
	{
		/* Breadcrumb Setup Start */
			$link 					=breadcrumb();
			$data['breadcrumb'] 	=$link;
		/* Breadcrumb Setup End */

		$data['page_title'] 	= $this->lang->line('label_messages'); //label_suggestions_listing_page');
		
		
		$where				 =array('oxs.suggestion_reciever' =>$this->sessid); //'oxs.suggestion_status' =>$type, 
 	    $data['rs']		     =$this->mod_suggestions->get_read_publisher_listing($where);
	   
		$data['page_content']	     =$this->load->view('messages/messages_list', $data, true);
		$this->load->view('publisher_layout', $data);
	} 
  
    function view($id=false)
	{
		if($id ==false)
		{
			redirect('publisher/dashboard');
		}
		else
		{
			$update_status		 =$this->mod_suggestions->set_read_status($id);
			
			$where				 =array('oxs.suggestion_id' =>$id , 'oxs.suggestion_reciever' =>$this->sessid); //,'oxs.suggestion_status' =>0
 	    	$data['rs']		     =$this->mod_suggestions->get_read_publisher_listing($where);
		    $data['admin_email'] =$this->mod_suggestions->get_admin_email();
		
			$data['content']	=$this->load->view('messages/view_layout', $data, TRUE);
			$this->load->view('messages/view_tpl', $data);
		}
	} 

    function add()
	{		
		$data['admin_email']=$this->mod_suggestions->get_admin_email();
		$data['content']	=$this->load->view('messages/add_layout', $data, TRUE);
		$this->load->view('messages/view_tpl', $data);
	} 

    function reply($id=false)
	{
		$where			=('oxs.suggestion_id ='.$id.' AND oxs.suggestion_reciever ='.$this->sessid." AND oxs.suggestion_sendertype ='MANAGER'"); //'oxs.suggestion_status' =>0,
 	    $data['rs']		     =$this->mod_suggestions->get_read_listing($where);
		$data['admin_email'] =$this->mod_suggestions->get_admin_email();
		
		$data['content']	=$this->load->view('messages/reply_layout', $data, TRUE);
		$this->load->view('messages/view_tpl', $data);
	} 

    function process()
	{
			$date		=date("Y-m-d H:i:s");
			$sendertype	=$this->session->userdata('session_user_type');
			$senderid	=$this->sessid;
			$recieverid	=2; //$_REQUEST['recieverid'];
			$subject	=$_REQUEST['subject'];
			$content	=$_REQUEST['content'];
			$repliedid  =$_REQUEST['suggestionid'];
			$recievertype	=$this->input->post('recievertype');
			
			$sender		=$this->input->post('sender');
			$reciever	=$this->input->post('reciever');
			
			$update_status		 =$this->mod_suggestions->set_read_status($repliedid);
			
			$ins		=array('suggestion_sender' =>$senderid, 'suggestion_reciever' =>$recieverid, 'suggestion_subject' => $subject, 'suggestion_content' => $content, 'suggestion_repliedid' => $repliedid, 'suggestion_date' => $date, 'suggestion_sendertype' => $sendertype);
			
			$data['sender_email']	=$sender;
			$data['reciever_email']	=$reciever;
			$data['rs']				=$ins;
			$data['recievertype']	=$recievertype;
			$data['content']		=$this->load->view('messages/mail_layout', $data, TRUE);
			$content				=$this->load->view('messages/view_tpl', $data, TRUE);

			////// /////////// Mailing \\\\\\\\\\\\\\\\\\\\\\\
															
			$config['mailtype'] 			='html';
			$config['charset'] 				='UTF-8';	
			$this->email->set_newline("\r\n");
			$this->email->initialize($config);
			$this->email->from($sender);
			$this->email->to($reciever);        
			$this->email->subject($subject);        
			$this->email->message($content);
			$this->email->send();			
			
			////// /////////// Mailing \\\\\\\\\\\\\\\\\\\\\\\

			$this->session->set_flashdata('message', 'Message sent successfully.'/*$this->lang->line('label_suggestions_reply_success')*/);

			$status	=$this->mod_suggestions->insert($ins);
			echo $status;    	
	} 

    function addprocess()
	{
			$date		=date("Y-m-d H:i:s");
			$sendertype	=$this->session->userdata('session_user_type');
			$senderid	=$this->sessid;
			$recieverid	=$this->input->post('recieverid');
			$subject	=$this->input->post('subject');
			$content	=$this->input->post('content');
			$repliedid	=$this->input->post('suggestionid');
			$recievertype	=$this->input->post('recievertype');
			
			$update_status		 =$this->mod_suggestions->set_read_status($repliedid);
			
			$sender		=$this->input->post('sender');
			$reciever	=$this->input->post('reciever');
			
			$ins		=array('suggestion_sender' =>$senderid, 'suggestion_reciever' =>$recieverid, 'suggestion_subject' => $subject, 'suggestion_content' => $content, 'suggestion_repliedid' => $repliedid, 'suggestion_date' => $date, 'suggestion_sendertype' => $sendertype);
			
			
			$data['sender_email']	=$sender;
			$data['reciever_email']	=$reciever;
			$data['rs']				=$ins;
			$data['recievertype']	=$recievertype;
			$data['content']		=$this->load->view('messages/mail_layout', $data, TRUE);
			$content				=$this->load->view('messages/view_tpl', $data, TRUE);

			////// /////////// Mailing \\\\\\\\\\\\\\\\\\\\\\\
															
			$config['mailtype'] 			='html';
			$config['charset'] 				='UTF-8';	
			$this->email->set_newline("\r\n");
			$this->email->initialize($config);
			$this->email->from($sender);
			$this->email->to($reciever);        
			$this->email->subject($subject);        
			$this->email->message($content);
			$this->email->send();			
			
			////// /////////// Mailing \\\\\\\\\\\\\\\\\\\\\\\			
			
			$this->session->set_flashdata('message', 'Message sent successfully.'/*$this->lang->line('label_suggestions_reply_success')*/);

			$status	=$this->mod_suggestions->insert($ins);
			echo $status;    	
	}
	
	function sent_list($type=0, $start =0)
	{
		
		/* Breadcrumb Setup Start */
			$link 					=breadcrumb();
			$data['breadcrumb'] 	=$link;
		/* Breadcrumb Setup End */

		$data['page_title'] 	= "Sent Messages";
		
		$where				 =array('oxs.suggestion_sender' =>$this->sessid); //'oxs.suggestion_status' =>$type, 
 	    $data['rs']		     =$this->mod_suggestions->get_sent_messages($where);

 	    
	   
		$data['page_content']	     =$this->load->view('messages/messages_sent_list', $data, true);
		$this->load->view('publisher_layout', $data);
	}


	function sent_view($id=false)
	{
		if($id ==false)
		{
			redirect('publisher/dashboard');
		}
		else
		{
			
			$where				 =array('oxs.suggestion_id' =>$id); //,'oxs.suggestion_status' =>0
 	    	$data['rs']		     =$this->mod_suggestions->get_sent_messages_content($where);
			$data['content']	=$this->load->view('messages/sent_view_layout', $data, TRUE);
			$this->load->view('messages/view_tpl', $data);
		}
	} 

	
}

/* End of file myaccount.php */
/* Location: ./modules/admin/myaccount.php */
