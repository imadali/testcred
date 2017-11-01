<?php

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
require_once('clients/base/api/RelateApi.php');

class customHistoryApi extends RelateApi {

    /**
     * This is the list of allowed History Modules
     * @var array
     */
    protected $moduleList = array(
        //'meetings' => 'Meetings',
        //'calls' => 'Calls',
        'notes' => 'Notes',
        'tasks' => 'Tasks',
        'emails' => 'Emails',
    );

    /**
     * filters per module for list requests
     * @var array
     */
    protected $moduleFilters = array(
        'Calls' => array(
        /* array(
          'status' => array(
          '$in' => array(
          'Not Held',
          'Held'
          ),
          ),
          ), */
        ),
        'Meetings' => array(
        /* array(
          'status' => array(
          '$in' => array(
          'Not Held',
          'Held'
          ),
          ),
          ), */
        ),
        'Tasks' => array(
        /*  array(
          'status' => array(
          '$in' => array(
          'open',
          ),
          ),
          ), */
        )
    );

    /**
     * This is the list of valid fields that should be on each select
     * @var array
     */
    protected $validFields = array(
        'name',
        'id',
        'status',
        'description',
        'date_entered',
        'date_modified',
        'related_contact',
        'assigned_user_name',
        'assigned_user_id',
        'date_due',
        'team_id',
        'team_name',
        'created_by',
    );

