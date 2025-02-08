<script>
    $(document).ready(function() {

        var formControlRange = $('#formControlRange');
        var dataTable = $('#tblApplyjob').DataTable({

            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, 500],

            ajax: {
                url: '{{ route('admin.employerJobs.ActiveJobDatatable') }}',
                type: 'GET',
                data: function(d) {
                    d.rangeValue = formControlRange.val();
                    d.latitude = $('#latitude').val();
                    d.longitude = $('#longitude').val();
                    // Add dynamic parameters here
                },
            },
            columns: [{
                    data: 'id',
                    name: 'S.No.',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        // Set the row index number (assuming you want it to start from 1)
                        var rowIndex = meta.row + 1;
                        // Add a checkbox in the first column
                        return '<input type="checkbox" class="select-checkbox job-checkbox" data-id="' +
                            data + '">';
                    }
                },

                {
                    data: 'job_title',
                    name: 'Job Title'
                },
                {
                    data: 'job_recruiment_duration',
                    name: 'Job Recruiment Duration'
                },
                {
                    data: 'job_start_date',
                    name: 'Job Luanch Date'
                },
                {
                    data: 'calculated_end_date',
                    name: 'Job Expire Date'
                },

                {
                    data: 'job_type',
                    name: 'Job Type'
                },
                {
                    data: 'location',
                    name: 'Job Location'
                },
                {
                    data: 'status',
                    name: 'Status'
                },
                {
                    data: 'first_name',
                    name: 'Added By'
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

        $('.select-all-checkbox').change(function() {

            $('.select-checkbox').prop('checked', this.checked);
            dataTable.rows().deselect();
            if (this.checked) {
                dataTable.rows().select();
            }
        });

        $('#getJobCheckedIds').on('click', function() {

            var checkedJobIds = [];
            $('.job-checkbox:checked').each(function() {
                checkedJobIds.push($(this).data('id'));
            });

            if (checkedJobIds.length == 0) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please select at least one candidate.!',
                    position: 'topRight',

                });
                return false;
            }
            $('#miles-label').html('Find the nearest candidates  based on the job location and distance :');
            $('#checkedJobIdsArr').val(checkedJobIds);
            $('.JobDataTable').hide();
            $('.CandidateDataTable').show();
            CalltblCandidateJobFn();

            $(this).hide();



            // Perform further actions with the checked IDs
        });




    });

    function CalltblCandidateJobFn() {


        var formControlRange = $('#formControlRange');
        var candidateDataTable = $('#tblCandidateJob').DataTable({

            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, 500],

            ajax: {
                url: '{{ route('admin.employerJobs.candidateDatatabes') }}',
                type: 'GET',
                data: function(d) {
                    // Add dynamic parameters here
                    d.rangeValue = formControlRange.val();
                    d.latitude = $('#latitude').val();
                    d.longitude = $('#longitude').val();
                    d.jobIds = $('#checkedJobIdsArr').val();


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
                        return '<input type="checkbox" class="select-checkbox candidate-checkbox" data-id="' +
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
                var headerRow = $('<tr>').appendTo('#tblCandidateJob thead');

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
            candidateDataTable.ajax.reload();
        });

        $('.select-all-checkbox').change(function() {

            $('.select-checkbox').prop('checked', this.checked);
            candidateDataTable.rows().deselect();
            if (this.checked) {
                candidateDataTable.rows().select();
            }
        });

        $('#submitformBtn').on('click', function(e) {
            e.preventDefault();
            var checkedCandidateIds = [];
            $('.candidate-checkbox:checked').each(function() {
                checkedCandidateIds.push($(this).data('id'));
            });

            if (checkedCandidateIds.length == 0) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please select at least one candidate.!',
                    position: 'topRight',

                });
                return false;
            }
            $('#checkedCandidateArr').val(checkedCandidateIds);



            var formData = $('#bulkApplyJobForm').serialize();
            $('.duplicate-candidate-error').html('');
            $.ajax({
                url: '{{ route("admin.employerJobs.bulkPostApplyJob") }}', // Replace with your route name
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $(".data-loader").addClass('loading');
                },
                success: function(response){
                    $(".data-loader").removeClass('loading');
                    console.log(response.status);
                    // Handle success response
                    if(response.status == true){

                        iziToast.success({
                            title: 'Success',
                            message: 'Jobs Applied successfully.!',
                            position: 'topRight',

                        });

                        setTimeout(function() {
                            window.location.href = "{{ url('admin/employerJobs/bulk-apply-job') }}";
                        }, 2500);



                    }else{
                        $('.manual-hide').removeClass('d-none');
                        $('.duplicate-candidate-error').html(response.message);

                    }

                },
                error: function(error){
                    $(".data-loader").removeClass('loading');
                    // Handle error response
                    iziToast.error({
                            title: 'Error',
                            message: 'Something want to wrong while apply job!',
                            position: 'topRight',

                        });
                }
            });

            // Perform further actions with the checked IDs
        });

    }
    $(document).ready(function() {

        var displayValue = $('#tblCandidateJob').css('display');

        if (displayValue === 'block') {

            CalltblCandidateJobFn();
        }


        $('#getCheckedIds').on('click', function() {
            var checkedIds = [];
            $('.select-checkbox:checked').each(function() {
                checkedIds.push($(this).data('id'));
            });

            if (checkedIds.length == 0) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please select at least one job.!',
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
                checkedIds + "&new_candidate=No&job_id=" + job_id);
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

        if (value == 'Yes') {
            $('.new-candidate-div').show();
            $('.saved-candidate').hide();
        } else {
            $('.new-candidate-div').hide();
            $('.saved-candidate').show();
        }
    });

    $("input[name='new_candidate']").change(function() {
        // Does some stuff and logs the event to the console

        var value = $(this).val();

        if (value == 'Yes') {
            $('.new-candidate-div').show();
            $('.saved-candidate').hide();
        } else {
            $('.new-candidate-div').hide();
            $('.saved-candidate').show();
        }
    });

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
