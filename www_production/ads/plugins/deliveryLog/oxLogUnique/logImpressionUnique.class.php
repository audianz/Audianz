<?php


require_once LIB_PATH . '/Extension/deliveryLog/BucketProcessingStrategyFactory.php';
require_once LIB_PATH . '/Extension/deliveryLog/DeliveryLog.php';


class Plugins_DeliveryLog_OxLogUnique_LogImpressionUnique extends Plugins_DeliveryLog
{

    function getDependencies()
    {
        return array(
            'deliveryLog:oxLogUnique:logImpressionUnique' => array(
                'deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon',
                'deliveryDataPrepare:oxDeliveryView:dataView'
            )
        );
    }


    function getBucketName()
    {
        return 'data_bkt_unique_m';
    }


    public function getBucketTableColumns()
    {
        $aColumns = array(
            'interval_start' => self::TIMESTAMP_WITHOUT_ZONE,
            'creative_id'    => self::INTEGER,
            'zone_id'        => self::INTEGER,
            'viewer_id'        => self::CHAR,
            'count'          => self::INTEGER,
        );
        return $aColumns;
    }


    public function getStatisticsName()
    {
        return 'unique';
    }


    public function getStatisticsMigration()
    {
        $aMap = array(
            'method'           => 'aggregate',
            'bucketTable'      => $this->getBucketTableName(),
            'dateTimeColumn'   => 'interval_start',
            'groupSource'      => array(
                0 => 'interval_start',
                1 => 'creative_id',
                2 => 'zone_id',
                3 => 'viewer_id'
            ),
            'groupDestination' => array(
                0 => 'date_time',
                1 => 'creative_id',
                2 => 'zone_id',
                3 => 'viewer_id'
            ),
            'sumSource'        => array(
                0 => 'count'
            ),
            'sumDestination'   => array(
                0 => 'impressions'
            ),
            'sumDefault'       => array(
                0 => 0
            )
        );
        return $aMap;
    }

}

?>
