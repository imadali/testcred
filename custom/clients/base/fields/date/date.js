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
({
    extendsFrom: 'DateField',
    initialize: function (options) {
        this._super('initialize', [options]);
    }, 
    unbindDom: function () {
        this._super('unbindDom');
        if (this._inDetailMode()) {
           // return;
        }
        $('.main-pane, .flex-list-view-content').off('scroll.' + this.cid);
        var $field = this.$(this.fieldTag), datePicker = $field.data('datepicker');
        if (datePicker && !datePicker.hidden) {
            $field.datepicker('hide');
        }
    },
	
	_dispose: function() {
        // FIXME: new date picker versions have support for plugin removal/destroy
        // we should do the upgrade in order to prevent memory leaks
		
        /* if (this._hasDatePicker) {
            $(window).off('resize', this.$(this.fieldTag).data('datepicker').place);
        } */
		var $field = this.$(this.fieldTag);
        if ($field.data('datepicker')) {
            $(window).off('resize', $field.data('datepicker').place);
        }

        this._super('_dispose');
    }
})