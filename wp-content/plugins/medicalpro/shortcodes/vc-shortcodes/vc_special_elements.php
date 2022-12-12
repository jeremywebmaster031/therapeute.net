<?php
/*------------------------------------------------------*/
/* Partners Logos
/*------------------------------------------------------*/
vc_map( array(
    "name" => __("MD Partners", "js_composer"),
    "base" => "medicalpro_partners",
    "category" => __('Medicalpro', 'js_composer'), //by Abbas
    "as_parent" => array('only' => 'medicalpro_partner'),
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params" => array(
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __( "Element title", "js_composer" ),
            "param_name" => "partner_title",
            "value"       => "10000+ clients trust us to electrify their events",
            'save_always' => true,
            "description" => "Enter Element Title"
        ),
    ),
    "js_view" => 'VcColumnView'
) );
function medicalpro_shortcode_medicalpro_partners_container( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'partner_title'   => '',
    ), $atts));
    $output = null;

    $output .= ' <div class="travel-brands padding-bottom-100 padding-top-30">';
    $output .= '	<div class="row">';

    $output .= 				do_shortcode($content);

    $output .= '	</div>';
    $output .= '</div>';



    return $output;
}
add_shortcode( 'medicalpro_partners', 'medicalpro_shortcode_medicalpro_partners_container' );

vc_map( array(
    "name"                      => __("MD Single Partner Logo", "js_composer"),
    "base"                      => 'medicalpro_partner',
    "category"                  => __('Medicalpro', 'js_composer'),
    "description"               => '',
	"icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "content_element" => true,
    "as_child" => array('only' => 'medicalpro_partners'),
    "params"                    => array(
        array(
            "type"        => "attach_image",
            "class"       => "",
            "heading"     => __("Partner logo ","js_composer"),
            "param_name"  => "p_image1",
            "value"       => "",
            "description" => "Put here Partner logo."
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __("Logo Url","js_composer"),
            "param_name"  => "p_image1_url",
            "value"       => "#",
            "description" => ""
        ),
    ),
) );
function medicalpro_shortcode_medicalpro_partner($atts, $content = null) {
    extract(shortcode_atts(array(
        'p_image1'		=> '',
        'p_image1_url'		=> '',
    ), $atts));

    $output = null;

    $pimahe1 = '';
    if ( $p_image1 ) {
        if( is_array( $p_image1 ) )
        {
            $p_image1   =   $p_image1['id'];
        }
        $imgurl = wp_get_attachment_image_src( $p_image1, 'full');

        if($imgurl){
            $thumbnail = $imgurl[0];
        }else{
            $thumbnail = 'https://via.placeholder.com/570x228';
        }
    }
    $output .= '<div class="mp-w-20 partner-box text-center">
					<div class="partner-box-inner">
						<div class="partner-image">
							<a href="'.$p_image1_url.'"><img src="'.$thumbnail.'" /></a>
						</div>
					</div>
				</div>';
    return $output;
}
add_shortcode('medicalpro_partner', 'medicalpro_shortcode_medicalpro_partner');

/*------------------------------------------------------*/
/* Listings
/*------------------------------------------------------*/

