<script>
    $(".role-name").click(function() {
        $("#userFormId").validate().resetForm();
        document.getElementById("userFormId").reset();

        var value = $(this).val();
        var id = $(this).attr('id');

        $(".role-name").attr('checked', false);
        $("#" + id).attr('checked', true);

        if (value == 2) {

            $('.recruiter-div').hide();
            $('.employee-div').hide();
            $('.admin-div').show();
        }
        if (value == 3) {

            $('.recruiter-div').hide();
            $('.admin-div').hide();
            $('.employee-div').show();
        }
        if (value == 4) {

            $('.admin-div').hide();
            $('.employee-div').hide();
            $('.recruiter-div').show();
        }

    });

    $('#userFormId').validate({
        rules: {
            first_name: {
                required: true,
                maxlength: 50,
            },
            last_name: {
                required: true,
                maxlength: 50,
            },
            email: {
                required: true,
                pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                maxlength: 100,
            },
            password: {
                required: true,
                minlength: 4,
                maxlength: 12,
            },
            roles: {
                required: true
            },
            company_name: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            company_type: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            emp_company_name: {
                required: {

                    depends: function(element) {
                        return $("#radio2").is(":checked");
                    },

                },
            },
            req_company_name: {
                required: {

                    depends: function(element) {
                        return $("#radio3").is(":checked");
                    },

                },
            },
            address: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            emp_address: {
                required: {

                    depends: function(element) {
                        return $("#radio2").is(":checked");
                    },

                },
            },
            address: {
                required: {

                    depends: function(element) {
                        return $("#radio3").is(":checked");
                    },

                },
            },
            req_phone_no: {
                required: {

                    depends: function(element) {
                        return $("#radio3").is(":checked");
                    },

                },
            },
            phone_no: {
                required: {

                    depends: function(element) {
                        return $("#radio2").is(":checked");
                    },

                },
            },
            phone_no: {
                required: {

                    depends: function(element) {
                        return $("#radio2").is(":checked");
                    },

                },
            },
            country_id: {
                required: {

                    depends: function(element) {
                        return $("#radio3").is(":checked");
                    },

                },
            },
            // image  : {
            //     required: {

            //          depends: function(element) {
            //              return $("#radio3").is(":checked");
            //          },

            //      },
            // },



        },

        messages: {
            first_name: {
                required: "Please enter first name.",
                maxlength: "Please enter no more than 80 characters."
            },
            last_name: {
                required: "Please enter last name.",
                maxlength: "Please enter no more than 80 characters."
            },
            email: {
                required: "Please enter email address.",
                pattern: "Please enter a valid email address.",
                maxlength: "Please enter no more than 100 characters.",
            },
            password: {
                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength: "Please enter no more than 12 characters."
            },
            roles: {
                required: "Please select Roles."
            }
        },
    });


    $('#change-password-form').validate({
        rules: {


            current_password: {
                required: true,
                minlength: 4,
                maxlength: 12,
            },
            password: {
                required: true,
                minlength: 4,
                maxlength: 12,

            },
            confirm_password: {
                required: true,
                minlength: 4,
                maxlength: 12,
                equalTo: "#password",
            }
        },
        messages: {
            current_password: {

                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength: "Please enter no more than 12 characters.",


            },
            password: {

                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength: "Please enter no more than 12 characters.",
            },
            confirm_password: {

                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength: "Please enter no more than 12 characters.",
                equalTo: "Passwords do not match",
            }
        },
        submitHandler: function(form) {
            // Your code to handle the form submission
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
</script>
