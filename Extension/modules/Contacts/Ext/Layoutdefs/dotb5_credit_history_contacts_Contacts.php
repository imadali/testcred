<?php
 // created: 2015-07-10 15:09:18
$layout_defs["Contacts"]["subpanel_setup"]['dotb5_credit_history_contacts'] = array (
  'order' => 100,
  'module' => 'dotb5_credit_history',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_DOTB5_CREDIT_HISTORY_CONTACTS_FROM_DOTB5_CREDIT_HISTORY_TITLE',
  'get_subpanel_data' => 'dotb5_credit_history_contacts',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
  ),
);
