<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
$module_name                                               = 'Documents';
$viewdefs[$module_name]['base']['view']['subpanel-create'] = array(
	'panels' => array(
		array(
			'name' => 'panel_header',
			'label' => 'LBL_PANEL_1',
			'fields' => array(
				array(
					array(
						'name' => 'document_name',
						'label' => 'LBL_DOCUMENT_NAME',
						'span' => 6,
                                                'required' => false,
					),
					array(
						'name' => 'filename',
						'label' => 'LBL_FILENAME',
						'span' => 6,
						'required' => false,
					),
						/* array(
						'name' => 'active_date',
						'label' => 'LBL_ACTIVE_DATE',
						'span' => 3,
						), */
				),
			)
		),
		array(
			'name' => 'panel_doc_track',
			'label' => 'LBL_PANEL_1',
			'fields' => array(
				array(
					'name' => 'category',
					'label' => 'LBL_CATEGORY_PLURAL',
					'span' => 6,
					'type' => 'enum',
					'isMultiSelect' => true,
					'required' => false,
					'options' => 'dotb_document_category_list'
				),
				array(
					'name' => 'category',
					'label' => 'LBL_CATEGORY',
					'span' => 4,
					'type' => 'enum',
					'options' => 'dotb_document_category_list',
					'readonly' => true
				),
				array(
					'name' => 'month',
					'label' => 'LBL_MONTH',
					'span' => 2,
					'type' => 'enum',
                                        'isMultiSelect' => true,
					'options' => 'document_month_list',
				),
				array(
					'name' => 'status',
					'label' => 'LBL_STATUS',
					'span' => 2,
					'type' => 'enum',
					'required' => true,
					'options' => 'status_list',
					'default_value' => 'fehlt', 
				),
 				/*array(
					'name' => 'documents_checked',
					'label' => 'LBL_DOCUMENT_CHECKED',
					'span' => 1,
					'type' => 'bool',
				),
				array(
					'name' => 'documents_recieved',
					'label' => 'LBL_DOCUMENT_RECIEVED',
					'span' => 1,
					'type' => 'bool',
				), */
				array(
					'name' => 'description',
					'label' => 'LBL_DESCRIPTION',
					'span' => 3,
					'type' => 'text',
				),
			)
		),
                array(
			'name' => 'manual_panel_doc_track',
			'label' => 'LBL_PANEL_1',
			'fields' => array(
				array(
					'name' => 'category',
					'label' => 'LBL_CATEGORY',
					'span' => 4,
					'type' => 'varchar',
				),
				array(
					'name' => 'month',
					'label' => 'LBL_MONTH',
					'span' => 2,
					'type' => 'enum',
                                        'isMultiSelect' => true,
					'options' => 'document_month_list',
				),
				array(
					'name' => 'status',
					'label' => 'LBL_STATUS',
					'span' => 2,
					'type' => 'enum',
					'required' => false,
					'options' => 'status_list',
					'default_value' => 'fehlt', 
				),
				array(
					'name' => 'description',
					'label' => 'LBL_DESCRIPTION',
					'span' => 3,
					'type' => 'text',
				),
			)
		),
	)
);
