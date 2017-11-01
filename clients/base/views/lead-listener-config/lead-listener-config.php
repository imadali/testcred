<?php

$viewdefs['base']['view']['lead-listener-config'] = array(
    'panels' => array(
        array(
            'name' => 'panel_monday_1',
            'fields' => array(
                array(
                    'name' => 'day_monday_1',
                    'vname' => 'Day',
                    'type' => 'enum',                    
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'default' => '1',
                ),
                array(
                    'name' => 'time_from_monday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%',
                ),
                array(
                    'name' => 'time_to_monday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%',
                ),
                array(
                    'name' => 'emails_monday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%',
                ),
                array(
                    'name' => 'enable_monday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_monday_2',
            'fields' => array(
                array(
                    'name' => 'day_monday_2',
                    'vname' => 'Day',
                    'type' => 'enum',                    
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'default' => '1',
                ),
                array(
                    'name' => 'time_from_monday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%',
                ),
                array(
                    'name' => 'time_to_monday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%',
                ),
                array(
                    'name' => 'emails_monday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%',
                ),
                array(
                    'name' => 'enable_monday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_tuesday_1',
            'fields' => array(
                array(
                    'name' => 'day_tuesday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_tuesday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_tuesday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_tuesday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_tuesday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_tuesday_2',
            'fields' => array(
                array(
                    'name' => 'day_tuesday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_tuesday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_tuesday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_tuesday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_tuesday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_wednesday_1',
            'fields' => array(
                array(
                    'name' => 'day_wednesday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_wednesday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_wednesday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_wednesday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_wednesday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_wednesday_2',
            'fields' => array(
                array(
                    'name' => 'day_wednesday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_wednesday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_wednesday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_wednesday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_wednesday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_thursday_1',
            'fields' => array(
                array(
                    'name' => 'day_thursday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_thursday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_thursday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_thursday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_thursday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
            
        ),
        array(
            'name' => 'panel_thursday_2',
            'fields' => array(
                array(
                    'name' => 'day_thursday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_thursday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_thursday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_thursday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_thursday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
            
        ),
        array(
            'name' => 'panel_friday_1',
            'fields' => array(
                array(
                    'name' => 'day_friday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_friday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_friday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_friday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_friday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_friday_2',
            'fields' => array(
                array(
                    'name' => 'day_friday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_friday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_friday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_friday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_friday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_saturday_1',
            'fields' => array(
                array(
                    'name' => 'day_saturday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_saturday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_saturday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_saturday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_saturday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_saturday_2',
            'fields' => array(
                array(
                    'name' => 'day_saturday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_saturday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_saturday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_saturday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_saturday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_sunday_1',
            'fields' => array(
                array(
                    'name' => 'day_sunday_1',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_sunday_1',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_sunday_1',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_sunday_1',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_sunday_1',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
        array(
            'name' => 'panel_sunday_2',
            'fields' => array(
                array(
                    'name' => 'day_sunday_2',
                    'vname' => 'Day',
                    'type' => 'enum',
                    'default' => 1,
                    'options' => 'dom_cal_day_of_week',
                    'width' => '20%',
                    'value' => 'Monday'
                ),
                array(
                    'name' => 'time_from_sunday_2',
                    'vname' => 'From',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'time_to_sunday_2',
                    'vname' => 'To',
                    'type' => 'enum',
                    'default' => '00:00',
                    'options' => 'employee_time_list',
                    'width' => '20%'
                ),
                array(
                    'name' => 'emails_sunday_2',
                    'vname' => 'Email',
                    'type' => 'varchar',
                    'default' => '',
                    'width' => '94%'
                ),
                array(
                    'name' => 'enable_sunday_2',
                    'vname' => 'Enable',
                    'type' => 'bool',
                    'default' => 0,
                ),
                
            ),
        ),
    )
);