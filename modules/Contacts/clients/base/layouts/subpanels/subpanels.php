<?php
$viewdefs['Contacts']['base']['layout']['subpanels']['components'] = array(
    array(
        'layout' => "subpanel",
        'label' => 'LBL_LEADS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'leads'
        )
    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'opportunities'
        )
    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_CASES_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'cases'
        )
    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_BUGS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'bugs'
        )
    ),
//    array(
//        'layout' => 'subpanel',Ex E
//        'label' => 'LBL_DIRECT_REPORTS_SUBPANEL_TITLE',
//        'override_subpanel_list_view' => 'subpanel-for-contacts',
//        'context' => array(
//            'link' => 'direct_reports'
//        )
//    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'documents'
        )
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_QUOTES_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'quotes',
            'collectionOptions' => array(
                'params' => array(
                    'ignore_role' => 1
                )
            )
        )
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_CONTRACTS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'contracts'
        )
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'products'
        )
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'campaigns'
        )
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_PROJECTS_SUBPANEL_TITLE',
        'context' => array(
            'link' => 'project'
        )
    ),
//    array(
//        'layout' => "subpanel",
//        'label' => 'LBL_PARTNER',
//        'context' => array(
//            'link' => 'leads_contacts_1'
//        )
//    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_PARTNER',
        'context' => array(
            'link' => 'contacts_contacts_1'
        )
    ),
    array(
        'layout' => "subpanel",
        'label' => 'LBL_PROSPECT_LIST',
        'context' => array(
            'link' => 'prospect_lists'
        )
    ),
)
;
