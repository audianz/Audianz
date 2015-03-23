<?php
$con= mysql_connect("localhost","root","rgbxyz");
	mysql_select_db("audianz", $con)or die(mysql_error());

	if (!$con) {
		die('Could not connect: ' . mysql_error());
	}
	else
	{
		$get_locInfo= mysql_query("SELECT device_geo_latitude as lat, device_geo_longitude as lon FROM `djax_smaato_bid_request` where date_format(datetime,'%d%m%y')=date_format(now(),'%d%m%y')");
		$result=array();
		if(mysql_num_rows($get_locInfo)>0)
		{
			while($get_bannerRow=mysql_fetch_assoc($get_locInfo))
			{	
				$result[]=$get_bannerRow;
			}
		}
		echo json_encode($result);
	}
?>
