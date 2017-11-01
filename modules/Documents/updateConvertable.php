<?php

class updateConvertable
{
	/**
	** If document revision id changes do the following:
	** 1. updated converted bit
	** 2. Unlink the image created earlier
	**/
	function updateDocumentBit($bean, $event, $arguments)
	{
		if($bean->fetched_row['document_revision_id'] != $bean->document_revision_id){
			$bean->converted = '0';
			
			//get the revision id
			$doc_revision_query = "SELECT id FROM document_revisions WHERE deleted='1' AND document_id='".$bean->id."' ORDER BY date_modified DESC LIMIT 0,1";
			$doc_revision_result = $GLOBALS['db']->query($doc_revision_query);
			// $GLOBALS['log']->fatal($doc_revision_query);
			$last_revision_id = '';
			while($revison_row = $GLOBALS['db']->fetchByAssoc($doc_revision_result)){
				$last_revision_id = $revison_row['id'];
			}
			
			if(!empty($last_revision_id)){
				// unlink image created earlier
				$image_directory = $GLOBALS['sugar_config']['thumbnails'].'/'.$last_revision_id;
				$this->recursiveRemoveDirectory($image_directory);	
			}
			
		}
	}
	
	function recursiveRemoveDirectory($target) {
		if(is_dir($target)){
			$files = glob( $target . '/*');
			
			foreach( $files as $file )
			{
				$this->recursiveRemoveDirectory( $file );      
			}
		  
			rmdir( $target );
		} elseif(is_file($target)) {
			unlink( $target );  
		}
	}
}
?>