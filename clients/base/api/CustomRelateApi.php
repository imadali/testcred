<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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

class CustomRelateApi extends RelateApi
{
    /*
     * Overriding this function to get correct count for Activities custom subpanel
     */

    public function filterRelatedCount(ServiceBase $api, array $args)
    {
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

            /**
             * CRED-908 : 500 error shown on clicking
             *  'show more' in all subpanels
             */
            list(, $q) = $this->filterRelatedSetup($api, $args);

            $q->select->selectReset()->setCountQuery();
            $q->limit = null;
            $q->orderByReset();

            $stmt = $q->compile()->execute();
            $count = (int) $stmt->fetchColumn();

            return array(
                'record_count' => $count,
            );
        }
    }
}
