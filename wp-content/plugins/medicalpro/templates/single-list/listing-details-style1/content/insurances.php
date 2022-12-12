<?php
global $post;
$listing_insurances = wp_get_post_terms(get_the_ID(), 'medicalpro-insurance');
if (isset($listing_insurances) && !empty($listing_insurances)) { ?>
    <div id="mp-insurances-tab" class="mp-insurances-tab margin-bottom-60">
        <div class="mp-insurances-heading">
            <h1><?php esc_html_e('Accepted Insurances', 'medicalpro'); ?></h1>
        </div>
        <div class="mp-insurances-content">
            <div class="mp-clearfix"></div>
            <div class="mp-border-radius-10">
                <?php
                $insurances_list = array();
                $counter = 0;
                foreach ($listing_insurances as $listing_insurance) {
                    $term_image = get_term_meta($listing_insurance->term_id, 'medpro_insurance_image', 'image');
                    
                    $counter++;
                    $class = '';
                    $style = '';
                    if( $counter  > 3 ){
                        $class = 'show-more-insurance';
                        $style = 'style="display:none;"';
                    }
                    $insurance_output = '
                    <div class="col-md-6 mp-insurances-content-card '. $class .'" '. $style .'>
                        <div class="display-flex content-center horizontal">';
                            if (isset($term_image) && !empty($term_image)) {
                                $insurance_output .= '<div class="mp-insurances-content-card-icon vertical">
                                    <span class="cat-icon"><img class="icon icons8-Food" src="' . $term_image . '" alt="cat-icon"></span>
                                </div>';
                            }
                            $insurance_output .= '<div class="mp-insurances-content-card-detail vertical">
                                <p>' . esc_html($listing_insurance->name) . '</p>
                            </div>
                        </div>
                    </div>';
                    $insurances_list[] = $insurance_output;
                }

                foreach ($insurances_list as $insurance) {
                    echo $insurance;
                }
                if (count($insurances_list) > 3) {
                    echo '<div class="col-md-6 mp-insurances-content-card">
                        <div class="display-flex content-center horizontal">
                            <div class="mp-insurances-content-card-detail vertical margin-top-20">
                                <p><a href="javascript:void(0);" class="show-all-insurance">' . esc_html__('View All Insurances', 'medicalpro') . '</a></p>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
            <div class="mp-clearfix"></div>
        </div>
    </div>
<?php } ?>