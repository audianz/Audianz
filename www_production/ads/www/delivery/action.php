<?php
require_once '../../lib/ELogger.php';
global $log;
$log = new ELogger ( "/var/www/log" , ELogger::DEBUG );
$log->logInfo("Entered action.php");


$clientid	=	$_GET['cl_id'];
$bannerid	=	$_GET['ban_id'];
$cmpid		=	$_GET['cmp_id'];
$zoneid		=	$_GET['zone_id'];
$poi_id		=	$_GET['poi_id'];
$date           =       date("Y-m-d");
function parseDeliveryIniFile($configPath = null, $configFile = null, $sections = true)
{
	if (!$configPath) {
		$configPath = MAX_PATH . '/var';
	}
	if ($configFile) {
		$configFile = '.' . $configFile;
	}
	$host = OX_getHostName();
	$configFileName = $configPath . '/' . $host . $configFile . '.conf.php';
	$conf = @parse_ini_file($configFileName, $sections);
	if (isset($conf['realConfig'])) {
		$realconf = @parse_ini_file(MAX_PATH . '/var/' . $conf['realConfig'] . '.conf.php', $sections);
		$conf = mergeConfigFiles($realconf, $conf);
	}
	if (!empty($conf)) {
		return $conf;
	} elseif ($configFile === '.plugin') {
		$pluginType = basename($configPath);
		$defaultConfig = MAX_PATH . '/plugins/' . $pluginType . '/default.plugin.conf.php';
		$conf = @parse_ini_file($defaultConfig, $sections);
		if ($conf !== false) {
			return $conf;
		}
		echo "OpenX could not read the default configuration file for the {$pluginType} plugin";
		exit(1);
	}
	$configFileName = $configPath . '/default' . $configFile . '.conf.php';
	$conf = @parse_ini_file($configFileName, $sections);
	if (isset($conf['realConfig'])) {
		$conf = @parse_ini_file(MAX_PATH . '/var/' . $conf['realConfig'] . '.conf.php', $sections);
	}
	if (!empty($conf)) {
		return $conf;
	}
	if (file_exists(MAX_PATH . '/var/INSTALLED')) {
		echo "OpenX has been installed, but no configuration file was found.\n";
		exit(1);
	}
	echo "OpenX has not been installed yet -- please read the INSTALL.txt file.\n";
	exit(1);
}
if (!function_exists('mergeConfigFiles'))
{
	function mergeConfigFiles($realConfig, $fakeConfig)
	{
		foreach ($fakeConfig as $key => $value) {
			if (is_array($value)) {
				if (!isset($realConfig[$key])) {
					$realConfig[$key] = array();
				}
				$realConfig[$key] = mergeConfigFiles($realConfig[$key], $value);
			} else {
				if (isset($realConfig[$key]) && is_array($realConfig[$key])) {
					$realConfig[$key][0] = $value;
				} else {
					if (isset($realConfig) && !is_array($realConfig)) {
						$temp = $realConfig;
						$realConfig = array();
						$realConfig[0] = $temp;
					}
					$realConfig[$key] = $value;
				}
			}
		}
		unset($realConfig['realConfig']);
		return $realConfig;
	}
}





