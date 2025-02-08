<script>
    $(function() {
        $('.select2').select2();


        $('#date_of_birth').datepicker({
            format: 'dd-mm-yyyy',
            endDate: new Date() // Set the end date to today
        });

        $('#last_month_year_in_sf').datepicker({
            format: 'mm-yyyy',
            minViewMode: 'months', // Set the view mode to show only months
            endDate: '0d', // Set the end date to today
            autoclose: true
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


    $('#EditCandidateForm').validate({
        rules: {
            candidate_name: {
                required: true,
                maxlength: 50,
            },
            email: {
                required: true,
                pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                maxlength: 100,
                email: true,
                // remote: {
                //     url: '{{ route('admin.check-email-unique-candidate') }}',
                //     type: 'post',
                //     data: {
                //         email: function() {
                //             return $('#email').val();
                //         },
                //         form: 'create',
                //         _token: $('input[name="_token"]').val()
                //     }
                // },
            },
            contact_number: {
                required: true,
                pattern: /^\+?1?[-.\s]?\(?(\d{3})\)?[-.\s]?(\d{3})[-.\s]?(\d{4})$/,
                // pattern: /^[\d\+\- ]+$/, // Allow digits, plus, and minus
                // digits: true, // Ensure that only digits are allowed
                // minlength: 8, // Minimum length is 10 digits
                // maxlength: 12, // Maximum length is 10 digits
            },
            location: {
                required: true,
            },
            country_id: {
                required: true,
            },
            location: {
                required: true,
            },
            any_other_langauge: {
                required: true,

            },
            other_any_other_langauge: {
                required: function(element) {
                    return $("#any_other_langauge").val() == "Other";
                },
            },
            experience_sf: {
                required: true,
            },
            experience_without_sf: {
                required: true,
            },

            // how_many_experience: {
            //     required: {
            //         depends: function(element) {
            //             return ($("#experience_sf").val() == "Yes");
            //         },
            //     },
            // },
            // presently_working_in_sf:{
            //     required: {
            //         depends: function(element) {
            //             return ($("#experience_sf").val() == "Yes");
            //         },
            //     },
            // },
            // license_candidate_basic_training: {
            //     required: true,
            // },
            any_other_langauge: {
                required: true,
            },
            license_requirement: {
                required: true,
            },
            other_license_requirement: {
                required: {
                    depends: function(element) {
                        return ($("#license_requirement").val() == "Other");
                    },
                },
            },

            // expected_pay_per_hour: {
            //     required: true,
            //     min: 1,
            // },
            // job_type: {
            //     required: true,
            // },

        },
        messages: {
            candidate_name: {
                required: "Please enter Full name.",
                maxlength: "Please enter no more than 50 characters."
            },
            email: {
                required: "Please enter email address",
                pattern: "Please enter a valid email address",
                maxlength: "Please enter no more than 100 characters",
                email: 'Please enter a valid email',
                remote: 'This email is already taken',
            },
            contact_number: {
                digits: "Please enter a valid 10-digit phone number.",
                minlength: "Phone number must be exactly 10 digits long.",
                maxlength: "Phone number must be exactly 10 digits long.",
            },
            location: {
                required: "Please enter address.",
            },
            country_id: {
                required: "Please enter country.",
            },
            location: {
                required: "Please select location"
            },
            // job_preference: {
            //     required: "Please select job preference.",
            // },
            experience_sf: {
                required: "Please select insurance experience with State Farm.",
            },
            experience_without_sf: {
                required: "Please select insurance experience without State Farm.",
            },
            // license_candidate_basic_training: {
            //     required: "Please select license candidate with completed basic Training.",
            // },
            any_other_langauge: {
                required: "Please select language.",
            },
            license_requirement: {
                required: "Please select license requirement.",
            },
            other_license_requirement: {
                required: "Please select other license requirementt.",
            },

            // expected_pay_per_hour: {
            //     required: "Please enter pay per hour",
            //     min: "Please enter a value greater than or equal to 1"
            // },
            // job_type: {
            //     required: "Please enter job type",
            // },

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

    $("#any_other_langauge").on('change', function() {
        var value = $(this).val();
        if (value == 'Other') {
            $(".other_any_other_langauge").show();
        } else {
            $(".other_any_other_langauge").hide();
        }
    });

    if ($('#license_requirement').val() == 'Other') {

        $(".other_license_requirement").show();
    } else {
        $(".other_license_requirement").hide();

    }

    $("#license_requirement").on('change', function() {
        var value = $(this).val();
        if (value == 'Other') {
            $(".other_license_requirement").show();
        } else {
            $(".other_license_requirement").hide();
        }
    });

    $("#experience_sf").on('change', function() {
        var value = $(this).val();
        if (value == 'Yes') {
            $(".how_many_experience").show();
            $(".presently_working_in_sf").show();
        } else {
            $('#last_month_year_in_sf').hide();
            $(".how_many_experience").hide();
            $(".presently_working_in_sf").hide();
        }
    });


    $('.unique').on('change', function() {

        var columnValue = $(this).val();
        var columnName = $(this).attr('id');
        var tableName = 'candidates';
        var id = $('#' + tableName + '_hidden_id').val();

        $.ajax({
            url: "{{ route('admin.validate-unique-item') }}",
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'column_value': columnValue,
                'table_name': tableName,
                'column_name': columnName,
                'id': id
            },
            beforeSend: function() {
                $(".data-loader").addClass('loading');
            },
            success: function(response) {
                $(".data-loader").removeClass('loading');
                if (response.status === false) {
                    $('.uniqueError').remove();
                    $("<label id='email-error' class='uniqueError' style='color:red' for='email'>email is not unique.</label>")
                        .insertAfter('#' + columnName + '');
                    return false;

                } else {
                    $(".data-loader").removeClass('loading');
                    $('.uniqueError').html('');
                    return true;

                }
            }
        });
    });

    $("#presently_working_in_sf").on('change', function() {
        var value = $(this).val();
        if (value == 'No') {

            $(".last_month_year_in_sf").show();
        } else {

            $(".last_month_year_in_sf").hide();
        }
    });

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
