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


 $foundIndex = false; for($i=0; $i<count($viewdefs['ProspectLists']['base']['view']['record']['buttons']) ;$i++) { if($viewdefs['ProspectLists']['base']['view']['record']['buttons'][$i]['name'] == "main_dropdown") { $foundIndex = $i; break; } } if($foundIndex) $viewdefs['ProspectLists']['base']['view']['record']['buttons'][$foundIndex]['buttons'][] = array( 'type' => 'rowaction', 'event' => 'button:sync_to_evalanche:click', 'name' => 'edit_button', 'label' => 'Sync. to Evalanche', 'acl_action' => 'edit', );