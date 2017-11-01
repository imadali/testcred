<?php
// created: 2016-02-22 11:05:07
$dictionary["dotb9_risk_profiling"]["fields"]["accounts_dotb9_risk_profiling_1"] = array (
  'name' => 'accounts_dotb9_risk_profiling_1',
  'type' => 'link',
  'relationship' => 'accounts_dotb9_risk_profiling_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_DOTB9_RISK_PROFILING_1_FROM_DOTB9_RISK_PROFILING_TITLE',
  'id_name' => 'accounts_dotb9_risk_profiling_1accounts_ida',
  'link-type' => 'one',
);
$dictionary["dotb9_risk_profiling"]["fields"]["accounts_dotb9_risk_profiling_1_name"] = array (
  'name' => 'accounts_dotb9_risk_profiling_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOTB9_RISK_PROFILING_1_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_dotb9_risk_profiling_1accounts_ida',
  'link' => 'accounts_dotb9_risk_profiling_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["dotb9_risk_profiling"]["fields"]["accounts_dotb9_risk_profiling_1accounts_ida"] = array (
  'name' => 'accounts_dotb9_risk_profiling_1accounts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOTB9_RISK_PROFILING_1_FROM_DOTB9_RISK_PROFILING_TITLE_ID',
  'id_name' => 'accounts_dotb9_risk_profiling_1accounts_ida',
  'link' => 'accounts_dotb9_risk_profiling_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
