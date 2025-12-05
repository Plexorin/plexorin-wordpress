<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function bts_plexorin_send_post_to_plexorin($post_ID, $post, $update) {
    if ($post->post_status != 'publish') {
        return;
    }

    $options = get_option('bts_plexorin_settings');

    // Check user and category
    if (!in_array($post->post_author, $options['users'])) {
        return;
    }
    $post_categories = wp_get_post_categories($post_ID);
    if (!array_intersect($options['categories'], $post_categories)) {
        return;
    }

    // Check if share is canceled
    $cancel_share = get_post_meta($post_ID, '_bts_plexorin_cancel_share', true);
    if ($cancel_share == '1') {
        return;
    }

    // Get custom title from the post meta
    $custom_title = get_post_meta($post_ID, '_bts_plexorin_custom_title', true);

    // Get custom description from the post meta
    $custom_description = get_post_meta($post_ID, '_bts_plexorin_custom_description', true);

    // Get custom image from the post meta
    $custom_image = get_post_meta($post_ID, '_bts_plexorin_custom_image', true);

    // Fetch SEO description from Yoast or fallback to the post excerpt/content
    $seo_description = get_post_meta($post_ID, '_yoast_wpseo_metadesc', true);
    if (empty($seo_description)) {
        $seo_description = get_post_meta($post_ID, '_aioseo_description', true); // Check for All in One SEO
    }
    if (empty($seo_description)) {
        $seo_description = $post->post_excerpt;
    }
    if (empty($seo_description)) {
        $seo_description = wp_trim_words($post->post_content, 55, '...');
    }

    $post_url = get_permalink($post_id);

    // Determine the title
    if (!empty($custom_title)) {
        $title = str_replace('{post_title}', $post->post_title, $custom_title);
    } elseif (!empty($options['default_title'])) {
        $title = str_replace('{post_title}', $post->post_title, $options['default_title']);
    } else {
        $title = $post->post_title;
    }

    // Determine the description
    if (!empty($custom_description)) {
        $description = str_replace('{post_title}', $title, $custom_description);
        $description = str_replace('{post_description}', $seo_description, $description);
        $description = str_replace('{post_url}', $post_url, $description);
        $description = str_replace('{hashtags}', $options['default_hashtags'], $description);
    } elseif (!empty($options['default_description'])) {
        $description = str_replace('{post_title}', $title, $options['default_description']);
        $description = str_replace('{post_description}', $seo_description, $description);
        $description = str_replace('{post_url}', $post_url, $description);
        $description = str_replace('{hashtags}', $options['default_hashtags'], $description);
    } else {
        $description = $seo_description;
    }

    // Determine the image URL
    $thumbnail_id = get_post_thumbnail_id($post_ID);
    $thumbnail_url = !empty($custom_image) ? $custom_image : ($thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $options['default_image']);

    $api_key = sanitize_text_field($options['api_key']);

    // Prepare the data to be sent
    $data = array(
        'title' => sanitize_text_field($title),
        'description' => sanitize_textarea_field($description),
        'image' => esc_url_raw($thumbnail_url),
        'post_url' => esc_url_raw($post_url),
        'api_key' => $api_key
    );

    // Send the data to the specified endpoint
    $response = wp_remote_post('https://plexorin.com/api/v1/create-content', array(
        'method' => 'POST',
        'body' => wp_json_encode($data),
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    ));

    // Log the request for debugging
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log("Plexorin API request failed: $error_message");
    } else {
        error_log("Plexorin API request data: " . print_r($data, true));
    }
}
add_action('save_post', 'bts_plexorin_send_post_to_plexorin', 10, 3);