vc_map( array(
    "name"                      => __("MD Listings", "js_composer"),
    "base"                      => 'medicalpro_listings',
    "category"                  => __('Medicalpro', 'js_composer'),
    "description"               => '',
	"icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
        array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => esc_html__("Posts per page","js_composer"),
            "param_name"  => "number_posts",
            'value' => array(
                esc_html__( '3 Posts', 'js_composer' ) => '3',
                esc_html__( '6 Posts', 'js_composer' ) => '6',
                esc_html__( '9 Posts', 'js_composer' ) => '9',
                esc_html__( '12 Posts', 'js_composer' ) => '12',
                esc_html__( '15 Posts', 'js_composer' ) => '15',
            ),
            'save_always' => true,
            "description" => "Select number of posts you want to show"
        ),
    ),
) );
function medicalpro_shortcode_medicalpro_listings($atts, $content = null) {
    extract(shortcode_atts(array(
        'number_posts'   => '3'
    ), $atts));

    $output = null;
    $type = 'listing';
    $args=array(
        'post_type' => $type,
        'post_status' => 'publish',
        'posts_per_page' => $number_posts,
    );

    $listingcurrency = '';
    $listingprice = '';
    $listing_query = null;
    $listing_query = new WP_Query($args);

    global $listingpro_options;
    $listing_mobile_view    =   $listingpro_options['single_listing_mobile_view'];
    $img_url    =     $listingpro_options['lp_def_featured_image']['url'];
    if( $listing_mobile_view == 'app_view2' && wp_is_mobile() )
    {
        ob_start();
            if( $listing_query->have_posts() )
            {
                $listing_entries_counter    =   1;
                while ( $listing_query->have_posts() ): $listing_query->the_post();
                if( $listing_entries_counter == 1 )
                {
                    echo '<div class="app-view2-first-recent">';
                    get_template_part('mobile/listing-loop-app-view-adds');
                    echo '</div>';
                }
                else
                {
                    get_template_part('mobile/listing-loop-app-view-new');
                }
                $listing_entries_counter++;
                endwhile;
            }
            else
            {
                echo 'no listings found';
            }
        $output .= ob_get_contents();
        ob_end_clean();
        ob_flush();
    }
    else
    {
        $post_count =1;
        $output.='
	<div class="listing-second-view paid-listing lp-section-content-container lp-list-page-grid">
		<div class="listing-post listing-md-slider4">
			';
        if( $listing_query->have_posts() ) {
            while ($listing_query->have_posts()) : $listing_query->the_post();
                $phone = listing_get_metabox('phone');
                $website = listing_get_metabox('website');
                $email = listing_get_metabox('email');
                $latitude = listing_get_metabox('latitude');
                $longitude = listing_get_metabox('longitude');
                $gAddress = listing_get_metabox('gAddress');
                $priceRange = listing_get_metabox('price_status');
                $listingpTo = listing_get_metabox('list_price_to');
                $listingprice = listing_get_metabox('list_price');
                $isfavouriteicon = listingpro_is_favourite_grids(get_the_ID(),$onlyicon=true);
                $isfavouritetext = listingpro_is_favourite_grids(get_the_ID(),$onlyicon=false);
                $claimed_section = listing_get_metabox('claimed_section');
                $rating = get_post_meta( get_the_ID(), 'listing_rate', true );
                 $rating_num_bg  =   '';
				$rating_num_clr  =   '';

				if( $rating < 2 ){ $rating_num_bg  =   'num-level1'; $rating_num_clr  =   'level1'; }
				if( $rating < 3 ){ $rating_num_bg  =   'num-level2'; $rating_num_clr  =   'level2'; }
				if( $rating < 4 ){ $rating_num_bg  =   'num-level3'; $rating_num_clr  =   'level3'; }
				if( $rating >= 4 ){ $rating_num_bg  =   'num-level4'; $rating_num_clr  =   'level4'; }
                $output .= '<div class="md-listing-outer"><div class="md-listing-inner">';
				 $output .= '		 <div class="md-listing-img">';
				 
				$certified_doctor    = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
				if($certified_doctor == 'Yes') {
					$claim = '<div class="mp-claimed-profile mp-tooltip mp-home-grid-claim-badge"><span class="mp-tooltiptext"> '.esc_html__('Certified Doctor', 'medicalpro').'</span>
					 <img src="'. MP_PLUGIN_DIR . 'assets/images/claimed/claimed1.svg'.'" alt="Claimed Profile">
					</div>';
				}else {
					$claim = '';

				}
				 
				 
                if ( has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'listingpro-blog-grid' );
                    if(!empty($image[0])){
                        $output.='
												<a href="'.get_the_permalink().'" >
													<img src="'. $image[0] .'" class="mp-doc-img" />
													'.$claim.'
												</a>';
                    }else{
                        $output.='
												<a href="'.get_the_permalink().'" >
													<img class="mp-doc-img" src="'.esc_html__('https://via.placeholder.com/372x240', 'medicalpro').'" alt="">
													'.$claim.'
												</a>';
                    }
                }else {
                    $output.='
										<a href="'.get_the_permalink().'" >
											<img class="mp-doc-img" src="'.$img_url.'" alt="">
											'.$claim.'
										</a>';
                }
                
				 $output .= '		 </div>';
				 $output .= '		 <div class="md-listing-content">';		
				  $output .= '
									<h4 class="margin-0">
										<a href="'.get_the_permalink().'">
											'.mb_substr(get_the_title(), 0, 18).'
										</a>
									</h4>
									<div class="listing-cats">';
                $cats = get_the_terms( get_the_ID(), 'listing-category' );
                if(!empty($cats)){
                    foreach ( $cats as $cat ) {
                        $term_link = get_term_link( $cat );
                        $output.='
														<a href="'.$term_link.'">
															'.$cat->name.'
														</a>';
							break; // For Only First Cat To Show
                    }
                }
                $output.='
											</div>
							<div class="text-left">
								<div class="md-listing-stars clearfix">
								
									<span class="lp-rating-num rating-with-colors '. review_rating_color_class($rating) .'">'. round((float)$rating, 2) .'</span>
									<div class="md-rating-stars-outer">
																	<span class="lp-star-box ';
													if( $rating > 0 ){ $output .= 'filled'.' '.$rating_num_clr; }
													$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
													if( $rating > 1 ){ $output .= 'filled'.' '.$rating_num_clr; }
													$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
													if( $rating > 2  ){ $output .= 'filled'.' '.$rating_num_clr; }
													$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
													if( $rating > 3 ){ $output .= 'filled'.' '.$rating_num_clr; }
													$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
													if( $rating > 4 ){ $output .= 'filled'.' '.$rating_num_clr; }
													$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									</div>
									
								</div>
							</div>';
							 if(!empty($gAddress)) {
							     $s = $gAddress;
							     $max_length = 25;
                                if (strlen($s) > $max_length)
                                {
                                    $offset = $max_length - strlen($s);
                                    $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
                                }
                    $output .= '
												
													<p class="md-listing-adres" title="'.$gAddress.'">'.$s.'</p>
												';
                }		
				 $output .= '</div>';	
					  $output .= '</div></div>';	
               
            endwhile;
        }
        $output .='
			
		</div>
	</div>';
    }



    return $output;
}
add_shortcode('medicalpro_listings', 'medicalpro_shortcode_medicalpro_listings');


