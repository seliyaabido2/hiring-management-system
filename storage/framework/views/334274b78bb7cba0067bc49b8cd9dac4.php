<script>
    var dataTable = $('#notificationTbl').DataTable({

        processing: true,
        serverSide: true,
        lengthMenu: [10, 25, 50, 100, 500],

        ajax: {
            url: '<?php echo e(route('admin.notifications.getNotificationDatatable')); ?>',
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
                data: 'title',
                name: 'title'
            },
            {
                data: 'message',
                name: 'message'
            },
            {
                data: 'formatted_created_at',
                name: 'created_at'
            },

        ],

        initComplete: function() {
            var headerRow = $('<tr>').appendTo('#notificationTbl thead');

            this.api().columns().every(function() {
                var column = this;
                var columnIndex = column.index();
                var headerText = $(column.header()).text().trim();

                if (headerText != "Actions" && headerText != "" && headerText != "ID") {

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


        $('.delete-form').attr('action', "<?php echo e(url('admin/notifications/massDestroy')); ?>?ids=" +
            checkedIds.toString());
        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });


        // Perform further actions with the checked IDs
    });
</script>
<?php /**PATH /var/www/html/laravel/bod/resources/views/admin/customJs/notifications/index.blade.php ENDPATH**/ ?>