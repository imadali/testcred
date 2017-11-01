<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addNotesInLead {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function addNotesInLeadActivities($bean, $event, $arguments) {
        require_once 'modules/ModuleBuilder/views/view.modulefields.php';
        if ($arguments['related_module'] == 'Notes' && !empty($arguments['related_id']) && !empty($arguments['related_name']) && !empty($bean->contracts_leads_1leads_idb) && $arguments['relationship'] == 'contract_notes') {
            $parent = BeanFactory::getBean("Leads", $bean->contracts_leads_1leads_idb);
            $relatedNote = BeanFactory::getBean("Notes", $arguments['related_id']);

            $parent_notes = $parent->get_linked_beans('notes', 'Note');
            $create_copy = true;
            foreach ($parent_notes as $parent_note) {
                if ($parent_note->name == $relatedNote->name && $parent_note->date_entered == $relatedNote->date_entered) {
                    $create_copy = false;
                }
            }
            if ($create_copy) {
                $objectName = BeanFactory::getObjectName("Notes");
                VardefManager::loadVardef("Notes", $objectName, true);
                $viewmodfields = new ViewModulefields();
                global $dictionary;
                $newCopy = new Note();
                foreach ($dictionary[$objectName]['fields'] as $def) {
                    if ($viewmodfields->isValidStudioField($def)) {
                        $name = $def['name'];
                        $newCopy->$name = $relatedNote->$name;
                        $newCopy->id = "";
                    }
                }
                $newCopy->assigned_user_id = $relatedNote->assigned_user_id;
                $newCopy->file_url = $relatedNote->file_url;
                $newCopy->parent_id = $relatedNote->parent_id;
                $newCopy->contact_id = $relatedNote->contact_id;
                $newCopy->contact_name = $relatedNote->contact_name;
                $newCopy->save();
                if (file_exists("upload/$relatedNote->id")) {
                    copy("upload/$relatedNote->id", "upload/$newCopy->id");
                }
                $parent->load_relationship('notes');
                $parent->notes->add($newCopy->id);
            }
        }
    }

}
