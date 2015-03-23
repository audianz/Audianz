<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		/* Models */
		$this->load->model("mod_account");
		
		/* Libraries */
		$this->load->library('image_lib'); 
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	/* Site Settings Page */
	
	/* Site Setting Landing Page */
	public function index()
	{
	
		/* Breadcrumb Setup Start */
		
		$link 					= 	breadcrumb_home();
		
		$data['breadcrumb'] 	= 	$link;
		
		/* Breadcrumb Setup End */
          	$data['time_zone']		 =	$this->mod_account->get_timezone();
		  $data['country']		     =	$this->mod_account->get_country();
		  $data['getrecord']		 =   $this->mod_account->get_myaccount();
	  	
		$data['page_content']	=	$this->load->view('myaccount',$data,true);
		$this->load->view('advertiser_layout',$data);
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
			//$username		=	trim($this->input->post("username"));
			$email			=	trim($this->input->post("email"));
			$address		=	trim($this->input->post("address"));
			$city			=	trim($this->input->post("city"));
			$state			=	trim($this->input->post("state"));
			$country		=	trim($this->input->post("country"));
			$mobileno		=	trim($this->input->post("mobileno"));
			$paypalid		=	trim($this->input->post("paypalid"));
			$bank_acctype	=	trim($this->input->post("bank_acctype"));
			$tax		=	trim($this->input->post("tax"));
			$email_campaign		=	trim($this->input->post("email_campaign"));
			$email_del	=	trim($this->input->post("email_del"));
			$camp_dely_rpt		=	trim($this->input->post("camp_dely_rpt"));

			if($email_campaign == "")
			{
			  		   	   
			  $email_campaign ='f';
			  }
			   if($email_del	== "")
			{
			  $email_del	='f';
			  }
				  
			$inputData = array(
			
						//"username"		=>	$username,
						"email"			=>	$email,
						"address"		=>	$address,
						"city"			=>	$city,
						"state"			=>	$state,
						"country"		=>	$country,
						"mobileno"		=>	$mobileno,
						"paypalid"		=>	$paypalid,
						"bank_acctype"		=>$bank_acctype,
						"tax"		=>	$tax,
						"reportdeactivate"		=>	$email_campaign,
						"report"		=>$email_del,
						"reportinterval"		=>	 $camp_dely_rpt );

		/* Advertiser Image Upload */
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
					$this->session->set_flashdata('message_error',  $this->image_lib->display_errors());
					redirect("advertiser/myaccount");
				}
	
					$this->image_lib->clear();
	
					    
			}
			$this->mod_account->myaccount_update($inputData);
			$this->session->set_flashdata('message', $this->lang->line('label_myaccount_settings_updated_success'));
			redirect("advertiser/myaccount");
		 
		}
		
	}
	function myaccount_delete_avatar()
	{	
		$this->mod_account->myaccount_delete_avatar();
		$this->session->set_flashdata('message', $this->lang->line('label_user_avatar_deleted_success'));
		redirect("advertiser/myaccount");	
	}
 public function user_email_check()
	{
 							
								$session_id=$this->session->userdata('session_advertiser_account_id');
								$this->db->select('*');	
								$this->db->where('email',$this->input->post('email'));
								$query1=$this->db->get('ox_clients');
                                                               
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

								$this->form_validation->set_message('user_email_check', $this->lang->line('label_entered').'%s'.$this->lang->line('label_already_exists'));
								return FALSE;	
					
		        					}

                                                                }

                   						else
			   					{
                                       				return true;
								}
	                     }	
	
}

/* End of file myaccount.php */
/* Location: ./modules/admin/myaccount.php */
