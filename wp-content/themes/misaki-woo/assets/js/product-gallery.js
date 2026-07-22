/**
 * Galería de compra — miniaturas y cambio de imagen al elegir variación.
 */
(function ($) {
    'use strict';

    function setMainImage(mainImg, url, srcset, alt) {
        if (!mainImg || !url) {
            return;
        }

        mainImg.setAttribute('src', url);

        if (srcset) {
            mainImg.setAttribute('srcset', srcset);
        } else {
            mainImg.removeAttribute('srcset');
        }

        if (alt) {
            mainImg.setAttribute('alt', alt);
        }
    }

    function clearActiveThumbs(gallery) {
        gallery.querySelectorAll('[data-gallery-thumb]').forEach(function (item) {
            item.classList.remove('is-active');
            item.setAttribute('aria-pressed', 'false');
        });
    }

    document.querySelectorAll('[data-product-gallery]').forEach(function (gallery) {
        const mainImg = gallery.querySelector('[data-gallery-main]');
        const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');

        if (!mainImg) {
            return;
        }

        gallery.misakiOriginalImage = {
            src: mainImg.getAttribute('src') || '',
            srcset: mainImg.getAttribute('srcset') || '',
            alt: mainImg.getAttribute('alt') || '',
        };

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                const fullUrl = thumb.getAttribute('data-full-url');
                const fullAlt = thumb.getAttribute('data-full-alt');

                if (!fullUrl) {
                    return;
                }

                setMainImage(mainImg, fullUrl, '', fullAlt);

                thumbs.forEach(function (item) {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-pressed', 'false');
                });

                thumb.classList.add('is-active');
                thumb.setAttribute('aria-pressed', 'true');
            });
        });
    });

    if (typeof $ === 'undefined') {
        return;
    }

    $('.variations_form').each(function () {
        const $form = $(this);
        const gallery = document.querySelector('[data-product-gallery]');
        const mainImg = gallery ? gallery.querySelector('[data-gallery-main]') : null;

        if (!gallery || !mainImg || !gallery.misakiOriginalImage) {
            return;
        }

        $form.on('found_variation', function (event, variation) {
            if (!variation || !variation.image || !variation.image.src) {
                return;
            }

            setMainImage(
                mainImg,
                variation.image.full_src || variation.image.src,
                variation.image.srcset || '',
                variation.image.alt || variation.image.title || ''
            );

            clearActiveThumbs(gallery);
        });

        $form.on('reset_data hide_variation', function () {
            const original = gallery.misakiOriginalImage;

            setMainImage(mainImg, original.src, original.srcset, original.alt);
            clearActiveThumbs(gallery);

            const firstThumb = gallery.querySelector('[data-gallery-thumb]');

            if (firstThumb) {
                firstThumb.classList.add('is-active');
                firstThumb.setAttribute('aria-pressed', 'true');
            }
        });
    });
})(jQuery);
