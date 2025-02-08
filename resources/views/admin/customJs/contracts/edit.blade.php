<script>


$(document).ready(function () {
    $('.select2').select2();

    $('#edit-contracts-form').validate({
        ignore: [],
        rules: {
            name: {
                required: true,
            },
            related_to: {
                required: true,
            },
            // description: {
            //     required: true,
            // },
            expire_alert: {
                required: true,
                digits: true, // Validate as digits only
            },
        },
        messages: {
            name: {
                required: "Please enter Contract name",
            },
            related_to: {
                required: "Please select Contract related to",
            },
            // description: {
            //     required: "Please enter Contract description",
            // },
            expire_alert: {
                required: "Please enter expire alert",
                digits: "Please enter valid digit-only value",
            },
        },
        errorPlacement: function (error, element) {
            if (element.parent().hasClass('input-item')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {

            $(".data-loader").addClass('loading');

            // Submit the form and get the promise
            var submissionPromise = form.submit();

            // Handle the completion of the form submission
            submissionPromise.done(function () {
                // After form submission is done, remove the loader
                $(".data-loader").removeClass('loading');
            });

            // Handle any errors or failures
            submissionPromise.fail(function () {
                $(".data-loader").removeClass('loading');
                // Handle errors here if needed
            });
        },
    });
});

$('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id= $(this).attr('id');
        $('#'+id+'-error').css('display','none');
        $('#'+id).removeClass('error');
    });

</script>
