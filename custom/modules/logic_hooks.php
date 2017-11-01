<?php

$hook_array['after_save'][] = Array(
    1,
    'Send Email to User when comment is added',
    'custom/modules/Activities/ActivityNotification.php',
    'ActivityNotification',
    'sendNotification'
);
?>