/*------------------------------------------------------*/
/* new location and feature box 
/*------------------------------------------------------*/
$location_terms = get_terms('location', array('hide_empty' => false));
$locations = array();
if(isset($location_terms) && !empty($location_terms)){
    foreach($location_terms as $location_term) {
        $locations[$location_term->name] = $location_term->slug;
    }
}

$category_terms = get_terms('listing-category', array('hide_empty' => false));
$cats = array();
if(isset($category_terms) && !empty($category_terms)){
    foreach($category_terms as $category_term) {
        $cats[$category_term->name] = $category_term->slug;
    }
}

$feature_terms = get_terms('features', array('hide_empty' => false));
$features = array();
if(isset($feature_terms) && !empty($feature_terms)){
    foreach($feature_terms as $feature_term) {
        $features[$feature_term->name] = $feature_term->slug;
    }
}

$hospital_terms = get_terms('medicalpro-hospital', array('hide_empty' => false));
$hospitals = array();
if(isset($hospital_terms) && !empty($hospital_terms)){
    foreach($hospital_terms as $hospital_term) {
        $hospitals[$hospital_term->name] = $hospital_term->slug;
    }
}

vc_map( array(
    "name"                      => __("MedicalPro Taxonomy List", "js_composer"),
    "base"                      => 'loc_cat_box',
    "category"                  => __('Medicalpro', 'js_composer'),
    "description"               => '',
    "icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
        /// Locations
        array(
            "type"       => "textfield",
            "heading"    => esc_html__( 'Title for Location', 'js_composer' ),
            "param_name" => "location_title",
            "value"      => esc_html__( 'Location', 'js_composer' ),
           
        ),
	array(
            'type'        => 'checkbox',
            'heading'     => esc_html__( 'Select Location', 'js_composer' ),
            'param_name'  => 'location_ids',
            'description' => esc_html__( 'Check the checkbox', 'js_composer' ),
            'value'       => $locations
        ),
        /// Categories
        array(
            "type"       => "textfield",
            "heading"    => esc_html__( 'Title for Category', 'js_composer' ),
            "param_name" => "category_title",
            "value"      => esc_html__( 'Category', 'js_composer' ),
           
        ),
	array(
            'type'        => 'checkbox',
            'heading'     => esc_html__( 'Select Category', 'js_composer' ),
            'param_name'  => 'category_ids',
            'description' => esc_html__( 'Check the checkbox', 'js_composer' ),
            'value'       => $cats
        ),
        /// Features
        array(
            "type"       => "textfield",
            "heading"    => esc_html__( 'Title for Feature', 'js_composer' ),
            "param_name" => "feature_title",
            "value"      => esc_html__( 'Feature', 'js_composer' ),
           
        ),
	array(
            'type'        => 'checkbox',
            'heading'     => esc_html__( 'Select Feature', 'js_composer' ),
            'param_name'  => 'feature_ids',
            'description' => esc_html__( 'Check the checkbox', 'js_composer' ),
            'value'       => $features
        ),
        /// Features
        array(
            "type"       => "textfield",
            "heading"    => esc_html__( 'Title for Hospital', 'js_composer' ),
            "param_name" => "hospital_title",
            "value"      => esc_html__( 'Hospital', 'js_composer' ),
           
        ),
	array(
            'type'        => 'checkbox',
            'heading'     => esc_html__( 'Select Hospital', 'js_composer' ),
            'param_name'  => 'hospital_ids',
            'description' => esc_html__( 'Check the checkbox', 'js_composer' ),
            'value'       => $hospitals
        ),
		
    ),
) );
function medicalpro_shortcode_loc_cat_box($atts, $content = null) {
    
    extract(shortcode_atts(array(
        'location_title'    => esc_html__( 'Location', 'js_composer' ),
        'location_ids'      => '',
        'category_title'    => esc_html__( 'Category', 'js_composer' ),
        'category_ids'      => '',
        'feature_title'     => esc_html__( 'Feature', 'js_composer' ),
        'feature_ids'       => '',
        'hospital_title'    => esc_html__( 'Hospital', 'js_composer' ),
        'hospital_ids'      => '',
        'order_by'          => 'ASC',
        
    ), $atts));
    
    
    $cat_columns = array(
        'col-1' => array(
            'title'           => $location_title,
            'slugs'            => $location_ids,
            'class'           => 'lp-new-location-outer-title-loc3',
            'tax_slug'        => 'location',
            'icon_class'      => 'fa-map-marker',
            'see_all_class'   => 'show-all-feture-loc'
        ),
        'col-2' => array(
            'title'           => $category_title,
            'slugs'           => $category_ids,
            'class'           => 'lp-new-location-outer-title-cat',
            'tax_slug'        => 'listing-category',
            'icon_class'      => 'fa-user-md',
            'see_all_class'   => 'show-all-feture-cat'
        ),
        
        
        'col-3' => array(
            'title'           => $feature_title,
            'slugs'           => $feature_ids,
            'class'           => 'lp-new-location-outer-title-loc',
            'tax_slug'        => 'features',
            'icon_class'      => 'fa-user-md',
            'see_all_class'   => 'show-all-feture-fec'
        ),
        'col-4' => array(
            'title'           => $hospital_title,
            'slugs'           => $hospital_ids,
            'class'           => 'lp-new-location-outer-title-loc4',
            'tax_slug'        => 'medicalpro-hospital',
            'icon_class'      => 'fa-bed',
            'see_all_class'   => 'show-all-feture-loc4'
        ),
    );
    //    echo '<pre>';
    //    print_r($cat_columns);
    //    echo '</pre>';

    $output = '';
    $output .= '<div class="row padding-top-40">';
        foreach($cat_columns as $cat_column){

            $terms = (isset($cat_column['slugs']) && !empty($cat_column['slugs'])) ? explode(',', $cat_column['slugs']) : array();
            if(isset($terms) && !empty($terms)){
                $output .= '<div class="col-md-3 col-sm-4">';
                    $output .= '<div class="lp-new-location-outer text-center">
                        <h4 class="lp-new-location-outer-title '. $cat_column['class'] .'">
                            <span><i class="fa '. $cat_column['icon_class'] .'" aria-hidden="true"></i></span>'. sprintf(esc_html__('BROWSE BY %s', 'medicalpro'), $cat_column['title']) .'
                        </h4>';
                        $output .= '<div class="lp-new-outer">';
                        $catCount = 1;
                        foreach ( $terms as $term_slug ){
                            $term = get_term_by('slug', $term_slug, $cat_column['tax_slug']);
                            $catCount++;
                            $class = '';
                            $style = '';
                            if( $catCount  > 5 ){
                                $class = 'show-more';
                                $style = 'style="display:none;"';
                            }
                            if (!empty($term) && is_object($term)) {
                                $output .= '<div class="'. esc_attr($class).'" '. $style .'>
                                    <div class="lp-new-grid-style-inner">
                                        <div class="text-center">
                                            <a href="'. esc_url( get_term_link( $term->term_id)) .'">'. esc_attr($term->name) .'</a>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }
                        $output .= '</div>';
                        if(isset($terms) && count($terms) > 5){
                            $output .= '<a href="javascript:void(0);" class="show-all-feature '. $cat_column['see_all_class'] .'" data-show_more="'.esc_html__('Show More','medicalpro').'" data-less_more="'.esc_html__('Show Less','medicalpro').'">'.esc_html__('Show More','medicalpro').'</a>';
                        }
                    $output .= '</div>';
                $output .= '</div>';
            }
        }
    $output .= '</div>';
    return $output;
}
add_shortcode('loc_cat_box', 'medicalpro_shortcode_loc_cat_box');


/*------------------------------------------------------*/
/* end new location and feature box 
/*------------------------------------------------------*/

/*------------------------------------------------------*/
/* Content boxes
/*------------------------------------------------------*/
vc_map( array(
    "name" => __("MD Content Boxes", "my-text-domain"),
    "base" => "md_content_boxes",
    "category"  => __('Medicalpro', 'js_composer'),
    "as_parent" => array('only' => 'md_content_box'),
    "content_element" => true,
    "show_settings_on_create" => false,
    "icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "is_container" => true,
    "params" => array(
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __( "Element title", "js_composer" ),
            "param_name" => "moderen_title",
            "value"       => "How Its Actually Work",
            'save_always' => true,
            "description" => "Enter Element Title"
        ),
    ),
    "js_view" => 'VcColumnView'
) );
function medicalpro_shortcode_content_box_container( $atts, $content = null ) {
    extract(shortcode_atts(array(

        'moderen_title'   => '',
    ), $atts));
    $output = null;

    $output .= ' <div class="about-box-container">';
    $output .= '	<div class="lp-section-content-container clearfix">';

    $output .= 				do_shortcode($content);

    $output .= '	</div>';
    $output .= '</div>';



    return $output;
}
add_shortcode( 'md_content_boxes', 'medicalpro_shortcode_content_box_container' );


