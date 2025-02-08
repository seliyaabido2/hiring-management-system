<script>
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

    var roleName = $('#RoleName').val();

    if (roleName == 'Admin') {

        $('#userEditFormId').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50,
                },
                // last_name: {
                //     required: true,
                //     maxlength: 50,
                // },
                email: {
                    required: true,
                    pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                    maxlength: 100,
                },
                // password: {
                //     required: true,
                //     minlength: 4,
                //     maxlength: 12,
                // },
                phone_no: {
                    required: true,
                    digits: true, // Ensure that only digits are allowed
                    minlength: 10, // Minimum length is 10 digits
                    maxlength: 10, // Maximum length is 10 digits
                },
                company_name: {
                    required: true,
                },
                company_type: {
                    required: true,
                },
                location: {
                    required: true,
                },

            },

            messages: {
                first_name: {
                    required: "Please enter first name.",
                    maxlength: "Please enter no more than 80 characters."
                },
                // last_name: {
                //     required: "Please enter last name.",
                //     maxlength: "Please enter no more than 80 characters."
                // },
                email: {
                    required: "Please enter email address.",
                    pattern: "Please enter a valid email address.",
                    maxlength: "Please enter no more than 100 characters.",
                },
                // password: {
                //     required: "Please enter password.",
                //     minlength: "Please enter at least 4 characters.",
                //     maxlength :"Please enter no more than 12 characters.",
                // },
                phone_no: {
                    digits: "Please enter a valid 10-digit phone number.",
                    minlength: "Phone number must be exactly 10 digits long.",
                    maxlength: "Phone number must be exactly 10 digits long.",
                },
                company_name: {
                    required: "Please Enter company name."
                },
                company_type: {
                    required: "Please Enter company type."
                },
                location: {
                    required: "Please select location"
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
        });

    }

    if (roleName == 'Employer') {

        $('#userEditFormId').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50,
                },
                // last_name: {
                //     required: true,
                //     maxlength: 50,
                // },
                email: {
                    required: true,
                    pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                    maxlength: 100,
                },
                emp_company_name: {
                    required: true,
                },
                phone_no: {
                    required: true,
                    digits: true, // Ensure that only digits are allowed
                    minlength: 10, // Minimum length is 10 digits
                    maxlength: 10, // Maximum length is 10 digits
                },
                location: {
                    required: true,
                },

            },

            messages: {
                first_name: {
                    required: "Please enter first name",
                    maxlength: "Please enter no more than 80 characters"
                },
                // last_name: {
                //     required: "Please enter last name",
                //     maxlength: "Please enter no more than 80 characters"
                // },
                email: {
                    required: "Please enter email address",
                    pattern: "Please enter a valid email address",
                    maxlength: "Please enter no more than 100 characters",
                },
                emp_company_name: {
                    required: "Please Enter company Name",
                },
                phone_no: {
                    digits: "Please enter a valid 10-digit phone number.",
                    minlength: "Phone number must be exactly 10 digits long.",
                    maxlength: "Phone number must be exactly 10 digits long.",
                },
                location: {
                    required: "Please select location"
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
        });

    }

    if (roleName == 'Recruiter') {

        $('#userEditFormId').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50,
                },
                // last_name: {
                //     required: true,
                //     maxlength: 50,
                // },
                email: {
                    required: true,
                    pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                    maxlength: 100,
                },
                req_company_name: {
                    required: true,
                },
                req_phone_no: {
                    required: true,
                    digits: true, // Ensure that only digits are allowed
                    minlength: 10, // Minimum length is 10 digits
                    maxlength: 10, // Maximum length is 10 digits
                },
                location: {
                    required: true,
                },

            },

            messages: {
                first_name: {
                    required: "Please enter first name",
                    maxlength: "Please enter no more than 80 characters"
                },
                // last_name: {
                //     required: "Please enter last name",
                //     maxlength: "Please enter no more than 80 characters"
                // },
                email: {
                    required: "Please enter email address",
                    pattern: "Please enter a valid email address",
                    maxlength: "Please enter no more than 100 characters",
                },
                req_company_name: {
                    required: "Please Enter company Name",
                },
                req_phone_no: {
                    digits: "Please enter a valid 10-digit phone number.",
                    minlength: "Phone number must be exactly 10 digits long.",
                    maxlength: "Phone number must be exactly 10 digits long.",
                },
                location: {
                    required: "Please select location"
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
        });

    }
</script>
