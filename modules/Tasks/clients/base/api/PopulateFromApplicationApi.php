<?php

class PopulateFromApplicationApi extends SugarApi {

    public function registerApiRest() {
        return array(
            'PopulateFromApplication' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'PopulateTask',),
                'pathVars' => array('module', '',),
                'method' => 'populateFromApplication',
                'shortHelp' => 'This api will populate values from  application to new Task',
                'longHelp' => '',
            ),
        );
    }

    /*
     * Fetching values from thh related application on the basis of relation - 1:1 or 1:n
     */
    public function populateFromApplication(ServiceBase $api, array $args) {
        $parent_id = $args['parent_id'];
        $parent_type = $args['parent_type'];
        $appData = array();
        if (isset($args['parent_id']) && isset($args['parent_type']) && $parent_type == "Leads") {
            $leadBean = BeanFactory::getBean($parent_type, $parent_id);
            $leadBean->load_relationship('leads_opportunities_1');
            if ($appBeans = $leadBean->leads_opportunities_1->getBeans()) {
                foreach ($appBeans as $app) {
                    if (!empty($appBeans) && sizeof($appBeans) == 1) {
                        $appData['count'] = 'single';
                        $appData['user_id_c'] = $app->user_id_c;
                        $appData['approval'] = $app->dotb_user_approval_c;
                        $appData['provider'] = $app->provider_id_c;
                        $appData['provider_contract'] = $app->provider_contract_no;
                    } else if (!empty($appBeans) && sizeof($appBeans) > 1) {
                        $appData['count'] = 'multiple';
                        $appData[$app->id] = $app->name;
                    }
                }
            } else {
                $appData['count'] = 'none';
            }
        }
        return $appData;
    }

}

?>