jQuery(document).ready(function (e) {

    var template_type   =   jQuery('#lp-template-type').val(),
        template_style  =   jQuery('#lp-active-template').val(),
        tStyle          =   '.template-customizer-wrap-inner.wrap-'+template_style;

    adjust_sidebar_height(tStyle, template_style, template_type);

    jQuery('.lp-section-list').each(function (index) {
        var $this       =   jQuery(this),
            findInner   =   $this.find('.lp-section-inner');

        if( findInner.length == 0 )
        {
            $this.addClass('lp-empty-sec');
        }
    });
    jQuery('.lp-dragables-list li').hover(function(e){
        jQuery('.lp-dragables-list li.active-list ').removeClass('active-list');
        jQuery(this).addClass('active-list');

        var active_template =   jQuery('#lp-active-template').val();
        if( active_template == 'style1' )
        {
            var Idd2    =   'lp-archive-outer';
        }
        else
        {
            Idd2    =   'lp-archive-outer-'+active_template;
        }
        var activeDragables =   jQuery(this).find('.lp-dragables-list-child').attr('id');
        initialize_element_list(activeDragables);
    },
    function (e) {
        jQuery('.lp-dragables-list li.active-list ').removeClass('active-list');
    });

    display_drag_section();
    delete_empty_section();

    jQuery('.lp-reset-btns').click(function (e) {
        e.preventDefault();
        jQuery('#lp-reset-action').modal('show');
    });
    jQuery('.confirm-reset').click(function (e) {
        var active_template =   jQuery('#lp-active-template').val(),
            template_type   =   jQuery('#lp-template-type').val(),
            $this          =    jQuery(this);

        $this.find('i').removeClass('fa-refresh').addClass('fa-spinner fa-spin');
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'reset_customizer',
            },
            success: function(data)
            {
                $this.removeClass('fa-spinner fa-spin').addClass('fa-refresh');
                jQuery('#lp-reset-action').modal('hide');
                location.reload();
            }
        });
    });

    /** JS aFTER AQIB ENDS **/
    jQuery( '.tabs-row .lp-section-list .lp-section-inner' ).click(function (e) {
        e.preventDefault();
        var targetID    =   '.'+jQuery(this).attr('id');

        jQuery('.active-tab-detail2').removeClass('active-tab-detail2');
        jQuery(this).addClass('active-tab-detail2');
        jQuery('.tab-ct').hide();
        jQuery(targetID).show();
    });
    jQuery('.lp-template-selection ul li').click(function (e) {

        jQuery('.lp-template-selection ul li.active').removeClass('active');
        var $this               =   jQuery(this),
            style               =   $this.data('style'),
            tStyle              =   '.template-customizer-wrap-inner.wrap-'+style,
            styleL              =   $this.data('active-layout'),
            template_type       =   jQuery('#lp-template-type').val(),
            el_visible          =   'visible_'+style,
            reverseLayout       =   false;

        if( template_type == 'archive' && ( style == 'style1' || style == 'style3' ) )
        {
            if( jQuery('.wrap-'+ style +' .section-outer:first').hasClass('lp-customizer-sidebar') )
            {
                reverseLayout   =   true;
            }
        }

        if( template_type == 'detail_page' && ( style != 'style4' && style != 'style3' ) )
        {
            jQuery('.lp-position-sidebar').removeClass('lp-position-sidebar');
        }
        if( template_type == 'detail_page' && ( style == 'style4' || style == 'style3' ) )
        {
            jQuery(tStyle).find('.drag-section').remove();
            jQuery('.lp-customizer-sidebar').addClass('lp-position-sidebar');

        }
        if( template_type == 'archive' && ( style == 'style4' || style == 'style2' ) )
        {
            jQuery(tStyle).find('.lp-columns-select').remove();
        }

        jQuery('.lp-dragables-list-parent').removeClass(function (index, css) {
            return (css.match (/\bvisible_\S+/g) || []).join(' ');
        });
        jQuery('.lp-dragables-list-parent').addClass(el_visible);
        //get active layout for specific style and ACTIVE class
        add_active_layout_class( tStyle, styleL, style );

        jQuery('.template-customizer-wrap-inner.active-style').removeClass('active-style');
        jQuery(tStyle).addClass('active-style');

        jQuery('#lp-active-template').val(style);
        jQuery('#lp-layout-class').val(styleL);

        var active_template =   jQuery('#lp-active-template').val();

        if( active_template == 'style1' )
        {
            var Idd1            =   'lp-dragables-els1',
                archiveID       =   'lp-archive-outer',
                detailID        =   'lp-detail-outer';
        }
        else
        {
            var tempnum =   active_template.replace('style','');
            Idd1            =   'lp-dragables-els'+tempnum;
            archiveID       =   'lp-archive-outer-'+active_template,
            detailID        =   'lp-detail-outer-'+active_template;
        }

        if( style == 'style5' && template_type == 'detail_page' )
        {
            jQuery('#'+detailID).find('.lp-columns-select').remove();
        }
        if( style == 'style2' && template_type == 'archive' )
        {
            jQuery('#'+archiveID).find('.lp-columns-select').remove();
        }

        if( style == 'style2' )
        {
            if( jQuery(tStyle).find('.lp-customizer-sidebar').length == 0 )
            {
                jQuery(tStyle).find('.section-outer').removeClass(function (index, css) {
                    return (css.match (/\bwidth-\S+/g) || []).join(' ');
                });
                jQuery(tStyle).find('.section-outer:first').addClass('width-12');
            }
        }
        if( style == 'style3' )
        {
            var styleLArr   =   styleL.split(',');

            jQuery(tStyle).find('.section-outer').removeClass(function (index, css) {
                return (css.match (/\bwidth-\S+/g) || []).join(' ');
            });

            if( reverseLayout == true && template_type == 'archive' )
            {
                jQuery(tStyle).find('.section-outer:first').addClass('width-'+styleLArr[1]);
                jQuery(tStyle).find('.section-outer:eq(1)').addClass('width-'+styleLArr[0]);
            }
            else
            {
                jQuery(tStyle).find('.section-outer:first').addClass('width-'+styleLArr[0]);
                jQuery(tStyle).find('.section-outer:eq(1)').addClass('width-'+styleLArr[1]);
            }


        }
        if( style == 'style4' && template_type == 'archive' )
        {
            var styleLArr   =   styleL.split(',');


            jQuery(tStyle).find('.section-outer').removeClass(function (index, css) {
                return (css.match (/\bwidth-\S+/g) || []).join(' ');
            });

            jQuery(tStyle).find('.archive-sidebar1').addClass('width-'+styleLArr[0]);
            jQuery(tStyle).find('.archive-content').addClass('width-'+styleLArr[1]);
            jQuery(tStyle).find('.lp-customizer-sidebar').addClass('width-'+styleLArr[2]);

            if( template_type == 'archive' )
            {
                jQuery(tStyle).find('.archive-content, .lp-customizer-sidebar').find('.section-layout-wrap').remove();
            }
        }
        if( style == 'style1' )
        {
            var styleLArr   =   styleL.split(',');

            jQuery(tStyle).find('.section-outer').removeClass(function (index, css) {
                return (css.match (/\bwidth-\S+/g) || []).join(' ');
            });

            if( reverseLayout == true && template_type == 'archive' )
            {
                jQuery(tStyle).find('.section-outer:first').addClass('width-'+styleLArr[1]);
                jQuery(tStyle).find('.section-outer:eq(1)').addClass('width-'+styleLArr[0]);
            }
            else
            {
                jQuery(tStyle).find('.section-outer:first').addClass('width-'+styleLArr[0]);
                jQuery(tStyle).find('.section-outer:eq(1)').addClass('width-'+styleLArr[1]);
            }


        }
        if( template_type == 'archive' )
        {
            initiate_customizer_main(archiveID);
        }

        if( template_type == 'detail_page' )
        {
            initiate_detail_page_customzier(detailID);
        }
        $this.addClass('active');

        adjust_sidebar_height( tStyle, style, template_type );

    });
    jQuery('.switch-customizer').click(function (e) {
        e.preventDefault();

        var $this           =   jQuery(this),
            enable_data     =   '',
            enable_type     =   jQuery('#lp-template-type').val();
        if( jQuery(this).hasClass('active') )
        {
            enable_data  =   0;
            jQuery('.lp-customizer-outer-wrap').removeClass('active');
            jQuery('.lp-composer-notice').removeClass('active');
            jQuery('.lp-customized-secction-outer').removeClass('active');
            jQuery('.lp-activate-compser').removeClass('active');
            jQuery(this).removeClass('active');
        }
        else
        {
            enable_data  =   1;
            jQuery('.lp-customizer-outer-wrap').addClass('active');
            jQuery(this).addClass('active');
            jQuery('.lp-composer-notice').addClass('active');
            jQuery('.lp-customized-secction-outer').addClass('active');
            jQuery('.lp-activate-compser').addClass('active');
        }


        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'enable_cusomizer',
                'enable_type': enable_type,
                'enable_data' : enable_data,
            },
            success: function(data)
            {
                if( enable_data == 1 )
               {
                   jQuery('.lp-save-template').trigger('click');
               }
            }
        });
    });

    jQuery('.switch-user-customizer').click(function (e) {
        e.preventDefault();

        var $this           =   jQuery(this),
            enable_data     =   '';
        if( jQuery(this).hasClass('active') )
        {
            enable_data  =   0;
            jQuery(this).removeClass('active');
        }
        else
        {
            enable_data  =   1;
            jQuery(this).addClass('active');
        }

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'enable_user_cusomizer',
                'enable_data' : enable_data,
            },
            success: function(data)
            {
                console.log(data);
            }
        });
    });

    jQuery( '.section-layout-wrap' ).click(function (e) {
        var $this            =   jQuery(this),
            layoutDivi       =   $this.data('layout'),
            layoutStyle      =   $this.attr('data-layout-style'),
            template_type    =  jQuery('#lp-template-type').val();

        var wrapID   =   '#lp-archive-outer';
        if( template_type == 'detail_page' )
        {
            wrapID  =   '#lp-detail-outer'
        }
        if( layoutStyle != 'style1' )
        {
            wrapID   =   '#lp-archive-outer-'+layoutStyle;
            if( template_type == 'detail_page' )
            {
                wrapID  =   '#lp-detail-outer-'+layoutStyle;
            }
        }
        jQuery(wrapID).find('.section-layout-wrap').removeClass('active');
        $this.addClass('active');

        if( layoutDivi != 12 )
        {
            var layoutDiviArr    =   layoutDivi.split(',');
        }
        else
        {
            var layoutDiviArr    =   layoutDivi;
        }

        jQuery('#lp-layout-class').val(layoutDivi);

        if( template_type == 'detail_page' && layoutStyle != 'style5' )
        {
            jQuery(wrapID).find('.section-outer[data-columnize="yes"]').removeClass(function (index, css) {
                return (css.match (/\bwidth-\S+/g) || []).join(' ');
            });
            if( layoutDiviArr == 12 )
            {
                jQuery(wrapID).find('.lp-customizer-sidebar').addClass('disabled-sidebar');
            }
            else
            {
                jQuery(wrapID).find('.lp-customizer-sidebar').removeClass('disabled-sidebar');
            }
            jQuery( wrapID ).find('.lp-customizer-sidebar').addClass( 'width-'+layoutDiviArr[1] );
            jQuery( wrapID ).find('.detail-content').addClass( 'width-'+layoutDiviArr[0] );


            // jQuery(wrapID).find('.section-outer[data-columnize="yes"]:first, .section-outer[data-columnize="yes"]:eq(2)').addClass('width-'+layoutDiviArr[0]);
            // jQuery(wrapID).find('.section-outer[data-columnize="yes"]:eq(1)').addClass('width-'+layoutDiviArr[1]);

        }

        if( template_type == 'archive' )
        {
            jQuery(wrapID).find('.section-outer[data-columnize="yes"]').removeClass(function (index, css) {
                return (css.match (/\bwidth-\S+/g) || []).join(' ');
            });

            if( layoutStyle != 'style4' )
            {
                if( layoutDiviArr == 12 )
                {
                    jQuery(wrapID).find('.lp-customizer-sidebar').addClass('disabled-sidebar');
                }
                else
                {
                    jQuery(wrapID).find('.lp-customizer-sidebar').removeClass('disabled-sidebar');
                }
                jQuery( wrapID ).find('.lp-customizer-sidebar').addClass( 'width-'+layoutDiviArr[1] );
                jQuery( wrapID ).find('.archive-content').addClass( 'width-'+layoutDiviArr[0] );

                // jQuery(wrapID).find('.section-outer[data-columnize="yes"]:first').addClass('width-'+layoutDiviArr[0]);
                // jQuery(wrapID).find('.section-outer[data-columnize="yes"]:eq(1)').addClass('width-'+layoutDiviArr[1]);
            }
            if( layoutStyle == 'style4' )
            {
                jQuery(wrapID).find('.section-outer:first').addClass('width-'+layoutDiviArr[0]);
                jQuery(wrapID).find('.section-outer:eq(1)').addClass('width-'+layoutDiviArr[1]);
                jQuery(wrapID).find('.section-outer:eq(2)').addClass('width-'+layoutDiviArr[2]);
            }

        }

    });
    jQuery('.lp-customizer-header, .lp-customizer-footer').click(function (e) {
        alert('please get off');
    });

});


