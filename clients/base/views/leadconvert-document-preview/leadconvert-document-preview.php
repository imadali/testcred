<?php

$viewdefs['base']['view']['leadconvert-document-preview'] = array(
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'lead_status',
                    'label' => 'LBL_LEAD_STATUS',
                    'span' => 6,
                    'type' => 'enum',
		    'options' => 'dotb_credit_request_status_list',
                    'required' => true
                ),
                array(
                    'name' => 'lead_campaign',
                    'label' => 'LBL_LEAD_CAMPAIGN',
                    'span' => 6,
                    'type' => 'enum',
                    'options' => 'related_application_list',
                    'required' => false
                ),
            ),
        ),
    ),
);