    public function registerApiRest() {
        return array(
            'recordListView' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', 'historical_summary'),
                'pathVars' => array('module', 'record', ''),
                //'path' => array('<module>', '?', 'link', 'historical_summary','?'),
                //'pathVars' => array('module', 'record','','','preview'),
                'method' => 'filterModuleList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Get the history records for a specific record',
                'longHelp' => 'include/api/help/history_filter.html',
                'exceptions' => array(
                    // Thrown in filterList
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'previewListView' => array(
                'reqType' => 'GET',
                //'path' => array('<module>', '?', 'link', 'historical_summary'),
                //'pathVars' => array('module', 'record', ''),
                'path' => array('<module>', '?', 'link', 'preview_activities', '?', '?'),
                'pathVars' => array('module', 'record', '', '', 'offset', 'orderBy'),
                'method' => 'filterModuleList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Get the history records for a specific record',
                'longHelp' => 'include/api/help/history_filter.html',
                'exceptions' => array(
                    // Thrown in filterList
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
        );
    }

    public function filterModuleList(ServiceBase $api, array $args, $acl = 'list') {
        if (isset($args['view']) || isset($args['orderBy'])) {
            if (!empty($args['module_list'])) {
                $module_list = explode(',', $args['module_list']);
                foreach ($this->moduleList as $link_name => $module) {
                    if (!in_array($module, $module_list)) {
                        unset($this->moduleList[$link_name]);
                    }
                }
            }

            // if the module list is empty then someone passed in bad modules for the history
            if (empty($this->moduleList)) {
                throw new SugarApiExceptionInvalidParameter("Module List is empty, must contain: Meetings, Calls, Notes, Tasks, or Emails");
            }

            $query = new SugarQuery();
            $api->action = 'list';
            $orderBy = array();

            // modules is a char field used for sorting on module name
            // it is added to the select below, it can be sorted on but needs to be removed from
            // the arguments to allow it to be maintained throughout the code
            $removedModuleDirection = false;
            if (isset($args['orderBy']))
                $args['order_by'] = $args['orderBy'];
            if (!empty($args['order_by'])) {
                $orderBy = explode(',', $args['order_by']);
                foreach ($orderBy as $key => $list) {
                    list($field, $direction) = explode(':', $list);
                    // `picture` is considered the same field as `module` because it
                    // corresponds to the module icon.
                    if ($field === 'module' || $field === 'picture') {
                        unset($orderBy[$key]);
                        $removedModuleDirection = !empty($direction) ? $direction : 'DESC';
                    } else if ($field === 'assigned_user_name') {
                        // custom check to user assigned_user_id for sorting
                        $orderBy[$key] = 'assigned_user_id:' . $direction;
                    }
                }
                $args['order_by'] = implode(',', $orderBy);
                $orderBy[] = "module:{$removedModuleDirection}";
            }

            if (!empty($args['fields'])) {
                $args['fields'] .= "," . implode(',', $this->validFields);
            } else {
                $args['fields'] = implode(',', $this->validFields);
            }

            if (!empty($args['order_by']) || !empty($args['fields'])) {
                $args = $this->scrubFields($args);
            }

            unset($args['order_by']);
            foreach ($this->moduleList as $link_name => $module) {
                $args['filter'] = array();
                $savedFields = $args['fields'];
                $args['link_name'] = $link_name;

                $fields = explode(',', $args['fields']);

                foreach ($fields as $k => $field) {
                    if (isset($args['placeholder_fields'][$module][$field])) {
                        unset($fields[$k]);
                    }
                }

                $args['fields'] = implode(',', $fields);
                if (!empty($this->moduleFilters[$module])) {
                    $args['filter'] = $this->moduleFilters[$module];
                }

                list($args, $q, $options, $linkSeed) = $this->filterRelatedSetup($api, $args);
                $q->select()->selectReset();
                $q->orderByReset(); // ORACLE doesn't allow order by in UNION queries
                if (!empty($args['placeholder_fields'])) {
                    $newFields = array_merge($args['placeholder_fields'][$module], $fields);
                } else {
                    $newFields = $fields;
                }

                sort($newFields);
                foreach ($newFields as $field) {
                    if ($field == 'module') {
                        continue;
                    }
                    // special case for description on emails
                    if ($module == 'Emails' && $field == 'description') {
                        // ORACLE requires EMPTY_CLOB() for union queries if CLOB fields were used before
                        $q->select()->fieldRaw(DBManagerFactory::getInstance()->emptyValue('text') . " email_description");
                    } else {
                        if (isset($args['placeholder_fields'][$module][$field])) {
                            $q->select()->fieldRaw("'' {$args['placeholder_fields'][$module][$field]}");
                        } else {
                            $q->select()->field($field);
                        }
                    }
                }
                if (isset($args['offset']))
                    $options['offset'] = $args['offset'];
                $q->select()->field('id');
                $q->select()->field('assigned_user_id');
                if($module=='Tasks')
                $q->where()->equals('hide',0);
                $q->limit = $q->offset = null;
                $q->select()->fieldRaw("'{$module}'", 'module');
                $query->union($q);
                $query->limit($options['limit'] + 1);
                $query->offset($options['offset']);
                $args['fields'] = $savedFields;
            }

            if (!empty($orderBy)) {
                if ($removedModuleDirection !== false) {
                    $orderBy[] = "module:{$removedModuleDirection}";
                }
                foreach ($orderBy as $order) {
                    $ordering = explode(':', $order);
                    if (count($ordering) > 1) {
                        $query->orderByRaw("{$ordering[0]}", "{$ordering[1]}");
                    } else {
                        $query->orderByRaw("{$ordering[0]}");
                    }
                }
            } else {
                $query->orderByRaw('date_modified');
            }

            $result = $this->runQuery($api, $args, $query, $options);
            return $result;
        } else {
            return;
        }
    }

    protected function scrubFields($args) {
        $filters = !empty($args['order_by']) ? explode(',', $args['order_by']) : array();
        foreach ($filters as $filter) {
            $order_by = explode(':', $filter);
            foreach ($this->moduleList as $module_name) {
                $seed = BeanFactory::getBean($module_name);
                if (!isset($seed->field_defs[$order_by[0]])) {
                    $args['placeholder_fields'][$module_name][$order_by[0]] = $order_by[0];
                } else {
                    if (empty($args['fields'])) {
                        $args['fields'] = "{$order_by[0]}";
                    } else {
                        $args['fields'] .= ",{$order_by[0]}";
                    }
                }
            }
        }

        $fields = !empty($args['fields']) ? explode(',', $args['fields']) : array();
        foreach ($fields as $key => $field) {
            foreach ($this->moduleList as $module_name) {
                $seed = BeanFactory::getBean($module_name);
                if (!isset($seed->field_defs[$field])) {
                    $args['placeholder_fields'][$module_name][$field] = $field;
                }
            }
        }
        return $args;
    }

    protected function runQuery(ServiceBase $api, array $args, SugarQuery $q, array $options) {

        $beans = array(
            '_rows' => array()
        );

        foreach ($q->execute() as $row) {
            $beans[$row['id']] = BeanFactory::getBean($row['module'], $row['id']);
            $beans['_rows'][$row['id']] = $row;
        }

        $rows = $beans['_rows'];
        unset($beans['_rows']);

        $data = array();
        $data['next_offset'] = -1;

        $i = 0;
        foreach ($beans as $bean_id => $bean) {
            if ($i == $options['limit']) {
                unset($beans[$bean_id]);
                $data['next_offset'] = (int) ($options['limit'] + $options['offset']);
                continue;
            }
            $i++;

            $this->populateRelatedFields($bean, $rows[$bean_id]);
        }

        // add on the contact_id and contact_name fields so we get those
        // returned in the response
        $args['fields'] .= ',contact_id,contact_name';
        $data['records'] = $this->formatBeans($api, $args, $beans);

        foreach ($data['records'] as $id => $record) {
            $data['records'][$id]['moduleName'] = '';
            $data['records'][$id]['moduleNameSingular'] = '';
            
            if(isset($GLOBALS['app_list_strings']['moduleListSingular'][$record['_module']])) {
                $data['records'][$id]['moduleNameSingular'] = $GLOBALS['app_list_strings']['moduleListSingular'][$record['_module']];
            } 
            if(isset($GLOBALS['app_list_strings']['moduleList'][$record['_module']])) {
                $data['records'][$id]['moduleName'] = $GLOBALS['app_list_strings']['moduleList'][$record['_module']];
            }
            // Have to tack on from/to/description here due to not all modules
            // having all these fields
            if ($record['_module'] == 'Emails') {
                /* @var $q SugarQuery */
                $q = new SugarQuery();
                $q->select(array('description', 'description_html', 'from_addr', 'to_addrs'));
                $q->from(BeanFactory::getBean('EmailText'));
                $q->where()->equals('email_id', $data['records'][$id]['id']);
                foreach ($q->execute() as $row) {
                    $data['records'][$id]['description_html'] = $row['description_html'];
                    $data['records'][$id]['description'] = $row['description'];
                    $data['records'][$id]['from_addr'] = $row['from_addr'];
                    $data['records'][$id]['to_addrs'] = $row['to_addrs'];
                }

                /*
                 * Getting Email Attachements
                 */
                $parent_id = $data['records'][$id]['id'];
                $notes_query = new SugarQuery();
                $notes_query->select(array('id', 'name', 'file_mime_type', 'filename', 'parent_id'));
                $notes_query->from(BeanFactory::getBean('Notes'), array('team_security' => false));
                $notes_query->where()->equals('parent_type', 'Emails');
                $notes_query->where()->equals('parent_id', "$parent_id");

                $notes = $notes_query->execute();
                $note_count = count($notes);
                $attachements = array();
                foreach ($notes as $note) {
                    $arr = array(
                        'note_id' => $note['id'],
                        'filename' => $note['filename']
                    );
                    $attachements[] = $arr;
                    $data['records'][$id]['attachements_count'] = $note_count;
                }
                $data['records'][$id]['attachements'] = $attachements;
            }
            /*
             * Getting Note's Created by user name from user id
             */
            if ($record['_module'] == 'Notes' && isset($data['records'][$id]['created_by'])) {
                $user_id = $data['records'][$id]['created_by'];
                $result = $GLOBALS['db']->query("select first_name, last_name FROM users where id='$user_id'");
                $user = $GLOBALS["db"]->fetchByAssoc($result);
                $data['records'][$id]['created_by_id'] = $user_id;
                $data['records'][$id]['created_by_name'] = $user['first_name'] . ' ' . $user['last_name'];
            }
        }

        return $data;
    }

}
