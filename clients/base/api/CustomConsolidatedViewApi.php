<?php

require_once 'clients/base/api/FilterApi.php';

/**
 * CRED-941 : Consolidated View of Tasks and Calls 
 */
class CustomConsolidatedViewApi extends FilterApi
{

    /**
     * @Override
     * replace the api end point names.
     * 
     * @return array Containing Customized Endpoint Details
     */
    public function registerApiRest()
    {
        $register_defs = parent::registerApiRest();

        $ret = array();
        foreach ($register_defs as $api_name => $defs) {
            array_splice($defs['path'], 0, 0, 'consolidation');
            array_splice($defs['pathVars'], 0, 0, '');
            $ret[$api_name] = $defs;
        }
        
        return $ret;
    }

     /**
     * @Override
     * Customize the calls fro the two different modules and use
     * Suagr Query JOIN to create the resultant object.
     * 
     * @return array Containing Arguments
     */
    public function filterListSetup(ServiceBase $api, array $args, $acl = 'list')
    {
        $orderBy = '';
        $new_fields = '';
        $args_store = array();
        
        $args_store = $args;

        if (isset($args['order_by'])) {
            $orderBy = $args['order_by'];
        }
        
        if(isset($args['fields'])) {
            $new_fields= $args['fields'];
        }
        
        if (!is_array($new_fields)) {
            $new_fields = explode(',', $new_fields);
        }
        
        $index = array_search('date_due', $new_fields);
        if ($index) {
            unset($new_fields[$index]);
        }
        
        sort($new_fields);
        
        /**
         * Formatting filters according to modules and no.of filters.
         */
        if (isset($args['filter']) && $args['module'] == "Calls") {
            if (isset($args['filter'][0]['$and'])) {
                foreach ($args['filter'][0]['$and'] as $k => $v) {
                    if (array_keys($v)[0] == 'date_due') {
                        $args['filter'][0]['$and'][$k]['date_end'] = $v['date_due'];
                        unset($args['filter'][0]['$and'][$k]['date_due']);
                    }
                }
            } else {
                foreach ($args['filter'] as $k => $v) {
                    if (array_keys($v)[0] == 'date_due') {
                        $args['filter'][$k]['date_end'] = $v['date_due'];
                        unset($args['filter'][$k]['date_due']);
                    }
                }
            }
        }

        list($args, $query, $options, $bean) = parent::filterListSetup($api, $args, $acl);

        $query->select()->selectReset();
        $query->select(array('id'));
        $query->select(array('assigned_user_id'));
        $query->select($new_fields);
        $query->select()->fieldRaw("'{$args['module']}'", 'moduleName');
        
        $y = $query->offset ;
        $x = $query->limit ;
         
        if ($args['module'] == "Tasks") {
            $query->select()->fieldRaw("date_due", 'Due_Date');
        } else {
            $query->select()->fieldRaw("date_end", 'Due_Date');
        }

        $query->order_by = array();
        $query->offset = null;
        $query->limit = null;
        
        /**
         * Formatting filters according to modules and no.of filters.
         */
        if ($args['module'] == "Tasks") {
            $args['module'] = 'Calls';
            if (isset($args['filter']) && $args['module'] == "Calls") {

                if (isset($args['filter'][0]['$and'])) {
                    foreach ($args['filter'][0]['$and'] as $k => $v) {
                        if (array_keys($v)[0] == 'date_due') {
                            $args['filter'][0]['$and'][$k]['date_end'] = $v['date_due'];
                            unset($args['filter'][0]['$and'][$k]['date_due']);
                        }
                    }
                } else {
                    foreach ($args['filter'] as $k => $v) {
                        if (array_keys($v)[0] == 'date_due') {
                            $args['filter'][$k]['date_end'] = $v['date_due'];
                            unset($args['filter'][$k]['date_due']);
                        }
                    }
                }
            }
        } else {
            $args['module'] = 'Tasks';
            if (isset($args['filter']) && $args['module'] == "Tasks") {

                if (isset($args['filter'][0]['$and'])) {
                    foreach ($args['filter'][0]['$and'] as $k => $v) {
                        if (array_keys($v)[0] == 'date_end') {
                            $args['filter'][0]['$and'][$k]['date_due'] = $v['date_end'];
                            unset($args['filter'][0]['$and'][$k]['date_end']);
                        }
                    }
                } else {
                    foreach ($args['filter'] as $k => $v) {
                        if (array_keys($v)[0] == 'date_end') {
                            $args['filter'][$k]['date_due'] = $v['date_end'];
                            unset($args['filter'][$k]['date_end']);
                        }
                    }
                }
            }
        }

        list($args, $q, $options, $b) = parent::filterListSetup($api, $args, $acl);

        $q->select()->selectReset();
        $q->select(array('id'));
        $q->select(array('assigned_user_id'));
        $q->select($new_fields);
        $q->select()->fieldRaw("'{$args['module']}'", 'moduleName');

        if ($args['module'] == "Tasks") {
            $q->select()->fieldRaw("date_due", 'Due_Date');
        } else {
            $q->select()->fieldRaw("date_end", 'Due_Date');
        }

        $q->order_by = array();
        $q->offset = null;
        $q->limit = null;

        $sqUnion = new SugarQuery();
        $sqUnion->union($q);
        $sqUnion->union($query);
        $sqUnion->limit = $x;
        $sqUnion->offset = $y;
        
        if (empty($orderBy)) {
            $sqUnion->orderByRaw('date_modified', 'DESC');
        } else {
            $sort = explode(':', $orderBy);
            if ($sort[0] == 'assigned_user_name') {
                $sqUnion->orderByRaw('rel_assigned_user_name_first_name', strtoupper($sort[1]));
                $sqUnion->orderByRaw('rel_assigned_user_name_last_name', strtoupper($sort[1]));
            } elseif ($sort[0] == 'mixed_date_due') {
                $sqUnion->orderByRaw('Due_Date', strtoupper($sort[1]));
            } else {
                $sqUnion->orderByRaw($sort[0], strtoupper($sort[1]));
            }
        }

        return array($args, $sqUnion, $options, $bean);
    }
    