function setupConfigVariables()
{
	$GLOBALS['_MAX']['MAX_DELIVERY_MULTIPLE_DELIMITER'] = '|';
	$GLOBALS['_MAX']['MAX_COOKIELESS_PREFIX'] = '__';
	$GLOBALS['_MAX']['thread_id'] = uniqid();
	$GLOBALS['_MAX']['SSL_REQUEST'] = false;
	if (
			(!empty($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == $GLOBALS['_MAX']['CONF']['openads']['sslPort'])) ||
			(!empty($_SERVER['HTTPS']) && ((strtolower($_SERVER['HTTPS']) == 'on') || ($_SERVER['HTTPS'] == 1))) ||
			(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')) ||
			(!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on')) ||
			(!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && (strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) == 'on')) ||
			(!empty($_SERVER['FRONT-END-HTTPS']) && (strtolower($_SERVER['FRONT-END-HTTPS']) == 'on'))
	) {
		$GLOBALS['_MAX']['SSL_REQUEST'] = true;
	}
	$GLOBALS['_MAX']['MAX_RAND'] = isset($GLOBALS['_MAX']['CONF']['priority']['randmax']) ?
	$GLOBALS['_MAX']['CONF']['priority']['randmax'] : 2147483647;
	list($micro_seconds, $seconds) = explode(" ", microtime());
	$GLOBALS['_MAX']['NOW_ms'] = round(1000 *((float)$micro_seconds + (float)$seconds));
	if (substr($_SERVER['SCRIPT_NAME'], -11) != 'install.php') {
		$GLOBALS['serverTimezone'] = date_default_timezone_get();
		OA_setTimeZoneUTC();
	}
}
function setupServerVariables()
{
	if (empty($_SERVER['REQUEST_URI'])) {
		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
		if (!empty($_SERVER['QUERY_STRING'])) {
			$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
}
function setupDeliveryConfigVariables()
{
	if (!defined('MAX_PATH')) {
		define('MAX_PATH', dirname(__FILE__).'/../..');
	}
	if (!defined('OX_PATH')) {
		define('OX_PATH', MAX_PATH);
	}
	if (!defined('LIB_PATH')) {
		define('LIB_PATH', MAX_PATH. DIRECTORY_SEPARATOR. 'lib'. DIRECTORY_SEPARATOR. 'OX');
	}
	if ( !(isset($GLOBALS['_MAX']['CONF']))) {
		$GLOBALS['_MAX']['CONF'] = parseDeliveryIniFile();
	}
	setupConfigVariables();
}
function OX_getHostName()
{
	if (!empty($_SERVER['HTTP_HOST'])) {
		$host = explode(':', $_SERVER['HTTP_HOST']);
		$host = $host[0];
	} else if (!empty($_SERVER['SERVER_NAME'])) {
		$host = explode(':', $_SERVER['SERVER_NAME']);
		$host = $host[0];
	}
	return $host;
}
function OA_setTimeZone($timezone)
{
	date_default_timezone_set($timezone);
	$GLOBALS['_DATE_TIMEZONE_DEFAULT'] = $timezone;
}
function OA_setTimeZoneUTC()
{
	OA_setTimeZone('UTC');
}

setupServerVariables();
setupDeliveryConfigVariables();


function updateTableInfo()
 {
 	
    global $log; 
 //	$log->logDebug("Entered Action: updateTableInfo");
     
 	$conf   = $GLOBALS['_MAX']['CONF']['database'];
 	
 	$dbhost = $conf['host']; 
 	$dbuser = $conf['username']; 
 	$dbpass = $conf['password']; 
 	$dbname = $conf['name']; 
 	$con1 = mysql_connect($dbhost,$dbuser,$dbpass);
 	
         	
 	if(!$con1)
 	{
 		$log->logError(" Action: updateTableInfo:: fail to connect database.");
 		return false;
 	}
 	if(!mysql_select_db($dbname, $con1))//or die("culnot selectn:".mysql_error()))
 	{
 		$log->logError(" Action: updateTableInfo:: fail to select database. error no : ");
 		$log->logError(mysql_error());
 		return ;
 	}
 
	global $clientid;
        global $bannerid;
        global $cmpid;
        global $zoneid;
        global $date;
        global $poi_id;
	
 	
 	switch($_GET['action'])
 	{
 		case 'CALL':
 			{

 			//	$log->logDebug("Enter Action: updateTableInfo CALL \n");
 	/*			global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 				global $poi_id;   
                             */
 				$query	="select * from oxm_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 			
 				$_resultQuery = mysql_query($query);
				if(!$_resultQuery)
				{
					$log->logError("view getTableInfo:: fail to apply database query. error no : ");
					$log->logError(mysql_error());
                	mysql_free_result($_resultQuery);
					return false;
				}
				if(mysql_num_rows($_resultQuery) > 0)
				{
				//	$log->logDebug(" adLocationMatches storefront row found \n");
					
					$_tableValues = mysql_fetch_assoc($_resultQuery);
					
					$callcount	=	$_tableValues['click_to_call'];
					$callcount	=$callcount+1;
					$updatequery="update oxm_report set click_to_call='$callcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
					mysql_query($updatequery);
					
				}
			/*	else
				{
					$clk	=	1;
					mysql_query("insert into oxm_report(clientid,zoneid,campaignid,bannerid,date,poi_id,click_to_call)values('$clientid','$zoneid','$cmpid','$bannerid','$date','$poi_id','$clk')") or die(mysql_error());
				}		
 			*/	
 			}
 			break;
 		
 		case 'WEB':
 			{


 			//	$log->logDebug("Enter Action: updateTableInfo WEB \n");
 			/*	global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 			*/		
 				$query	="select * from oxm_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 				
 				$_resultQuery = mysql_query($query);
 				if(!$_resultQuery)
 				{
 					$log->logError("view getTableInfo:: fail to apply database query. error no : ");
 					$log->logError(mysql_error());
 					mysql_free_result($_resultQuery);
 					return false;
 				}
 				if(mysql_num_rows($_resultQuery) > 0)
 				{
 					$log->logDebug(" adLocationMatches storefront row found \n");
 						
 					$_tableValues = mysql_fetch_assoc($_resultQuery);
 						
 					$webcount	=	$_tableValues['click_to_web'];
 					$webcount	=	$webcount+1;
 					$updatequery="update oxm_report set click_to_web='$webcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 					mysql_query($updatequery);
 						
 				
 				}
	/*			else
				{
					$clk	=	1;
 					mysql_query("insert into oxm_report(clientid,zoneid,campaignid,bannerid,date,poi_id,click_to_web)values('$clientid','$zoneid','$cmpid','$bannerid','$date','$poiId','$clk')") or die(mysql_error());
				}
 					*/
 				
 			}
 			
 			break;
 			
 		case 'MAP':
 			{


 			//	$log->logDebug("Enter Action: updateTableInfo MAP \n");
 			/*	global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 			*/		
 				$query	="select * from oxm_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid'AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 				
 				$_resultQuery = mysql_query($query);
 				if(!$_resultQuery)
 				{
 					$log->logError("view getTableInfo:: fail to apply database query. error no : ");
 					$log->logError(mysql_error());
 					mysql_free_result($_resultQuery);
 					return false;
 				}
 				if(mysql_num_rows($_resultQuery) > 0)
 				{
 				//	$log->logDebug(" Action: updateTableInfo oxm_report row found \n");
 						
 					$_tableValues = mysql_fetch_assoc($_resultQuery);
 						
 					$mapcount	=	$_tableValues['click_to_map'];
 					$mapcount	=	$mapcount+1;
 					$updatequery="update oxm_report set click_to_map='$mapcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 					mysql_query($updatequery);
 						
 				
 				}
 					
 				
 			}
 			
 			break;
 		default:
 		//	$log->logDebug(" Action: updateTableInfo default case no update needed \n");
 			
 			break;
 		
 	}
 	
 	
 }
 
 
 /**
  *  Function to store storewise data
  */
 
 function updateStoreData()
 {


 	global $log;
 //	$log->logDebug("Entered action.php:: updateStoreData ");
 	 
        $conf   = $GLOBALS['_MAX']['CONF']['database'];
 	
 	$dbhost = $conf['host']; 
 	$dbuser = $conf['username']; 
 	$dbpass = $conf['password']; 
 	$dbname = $conf['name']; 	
 	$con1 = mysql_connect($dbhost,$dbuser,$dbpass);
 	
 	
 	if(!$con1)
 	{
 		$log->logError("action.php:: updateStoreData fail to connect database.");
 		return false;
 	}
 	if(!mysql_select_db($dbname, $con1))
 	{
 		$log->logError("action.php:: updateStoreData fail to select database. error no : ");
 		$log->logError(mysql_error());
 		return ;
 	}
 	
 	
 	switch($_GET['action'])
 	{
 		case 'CALL':
 			{
 	
 			//	$log->logDebug("Entered action.php:: updateStoreData CALL ");
 				global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 				global $poi_id;
 					
 				$query	="select * from oxm_store_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid'AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 	
 				$_resultQuery = mysql_query($query);
 				if(!$_resultQuery)
 				{
 					$log->logError("action.php:: updateStoreData fail to apply database query. error no : ");
 					$log->logError(mysql_error());
 					mysql_free_result($_resultQuery);
 					return false;
 				}
 				if(mysql_num_rows($_resultQuery) > 0)
 				{
 				//	$log->logDebug(" action.php:: updateStoreData storefront row found ");
 						
 					$_tableValues = mysql_fetch_assoc($_resultQuery);
 						
 					$callcount	=	$_tableValues['click_to_call'];
 					$callcount	=$callcount+1;
 					$updatequery="update oxm_store_report set click_to_call='$callcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 					mysql_query($updatequery);
 						
 	
 				}
 					
 			}
 			break;
 				
 		case 'WEB':
 			{
 	
 	
 			//	$log->logDebug("Entered action.php:: updateStoreData WEB ");
 				global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 				global $poi_id;
 	
 				$query	="select * from oxm_store_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid'AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 		
 				$_resultQuery = mysql_query($query);
 				if(!$_resultQuery)
 				{
 					$log->logError("action.php:: updateStoreData fail to apply database query. error no : ");
 					$log->logError(mysql_error());
 					mysql_free_result($_resultQuery);
 					return false;
 				}
 				if(mysql_num_rows($_resultQuery) > 0)
 				{
 				//	$log->logDebug(" action.php:: updateStoreData storefront row found ");
 						
 					$_tableValues = mysql_fetch_assoc($_resultQuery);
 						
 					$webcount	=	$_tableValues['click_to_web'];
 					$webcount	=	$webcount+1;
 					$updatequery="update oxm_store_report set click_to_web='$webcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 					mysql_query($updatequery);
 						
 						
 				}
 	
 					
 			}
 	
 			break;
 	
 		case 'MAP':
 			{
 	
 	
 			//	$log->logDebug("Entered action.php:: updateStoreData MAP ");
 				global $clientid;
 				global $bannerid;
 				global $cmpid;
 				global $zoneid;
 				global $date;
 				global $poi_id;
 	
 				$query	="select * from oxm_store_report where clientid='$clientid' AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid'AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 		
 				$_resultQuery = mysql_query($query);
 				if(!$_resultQuery)
 				{
 					$log->logError("action.php:: updateStoreData fail to apply database query. error no : ");
 					$log->logError(mysql_error());
 					mysql_free_result($_resultQuery);
 					return false;
 				}
 				if(mysql_num_rows($_resultQuery) > 0)
 				{
 				//	$log->logDebug(" action.php:: updateStoreData row found ");
 						
 					$_tableValues = mysql_fetch_assoc($_resultQuery);
 						
 					$mapcount	=	$_tableValues['click_to_map'];
 					$mapcount	=	$mapcount+1;
 					$updatequery="update oxm_store_report set click_to_map='$mapcount' where clientid='$clientid'AND zoneid='$zoneid' AND bannerid='$bannerid' AND campaignid='$cmpid' AND poi_id='$poi_id' AND DATE_FORMAT(date,'%Y-%m-%d')=date(now())";
 					mysql_query($updatequery);
 						
 						
 				}
 	
 					
 			}
 	
 			break;
 		default:
 		//	$log->logDebug(" action.php:: updateStoreData default case no update needed ");
 	
 			break;
 				
 	}
 	
 	
 		
 }
 
 
 updateTableInfo();
 updateStoreData();
 
 
?>
