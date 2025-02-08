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

        $('#AdminEditFormId').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50,
                },
                email: {
                    required: true,
                    pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                    maxlength: 100,
                    email: true,
                    remote: {
                        url: '{{ route('admin.check-email-unique-subAdmin') }}',
                        type: 'post',
                        data: {
                            userId: function() {
                                return $('#userId').val();
                            },
                            email: function() {
                                return $('#email').val();
                            },
                            form: 'update',
                            _token: $('input[name="_token"]').val()
                        }
                    },
                },
                location: {
                    required: true,
                },
                phone_no: {
                    required: true,
                    digits: true, // Ensure that only digits are allowed
                    minlength: 10, // Minimum length is 10 digits
                    maxlength: 10, // Maximum length is 10 digits
                },
                designation: {
                    required: true,
                },
                status: {
                    required: true,
                },

            },
            messages: {
                first_name: {
                    required: "Please enter first name",
                    maxlength: "Please enter no more than 80 characters"
                },
                email: {
                    required: "Please enter email address",
                    pattern: "Please enter a valid email address",
                    maxlength: "Please enter no more than 100 characters",
                    email: 'Please enter a valid email',
                    remote: 'This email is already taken',
                },
                location: {
                    required: "Please select location"
                },
                phone_no: {
                    digits: "Please enter a valid 10-digit phone number.",
                    minlength: "Phone number must be exactly 10 digits long.",
                    maxlength: "Phone number must be exactly 10 digits long.",
                },

                designation: {
                    required: "Please enter designation"
                },
                status: {
                    required: "Please Select Admin Status",
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

            }
        });

    }

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
