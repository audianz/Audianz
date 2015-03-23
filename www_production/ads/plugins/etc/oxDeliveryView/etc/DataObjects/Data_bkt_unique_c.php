<?php



require_once MAX_PATH.'/lib/max/Dal/DataObjects/DB_DataObjectCommon.php';


class DataObjects_Data_bkt_unique_c extends DB_DataObjectCommon
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'data_bkt_unique_c';             
    public $interval_start;                  
    public $creative_id;                   
    public $zone_id;                        
    public $viewer_id;                         
    public $count;                         

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Data_bkt_unique_c',$k,$v); }

    var $defaultValues = array(
                'interval_start' => '%NO_DATE_TIME%',
                'viewer_id' => '',
                'count' => 0,
                );

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
?>
