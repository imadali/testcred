<?php
// created: 2016-02-19 18:56:19
$dictionary["Lead"]["fields"]["opportunities_leads_1"] = array (
  'name' => 'opportunities_leads_1',
  'type' => 'link',
  'relationship' => 'opportunities_leads_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_leads_1opportunities_ida',
);
$dictionary["Lead"]["fields"]["opportunities_leads_1_name"] = array (
  'name' => 'opportunities_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportunities_leads_1opportunities_ida',
  'link' => 'opportunities_leads_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["opportunities_leads_1opportunities_ida"] = array (
  'name' => 'opportunities_leads_1opportunities_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_LEADS_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'opportunities_leads_1opportunities_ida',
  'link' => 'opportunities_leads_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
