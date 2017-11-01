<?php

class GetXDRolesForUser extends SugarApi
{
    // This function is only called whenever the rest service cache file is deleted.
    // This shoud return an array of arrays that define how different paths map to different functions
    public function registerApiRest() {
        return array(
            'getXDRolesForUser' => array(
                // What type of HTTP request to match against, we support GET/PUT/POST/DELETE
                'reqType' => 'GET',
                // This is the path you are hoping to match, it also accepts wildcards of ? and <module>
                'path' => array('XperiDo', 'get_xperidoroles'),
                // These take elements from the path and use them to populate $args
                'pathVars' => array('', ''),
                // This is the method name in this class that the url maps to
                'method' => 'getXDRolesForUserMethod',
                // The shortHelp is vital, without it you will not see your endpoint in the /help
                'shortHelp' => 'Lists if user is in XperiDo roles',
                // The longHelp points to an HTML file and will be there on /help for people to expand and show
                'longHelp' => '',
            ),
        );
    }
    
    function getXDRolesForUserMethod($api, $args)
    {
        // Start off with something simple so we can verify the endpoint is registered.
        $rolename = '';
		$roleid = '';
        $idCurrentUser = $args['currentUser'];
		$guid = strtoupper($idCurrentUser);
		if (!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $guid)) 
		{
		  return $rolename;
		}
    
        $sql1 = "SELECT acl_roles_users.role_id FROM acl_roles_users JOIN acl_roles on acl_roles_users.role_id = acl_roles.id WHERE user_id = '{$idCurrentUser}' and name like '%XperiDo%' and acl_roles_users.deleted = '0'";// limit 1";
        $result1 = $GLOBALS['db']->query($sql1);		
        while($row1 = $GLOBALS['db']->fetchByAssoc($result1))
        {		
			$roleid = $row1['role_id'];
			$sql = "SELECT acl_roles.name FROM acl_roles WHERE id = '{$roleid}'";
			$result = $GLOBALS['db']->query($sql);
			while($row = $GLOBALS['db']->fetchByAssoc($result))
			{
				$rolename =  $rolename . "," . $row['name'];
			}
        }
		if(!empty($rolename))
		{
			$rolename = substr($rolename,1);
		}
		
        return $rolename; 
    }
}