     /**
     * @Override
     * Replace the Method to return the customized count value.
     * 
     * @return array Containing Count
     */
    public function getFilterListCount(ServiceBase $api, array $args)
    {
        $api->action = 'list';
        $count = 0;
        list(, $q) = $this->filterListSetup($api, $args, $acl = 'list');
        
        foreach ($q->union->getQueries() as $qq) {
            $qq['query']->select->selectReset()->setCountQuery();
            $qq['query']->orderByReset();
            $qq['query']->limit = null;
            $stmt = $qq['query']->compile()->execute();
            $count  += (int) $stmt->fetchColumn();
        }
        
        return array(
            'record_count' => $count,
        );
    }
    
    /**
     * @Override
     * Replace the Method to add extra parameters for the list view to display.
     * 
     * @return array Containing Formatted Data
     */
    protected function formatBean(ServiceBase $api, $args, SugarBean $bean, array $options = array())
    {
        global $timedate, $current_user;
        $data = parent::formatBean($api, $args, $bean);
        $data['moduleName'] = $bean->fetched_row['moduleName'];       
        $dueDate = new DateTime($bean->fetched_row['Due_Date']);

        if ($bean->module_name == 'Calls') {
            $data['mixed_date_due'] = $timedate->tzUser($dueDate, $current_user)->format('Y-m-d H:i:s');
        } elseif ($bean->module_name == 'Tasks') {
            $data['mixed_date_due'] = $timedate->tzUser($dueDate, $current_user)->format('Y-m-d H:i:s');
        }

        return $data;
    }
    
    /**
     * @Override
     * Replace the Method to negate the default OrderBy and apply custom OrderBy.
     * 
     * @return Array Empty Array
     */
    protected function getOrderByFromArgs(array $args, SugarBean $seed = null)
    {
        return array();
    }
  
}