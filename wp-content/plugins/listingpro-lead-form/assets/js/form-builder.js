jQuery(document).ready(function (e) {
    jQuery('.add-new-form-field').on('click', function (e) {
        e.preventDefault();
        if( jQuery(this).hasClass('front-field-btn') )
        {
            if( jQuery('.active-update-form').length != 0 )
            {
                var pid =   jQuery('.active-update-form').data('pid');
                jQuery('.lp-lead-form-outer-'+pid).slideToggle();
                jQuery('.save-lead-form-loop-wrap').hide();
            }
            else
            {
                jQuery('.save-lead-form-wrap').hide();
                jQuery('.lp-lead-form-outer').slideToggle();
            }
        }
        else
        {
            var targetModal  =   jQuery(this).data('target');
            if( jQuery(this).hasClass('add-new-form-field-loop') )
            {
                var targetID    =   jQuery(this).data('pid');
                jQuery('.add-form-field').attr("data-pid", targetID);
            }

            jQuery(targetModal).modal('show');
            jQuery('.modal-backdrop').hide();
            jQuery(targetModal).on('hidden.bs.modal', function (e) {
                jQuery('.add-form-field').attr("data-pid", '');
                document.getElementById("add-new-field-popup").reset();
            });
        }
    });
});
jQuery(document).on('click', '.cancel-new-field', function (e) {
    e.preventDefault();
    jQuery('.lp-lead-form-outer').slideToggle();
});
jQuery(document).on('keyup', '.lp-opt-placeholder', function(e){

    e.preventDefault();
    var $this   =   jQuery(this),
        optWrap =   $this.closest('li'),
        placeh  =   $this.val();

    optWrap.attr('data-placeholder', placeh);

});
jQuery(document).on('keyup', '.opt-label', function(e){

    e.preventDefault();
    var $this   =   jQuery(this),
        optWrap =   $this.closest('li'),
        placeh  =   $this.val();

    optWrap.attr('data-label', placeh);

});
jQuery(document).on('click', '.add-form-field', function (e) {
    e.preventDefault();

    var targetSorter    =   jQuery(this).attr('data-pid');

    if( targetSorter == '' || targetSorter == 'undefined' || targetSorter == null )
    {
        var field_required  =    "required='no' ",
            field_type      =   "data-type='"+jQuery('#field-type').val()+"' ",
            field_typee     =   jQuery('#field-type').val(),
            field_label      =   jQuery('#field-label').val();


        var field_name      =   jQuery('#field-name').val(),
            field_name      =   field_name.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_'),
            field_name      =   "data-name='"+ field_name +"' ";


        if( jQuery('#field-label').val() == '' )
        {
            jQuery('#field-label').addClass('error');
        }
        else
        {
            jQuery('#field-label').removeClass('error');
        }
        if( jQuery('#field-name').val() == '' )
        {
            jQuery('#field-name').addClass('error');
        }
        else
        {
            jQuery('#field-name').removeClass('error');
        }
        if( jQuery('#field-label').val() == '' || jQuery('#field-name').val() == '' ){
            return false;
        }

        var field_options   =   '',
            multiSelect     =   '',
            palceholder     =   '';

        var rangeAtts   =   '';


        if( jQuery('#field-required').is(':checked') )
        {
            field_required  =   "data-required='yes' ";
        }

        if( field_typee == 'dropdown' || field_typee == 'checkbox' || field_typee == 'radio' )
        {

            var fieldOptions    =   jQuery('#field-options').val();
            fieldOptions = fieldOptions.replace(/\n/g, " ");
            field_options   =   "data-options='"+fieldOptions+",' ";
        }
        if( field_typee == 'dropdown' )
        {
            jQuery('.multiselect-field').show();
            if( jQuery('#field-multi').is(':checked') )
            {
                multiSelect =   "data-multiselect='yes' ";
            }
        }
        else
        {
            jQuery('.multiselect-field').hide();
        }
        if( field_typee == 'text' || field_typee == 'email' || field_typee == 'tel' || field_typee == 'url' )
        {
            var placeholderVal  =   jQuery('#field-placeholder').val();
            palceholder         =   "data-placeholder='"+ placeholderVal +"' ";
        }
        if( field_typee == 'range' )
        {
            rangeAtts   +=   "data-min='"+jQuery('#min-val').val()+"' ";
            rangeAtts   +=   "data-max='"+jQuery('#max-val').val()+"' ";
            rangeAtts   +=   "data-step='"+jQuery('#step-val').val()+"' ";
            rangeAtts   +=   "data-def='"+jQuery('#def-val').val()+"' ";
        }
        var field_label_val =   "data-label='"+ field_label +"' ";

        var field_attrs =   field_required+' '+field_name+' '+field_label_val+' '+ field_type+' '+field_options+' '+ multiSelect +' '+ palceholder +' '+ rangeAtts;

        var fieldShortcode  =   'data-shortcode="[lp-customizer-field]"';
        var fieldMarkup =   '<li '+ fieldShortcode +' '+ field_attrs +'"><div class="lp-menu-closed clearfix "> ' +
            '<span><i class="fa fa-bars" aria-hidden="true"></i></span> ' +
            '<span class="lp-right-side-title">'+ field_label +'</span> ' +
            '<span class="lp-el-remove"><i class="fa fa-trash-o" aria-hidden="true"></i></span> ' +
            '</div></li>';


        if( jQuery(this).hasClass('form-field-front') )
        {
            jQuery('.lp-lead-form-outer').slideToggle('slow', function () {
                if( targetSorter != '' )
                {
                    jQuery('.fields-sroter-'+targetSorter).append(fieldMarkup);
                }
                else
                {
                    jQuery('.fields-sroter').append(fieldMarkup);
                }
            });
        }
        else
        {
            jQuery('#fieldModal').modal('hide');
            jQuery("#lp-notice-customizer-warning").slideDown('900');
        }
        jQuery('#lp-lead-form').find('input, textarea').val('');
        jQuery('#lp-lead-form').find('select').val('text').change();
        jQuery('#lp-lead-form').find('input[type="checkbox"]').removeAttr('checked');
        jQuery('.save-lead-form-wrap').show();
    }
    else
    {
        var field_required  =    "data-required='no' ",
            field_type      =   "data-type='"+jQuery('#field-type-'+targetSorter).val()+"' ",
            field_typee     =   jQuery('#field-type-'+targetSorter).val(),
            field_label      =   jQuery('#field-label-'+targetSorter).val();

        var field_name      =   jQuery('#field-name-'+targetSorter).val(),
            field_name      =   field_name.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_'),
            field_name      =   "data-name='"+ field_name +"' ";


        if( jQuery('#field-label-'+targetSorter).val() == '' )
        {
            jQuery('#field-label-'+targetSorter).addClass('error');
        }
        else
        {
            jQuery('#field-label-'+targetSorter).removeClass('error');
        }
        if( jQuery('#field-name-'+targetSorter).val() == '' )
        {
            jQuery('#field-name-'+targetSorter).addClass('error');
        }
        else
        {
            jQuery('#field-name-'+targetSorter).removeClass('error');
        }
        if( jQuery('#field-label-'+targetSorter).val() == '' || jQuery('#field-name-'+targetSorter).val() == '' ){
            return false;
        }

        var field_options   =   '',
            multiSelect     =   '',
            palceholder     =   '';

        var rangeAtts   =   '';


        if( jQuery('#field-required-'+targetSorter).is(':checked') )
        {
            field_required  =   "data-required='yes' ";
        }

        if( field_typee == 'dropdown' || field_typee == 'checkbox' || field_typee == 'radio' )
        {
            var fieldOptions    =   jQuery('#field-options-'+targetSorter).val();
            fieldOptions = fieldOptions.replace(/\n/g, " ");
            field_options   =   "data-options='"+fieldOptions+",' ";
        }


        if( field_typee == 'dropdown' )
        {
            jQuery('.multiselect-field').show();
            if( jQuery('#field-multi-'+targetSorter).is(':checked') )
            {
                multiSelect =   "data-multiselect='yes' ";
            }
        }
        else
        {
            jQuery('.multiselect-field').hide();
        }
        if( field_typee == 'text' || field_typee == 'email' || field_typee == 'tel' || field_typee == 'url' )
        {
            var placeholderVal  =   jQuery('#field-placeholder-'+targetSorter).val();
            palceholder         =   "data-placeholder='"+ placeholderVal +"' ";
        }
        if( field_typee == 'range' )
        {
            rangeAtts   +=   "data-min='"+jQuery('#min-val-'+targetSorter).val()+"' ";
            rangeAtts   +=   "data-max='"+jQuery('#max-val-'+targetSorter).val()+"' ";
            rangeAtts   +=   "data-step='"+jQuery('#step-val-'+targetSorter).val()+"' ";
            rangeAtts   +=   "data-def='"+jQuery('#def-val-'+targetSorter).val()+"' ";
        }

        var field_label_val =   "data-label='"+ field_label +"' ";
        if( jQuery(this).hasClass('form-field-front') ){
            var field_attrs =   field_required+' '+field_name+' '+field_label_val+' '+ field_type+' '+field_options+' '+ multiSelect +' '+ palceholder +' '+ rangeAtts;

            var fieldShortcode  =   'data-shortcode="[lp-customizer-field]"';
            var fieldMarkup =   '<li '+ fieldShortcode +' '+ field_attrs +'"><div class="lp-menu-closed clearfix "> ' +
                '<span><i class="fa fa-bars" aria-hidden="true"></i></span> ' +
                '<span class="lp-right-side-title">'+ field_label +'</span> ' +
                '<span class="lp-el-remove"><i class="fa fa-trash-o" aria-hidden="true"></i></span> ' +
                '</div></li>';
        } else {
            var fieldShortcode  =   'data-shortcode="[lp-customizer-field '+ field_name +' ' + field_required + ''+ field_type +''+ field_options +''+ multiSelect +''+ palceholder +''+ field_label_val +''+ rangeAtts +']"';
            var fieldMarkup =   '<li '+ fieldShortcode +' data-name="'+ field_name +'"><div class="lp-menu-closed clearfix "> ' +
                '<span><i class="fa fa-bars" aria-hidden="true"></i></span> ' +
                '<span class="lp-right-side-title">'+ field_label +'</span> ' +
                '<span class="pull-right"><i class="fa fa-trash-o" aria-hidden="true"></i></span> ' +
                '</div></li>';
        }

        if( jQuery(this).hasClass('form-field-front') )
        {
            jQuery('.lp-lead-form-outer-'+targetSorter).slideToggle('slow', function () {
                jQuery('.fields-sroter-'+targetSorter).append(fieldMarkup);
            });
        }
        else
        {
            jQuery('#fieldModal').modal('hide');
            jQuery("#lp-notice-customizer-warning").slideDown('900');
        }

        jQuery('#lp-lead-form-form-'+targetSorter)[0].reset();
        jQuery('.save-lead-form-loop-wrap').show();

    }

    if(jQuery(this).hasClass('lead-form-front-field')) {

    } else {
        jQuery('.lp-save-template').find('.fa-save').removeClass('fa-save').addClass('fa-spinner fa-spin');
        setTimeout(function () {
            lp_composer_get_data_form_buider('.template-customizer-wrap-inner.active-style');
            var template_code   =   jQuery('.lp-composer-result-lead_form').val();

            var lead_ajax_url   =   jQuery('#lead-ajax-url').val();
            jQuery("#lp-notice-customizer-warning").slideUp('900');
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: lead_ajax_url,
                data: {
                    'action': 'save_cusomizer_template',
                    'template_code': template_code,
                },
                success: function(data)
                {
                    jQuery('.lp-save-template').find('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-save');
                    jQuery("#lp-notice-customizer").slideDown('900').delay(3000).slideUp('900');
                }
            });
        }, 1000);

    }

});

