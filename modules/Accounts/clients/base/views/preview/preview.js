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
    extendsFrom: 'PreviewView',
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    renderPreview: function(model, newCollection) {
        this._super('renderPreview', [model],[newCollection]);
        
        if(_.isEqual(model.module, 'Emails')) {
            $('div.activitystream-layout').hide();
        }
    },
    _renderPreview: function(model, collection, fetch, previewId) {
        if(app.drawer && !app.drawer.isActive(this.$el)){
            return; 
        }

        // Close preview if we are already displaying this model
        if (this.model && model && (this.model.get('id') == model.get('id') && previewId == this.previewId)) {
            // Remove the decoration of the highlighted row
            app.events.trigger('list:preview:decorate', false);
            // Close the preview panel
            app.events.trigger('preview:close');
            return;
        }

        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // if module is in BWC mode, just return
            //return;
        }

        if (model) {
            var viewName = 'preview',
                previewMeta = app.metadata.getView(model.module, 'preview'),
                recordMeta = app.metadata.getView(model.module, 'record');
            if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
                viewName = 'record';
            }
            this.meta = this._previewifyMetadata(_.extend({}, recordMeta, previewMeta));
            this.renderPreview(model, collection);
            fetch && model.fetch({
                showAlerts: true,
                view: viewName
            });
        }

        this.previewId = previewId;
    },
})