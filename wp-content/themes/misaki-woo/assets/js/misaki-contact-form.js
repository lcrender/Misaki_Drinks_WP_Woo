/**
 * Misaki Contact Form — mostrar campo Other en Business Type.
 */
(function () {
    'use strict';

    function syncOtherField(form) {
        var otherWrap = form.querySelector('[data-business-other]');
        if (!otherWrap) {
            return;
        }

        var selected = form.querySelector('input[name="business-type"]:checked');
        var isOther = selected && selected.value === 'Other';

        if (isOther) {
            otherWrap.hidden = false;
        } else {
            otherWrap.hidden = true;
            var input = otherWrap.querySelector('input');
            if (input) {
                input.value = '';
            }
        }
    }

    function bindForm(form) {
        form.addEventListener('change', function (event) {
            if (event.target && event.target.name === 'business-type') {
                syncOtherField(form);
            }
        });
        syncOtherField(form);
    }

    document.querySelectorAll('.wpcf7 form').forEach(bindForm);

    document.addEventListener('wpcf7mailsent', function (event) {
        var form = event.target;
        if (form) {
            syncOtherField(form);
        }
    });
})();
