<?php

$dictionary['k_connector_syncdates'] = array(

    'table' => 'k_connector_syncdates',
    'fields' => array (
        'plugin' => array(
            'name' => 'plugin',
            'vname' => 'LBL_K_CONNECTOR_SYNCDATES_PLUGIN',
            'type' => 'varchar',
            'len'    => 64
        ),
        'syncdate' => array(
            'name' => 'syncdate',
            'vname' => 'LBL_K_CONNECTOR_SYNCDATES_SYNCDATE',
            'type' => 'datetime'
        ),
        'syncunsubscriptiondate' => array(
            'name' => 'syncunsubscriptiondate',
            'vname' => 'LBL_K_CONNECTOR_UNSUBSCRIPTION_SYNCDATE',
            'type' => 'datetime',
        )
    ),
	'indices' => array (
        array('name' =>'idx_plugin', 'type' =>'index', 'fields'=>array('plugin')),
	),
	'relationships' => array (),
	'optimistic_locking' => true,
	'unified_search' => false,


);

?>