({
    className: 'front-view-setup',
    plugins: ['Editable'],
    front_fields: null,
    populateValues: null,
    events: {
        'click .edit-front': 'setEdiMode',
    },
    initialize: function (opts) {
        var self = this;
        this.front_fields = new Array;
        app.view.View.prototype.initialize.call(this, opts);
        this.populateValues = App.data.createBean('dummy');
        this.populateValues.fields = this.meta.panels[0].fields;
        _.each(this.meta.panels[0].fields, function (field) {
            self.front_fields.push(field);
            //self.populateValues.set(field.name, self.model.get(field.name));
        });
        /**
        ** CRED-885: binded onchange events on sync complete
        */
        this.model.on('data:sync:complete', _.bind(this.bindChangeEvent, this));
        this._super('initialize', [opts]);
    },
    _render: function () {
        this._super('_render');
        return this;
    },
    /**
    ** CRED-885
    */
    bindChangeEvent: function () {
        this.model.on("change:birthdate", _.bind(this.setEdiMode, this));
        this.model.on("change:dotb_gender_id", _.bind(this.setEdiMode, this));
        this.model.on("change:dotb_correspondence_language", _.bind(this.setEdiMode, this));
    },
    setEdiMode: function () {
        if (!$(".btn-group").hasClass("hide")) {
            app.events.trigger('enableEdiForFrontViewForContacts');
        }
    },
    _dispose: function () {
        this.model.off("change:birthdate");
        this.model.off("change:dotb_gender_id");
        this.model.off("change:dotb_correspondence_language");
        this._super('_dispose');
    },
})
