<?php


require_once MAX_PATH.'/lib/max/Dal/DataObjects/DB_DataObjectCommon.php';

class DataObjects_Unique extends DB_DataObjectCommon
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'unique';               
    public $unique_id;                
    public $date_time;                       
    public $operation_interval;              
    public $creative_id;                    
    public $zone_id;                       
    public $impressions;                 
    public $clicks;                          
    public $viewer_id;                        

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Unique',$k,$v); }

    var $defaultValues = array(
                'date_time' => '%NO_DATE_TIME%',
                'impressions' => 0,
                'clicks' => 0,
                'viewer_id' => '',
                );

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
?>
