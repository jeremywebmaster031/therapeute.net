var widgetsubmit;


/**
 * detect IE
 * returns version of IE or false, if browser is not Internet Explorer
 */
function detectIE() {
    var ua = window.navigator.userAgent;

    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }

    // other browser
    return false;
}

function recaptchaCallbackk() {
    if (jQuery('#recaptcha-securet').length) {
        var sitekey = jQuery('#recaptcha-securet').data('sitekey');
        widgetsubmit = grecaptcha.render(document.getElementById('recaptcha-securet'), {
            'sitekey': sitekey
        })
    }
}
window.onload = recaptchaCallbackk;

jQuery(document).on('input', '#extra-feature-virtual-consult', function(e){
    var $this = jQuery(this);
    if ($this.is(':checked')){
        $this.closest('.lp-new-cat-wrape').find('.lp-mp-video-consult').slideDown();
    }else{
        $this.closest('.lp-new-cat-wrape').find('.lp-mp-video-consult').slideUp();
    }
});

jQuery(document).ready(function () {
    jQuery(document).on('click', '.removethishospital', function () {
        var $this = jQuery(this),
            tab = $this.closest('.mp-hospital-tabber-tab');
            content = tab.data('content-id');
        jQuery('#' + content).remove();
        tab.remove();
    });

    jQuery(document).on('click', '.mp-hospital-tabber-tab', function () {
        var $this = jQuery(this);
        jQuery('.mp-hospital-tabber-tab').removeClass('active');
        $this.addClass('active');
        jQuery('.mp-hospital-tabber-tab-content').removeClass('active');
        jQuery('#' + $this.data('content-id')).addClass('active');
    });
});

