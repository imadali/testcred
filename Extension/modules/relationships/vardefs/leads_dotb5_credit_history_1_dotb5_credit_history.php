<?php
// created: 2016-02-19 18:30:38
$dictionary["dotb5_credit_history"]["fields"]["leads_dotb5_credit_history_1"] = array (
  'name' => 'leads_dotb5_credit_history_1',
  'type' => 'link',
  'relationship' => 'leads_dotb5_credit_history_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'side' => 'right',
  'vname' => 'LBL_LEADS_DOTB5_CREDIT_HISTORY_1_FROM_DOTB5_CREDIT_HISTORY_TITLE',
  'id_name' => 'leads_dotb5_credit_history_1leads_ida',
  'link-type' => 'one',
);
$dictionary["dotb5_credit_history"]["fields"]["leads_dotb5_credit_history_1_name"] = array (
  'name' => 'leads_dotb5_credit_history_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_DOTB5_CREDIT_HISTORY_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_dotb5_credit_history_1leads_ida',
  'link' => 'leads_dotb5_credit_history_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["dotb5_credit_history"]["fields"]["leads_dotb5_credit_history_1leads_ida"] = array (
  'name' => 'leads_dotb5_credit_history_1leads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_DOTB5_CREDIT_HISTORY_1_FROM_DOTB5_CREDIT_HISTORY_TITLE_ID',
  'id_name' => 'leads_dotb5_credit_history_1leads_ida',
  'link' => 'leads_dotb5_credit_history_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
