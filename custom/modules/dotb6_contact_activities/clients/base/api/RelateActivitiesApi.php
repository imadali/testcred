<?php

class RelateActivitiesApi extends RelateRecordApi {

    public function registerApiRest() {
        return array(
            'deleteRelatedLink' => array(
                'reqType' => 'DELETE',
                'path' => array(
                    'Contacts',
                    '?',
                    'link',
                    'contacts_dotb6_contact_activities',
                    '?'
                ),
                'pathVars' => array(
                    'module',
                    'record',
                    '',
                    'link_name',
                    'remote_id'
                ),
                'method' => 'deleteRelatedLink',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp' => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html'
            ),
            'deleteRelatedLinkForLeads' => array(
                'reqType' => 'DELETE',
                'path' => array(
                    'Leads',
                    '?',
                    'link',
                    'leads_dotb6_contact_activities',
                    '?'
                ),
                'pathVars' => array(
                    'module',
                    'record',
                    '',
                    'link_name',
                    'remote_id'
                ),
                'method' => 'deleteRelatedLinkForLeads',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp' => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html'
            ),
            'deleteRelatedLinkForAccounts' => array(
                'reqType' => 'DELETE',
                'path' => array(
                    'Accounts',
                    '?',
                    'link',
                    'accounts_dotb6_contact_activities',
                    '?'
                ),
                'pathVars' => array(
                    'module',
                    'record',
                    '',
                    'link_name',
                    'remote_id'
                ),
                'method' => 'deleteRelatedLinkForAccounts',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp' => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html'
            ),
            'deleteRelatedLinkForOpportunities' => array(
                'reqType' => 'DELETE',
                'path' => array(
                    'Opportunities',
                    '?',
                    'link',
                    'opportunities_dotb6_contact_activities',
                    '?'
                ),
                'pathVars' => array(
                    'module',
                    'record',
                    '',
                    'link_name',
                    'remote_id'
                ),
                'method' => 'deleteRelatedLinkForOpportunities',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp' => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html'
            )
        );
    }

    public function deleteRelatedLink($api, $args) {
        $logFile = 'cache/comparis_activity_tests.log';
        file_put_contents($logFile, "entering custom unlink API\n", FILE_APPEND);
        $linkSeed = BeanFactory::getBean('dotb6_contact_activities', $args['remote_id']);
        file_put_contents($logFile, "before args :\n" . print_r($args, true) . "\n", FILE_APPEND);
        $args['remote_id'] = $linkSeed->parent_id;

        if (!empty($linkSeed->parent_type)) {
            switch ($linkSeed->parent_type) {
                case 'Calls' :
                    $args['link_name'] = 'calls';
                    break;
                case 'Emails' :
                    $args['link_name'] = 'archived_emails';
                    break;
                case 'Meetings' :
                    $args['link_name'] = 'meetings';
                    break;
                case 'Notes' :
                    $args['link_name'] = 'notes';
                    break;
                case 'Tasks' :
                    $args['link_name'] = 'all_tasks';
                    break;
            }
        }
        file_put_contents($logFile, "after args :\n" . print_r($args, true) . "\n", FILE_APPEND);

        return parent::deleteRelatedLink($api, $args);
    }

    public function deleteRelatedLinkForLeads($api, $args) {
        $logFile = 'cache/comparis_activity_tests.log';
        file_put_contents($logFile, "entering custom unlink API\n", FILE_APPEND);
        $linkSeed = BeanFactory::getBean('dotb6_contact_activities', $args['remote_id']);
        file_put_contents($logFile, "before args :\n" . print_r($args, true) . "\n", FILE_APPEND);
        $args['remote_id'] = $linkSeed->parent_id;

        if (!empty($linkSeed->parent_type)) {
            switch ($linkSeed->parent_type) {
                case 'Calls' :
                    $args['link_name'] = 'calls';
                    break;
                case 'Emails' :
                    $args['link_name'] = 'archived_emails';
                    break;
                case 'Meetings' :
                    $args['link_name'] = 'meetings';
                    break;
                case 'Notes' :
                    $args['link_name'] = 'notes';
                    break;
                case 'Tasks' :
                    $args['link_name'] = 'tasks';
                    break;
            }
        }
        file_put_contents($logFile, "after args :\n" . print_r($args, true) . "\n", FILE_APPEND);

        return parent::deleteRelatedLink($api, $args);
    }

    public function deleteRelatedLinkForAccounts($api, $args) {
        $logFile = 'cache/comparis_activity_tests.log';
        file_put_contents($logFile, "entering custom unlink API\n", FILE_APPEND);
        $linkSeed = BeanFactory::getBean('dotb6_contact_activities', $args['remote_id']);
        file_put_contents($logFile, "before args :\n" . print_r($args, true) . "\n", FILE_APPEND);
        $args['remote_id'] = $linkSeed->parent_id;

        if (!empty($linkSeed->parent_type)) {
            switch ($linkSeed->parent_type) {
                case 'Calls' :
                    $args['link_name'] = 'calls';
                    break;
                case 'Emails' :
                    $args['link_name'] = 'archived_emails';
                    break;
                case 'Meetings' :
                    $args['link_name'] = 'meetings';
                    break;
                case 'Notes' :
                    $args['link_name'] = 'notes';
                    break;
                case 'Tasks' :
                    $args['link_name'] = 'tasks';
                    break;
            }
        }
        file_put_contents($logFile, "after args :\n" . print_r($args, true) . "\n", FILE_APPEND);

        return parent::deleteRelatedLink($api, $args);
    }

    public function deleteRelatedLinkForOpportunities($api, $args) {
        $logFile = 'cache/comparis_activity_tests.log';
        file_put_contents($logFile, "entering custom unlink API\n", FILE_APPEND);
        $linkSeed = BeanFactory::getBean('dotb6_contact_activities', $args['remote_id']);
        file_put_contents($logFile, "before args :\n" . print_r($args, true) . "\n", FILE_APPEND);
        $args['remote_id'] = $linkSeed->parent_id;

        if (!empty($linkSeed->parent_type)) {
            switch ($linkSeed->parent_type) {
                case 'Calls' :
                    $args['link_name'] = 'calls';
                    break;
                case 'Emails' :
                    $args['link_name'] = 'archived_emails';
                    break;
                case 'Meetings' :
                    $args['link_name'] = 'meetings';
                    break;
                case 'Notes' :
                    $args['link_name'] = 'notes';
                    break;
                case 'Tasks' :
                    $args['link_name'] = 'tasks';
                    break;
            }
        }
        file_put_contents($logFile, "after args :\n" . print_r($args, true) . "\n", FILE_APPEND);

        return parent::deleteRelatedLink($api, $args);
    }

}
