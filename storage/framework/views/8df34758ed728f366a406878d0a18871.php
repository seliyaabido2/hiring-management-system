<script>
    var dataTable = $('#bodCandidateTbl').DataTable({

        processing: true,
        serverSide: true,
        lengthMenu: [10, 25, 50, 100, 500],

        ajax: {
            url: '<?php echo e(route('admin.bodCandidate.getBodCandidateDatatable')); ?>',
            type: 'GET',

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
                    return '<input type="checkbox" class="select-checkbox" data-id="' +
                        data + '">';
                }
            },

            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'resume',
                name: 'resume',
                orderable: false,
                searchable: false,
                render: function(data, type, full, meta) {

                    return '<a href="' + "<?php echo e(asset('candidate_resume')); ?>/" + data +
                        '" download>Download</a>';
                }

            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'formatted_updated_at',
                name: 'updated_at'
            },
            {
                data: 'actions',
                name: 'actions'
            },

        ],

        initComplete: function() {
            var headerRow = $('<tr>').appendTo('#bodCandidateTbl thead');

            this.api().columns().every(function() {
                var column = this;
                var columnIndex = column.index();
                var headerText = $(column.header()).text().trim();

                if (headerText != "Resume" && headerText != "Action" && headerText != "" &&
                    headerText != "ID") {

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


        $('.delete-form').attr('action', "<?php echo e(url('admin/bodCandidate/massDestroy')); ?>?ids=" +
            checkedIds.toString());
        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });


        // Perform further actions with the checked IDs
    });

    
    $(document).on('click', '.delete-btn', function(){
        var itemId = $(this).data('id');

        $('.modal-body').html(
            'Are you sure you want to delete this candidate? Deleting this candidate will also remove any applied jobs associated with them.'
        );
        $('#delete-model').modal('show');
        $('.delete-form').attr('action', "<?php echo e(url('admin/bodCandidate/destroy')); ?>?id=" + itemId);
    });
    $(document).on('click', '.cancel-btn', function(){

        $(this).closest('.modal').modal('hide');
    });

</script>
<?php /**PATH /var/www/html/laravel/bod/resources/views/admin/customJs/bodCandidates/index.blade.php ENDPATH**/ ?>