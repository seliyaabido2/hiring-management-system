<script>
    $(function() {

        $('#input_start_date_0').datepicker({
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

        $('#input_end_date_0').datepicker({
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


        $('#input_start_date_0').datepicker().on("changeDate", function() {

            var startDate = $('#input_start_date_0').datepicker('getDate');
            var oneDayFromStartDate = moment(startDate).add(1, 'days').toDate();

            // Set the minimum date for input_end_date to one day after the selected start date
            $('#input_end_date_0').datepicker('setStartDate', oneDayFromStartDate);

            // Clear the selected date in input_end_date
            $('#input_end_date_0').datepicker('clearDates');
        });

        $('#input_end_date_0').datepicker().on("show", function() {

            var startDate = $('#input_start_date_0').datepicker('getDate');

            // Disable specific dates in input_end_date
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });

    });



    var sectionCount = 1;

    // jQuery function to add new contract section
    $(".add_contract").on("click", function() {
        sectionCount++;
        var newContractSection = $(".contract_section:first").clone(); // Clone the first section
        newContractSection.attr("id", "contract_section_" + sectionCount); // Set a new unique ID
        newContractSection.find("input, select, div").each(function() {
            // Update the id attribute for each input and select within the new section
            var currentId = $(this).attr('id');
            if (currentId) {
                var newId = currentId.replace(/_\d+$/, '_' + sectionCount);
                $(this).attr('id', newId);
            }
            $(this).val(""); // Clear input values
        });
        $(".contract_block").append(newContractSection); // Append the new section



        $('#input_start_date_' + sectionCount).datepicker({
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

        $('#input_end_date_' + sectionCount).datepicker({
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            format: "dd-mm-yyyy",
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            orientation: "bottom auto"
        });

        $('#input_start_date_' + sectionCount).datepicker().on("changeDate", function() {
            var startDate = $('#input_start_date_' + sectionCount).datepicker('getDate');
            var oneDayFromStartDate = moment(startDate).add(1, 'days').toDate();

            // Set the minimum date for input_end_date to one day after the selected start date
            $('#input_end_date_' + sectionCount).datepicker('setStartDate', oneDayFromStartDate);

            // Clear the selected date in input_end_date
            $('#input_end_date_' + sectionCount).datepicker('clearDates');
        });

        $('#input_end_date_' + sectionCount).datepicker().on("show", function() {
            var startDate = $('#input_start_date_' + sectionCount).datepicker('getDate');

            // Disable specific dates in input_end_date
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });



        updateButtons(); // Update button positions
    });

    // jQuery function to remove last contract section
    $(".remove_contract").on("click", function() {
        if (sectionCount > 1) {
            $("#contract_section_" + sectionCount).remove(); // Remove the last section
            sectionCount--;
            updateButtons(); // Update button positions
        }
    });

    // Function to update button positions
    function updateButtons() {
        $(".add_contract, .remove_contract").appendTo(".contract_block"); // Move buttons outside all sections
    }



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


    $(document).on('change', '.contract_type', function() {

        var contract_type_id = $(this).attr('id');
        var id = contract_type_id.split('_');
        id = id[2];

        var contract_type = $(this).val();
        if (contract_type == 1) {
            $('#checklist_upload_row_' + id).css('display', 'block');
        } else {
            $('#checklist_upload_row_' + id).css('display', 'none');
        }
    });

    $('#employerCreateFormId').validate({

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
            checklist_upload: {
                required: {
                    depends: function(element) {
                        return $("#contract_type").val() == 1;
                    },
                },
                // extension: "pdf|doc|docx",
            },

            email: {
                required: true,
                pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
                maxlength: 100,
                email: true,
                remote: {
                    url: '{{ route('admin.check-email-unique-employer') }}',
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
