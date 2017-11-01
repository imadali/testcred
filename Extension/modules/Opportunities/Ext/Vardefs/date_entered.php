<?php

$dictionary['Opportunity']['fields']['date_entered'] =
    array (
      'name' => 'date_entered',
      'vname' => 'LBL_DATE_ENTERED',
      'type' => 'datetime',
      'group' => 'created_by_name',
      'comment' => 'Date record created',
      'enable_range_search' => '1',
      'options' => 'date_range_search_dom',
      'studio' => 
      array (
        'portaleditview' => false,
      ),
      'duplicate_on_record_copy' => 'no',
      'readonly' => true,
      'massupdate' => false,
      'unified_search' => true,
      'full_text_search' => 
      array (
        'enabled' => true,
        'searchable' => true,
        'aggregations' => 
        array (
          'date_entered' => 
          array (
            'type' => 'DateRange',
          ),
        ),
        'boost' => 1,
      ),
      'audited' => false,
      'comments' => 'Date record created',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => 1,
      'merge_filter' => 'disabled',
      'calculated' => false,
    );
