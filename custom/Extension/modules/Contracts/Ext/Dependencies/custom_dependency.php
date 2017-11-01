<?php
$dependencies['Contracts']['lead_first_name'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('payment_option_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'lead_first_name',
            'value' => 'equal($payment_option_id_c, "CH")',
            ),
        ),
    ),
);