vc_map( array(
    "name"                      => __("LP Single Content Box", "js_composer"),
    "base"                      => 'md_content_box',
    "category"                  => __('medicalpro', 'js_composer'),
    "description"               => '',
    "content_element" => true,
    "as_child" => array('only' => 'md_content_boxes'),
    "icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
       
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __("Title","js_composer"),
            "param_name"  => "content_title",
            "value"       => "PLANNING",
            "description" => "Title fot content"
        ),
        array(
            "type"        => "textarea",
            "class"       => "",
            "heading"     => __("Content","js_composer"),
            "param_name"  => "content_desc",
            "value"       => "Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium or sit v oluptatem accusantiumor sit v oluptatem ",
            "description" => "Some text"
        ),
        array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Upload Content Icon iamge","js_composer"),
            "param_name"  => "content_img",
            "value"       => get_template_directory_uri()."/assets/images/search-icon2.png",
            "description" => "",
			
        ),
		array(
            "type" => "colorpicker",
			  "class" => "",
			  "heading" => __( "icon background color", "my-text-domain" ),
			  "param_name" => "icon_background_color",
			  
			  "value" => '#000000', //Default Red color
			  "description" => __( "Choose text color", "my-text-domain" )

        ),


    ),
) );
function medicalpro_shortcode_content_box($atts, $content = null) {
    extract(shortcode_atts(array(
        
        'content_title'   => 'PLANNING',
        'content_desc'   => 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium or sit v oluptatem accusantiumor sit v oluptatem',
        'content_img'   => plugin_dir_url( __FILE__ ) . "/assets/images/icons/con3.png",
        'icon_background_color'   => '#FEE0F6',
    ), $atts));

	$FimageURL=null;
	$con=null;
	 if ( !empty($content_img )) {
        if( is_array( $content_img ) )
        {
            $content_img  =   $content_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $content_img, 'full');
        if($imgurl){
            $con = $imgurl[0];
        }else{
            $con = plugin_dir_url( __FILE__ ) . "/assets/images/icons/con3.png";
        }
    }
	 $color = $icon_background_color;
    $rgb = medicalpro_hex2rgba2($color);
    $rgba = medicalpro_hex2rgba2($color, 0.9);
    $output = null;
  
		$output .= '<div class="col-md-4 col-sm-6 about-box about-box-style3">
						<div class="about-box-inner">
							<div class="about-box-slide">
								<div class="about-box-icon-style2" style="background:'.medicalpro_hex2rgba2($icon_background_color, 1). ';">
									<img src="'.$con.'" alt="" />
								</div>
								<div class="listingpro-columns-style2-content">
									<div class="about-box-title-style2 clearfix">
										<h4>'.$content_title.'</h4>
									</div>
									<div class="about-box-description-style2">
										<p class="paragraph-small">
											
											'.mb_substr($content_desc, 0, 90).'
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>';
	

    return $output;
}
add_shortcode('md_content_box', 'medicalpro_shortcode_content_box');


