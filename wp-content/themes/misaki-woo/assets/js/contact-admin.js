(function ($) {
    'use strict';

    function openMediaFrame(targetId, previewId) {
        const frame = wp.media({
            title: 'Elegir imagen',
            button: { text: 'Usar esta imagen' },
            multiple: false,
            library: { type: 'image' },
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            const url = attachment.sizes && attachment.sizes.medium
                ? attachment.sizes.medium.url
                : attachment.url;

            $('#' + targetId).val(attachment.id);
            $('#' + previewId).html(
                $('<img>', {
                    src: url,
                    alt: '',
                    css: { maxWidth: '240px', height: 'auto' },
                })
            );
        });

        frame.open();
    }

    $(document).on('click', '.misaki-page-media-select', function (event) {
        event.preventDefault();
        openMediaFrame($(this).data('target'), $(this).data('preview'));
    });

    $(document).on('click', '.misaki-page-media-remove', function (event) {
        event.preventDefault();
        $('#' + $(this).data('target')).val('');
        $('#' + $(this).data('preview')).empty();
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
            .replace(/#1000/g, '#' + (index + 1));
    }

    $('#misaki-contact-add-team').on('click', function (event) {
        event.preventDefault();

        const $list = $('#misaki-contact-team-list');
        const $template = $('#misaki-contact-team-template');
        const index = nextIndex($list, '.misaki-contact-team-row');
        const html = replaceIndexTokens($template.html(), index);

        $list.append(html);
    });

    $('#misaki-contact-add-country').on('click', function (event) {
        event.preventDefault();

        const $list = $('#misaki-contact-countries-list');
        const $template = $('#misaki-contact-country-template');
        const index = nextIndex($list, '.misaki-contact-country-row');
        const html = replaceIndexTokens($template.html(), index);

        $list.append(html);
    });

    $(document).on('click', '.misaki-contact-remove-row', function (event) {
        event.preventDefault();
        $(this).closest('.misaki-contact-team-row, .misaki-contact-country-row').remove();
    });
})(jQuery);
