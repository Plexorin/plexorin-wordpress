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

jQuery(document).ready(function($) {
    function checkApiKey() {
        var apiKey = $('#bts_plexorin_api_key').val();
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

    $('#bts_plexorin_api_key').on('change', checkApiKey);
    checkApiKey();
});


var defaultImageUrl = document.getElementById('bts_plexorin_default_image_preview').src;
document.addEventListener('DOMContentLoaded', function() {
    var images = document.querySelectorAll('.content-url');
    images.forEach(function(img) {
        img.src = defaultImageUrl;
    });

    var frame;
    document.getElementById('bts_plexorin_default_image_button').addEventListener('click', function(e) {
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
            document.getElementById('bts_plexorin_default_image').value = attachment.id;
            var imageUrl = attachment.url;
            document.getElementById('bts_plexorin_default_image_preview').src = imageUrl;
            document.getElementById('bts_plexorin_default_image_preview').style.display = 'block';
            document.getElementById('bts_plexorin_default_image_remove').style.display = 'inline-block';

            // Update the preview images
            images.forEach(function(img) {
                img.src = imageUrl;
            });
        });

        frame.open();
    });

    document.getElementById('bts_plexorin_default_image_remove').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('bts_plexorin_default_image').value = '';
        document.getElementById('bts_plexorin_default_image_preview').style.display = 'none';
        this.style.display = 'none';

        // Reset the preview images to default
        images.forEach(function(img) {
            img.src = defaultImageUrl;
        });
    });
});
