<?php

require_once MAX_PATH .'/init.php';
class Advertiser extends Controller
{
	function Advertiser()
	{
		//error_reporting(E_ALL);
		parent::Controller();
		if($this->config->item('is_installed')=='1')
		{
		 $this->load->database();
		}
		else
		{
		$dir="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		redirect($dir."install");
		}


		$this->load->library('email');
		$this->load->library('Paypal_Lib');
		$this->load->model('dbproduct');
		$this->load->model('dbtracker');
		$this->load->model('cam_page');
		$this->load->library('Form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$this->load->library('pagination');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model('targeting_model','targeting');
		if($this->session->userdata('account_name')=="")
		{
		redirect("login");
		}
							
	}
	
	function index()
	{	
	}

	function dashboard()
	{
	//error_reporting(E_ALL);
	$this->load->view('advertiser/campaigns/report');
	}
	

function link_banner_permission()
{
$bannerid=$this->input->post("banid");
$bannervalue=mysql_query("select * from ox_banners where bannerid='$bannerid'");
$banvalue=mysql_fetch_array($bannervalue);
$adminstatus=$banvalue['adminstatus'];
if($adminstatus=="1")
{
echo "1";
}
else
{
echo "2".".".$bannerid;
}
}
	/* status in registration */
	
       /* New account  */
	function add()
	{
	$this->load->view('advertiser/signup');
	}
	function addprocess()
	{
	$a=1;
	$email=$this->input->post("email");
	$name=$this->input->post("name");
	$password=md5($this->input->post("password"));
	$account="ADVERTISER";
	$fullname=$this->input->post("fullname");
	$address=$this->input->post("address");
	$city=$this->input->post("city");
	$state=$this->input->post("state");
	$country=$this->input->post("country");
	$mobile=$this->input->post("mobile");
	$language=$this->input->post("language");
	$zip=$this->input->post("zip");
	
	$insert1=array("account_type"=>$account,"account_name"=>$name);
	$this->dbproduct->insert_accountdata($insert1);
  	$lastid=mysql_insert_id(); 
	     
	$insert=array("accountid" =>$lastid, "accounttype" =>$account, "username"=>$name,"email"=>$email,"password"=>$password,"address"=>$address,"city"=>$city,"state"=>$state,"country"=>$country,"mobileno"=>$mobile,"postcode"=>$zip);
	$this->dbproduct->insert_data($insert);	
   
	$insert2=array( "email"=>$email,"contact"=>$name,"clientname"=>$name,"agencyid"=>$a,"account_id"=>$lastid);
	$this->dbproduct->insert_clientdata($insert2);
	$insert3=array("contact_name"=>$name,"email_address"=>$email,"username"=>$name,"password"=>$password,"default_account_id"=>$lastid);
	$this->dbproduct->insert_oxusersdata($insert3);
	$user_lastid=mysql_insert_id();
    $date=date("Y-m-d H:i:s");
	$insert4=array("account_id"=>$lastid,"user_id"=>$user_lastid,"linked"=>$date);
	$this->dbproduct->insert_ox_account_user_assoc($insert4);
	$inserperm1=mysql_query("INSERT INTO ox_account_user_permission_assoc(account_id,user_id,permission_id) values('$lastid','$user_lastid','10')");
					$inserperm2=mysql_query("INSERT INTO ox_account_user_permission_assoc(account_id,user_id,permission_id) values('$lastid','$user_lastid','4')");
					$inserperm3=mysql_query("INSERT INTO ox_account_user_permission_assoc(account_id,user_id,permission_id) values('$lastid','$user_lastid','2')");
					$inserperm4=mysql_query("INSERT INTO ox_account_user_permission_assoc(account_id,user_id,permission_id) values('$lastid','$user_lastid','1')");
					$inserperm5=mysql_query("INSERT INTO ox_account_user_permission_assoc(account_id,user_id,permission_id) values('$lastid','$user_lastid','11')");
	
	
	/*Send the mail after registration */
	//$mailid=mysql_query("select * from ox_clients where clientname="$name"");
	//$norow1=mysql_fetch_array($mailid);
	//$to=$norow1['email'];
	$to=$email;
	$query=mysql_query("select * from ox_users where default_account_id ='2'");
	$query1=mysql_fetch_array($query);
	$fromemailid=$query1['email_address'];
	$subject="message from Adserver";
	$message="<table><tr><td><h1>U have successfully register with in Adserver</h1></td></tr>
	</table>";

	$this->email->set_newline("\r\n");
        $config['mailtype'] = 'html';
        $config['charset'] = 'UTF-8';	
        $this->email->initialize($config);
        $this->email->from($fromemailid);
        $this->email->to($to);        
        $this->email->subject($subject);        
        $this->email->message($message);
	$this->email->send();

	$data['reg']="Registration Successfully";
	$this->load->view('advertiser/login',$data);
	}
	

/* Forget password */
	function forgetpassword()
	{
	$this->load->view('advertiser/forgetpassword');
	}
	function forgetpassprocess()
	{
	$email=$this->input->post("email");
	$newpass=md5($this->input->post("password"));
	$confirm=md5($this->input->post("confirm_password"));
	if($newpass == $confirm)
	{
	$up=array("password"=>$newpass);
	$where=array("email_address"=>$email);
	$this->dbproduct->update_passdata($up,$where);
	$data['reg']="Password changed Successfully";
	$this->load->view('advertiser/login',$data);
	}
	else
	{
	$data["pass"]="give confirm password same as new password";
	$this->load->view('advertiser/forgetpassword',$data);
	}
	}


	
/* Add campaign */
	function addcampaign()
	{
		$data['title']="Adserver-Add New Campaigns";
		$client=$this->session->userdata('clientid');
		$this->load->view('advertiser/campaigns/addcampaign',$data);
		
	}
	
	
	function addcampaignprocess()
	{
		$CampaignName=$this->input->post("campaign");
		$weight=$this->input->post("weight");
		if($CampaignName!="")
		{
		$startdate=$this->input->post("startdate");
		$enddate=$this->input->post("enddate");
	
				/*Start date */
				if($startdate=="startdate")
				{
				 $activate_time=date("Y-m-d h:i:s");
				 $status_startdate="1";
				}
				else
				{
				$activate_time=$this->input->post("pickerfield");
				$status_startdate="0";
				}
				$budget=$this->input->post("budget");
				$revenue=$this->input->post("select_model");
				$select_model=$this->input->post("select_model");
				if($revenue=="1")
				{
					$price=$this->input->post("imp");
				}
				else if($revenue=="2")
				{
			    	$price=$this->input->post("clicks");
				}
				else
				{
					$price=$this->input->post("conv");
				}
				
				$client=$this->session->userdata('clientid');
	/*Enddate */
	if($enddate=="enddate")
	{
				$expire_time=NULL;
				$status_enddate="1";
				$budget=$this->input->post("budget");
				$revenue=$this->input->post("select_model");
				$select_model=$this->input->post("select_model");
				$client=$this->session->userdata('clientid');
				
				/*Awaiting status */
				$activate_time;
				$starttime=explode(" ",$activate_time);
				$currenttime=date("Y-m-d");
				$stime=explode("-",$starttime[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$data=$end_date-$start_date;
				$date_diff = floor($data/(60*60*24));
				
					if($date_diff<0)
					{
					$status="2";
					$inactive="1";
					}
					else
					{
					$status="0";
					$inactive="0";
					}
					
				
				$insert=array("campaignname"=>$CampaignName,"clientid"=>$client,"activate_time"=>$activate_time,"expire_time"=>$expire_time,"revenue_type"=>$revenue,"status_startdate"=>$status_startdate,"status_enddate"=>$status_enddate,"status"=>$status,"inactive"=>$inactive,"revenue"=>$price,"weight"=>$weight);
				$data=$this->dbproduct->insert_campaigndata($insert);
				
				
	}
	else
	{
	$client=$this->session->userdata('clientid');
	/*End date with specify */
				$activate_time;
				$expire_time=$this->input->post("pickerfield2");
				$status_enddate="0";
				$revenue=$this->input->post("select_model");
				
				/*Awaiting status */
				$starttime=explode(" ",$activate_time);
				$currenttime=date("Y-m-d");
				$stime=explode("-",$starttime[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$data=$end_date-$start_date;
			    $date_diff = floor($data/(60*60*24));
				//echo "<br>";
				 /*Completed staus */
				$currenttime=date("Y-m-d");
				$split_expire=explode(" ",$expire_time);
				$sdate=explode('-',$currenttime);
				$edate=explode('-',$split_expire[0]);
				$s_date=mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
				$e_date=mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
				$data=$e_date-$s_date;
				$finalvalues = floor($data/(60*60*24));
				
								
				if($date_diff<0 && $finalvalues<0)
				{
				$status="2";
				$inactive="1";
				}
				elseif($date_diff<0)
				{
				//echo "2";
				$status="2";
				$inactive="1";
				}
				elseif($finalvalues<0)
				{
				//echo "3";
				$status="3";
				$inactive="1";
				}
				else
				{
				//echo "4";
				$status="0";
				$inactive="0";
				}
		
			
		$insert=array("campaignname"=>$CampaignName,"clientid"=>$client,"activate_time"=>$activate_time,"expire_time"=>$expire_time,"revenue_type"=>$revenue,"status_startdate"=>$status_startdate,"status_enddate"=>$status_enddate,"status"=>$status,"inactive"=>$inactive,"revenue"=>$price,"weight"=>$weight);
	$this->dbproduct->insert_campaigndata($insert);	
	}
	
	$cam_db_id=mysql_insert_id();
	$bud_id=mysql_query("select * from ox_campaigns where campaignid='$cam_db_id'");
	$budget_id=mysql_fetch_array($bud_id);
	$bud_camid=$budget_id['campaignid'];
	$current_date=$this->input->post('current_date');
		$budget=$this->input->post("budget");
	$budget_ins=array("campaignid"=>$bud_camid,"budget"=>$budget,"clientid"=>$client,"currentdate"=>$current_date,"dailybudget"=>$budget);
	$this->dbproduct->insert_budgetdata($budget_ins);
	$this->session->set_userdata('campaignname',$CampaignName);
	/*Balance should be reduce in addbalance table */
		$acc_bal=mysql_query("select * from oxm_accbalance where clientid='$client'");
		$acc_ba=mysql_fetch_array($acc_bal);
		$acc_balance=$acc_ba['accbalance'];
		if($acc_balance>0)
		{
		if($budget==0)
		{
		$up=array("status"=>'1',"inactive"=>'1');
		$where=array("campaignid"=>$bud_camid);
		$list=$this->dbproduct->update_campaignsdata($up,$where);
		$data['list']=$list;
		$this->load->view('advertiser/campaigns/campaign',$data);
		}
		else
		{
		$fetch_date=mysql_query("select * from ox_campaigns where campaignid='$bud_camid'");
		$value_date=mysql_fetch_array($fetch_date);
		if($value_date['status']==0 && $value_date['inactive']==0)
		{
		$up=array("status"=>'0',"inactive"=>'0');
		$where=array("campaignid"=>$bud_camid);
		$list=$this->dbproduct->update_campaignsdata($up,$where);
		$data['list']=$list;
		$this->load->view('advertiser/campaigns/campaign',$data);
		}
		}
		}
		else
		{
		$up=array("status"=>'1',"inactive"=>'1');
		$where=array("campaignid"=>$bud_camid);
		$list=$this->dbproduct->update_campaignsdata($up,$where);
		$data['list']=$list;
		$this->load->view('advertiser/campaigns/campaign',$data);
		}
	
	redirect("advertiser/campaign");
	
	}
	}
/* view campaign */
function campaign($start=0)
{
		    	$showvalue="";
			$limit=$this->input->post("pages");
			$clientid=$this->session->userdata('clientid');
			$value=mysql_query("select *from ox_campaigns where clientid='$clientid'");
			$a=mysql_num_rows($value);
			if($a>0)	
			{
			$value=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
			$list=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
			$config['base_url'] = base_url().'index.php/advertiser/campaign';
			$config['total_rows'] = count($value);
			$config['per_page']=($this->uri->segment(3)!="")?$this->uri->segment(3):"5";
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['list']=$list;
			$data['limit']='5';
			$this->load->view('advertiser/campaigns/campaign',$data);
			}
			else
			{
				$data['limit']='5';
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'0',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'0',"clientid"=>$id));
				$data['list']=$value;	
				$data['error']="There are currently no campaigns defined for this advertiser";
				$this->load->view('advertiser/campaigns/campaign',$data);
			}
			
		}
function selectpages($start=0,$limit=false)
{

			if($limit != false){
				$this->session->set_userdata('page_limit',$limit);
			}
			else{
				if($this->session->userdata('page_limit') != ''){
					//$this->session->set_userdata('page_limit',$this->session->userdata('page_limit'));
				}
				else{
					$this->session->set_userdata('page_limit',5);
				}
			}
			//$limit=$this->input->post("pages");
			
			$clientid=$this->session->userdata('clientid');
			$value=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
			$list=$this->dbproduct->getdata($start,$this->session->userdata('page_limit'),array("clientid"=>$clientid));
			$config['base_url'] = base_url().'index.php/advertiser/selectpages';
			$config['total_rows'] = count($value);
			$config['per_page']= $this->session->userdata('page_limit');//$limit; 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['list']=$list;
			$data['limit']=$this->session->userdata('page_limit');
			$this->load->view('advertiser/campaigns/campaign',$data);
}	

/*Run Campaigns */
function runcampaign()
{	
		$clientid=$this->session->userdata('clientid');
		$s=$this->input->post("runvalue");
		$t=explode('.',rtrim($s,'.'));

		foreach($t as $key)
		{
		$acc_bal=mysql_query("select * from oxm_accbalance where clientid='$clientid'");
		$acc_ba=mysql_fetch_array($acc_bal);
		$acc_balance=$acc_ba['accbalance'];
		$oxm_budget=mysql_query("select * from oxm_budget where campaignid='$key'");
		$ox_budget=mysql_fetch_array($oxm_budget);
		$budget=$ox_budget['dailybudget'];
		$currentdate=date("Y-m-d");
		$report=mysql_query("select * from oxm_report where clientid='$clientid' AND campaignid='$key' AND date='$currentdate'");
		$report1=mysql_fetch_array($report);
		$totalbudget=$report1['amount'];
		$date_fetch=mysql_query("select * from ox_campaigns where campaignid='$key'");
		$dates_fetch=mysql_fetch_array($date_fetch);
		$starttime=$dates_fetch['activate_time'];
		$endtime=$dates_fetch['expire_time'];
		$status_enddate=$dates_fetch['status_enddate'];
			
		if($totalbudget!=" ")
		{
		$totalbudget=$ox_report['amount'];
		}
		else
		{
		$totalbudget=0;
		}	
		
		if($acc_balance>0)
		{
		//echo"1";
		if($totalbudget<=$budget)
		{		
		
		if($status_enddate!="1")
		{
				/*completed  with enddate*/
				$s_time=date("Y-m-d");
				$e_time=explode(" ",$endtime);
				$stime=explode('-',$s_time);
				$ctime=explode('-',$e_time[0]);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $diff = floor($value/(60*60*24));
				
				/*completed with startdate */
				$s_time=date("Y-m-d");
				$e_time=explode(" ",$starttime);
				$stime=explode('-',$s_time);
				$ctime=explode('-',$e_time[0]);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $diff1 = floor($value/(60*60*24));
							
				if($diff<0 &&  $diff1 <0 )
				{
				$status="3";
				$inactive="2";
				}
				elseif($diff<0)
				{
				$status="1";
				$inactive="2";
				}
				else
				{
				$status="0";
				$inactive="0";
				}
				$up=array("status"=>$status,"inactive"=>$inactive);
				$where=array("campaignid"=>$key);
				$list=$this->dbproduct->update_campaignsdata($up,$where);
				$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
				//echo count($pause);
				
				$newlist=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
				$config['base_url'] = base_url().'index.php/advertiser/campaign/'.$clientid;
				$config['total_rows'] = count($pause);
				$config['per_page']=($this->uri->segment(3)!="")?$this->uri->segment(3):"5";
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$this->pagination->initialize($config);
				$data['list']=$newlist;
				$data['msg']="Your Campaign is Running";
		
				
		}
		else
		{
				/*Awaiting status */
				$currenttime=date("Y-m-d");
				$s_time=explode(" ",$starttime);
				$stime=explode('-',$s_time[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $date_diff = floor($value/(60*60*24));
				if($date_diff<0)
				{
				$status="2";
				$inactive="2";
				}
				else
				{
				$status="0";
				$inactive="0";
				}
							
		}
				$up=array("status"=>$status,"inactive"=>$inactive);
				$where=array("campaignid"=>$key);
				$list=$this->dbproduct->update_campaignsdata($up,$where);
				$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
					//echo "2".count($pause);
				$newlist=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
				$config['base_url'] = base_url().'index.php/advertiser/campaign/'.$clientid;
				$config['total_rows'] = count($pause);
				$config['per_page']=($this->uri->segment(3)!="")?$this->uri->segment(3):"5";
				$config['next_link'] = 'Next';

				$config['prev_link'] = 'Prev';
				$this->pagination->initialize($config);
				$data['list']=$newlist;
				$data['msg']="Your Campaign is Running";
			
		
		}
		else
		{
		$up=array("status"=>'1',"inactive"=>'1');
		$where=array("campaignid"=>$key);
		$list=$this->dbproduct->update_campaignsdata($up,$where);
		$data['run_error']="Your Daily budget is completed..";
		$data['list']=$list;
		}
		}
		else
		{
		if($status_enddate!="1")
		{
		
				/*completed  with enddate*/
				$s_time=date("Y-m-d");
				$e_time=explode(" ",$endtime);
				$stime=explode('-',$s_time);
				$ctime=explode('-',$e_time[0]);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $diff = floor($value/(60*60*24));
				
				/*completed with startdate */
				$s_time=date("Y-m-d");
				$e_time=explode(" ",$starttime);
				$stime=explode('-',$s_time);
				$ctime=explode('-',$e_time[0]);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $diff1 = floor($value/(60*60*24));
							
				if($diff<0 &&  $diff1 <0 )
				{
				$status="3";
				$inactive="2";
				}
				elseif($diff<0)
				{
				$status="1";
				$inactive="2";
				}
				else
				{
				$status="1";
				$inactive="0";
				}
				$up=array("status"=>$status,"inactive"=>$inactive);
				$where=array("campaignid"=>$key);
				$list=$this->dbproduct->update_campaignsdata($up,$where);
				$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
					//echo "3".count($pause);
				$newlist=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
				$config['base_url'] = base_url().'index.php/advertiser/campaign/'.$clientid;
				$config['total_rows'] = count($pause);
				$config['per_page']=($this->uri->segment(3)!="")?$this->uri->segment(3):"5";
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$this->pagination->initialize($config);
				$data['list']=$newlist;
				$data['msg1']="Your Account Balance is Deplicated.So add fund To account Then start your process";
			
				
				
		}
		else
		{
				/*Awaiting status */
				$currenttime=date("Y-m-d");
				$s_time=explode(" ",$starttime);
				$stime=explode('-',$s_time[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$value=$end_date-$start_date;
			    $date_diff = floor($value/(60*60*24));
				if($date_diff<0)
				{
				$status="2";
				$inactive="2";
				}
				else
				{
				$status="1";
				$inactive="0";
				}
				$up=array("status"=>$status,"inactive"=>$inactive);
				$where=array("campaignid"=>$key);
				$list=$this->dbproduct->update_campaignsdata($up,$where);
				$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
				//echo "4" count($pause);
				$newlist=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
				$config['base_url'] = base_url().'index.php/advertiser/campaign/'.$clientid;
				$config['total_rows'] = count($pause);
				$config['per_page']=($this->uri->segment(3)!="")?$this->uri->segment(3):"5";
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$this->pagination->initialize($config);
				$data['list']=$newlist;
				$data['msg1']="Your Account Balance is Deplicated.So add fund To account Then start your process";
				
							
		}
		}
		}
		$this->load->view('advertiser/campaigns/campaign',$data);
			
}
function selectpages_run($start=0,$limit=false)
{

			if($limit != false){
				$this->session->set_userdata('page_limit',$limit);
			}
			else{
				if($this->session->userdata('page_limit') != ''){
					//$this->session->set_userdata('page_limit',$this->session->userdata('page_limit'));
				}
				else{
					$this->session->set_userdata('page_limit',5);
				}
			}
			$clientid=$this->session->userdata('clientid');
			$s=$this->input->post("runvalue");
			$t=explode('.',$s);
			foreach($t as $key)
			{
			$where=array("campaignid"=>$key);
			$up=array("status"=>'0');
			$this->dbproduct->update_campaignsdata($up,$where);
			}
			$run=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid,"status"=>'0'));
			$value=$this->dbproduct->getdata($start,$this->session->userdata('page_limit'),array("clientid"=>$clientid,"status"=>'0'));
			//$config['base_url'] = 'http://localhost/advertiser/dbproduct/advertiser/selectpages_run';
			$config['total_rows'] = count($run);
			$config['per_page']= $this->session->userdata('page_limit'); 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['run']=$value;
			$data['limit']= $this->session->userdata('page_limit');
			$this->load->view('advertiser/campaign',$data);
}	



		
/* Pause Campaigns*/		
function pausecampaign($start=0)
{
			$clientid=$this->session->userdata("clientid");
			$s=$this->input->post("pausevalue");
			$t=explode('.',$s);
			foreach($t as $key)
				{
				 $key;
				$where=array("campaignid"=>$key);
				$up=array("status"=>'1',"inactive"=>'2');
				$this->dbproduct->update_campaignsdata($up,$where);
				}
			$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
			$value=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
			//$config['base_url'] = 'http://localhost/advertiser/dbproduct/advertiser/pausecampaign';
			$config['total_rows'] = count($pause);
			$config['per_page']='5'; 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['list']=$value;
			$data['limit']='5';
			$data['msg']="Campaign is Paused";
			$this->load->view('advertiser/campaigns/campaign',$data);
}

function selectpages_pause($start=0,$limit=false)
{	
			if($limit != false){
				$this->session->set_userdata('page_limit',$limit);
			}
			else{
				if($this->session->userdata('page_limit') != ''){
					//$this->session->set_userdata('page_limit',$this->session->userdata('page_limit'));
				}
				else{
					$this->session->set_userdata('page_limit',5);
				}
			}
			$clientid=$this->session->userdata("clientid");
			$s=$this->input->post("pausevalue");
			$t=explode('.',$s);
			foreach($t as $key)
				{
				 $key;
				$where=array("campaignid"=>$key);
				$up=array("status"=>'1');
				$this->dbproduct->update_campaignsdata($up,$where);
				}
			$pause=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid,"status"=>'1'));
			$value=$this->dbproduct->getdata($start,$this->session->userdata('page_limit'),array("clientid"=>$clientid,"status"=>'1'));
			//$config['base_url'] = 'http://localhost/advertiser/dbproduct/advertiser/selectpages_pause';
			$config['total_rows'] = count($pause);
			$config['per_page']=$this->session->userdata('page_limit'); 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['pause']=$value;
			$data['limit']=$this->session->userdata('page_limit');
			$this->load->view('advertiser/campaigns/campaign',$data);



}	


/*delete campaigns */
	function deletecampaign($start=0)
	{
	$clientid=$this->session->userdata("clientid");
	$s=$this->input->post("jsvalue");
	$t=explode('.',$s);
	if(count($t)>0)
	{
	foreach($t as $u)
	{
/*ox_uniques */
	mysql_query("delete from ox_data_bkt_unique_m where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_unique_c where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_unique_con where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*bucket tables */
	mysql_query("delete from ox_data_bkt_a where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_a_var where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_c where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_m where creative_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*Linking tables */
	mysql_query("delete from ox_ad_zone_assoc where ad_id IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*Bucket country table */
	mysql_query("delete from ox_data_bkt_country_c where creative_id  IN (select b.bannerid from ox_banners b where b.campaignid='$u')");
	mysql_query("delete from ox_data_bkt_country_m where creative_id  IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*Country table */
	mysql_query("delete from ox_stats_country where creative_id  IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*Intermediate table */
	mysql_query("delete from  ox_data_intermediate_ad where ad_id  IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/* Ad hourly table */
	mysql_query("delete from  ox_data_summary_ad_hourly where ad_id  IN (select b.bannerid from ox_banners b where b.campaignid='$u')");

/*oxm_report */
	mysql_query("delete from oxm_report where campaignid ='$u'");

/*oxm_budget */
	mysql_query("delete from oxm_budget where campaignid ='$u'");



	$this->dbproduct->delete_campaigndata(array("campaignid"=>$u));
	$this->dbproduct->delete_bannerdata(array("campaignid"=>$u));
	$this->dbproduct->delete_Placementdata(array("placement_id"=>$u));
	$this->targeting->deleteTargeting(array("campaignid"=>$u));
	$this->targeting->deleteTargetingLimitation(array("campaignid"=>$u));
	}

	$lists=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
			$value=$this->dbproduct->getdata($start,5,array("clientid"=>$clientid));
			//$config['base_url'] = 'http://localhost/advertiser/dbproduct/advertiser/campaign';
			$config['total_rows'] = count($lists);
			$config['per_page']='5'; 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['limit']='5';
	redirect("advertiser/campaign",$data);
	}
	else
	{
	$data['error']="There are currently no campaigns defined for this advertiser";
	redirect("advertiser/campaign",$data);
	}
	}
function selectpages_delete($start=0,$limit=false)
{	
			if($limit != false){
				$this->session->set_userdata('page_limit',$limit);
			}
			else{
				if($this->session->userdata('page_limit') != ''){
					//$this->session->set_userdata('page_limit',$this->session->userdata('page_limit'));
				}
				else{
					$this->session->set_userdata('page_limit',5);
				}
				}
	$clientid=$this->session->userdata("clientid");
	$s=$this->input->post("jsvalue");
	$t=explode('.',$s);
	if(count($t)>0)
	{
	foreach($t as $u)
	{
	$u.'<br>';
	$this->dbproduct->delete_campaigndata(array("campaignid"=>$u));
	$this->dbproduct->delete_bannerdata(array("campaignid"=>$u));
	}
	$lists=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
			$value=$this->dbproduct->getdata($start,$this->session->userdata('page_limit'),array("clientid"=>$clientid));
			//$config['base_url'] = 'http://localhost/advertiser/dbproduct/advertiser/campaign';
			$config['total_rows'] = count($lists);
			$config['per_page']=$this->session->userdata('page_limit'); 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['lists']=$value;
			$data['limit']=$this->session->userdata('page_limit');
			$this->load->view('advertiser/campaigns/campaign',$data);
	}				
}	
/*select campaigns */
function selectcampaign($status,$start=0)
{

$id=$this->session->userdata('clientid');
/*$value =($this->uri->segment(3) !="")?$this->uri->segment(3):"active";
$status_value=array("active"=>0,"deactive"=>1,'Allcampaigns'=>2);
	echo $check=$status_value;	*/
		
		if($status=="Allcampaigns")
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("clientid"=>$id));
				$config['base_url'] = base_url().'dbproduct/advertiser/selectcampaign/Allcampaigns';
				$config['total_rows'] = count($list);
				$config['per_page']='5'; 
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$config['full_tag_open'] = '<p class="pagination">';
				$config['first_tag_close'] = '</p>';
				$this->pagination->initialize($config);
				$data['list']=$value;
				$data['limit']='5';	
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
			if($status=="active")
				{
				$value=mysql_query("select *from ox_campaigns where status ='0' and clientid='$id'");
				$a=mysql_num_rows($value);
				if($a>0)		
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'0',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'0',"clientid"=>$id));
				$config['base_url'] =  base_url().'index.php/advertiser/selectcampaign/active';
				$config['total_rows'] = count($list);
				$config['per_page']='5'; 
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$config['full_tag_open'] = '<p class="pagination">';
				$config['first_tag_close'] = '</p>';
				$this->pagination->initialize($config);
				$data['list']=$value;
				$data['limit']='5';				
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				else
				{
				$data['limit']='5';
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'0',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'0',"clientid"=>$id));
				$data['list']=$value;	
				$data['error']="There are currently no  active campaigns defined for this advertiser";
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				}
			if($status=="deactive")
				{

				$value=mysql_query("select * from ox_campaigns where status ='1' and clientid='$id'");
				$a=mysql_num_rows($value);
				if($a>0)		
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'1',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'1',"clientid"=>$id));
				$config['base_url'] =  base_url().'index.php/advertiser/selectcampaign/deactive';
				$config['total_rows'] = count($list);
				$config['per_page']='5'; 
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$config['full_tag_open'] = '<p class="pagination">';
				$config['first_tag_close'] = '</p>';
				$this->pagination->initialize($config);
				$data['list']=$value;
				$data['limit']='5';	
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				else
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'1',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'1',"clientid"=>$id));
				$data['list']=$value;	
				$data['limit']='5';
				$data['error']="There are currently no inactive campaigns defined for this advertiser";
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				}
				if($status=="awaiting")
				{
				$value=mysql_query("select *from ox_campaigns where status ='2' and clientid='$id'");
				$a=mysql_num_rows($value);
				if($a>0)		
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'2',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'2',"clientid"=>$id));
				$config['base_url'] =  base_url().'index.php/advertiser/selectcampaign/awaiting';
				$config['total_rows'] = count($list);
				$config['per_page']='5'; 
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$config['full_tag_open'] = '<p class="pagination">';
				$config['first_tag_close'] = '</p>';
				$this->pagination->initialize($config);
				$data['list']=$value;
				$data['limit']='5';				
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				else
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'2',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'2',"clientid"=>$id));
				$data['list']=$value;
				$data['limit']='5';	
				$data['error']="There are currently no awaiting campaigns defined for this advertiser";
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				}
				if($status=="completed")
				{
				$value=mysql_query("select *from ox_campaigns where status ='3' and clientid='$id'");
				$a=mysql_num_rows($value);
				if($a>0)		
				{
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'3',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'3',"clientid"=>$id));
				$config['base_url'] =  base_url().'index.php/advertiser/selectcampaign/completed';
				$config['total_rows'] = count($list);
				$config['per_page']='5'; 
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Prev';
				$config['full_tag_open'] = '<p class="pagination">';
				$config['first_tag_close'] = '</p>';
				$this->pagination->initialize($config);
				$data['list']=$value;
				$data['limit']='5';				
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				else
				{
				$data['limit']='5';	
				$list=$this->dbproduct->retrieve_campaigndata(array("status"=>'3',"clientid"=>$id));
				$value=$this->dbproduct->getdata($start,5,array("status"=>'3',"clientid"=>$id));
				$data['list']=$value;
				$data['error']="There are currently no Completed campaigns defined for this advertiser";
				$this->load->view('advertiser/campaigns/campaign',$data);
				}
				}
				
				
			
}
		
