<html>
<meta http-equiv="refresh" content="<?php echo $_GET['refresh'];?>" > 
<body>
<?php
require_once '../../init.php';
require_once '../../lib/ELogger.php';

$log = new ELogger ( "/var/www/log" , ELogger::DEBUG );
$log->logInfo("Entered oxm_ref.php");

$confa = $GLOBALS['_MAX']['CONF'];
$path="http://".$confa['webpath']['deliverySSL'];

$zoneid=$_GET['zoneid'];
$refresh=$_GET['refresh'];
$gender=$_GET['gender'];
$age=$_GET['age'];
$teleco=$_GET['teleco'];
$lat=$_GET['lat'];
$lon=$_GET['lon'];
$bannerid=$_GET['bnrid'];
$poi=$_GET['poi'];




?>

<a href='<?php echo $path; ?>/ck.php?n=abc147f4&amp;lat=<?php echo $lat;?>&lon=<?php echo $lon;?>&amp;zoneid=<?php echo $zoneid; ?>&amp;poi=<?php echo $poi; ?>&amp;type=1;' target='_blank'>
<img src='<?php echo $path; ?>/avw.php?zoneid=<?php echo $zoneid; ?>&amp;n=abc147f4&lat=<?php echo $lat;?>&lon=<?php echo $lon;?>&amp;gender=<?php echo $gender; ?>&amp;age=<?php echo $age; ?>&amp;teleco=<?php echo $teleco; ?>' border='0' alt='' /></a>
<body>
</html>
