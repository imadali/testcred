<?php
$dependencies['Opportunities']['status_bank_application_kk'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('provider_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'status_bank_application_kk',
            'label' => 'LBL_STATUS_BANK_APP',
            'value' => 'equal($provider_id_c, "cembra")',
            ),
        ),
    ),
);
$dependencies['Opportunities']['customer_request_kk'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('provider_id_c','status_bank_application_kk'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'customer_request_kk',
            'label' => 'LBL_STATUS_BANK_APP',
            'value' => 'and(equal($provider_id_c, "cembra"),equal($status_bank_application_kk, "granted"))',
            ),
        ),
    ),
);
/**
* CRED-766 : Credit Card Limit field visible if Granted in Status Bank Application KK field
*/
$dependencies['Opportunities']['credit_card_limit'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('status_bank_application_kk'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'credit_card_limit',
            'label' => 'LBL_CREDIT_CARD_LIMIT',
            'value' => 'equal($status_bank_application_kk, "granted")',
            ),
        ),
    ),
);

?>