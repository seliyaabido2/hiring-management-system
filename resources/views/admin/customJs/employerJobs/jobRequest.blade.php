<script>

        function ChangeStatus(jobId){

            var status = $('#status').val();

            window.location.href = "{{ url('admin/employerJobs/chageJobRequestStatus') }}/"+jobId+'/'+status;


        }

        $('input').on('change', function() {
        // Your code to handle the 'change' event here
        var id= $(this).attr('id');
        $('#'+id+'-error').css('display','none');
        $('#'+id).removeClass('error');
    });

</script>
