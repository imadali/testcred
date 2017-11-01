<?php

class syncAssign {

    function sync($bean, $event, $arguments) {
        if ($bean->load_relationship("leads_documents_1")) {
            $relatedLeads = $bean->leads_documents_1->getBeans();
            foreach ($relatedLeads as $relatedLead) {
                $GLOBALS['db']->query("UPDATE documents SET team_id='$relatedLead->team_id', team_set_id='$relatedLead->team_set_id',assigned_user_id='$relatedLead->assigned_user_id' WHERE id='$bean->id'");
                $bean->load_relationship('documents_dotb7_document_tracking_1');
                $document_tracking = $bean->documents_dotb7_document_tracking_1->getBeans();
                foreach ($document_tracking as $id => $document_tracking_bean) {
                    $update_doc_tracking="UPDATE dotb7_document_tracking SET team_id='$relatedLead->team_id', team_set_id='$relatedLead->team_set_id',assigned_user_id='$relatedLead->assigned_user_id' WHERE id='$document_tracking_bean->id'";
                    $GLOBALS['db']->query($update_doc_tracking);  
                }
            }
        }
    }

}

?>