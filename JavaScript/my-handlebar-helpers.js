 /**
 * Handlebars helpers.
 *
 * These functions are to be used in handlebars templates.
 * @class Handlebars.helpers
 * @singleton
 */
(function(app) {
    app.events.on("app:init", function() {

        /**
         * convert a string to upper case
         */
        Handlebars.registerHelper("showMore", function (text,id){
            if(text){
            if(text.length>256){
                var short=text.substring(0, 256);
                var remainig=text.substring(256, text.length);
                text =short+'<span id="'+id+'" class="more_text">'+remainig+'</span><a id="a_'+id+'" onclick="$(\'#'+id+'\').slideToggle(); $(\'#a_'+id+'\').text($(\'#a_'+id+'\').text() == \' more...\' ? \' less\' : \' more...\');" href="javascript: void(0);"> more...</a>';
            return text;
            }else{
                return text;
            }
        }else{
            return ' ';
        }
            
        });
        
        
        /**
         * Add two integer numbers
         */
        Handlebars.registerHelper("sumInt", function (number1,number2){
            number1=parseInt(number1);
            number2=parseInt(number2);
            return number1+number2;
        });
		
        /**
        * CRED-767 Helper to split a string
        */
        Handlebars.registerHelper("splitString", function (str, part){
            var resultant_string = str.split("/");
            if(part == '0')
                return resultant_string[0];
            else 
                return resultant_string[1];
        });

    });
})(SUGAR.App); 