/*update campaigns*/
function updatecampaign($campaignid)
	{
	$lists=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$campaignid));
	$data['title']="Adserver-Update Campaigns";
	$data['lists']=$lists;
	$this->load->view('advertiser/campaigns/updatecampaign',$data);
	}
	
function updatecampaignprocess($campaignid)
	{
	$client=$this->session->userdata('clientid');
	$cname=$this->input->post("cname");
	$startdate=$this->input->post("startdate");
	$enddate=$this->input->post("enddate");
	$select_model=$this->input->post("select_model");
	$old_cam_id=$this->input->post("old_cam_id");
	$weight=$this->input->post("weight");
	
	if($startdate=="startdate")
	{
	$activate_time=date("y-m-d h:i:s");
	$status_startdate="1";
	}
	else
	{
	$activate_time=$this->input->post("pickerfield");
	$status_startdate="0";
	}
	
	/*Enddate */
	if($enddate=="enddate")
	{
				$expire_time=NULL;
				$status_enddate="1";
				$budget=$this->input->post("budget");
				$revenue=$this->input->post("select_model");
				$note=$this->input->post("note");
				$client=$this->session->userdata('clientid');
				if($revenue=="1")
				{
					$price=$this->input->post("imp");
				}
				else if($revenue=="2")
				{
			    	$price=$this->input->post("clicks");
				}
				else
				{
					$price=$this->input->post("conv");
				}
				
				/*Awaiting status */
				$activate_time;
				$starttime=explode(" ",$activate_time);
				$currenttime=date("Y-m-d");
				$stime=explode("-",$starttime[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$data=$end_date-$start_date;
				$date_diff = floor($data/(60*60*24));
				$acc_bal=mysql_query("select * from oxm_accbalance where clientid='$client'");
				$acc_ba=mysql_fetch_array($acc_bal);
				$acc_balance=$acc_ba['accbalance'];
				
					if($acc_balance>0)
					{	
					if($date_diff<0)
					{
					$status="2";
					$inactive="1";
					}
					else
					{
					$status="0";
					$inactive="0";
					}
					}
					else
					{
					$status="1";
					$inactive="1";
					}
					$up=array("campaignname"=>$cname,"activate_time"=>$activate_time,"expire_time"=>$expire_time,"clientid"=>$client,"revenue_type"=>$revenue,"status_startdate"=>$status_startdate,"status_enddate"=>$status_enddate,"status"=>$status,"inactive"=>$inactive,"revenue"=>$price,"weight"=>$weight);
					
	$where=array("campaignid"=>$campaignid);
	$this->dbproduct->update_campaignsdata($up,$where);				
	}
	else
	{
	$client=$this->session->userdata('clientid');
	/*End date with specify */
				$activate_time;
				$expire_time=$this->input->post("pickerfield2");
				$status_enddate="0";
				$revenue=$this->input->post("select_model");
				$select_model=$this->input->post("select_model");
				if($revenue=="1")
				{
					$price=$this->input->post("imp");
				}
				else if($revenue=="2")
				{
			    	$price=$this->input->post("clicks");
				}
				else
				{
					$price=$this->input->post("conv");
				}
				
				/*Awaiting status */
				$starttime=explode(" ",$activate_time);
				$currenttime=date("Y-m-d");
				$stime=explode("-",$starttime[0]);
				$ctime=explode('-',$currenttime);
				$start_date=mktime(0,0,0,$stime[1],$stime[2],$stime[0]);
				$end_date=mktime(0,0,0,$ctime[1],$ctime[2],$ctime[0]);
				$data=$end_date-$start_date;
			    $date_diff = floor($data/(60*60*24));
				//echo "<br>";
				 /*Completed staus */
				$currenttime=date("Y-m-d");
				$split_expire=explode(" ",$expire_time);
				$sdate=explode('-',$currenttime);
				$edate=explode('-',$split_expire[0]);
				$s_date=mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
				$e_date=mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
				$data=$e_date-$s_date;
				$finalvalues = floor($data/(60*60*24));
				//echo "<br>";
				
				$acc_bal=mysql_query("select * from oxm_accbalance where clientid='$client'");
				$acc_ba=mysql_fetch_array($acc_bal);
				$acc_balance=$acc_ba['accbalance'];
				
				if($acc_balance>0)
				{		
				if($date_diff<0 && $finalvalues<0)
				{
				//echo "1";
				$status="2";
				$inactive="1";
				}
				elseif($date_diff<0)
				{
				//echo "2";
				$status="2";
				$inactive="1";
				}
				elseif($finalvalues<0)
				{
				//echo "3";
				$status="3";
				$inactive="1";
				}
				else
				{
				//echo "4";
				$status="0";
				$inactive="0";
				}
				}
				else
				{
				$status="2";
				$inactive="1";
				}
		
		$up=array("campaignname"=>$cname,"activate_time"=>$activate_time,"expire_time"=>$expire_time,"clientid"=>$client,"revenue_type"=>$revenue,"status_startdate"=>$status_startdate,"status_enddate"=>$status_enddate,"status"=>$status,"inactive"=>$inactive,"revenue"=>$price,"weight"=>$weight);
	$where=array("campaignid"=>$campaignid);

	$this->dbproduct->update_campaignsdata($up,$where);	
	}
				$budget=$this->input->post("budget");
				$revenue=$this->input->post("select_model");
				$client=$this->session->userdata('clientid');
				$current_date=date("Y-m-d");
	$up_budget=array("clientid"=>$client,"budget"=>$budget,"currentdate"=>$current_date,"dailybudget"=>$budget);
	$this->dbproduct->update_budgetdata($up_budget,$where);
	/*Balance should be reduce in addbalance table */
		$acc_bal=mysql_query("select * from oxm_accbalance where clientid='$client'");
		$acc_ba=mysql_fetch_array($acc_bal);
		$acc_balance=$acc_ba['accbalance'];
		if($acc_balance>0)
		{
			if($budget==0)
			{
				$up=array("status"=>'1',"inactive"=>'1');
				$where=array("campaignid"=>$bud_camid);
				$list=$this->dbproduct->update_campaignsdata($up,$where);
				$data['list']=$list;
				$this->load->view('advertiser/campaigns/campaign',$data);
			}
		}
		
		/* Linked with zone */
		if($old_cam_id != $select_model)
		{
		$linkingzone=mysql_query("select * from ox_placement_zone_assoc where placement_id='$campaignid'");
		if(mysql_num_rows($linkingzone)>0)
		{
		mysql_query("delete from ox_placement_zone_assoc where placement_id='$campaignid'");
		}
		$campaign_detail=mysql_query("select * from ox_banners where campaignid='$campaignid'");
		if(mysql_num_rows($campaign_detail)>0)
		{
		while($banner_data=mysql_fetch_array($campaign_detail))
		{
		$db_banner_id=$banner_data['bannerid'];
		mysql_query("delete from ox_ad_zone_assoc where ad_id='$db_banner_id'");
		}
		}		
		}
		redirect("advertiser/campaign");
	}

	
	/* Duplicate zones*/
	function duplicatecheck($campaignname)
	{
        $qry   ="SELECT * FROM ox_campaigns WHERE campaignname='".trim($campaignname)."' LIMIT 1";
		$query =$this->db->query($qry);
	
		if($query->num_rows>0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}	
 	}
	
/* Duplicate campaigns*/
	
function duplicatecampaign($campaignid)
{
			 $dup=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$campaignid));
			 $data['title']="Adserver-Duplicate Campaigns";
			 $data['dup']=$dup;
			 $this->load->view('advertiser/campaigns/duplicatecampaign',$data);	
 }
