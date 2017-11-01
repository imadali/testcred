<?php
$dictionary['Lead']['fields']['address_c_o']=array (
      'required' => false,
      'name' => 'address_c_o',
      'vname' => 'LBL_ADDRESS_C_O',
      'type' => 'varchar',
      'len' => '255',
      'size' => '20',
      'merge_filter' => 'enabled',
      'duplicate_merge' => 'enabled',
    );

$dictionary['Lead']['fields']['email_addrs_primary_secondary'] = array(
    'name' => 'email_addrs_primary_secondary',
    'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY_SECONDARY',
    'type' => 'email',
    'source' => 'non-db',
    'importable' => 'false',
    'massupdate' => false,
    'studio' => 'false',
);

$dictionary['Lead']['fields']['lead_with_no_open_task'] = array(
    'name' => 'lead_with_no_open_task',
    'vname' => 'LBL_LEAD_WITH_NO_OPEN_TASK',
    'type' => 'bool',
    'source' => 'non-db',
    'importable' => 'false',
    'massupdate' => false,
    'studio' => 'false',
);

$dictionary['Lead']['fields']['leads_audit_custom'] = array(
    'name' => 'leads_audit_custom',
    'type' => 'link',
    'relationship' => 'custom_leads_audit_leads',
    'module' => 'Leads_Audit',
    'bean_name' => 'Leads_Audit',
    'source' => 'non-db',
    'vname' => 'LBL_LEADS_AUDIT_CUSTOM',
    );
 ?>