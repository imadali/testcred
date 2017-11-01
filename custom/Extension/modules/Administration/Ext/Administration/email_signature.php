<?php

$admin_option_defs = array();
    $admin_option_defs['Administration']['email_signature'] = array(
        'Administration',
        'LBL_LINK_NAME',
        'LBL_LINK_DESCRIPTION',
        'javascript:parent.SUGAR.App.context.cstep="1";parent.SUGAR.App.router.navigate("Leads/layout/email-signature", {trigger: true});',
    );

    $admin_group_header[] = array(
        'LBL_SECTION_HEADER',
        '',
        false,
        $admin_option_defs, 
        'LBL_SECTION_DESCRIPTION'
    );