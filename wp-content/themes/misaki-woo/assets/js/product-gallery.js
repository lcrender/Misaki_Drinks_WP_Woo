/**
 * Galería de compra — cambio de imagen principal al pulsar miniatura.
 */
(function () {
    document.querySelectorAll('[data-product-gallery]').forEach(function (gallery) {
        const mainImg = gallery.querySelector('[data-gallery-main]');
        const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');

        if (!mainImg || !thumbs.length) {
            return;
        }

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                const fullUrl = thumb.getAttribute('data-full-url');
                const fullAlt = thumb.getAttribute('data-full-alt');

                if (!fullUrl) {
                    return;
                }

                mainImg.setAttribute('src', fullUrl);
                mainImg.setAttribute('srcset', '');

                if (fullAlt) {
                    mainImg.setAttribute('alt', fullAlt);
                }

                thumbs.forEach(function (item) {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-pressed', 'false');
                });

                thumb.classList.add('is-active');
                thumb.setAttribute('aria-pressed', 'true');
            });
        });
    });
})();
