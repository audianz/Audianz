<?php
require_once '../../lib/ELogger.php';
global $log;
$log = new ELogger ( "/var/www/log" , ELogger::DEBUG );
$log->logInfo("Entered view.php");

$addr1 = "Not found";
$addr2 = "Not found";
$city  = "";
$tel = 0;
$distance = -1;
$bannerDes = "Not found";
$bannerName = "Not found";
$imageUrl = "Not Found";

global $globClientid;
global $globZoneid;
global $globBannerid;
global $globCampaignid;
global $globRevenue_type;

global $call;
global $web;
global $map;
global $poiVal;

/**
 * The function is used to store landing page count in database.
 * Added by	Shyam
 */
function updateLanding()
{
	global $log;
	$con1 = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
	if(!$con1)
	{
		$log->logError("view updateLanding:: fail to connect database.");
		return false;
	}
	if(!mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con1))//or die("culnot selectn:".mysql_error()))
	{
		$log->logError("view updateLanding:: fail to select database. error no : ");
		$log->logError(mysql_error());
		return false;
	}
	

	global $poiVal;
	global $globClientid;
	global $globZoneid;
	global $globBannerid;
	global $globCampaignid;
	global $date;
	$log->logDebug(" Value of poi id is ".$poiVal);
	
	
	$query	="select * from oxm_report where clientid='$globClientid' AND poi_id='$poiVal' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid'AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";

	
	$_resultQuery = mysql_query($query);
	if(!$_resultQuery)
	{
		$log->logError("view updateLanding:: fail to apply database query. error no : ");
		$log->logError(mysql_error());
		mysql_free_result($_resultQuery);
		return false;
	}
	if(mysql_num_rows($_resultQuery) > 0)
	{
			
		$_tableValues = mysql_fetch_assoc($_resultQuery);
			
		$landing	=	$_tableValues['landing'];
		$landing	=	$landing+1;
		$updatequery="update oxm_report set landing='$landing' where clientid='$globClientid' AND poi_id='$poiVal' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
		mysql_query($updatequery);
			
	
	}
	else 
	{
		$query1	="select * from oxm_report where clientid='$globClientid' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid'AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
		
	
		$_resultQuery1 = mysql_query($query1);
		
		if(!$_resultQuery1)
		{
			$log->logError("view updateLanding:: fail to apply database query. error no : ");
			$log->logError(mysql_error());
			mysql_free_result($_resultQuery1);
			return false;
		}
		if(mysql_num_rows($_resultQuery1) > 0)
		{
				
			$_tableValues = mysql_fetch_assoc($_resultQuery1);
				
			$landing	=	$_tableValues['landing'];
			$landing	=	$landing+1;
			$updatequery="update oxm_report set landing='$landing',poi_id='$poiVal' where clientid='$globClientid' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
			
			mysql_query($updatequery);
				
		
		}
		
		
	}
	
	
	
}

function updateStore()
{
	
	global $log;
	$con1 = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
	if(!$con1)
	{
		$log->logError("view.php updateStore() fail to connect database.");
		return false;
	}
	if(!mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con1))//or die("culnot selectn:".mysql_error()))
	{
		$log->logError("view.php updateStore() fail to select database. error no : ");
		$log->logError(mysql_error());
		return false;
	}
	
	
	global $poiVal;
	global $globClientid;
	global $globZoneid;
	global $globBannerid;
	global $globCampaignid;
	global $date;
	
	
	$query	="select * from oxm_store_report where clientid='$globClientid' AND poi_id='$poiVal' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid'AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
	
	
	$resultQuery = mysql_query($query);
	if(!$resultQuery)
	{
		$log->logError("view.php updateStore() fail to apply database query. error no : ");
		$log->logError(mysql_error());
		mysql_free_result($resultQuery);
		return false;
	}
	if(mysql_num_rows($resultQuery) > 0)
	{
			
		$_tableValues = mysql_fetch_assoc($resultQuery);
			
		$landing	=	$_tableValues['landing'];
		$landing	=	$landing+1;
		$updatequery="update oxm_store_report set landing='$landing' where clientid='$globClientid' AND poi_id='$poiVal' AND zoneid='$globZoneid' AND bannerid='$globBannerid' AND campaignid='$globCampaignid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
		mysql_query($updatequery);
			
	
	}
	else
	{
		mysql_query("insert into oxm_store_report(clientid,zoneid,campaignid,bannerid,date,poi_id)values('$clientid','$zoneid','$campid','$bannerid','$date','$poiVal')") or die(mysql_error());
	}
	
}

function distancee($lat1, $lon1, $lat2, $lon2, $unit) {
	global $log;
	$log->logDebug(" lat1 is $lat1, lon1 is $lon1, lat2 is $lat2, lon2 is $lon2 ");
 if(is_null($lat1) || is_null($lon1) || is_null($lat2) || is_null($lon2))
  { 
        $log->logDebug("\n view lat lon not valid \n");
        return -1;
  }
  else if(empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2))
  {
    $log->logDebug("\n view lat lon not valid1 \n");
    return -1;
  }


	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}

