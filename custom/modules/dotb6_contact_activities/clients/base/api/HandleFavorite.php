<?php

class HandleFavorite extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'favorite' => array(
                'reqType' => 'PUT',
                'path' => array('dotb6_contact_activities','?', 'favorite'),
                'pathVars' => array('module','record', 'favorite'),
                'method' => 'setFavorite',
                'shortHelp' => 'This method sets a record of the specified type as a favorite',
                'longHelp' => 'include/api/help/module_record_favorite_put_help.html',
            ),
            'deleteFavorite' => array(
                'reqType' => 'DELETE',
                'path' => array('dotb6_contact_activities','?', 'favorite'),
                'pathVars' => array('module','record', 'favorite'),
                'method' => 'unsetFavorite',
                'shortHelp' => 'This method unsets a record of the specified type as a favorite',
                'longHelp' => 'include/api/help/module_record_favorite_delete_help.html',
            ),
            'unfavorite' => array(
                'reqType' => 'PUT',
                'path' => array('dotb6_contact_activities','?', 'unfavorite'),
                'pathVars' => array('module','record', 'unfavorite'),
                'method' => 'unsetFavorite',
                'shortHelp' => 'This method unsets a record of the specified type as a favorite',
                'longHelp' => 'include/api/help/module_record_favorite_delete_help.html',
            ),
        );
    }
    
    
    /**
     * (non-PHPdoc)
     * @see ModuleApi::setFavorite()
     */
    public function setFavorite($api, $args)
    {
        $this->requireArgs($args, array('module', 'record'));
        $bean = $this->loadBean($api, $args, 'view');

        $args['module'] = $bean->parent_type;
        $args['record'] = $bean->parent_id;
        return parent::setFavorite($api, $args);
    }
    

    /**
     * (non-PHPdoc)
     * @see ModuleApi::unsetFavorite()
     */
    public function unsetFavorite($api, $args)
    {
        $this->requireArgs($args, array('module', 'record'));
        $bean = $this->loadBean($api, $args, 'view');

        $args['module'] = $bean->parent_type;
        $args['record'] = $bean->parent_id;
        return parent::unsetFavorite($api, $args);
    }
}