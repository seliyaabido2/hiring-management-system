<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
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

            $('#additional_note').html('');
            if (status == 'Phone Interview' || status == 'Assessment') {
                $('#assessment_link').val('');
                $('.assessment_link-div').show();
                // PhoneIntereviewModel(id,status);

            } else {
                $('.assessment_link-div').hide();
                // $.ajax({
                //     url: "{{ route('admin.appliedJobs.candidatesChangeJobStatus') }}",
                //     type: 'post',
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     },
                //     data: {
                //         status: status,
                //         id: id,
                //     },
                //     success: function(status) {
                //         if (status == true) {

                //             iziToast.success({
                //                 title: 'success',
                //                 message: 'status Changed Successfully.',
                //                 position: 'topRight',

                //             });


                //         } else {
                //             iziToast.warning({
                //                 title: 'Error',
                //                 message: 'somthing Went wrong!',
                //                 position: 'topRight',

                //             });
                //         }

                //         setTimeout(function() {
                //             location.reload();
                //         }, 2000);

                //     },
                //     error: function() {
                //         Swal.fire("Error!", 'Error in update data record', "error");
                //     }
                // });

            }

        });

        $('#EditAppliedJobForm').validate({

            ignore: [],
            rules: {

                status: {
                    required: true,

                },
                assessment_link: {
                    required: function(element) {
                        return $(".CandidateJobStatus").val() == "Phone Interview";
                    }

                },
                additional_note: {
                    required: true,

                },
            },
            messages: {
                status: {
                    required: "Please select status",
                },
                assessment_link: {
                    required: "Please enter Booking Url",
                },
                additional_note: {
                    required: "Please enter note",
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
