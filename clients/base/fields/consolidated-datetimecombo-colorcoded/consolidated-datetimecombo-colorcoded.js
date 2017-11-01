({
    /**
     * CRED-941 : Consolidated View of Tasks and Calls 
     */
    extendsFrom: 'DatetimecomboColorcodedField',

    /**
     * @inheritdoc
     *
     * Checks color code conditions to determine if field should have
     * color applied to it.
     */
    _render: function() {
        this._super('_render');
    },

    /**
    * Check if status is completed based on status value defined in the view def
    *
    * @return {Boolean}
    * @private
    */
    _isCompletedStatus: function() {
        if (_.isUndefined(this.def.completed_status_value)) {
            return false;
        }
        if(_.indexOf(this.def.completed_status_value, this.model.get('status')) !== -1) {
            return true;
        } else {
            return false
        }
    },

})
