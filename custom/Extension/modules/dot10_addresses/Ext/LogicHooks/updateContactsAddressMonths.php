<?php

	$hook_array['after_save'][] = Array(      
        3,
       
        'Update address months in related Contact record',
       
        'custom/modules/dot10_addresses/updateContactsAddressMonths.php',
       
        'updateContactsAddressMonths',
       
        'updateMonthsInContacts'
    );
	
	$hook_array['after_relationship_delete'][] = Array(      
        6,
       
        'Update address months in related Contact record',
       
        'custom/modules/dot10_addresses/updateContactsAddressMonths.php',
       
        'updateContactsAddressMonths',
       
        'updateMonthsInContactsWhenAddressDeleted'
    );

	
?>