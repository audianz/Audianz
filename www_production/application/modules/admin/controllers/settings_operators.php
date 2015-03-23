<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_operators extends CI_Controller {   
	 
	/* Page Limit:  Number of records showed at the time of pagination */
	var $page_limit = 10; 
	var $table	='djx_carrier_detail';
	/* Checks the Status of the Listing Records */
	var $status     = "all";
	
	public function __construct() 
    {  
		parent::__construct();
		
		/* Libraries */
			$this->load->library('csvreader');
		
		/* Helpers */
			$this->load->helper('download');
			$this->load->helper('url');
			$this->load->helper('csv');	
	
		/* Models */
			$this->load->model("mod_operator_settings", 'maudi');		
		
		/* Classes */
    }
	
	public function index()
	{
		$this->operator_list();
	}
		
	/* System Settings Landing Page */
	public function operator_list($offset = 0) 
	{
		/*-------------------------------------------------------------
		 	Breadcrumb Setup Start
		 -------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] 	= $link;
	
		/*--------------------------------------------------------------
		 	Pagination  Config Setup
		 ---------------------------------------------------------------*/
		$where	='';
		$total 				=$this->maudi->count_rows($this->table, $where);
		$list_data 			=$this->maudi->list_data($this->table, $where, $this->page_limit, $offset);

		$config['per_page'] = $this->page_limit;
		$config['base_url'] = site_url("admin/settings_operators/operator_list/");
		
		$config['uri_segment']  =4;
		$config['total_rows'] 	=$total;
		$config['next_link'] 	=$this->lang->line("pagination_next_link");
		$config['prev_link'] 	=$this->lang->line("pagination_prev_link");		
		$config['last_link'] 	=$this->lang->line("pagination_last_link");		
		$config['first_link'] 	=$this->lang->line("pagination_first_link");
		
		$this->pagination->initialize($config);		
		
		//$list_data = $this->mod_system_settings->get_geo_operators($this->status,$limit,$start);
		$data['operators_list']	=$list_data;
					
		/*-------------------------------------------------------------
		 	Page Title showed at the content section of page
		 -------------------------------------------------------------*/
		$data['page_title'] 	= $this->lang->line('label_geo_operators_title');	
			
		/*-------------------------------------------------------------
		 	Embed current page content into template layout
		 -------------------------------------------------------------*/
		$data['offset']			=($offset ==0)?1:($offset + 1);
		$data['page_content']	= $this->load->view("operators/list_operators", $data, true);
		$this->load->view('page_layout', $data);
	}
	
	function import_operator()
	{        
		//$config['upload_path'] 	 ='./uploads/';
		$config['upload_path'] = $this->config->item('csv_geooperators');
   		$config['allowed_types'] ='application/csv|csv'; //|xls|xlsx';
   		$config['file_name'] 	 =time() .$_FILES['geoOpCsv']['name'];
		 
    	 $this->load->library('upload', $config);

		if(!$this->upload->do_upload("geoOpCsv"))
		{ 
			$this->session->set_flashdata('error_msg', $this->upload->display_errors());
			redirect("admin/settings_operators");		
		}
		else 
		{
			$file_info 	 =$this->upload->data();
			$csvfilepath =$this->config->item('read_csv_geooperators') . $config['file_name'];
			$data	 	 =$this->csvreader->parse_file($csvfilepath); 
		
			if(count($data) > 0)
			{
				// TRUNCATE DJX EXCEL TABLE
				$this->db->query("TRUNCATE TABLE djx_excel;");

				foreach($data as $row){ 
				
				//print_r($row);
				
				//$keys	=array_keys($row);
				//$values	=array_values($row);
				
				//print_r($keys);
				//print_r($values);
				
				//Array ( [carriername;country;country_code;start_ip;end_ip] => Provider;india;IN;1.1.1.1;255.255.255.255 ) 
				
				
					// UPDATE / INSERT DJX EXCEL TABLE and djx_carrier_detail Table

					if($row['carriername'] != "" AND $row['country'] != "" AND $row['country_code'] != "" AND $row['start_ip'] != "" AND $row['end_ip'] != ""){
					
						$insertData = array(
										'start_ip' 		=> $row['start_ip'],
										'end_ip' 		=> $row['end_ip'],
										'country' 		=> $row['country'],	
										'country_code' 	=> $row['country_code'],			
										'carriername' 	=> $row['carriername'],
										'Statustext' 	=> "success"						
										);
										
						$insertCarrierDet = array(
										'start_ip' 		=> $row['start_ip'],
										'end_ip' 		=> $row['end_ip'],
										'country' 		=> $row['country'],	
										'country_code' 	=> $row['country_code'],			
										'carriername' 	=> $row['carriername']			
										);				
						
						if($this->check_operator($row['start_ip'], $row['end_ip'])){
							// Already Exists
							$insertData['Statustext']		="Already exists.";
							$this->db->insert('djx_excel', $insertData);
						}
						else
						{
							// New Operator for specified IP range
							$this->db->insert('djx_carrier_detail', $insertCarrierDet);
							$this->db->insert('djx_excel', $insertData);
						}				
										
					}
					else
					{
						$insertData = array(
										'start_ip' 		=> ($row['start_ip'] != "")?$row['start_ip']:'',
										'end_ip' 		=> ($row['end_ip'] != "")?$row['end_ip']:'',
										'country' 		=> ($row['country'] != "")?$row['country']:'',	
										'country_code' 	=> ($row['country_code'] != "")?$row['country_code']:'',		
										'carriername' 	=> ($row['carriername'] != "")?$row['carriername']:'',
										'Statustext' 	=> "error"						
										);
						$this->db->insert('djx_excel', $insertData);
					}
				}
			}
			else
			{
				$this->session->set_flashdata('error_msg','Please select valid CSV file.');
				redirect("admin/settings_operators");
			}
		}
		
		@unlink($this->config->item('read_csv_geooperators') . $config['file_name']);
		$this->session->set_flashdata('success_msg','Operators uploaded.'); 
		redirect("admin/settings_operators");	
	}

	function check_operator($start_ip,$end_ip){
	
		$this->db->where('start_ip', $start_ip);
		$this->db->where('end_ip', $end_ip);

		$query = $this->db->get('djx_carrier_detail');

		if($query->num_rows() > 0)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	function operator_download()
	{		
		 $query 	=$this->db->query('SELECT * FROM djx_excel');
         $num 		=$query->num_fields();
         $var 		=array();
         $i 		=1;
         $fname		="";
         while($i <= $num)
		 {
            $test 	=$i;
            $value 	=$this->input->post($test);

            if($value !='')
			{
               $fname	=$fname." ".$value;
               array_push($var, $value);
            }
            $i++;
         }

         $fname 	=trim($fname);
         $fname		=str_replace(' ', ',', $fname);
         $this->db->select($fname);
         $quer 		=$this->db->get('djx_excel');
         query_to_csv($quer,TRUE,'Geo_Operators_'.date('dMy').'.csv');
	}

	function functional()
	{
		$action	=$this->uri->segment(4);

		$data['page_title'] 	=$this->lang->line('label_geo_operators_title');
		$link 					=breadcrumb();
		$data['breadcrumb'] 	=$link;

		switch($action) {
		
			case 'operators' :
			{
				$code		=($this->uri->segment(5) =='')?0:$this->uri->segment(5);
				if($code ==0 || $code=='') {
		 		$data['type']		='add';
				$data['country'] 	=$this->maudi->list_country();
				$this->load->view('admin/operators/edit_operators', $data);
				
				$data['page_content']	= $this->load->view("admin/operators/edit_operators", $data, true);
				$this->load->view('page_layout', $data);
				}
				else 
				{
		    		$where				=array('id' =>$code);
		 			$data['rs']			=$this->maudi->getGeooperator($where);
					$data['country'] 	=$this->maudi->list_country();
		 			$data['type']		='update';
					$data['code']		=$code;
					$data['page_content']	= $this->load->view("admin/operators/edit_operators", $data, true);
					$this->load->view('page_layout', $data);
				}
		 	break;
		}
		
		default : redirect('admin/settings_operators'); break;
		
		}
	}

	function process()
	{
		$action	=$this->uri->segment(4);

		switch($action)
		{
			case 'operators' :
			{
				$operator	  	 =$this->input->post('operators_name');
				$country 		 =$this->input->post('country');
				$countrycode  	 =$this->input->post('country_code');
				$sip			 =$this->input->post('sip');
				$eip		  	 =$this->input->post('eip');
				$id				 =$this->input->post('id');
				$status		  	 =1;
				$data		  	 =array("carriername" =>$operator, "country" =>$country, "country_code" =>$countrycode, "start_ip" =>$sip, "end_ip" => $eip);
		 		$this->maudi->updateGeooperator($data, $id);
				
				$this->session->set_flashdata('success_msg','Operators updated.'); 
		 		redirect('admin/settings_operators');
		 	break;
		  }
		default : redirect('admin/settings_operators'); break;
		}
	}

	function delete()
	{
		$action	=$this->uri->segment(4);

		switch($action) {
		
		case 'operator' :
		{
			$code		  =$this->uri->segment(5);
			
			/*    ##############################################    */
			$sql	=$this->db->query("SELECT targeting_id, operators FROM djx_targeting_limitations");
			if ($sql->num_rows() > 0)
			{
				$out1	=array();
				foreach($sql->result() as $result) :
					$tl	=explode(',', $result->operators);
					foreach ($tl as $k => $v) 
					{ if($v != $code) $out1[$k] =$v; };
					$out1	=@implode(",", $out1);

					$data = array("operators" =>$out1);
					$this->db->where('targeting_id', $result->targeting_id);
					$this->db->update('djx_targeting_limitations', $data);
				endforeach; 
			}
			/*    ##############################################    */
			//echo "<br />";
			//echo "<br />";
			/*    ##############################################    */
			$sql	=$this->db->query("SELECT id, compiledlimitation FROM djx_campaign_limitation");
			if ($sql->num_rows() > 0)
			{
				$out	=array();
				foreach($sql->result() as $res) :
					$rs	    =explode(" AND ", $res->compiledlimitation);
					foreach ($rs as $key => $item) :
					if (preg_match("/geographic_operators/", $item, $matches))
					{
						$first	=explode('(', $item);	
						$second	=explode('\',\'', $first[1]);
						$third	=ltrim($second[0], '\'');
						$four	=explode(',', $third);
						foreach ($four as $k => $v) { if($v != $code) $out[$k] =$v; };
						$final	="geographic_operators('".implode($out,",")."','=~')";
						$rs[$key]	=$final;
					}
					endforeach;
					$rs	=implode($rs," AND ");

					$data = array("compiledlimitation" =>$rs);
					$this->db->where('id', $res->id);
					$this->db->update('djx_campaign_limitation', $data);
				endforeach; 
			}
			/*    ##############################################    */
			
		 	$status		  =$this->maudi->deleteGeooperator($code);
			$this->session->set_flashdata('success_msg','Operators deleted Successfully.'); 
			redirect('admin/settings_operators');
		 break;
		}
		default : redirect('admin/settings/operators'); break;
	}
	}
	
	function validate()
	{
		$action	=$this->uri->segment(4);
		switch($action) {
		case 'operator' :
		{
			$id	=trim($this->input->post("id"));
			$sql	=$this->db->query("SELECT COUNT(*) FROM djx_targeting_limitations WHERE FIND_IN_SET(".$id.", operators)");
			if ($sql->num_rows() > 0)
			{
				echo '1';
			}
			else
			{
				echo '0';
			}
			
		break;
		}
	  }
    }
	
	function operators_download_file()
	{	
	 	$data =file_get_contents("./assets/upload/admin/geooperators/geooperators.csv"); // Read the file's contents
     	$name = 'sample.csv';
     	force_download($name, $data); 
	}
	
}
/* End of file advertisers.php */
/* Location: ./modules/system_settings/controllers/geo_operators.php */



