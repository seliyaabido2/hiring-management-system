<script>
    $(document).on('change', '.job-data', function() {
        var start_date = $('input[name="from_date"]').val();
        var end_date = $('input[name="to_date"]').val();
        var recruiter_id = $('#recruiter_id').val();
        var candidate_id = $('#candidate_id').val();
        var csrfToken = $('input[name="_token"]').val();


        $.ajax({
            url: '<?php echo e(route('admin.recruiterReports.getJobs')); ?>',
            method: 'POST',
            data: {
                start_date: start_date,
                end_date: end_date,
                recruiter_id: recruiter_id,
                candidate_id: candidate_id,

            },
            headers: {
                'X-CSRF-TOKEN': csrfToken // Add the CSRF token to the headers
            },
            beforeSend: function() {
                $(".data-loader").addClass('loading');
            },
            success: function(response) {
                $(".data-loader").removeClass('loading');
                $('#job_id').empty();
                $('#job_id').append($("<option></option>")
                    .attr("value", "")
                    .text('All'));
                $.each(response, function(key, value) {
                    $('#job_id').append($("<option></option>")
                        .attr("value", value.id)
                        .text(value.job_title));
                });

            },
            error: function(error) {
                $(".data-loader").removeClass('loading');
                iziToast.warning({
                    title: 'Error',
                    message: 'somthing Went wrong!',
                    position: 'topRight',

                });
                // Handle errors
            }
        });


    });
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        $.extend(true, $.fn.dataTable.defaults, {
            order: [
                [0, 'asc']
            ],
            pageLength: 10,
        });
        $('.datatable-Role:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#from_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            // Set the start date of 'to_date' datepicker to the selected date of 'from_date' datepicker
            $('#to_date').datepicker('setStartDate', selected.date);
        });

        $('#to_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayHighlight: true,
        });
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "<?php echo e(url('admin/cmsPages/destroy')); ?>?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });

    $('#report-filter').validate({
        ignore: [],
        rules: {

            from_date: {
                required: true,

            },
            to_date: {
                required: true,

            }

        },
        messages: {
            from_date: {
                required: "Please enter from date",

            },
            to_date: {
                required: "Please enter to date",

            }

        },
        errorPlacement: function(error, element) {

            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }

        },
        submitHandler: function(form) {

            var formVal = $('#report-filter');

            var csrfToken = $('input[name="_token"]').val();

            $.ajax({
                url: '<?php echo e(route('admin.recruiterReports.store')); ?>',
                method: 'POST',
                data: formVal.serialize(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Add the CSRF token to the headers
                },
                beforeSend: function() {
                    $(".data-loader").addClass('loading');
                },
                success: function(response) {
                    $(".data-loader").removeClass('loading');
                    if (response.status == true) {

                        // Clear and redraw DataTable with new data

                        console.log(response.data);

                        // Get a reference to the DataTable
                        var dataTable = $($.fn.dataTable.tables(true)).DataTable();
                        dataTable.clear();
                        // Your object response
                        var responseObject = response.data;

                        // Loop through the response and add each object as a new row
                        $.each(responseObject, function(index, object) {

                            var ids = index + 1;

                            if (object.recruiter_name != undefined) {
                                // Extract the properties you want to display in each column
                                var rowData = [
                                    ids,
                                    object.from_date,
                                    object.to_date,
                                    object.recruiter_name,
                                    object.candidate_name,
                                    object.job_title,
                                    object.job_status,
                                    object.round_name,
                                    object.round_status,
                                    object.created_at_formatted,
                                    '<a href="' + object.link +
                                    '" download><i class="fa fa-download" aria-hidden="true"></i></a>',
                                    // Add other properties as needed
                                ];
                            } else {
                                // Extract the properties you want to display in each column
                                var rowData = [
                                    ids,
                                    object.from_date,
                                    object.to_date,
                                    object.candidate_name,
                                    object.job_title,
                                    object.job_status,
                                    object.round_name,
                                    object.round_status,
                                    object.created_at_formatted,
                                    '<a href="' + object.link +
                                    '" download><i class="fa fa-download" aria-hidden="true"></i></a>',
                                    // Add other properties as needed
                                ];
                            }


                            // Add the row to the DataTable
                            dataTable.row.add(rowData);
                        });

                        // Redraw the DataTable to reflect the changes
                        dataTable.draw();

                        iziToast.success({
                            title: 'Recruiter report generate successfully.',
                            message: response,
                            position: 'topRight',

                        });


                    } else {
                        iziToast.warning({
                            title: 'Error',
                            message: response.message,
                            position: 'topRight',

                        });
                    }





                },
                error: function(error) {
                    $(".data-loader").removeClass('loading');
                    iziToast.warning({
                        title: 'Error',
                        message: 'somthing Went wrong!',
                        position: 'topRight',

                    });
                    // Handle errors
                }
            });

        }
    });

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
<?php /**PATH /var/www/html/laravel/bod/resources/views/admin/customJs/recruiterReports/index.blade.php ENDPATH**/ ?>