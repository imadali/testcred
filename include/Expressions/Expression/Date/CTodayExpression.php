<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
require_once('include/Expressions/Expression/Date/DateExpression.php');

/**
 * <b>today()</b><br>
 * Returns a date object representing todays date.
 *
 */
class CTodayExpression extends DateExpression
{
	/**
     * The today function is sensitive to the current users timezone since it returns a day without time.
	 */
	function evaluate() {
        $d = TimeDate::getInstance()->getNow(true);
        $d->setTime(0,0,0);
		return $d;
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
           var today = new Date().toLocaleDateString('en-GB', {  
    year : 'numeric',        month : 'numeric', day : 'numeric',


}).split('/').join('-');
		  return today;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "cToday";
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 0;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>