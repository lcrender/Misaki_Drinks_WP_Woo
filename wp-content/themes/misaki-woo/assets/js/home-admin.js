(function ($) {
    'use strict';

    function openMediaFrame(targetId, previewId, mediaType) {
        const frame = wp.media({
            title: mediaType === 'video' ? 'Elegir video' : 'Elegir imagen',
            button: { text: mediaType === 'video' ? 'Usar este video' : 'Usar esta imagen' },
            multiple: false,
            library: { type: mediaType === 'video' ? 'video' : 'image' },
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            const url = attachment.url;

            $('#' + targetId).val(attachment.id);

            if (mediaType === 'video') {
                $('#' + previewId).html(
                    $('<video>', {
                        src: url,
                        controls: true,
                        muted: true,
                        playsinline: true,
                        css: { maxWidth: '240px', height: 'auto' },
                    })
                );
                return;
            }

            const imageUrl = attachment.sizes && attachment.sizes.medium
                ? attachment.sizes.medium.url
                : url;

            $('#' + previewId).html(
                $('<img>', {
                    src: imageUrl,
                    alt: '',
                    css: { maxWidth: '240px', height: 'auto' },
                })
            );
        });

        frame.open();
    }

    $(document).on('click', '.misaki-page-media-select', function (event) {
        event.preventDefault();
        const mediaType = $(this).hasClass('misaki-page-media-select--video') ? 'video' : 'image';
        openMediaFrame($(this).data('target'), $(this).data('preview'), mediaType);
    });

    $(document).on('click', '.misaki-page-media-remove', function (event) {
        event.preventDefault();
        $('#' + $(this).data('target')).val('');
        $('#' + $(this).data('preview')).empty();
    });

    $(document).on('change', 'input[name="misaki_home_hero_type"]', function () {
        const type = $('input[name="misaki_home_hero_type"]:checked').val();
        const showVideo = type === 'video';

        $('.misaki-home-hero-panel--video').prop('hidden', !showVideo);
        $('.misaki-home-hero-panel--image').prop('hidden', showVideo);
    });

    function nextIndex($list, rowSelector) {
        let max = -1;

        $list.find(rowSelector).each(function () {
            const index = parseInt($(this).attr('data-index'), 10);

            if (!Number.isNaN(index) && index > max) {
                max = index;
            }
        });

        return max + 1;
    }

    function replaceIndexTokens($html, index) {
        return $html
            .replace(/data-index="999"/g, 'data-index="' + index + '"')
            .replace(/\[999\]/g, '[' + index + ']')
            .replace(/_999/g, '_' + index)
            .replace(/#1000/g, '#' + (index + 1));
    }

    $('#misaki-home-add-product').on('click', function (event) {
        event.preventDefault();

        const $list = $('#misaki-home-products-list');
        const $template = $('#misaki-home-product-template');
        const index = nextIndex($list, '.misaki-home-product-row');
        const html = replaceIndexTokens($template.html(), index);

        $list.append(html);
    });

    $(document).on('click', '.misaki-home-remove-row', function (event) {
        event.preventDefault();
        $(this).closest('.misaki-home-product-row').remove();
    });
})(jQuery);
