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

function plexorin_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="wrap">
        <style>
            .settings-container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
            .settings-form {
                flex: 1 1 70%;
                margin-right: 20px;
            }
            .preview-section {
                display: flex;
                flex: 1 1 30%;
                flex-direction: column;
            }
            .preview {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }
            .plexorin-settings input[type="text"], .plexorin-settings textarea {
                width: 100%;
                max-width: 600px;
            }
            .plexorin-settings label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .plexorin-settings p {
                margin-bottom: 10px;
            }
            .disabled {
                pointer-events: none;
                opacity: 0.5;
            }
        </style>
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
                <div class="preview">
                    <h2>Twitter Önizleme</h2>
                    <p style="font-size:12px; color: red">Twitter için 300 karakter sınırı vardır, içeriklerin sadece ilk 300 karakteri paylaşılacaktır!</p>
                    <hr>
                    <div id="twitter-preview-content">
                        <p class="content-title">(Haber Başlığı) Plexorin ile İçerikleriniz Sosyal Medyada Otomatik Olarak Paylaşılsın!</p>
                        <p class="content-link">(Haber Linki) https://plexorin.com/tr/</p>
                        <p class="content-hashtag">(Hashtagler) #plexorin #sosyalmedyapaylasimi #sosyalmedya</p>
                        <img class="content-url" src="https://plexorin.com/assets/img/default.webp" style="width: 100%">
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
                        <img class="content-url" src="https://plexorin.com/assets/img/default.webp" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updatePreview = function() {
                var title = document.getElementById('title-input').value;
                var description = document.getElementById('description-textarea').value;
                var hashtag = document.getElementById('hashtag-input').value;

                title = title.replace('{post_title}', '[Haber Başlığı]');
                document.querySelectorAll('.content-title').forEach(function(p) {
                    p.textContent = title;
                });

                description = description.replace('{post_title}', '[Haber Başlığı]');
                description = description.replace('{post_description}', '[Haber Açıklaması]');
                description = description.replace('{post_link}', '[Haber Linki]');
                description = description.replace('{hashtags}', '[Hashtagler]');
                description = description.replace(/\n/g, '<br>');
                document.querySelectorAll('.content-description').forEach(function(p) {
                    p.innerHTML = description;
                });

                document.querySelectorAll('.content-hashtag').forEach(function(p) {
                    p.textContent = hashtag;
                });
            };

            document.getElementById('title-input').addEventListener('input', updatePreview);
            document.getElementById('description-textarea').addEventListener('input', updatePreview);
            document.getElementById('hashtag-input').addEventListener('input', updatePreview);
        });
        </script>
    <script>
    jQuery(document).ready(function($) {
        function checkApiKey() {
            var apiKey = $('#plexorin_api_key').val();
            if (!apiKey) {
                $('.api-dependent').addClass('disabled');
                return;
            }
            $.ajax({
                url: 'https://plexorin.com/hub/operations/api-verify-key',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                data: JSON.stringify({ api_key: apiKey }),
                success: function(response) {
                    if (response.valid) {
                        $('.api-dependent').removeClass('disabled');
                    } else {
                        $('.api-dependent').addClass('disabled');
                    }
                },
                error: function() {
                    $('.api-dependent').addClass('disabled');
                }
            });
        }

        $('#plexorin_api_key').on('change', checkApiKey);
        checkApiKey();
    });

    </script>
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
    $image_url = $image_id ? wp_get_attachment_url($image_id) : 'https://plexorin.com/assets/img/default.webp';
    ?>
    <div class="api-dependent">
        <p><?php esc_html_e('Bir gönderinin öne çıkan görseli yoksa kullanılacak varsayılan öne çıkan görselin linkini girin veya medya kitaplığından bir görsel seçin.', 'plexorin'); ?></p>
        <input type="hidden" id="plexorin_default_image" name="plexorin_settings[default_image]" value="<?php echo esc_attr($image_id); ?>">
        <img id="plexorin_default_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; display: <?php echo $image_url ? 'block' : 'none'; ?>;" />
        <br>
        <button type="button" class="button" id="plexorin_default_image_button"><?php esc_html_e('Resim Seç', 'plexorin'); ?></button>
        <button type="button" class="button" id="plexorin_default_image_remove" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>;"><?php esc_html_e('Resmi Sil', 'plexorin'); ?></button>
    </div>
    <script type="text/javascript">
        var defaultImageUrl = "<?php echo esc_url($image_url); ?>";
        document.addEventListener('DOMContentLoaded', function() {
            var images = document.querySelectorAll('.content-url');
            images.forEach(function(img) {
                img.src = defaultImageUrl;
            });

            var frame;
            document.getElementById('plexorin_default_image_button').addEventListener('click', function(e) {
                e.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Select or Upload Media',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    document.getElementById('plexorin_default_image').value = attachment.id;
                    var imageUrl = attachment.url;
                    document.getElementById('plexorin_default_image_preview').src = imageUrl;
                    document.getElementById('plexorin_default_image_preview').style.display = 'block';
                    document.getElementById('plexorin_default_image_remove').style.display = 'inline-block';

                    // Update the preview images
                    images.forEach(function(img) {
                        img.src = imageUrl;
                    });
                });

                frame.open();
            });

            document.getElementById('plexorin_default_image_remove').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('plexorin_default_image').value = '';
                document.getElementById('plexorin_default_image_preview').style.display = 'none';
                this.style.display = 'none';

                // Reset the preview images to default
                images.forEach(function(img) {
                    img.src = defaultImageUrl;
                });
            });
        });
    </script>
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
