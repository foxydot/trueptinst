// my-ajax-request.js
jQuery(document).ready(function($) {
    $('.additional-projects li a').click(function(event) {
         // Stop form from submitting normally
        event.preventDefault();
        // get form data
        var data = "id="+$(this).attr('id');
        // add my action (for ajax routing)
        data += '&action=' + 'my_frontend_action';
        // add nonce (for verification)
        data += '&nonce=' + MyAjaxObject.nonce;
        // post it
        $.post(MyAjaxObject.ajax_url, data, function(response) {
            $('#sidebar-alt').html(response);
        });
    }); 
});