<?php



function Plugin_deliveryLog_oxLogUnique_logImpressionUnique_Delivery_logImpression()
{
    $data = $GLOBALS['_MAX']['deliveryData'];
    $aQuery = array(
        'interval_start' => $data['interval_start'],
        'creative_id'    => $data['creative_id'],
        'zone_id'        => $data['zone_id'],
        'viewer_id'        => $data['viewer_id'],
    );
    return OX_bucket_updateTable('data_bkt_unique_m', $aQuery);
}

?>
