<?php
/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['leads_deltavista_score'] = array(
   'name' =>  'leads_deltavista_score',
   'vname' => 'LBL_LEADS_DELTA_VISTA_SCORE',
   'source' => 'non-db', 
   'type' => 'int',
   'massupdate' => false,
);

$dictionary['Call']['fields']['secondary_teams_custom'] = array(
   'name' =>  'secondary_teams_custom',
   'vname' => 'LBL_PRIMARY_SECONDARY',
   'source' => 'non-db', 
   'type' => 'enum',
   'massupdate' => false,
   'function' => 'getAllTeams',
   'function_bean' => 'dot10_addresses',
);