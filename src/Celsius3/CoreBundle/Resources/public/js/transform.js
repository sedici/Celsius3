function checkRole() {
    var selected = $('input:radio[name ="transformuser[type]"]:checked').val();
    if ((selected === 'ROLE_SUPER_ADMIN') || (selected === 'ROLE_ADMIN')) {
        $('#transformuser_instances').prop('disabled', false);
    } else {
        $('#transformuser_instances').prop('disabled', true);
    }
}

checkRole();

$('input:radio[name ="transformuser[type]"]').on('change', function (e) {
    checkRole();
});
