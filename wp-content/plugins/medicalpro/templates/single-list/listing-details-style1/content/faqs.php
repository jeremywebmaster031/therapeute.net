<?php
global $post;
$faqs = listing_get_metabox_by_ID('faqs', $post->ID);
if (!empty($faqs) && count($faqs) > 0) {
    $faq    = $faqs['faq'];
    $faqans = $faqs['faqans'];
    if (!empty($faq[1])) { ?>
        <div id="mp-faq-tab" class="mp-faq-tab margin-bottom-60">
            <div class="mp-faq-heading">
                <h1><?php esc_html_e('Frequently Asked Questions', 'medicalpro'); ?></h1>
            </div>
            <div class="mp-faq-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mp-faq-content-container">
                            <div class="mp-faq-content-qa">
                                <?php
                                if (!empty($faqs) && count($faqs) > 0) {
                                    $faq = $faqs['faq'];
                                    $faqans = $faqs['faqans'];
                                    if (!empty($faq[1])) { ?>
                                        <div class="post-row faq-sectionv clearfix">
                                            <div class="post-row-accordionv sssssssss">
                                                <div id="accordionv">
                                                    <?php for ($i = 1; $i <= (count($faq)); $i++) { ?>
                                                        <?php if (!empty($faq[$i])) { ?>
                                                            <script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"<?php echo esc_html($faq[$i]); ?> ?","acceptedAnswer":{"@type":"Answer","text":"<p><?php echo nl2br(do_shortcode($faqans[$i]), false); ?>.</p>"}}]}</script>
                                                            <div class="mp-faq-content-qa-single-q clearfix">
                                                                <div class="mp-faq-content-qa-single-q-quote display-inline-block pull-left">
                                                                    <p><?php esc_html_e('Q:', 'medicalpro'); ?></p>
                                                                </div>	
                                                                <div class="mp-faq-content-qa-single-q-text display-inline-block pull-left">
                                                                    <p><?php echo esc_html($faq[$i]); ?></p>
                                                                </div>

                                                            </div>
                                                            <div class="mp-faq-content-qa-single-a clearfix">
                                                                <div class="mp-faq-content-qa-single-a-quote display-inline-block pull-left">
                                                                    <p><?php esc_html_e('A:', 'medicalpro'); ?></p>
                                                                </div>
                                                                <div class="mp-faq-content-qa-single-a-text display-inline-block pull-left">
                                                                    <p>
                                                                        <?php //echo do_shortcode($faqans[$i]);    ?>
                                                                        <?php echo nl2br(do_shortcode($faqans[$i]), false); ?>
                                                                    </p>
                                                                </div><!-- accordion tab -->
                                                            </div><!-- accordion tab -->
                                                        <?php } ?>	
                                                    <?php } ?>	
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>