/*------------------------------------------------------*/
/* Activities
/*------------------------------------------------------*/

vc_map( array(
    "name"                      => __("MD Activities", "js_composer"),
    "base"                      => 'md_activities',
    "category"                  => __('Medicalpro', 'js_composer'),
    "description"               => '',
	"icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
		
        array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => esc_html__("Number of Activities","js_composer"),
            "param_name"  => "number_posts",
            'value' => array(
                esc_html__( '3 Posts', 'js_composer' ) => '3',
                esc_html__( '4 Posts', 'js_composer' ) => '4',
                esc_html__( '5 Posts', 'js_composer' ) => '5',
            ),
            'save_always' => true,
            "description" => "Select number of activities you want to show"
        ),
		
    ),
) );

function listingpro_shortcode_md_activities($atts, $content = null) {
    extract(shortcode_atts(array(
        'number_posts'   => '5',

        'activity_placeholder' => ''
    ), $atts));
    require_once (THEME_PATH . "/include/aq_resizer.php");
    $output = null;

    $args   =   array(
        'post_type' => 'lp-reviews',
        'post_status' => 'publish',
        'posts_per_page' => $number_posts,
    );
    $activities  =   new WP_Query( $args );
    $img_url    = '';
    $img_url2   = '';
    $img_url3   = '';
    $img_url4   = '';
    global $listingpro_options;
    $placeholder_img    =   '';
    $use_listing_img    =   $listingpro_options['lp_review_img_from_listing'];
    if( $use_listing_img == 'off' )
    {
        $placeholder_img    =   $listingpro_options['lp_review_placeholder'];
        $placeholder_img    =   $placeholder_img['url'];
    }

    if( $activities->have_posts() ) :
        $counter    =   1;
        $output .=  '<div class="lp-activities"><div class="lp-section-content-container"> ';
        $output .=  '    <div class="row">';
        while ( $activities->have_posts() ) : $activities->the_post();
            global $post;
            $r_meta     =   get_post_meta( get_the_ID(), 'lp_listingpro_options', true );
            $LID        =   $r_meta['listing_id'];
            $rating     =   $r_meta['rating'];

            $adStatus = get_post_meta( $LID, 'campaign_status', true );
            $CHeckAd = '';
            $adClass = '';
            if($adStatus == 'active'){
                $CHeckAd = '<span>'.esc_html__('Ad','medicalpro').'</span>';
                $adClass = 'promoted';
            }
            $author_avatar_url = get_user_meta( $post->post_author, "listingpro_author_img_url", true);
            $avatar;
            if( !empty( $author_avatar_url ) )
            {
                $avatar =  $author_avatar_url;

            }
            else
            {
                $avatar_url = listingpro_get_avatar_url ( $post->post_author, $size = '55' );
                $avatar =  $avatar_url;
            }
           
            $lp_liting_title    =   get_the_title( $LID );
            if( strlen( $lp_liting_title ) > 35 )
            {
                $lp_liting_title    =   mb_substr( $lp_liting_title, 0, 35 ).'...';
            }

            $rating_num_bg  =   '';
            $rating_num_clr  =   '';

            if( $rating < 2 ){ $rating_num_bg  =   'num-level1'; $rating_num_clr  =   'level1'; }
            if( $rating < 3 ){ $rating_num_bg  =   'num-level2'; $rating_num_clr  =   'level2'; }
            if( $rating < 4 ){ $rating_num_bg  =   'num-level3'; $rating_num_clr  =   'level3'; }
            if( $rating >= 4 ){ $rating_num_bg  =   'num-level4'; $rating_num_clr  =   'level4'; }
			
				
				$output .=  '
					<div class="col-md-3 col-sm-6"> 
						<div class="lp-activity lp-activity-new ">
							<div class="lp-activity-top-new">
								<a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" class=""><img src="'. esc_attr($avatar) .'" alt="'. get_the_title() .'"></a>
								
							</div>
							<div class="lp-activity-bottom md-activity-bottom">
								<div class="lp-activity-review-writer ">
									<a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">'. get_the_author() .'</a> 
									
								</div>
								<div class="md-listing-stars clearfix">
							   <div class="md-rating-stars-outer">
							   <span class="lp-rating-num rating-with-colors '. review_rating_color_class($rating) .'">'. round($rating, 2) .'</span>
									<span class="lp-star-box ';
					if( $rating > 0 ){ $output .= 'filled'.' '.$rating_num_clr; }
					$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
					if( $rating > 1 ){ $output .= 'filled'.' '.$rating_num_clr; }
					$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
					if( $rating > 2  ){ $output .= 'filled'.' '.$rating_num_clr; }
					$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
					if( $rating > 3 ){ $output .= 'filled'.' '.$rating_num_clr; }
					$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
					if( $rating > 4 ){ $output .= 'filled'.' '.$rating_num_clr; }
					$output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
	</div>
									
								</div>
								
								
								
							</div>
							<div class="md-activity-description">
									<p>'. mb_substr( $post->post_content, '0', '70' ) .' <a href="'. get_permalink( $LID ) .'">'.esc_html__('Read More', 'medicalpro' ).'</a></p>
									
								</div>
								<p class="lp-new-activity-title"><span>Review For</span><a href="'. get_permalink( $LID ) .'">'. $lp_liting_title .'</a></p>
						</div>
					</div>';
			
            $counter++;
        endwhile; wp_reset_postdata();
        $output .=  '   </div></div>';
        $output .=  '</div>';
    endif;

    return $output;
}

