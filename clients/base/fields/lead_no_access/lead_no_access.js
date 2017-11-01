({
    extendsFrom: 'RowactionField',
    initialize: function(options) {
        this._super('initialize', [options]);
    },

    hasAccess: function() {
        var parent = this.model._syncedAttributes._module;
        var status = this.model.get('credit_request_status_id_c');
        
        if(_.isEqual(parent,"Leads") && !_.isEqual(status,'10_active') && !_.isEqual(status,'11_closed') ) {
            var status1 = true;
            return status1 && this._super('hasAccess');
        }
        else{
            var status2 = false;
            return status2 && this._super('hasAccess');
        }
    }
})