<?php
/**
 * Created by PhpStorm.
 * User: mehmet.kizilmese
 * Date: 2/03/2015
 * Time: 9:16
 */
class MetaDataFields extends SugarApi
{
    // This function is only called whenever the rest service cache file is deleted.
    // This shoud return an array of arrays that define how different paths map to different functions
    public function registerApiRest() {
        return array(
            'getTempMetaData' => array(
                // What type of HTTP request to match against, we support GET/PUT/POST/DELETE
                'reqType' => 'GET',
                // This is the path you are hoping to match, it also accepts wildcards of ? and <module>
                'path' => array('XperiDo', 'get_Templates'),
                // These take elements from the path and use them to populate $args
                'pathVars' => array('', ''),
                // This is the method name in this class that the url maps to
                'method' => 'getTemplates',
                // The shortHelp is vital, without it you will not see your endpoint in the /help
                'shortHelp' => 'Get Data from XperiDo MetaData',
                // The longHelp points to an HTML file and will be there on /help for people to expand and show
                'longHelp' => '',
            ),
        );
    }

    function getTemplates($api, $args)
    {
        // Start off with something simple so we can verify the endpoint is registered.      
        $query = new SugarQuery();
        $query->select(array('id','name','invenso_value','invenso_xperidometadataname'));
        $query->from(BeanFactory::getBean('X01_XperiDoMetaData'));
        $query->where()->equals('invenso_category','Template');
        $query->where()->equals('invenso_subcategory','additional-fields');
		$query->orderby('invenso_xperidometadataname','ASC');
        $results = $query->execute();

        return $results;

    }
}