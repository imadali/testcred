<?php

/**
 * CRED-914: Linking Customer Center with SugarCRM@credaris
 */
$hook_array['before_save'][] = Array(
    10,
    'Updating the lead record status at customer center if status is 00 or 11',
    'custom/modules/Leads/updateCustomerCenterRecord.php',
    'updateCustomerCenterRecord',
    'updateCCRecord'
);
