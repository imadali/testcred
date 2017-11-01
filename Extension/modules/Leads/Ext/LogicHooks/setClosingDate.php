<?php
$hook_array['before_save'][] = Array(
    12,
    'When the status of the Lead is set to 00_pendent_geschlossen update closing_date_c field' ,
    'custom/modules/Leads/setClosingDate.php',
    'setClosingDate',
    'setClosingDateForLead'
);


?>