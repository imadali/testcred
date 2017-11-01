<?php

$hook_array['before_save'][] = Array(
        1,
        'populate contract date in related Lead record',
        'custom/modules/Contracts/populateContractDate.php',
        'populateContractDate',
        'populateDate'
    );

?>