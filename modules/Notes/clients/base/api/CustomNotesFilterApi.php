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

class CustomNotesFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        $register_defs = parent::registerApiRest();
        foreach ($register_defs as $api_name => $def) {
            $register_defs[$api_name]['path'][0] = 'Notes';
        }
        return $register_defs;
    }
    /**
     * Add filters to the query
     * @param array $filterDefs
     * @param SugarQuery_Builder_Where $where
     * @param SugarQuery $q
     * @throws SugarApiExceptionInvalidParameter
     */
    protected static function addFilters(array $filterDefs, SugarQuery_Builder_Where $where, SugarQuery $q)
    {
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
                } else if($field == 'email_notes') {
                    //custom code for getting notes linked to archieved emails CRED-778
                    static::getArchievedEmailNotes($q, $where->queryAnd(), $filter);
                }else {
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

    /**
    * Custom function to get notes linked to archieved emails CRED-778
    */
    protected static function getArchievedEmailNotes(SugarQuery $q, SugarQuery_Builder_Where $where, $filter) 
    {
        $rawJoin = 'notes.parent_type IS NOT NULL AND notes.parent_type = "Emails" AND notes.parent_id IN (SELECT distinct(emails.id) AS email_id FROM emails WHERE ((emails.type = "archived" AND emails.status = "archived") OR emails.type = "inbound") AND emails.deleted=0)';
        
        $where->addRaw($rawJoin);
    }
}