function dupcampaign()
{

 	$clientid=$this->session->userdata('clientid');
	$id=$this->session->userdata('campaignid');
	$copycampaign=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$id));
	foreach($copycampaign as $cpc)
	{
	$startdate=$cpc->activate_time;
	$enddate=$cpc->	expire_time;
	$pricingmodel=$cpc->revenue_type;
	$status=$cpc->status;
	$inactive=$cpc->inactive;
	$status_startdate=$cpc->status_startdate;
	$status_enddate=$cpc->status_enddate;	
	$revenue=$cpc->revenue;
	}
	/*fetch values from budget table */
	$dup_bud=mysql_query("select * from oxm_budget where campaignid='$id'");
	$dupp_bud=mysql_fetch_array($dup_bud);
	$dup_budget=$dupp_bud['dailybudget'];
	$budget=$dupp_bud['budget'];
	/*Insert the campaigns */
	$camname=$this->input->post("newcam");
	$ins=array("campaignname"=>$camname,"clientid"=>$clientid,"activate_time"=>$startdate,"expire_time"=>$enddate,"revenue_type"=>$pricingmodel,"status"=>$status,"inactive"=>$inactive,"status_startdate"=>$status_startdate,"status_enddate"=>$status_enddate,"revenue"=>$revenue);
	$this->dbproduct->insert_campaigndata($ins);
	$newcampaignid=mysql_insert_id();
	$campaign_id=array("placement_id"=>$id);
	$camquery=mysql_query("select * from ox_placement_zone_assoc where placement_id='$id'");
	if(mysql_num_rows($camquery)>0)
	{
	while($camquery1=mysql_fetch_array($camquery))
	{
	$zone_id=$camquery1['zone_id'];
	$ins=array("placement_id"=>$newcampaignid,"zone_id"=>$zone_id);
	$this->dbproduct->insert_linkplacementdata($ins);
	}
	}
	$none=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$newcampaignid));
	foreach($none as $row)
	{
	$rowid= $row->campaignid;

	}
	/*Insert the budget in oxm_budget */
	$current_date=date('Y-m-d');
	 $ins_bud=array("campaignid"=>$rowid,"clientid"=>$clientid,"dailybudget"=>$dup_budget,"currentdate"=>$current_date,"budget"=>$budget);
	$this->dbproduct->insert_budgetdata($ins_bud);

	/*Copy-Targetting*/
	$where=array("campaignid"=>$id);
	$qry=$this->db->getwhere('djx_targeting_limitations',$where);	
	foreach($qry->result() as $target)
	{
	$devices=$target->devices;
	$locations=$target->locations;
	$operators=$target->operators;
	$gender=$target->gender;
	$ages=$target->ages;
	$device_type=$target->device_type;
	$location_type=$target->location_type;
	$operator_type=$target->operator_type;
	$gender_type=$target->gender_type;
	$ages_type=$target->ages_type;
	$model=$target->model;
	$model_type=$target->model_type;
	$target_data =array('devices' =>$devices, 'locations' =>$locations, 'operators' =>$operators, 'ages' =>$ages, 'gender' =>$gender, 'device_type' =>$device_type, 'location_type' =>$location_type, 'operator_type' =>$operator_type, 'gender_type' =>$gender_type, 'ages_type' =>$ages_type, 'model' =>$model, 'model_type' =>$model_type, 'campaignid' =>$rowid);
	$this->targeting->insertTargetingLimitation($target_data);
	}
	/*Targetting -campaign */
	$qry1=$this->db->getwhere('djx_campaign_limitation',$where);	
	foreach($qry1->result() as $target_c)
	{
	$compiledlimitation=$target_c->compiledlimitation;
	$acl_plugins=$target_c->acl_plugins;
	$status=$target_c->status;
	$targetdata =array('compiledlimitation' =>$compiledlimitation, 'acl_plugins' =>$acl_plugins, 'status' =>$status,'campaignid' => $rowid);
	$this->targeting->insertTargeting($targetdata);
	}
	
	
	
	/*copy banners */

	//$query=$this->db->getwhere('ox_banners',array("campaignid"=>$id));
	//$query=$this->db->get('ox_banners');
	//$this->db->where('campaignid', $id); 
	//$this->db->order_by("bannerid", "asc"); 
	$query=mysql_query("select * from ox_banners where campaignid='$id' and (master_banner IN(-1,-2,-3)) ORDER BY bannerid ASC");
	if(mysql_num_rows($query)>0)
	{
	while($row=mysql_fetch_array($query))
		{ 
			$bannerid=$row['bannerid']; 
			$campaignid=$rowid;
			$contenttype=$row['contenttype'];
			$pluginversion=$row['pluginversion'];
			$storagetype=$row['storagetype'];
			$filename=$row['filename'];
			$imageurl=$row['imageurl'];
			$htmltemplate=$row['htmltemplate'];
			$htmlcache=$row['htmlcache'];
			$width=$row['width'];
			$height=$row['height'];
			$weight=$row['weight'];
			$seq=$row['seq'];
			$url=$row['url'];
			$alt=$row['alt'];
			$statustext=$row['statustext'];
			$bannertext=$row['bannertext'];
			$description=$row['description'];
			$adserver=$row['adserver'];
			$block=$row['block'];
			$capping=$row['capping'];
			$session_capping=$row['session_capping'];
			$compiledlimitation=$row['compiledlimitation'];
			$acl_plugins=$row['acl_plugins'];
			$append=$row['append'];
			$bannertype=$row['bannertype'];
			$alt_contenttype=$row['alt_contenttype'];
			$comments=$row['comments'];
			$updated=$row['updated'];
			$acls_updated=$row['acls_updated'];
			$parameters=$row['parameters'];
			$an_banner_id=$row['an_banner_id'];
			$ext_bannertype=$row['ext_bannertype'];
			 $master_banner=$row['master_banner'];
			$adminstatus=$row['adminstatus'];	
			$bannerdata=array('campaignid'=>$rowid,'contenttype'=>$contenttype,'pluginversion'=>$pluginversion,'storagetype'=>$storagetype,'filename'=>$filename,'imageurl'=>$imageurl,'htmltemplate'=>$htmltemplate,'htmlcache'=>$htmlcache,'width'=>$width,'height'=>$height,'weight'=>$weight,'seq'=>$seq,'url'=>$url,'alt'=>$alt,'statustext'=>$statustext,'bannertext'=>$bannertext,'description'=>$description,'adserver'=>$adserver,'block'=>$block,'capping'=>$capping,'session_capping'=>$session_capping,'compiledlimitation'=>$compiledlimitation,'acl_plugins'=>$acl_plugins,'append'=>$append,'bannertype'=>$bannertype,'alt_contenttype'=>$alt_contenttype,'comments'=>$comments,'updated'=>$updated,'acls_updated'=>$acls_updated,'parameters'=>$parameters,'an_banner_id'=>$an_banner_id,'ext_bannertype'=>$ext_bannertype,'master_banner'=>$master_banner,'adminstatus'=>$adminstatus);
			$this->dbproduct->insert_bannerdata($bannerdata);

			$new_bannerid=mysql_insert_id();
			
			$where1=array("ad_id"=>$bannerid);
			$ad_query=$this->db->getwhere('ox_ad_zone_assoc',$where1);
			if($ad_query->num_rows()>0)
			{
						foreach($ad_query->result() as $adquery)
						{
							$zone_id=$adquery->zone_id;
							$ad_id=$new_bannerid;
							$priority=$adquery->priority;
							$link_type=$adquery->link_type;
							$priority_factor=$adquery->priority_factor;
							$to_be_delivered=$adquery->to_be_delivered;
							
							$addata=array('zone_id'=>$zone_id,'ad_id'=>$ad_id,'priority'=>$priority,'link_type'=>$link_type,'priority_factor'=>$priority_factor,'to_be_delivered'=>$to_be_delivered);
							
							$this->dbproduct->insert_linkdata($addata);
						}	
			}


			
			$query1=mysql_query("select * from ox_banners where master_banner='-2' and campaignid='$rowid' and bannerid='$new_bannerid'");
			if(mysql_num_rows($query1)>0)
			{
			$query2=mysql_fetch_array($query1);			
			$masterbannerid=$query2['bannerid'];
			$query3=mysql_query("select * from ox_banners where campaignid='$id' and master_banner='$bannerid' and (master_banner NOT IN(-1,-2,-3)) ORDER BY bannerid ASC");
			while($row1=mysql_fetch_array($query3))
			{
			$bannerid=$row1['bannerid']; 
			$campaignid=$rowid;
			$contenttype=$row1['contenttype'];
			$pluginversion=$row1['pluginversion'];
			$storagetype=$row1['storagetype'];
			$filename=$row1['filename'];
			$imageurl=$row1['imageurl'];
			$htmltemplate=$row1['htmltemplate'];
			$htmlcache=$row1['htmlcache'];
			$width=$row1['width'];
			$height=$row1['height'];
			$weight=$row1['weight'];
			$seq=$row1['seq'];
			$url=$row1['url'];
			$alt=$row1['alt'];
			$statustext=$row1['statustext'];
			$bannertext=$row1['bannertext'];
			$description=$row1['description'];
			$adserver=$row1['adserver'];
			$block=$row1['block'];
			$capping=$row1['capping'];
			$session_capping=$row1['session_capping'];
			$compiledlimitation=$row1['compiledlimitation'];
			$acl_plugins=$row1['acl_plugins'];
			$append=$row1['append'];
			$bannertype=$row1['bannertype'];
			$alt_contenttype=$row1['alt_contenttype'];
			$comments=$row1['comments'];
			$updated=$row1['updated'];
			$acls_updated=$row1['acls_updated'];
			$parameters=$row1['parameters'];
			$an_banner_id=$row1['an_banner_id'];
			$ext_bannertype=$row1['ext_bannertype'];
			 $master_banner=$row1['master_banner'];
			$adminstatus=$row1['adminstatus'];	
			$bannerdata=array('campaignid'=>$rowid,'contenttype'=>$contenttype,'pluginversion'=>$pluginversion,'storagetype'=>$storagetype,'filename'=>$filename,'imageurl'=>$imageurl,'htmltemplate'=>$htmltemplate,'htmlcache'=>$htmlcache,'width'=>$width,'height'=>$height,'weight'=>$weight,'seq'=>$seq,'url'=>$url,'alt'=>$alt,'statustext'=>$statustext,'bannertext'=>$bannertext,'description'=>$description,'adserver'=>$adserver,'block'=>$block,'capping'=>$capping,'session_capping'=>$session_capping,'compiledlimitation'=>$compiledlimitation,'acl_plugins'=>$acl_plugins,'append'=>$append,'bannertype'=>$bannertype,'alt_contenttype'=>$alt_contenttype,'comments'=>$comments,'updated'=>$updated,'acls_updated'=>$acls_updated,'parameters'=>$parameters,'an_banner_id'=>$an_banner_id,'ext_bannertype'=>$ext_bannertype,'master_banner'=>$masterbannerid,'adminstatus'=>$adminstatus);
			$this->dbproduct->insert_bannerdata($bannerdata);
			
			$new_bannerid=mysql_insert_id();
			$where2=array("ad_id"=>$bannerid);
			$ad_query1=$this->db->getwhere('ox_ad_zone_assoc',$where2);
			if($ad_query1->num_rows()>0)
			{
						foreach($ad_query1->result() as $adquery)
						{
							$zone_id=$adquery->zone_id;
							$ad_id=$new_bannerid;
							$priority=$adquery->priority;
							$link_type=$adquery->link_type;
							$priority_factor=$adquery->priority_factor;
							$to_be_delivered=$adquery->to_be_delivered;
							
							$addata=array('zone_id'=>$zone_id,'ad_id'=>$ad_id,'priority'=>$priority,'link_type'=>$link_type,'priority_factor'=>$priority_factor,'to_be_delivered'=>$to_be_delivered);
							
							$this->dbproduct->insert_linkdata($addata);
						}	
			}

			}
			}
					
					
		}
	}


