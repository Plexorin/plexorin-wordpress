<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function plexorin_admin_menu() {
    add_menu_page(
        'Plexorin', // Page title
        'Plexorin', // Menu title
        'manage_options', // Capability
        'plexorin-settings', // Menu slug
        'plexorin_settings_page', // Callback function
        'dashicons-admin-generic', // Icon URL
        25 // Position
    );
}
add_action('admin_menu', 'plexorin_admin_menu');

add_action('admin_enqueue_scripts', 'plugin_name_enqueue_admin_scripts');
function plugin_name_enqueue_admin_scripts($hook) {
    // Sadece eklentiye özgü ayar sayfasında yüklenmesini sağlamak
    if ($hook != 'toplevel_page_plexorin-settings') {
        return;
    }
    
    // JS dosyasını ekleyin
    wp_enqueue_script('plugin-custom-js', plugin_dir_url(__FILE__) . '../js/main.js', array('jquery'), '1.0', true);

    // CSS dosyasını ekleyin
    wp_enqueue_style('plugin-custom-css', plugin_dir_url(__FILE__) . '../css/style.css', array(), '1.0');
}

function plexorin_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="wrap">
        <h1>Plexorin Sosyal Medya Otomatik Paylaşım Aracı Ayarları</h1>
        <div class="settings-container">
            <div class="settings-form">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('plexorin_settings_group');
                    do_settings_sections('plexorin-settings');
                    submit_button('Kaydet', 'primary', 'save_changes');
                    ?>
                </form>
            </div>
            <div class="preview-section">
                <div class="preview">
                    <h2>Twitter Önizleme</h2>
                    <p style="font-size:12px; color: red">Twitter için 300 karakter sınırı vardır, içeriklerin sadece ilk 300 karakteri paylaşılacaktır!</p>
                    <hr>
                    <div id="twitter-preview-content">
                        <p class="content-title">(Haber Başlığı) Plexorin ile İçerikleriniz Sosyal Medyada Otomatik Olarak Paylaşılsın!</p>
                        <p class="content-link">(Haber Linki) https://plexorin.com/tr/</p>
                        <p class="content-hashtag">(Hashtagler) #plexorin #sosyalmedyapaylasimi #sosyalmedya</p>
                        <img class="content-url" src="https://plexorin.com/assets/imgs/default.webp" style="width: 100%">
                    </div>
                </div>
                <div class="preview">
                    <h2>Facebook Önizleme</h2>
                    <hr>
                    <div id="facebook-preview-content">
                        <p class="content-title">(Haber Başlığı) Plexorin ile İçerikleriniz Sosyal Medyada Otomatik Olarak Paylaşılsın!</p>
                        <p class="content-description">(Haber Açıklaması) Sosyal medya hesaplarınızı Plexorin'e üye olarak bağlayın ardından sizin için oluşturulan API anahtarını eklenti ayarlarından ekleyin. Plexorin hesabınızda seçeceğiniz hesaplarda paylaşımlar otomatik olarak yapılacaktır.</p>
                        <p class="content-link">(Haber Linki) https://plexorin.com/tr/</p>
                        <p class="content-hashtag">(Hashtagler)#plexorin #sosyalmedyapaylasimi #sosyalmedya</p>
                        <img class="content-url" src="https://plexorin.com/assets/imgs/default.webp" style="width: 100%">
                    </div>
                </div>
                <div class="preview">
                    <h2>Instagram Önizleme</h2>
                    <hr>
                    <div id="instagram-preview-content">
                        <p class="content-title">(Haber Başlığı) Plexorin ile İçerikleriniz Sosyal Medyada Otomatik Olarak Paylaşılsın!</p>
                        <p class="content-description">(Haber Açıklaması) Sosyal medya hesaplarınızı Plexorin'e üye olarak bağlayın ardından sizin için oluşturulan API anahtarını eklenti ayarlarından ekleyin. Plexorin hesabınızda seçeceğiniz hesaplarda paylaşımlar otomatik olarak yapılacaktır.</p>
                        <p class="content-link">(Haber Linki) https://plexorin.com/tr/</p>
                        <p class="content-hashtag">(Hashtagler) #plexorin #sosyalmedyapaylasimi #sosyalmedya</p>
                        <img class="content-url" src="https://plexorin.com/assets/img/default.webp" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function plexorin_register_settings() {
    register_setting('plexorin_settings_group', 'plexorin_settings', 'plexorin_sanitize_settings');

    add_settings_section('plexorin_main_section', '', null, 'plexorin-settings');

    add_settings_field('plexorin_api_key', 'API Anahtarı', 'plexorin_api_key_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_users', 'Kullanıcıları Seç', 'plexorin_users_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_categories', 'Kategorileri Seç', 'plexorin_categories_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_default_title', 'Varsayılan Başlık Formatı', 'plexorin_default_title_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_default_description', 'Varsayılan Açıklama Formatı', 'plexorin_default_description_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_default_hashtags', 'Varsayılan Hashtag Formatı', 'plexorin_default_hashtags_callback', 'plexorin-settings', 'plexorin_main_section');
    add_settings_field('plexorin_default_image', 'Varsayılan Kapak Resmi', 'plexorin_default_image_callback', 'plexorin-settings', 'plexorin_main_section');
}
add_action('admin_init', 'plexorin_register_settings');

