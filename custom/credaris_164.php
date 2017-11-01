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


 $language = $_SESSION["authenticated_user_language"]; if(strtolower($language) != "de_de" && strtolower($language) != "ge_ge") { $language = "en_us"; } switch($_REQUEST["type"]) { case "statpie": include "custom/include/jquerystats/running/pieEvaCampaign.php"; break; case "statclicks": include "custom/include/jquerystats/running/pieEvaCampaignClicks.php"; break; case "activity": include "custom/include/jquerystats/running/evaCampaignActivity.php"; break; }