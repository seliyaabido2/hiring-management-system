<script>

    function submitFormResume() {
        $('.upload-btn').hide();
        // var formData = $('#bodresume_fetch').serialize();
        var formData = new FormData($('#bodresume_fetch')[0]);

        $.ajax({
            url: '{{ route("admin.resumeFetchData.upload") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,  // Set cache to false to ensure the file is not cached
            success: function(response) {
                $('.new-candidate-div').show();
                $('#candidate_name').val(response['candidate_name']);
                $('#contact_number').val(response['contact_number']);
                $('#email').val(response['email']);
                $('#resume_path').val(response['resume']);
                $('#location').val(response['address']);

                console.log(response['candidate_name']);
                console.log(response['contact_number']);
                console.log(response['email']);
                console.log(response['resume']);
                // console.log(response['resume_path']);
                console.log(response['address']);
                // Handle success response
            },
            error: function(error) {
                console.log(error);
                // Handle error response
            }
        });
    }





    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    $(document).ready(function() {


        $('#last_month_year_in_sf').datepicker({
            format: 'mm-yyyy',
            minViewMode: 'months', // Set the view mode to show only months
            endDate: '0d', // Set the end date to today
            autoclose: true
        });

        var formControlRange = $('#formControlRange');
        var dataTable = $('#tblApplyjob').DataTable({

            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, 500],

            ajax: {
                url: '{{ route('admin.employerJobs.candidateDatatabes') }}',
                type: 'GET',
                data: function(d) {
                    // Add dynamic parameters here
                    d.rangeValue = formControlRange.val();
                    d.latitude = $('#formControlRange').attr('data-lat');
                    d.longitude = $('#formControlRange').attr('data-long');
                    d.jobId = $('#job_id').val();

                },
            },
            columns: [{
                    data: 'candidate_id',
                    name: 'S.No.',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        // Set the row index number (assuming you want it to start from 1)
                        var rowIndex = meta.row + 1;
                        // Add a checkbox in the first column
                        return '<input type="checkbox" class="select-checkbox" data-id="' +
                            data + '">';
                    }
                },

                {
                    data: 'name',
                    name: 'Name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'experience_sf',
                    name: 'Experirnce with State Farm'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],

            initComplete: function() {
                var headerRow = $('<tr>').appendTo('#tblApplyjob thead');

                this.api().columns().every(function() {
                    var column = this;
                    var columnIndex = column.index();
                    var headerText = $(column.header()).text().trim();

                    if (headerText != "Actions" && headerText != "" && headerText != "ID") {
                        console.log(headerText);

                        // Append a new header cell


                        var input = $(
                                '<input type="text" class="form-control" placeholder="Search ' +
                                headerText + '" />')
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });

                        $('<th>').html(input).appendTo(headerRow);

                    } else {
                        $('<th>').html("").appendTo(headerRow);
                    }
                });
            },
            drawCallback: function(settings) {
                selectedCandidateChecked();
            }
        });

        formControlRange.on('input', function() {
            dataTable.ajax.reload();
        });

        $('#select-all-checkbox').change(function() {

            $('.select-checkbox').prop('checked', this.checked);
            dataTable.rows().deselect();
            if (this.checked) {
                dataTable.rows().select();
            }
        });

        $('#getCheckedIds').on('click', function() {
            var checkedIds = [];
            $('.select-checkbox:checked').each(function() {
                checkedIds.push($(this).data('id'));
            });

            if (checkedIds.length == 0) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please select at least one candidate.!',
                    position: 'topRight',

                });
                return false;
            }
            console.log(checkedIds);

            var job_id = $('#job_id').val();
            $('.modal-body').html('Are you sure you?');
            $('.delete-form .btn').removeClass('btn-danger');
            $('.delete-form .btn').addClass('btn-success');
            $('.delete-form .btn').html('Apply');
            $('#delete-model').modal('show');

            $('input[name="_method"]').val('POST');

            $('.delete-form').attr('action',
                "{{ url('admin/employerJobs/post-apply-job') }}?candidate=" +
                checkedIds + "&new_candidate=No&new_candidate_post=No&job_id=" + job_id);
            // Perform further actions with the checked IDs
        });


    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
</script>


<script>
    function selectedCandidateChecked() {

        var selectedCandidateData = {!! json_encode(session('selectedCanidateData')) !!};
        if (selectedCandidateData && selectedCandidateData.length > 0) {
            selectedCandidateData.forEach(function(candidate) {
                console.log(candidate);
                $('[data-id="' + candidate + '"]').prop('checked', true).closest('tr').addClass('selected');
            });

        }
    }
</script>

