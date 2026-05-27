(function () {
    'use strict';

    var body = document.body;
    var drawer = document.getElementById('site-drawer');
    var overlay = document.getElementById('site-drawer-overlay');
    var toggle = document.querySelector('.site-header__menu-toggle');
    var closeBtn = document.querySelector('.site-drawer__close');
    var scrollThreshold = 8;

    if (!drawer || !toggle) {
        return;
    }

    function updateHeaderState() {
        body.classList.toggle('misaki-header-scrolled', window.scrollY > scrollThreshold);
    }

    function setOpen(open) {
        drawer.classList.toggle('is-open', open);
        if (overlay) {
            overlay.classList.toggle('is-visible', open);
            overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
        drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
        body.classList.toggle('misaki-drawer-open', open);
    }

    function openDrawer() {
        setOpen(true);
    }

    function closeDrawer() {
        setOpen(false);
        toggle.focus();
    }

    toggle.addEventListener('click', function () {
        if (drawer.classList.contains('is-open')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }

    if (overlay) {
        overlay.addEventListener('click', closeDrawer);
    }

    drawer.addEventListener('click', function (event) {
        const link = event.target.closest('a[href*="#"]');

        if (link) {
            closeDrawer();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
            closeDrawer();
        }
    });

    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });
})();
