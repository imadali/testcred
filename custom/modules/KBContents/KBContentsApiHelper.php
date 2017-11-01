<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once 'modules/KBContents/KBContentsApiHelper.php';

class CustomKBContentsApiHelper extends KBContentsApiHelper {

    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array()) {

        /* 
         * Adding View Count Value to Audit Table as Sugar is using query to update
         */
        $result = parent::formatForApi($bean, $fieldList, $options);
        if ($this->api->action == 'view' && !empty($this->api->getRequest()->args['viewed'])) {
            global $current_user, $timedate;
            $audit_id = create_guid();
            $CurrenrDateTime = $timedate->getInstance()->nowDb();
            $date = date('Y-m-d H:i:s', strtotime($CurrenrDateTime));
            $audit_insert = 'INSERT INTO kbcontents_audit(id, parent_id, date_created, data_type, created_by, field_name, before_value_string, after_value_string)'
                    . ' VALUES("' . $audit_id . '", "' . $bean->id . '","' . $date . '","int", "' . $current_user->id . '", "viewcount","' . ($bean->viewcount - 1) . '","' . $bean->viewcount . '")';
            $GLOBALS['db']->query($audit_insert);
        }

        return $result;
    }

}
