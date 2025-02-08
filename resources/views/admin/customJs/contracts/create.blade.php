<script>


$(document).ready(function () {
    $('.select2').select2();

    $('#add-contracts-form').validate({
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

            var submissionPromise = form.submit();

            submissionPromise.done(function() {
                $(".data-loader").removeClass('loading');
            });

            submissionPromise.fail(function() {
                $(".data-loader").removeClass('loading');
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
