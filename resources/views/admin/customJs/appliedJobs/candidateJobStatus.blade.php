<script>

//     function calendlyScheduleModel(scheduleurl){

//         $('#calendly-schedule-model').modal('show');
//         $('.schedule-data-url').html('<div class="calendly-inline-widget" data-url="https://calendly.com/qatest2122/phone-interview?
// utm_source=jobid&name={{ $appliedJobData->candidate->name }}&email={{ $appliedJobData->candidate->email }}" style="min-width:320px;height:630px;"></div>');
//     }
// function calendlyScheduleModel(name, email,jobId,candidateId,eventType) {
//     var scheduleurl = 'https://calendly.com/qatest2122/'+eventType+'?utm_source='+jobId+'&utm_campaign='+candidateId+'&name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email);
// alert(scheduleurl);
//     // Show the modal
//     $('#calendly-schedule-model').modal('show');

//     // Update the Calendly widget URL
//     $('.schedule-data-url').html('<div class="calendly-inline-widget" data-url="' + scheduleurl + '" style="min-width:320px;height:630px;"></div>');

// }


    function changeStatus(candidateId,jobId,currentStatus,status){

        // console.log(candidateId+' '+jobId+' '+currentStatus+' '+status);
        // return false;
       var candidateName =  $('.candidateName').html();

        if(currentStatus != ''){
                $.ajax({
                url:"{{route('admin.appliedJobs.candidateFieldChangeStatus')}}",
                type: 'post',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    candidateId:candidateId,
                    jobId:jobId,
                    currentStatus:currentStatus,
                    status:status,
                    candidateName:candidateName,
                },
                beforeSend: function() {
                $(".data-loader").addClass('loading');
                },
                success:function(response){
                    $(".data-loader").removeClass('loading');
                    if(response.status  ==  true){
                        Swal.fire("Success!", response.message, "success");
                        location.reload();

                    }
                    else{
                        Swal.fire("Error!", response.message, "error");

                    }
                },
                error:function(){
                    $(".data-loader").removeClass('loading');
                    Swal.fire("Error!", 'Error in fetch state record', "error");
                }
            });

        }

    }



    function SkipFieldStatus(candidateName,candidateId,jobId,status,field_status){

        // console.log(candidateId+','+jobId+','+status+','+field_status);
        // return false;


        $('#skip-status-model').modal('show');
        $('#skip_candidate_name').val(candidateName);
        $('#skip_candidate_id').val(candidateId);
        $('#skip_job_id').val(jobId);
        $('#skip_status').val(status);
        $('#skip_field_status').val(field_status);


        // $.ajax({
        //     url:"{{route('admin.appliedJobs.candidateFieldChangeStatus')}}",
        //     type: 'post',
        //     headers: {
        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     data: {
        //         candidateId:candidateId,
        //         jobId:jobId,
        //         status:status,
        //         field_status:field_status,

        //         },
        //     success:function(response){

        //         if(response.status  ==  true){
        //             Swal.fire("Success!", response.message, "success");
        //             location.reload();

        //         }
        //         else{
        //             Swal.fire("Error!", response.message, "error");

        //         }
        //     },
        //     error:function(){
        //         Swal.fire("Error!", 'Error in fetch state record', "error");
        //     }
        // });
    }
    function BtnEditNotesClick(id){
            $('.btn-save_'+id).css("display", "block");
            $('#additional_note_'+id).attr('readonly',false);
        }

    $(document).ready(function() {




        $('.candidate-status-update').on('click', function() {
            var id = $(this).attr('data-id');
            // $('#EditAppliedJobForm').attr('action', "{{ url('admin/appliedJobs/candidatesChangeJobStatus') }}?id=" + id);

            var status = $(this).attr('data-status');

            // alert(status);

             if (status == 'Selected') {
                 $('.selectedJobStatus').show();
             } else
             {
                $('.selectedJobStatus').hide();

             }

            if (status == 'Assessment' || status == 'Phone Interview') {
                $('.assessment_link-div').show();
            } else {
                $('.assessment_link-div').hide();
            }
            $('#candidate-status-model').modal('show');
            $('#job_status').val(status);
            //   $('#' + $(this).data('display')).toggle();
        });



        $('.accordion-button').on('click', function() {

            $(".CustomSaveNotesClass").css("display", "none");

            $('.CustomNotesClass').attr('readonly',true);

        });


    //     $('.accordion-button-custom').on('click', function() {

    //        $(".CustomSaveNotesClass2").css("display", "none");

    //        $('.CustomNotesClass2').attr('readonly',true);

    //    });




        $('.btn-collapsed-header').on('click', function() {
            $(".btn-save").css("display", "none");
            $('#additional_note').attr('readonly',true);


        });

        $('.candidate-edit-status').on('click', function() {
            var status = $(this).attr('data-id');
            var statusId = $(this).attr('data-statusId');

             if (status == 'Stand By' || status == 'Candidate not responding (No Response)') {
                 $('.stand_by-div').show();
             }
             else
             {
                $('.stand_by-div').hide();
             }


             if(status == 'Assessment'){

                $('.assessment_link-div').show();
             }
             else
             {
                $('.assessment_link-div').hide();
             }

            $('#candidate-edit-status-model').modal('show');
            $('#candidateStatus_id').val(statusId);
            $('#status').val(status);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });

        $('#EditAppliedJobForm').validate({

            ignore: [],
            rules: {

                assessment_link: {
                    required: function(element) {
                        return ($("#job_status").val() == "Phone Interview" || $("#job_status")
                        .val() == "Assessment");
                    }

                },
                additional_note: {
                    required: true,

                },
            },
            messages: {

                assessment_link: {
                    required: "Please enter link Url",
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





        // $('.skip-status-update').on('click', function() {

        //     var id = $(this).attr('data-id');
        //     // $('#EditAppliedJobForm').attr('action', "{{ url('admin/appliedJobs/candidatesChangeJobStatus') }}?id=" + id);

        //     var status = $(this).attr('data-status');


        //     $('#skip-status-model').modal('show');
        //     $('#skip_job_status').val(status);
        //     //   $('#' + $(this).data('display')).toggle();
        // });

    })

    $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id= $(this).attr('id');
        $('#'+id+'-error').css('display','none');
        $('#'+id).removeClass('error');
    });
</script>
