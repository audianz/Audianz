<?php
require_once '../../lib/ELogger.php';
global $log;
$log = new ELogger ( "/var/www/log" , ELogger::DEBUG );
$log->logInfo("Entered handset.php");


	$_query = mysql_query("select * from oxm_terawurfl") or die(mysql_error());
	$_tera_path = '';
	if(mysql_num_rows($_query)) {
		$_row = mysql_fetch_assoc($_query);
		$_tera_path = $_row['terawurfl_path'];
	} 
	$_tera_path .= "/webservice.php";

	//$_tera_path .= "http://openxservices.com/Tera-WURFL/webservice.php";

	define("M_WURFL", $_tera_path);


function changeZone($zoneid=0){

 // GET ZONE ID BASED ON WIDTH AND HEIGHT OF MOBILE DEVICES

$con = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con)or die("culnot select3:".mysql_error());
$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

	global $keycode, $mobileSize, $errorM;
//$_SERVER['HTTP_USER_AGENT']  to check wether user is on mobile device.
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'SonyEricssonK700i/R2AC SEMC-Browser/4.0.2 Profile/MIDP-2.0 Configuration/CLDC-1.1' ;
	$all_values = getCapabilityValues(M_WURFL."?ua=".urlencode($ua));
	$common = $all_values->device;
	$all = $common->capability;
    
	$os = $all[0]['value'];   //device OS
	$model_name = $all[1]['value'];  //device Model
	$brand_name = $all[2]['value'];  //device Brand
	$widthx = $all[3]['value']; //device width
	$heightx = $all[4]['value']; //device height
	$capability_name=$all[6]['value'];
	$capability_name1=$all[7]['value'];
	$capability_name2=$all[8]['value'];
	$capability_name3=$all[9]['value'];



	$MR = array();
	
	$width = array();
	$height = array();
//All available size of banners
	$query = mysql_query("select * from oxm_mobilescreensizesettings") or die("culnot select2:".mysql_error());
	while($row = mysql_fetch_assoc($query))
	{
			$width[$row['id']] = $row['width'];
			$height[$row['id']] = $row['height'];
	}


$mobileSize['extralarge']['width'] 	= $width[1];
$mobileSize['extralarge']['height']	= $height[1];
$mobileSize['large']['width'] 		= $width[2];
$mobileSize['large']['height'] 		= $height[2];
$mobileSize['medium']['width'] 		= $width[3];
$mobileSize['medium']['height'] 	= $height[3];
$mobileSize['small']['width'] 		= $width[4];
$mobileSize['small']['height'] 		= $height[4];

$zonequery = mysql_query("select * from djx_mobilezones where masterzoneid=".$zoneid." ") or die("culnot select1:".mysql_error());
$zonerow = mysql_fetch_assoc($zonequery);

	if(mysql_num_rows($zonequery) > 0){

		$MR['width']  = $widthx;     //'216';//$mobileInfo['display']['max_image_width'];
		$MR['height'] = $heightx;    //'36';$mobileInfo['display']['max_image_height'];

		$mz=4;

		
		if( $MR['width']>=$mobileSize['extralarge']['width'] )
		{

		$mz=1;
		}else{
			if( $MR['width']>=$mobileSize['large']['width'] )
			{

			$mz=2;
			}else{
				if( $MR['width']>=$mobileSize['medium']['width'] )
				{

				$mz=3;
				}else{
					if( $MR['width']>=$mobileSize['small']['width'] )
					{
					$mz=4;

					}
				}
			}
		}
		# Check Which MobileZone is detect
		# Get related Mobile Zone IDdie($mz);
				switch($mz){
						case 1:
							$zoneid = $zonerow['mz1'];
						break;
						case 2:
							$zoneid = $zonerow['mz2'];
						break;
						case 3:
							$zoneid = $zonerow['mz3'];
						break;
						case 4:
							$zoneid = $zonerow['mz4'];
						break;
						default:
							$zoneid = $zonerow['mz4'];
						break;
					}
	}else{
	 $errorM .= 'Error:Mobile Zone';
	}	
	

	//echo $zoneid;


	return $zoneid; #return zoneid as passed if Handset Detection account is not present in OpenX for the User
}

function getCapabilityValues($url){
//global $log;
//$log->logDebug("Inside getCapabilityValue url is : ".$url);


	$rfd = fopen($url, 'r');


	stream_set_blocking($rfd,true);
	stream_set_timeout($rfd, 20);  // 20-second timeout

	$data = stream_get_contents($rfd);



	$status = stream_get_meta_data($rfd);

	
	fclose($rfd);


	
	if($status['timed_out']){

				$xml = simplexml_load_file($url);
		}else{
	
			$xml = simplexml_load_string($data);
		}
return $xml;
}


function getScreenSize()
{
 // GET WIDTH AND HEIGHT OF MOBILE DEVICES

$con = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con)or die("culnot select3:".mysql_error());
$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

	global $keycode, $mobileSize, $errorM;
//$_SERVER['HTTP_USER_AGENT']  to check wether user is on mobile device.
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'SonyEricssonK700i/R2AC SEMC-Browser/4.0.2 Profile/MIDP-2.0 Configuration/CLDC-1.1' ;
	$all_values = getCapabilityValues(M_WURFL."?ua=".urlencode($ua));
	$common = $all_values->device;
	$all = $common->capability;
    
	$os = $all[0]['value'];   //device OS
	$model_name = $all[1]['value'];  //device Model
	$brand_name = $all[2]['value'];  //device Brand
	$widthx = $all[3]['value']; //device width
	$heightx = $all[4]['value']; //device height
	$capability_name=$all[6]['value'];
	$capability_name1=$all[7]['value'];
	$capability_name2=$all[8]['value'];
	$capability_name3=$all[9]['value'];



	$MR = array();
	
	$width = array();
	$height = array();
//All available size of banners
	$query = mysql_query("select * from oxm_rectbannersizes") or die("culnot select2:".mysql_error());
	while($row = mysql_fetch_assoc($query))
	{
			$width[$row['id']] = $row['width'];
			$height[$row['id']] = $row['height'];
	}

$mobileSize['extralarge']['width'] 	= $width[1];
$mobileSize['extralarge']['height']	= $height[1];
$mobileSize['large']['width'] 		= $width[2];
$mobileSize['large']['height'] 		= $height[2];
$mobileSize['medium']['width'] 		= $width[3];
$mobileSize['medium']['height'] 	= $height[3];
$mobileSize['small']['width'] 		= $width[4];
$mobileSize['small']['height'] 		= $height[4];



$MR['width']  = $widthx;     //'216';//$mobileInfo['display']['max_image_width'];
$MR['height'] = $heightx;    //'36';$mobileInfo['display']['max_image_height'];
$mz=4;

		
		if( $MR['width']>=$mobileSize['extralarge']['width'] )
		{

		$mz=1;
		}
		else if( $MR['width']>=$mobileSize['large']['width'] )
		{
			$mz=2;
		}
		else if( $MR['width']>=$mobileSize['medium']['width'] )
		{
			$mz=3;
		}
		else if( $MR['width']>=$mobileSize['small']['width'] )
		{
			$mz=4;
		}
return $mz;
}
?>
