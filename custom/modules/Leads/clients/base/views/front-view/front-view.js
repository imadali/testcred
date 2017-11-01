({
    className: 'front-view-setup',
    plugins: ['Editable'],
    front_fields: null,
    edit_mode: false,
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
        this.model.on("change:birthdate", this.setEdiMode, this);
        this.model.on("change:dotb_gender_id_c", this.setEdiMode, this);
        this.model.on("change:dotb_correspondence_language_c", this.setEdiMode, this);
        this._super('initialize', [opts]);
    },
    _render: function () {
        this._super('_render');
        return this;
    },
    setEdiMode: function () {
        if (this.edit_mode) {
            if (!$(".btn-group").hasClass("hide")) {
                app.events.trigger('enableEdiForFrontViewForLeads');
            }
        }
        this.edit_mode = true;
    },
    _dispose: function () {
        this.model.off("change:dotb_gender_id_c");
        this.model.off("change:birthdate");
        this.model.off("change:dotb_gender_id_c");
        this._super('_dispose');
    },
})
