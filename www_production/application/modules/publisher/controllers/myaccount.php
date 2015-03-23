<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		/* Login Check */
		$check_status = publisher_login_check();	
		if($check_status==FALSE)
		{
			redirect('site');
		}
		
		/* Models */
		$this->load->model("mod_account");
		
		/* Libraries */
		$this->load->library('image_lib'); 
    }
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					 =breadcrumb_home();
		$data['breadcrumb'] 	 =$link;
		
		/* Breadcrumb Setup End */
        $data['time_zone']		 =$this->mod_account->get_timezone();
		$data['country']		 =$this->mod_account->get_country();
		$data['getrecord']		 =$this->mod_account->get_myaccount();
	  
		/*-------------------------------------------------------------
			Get Categories List
        -------------------------------------------------------------*/
        $category_list 			=$this->mod_account->getCategory();
        $data['category_list']  =$category_list;
		
		$data['page_content']	=	$this->load->view('myaccount', $data, true);
		$this->load->view('publisher_layout', $data);
	}
    
	function myaccount_settings_update()
	{
		   
		//$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('email', 'email','required|callback_user_email_check');
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('state', 'state', 'required');
		$this->form_validation->set_rules('country', 'country', 'required');
		$this->form_validation->set_rules('mobileno', 'mobileno', 'required');
		//$this->form_validation->set_rules('paypalid', 'paypalid', 'required');
		//$this->form_validation->set_rules('bank_acctype', 'accounttype', 'required');
		//$this->form_validation->set_rules('tax', 'tax', 'required');
			
		
		if($this->form_validation->run() == FALSE)
		{
		   
			//$this->session->set_userdata('notification_message', $this->lang->line("label_fill_device_capability").'');
			$this->index();
		}
		else
		{   


			// $username	=trim($this->input->post("username"));
			 $email			=trim($this->input->post("email"));
			 $category		=$this->input->post('category');
			 $address		=trim($this->input->post("address"));
			 $city			=trim($this->input->post("city"));
			 $state			=trim($this->input->post("state"));
			 $country		=trim($this->input->post("country"));
			 $mobileno		=trim($this->input->post("mobileno"));
			 $paypalid		=trim($this->input->post("paypalid"));
			 $bank_acctype	=trim($this->input->post("bank_acctype"));
			 $tax			=trim($this->input->post("tax"));		
			 			
			$inputData = array(
							//"username"		=>	$username,
							"email"			=>	$email,
							"address"		=>	$address,
							"city"			=>	$city,
							"state"			=>	$state,
							"country"		=>	$country,
							"mobileno"		=>	$mobileno,
							"paypalid"		=>	$paypalid,
							"bank_acctype"	=>  $bank_acctype,
							"tax"			=>	$tax,
							"category"		=> @implode(",", $category)
							 );	
					 
			/* Publisher Image Upload */
			$avatar_image               	= rand(999, 9999)."-".$_FILES['avatar_upload']['name'];
			$config['image_library']   	= 'gd2';
			$config['allowed_types']   	= 'gif|jpg|png|jpeg';
			$config['max_size']	    	= '2000';
			$config['source_image']		= $_FILES['avatar_upload']['tmp_name'];
			$config['new_image']       	= $this->config->item('user_img_url').$avatar_image;
			$config['maintain_ratio']  	= FALSE;
			$config['width'] 		    = 50;
			$config['height'] 		    = 50;
	
			$this->image_lib->initialize($config);
			
			if($_FILES['avatar_upload']['name'] != '') 
			{
				if($this->image_lib->resize())	
				{			
					$inputData["avatar"]	=	$avatar_image;		
				}
				else
				{
					$this->session->set_flashdata('message_error', $this->lang->line('label_upload_avatar_error'));
					redirect("publisher/myaccount");
				}
			
				$this->image_lib->clear();
			
			}
			$this->mod_account->myaccount_update($inputData);
			$this->session->set_flashdata('message', $this->lang->line('label_myaccount_settings_updated_success'));
			redirect("publisher/myaccount");
		}
			
			
			
	}
			
	function myaccount_delete_avatar()
	{	
		$this->mod_account->myaccount_delete_avatar();
		$this->session->set_flashdata('message', $this->lang->line('label_user_avatar_deleted_success'));
		redirect("publisher/myaccount");	
	}
 public function user_email_check()
	{
 							
								$session_id=$this->session->userdata('session_publisher_account_id');

								$this->db->select('*');	
								$this->db->where('email',$this->input->post('email'));
								$query1=$this->db->get('ox_affiliates');
                                                               
                                                                if($query1->num_rows()>0)
								{

								$temp=$query1->row();
                                                                $id=$temp->account_id;
                                                                if($query1->num_rows() >= 1 && $session_id==$id)                    
								   {
				
								return true;
								   }
								else
								   {
									$this->form_validation->set_message('user_email_check', 									$this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
									return FALSE;	
					
					
		      						  }
                                                                }
                                                               else{
                                                                    return true;
                                                                   }   

			
	                     }	 
	
}

/* End of file myaccount.php */
/* Location: ./modules/admin/myaccount.php */
