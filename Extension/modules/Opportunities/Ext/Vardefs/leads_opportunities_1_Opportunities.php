<?php
// created: 2016-03-15 06:58:21
$dictionary["Opportunity"]["fields"]["leads_opportunities_1"] = array (
  'name' => 'leads_opportunities_1',
  'type' => 'link',
  'relationship' => 'leads_opportunities_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'side' => 'right',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'leads_opportunities_1leads_ida',
  'link-type' => 'one',
  'populate_list' => array(
	'first_name' => 'first_name',
	'last_name' => 'last_name',
   ),
);
$dictionary["Opportunity"]["fields"]["leads_opportunities_1_name"] = array (
  'name' => 'leads_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_opportunities_1leads_ida',
  'link' => 'leads_opportunities_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'full_name',
  'populate_list' => array(
	'first_name' => 'first_name',
	'last_name' => 'last_name',
   ),
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Opportunity"]["fields"]["leads_opportunities_1leads_ida"] = array (
  'name' => 'leads_opportunities_1leads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'leads_opportunities_1leads_ida',
  'link' => 'leads_opportunities_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
