({
	extendsFrom: 'CreateActionsView',
	
    initialize : function(options) { 
        this._super('initialize', [ options ]);
        this.events['blur input[name=primary_address_postalcode]'] = 'populateAddress';
    },
    
    render: function () {
        this._super('render');
    },
    populateAddress : function(){
         self = this;
        var postalCode = this.model.get("primary_address_postalcode");
        var postalCodes = ["9485" , "9486" , "9487", "9488"  , "9498" , "9489" , "9490" , "9491" , "9492" , "9493" , "9494" , "9495" , "9496" , "9497"];
        if(postalCodes.indexOf(postalCode) == -1){
            $.ajax({
                url: "https://api.zippopotam.us/CH/"+postalCode,
                success: function(response){
                    self.model.set({"primary_address_city" : response.places[0]['place name']});
                    
                },
 
            });
        }
        else {
            $.ajax({
                url: "https://api.zippopotam.us/LI/"+postalCode,
                success: function(response){
                    self.model.set({"primary_address_city" : response.places[0]['place name']});
                    
                },
                
            });
        }
    },
    
})