<script>
   var dataTable = $('#subAdminTbl').DataTable({
    processing: true,
    serverSide: true,
    lengthMenu: [10, 25, 50, 100, 500],
    ajax: {
        url: '{{ route('admin.SubAdmin.getSubAdminDatatable') }}',
        type: 'GET',
    },
    columns: [

        {
            data: 'first_name',
            name: 'first_name'
        },
    
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'admin_detail.phone_no',
            name: 'AdminDetail.phone_no'
        },
        {
            data: 'admin_detail.designation', // Update to the nested property used in the server-side code
            name: 'AdminDetail.designation'
        },
        {
            data: 'admin_detail.location', // Update to the nested property used in the server-side code
            name: 'AdminDetail.location'
        },
        
        {
            data: 'status', // Update to the nested property used in the server-side code
            name: 'status'
        },
        {
                data: 'actions',
                name: 'actions'
        },
    ],
    initComplete: function () {
        var headerRow = $('<tr>').appendTo('#subAdminTbl thead');

        this.api().columns().every(function () {
            var column = this;
            var columnIndex = column.index();
            var headerText = $(column.header()).text().trim();

            if (headerText !== "Actions" && headerText !== "" && headerText !== "ID") {

                // Append a new header cell
                var input;
                if (headerText === "Phone No") { // Check if the header is for the 'phone_no' column
                    input = $(
                        '<input type="text" class="form-control" placeholder="Search ' +
                        headerText + '" />'
                    ).on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                } else {
                    input = $(
                        '<input type="text" class="form-control" placeholder="Search ' +
                        headerText + '" />'
                    ).on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                }

                $('<th>').html(input).appendTo(headerRow);

            } else {
                $('<th>').html("").appendTo(headerRow);
            }
        });
    },
});


    $('#select-all-checkbox').change(function() {

        $('.select-checkbox').prop('checked', this.checked);
        dataTable.rows().deselect();
        if (this.checked) {
            dataTable.rows().select();
        }
    });

    $('#Bulkdelete').on('click', function() {
        var checkedIds = [];
        $('.select-checkbox:checked').each(function() {
            checkedIds.push($(this).data('id'));
        });

        if (checkedIds.length == 0) {
            iziToast.error({
                title: 'Error',
                message: 'Please select at least one Notifiction.!',
                position: 'topRight',

            });
            return false;
        }


        $('.modal-body').html('Are you sure you?');
        $('.delete-form .btn').removeClass('btn-danger');
        $('.delete-form .btn').addClass('btn-danger');
        $('.delete-form .btn').html('Delete');
        $('#delete-model').modal('show');

        // $('input[name="_method"]').val('post');
        // Perform further actions with the checked IDs
    });
</script>


<!-- delete Data script -->
<script>
    
   
        $(document).on('click', '.delete-btn', function(){
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/users/destroy') }}?id=" + itemId);

        });

       
        $(document).on('click', '.cancel-btn', function(){
            $(this).closest('.modal').modal('hide');
        });


        $(document).on('click', '.add-calendly-btn', function(){

            var itemId = $(this).data('id');
            $('.modal-body').html('Are you sure you want to add in calendly');
            $('.delete-form .btn').removeClass('btn-danger');
            $('.delete-form .btn').addClass('btn-success');
            $('.delete-form .btn').html('Add');
            $('#delete-model').modal('show');

            $('.delete-form').attr('action', "{{ url('admin/users/add-calendly-user') }}?id=" + itemId);
            $('input[name="_method"]').val('POST');

            $('.btn-success').click(function() {
            $(this).closest('.modal').modal('hide');
            });
        });

</script>
