jQuery(function($) {

    /***
     *  let medicalproDisabledFields is an array
     *  array key is redux field id
     *  array value is for default input value if any default : --no
     * */
    let medicalproDisabledFields = {
        'lp_detail_page_ads_switch': '0',
        'events_dashoard': '0',
        'discounts_dashoard': '0',
        'menu_dashoard': '0',
        'report_btn': '0',
        'enable_best_changed_search_filter': '0',
        'lp_detail_slider_styles': '--no',
        'slider_height': '--no',
        'lp_icon_for_archive_pages_switch': '--no',
        'lp_map_pin': '--no',
        'enable_price_search_filter': '--no',
        'enable_most_viewed_search_filter': '--no',
        'enable_nearme_search_filter': '--no',
        'oph_switch': '--no',
        'lp_showhide_address': '--no',
        'price_switch': '--no',
        'currency_switch': '--no',
        'digit_price_switch': '--no',
    };

    jQuery.each(medicalproDisabledFields, function (option, value){
        var $this = jQuery('#' + option),
            parent = $this.closest('tr');
        if ($this.length > 0) {
            if ('--no' !== value) {
                $this.val(value);
            }
            parent.addClass('fold hide');
        }
    });

    jQuery('#bulk_enable_price_options').on('change', function(event){
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        if (jQuery(this).is(':checked')) {
            jQuery('#contact_show').prop('checked', true);
            jQuery('#map_show').prop('checked', true);
            jQuery('#video_show').prop('checked', true);
            jQuery('#gallery_show').prop('checked', true);
            jQuery('#listingproc_tagline').prop('checked', true);
            jQuery('#listingproc_location').prop('checked', true);
            jQuery('#listingproc_website').prop('checked', true);
            jQuery('#listingproc_social').prop('checked', true);
            jQuery('#listingproc_faq').prop('checked', true);
            jQuery('#listingproc_price').prop('checked', true);
            jQuery('#listingproc_tag_key').prop('checked', true);
            jQuery('#listingproc_bhours').prop('checked', true);
            jQuery('#listingproc_plan_reservera').prop('checked', true);
            jQuery('#listingproc_plan_timekit').prop('checked', true);
            jQuery('#listingproc_plan_menu').prop('checked', true);
            jQuery('#listingproc_bookings').prop('checked', true);
            jQuery('#listingproc_leadform').prop('checked', true);
            jQuery('#listingproc_plan_announcment').prop('checked', true);
            jQuery('#listingproc_plan_deals').prop('checked', true);
            jQuery('#listingproc_plan_campaigns').prop('checked', true);
            jQuery('#lp_eventsplan').prop('checked', true);
            jQuery('#lp_hidegooglead').prop('checked', true);

            jQuery('#insurances_show').prop('checked', true);
            jQuery('#awards_show').prop('checked', true);
            jQuery('#video_consult_show').prop('checked', true);
        }else{
            jQuery('#contact_show').prop('checked', false);
            jQuery('#map_show').prop('checked', false);
            jQuery('#video_show').prop('checked', false);
            jQuery('#gallery_show').prop('checked', false);
            jQuery('#listingproc_tagline').prop('checked', false);
            jQuery('#listingproc_location').prop('checked', false);
            jQuery('#listingproc_website').prop('checked', false);
            jQuery('#listingproc_social').prop('checked', false);
            jQuery('#listingproc_faq').prop('checked', false);
            jQuery('#listingproc_price').prop('checked', false);
            jQuery('#listingproc_tag_key').prop('checked', false);
            jQuery('#listingproc_bhours').prop('checked', false);
            jQuery('#listingproc_plan_reservera').prop('checked', false);
            jQuery('#listingproc_plan_timekit').prop('checked', false);
            jQuery('#listingproc_plan_menu').prop('checked', false);
            jQuery('#listingproc_plan_announcment').prop('checked', false);
            jQuery('#listingproc_plan_deals').prop('checked', false);
            jQuery('#listingproc_bookings').prop('checked', false);
            jQuery('#listingproc_leadform').prop('checked', false);
            jQuery('#listingproc_plan_campaigns').prop('checked', false);
            jQuery('#lp_eventsplan').prop('checked', false);
            jQuery('#lp_hidegooglead').prop('checked', false);

            jQuery('#insurances_show').prop('checked', false);
            jQuery('#awards_show').prop('checked', false);
            jQuery('#video_consult_show').prop('checked', false);
        }
    });

    function initialize() {
        var input = document.getElementById('hospitalAddress');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        });
    }
    if (jQuery('input').is('#hospitalAddress')) {
        google.maps.event.addDomListener(window, 'load', initialize)
    }


    jQuery('ul.term-gallery-list').sortable({
        items: 'li',
        cursor: '-webkit-grabbing',
        scrollSensitivity: 40,
    });

    jQuery(document).on('click', '#medicalpro-add-term-gallery', function (e) {
        e.preventDefault();

        var thisObj    = jQuery(this),
        custom_uploader = wp.media({
            title: 'Add Images to Gallery',
            library : {type : 'image'},
            multiple: true
        }).on('select', function() {
            var attachments = custom_uploader.state().get('selection').map(function( attachment_data ) {
                attachment_data.toJSON();
                return attachment_data;
            });

            var attachments_list = '';
            jQuery.each( attachments, function( key, attachment_data ) {
                var attachment = '<li class="gallery-item" data-id="'+ attachment_data.id +'"><input type="hidden" name="lp_hospital[gallery][]" value="'+ attachment_data.id +'"><div class="thumbnail"><img src="'+ attachment_data.attributes.url +'" alt="'+ attachment_data.attributes.title +'"></div><div class="gallery-actions"><a class="remove-gallery-item" href="javascript:void(0);"><i class="fa fa-remove"></i></a></div></li>';
                attachments_list += attachment;
            });
            jQuery('.term-gallery .term-gallery-list').append(attachments_list);
        }).open();

    });

    jQuery(document).on('click', '.remove-gallery-item', function (e) {
        jQuery(this).closest('.term-gallery-list li.gallery-item').remove();
    });

    jQuery(document).on('click', '#business_logo', function (e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            library : {type : 'image'},
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('.business-logo-image').html('<input type="hidden" name="lp_hospital[business_logo]" value="'+ attachment.id +'"><img src="'+ attachment.url +'" alt="'+ attachment.title +'"><a class="remove-business-logo" href="javascript:void(0);"><i class="fa fa-remove"></i></a>');
        }).open();
    });

    jQuery(document).on('click', '.remove-business-logo', function (e) {
        jQuery('.business-logo-image').html('<input type="hidden" name="lp_hospital[business_logo]" value="">');
    });

});
jQuery(document).ready(function () {
    jQuery(document).on('click', '.lp_withdrawal_action_btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = jQuery(this);
        $this.closest('.lp_withdrawal_action_modal_content').find('.lp_withdrawal_action_modal_container').fadeIn();
    });
    jQuery(document).on('click', '.lp_withdrawal_action_modal_container', function () {
        var $this = jQuery(this);
        $this.fadeOut();
    });
    jQuery(document).on('click', '.lp_withdrawal_action_modal', function (x) {
        x.preventDefault();
        x.stopPropagation();
        x.stopImmediatePropagation();
    });
    jQuery(document).on('click', '.lp_withdrawal_action_modal_confirm,.lp_withdrawal_action_modal_reject', function (x) {
        x.preventDefault();
        x.stopPropagation();
        x.stopImmediatePropagation();
        var $this = jQuery(this),
            postID = $this.data('postid'),
            request = 'confirm';
            if ($this.hasClass('lp_withdrawal_action_modal_confirm')) {
                request = 'confirm';
            }else if ($this.hasClass('lp_withdrawal_action_modal_reject')) {
                request = 'reject';
            }
            if (!$this.hasClass('active')){

            jQuery('.lp_withdrawal_action_modal_confirm,.lp_withdrawal_action_modal_reject').addClass('active').prop('disabled', true);

            $this.find('i').removeClass('fa-credit-card fa-remove').addClass('fa-spinner fa-spin');

            jQuery.ajax({
                type: 'POST',
                url: ajax_search_term_object.ajaxurl,
                dataType: "json",
                data: {
                    'action' : 'lp_withdrawal_complete_request',
                    'postID' : postID,
                    'request' : request
                },
                success:function(data) {
                    $this.find('i').addClass('fa-check').removeClass('fa-spinner fa-spin');
                    jQuery(document).delay(1000).queue(function () {
                        location.reload();
                    });
                },
                error: function(errorThrown){
                    $this.find('i').addClass('fa-warning').removeClass('fa-spinner fa-spin');
                    jQuery(document).delay(1000).queue(function () {
                        location.reload();
                    });
                }
            });
        }
    });
});