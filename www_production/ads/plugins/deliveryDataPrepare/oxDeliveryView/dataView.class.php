<?php



require_once LIB_PATH . '/Plugin/Component.php';


class Plugins_DeliveryDataPrepare_oxDeliveryView_DataView extends OX_Component
{
    function getDependencies()
    {
        return array(
             'deliveryDataPrepare:oxDeliveryView:dataView' => array(
                'deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon'
            )
        );
    }
}

?>
