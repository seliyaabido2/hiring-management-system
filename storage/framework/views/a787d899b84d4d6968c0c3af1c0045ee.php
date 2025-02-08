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
            url: "<?php echo e(route('admin.users.getState')); ?>",
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
            url: "<?php echo e(route('admin.users.getCity')); ?>",
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



    // $('.unique').on('change', function() {

    //     var columnValue = $(this).val();
    //     var columnName = $(this).attr('id');
    //     var tableName = 'candidates';


    //     $.ajax({
    //         url: "<?php echo e(route('admin.validate-unique-item')); ?>",
    //         method: 'POST',
    //         data: {
    //             '_token': '<?php echo e(csrf_token()); ?>',
    //             'column_value': columnValue,
    //             'table_name': tableName,
    //             'column_name': columnName
    //         },
    //         success: function(response) {
    //             if (response.status === false) {
    //                 $('#UniqueValidationError').val(true);
    //                 $("<label id='email-error' class='uniqueError' style='color:red' for='email'>email is not unique.</label>").insertAfter('#'+columnName+'');


    //             }else{
    //                 $('#UniqueValidationError').val(false);

    //              $('.uniqueError').html('');


    //             }
    //         }
    //     });
    // });

    $.validator.addMethod('filesize', function(value, element, param) {
        // Get the file size
        var fileSize = element.files[0].size;

        // Convert the maximum file size in bytes
        var maxSize = param * 1024;

        // Check if the file size is within the limit
        return this.optional(element) || (fileSize <= maxSize);
    }, "File size exceeds the maximum limit of {0} KB.");



    $('#bodresume_fetch').validate({
        rules: {
            file: {
                required: true,
                extension: "pdf|doc|docx",
                // filesize: 3072,
            },

        },
        messages: {
            file: {
                required: "Please upload resume",
                // filesize: "File size exceeds the maximum limit of 3MB!"
            },

        },
        errorPlacement: function(error, element) {

            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }

        },
        submitHandler: function(form) {
            $(".data-loader").addClass('loading');

            var submissionPromise = form.submit();

            submissionPromise.done(function() {
                $(".data-loader").removeClass('loading');
            });

            submissionPromise.fail(function() {
                $(".data-loader").removeClass('loading');
            });
        }
    });

    $('#AddCandidateForm').validate({
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
                //     url: '<?php echo e(route('admin.check-email-unique-candidate')); ?>',
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

            any_other_langauge: {
                required: true,

            },
            other_any_other_langauge: {
                required: function(element) {
                    return $("#any_other_langauge").val() == "Other";
                }
            },
            experience_sf: {
                required: true,
            },
            experience_without_sf: {
                required: true,
            },
            // job_preference:{
            //     required: true,

            // },
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
            resume_path: {
                required: true,
                // extension: "pdf|doc|docx",
                // filesize: 3072,
            },
            expected_pay_per_hour: {
                // required: true,
                min: 1,

            },
            current_pay_per_hour: {
                min: 1,
            },
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
            resume_path: {
                required: "Please upload resume",
                // filesize: "File size exceeds the maximum limit of 3MB!"
            },
            expected_pay_per_hour: {
                required: "Please enter pay per hour",
                min: "Please enter a value greater than or equal to 1"
            },
            // job_type: {
            //     required: "Please enter job type",
            // },

        },
        errorPlacement: function(error, element) {

            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }

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

            var email = $('#email').val();
            var contact_number = $('#contact_number').val();

            $.ajax({
                url: "<?php echo e(route('admin.employerJobs.checkUniqueEmailOrContact')); ?>",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    email: email,
                    contact_number: contact_number,
                },
                beforeSend: function() {
                    $(".data-loader").addClass('loading');
                },
                success: function(response) {
                    $(".data-loader").removeClass('loading');
                    var responseObject = JSON.parse(response);

                    if (responseObject.status === true) {
                        var tableBody = $('#dub_candidate_tbl tbody');

                        tableBody.empty();

                        $.each(responseObject.data, function(key, value) {
                            var row = '<tr data-id=' + value.id + '>' +
                                '<td><input type="radio" name="SelectedRowValue" value="' +
                                value.id + '"></td>' +
                                '<td>' + (key + 1) + '</td>' +
                                '<td>' + value.name + '</td>' +
                                '<td>' + (value.date_of_birth ? value.date_of_birth :
                                    'N/A') + '</td>' +
                                '<td>' + value.email + '</td>' +
                                '<td>' + value.contact_no + '</td>' +
                                '<td>' + new Date(value.created_at).toLocaleString(
                                    'en-GB', {
                                        timeZone: 'UTC'
                                    }) + '</td>' +
                                '</tr>';

                            tableBody.append(row);
                        });

                        $('#dub-candidate-model').modal('show');

                        $("#dub_candidate_tbl tbody tr").click(function() {
                            $(this).find('input[type="radio"]').prop('checked', true);

                            $(this).addClass('selectedOneRow').siblings().removeClass(
                                'selectedOneRow');

                            var value = $(this).find('td:first').html();


                        });

                        $('.overwrite').click(function() {

                            var selectedRow = $('.selectedOneRow');

                            if (selectedRow.length > 0) {

                                var candidate_id = selectedRow.attr('data-id');
                                $('#overwrite_candidate_id').val(candidate_id);
                                $('#dub-candidate-model').modal('hide');
                                $(".data-loader").addClass('loading');

                                var submissionPromise = form.submit();

                                submissionPromise.done(function() {
                                    $(".data-loader").removeClass('loading');
                                });

                                submissionPromise.fail(function() {
                                    $(".data-loader").removeClass('loading');
                                });


                            } else {
                                alert(
                                    'Please select a row before submitting the form.'
                                );
                            }
                        });

                        $('.viewCandidateData').click(function() {

                            var selectedRow = $('.selectedOneRow');

                            if (selectedRow.length > 0) {

                                var candidate_id = selectedRow.attr('data-id');

                                var newTabUrl = window.location.origin +
                                    '/admin/candidate/redirect-to-candidate-view/' +
                                    candidate_id;
                                window.open(newTabUrl, '_blank');


                            } else {
                                alert(
                                    'Please select a row before submitting the form.'
                                );
                            }


                        });

                        $('.cancelCandidateData').click(function() {
                            $('#dub-candidate-model').modal('hide');
                        });

                        $('.create_new').click(function() {
                            $(".data-loader").addClass('loading');

                            var submissionPromise = form.submit();

                            submissionPromise.done(function() {
                                $(".data-loader").removeClass('loading');
                            });

                            submissionPromise.fail(function() {
                                $(".data-loader").removeClass('loading');
                            });
                        });

                    } else {
                        $(".data-loader").addClass('loading');

                        var submissionPromise = form.submit();

                        submissionPromise.done(function() {
                            $(".data-loader").removeClass('loading');
                        });

                        submissionPromise.fail(function() {
                            $(".data-loader").removeClass('loading');
                        });
                    }

                },
                error: function() {
                    $(".data-loader").removeClass('loading');
                    Swal.fire("Error!", 'Error in fetch city record', "error");
                }
            });
        }
    });

    $("#any_other_langauge").on('change', function() {
        var value = $(this).val();
        if (value == 'Other') {
            $(".other_any_other_langauge").show();
        } else {
            $(".other_any_other_langauge").hide();
        }
    });

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
            $(".how_many_experience").hide();
            $('#last_month_year_in_sf').hide();
            $(".presently_working_in_sf").hide();
        }
    });

    $("#presently_working_in_sf").on('change', function() {
        var value = $(this).val();
        if (value == 'No') {

            $(".last_month_year_in_sf").show();
        } else {

            $(".last_month_year_in_sf").hide();
        }
    });

    $(".resume-remove-btn").on('click', function() {
        $('.re-resume-upload').show();
        $('.resume-file').hide();
        $('.candidate-form').hide();

    });




    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
<?php /**PATH /var/www/html/laravel/bod/resources/views/admin/customJs/bodCandidates/create.blade.php ENDPATH**/ ?>