<?php
// created: 2015-07-10 15:09:18
$layout_defs["Contacts"]["subpanel_setup"]['contacts_dotb6_contact_activities'] = array(
    'order' => 100,
    'module' => 'dotb6_contact_activities',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'title_key' => 'LBL_CONTACTS_DOTB6_CONTACT_ACTIVITIES_FROM_DOTB6_CONTACT_ACTIVITIES_TITLE',
    'get_subpanel_data' => 'contacts_dotb6_contact_activities',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect'
        ),
        1 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate'
        )
    )
);
