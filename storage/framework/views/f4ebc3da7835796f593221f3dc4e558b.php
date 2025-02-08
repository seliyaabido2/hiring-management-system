<script>
$('.existing-job').on('click', function() {
    $('#existing-job-modal').modal('show');
    $(".select2bs4").select2();
    $("#exsiting_job").select2("val", "");


});


$('.cancel-btn').on('click', function() {
    $(this).closest('.modal').modal('hide');



});


$('#exsiting_job').on('change', function() {
    var selectedValue = $(this).val();

// Update the URL with the selected value (replace 'your_url_here' with your actual URL)
var newUrl = 'createExitJob?job=' + selectedValue;

// Redirect to the new URL
window.location.href = newUrl;

});

$('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id= $(this).attr('id');
        $('#'+id+'-error').css('display','none');
        $('#'+id).removeClass('error');
    });



</script>
<?php /**PATH /var/www/html/laravel/bod/resources/views/admin/customJs/employerJobs/createJob.blade.php ENDPATH**/ ?>