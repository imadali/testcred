<?php

/*
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
        );
    }

    public function getSiteUrl($api, $args)
    {
        global $sugar_config;
        $siteUrl = $sugar_config['site_url'];
        return $siteUrl;
    }

}
