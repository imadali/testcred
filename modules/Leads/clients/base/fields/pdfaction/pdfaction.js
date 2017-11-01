({
    /**
     * CRED-951 - Action "Dossier-Cover" not available
     */
    extendsFrom: 'PdfactionField',
    
    _render: function() {
        var emailClientPreference = app.user.getPreference('email_client_preference');
        if (!this.templateCollection.length > 0 ||
            (this.def.action === 'email' && emailClientPreference.type !== 'sugar')) {
            
            /**
             * Commenting this call, PDF button Works as expected
             */
            /*this.hide();*/
        } else {
            this._super('_render');
        }
    }
})
