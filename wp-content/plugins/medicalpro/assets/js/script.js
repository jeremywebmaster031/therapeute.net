jQuery(document).ready(function () {
    setTimeout(function() {
        if( jQuery('body').hasClass('mp-home') ){
            jQuery( '.md-header-search-wrap' ).css('top', '0px');
        }
        jQuery(".lp-header-search .lp-header-search-form form, .lp-header-search .lp-header-search-cats ul").show();

    }, 800);
    jQuery(document).on('click', '.mp-expand-all-filters-trigger', function(){
        var $this = jQuery(this);
        if ($this.hasClass('fa-angle-down')){
            $this.removeClass('fa-angle-down');
            $this.addClass('fa-angle-up');
        }else if ($this.hasClass('fa-angle-up')){
            $this.removeClass('fa-angle-up');
            $this.addClass('fa-angle-down');
        }
        $this.closest('.lp-more-filters-outer').find('.lp_filter_checkbox').toggle();
    });
    
    jQuery('[data-toggle="mp-tooltip"]').tooltip();

    if (jQuery('.mp-profile-content-detail').length > 0) {
        var showChar = 252;
        var ellipsestext = '';
        var moretext = jQuery('.mp-profile-content-detail').data('readless');
        var lesstext = jQuery('.mp-profile-content-detail').data('readmore');
        jQuery('.mp-profile-content-detail').each(function () {
            var content = jQuery(this).html();
            if (content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);
                var html = c + '<span class="mp-moreellipses">' + ellipsestext + ' </span><span class="mp-morecontent"><span>' + h + '</span> <a href="" class="mp-morelink">' + moretext + '</a></span>';
                jQuery(this).html(html);
            }
        });
        jQuery(".mp-morelink").click(function () {
            if (jQuery(this).hasClass("mp-less")) {
                jQuery(this).removeClass("mp-less");
                jQuery(this).html(moretext);
            } else {
                jQuery(this).addClass("mp-less");
                jQuery(this).html(lesstext);
            }
            jQuery(this).parent().prev().toggle();
            jQuery(this).prev().toggle();
            return false;
        });
        if (jQuery('.mp-morelink').length > 0) {
            jQuery(".mp-morelink").trigger('click');
        }
    }
    jQuery(document).on('click', function () {
        jQuery('.mp-timing-day-view-all-timings.active').trigger('click');
    });
    jQuery(document).on('click', '.mp-timing-day-view-all-timings', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        jQuery('.mp-timings-other-days-container').slideUp();
        var $this = jQuery(this);
        if ($this.hasClass('active')){
            $this.closest('.mp-timings').find('.mp-timings-other-days-container').slideUp();
            $this.removeClass('active');
            $this.find('i').removeClass('fa-caret-up');
            $this.find('i').addClass('fa-caret-down');
        } else {
            $this.closest('.mp-timings').find('.mp-timings-other-days-container').slideDown();
            $this.addClass('active');
            $this.find('i').addClass('fa-caret-up');
            $this.find('i').removeClass('fa-caret-down');
        }
    });
    jQuery(document).on('click', '.mp-view-all-profile-locations', function () {
        var $this = jQuery(this);
        if ($this.hasClass('active')){
            jQuery('.mp-profile-location.view-more').slideUp(function(){ 1000 });
            $this.removeClass('active');
            $this.find('i').removeClass('fa-angle-up');
            $this.find('i').addClass('fa-angle-down');
            $this.find('span').text($this.data('viewmore'));
        } else {
            jQuery('.mp-profile-location.view-more').slideDown(function(){ 1000 });
            $this.addClass('active');
            $this.find('i').addClass('fa-angle-up');
            $this.find('i').removeClass('fa-angle-down');
            $this.find('span').text($this.data('viewless'));
        }
    });

    jQuery('.mp-add-new-review-form-rating-single-stars label').hover(function () {
        var $this = jQuery(this);
        mp_review_stars_colors($this);
    });
    jQuery(document).on('click', '.mp-add-new-review-form-rating-single-stars label', function () {
        var $this = jQuery(this);
        mp_review_stars_colors($this);
    });
    
    jQuery('.mp-experiences-content-feedback-stars svg').hover(function (x){
        var $this = jQuery(this),
            num = jQuery(this).index() + 1;
            
        if (num == 1) {
            $this.closest('.mp-experiences-content-feedback-stars').find('svg').css({
                'fill': '#fff'
            });
            $this.closest('.mp-experiences-content-feedback-stars').find('svg:nth-child(1)').css({
                'fill': '#de9147'
            });
        } else if (num == 2) {
            $this.closest('.mp-experiences-content-feedback-stars').find('svg').css({
                'fill': '#fff'
            });
            $this.closest('.mp-experiences-content-feedback-stars').find('svg:nth-child(1),svg:nth-child(2)').css({
                'fill': '#FFC107'
            });
        } else if (num == 3) {
            $this.closest('.mp-experiences-content-feedback-stars').find('svg').css({
                'fill': '#fff'
            });
            $this.closest('.mp-experiences-content-feedback-stars').find('svg:nth-child(1),svg:nth-child(2),svg:nth-child(3)').css({
                'fill': 'rgb(199 223 55)'
            });
        } else if (num == 4) {
            $this.closest('.mp-experiences-content-feedback-stars').find('svg').css({
                'fill': '#fff'
            });
            $this.closest('.mp-experiences-content-feedback-stars').find('svg:nth-child(1),svg:nth-child(2),svg:nth-child(3),svg:nth-child(4)').css({
                'fill': 'rgb(210 244 13)'
            });
        } else if (num == 5) {
            $this.closest('.mp-experiences-content-feedback-stars').find('svg').css({
                'fill': '#fff'
            });
            $this.closest('.mp-experiences-content-feedback-stars').find('svg:nth-child(1),svg:nth-child(2),svg:nth-child(3),svg:nth-child(4),svg:nth-child(5)').css({
                'fill': 'rgb(115, 207, 66)'
            });
        }
    });
    
    jQuery(document).on('click', '.mp-experiences-content-feedback-stars svg', function (x){
        var $this = jQuery(this);
        var val = $this.index() + 1;
        jQuery('.mp-add-new-review').slideDown();
        jQuery('html, body').animate({
            scrollTop: jQuery("#mp-add-new-review").offset().top
        }, 1000);
        jQuery(document).delay(1000).queue(function (){
            // Fixed Sidebar 
            if (jQuery('#mp-profile-sidebar-fixed').length > 0) {
                if (jQuery('#mp-profile-sidebar-fixed').hasClass('listing-detail-page-fixed-sidebar-true')) {
                    var $sidebarminheight = jQuery('.mp-detail-content').outerHeight() + jQuery('.mp-detail-header').outerHeight() - 110;
                    jQuery('.mp-profile-sidebar').css('min-height', $sidebarminheight - 80);
                    var top = 0,
                        header = jQuery('.mp-fixed-header-inner'),
                        headerHeight = 0;
                    if (header.length > 0) {
                        headerHeight = header.outerHeight();
                        top = headerHeight;
                    }
                    if (jQuery('#wpadminbar').length > 0){
                        top = 32 + headerHeight;
                    }
                    jQuery(".mp-profile-sidebar-fixed").stick_in_parent({
                        offset_top: top
                    });
                    jQuery(document.body).trigger("sticky_kit:recalc");
                }
            }
        });
    });
    
    function mp_review_stars_colors($this) {
        $val = null;
        if (jQuery('input[name=mp-review-form-rating]').is(':checked')) {
            $val = jQuery('input[name=mp-review-form-rating]:checked').val();
        }

        if ($this.hasClass('mp-rating-star-1') || $val == 1) {
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label i').css({
                'color': '#42505D'
            });
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label.mp-rating-star-1 i').css({
                'color': '#de9147'
            });
        } else if ($this.hasClass('mp-rating-star-2') || $val == 2) {
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label i').css({
                'color': '#42505D'
            });
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label.mp-rating-star-1 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-2 i').css({
                'color': '#FFC107'
            });
        } else if ($this.hasClass('mp-rating-star-3') || $val == 3) {
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label i').css({
                'color': '#42505D'
            });
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label.mp-rating-star-1 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-2 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-3 i').css({
                'color': 'rgb(199 223 55)'
            });
        } else if ($this.hasClass('mp-rating-star-4') || $val == 4) {
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label i').css({
                'color': '#42505D'
            });
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label.mp-rating-star-1 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-2 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-3 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-4 i').css({
                'color': 'rgb(210 244 13)'
            });
        } else if ($this.hasClass('mp-rating-star-5') || $val == 5) {
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label i').css({
                'color': '#42505D'
            });
            $this.closest('.mp-add-new-review-form-rating-single').find('.mp-add-new-review-form-rating-single-stars label.mp-rating-star-1 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-2 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-3 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-4 i, .mp-add-new-review-form-rating-single-stars label.mp-rating-star-5 i').css({
                'color': 'rgb(115, 207, 66)'
            });
        }

    }
    
    jQuery(document).on('click', '.mp-review-images-upload', function (x) {
        x.preventDefault();
        x.stopPropagation();
        x.stopImmediatePropagation();
        jQuery('input[name=mp-review-images-upload-hidden]').trigger('click');
    });
    
    // Fixed Sidebar 
    if (jQuery('#mp-profile-sidebar-fixed').length > 0) {
        if (jQuery('#mp-profile-sidebar-fixed').hasClass('listing-detail-page-fixed-sidebar-true') || jQuery('#mp-profile-sidebar-fixed').hasClass('archive-page-fixed-sidebar-true')) {
            var $sidebarminheight = jQuery('.mp-detail-content').outerHeight() + jQuery('.mp-detail-header').outerHeight() - 110;
            jQuery('.mp-profile-sidebar').css({
                'min-height': $sidebarminheight,
                'position': 'relative'
            });
            var top = 0,
                header = jQuery('.mp-fixed-header-inner'),
                headerHeight = 0;
            if (header.length > 0) {
                headerHeight = header.outerHeight();
                top = headerHeight;
            }
            if (jQuery('#wpadminbar').length > 0){
                top = 32 + headerHeight;
            }
            jQuery(".mp-profile-sidebar-fixed").stick_in_parent({
                container: jQuery(".mp-profile-sidebar"),
                offset_top: top,
                recalc_every: 1
            });
        }
    }
    
    if (jQuery('.mp-profile-content-tabs-container').length > 0 && !jQuery('.mp-profile-content-tabs-container').hasClass('mp-tabs-in-mobile')){
        jQuery(window).scroll(function(e){ 
            var $el = jQuery('.mp-profile-content-tabs-container');
            var width = $el.width();
            var top = 0,
                header = jQuery('.mp-fixed-header-inner'),
                headerHeight = 0;
            if (header.length > 0) {
                headerHeight = header.outerHeight();
                top = headerHeight;
            }
            if (jQuery('#wpadminbar').length > 0){
                top = 32 + headerHeight;
            }
            var isPositionFixed = ($el.css('position') == 'fixed'),
                footer = jQuery('footer').offset().top - 200;

            if (jQuery(this).scrollTop() > 600 && !isPositionFixed){ 
                $el.css({'position': 'fixed', 'top': '0', 'transform': 'translate(0, '+top+'px)', 'min-width': width}); 
            }
            if ((jQuery(this).scrollTop() < 600 && isPositionFixed) || (jQuery(this).scrollTop() > footer)){
                
                $el.css({'position': 'static', 'top': '0', 'transform': 'translate(0, 0)', 'min-width': 'auto'}); 
            }
        });
    }
        
    // Smooth Scroll
    jQuery("a").on('click', function(event) {
        if (jQuery(this).hasClass('mp-event-scroll')) {
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;
                jQuery('html, body').animate({
                    scrollTop: jQuery(hash).offset().top - 150
                }, 1200);
            }
        }
    });
	if(jQuery('.listing-post').hasClass('listing-md-slider4')){
	  jQuery('.listing-md-slider4').slick({
          centerMode: false,
          centerPadding: '0px',
          infinite: true,
          accesibility: false,
          draggable: true,
          swipe: true,
          touchMove: false,
          autoplaySpeed: 1400,
          speed: 600,
          slidesToShow: 3,
          dots: false,
          arrows: true,
          responsive: [
              {
                  breakpoint: 991,
                  settings: {
                      arrows: false,
                      centerMode: false,
                      centerPadding: '0px',
                      slidesToShow: 1
                  }
              },
              {
                  breakpoint: 480,
                  settings: {
                      arrows: false,
                      centerMode: false,
                      centerPadding: '0px',
                      slidesToShow: 1
                  }
              }
          ]
      });
	}

	jQuery('a.show-all-feture').on('click', function(event) {
		event.preventDefault();
		jQuery(this).toggleClass('opened');
		jQuery(this).next('.hidding-fetures').slideToggle(400);
	});
	jQuery('a.show-all-insurance').on('click', function(event) {
        event.preventDefault();
        jQuery(this).closest('.mp-insurances-content-card').hide();
        jQuery('.show-more-insurance').slideToggle(400);
    });
	jQuery('.lp-new-location-outer .show-all-feature').on('click', function(event) {
        event.preventDefault();
        if(jQuery(this).hasClass('opened')){
            jQuery(this).toggleClass('opened');
            jQuery(this).closest('.lp-new-location-outer').find('.show-more').slideToggle(400);
            jQuery(this).html(jQuery(this).data('show_more'));
        }else{
            jQuery(this).toggleClass('opened');
            jQuery(this).closest('.lp-new-location-outer').find('.show-more').slideToggle(400);
            jQuery(this).html(jQuery(this).data('less_more'));
        }
    });
});

