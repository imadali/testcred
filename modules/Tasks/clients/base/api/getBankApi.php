<?php

require_once('include/SugarQuery/SugarQuery.php');

class getBankApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API call to make project status field read-only or not
     * @return type
     */
    public function registerApiRest() {
        return array(
             'getBank' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('Tasks', 'getBank'),
                'pathVars' => array('', ''),
                'method' => 'getBank',
                'shortHelp' => 'Auto Execute workflow selected by user',
                'longHelp' => '',
            ),
        );
    }

    public function getBank(ServiceBase $api, array $args) {
        return getTaskBank($args['lead_id']);
    }
}
