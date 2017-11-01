<?php
class SaveDocAttachment
{
	function save($bean, $event, $arguments)
	{
		if(!empty($bean->filename)){
			$bean->rev_file_name = $bean->filename;
		}
		if(empty($bean->document_revision_id) || $bean->document_revision_id == ""){
			$bean->rev_file_name = "";	
		}
		if(!empty($bean->filename) && empty($bean->document_name)){
			$bean->document_name = $bean->filename;
		}
	}
}
?>