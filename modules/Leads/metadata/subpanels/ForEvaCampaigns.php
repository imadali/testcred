<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/



$subpanel_layout = array(
	'top_buttons' => array(
		// array('widget_class' => 'SubPanelTopCreateButton'),
		// array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Leads'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'first_name'=>array(
			'name'=>'first_name',
			'usage' => 'query_only',
		),
		'last_name'=>array(
			'name'=>'last_name',
		 	'usage' => 'query_only',
		),
		'salutation'=>array(
			'name'=>'salutation',
		 	'usage' => 'query_only',
		),
		'name'=>array(
			'name'=>'name',		
			'vname' => 'LBL_LIST_NAME',
            'sort_by' => 'last_name',
            'sort_order' => 'asc',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'module' => 'Leads',
			'width' => '23%',
		),
		'account_name'=>array(
			'name'=>'account_name',
		 	'vname' => 'LBL_LIST_ACCOUNT_NAME',
			'width' => '22%',
			'sortable'=>false,
		),
		'account_id'=>array(
			'usage'=>'query_only',
			
		),
		'email1'=>array(
			'name'=>'email1',		
			'vname' => 'LBL_LIST_EMAIL',
			'widget_class' => 'SubPanelEmailLink',
			'width' => '20%',
			'sortable'=>false,
		),
                'opened'=>array(
                        'name'=>'opened',
                        'vname'=>'LBL_IMPRESSIONS',
                        'widget_class' => 'SubPanelDetailViewCampaignContactData',
                        'target_record_key' => 'opened',
                        'width' => '10%',
                        'sortable' => false,
                        'force_exists' => true,
                ),
                'unsubscribed'=>array(
                        'name'=>'unsubscribed',
                        'vname'=>'LBL_UNSUBSCRIBES',
                        'widget_class' => 'SubPanelDetailViewCampaignContactData',
                        'target_record_key' => 'unsubscribed',
                        'width' => '10%',
                        'sortable' => false,
                        'force_exists' => true,
                ),
		'readfactor'=>array(
			'name'=>'readfactor',		
			'vname' => 'LBL_READFACTOR',
			'width' => '10%',
			'sortable'=>true,
		),
	),
);		
?>