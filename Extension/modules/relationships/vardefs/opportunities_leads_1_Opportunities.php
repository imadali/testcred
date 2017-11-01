<?php
// created: 2016-02-19 18:56:19
$dictionary["Opportunity"]["fields"]["opportunities_leads_1"] = array (
  'name' => 'opportunities_leads_1',
  'type' => 'link',
  'relationship' => 'opportunities_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'opportunities_leads_1leads_idb',
);
$dictionary["Opportunity"]["fields"]["opportunities_leads_1_name"] = array (
  'name' => 'opportunities_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'opportunities_leads_1leads_idb',
  'link' => 'opportunities_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Opportunity"]["fields"]["opportunities_leads_1leads_idb"] = array (
  'name' => 'opportunities_leads_1leads_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'opportunities_leads_1leads_idb',
  'link' => 'opportunities_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
