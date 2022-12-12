<?php
add_action('medicalpro-insurance_add_form_fields', 'medicalpro_insurance_add_image', 10, 2);
function medicalpro_insurance_add_image($term) {
    $insurance_meta = Array(
        array(
            'name' => esc_html__('Insurance Logo', 'medicalpro'),
            'id' => 'medpro_insurance_image',
            'type' => 'file',
            'value' => '',
            'desc' => ''
        ),
    );
    foreach ($insurance_meta as $meta) {
        call_user_func('settings_' . $meta['type'], $meta);
    }
}

add_action('medicalpro-insurance_edit_form_fields', 'medicalpro_insurance_edit_image', 10);
function medicalpro_insurance_edit_image($term) {
    $insurance_meta = Array(
        Array(
            'name' => esc_html__('Insurance Logo', 'medicalpro'),
            'id' => 'medpro_insurance_image',
            'type' => 'file',
            'value' => '',
            'desc' => ''
        ),
    );
    foreach ($insurance_meta as $meta) {
        $value = listingpro_get_term_meta($term->term_id, $meta['id']);
        $meta2 = Array(
            'name' => $meta['name'],
            'id' => $meta['id'],
            'type' => $meta['type'],
            'value' => $value,
            'desc' => $meta['desc']);
        call_user_func('settings_' . $meta['type'], $meta2);
    }
}



add_action('edited_medicalpro-insurance', 'medicalpro_insurance_save_image');
add_action('create_medicalpro-insurance', 'medicalpro_insurance_save_image');
function medicalpro_insurance_save_image($term_id) {
    if (isset($_POST['medpro_insurance_image'])) {
        $term_image = $_POST['medpro_insurance_image'];
        if ($term_image) {
            update_term_meta($term_id, 'medpro_insurance_image', $term_image);
        }
    }
}


