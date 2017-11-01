/**
 * @class View.Fields.Base.ExtensiveFiltersCollectionCountField
 * @alias SUGAR.App.view.fields.BaseExtensiveFiltersCollectionCountField
 * @extends View.Fields.Base.BaseCollectionCountField
 */
({
    extendsFrom: 'CollectionCountField',

    initialize: function(options) {
        this._super('initialize', [options]);
    },

    /**
     * @Override
     */
    fetchCount: function() {
         if (_.isNull(this.collection.total)) {
            app.alert.show('fetch_count', {
                level: 'process',
                title: app.lang.get('LBL_LOADING'),
                autoClose: false
            });
        }
     
        var params = {};
        var collectionFitler = [this.context.get('filter')];
        if (!_.isUndefined(this.collection) && !_.isUndefined(this.collection.filterDef) && !_.isEmpty(this.collection.filterDef)) {
            collectionFitler = this.collection.filterDef;
            params = {
                filter: collectionFitler,
            };
        }

        app.api.count(
            'consolidation/' + this.context.get('module'),
            null, {
                success: _.bind(function(data) {
                    this.collection.total = parseInt(data.record_count, 10);
                    this.context.set('total_count', this.collection.total);
                    if (!this.disposed) {
                        this.updateCount();
                    }
                }, this),
                complete: function() {
                    app.alert.dismiss('fetch_count');
                }
            },
            params
        );
    },
    
    _setCountLabel: function(options) {        
        // Default properties.
        options = options || {};
        
        /**
         * Using this.collection.offset insated of this.collection.length
         */
        var length = this.collection.offset;
        var fullyFetched = this.collection.next_offset <= 0;
        // Override default properties with passed-in values.
        length = !_.isUndefined(options.length) ? options.length : length;
        fullyFetched = !_.isUndefined(options.hasMore) ? !options.hasMore : fullyFetched;

        if (!length && !this.collection.dataFetched) {
            return this.countLabel = '';
        }

        var tplKey = 'TPL_LIST_HEADER_COUNT_TOTAL';
        var context = {num: length};

        if (fullyFetched) {
            tplKey = 'TPL_LIST_HEADER_COUNT';
        } else if (!_.isNull(this.collection.total)) {
            context.total = this.collection.total;
        } else {
            var tooltipLabel = app.lang.get('TPL_LIST_HEADER_COUNT_TOOLTIP', this.module);
            context.total = new Handlebars.SafeString(
                '<a href="javascript:void(0);" data-action="count" rel="tooltip" data-placement="right" title="' + tooltipLabel + '" role="button" tabindex="0">' +
                Handlebars.Utils.escapeExpression(
                    app.lang.get('TPL_LIST_HEADER_COUNT_PARTIAL', this.module, {num: context.num + 1})
                ) + '</a>'
            );
        }

        return this.countLabel = new Handlebars.SafeString(app.lang.get(tplKey, this.module, context));
    },
    

})
