<?php

// created: 2016-02-23 14:22:54
$viewdefs['Leads']['base']['filter']['status'] = array(
    'filters' => array(
        array(
            'id' => 'status',
            'name' => 'LBL_STATUS123',
            'filter_definition' => array(
                array(
                    '$or' => array(
                        array('credit_request_status_id_c' => '01_new'),
                        array('credit_request_status_id_c' => '2a_not_reached_first_round'),
                        array('credit_request_status_id_c' => '2b_not_reached_second_round'),
                        array('credit_request_status_id_c' => '2c_not_reached_third_round'),
                        array('credit_request_status_id_c' => '3a_document_sent_first_round'),
                        array('credit_request_status_id_c' => '3b_document_sent_second_round'),
                    ),
                ),
            ),
            'editable' => false, 
        ),
    ),
);
