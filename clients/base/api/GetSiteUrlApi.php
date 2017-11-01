<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GetSiteUrlApi extends SugarApi
{

    public function registerApiRest()
    {
        return array(
            'getSiteUrl' => array(
                'reqType' => 'GET',
                'path' => array('get_site_url'),
                'pathVars' => array('', ''),
                'method' => 'getSiteUrl',
            ),
            
            'saveLeadListenerConfig' =>array(
                'reqType' => 'POST',
                'path' => array('saveLeadListenerConfig'),
                'pathVars' => array(''),
                'method' => 'saveLeadListenerConfig',
                'longHelp' => '',
            ),
            
            'retrieveLeadListenerConfig' =>array(
                'reqType' => 'GET',
                'path' => array('retrieveLeadListenerConfig'),
                'pathVars' => array(''),
                'method' => 'retrieveLeadListenerConfig',
                'longHelp' => '',
            ),
            
            'copyDashboardMeta' =>array(
                'reqType' => 'POST',
                'path' => array('copyDashboardMeta'),
                'pathVars' => array(''),
                'method' => 'copyDashboardMeta',
                'longHelp' => '',
            ),
            
        );
    }

    public function getSiteUrl($api, $args)
    {
        global $sugar_config;
        $siteUrl = $sugar_config['site_url'];
        return $siteUrl;
    }
    
    /**
     * CRED-804 : Adjustment of listener for ingoing leads
     * 
     * @param  type $api
     * @param  type $args
     * @return boolean
     */
    public function saveLeadListenerConfig($api, $args)
    {
        $this->requireArgs($args, array('configuration', 'type'));

        if ($args['type'] == 'old') {
            $query = "UPDATE config SET value = '" . $args['configuration'] . "' WHERE name = 'lead_listener' ";
        } else {
            $query = 'INSERT INTO config (category, name, value, platform) '
                    . " VALUES ( 'custom_admin_setting', 'lead_listener' , '" . $args['configuration'] . "', NULL ) ";
        }

        $GLOBALS['db']->query($query);
        
        return true;
    }
    
    /**
     * CRED-804 : Adjustment of listener for ingoing leads
     * 
     * @param  type $api
     * @param  type $args
     * @return boolean
     */
    public function retrieveLeadListenerConfig($api, $args)
    {
        $query = 'SELECT name, value FROM config WHERE name = "lead_listener"';
        $result = $GLOBALS['db']->query($query);
        
        $config = '';
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        $config = $row['value'];

        if (empty($config)) {
            return false;
        }
        return array('configuration'=> $config);
    }
    
    /**
     * CRED-943 : Sharing Dashboard
     * 
     * @param type $api
     * @param type $args
     */
    public function copyDashboardMeta($api, $args)
    {
        $this->requireArgs($args, array('selectedUsers', 'dashbaord', 'module'));

        $submit = 0;
        $users = $args['selectedUsers'];
        $dashboardId = $args['dashbaord'];
        $parentModule = $args['module'];

        $query = "Select * from dashboards WHERE deleted = 0 AND id = '$dashboardId' AND dashboard_module='$parentModule'";
        $result = $GLOBALS['db']->query($query);
        $dashboard = $GLOBALS['db']->fetchByAssoc($result);

        foreach ($users as $user) {
            /**
             * Saving shared Dasboard Information using Bean to maintain encoding
             */
            $dashBean = BeanFactory::getBean('Dashboards');
            $dashBean->name = $dashboard['name'];
            /* $dashBean->assigned_user_id = $user; */
            $dashBean->dashboard_module = $dashboard['dashboard_module'];
            $dashBean->metadata = $dashboard['metadata'];
            $dashBean->save();

            /**
             * CRED-1010 : Title-Spelling of Shared Dashboards: Vowel Mutations (Umlaut)
             * not shown correctly
             * Assigned user Id manually nees to be updated as Sugar overrides
             * save function in Dashboad Class
             */
            if ($dashBean->id) {
                $query_insert = "UPDATE dashboards SET assigned_user_id = '" . $user . "' WHERE id = '" . $dashBean->id . "'";
                $submit = $GLOBALS['db']->query($query_insert) ? 1 : 0;
                $GLOBALS['log']->debug($query_insert);
                if ($submit != 1) {
                    return false;
                }
            }
        }

        return true; 
    }

}
