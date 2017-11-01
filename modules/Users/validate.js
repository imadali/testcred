/**
 * CRED-666 : Adding Comments on Article and notifying the Assigned User
 */

$( document ).ready(function() {
    $("#notification_email").blur(function(){
        var email = $("#notification_email").val();
        $("#email-msg").remove();
        if (!_.isEmpty(email)) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
            {
                $("#SAVE_HEADER").attr("disabled", false);
                return true;
            } else {
                $(this).after('<tr id="email-msg"><td></td><td><span class="error">'+app.lang.get('LBL_INVALID_EMAIL','Users')+'</span></td></tr>');
                $("#SAVE_HEADER").attr("disabled", true);
                return false;
            }
        }
     });

});