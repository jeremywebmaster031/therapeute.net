<?php
global $post;
$awards = wp_get_post_terms($post->ID, 'medicalpro-award');
if(isset($awards) && !empty($awards)){ ?>	
    <div id="mp-awards-tab" class="mp-awards-tab margin-bottom-60">
        <div class="mp-awards-heading">
            <h1><?php esc_html_e('Awards and Recognition', 'medicalpro'); ?></h1>
        </div>
        <div class="mp-awards-content">
            <div class="row">
                <?php
                foreach ($awards as $award) {
                    $icon = listingpro_get_term_meta($award->term_id, 'medpro_award_icon');
                    echo '<div class="col-md-4"><div class="mp-awards-content-card text-center">';
                        if(isset($icon) && !empty($icon)) {
                            echo '<div class="mp-awards-content-card-icon">';
                                echo '<i class="fa ' . esc_attr($icon) . '" aria-hidden="true"></i>';
                            echo '</div>';
                        }
                        echo '<div class="mp-awards-content-card-detail">
                            <p>' . esc_html($award->name) . '</p>
                        </div>
                    </div></div>';
                }
                ?>		
            </div>
        </div>
    </div>
<?php } ?>