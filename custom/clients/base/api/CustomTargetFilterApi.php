<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once 'clients/base/api/FilterApi.php';

class CustomTargetFilterApi extends FilterApi {

    public function filterList(ServiceBase $api, array $args, $acl = 'list') {
        if (!empty($args['q'])) {
            if (!empty($args['filter']) || !empty($args['deleted'])) {
                throw new SugarApiExceptionInvalidParameter();
            }

            require_once('clients/base/api/UnifiedSearchApi.php');
            $search = new UnifiedSearchApi();
            $args['module_list'] = $args['module'];
            return $search->globalSearch($api, $args);
        }

        $api->action = 'list';

        list($args, $q, $options, $seed) = $this->filterListSetup($api, $args, $acl);

        if (isset($args['link_name']) && $args['link_name'] == 'contacts_prospects_1') {
            $q->joinTable('prospect_lists_prospects', array('alias' => 'e2', 'joinType' => 'LEFT', 'linkingTable' => true))
                    ->on()
                    ->equalsField('prospect_lists.id', 'e2.prospect_list_id');
            $q->joinTable('contacts', array('alias' => 'c', 'joinType' => 'LEFT', 'linkingTable' => true))
                    ->on()
                    ->equalsField('c.id', 'e2.related_id');
            $q->where()->equals("e2.deleted", "0");
            $q->where()->equals("c.deleted", "0");
            $q->where()->equals("e2.related_type", "Contacts");
            $q->where()->equals("e2.related_id", $args['contact']);
            $GLOBALS['log']->debug($q->compileSql());
        }

        return $this->runQuery($api, $args, $q, $options, $seed);
    }

}
