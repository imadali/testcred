<?php

/**
 * CRED-970 : Promotion of Leads in Status 00 to 11 after 30 days
 */
$entry_point_registry['updateLeadStatus'] = array(
    'file' => 'custom/updateLeadStatusTo11.php',
    'auth' => true
);

?>