<script>

    $('#add-bodbulk-form').validate({
        rules: {
            bodbulkcan: {
                required: true,
                // extension: "xlsx",
                // filesize: 3072,
            },
        },
        messages: {
            bodbulkcan: {
                required: "Please upload BOD Bulk file",
                // filesize: "File size exceeds the maximum limit of 3MB!"
            },

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
        errorPlacement: function(error, element) {

            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }

        }

    });



</script>
