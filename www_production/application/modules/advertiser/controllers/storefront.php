<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 *
 * @author shyam
 * This is the controller class for uploading the storefront data using  advertiser interface
*/
class Storefront extends CI_Controller
{
	var $page_limit=5;
	public function __construct()
	{
		parent::__construct();

		/*   Helpers */
		$this->load->helper('download');
			
		/* Libraries  */
		$this->load->library('csvreader');
	//	$this->load->library('pagination');
		/* Models */
		$this->load->model('mod_storefront');
	

		/* Login Check */
		$check_status = advertiser_login_check();
		if($check_status == FALSE)
		{
			redirect('site');
		}
	}
	public function index()
	{

		$this->storefront_list();
	}

	public function storefront_list($offset= 0)
	{
		/*-------------------------------------------------------------
		 Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb(); 
		$data['breadcrumb'] 	= $link;
			
		/*--------------------------------------------------------------
		 Pagination  Config Setup
		---------------------------------------------------------------*/
	
		$client_id=$this->session->userdata('session_advertiser_id');
             
         
        $list_data = $this->mod_storefront->get_storefront_list($client_id);
      
     
		
		if($list_data!=false)
		{
			$data['storefront_list']	=	$list_data;
			
		}
		else
		{
			$data['storefront_list']=0;
		}
	
	/*
	 * Get the default radius of advertiser 
	 */
		
		$radius=$this->mod_storefront->defaultRadius($client_id);
		
		if($radius!=false)
		{
			$data['radius']=$radius;
		}
		else 
		{
			$data['radius']=0;
		}

			
		/** Get the Number of Records ****/
		$data['page_content']=$this->load->view('storefront/map_view',$data,TRUE);
		$this->load->view('advertiser_location',$data);
		//
	}

	/**
	 * This method is used to download the sample csv file containing storefront example data
	 */
	public function storefront_download_file()
	{
		$data = file_get_contents("./assets/upload/advertiser/storefront_sample.csv"); // Read the file's contents
		$name = 'sample.csv';
		force_download($name, $data);
	}

