<?php

class ConvertAgain
{
    function saveConvertAgain($bean, $event, $arguments)
    {
        if(!empty($bean->dot11_document_log_documentsdocuments_ida) 
                && $bean->convert_again 
                && $bean->convert_again!= $bean->fetched_row['convert_again'] ){
            
            $update_documents = 'UPDATE documents SET converted = 0 '
                    . ' WHERE id ="'.$bean->dot11_document_log_documentsdocuments_ida.'" AND deleted = 0 ';
            $GLOBALS['db']->query($update_documents);
            
            $GLOBALS['log']->debug('Update Documents :: '.$update_documents);
        }
    }
}

