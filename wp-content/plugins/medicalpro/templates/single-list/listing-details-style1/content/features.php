<?php
global $post;
$tags = get_the_terms($post->ID, 'features');
if (isset($tags) && !empty($tags)) { ?>
    <div id="mp-galance-tab" class="mp-galance-tab margin-bottom-50">
        <div class="mp-galance-heading">
            <h1><?php esc_html_e('At Galance', 'medicalpro'); ?></h1>
        </div>
        <div class="mp-galance-content">
            <div class="row">
                <div class="list-style-none clearfix">
                    <?php
                    foreach ($tags as $tag) {
                        $icon = listingpro_get_term_meta($tag->term_id, 'lp_features_icon');
                        ?>								
                        <div class=" col-md-4">
                            <div class="mp-galance-content-card">
                                <div class="">
                                    <div class="mp-galance-content-card-icon">
                                        <span class="tick-icon">
                                            <?php if (!empty($icon)) { ?>
                                                <i class="fa <?php echo esc_attr($icon); ?>"></i>
                                            <?php } else { ?>
                                                <i class="fa fa-check"></i>
                                            <?php } ?>
                                        </span>
                                    </div>
                                    <div class="mp-galance-content-card-detail">
                                        <a href="<?php echo get_term_link($tag); ?>" class="parimary-link"><?php echo esc_html($tag->name); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>