jQuery(document).on('click', '.md-listing-sorter .view-all-cats', function (e) {
    jQuery('.md-listing-sorter .search-by-cat').show();
    jQuery(this).hide();
    
    if (jQuery('#mp-profile-sidebar-fixed').length > 0) {
        if (jQuery('#mp-profile-sidebar-fixed').hasClass('listing-detail-page-fixed-sidebar-true') || jQuery('#mp-profile-sidebar-fixed').hasClass('archive-page-fixed-sidebar-true')) {
            var $sidebarminheight = jQuery('.mp-detail-content').outerHeight() + jQuery('.mp-detail-header').outerHeight() - 110;
            jQuery('.mp-profile-sidebar').css({
                'min-height': $sidebarminheight,
                'position': 'relative'
            });
            var top = 0,
                header = jQuery('.mp-fixed-header-inner'),
                headerHeight = 0;
            if (header.length > 0) {
                headerHeight = header.outerHeight();
                top = headerHeight;
            }
            if (jQuery('#wpadminbar').length > 0){
                top = 32 + headerHeight;
            }
            jQuery(".mp-profile-sidebar-fixed").stick_in_parent({
                container: jQuery(".mp-profile-sidebar"),
                offset_top: top,
                recalc_every: 1
            });
        }
    }
});