	/**
	 * This method is used to upload storefront file
	 */
	public function storefront_import_file()
	{
		
		//	$allowed_types = array('application/csv','application/x-csv','text/x-csv','text/csv');
		$allowedExt = array("csv");
		$temp = explode(".", $_FILES["storefront"]["name"]);
		$extension = end($temp);
		
		$client_id=$this->session->userdata('session_advertiser_id');
        
		if($_FILES['storefront']['name'] != '')
		{
			if(in_array($extension,$allowedExt))
			{
				
				$config['upload_path'] = $this->config->item('csv_storefront_path');

				$config['allowed_types'] = '*';
				$config['file_name'] = 'upload' . time() .$_FILES['storefront']['name'];
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload("storefront"))
				{
					$this->session->set_flashdata('error_msg', $this->upload->display_errors());
					redirect("advertiser/storefront");
				}
				else
				{
					
					$file_info 		= $this->upload->data();
					$csvfilepath 	= $this->config->item('read_csv_storefront').$file_info['file_name'];
					$data	 		= $this->csvreader->parse_csv($csvfilepath);
				
					$counter		= '1';
				  
					$error_head_field_details = array();
					$error_poi_name			= array();
					$table_row	=array();
					if(count($data) > 0)
					{
						
						foreach($data as $row)
						{
							
							if($row['POI_Name'] !=''  AND $row['Tel']!='' AND $row['Lat']!='' AND $row['Lon']!='')
							{
								$poi_name 	= $row['POI_Name'];
								
								$tel		= $row['Tel'];
								$lat		= $row['Lat'];
								$lon		= $row['Lon'];
								
								
								if(!isset($row['Address1']))
								{
									$address1 	= "";
								}
								else 
								{
									$address1 	= $row['Address1'];
								}
								if(!isset($row['City']))
								{
									$city	= "";
								}
								else
								{
									$city	  	= $row['City'];
								}
								if(!isset($row['Address2']))
								{
									$address2 	= "";
								}
								else 
								{
									$address2 	= $row['Address2'];
								}
								if(!isset($row['State']))
								{
									$state		= "";
								}
								else
								{
									$state		= $row['State'];
								}
								if(!isset($row['PIN']))
								{
									$pin		= 0;
								}
								else
								{
									$pin		= $row['PIN'];
								}
								if(!isset($row['Country']))
								{
									$country	= "";
								}
								else
								{
									$country	= $row['Country'];
								}
								
								$insert_data		=array("clientid"=>$client_id, "poi_name" =>$poi_name, "address1" =>$address1, "address2"=> $address2,"city"=>$city,"state"=>$state,"pin"=>$pin,"country"=>$country,"tel"=>$tel,"lat"=>$lat,"lon"=>$lon);
								$tbl_name			='storefrontdata';
								array_push($table_row,$insert_data);
								
								
								
								
								
							}
							else
							{
								$error_head_field_details[]=$counter;
							
							} 
							$counter++;
						}
						
						
						
						if(count($error_head_field_details)>0)
						{
							
							$this->session->set_flashdata('error_head_field_details_msg', 'Some fields are missing in the uploaded file.');
						}
						else 
						{
							$this->mod_storefront->insert_data($table_row, $tbl_name,$client_id);
							$this->session->set_flashdata('message', 'Storefront has been added successfully');
						}
						if(file_exists($this->config->item('csv_storefront_path')."/".$config['file_name']))
						{
							unlink($this->config->item('csv_storefront_path')."/".$config['file_name']);
						}

						redirect('advertiser/storefront');

					}
					


				} 
			} 
			else
			{
				$this->session->set_flashdata('error_msg', 'Please upload csv files only.');
				redirect("advertiser/storefront");

			}
			
		} 
		else
		{
			$this->session->set_flashdata('storefront_file_chk','Please select import file');
			redirect('advertiser/storefront');
		}
	}

	/**
	 * The function is used to get the radius value and update it
	 * @param unknown $val
	 */
	
	public function updateRadius()
	{
		$adv_id=$this->session->userdata('session_advertiser_id');
		
		
		$val=$this->input->post('txtradius');
		if($val<0)
		{
			$this->session->set_flashdata('negativeRadius',"Radius can not be negative");
			redirect("advertiser/storefront/show_list");
		}
		if($val>2147483640)
		{
			$this->session->set_flashdata('largeRadius',"Radius value is too large");
			redirect("advertiser/storefront/show_list");
		}
		if(!is_numeric($val))
		{
			$this->session->set_flashdata('invalidRadius',"Please enter the numeric value");
			redirect("advertiser/storefront/show_list");
		}
		if($val!='')
		{
			$this->mod_storefront->updateRecord($val,$adv_id);
			$this->session->set_flashdata('updateSuccess',"Radius has been successfully updated");
			redirect("advertiser/storefront/show_list");
		}
		else
		{
			$this->session->set_flashdata('updateErr',"Please enter the radius value");
			redirect("advertiser/storefront/show_list");
		}
	}
	/**
	 * This method is used to display edit form for storefront
	 */
	public function edit($id=0)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$data['advertiser'] = $clientid;
		
		/*-------------------------------------------------------------
		Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;

		/*-------------------------------------------------------------
		 Get Campaign Details
		-------------------------------------------------------------*/
		if($id !=0)
		{
			//$where_store = array('storefrontdata.id'=>$id, 'storefrontdata.clientid'=>$clientid);
			$store = $this->mod_storefront->retrieve_store($id);
			$data['store_info']=$store;
			//print_r($store);
		}

		else
		{
			$data['store_info'] =0;
		}
		$data['page_content']    = $this->load->view("storefront/edit_store",$data,true);
		$this->load->view('advertiser_report',$data);

	}
	public function edit_process($id)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$data_update=array("clientid"=>$clientid, "poi_name" =>$_POST['store_name'], "address1" =>$_POST['ad1'], "address2"=> $_POST['ad2'],"city"=>$_POST['city'],"state"=>$_POST['state'],"pin"=>$_POST['pin'],"country"=>$_POST['country'],"tel"=>$_POST['tel'],"lat"=>$_POST['lat'],"lon"=>$_POST['lon'],"floor"=>$_POST['floor'],"shelf"=>$_POST['shelf'],"tel"=>$_POST['tel'],"location_01"=>$_POST['loc1'],"location_02"=>$_POST['loc2'],"location_03"=>$_POST['loc3']);
		$table_name='storefrontdata';
		$this->mod_storefront->update_store_info($data_update, $tabl_name,$id);
		$this->session->set_flashdata('message', 'Store Details has been updated successfully');
		redirect('advertiser/storefront');
			
	}
		



	public function add_store()
	{
		$data['page_content']    = $this->load->view("storefront/add_store",$data,true);
		$this->load->view('advertiser_report',$data);
			
	}


	public function add()
	{
		$adv_id = $this->session->userdata('session_advertiser_id');
		print_r($_POST);
		$tbl_name='storefrontdata';
		$insert_data  =array("clientid"=>$adv_id, "poi_name" =>$_POST['store_name'], "address1" =>$_POST['ad1'], "address2"=> $_POST['ad2'],"city"=>$_POST['city'],"state"=>$_POST['state'],"pin"=>$_POST['pin'],"country"=>$_POST['country'],"tel"=>$_POST['tel'],"lat"=>$_POST['lat'],"lon"=>$_POST['lon'],"aisle"=>$_POST['aisle'],"floor"=>$_POST['floor'],"shelf"=>$_POST['shelf']);
		$this->mod_storefront->add_store($insert_data, $tbl_nme,$adv_id);
		redirect('advertiser/storefront');
	}

	//To show stores list
	public function show_list()
	{
		/*-------------------------------------------------------------
		 Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb']     = $link;

		/*--------------------------------------------------------------
		 Pagination  Config Setup
		---------------------------------------------------------------*/

		$client_id=$this->session->userdata('session_advertiser_id');
		$list_data = $this->mod_storefront->get_storefront_list($client_id);
		if($list_data!=false)
		{
			$data['storefront_list']    =   $list_data;

		}
		else
		{
			$data['storefront_list']=0;
		}

		/*
		 * Get the default radius of advertiser
		*/

		$radius=$this->mod_storefront->defaultRadius($client_id);

		if($radius!=false)
		{
			$data['radius']=$radius;
		}
		else
		{
			$data['radius']=0;
		}


		/** Get the Number of Records ****/
		$data['page_content']=$this->load->view('storefront/listdata',$data,TRUE);
		$this->load->view('advertiser_location',$data);
		 
	}

	//Function to remove stores
	public function delete_stores()
	{
		$stores= $_POST['arr'];
		if($stores[0]=='checkall')
		{
			$store_list = array_shift($stores);
		}
		$count = count($stores);
		for($m=0;$m<$count;$m++)
		{
			$id=$stores[$m];
			$this->mod_storefront->delete_store($id);
		}
			
	}

	//TODO with map search
	public function map_search()
	{
		$data['storefront_list']=$this->mod_storefront->map_search_store($_POST['location']);
		/*-------------------------------------------------------------
		Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb']     = $link;
		$data['page_content']=$this->load->view('storefront/map_view',$data,TRUE);
		$this->load->view('advertiser_location',$data);
	}

	//TODO with link of map view on stores list
	public function get_map_view($id)
	{
		$clientid = $this->session->userdata('session_advertiser_id');
		$data['advertiser'] = $clientid;
		/*-------------------------------------------------------------
		 Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;

		/*-------------------------------------------------------------
		 Get Campaign Details
		-------------------------------------------------------------*/
		if($id !=0)
		{
			$store = $this->mod_storefront->retrieve_store($id);
			$data['storefront_list']=$store;
		}
		else
		{
			$data['storefront_list'] =0;
		}
		$data['page_content']    = $this->load->view("storefront/map_view",$data,true);
		$this->load->view('advertiser_location',$data);
	}
		
	//TODO with beacon related stores
	public function store_beacon_map()
	{
		/*-------------------------------------------------------------
		 Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb']     = $link;

		/*--------------------------------------------------------------
		 Pagination  Config Setup
		---------------------------------------------------------------*/

		$data['stores']=$this->mod_storefront->beacon_related_stores();
		$data['page_content']=$this->load->view('storefront/store_beacon_map',$data,TRUE);
		$this->load->view('advertiser_location',$data);
	}

	public function map_store()
	{
		$store=$_POST['arr'];
		$data=$this->mod_storefront->map_search_store($store[0]);
		return $data;
			
			
	}
	
	/* This method is used to display map view of storefronts
	 */
	public function map_view()
	{
		$this->storefront_list();
	}

	
}
?>
