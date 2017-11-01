<?php

	$hook_array['after_save'][] = Array(      
        2,
       
        'Update address months in related Lead record',
       
        'custom/modules/dot10_addresses/updateLeadsAddressMonths.php',
       
        'updateLeadsAddressMonths',
       
        'updateMonthsInLeads'
    );
	
	$hook_array['after_relationship_delete'][] = Array(      
        5,
       
        'Update address months in related Lead record',
       
        'custom/modules/dot10_addresses/updateLeadsAddressMonths.php',
       
        'updateLeadsAddressMonths',
       
        'updateMonthsInLeadsWhenAddressDeleted'
    );
	
	// when lead is created from contact
	$hook_array['after_relationship_add'][] = Array(      
        5,
       
        'Update address months in related Lead record',
       
        'custom/modules/dot10_addresses/updateLeadsAddressMonths.php',
       
        'updateLeadsAddressMonths',
       
        'updateMonthsInLeadsWhenAddressDeleted'
    );

	
?>