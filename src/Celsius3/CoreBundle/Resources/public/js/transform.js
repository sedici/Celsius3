function checkRole() {
    var selected = $('input:radio[name ="celsius3_corebundle_transformusertype[type]"]:checked').val();
    if ((selected === 'ROLE_SUPER_ADMIN') || (selected === 'ROLE_ADMIN')) {
        $('#celsius3_corebundle_transformusertype_instances').prop('disabled', false);
    } else {
        $('#celsius3_corebundle_transformusertype_instances').prop('disabled', true);
    }
}

checkRole();

$('input:radio[name ="celsius3_corebundle_transformusertype[type]"]').on('change', function(e) {
    checkRole();
});