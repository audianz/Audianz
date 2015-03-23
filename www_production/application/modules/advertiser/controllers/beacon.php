<?php 
  class Beacon extends CI_Controller { 

	public function __construct()
    {
		parent::__construct();
		$this->load->model('mod_beacon'); 
		$this->load->model('mod_storefront');	
		
		/* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
    }
	
	/* Dashboard Page */
	public function index()
	{ 
		$this->get_list();
	}
	
	public function get_list()
	{
		$link = breadcrumb(); 
		$data['breadcrumb'] = $link;
		$client_id=$this->session->userdata('session_advertiser_id'); 
		$list_data = $this->mod_beacon->get_beacon_list($client_id);
		foreach($list_data as $row)
		{
			$poi_id=$row->Beacon_Install_Location_ID;
			if($poi_id==0 || $poi_id==" " )
			{
				$row->location="No location  ";
			}
			else
			{
				$store_details=$this->mod_storefront->retrieve_store($poi_id);
				$row->location=$store_details[0]['poi_name'].",".$store_details[0]['pin'];
			}
		}
		
		$data['list']=$list_data;     
		$data['page_content']	= $this->load->view("advertiser/beacon/list",$data,true);
		$this->load->view('advertiser_beacon',$data);
		
	}
	
	// Function to show the configuration page of the Beacon details
	 public function configure_beacon($id=0)
     {
            $clientid = $this->session->userdata('session_advertiser_id');
            $locations = $this->mod_storefront->get_storefront_list($clientid);
            $data['beacon_locations']=$locations;
			$data['advertiser'] = $clientid;
			$link = breadcrumb();
			$data['breadcrumb'] = $link;
		
		
				if($id !=0)
				{
					
					$detail = $this->mod_beacon->retrieve_beacon_details($id);//print_r($detail);
					$data['beacon_info']=$detail;
				}
				
				else
				{
					$data['beacon_info'] =0;
				} 
					//$data['page_content']    = $this->load->view("beacon/configure_beacon",$data,true);
					$data['page_content']    = $this->load->view("beacon/configure",$data,true);
					$this->load->view('advertiser_beacon',$data);
	 }
	 
	 
	 //This function is to save the configuration changes of the beacon.
	 public function save_configurations($id=0)
	 {
		
		$poi_id=$_POST['beacon_location'];
		$store_details=$this->mod_storefront->retrieve_store($poi_id);
		$store=$store_details[0]['poi_name'].",".$store_details[0]['pin'];		
		$data_update=array("description"=>$_POST['description'], "Beacon_name" =>$_POST['beacon_name'], "Beacon_Minor_ID" =>$_POST['minor_id'], "Beacon_Install_Location_ID"=>$_POST['beacon_location']);
		$this->mod_beacon->update_beacon_info($data_update,$id);
		$this->session->set_flashdata('message', 'Beacon has been configured successfully');
		redirect('advertiser/beacon');
     }
     
     //Function to delete beacons from list and dB
     public function delete_beacons()
     {
		 $beacons= $_POST['arr'];
		if($beacons[0]=='checkall')
		{
			$val = array_shift($beacons);
		}
		$count = count($beacons);
		//print_r($stores);
		for($m=0;$m<$count;$m++)
		{ 
			$id=$beacons[$m];
			//echo $id;
			$this->mod_beacon->delete_beacon($id);
		}
	
	 }
	
	 //Function to attach actions with  the beacon
	 public function attach_actions($id=0)
	 {
		if( $this->session->userdata('attach_beacon_id')!=NULL )
		{
			$id=$this->session->userdata('attach_beacon_id');
			$pid=$this->session->userdata('attached_pid');
			if($pid ==11)
			$this->session->set_userdata('pid',11);
			else if($pid==22)
			$this->session->set_userdata('pid',22);
			else
			$this->session->set_userdata('pid',33);
			$details=$this->mod_beacon->get_action_data($id,$pid);
			$this->session->set_userdata('remarks',$details[0]->Remarks);
			//print_r($details); 
			//echo $details[0]->Campaign_Id;
			$camp=$this->mod_beacon->retrieve_campaign_details($details[0]->Campaign_Id);
			$this->session->set_userdata('campaign',$camp[0]->campaignname);
			$this->session->unset_userdata('attach_beacon_id');
		}
		       
		        $link = breadcrumb(); 
		        $data['breadcrumb']     = $link;
		        if($id !=0)
				{
					$details= $this->mod_beacon->retrieve_beacon_details($id);
					$data['beacon_info']=$details;
					$poi_id=$details[0]->Beacon_Install_Location_ID;
					$store_details=$this->mod_storefront->retrieve_store($poi_id);
					$store=$store_details[0]['poi_name'].",".$store_details[0]['pin'];
					$data['location']=$store;
					$campaigns_list=$this->mod_beacon->get_campaigns_list();
					$data['campaigns']=$campaigns_list;
					
					//To Fetch already attached actions list
					$list= $this->mod_beacon->retrieve_actions_list($id);
					foreach($list as $row)
					{
						$cmp_id=$row->Campaign_Id;
						if($cmp_id==0 || $cmp_id==" " )
						{
							$row->campaign="No Campaign Linked";
						}
						else
						{
							$camp=$this->mod_beacon->retrieve_campaign_details($cmp_id);
							$row->campaign=$camp[0]->campaignname;
						}
					}
					
					$data['action_list']=$list;	
					
				}
				
				else
				{
					$data['beacon_info'] =0;
				} 
				
		  		//$data['page_content']	= $this->load->view("advertiser/beacon/attach_actions",$data,true);
		  		$data['page_content']	= $this->load->view("advertiser/beacon/actions",$data,true);
				$this->load->view('advertiser_beacon',$data);
	 }
	 
	 //To update data of attached action
	 public function update_actions($id=0)
	 {
		 $this->session->unset_userdata('remarks');
		 $this->session->unset_userdata('campaign');
		 $this->session->unset_userdata('pid');
		 
		$details = $this->mod_beacon->retrieve_beacon_details($id);
		$this->mod_beacon->update_beacon_content($id,$details);
		$this->session->set_flashdata('message', 'Beacon has been attached successfully');
		redirect('advertiser/beacon/attach_actions/'.$id);
	}
	
	//To upadte beacon status to active
	public function active_status()
	{
		
		 $beacons= $_POST['arr'];
		if($beacons[0]=='checkall')
		{
			$val = array_shift($beacons);
		}
		$count = count($beacons);
		for($m=0;$m<$count;$m++)
		{ 
			$id=$beacons[$m];
			//echo $id;
			$this->mod_beacon->change_status_to_active($id);
		}
	}
	//To change beacon status to Passive
	public function passive_status()
	{
		
		 $beacons= $_POST['arr'];
		if($beacons[0]=='checkall')
		{
			$val = array_shift($beacons);
		}
		$count = count($beacons);
		for($m=0;$m<$count;$m++)
		{ 
			$id=$beacons[$m];
			$this->mod_beacon->change_status_to_passive($id);
		}
	}
	
	/* Called on click of edit */
	public function edit_action($id=0,$pid=0)
	{
		$this->session->unset_userdata('remarks');
		 $this->session->unset_userdata('campaign');
		 $this->session->unset_userdata('pid');
		$this->session->set_userdata('attach_beacon_id', $id);
		$this->session->set_userdata('attached_pid', $pid);
		redirect('advertiser/beacon/attach_actions/'.$id);
	}
	
	/* Function to remove already performed action */
	public function remove_action($id=0,$pid=0)
	{
		$this->session->unset_userdata('remarks');
		 $this->session->unset_userdata('campaign');
		 $this->session->unset_userdata('pid');
		$this->mod_beacon->delete_action($id,$pid);
		$this->session->set_flashdata('message', 'Attached action is removed succesfully');
		redirect('advertiser/beacon/attach_actions/'.$id);
		
	}
		
	

}
?>