function getTableInfo()
{
	global $log;
	$con1 = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
	if(!$con1)
	{
		$log->logError("view getTableInfo:: fail to connect database.");
		return false;
	}
	if(!mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con1))//or die("culnot selectn:".mysql_error()))
	{
		$log->logError("view getTableInfo:: fail to select database. error no : ");
		$log->logError(mysql_error());
		return false;
	}
	global $poiVal;
	
	
	$_distanceQuery = mysql_query("select * from storefrontdata where (id='".$poiVal."')");
	if(!$_distanceQuery)
	{
		$log->logError("view getTableInfo:: fail to apply database query. error no : ");
		$log->logError(mysql_error());
                mysql_free_result($_distanceQuery);
		return false;
	}
	if(mysql_num_rows($_distanceQuery) > 0)
	{
		$_tableValues = mysql_fetch_assoc($_distanceQuery);
		global $addr1;
		global $addr2;
		global $city;
		global $tel;
		global $bannerName;
		global $distance;
		$addr1 = $_tableValues['address1'];
		$addr2 = $_tableValues['address2'];
                $city  = $_tableValues['city'];
		$tel = $_tableValues['tel'];
		$bannerName = $_tableValues['poi_name'];
		global $globalarr;
		if($_GET['zoneid']==0)
		{
			$latitude  = $globalarr['lat'];
			$longitude = $globalarr['lon'];
		}
		else
		{
			$latitude  = $_GET['lat'];
			$longitude = $_GET['lon'];
		}
		if($_GET['zoneid']==0)
		{
			$distance = distancee($globalarr['lat'],$globalarr['lon'],$_tableValues['lat'],$_tableValues['lon'],k);
		}
		else
		{
			$distance = distancee($_GET['lat'],$_GET['lon'],$_tableValues['lat'],$_tableValues['lon'],k);
		}
		$log->logDebug("distance b/w lat/lon ".$distance);
                
		global $maplat;
		global $maplon;
		global $curlat;
		global $curlon;
		$maplat		=	$_tableValues['lat'];
		$maplon		=	$_tableValues['lon'];
		$curlat		=	$latitude;
		$curlon		=	$longitude;
	}
	global $banId;
	
	$_bannerQuery = mysql_query("select * from ox_banners where (bannerid='".$banId."')");
	if(!$_bannerQuery)
	{
		$log->logError("view getTableInfo:: fail to apply database query. error no : ");
		$log->logError(mysql_error());
                mysql_free_result($_bannerQuery);
		return false;
	}
	if(mysql_num_rows($_bannerQuery) > 0)
	{
		$_tableData = mysql_fetch_assoc($_bannerQuery);
                $_campaignid = $_tableData['campaignid'];
                if($_campaignid > 0 )
                {
                	$_campaignQuery = mysql_query("select * from djx_intermediate_landing where (camp_id='".$_campaignid."')");
			if(!$_campaignQuery)
			{
				$log->logError("view getTableInfo:: fail to apply database query1. error no : ");
				$log->logError(mysql_error());
                		mysql_free_result($_campaignQuery);
				return false;
			}
			if(mysql_num_rows($_campaignQuery) > 0)
        		{
                        	$_rowData = mysql_fetch_assoc($_campaignQuery);
                        	global $bannerDes;
				$bannerDes = $_rowData['description'];
  // Get screen size then select image.
				require_once 'handset.php';
                                $screenSize = getScreenSize();
				
				global $imageUrl;
				switch($screenSize)
				{
					 case 1:
        					$imageUrl = $_rowData['rect_banner_xl'];
       						break;
    					case 2:
        					$imageUrl = $_rowData['rect_banner_l'];
       						break;
    					case 3:
        					$imageUrl = $_rowData['rect_banner_m'];
        					break;
					case 4:
        					$imageUrl = $_rowData['rect_banner_s'];
        					break;
					default:
						$imageUrl = $_rowData['rect_banner_s'];
						
				} 
				
				
			}
		
                }
	
	}
	mysql_free_result($_campaignQuery);
        mysql_free_result($_distanceQuery);
        mysql_free_result($_bannerQuery);
	return true;
}


updateStore();
updateLanding();


$result = getTableInfo();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://54.235.173.134/jq.js"></script>
<script>
function run(click_action,adurl)
{
	
	
	
	var ad="http://<?php global $conf;echo $conf['webpath']['delivery'];?>/action.php?action="+click_action+"&cl_id="+<?php global $globClientid;echo $globClientid?>+"&ban_id="+<?php global $globBannerid;echo $globBannerid?>+"&zone_id="+<?php global $globZoneid;echo $globZoneid?>+"&cmp_id="+<?php global $globCampaignid;echo $globCampaignid?>+"&poi_id="+<?php global $poiVal;echo $poiVal;?>;
	
	$.ajax(
		{url: ad,type: 'GET',
		    success: function(res) 
			{ 
				location.href=adurl;
			//	window.open(adurl,'_blank');

			}
		}
	);

return false;
}
</script>
<title>Intermediate Landing</title>
        <meta charset="utf-8" />
	
	<link rel="stylesheet" href="style.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>
