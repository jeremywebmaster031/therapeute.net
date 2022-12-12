<?php
			global $listingpro_options;
			$default_feature_img= lp_theme_option_url('lp_def_featured_image');
			$lp_review_switch = $listingpro_options['lp_review_switch'];

			if(!isset($postGridCount)){
				$postGridCount = '0';
			}
			global $postGridCount;
			$postGridCount++;

			$listing_style = '';
				$listing_style = $listingpro_options['listing_style'];
				if(isset($_GET['list-style']) && !empty($_GET['list-style'])){
					$listing_style = esc_html($_GET['list-style']);
				}
				if(is_front_page()){
					$listing_style = 'col-md-4 col-sm-6';
					$postGridnumber = 3;
				}else{
					if($listing_style == '1'){
						$listing_style = 'col-md-4 col-sm-6';
						$postGridnumber = 3;
					}elseif($listing_style == '3' && !is_page()){
						$listing_style = 'col-md-4 col-sm-12';
						$postGridnumber = 3;
					}else{
						$listing_style = 'col-md-4 col-sm-6';
						$postGridnumber =3;
					}
				}
				if(is_page_template('template-favourites.php')){
					$listing_style = 'col-md-4 col-sm-6';
					$postGridnumber =3;
				}
                $latitude = listing_get_metabox('latitude');
                $longitude = listing_get_metabox('longitude');
                $gAddress = listing_get_metabox('gAddress');
                $phone = listing_get_metabox('phone');

				$isfavouriteicon = listingpro_is_favourite_grids(get_the_ID(),$onlyicon=true);
				$isfavouritetext = listingpro_is_favourite_grids(get_the_ID(),$onlyicon=false);

				$adStatus = get_post_meta( get_the_ID(), 'campaign_status', true );
				$CHeckAd = '';
				$adClass = '';
				if($adStatus == 'active'){
					$CHeckAd = '<div class="padding-top-10"><span class="listing-pro mp-listing-featured-tag"><i class="fa fa-info-circle" aria-hidden="true"></i> '.esc_html__('Sponsored','medicalpro').'</span></div>';
					$adClass = 'promoted';
				}
				$claimed_section = listing_get_metabox('claimed_section');

				$claim = '';
				$claimStatus = '';

				$certified_doctor    = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);

				if($certified_doctor == 'Yes') {
					if(is_singular( 'listing' ) ){
						$claimStatus = esc_html__('Claimed', 'medicalpro');
					}
					$claim = '<div class="mp-claimed-profile mp-tooltip"><span class="mp-tooltiptext"> '.esc_html__('Certified Doctor', 'medicalpro').'</span>
					
					 <img src="'. MP_PLUGIN_DIR . 'assets/images/claimed/claimed1.svg'.'" alt="Claimed Profile">
					
					
					</div>';

				}else {
					$claim = '';

				}
				$listing_layout = $listingpro_options['listing_views'];

					?>


					<div class="col-md-4 <?php echo esc_attr($adClass); ?> lp-grid-box-contianer grid_view_s2 grid_view2 card1 lp-grid-box-contianer1 listing-grid-view2-outer md-listing-outer" data-title="<?php echo get_the_title(); ?>" data-postid="<?php echo get_the_ID(); ?>"   data-lattitue="<?php echo esc_attr($latitude); ?>" data-longitute="<?php echo esc_attr($longitude); ?>" data-posturl="<?php echo get_the_permalink(); ?>">
						<?php if(is_page_template('template-favourites.php')){ ?>
							<div class="remove-fav md-close" data-post-id="<?php echo get_the_ID(); ?>">
								<i class="fa fa-close"></i>
							</div>

						<?php } ?>
						<div class="lp-grid-box">
                            <?php echo $CHeckAd; ?>
							<div class="md-grid-box-thumb-container" >
								<div class="lp-grid-box-thumb">
									<div class="show-img">
										<?php
											if ( has_post_thumbnail()) {
												$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'listingpro-blog-grid2' );
													if(!empty($image[0])){
														echo "<a href='".get_the_permalink()."' >
																<img src='" . $image[0] . "' />
															</a>";
													}else {
														echo '
														<a href="'.get_the_permalink().'" >
															<img src="'.esc_html__('https://via.placeholder.com/78x78', 'medicalpro').'" alt="image">
														</a>';
													}
											}elseif(!empty($default_feature_img)){
												echo '
												<a href="'.get_the_permalink().'" >
													<img src="'.$default_feature_img.'" alt="image">
												</a>';
											}else {
												echo '
												<a href="'.get_the_permalink().'" >
													<img src="'.esc_html__('https://via.placeholder.com/78x78', 'medicalpro').'" alt="image">
												</a>';
											}
										?>
									</div>
									<div class="hide-img listingpro-list-thumb">
										<?php
											if ( has_post_thumbnail()) {
												$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'listingpro-blog-grid' );
													if(!empty($image[0])){
														echo "<a href='".get_the_permalink()."' >
																<img src='" . $image[0] . "' />
															</a>";
													}else {
														echo '
														<a href="'.get_the_permalink().'" >
															<img src="'.esc_html__('https://via.placeholder.com/78x78', 'medicalpro').'" alt="image">
														</a>';
													}
											}else {
												echo '
												<a href="'.get_the_permalink().'" >
													<img src="'.esc_html__('https://via.placeholder.com/78x78', 'medicalpro').'" alt="image">
												</a>';
											}
										?>
									</div>
                                    <?php echo $claim; ?>
							   	</div>
							</div>
							<div class="lp-grid-desc-container clearfix">
								<div class="lp-grid-box-description clearfix">
									<div class="lp-grid-box-left">
										<h4 class="lp-h4">
											<a href="<?php echo get_the_permalink(); ?>">
											    <?php echo mb_substr(get_the_title(), 0, 18); ?>
											</a>
										</h4>
										<ul class="md-loop-cat">
											<li>
												<?php
													$cats = get_the_terms( get_the_ID(), 'listing-category' );
													if(!empty($cats)){
														foreach ( $cats as $cat ) {
															$category_image = listing_get_tax_meta($cat->term_id,'category','image');
																if(!empty($category_image)){
																	echo '<span class="cat-icon"><img class="icon icons8-Food" src="'.$category_image.'" alt="cat-icon"></span>';
																}
															$term_link = get_term_link( $cat );
															echo '
															<a href="'.$term_link.'">
																'.$cat->name.'
															</a>';

															break; // For Only First Cat To Show
														}
													}
												?>
											</li>
										</ul>
									</div>
								</div>
								<div class="clearfix"></div>
								<ul class="md-experiences margin-bottom-0">
								    <?php
								    $plan_id = listing_get_metabox_by_ID('Plan_id',get_the_ID());
								    $tagline_show = get_post_meta( $plan_id, 'listingproc_tagline', true );
								    if ( $plan_id == 'none' || empty($plan_id) ) :
                                        $tagline_show   =   true;
                                    endif;
								    $tagline_text = listing_get_metabox_by_ID('tagline_text', get_the_ID());
								    if ($tagline_show == "true" && !empty($tagline_text)) :
								    ?>



								    <?php
                					$rating = get_post_meta( get_the_ID(), 'listing_rate', true );
                					$rating_num_bg  =   '';
                					$rating_num_clr  =   '';
                					if( $rating < 3 ){ $rating_num_bg  =   'num-level1'; $rating_num_clr  =   'level1'; }
                					if( $rating < 4 ){ $rating_num_bg  =   'num-level2'; $rating_num_clr  =   'level2'; }
                					if( $rating < 5 ){ $rating_num_bg  =   'num-level3'; $rating_num_clr  =   'level3'; }
                					if( $rating >= 5 ){ $rating_num_bg  =   'num-level4'; $rating_num_clr  =   'level4'; }
                					if(review_rating_color_class($rating) != 'lp-star-worst'){ ?>
                					    <li>
                    					    <div class="md-listing-stars clearfix">
                        					    <div class="md-rating-stars-outer lp-rating-num rating-with-colors <?php echo review_rating_color_class($rating); ?>">
                        						    <span class="lp-rating-num rating-with-colors <?php echo review_rating_color_class($rating); ?>"><?php echo $rating; ?></span>
                        							<span class="lp-star-box <?php if($rating >= 1){echo 'filled'.' '.$rating_num_clr;}?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                        							<span class="lp-star-box <?php if($rating >= 2){echo 'filled'.' '.$rating_num_clr;}?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                        							<span class="lp-star-box <?php if($rating >= 3){echo 'filled'.' '.$rating_num_clr;}?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                        							<span class="lp-star-box <?php if($rating >= 4){echo 'filled'.' '.$rating_num_clr;}?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                        							<span class="lp-star-box <?php if($rating >= 5){echo 'filled'.' '.$rating_num_clr;}?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                        						</div>
                    						</div>
                                        </li>
                                    <?php } ?>



									<li class="md-exp">
									    <?php echo $tagline_text; ?>
									</li>
									<?php
									endif;
                                        $listingExtraOptions = get_post_meta(get_the_ID(), 'mp_listing_extra_fields', true);
                                        $virtual_consult     = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_virtual_consult', true);
                                        $certified_doctor    = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
                                        $online_prescription = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_online_prescription', true);
                                        $taking_new_patient  = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_taking_new_patient', true);
                                    if ($taking_new_patient == 'Yes') : ?>
									    <li><i class="fa fa-check-circle" aria-hidden="true"></i> <?php esc_html_e('Taking New Patients', 'medicalpro'); ?></li>
									<?php endif;
									if ($virtual_consult == 'Yes') : ?>
									    <li><i class="fa fa-microchip" aria-hidden="true"></i> <?php esc_html_e('Video Consultation', 'medicalpro'); ?></li>
                                    <?php endif; ?>

								<?php

								if (mp_get_listing_status(get_the_ID()) == 'opened'){
								    ?><li style="font: normal normal 400 13px/20px Lato;color: #18DEC5;"><i class="fa fa-calendar" aria-hidden="true" style="color: #18DEC5;margin-right: 7px;"></i> <?php esc_html_e('Available Today', 'medicalpro'); ?></li><?php
								}

								?>

								</ul>
								<div class="mp-profile-location-book-outer">
									<a href="<?php echo get_permalink(get_the_ID());?>" class="mp-profile-location-book"> <?php echo esc_html_e('Book Appoinment', 'medicalpro'); ?></a>

								</div>
								<?php
								$openStatus = listingpro_check_time(get_the_ID());
								$cats = get_the_terms( get_the_ID(), 'location' );

								if(!empty($openStatus) || !empty($cats)){
								?>
									<div class="md-grid-box-bottom clearfix">
										<div class="">
											<div class="show">
												<?php
													$cats = get_the_terms( get_the_ID(), 'location' );
													if(!empty($cats)){

														foreach ( $cats as $cat ) {
															$term_link = get_term_link( $cat );
															echo '
															<a href="'.$term_link.'">
																<i class="fa fa-map-marker" aria-hidden="true"></i> '.$cat->name.'
															</a>';

															break; // For Only First One Location To Show
														}
													}

												?>
											</div>
											<?php if(!empty($gAddress)) { ?>
												<div class="hide">

													<span class="text gaddress" title="<?php echo $gAddress; ?>"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $gAddress; ?></span>
												</div>
											<?php } ?>
											<?php if(!empty($phone)) { ?>

													<input type="hidden" id="phone" value="<?php echo $phone; ?>">

											<?php } ?>
										</div>

										<div class="clearfix"></div>
									</div>

								<?php } ?>
							</div>
						</div>
					</div>

					<?php get_template_part('templates/preview'); ?>

				<?php
					if($postGridCount%$postGridnumber == 0){
						echo '<div class="clearfix"></div>';
					}
?>