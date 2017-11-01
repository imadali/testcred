<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class getRelatedContactApi extends SugarApi {
	/**
	* @function registerApiRest
	* @description registering the API call to make project status field read-only or not
	* @return type
	*/
	public function registerApiRest() {
		return array(
			'getRiskProfilingData' => array(
				'reqType' => 'GET',
				'path' => array('Leads','?','getRelatedContact'),
                'pathVars' => array('module','record','action'),
				'method' => 'retrieveRelatedContact',
				'shortHelp' => 'This api will return the most recent contact linked to the given lead with relation "Partner" or "Married"',
				'longHelp' => '',
			),
		);
	}
	
	/**
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function retrieveRelatedContact(ServiceBase $api, array $args)
    {
        $bean = $this->loadBean($api, $args, 'view');
        
        if (empty($bean->id)) {
            throw new SugarApiExceptionNotFound();
        }
        
        $bean->load_relationship('leads_contacts_1');
        
        $params = array(
            'where' => 'relative_type_c IN ("partner","married")',
            'orderby' => 'date_entered DESC',
            'limit' => 1,
        );
        $contactLinks = $bean->leads_contacts_1->getBeans($params);
        
        if (empty($contactLinks)) {
            $partner = BeanFactory::getBean('Contacts');
        } else {
            $familyRelative = array_pop($contactLinks);
			$partner = BeanFactory::getBean('Contacts', $familyRelative->id);
        }

        // formatBean is soft on view so that creates without view access will still work
        if (!$partner->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('SUGAR_API_EXCEPTION_RECORD_NOT_AUTHORIZED',array('view'));
        }
        
        $api->action = 'view';
        $data = $this->formatBean($api, $args, $partner);
        // $GLOBALS['log']->fatal("retrieveRelatedContact");
		// $GLOBALS['log']->fatal(print_r($data,true));
        return $data;
    }
}

?>