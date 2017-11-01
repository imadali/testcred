<?php
// created: 2016-02-19 18:24:05
$dictionary["Lead"]["fields"]["contracts_leads_1"] = array (
  'name' => 'contracts_leads_1',
  'type' => 'link',
  'relationship' => 'contracts_leads_1',
  'source' => 'non-db',
  'module' => 'Contracts',
  'bean_name' => 'Contract',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_CONTRACTS_TITLE',
  'id_name' => 'contracts_leads_1contracts_ida',
);
$dictionary["Lead"]["fields"]["contracts_leads_1_name"] = array (
  'name' => 'contracts_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_CONTRACTS_TITLE',
  'save' => true,
  'id_name' => 'contracts_leads_1contracts_ida',
  'link' => 'contracts_leads_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["contracts_leads_1contracts_ida"] = array (
  'name' => 'contracts_leads_1contracts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_LEADS_1_FROM_CONTRACTS_TITLE_ID',
  'id_name' => 'contracts_leads_1contracts_ida',
  'link' => 'contracts_leads_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
