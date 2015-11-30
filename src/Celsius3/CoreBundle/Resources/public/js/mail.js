var templates = [];
$(document).on('click', '.emailModal', function () {
    $('.modal').modal('hide');
    var address = $(this).data('email');
    $.get(Routing.generate('admin_rest_mail_template'))
            .done(function (data) {
                templates = data;

                data = {
                    address: address,
                    templates: templates
                };

                $('#emailModal .modal-body').html(ich.mail(data));

                $('select.select2').select2({
                    allowClear: true
                });

                $('#emailModal').modal('show');
            });
});

$(document).on('change', '#templateSelect', function (e) {
    var template = _.find(templates, function (template) {
        return template.id === e.val;
    });

    $('#emailSubject').val(template.title);
    $('#emailBody').val(template.text);
});

$(document).on('click', '#sendEmail', function () {
    var data = {
        email: $('#emailAddress').val(),
        subject: $('#emailSubject').val(),
        text: $('#emailBody').val()
    };

    $.post(Routing.generate('admin_rest_email'), data)
            .done(function (data) {
                if (data) {
                    $('#emailForm').get(0).reset;
                    $('.modal').modal('hide');
                }
            });
});
