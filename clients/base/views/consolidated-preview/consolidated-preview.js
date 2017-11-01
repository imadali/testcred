({
    extendsFrom: 'PrevievView',
    
    initialize: function(options) {
        this._super('initialize', [options]);
        this.addPreviewEvents();
        this._delegateEvents();
        
    },
    
    render: function() {
         this._super('render');
    },
    
    _delegateEvents: function() {
        this._super('_delegateEvents');
        app.events.on('consolidated-preview:render', this._renderPreview, this);
        app.events.on('consolidated-preview:open', this.showPreviewPanel, this);
        app.events.on('preview:close', this.hidePreviewPanel, this);
        app.events.on('consolidated-preview:pagination:hide', this.hidePagination, this);
        
    },

    hidePreviewPanel: function() {
        if (!this._isActive()) {
            return;
        }

        var layout = this.$el.parents('.sidebar-content');
        layout.find('.side-pane').addClass('active');
        layout.find('.dashboard-pane').show();
        layout.find('.preview-pane').removeClass('active');
        app.events.trigger('list:preview:decorate', false);
        this._hidden = true;
    },

    showPreviewPanel: function() {
        if (!this._isActive()) {
            return;
        }

        var layout = this.$el.parents('.sidebar-content');
        layout.find('.side-pane').removeClass('active');
        layout.find('.dashboard-pane').hide();
        layout.find('.preview-pane').addClass('active');
        var defaultLayout = this.closestComponent('sidebar');
        if (defaultLayout) {
            defaultLayout.trigger('sidebar:toggle', true);
        }
        this._hidden = false;
    },

    _isActive: function() {
        if (_.isEmpty(app.drawer)) {
            return true;
        }

        return app.drawer.isActive(this.$el);
    },
    
    addPreviewEvents: function () {
        this._super('addPreviewEvents');
        
        this.context.on("list:consolidated-preview:fire", function (model) {
             app.events.trigger("consolidated-preview:render", model, this.collection, true);
        }, this);

        //When switching to next/previous record from the preview panel, we need to update the highlighted row
        app.events.on("list:consolidated-preview:decorate", this.decorateRow, this);
        if (this.layout) {
            this.layout.on("list:consolidated-preview:fire", function () {
                //When sorting the list view, we need to close the preview panel
                app.events.trigger("preview:close");
            }, this);
            this.layout.on("list:paginate:success", function () {
                //When fetching more records, we need to update the preview collection
                app.events.trigger("consolidated-preview:collection:change", this.collection);
                // If we have a model in preview, redecorate the row as previewed
                if (this._previewed) {
                    this.decorateRow(this._previewed);
                }
            }, this);
        }
    },
    
    decorateRow: function (model) {
        // If there are drawers, make sure we're updating only list views on active drawer.
        if (_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)) {
            this._previewed = model;
            this.$('.btn.rowaction.active').removeClass('active').attr('aria-pressed', false);
            this.$('tr.highlighted').removeClass('highlighted current');
            if (model) {
                var rowName = model.get('_module') + "_" + model.id;
                var curr = $('tr[name="' + rowName + '"]');
                curr.addClass('current highlighted');
                curr.addClass('active');
                curr.attr('aria-pressed', true);
                         
            }
        }
    },

    _renderPreview: function(model, collection, fetch, previewId) {
        var self = this;
        
        app.alert.show('fetching-model', {
            level: 'process',
            title: 'Loading Preview...',
            autoClose: false,
        });
        var url = app.api.buildURL(model.get('moduleName'), 'read', {id: model.get('id')});
        if (model.get('moduleName') == 'Calls') {
            layout = 'create';
            url += '?view=record';
        }
        app.api.call(
                'read', url, null,
                {
                    success: function (data) {
                        app.alert.dismiss('fetching-model');
                        bean = app.data.createBean(data._module, data);
                        // If there are drawers there could be multiple previews, make sure we are only rendering preview for active drawer
                        if (app.drawer && !app.drawer.isActive(this.$el)) {
                            return;  //This preview isn't on the active layout
                        }

                        if (app.metadata.getModule(bean.get('_module')).isBwcEnabled) {
                            // if module is in BWC mode, just return
                            return;
                        }

                        if (bean) {
                            // Use preview view if available, otherwise fallback to record view
                            var viewName = 'consolidated-preview';
                            var previewMeta = app.metadata.getView(model.get('moduleName'), 'preview');
                            var recordMeta = app.metadata.getView(model.get('moduleName'), 'record');
                            if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
                                viewName = 'record';
                            }
                            self.meta = self._previewifyMetadata(_.extend({}, recordMeta, previewMeta));
                            self.renderPreview(bean, collection);
                            fetch && bean.fetch({
                                showAlerts: true,
                                view: viewName
                            });
                        }

                        self.previewId = previewId;
                    },
                    error: function () {
                        return;
                    }
                }
        );   
    },
    
    renderPreview: function (model, newCollection) {
        if (newCollection) {
            this.collection.reset(newCollection.models);
        }

        if (model) {
            this.switchModel(model);
            if (this.layout) {
                this.layout.trigger('consolidated-previewheader:ACLCheck', model);
            }

            this.render();
            // Open the preview panel
            app.events.trigger('consolidated-preview:open', this);
            // Highlight the row
            app.events.trigger('list:consolidated-preview:decorate', this.model, this);
        }
    },

    _previewifyMetadata: function(meta){
        this.hiddenPanelExists = false; // reset
        _.each(meta.panels, function(panel){
            if(panel.header){
                panel.header = false;
                panel.fields = _.filter(panel.fields, function(field){
                    //Don't show favorite or follow in Preview, it's already on list view row
                    return field.type != 'favorite' && field.type != 'follow';
                });
            }
            //Keep track if a hidden panel exists
            if(!this.hiddenPanelExists && panel.hide){
                this.hiddenPanelExists = true;
            }
        }, this);
        return meta;
    },
    
    switchModel: function(model) {
        this.model && this.model.abortFetchRequest();
        this.stopListening(this.model);
        this.model = model;

        // Close preview when model destroyed by deleting the record
        this.listenTo(this.model, 'destroy', function() {
            // Remove the decoration of the highlighted row
            app.events.trigger('list:consolidated-preview:decorate', false);
            // Close the preview panel
            app.events.trigger('preview:close');
        });
    },
    
    
})