jQuery(document).on('submit', '#lp-submit-form', function (e) {
    jQuery('.error_box').hide('');
    jQuery('.error_box').html('');
    jQuery('.error_box').text('');
    jQuery('.username-invalid-error').html('');
    jQuery('.username-invalid-error').text('');
    var $this = jQuery(this);
    jQuery('span.email-exist-error').remove();
    jQuery('input').removeClass('error-msg');
    jQuery('textarea').removeClass('error-msg');
    $this.find('.preview-section .fa-angle-right').removeClass('fa-angle-right');
    $this.find('.preview-section .fa').addClass('fa-spinner fa-spin');
    jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-spinner fa-spin');
    jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-spinner fa-spin');

    isCaptcha = jQuery(this).data('lp-recaptcha');
    siteKey = jQuery(this).data('lp-recaptcha-sitekey');
    token = '';

    var fd = new FormData(this);

    $maxAlloedSize = jQuery('#lp-submit-form').data('imgsize');
    $totalAlloedImgs = jQuery('#lp-submit-form').data('imgcount');

    if (detectIE() == false) {

        var $fullbrowserdet = navigator.sayswho;
        var $browserArray = $fullbrowserdet.split(" ");// outputs: `Chrome 62`
        if ($browserArray[0] == "Safari") {
            if ($browserArray[1] >= 12) {
                fd.delete('listingfiles[]');
                fd.delete('lp-featuredimage[]');
                fd.delete('business_logo[]');
            }

        } else {

            fd.delete('listingfiles[]');
            fd.delete('lp-featuredimage[]');
            fd.delete('business_logo[]');

        }


    }
    $totalfilesize = 0;
    var lpcount = 0;
    var lpcountsize = 0;
    $totalfilesize = jQuery('.lplistgallery').attr('data-savedgallweight');

    $selectedImagesCount = jQuery('.lplistgallery').attr('data-savedgallerysize');
    if (jQuery("input[name='listingfiles[]']").length) {

        jQuery.each(jQuery("input[name='listingfiles[]']"), function (k, files) {
            jQuery.each(jQuery("input[name='listingfiles[]']")[k].files, function (i, file) {
                if (file.size > 1 || file.fileSize > 1) {
                    $totalfilesize = parseInt($totalfilesize) + parseInt(file.size);
                    fd.append('listingfiles[' + lpcount + ']', file);
                    lpcount++;

                }
            });
        });
    }

    lpcount = parseInt(lpcount) + parseInt($selectedImagesCount);
    $AlloedSize = true;
    $Alloedimgcount = true;
    if (!isNaN($totalfilesize)) {
        if ($totalfilesize > $maxAlloedSize) {
            msgf = jQuery('#lp-submit-form').data('sizenotice');
            var resError = {response: 'fail', msg: msgf};
            listing_ajax_response_notice(resError);
            $this.find('.preview-section').addClass('fa-angle-right');
            jQuery('.lpsubmitloading').addClass('fa-angle-right');
            $this.find('.preview-section .fa').removeClass('fa-spinner fa-spin');
            jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
            jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
            $AlloedSize = false;
            return false;
        }
    }
    if (lpcount > $totalAlloedImgs) {
        msgf = jQuery('#lp-submit-form').data('countnotice');
        var resError = {response: 'fail', msg: msgf};
        listing_ajax_response_notice(resError);
        jQuery('.lpsubmitloading').addClass('fa-angle-right');
        $this.find('.preview-section').addClass('fa-angle-right');
        $this.find('.preview-section .fa').removeClass('fa-spinner fa-spin');
        jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
        jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
        $Alloedimgcount = false;
    } else {



        if (jQuery("input[name='business_logo[]']").length) {
            fd.append('business_logo[]', jQuery("input[name='business_logo[]']")[0].files[0]);
        }

        if (jQuery("input[name='lp-featuredimage[]']").length) {
            fd.append('lp-featuredimage[]', jQuery("input[name='lp-featuredimage[]']")[0].files[0]);
        }
        jQuery("#listingsubmitBTN").prop('disabled', !0);
        fd.append('action', 'medpro_submit_listing_ajax');
        if (jQuery('#already-account').is(':checked')) {
            fd.append('processLogin', 'yes')
        } else {
            fd.append('processLogin', 'no')
        }
        var postContent = tinymce.editors.inputDescription.getContent();
        if (postContent != '' || postContent != null || postContent != !1) {
            fd.append('postContent', postContent)
        } else {
            fd.append('postContent', '')
        }
        if ($Alloedimgcount == true) {
            fd.append('imageCount', lpcount)
        }
        if ((isCaptcha == '' || isCaptcha === null) || (siteKey == '' || siteKey === null)) {
            jQuery.ajax({
                type: 'POST',
                url: ajax_medpro_submit_object.ajaxurl,
                data: fd,
                contentType: !1,
                processData: !1,
                success: function (res) {

                    var resp = jQuery.parseJSON(res);
                    listing_ajax_response_notice(resp);
                    if (resp.response === "fail") {
                        jQuery("#listingsubmitBTN").prop('disabled', !1);
                        jQuery.each(resp.status, function (k, v) {
                            if (k === "postTitle") {
                                jQuery("input:text[name='postTitle']").addClass('error-msg')
                            } else if (k === "gAddress") {
                                jQuery("input:text[name='gAddress']").addClass('error-msg')
                            }else if (k === "videoconsult") {
                                jQuery("input[name='videoconsult']").addClass('error-msg')
                            } else if (k === "category") {
                                jQuery("#inputCategory_chosen").find('a.chosen-single').addClass('error-msg');
                                jQuery("#inputCategory").next('.select2-container').find('.selection').find('.select2-selection--single').addClass('error-msg');
                                jQuery("#inputCategory").next('.select2-container').find('.selection').find('.select2-selection--multiple').addClass('error-msg')
                            } else if (k === "location") {
                                jQuery("#inputCity_chosen").find('a.chosen-single').addClass('error-msg');
                                jQuery("#inputCity").next('.select2-container').find('.selection').find('.select2-selection--single').addClass('error-msg');
                                jQuery("#inputCity").next('.select2-container').find('.selection').find('.select2-selection--multiple').addClass('error-msg')
                            } else if (k === "postContent") {
                                jQuery("textarea[name='postContent']").addClass('error-msg');
                                jQuery("#lp-submit-form .wp-editor-container").addClass('error-msg')
                            } else if (k === "email") {
                                jQuery("input#inputEmail").addClass('error-msg')
                            } else if (k === "inputUsername") {
                                jQuery("input#inputUsername").addClass('error-msg')
                            } else if (k === "inputUserpass") {
                                jQuery("input#inputUserpass").addClass('error-msg')
                            }
                        });
                        var errorrmsg = jQuery("input[name='errorrmsg']").val();
                        $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                        $this.find('.preview-section .fa').addClass('fa-times');
                        $this.find('.preview-section').find('.error_box').text(errorrmsg).show();
                        jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                        jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-times');
                        jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                        jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-times');
                    } else if (resp.response === "failure") {
                        if (jQuery('#already-account').is(':checked')) {
                            jQuery('.lp-submit-have-account').append(resp.status)
                        } else {
                            jQuery("input#inputEmail").after(resp.status);
                            jQuery("div#inputEmail").after(resp.status)
                        }
                        $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                        $this.find('.preview-section .fa').addClass('fa-angle-right');
                        jQuery("#listingsubmitBTN").prop('disabled', !1);
                        jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                        jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-times');
                        jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                        jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-times');
                    } else if (resp.response === "success") {
                        $this.find('.preview-section .fa-spinner').removeClass('fa-times');
                        $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                        $this.find('.preview-section .fa').addClass('fa-check');
                        jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-times');
                        jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-check');
                        jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                        jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-angle-right');
                        jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-check');
                        var redURL = resp.status;

                        function redirectPageNow() {
                            window.location.href = redURL
                        }
                        setTimeout(redirectPageNow, 1000)
                    }
                },
                error: function (request, error) {
                    $this.find('.preview-section .fa-spinner').removeClass('fa-times');
                    $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                    $this.find('.preview-section .fa').addClass('fa-times');
                    alert(error)
                }
            });


        } else {
            //for recaptcha
            grecaptcha.ready(function () {
                grecaptcha.execute(siteKey, {action: 'lp_submitlisting'}).then(function (token) {
                    fd.append('recaptha-action', 'lp_submitlisting');
                    fd.append('token', token);


                    jQuery.ajax({
                        type: 'POST',
                        url: ajax_medpro_submit_object.ajaxurl,
                        data: fd,
                        contentType: !1,
                        processData: !1,
                        success: function (res) {

                            var resp = jQuery.parseJSON(res);
                            listing_ajax_response_notice(resp);
                            if (resp.response === "fail") {
                                jQuery("#listingsubmitBTN").prop('disabled', !1);
                                jQuery.each(resp.status, function (k, v) {
                                    if (k === "postTitle") {
                                        jQuery("input:text[name='postTitle']").addClass('error-msg')
                                    } else if (k === "gAddress") {
                                        jQuery("input:text[name='gAddress']").addClass('error-msg')
                                    } else if (k === "category") {
                                        jQuery("#inputCategory_chosen").find('a.chosen-single').addClass('error-msg');
                                        jQuery("#inputCategory").next('.select2-container').find('.selection').find('.select2-selection--single').addClass('error-msg');
                                        jQuery("#inputCategory").next('.select2-container').find('.selection').find('.select2-selection--multiple').addClass('error-msg')
                                    } else if (k === "location") {
                                        jQuery("#inputCity_chosen").find('a.chosen-single').addClass('error-msg');
                                        jQuery("#inputCity").next('.select2-container').find('.selection').find('.select2-selection--single').addClass('error-msg');
                                        jQuery("#inputCity").next('.select2-container').find('.selection').find('.select2-selection--multiple').addClass('error-msg')
                                    } else if (k === "postContent") {
                                        jQuery("textarea[name='postContent']").addClass('error-msg');
                                        jQuery("#lp-submit-form .wp-editor-container").addClass('error-msg')
                                    } else if (k === "email") {
                                        jQuery("input#inputEmail").addClass('error-msg')
                                    } else if (k === "inputUsername") {
                                        jQuery("input#inputUsername").addClass('error-msg')
                                    } else if (k === "inputUserpass") {
                                        jQuery("input#inputUserpass").addClass('error-msg')
                                    }
                                });
                                var errorrmsg = jQuery("input[name='errorrmsg']").val();
                                $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                                $this.find('.preview-section .fa').addClass('fa-times');
                                $this.find('.preview-section').find('.error_box').text(errorrmsg).show();
                                jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                                jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-times');
                                jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                                jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-times');
                            } else if (resp.response === "failure") {
                                if (jQuery('#already-account').is(':checked')) {
                                    jQuery('.lp-submit-have-account').append(resp.status)
                                } else {
                                    jQuery("input#inputEmail").after(resp.status);
                                    jQuery("div#inputEmail").after(resp.status)
                                }
                                $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                                $this.find('.preview-section .fa').addClass('fa-angle-right');
                                jQuery("#listingsubmitBTN").prop('disabled', !1);
                                jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                                jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-times');
                                jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                                jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-times');
                            } else if (resp.response === "success") {
                                $this.find('.preview-section .fa-spinner').removeClass('fa-times');
                                $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                                $this.find('.preview-section .fa').addClass('fa-check');
                                jQuery('.bottomofbutton.lpsubmitloading').removeClass('fa-times');
                                jQuery('.bottomofbutton.lpsubmitloading').addClass('fa-check');
                                jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-spinner fa-spin');
                                jQuery('.loaderoneditbutton.lpsubmitloading').removeClass('fa-angle-right');
                                jQuery('.loaderoneditbutton.lpsubmitloading').addClass('fa-check');
                                var redURL = resp.status;

                                function redirectPageNow() {
                                    window.location.href = redURL
                                }
                                setTimeout(redirectPageNow, 1000)
                            }
                        },
                        error: function (request, error) {
                            if (!jQuery('#recaptcha-securet').length === 0) {
                                lp_reset_grecaptcha()
                            }
                            $this.find('.preview-section .fa-spinner').removeClass('fa-times');
                            $this.find('.preview-section .fa-spinner').removeClass('fa-spinner fa-spin');
                            $this.find('.preview-section .fa').addClass('fa-times');
                            alert(error)
                        }
                    });


                });
            })


        }
    }
    e.preventDefault()
});



