<script>
window.onload = function() {
    document.getElementById('data_4').id = 'datepicker';
    $(function() {
        $( "#datepicker" ).datepicker(
        {
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: "yy-mm-dd",
            dayNamesMin: [ "Ig", "Al", "As", "Az", "Og", "Or", "Lr"],
            firstDay: 1
        });
    });
};
</script>