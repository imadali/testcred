<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class skipWeekend {

    function skipSatSun($bean, $event, $arguments) {
		$date_due=$bean->date_due;
		// $GLOBALS['log']->dubug("bean due date: " . $bean->date_due);
		 
        if ($bean->fetched_row['date_due'] != $date_due) {
            global $current_user;
            $timeDate = new TimeDate();
			
			$hours=date('H', strtotime($date_due));
			// $GLOBALS['log']->dubug("hours1: " . $hours);
			$localDate = $timeDate->to_display_date_time($bean->date_due, true, true, $current_user);
			$hours=date('H', strtotime($localDate));
			// $GLOBALS['log']->dubug("hours2: " . $hours);
			// $GLOBALS['log']->dubug("localDate: " . $localDate);
			
			$day = date('w', strtotime($localDate));
			// $GLOBALS['log']->dubug("day: " . $day);
			
			if ($day == 0) {
                $bean->date_due = date('Y-m-d H:i:s', strtotime($date_due . ' + 1 days'));
            } else if ($day == 6) {
                $bean->date_due = date('Y-m-d H:i:s', strtotime($date_due . ' + 2 days'));
            }
			
            /*$hours=date('H', strtotime($date_due));
			$GLOBALS['log']->fatal("hours: " . $hours);
            if($hours > 18 && $hours < 24)
            $date_due = date('Y-m-d H:i:s', strtotime($date_due . ' + 1 days'));    
            $day = date('w', strtotime($date_due));
            //$localDate = $timeDate->to_display_date_time($bean->date_due, true, true, $current_user);
            //$day = date('w', strtotime($localDate));
            if ($day == 0) {
                $bean->date_due = date('Y-m-d H:i:s', strtotime($bean->date_due . ' + 1 days'));
            } else if ($day == 6) {
                $bean->date_due = date('Y-m-d H:i:s', strtotime($bean->date_due . ' + 2 days'));
            } */
        }
    }

}
