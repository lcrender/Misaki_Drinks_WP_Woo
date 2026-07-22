/**
 * Age gate — Accept / Deny con persistencia en localStorage.
 */
(function () {
    'use strict';

    var config = window.misakiAgeGate || {};
    var storageKey = config.storageKey || 'misaki_age_verified';
    var deniedKey = config.deniedKey || 'misaki_age_denied';
    var exitUrl = config.exitUrl || 'https://www.google.com/';
    var gate = document.getElementById('misaki-age-gate');

    if (!gate) {
        return;
    }

    function readStorage(key) {
        try {
            return window.localStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    function writeStorage(key, value) {
        try {
            window.localStorage.setItem(key, value);
        } catch (e) {
            // ignore
        }
    }

    function openGate() {
        gate.hidden = false;
        document.body.classList.add('misaki-age-gate-active');
        var accept = gate.querySelector('[data-age-gate="accept"]');
        if (accept) {
            accept.focus();
        }
    }

    function closeGate() {
        gate.hidden = true;
        document.body.classList.remove('misaki-age-gate-active');
    }

    function onAccept() {
        writeStorage(storageKey, '1');
        try {
            window.localStorage.removeItem(deniedKey);
        } catch (e) {
            // ignore
        }
        closeGate();
    }

    function onDeny() {
        writeStorage(deniedKey, '1');
        try {
            window.localStorage.removeItem(storageKey);
        } catch (e) {
            // ignore
        }
        window.location.href = exitUrl;
    }

    if (readStorage(storageKey) === '1') {
        closeGate();
        return;
    }

    openGate();

    gate.addEventListener('click', function (event) {
        var target = event.target.closest('[data-age-gate]');
        if (!target) {
            return;
        }

        var action = target.getAttribute('data-age-gate');
        if (action === 'accept') {
            onAccept();
        } else if (action === 'deny') {
            onDeny();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (gate.hidden) {
            return;
        }
        if (event.key === 'Escape') {
            event.preventDefault();
            onDeny();
        }
    });
})();
