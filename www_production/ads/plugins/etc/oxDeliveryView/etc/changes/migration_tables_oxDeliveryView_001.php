<?php



require_once(MAX_PATH.'/lib/OA/Upgrade/Migration.php');


class Migration_001 extends Migration
{

    function Migration_001()
    {
        //$this->__construct();

		$this->aTaskList_constructive[] = 'beforeAddTable__data_bkt_unique_m';
		$this->aTaskList_constructive[] = 'afterAddTable__data_bkt_unique_m';
		$this->aTaskList_constructive[] = 'beforeAddTable__data_bkt_unique_c';
		$this->aTaskList_constructive[] = 'afterAddTable__data_bkt_unique_c';


    }


	function beforeAddTable__data_bkt_unique_m()
	{
		return $this->beforeAddTable('data_bkt_unique_m');
	}

	function afterAddTable__data_bkt_unique_m()
	{
		return $this->afterAddTable('data_bkt_unique_m');
	}

	function beforeAddTable__data_bkt_unique_c()
	{
		return $this->beforeAddTable('data_bkt_unique_c');
	}

	function afterAddTable__data_bkt_unique_c()
	{
		return $this->afterAddTable('data_bkt_unique_c');
	}
}

?>
