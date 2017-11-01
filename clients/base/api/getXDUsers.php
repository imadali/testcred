<?php

class GetXDUsers extends SugarApi
{
    // This function is only called whenever the rest service cache file is deleted.
    // This shoud return an array of arrays that define how different paths map to different functions
    public function registerApiRest() {
        return array(
            'getXDUsers' => array(
                // What type of HTTP request to match against, we support GET/PUT/POST/DELETE
                'reqType' => 'GET',
                // This is the path you are hoping to match, it also accepts wildcards of ? and <module>
                'path' => array('XperiDo', 'get_xdusers'),
                // These take elements from the path and use them to populate $args
                'pathVars' => array('', ''),
                // This is the method name in this class that the url maps to
                'method' => 'getXDUsersMethod',
                // The shortHelp is vital, without it you will not see your endpoint in the /help
                'shortHelp' => 'Lists if user is in XperiDo roles',
                // The longHelp points to an HTML file and will be there on /help for people to expand and show
                'longHelp' => '',
            ),
        );
    }
    
    function getXDUsersMethod($api, $args)
    {
        // Start off with something simple so we can verify the endpoint is registered.
    
        $sql = "Select * from 
                        (
                                SELECT acl_roles_users.user_id, users.user_name, acl_roles.name, email_addresses.email_address				
                                FROM acl_roles_users 
                                JOIN acl_roles on acl_roles_users.role_id = acl_roles.id 
                                JOIN users on acl_roles_users.user_id = users.id
                                JOIN email_addr_bean_rel on email_addr_bean_rel.bean_id = users.id
                                JOIN email_addresses on email_addr_bean_rel.email_address_id = email_addresses.id
                                WHERE email_addr_bean_rel.primary_address = '1' and acl_roles.name like '%XperiDo%' and acl_roles_users.deleted = '0' AND email_addr_bean_rel.deleted = '0' AND email_addresses.deleted = '0'
                                UNION
                                SELECT users.id, users.user_name, 'XperiDo Administrator' as name, email_addresses.email_address
                                FROM users 
                                JOIN email_addr_bean_rel on email_addr_bean_rel.bean_id = users.id
                                JOIN email_addresses on email_addr_bean_rel.email_address_id = email_addresses.id
                                WHERE users.is_admin = '1' and users.deleted = '0' AND email_addr_bean_rel.primary_address = '1' AND email_addr_bean_rel.deleted = '0' AND email_addresses.deleted = '0'
                        ) as a order by name, user_name"; 
					
        $result = $GLOBALS['db']->query($sql);

        while($row = $GLOBALS['db']->fetchByAssoc($result))
        {		
			$usersinroles[] = $row;
        }
		
        return $usersinroles; 
    }
}