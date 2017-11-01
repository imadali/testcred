({
    extendsFrom: 'FilterpanelLayout',
    /**
     * CRED-917 : [Upgrade] - Preview overlapping the dashlet
     * 
     * Refreshes the list view by applying filters.
     */
    _refreshList: function() {
        this._super('_refreshList');
        if ($('.document-preview').html()) {
            $('.document-preview').hide();
        }
    },
})
