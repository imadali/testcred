<?php

$dictionary['dotb7_document_tracking']['fields']['month'] = array(
	'name' => 'month',
	'label' => 'LBL_MONTH',
	'type' => 'multienum',
	'module' => 'dotb7_document_tracking',
	'options' => 'document_month_list',
	'default_value' => '',
	'mass_update' => false,
	'required' => false,
	'reportable' => true,
	'audited' => true,
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'massupdate' => false,
	'duplicate_merge_dom_value' => '0',
	'merge_filter' => 'disabled',
	'unified_search' => false,
	'calculated' => false,
	'dependency' => false,
        'isMultiSelect' => true
);

?>