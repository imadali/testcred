<?php
// created: 2016-02-19 18:24:05
$dictionary["Contract"]["fields"]["contracts_leads_1"] = array (
  'name' => 'contracts_leads_1',
  'type' => 'link',
  'relationship' => 'contracts_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'contracts_leads_1leads_idb',
);
$dictionary["Contract"]["fields"]["contracts_leads_1_name"] = array (
  'name' => 'contracts_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'contracts_leads_1leads_idb',
  'link' => 'contracts_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Contract"]["fields"]["contracts_leads_1leads_idb"] = array (
  'name' => 'contracts_leads_1leads_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'contracts_leads_1leads_idb',
  'link' => 'contracts_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
