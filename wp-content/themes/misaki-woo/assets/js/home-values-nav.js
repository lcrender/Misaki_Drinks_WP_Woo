/**
 * Scroll suave a subsecciones Our Values desde We Are.
 */
(function () {
    'use strict';

    const links = document.querySelectorAll('[data-values-jump]');
    if (!links.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const extraScrollGap = 80;

    function getHeaderOffset() {
        const header = document.querySelector('.site-header');
        if (header) {
            return Math.ceil(header.getBoundingClientRect().height);
        }

        const raw = getComputedStyle(document.documentElement)
            .getPropertyValue('--misaki-header-h')
            .trim();
        const value = parseFloat(raw);

        if (!Number.isFinite(value)) {
            return 80;
        }

        if (raw.endsWith('rem')) {
            const rootSize = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
            return Math.ceil(value * rootSize);
        }

        return Math.ceil(value);
    }

    function scrollToTarget(id) {
        const target = document.getElementById(id);
        if (!target) {
            return;
        }

        const top =
            target.getBoundingClientRect().top +
            window.scrollY -
            getHeaderOffset() -
            extraScrollGap;

        window.scrollTo({
            top: Math.max(top, 0),
            behavior: prefersReducedMotion ? 'auto' : 'smooth',
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, '', '#' + id);
        }
    }

    links.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const href = link.getAttribute('href') || '';
            const id = href.startsWith('#') ? href.slice(1) : '';

            if (!id) {
                return;
            }

            event.preventDefault();
            scrollToTarget(id);
        });
    });

    function handleInitialHash() {
        const hash = window.location.hash.replace('#', '');
        if (!hash) {
            return;
        }

        const isValuesAnchor = Array.from(links).some(function (link) {
            return (link.getAttribute('href') || '') === '#' + hash;
        });

        if (!isValuesAnchor) {
            return;
        }

        window.requestAnimationFrame(function () {
            scrollToTarget(hash);
            window.setTimeout(function () {
                scrollToTarget(hash);
            }, 120);
        });
    }

    handleInitialHash();
})();
