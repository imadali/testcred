<?php
$hook_array['before_save'][] = Array(
        1,
        'Saving all email addresses in text field to access in global search',
    
        'custom/modules/Contacts/contactEmailAddresses.php',
    
        'contactEmailAddresses',
    
        'syncEmailAddresses'
    
    );


?>