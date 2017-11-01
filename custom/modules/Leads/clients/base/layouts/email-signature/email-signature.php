<?php

$module_name = 'Leads';
$viewdefs[$module_name]['base']['layout']['email-signature'] = array(
	'type' => 'simple',
	'components' => array(
		array(
			'view' => 'email-signature',
		),
	),
);