<script>
    $(document).on("click", ".paginate_button", function() {

        $('#select-all-checkbox').prop('checked', false);
    });

    $(function() {

        $('.select2').select2();

        $('#date_of_birth').datepicker({
            format: 'dd-mm-yyyy',
            endDate: new Date() // Set the end date to today
        });


    });

    $("input[name='new_candidate']").change(function() {
        // Does some stuff and logs the event to the console

        var value = $(this).val();
        // alert(value);
        $('#new_candidate_post').val(value);
        if (value == 'Yes') {

            // $('.new-candidate-div').show();
            $('.new-candidate-resume').show();
            $('.saved-candidate').hide();
        } else {
            $('.new-candidate-div').hide();
            $('.new-candidate-resume').hide();
            $('.saved-candidate').show();
        }
    });

    // $("input[name='new_candidate']").change(function() {
    //     // Does some stuff and logs the event to the console

    //     var value = $(this).val();

    //     if (value == 'Yes') {
    //         $('.new-candidate-div').show();
    //         $('.saved-candidate').hide();
    //     } else {
    //         $('.new-candidate-div').hide();
    //         $('.saved-candidate').show();
    //     }
    // });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
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

    $.validator.addMethod('filesize', function(value, element, param) {
        // Get the file size
        var fileSize = element.files[0].size;

        // Convert the maximum file size in bytes
        var maxSize = param * 1024;

        // Check if the file size is within the limit
        return this.optional(element) || (fileSize <= maxSize);
    }, "File size exceeds the maximum limit of {0} KB.");

    $('#applyJobForm').validate({
        rules: {

            'candidate[]': {
                required: {

                    depends: function(element) {
                        return $("#radio2").is(":checked");
                    },

                },

            },

            candidate_name: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

                maxlength: 50,

            },

            email: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },

            // email: {
            //     required: true,
            //     pattern: /^\b[a-z0-9._]+@[a-z_]+?\.[a-z]{2,3}\b$/i,
            //     maxlength: 100,
            //     email: true,
            //     // remote: {
            //     //     url: '{{ route('admin.check-email-unique-candidate') }}',
            //     //     type: 'post',
            //     //     data: {
            //     //         email: function() {
            //     //             return $('#email').val();
            //     //         },
            //     //         form: 'create',
            //     //         _token: $('input[name="_token"]').val()
            //     //     }
            //     // },
            // },
            contact_number: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            location: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            country_id: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            // job_preference: {
            //     required: {

            //         depends: function(element) {
            //             return $("#radio1").is(":checked");
            //         },

            //     },

            // },
            experience_sf: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            new_presenting_experience_sf: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            experience_without_sf: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            // how_many_experience: {
            //     required: {
            //         depends: function(element) {
            //             return ($("#radio1").is(":checked") || $("#experience_without_sf").val() === "Yes");
            //         }
            //     }
            // },
            // license_candidate_basic_training: {
            //     required: {

            //         depends: function(element) {
            //             return $("#radio1").is(":checked");
            //         },

            //     },

            // },

            license_candidate_no_experience: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },

            any_other_langauge: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            other_any_other_langauge: {
                required: {
                    depends: function(element) {
                        return ($("#radio1").is(":checked") || $("#any_other_langauge").val() === "Other");
                    }
                }
            },
            license_requirement: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },

            },
            other_license_requirement: {
                required: {

                    depends: function(element) {
                        return ($("#license_requirement").val() == "Other" && $("#radio1").is(":checked"));
                    },

                },

            },
            resume: {
                required: {

                    depends: function(element) {
                        return $("#radio1").is(":checked");
                    },

                },
                extension: "pdf|doc|docx",
                // filesize: 3072,

            },
            expected_pay_per_hour: {
                min: {
                    depends: function(element) {
                        return $("#radio1").is(":checked") && $(element).val().trim() !== "";
                    },
                    param: 1
                }
            },
            current_pay_per_hour: {
                min: {
                    depends: function(element) {
                        return $("#radio1").is(":checked") && $(element).val().trim() !== "";
                    },
                    param: 1
                }
            },
            // job_type: {
            //     required: {

            //         depends: function(element) {
            //             return $("#radio1").is(":checked");
            //         },

            //     },

            // },


        },
        messages: {

            'candidate[]': {
                required: "Please Select candidate Id",
            },
            candidate_name: {
                required: "Please enter Full name.",
                maxlength: "Please enter no more than 80 characters."
            },

            email: {
                required: "Please enter email address",
                pattern: "Please enter a valid email address",
                maxlength: "Please enter no more than 100 characters",
                email: 'Please enter a valid email',
                // remote: 'This email is already taken',
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
            expected_pay_per_hour: {

                min: "Please enter a value greater than or equal to 1",
            },
            current_pay_per_hour: {
                min: "Please enter a value greater than or equal to 1",
            },
            // license_candidate_basic_training: {
            //     required: "Please select license candidate with completed basic Training.",
            // },
            license_candidate_no_experience: {
                required: "Please select License candidate without insurance Experience.",
            },
            any_other_langauge: {
                required: "Please select language.",
            },

            license_requirement: {
                required: "Please select license requirement.",
            },
            other_license_requirement: {
                required: "Please select other license requirementt.",
            },
            resume: {
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

            var getSelectedValue = document.querySelector('input[name="new_candidate"]:checked');

            if (getSelectedValue.value == 'Yes') {
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
                    url: "{{ route('admin.employerJobs.checkUniqueEmailOrContact') }}",
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
                                    '<td>' + (value.date_of_birth ? value
                                        .date_of_birth :
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
                                $(this).find('input[type="radio"]').prop('checked',
                                    true);

                                $(this).addClass('selectedOneRow').siblings()
                                    .removeClass(
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
                                        $(".data-loader").removeClass(
                                            'loading');
                                    });

                                    submissionPromise.fail(function() {
                                        $(".data-loader").removeClass(
                                            'loading');
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
            $(".presently_working_in_sf").hide();
            $('#last_month_year_in_sf').hide();
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
    // last_month_year_in_sflast_month_year_in_sf

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>

<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/notifications/destroy') }}?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });

    });
</script>