jQuery(document).on('click', '.md-listing-alpha .search-by-letter', function (e) {
    if(jQuery(this).hasClass('active')){
        jQuery('.md-listing-alpha .search-by-letter').removeClass('active');
    }else{
        jQuery('.md-listing-alpha .search-by-letter').removeClass('active');
        jQuery(this).addClass('active');
    }
    medicalpro_hospital_doctors_content();
});

jQuery(document).on('click', '.md-listing-sorter .search-by-cat', function (e) {
    jQuery('.md-listing-sorter .search-by-cat').removeClass('active');
    jQuery(this).addClass('active');
    medicalpro_hospital_doctors_content();
});

function medicalpro_hospital_doctors_content(){
    
    var letter = '';
    if( jQuery('.md-listing-alpha .search-by-letter.active').length > 0 ){
        letter = jQuery('.md-listing-alpha .search-by-letter.active').data('letter');
    }
    var cat_id      = jQuery('.md-listing-sorter .search-by-cat.active').data('id');
    var hospital_id = jQuery('.md-hospital-listing-filters').data('id');
    
    jQuery.ajax({
        type: 'POST',
        dataType: 'html',
        url: ajax_search_term_object.ajaxurl,
        data: 'action=medicalpro_hospital_doctors_content&hospital_id='+ hospital_id +'&letter='+ letter +'&cat_id='+ cat_id,
        success: function (data) {
            jQuery('.mp-hospital-doctors-holder').html(data);
            if (jQuery('#mp-profile-sidebar-fixed').length > 0) {
                if (jQuery('#mp-profile-sidebar-fixed').hasClass('listing-detail-page-fixed-sidebar-true') || jQuery('#mp-profile-sidebar-fixed').hasClass('archive-page-fixed-sidebar-true')) {
                    var $sidebarminheight = jQuery('.mp-detail-content').outerHeight() + jQuery('.mp-detail-header').outerHeight() - 110;
                    jQuery('.mp-profile-sidebar').css({
                        'min-height': $sidebarminheight,
                        'position': 'relative'
                    });
                    var top = 0,
                        header = jQuery('.mp-fixed-header-inner'),
                        headerHeight = 0;
                    if (header.length > 0) {
                        headerHeight = header.outerHeight();
                        top = headerHeight;
                    }
                    if (jQuery('#wpadminbar').length > 0){
                        top = 32 + headerHeight;
                    }
                    jQuery(".mp-profile-sidebar-fixed").stick_in_parent({
                        container: jQuery(".mp-profile-sidebar"),
                        offset_top: top,
                        recalc_every: 1
                    });
                }
            }
        }
    });
    return false;
}

jQuery(document).ready(function(){
    if(jQuery('.mp-hospital-gallery').length > 0 || jQuery('.mp-profile-image').length > 0){
        jQuery("a[rel^='prettyPhoto']").prettyPhoto({
            animation_speed:'fast',
            theme:'dark_rounded',
            slideshow:7000,
            autoplay_slideshow: true,
            social_tools: '',
            deeplinking: false,
            show_title: false,
        });
    }
    if (jQuery('#map-section-expand').length > 0) {
        jQuery(document).on('click', '#map-section-expand', function (x) {
            x.preventDefault();
            var $this = jQuery(this).find('i.fa'),
                map = jQuery('.sidebar-filters .md-sidemap-container');
            if ($this.hasClass('fa-expand')) {
                map.css('width', '100vw');
                $this.removeClass('fa-expand').addClass('fa-compress');
                setTimeout(function () {
                    jQuery('.all-list-map').trigger('click');
                }, 350);
            }else if ($this.hasClass('fa-compress')) {
                map.removeAttr('style');
                $this.addClass('fa-expand').removeClass('fa-compress');
                setTimeout(function () {
                    jQuery('.all-list-map').trigger('click');
                }, 350);
            }
        });
    }
});