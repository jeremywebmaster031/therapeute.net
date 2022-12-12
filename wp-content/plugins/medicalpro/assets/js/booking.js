jQuery(document).ready(function () {
    
    jQuery('.mp-booking-bar-login').on('click', function(e){
        jQuery('div#modal-3').html('');
        jQuery('.content-loading').css('background-position','center center');
	jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: needlogin_object.ajaxurl,
            data: { 
                'action': 'listingpro_loginpopup',
                'lpNonce' : jQuery('#lpNonce').val()
            },
            success: function(res){
                jQuery('.content-loading').css('background-position','-9999px -9999px');
                jQuery('div#modal-3').html(res);
                if(jQuery('form#register .check_policy').is('.termpolicy')){
                    jQuery("input#lp_usr_reg_btn").prop('disabled',true);
                    jQuery('.check_policy').on('click', function(){
                        if(jQuery('#check_policy').is(':checked')){
                            jQuery("input#lp_usr_reg_btn").prop('disabled',false);
                        }
                        else{
                            jQuery("input#lp_usr_reg_btn").prop('disabled',true);
                        }
                    });
                }
            }
        });
    });
    
    medicalpro_booking_video_consultation_fee();
    medical_booking_active_booking_button();
    jQuery(document).on('click', '.card-header-tabs li', function () {
        var id = jQuery(this).find('a').attr('id');
        jQuery('.card-header-tabs li').removeClass('active');
        jQuery(this).addClass('active');
        medicalpro_booking_video_consultation_fee();
    });

    jQuery(document).on('click', '.date-slider-list li', function () {
        if (jQuery(this).hasClass('DisableArrow')) {
            return false;
        } else {
            medicalpro_booking_slots(jQuery(this).data('date'));
        }
    });

    jQuery(document).on('click', '.book-appoinment-btn', function () {
        jQuery('#place').val(jQuery(this).data('id')).change();
        medicalpro_booking_video_consultation_fee();
        medicalpro_booking_slots(jQuery(this).data('date'));
        jQuery('.md-booking-con-title .book-step-1').trigger('click');
    });

    jQuery(document).on('click', '.medicalpro-booking-footer-view-switch', function () {

        var $this = jQuery(this);
        var $icon = $this.find("i");
        if ($icon.hasClass('fa-calendar-o')) {

            $icon.addClass('fa-list');
            $icon.removeClass('fa-calendar-o');

            jQuery('.booking-date-selection').hide();
            jQuery('.booking-date-calendar').show();

            jQuery("#booking-calendar-select-date").datepicker({
                minDate: new Date(),
            },
                    jQuery.datepicker._selectDate = function (id, dateStr) {
                        medicalpro_booking_slots(dateStr);
                        
                        var $this = jQuery('.medicalpro-booking-footer-view-switch');
                        var $icon = $this.find("i");
                        if ($icon.hasClass('fa-calendar-o')) {
                
                            $icon.addClass('fa-list');
                            $icon.removeClass('fa-calendar-o');
                
                            jQuery('.booking-date-selection').hide();
                            jQuery('.booking-date-calendar').show();
                
                            jQuery("#booking-calendar-select-date").datepicker({
                                minDate: new Date(),
                            },
                                    jQuery.datepicker._selectDate = function (id, dateStr) {
                                        medicalpro_booking_slots(dateStr);
                                    });
                        } else {
                            $icon.removeClass('fa-list');
                            $icon.addClass('fa-calendar-o');
                            jQuery('.booking-date-selection').show();
                            jQuery('.booking-date-calendar').hide();
                        }
                        
                        
                    });
        } else {
            $icon.removeClass('fa-list');
            $icon.addClass('fa-calendar-o');
            jQuery('.booking-date-selection').show();
            jQuery('.booking-date-calendar').hide();
        }
    });

    jQuery(document).on('click', 'li:not(.lp-booking-disable) .medical-booking-time-pill-hover', function () {
        jQuery('.medical-booking-time-pill-hover').closest('li').removeClass('active');
        jQuery(this).closest('li').addClass('active');
        jQuery('#booking_date').val(jQuery(this).closest('li').data('booking-slot-date'));
        jQuery('#slot_start_time').val(jQuery(this).closest('li').data('booking-slot-start'));
        jQuery('#slot_end_time').val(jQuery(this).closest('li').data('booking-slot-end'));
        medical_booking_active_continue_booking();
    });

    jQuery(document).on('click', '#continue_booking', function () {
        jQuery('.book-step-1').removeClass('select-bok-step').addClass('active');
        jQuery('.book-step-2').addClass('select-bok-step');
        jQuery('#step-1').hide();
        jQuery('#step-2').show();
        medicalpro_booking_selected_fields();
    });

    jQuery(document).on('click', '.book-step-1', function () {
        jQuery('.book-step-1').addClass('select-bok-step').removeClass('active');
        jQuery('.book-step-2').removeClass('select-bok-step active');
        jQuery('#step-1').show();
        jQuery('#step-2').hide();
        medicalpro_booking_selected_fields();
    });

    jQuery(document).on('change', '#medicalpro-booking-form #step-2 .required', function () {
        jQuery(this).css('border', '');
        medical_booking_active_booking_button();
    });

    jQuery(document).on('keyup', '#medicalpro-booking-form #step-2 .required', function () {
        jQuery(this).css('border', '');
        medical_booking_active_booking_button();
    });

    jQuery(document).on('submit', '#medicalpro-booking-form', function (x) {
        x.preventDefault();
        var thisObj  = jQuery(this);
        var is_valid = true;
        jQuery('#medicalpro-booking-form #step-2 .required').each(function () {
            var thisObj = jQuery(this);
            var value = thisObj.val();
            if (typeof value === 'undefined' || value === '' || value === null) {
                is_valid = false;
                jQuery(this).css('border', '1px solid red');
            }
        });
        if (is_valid === true) {
            thisObj.find('#submit_booking_btn').prop('disabled', true);
            thisObj.find('#submit_booking_btn').append('<i class="fa fa-spinner fa-spin mp-booking-preloader"></i>');
            jQuery('.booking-loader').fadeIn();
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_search_term_object.ajaxurl,
                data: 'action=create_medical_booking&' + jQuery("form#medicalpro-booking-form").serialize(),
                success: function (response) {
                    thisObj.find('#submit_booking_btn').prop('disabled', false);
                    jQuery('.mp-booking-preloader').remove();
                    jQuery('.booking-loader').fadeOut();
                    if (response.status === 'error') {
                        alert(response.msg);
                    } else {
                        jQuery('form#medicalpro-booking-form').hide();
                        jQuery('.booking-success-box').show();
                        if (typeof response.redirectURL !== 'undefined' && response.redirectURL !== '') {
                            window.location.href = response.redirectURL;
                            jQuery('.mp-booking-paid-redirect-notification').show();
                            jQuery('.booking-success-box h3:not(.md-booking-con-title)').hide();
                        }
                    }
                }
            });
        } else {

        }
        return false;
    });

    jQuery(document).on('click', '.booking-content ul.booking-action-content i.noticefi_er', function (e) {
        e.preventDefault();
        var $this = jQuery(this).closest('ul'),
        cbid = $this.find('.radio-container-box').attr('id'),
        mybookings = $this.find('.radio-container-box').data('mybookings');
        if (!$this.hasClass('active')){
            jQuery('#booking-details-sidebar').append('<div id="booking-details-sidebar-preloader"><i class="fa fa-spin fa-spinner"></i></div>');
            jQuery('.booking-content ul.booking-action-content').removeClass('active');
            $this.addClass('active');
            jQuery.ajax({
                dataType: 'html',
                url: ajax_search_term_object.ajaxurl,
                data: {
                    'action': 'medicalpro_get_booking_details',
                    'cbid': cbid,
                    'mybookings': mybookings
                },
                success: function (res) {
                    jQuery('#booking-details-sidebar').html(res);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        return false;
    });

    jQuery(document).on('click', '.booking-action-content .dropdown-menu a, .mp-my-booking-cancel-action', function (e) {

        e.stopPropagation();
        var $this = jQuery(this);
        cbid = $this.attr('data-id');
        cBstatus = $this.attr('data-status');

        e.preventDefault();
        if ($this.hasClass('active-ajax')) {

        } else {
            if (jQuery('.mp-my-booking-cancel-action').length > 0) {
                $this.html('<i class="fa fa-spinner fa-spin"></i>');
            }else{
                $this.closest('div.dropdown').find('.dropdown-toggle').append('<span class="booking-action-spinner"><i class="fa fa-spinner fa-spin"></i></span>');
            }
            $this.closest('.dropdown-menu').hide();
            $this.addClass('active-ajax');

            jQuery.ajax({
                dataType: 'html',
                url: ajax_search_term_object.ajaxurl,
                data: {
                    'action': 'medicalpro_update_booking_status',
                    'cbid': cbid,
                    'cBstatus': cBstatus,
                },
                success: function (res) {
                    $this.find('.booking-action-spinner').remove();
                    if (cBstatus == "APPROVED") {
                        $status_color = "approved";
                    } else if (cBstatus == "CANCELED") {
                        $status_color = "canceled";
                    } else {
                        $status_color = "pending";
                    }
                    $this.closest('.booking-action-content').find('.booking-status').removeClass("pending canceled approved");
                    $this.closest('.booking-action-content').find('.booking-status').addClass($status_color);
                    $this.closest('div.dropdown').find('button.dropdown-toggle').html(res);
                    location.reload();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });

    var firstDay = jQuery('#start_of_weekk').val();
    jQuery("#lp-dashboard-booking-calander").datepicker(
            {
                firstDay: firstDay,
                minDate: new Date(),
            },
            jQuery.datepicker._selectDate = function (id, dateStr) {
                var onSelect,
                        target = jQuery(id),
                        inst = this._getInst(target[0]);
                if (id == '#lp-booking-calander') {
                    return false;
                }
            }
    );
    jQuery('#lp-dashboard-booking-calander .ui-datepicker-next').addClass("dashboard-calander-next");
    jQuery('#lp-dashboard-booking-calander .ui-datepicker-prev').addClass("dashboard-calander-prev");

    jQuery(".grid-btn, .bookings-back-btn").click(function () {
        jQuery('.back-to-bookings').hide();
        jQuery('#lp-dashboard-booking-calander').hide();
        jQuery('.booking-grid-wrapper').toggle();
        jQuery('.lp-dashboard-booking-calander-header').hide();
    });

    jQuery(document).on('click', '.calendar-btn,.ui-datepicker-next,.ui-datepicker-prev', function () {

        var $CananderActionBtn = jQuery(this);
        if (!$CananderActionBtn.hasClass('ui-state-disabled')) {
            jQuery('#lp-dashboard-booking-calander').append('<div class="lp-dashboard-booking-calander-loader"><i class="fa fa-spinner fa-spin"></i> </div>');
        }
        if ($CananderActionBtn.hasClass('ui-datepicker-next')) {

        } else if ($CananderActionBtn.hasClass('ui-datepicker-prev')) {

        } else {
            jQuery('.back-to-bookings').show();
            jQuery('#lp-dashboard-booking-calander').toggle();
            jQuery('.lp-dashboard-booking-calander-header').toggle();
            jQuery('.booking-grid-wrapper').hide();
        }

        firstDay = $CananderActionBtn.attr('data-first-day'),
                lastDay = $CananderActionBtn.attr('data-last-day');

        //console.log(firstDay+'---'+lastDay);

        jQuery('#lp-dashboard-booking-calander table.ui-datepicker-calendar td').find('.lp-dashboard-booking-calander-cell').remove();
        var bookings_type = jQuery('.calendar-btn').data('bookings_type');

        jQuery.ajax({
            dataType: 'json',
            url: ajax_search_term_object.ajaxurl,
            data: {
                'action': 'medicalpro_calendar_bookings_listing',
                'firstDay': firstDay,
                'lastDay': lastDay,
                'bookings_type': bookings_type
            },
            success: function (res) {
                

                jQuery('#lp-dashboard-booking-calander').find('.lp-dashboard-booking-calander-loader').remove();
                jQuery('.ui-datepicker-next').attr({
                    'data-first-day': res.next_first,
                    'data-last-day': res.next_last
                });

                jQuery('.ui-datepicker-prev').attr({
                    'data-first-day': res.prev_first,
                    'data-last-day': res.prev_last
                });

                jQuery('#lp-dashboard-booking-calander table.ui-datepicker-calendar td').each(function (thisTD) {
                    var loopTD = jQuery(this),
                            dataMonth = loopTD.data('month'),
                            dataMonthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                            dataweekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                            dataYear = loopTD.data('year'),
                            tdDay = loopTD.find('a.ui-state-default').text(),
                            dataFullDate = dataMonthName[dataMonth] + ' ' + tdDay + ',' + ' ' + dataYear,
                            dataDayName = new Date(dataFullDate),
                            allbookingspopup = '';

                    if (res.lp_booking_settings[dataFullDate]) {
                        var disabled_bookings = '<div class="disabled-booking"><i class="fa fa-close"></i><h5>Appointments Disabled</h5></div>';
                        loopTD.append(disabled_bookings);
                    } else {
                        loopTD.find('.disabled-booking').css('visibility', 'hidden');
                    }
                    calenderPill = '<div class="lp-dashboard-booking-calander-cell">';
                    allbookingspopup = '<div class="lp-dashboard-booking-calander-section lp-dashboard-booking-calander-more-popup">' + '<i class="fa fa-close close-lp-dashboard-booking-calander-section"></i>'
                            + '<h3 class="lp-dashboard-booking-calander-section-head-day">' + dataweekday[dataDayName.getDay()] + '</h3>'
                            + '<h2 class="lp-dashboard-booking-calander-section-head-date">' + tdDay + '</h2>'
                            + '<hr>';
                    
                    
                    jQuery.each(res[dataFullDate], function (i, item) {
                        var listingTitle = item['Listing Title'],
                                Listing_author = item['Booker Name'],
                                bookingDate = item['Booking Date'],
                                bookingEndTime = item['End Time'],
                                bookingStartTime = item['Start Time'],
                                bookingPhone = item['Booking Phone'],
                                bookingMsg = item['Booking Message'],
                                PrevMonthStart = item['Prev Start'],
                                PrevMonthEnd = item['Prev Last'],
                                addr = item['addr'],
                                NextMonthStart = item['Next Start'],
                                NextMonthLast = item['Next Last'],
                                time_zone = jQuery('#lp_booking_get_time_zone_val').val();


                        var calenderPillData = '<div class=' + 'droped-content' + '>'
                                + '<h3 class=' + 'detail-booker-name' + '><i class= ' + ' fa' + '>&#xf007;</i>' + Listing_author + '</h3>'
                                + '<p class=' + 'detail-booker-info' + '><i class= ' + ' fa' + '>&#xf133;</i>' + bookingDate + '</p>'
                                + '<p class=' + 'detail-booker-info' + '><i class= ' + ' fa ' + '>&#xf017;</i>' + bookingStartTime + ' - ' + bookingEndTime + '<i class=' + 'tag-time-zone' + '>' + time_zone + '</i></p>'
                                + '<p class=' + 'detail-booker-info' + '><i class= ' + ' fa ' + '>&#xf095;</i>' + bookingPhone + '</p>';

                        if (addr != '') {
                            calenderPillData += '<p class=' + 'detail-booker-info' + '><i class= ' + ' fa ' + '>&#xf124;</i>' + addr + '</p>';
                        }
                        calenderPillData += '<p class=' + 'detail-booker-info' + '><i class= ' + ' fa ' + '>&#xf249;</i>' + bookingMsg + '<i class=' + 'tag-reply' + '>Reply</i></p>'
                                + '<p class=' + 'booking-listing-info' + '>Associated Listing:</p>'
                                + '<h4 class=' + 'booking-listing-info' + '>' + listingTitle + '</h4>'
                                + '</div>',
                                calenderPill += '<a href="#" class="cell-pill-container cell-pill-container-list" data-trigger="focus" data-toggle="cus-popover" data-content="' + calenderPillData + '"><label class="cell-pill">' + bookingStartTime + ' <span class="cal-bookingEndTime">' + ' &nbsp; - &nbsp;&nbsp; ' + bookingEndTime + '</span></label><label class="cal-pill-listing_title">' + listingTitle + '</label> </a>';
                        allbookingspopup += '<a href="#" class="cell-pill-container" data-trigger="focus" data-toggle="cus-popover" data-content="' + calenderPillData + '"><label class="cell-pill">' + bookingStartTime + ' <span class="cal-bookingEndTime">' + ' &nbsp; - &nbsp;&nbsp; ' + bookingEndTime + '</span></label><label class="cal-pill-listing_title">' + listingTitle + '</label> </a>';
                       
                    });
                    calenderPill += '</div>';
                    allbookingspopup += '</div>';

                    loopTD.append(calenderPill);
                    loopTD.append(allbookingspopup);

                    pillsLength = loopTD.find('.cell-pill-container-list').length;
                    if (pillsLength > '3') {
                        pillsLength = loopTD.find('.cell-pill-container-list').length - 3;
                        loadmorebtn = '<a href="" class="pull-right cell-bookings-row-expanded">' + pillsLength + ' More</a>';
                        loopTD.find('.lp-dashboard-booking-calander-cell').append(loadmorebtn);
                    }
                    jQuery('[data-toggle="cus-popover"]').popover({
                        placement: "left",
                        container: '#lp-dashboard-booking-calander',
                        html: true,
                    });
                    jQuery('.cell-bookings-row-expanded').click(function () {
                        jQuery('.lp-dashboard-booking-calander-section').hide();
                        jQuery(this).closest('td').find('.lp-dashboard-booking-calander-section').show();

                    });
                    jQuery('.close-lp-dashboard-booking-calander-section').click(function () {
                        jQuery('.lp-dashboard-booking-calander-section').hide();
                    });
                });
            },
            error: function (err) {
            }
        });
    });

});
jQuery(document).on('change', '#place', function (){
    medicalpro_booking_video_consultation_fee();
    medicalpro_booking_slots(jQuery(this).data('date'));
    jQuery('.md-booking-con-title .book-step-1').trigger('click');
});
function medicalpro_booking_video_consultation_fee() {
    var active_tab = jQuery('.md-booking-sidebar-tabs li.active').find('a').attr('id');
    jQuery('#booking_type').val(active_tab);
    if (active_tab == 'video-consultation') {
        var price = jQuery('#place option:selected').data('price');
        if (Number(price) > 0) {
            jQuery('.video-consultation-fee').show();
            jQuery('.video-consultation-fee').find('span.price').text(Number(price));
        } else {
            jQuery('.video-consultation-fee').hide();
        }
    } else {
        jQuery('.video-consultation-fee').hide();
    }
}

function medicalpro_booking_slots(selected_date) {

    var listing_id = jQuery('.date-slider-list').data('lid');
    var hospital_id = jQuery('#place').val();

    jQuery('#booking_date').val('');
    jQuery('#slot_start_time').val('');
    jQuery('#slot_end_time').val('');
    jQuery('.booking-loader').fadeIn();
    jQuery.ajax({
        type: 'POST',
        dataType: 'html',
        url: ajax_search_term_object.ajaxurl,
        data: {
            action: 'medicalpro_booking_slots',
            selected_date: selected_date,
            listing_id: listing_id,
            hospital_id: hospital_id
        },
        success: function (data) {
            jQuery('.booking-date-selection').html(data);
            jQuery('.booking-date-calendar').hide();
            jQuery('.booking-date-selection').show();

            jQuery('.booking-loader').fadeOut();
            medical_booking_active_continue_booking();
        }
    });
}

function medical_booking_active_continue_booking() {
    var is_valid = true;
    jQuery('#medicalpro-booking-form #step-1 .required').each(function () {
        var thisObj = jQuery(this);
        var value = thisObj.val();
        if (typeof value === 'undefined' || value === '' || value === null) {
            is_valid = false;
        }
    });
    if (is_valid === true) {
        jQuery('#continue_booking').prop("disabled", false);
    } else {
        jQuery('#continue_booking').prop("disabled", true);
    }
}

function medical_booking_active_booking_button() {
    var is_valid = true;
    jQuery('#medicalpro-booking-form #step-2 .required').each(function () {
        var thisObj = jQuery(this);
        var value = thisObj.val();
        if (typeof value === 'undefined' || value === '' || value === null) {
            is_valid = false;
        }
    });
    if (is_valid === true) {
        jQuery('#submit_booking_btn').prop("disabled", false);
    } else {
        jQuery('#submit_booking_btn').prop("disabled", true);
    }
}

function medicalpro_booking_selected_fields() {
    var hospital_name = jQuery('#place option:selected').text();
    var hospital_address = jQuery('#place option:selected').data('address');
    jQuery('#selected_hospital').html(hospital_name + '<p>' + hospital_address + '</p>');

    var selected_time = jQuery('.available-booking-slots li.active').find('span').text();
    jQuery('#selected_time').val(selected_time);

    var selected_date = jQuery('.available-booking-slots li.active').data('date_string');
    jQuery('#selected_date').val(selected_date);

    var insurance = jQuery('#insurance option:selected').text();
    jQuery('#selected_insurance').html('<option value="">' + insurance + '</option>');
    jQuery("#selected_insurance").select2();
}

jQuery(document).ready(function(){
    
    jQuery('#mp_booking_checkout_form input[name=plan]').click(function(){
        
        if( jQuery('#mp_booking_checkout_form input[name=plan]').is(':checked') ){
            jQuery('#mp_booking_checkout_form input[name=method]').val(jQuery(this).val());
            if( jQuery('#mp_booking_checkout_form input[name=booking_id]').is(':checked') ){
                //both are checked
                jQuery('.lp_payment_step_next.booking_firstStep').addClass('active');
                lp_make_checkout_step_active('booking_firstStep');
                jQuery('.lp_payment_step_next.booking_firstStep').prop('disabled', false);
            }else{
                jQuery('.lp-checkout-steps .booking_firstStep').removeClass('current');
                jQuery('.lp_payment_step_next.booking_firstStep').removeClass('active');
                jQuery('.lp_payment_step_next.booking_firstStep').prop('disabled', true);
            }
        }
    });
    
    jQuery(document).on('click', 'button.booking_firstStep', function(){
    	if(jQuery('.inactive-payment-mode').length) {
            jQuery('.inactive-payment-mode').hide();
        }
        jQuery('#mp_booking_checkout_form input[name=listing_id]').not(':checked').closest('.lp-user-listings').css('display', 'none');
        jQuery('#mp_booking_checkout_form input[name=plan]').not(':checked').closest('.lp-method-wrap').css('display', 'none');
        
        var title  = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').data('title');
        jQuery('span.lp-subtotal-plan').text(title);
        
        var price  = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').data('price');
        mp_update_cart_values( price );
        
        jQuery('.lp-checkout-coupon-outer').css('display', 'block');
        jQuery('.active-checkout-listing').addClass('lp-checkout-wrapper-new-without-radius');
        
        
        // display terms and conditions
        jQuery('.lp-new-term-style').css('display', 'block');
        jQuery('button.lp_payment_step_next.booking_secondStep').css("display", "block");
        jQuery(this).addClass('booking_secondStep');
        jQuery(this).removeClass('booking_firstStep');
        if (jQuery(".terms-checkbox-container input[type=checkbox]").length){
            jQuery('.lp_payment_step_next.booking_secondStep').prop('disabled', true);
        }else{
            lp_make_checkout_step_active('booking_secondStep');
        }
        
    });
    
    
    jQuery(document).on('click','button.lp_payment_step_next.booking_secondStep', function(){
        
        jQuery('.lp-checkout-steps .firstStep').addClass('completed');
        jQuery('button.lp_payment_step_next.booking_thirdStep').css("display", "block");
        jQuery(this).addClass('booking_thirdStep');
        jQuery(this).removeClass('booking_secondStep');
        var paid_price = jQuery('input[name=paid_price]').val();

        if(jQuery('input[name="lp-recurring-option"]').is(':checked')) {
            jQuery(this).prop("type", "submit");
	} else {
            if (parseFloat(paid_price) == 0) {
                /* price zero or 100% discount */
                //$listings_id = jQuery('input[name=listings_id]').val();
                //lp_make_this_listing_publish_withdiscount($listings_id);
            }else{
                jQuery(this).prop("type", "submit");
            }
	}
    });
    
    /* third step */
    jQuery(document).on('click', 'button.lp_payment_step_next.booking_thirdStep', function(){
        jQuery('.lp-checkout-steps .booking_secondStep').addClass('completed');
    });
    
    
});

jQuery(document).on('click', '.terms-checkbox-container input[type=checkbox]', function(){
    if(jQuery(this).is(':checked')) {
        jQuery('.lp_payment_step_next.booking_secondStep').prop('disabled', false);
        lp_make_checkout_step_active('booking_secondStep');
    }else{
        jQuery('.lp_payment_step_next.booking_secondStep').prop('disabled', true);
        lp_make_checkout_step_passive('booking_secondStep');
    }
});

jQuery(document).on('click', 'input[name=mp_checkbox_coupon]', function(){
    jQuery(this).toggleClass('active');
    if(jQuery(this).hasClass('active')){
        jQuery('input.coupon-text-field').prop('disabled', false);
        jQuery('button.coupon-apply-btn').prop('disabled', false);
    }else{
        jQuery('input.coupon-text-field').prop('disabled', true);
        jQuery('button.coupon-apply-btn').prop('disabled', true);
        /* reset the minicart and checkout form data */
        
        var price  = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').data('price');
        mp_update_cart_values( price );
        
        jQuery('li.checkout_discount_val').remove();
    }
});

jQuery(document).on('click', 'button.coupon-apply-btn', function(){
    
    var couponcode           = jQuery('input[name=coupon-text-field]').val();
    var taxenable            = jQuery('#mp_booking_checkout_form').data('taxenable');
    var taxrate              = jQuery('#mp_booking_checkout_form').data('taxrate');
    var booking_id           = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').val();
    var price                = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').data('price');
    price                    = parseFloat(price).toFixed(2);

    if(couponcode !== ''){
        jQuery('body').addClass('listingpro-loading');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: single_ajax_object.ajaxurl,
            data: {
                action: 'listingpro_apply_coupon_code',
                coupon: couponcode,
                booking_id: booking_id,
                taxenable: taxenable,
                taxrate: taxrate,
                price: price,
                lpNonce : jQuery('#lpNonce').val()
            },
            success: function(data){
                jQuery('body').removeClass('listingpro-loading');
                if(data.status=="success"){
                    $discount     = data.discount;
                    $discounttype = data.coupontype;
                    $discountIn   = '%';
                    if($discounttype == 'on'){
                        /* means it is fixed price coupon */
                        $discountIn = '';
                    }
                    $newprice = data.price;
                    $newprice = parseFloat($newprice).toFixed(2);
                    mp_update_cart_values($newprice, taxenable, taxrate);
                    if(!jQuery('li').hasClass('checkout_discount_val')){
                       jQuery('span.lp-subtotal-p-price').parent().after('<li class="checkout_discount_val"><span class="item-price-total-left lp-subtotal-plan">Discounted</span><span class="item-price-total-right lp-subtotal-p-prasaice">'+$discount+$discountIn+'</span></li>');
                    }
                }else{
                    ajax_success_popup( data, '' )
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                jQuery('body').removeClass('listingpro-loading');
                console.log(textStatus, errorThrown);
            }
        });
    }

});

jQuery(document).on('submit', '#mp_booking_checkout_form', function(e){
    
    var $this         = jQuery(this);
    var method        = $this.find('input[name="plan"]:checked').val();
    var booking_id    = $this.find('input[name="booking_id"]:checked').val();
    var listing_id    = $this.find('input[name="booking_id"]:checked').data('listingID');
    var post_title    = $this.find('input[name="booking_id"]:checked').data('post_title');
    var hospital_name = $this.find('input[name="booking_id"]:checked').data('title');
    var paid_price    = jQuery('input[name=paid_price]').val();
    var currency      = jQuery('input[name=currency]').val();
    if (method === 'stripe') {
        
        paid_price = paid_price * 100;
        handler.open({
            name: post_title,
            description: hospital_name,
            zipCode: true,
            amount: paid_price,
            currency: currency,
        });
        e.preventDefault();
        
    } else if (method === '2checkout') {
        paid_price = jQuery('span.lp-subtotal-total-price').data('subtotal');
        listing_id = $this.find('input[name="booking_id"]:checked').val();
        jQuery('#myCCForm input#tprice').val(paid_price);
        jQuery('#myCCForm input#listing_id').val(listing_id);
        jQuery("button.lp-2checkout-modal").trigger('click');
        e.preventDefault();
    } else if (method === 'paypal') {
        jQuery('#mp_booking_checkout_form').submit();
    }
});

function mp_update_cart_values( price ){
    
    var taxenable    = jQuery('#mp_booking_checkout_form').data('taxenable');
    var taxrate      = jQuery('#mp_booking_checkout_form').data('taxrate');
    var booking_id   = jQuery('#mp_booking_checkout_form input[name=booking_id]:checked').val();
    
    price = parseFloat(price).toFixed(2);
    var paid_price = parseFloat(price).toFixed(2);
    if( taxenable == "1" ){
        var taxPrice = (taxrate/100)*price;
        taxPrice = parseFloat(taxPrice).toFixed(2);
        paid_price = parseFloat(price) + parseFloat(taxPrice);
        paid_price = parseFloat(paid_price).toFixed(2);
    }
    
    jQuery('span.lp-subtotal-p-price').text( mp_price_with_currency_sign(price) );
    jQuery('span.lp-subtotal-total-price').text( mp_price_with_currency_sign(paid_price) );
        
    jQuery('input[name="price"]').val(price);
    jQuery('input[name="paid_price"]').val(paid_price);
    jQuery('input[name="booking_id"]').val(booking_id);
    
    if( taxenable == "1" ){
        jQuery('span.lp-subtotal-taxamount').text(mp_price_with_currency_sign(taxPrice));
        jQuery('input[name="tax_price"]').val(taxPrice);
        jQuery('input[name="tax_rate"]').val(taxrate);
    }
    
}

function mp_price_with_currency_sign( price ){
    
    var currency_sign        = jQuery('#mp_booking_checkout_form').data('currency_sign');
    var currency_position    = jQuery('#mp_booking_checkout_form').data('currency_position');
    
    var price_with_currency = currency_sign + price;
    switch(currency_position){
        case('left'):
            price_with_currency = currency_sign+price;
            break;
        case('right'):
            price_with_currency = price+currency_sign;
            break;
        default:
            price_with_currency = currency_sign+price;
    }
    
    return price_with_currency;
    
}

function mp_show_recurring_switch(){
    jQuery('.lp-checkout-recurring-wrap input[name="lp-recurring-option"]').prop("checked", false);
    jQuery('.lp-checkout-recurring-wrap').css('display', 'none');
}