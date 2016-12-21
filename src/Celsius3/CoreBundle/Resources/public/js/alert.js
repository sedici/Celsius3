var flashes = {
    'warning': 'warning',
    'notice': 'info',
    'error': 'danger',
    'success': 'success'
};

celsiusAlert = function(message, type) {
    $.notify({
        message: message
    }, {
        type: flashes[type],
        offset: {
            x: 50,
            y: 70
        },
        delay: 6000
    });
};

generateCelsiusAlert = function(response) {
    if (!_.isUndefined(response.data.error) && response.data.error) {
        celsiusAlert(response.data.message, 'error');
    } else {
        celsiusAlert('alert.error.default_message', 'danger');
    }
};

$(document).ready(function() {
    var bag = $.parseJSON(flashbag);
    $.each(flashes, function(alertCode, alertType) {
        if (bag[alertType]) {
            $.each(bag[alertType], function(k, message) {
                celsiusAlert(message, alertType);
            });
        }
    });

    if (!smtp_status) {
        smtp_status = true;
        celsiusAlert('alert.warning.smtp_conection_error', 'warning');
    }
});
