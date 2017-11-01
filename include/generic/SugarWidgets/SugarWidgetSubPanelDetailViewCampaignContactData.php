<?php

/*********************************************************************************
*
* DO NOT MODIFY THIS FILE!
*
* This file is part of KINAMU EvalancheConnector, an enhanced module for Sugar (TM). 
* Sugar (TM) is developed by SugarCRM Inc. (C).
* 
* KINAMU EvalancheConnector is licensed software and may only be used in alignment with the 
* End User License Agreement (EULA) received with this software. This software is copyrighted 
* and may not be further distributed without any written consent of 
* KINAMU Business Solutions GmbH (C).
* 
* KINAMU EvalancheConnector: Copyright (C) 2016 by KINAMU Business Solutions GmbH. All rights reserved.
*
* You can contact KINAMU Business Solutions GmbH via email at office@kinamu.com
*
********************************************************************************/


if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); class SugarWidgetSubPanelDetailViewCampaignContactData extends SugarWidgetField { function displayList(&$layout_def) { $current_language = $GLOBALS["current_language"]; if($layout_def['module'] == 'Contacts' || $layout_def['module'] == 'Leads') { $contact_id = $layout_def['fields']['ID']; $campaign_id = $_REQUEST['record']; } else { $contact_id = $_REQUEST['record']; $campaign_id = $layout_def['fields']['ID']; } if(!$layout_def['target_record_key']) { $layout_def['target_record_key'] = 'opened'; } $queryResult = $GLOBALS['db']->query("select ".$layout_def['target_record_key']." wert from k_evacampaigns_contacts where ". "contact_id = '$contact_id' and k_evacampaign_id = '$campaign_id'"); if(!($aRow = $GLOBALS['db']->fetchByAssoc($queryResult))) { $queryResult = $GLOBALS['db']->query("select ".$layout_def['target_record_key']." wert from k_evacampaigns_leads where ". "lead_id = '$contact_id' and k_evacampaign_id = '$campaign_id'"); $aRow = $GLOBALS['db']->fetchByAssoc($queryResult); } if(stristr($current_language,"de_") || stristr($current_language,"ge_")) $return = ($aRow['wert']?'ja':'nein'); else $return = ($aRow['wert']?'yes':'no'); return($return); } } 