<?php


$viewdefs['Contacts']['base']['view']['leadconvert-tracking'] = array(
	'panels' => array(
		array(
			'name' => 'panel_doc_track',
			'label' => 'LBL_PANEL_1',
			'fields' => array(
				array(
					'name' => 'category',
					'label' => 'LBL_CATEGORY_LEAD_CONVERT_PLURAL',
					'span' => 6,
					'type' => 'enum',
					'isMultiSelect' => true,
					'required' => false,
					'options' => 'dotb_document_category_list'
				),
                            	
			)
		),
                
                array(
                  'name' => 'panel_doc_track_category',
                  'label' => 'LBL_PANEL_2',
                  'fields' => array(
                            array(
                                    'name' => 'category',
                                    'label' => 'LBL_CATEGORY_LEAD_CONVERT',
                                    'span' => 4,
                                    'type' => 'enum',
                                    'options' => 'dotb_document_category_list',
                                    'readonly' => true
                                ),  
                            array(
                                    'name' => 'month',
                                    'label' => 'LBL_MONTH_LEAD_CONVERT',
                                    'span' => 2,
                                    'type' => 'enum',
                                    'isMultiSelect' => true,
                                    'options' => 'document_month_list',
                            ),
                            array(
                                    'name' => 'status',
                                    'label' => 'LBL_STATUS_LEAD_CONVERT',
                                    'span' => 2,
                                    'type' => 'enum',
                                    'required' => false,
                                    'options' => 'status_list',
                                    'default_value' => 'fehlt', 
                            ),
                            array(
                                    'name' => 'description',
                                    'label' => 'LBL_DESCRIPTION_LEAD_CONVERT',
                                    'span' => 3,
                                    'type' => 'text',
                            ),  
                      ), 
                  ),  
            
                array(
			'name' => 'manual_panel_doc_track',
			'label' => 'LBL_PANEL_1',
			'fields' => array(
				array(
					'name' => 'category',
					'label' => 'LBL_CATEGORY_LEAD_CONVERT',
					'span' => 4,
					'type' => 'varchar',
				),
				array(
					'name' => 'month',
					'label' => 'LBL_MONTH_LEAD_CONVERT',
					'span' => 2,
					'type' => 'enum',
                                        'isMultiSelect' => true,
					'options' => 'document_month_list',
				),
				array(
					'name' => 'status',
					'label' => 'LBL_STATUS_LEAD_CONVERT',
					'span' => 2,
					'type' => 'enum',
					'required' => true,
					'options' => 'status_list',
					'default_value' => 'fehlt', 
				),
				array(
					'name' => 'description',
					'label' => 'LBL_DESCRIPTION_LEAD_CONVERT',
					'span' => 3,
					'type' => 'text',
				),
			)
		),  
	)
);