function add_active_layout_class( tStyle, styleL, style )
{
    jQuery(tStyle).find('.section-layout-wrap').removeClass('active');
    jQuery(tStyle).find('.section-layout-wrap').each(function (index) {
        jQuery(this).attr('data-layout-style', style);
        var thisLayout  =   jQuery(this).data('layout');
        if( thisLayout == styleL )
        {
            jQuery(this).addClass('active');
        }
    });
}

jQuery(document).ready(function(){
    jQuery('.select-menu-bars').on('click', function(){
        jQuery('.lp-dragables').slideToggle(300);
    });
});

function setScrollPos( clientX, clientY )
{
    var scrollTopMousedown  =   jQuery(document).scrollTop();
    if( clientY < 200 )
    {
        jQuery(document).scrollTop(jQuery(document).scrollTop()-clientY);
    }
}

function adjust_sidebar_height( tStyle, style, template_type )
{

    if( template_type == 'detail_page' && ( style == 'style4' || style == 'style3' ) )
    {
        var sidebarHeight   =   jQuery(tStyle).find('.lp-customizer-sidebar').height(),
            contentHeight   =   jQuery(tStyle).find('.detail-content').height(),
            targetElHeight  =   jQuery(tStyle).find('.position-style3').height();


        if( sidebarHeight > targetElHeight )
        {
            jQuery(tStyle).find('.position-style3').css('height', (sidebarHeight-contentHeight)+'px');
        }

    }
}