<body class="body">
	<header class="mainHeader">
			<h2><?php global $bannerName; echo $bannerName; ?></h2>		
	</header>
		
			<?php 
			global 	$imageUrl;
			global 	$maplat;
			global 	$maplon;
			global  $curlat;
			global 	$curlon;
			
                        global $tel;
                        global $conf;
                        $imgsrc = "'http://".$conf['webpath']['rectBanner']."/".$imageUrl."'";
			$mapurl="'http://".$conf['webpath']['delivery'].'/map.php?maplat='.$maplat."&maplon=".$maplon."&curlat=".$curlat."&curlon=".$curlon."'";
                        $telurl = "tel:".$tel;
		        $callImg; $webImg;$mapImg;
			 global $landingArray;
			if( $landingArray['call'] == "false" )
                        {
				$callImg = "'http://".$conf['webpath']['images']."/call_disable.png'";
				global $call;
				$call='0';
			}
			else
			{
				$callImg = "'http://".$conf['webpath']['images']."/call_enable.png'";
				global $call;
				$call="CALL";
			}
			if( $landingArray['web'] == "false" )
			{
				$webImg = "'http://".$conf['webpath']['images']."/web_disable.png'";
				global $web;
				$web='0';
			}
			else
			{
				$webImg = "'http://".$conf['webpath']['images']."/web_enable.png'";
				global $web;
				$web="WEB";
			}
			if( $landingArray['map'] == "false" )
			{
				$mapImg = "'http://".$conf['webpath']['images']."/map_disable.png'";
				global $map;
				$map='0';
				
			}
			else
			{
				$mapImg = "'http://".$conf['webpath']['images']."/map_enable.png'";
				global $map;
				$map="MAP";
			}

		?>
<div class="mainContent">
<img style="display: block; margin-left: auto; margin-right: auto;" src=<?php echo $imgsrc;?>>		
<div class="content">	

		<article class="topcontent">	
										
					<div style="width:100%;"> <label class="text"><?php global $bannerDes; echo $bannerDes; ?></label></div>
			</br> </br> 
<span> <label class="txt">&nbsp;<?php global $addr1;global $addr2; global $city; echo $addr1.",  ".$addr2.", ".$city; ?></label> </span>
		
		<label class="txt" style="margin-right:8%;float:right;">&nbsp;<?php global $distance; echo round($distance,2); ?> &nbsp;Km</label>
		</br> 
		<span ><label class="txt">&nbsp;Tel: <?php global $tel; echo $tel; ?></label>
		</br> </br> 	
		</span >
		<span >
<a  class='<?php global $landingArray; if($landingArray['call'] == "false"){ echo "button_example_disabled"; $telurl = "";} else { echo "button_example"; } ?>' style="padding:4% 0% 4% 0%;" onclick="return run('<?php global $call; echo $call;?>','<?php echo $telurl;?>');" href="<?php echo $telurl;?>" href="<?php echo $tel;?>">Call</a>
<?php 
global $globalarr;
if($_GET['zoneid']!=0)
{
?>		
<a class='<?php global $landingArray; if($landingArray['web'] == "false"){ echo "button_example_disabled";global $bannerurl; $bannerurl = "";} else { echo "button_example"; } ?>' style="padding: 4% 0% 4% 0%;" onclick="return run('<?php global $web; echo $web;?>','<?php echo $bannerurl;?>');" href='<?php global $bannerurl; echo $bannerurl;?>'>Web</a>		

<?php }
else{

global $globalarr;
$url = explode('_',$globalarr['oaparams']);
if(!empty($url))
{
	$len =count($url);
	$ad=$url[$len-1];
	$res = explode('=',$ad);
	$adurl=$res[1];
}

?>
<a class='<?php global $landingArray; if($landingArray['web'] == "false"){ echo "button_example_disabled";global $bannerurl; $bannerurl = "";} else { echo "button_example"; } ?>' style="padding: 4% 0% 4% 0%;" onclick="return run('<?php global $web; echo $web;?>','<?php echo $adurl;?>');" href='<?php echo $adurl;?>'>Web</a>
<?php } ?>
<a class='<?php global $landingArray; if($landingArray['map'] == "false"){ echo "button_example_disabled"; $mapurl = "";} else { echo "button_example"; } ?>' style="padding: 4% 0% 4% 0%;" onclick="return run('<?php global $map; echo $map;?>',<?php echo $mapurl;?>);" href=<?php echo $mapurl;?>>Map</a>

</span>

		</div>
	</div>
</body>
</html>

