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

require_once 'modules/ActivityStream/clients/base/api/ActivitiesApi.php';

class CustomActivitiesApi extends ActivitiesApi {

    protected function formatResult(ServiceBase $api, array $args, SugarQuery $query, SugarBean $bean = null) {
        global $locale;

        $response = array();
        $data = $query->execute('array', false);

        $seed = BeanFactory::newBean('Activities');

        // We add one to it when setting it, so we subtract one now for the true
        // limit.
        $limit = $query->limit - 1;
        $count = count($data);
        if ($count > $limit) {
            $nextOffset = $query->offset + $limit;
            array_pop($data);
        } else {
            $nextOffset = -1;
        }

        $options = array(
            'requestBean' => $bean,
        );

        foreach ($data as $row) {
            $content = array();
            $content = json_decode($row['data']);

            /*
             * Check for seperating activity stream process of KBContents and other modules
             */
            if ($args['module'] == 'KBContents') {
                if ($row['activity_type'] == 'update' && isset($content->changes->kbdocument_body) && $content->changes->kbdocument_body->data_type == 'htmleditable_tinymce') {
                    $seed->populateFromRow($row, true);
                    $record = $this->formatBean($api, $args, $seed, $options);
                    $record_modified = $this->specificFormating($api, $record, $row, $bean, $locale);
                    foreach ($record_modified['data']['changes'] as $key => $value) {
                        if ($key != 'kbdocument_body') {
                            unset($record_modified['data']['changes'][$key]);
                        }
                    }
                    $response['records'][] = $record_modified;
                }
            } else {
                $seed->populateFromRow($row, true);
                $record = $this->formatBean($api, $args, $seed, $options);
                $record_modified = $this->specificFormating($api, $record, $row, $bean, $locale);
                $response['records'][] = $record_modified;
            }
        }


        $response['next_offset'] = $nextOffset;
        $response['args'] = $args;
        return $response;
    }

    /*
     * Additional function for avoiding replication-Performs other checks on the data
     */

    public function specificFormating(ServiceBase $api, array $record, array $row, SugarBean $bean, $locale) {
        global $locale;
        if ($record['activity_type'] === 'update') {

            if (is_null($bean) || empty($bean->id)) {
                $fields = json_decode($row['fields'], true);
                $changedData = array();
                if (!empty($fields)) {
                    $aclBean = null;
                    if (!is_null($bean)) {
                        $aclBean = $bean;
                    } elseif (!empty($record['data']['object']['module'])) {
                        $aclModule = $record['data']['object']['module'];
                        $aclBean = $this->getEmptyBean($aclModule);
                    }
                    if (!is_null($aclBean)) {
                        $context = array('user' => $api->user);
                        $aclBean->ACLFilterFieldList($record['data']['changes'], $context);
                    }
                    foreach ($record['data']['changes'] as &$change) {

                        if (in_array($change['field_name'], $fields)) {
                            $changedData[$change['field_name']] = $record['data']['changes'][$change['field_name']];
                        }
                    }
                }
                $record['data']['changes'] = $changedData;
            } else {
                $context = array('user' => $api->user);
                $bean->ACLFilterFieldList($record['data']['changes'], $context);
            }
        }

        //check if parent record preview should be enabled
        if (!empty($record['parent_type']) && !empty($record['parent_id'])) {
            $previewCheckResult = $this->checkParentPreviewEnabled($api->user, $record['display_parent_type'], $record['display_parent_id']);
            $record['preview_enabled'] = $previewCheckResult['preview_enabled'];
            $record['preview_disabled_reason'] = $previewCheckResult['preview_disabled_reason'];
        }

        $record['created_by_name'] = $locale->formatName('Users', $row);

        if (!isset($record['created_by_name']) && isset($record['data']['created_by_name'])) {
            $record['created_by_name'] = $record['data']['created_by_name'];
        }

        return $record;
    }

}
