<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class compareArticlesDashletApi extends SugarApi {
    public function registerApiRest() {
        return array(
            'KBBodyComparison' => array(
                'reqType' => 'GET',
                'path' => array('KBContents', 'KBBodyComparison', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getKBBodyComparison',
                'shortHelp' => 'This api will return changes in the body field of a KB record',
                'longHelp' => '',
            ),
        );
    }
	
    public function getKBBodyComparison(ServiceBase $api, array $args) {
        global $db;
        require("custom/include/htmlDiff/html_diff.php");
        $record_id = $args['id'];
        // $kbBean = BeanFactory::getBean("KBContents", $record_id);
        // $current_body = strip_tags($kbBean->kbdocument_body);

        $audit_query = "SELECT before_value_string,after_value_string FROM kbcontents_audit WHERE parent_id = '$record_id' AND field_name = 'kbdocument_body' AND deleted=0 ORDER BY date_created DESC LIMIT 0,1";
        $audit_result = $db->query($audit_query);
        $previous_body = '';
        while ($audit_row = $db->fetchByAssoc($audit_result)){
            $previous_body = $audit_row['before_value_string'];
            $current_body  = $audit_row['after_value_string'];
        }

        $body_diff = html_diff($previous_body, $current_body);
        // for dashlet add css to display uls
        $body_diff = str_replace('<ul>', '<ul  style="list-style: circle !important;margin-left: 5%;">', $body_diff);
        $body_diff = js_escape($body_diff);

        return $body_diff;
    }
}
