<?php
// created: 2016-02-25 09:36:19
$dictionary["Opportunity"]["fields"]["accounts_opportunities_1"] = array (
  'name' => 'accounts_opportunities_1',
  'type' => 'link',
  'relationship' => 'accounts_opportunities_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_ACCOUNTS_OPPORTUNITIES_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'accounts_opportunities_1accounts_ida',
);

$dictionary['Opportunity']['fields']['opportunities_audit_custom'] = array(
    'name' => 'opportunities_audit_custom',
    'type' => 'link',
    'relationship' => 'custom_opportunities_audit_opportunities',
    'module' => 'Opportunities_Audit',
    'bean_name' => 'Opportunities_Audit',
    'source' => 'non-db',
    'vname' => 'LBL_OPPORTUNITIES_AUDIT_CUSTOM',
);