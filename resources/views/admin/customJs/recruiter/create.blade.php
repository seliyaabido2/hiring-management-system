<script>
    $(function() {

        $('#input_start_date').datepicker({
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            format: "dd-mm-yyyy",
            startDate: new Date(),
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            orientation: "bottom auto"
        });

        $('#input_end_date').datepicker({
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            format: "dd-mm-yyyy",
            startDate: moment().add(1, 'days').toDate(),
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            orientation: "bottom auto"

        });


        $('#input_start_date').datepicker().on("changeDate", function() {
            var startDate = $('#input_start_date').datepicker('getDate');
            var oneDayFromStartDate = moment(startDate).add(1, 'days').toDate();

            // Set the minimum date for input_end_date to one day after the selected start date
            $('#input_end_date').datepicker('setStartDate', oneDayFromStartDate);

            // Clear the selected date in input_end_date
            $('#input_end_date').datepicker('clearDates');
        });

        $('#input_end_date').datepicker().on("show", function() {
            var startDate = $('#input_start_date').datepicker('getDate');

            // Disable specific dates in input_end_date
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });

    });

    function getState(val) {
        var id = val.value;

        $.ajax({
            url: "{{ route('admin.users.getState') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            success: function(html) {
                $("#state_id").html(html);
            },
            error: function() {
                Swal.fire("Error!", 'Error in fetch state record', "error");
            }
        });
    }

    function getCity(val) {
        var id = val.value;

        $.ajax({
            url: "{{ route('admin.users.getCity') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            success: function(html) {
                $("#city_id").html(html);
            },
            error: function() {
                Swal.fire("Error!", 'Error in fetch city record', "error");
            }
        });
    }



    $('#recruiterCreateFormId').validate({

        rules: {

            first_name: {
                required: true,
                maxlength: 50,
            },
            authorized_name: {
                required: true,
                maxlength: 50,
            },
            phone_no: {
                required: true,
                digits: true, // Ensure that only digits are allowed
                minlength: 10, // Minimum length is 10 digits
                maxlength: 10, // Maximum length is 10 digits
            },

            password: {
                required: true,
                minlength: 4,
                maxlength: 12,
            },
            company_name: {
                required: true,
            },
            location: {
                required: true,
            },
            contract_type: {
                required: true,
            },
            start_date: {
                required: true,
            },
            // end_date:{
            //     required: true,
            // },
            contract_upload: {
                required: true,
                extension: "pdf|doc|docx",
                // filesize: 3072,
            },

            email: {
                required: true,
                pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                maxlength: 100,
                email: true,
                remote: {
                    url: '{{ route('admin.check-email-unique-recruiter') }}',
                    type: 'post',
                    data: {
                        email: function() {
                            return $('#email').val();
                        },
                        form: 'create',
                        _token: $('input[name="_token"]').val()
                    }
                },
            },



        },
        messages: {

            first_name: {
                required: "Please enter first name",
                maxlength: "Please enter no more than 80 characters"
            },
            authorized_name: {
                required: "Please enter authorized name",
                maxlength: "Please enter no more than 80 characters"
            },
            phone_no: {
                digits: "Please enter a valid 10-digit phone number.",
                minlength: "Phone number must be exactly 10 digits long.",
                maxlength: "Phone number must be exactly 10 digits long.",
            },

            password: {
                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength: "Please enter no more than 12 characters."
            },
            company_name: {
                required: "Please Enter company Name",
            },
            location: {
                required: "Please select location"
            },
            contract_type: {
                required: "Please Enter Contract Type",
            },
            start_date: {
                required: "Please Enter Start Date",
            },
            // end_date:{
            //     required: "Please Enter End Date",
            // },
            contract_upload: {
                required: "Please upload a file.",
                extension: "Only PDF, DOC, or DOCX files are allowed.",
                // filesize: "File size exceeds the maximum limit of 3MB.",
            },

            email: {
                required: "Please enter email address",
                pattern: "Please enter a valid email address",
                maxlength: "Please enter no more than 100 characters",
                email: 'Please enter a valid email',
                remote: 'This email is already taken',
            },

        },

        submitHandler: function(form) {
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();

            if (latitude == '' && longitude == '') {
                $('#location').val('');
                $('.location-error').css("display", "block");
                $('.location-error').html("Please select location.");

                return false;
            }

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

        },
    });

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
