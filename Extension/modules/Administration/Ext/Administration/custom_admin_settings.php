<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
    
$admin_option_defs = array();
$admin_option_defs['Administration']['email_signature'] = array(
    'Administration',
    'LBL_LINK_NAME',
    'LBL_LINK_DESCRIPTION',
    'javascript:parent.SUGAR.App.context.cstep="1";parent.SUGAR.App.router.navigate("Leads/layout/email-signature", {trigger: true});',
);

$admin_option_defs['Administration']['risk_profiling'] = array(
     'Administration',
     'LBL_RISK_PROFILING_LINK',
     'LBL_RISK_PROFILING_LINK_DESCRIPTION',
     'javascript:parent.SUGAR.App.context.cstep="1";parent.SUGAR.App.router.navigate("dotb9_risk_profiling/layout/riskprofiling-config", {trigger: true});'
);

$admin_option_defs['Administration']['lead_listener'] = array(
     'Administration',
     'LBL_LEAD_LISTENER',
     'LBL_LEAD_LISTENER_LINK_DESCRIPTION',
     'javascript:parent.SUGAR.App.context.cstep="1";parent.SUGAR.App.router.navigate("Leads/layout/lead-listener-config", {trigger: true});'
);


$admin_group_header[] = array(
    'LBL_CUSTOM_ADMIN_SETTINGS',
    '',
    false,
    $admin_option_defs,
    'LBL_CUSTOM_ADMIN_SETTINGS_CONFIGURE'
);

