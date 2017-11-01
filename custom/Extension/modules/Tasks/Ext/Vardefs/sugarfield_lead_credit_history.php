<?php

$dictionary['Task']['fields']['leads_credit_history'] = array(
   'name' =>  'leads_credit_history',
   'vname' => 'LBL_LEADS_CREDIT_HISTORY',
   'source' => 'non-db', 
   'type' => 'bool',
   'massupdate' => false,
);

$dictionary['Task']['fields']['leads_deltavista_score'] = array(
   'name' =>  'leads_deltavista_score',
   'vname' => 'LBL_LEADS_DELTA_VISTA_SCORE',
   'source' => 'non-db', 
   'type' => 'int',
   'massupdate' => false,
);

$dictionary['Task']['fields']['secondary_teams_custom'] = array(
   'name' =>  'secondary_teams_custom',
   'vname' => 'LBL_PRIMARY_SECONDARY',
   'source' => 'non-db', 
   'type' => 'enum',
   'massupdate' => false,
   'function' => 'getAllTeams',
   'function_bean' => 'dot10_addresses',
);

$dictionary['Task']['fields']['till_today']=array (
    'required' => false,
    'name' => 'till_today',
    'vname' => 'LBL_TILL_TODAY',
    'source' => 'non-db',
    'type' => 'bool',
    'massupdate' => '0',
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => false,
);
