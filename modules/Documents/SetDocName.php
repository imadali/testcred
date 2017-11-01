<?php
class SetDocName
{
	function set($bean, $event, $arguments)
	{
		global $app_list_strings;
		if(empty($bean->document_name)){
			$link = 'documents_dotb7_document_tracking_1';
			if ($bean->load_relationship($link))
			{
				$relatedBeans = $bean->$link->getBeans();
				reset($relatedBeans);        
        		$docTrack = current($relatedBeans);
                        if(!empty($docTrack)){
                            $bean->name = $docTrack->fetched_row['category'];
                            $document_name = empty($docTrack->fetched_row['category']) ? $docTrack->category : $docTrack->fetched_row['category'];
                            $sql = "UPDATE documents set document_name = '". $app_list_strings['dotb_document_category_list'][$document_name] . "' WHERE id = '$bean->id'";
                            $GLOBALS['db']->query($sql);
                        }
			}
		}
	}
}
?>