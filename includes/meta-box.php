<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function bts_plexorin_add_meta_box() {
    add_meta_box(
        'bts_plexorin_meta_box',
         __('Plexorin Post Settings', 'plexorin'),
        'bts_plexorin_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'plexorin_add_meta_box');

function bts_plexorin_meta_box_callback($post) {
    wp_nonce_field('bts_plexorin_meta_box', 'prefix_nonce');

    $cancel_share = get_post_meta($post->ID, '_bts_plexorin_cancel_share', true);
    $custom_title = get_post_meta($post->ID, '_bts_plexorin_custom_title', true);
    $custom_description = get_post_meta($post->ID, '_bts_plexorin_custom_description', true);
    $custom_image = get_post_meta($post->ID, '_bts_plexorin_custom_image', true);

    ?>
    <p>
        <label for="bts_plexorin_cancel_share">
            <input type="checkbox" name="bts_plexorin_cancel_share" id="bts_plexorin_cancel_share" value="1" <?php checked($cancel_share, '1'); ?> />
            <?php esc_html_e('Bu gönderi paylaşılmasın', 'bts_plexorin'); ?>
        </label>
    </p>
    <p>
        <label for="bts_plexorin_custom_title"><?php esc_html_e('Özel Başlık', 'bts_plexorin'); ?></label>
        <input type="text" name="bts_plexorin_custom_title" id="bts_plexorin_custom_title" value="<?php echo esc_attr($custom_title); ?>" class="widefat" />
    </p>
    <p>
        <label for="bts_plexorin_custom_description"><?php esc_html_e('Özel Açıklama', 'bts_plexorin'); ?></label>
        <textarea name="bts_plexorin_custom_description" id="bts_plexorin_custom_description" class="widefat"><?php echo esc_textarea($custom_description); ?></textarea>
    </p>
    <p>
        <label for="bts_plexorin_custom_image"><?php esc_html_e('Özel Öne Çıkan Resim', 'bts_plexorin'); ?></label>
        <input type="text" name="bts_plexorin_custom_image" id="bts_plexorin_custom_image" value="<?php echo esc_url($custom_image); ?>" class="widefat" />
    </p>
    <?php
}

function bts_plexorin_save_postdata($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['prefix_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! isset( $_POST['prefix_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['prefix_nonce'] ) ) , 'prefix_nonce' ) ){
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $cancel_share = isset($_POST['bts_plexorin_cancel_share']) ? '1' : '';
    update_post_meta($post_id, '_bts_plexorin_cancel_share', $cancel_share);

    if (isset($_POST['bts_plexorin_custom_title'])) {
        update_post_meta($post_id, '_bts_plexorin_custom_title', sanitize_text_field($_POST['bts_plexorin_custom_title']));
    }

    if (isset($_POST['bts_plexorin_custom_description'])) {
        update_post_meta($post_id, '_bts_plexorin_custom_description', sanitize_textarea_field($_POST['bts_plexorin_custom_description']));
    }

    if (isset($_POST['bts_plexorin_custom_image'])) {
        update_post_meta($post_id, '_bts_plexorin_custom_image', esc_url_raw($_POST['bts_plexorin_custom_image']));
    }
}
add_action('save_post', 'bts_plexorin_save_postdata');
