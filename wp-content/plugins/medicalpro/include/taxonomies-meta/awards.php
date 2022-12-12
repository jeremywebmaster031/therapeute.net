<?php
function medicalpro_award_add_icon($term) {
    $insurance_meta = Array(
        Array(
            'name' => esc_html__('Awrds Icon', 'medicalpro'),
            'id' => 'medpro_award_icon',
            'type' => 'text',
            'value' => '',
            'desc' => 'Font Awesome icons from <a href="http://fontawesome.io/icons/" target="blank">FontAwesome Website</a> Icone Code should be like `fa-address-book `',
        ),
    );
    foreach ($insurance_meta as $meta) {
        call_user_func('settings_' . $meta['type'], $meta);
    }
}

add_action('medicalpro-award_add_form_fields', 'medicalpro_award_add_icon', 10, 2);

function medicalpro_award_edit_icon($term) {
    $insurance_meta = Array(
        Array(
            'name' => esc_html__('Award Icon', 'medicalpro'),
            'id' => 'medpro_award_icon',
            'type' => 'text',
            'value' => '',
            'desc' => 'Font Awesome icons from <a href="http://fontawesome.io/icons/" target="blank">FontAwesome Website</a> Icone Code should be like `fa-address-book `',
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

add_action('medicalpro-award_edit_form_fields', 'medicalpro_award_edit_icon', 10);

function medicalpro_award_save_icon($term_id) {
    if (isset($_POST['medpro_award_icon'])) {
        $term_icon = $_POST['medpro_award_icon'];
        if ($term_icon) {
            update_term_meta($term_id, 'medpro_award_icon', $term_icon);
        }
    }
}

add_action('edited_medicalpro-award', 'medicalpro_award_save_icon');
add_action('create_medicalpro-award', 'medicalpro_award_save_icon');
?>