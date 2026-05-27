/**
 * We Are — cascada fija (sticky), panel con padding, ancla About.
 */
(function () {
    const scene = document.querySelector('[data-we-are-scene]');
    if (!scene) {
        return;
    }

    const track = scene.querySelector('[data-we-are-track]');
    const panel = scene.querySelector('[data-we-are-panel]');

    if (!track || !panel) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const styles = getComputedStyle(scene);
    const topInset = parseFloat(styles.getPropertyValue('--we-are-panel-inset-top')) || 14;
    const sideInset = parseFloat(styles.getPropertyValue('--we-are-panel-inset-x')) || 9;
    const bottomInset = parseFloat(styles.getPropertyValue('--we-are-panel-inset-bottom')) || 12;
    const panelScrollRatio = 0.62;
    let ticking = false;

    function getPanelScrollRange() {
        const viewport = window.innerHeight;
        const scrollable = Math.max(track.offsetHeight - viewport, 1);

        return scrollable * panelScrollRatio;
    }

    /** Scroll donde el panel blanco queda centrado (completamente subido). */
    function getAboutScrollTop() {
        const sceneTop = scene.getBoundingClientRect().top + window.scrollY;

        return Math.round(sceneTop + getPanelScrollRange());
    }

    function applyPanelFrame(progress) {
        const clamped = Math.min(Math.max(progress, 0), 1);
        const top = topInset * clamped;
        const side = sideInset * clamped;
        const bottom = bottomInset * clamped;
        const translate = (1 - clamped) * 100;

        panel.classList.toggle('is-raised', clamped >= 0.98);

        if (clamped >= 0.98) {
            panel.style.transform = 'none';
            panel.style.top = `${topInset}vh`;
            panel.style.right = `${sideInset}vw`;
            panel.style.bottom = `${bottomInset}vh`;
            panel.style.left = `${sideInset}vw`;
            panel.style.borderRadius = '1.25rem';
            return;
        }

        panel.style.top = clamped > 0 ? `${top}vh` : 'auto';
        panel.style.right = `${side}vw`;
        panel.style.bottom = clamped > 0 ? `${bottom}vh` : '0';
        panel.style.left = `${side}vw`;
        panel.style.transform = `translate3d(0, ${translate}%, 0)`;
        panel.style.borderRadius =
            clamped > 0
                ? `${Math.min(clamped * 1.35, 1.35)}rem ${Math.min(clamped * 1.35, 1.35)}rem 0 0`
                : '0';
    }

    function setFinalState() {
        applyPanelFrame(1);
    }

    function update() {
        ticking = false;

        if (prefersReducedMotion) {
            setFinalState();
            return;
        }

        const viewport = window.innerHeight;
        const rect = scene.getBoundingClientRect();
        const panelScrollRange = getPanelScrollRange();
        const pinnedOffset = Math.max(-rect.top, 0);
        const panelProgress =
            rect.top > 0 ? 0 : Math.min(pinnedOffset / panelScrollRange, 1);

        applyPanelFrame(panelProgress);
    }

    function scrollToAboutPanel(event) {
        if (event) {
            event.preventDefault();
        }

        const top = getAboutScrollTop();

        window.scrollTo({
            top,
            behavior: prefersReducedMotion ? 'auto' : 'smooth',
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, '', '#about');
        }
    }

    function bindAboutAnchors() {
        document.querySelectorAll('a[href="#about"], a[href$="#about"]').forEach(function (link) {
            link.addEventListener('click', scrollToAboutPanel);
        });
    }

    function handleInitialHash() {
        if (window.location.hash !== '#about') {
            return;
        }

        window.requestAnimationFrame(function () {
            scrollToAboutPanel();
            window.setTimeout(scrollToAboutPanel, 120);
        });
    }

    function onScroll() {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () {
        update();
        if (window.location.hash === '#about') {
            scrollToAboutPanel();
        }
    });

    bindAboutAnchors();
    handleInitialHash();
    update();
})();