function listing_ajax_response_notice(res) {
    if (res.response == 'success') {
        jQuery('.lp-notifaction-area').find('h4').text(res.msg);
        jQuery('.lp-notifaction-area').removeClass('lp-notifaction-error').addClass('lp-notifaction-success');
        jQuery('.lp-notifaction-area').addClass('active-wrap');

    }
    if (res.response == 'fail' || res.response == 'failure') {
        jQuery('.lp-notifaction-area').find('h4').text(res.msg);
        jQuery('.lp-notifaction-area').removeClass('lp-notifaction-success').addClass('lp-notifaction-error');
        jQuery('.lp-notifaction-area').addClass('active-wrap');
    }
}

//check browser code

navigator.sayswho = (function () {
    var ua = navigator.userAgent, tem,
            M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if (/trident/i.test(M[1])) {
        tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE ' + (tem[1] || '');
    }
    if (M[1] === 'Chrome') {
        tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
        if (tem != null)
            return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
    if ((tem = ua.match(/version\/(\d+)/i)) != null)
        M.splice(1, 1, tem[1]);
    return M.join(' ');
})();

jQuery(document).on('change', '.hospital-name', function (e) {
    var phone = jQuery(this).find('option:selected').data('phone'),
        id = jQuery(this).find('option:selected').data('id');
    jQuery(this).closest('.tab-content').find('.hospital-phone').val(phone);
    jQuery(this).closest('.tab-content').find('.mark-this-primary').val(id);
});

jQuery(document).on('change', '.hospitalfulldayopen', function (e) {
    var $this = jQuery(this);
    if (this.checked) {
        $this.closest('.hospital-business-hours').find('select.hours-start').prop("disabled", !0);
        $this.closest('.hospital-business-hours').find('select.hours-end').prop("disabled", !0);
        $this.closest('.hospital-business-hours').find('select.hours-start2').prop("disabled", !0);
        $this.closest('.hospital-business-hours').find('select.hours-end2').prop("disabled", !0)
    } else {
        $this.closest('.hospital-business-hours').find('select.hours-start').prop("disabled", !1);
        $this.closest('.hospital-business-hours').find('select.hours-end').prop("disabled", !1);
        $this.closest('.hospital-business-hours').find('select.hours-start2').prop("disabled", !1);
        $this.closest('.hospital-business-hours').find('select.hours-end2').prop("disabled", !1)
    }
});

jQuery(document).on('click', 'button.add-hospital-hours', function (event) {
    event.preventDefault();

    var $this = jQuery(this);
    var $this = jQuery(this);
    
    var error = !1;
    var fullday = '';
    var fullhoursclass = '';
    var lpdash = "~";
   
    var weekday = $this.closest('.hospital-business-hours').find('select.weekday').val();
    var rand_id = $this.closest('.hospital-business-hours').find('.day-hours').data('id');
    
    if ($this.closest('.hospital-business-hours').find(".hospitalfulldayopen").is(":checked")) {
        $this.closest('.hospital-business-hours').find('.hospitalfulldayopen').attr('checked', !1);
        $this.closest('.hospital-business-hours').find('select.hours-start').prop("disabled", !1);
        $this.closest('.hospital-business-hours').find('select.hours-end').prop("disabled", !1);
        var startVal = '';
        var endVal = '';
        var hrstart = '';
        var hrend = '';
        fullday = $this.data('fullday');
        fullhoursclass = 'fullhours';
        lpdash = ""
    } else {
        var startVal = $this.closest('.hospital-business-hours').find('select.hours-start').val();
        var endVal = $this.closest('.hospital-business-hours').find('select.hours-end').val();
        var hrstart = $this.closest('.hospital-business-hours').find('select.hours-start').find('option:selected').val();
        var hrend = $this.closest('.hospital-business-hours').find('select.hours-end').find('option:selected').val();
        var startVal_digit = hrstart.replace(':', '');
        var endVal_digit = hrend.replace(':', '');

        if (startVal_digit.indexOf('am') > -1) {
            startVal_digit = startVal_digit.replace('am', '');
        }
        else if (startVal_digit.indexOf('pm') > -1) {
            startVal_digit = startVal_digit.replace('pm', '');
            if (startVal_digit != '1200' && startVal_digit != '1230') {
                startVal_digit = parseInt(startVal_digit) + 1200;
            }
        }
        if (endVal_digit.indexOf('am') > -1) {
            endVal_digit = endVal_digit.replace('am', '');
            endVal_digit = parseInt(endVal_digit);
            if(endVal_digit >= 1200){
                endVal_digit = parseInt(endVal_digit) - 1200;
            }

        }
        else if (endVal_digit.indexOf('pm') > -1) {
            endVal_digit = endVal_digit.replace('pm', '');
            endVal_digit = parseInt(endVal_digit) + 1200;
        }
        if (startVal_digit > endVal_digit) {
            nextWeekday = $this.closest('.hospital-business-hours').find("select.weekday option:selected+option").val();
            if (typeof nextWeekday === "undefined") {
                nextWeekday = $this.closest('.hospital-business-hours').find("select.weekday").find("option:first-child").val()
            }
            weekday = weekday + "~" + nextWeekday
        }
    }
    var sorryMsg = $this.data('sorrymsg');
    var alreadyadded = $this.data('alreadyadded');
    if( $this.hasClass('lp-add-hours-st') )
    {
        var remove = '<i class="fa fa-times"></i>';
    }
    else
    {
        var remove  =   $this.data('remove');
    }

    $this.closest('.hospital-business-hours').find('.hours-display .hours').each(function(index, element) {
        var weekdayTExt = jQuery(element).children('.weekday').text();
        if (weekdayTExt == weekday) {
            alert(sorryMsg + '! ' + weekday + ' ' + alreadyadded);
            error = !0
        }
    });
    
    if (error != !0) {
        $this.closest('.hospital-business-hours').find('.hours-display').append("<div class='hours " + fullhoursclass + "'><span class='weekday'>" + weekday + "</span><span class='start-end fullday'>" + fullday + "</span><span class='start'>" + hrstart + "</span><span>" + lpdash + "</span><span class='end'>" + hrend + "</span><a class='remove-hours' href='#'>" + remove + "</a><input name='medicalpro_hospitals[" + rand_id + "][business_hours][" + weekday + "][open]' value='" + startVal + "' type='hidden'><input name='medicalpro_hospitals[" + rand_id + "][business_hours][" + weekday + "][close]' value='" + endVal + "' type='hidden'></div>");
        var current = $this.closest('.hospital-business-hours').find('select.weekday').find('option:selected');
        var nextval = current.next();
        current.removeAttr('selected');
        nextval.attr('selected', 'selected');
        $this.closest('.hospital-business-hours').find('select.weekday').trigger('change.select2')
    }
    
});

jQuery(document).on('change', '.mp_suggest_hospital', function (event) {
    // event.preventDefault();
    var $this = jQuery(this),
        parent = $this.closest('div.mp_hospital_edit_form'),
        targetShow = $this.closest('div.col-md-12').find('.mp_suggest_hospital_name'),
        targetHide = $this.closest('div.col-md-12').find('.mp_suggest_hospital_name_dd');

    if ($this.is(":checked")) {
        targetShow.show();
        targetHide.hide();
        parent.find('.hospital_primary_class').hide();
        parent.find('.hospital_phone_class').hide();
        parent.find('.hospital_price_class').removeClass('col-md-6').addClass('col-md-12');
    }else {
        targetShow.hide();
        targetHide.show();
        parent.find('.hospital_primary_class').show();
        parent.find('.hospital_phone_class').show();
        parent.find('.hospital_price_class').removeClass('col-md-12').addClass('col-md-6');
    }

});

jQuery(document).on('click', 'a#hospital_btn', function (e) {
    e.preventDefault();
    
    var empty_required_fields = false;
    jQuery(".lsiting-submit-hospitals-tabs .tab-content").each(function(i) {
        var hospital_name = jQuery(this).find('.hospital-name').val();
        if( hospital_name === '' && !jQuery(this).find('.mp_suggest_hospital').is(':checked') ){
            empty_required_fields = true;
            jQuery(this).find('.hospital-name').next('.select2-container').find('.select2-selection').css('border', '1px solid red');
        }else if(jQuery(this).find('.mp_suggest_hospital_name').find('.form-control').val() === '' && jQuery(this).find('.mp_suggest_hospital').is(':checked')) {
            empty_required_fields = true;
            jQuery(this).find('.mp_suggest_hospital_name').find('.form-control').css('border', '1px solid red');
        }
    });
    if( empty_required_fields === true ){
        var response = {
          response: 'fail',
          msg: ajax_search_term_object.empty_fields_error,
        }
        listing_ajax_response_notice(response);
        return false;
    }
    
    var listing_id = jQuery(this).data('listing_id');
    if (!jQuery("input[name=medicalpro_primary]:checked").val()) {
        jQuery("input[name=medicalpro_primary]").prop('checked', 'checked');
    }
    jQuery.ajax({
        type: 'POST',
        url: ajax_medpro_submit_object.ajaxurl,
        data: 'action=medicalpro_add_listing_hospital&listing_id='+ listing_id,
        success: function (data) {
            var HTML = jQuery.parseJSON(data);
            jQuery('.mp-hospital-tabber-tab').removeClass('active');
            jQuery('.mp-hospital-tabber-tab-content').removeClass('active');
            jQuery('.mp-hospital-tabber-tabs-content').append(HTML.tabContent);
            jQuery('.mp-hospital-tabber-tabs').append(HTML.tabHTML);
            jQuery('select.select2').select2();
        }
    });
});

jQuery(document).on('input', '.mp_suggest_hospital_name input', function (e) {
    var thisObj  =  jQuery(this)
    var tabID = '#tab-' + thisObj.closest('.mp-hospital-tabber-tab-content').attr('id');
    jQuery(tabID).find('p').text(thisObj.val());
});
jQuery(document).on('input', '.mp_suggest_hospital_name input', function (e) {
    jQuery(this).css('border', '');
});
jQuery(document).on('change', '.lsiting-submit-hospitals-tabs .hospital-name', function (e) {
    var thisObj  =  jQuery(this);
    var selected_hospital  = thisObj.val();
    var hospital_count = 0;
    var selected_hospital_text = thisObj.find('option:selected').text();
    var tabID = '#tab-' + thisObj.closest('.mp-hospital-tabber-tab-content').attr('id');

    jQuery(tabID).find('p').text(selected_hospital_text);

    jQuery(".lsiting-submit-hospitals-tabs .hospital-name").each(function(i) {
        if( selected_hospital === jQuery(this).val() ){
            hospital_count = Number(hospital_count) + 1 ;
        }
    });
    if( hospital_count > 1 ){
        var response = {
            response: 'fail',
            msg: ajax_search_term_object.duplicate_hospital,
        }
        listing_ajax_response_notice(response);
        thisObj.val('').select2();
        return false;
    }
    if(jQuery(this).val() !== ''){
        jQuery(this).next('.select2-container').find('.select2-selection').css('border', '');
    }
});


