<?php

require_once 'clients/base/api/FilterApi.php';

class CustomLeadsFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        $register_defs = parent::registerApiRest();
        foreach ($register_defs as $api_name => $def) {
            $register_defs[$api_name]['path'][0] = 'Leads';
        }
        return $register_defs;
    }
    
    /**
     * Add filters to the query :
     * 
     * @param  array                    $filterDefs Contains Filter Definition
     * @param  SugarQuery_Builder_Where $where      Contains Where Cluase
     * @param  SugarQuery               $q          Sugar Query Object
     * @throws SugarApiExceptionInvalidParameter
     * @return WHERE cluase
     */
    protected static function addFilters(array $filterDefs, SugarQuery_Builder_Where $where, SugarQuery $q) {
        static $sfh;
        if (!isset($sfh)) {
            $sfh = new SugarFieldHandler();
        }

        foreach ($filterDefs as $filterDef) {
            if (!is_array($filterDef)) {
                throw new SugarApiExceptionInvalidParameter(
                    sprintf(
                        'Did not recognize the definition: %s',
                        print_r($filterDef, true)
                    )
                );
            }
            foreach ($filterDef as $field => $filter) {
                if ($field == '$or') {
                    static::addFilters($filter, $where->queryOr(), $q);
                } elseif ($field == '$and') {
                    static::addFilters($filter, $where->queryAnd(), $q);
                } elseif ($field == '$favorite') {
                    static::addFavoriteFilter($q, $where, $filter);
                } elseif ($field == '$owner') {
                    static::addOwnerFilter($q, $where, $filter);
                } elseif ($field == '$creator') {
                    static::addCreatorFilter($q, $where, $filter);
                } elseif ($field == '$tracker') {
                    static::addTrackerFilter($q, $where, $filter);
                } elseif ($field == '$following') {
                    static::addFollowFilter($q, $where, $filter);
                } else if($field == 'email_addrs_primary_secondary'){
                    static::getPrimarySecondaryEmailRecords($q, $where->queryAnd(), $filter);
                }else if($field == 'lead_with_no_open_task'){
                    static::getLeadWithNoOpenTasks($q, $where->queryAnd(), $filter);
                } else {
                    // Looks like just a normal field, parse it's options
                    $fieldInfo = self::verifyField($q, $field);

                    //If the field was a related field and we added a join, we need to adjust the table name used
                    //to get the right join table alias
                    if (!empty($fieldInfo['field'])) {
                        $field = $fieldInfo['field'];
                    }
                    $fieldType = !empty($fieldInfo['def']['custom_type']) ? $fieldInfo['def']['custom_type'] : $fieldInfo['def']['type'];
                    $sugarField = $sfh->getSugarField($fieldType);
                    if (!is_array($filter)) {
                        $value = $filter;
                        $filter = array();
                        $filter['$equals'] = $value;
                    }
                    foreach ($filter as $op => $value) {
                        /*
                         * occasionally fields may need to be fixed up for the Filter, for instance if you are
                         * doing an operation on a datetime field and only send in a date, we need to fix that field to
                         * be a dateTime then unFormat it so that its in GMT ready for DB use
                         */
                        if ($sugarField->fixForFilter($value, $field, $fieldInfo['bean'], $q, $where, $op) == false) {
                            continue;
                        }

                        if (is_array($value)) {
                            foreach ($value as $i => $val) {
                                // TODO: apiUnformat() is deprecated, this will change to apiUnformatField() in next API version
                                $value[$i] = $sugarField->apiUnformat($val);
                            }
                        } else {
                            // TODO: apiUnformat() is deprecated, this will change to apiUnformatField() in next API version
                            $value = $sugarField->apiUnformat($value);
                        }

                        switch ($op) {
                            case '$equals':
                                $where->equals($field, $value);
                                break;
                            case '$not_equals':
                                $where->notEquals($field, $value);
                                break;
                            case '$starts':
                                $where->starts($field, $value);
                                break;
                            case '$ends':
                                $where->ends($field, $value);
                                break;
                            case '$contains':
                                $where->contains($field, $value);
                                break;
                            case '$not_contains':
                                $where->notContains($field, $value);
                                break;
                            case '$in':
                                if (!is_array($value)) {
                                    throw new SugarApiExceptionInvalidParameter('$in requires an array');
                                }
                                $where->in($field, $value);
                                break;
                            case '$not_in':
                                if (!is_array($value)) {
                                    throw new SugarApiExceptionInvalidParameter('$not_in requires an array');
                                }
                                $where->notIn($field, $value);
                                break;
                            case '$dateBetween':
                            case '$between':
                                if (!is_array($value) || count($value) != 2) {
                                    throw new SugarApiExceptionInvalidParameter(
                                        '$between requires an array with two values.'
                                    );
                                }
                                $where->between($field, $value[0], $value[1]);
                                break;
                            case '$is_null':
                                $where->isNull($field);
                                break;
                            case '$not_null':
                                $where->notNull($field);
                                break;
                            case '$empty':
                                $where->isEmpty($field);
                                break;
                            case '$not_empty':
                                $where->isNotEmpty($field);
                                break;
                            case '$lt':
                                $where->lt($field, $value);
                                break;
                            case '$lte':
                                $where->lte($field, $value);
                                break;
                            case '$gt':
                                $where->gt($field, $value);
                                break;
                            case '$gte':
                                $where->gte($field, $value);
                                break;
                            case '$dateRange':
                                $where->dateRange($field, $value, $fieldInfo['bean']);
                                break;
                            default:
                                throw new SugarApiExceptionInvalidParameter("Did not recognize the operand: " . $op);
                        }
                    }
                }
            }
        }
    }
    
    protected static function getLeadWithNoOpenTasks(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        $condition = ' NOT IN ';
        if($filter){
            $condition = ' IN ';
        }
        $sql_raw_join = ' leads.id '.$condition.' ( SELECT tasks.parent_id FROM tasks WHERE tasks.status = "open" AND tasks.parent_type="Leads" AND tasks.deleted = 0 AND tasks.parent_id IS NOT NULL )';
        $where->addRaw($sql_raw_join);
    }
    
    protected static function getPrimarySecondaryEmailRecords(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        $comparison = ' = ';
        $email_address = '';
        
        if(is_array($filter)){ // Checks for Starts with
            $comparison = ' LIKE ';
            $email_address = strtolower($filter['$starts']).'%';
        } else{
            $email_address = strtolower($filter);
        }
        
        if(!empty($email_address)) {
            $sql_email_raw_join = ' leads.id IN ( SELECT distinct(email_addr_bean_rel.bean_id) FROM email_addr_bean_rel
                           INNER JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id 
                                 AND email_addresses.deleted = 0 AND email_addresses.email_address '.$comparison."'$email_address'".'
                           WHERE email_addr_bean_rel.deleted = 0 AND email_addr_bean_rel.bean_module = "Leads" ) ';
            
            $where->addRaw($sql_email_raw_join);
        }
    }
}

