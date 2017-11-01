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
({extendsFrom: 'SubpanelListView',
    unlinkModel: function () {
        var self = this, model = this._modelToUnlink;
        model.destroy({
            showAlerts: {'process': true,
                'success': {messages: self.getUnlinkMessages(self._modelToUnlink).success}}, relate: true, success: function () {
                console.log('self');
                var parent = self._targetUrl;
                parent = parent.split("/");
                if (parent[0] == "Leads") {
                    app.events.trigger('record-saved');
                    app.events.trigger('addressAlert');
                }
                var redirect = self._targetUrl !== self._currentUrl;
                self._modelToUnlink = null;
                self.collection.remove(model, {silent: redirect});
                if (redirect) {
                    self.unbindBeforeRouteUnlink();
                    app.router.navigate(self._targetUrl, {trigger: true});
                    return;
                }
                self.collection.trigger('reset');
                self.render();
            }});
    },
    render: function(){
        app.events.trigger('record-saved');
        this._super('render');
    }
})