<?php


$viewdefs['Leads']['base']['view']['email-signature'] = array(
	'panels' => array(
                array(
                  'name' => 'custom_salutation',
                  'label' => 'LBL_PANEL_1',
                  'fields' => array(
                        array(
                                'name' => 'signature_saluation_de',
                                'label' => 'LBL_SALUATION_GERMAN',
                                'span' => 6,
                                'type' => 'varchar',
                            ),  
                        array(
                                'name' => 'signature_saluation_fr',
                                'label' => 'LBL_SALUATION_FRENCH',
                                'span' => 6,
                                'type' => 'varchar',
                        ),
                        array(
                                'name' => 'signature_saluation_it',
                                'label' => 'LBL_SALUATION_ITALIAN',
                                'span' => 6,
                                'type' => 'varchar',
                        ),
                        array(
                                'name' => 'signature_saluation_en',
                                'label' => 'LBL_SALUATION_ENGLISH',
                                'span' => 6,
                                'type' => 'varchar',
                        ),  
                    ), 
                ), 
                array(
                  'name' => 'custom_payoff',
                  'label' => 'LBL_PANEL_2',
                  'fields' => array(
                        array(
                                'name' => 'signature_payoff_de',
                                'label' => 'LBL_PAYOFF_GERMAN',
                                'span' => 6,
                                'type' => 'varchar',
                            ),  
                        array(
                                'name' => 'signature_payoff_fr',
                                'label' => 'LBL_PAYOFF_FRENCH',
                                'span' => 6,
                                'type' => 'varchar',
                        ),
                        array(
                                'name' => 'signature_payoff_it',
                                'label' => 'LBL_PAYOFF_ITALIAN',
                                'span' => 6,
                                'type' => 'varchar',
                        ),
                        array(
                                'name' => 'signature_payoff_en',
                                'label' => 'LBL_PAYOFF_ENGLISH',
                                'span' => 6,
                                'type' => 'varchar',
                        ),  
                    ), 
                ), 
	)
);

