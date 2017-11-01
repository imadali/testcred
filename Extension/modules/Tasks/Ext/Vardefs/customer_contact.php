<?php
$dictionary['Task']['fields']['customer_contact_name'] = array (
      'required' => false,
      'source' => 'non-db',
      'name' => 'customer_contact_name',
      'vname' => 'LBL_CUSTOMER_CONTACT_NAME',
      'type' => 'relate',
      'massupdate' => true,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'len' => '255',
      'size' => '20',
      'id_name' => 'customer_contact_id',
      //'ext2' => 'Users',
      'module' => 'Users',
      'rname' => 'name',
      'quicksearch' => 'enabled',
      'studio' => 'visible',
    );
$dictionary['Task']['fields']['customer_contact_id'] = array (
      'required' => false,
      //'source' => 'custom_fields',
      'name' => 'customer_contact_id',
      'vname' => 'LBL_CUSTOMER_CONTACT_ID',
      'type' => 'id',
      'massupdate' => false,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'len' => '36',
      'size' => '20',
    );