function checkRole() {
    var selected = $('input:radio[name ="celsius3_corebundle_transformusertype[type]"]:checked').val();
    if (selected !== 'ROLE_SUPER_ADMIN') {
        $('#celsius3_corebundle_transformusertype_instances').prop('disabled', true);
    } else {
        $('#celsius3_corebundle_transformusertype_instances').prop('disabled', false);
    }
}

checkRole();

$('input:radio[name ="celsius3_corebundle_transformusertype[type]"]').on('change', function(e) {
    checkRole();
});