redirect("advertiser/campaign");
	
	


}
/*Banner type */
	function bannertype()
	{
	$typeid=$this->input->post("type_id");
	if($typeid=="web")
	{
	$this->load->view('advertiser/campaigns/web');
	}
	if($typeid=="sql")
	{
	$this->load->view('advertiser/campaigns/sql');
	}
	if($typeid=="url")
	{
	$this->load->view('advertiser/campaigns/url');
	}
	if($typeid=="html")
	{
	$this->load->view('advertiser/campaigns/generichtml');
	}
	if($typeid=="txt")
	{
	$this->load->view('advertiser/campaigns/textbanner');
	}
	}

			

/*Add banner */
	function addbanner($campaignid)
	{

	$lists=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$campaignid));
	$data['title']="Adserver-Add New Banner";
	$data['lists']=$lists;
	$this->load->view('advertiser/campaigns/addbanner',$data);
	}
	function addbanners($campaignid)
	{
	$lists=$this->dbproduct->retrieve_campaigndata(array("campaignid"=>$campaignid));
	$data['lists']=$lists;
	$this->load->view('advertiser/campaigns/addbanner',$data);
	}
	function addbannerprocess($campaignid)
	{
	
	$bannertext=$this->input->post("bannertext");
	$type_banner=$this->input->post("type_banner");
	$des=$this->input->post("banner");
	$url=$this->input->post("url");
	$image=$this->input->post("image");


	$width=$this->input->post("width");
	$height=$this->input->post("height");
	if($type_banner=="web" ||$type_banner=="sql")
	{
			$config['upload_path'] = './org_images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']='0';
			$config['max_width']='0';
			$config['max_height']='0';

			$this->upload->initialize($config);
	
	if (!$this->upload->do_upload('image'))
	{
	$data['error']="Upload Error: ".$this->upload->display_errors();
	$this->load->view('advertiser/campaigns/addbanner',$data);
	} 
	else
	{
			$image_data = $this->upload->data();
			$upload_img_name = $image_data['file_name'];
			$upload_image_path= $image_data['full_path'];
			$upload_size=$image_data['image_width'];
			$upload_height=$image_data['image_height'];
			$up_arr["filename"]=$upload_img_name;
			$up_arr["image_width"]=$upload_size;
			$up_arr["image_height"]=$upload_height;
			if($upload_size > 10)
			{
			$image_name=$upload_img_name;
			$image1= explode('.',$image_name);
			$image2= md5($image1[0]).".".$image1[1];
		 	$config1['image_library'] = 'gd2';
			$config1['source_image'] = $upload_image_path;
			if($type_banner=="web")
			{
			$config1['new_image'] = './ads/www/images/'.$image2;
			}
			if($type_banner=="sql")
			{

			}
			$config1['maintain_ratio'] = FALSE;
			$config1['width'] = $width;
			$config1['height'] = $height ;
			$this->image_lib->initialize($config1);
			$this->image_lib->resize();
			$this->image_lib->clear();
			}
		else
			{
			echo "error";
			}
			$image_name=$upload_img_name;
			$image1= explode('.',$image_name);
			$image2= md5($image1[0]).".".$image1[1];
			$image3=$image1[1];
			if($type_banner=="web")
			{
			$ins=array("filename"=>$image2,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner);
	$this->dbproduct->insert_bannerdata($ins);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	if($type_banner=="sql")
	{
	$image=file_get_contents($_FILES["image"]["tmp_name"]);
	$ins=array("filename"=>$image_name,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner,"contenttype"=>"jpeg");
	$this->dbproduct->insert_bannerdata($ins);
	$date=date("Y-m-d H:i:s");
	$insert=array("filename"=>$image_name,"contents"=>$image,"t_stamp"=>$date);
	$this->dbproduct->insert_sqlbannerdata($insert);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	}
	}
	if($type_banner=="url")
	{
	$ins=array("imageurl"=>$image,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner);
	$this->dbproduct->insert_bannerdata($ins);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	if($type_banner=="html")
	{
	$ins=array("url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner,"contenttype"=>"","htmltemplate"=>$bannertext,"htmlcache"=>$bannertext);
	$this->dbproduct->insert_bannerdata($ins);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	if($type_banner=="txt")
	{
	$ins=array("url"=>$url,"description"=>$des,"campaignid"=>$campaignid,"contenttype"=>$type_banner,"storagetype"=>$type_banner,"bannertext"=>$bannertext);
	$this->dbproduct->insert_bannerdata($ins);
	redirect("advertiser/viewbanner/".$campaignid);
	
	}
	
		
}
/*banner */
	function banner()
	{
	$list=$this->dbproduct->retrieve_bannerdata();
	$data['list']=$list;
	$this->load->view('advertiser/campaigns/banner',$data);
	}
/*view banner */
	function viewbanner($campaignid)
	{
	$list=$this->dbproduct->retrieve_bannerdata(array("campaignid"=>$campaignid));
	$data['title']="Adserver-View Banners";
	$data['display']=$list;
	$this->load->view('advertiser/campaigns/viewbanner',$data);
	}
/* update banner */
function updatebanner($bannerid)
	{
	$lists=$this->dbproduct->retrieve_bannerdata(array("bannerid"=>$bannerid));
	$data['title']="Adserver-Update Banners";
	$data['lists']=$lists;
	$this->load->view('advertiser/campaigns/updatebanner',$data);
	}
	function updatebannerprocess($bannerid)
	{
	$cam_id=mysql_query("select * from ox_banners where bannerid='$bannerid'");
	$campaign_id=mysql_fetch_array($cam_id);
	$campaignid=$campaign_id['campaignid'];
	$bannerimage=$campaign_id['filename'];
	$bannertext=$this->input->post("bannertext");
	$type_banner=$this->input->post('storagetype');
	$des=$this->input->post("banner");
	$url=$this->input->post("url");
	$image=$this->input->post("image");
	$status=$this->input->post("status");
	$width=$this->input->post("width");
	$height=$this->input->post("height");
	$up_arr=array("description"=>$des,"url"=>$url);
	if($type_banner=="web" ||$type_banner=="sql")
	{
		$config['upload_path'] = './org_images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']='0';
			$config['max_width']='0';
			$config['max_height']='0';

			$this->upload->initialize($config);
	
	if (!$this->upload->do_upload('image'))
	{
    $data['error']="Upload Error: ".$this->upload->display_errors();
	$this->load->view('advertiser/addbanner',$data);
	} 
	else
	{
		
			$image_data = $this->upload->data();
			$upload_img_name = $image_data['file_name'];
			$upload_image_path= $image_data['full_path'];
			$upload_size=$image_data['image_width'];
			$upload_height=$image_data['image_height'];
			$up_arr["filename"]=$upload_img_name;
			$up_arr["image_width"]=$upload_size;
			$up_arr["image_height"]=$upload_height;
		
			if($upload_size > 10)
			{
			$image_name=$upload_img_name;
			$image1= explode('.',$image_name);
			$image2= md5($image1[0]).".".$image1[1];
		 	$config1['image_library'] = 'gd2';
			$config1['source_image'] = $upload_image_path;
			$config1['new_image'] = './ads/www/images/'.$image2;
			if($type_banner=="web")
			{
			$config1['new_image'] = './ads/www/images/'.$image2;
			}
			if($type_banner=="sql")
			{

			}
			$config1['width'] = $width;
			$config1['height'] = $height ;
			$this->image_lib->initialize($config1);
			$this->image_lib->resize();
			$this->image_lib->clear();
			}
		else
			{
			echo "error";
			}
			
			$image_name=$upload_img_name;
			$image1= explode('.',$image_name);
			$image2= md5($image1[0]).".".$image1[1];
			$image3=$image1[1];
			if($type_banner=="web")
			{
	$up=array("filename"=>$image2,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"status"=>$status,"storagetype"=>$type_banner);
	$where=array("bannerid"=>$bannerid);   
	$this->dbproduct->update_bannerdata($up,$where);
	}
	if($type_banner=="sql")
	{
	$image=file_get_contents($_FILES["image"]["tmp_name"]);
	$ins=array("filename"=>$image_name,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner,"contenttype"=>"jpeg");
	$where=array("bannerid"=>$bannerid); 
	$this->dbproduct->update_bannerdata($ins,$where);
	$date=date("Y-m-d H:i:s");
	$insert=array("filename"=>$image_name,"contents"=>$image,"t_stamp"=>$date);
	$this->dbproduct->insert_sqlbannerdata($insert);
	}
	redirect("advertiser/viewbanner/$campaignid");
	}
	}
	if($type_banner=="url")
	{
	$up=array("imageurl"=>$image,"url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner);
	$where=array("bannerid"=>$bannerid); 
	$this->dbproduct->update_bannerdata($up,$where);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	if($type_banner=="html")
	{
	$up=array("url"=>$url,"description"=>$des,"width"=>$width,"height"=>$height,"campaignid"=>$campaignid,"storagetype"=>$type_banner,"htmltemplate"=>$bannertext,"htmlcache"=>$bannertext,"contenttype"=>"");
    $where=array("bannerid"=>$bannerid); 
	$this->dbproduct->update_bannerdata($up,$where);
	redirect("advertiser/viewbanner/".$campaignid);
	}
	if($type_banner=="txt")
	{
	$up=array("url"=>$url,"description"=>$des,"campaignid"=>$campaignid,"contenttype"=>$type_banner,"storagetype"=>$type_banner,"bannertext"=>$bannertext);
	$where=array("bannerid"=>$bannerid); 
	$this->dbproduct->update_bannerdata($up,$where);
	redirect("advertiser/viewbanner/".$campaignid);
	
	}
	}
	
/*delete banners */
	function deletebanner()
	{
	$camid=$this->session->userdata('campaign');
	$s=$this->input->post("deletevalue");
	$t=explode('.',$s);
	if(count($t)>0)
	{
	foreach($t as $u)
	{
	/*ox_uniques */
	mysql_query("delete from ox_data_bkt_unique_m where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_unique_c where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_unique_con where creative_id ='$u'");

/*bucket tables */
	mysql_query("delete from ox_data_bkt_a where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_a_var where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_c where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_m where creative_id ='$u'");

/*Linking tables */
	mysql_query("delete from ox_ad_zone_assoc where ad_id ='$u'");

/*Bucket country table */
	mysql_query("delete from ox_data_bkt_country_c where creative_id ='$u'");
	mysql_query("delete from ox_data_bkt_country_m where creative_id ='$u'");

/*Country table */
	mysql_query("delete from ox_stats_country where creative_id ='$u'");

/*Intermediate table */
	mysql_query("delete from  ox_data_intermediate_ad where ad_id ='$u'");

/* Ad hourly table */
	mysql_query("delete from  ox_data_summary_ad_hourly where ad_id ='$u'");

/*oxm_report */
	mysql_query("delete from oxm_report where bannerid ='$u'");
	
	$this->dbproduct->delete_bannerdata(array("bannerid"=>$u));
	$query=mysql_query("select * from ox_ad_zone_assoc where ad_id='$u'");
	while($query1=mysql_fetch_array($query))
	{
	mysql_query("delete from ox_ad_zone_assoc where ad_id='$u'");
	}
	}
	redirect("advertiser/viewbanner/".$camid);	
	}
	else
	{
	$data['error']="There are currently no Banners defined for this advertiser";
	$this->load->view('advertiser/campaigns/campaign',$data);
	}
	}
	
/*Duplicate banners */
function duplicatebanner($bannerid)
{
			 $dup=$this->dbproduct->retrieve_bannerdata(array("bannerid"=>$bannerid));
			 $data['dup']=$dup;
			 $this->load->view('advertiser/campaigns/duplicatebanner',$data);	
 }
function dupbanner()
{

 	$camid=$this->input->post("camid");
	$id=$this->session->userdata('bannerid');
	$copybanner=$this->dbproduct->retrieve_bannerdata(array("bannerid"=>$id));
	foreach($copybanner as $cpc)
	{
	$width=$cpc->width;
	$height=$cpc->height;
	$url=$cpc->url;
	$image=$cpc->filename;
	}
	/*Insert the Banners */
	$camname=$this->input->post("newcam");
	$ins=array("description"=>$camname,"campaignid"=>$camid,"width"=>$width,"height"=>$height,"url"=>$url,"filename"=>$image);
	$this->dbproduct->insert_bannerdata($ins);
	redirect("advertiser/viewbanner/$camid");
	}

/* Linked Banners */
function linkedzone($bannerid)
{
		$client_id	=$this->session->userdata('clientid');
		$camm_id	=mysql_query("select * from ox_banners where bannerid='$bannerid'");
		$cam_id		=mysql_fetch_array($camm_id);
		$campaignid	=$cam_id['campaignid'];

		 /*Fetch the value in oxm_budget */
		 $budget_value=mysql_query("select * from oxm_budget where clientid='$client_id'");

		 if(mysql_num_rows($budget_value) >0 )
		 {
		 $budget_val=mysql_fetch_array($budget_value);
		 $bud_value= $budget_val['budget'];
		 $this->session->set_userdata('banner',$bannerid);
		 $banner_query=mysql_query("select * from ox_banners where bannerid='$bannerid'");
		 $query=mysql_fetch_array($banner_query);
		 $storagetype=$query['storagetype'];

		 if($storagetype =="web")
		 {
		  $qry ="select c.clientid,b.bannerid,b.campaignid,b.width as bannerwidth,b.height as bannerheight ,z.revenue_type,z.width as zonewidth ,z.height as zoneheight ,z.zonename,z.zoneid,c.campaignid,c.revenue_type,s.clientid,c.campaignname,s.clientname,b.description from ox_campaigns c join ox_banners b join ox_zones z join ox_clients s on z.height=b.height and z.width=b.width where c.campaignid=b.campaignid and c.revenue_type=z.revenue_type AND c.clientid=s.clientid AND z.master_zone=-2 AND b.master_banner=-2 AND bannerid=$bannerid";
		
  			//$qry ="select c.revenue_type,z.revenue_type,b.bannerid,z.zoneid,b.width,b.height,z.height,z.width from ox_banners b join ox_campaigns c join  ox_zones z on b.width=z.width and b.height=z.height and c.revenue_type=z.revenue_type where b.bannerid='$bannerid'";
			
		 $ban			=mysql_query($qry);
		 $sql           ="select * from ox_campaigns where campaignid='$campaignid'";
		 $ret_id		=mysql_query($sql);
		 $revenue_id	=mysql_fetch_array($ret_id);
		 $revenuee_id	=$revenue_id['revenue_type'];
		 if(mysql_num_rows($ban) > 0)
         {
          while($ban1=mysql_fetch_array($ban))
		   {
		    //print_r($ban1);
		    $rev_id		=$ban1['revenue_type'];
		    $zoneid		=$ban1['zoneid'];
		    $bannerid	=$ban1['bannerid'];
		    $ban3		=$ban1['bannerwidth'];
		    $ban4		=$ban1['bannerheight'];
			//echo "z-".$rev_id."=>".$revenuee_id."<br/>";
			if($rev_id == $revenuee_id)
			{
			  //$where=array("width"=>$ban3,"height"=>$ban4,"revenue_type"=>$rev_id);
			  $qry ="SELECT * FROM ox_zones WHERE `width` = '".$ban3."' AND `height` = '".$ban4."' AND `revenue_type` = '".$rev_id."' AND (`master_zone` = '-1' OR `master_zone` = '-2' OR `master_zone` = '-3')";
			}
			else
			{
			  //$where=array("width"=>$ban3,"height"=>$ban4,"revenue_type"=>$revenuee_id);
			  $qry ="SELECT * FROM (`ox_zones`) WHERE `width` = '".$ban3."' AND `height` = '".$ban4."' AND `revenue_type` = '".$revenuee_id."' AND (`master_zone` = '-1' OR `master_zone` = '-2' OR `master_zone` = '-3')";
			}

		  }
			$zonevalue=$this->dbproduct->retrieve_zonedata1($qry);
			//echo $this->db->last_query();						   
			$data['title']="Adserver-Linking Banners with zones";
			$data['zonevalue']=$zonevalue;
			$data['html']="";
			$this->load->view('advertiser/campaigns/viewzones',$data);
		}
		else
	    {
		  $data['error']="There are currently no Banners match with this zones";
		  //redirect('advertiser/campaigns/viewzones/'.$bannerid);
		  $this->load->view('advertiser/campaigns/viewzones',$data);
		}
	}
	elseif($storagetype=="txt")
	{
	  $delivery=3;
	  $sql ="select c.revenue_type,z.revenue_type,b.bannerid,z.zoneid,z.delivery from ox_banners b join ox_campaigns c join ox_zones z on c.revenue_type=z.revenue_type where b.bannerid='$bannerid' and z.delivery='$delivery'"; // AND (z.master_zone =-1 OR z.master_zone -2 OR z.master_zone=-3)";
	 	 $ban=mysql_query($sql);
		 $ret_id=mysql_query("select * from ox_campaigns where campaignid='$campaignid'");
		 $revenue_id=mysql_fetch_array($ret_id);
		 $revenuee_id= $revenue_id['revenue_type'];

		 if(mysql_num_rows($ban) > 0)
		 {
			while($ban1=mysql_fetch_array($ban))
			{
			 $deli=$ban1['delivery'];
			 $rev_id=$ban1['revenue_type'].'<br>';
			 $zoneid= $ban1['zoneid'].'<br>';
		     $bannerid=$ban1['bannerid'].'<br>';
			 
			 if($rev_id==$revenuee_id && $deli==$delivery)
			 {
			 	$qry ="SELECT * FROM ox_zones WHERE `revenue_type` = '".$rev_id."' AND delivery ='".$deli."' AND (`master_zone` = '-1' OR `master_zone` = '-2' OR `master_zone` = '-3')";
			   //$where=array("revenue_type"=>$rev_id,"delivery"=>$delivery);
			 }
			 else
			 {
			 	$qry ="SELECT * FROM ox_zones WHERE `revenue_type` = '".$revenuee_id."' AND delivery ='".$delivery."' AND (`master_zone` = '-1' OR `master_zone` = '-2' OR `master_zone` = '-3')";
				//$where=array("revenue_type"=>$revenuee_id,"delivery"=>$delivery);
			 }
		 }

		$zonevalue=$this->dbproduct->retrieve_zonedata1($qry);
		//echo $this->db->last_query();
		
		$data['title']="Adserver-Linking Banners with zones";
		$data['zonevalue']=$zonevalue;
		$data['html']="";
		$this->load->view('advertiser/campaigns/viewzones',$data);
	}
	else
	{
	$data['error']="There are currently no Banners match with this zones";
	$this->load->view('advertiser/campaigns/viewzones',$data);
	}

}
elseif($storagetype=="html")
{
	 $ban=mysql_query("select c.revenue_type,z.revenue_type,b.bannerid,z.zoneid,b.width,b.height,z.height,z.width,z.delivery from ox_banners b join ox_campaigns c join  ox_zones z on b.width=z.width and b.height=z.height and c.revenue_type=z.revenue_type where b.bannerid='$bannerid'"); 

	 $ret_id=mysql_query("select * from ox_campaigns where campaignid='$campaignid'");
	 $revenue_id=mysql_fetch_array($ret_id);
	 $revenuee_id= $revenue_id['revenue_type'];

	 if(mysql_num_rows($ban) > 0)
	 {
	  while($ban1=mysql_fetch_array($ban))
	  {
		$rev_id=$ban1['revenue_type'].'<br>';
	    $zoneid= $ban1['zoneid'].'<br>';
		$bannerid=$ban1['bannerid'].'<br>';
		$ban3=$ban1['width'];
	    $delivery=$ban1['delivery'];
		$ban4=$ban1['height'];
							
		if($delivery!="4")
		{		 
			if($rev_id==$revenuee_id && $delivery!="4")
			{
			$where=array("width"=>$ban3,"height"=>$ban4,"revenue_type"=>$rev_id);
			}
			else
			{
			$where=array("width"=>$ban3,"height"=>$ban4,"revenue_type"=>$revenuee_id);
			}
	    }
	    else
	    {
		}
	  }

	  $zonevalue=$this->dbproduct->retrieve_zonedata($where);
	  $data['title']		="Adserver-Linking Banners with zones";
	  $data['zonevalue']	=$zonevalue;
	  $data['html']			="html";
	  
	  $this->load->view('advertiser/campaigns/viewzones',$data);
	}
    else
	{
	$data['error']="There are currently no Banners match with this zones";
	$this->load->view('advertiser/campaigns/viewzones',$data);
	}
 }
}
}	

function linkvalues()
	{
	$bannerid= $this->session->userdata('bannerid');
	$campaign_query=mysql_query("select * from ox_banners where bannerid='$bannerid'");
	$cam_qry=mysql_fetch_array($campaign_query);
	 $cam_id=$cam_qry['campaignid'];
	$bannertext=$cam_qry['description'];
	$r2=$this->input->post("value");
	$r3=explode(",",rtrim($r2,","));

	$empty =mysql_query("delete from ox_ad_zone_assoc where  ad_id IN (select b.bannerid from ox_banners b  where (b.master_banner='$bannerid' OR b.bannerid='$bannerid'))");

	foreach($r3 as $zone_spl)
	{
			
			$zone_qry		="select z.revenue_type from ox_zones z where zoneid='$zone_spl'";
			$zone_type		=mysql_query($zone_qry);
			$zone_idd		=mysql_fetch_array($zone_type);
			$zone_type		=$zone_idd['revenue_type'];
			
			$chk_ban_qry	=mysql_query("select master_banner from ox_banners where bannerid='$bannerid'");
			$chk_ban		=mysql_fetch_array($chk_ban_qry);
			$master_ban_id =$chk_ban['master_banner'];

			if($master_ban_id ==-2)
			{
			$ins=array("ad_id"=>$bannerid,"zone_id"=>$zone_spl);
			$this->dbproduct->insert_linkdata($ins);		   				

			$qry            ="select * from ox_banners where  master_banner='$bannerid'"; 
			$banners	=mysql_query($qry);
			$camquery=mysql_query("select * from ox_banners b join ox_campaigns c on b.campaignid=c.campaignid  where b.bannerid='$bannerid'");
			$cam_query=mysql_fetch_array($camquery);
			$cam_type=$cam_query['revenue_type'];
		
			while($banner_rs=mysql_fetch_array($banners))
			{
				$banner_name	=$banner_rs['description'];
				$banner_height	=$banner_rs['height'];
				$banner_width 	=$banner_rs['width'];
				$child_banner_id  =$banner_rs['bannerid'];
				

				$zone_qry ="select * from ox_zones where master_zone='$zone_spl'";
				$zones_id 	=mysql_query($zone_qry);
			
					while($zonee_id	=mysql_fetch_array($zones_id))
					{
						$zone_width	=$zonee_id['width'];
						$zone_height	=$zonee_id['height'];
						$zon_id		=$zonee_id['zoneid'];
						$master_zone  =$zonee_id['master_zone'];
						$zone_type=$zonee_id['revenue_type'];
						

							if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
							{

								$check ="select * from ox_ad_zone_assoc where zone_id='$zon_id' and ad_id='$child_banner_id '";
								$count_camid	=mysql_query($check);
					
									if(mysql_num_rows($count_camid)>0)
									{
																	 
 										$ins ="insert into ox_ad_zone_assoc(zone_id,ad_id) values ('$zon_id','$child_banner_id')";
										 mysql_query($ins);
										
									}
									else
									{
									  
										 $ins ="insert into ox_ad_zone_assoc(zone_id,ad_id) values ('$zon_id','$child_banner_id')";
										 mysql_query($ins);
										
									}
							}
					

					}
			}

			}
			else
			{

			$qry            ="select * from ox_banners where  master_banner='$bannerid' or bannerid='$bannerid'"; 
			$banners	=mysql_query($qry);
			$camquery=mysql_query("select * from ox_banners b join ox_campaigns c on b.campaignid=c.campaignid  where b.bannerid='$bannerid'");
			$cam_query=mysql_fetch_array($camquery);
			$cam_type=$cam_query['revenue_type'];
			$banner_rs=mysql_fetch_array($banners);
				$banner_name	=$banner_rs['description'];
				$banner_height	=$banner_rs['height'];
				$banner_width 	=$banner_rs['width'];
				$child_banner_id  =$banner_rs['bannerid'];

				$zone_qry ="select * from ox_zones where (master_zone='$zone_spl' OR zoneid='$zone_spl')";


				$zones_id 	=mysql_query($zone_qry);
			
					while($zonee_id	=mysql_fetch_array($zones_id))
					{
						$zone_width	=$zonee_id['width'];
						$zone_height	=$zonee_id['height'];
						$zon_id		=$zonee_id['zoneid'];
						$master_zone  =$zonee_id['master_zone'];
						$zone_type=$zonee_id['revenue_type'];
						

							if($cam_type==$zone_type && $banner_width==$zone_width && $banner_height==$zone_height)
							{

								$check ="select * from ox_ad_zone_assoc where zone_id='$zon_id' and ad_id='$child_banner_id '";
								$count_camid	=mysql_query($check);
					
									if(mysql_num_rows($count_camid)>0)
									{
									    $ins ="insert into ox_ad_zone_assoc(zone_id,ad_id) values ('$zon_id','$child_banner_id')";
										 mysql_query($ins);
									}
									else
									{
									  
										 $ins ="insert into ox_ad_zone_assoc(zone_id,ad_id) values ('$zon_id','$child_banner_id')";
										 mysql_query($ins);
										
									}
							}
					

					}
			
			}
	
	
	}
	

//echo "SELECT * FROM (`ox_banners`) WHERE `campaignid` ='".$cam_id."' AND (master_banner=-1 OR master_banner =-2 OR master_banner=-3) ORDER BY `bannerid` asc";
		$qry ="SELECT * FROM (`ox_banners`) WHERE `campaignid` ='".$cam_id."' AND (master_banner=-1 OR master_banner =-2 OR master_banner=-3) ORDER BY `bannerid` asc";
		$list=$this->dbproduct->retrieve_bannerdata1($qry);	
		$data['display']=$list;
		$data['campaignid']=$cam_id;
		$dbvalues=mysql_query("select * from ox_ad_zone_assoc where ad_id='$bannerid'" );
		if(mysql_num_rows($dbvalues)>0)
		{
		$data['banner_link']="This ".$bannertext ." Linked with Zone";
		}
		else
		{
		$data['banner_unlink']="This ".$bannertext ." unLinked From Zone";
		}

	$this->load->view('advertiser/campaigns/mob_viewbanner',$data);
	}
	
	
/* check & display banners */
function selectbanner($status)
		{
		$id=$this->session->userdata('campaignid');
		$value=$this->dbproduct->retrieve_bannerdata(array('status'=>$status,"campaignid"=>$id));
         	$a=count($value);
		if($a>0)
		{
		$data['value']=$value;
		$this->load->view('advertiser/campaigns/viewbanner',$data);
		} 
		else
		{
		$data['value']=$value;
		$this->load->view('advertiser/campaigns/viewbanner',$data);
		}
		}

	


/* publisher */

	function addwebsite()
	{
	$this->load->view('advertiser/websites/addwebsite');
	}
	function addwebsiteprocess()
	{
	if ($this->form_validation->run('websiteform') == FALSE)
	{
	$this->load->view('advertiser/websites/addwebsite');
	}
	else
	{
	$type="TRAFFICKER";
	$agencyid=1;
	$url=$this->input->post("url");	
	$contact=$this->input->post("contact");
	$email=$this->input->post("email");
	$website="http://".$this->input->post("url");
	$ins=array("account_type"=>$type,"account_name"=>$url);
	$this->dbproduct->insert_websitedata($ins);
	$insert=array("agencyid"=>$agencyid,"name"=>$url,"contact"=>$contact,"email"=>$email,"website"=>$website);
	$this->dbproduct->insert_webdata($insert);
	$this->session->set_userdata('name',$url);
	redirect("advertiser/website");
	}
	}
/* view websites/publishers */
	function website($start=0)
	{
			$list=$this->dbproduct->retrieve_websitedata();
			$value=$this->dbproduct->website_getdata($start,5);
			//print_r($value);
			$config['base_url'] = base_url().'index.php/advertiser/website';
		    $config['total_rows'] = count($list);
			$config['per_page']='5';//$this->session->userdata('page_limit');
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['list']=$value;
			$this->load->view('advertiser/websites/website',$data);
			}
/* update websites */
	function updatewebsite($affiliateid)
	{
	$lists=$this->dbproduct->retrieve_websitedata(array("affiliateid"=>$affiliateid));
	$data['lists']=$lists;
	$this->load->view('advertiser/websites//updatewebsite',$data);
	}
	function updatewebsiteprocess($affiliateid)
	{
	$url=$this->input->post("name");	
	$contact=$this->input->post("contact");
	$email=$this->input->post("email");
	$website="http://".$this->input->post("name");
	$up=array("name"=>$url,"contact"=>$contact,"email"=>$email,"website"=>$website);
	$where=array("affiliateid"=>$affiliateid);
	$this->dbproduct->update_websitedata($up,$where);
	redirect("advertiser/website");
	}
/* delete websites */
function deletewebsite($affiliateid=false)
	{
	if($affiliateid!=false)
		{
		$this->dbproduct->delete_websitedata(array("affiliateid"=>$affiliateid));
		redirect("advertiser/website");
		}
	else
		{
		redirect("advertiser/website");
		}
	}


/*Add zones */
	function addzone($affiliateid)
	{
	$lists=$this->dbproduct->retrieve_websitedata(array("affiliateid"=>$affiliateid));
	$data['lists']=$lists;
	$this->load->view('advertiser/websites/addzone',$data);
	}
	function addzoneprocess($affiliateid)
	{
	if ($this->form_validation->run('zoneform') == FALSE)
	{
	$this->load->view('advertiser/websites/addzone');
	}
	else
	{
	$name=$this->input->post("name");
	$des=$this->input->post("des");
	$height=$this->input->post("size");
	$width=$this->input->post("size1");
	$ins=array("zonename"=>$name,"description"=>$des,"width"=>$width,"height"=>$height,"affiliateid"=>$affiliateid);
	$this->dbproduct->insert_zonedata($ins);
	redirect("advertiser/website");
	}
	}
/* view zone*/
	function viewzone($affiliateid,$start=0)
	{
			$list=$this->dbproduct->retrieve_zonedata(array("affiliateid"=>$affiliateid));
			//$value=$this->dbproduct->retrieve_zonepagedata($start,5,array("affiliateid"=>$affiliateid));
			//print_r($value);
			$config['base_url'] = base_url().'index.php/advertiser/website';
		    $config['total_rows'] = count($list);
			$config['per_page']='5';//$this->session->userdata('page_limit');
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			//$config['full_tag_open'] = '<p class="pagination">';
			//$config['first_tag_close'] = '</p>';
			$this->pagination->initialize($config);
			$data['list']=$list;
	$this->load->view('advertiser/websites/viewzone',$data);
	}
/* update zone */
	function updatezone($zoneid)
	{
	$lists=$this->dbproduct->retrieve_zonedata(array("zoneid"=>$zoneid));
	$data['lists']=$lists;
	$this->load->view('advertiser/websites/updatezone',$data);
	}
	function updatezoneprocess($zoneid)
	{
	$name=$this->input->post("name");
	$des=$this->input->post("des");
	$height=$this->input->post("height");
	$width=$this->input->post("width");
	$up=array("zonename"=>$name,"description"=>$des,"width"=>$width,"height"=>$height);
	$where=array("zoneid"=>$zoneid);
	$this->dbproduct->update_zonedata($up,$where);
	redirect("advertiser/website");
	}
/*delete zone */
	function deletezone($zoneid=false)
	{
	if($zoneid!=false)
		{
		$this->dbproduct->delete_zonedata(array("zoneid"=>$zoneid));
		redirect("advertiser/website");
		}
	else
		{
		redirect("advertiser/website");
		}
	}
	



	function checkbox()
	{
	 $bannername=$this->session->userdata('banner');
	$bantab=mysql_query("select * from ox_linkedzones where bannername='$bannername'");
	if(mysql_num_rows($bantab) > 0 )
	{
	$zones=$this->input->post('check');
	$list=implode(',',$zones);
	$up=array("zonename"=>$list);
	$where=array("bannername"=>$bannername);
	$this->dbproduct->update_selectzonedata($up,$where);
	redirect("advertiser/campaign");
	}
	else
	{
	$zones=$this->input->post('check');
	$list=implode(',',$zones);
	$ins=array("zonename"=>$list,"bannername"=>$bannername);
	$this->dbproduct->insert_selectzonedata($ins);
	redirect("advertiser/campaign");
	}
	}
	function linkedzones($bannerid)
	{
	$btab=mysql_query("select * from ox_linkedzones where bannername='$bannerid'");
	if(mysql_num_rows($btab) > 0 )
	{
	$data['banner']=$bannerid;	
	$this->load->view('advertiser/campaigns/viewimage',$data);
	}
	else
	{
	$data['error']="Dont match your banner with available zone sizes";
	$this->load->view('advertiser/campaigns/viewimage',$data);
	}
	}
	
/*accounts module */
	function accounts()
	{
	$data['title']="Adserver-Accounts";
	$this->load->view('advertiser/account/viewaccount',$data);
	}
/*Account information */
	function account()
	{
    $accname=$this->session->userdata('account_name');
	$aname=$this->dbproduct->retrieve_acinformationdata(array("username"=>$accname));
	$data['title']="Adserver-Account Information";
	$data['aname']=$aname;
	$this->load->view('advertiser/account/account_info',$data);
	}
	function accountprocess()
	{
	$name=$this->session->userdata('account_name');
	$p=mysql_query("select * from oxm_userdetails where username='$name'");
	$qry1=mysql_fetch_array($p);
	$account_id=$qry1['accountid'];

	$email=$this->input->post("email");
	$address=$this->input->post("address");
	$city=$this->input->post("city");
	$state=$this->input->post("state");
	$country=$this->input->post("country");
	$mobile=$this->input->post("mobile");
	$zip=$this->input->post("zip");
	$type=$this->input->post("type"); //"accounttype"=>$type,
	$taxid=$this->input->post("taxid");
	$paypal=$this->input->post("paypal");
	
	$where=array("accountid"=>$account_id);
	$up=array("email"=>$email,"address"=>$address,"city"=>$city,"state"=>$state,"country"=>$country,"mobileno"=>$mobile,"postcode"=>$zip,"tax"=>$taxid, "paypalid"=>$paypal,"bank_acctype"=>$type);
	$this->dbproduct->update_accountinformationdata($up,$where);
	
	$whereacc=array("default_account_id"=>$account_id);
	$updat=array("email_address"=>$email);
	$this->dbproduct->update_emaildata($updat,$whereacc);
	
	$email_campaign=$this->input->post("email_campaign");
	$email_del=$this->input->post("email_del");
	$report=$this->input->post("report");

	$whereacc=array("account_id"=>$account_id);
	$updat1=array("email"=>$email,"reportdeactivate"=>$email_campaign,"report"=>$email_del,"reportinterval"=>$report);
	$this->dbproduct->update_clidata($updat1,$whereacc);
	
	
	
	$aname=$this->dbproduct->retrieve_acinformationdata(array("username"=>$name));
	$data['aname']=$aname;
	$data['acc']="Your Account Has been updated";
	$this->load->view('advertiser/account/account_info',$data);
	}
/*ADD fund */
	function addfund()
	{
	$data['title']="Adserver-Add Funds";
	$this->load->view('advertiser/account/addfund',$data);
	}
/* Payment Details */
	function payment()
	{
	$this->load->view('advertiser/account/payment');
	}
	
	function paymentprocess()
	{
	$id=$this->session->userdata('clientid');
	$name=$this->session->userdata('account_name');
	if ($this->form_validation->run('paymentform') == FALSE)
	{
	$this->load->view('advertiser/account/payment');
	}
	else
	{
	$p=mysql_query("select name from oxm_paymentdetails where clientid ='$id'");
	if(mysql_num_rows($p) > 0)
	{
	$country=$this->input->post("country");
	$type=$this->input->post("type");
	$busname=$this->input->post("busname");
	$taxid=$this->input->post("taxid");
	$paypallogin=$this->input->post("payment");
	$up=array("name"=>$name,"country"=>$country,"type"=>$type,"businessname"=>$busname,"taxid"=>$taxid,"paypallogin"=>$paypallogin);
	$where=array("clientid"=>$id);
	$this->dbproduct->update_paymentdata($up,$where);
	 $id=$this->session->userdata('clientid');
	$payment_data=$this->dbproduct->retrieve_paymentinformationdata(array("clientid"=>$id));
	$data['payment_data']=$payment_data;
	$data['acc']="Your account has been updated.";
	$this->load->view('advertiser/account/payment',$data);
	}
	else
	{
	$country=$this->input->post("country");
	$type=$this->input->post("type");
	$busname=$this->input->post("busname");
	$taxid=$this->input->post("taxid");
	$paypallogin=$this->input->post("payment");
	$ins1=array("clientid"=>$id,"name"=>$name,"country"=>$country,"type"=>$type,"businessname"=>$busname,"taxid"=>$taxid,"paypallogin"=>$paypallogin);
	$this->dbproduct->insert_paymentdetaildata($ins1);
	$data['acc']="Your account has been updated.";
	$this->load->view('advertiser/account/payment',$data);
	}
	}
	}
	
	/* Account settings*/
	function accountsettings()
	{
		$this->load->view('advertiser/account/accountsettings');
	}
	
	/*Notifiction Settings */
	function settings()
	{
		$data['title']="Adserver-Notification Settings";
		$this->load->view('advertiser/account/settings',$data);
	}
	
	function settingprocess()
	{
	 $name=$this->session->userdata('account_name');
	 $id=$this->session->userdata('clientid');
	 if (0) //$this->form_validation->run('settingform') == FALSE)
	 {
		//$this->load->view('advertiser/account/settings');
	 }
	 else
	 {
		$p=mysql_query("select email from oxm_notification where clientid ='$id'");
		if(mysql_num_rows($p)>0)
		{
			$accbalance=$this->input->post("notify");
			$acc_bal=$this->input->post('accbalance');
			$budbalance=$this->input->post("notify1");
			$bud_bal=$this->input->post('dailyvalue');
			$email=$this->input->post("email");
			$up=array("accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal,"email"=>$email);
			$where=array("clientid"=>$id);
			$this->dbproduct->update_notifydata($up,$where);
			$dbnotify=$this->dbproduct->retrieve_notifydata(array("clientid"=>$id));
			$data['dbnotify']=$dbnotify;
			$data['acc']="Your account has been updated.";
			$this->load->view('advertiser/account/settings',$data);
		}
		else
		{
			$accbalance=$this->input->post("notify");
			$acc_bal=$this->input->post('accbalance');
			$budbalance=$this->input->post("notify1");
			$bud_bal=$this->input->post('dailyvalue');
			$email=$this->input->post("email");
			$ins=array("clientid"=>$id,"accbalance"=>$accbalance.",".$acc_bal,"budbalance"=>$budbalance.",".$bud_bal,"email"=>$email);
			$this->dbproduct->insert_notifydata($ins);
			$data['acc']="Your account has been updated.";
			$this->load->view('advertiser/account/settings',$data);
		}
	}
	}
/*change password */
function change_pass()
{
	$data['title']="Adserver-Change Password";
	$this->load->view('advertiser/account/cpassword',$data);
}
function changepassprocess()
{
	if ($this->form_validation->run('changepasswordform') == FALSE)
	{
	$this->load->view('advertiser/account/cpassword');
	}
	else
		{
		$emailid= $this->session->userdata('account_name');
		 $old=md5($this->input->post("oldpassword"));
		 $old_user=mysql_query("select * from ox_users where username='$emailid'");
		while($old_pass=mysql_fetch_array($old_user))
		{
		$old_password=$old_pass['password'];
		if( $old==$old_password)
		{
			$new=md5($this->input->post("newpassword"));
			$where=array("username"=>$emailid);
			$up=array("password"=>$new);
			$this->dbproduct->update_passdata($up,$where);
			$data['acc']="Your password has been changed.";
			$this->load->view('advertiser/account/cpassword',$data);
			}
		else
			{	
			$data['oldpass']="old password is incorrect";
			$this->load->view('advertiser/account/cpassword',$data);
			}
			}
		}
		
}	
	function fundtransfer()
	{
	$amount=$this->input->post('amount');
	$this->session->set_userdata('amount',$amount);
	redirect("paypal/auto_form");
	}
/*success payment */
	function success()
	{
	 $name=$this->session->userdata('account_name');
	 $fund=$this->session->userdata('amount');
	 $item=$this->session->userdata('item_name');
	 $itemno=$this->session->userdata('item_number');
	 $business=$this->session->userdata('business');
	 $date=date("Y-m-d");
	 $ins=array("username"=>$name,"Amount"=>$fund,"date"=>$date);
	 $this->dbproduct->insert_paypaldata($ins);
	$this->load->view('success');
	}
/* Account Summary */
	function summary()
	{
	$data['title']="Adserver-Account Summary";
	$this->load->view('advertiser/account/summary',$data);
	}
/* Account Summary -orders */
	function orders()
	{
	$name=$this->session->userdata('account_name');
	$z=mysql_query("select * from oxm_paypal where username ='$name'");
	if(mysql_num_rows($z)>0)
	{
	$payorder=$this->dbproduct->retrieve_paypaldata(array("username"=>$name));	
	$data['payorder']=$payorder;
	$this->load->view('advertiser/account/summary',$data);
	}
	else
	{
	$data['payorder']="You have no orders";
	$this->load->view('advertiser/account/summary',$data);
	}	
	}
/*Account summary -payment */
	function payment_sum()
	{
	$id=$this->session->userdata('clientid');
	$name=$this->session->userdata('account_name');
	$z=mysql_query("select * from oxm_paypal where clientid ='$id'");
	if(mysql_num_rows($z)>0)
	{
	$payment=$this->dbproduct->retrieve_paypaldata(array("clientid"=>$id));	
	$data['payment']=$payment;
	$this->load->view('advertiser/account/summary',$data);
	}
	else
	{
	$data['payment']="You have no Payments";
	$this->load->view('advertiser/account/summary',$data);
	}
	}
/*Account summary -Acc balance */
	function acc_balance()
	{
	$clientid=$this->session->userdata('clientid');
	$data['clientid']=$clientid;
	$this->load->view('advertiser/account/summary',$data);
	}

	
	function budget_value_click()
	{
	 $this->session->userdata('clientid');
		$id=$this->input->post('cat_id');
		$bud_chk_value=mysql_query("select b.budget,a.accbalance from oxm_budget b join oxm_accbalance a on a.clientid=b.clientid where campaignid='$id'");
		$bud_chk=mysql_fetch_array($bud_chk_value);
		$bug_value=$bud_chk['budget'];
		$acc_value=$bud_chk['accbalance'];
		if($acc_value >0 && $bug_value >0 )
		{
		$where=array("campaignid"=>$id);
		$up=array("status"=>'0');
		$this->dbproduct->update_campaignsdata($up,$where);
		echo $acc_value;
		}
		else if(($acc_value==0)||($bug_value==0))
		{
		$where=array("campaignid"=>$id);
		$up=array("status"=>'0');
		$this->dbproduct->update_campaignsdata($up,$where);
		echo $acc_value;
		}
		
	}
	
/*Fetch values from ox_zones */	
	function fetch_amount($zoneid)
	{
	 $zoneid;
	 $zone_revenue=mysql_query("select * from ox_zones where zoneid='$zoneid'");
	 $z_revenue=mysql_fetch_array($zone_revenue);
	  echo "Rate/Price:".$z_revenue['revenue'].'<br>';
	$pricemodel= $z_revenue['revenue_type'];
	switch($pricemodel)
	{
	case '1':
	$model_value="CPM";
	break;
	case '2':	
	$model_value= "CPC";
	break;
	case '3':	
	$model_value= "CPA";
	break;	
	case '4':	
	$model_value="Tenancy";
	break;	
	}
	echo "Pricing Model:";
	 echo $model_value;


	  
	//$zone_amount=$this->dbproduct->retrieve_zonedata(array("zoneid"=>$zoneid));
	//$data['zone_amount']=$zone_amount;
	//$this->load->view('advertiser/view_zones',$data);
	}
	
/* Open Inviter */	
		function referfriends()
		{
		$this->load->view('advertiser/account/refer_frd');
		}
	
/*send mail to individual friends */	

		function send_mail()
		{
		/*Send the mail */
		$clientid= $this->session->userdata('clientid');
		$mailid=mysql_query("select * from ox_clients where clientid='$clientid'");
		$norow1=mysql_fetch_array($mailid);
		$from=$norow1['email'];
		$tomail=$this->input->post("email");
		$mail=explode(',',$tomail);
		count($mail);
		for($i=0;$i<count($mail);$i++)
		{
		$to=$mail[$i];
		$subject=$this->input->post("subject");
		$message=$this->input->post("message");
		$this->email->set_newline("\r\n");
		$config['mailtype'] = 'html';
		$config['charset'] = 'UTF-8';	
		$this->email->initialize($config);
		$this->email->from($from);
		$this->email->to($to); 
		$this->email->subject($subject);        
		$this->email->message($message);
		$this->email->send();
		}
		$data['msg']="Message Sent Successfully";
		$this->load->view('advertiser/account/refer_frd',$data);
		}

/*sql banner */
  function showimage($dbimage)
	{
$data['dbimage']=$dbimage;
$this->load->view('advertiser/campaigns/showimage',$data);
	}

/*Report */
function report()
{
$clientid=$this->session->userdata('clientid');
$query=mysql_query("select c.campaignname,b.description,h.date_time,h.impressions,h.clicks,h.total_revenue,b.bannerid,c.clientid,h.ad_id,b.campaignid,c.campaignid from ox_campaigns c join  ox_data_summary_ad_hourly h join ox_banners b on b.bannerid=h.ad_id AND b.campaignid=c.campaignid where c.clientid='$clientid'");
$data['row']=$query;
$this->load->view('advertiser/campaigns/report',$data);
}
function viewfullreports()
	{
	$this->load->view('advertiser/account/viewfullreports');
	}	

function exportcsv()
{
	$this->load->view('advertiser/account/csv');
}
	
	
/*performancereport */
function ajaxload($filename)
{
	$gd	="jscript/".$filename;	
	$f = fopen($gd, "r");
	while ( $line = fgets($f, 1000) ) { print $line; }
	//@unlink($gd);
}

/*performancereport */
function performancereport()
{
	//$gd	="jscript/chartadvertiservalue.tsv";	
	//@unlink($gd);
	
	$date=date("Y-m-d");
	$data['date']=$date;
	redirect("advertiser/performance_report/".$date);
}

function performance_report()
{

$dv	=($this->input->post("date") !="")?$this->input->post("date"):date("Y-m-d");
$inputdate=($this->uri->segment(3) =="")?$dv:$this->uri->segment(3);

	if($inputdate!="")
	{
	$fetchdate=explode(':',$inputdate);
	}
	else
	{
	$fetchdate=explode(':',$inputdate);
	}												
		
	$count=count($fetchdate);
	if($count>1)
	{
	for($i=0;$i<$count;$i++)
	{
	$startdate=$fetchdate[0];
	$enddate=$fetchdate[1];
	}
	$sdate=explode('-',$startdate);
	$edate=explode('-',$enddate);
	$start_date=mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
	$end_date=mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
	$final_dat=$end_date-$start_date;
	$fullDays = floor($final_dat/(60*60*24));
	
	/*   File Creation  */ 
	$filename	="charts".rand(0,99).".tsv";
	$fh="jscript/".$filename;							
	$file=fopen($fh,"w");
	$linedata ="# ----------------------------------------\n";
	$linedata .="# Graph\n";
	$linedata .="# ----------------------------------------\n";
	//$linedata .="Day	Pageviews(Segment)	Clicks(Segment)	\n";
	$linedata .="Day	UniqueImpressions(Segment) UniqueClicks(Segment)	Impressions(Segment)  Clicks(Segment) Conversions(Segment) CTR(Segment)	Estimated Earnings(Segment)\n";
	fwrite($file, $linedata);
	$line_data="";											
															
				$clientid=$this->session->userdata('clientid');
				for($i=0;$i<=$fullDays;$i++)
				{
				$start_date=date('Y-m-d H:i:s',mktime(0,0,0,$sdate[1],$sdate[2]+$i,$sdate[0]));
				$end_date=date('Y-m-d H:i:s',mktime(23,59,59,$sdate[1],$sdate[2]+$i,$sdate[0]));
				$revenue=mysql_query("select sum(h.impressions),sum(h.clicks),sum(h.conversions),sum(h.total_revenue),h.date_time,c.clientid,b.bannerid,h.ad_id,c.campaignid,b.campaignid,b.description from ox_data_summary_ad_hourly h join ox_campaigns c join ox_banners b on c.campaignid=b.campaignid AND b.bannerid=h.ad_id and c.clientid='$clientid' where date(h.date_time) BETWEEN '$start_date ' AND '$end_date'");
				$rev=mysql_fetch_array($revenue);
				
				$datas=$rev['date_time'];
				$dbdate=substr($datas,0,10);
				$finddate=substr($start_date,0,10);
				$find_date=explode("-",$finddate);
				$month_id=$find_date[1];
				if(strlen($month_id)>1)
				{
				$monthid=$month_id;
				}
				else
				{
				$monthid="0".$month_id;
				}
				switch($monthid)
				{
					case '01':
					$month="Jan";
					break;
					case '02':
					$month="Feb";
					break;
					case '03':
					$month="Mar";
					break;
					case '04':
					$month="Apr";
					break;
					case '05':
					$month="May";
					break;
					case '06':
					$month="Jun";
					break;
					case '07':
					$month="Jul";
					break;
					case '08':
					$month="Aug";
					break;
					case '09':
					$month="Sep";
					break;
					case '10':
					$month="Oct";
					break;
					case '11':
					$month="Nov";
					break;
					case '12':
					$month="Dec";
					break;
				}
				
				$check_date_value=$month.($find_date[2].",".$find_date[0]);
$page_views=mysql_query("select c.clientid,b.bannerid,u.creative_id,u.clicks,u.viewer_id,u.date_time,u.impressions,b.campaignid,c.campaignid from ox_banners b join ox_campaigns c join  ox_unique u on u.creative_id=b.bannerid and b.campaignid=c.campaignid  where c.clientid='$clientid' AND date(u.date_time)='$start_date' AND '$end_date' AND u.impressions!='0' GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.date_time)");
if(mysql_num_rows($page_views)>0)
{
	$page_view=mysql_num_rows($page_views);
	if($page_view!="")
	{
		$pageview=mysql_num_rows($page_views);
	}
	else
	{
		$pageview=0;
	}
	}
	else
	{
	$page_views2=mysql_query("select c.clientid,b.bannerid,u.creative_id,u.viewer_id,u.interval_start,u.count,b.campaignid,c.campaignid from ox_banners b join ox_campaigns c join ox_data_bkt_unique_m u on u.creative_id=b.bannerid and b.campaignid=c.campaignid where c.clientid='$clientid' AND date(u.interval_start)='$start_date' AND '$end_date' GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.interval_start)");
	$page_view=mysql_num_rows($page_views2);
	if($page_view!="")
	{
		$pageview=mysql_num_rows($page_views2);
	}
	else
	{
		$pageview=0;
	}
}				
				


	$clicks_view=mysql_query("select b.bannerid,c.campaignid,c.clientid,u.clicks,u.viewer_id,u.date_time,u.creative_id,u.clicks I from ox_banners b join ox_unique u join ox_campaigns c on u.creative_id=b.bannerid and b.campaignid=c.campaignid where c.clientid='$clientid'  AND date(u.date_time)='$start_date' AND '$end_date' AND u.clicks!='0'  GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.date_time) ");
if(mysql_num_rows($clicks_view)>0)
{
$clickview=mysql_num_rows($clicks_view);
if($clickview!="")
							{
							$clickview=mysql_num_rows($clicks_view);
							}
							else
							{
							$clickview=0;
							}
}
else
{
$clicks_view1=mysql_query("select c.clientid,b.bannerid,u.creative_id,u.viewer_id,u.interval_start from ox_banners b join ox_campaigns c join ox_data_bkt_unique_c u on u.creative_id=b.bannerid where c.clientid='$clientid' AND date(u.interval_start)='$start_date' AND '$end_date' GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.interval_start)");
$clickview=mysql_num_rows($clicks_view1);
if($clickview!="")
							{
							$clickview=mysql_num_rows($clicks_view1);
							}
							else
							{
							$clickview=0;
							}

}

			$bucket_imp=mysql_query("select sum(count),m.creative_id,m.interval_start,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_m m join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=m.creative_id and c.clientid='$clientid' where date(m.interval_start) BETWEEN '$start_date ' AND '$end_date' GROUP BY date(m.interval_start)");
			$bu_impr=mysql_fetch_array($bucket_imp);
			$buck_imp=$bu_impr['sum(count)'];

			$bucket_cli=mysql_query("select sum(count),cl.creative_id,cl.interval_start,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_c cl join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=cl.creative_id and c.clientid='$clientid' where date(cl.interval_start) BETWEEN '$start_date ' AND '$end_date' GROUP BY date(cl.interval_start)");
			$bu_cli=mysql_fetch_array($bucket_cli);
			$buck_cli=$bu_cli['sum(count)'];

			$bucket_conv=mysql_query("select count(action),a.creative_id,a.date_time,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_a a join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=a.creative_id and c.clientid='$clientid' and a.action='1' where date(a.date_time) BETWEEN '$start_date ' AND '$end_date' GROUP BY date(a.date_time)");
				$bu_conv=mysql_fetch_array($bucket_conv);
				$buk_conv=$bu_conv['count(action)'];
			
				
				if($rev['sum(h.clicks)']!="")
				{
				$cli=$rev['sum(h.clicks)']+$buck_cli;
				}
				else
				{
				$cli=0+$buck_cli;
				}
				if($rev['sum(h.impressions)']!="")
				{
				$imp=$rev['sum(h.impressions)']+$buck_imp;
				}
				else
				{
				$imp=0+$buck_imp;
				}
				if($rev['sum(h.conversions)'])
				{
				$conv=$rev['sum(h.conversions)']+buk_conv;
				}
				else
				{
				$conv=0+buk_conv;
				}
				if($cli!="" && $imp!="")
				{
				$val=($cli/$imp);
				$ctr1=($val*100);
				$ctr=number_format($ctr1,2);
				}
				else
				{
				$ctr=0;
				}
				
				$client_amount=mysql_query("select sum(amount) from oxm_report where date BETWEEN '$start_date ' AND '$end_date'  AND clientid='$clientid'");
				$cli_amount=mysql_fetch_array($client_amount);
				$tot=$cli_amount['sum(amount)'];
				if($tot!="")
				{
					$tot=number_format($cli_amount['sum(amount)'],2);
				}
				else
				{
					$tot=0;
				}
					
				$line_data .= $check_date_value."\t".$pageview."\t".$clickview."\t".$imp."\t".$cli."\t".$conv."\t".$ctr."\t".$tot."\n";							
				}
				fwrite($file, $line_data);
				fclose($file);
				$data['filename']	=$filename;
				$data['date_view']	=$inputdate;
				$data['contents']	=$linedata.$line_data;
				}
				else
				{
				$startdate=$dv; //$fetchdate[0];
				$data['date_view']=$inputdate;
				
								for($i=0;$i<$count;$i++)
								{
								$startdate=$fetchdate[0];
								}
								$finddate=substr($startdate,0,10);
								$find_date=explode("-",$finddate);
								$month_id=$find_date[1];
								if(strlen($month_id)>1)
								{
								$monthid=$month_id;
								}
								else
								{
								$monthid="0".$month_id;
								}
								switch($monthid)
								{
									case '01':
									$month="Jan";
									break;
									case '02':
									$month="Feb";
									break;
									case '03':
									$month="Mar";
									break;
									case '04':
									$month="Apr";
									break;
									case '05':
									$month="May";
									break;
									case '06':
									$month="Jun";
									break;
									case '07':
									$month="Jul";
									break;
									case '08':
									$month="Aug";
									break;
									case '09':
									$month="Sep";
									break;
									case '10':
									$month="Oct";
									break;
									case '11':
									$month="Nov";
									break;
									case '12':
									$month="Dec";
									break;
								
								
								}
							  $check_date_value=$month.($find_date[2].",".$find_date[0]);
$clientid=$this->session->userdata('clientid');
$revenue=mysql_query("select sum(h.impressions),sum(h.clicks),sum(h.conversions),sum(h.total_revenue),h.date_time,c.clientid,b.bannerid,h.ad_id,c.campaignid,b.campaignid,b.description from ox_data_summary_ad_hourly h join ox_campaigns c join ox_banners b on c.campaignid=b.campaignid AND b.bannerid=h.ad_id and c.clientid='$clientid' where date(h.date_time)='$startdate' GROUP BY date(h.date_time)");
$rev=mysql_fetch_array($revenue);

$page_views=mysql_query("select b.bannerid,c.campaignid,c.clientid,u.clicks,u.viewer_id,u.date_time,u.creative_id,sum(u.impressions) from ox_banners b join ox_unique u join ox_campaigns c on u.creative_id=b.bannerid AND c.clientid='$clientid' where date(u.date_time)='$startdate' AND u.impressions!='0' GROUP BY u.viewer_id,u.creative_id,date(u.date_time)");
	if(mysql_num_rows($page_views)>0)
	{
		$pageview=mysql_num_rows($page_views);
		if($pageview!="")
		{
			$pageview=mysql_num_rows($page_views);
		}
		else
		{
			$pageview=0;
		}
}
else
{
$page_views2=mysql_query("select c.clientid,b.bannerid,u.creative_id,u.viewer_id,u.interval_start,u.count from ox_banners b join ox_campaigns c join ox_data_bkt_unique_m u on u.creative_id=b.bannerid where c.clientid='$clientid' AND date(u.interval_start)='$startdate' GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.interval_start)");
$pageview=mysql_num_rows($page_views2);
	if($pageview!="")
	{
		$pageview=mysql_num_rows($page_views2);
	}
	else
	{
		$pageview=0;
	}
	}

$clicks_view=mysql_query("select b.bannerid,c.campaignid,c.clientid,u.clicks,u.viewer_id,u.date_time,u.creative_id,u.clicks IS NOT NULL from ox_banners b join ox_unique u join ox_campaigns c on u.creative_id=b.bannerid AND c.clientid='$clientid' where date(u.date_time)='$startdate' and u.clicks!='0' GROUP BY u.viewer_id,u.creative_id,date(u.date_time)");
	if(mysql_num_rows($clicks_view)>0)
	{
	$clickview=mysql_num_rows($clicks_view);
	if($clickview!="")
	{
		$clickview=mysql_num_rows($clicks_view);
	}
	else
	{
		$clickview=0;
	}
	}
	else
	{
	$clicks_view1=mysql_query("select c.clientid,b.bannerid,u.creative_id,u.viewer_id,u.interval_start,u.count IS NOT NULL from ox_banners b join ox_campaigns c join ox_data_bkt_unique_c u on u.creative_id=b.bannerid where c.clientid='$clientid' AND date(u.interval_start)='$startdate' GROUP BY u.viewer_id,u.creative_id,u.zone_id,date(u.interval_start)");
	$clickview=mysql_num_rows($clicks_view1);
	if($clickview!="")
	{
		$clickview=mysql_num_rows($clicks_view1);
	}
	else
	{
		$clickview=0;
	}
	}

$bucket_imp=mysql_query("select sum(count),m.creative_id,m.interval_start,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_m m join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=m.creative_id and c.clientid='$clientid' where date(m.interval_start)='$startdate' GROUP BY date(m.interval_start)");
$bu_impr=mysql_fetch_array($bucket_imp);
$buck_imp=$bu_impr['sum(count)'];

$bucket_cli=mysql_query("select sum(count),cl.creative_id,cl.interval_start,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_c cl join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=cl.creative_id and c.clientid='$clientid' where date(cl.interval_start)='$startdate' GROUP BY date(cl.interval_start)");
$bu_cli=mysql_fetch_array($bucket_cli);
$buck_cli=$bu_cli['sum(count)'];

$bucket_conv=mysql_query("select count(action),a.creative_id,a.date_time,b.bannerid,c.clientid,c.campaignid,b.campaignid from ox_data_bkt_a a join ox_banners b join ox_campaigns c on c.campaignid=b.campaignid and b.bannerid=a.creative_id and c.clientid='$clientid' and a.action='1' where date(a.date_time)='$startdate' GROUP BY date(a.date_time)");
$bu_conv=mysql_fetch_array($bucket_conv);
$buk_conv=$bu_conv['count(action)'];

if($rev['sum(h.clicks)']!="")
{
$cli=$rev['sum(h.clicks)']+$buck_cli;
}
else
{
$cli=0+$buck_cli;
}
if($rev['sum(h.impressions)']!="")
{
$imp=$rev['sum(h.impressions)']+$buck_imp;
}
else
{
$imp=0+$buck_imp;
}
if($rev['sum(h.conversions)'])
{
	$conv=$rev['sum(h.conversions)']+$buk_conv;
}
else
{
	$conv=0+$buk_conv;
}
if($cli!="" && $imp!="")
{
	$val=($cli/$imp);
	$ctr1=($val*100);
	$ctr=number_format($ctr1,2);
}
else
{
	$ctr=0;
}
$client_amount=mysql_query("select sum(amount) from oxm_report where date='$startdate' AND clientid='$clientid'");
$cli_amount=mysql_fetch_array($client_amount);
$tot=$cli_amount['sum(amount)'];

if($tot!="")
{
	$tot=number_format($cli_amount['sum(amount)'],2);
}
else
{
	$tot=0;
}

	$filename	="charts".rand(0,99).".tsv";
	$data['filename']	=$filename;
	$fh="jscript/".$filename;
	
	$new_file=fopen($fh,"w");
	$linedata ="# ----------------------------------------\n";
	$linedata .="# Graph\n";
	$linedata .="# ----------------------------------------\n";
	//$linedata .="Day	Pageviews(Segment)	Clicks(Segment)	\n";
	$linedata .="Day	UniqueImpressions(Segment) UniqueClicks(Segment)	Impressions(Segment)  Clicks(Segment) Conversions(Segment) CTR(Segment)	Estimated Earnings(Segment)\n";
	$linedata .= $check_date_value."\t".$pageview."\t".$clickview."\t".$imp."\t".$cli."\t".$conv."\t".$ctr."\t".$tot."\n";
	
	fwrite($new_file,$linedata);
	fclose($new_file);
	
	$data['contents']	=$linedata;
}						

$this->load->view('advertiser/viewperformance',$data);
}	

/*Tracker */
function addtracker()
	{
	$this->load->view('advertiser/tracker/addtracker');
	}
	function addtracker_process()
	{
	$client_id=$this->input->post("clientid");
	$this->session->set_userdata('clientid',$client_id);
	$client_name=$this->input->post("addtracker_name");
	$des=$this->input->post("tracker_des");
	$conversion_type=$this->input->post("conversion_type");
	$default_status=$this->input->post("default_status");
	$tracker_ins=array("trackername"=>$client_name,"description"=>$des,"clientid"=>$client_id,"status"=>$default_status,"type"=>$conversion_type);
	$this->dbtracker->insert_trackerdata($tracker_ins);
	redirect("advertiser/viewtracker");
	}
	/* View trackers */
	function viewtracker($start=0)
	{
			$client_id=$this->session->userdata('clientid');
			$view_tracker_data=$this->dbtracker->retrieve_trackerdata(array("clientid"=>$client_id));
			$list=$this->dbtracker->getdata($start,5,array("clientid"=>$client_id));
			$config['base_url'] = base_url().'index.php/advertiser/viewtracker';
			$config['total_rows'] = count($view_tracker_data);
			$config['per_page']="5";
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';
			$this->pagination->initialize($config);
	$data['view_tracker']=$list;
	//print_r($view_tracker_data);
	$this->load->view('advertiser/tracker/viewtracker',$data);
	}
	/*Delete trackers */
	function deletetracker()
	{
$client_id=$this->session->userdata('clientid');
	$tracker_value=$this->input->post("delete_trackervalue");
	$delete_tracker=explode('.',$tracker_value);
	if(count($delete_tracker)>0)
	{
	foreach($delete_tracker as $delete_value)
	{
	$this->dbtracker->delete_trackerdata(array("trackerid"=>$delete_value));
	}
	$view_tracker_data=$this->dbtracker->retrieve_trackerdata(array("clientid"=>$client_id));
	$data['view_tracker']=$view_tracker_data;
	$this->load->view('advertiser/tracker/viewtracker',$data);
	}
	else
	{
	$data['error']="There are currently no campaigns defined for this advertiser";
	$this->load->view('advertiser/tracker/viewtracker',$data);
	}
	}
	/*Update tracker */
	function updatetracker($trackerid)
	{
	$update_tracker_data=$this->dbtracker->retrieve_trackerdata(array("trackerid"=>$trackerid));
	$data['update_tracker']=$update_tracker_data;
	$this->load->view('advertiser/tracker/updatetracker',$data);
	}
	function updatetracker_process($trackerid)
	{
	$clientid=$this->session->userdata('clientid');
	$trackername=$this->input->post("addtracker_name");
	$tracker_des=$this->input->post("tracker_des");
	$conversion_type=$this->input->post("conversion_type");
	$default_status=$this->input->post("default_status");
	$up=array("trackername"=>$trackername,"description"=>$tracker_des,"clientid"=>$clientid,"type"=>$conversion_type,"status"=>$default_status);
	$where=array("trackerid"=>$trackerid);
	$this->dbtracker->update_trackerdata($up,$where);
	redirect("advertiser/viewtracker");
	}
	
	function linked_campaigns($trackerid)
	{
	$track_id=$this->session->set_userdata('trackerid',$trackerid);
	$clientid=$this->session->userdata('clientid');
	$linked_cam=$this->dbproduct->retrieve_campaigndata(array("clientid"=>$clientid));
	$data['linked_campaign']=$linked_cam;
	$this->load->view('advertiser/tracker/view_campaigns',$data);
	}
/* insert the values with linked campaigns in ox_trackers*/	
	function linked_campaign_values($trackerid)
	{
	$campaign_value=$this->input->post("select_campaignvalue");
	$campaign=explode(',',$campaign_value);
	
				$dbtracker=mysql_query("select * from ox_campaigns_trackers where trackerid='$trackerid'" );
				$dbcount=mysql_num_rows($dbtracker);
				if($dbcount>0)
				{
				while($db=mysql_fetch_array($dbtracker))
				{
				$dbcam_id=$db['campaignid'];
			    $values1=mysql_query("delete from ox_campaigns_trackers where trackerid='$trackerid' and campaignid='$dbcam_id'");
				}
				foreach($campaign as $campaigns)
				{
				$ins=array("trackerid"=>$trackerid,"campaignid"=>$campaigns);
				$this->dbtracker->insert_linkedcampaigndata($ins); 
				$empty=mysql_query("delete from ox_campaigns_trackers where campaignid=NULL or campaignid=''" );
				}
				}
				else
				{
				foreach($campaign as $campaigns)
				{
				$ins=array("trackerid"=>$trackerid,"campaignid"=>$campaigns);
				$this->dbtracker->insert_linkedcampaigndata($ins); 
				$empty=mysql_query("delete from ox_campaigns_trackers where campaignid=NULL or campaignid=''" );
				}
				}
				
	/* added the values TRUE/FALSE in ox_trackers*/			
	if(count($campaign)>1)
	{
	foreach($campaign as $cam_id)
	{
	$up=array("linkcampaigns"=>'t');
	$where=array("trackerid"=>$trackerid);
	$this->dbtracker->update_trackerdata($up,$where);	
	}
	}
	else
	{
	$up=array("linkcampaigns"=>'f');
	$where=array("trackerid"=>$trackerid);
	$this->dbtracker->update_trackerdata($up,$where);
	}
	redirect("advertiser/viewtracker");
	}
	
	
	

/*Insert the linked trackers values */	
	function linker_trackers($campaignid)
	{
	$data['campaignid']=$campaignid;
	$this->load->view('advertiser/campaigns/linked_trackers',$data);
	}
	
	function linked_tracker_values($campaignid)
	{
	$tracker_value=$this->input->post("select_trackervalue");
	$tracker=explode(',',$tracker_value);
	
	$dbtracker=mysql_query("select * from ox_campaigns_trackers where campaignid='$campaignid'" );
				$dbcount=mysql_num_rows($dbtracker);
				if($dbcount>0)
				{
				while($db=mysql_fetch_array($dbtracker))
				{
				$dbtrack_id=$db['trackerid'];
			        $values1=mysql_query("delete from ox_campaigns_trackers where campaignid='$campaignid' and trackerid='$dbtrack_id'");
				}
				foreach($tracker as $trackers)
				{
				$ins=array("trackerid"=>$trackers,"campaignid"=>$campaignid);
				$this->dbtracker->insert_linkedcampaigndata($ins); 
				$empty=mysql_query("delete from ox_campaigns_trackers where trackerid='0'");
				}
				}
				else
				{
				foreach($tracker as $trackers)
				{
				$ins=array("trackerid"=>$trackers,"campaignid"=>$campaignid);
				$this->dbtracker->insert_linkedcampaigndata($ins); 
				$empty=mysql_query("delete from ox_campaigns_trackers where trackerid='0'");
				}
				}
	/* Days & hours calculation */	
	$view_res="";
	$click_res="";			
	$v_days=$this->input->post("view_days");	
	 $v_hours=$this->input->post("view_hours");	
	$c_days=$this->input->post("click_days");	
	$c_hours=$this->input->post("click_hours");	
	if($v_days!="" && $c_days!="")	
	{
		$view_result=$v_days*24;
		$click_result=$c_days*24;
		if($v_hours || $c_hours)
		{
			$click_res=($click_result+$c_hours)*3600;
			$view_res=($view_result+$v_hours)*3600;
			$up=array("viewwindow"=>$view_res,"clickwindow"=>$click_res);
			$where=array("campaignid"=>$campaignid);
			$this->dbproduct->update_campaignsdata($up,$where);
		}
	}
		
		redirect("advertiser/viewbanner/".$campaignid);
	}
	
/*Invocation code */
	function invocation_code($trackerid)
	{
	$data['track']=$trackerid;
	$this->load->view('advertiser/tracker/trackercode',$data);
	}
	


/*logout function */
	function logout()
	{
	$this->session->sess_destroy();
 	$this->session->unset_userdata('account_name');
	$this->session->unset_userdata('clientid');
	$this->session->unset_userdata('campaignid');
	$this->session->unset_userdata('campaign');
	$this->session->unset_userdata('bannerid');
	$this->session->sess_destroy('account_name');
	$this->session->sess_destroy('clientid');
	echo $this->session->userdata('account_name');
	//redirect("login");
	$this->redirecturl("login",'Logged out',2);
	
	}
function redirecturl($url, $message="", $delay=0) {

				if (!empty($message)) echo "<html><HEAD><meta http-equiv='Refresh' content='$delay; url=$url'><STYLE type='text/css'>.bodytext {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 11px; color: 56595C;	text-decoration: none;}</STYLE></HEAD><body><br /><div align=center><table width='500' height='100' bgcolor='white' style='border: 1px solid #cccccc;' cellpadding='10' cellspacing='0'><tr><td align='center' valign='middle'><font class='bodytext'>$message</font></td></tr></table></div></body></html>";

			

	}
	
	
		
	
}