function plexorin_api_key_callback() {
    $options = get_option('plexorin_settings');
    echo '<input type="text" id="plexorin_api_key" name="plexorin_settings[api_key]" value="' . esc_attr($options['api_key']) . '" placeholder="API Anahtarınızı Giriniz">';
}

function plexorin_users_callback() {
    $options = get_option('plexorin_settings');
    $selected_users = $options['users'] ?? array();
    $users = get_users();
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Paylaşımı yapılacak kullanıcıları seçin:', 'plexorin'); ?></p>
        <?php foreach ($users as $user) : ?>
            <label>
                <input type="checkbox" name="plexorin_settings[users][]" value="<?php echo esc_attr($user->ID); ?>" <?php checked(in_array($user->ID, $selected_users)); ?>>
                <?php echo esc_html($user->display_name); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <?php
}

function plexorin_categories_callback() {
    $options = get_option('plexorin_settings');
    $selected_categories = $options['categories'] ?? array();
    $categories = get_categories(array('hide_empty' => false));
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Paylaşımı yapılacak kategorileri seçin:', 'plexorin'); ?></p>
        <?php foreach ($categories as $category) : ?>
            <label>
                <input type="checkbox" name="plexorin_settings[categories][]" value="<?php echo esc_attr($category->term_id); ?>" <?php checked(in_array($category->term_id, $selected_categories)); ?>>
                <?php echo esc_html($category->name); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <?php
}

function plexorin_default_title_callback() {
    $options = get_option('plexorin_settings');
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Varsayılan başlık formatını ekleyin. Geçerli değişken: {post_title}', 'plexorin'); ?></p>
        <br>
        <input id="title-input" style="width: 100%" type="text" name="plexorin_settings[default_title]" value="<?php echo !empty($options['default_title']) ? esc_attr($options['default_title']) : esc_attr('{post_title}'); ?>" placeholder="<?php esc_attr_e('Örnek: {post_title} - Tamamını Oku!', 'plexorin'); ?>">
    </div>
    <?php
}

function plexorin_default_description_callback() {
    $options = get_option('plexorin_settings');
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Varsayılan açıklama formatını ekleyin. Geçerli değişkenler: {post_title}, {post_description}, {post_link}, {hashtags}', 'plexorin'); ?></p>
        <br>
        <textarea id="description-textarea" style="width: 100%" name="plexorin_settings[default_description]" rows="7" placeholder="<?php esc_attr_e('Örnek: Bu gönderi {post_title} hakkında. Devamını okumak için linke tıklayın: {post_link}', 'plexorin'); ?>"><?php echo !empty($options['default_description']) ? esc_textarea($options['default_description']) : esc_textarea('{post_description}
        
{post-link}
        
{hashtags}'); ?></textarea>
    </div>
    <?php
}

function plexorin_default_hashtags_callback() {
    $options = get_option('plexorin_settings');
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Varsayılan hashtagleri ekleyin. Kullanılabilmesi için açıklama kısmında {hashtags} değişkenini kullanmalısınız.', 'plexorin'); ?></p>
        <br>
        <input id="hashtag-input" style="width: 100%" type="text" name="plexorin_settings[default_hashtags]" value="<?php echo esc_attr($options['default_hashtags'] ?? ''); ?>" placeholder="<?php esc_attr_e('Örnek: #plexorin #sosyalmedyapaylasimi #sosyalmedya', 'plexorin'); ?>">
    </div>
    <?php
}

function plexorin_default_image_callback() {
    $options = get_option('plexorin_settings');
    $image_id = $options['default_image'];
    $image_url = $image_id ? wp_get_attachment_url($image_id) : 'https://plexorin.com/assets/imgs/default.webp';
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Bir gönderinin öne çıkan görseli yoksa kullanılacak varsayılan öne çıkan görselin linkini girin veya medya kitaplığından bir görsel seçin.', 'plexorin'); ?></p>
        <input type="hidden" id="plexorin_default_image" name="plexorin_settings[default_image]" value="<?php echo esc_attr($image_id); ?>">
        <img id="plexorin_default_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; display: <?php echo $image_url ? 'block' : 'none'; ?>;" />
        <br>
        <button type="button" class="button" id="plexorin_default_image_button"><?php esc_html_e('Resim Seç', 'plexorin'); ?></button>
        <button type="button" class="button" id="plexorin_default_image_remove" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>;"><?php esc_html_e('Resmi Sil', 'plexorin'); ?></button>
    </div>
    <?php
}


function plexorin_sanitize_settings($settings) {
    $settings['api_key'] = sanitize_text_field($settings['api_key']);
    $settings['default_title'] = sanitize_text_field($settings['default_title']);
    $settings['default_description'] = sanitize_textarea_field($settings['default_description']);
    $settings['default_image'] = absint($settings['default_image']);
    if (isset($settings['users'])) {
        $settings['users'] = array_map('absint', $settings['users']);
    }
    if (isset($settings['categories'])) {
        $settings['categories'] = array_map('absint', $settings['categories']);
    }
    return $settings;
}
