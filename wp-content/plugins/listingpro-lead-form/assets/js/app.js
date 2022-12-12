jQuery(document).ready(function (e) {
    'use strict';

    jQuery('#attributesModal').on('hidden.bs.modal', function (e) {
        jQuery('#attributesModal .modal-body').html('');
        jQuery('.active-editing').removeClass('active-editing');
    });



    var template_type   =   jQuery('#lp-template-type').val();
    if( template_type == 'form_builder' )
    {
        initiate_form_builder_customizer();
    }

    jQuery('#lp-el-remove-action').on('hidden.bs.modal', function (e) {
        jQuery('.remove-true').removeClass('remove-true');
        jQuery('.active-remove').removeClass('active-remove');
    });
});

jQuery(document).on('click', '.lp-el-remove', function (e) {
    e.preventDefault();
    var $this   =   jQuery(this);
    $this.addClass('active-remove');
    $this.closest('li').addClass('remove-true');
    jQuery('#lp-el-remove-action').modal('show');
});

jQuery(document).on('click', '.lp-save-template', function (e)
{
    e.preventDefault();
    jQuery('.lp-save-template').find('.fa-save').removeClass('fa-save').addClass('fa-spinner fa-spin');
    lp_composer_get_data_form_buider('.template-customizer-wrap-inner.active-style');
    var template_code   =   jQuery('.lp-composer-result-lead_form').val();

    jQuery("#lp-notice-customizer-warning").slideUp('900');
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
            'action': 'save_cusomizer_template',
            'template_code': template_code,
        },
        success: function(data)
        {
            console.log(data);
            jQuery('.lp-save-template').find('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-save');
            jQuery("#lp-notice-customizer").slideDown('900').delay(3000).slideUp('900');
        }
    });
});

function initiate_form_builder_customizer()
{
    var byId = function (id) { return document.getElementById(id); };

    [].forEach.call(
        byId('lp-lead-form-outer-lead_form').getElementsByClassName('fields-sroter'), function (el) {
            Sortable.create(el, {
                group: 'fields-sroter',
                animation: 150,
                onEnd: function (evt) {
                    jQuery("#lp-notice-customizer-warning").slideDown('900');
                    var template_type   =   jQuery('#lp-template-type').val();
                    if( template_type == 'archive' )
                    {
                        lp_composer_get_data('.active-style .section-outer');
                    }
                    if( template_type == 'detail_page' )
                    {
                        lp_composer_get_data_detail_page('.active-style .section-outer');
                    }
                    if( template_type == 'form_builder' )
                    {
                        lp_composer_get_data_form_buider('.template-customizer-wrap-inner.active-style');
                    }
                }
            })
        }
    );
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

                    var template_type   =   jQuery('#lp-template-type').val(),
                        active_template =   jQuery('#lp-active-template').val();
                    if( template_type == 'archive' )
                    {
                        lp_composer_get_data('.active-style .section-outer');
                    }
                    if( template_type == 'detail_page' )
                    {
                        lp_composer_get_data_detail_page('.active-style .section-outer');
                    }
                    if( template_type == 'form_builder' )
                    {
                        field_check( '.wrap-'+active_template+' .fields-list', evt );
                        lp_composer_get_data_form_buider('.template-customizer-wrap-inner.active-style');
                    }
                }
            })
        }
    );
}
function lp_composer_get_data_form_buider( selector )
{
    selector    =   jQuery(selector).find('.fields-list').find('li');

    var fieldShortcodes =   '',
        leadFormS       =   '[lead-form]shortcode content[/lead-form]',
        active_template =   jQuery('#lp-active-template').val(),
        targetInput     =   '.lp-composer-result-'+active_template;

    selector.each(function (index) {
        var $this   =   jQuery(this),
            sName   =   $this.attr('data-name'),
            sPlaceh =   $this.attr('data-placeholder'),
            sClass  =   $this.attr('data-class'),
            sLabel  =   $this.attr('data-label'),
            sRequi  =   $this.attr('data-required'),
            sType   =   $this.attr('data-type'),
            sOpts   =   $this.attr('data-options'),
            $thisS  =   $this.data('shortcode');

        fieldShortcodes +=  $thisS.replace(']', ' options="'+sOpts+'" type="'+sType+'" label="'+sLabel+'" required="'+sRequi+'" name="'+sName+'" placeholder="'+sPlaceh+'" class="'+sClass+'"]');
    });
    leadFormS   =   leadFormS.replace( 'shortcode content', fieldShortcodes );

    jQuery('.lp-composer-result-lead_form').val(leadFormS);
}

function display_drag_section() {
    jQuery('.section-inner-row').each(function (index) {
        var innerLength =   jQuery(this).find('.lp-section-inner').length;
        if( innerLength > 1 )
        {
            jQuery(this).find('.drag-section-row').css('display', 'block');
        }
    });
}
function get_empty_section_visible( selectorFrom, selectorTo )
{

    jQuery(selectorFrom).addClass('movedFrom');
    jQuery(selectorTo).addClass('movedTo');
    var lpSectionInnerFrom  =   jQuery(selectorFrom).find('.lp-section-inner'),
        lpSectionInnerTo    =   jQuery(selectorTo).find('.lp-section-inner');

    if( lpSectionInnerFrom.length == 0 )
    {
        jQuery(selectorFrom).addClass('lp-empty-sec');
    }

    if( lpSectionInnerTo.length > 0 )
    {
        jQuery(selectorTo).removeClass('lp-empty-sec');
    }
}
function delete_empty_section() {
    jQuery('.section-inner-row').each(function (index) {
        var innerLength =   jQuery(this).find('.lp-section-inner').length;
        if( innerLength == 0 )
        {
            jQuery(this).remove();
        }
    });
}
function class_empty_section(selector) {
    console.log(selector);
    var innerLength =   jQuery(selector).find('.title-row-inner').length,
        innerLength =   innerLength-2;

    if( innerLength == 0 )
    {
        jQuery(selector).addClass('empty-sec');
    }
    else
    {
        jQuery(selector).removeClass('empty-sec');
    }

}

jQuery(document).on('click', '.lp-el-confirm-delete', function (e) {

    e.preventDefault();
    jQuery('.remove-true').remove();
    jQuery('.active-remove').removeClass('active-remove');

    jQuery('#lp-el-remove-action').modal('hide');
    jQuery("#lp-notice-customizer-warning").slideDown('900');
    var template_type   =   jQuery('#lp-template-type').val();
    if( template_type == 'form_builder' )
    {
        lp_composer_get_data_form_buider('.template-customizer-wrap-inner.active-style');
    }
    else
    {
        display_drag_section();
        delete_empty_section();
        if( template_type == 'archive' )
        {
            lp_composer_get_data('.active-style .section-outer');
        }
        if( template_type == 'detail_page' )
        {
            lp_composer_get_data_detail_page('.active-style .section-outer');
        }
    }

});

