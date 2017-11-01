({
    /**
     * CRED-909 : [Upgrade]In line Delete button does
     *  not show popup message for non- admin user 
     */
    extendsFrom: 'RecordlistView',

    warnDelete: function(model) {
        if(app.user.get('type') != 'admin'){
            app.alert.show("regular-user", {
                level: 'info',
                messages: app.lang.get('LBL_REGULAR_USER_DELETE_MSG'),
                autoClose: false
            });
        } else {
            var self = this;
            this._modelToDelete = model;

            self._targetUrl = Backbone.history.getFragment();
            if (self._targetUrl !== self._currentUrl) {
                app.router.navigate(self._currentUrl, {trigger: false, replace: true});
            }

            app.alert.show('delete_confirmation', {
                level: 'confirmation',
                messages: self.getDeleteMessages(model).confirmation,
                onConfirm: _.bind(self.deleteModel, self),
                onCancel: function() {
                    self._modelToDelete = null;
                }
            });
        }
    },
})