jQuery(document).on('change', '.field-type', function (e) {
    e.preventDefault();

    var checkPID    =   jQuery(this).data('pid');
    var field_type  =   jQuery(this).val();

    if( field_type == 'dropdown' || field_type == 'checkbox' || field_type == 'radio' )
    {
        jQuery('.options-field').show();
    }
    else
    {
        jQuery('.options-field').hide();
    }

    if( field_type == 'dropdown' )
    {
        jQuery('.multiselect-field').show();
    }
    else
    {
        jQuery('.multiselect-field').hide();
    }

    if( field_type == 'text' || field_type == 'email' || field_type == 'tel' || field_type == 'url' )
    {
        jQuery('.field-placeholder').show();
    }
    else
    {
        jQuery('.field-placeholder').hide();
    }
    if( field_type == 'range' )
    {
        jQuery('.range-extra').show();
    }
    else
    {
        jQuery('.range-extra').hide();
    }
});


function field_check( selector, evt )
{
   var dataName    =   evt.clone.attributes[0]['nodeValue'];
   var elLength    =   jQuery(selector).find('li[data-name="'+ dataName +'"]').length;

   if( elLength > 1 )
   {
       var notification_text   =   'You cannot add duplicate field';
       jQuery('#lp-el-notification p.message-text').text(notification_text);
       jQuery('#lp-el-notification').modal('show');
       jQuery('.modal-backdrop').hide();
       evt.item.parentNode.removeChild(evt.item);
   }
}