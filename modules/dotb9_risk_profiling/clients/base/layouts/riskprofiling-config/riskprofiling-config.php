<?php

$module_name = 'dotb9_risk_profiling';
$viewdefs[$module_name]['base']['layout']['riskprofiling-config'] = array(
	'type' => 'simple',
	'components' => array(
		array(
			'view' => 'riskprofiling-config-view',
		),
	),
);
	
?>