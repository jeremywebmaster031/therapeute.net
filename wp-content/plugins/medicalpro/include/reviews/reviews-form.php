<?php
if (!function_exists('medicalpro_get_reviews_form')) {
	function medicalpro_get_reviews_form($postid)
	{
		if (class_exists('ListingReviews')) {

			global $listingpro_options;
			$listing_mobile_view    =   $listingpro_options['single_listing_mobile_view'];
			$lp_Reviews_OPT = $listingpro_options['lp_review_submit_options'];
			$gSiteKey = '';
			$gSiteKey = lp_theme_option('lp_recaptcha_site_key');
			$enableCaptcha = lp_check_receptcha('lp_recaptcha_reviews');
			$privacy_policy = $listingpro_options['payment_terms_condition'];
			$privacy_review = $listingpro_options['listingpro_privacy_review'];

			$lp_images_count = '555';
			$lp_images_size = '999999999999999999999999999999999999999999999999999';
			$lp_imagecount_notice = '';
			$lp_imagesize_notice = '';
			if (lp_theme_option('lp_listing_reviews_images_count_switch') == 'yes') {
				$lp_images_count = lp_theme_option('lp_listing_reviews_images_counter');
				$lp_imagecount_notice = esc_html__("Max. allowed images are ", 'medicalpro');
				$lp_imagecount_notice .= $lp_images_count;
			}
			if (lp_theme_option('lp_listing_reviews_images_size_switch') == 'yes') {
				$lp_images_size = lp_theme_option('lp_listing_reviews_images_sizes');
				$lp_imagesize_notice = esc_html__('Max. allowed images size is ', 'medicalpro');
				$lp_imagesize_notice .= $lp_images_size . esc_html__(' Mb', 'medicalpro');
				$lp_images_size = $lp_images_size * 1000000;
			}
			$enableUsernameField = lp_theme_option('lp_register_username');

			$lp_multi_rating_state    	=   $listingpro_options['lp_multirating_switch'];
			if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
				$lp_multi_rating_fields =   get_listing_multi_ratings_fields($postid);
			}
			$lp_detail_page_styles  =   $listingpro_options['lp_detail_page_styles'];
			$multi_col_class    =   'col-md-6';
			if ($lp_detail_page_styles == 'lp_detail_page_styles5') {
				$multi_col_class    =   'col-md-3';
			}
			if (is_user_logged_in()) {

?>
				<div class="" id="review-section">

					<div class="mp-review-form-average-rate-container">
						<div class="mp-review-form-average-rate">
							<h1><?php echo esc_html__('0.0', 'medicalpro'); ?></h1>
						</div>
						<div class="mp-review-form-average-rate-des">
							<div class="mp-experiences-content-overall-content-profile-rating-stars">
								<div class="mp-experiences-content-overall-content-profile-rating-stars">
									<i class="fa fa-star mp-fa-star-num mp-fa-star-num-one"></i>
									<i class="fa fa-star mp-fa-star-num mp-fa-star-num-two"></i>
									<i class="fa fa-star mp-fa-star-num mp-fa-star-num-three"></i>
									<i class="fa fa-star mp-fa-star-num mp-fa-star-num-four"></i>
									<i class="fa fa-star mp-fa-star-num mp-fa-star-num-five"></i>
								</div>
							</div>
							<div class="mp-experiences-content-overall-content-profile-rating-detail">
								<p><?php echo esc_html__('Based on your meeting', 'medicalpro'); ?></p>
							</div>
						</div>
					</div>

					<form data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" data-multi-rating="<?php echo $lp_multi_rating_state; ?>" id="rewies_form" name="rewies_form" action="" method="post" enctype="multipart/form-data" data-imgcount="<?php echo $lp_images_count; ?>" data-imgsize="<?php echo $lp_images_size; ?>" data-countnotice="<?php echo $lp_imagecount_notice; ?>" data-sizenotice="<?php echo $lp_imagesize_notice; ?>">
						<?php
						if ($lp_multi_rating_state == 1 && is_array($lp_multi_rating_fields) && !empty($lp_multi_rating_fields)) {
							echo '<div class="row lp-multi-rating-ui-wrap">';
							$lp_rating_field_counter	=	1;
							//new code 1.3
							$switch  = get_option('lp_multirating_switch');
							if ($switch == 1 && !empty($switch)) {
								$multi_rating_fileds = $lp_multi_rating_fields;
							} else {
								$multi_rating_fileds = $lp_multi_rating_fields['default'];
							}

							foreach ($multi_rating_fileds as $k => $lp_multi_rating_field)
							//End new code 1.3
							{
						?>
								<div class="mp-add-new-review-form-rating clearfix  col-md-6">
									<div class="mp-add-new-review-form-rating-single padding-0 margin-bottom-40">
										<div class="list-style-none form-review-stars text-left">
											<div class="mp-add-new-review-form-rating-single-title padding-left-0">
												<p class="text-left"><?php echo esc_attr($lp_multi_rating_field); ?></p>
											</div>
											<div class="mp-add-new-review-form-rating-single-stars">
												<input type="hidden" data-mrf="<?php echo $k; ?>" id="review-rating-<?php echo $k; ?>" name="rating-<?php echo $k; ?>" class="rating-tooltip lp-multi-rating-val" data-filled="fa fa-star fa-2x" data-empty="fa fa-star fa-2x" />

											</div>
										</div>
									</div>
								</div>

							<?php
								$lp_rating_field_counter++;
							}
							echo '<div class="clearfix"></div>';
							?>
							<div class="mp-add-new-review-form-img col-md-12">
								<div class="mp-add-new-review-form-img-title">
									<p><?php esc_html_e('Select Images to upload', 'medicalpro'); ?></p>
								</div>
								<div class="mp-add-new-review-form-img-content submit-images">


									<input type="file" class="hide visibility-hidden" id="filer_input2" name="post_gallery[]" multiple="multiple" />
									<button class="mp-review-images-upload browse-imgs"><?php esc_html_e('Browse', 'medicalpro'); ?></button>
								</div>
							</div>

				</div>
			<?php
						}
			?>


			<?php
				if ($lp_multi_rating_state == 0) {
			?>

				<div class="col-md-6 padding-left-0">
					<div class="form-group margin-bottom-40">
						<p class="padding-bottom-15"><?php esc_html_e('Your Rating for this listing', 'medicalpro'); ?></p>
						<div class="sfdfdf list-style-none form-review-stars">
							<input type="hidden" id="review-rating" name="rating" class="rating-tooltip" data-filled="fa fa-star fa-2x" data-empty="fa fa-star fa-2x" />
							<div class="review-emoticons">
								<div class="review angry"><?php echo listingpro_icons('angry'); ?></div>
								<div class="review cry"><?php echo listingpro_icons('crying'); ?></div>
								<div class="review sleeping"><?php echo listingpro_icons('sleeping'); ?></div>
								<div class="review smily"><?php echo listingpro_icons('smily'); ?></div>
								<div class="review cool"><?php echo listingpro_icons('cool'); ?></div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6 pull-right padding-right-0">
					<div class="form-group submit-images">
						<label for="post_gallery submit-images"><?php esc_html_e('Select Images', 'medicalpro'); ?></label>
						<a href="#" class="browse-imgs"><?php esc_html_e('Browse', 'medicalpro'); ?></a>
						<input type="file" id="filer_input2" name="post_gallery[]" multiple="multiple" />
					</div>
				</div>
				<div class="clearfix"></div>
			<?php
				}
			?>
			<div class="mp-add-new-review-form-fields">
				<div class="row margin-bottom-20">
					<div class="col-md-12">
						<div class="form-group">
							<label for="post_title"><?php esc_html_e('Title', 'medicalpro'); ?></label>
							<input placeholder="<?php esc_html_e('Example: It was an awesome experience to be there', 'medicalpro'); ?>" type="text" id="post_title" class="form-control" name="post_title" />
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="post_description"><?php esc_html_e('Review', 'medicalpro'); ?><span class="lp-requires-filed">*</span></label>
							<textarea placeholder="<?php esc_html_e('Tip: A great review covers food, service, and ambiance. Got recommendations for your favorite dishes and drinks, or something everyone should try here? Include that too!', 'medicalpro'); ?>" id="post_description" class="form-control" rows="8" name="post_description"></textarea>
							<p class="margin-top-20 md-rec"><?php esc_html_e('Your review is recommended to be at least 140 characters long :)', 'medicalpro'); ?></p>
						</div>
					</div>
				</div>
			</div>



			<?php
				if (!empty($privacy_policy) && $privacy_review == "yes") {
			?>
				<div class="form-group lp_privacy_policy_Wrap">
					<input class="lpprivacycheckboxopt" id="reviewpolicycheck" type="checkbox" name="reviewpolicycheck" value="true">
					<label for="reviewpolicycheck"><a target="_blank" href="<?php echo get_the_permalink($privacy_policy); ?>" class="help" target="_blank"><?php echo esc_html__('I Agree', 'medicalpro'); ?></a></label>
					<div class="help-text">
						<a class="help" target="_blank"><i class="fa fa-question"></i></a>
						<div class="help-tooltip">
							<p><?php echo esc_html__('You agree & accept our Terms & Conditions for posting this review?', 'medicalpro'); ?></p>
						</div>
					</div>
				</div>
				<p class="form-submit post-reletive">
					<input name="submit_review" type="submit" id="submit" class="lp-review-btn btn-second-hover" value="<?php esc_html_e('Submit Review', 'medicalpro'); ?>" disabled>
					<input type="hidden" name="comment_post_ID" value="<?php echo $postid; ?>" id="comment_post_ID">
					<input type="hidden" name="errormessage" value="<?php esc_html_e('Please fill Email, Title, Description and Rating', 'medicalpro'); ?>">
					<span class="review_status"></span>
					<img class="loadinerSearch" width="100px" src="<?php echo get_template_directory_uri() . '/assets/images/ajax-load.gif' ?>">
				</p>
			<?php
				} else {
			?>
				<p class="form-submit post-reletive mp-add-new-review-form-submit">
					<input name="submit_review" type="submit" id="submit" class="lp-review-btn mp-add-new-review-form-submit-btn" value="<?php esc_html_e('Submit Review', 'medicalpro'); ?>">
					<input type="hidden" name="comment_post_ID" value="<?php echo $postid; ?>" id="comment_post_ID">
					<input type="hidden" name="errormessage" value="<?php esc_html_e('Please fill Email, Title, Description and Rating', 'medicalpro'); ?>">
					<span class="review_status"></span>
					<img class="loadinerSearch" width="100px" src="<?php echo get_template_directory_uri() . '/assets/images/ajax-load.gif' ?>">
				</p>
			<?php
				}
			?>


			</form>
			</div>
		<?php
			} else {
		?>
			<div class="review-formm">
				<div class="mp-review-form-average-rate-container">
					<div class="mp-review-form-average-rate">
						<h1><?php echo esc_html__('0.0', 'medicalpro'); ?></h1>
					</div>
					<div class="mp-review-form-average-rate-des">
						<div class="mp-experiences-content-overall-content-profile-rating-stars">
							<div class="mp-experiences-content-overall-content-profile-rating-stars">
								<i class="fa fa-star mp-fa-star-num mp-fa-star-num-one"></i>
								<i class="fa fa-star mp-fa-star-num mp-fa-star-num-two"></i>
								<i class="fa fa-star mp-fa-star-num mp-fa-star-num-three"></i>
								<i class="fa fa-star mp-fa-star-num mp-fa-star-num-four"></i>
								<i class="fa fa-star mp-fa-star-num mp-fa-star-num-five"></i>
							</div>
						</div>
						<div class="mp-experiences-content-overall-content-profile-rating-detail">
							<p><?php echo esc_html__('Based on your meeting', 'medicalpro'); ?></p>
						</div>
					</div>
				</div>
				<?php
				if ($lp_Reviews_OPT == "instant_sign_in") {
				?>
					<form class="" data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" data-multi-rating="<?php echo $lp_multi_rating_state; ?>" id="rewies_form" name="rewies_form" action="" method="post" enctype="multipart/form-data" data-imgcount="<?php echo $lp_images_count; ?>" data-imgsize="<?php echo $lp_images_size; ?>" data-countnotice="<?php echo $lp_imagecount_notice; ?>" data-sizenotice="<?php echo $lp_imagesize_notice; ?>">
					<?php
				} else {
					?>
						<form data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" class="reviewformwithnotice" data-multi-rating="<?php echo $lp_multi_rating_state; ?>" id="rewies_formm" name="rewies_form" action="#" method="post" enctype="multipart/form-data" data-imgcount="<?php echo $lp_images_count; ?>" data-imgsize="<?php echo $lp_images_size; ?>" data-countnotice="<?php echo $lp_imagecount_notice; ?>" data-sizenotice="<?php echo $lp_imagesize_notice; ?>">
						<?php } ?>

						<?php
						if ($lp_multi_rating_state == 1 && is_array($lp_multi_rating_fields) && !empty($lp_multi_rating_fields)) {
							echo '<div class="row lp-multi-rating-ui-wrap">';
							$lp_rating_field_counter	=	1;
							//new code 1.3
							if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
								$multi_rating_fileds = $lp_multi_rating_fields;
							} else {
								$multi_rating_fileds = $lp_multi_rating_fields['default'];
							}

							if (isset($multi_rating_fileds['default'])) {
								foreach ($multi_rating_fileds['default'] as $k => $lp_multi_rating_field) {
						?>
									<div class="mp-add-new-review-form-rating clearfix  col-md-6">
										<div class="mp-add-new-review-form-rating-single padding-0 margin-bottom-40">
											<div class="list-style-none form-review-stars text-left">
												<div class="mp-add-new-review-form-rating-single-title padding-left-0">
													<p class="text-left"><?php echo $lp_multi_rating_field; ?></p>
												</div>
												<div class="mp-add-new-review-form-rating-single-stars">
													<input type="hidden" data-mrf="<?php echo $k; ?>" id="review-rating-<?php echo $k; ?>" name="rating-<?php echo $k; ?>" class="rating-tooltip lp-multi-rating-val" data-filled="fa fa-star fa-2x" data-empty="fa fa-star fa-2x" />

												</div>
											</div>
										</div>
									</div>

								<?php
									$lp_rating_field_counter++;
								}
							} else {
								foreach ($multi_rating_fileds as $k => $lp_multi_rating_field) {

								?>
									<div class="mp-add-new-review-form-rating clearfix  col-md-6">
										<div class="mp-add-new-review-form-rating-single padding-0 margin-bottom-40">
											<div class="list-style-none form-review-stars text-left">
												<div class="mp-add-new-review-form-rating-single-title padding-left-0">
													<p class="text-left"><?php echo $lp_multi_rating_field; ?></p>
												</div>
												<div class="mp-add-new-review-form-rating-single-stars">
													<input type="hidden" data-mrf="<?php echo $k; ?>" id="review-rating-<?php echo $k; ?>" name="rating-<?php echo $k; ?>" class="rating-tooltip lp-multi-rating-val" data-filled="fa fa-star fa-2x" data-empty="fa fa-star fa-2x" />

												</div>
											</div>
										</div>
									</div>

							<?php
									$lp_rating_field_counter++;
								}
							}
							//End new code 1.3
							echo '<div class="clearfix"></div>';
							?>
							<div class="mp-add-new-review-form-img col-md-12">
								<div class="mp-add-new-review-form-img-title">
									<p><?php esc_html_e('Select Images to upload', 'medicalpro'); ?></p>
								</div>
								<div class="mp-add-new-review-form-img-content submit-images">


									<input type="file" class="hide visibility-hidden" id="filer_input2" name="post_gallery[]" multiple="multiple" />
									<button class="mp-review-images-upload browse-imgs"><?php esc_html_e('Browse', 'medicalpro'); ?></button>
								</div>
							</div>
			</div>
		<?php
						}
		?>


		<?php
				if ($lp_multi_rating_state == 0) {
		?>
			<div class="col-md-6 padding-left-0">
				<div class="form-group margin-bottom-40">
					<p class="padding-bottom-15"><?php esc_html_e('Your Rating for this listing', 'medicalpro'); ?></p>
					<input type="hidden" id="review-rating" name="rating" class="rating-tooltip" data-filled="fa fa-star fa-2x" data-empty="fa fa-star fa-2x" />
					<div class="review-emoticons">
						<div class="review angry"><?php echo listingpro_icons('angry'); ?></div>
						<div class="review cry"><?php echo listingpro_icons('crying'); ?></div>
						<div class="review sleeping"><?php echo listingpro_icons('sleeping'); ?></div>
						<div class="review smily"><?php echo listingpro_icons('smily'); ?></div>
						<div class="review cool"><?php echo listingpro_icons('cool'); ?></div>
					</div>
				</div>
			</div>
			<div class="col-md-6 pull-right padding-right-0">
				<div class="form-group submit-images">
					<label for="post_gallery submit-images"><?php esc_html_e('Select Images', 'medicalpro'); ?></label>
					<a href="#" class="browse-imgs"><?php esc_html_e('Browse', 'medicalpro'); ?></a>
					<input type="file" id="filer_input2" name="post_gallery[]" multiple="multiple" />
				</div>
			</div>
			<div class="clearfix"></div>
		<?php
				}
		?>
		<div class="mp-add-new-review-form-fields row">
			<?php

				if ($lp_Reviews_OPT == "instant_sign_in") {
					if ($enableUsernameField == true) { ?>
					<div class="form-group col-md-6">
						<label for="u_mail"><?php esc_html_e('User Name', 'medicalpro'); ?><span class="lp-requires-filed">*</span></label>
						<input type="text" placeholder="<?php esc_html_e('john', 'medicalpro'); ?>" id="lp_custom_username" class="form-control" name="lp_custom_username" />
					</div>

				<?php } ?>
				<div class="form-group <?php if ($enableUsernameField == true) { ?>col-md-6 <?php } else {
																							echo ' col-md-12 ';
																						} ?>">
					<label for="u_mail"><?php esc_html_e('Email', 'medicalpro'); ?><span class="lp-requires-filed">*</span></label>
					<input type="email" placeholder="<?php esc_html_e('you@website.com', 'medicalpro'); ?>" id="u_mail" class="form-control" name="u_mail" />
				</div>
			<?php } ?>

			<div class="form-group col-md-12">
				<label for="post_title"><?php esc_html_e('Title', 'medicalpro'); ?><span class="lp-requires-filed">*</span></label>
				<input type="text" placeholder="<?php esc_html_e('Example: It was an awesome experience to be there', 'medicalpro'); ?>" id="post_title" class="form-control" name="post_title" />
			</div>
			<div class="form-group col-md-12">
				<label for="post_description"><?php esc_html_e('Review', 'medicalpro'); ?><span class="lp-requires-filed">*</span></label>
				<textarea placeholder="<?php esc_html_e('Tip: A great review covers food, service, and ambiance. Got recommendations for your favorite dishes and drinks, or something everyone should try here? Include that too!', 'medicalpro'); ?>" id="post_description" class="form-control" rows="8" name="post_description"></textarea>
				<p class="margin-top-20 md-rec"><?php esc_html_e('Your review is recommended to be at least 140 characters long', 'medicalpro'); ?></p>
			</div>
		</div>


		<?php

				if (!empty($privacy_policy) && $privacy_review == "yes") {
		?>
			<div class="form-group lp_privacy_policy_Wrap">
				<input class="lpprivacycheckboxopt" id="reviewpolicycheck" type="checkbox" name="reviewpolicycheck" value="true">
				<label for="reviewpolicycheck"><a target="_blank" href="<?php echo get_the_permalink($privacy_policy); ?>" class="help" target="_blank"><?php echo esc_html__('I Agree', 'medicalpro'); ?></a></label>
				<div class="help-text">
					<a class="help" target="_blank"><i class="fa fa-question"></i></a>
					<div class="help-tooltip">
						<p><?php echo esc_html__('You agree & accept our Terms & Conditions for posting this review?', 'medicalpro'); ?></p>
					</div>
				</div>
			</div>


			<p class="form-submit  mp-add-new-review-form-submit">
				<?php
					if ($lp_Reviews_OPT == "sign_in") {

						$reviewDataAtts = '';
						$extraDataatts = 'data-modal="modal-3"';
				?>
					<?php if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
							$reviewDataAtts = 'data-toggle="modal" data-target="#app-view-login-popup"';
							$extraDataatts = '';
						}
					?>

					<input name="submit_review" <?php echo $reviewDataAtts; ?> type="submit" id="submit" class="lp-review-btn btn-second-hover md-trigger" <?php echo $extraDataatts; ?> value="<?php echo esc_html__('Submit Review ', 'medicalpro'); ?>" disabled>
				<?php
					} elseif ($lp_Reviews_OPT == "instant_sign_in") {
				?>
					<input name="submit_review" type="submit" id="submit" class="lp-review-btn  mp-add-new-review-form-submit-btn" value="<?php echo esc_html__('Signup & Submit Review ', 'medicalpro'); ?>" disabled>
				<?php } ?>
				<span class="review_status"></span>
				<img class="loadinerSearch" width="100px" src="<?php echo get_template_directory_uri() . '/assets/images/ajax-load.gif' ?>">
			</p>
		<?php
				} else {
		?>
			<p class="form-submit  mp-add-new-review-form-submit">
				<?php
					if ($lp_Reviews_OPT == "sign_in") {

						$reviewDataAtts = '';
						$extraDataatts = 'data-modal="modal-3"';
				?>
					<?php if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
							$reviewDataAtts = 'data-toggle="modal" data-target="#app-view-login-popup"';
							$extraDataatts = '';
						}
					?>

					<input name="submit_review" <?php echo $reviewDataAtts; ?> type="submit" id="submit" class="lp-review-btn btn-second-hover md-trigger" <?php echo $extraDataatts; ?> value="<?php echo esc_html__('Submit Review ', 'medicalpro'); ?>">
				<?php
					} elseif ($lp_Reviews_OPT == "instant_sign_in") {
				?>
					<input name="submit_review" type="submit" id="submit" class="lp-review-btn  mp-add-new-review-form-submit-btn" value="<?php echo esc_html__('Signup & Submit Review ', 'medicalpro'); ?>">
				<?php } ?>

				<span class="review_status"></span>
				<img class="loadinerSearch" width="100px" src="<?php echo get_template_directory_uri() . '/assets/images/ajax-load.gif' ?>">
			</p>
		<?php
				}
		?>
		<input type="hidden" name="errormessage" value="<?php esc_html_e('Please fill Email, Title, Description and Rating', 'medicalpro'); ?>">

		<input type="hidden" name="comment_post_ID" value="<?php echo $postid; ?>" id="comment_post_ID">


		</form>
		</div>
<?php

			}
		}
	}
}




?>