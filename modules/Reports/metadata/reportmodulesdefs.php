<?php

/**
 * CRED-1029 : Reporting: Related Modules
 * 
 * Removing Prospects List (Target Lists) from list of Exempt Modules
 */

$exists = array_search("ProspectLists", $exemptModules);
unset($exemptModules[$exists]);
