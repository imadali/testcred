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

require_once 'clients/base/api/FilterApi.php';

class CustomFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        $register_defs = parent::registerApiRest();
        foreach ($register_defs as $api_name => $def) {
            $register_defs[$api_name]['path'][0] = 'Tasks';
        }
        return $register_defs;
    }
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
                } else if($field == 'leads_credit_history'){
                    static::getLinkedCreditHistoryToLead($q, $where->queryAnd(), $filter);
                }else if($field == 'leads_deltavista_score'){
                    static::getLinkedDeltaVistaScoreOfLead($q, $where->queryAnd(), $filter);
                } else if($field == 'secondary_teams_custom'){
                    static::getPrimarySecondaryTeamRecords($q, $where->queryAnd(), $filter);
                } else if($field == 'till_today') {
-                    static::getTasksTillToday($q, $where->queryAnd(), $filter);
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
    
    protected static function getPrimarySecondaryTeamRecords(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        
        $condition = static::getConditionalFilterForTeams($filter);
        $in_or_not_in = '';
        $whereClause = '';
        $customWhereClause = '';
        
        if($condition['condition'] == 'IN' && !empty($condition['teams'])) {
            $in_or_not_in = 'IN';
            $whereClause.= 'team_sets_teams.team_id IN ('.implode(',', array_map('add_quotes', $condition['teams'])).')';
        } else if($condition['condition'] == '!=' && !empty($condition['teams'])){
            $in_or_not_in = 'NOT IN';
            $whereClause.= 'team_sets_teams.team_id IN ('.implode(',', array_map('add_quotes', $condition['teams'])).')';
        } else if($condition['condition'] == 'empty') {
            $customWhereClause = 'tasks.team_id = "" OR tasks.team_id IS NULL ';
        } else if($condition['condition']== 'notempty') {
            $customWhereClause = 'tasks.team_id != "" OR tasks.team_id IS NOT NULL ' ;
        }

        if(!empty($whereClause)) {
            $rawJoin = ' tasks.team_set_id '.$in_or_not_in.' ( SELECT team_sets_teams.team_set_id 
                            FROM team_sets_teams 
                            INNER JOIN team_sets_modules ON team_sets_modules.team_set_id = team_sets_teams.team_set_id  
                                AND team_sets_modules.deleted = 0
                                AND team_sets_modules.module_table_name = "tasks"
                            WHERE '.$whereClause.'   AND team_sets_teams.deleted = 0 
                    )';
            $where->addRaw($rawJoin);
        } 
        else if(!empty($customWhereClause)) {
            $where->addRaw($customWhereClause);
        }
    }
    
    protected static function getTasksTillToday(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        $currentDate  = date("Y-m-d").' 22:59:59';
        if(!empty($currentDate)) {
            $customWhereClause = "tasks.date_due <='$currentDate'";
            $where->addRaw($customWhereClause);
        }
    }
    
    protected static function getLinkedCreditHistoryToLead(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        if($filter) {
            $condition = ' IN ';
        } else{
            $condition = ' NOT IN ';
        }
        $rawJoin = ' tasks.parent_id IS NOT NULL AND tasks.parent_type="Leads" AND tasks.parent_id '.$condition.'   
                       ( SELECT distinct(leads.id) AS lead_id FROM leads 
                         INNER JOIN leads_dotb5_credit_history_1_c jt 
                            ON leads.id = jt.leads_dotb5_credit_history_1leads_ida 
                            AND jt.deleted = 0
                        WHERE leads.deleted = 0 ) ';
        
        $where->addRaw($rawJoin);
    }
    
    protected static function getLinkedDeltaVistaScoreOfLead(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) {
        
        $condition = static::getConditionFromFilter($filter);
        
        $leads = 'jtn';
        $leads_cstm = $leads.'_cstm';
        $sql_where_condition = '';
 
        if($condition == 'between'){
            $sql_where_condition = $leads_cstm.'.deltavista_score_c >= '.$condition['value'].' AND '.$leads_cstm.'.deltavista_score_c <='.$condition['value'];
        } else if($condition['condition'] == 'IN') {
            $sql_where_condition = $leads_cstm.'.deltavista_score_c '.$condition['condition'].' ( '.$condition['value'].' )';
        } else {
            $sql_where_condition = $leads_cstm.'.deltavista_score_c '.$condition['condition'].$condition['value'];
        }
        
        $rawSql = ' tasks.parent_id IS NOT NULL AND tasks.parent_type="Leads" AND tasks.parent_id IN '
                . ' ( SELECT distinct('.$leads.'.id) FROM leads '.$leads.' 
                      INNER JOIN leads_cstm '.$leads_cstm.' ON '.$leads.'.id = '.$leads_cstm.'.id_c AND '.$sql_where_condition .' 
                      WHERE '.$leads.'.deleted = 0 )';
        
        $where->addRaw($rawSql);
    }
    
    protected static function getConditionalFilterForTeams($filter) {
        $condition = '';
        $condValue = '';

        foreach ($filter as $op => $value) {
                switch ($op) {
                    case '$not_in':
                        $condition = '!=';
                        $condValue = $value;
                        break;
                    case '$in':
                        $condition = 'IN';
                        $condValue = $value;
                        break;
                    case '$not_empty':
                        $condition = 'notempty';
                        $condValue = '';
                        break;
                    case '$empty':
                        $condition = 'empty';
                        $condValue = '';
                        break;
                }
            }
            return array('condition' => $condition , 'teams' => $condValue);
    }
    protected static function getConditionFromFilter($filter) {
        $condition = '=';
        $condValue = $filter;
        
        if(is_array($filter)){
            foreach ($filter as $op => $value) {
                switch ($op) {
                    case '$not_equals':
                        $condition = '!=';
                        $condValue = $value;
                        break;
                    case '$in':
                        $condition = 'IN';
                        $condValue = implode(',', array_map('add_quotes', $value));
                        break;
                    case '$gt':
                        $condition = '>';
                        $condValue = $value;
                        break;
                    case '$lt':
                        $condition = '<';
                        $condValue = $value;
                        break;
                    case '$gte':
                        $condition = '>=';
                        $condValue = $value;
                        break;
                    case '$lte':
                        $condition = '<=';
                        $condValue = $value;
                        break;
                    case '$between':
                        $condition = 'between';
                        $condValue = $value;
                        break;
                }
            }
        }
        
        return array('condition' => $condition, 'value' => $condValue);
    }
}
