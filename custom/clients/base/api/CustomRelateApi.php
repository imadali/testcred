<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
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

require_once 'clients/base/api/RelateApi.php';

class CustomRelateApi extends RelateApi {
    /*
     * Overriding this function to get correct count for Activities custom subpanel
     */

    public function filterRelatedCount(ServiceBase $api, array $args) {
        /*
         * Check if activity subpanel then get custom count other wise default
         */
        if ($args['link_name'] == 'historical_summary') {
            //The modules included in the acitivies
            $tables = array('notes', 'tasks', 'calls', 'emails');
            $module = $args['module'];
            $record = $args['record'];
            $total = 0;
            foreach ($tables as $key => $table) {
                $result = $GLOBALS['db']->query("SELECT count(*) count FROM $table WHERE parent_type=" . "'" . $module . "'" . " AND parent_id=" . "'" . $record . "'" . " AND deleted = 0");
                $result = $GLOBALS['db']->fetchByAssoc($result);
                $total+=$result['count'];
            }
            return array('record_count' => $total);
        } else {
            $api->action = 'list';

            list($args, $q, $options, $linkSeed) = $this->filterRelatedSetup($api, $args);

            $q->select->selectReset()->setCountQuery();
            $q->limit = null;
            $q->order_by = null;

            return reset($q->execute());
        }
    }
    
    public function filterRelated(ServiceBase $api, array $args) {
        global $app_list_strings;
        $api->action = 'list';

        if ($args['link_name'] == 'contacts_prospects_1') {
            $custom_args = array();
            $custom_args ['__sugar_url'] = 'v10/ProspectLists';
            $custom_args['max_num'] = $args['max_num'];
            $custom_args['view'] = 'list';
            $custom_args['module'] = 'ProspectLists';
            $custom_args['fields'] = 'list_type';
            $custom_args['contact'] = $args['record'];
            $custom_args['link_name'] = $args['link_name'];

            $filterList = new CustomTargetFilterApi();
            $data = $filterList->filterList($api, $custom_args, $acl = 'list');
            
            if (!empty($data)) {
                foreach ($data['records'] as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($k == 'list_type') {
                            $data['records'][$key]['title'] = $app_list_strings['prospect_list_type_dom'][$v];
                            unset($data['records'][$key][$k]);
                        }
                        if ($k == 'name') {
                            $naming = explode(' ', $v);
                            $data['records'][$key]['first_name'] = $naming[0];
                            $data['records'][$key]['last_name'] = '';
                            for ($i = 1; $i < sizeof($naming); $i++) {
                                $data['records'][$key]['last_name'] .= $naming[$i] . ' ';
                            }
                            unset($data['records'][$key][$k]);
                        }
                    }
                    $query = 'SELECT count(*) AS count FROM prospect_lists_prospects'
                            . ' WHERE prospect_list_id = "' . $data['records'][$key]['id'] . '"'
                            . ' AND deleted = 0';

                    $result = $GLOBALS['db']->query($query);
                    $row = $GLOBALS['db']->fetchByAssoc($result);
                    $data['records'][$key]['phone_fax'] = $row['count'];
                }
            }

            return $data;
        } else {
            list($args, $q, $options, $linkSeed) = $this->filterRelatedSetup($api, $args);

            return $this->runQuery($api, $args, $q, $options, $linkSeed);
        }
    }

}
