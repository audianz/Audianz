<?php


function Plugin_deliveryDataPrepare_oxDeliveryView_dataView($adId, $zoneId)
{
    
    if ($GLOBALS['_MAX']['deliveryData']['Plugin_deliveryDataPrepare_oxDeliveryView_dataView']) {
        return;
    }
    $GLOBALS['_MAX']['deliveryData']['Plugin_deliveryDataPrepare_oxDeliveryView_dataView'] = true;

		$create = true;
		static $uniqueViewerId = null;
		if(!is_null($uniqueViewerId)) {
		return $uniqueViewerId;
		}
		$conf = $GLOBALS['_MAX']['CONF'];
		if (isset($_COOKIE[$conf['var']['viewerId']])) {
		$uniqueViewerId = $_COOKIE[$conf['var']['viewerId']];
		} elseif ($create) {
		$uniqueViewerId = md5(uniqid('', true)); 

		$name = $conf['var']['viewerId'];
		$expire = time() + 31536000;

		$GLOBALS['_MAX']['COOKIE']['CACHE'][$name] = array($uniqueViewerId, $expire);

		$GLOBALS['_MAX']['COOKIE']['newViewerId'] = true;

		}

		$GLOBALS['_MAX']['deliveryData']['viewer_id'] = $uniqueViewerId;
	
}

function Plugin_deliveryDataPrepare_oxDeliveryView_dataView_Delivery_logRequest($adId, $zoneId)
{
    Plugin_deliveryDataPrepare_oxDeliveryView_dataView($adId, $zoneId);
}

function Plugin_deliveryDataPrepare_oxDeliveryView_dataView_Delivery_logImpression($adId, $zoneId)
{
    Plugin_deliveryDataPrepare_oxDeliveryView_dataView($adId, $zoneId);
}

function Plugin_deliveryDataPrepare_oxDeliveryView_dataView_Delivery_logClick($adId, $zoneId)
{
    Plugin_deliveryDataPrepare_oxDeliveryView_dataView($adId, $zoneId);
}

?>
