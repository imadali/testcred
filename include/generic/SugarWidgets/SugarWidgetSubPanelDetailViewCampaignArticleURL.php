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


if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); class SugarWidgetSubPanelDetailViewCampaignArticleURL extends SugarWidgetField { function displayList(&$layout_def) { $sugar_config = $GLOBALS["sugar_config"]; $article_id = $layout_def['fields']['ID']; $queryResult = $GLOBALS['db']->query("select url from k_evacampaignarticles where id = '$article_id'"); $aRow = $GLOBALS['db']->fetchByAssoc($queryResult); $return = "<a href=\"".$sugar_config['K_EvalancheConnector']['Campaigns']['eva_url'].$aRow["url"]."\" target=\"_blank\">".$aRow["url"]."</a>"; return($return); } } 