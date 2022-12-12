jQuery(document).ready(function (e) {

    jQuery('.add-new-lead-form').click(function (e) {
        e.preventDefault();
        jQuery('.lp-blank-section').hide();
        jQuery('.add-new-form-field.front-field-btn').show();
        var targetForm  =   '#'+jQuery(this).data('form')+'-form-toggle';
        jQuery(targetForm).slideToggle( 'fast', function () {
            jQuery('html, body').animate({
                scrollTop: jQuery(targetForm).offset().top
            }, 1000);
        });
        var byId = function (id) { return document.getElementById(id); };
        [].forEach.call(
            byId('lp-lead-form-outer-lead_form').getElementsByClassName('default-fields'), function (el) {
                Sortable.create(el, {
                    group: {
                        name: 'fields-sroter',
                        pull: 'clone',
                        put: false
                    },
                    animation: 150,
                    onEnd: function (evt) {

                        field_check( '.fields-sroter', evt );
                        lp_composer_get_data_form_buider('.fields-list');

                    }
                });
            }
        );
        [].forEach.call(
            byId('lp-lead-form-outer-lead_form').getElementsByClassName('fields-sroter'), function (el) {
                Sortable.create(el, {
                    group: 'fields-sroter',
                    animation: 150,
                    onEnd: function (evt) {
                        field_check( '.fields-sroter', evt );
                        lp_composer_get_data_form_buider('.fields-list');
                    }
                })
            }
        );
    });
    var byId = function (id) { return document.getElementById(id); };
    jQuery('.lead-form-edit-wrap').each(function (index) {
        var targetWrapID    =   jQuery(this).data('lid');
        [].forEach.call(
            byId('lp-lead-form-outer-lead_form').getElementsByClassName('fields-sroter-'+targetWrapID), function (el) {
                Sortable.create(el, {
                    group: 'fields-sroter-'+targetWrapID,
                    animation: 150,
                    onEnd: function (evt) {
                        field_check( '.fields-sroter-'+targetWrapID, evt );
                        lp_composer_get_data_form_buider('.fields-list-'+targetWrapID);
                    }
                })
            }
        );

    });
    jQuery('.select2_customizer').select2({
        ajax: {
            url: jQuery('#formajaxurl').val(),
            dataType: 'json',
            type:'GET',
            data: function (params) {
                return {
                    q: params.term, // search query
                    action: 'select2_ajax_listings' // AJAX action for admin-ajax.php
                };
                console.log(params);
            },
            processResults: function( data ) {
                var options = [];
                if ( data ) {

                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                        options.push( { id: text[0], text: text[1]  } );
                    });

                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

});

jQuery(document).on('click', '.lef-edit', function (e) {
    e.preventDefault();
    var $this   =   jQuery(this),
        PID     =   $this.data('pid'),
        UID     =   $this.data('uid');


    var tagetID =   jQuery('.edit-lead-form-wrap-'+PID);
    tagetID.slideToggle();


});
function lp_composer_get_data_form_buider( selector, PID )
{
    if( PID != '')
    {
        selector    =   jQuery(selector).find('.fields-sroter-'+PID).find('li');
    }else
    {
        selector    =   jQuery(selector).find('.fields-sroter').find('li');
    }


    var fieldShortcodes =   '',
        leadFormS       =   '[lead-form]shortcode content[/lead-form]',
        targetInput     =   '.lp-composer-result-lead_form';

    if( PID !=   '' )
    {
        targetInput     =   '.lp-composer-result-lead_form_'+PID;
    }


    selector.each(function (index) {

        var $this   =   jQuery(this),
            sName   =   $this.attr('data-name'),
            sPlaceh =   $this.attr('data-placeholder'),
            sClass  =   $this.attr('data-class'),
            sLabel  =   $this.attr('data-label'),
            sRequi  =   $this.attr('data-required'),
            sType   =   $this.attr('data-type'),
            sOpts   =   $this.attr('data-options'),
            sMulti   =   $this.attr('data-multiselect'),
            sRange  =   '',
            $thisS  =   $this.data('shortcode');

        if(sType == 'range') {
            sRange  =   'min="'+$this.attr('data-min')+'" max="'+$this.attr('data-max')+'" step="'+$this.attr('data-step')+'" def="'+$this.attr('data-def')+'"';
        }

        fieldShortcodes +=  $thisS.replace(']', ' multiselect="'+sMulti+'" '+sRange+' options="'+sOpts+'" type="'+sType+'" label="'+sLabel+'" required="'+sRequi+'" name="'+sName+'" placeholder="'+sPlaceh+'" class="'+sClass+'"]');
    });
    leadFormS   =   leadFormS.replace( 'shortcode content', fieldShortcodes );
    jQuery(targetInput).val(leadFormS);
}

jQuery(document).on('click', '.lead-form-save-front', function (e)
{

    e.preventDefault();
    if(jQuery(this).hasClass('ajax-process')){

    } else {

        if( jQuery(this).hasClass('lp-save-template-loop') )
        {
            var $this           =   jQuery(this),
                PID             =   $this.data('pid');

            lp_composer_get_data_form_buider('.fields-list-'+PID, PID);

            var listing_ID      =   PID,
                template_code   =   jQuery('.lp-composer-result-lead_form_'+PID).val(),
                update_form     =   'yes';

        }
        else
        {
            lp_composer_get_data_form_buider('.fields-list', '');
            var template_code = jQuery('.lp-composer-result-lead_form').val(),
                listing_ID = jQuery('#lead-form-listing').val(),
                update_form =   '';

            if( listing_ID == 0 || listing_ID == null || listing_ID == '' )
            {
                jQuery('.select2.select2-container').addClass('error');
                return false;
            }
            else
            {
                jQuery('.select2.select2-container').removeClass('error');
            }
        }


        jQuery(this).append('<i class="fa fa-spinner fa-spin" style="margin-left: 5px;"></i>');
        jQuery(this).addClass('ajax-process');
        jQuery.ajax({
            type: 'POST',
            url: jQuery('#formajaxurl').val(),
            data: {
                'action': 'save_cusomizer_template',
                'template_code': template_code,
                'front_end' : 'yes',
                'update_form' : update_form,
                'listing_ID' : listing_ID
            },
            success: function(data)
            {

                console.log(data);
                location.reload();
            }
        });
    }
});

jQuery(document).on('click', '.lp-remove-form-field', function (e) {
    e.preventDefault();
    jQuery(this).addClass('active-remove');

    jQuery('#dashboard-delete-modal').modal('show');
    jQuery('.modal-backdrop.fade.in').hide();

});
jQuery(document).on('click', '.dashboard-confirm-form-field-btn', function (e) {
    e.preventDefault();

    if(jQuery('.active-remove').hasClass('lf-del') )
    {
        var PID     =   jQuery('.active-remove').data('targetid'),
            UID     =   jQuery('.active-remove').data('uid');

        jQuery(this).append('<i class="fa fa-spinner fa-spin"></i>');
        jQuery.ajax({
            type: 'POST',
            url: jQuery('#formajaxurl').val(),
            data: {
                'action': 'remove_lead_form',
                'PID': PID,
                'UID' : UID,
            },
            success: function(data)
            {
                location.reload();
            }
        });
    }
    else
    {
        jQuery('.active-remove').closest('li').remove();
        jQuery('#dashboard-delete-modal').modal('hide');
        jQuery('.active-remove').removeClass('active-remove');
    }

});

jQuery(document).on('click', '.edit-lead-form', function(e)
{
    e.preventDefault();

    var $this       =   jQuery(this),
        targetID    =   $this.data('targetid'),
        updateWrap  =   '#update-wrap-'+targetID;

    jQuery('.add-new-form-field').show();
    if(jQuery('#lead-form-form-toggle').is(':visible')){
        jQuery('#lead-form-form-toggle').slideUp();
    }
    if( jQuery('.active-update-form').length != 0 )
    {
        jQuery('.active-update-form').slideUp(500, function (e) {
            jQuery('.active-update-form').removeClass('active-ann-form');
            jQuery(updateWrap).slideToggle('500', function (e) {
                jQuery(updateWrap).addClass('active-update-form');
                jQuery('.cancel-update').click(function(e)
                {
                    e.preventDefault();
                    jQuery('.active-update-form').slideUp(500, function (e) {
                        jQuery('.active-update-form').removeClass('active-update-form');
                    })
                })
            });
        });
    }
    else
    {
        jQuery(updateWrap).slideToggle('500', function (e) {
            jQuery(updateWrap).addClass('active-update-form');
            jQuery('.cancel-update').click(function(e)
            {
                e.preventDefault();
                jQuery('.active-update-form').slideUp(500, function (e) {
                    jQuery('.active-update-form').removeClass('active-update-form');
                })
            })
        });
    }
});
jQuery(document).on('click', '.cancel-lead-form', function () {
    jQuery('#lead-form-form-toggle').slideToggle();
});
