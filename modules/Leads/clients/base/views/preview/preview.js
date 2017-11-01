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
    activities: null,
    previourId: null,
    showActivities: false,
    getActivities: true,
    offset: 0,
    noMore: false,
    firstRecord: null,
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    closePreview: function () {
        this._super('closePreview');
        if ($('.document-preview').length) {
            $('.document-preview').empty();
        }
    },
    events: {
        'click .fa-chevron-up': 'toggleActivities',
        'click .more-activities': 'moreActivities',
    },
    moreActivities: function (e) {
        var self = this;
        self.offset = self.offset + 20;
        self._render(self.offset);

    },
    toggleActivities: function (e) {
        $(".listing").toggle();
        $(".fa-chevron-up").toggleClass("fa-chevron-down");
    },
    switchPreview: function (data, index, id, module) {
        this._super('switchPreview');
    },
    _renderHtml: function () {
        this._super('_renderHtml');
        if ($('.document-preview').length) {
            $('.document-preview').empty();
        }
    },
    _render: function (offset) {
        this._super('_render');
        var self = this;
        var module = self.model.get('_module');
        if (module == "Leads") {
            $('div[name="leads_documents"]').parent().hide();
            $('div[name=""]').parent().hide();
            if (self.noMore) {
                $(".more-activities").parent().hide();
                self.offset = 0;
            } else {
                $(".more-activities").parent().show();
            }
            if (self.getActivities) {
                app.alert.show('loading-activities', {
                    level: 'process',
                    title: 'Laoding Activities...'
                });
                var id = self.model.get('id');
                if (typeof offset == 'undefined') {
                    var offset = 0;
                }
                if (self.previourId != id) {
                    self.activities = null;
                }
                self.previourId = id;
                var url = 'Leads/' + id + '/link/preview_activities/' + offset + '/date_entered:desc';
                App.api.call('get', App.api.buildURL(url), {id: id, activities: "yes"}, {
                    success: function (data) {
                        if (typeof data.records[0] != 'undefined') {
                            self.firstRecord = data.records[0]['id'];
                        }
                        if (data.records.length < 20) {
                            self.noMore = true;
                        } else {
                            self.noMore = false;
                        }
                        if (offset == 0) {
                            self.activities = data;
                        } else {
                            for (var i = 0; i < data.records.length; i++)
                                self.activities.records.push(data.records[i]);
                        }
                        self.getActivities = false;
                        self.showActivities = true;
                        app.alert.dismiss('loading-activities');
                        self._render();
                    },
                    error: function (e) {
                        throw e;
                    }
                });
            } else {
                self.getActivities = true;
                var li_id='#activity_'+self.firstRecord;
                if($(li_id).position()){
                    $('.activitiesStreams').animate({scrollTop: $(li_id).position().top - 100}, 'fast');
                }
            }
        } else {
            if (self.showActivities) {
                self.showActivities = false;
                self._render();
            }
        }
    },
    renderPreview: function (model, newCollection) {
        this._super('renderPreview', [model], [newCollection]);

        if (_.isEqual(model.module, 'Emails')) {
            $('div.activitystream-layout').hide();
        }
    },
    _renderPreview: function (model, collection, fetch, previewId) {

        if (app.drawer && !app.drawer.isActive(this.$el)) {
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