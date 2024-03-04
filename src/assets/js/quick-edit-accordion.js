$(document).ready(function() {

    $(document).on('click', '.editable', function() {
        let span = $(this);
        if (!span.find('input').length) {
            let currentValue = span.text();
            let input = $('<input>', {
                'type': 'text',
                'value': currentValue,
                'class': 'form-control'
            });

            input.on('blur keyup', function(e) {
                if (e.type === 'blur' || e.keyCode === 13) {
                    let newValue = input.val();
                    let index = span.data('index');
                    let attribute = span.data('attribute');
                    let action = span.closest('.quick-edit-accordion').data('action');
                    if (currentValue !== newValue) {
                        updateItem(span, index, attribute, newValue, action);
                    }

                    span.text(newValue);
                }
            });

            span.empty().append(input);
            input.focus();
        }
    });

    function updateItem(span, index, attribute, newValue, action) {
        $.ajax({
            url: action,
            type: 'POST',
            data: { index: index, attribute: attribute, newValue: newValue },
            success: function(response) {
                if (response.success === true) {
                    span.text(response.newValue);
                } else {
                    span.text(response.oldValue);
                }
            },
            error: function() {
                span.text(currentValue);
            }
        });
    }

    $('#modal-import').on('hidden.bs.modal', function () {
        $('.dropdown-menu.collapse.in').each(function() {
            $(this).removeClass('collapse');
        });
    });
});
