({
    /**
     * CRED-941 : Consolidated View of Tasks and Calls 
     */
    translatedVal : null,
    
    initialize: function (options) {
        this._super('initialize', [options]);
    },

    _render: function ()
    {
        this.translatedVal = app.lang.getAppListStrings('dotb6_contact_activities_status_list')[this.model.get(this.name)];
        this._super('_render');
    },
})