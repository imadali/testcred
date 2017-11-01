<?php
// created: 2016-02-19 18:26:19
$dictionary["dotb5_credit_history"]["fields"]["contracts_dotb5_credit_history_1"] = array (
  'name' => 'contracts_dotb5_credit_history_1',
  'type' => 'link',
  'relationship' => 'contracts_dotb5_credit_history_1',
  'source' => 'non-db',
  'module' => 'Contracts',
  'bean_name' => 'Contract',
  'side' => 'right',
  'vname' => 'LBL_CONTRACTS_DOTB5_CREDIT_HISTORY_1_FROM_DOTB5_CREDIT_HISTORY_TITLE',
  'id_name' => 'contracts_dotb5_credit_history_1contracts_ida',
  'link-type' => 'one',
);
$dictionary["dotb5_credit_history"]["fields"]["contracts_dotb5_credit_history_1_name"] = array (
  'name' => 'contracts_dotb5_credit_history_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_DOTB5_CREDIT_HISTORY_1_FROM_CONTRACTS_TITLE',
  'save' => true,
  'id_name' => 'contracts_dotb5_credit_history_1contracts_ida',
  'link' => 'contracts_dotb5_credit_history_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'name',
);
$dictionary["dotb5_credit_history"]["fields"]["contracts_dotb5_credit_history_1contracts_ida"] = array (
  'name' => 'contracts_dotb5_credit_history_1contracts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_DOTB5_CREDIT_HISTORY_1_FROM_DOTB5_CREDIT_HISTORY_TITLE_ID',
  'id_name' => 'contracts_dotb5_credit_history_1contracts_ida',
  'link' => 'contracts_dotb5_credit_history_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
