<?php
$hook_array['before_save'][] = Array(
	1,
	'If add to history check box is checked add a new address record and link it to Contact',

	'custom/modules/Contacts/addAddressHistory.php',

	'addAddressHistory',

	'addAddressToHistory'
    
);

?>