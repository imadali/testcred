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


/*  $job_strings[] = 'syncEvalancheContacts'; $job_strings[] = 'syncEvalancheCampaigns'; function syncEvalancheContacts() { $GLOBALS['log']->info('----->Scheduler fired job of type ' . __FUNCTION__); require_once('custom/modules/K_EvalancheConnector/plugins/Contacts/Contacts.php'); $_REQUEST['plugin'] = 'Contacts'; $evalancheSyncPlugin = new K_EvalancheConnectorPlugin_Contacts(); $evalancheSyncPlugin->renderSyncCrontab(); if($GLOBALS['syncResult'] === false) { return false; } return true; } function syncEvalancheCampaigns() { $GLOBALS['log']->info('----->Scheduler fired job of type ' . __FUNCTION__); require_once('custom/modules/K_EvalancheConnector/plugins/Campaigns/Campaigns.php'); $_REQUEST['plugin'] = 'Campaigns'; $evalancheSyncPlugin = new K_EvalancheConnectorPlugin_Campaigns(); $evalancheSyncPlugin->renderSyncCrontab(); if($GLOBALS['syncResult'] === false) { return false; } return true; } */