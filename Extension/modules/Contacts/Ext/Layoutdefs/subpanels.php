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


 $layout_defs['Contacts']['subpanel_setup']['kevacampaigns'] = array( 'order' => 10, 'module' => 'K_EvaCampaigns', 'sort_order' => 'asc', 'sort_by' => 'date_modified', 'subpanel_name' => 'default', 'get_subpanel_data' => 'kevacampaigns_contacts_link', 'title_key' => 'LBL_CONTACTS_EVACAMPAIGN_SUBPANEL', 'top_buttons' => array( ) ); $layout_defs['Contacts']['subpanel_setup']['kevacampaignarticles'] = array( 'order' => 10, 'module' => 'K_EvaCampaignArticles', 'sort_order' => 'asc', 'sort_by' => 'date_modified', 'subpanel_name' => 'default', 'get_subpanel_data' => 'kevacampaignarticles_contacts_link', 'title_key' => 'LBL_CONTACTS_EVACAMPAIGNARTICLE_SUBPANEL', 'top_buttons' => array( ) ); 