add_shortcode('md_activities', 'listingpro_shortcode_md_activities');





/*------------------------------------------------------*/
/* ListingPro Columns Element
/*------------------------------------------------------*/




vc_map( array(
    "name"                      => esc_html__("MD Columns Element", "js_composer"),
    "base"                      => 'medicalpro_columns',
    "category"                  => esc_html__('Medicalpro', 'js_composer'),
    "description"               => '',
	"icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
		
        array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Column Left Image","js_composer"),
            "param_name"  => "listing_cols_left_img",
            "value"       => get_template_directory_uri()."/assets/images/columns.png",
            "description" => "",
			
        ),
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("First Column Title","js_composer"),
            "param_name"	=> "listing_first_col_title",
			
            "value"			=> "1- Claimed"
        ),
        array(
            'type'        => 'textarea',
            'heading'     => esc_html__( 'First Column Description', 'js_composer' ),
            'param_name'  => 'listing_first_col_desc',
			
            'value'       => 'Best way to start managing your business listing is by claiming it so you can update.'
        ),
		 array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("First Column icon","js_composer"),
            "param_name"  => "listing_cols_first_img",
            "value"       => get_template_directory_uri()."/assets/images/interesting.png",
            "description" => "",
			
        ),
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("Second Column Title","js_composer"),
            "param_name"	=> "listing_second_col_title",
			
            "value"			=> "2- Promote"
        ),
        array(
            'type'        => 'textarea',
            'heading'     => esc_html__( 'Second Column Description', 'js_composer' ),
            'param_name'  => 'listing_second_col_desc',
			
            'value'       => 'Promote your business to target customers who need your services or products.'
        ),
		 array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Second Column icon","js_composer"),
            "param_name"  => "listing_cols_second_img",
            "value"       => get_template_directory_uri()."/assets/images/interesting.png",
            "description" => "",
			
        ),
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("Third Column Title","js_composer"),
            "param_name"	=> "listing_third_col_title",
			
            "value"			=> "3- Convert"
        ),
        array(
            'type'        => 'textarea',
            'heading'     => esc_html__( 'Third Column Description', 'js_composer' ),
            'param_name'  => 'listing_third_col_desc',
			
            'value'       => 'Turn your visitors into paying customers with exciting offers and services on your page.'
        ),
		array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Third Column icon","js_composer"),
            "param_name"  => "listing_cols_third_img",
            "value"       => get_template_directory_uri()."/assets/images/interesting.png",
            "description" => "",
			
        ),
		
		
    ),
) );
function medicalpro_shortcode_columns($atts, $content = null) {
    extract(shortcode_atts(array(
		
        'listing_cols_left_img'      => get_template_directory_uri()."/assets/images/columns.png",
        'listing_cols_first_img'      => '',
        'listing_cols_second_img'      => '',
        'listing_cols_third_img'      => '',
		
        'listing_first_col_title'    => '1- Claimed',
        'listing_first_col_desc'     => 'Best way to start managing your business listing is by claiming it so you can update.',
        'listing_second_col_title' 	 => '2- Promote',
        'listing_second_col_desc' 	 => 'Promote your business to target customers who need your services or products.',
        'listing_third_col_title' 	 => '3- Convert',
        'listing_third_col_desc' 	 => 'Turn your visitors into paying customers with exciting offers and services on your page.',
        
		
    ), $atts));
	
    $output = null;
	$colimage1=null;
    $colimage2=null;
    $colimage3=null;
    $leftImg = '';
    if (!empty($listing_cols_left_img)) {
        if( is_array( $listing_cols_left_img ) )
        {
            $listing_cols_left_img  =   $listing_cols_left_img['id'];
        }
        $bgImage = wp_get_attachment_image_src( $listing_cols_left_img, 'full' );
        $leftImg = '<img src="'.$bgImage[0].'" alt="">';
    }else{
        $leftImg = '';
    }
	if ( $listing_cols_first_img ) {
        if( is_array( $listing_cols_first_img ) )
        {
            $listing_cols_first_img  =   $listing_cols_first_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_cols_first_img, 'full');
        if($imgurl){
            $colimage1 = $imgurl[0];
        }else{
            $colimage1 = plugin_dir_url( __FILE__ ) . "/assets/images/icons/col1.png";
        }
    }
    if ( !empty($listing_cols_second_img) ) {
        if( is_array( $listing_cols_second_img ) )
        {
            $listing_cols_second_img  =   $listing_cols_second_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_cols_second_img, 'full');
         $colimage2 = '<img src="'.$imgurl[0].'" alt="">';
    }else{
		
		$colimage2 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/col2.png".'" alt="">';
	}
	if ( !empty($listing_cols_third_img )) {
        if( is_array( $listing_cols_third_img ) )
        {
            $listing_cols_third_img  =   $listing_cols_third_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_cols_third_img, 'full');
        $colimage3 = '<img src="'. $imgurl[0] .'">';
    }else{
		
		$colimage3 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/col3.png".'" alt="">';
	}

		 $output .='
			<div class="promotional-element listingpro-columns listingpro-columns-style2">
				<div class="listingpro-row padding-top-60 padding-bottom-60">
					<div class="promotiona-col-left">
						'.$leftImg.'
					</div>
					<div class="promotiona-col-right">
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons1"><img src="'.$colimage1.'" alt=""/></div>
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_first_col_title.'</h3>
								<p>'.$listing_first_col_desc.'</p>
							</div>		
						</article>
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons2">'.$colimage2.'</div>
							
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_second_col_title.'</h3>
								<p>'.$listing_second_col_desc.'</p>
							</div>
						</article>
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons3">'.$colimage3.'</div>
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_third_col_title.'</h3>
								<p>'.$listing_third_col_desc.'</p>
							</div>
							
						</article>
					</div>
				</div>
			</div>'; 
		 
	
    return $output;
}
add_shortcode('medicalpro_columns', 'medicalpro_shortcode_columns');

