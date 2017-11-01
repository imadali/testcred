<?php
 // created: 2016-02-19 18:30:38
$layout_defs["Leads"]["subpanel_setup"]['leads_dotb5_credit_history_1'] = array (
  'order' => 100,
  'module' => 'dotb5_credit_history',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_DOTB5_CREDIT_HISTORY_1_FROM_DOTB5_CREDIT_HISTORY_TITLE',
  'get_subpanel_data' => 'leads_dotb5_credit_history_1',
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
