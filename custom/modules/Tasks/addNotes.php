<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addNotes {

    static $already_ran = false;
    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function addNotesToActivities($bean, $event, $arguments) {
        if (self::$already_ran == true)
            return;
        self::$already_ran = true;
        require_once 'modules/ModuleBuilder/views/view.modulefields.php';
        if ($arguments['related_module'] == 'Notes' && !empty($arguments['related_id']) && !empty($bean->parent_id)) {
            $parent = BeanFactory::getBean($bean->parent_type, $bean->parent_id);
            $relatedNote = BeanFactory::getBean("Notes", $arguments['related_id']);
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
            $newCopy->processed = true;
            $newCopy->save();
            if (file_exists("upload/$relatedNote->id")) {
                copy("upload/$relatedNote->id", "upload/$newCopy->id");
            }
             $parent->load_relationship('notes');
             $parent->notes->add($newCopy->id);
        }
    }

}
