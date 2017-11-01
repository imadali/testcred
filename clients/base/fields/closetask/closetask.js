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
    extendsFrom: 'RowactionField',
    initialize: function(options) {

    this.options.def.events = _.extend({}, this.options.def.events, {
            'click [name="close_task"]': 'closeTask'
        });
        this._super('initialize', [options]);
    },
    
    closeTask : function(){
      
    },
    
    hasAccess: function() {
    //var parent = this.model.get('parent_type');
	var parent = this.model._syncedAttributes._module;
    var status = null;
        if(parent == "Tasks"){
            var status1 = true;
            return status1 && this._super('hasAccess');
        }
        else{
            var status2 = false;
            return status2 && this._super('hasAccess');
        }
    
    }       

})
