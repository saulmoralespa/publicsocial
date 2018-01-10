jQuery("form#form-config").submit(function (e) {
    e.preventDefault();
    jQuery.ajax({
        type: jQuery(this).attr('method'),
        url: jQuery(this).attr('action'),
        data: jQuery(this).serialize() + '&fb_fan_page_token=' + jQuery('select[name=fb_fan_page] option:selected').attr('data-fbtoken'),
        beforeSend: function(){
        jQuery('div#alert-config').hide();
        jQuery('button#save-changes').addClass('disabled');
        },
        success: function(e) {
            jQuery('button#save-changes').removeClass('disabled');
            jQuery('div#alert-config').show();
            var obj =JSON.parse(e);
            if (obj.status){
                jQuery('div#alert-config').addClass('alert-success');
                jQuery('div#alert-config strong').html('Cambios guardados');
            }else{
                jQuery('div#alert-config').addClass('alert-danger');
                jQuery('div#alert-config strong').html('Error: '+ obj.error);
            }
        },
        error: function(xhr){
        console.log(xhr.statusText + xhr.responseText);
        }
    });
});