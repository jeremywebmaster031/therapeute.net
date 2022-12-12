jQuery(document).ready(function($){

    jQuery('#mp-import-content').on('click', function(e){
        e.preventDefault();
        var thisObj = jQuery(this);

        if (thisObj.hasClass('no-license')) {
            jQuery('.mp-license-notactive-modal-container').fadeIn();
            return;
        }

        jQuery('#import-content-response').children('.res-text').html('');
        jQuery('#import-content-response').children('.res-text').html('Processing content...');
        jQuery('#import-content-response').css('display', 'flex');
        thisObj.prop('disabled', true).hide();
        jQuery('.mp-setup-data-removal-toast.mp-server').hide();

        jQuery.ajax({
            type: "post",
            url: mp_import.ajaxurl,
            data: 'action=mp_import_content&'+ jQuery('#import_form').serialize(),
            success: function(response) {
                if(response.type == 'error' ){
					jQuery('#import-content-response').children('.res-text').html(response.msg);
					jQuery('#import-content-response').find('.loadinerSearch').hide();
					return false;
				}
                jQuery('#import-content-response').children('.res-text').html(response);
                jQuery('#import-content-response').find('.loadinerSearch').hide();
		        jQuery('#import-content-response').find('.checkImg').show();
                
                jQuery('#import-themeoptions-response').children('.res-text').html('');
                jQuery('#import-themeoptions-response').children('.res-text').html('Theme Option...');
                jQuery('#import-themeoptions-response').css('display', 'flex');
                
                jQuery.ajax({
                    type: "post",
                    url: mp_import.ajaxurl,
                    data: 'action=mp_import_theme_options&'+ jQuery('#import_form').serialize(),
                    success: function(response) {
                        jQuery('#import-themeoptions-response').children('.res-text').html(response);
                        jQuery('#import-themeoptions-response').find('.loadinerSearch').hide();
                        jQuery('#import-themeoptions-response').find('.checkImg').show();
                        jQuery('.mp-import-success').show();
                        // location.reload();
                    },
                    error: function (res) {
                        jQuery('#import-themeoptions-response').find('.loadinerSearch').hide();
                        console.log(res);
                        alert('Something went wrong. Try to increase your server max execution time limit or contact Listingpro Support.')
                    }
                });
            },
            error: function (res) {
                jQuery('#import-content-response').find('.loadinerSearch').hide();
                console.log(res);
                alert('Something went wrong. Try to increase your server max execution time limit or contact Listingpro Support.')
            }
        });
    });
});