<?php
/**
 * Created by PhpStorm.
 * User: mehmet.kizilmese
 * Date: 2/03/2015
 * Time: 9:16
 */
class ActiveCon extends SugarApi
{
    // This function is only called whenever the rest service cache file is deleted.
    // This shoud return an array of arrays that define how different paths map to different functions
    public function registerApiRest() {
        return array(
            'getAtRisk' => array(
                // What type of HTTP request to match against, we support GET/PUT/POST/DELETE
                'reqType' => 'GET',
                // This is the path you are hoping to match, it also accepts wildcards of ? and <module>
                'path' => array('XperiDo', 'active_con'),
                // These take elements from the path and use them to populate $args
                'pathVars' => array('', ''),
                // This is the method name in this class that the url maps to
                'method' => 'getActiveCon',
                // The shortHelp is vital, without it you will not see your endpoint in the /help
                'shortHelp' => 'Get Active Connection for Xperido',
                // The longHelp points to an HTML file and will be there on /help for people to expand and show
                'longHelp' => '',
            ),
        );
    }

    function getActiveCon($api, $args)
    {
        // Start off with something simple so we can verify the endpoint is registered.

        $query = new SugarQuery();
        $query->select(array('id','name','connectionname','xperidoconfigurationurl','xperidoserviceurl','xperidocrminstance'));
        $query->from(BeanFactory::getBean('X01_XperiDoConnection'));
        $query->where()->equals('active','1');
        $query->orderBy('date_modified', 'DESC');
        $results = $query->execute();

        return $results;
    }
}