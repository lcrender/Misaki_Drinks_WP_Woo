/**
 * Stepper de cantidad (+/−) en página de producto.
 */
(function () {
    document.querySelectorAll('.quantity--stepper').forEach(function (stepper) {
        const input = stepper.querySelector('.qty');
        const minus = stepper.querySelector('.quantity__btn--minus');
        const plus = stepper.querySelector('.quantity__btn--plus');

        if (!input || !minus || !plus) {
            return;
        }

        function getBounds() {
            const min = parseFloat(input.getAttribute('min'));
            const max = parseFloat(input.getAttribute('max'));
            const step = parseFloat(input.getAttribute('step')) || 1;

            return {
                min: Number.isFinite(min) ? min : 1,
                max: Number.isFinite(max) && max > 0 ? max : Infinity,
                step: step > 0 ? step : 1,
            };
        }

        function getValue() {
            const parsed = parseFloat(input.value);
            const bounds = getBounds();

            if (!Number.isFinite(parsed)) {
                return bounds.min;
            }

            return parsed;
        }

        function setValue(next) {
            const bounds = getBounds();
            let value = next;

            if (value < bounds.min) {
                value = bounds.min;
            }

            if (value > bounds.max) {
                value = bounds.max;
            }

            const decimals = (String(bounds.step).split('.')[1] || '').length;
            input.value = decimals > 0 ? value.toFixed(decimals) : String(Math.round(value));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }

        minus.addEventListener('click', function () {
            setValue(getValue() - getBounds().step);
        });

        plus.addEventListener('click', function () {
            setValue(getValue() + getBounds().step);
        });
    });
})();
