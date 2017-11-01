<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


    $admin_option_defs = array();
   
    $admin_option_defs['Administration']['risk_profiling'] = array('Administration', 'LBL_RISK_PROFILING_LINK', 'LBL_RISK_PROFILING_LINK_DESCRIPTION', 'javascript:parent.SUGAR.App.context.cstep="1";parent.SUGAR.App.router.navigate("dotb9_risk_profiling/layout/riskprofiling-config", {trigger: true});');
    $admin_group_header[] = array('LBL_RISK_PROFILING_HEADER', '', false, $admin_option_defs, 'LBL_RISK_PROFILING_DESCRIPTION');
?>