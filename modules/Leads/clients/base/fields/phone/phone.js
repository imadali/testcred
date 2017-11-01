({
    extendsFrom: "PhoneField",
    
    initialize: function (options) {
        this._super('initialize', [options]);
    },
    
    render: function () {
        this._super('render');

         /**
         * CRED-873 : CTI Basic: RT Action Items
         
        var phoneElement = this.$(this.fieldTag);
        this.$el.find(phoneElement).mask('+99-99-999-9999', {
            placeholder: "+XX-XX-XXX-XXXX",
        });
        */
    }
})