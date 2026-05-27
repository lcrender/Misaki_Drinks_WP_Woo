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

    $(document).on('click', '.misaki-services-media-select', function (event) {
        event.preventDefault();
        openMediaFrame($(this).data('target'), $(this).data('preview'));
    });

    $(document).on('click', '.misaki-services-media-remove', function (event) {
        event.preventDefault();
        $('#' + $(this).data('target')).val('');
        $('#' + $(this).data('preview')).empty();
    });
})(jQuery);
