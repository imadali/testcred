({

	extendsFrom: 'OpportunitiesRecordView',

	initialize: function (options) {
        this._super('initialize',[options]);
       
       if(app.user.attributes.type != "admin"){
            _.each(this.meta.panels, function(panel) {
                 _.each(panel.fields, function(field) {
                         if(field.name == "dotb_soko_c"){
                             field.readonly = true;
                         }
                 }, this);
             }, this);
        }
        this.model.on("change:provider_id_c", this.resetPPI, this);
        
    },

    render: function () {
        this._super('render');
    },

    resetPPI : function(){
    
    	var x = this.model.get("provider_id_c");
          if(x == "rci"){
                this.model.set({"ppi_c" : ""});
                this.model.save();
          }
    
    }

})