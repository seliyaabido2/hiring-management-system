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
            date_of_birth: {
                required: true,
            },
            gender: {
                required: true,
            },
            email: {
                required: true,
                pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                maxlength: 100,
                email: true,
                remote: {
                    url: '{{ route('admin.check-email-unique-candidate') }}',
                    type: 'post',
                    data: {
                        email: function() {
                            return $('#email').val();
                        },
                        form: 'update',
                        _token: $('input[name="_token"]').val()
                    }
                },
            },
            contact_number: {
                required: true,
            },
            location: {
                required: true,
            },
            country_id: {
                required: true,
            },
            experience_sf: {
                required: true,
            },
            experience_without_sf: {
                required: true,
            },
            license_candidate_basic_training: {
                required: true,
            },
            license_candidate_no_experience: {
                required: true,
            },
            license_candidate_banking_finance: {
                required: true,
            },
            any_other_langauge: {
                required: true,

            },
            other_any_other_langauge: {
                required: function(element) {
                    return $("#any_other_langauge").val() == "Other";
                }
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
            }
        },

        messages: {
            candidate_name: {
                required: "Please enter Full name.",
                maxlength: "Please enter no more than 50 characters."
            },
            date_of_birth: {
                required: "Please enter date of birth.",
            },
            email: {
                required: "Please enter email address",
                pattern: "Please enter a valid email address",
                maxlength: "Please enter no more than 100 characters",
                email: 'Please enter a valid email',
                remote: 'This email is already taken',
            },
            gender: {
                required: "Please select gender.",
            },
            contact_number: {
                required: "Please enter contact number.",
            },
            location: {
                required: "Please enter address.",
            },
            country_id: {
                required: "Please enter country.",
            },
            experience_sf: {
                required: "Please select insurance experience with State Farm.",
            },
            experience_sf: {
                required: "Please select insurance experience without State Farm.",
            },
            license_candidate_basic_training: {
                required: "Please select license candidate with completed basic Training.",
            },
            license_candidate_no_experience: {
                required: "Please select License candidate without insurance Experience.",
            },
            license_requirement: {
                required: "Please select license requirement.",
            },
            other_license_requirement: {
                required: "Please select other license requirementt.",
            }

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
