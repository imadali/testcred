<?php

$dictionary['Note']['fields']['dotb_flag'] = array(
	'name' => 'dotb_flag',
	'label' => 'LBL_DOTB_FLAG',
	'type' => 'int',
	'module' => 'Notes',
	'default' => '0',
	// 'max_size' => 255,
	'required' => false, 
	'reportable' => true, 
	'audited' => false, 
	'importable' => 'true', 
	'duplicate_merge' => false,
        'massupdate' => true,
    
);

$dictionary['Note']['fields']['error_message'] = array(
    'name' => 'error_message',
    'vname' => 'LBL_ERROR_MESSAGE',
    'type' => 'text',
    'reportable' => true,
);

?>
