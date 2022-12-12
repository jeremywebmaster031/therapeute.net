<?php
if (isset($_POST['create_mp_suggested_hospitals'])) {

    $key = $_POST['create_mp_suggested_hospitals_id'];
    $listId = $_POST['create_mp_suggested_hospitals_listing_id'];
    $request = get_post_meta($listId, 'mp_suggested_hospitals', true);

    if (!term_exists($request[$key]['name'], 'medicalpro-hospital')) {
        $term = wp_create_term( $request[$key]['name'], 'medicalpro-hospital' );
    }else {
        $term = get_term_by('name', $request[$key]['name'], 'medicalpro-hospital' , ARRAY_A);
    }

    $listing_hospitals = get_post_meta( $listId, 'medicalpro_listing_hospitals', true );
    if (empty($listing_hospitals) || !is_array($listing_hospitals)) $listing_hospitals = array();

    $listing_hospitals_ids = wp_get_post_terms( $listId, 'medicalpro-hospital', array( 'fields' => 'ids' ) );
    if (empty($listing_hospitals_ids) || !is_array($listing_hospitals_ids)) $listing_hospitals_ids = array();

    $listing_hospitals[$term['term_id']]['price'] = $request[$key]['price'];
    $listing_hospitals[$term['term_id']]['business_hours'] = $request[$key]['business_hours'];

    $listing_hospitals_ids[] = $term['term_id'];

    $listing_hospitals_ids = array_map('intval', $listing_hospitals_ids);
    $listing_hospitals_ids = array_unique($listing_hospitals_ids);
    $listing_hospitals_ids = wp_set_post_terms( $listId, $listing_hospitals_ids, 'medicalpro-hospital' );
    update_post_meta( $listId, 'medicalpro_listing_hospitals', $listing_hospitals );
    update_term_meta($term['term_id'], 'hospital_locations', $request[$key]['location']);
    $listingLocTax = wp_get_post_terms($listId, 'location');
    $location = get_term_meta($term['term_id'], 'hospital_locations', true);
    if (!empty($location) || $location != '' || $location != null || $location != ' ') $listingLocTax[] = $location;
    wp_set_post_terms($listId, $listingLocTax, 'location');

    unset($request[$key]);

    if ( empty($request) || !is_array($request) ) {
        delete_post_meta($listId, 'mp_suggested_hospitals');
    }else {
        update_post_meta($listId, 'mp_suggested_hospitals', $request);
    }

	$edit_link = add_query_arg(array(
        'taxonomy' => 'medicalpro-hospital',
        'tag_ID' => $term['term_id'],
        'post_type' => 'post'
    ), admin_url('term.php'));

    wp_redirect($edit_link);
    exit;
}

if (isset($_POST['delete_mp_suggested_hospitals'])) {
    $key = $_POST['delete_mp_suggested_hospitals_id'];
    $listId = $_POST['delete_mp_suggested_hospitals_listing_id'];
    $request = get_post_meta($listId, 'mp_suggested_hospitals', true);

    unset($request[$key]);

    if ( empty($request) || !is_array($request) ) {
        delete_post_meta($listId, 'mp_suggested_hospitals');
    }else {
        update_post_meta($listId, 'mp_suggested_hospitals', $request);
    }
}

?>


<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e("Suggested Hospitals Form Listing Owner's", "medicalpro") ?></h1>
</div>


<?php
$args = array(
    'post_type' => 'listing',
    'meta_query' => array(
        array(
            'key'     => 'mp_suggested_hospitals',
            'compare' => 'EXISTS',
        ),
    ),
);
$the_query = new WP_Query($args);
if ($the_query->have_posts()) :
    ?>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead>
    <tr>
    <th scope="col" class="manage-column"><?php esc_html_e("Suggested By Listing", "medicalpro") ?></th>
    <th scope="col" class="manage-column"><?php esc_html_e("Suggested Hospital Title", "medicalpro") ?></th>
    <th scope="col" class="manage-column"><?php esc_html_e("Action", "medicalpro") ?></th>
    </tr>
    </thead>
    <?php
    while ($the_query->have_posts()) :
        $the_query->the_post();
        $request = get_post_meta(get_the_ID(), 'mp_suggested_hospitals', true);
        if (is_array($request)) :
        foreach ($request as $k => $single):
            $request[$k]['viewed'] = true;
        ?>
        <tbody id="the-list">
        <tr>
            <td><?php the_title(); ?></td>
            <td><?php echo $single['name']; ?></td>
            <td>
                <form action="" method="post" style="display: inline-block;">
                    <input type="hidden" name="create_mp_suggested_hospitals_id" value="<?php echo $k; ?>">
                    <input type="hidden" name="create_mp_suggested_hospitals_listing_id" value="<?php echo get_the_ID(); ?>">
                    <input type="submit" name="create_mp_suggested_hospitals" value="<?php esc_html_e('Create And Assign Hospital', 'medicalpro'); ?>">
                </form>
                <form action="" method="post" style="display: inline-block;">
                    <input type="hidden" name="delete_mp_suggested_hospitals_id" value="<?php echo $k; ?>">
                    <input type="hidden" name="delete_mp_suggested_hospitals_listing_id" value="<?php echo get_the_ID(); ?>">
                    <input type="submit" name="delete_mp_suggested_hospitals" value="<?php esc_html_e('Reject', 'medicalpro'); ?>">
                </form>
            </td>
        </tr>
        </tbody>
        <?php
        endforeach;
        endif;
        update_post_meta(get_the_ID(), 'mp_suggested_hospitals', $request);
    endwhile;
    wp_reset_postdata();
    ?>
    <tfoot>
    <tr>
    <th scope="col" class="manage-column"><?php esc_html_e("Suggested By Listing", "medicalpro") ?></th>
    <th scope="col" class="manage-column"><?php esc_html_e("Suggested Hospital Title", "medicalpro") ?></th>
    <th scope="col" class="manage-column"><?php esc_html_e("Action", "medicalpro") ?></th>
    </tr>
    </tfoot>
    </table>
    <?php
else:
    ?><p><?php esc_html_e("No Suggested Hospitals Found.", "medicalpro") ?></p><?php
endif;