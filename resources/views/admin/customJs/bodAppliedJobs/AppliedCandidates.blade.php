<script>
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        // @can('user_delete')
        // let deleteButtonTrans = '{{ trans('global.datatables.delete ') }}'
        // let deleteButton = {
        //     text: deleteButtonTrans,
        //     url: "{{ route('admin.appliedJobs.massDestroy') }}",
        //     className: 'btn-danger',
        //     action: function(e, dt, node, config) {
        //         var ids = $.map(dt.rows({
        //             selected: true
        //         }).nodes(), function(entry) {
        //             return $(entry).data('entry-id')
        //         });

        //         if (ids.length === 0) {
        //             alert('{{ trans('global.datatables.zero_selected ') }}')

        //             return
        //         }

        //         if (confirm('{{ trans('global.areYouSure ') }}')) {
        //             $.ajax({
        //                     headers: {
        //                         'x-csrf-token': _token
        //                     },
        //                     method: 'POST',
        //                     url: config.url,
        //                     data: {
        //                         ids: ids,
        //                         _method: 'DELETE'
        //                     }
        //                 })
        //                 .done(function() {
        //                     location.reload()
        //                 })
        //         }
        //     }
        // }
        // dtButtons.push(deleteButton)
        // @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            order: [
                [1, 'asc']
            ],
            pageLength: 10,
        });

        // $('.datatable-User:not(.ajaxTable)').DataTable({
        //     buttons: dtButtons
        // })
        // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        //     $($.fn.dataTable.tables(true)).DataTable()
        //         .columns.adjust();
        // });
    })
</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        // $('.delete-btn').click(function() {
        //     var itemId = $(this).data('id');
        //     $('.modal-body').html('Are you sure you want to delete this candidate? Deleting this candidate will also remove any applied jobs associated with them.');
        //     $('#delete-model').modal('show');
        //     $('.delete-form').attr('action', "{{ url('admin/candidate/destroy') }}?id=" + itemId);
        // });

        $('.delete-applied-btn').click(function() {
            var itemId = $(this).data('id');
            $('.modal-body').html(
            'Are you sure you want to delete this Applied job of this candidate?');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/appliedJobs/destroy') }}?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>


<script>
    $(".CandidateJobStatus").change(function() {
        // Does some stuff and logs the event to the console

        var status = $(this).val();
        var id = $(this).attr("data-id");
        var roleName = '{{ getUserRole(auth()->user()->id) }}';

        if (roleName == 'Recruiter') {
            iziToast.warning({
                title: 'Error',
                message: 'Permission denied!',
                position: 'topRight',

            });
            return false;
        }


        if (status == 'Phone Interview') {

            PhoneIntereviewModel(id, status);

        } else {

            $.ajax({
                url: "{{ route('admin.appliedJobs.candidatesChangeJobStatus') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: status,
                    id: id,
                },
                beforeSend: function() {
                    $(".data-loader").addClass('loading');
                },
                success: function(status) {
                    $(".data-loader").removeClass('loading');
                    if (status == true) {

                        iziToast.success({
                            title: 'success',
                            message: 'status Changed Successfully.',
                            position: 'topRight',

                        });


                    } else {
                        iziToast.warning({
                            title: 'Error',
                            message: 'somthing Went wrong!',
                            position: 'topRight',

                        });
                    }

                    setTimeout(function() {
                        location.reload();
                    }, 2000);

                },
                error: function() {
                    $(".data-loader").removeClass('loading');
                    Swal.fire("Error!", 'Error in update data record', "error");
                }
            });

        }



    });

    function PhoneIntereviewModel(id, status) {

        $('#PhoneIntereviewModel').modal('show');
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#candidatejobstatus').val(status);
        $('.PhoneIntereviewModelForm').attr('action',
            "{{ url('admin/appliedJobs/candidatesChangeJobStatusAction') }}?id=" + id);
    }
    $('.cancel-btn').click(function() {
        $(this).closest('.modal').modal('hide');
    });


    $(document).ready(function() {

        $('.PhoneIntereviewModelForm').validate({

            ignore: [],
            rules: {

                assessment_link: {
                    required: true,

                },
            },
            messages: {

                assessment_link: {
                    required: "Please enter Booking Url",
                },
            },
            errorPlacement: function(error, element) {

                if (element.parent().hasClass('input-item')) {
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
    });

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id = $(this).attr('id');
        $('#' + id + '-error').css('display', 'none');
        $('#' + id).removeClass('error');
    });
</script>