/*------------------------------------------------------*/
/* ListingPro facts Element
/*------------------------------------------------------*/




vc_map( array(
    "name"                      => esc_html__("MD Facts Element", "js_composer"),
    "base"                      => 'medicalpro_facts',
    "category"                  => esc_html__('Medicalpro', 'js_composer'),
    "description"               => '',
	"icon" => get_template_directory_uri() . "/assets/images/vcicon.png", // Simply pass url to your icon here
    "params"                    => array(
		
      
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("First fact Title","js_composer"),
            "param_name"	=> "listing_first_fac_title",
			
            "value"			=> "256-bit"
        ),
        array(
            'type'        => 'textfield',
            'heading'     => esc_html__( 'First fact Title2', 'js_composer' ),
            'param_name'  => 'listing_first_fac_desc',
			
            'value'       => 'encryption'
        ),
		 array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("First fact Img","js_composer"),
            "param_name"  => "listing_fac_first_img",
            "value"       => "",
            "description" => "",
			
        ),
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("Second fact Title","js_composer"),
            "param_name"	=> "listing_second_fac_title",
			
            "value"			=> "ISO 27001"
        ),
        array(
            'type'        => 'textfield',
            'heading'     => esc_html__( 'Second fact Title2', 'js_composer' ),
            'param_name'  => 'listing_second_fac_desc',
			
            'value'       => ' certified'
        ),
		array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Second fact icon","js_composer"),
            "param_name"  => "listing_fac_sec_img",
            "value"       => "",
            "description" => "",
			
        ),
        array(
            "type"			=> "textfield",
            "class"			=> "",
            "heading"		=> esc_html__("Third fact Title","js_composer"),
            "param_name"	=> "listing_third_fac_title",
			
            "value"			=> "HIPAA"
        ),
        array(
            'type'        => 'textfield',
            'heading'     => esc_html__( 'Third fact Title2', 'js_composer' ),
            'param_name'  => 'listing_third_fac_desc',
			
            'value'       => 'compliant'
        ),
		array(
            "type"        => "attach_image",
            "class"       => "",
			
            "heading"     => esc_html__("Third fact icon","js_composer"),
            "param_name"  => "listing_fac_third_img",
            "value"       => "",
            "description" => "",
			
        ),
		
		
    ),
) );
function medicalpro_shortcode_facts($atts, $content = null) {
    extract(shortcode_atts(array(


        'listing_first_fac_title'    => '256-bit',
        'listing_fac_first_img'    => '',
        'listing_fac_sec_img'    => '',
        'listing_fac_third_img'    => '',

        'listing_first_fac_desc'     => 'encryption',
        'listing_second_fac_title' 	 => 'ISO 27001',
        'listing_second_fac_desc' 	 => 'certified',
        'listing_third_fac_title' 	 => 'HIPAA',
        'listing_third_fac_desc' 	 => 'compliant',


    ), $atts));

    $output = null;
	$facimage1=null;
    $facimage2=null;
    $facimage3=null;
	if ( !empty($listing_fac_first_img )) {
        if( is_array( $listing_fac_first_img ) )
        {
            $listing_fac_first_img  =   $listing_fac_first_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_fac_first_img, 'full');

         $facimage1 = '<img src="'.$imgurl[0].'" alt="">';
    }else{

		$facimage1 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc3.png".'" alt="">';

    }
    if (!empty($listing_fac_sec_img )) {
        if( is_array( $listing_fac_sec_img ) )
        {
            $listing_fac_sec_img  =   $listing_fac_sec_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_fac_sec_img, 'full');
       $facimage2 = '<img src="'.$imgurl[0].'" alt="">';
    }else{

		$facimage2 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc2.png".'" alt="">';

    }
	if ( !empty($listing_fac_third_img )) {
        if( is_array( $listing_fac_third_img ) )
        {
            $listing_fac_third_img  =   $listing_fac_third_img['id'];
        }
        $imgurl = wp_get_attachment_image_src( $listing_fac_third_img, 'full');
        $facimage3 = '<img src="'.$imgurl[0].'" alt="">';
    }else{

		$facimage3 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc1.png".'" alt="">';

    }


		 $output .='
			<div class="md-facts-outer">
				<div class="row padding-top-60 padding-bottom-60">
					<div class="col-md-4 text-center">
    					<div class="md-fact-container">
    						<div class="md-fact-icone">
    							
    							'.$facimage1.'
    						
    						</div>
    						<div class="md-facts-content">
    							<p>'.$listing_first_fac_title.'</p>
    							<p>'.$listing_first_fac_desc.'</p>
    						</div>
    					</div>
					</div>
					<div class="col-md-4 text-center">
    					<div class="md-fact-container">
    						<div class="md-fact-icone">
    							'.$facimage2.'
    						
    						</div>
    						<div class="md-facts-content">
    							<p>'.$listing_second_fac_title.'</p>
    							<p>'.$listing_second_fac_desc.'</p>
    						</div>
						</div>
					</div>
					<div class="col-md-4 text-center">
					    <div class="md-fact-container">
    						<div class="md-fact-icone">
	    						'.$facimage3.'
    						</div>
	    					<div class="md-facts-content">
		    					<p>'.$listing_third_fac_title.'</p>
			    				<p>'.$listing_third_fac_desc.'</p>
				    		</div>
					    </div>
					</div>
				</div>
			</div>';


    return $output;
}
add_shortcode('medicalpro_facts', 'medicalpro_shortcode_facts');

































if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_medicalpro_partners extends WPBakeryShortCodesContainer {
    }
	class WPBakeryShortCode_md_content_boxes extends WPBakeryShortCodesContainer {
    }
    
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_medicalpro_partner extends WPBakeryShortCode {
    }
	class WPBakeryShortCode_md_content_box extends WPBakeryShortCode {
    }
    
}