$(document).ready(function () {
    "use strict";
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

                $('#emailModal button#sendBatchEmail').attr('id', 'sendEmail');

                $('#emailModal').modal('show');
            });
    });

    $(document).on('select2:select', '#templateSelect', function (e) {
        var selected = $(this).val();
        var template = _.find(templates, function (template) {
            return template.id === selected;
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

    $(document).on('click', '#sendBatchEmail', function () {
        let emails = $('#emailAddress').attr('value').split(/ *, */);
        let subject = $('#emailSubject').val();
        let text = $('#emailBody').val();
        let url = Routing.generate('admin_rest_email');

        for (let i = 0; i < emails.length; ++i) {
            let data = {
                email: emails[i],
                subject: subject,
                text: text
            };

            $.post(url, data)
                .done(function (data) {
                    if (data) {
                        $('#emailForm').get(0).reset;
                        $('.modal').modal('hide');
                    }
                });
        }
    });
});