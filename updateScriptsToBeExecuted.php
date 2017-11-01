<?php

/**
 * CRED-876 : Scripts to be executed after upgrade
 */
    function dashboards_run()
    {
		global $db;
        // Get all live dashboards. For each dashboard, remove the learning
        // resources dashlet.
        $sql = "SELECT id, metadata
                FROM dashboards
                WHERE deleted=0";
        $result = $db->query($sql);
        $queryTemplate = "UPDATE dashboards SET metadata='%s' WHERE id='%s'";
        while ($dashboard = $db->fetchByAssoc($result, false)) {
            $metadata = json_decode($dashboard['metadata']);
            $changed = deleteLearningResources($metadata);

            // If there is no change, skip.
            if (!$changed) {
                continue;
            }

            $metadata = json_encode($metadata);
            $db->query(
			// $GLOBALS['log']->fatal(
                sprintf(
                    $queryTemplate,
                    $metadata,
                    $db->quote($dashboard['id'])
                )
            );
        }
    }

    /**
     * Removes Learning Resources Dashlet from passed in metadata.
     *
     * @param array metadata The dashboard metadata
     * @return boolean true if changed, otherwise false
     */
   function deleteLearningResources($metadata)
    {
        if (!property_exists($metadata, 'components')) {
            return false;
        }

        $changed = false;
        $components = $metadata->components;

        // Parse through the dashboard metadata and remove the learning-
        // resources dashlet.

        // Note that we need to remove the learning-resources dashlet  at the
        // `$column->rows` level, since each dashlet is an ordered array
        // containing an unordered array, which has the dashlet metadata.
        foreach ($components as $component) {
            if (!property_exists($component, 'rows')) {
                continue;
            }

            // Store the rows that become empty as a result of deleting the
            // Learning Resources dashlet. We can't delete them yet because
            // we are looping over `$component->rows[$rowsKey]`, and if we
            // delete them in place, it will throw the $rowsKey off.
            $emptyRowsKeys = array();
            foreach ($component->rows as $rowsKey => $rows) {
                $dashletsToDelete = array();
                foreach ($rows as $dashletKey => $dashlet) {
                    if (!property_exists($dashlet, 'view')) {
                        continue;
                    }
                    $view = $dashlet->view;
                    $shouldRemove = property_exists($view, 'type') && $view->type === 'learning-resources';
                    if ($shouldRemove) {
                        // Mark this learning-resources dashlet for deletion.
                        // Don't delete it now, because that will screw up the
                        // iteration.
                        $dashletsToDelete[] = $dashletKey;
                        $changed = true;
                    }
                }

                // Loop backward through the dashlets to delete and remove them.
                // This must be done backwards to avoid messing up the indexes
                // on $component->rows[$rowsKey].
                $lastDashletIndex = count($dashletsToDelete) - 1;
                for ($i = $lastDashletIndex; $i >= 0; $i--) {
                    array_splice($component->rows[$rowsKey], $dashletsToDelete[$i], 1);
                }

                // If the removal removed the last element of the row,
                // mark this row for deletion.
                // Don't delete it now, because that will screw up the
                // iteration.
                if (count($component->rows[$rowsKey]) === 0) {
                    $emptyRowsKeys[] = $rowsKey;
                }
            }

            // Delete the rows that have become empty as a result of deleting
            // the Learning Resources dashlet.
            // This must be done backwards to avoid messing up the indexes
            // on $emptyRowsKeys.
            for ($i = count($emptyRowsKeys) - 1; $i >=0; $i--) {
                array_splice($component->rows, $emptyRowsKeys[$i], 1);
            }
        }
        return $changed;
    }
	
	function TimePeriod_run()
    {
		global $db;
        $sql = "select id, start_date, end_date from timeperiods";
        $results = $db->query($sql);

        $dt = TimeDate::getInstance();
        $dt->setAlwaysDb(true);

        $updateSql = "UPDATE timeperiods SET start_date_timestamp = '%d', end_date_timestamp = '%d' where id = '%s'";
        while ($row = $db->fetchRow($results)) {
            $db->query(
			// $GLOBALS['log']->fatal(
                sprintf(
                    $updateSql,
                    strtotime(substr($row['start_date'], 0, 10) . ' 00:00:00'),
                    strtotime(substr($row['end_date'], 0, 10) . ' 23:59:59'),
                    $row['id']
                )
            );
        }

        $dt->setAlwaysDb(false);
    }
	
	function Users_run()
    {
        $user  = BeanFactory::newBean('Users');
        $users = get_user_array(false);

        foreach ($users as $userId => $userName) {
            $user->retrieve($userId);
            $emailClientPreference = $user->getPreference('email_link_type');

            if ($emailClientPreference == 'sugar') {
                $mailerPreferenceStatus = OutboundEmailConfigurationPeer::getMailConfigurationStatusForUser($user, 'sugar');
                if ($mailerPreferenceStatus != OutboundEmailConfigurationPeer::STATUS_VALID_CONFIG) {
                    $user->setPreference('email_link_type', 'mailto');
                }
                $user->savePreferencesToDB();
            }
        }
    }
	
	function pmse_Projects_run()
    {
		global $db;
        // $this->log('Droping `cas_pre_data` and `cas_data` columns from `pmse_bpm_form_action` table...');

        $query = $db->dropColumnSQL('pmse_bpm_form_action', array(
            array('name' => 'cas_pre_data'),
            array('name' => 'cas_data'),
        ));
        // $this->log('Generated sql to drop columns: ' . $query);

        if ($db->query($query)) {
            // $this->log('Columns were dropped');
        } else {
            // $this->log('Failed to drop columns');
        }
    }
	
	function ACLRoles_run()
    {
		global $db;
        $registrar = new AclRoleSetRegistrar();

        $result = $db->query("SELECT id FROM users where deleted = 0");
        while ($row = $db->fetchByAssoc($result)) {
            $user = BeanFactory::retrieveBean('Users', $row['id']);
            if ($user) {
                // $this->log('Registering ACL role sets for user ' . $user->id);
                $registrar->registerAclRoleSet($user);
            }
        }
    }
	
	
	dashboards_run();
	TimePeriod_run();
	Users_run();
	pmse_Projects_run();
	ACLRoles_run();
	

echo "Scripts executed";
?>