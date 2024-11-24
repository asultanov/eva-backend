export function resetForm(form, except) {
    ['[type="text"]', '[type="tel"]', '[type="hidden"]', '[type="date"]', 'select', '[type="checkbox"]', '[type="datetime-local"]', '[type="number"]']
        .forEach(function (selector) {
            form.querySelectorAll(selector).forEach(function (input) {
                if (except.indexOf(input.name) === -1) {
                    if (selector === 'select') {
                        input.selectedIndex = 0;
                        if ('createEvent' in document) {
                            var event = document.createEvent('HTMLEvents');
                            event.initEvent('change', false, true);
                            input.dispatchEvent(event);
                        } else {
                            input.fireEvent('onchange');
                        }
                    } else if (selector === '[type="checkbox"]') {
                        input.checked = false;
                    } else {
                        input.value = "";
                    }
                }
            });
        });
}
