


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(trans('panel.site_title')); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet" />
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet" />
    
    

    <style>
        .loading {
            position: fixed;
            z-index: 1111;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

            background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
        }

        / :not(:required) hides these rules from IE9 and below / .loading:not(:required) {
            / hide "loading..." text / font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        }


        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<?php
//  $d = downloadAndStorePDF();
//  dd($d);
?>
<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">
    <div class="data-loader"></div>
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <span class="navbar-brand-full"><img src="<?php echo e(asset('images/bod-logo.png')); ?>" class="img-fluid" alt=""></span>
            <!-- <span class="navbar-brand-full"><?php echo e(trans('panel.site_title')); ?></span> -->
            <span class="navbar-brand-minimized"><img src="<?php echo e(asset('images/bod-logo.png')); ?>" class="img-fluid" alt=""></span>
        </a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav ml-auto">
            <?php if(count(config('panel.available_languages', [])) > 1): ?>
            <li class="nav-item dropdown d-md-down-none">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo e(strtoupper(app()->getLocale())); ?>

                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <?php $__currentLoopData = config('panel.available_languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $langLocale => $langName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="dropdown-item" href="<?php echo e(url()->current()); ?>?change_language=<?php echo e($langLocale); ?>"><?php echo e(strtoupper($langLocale)); ?> (<?php echo e($langName); ?>)</a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </li>
            <?php endif; ?>
        </ul>
<div class="d-flex gap-lg-4 gap-2 align-items-center">

<div class="dropdown">
  <a class="dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?php if(notificationsCount() >0): ?>
    <span class="badge badge-danger navbar-badge notification-badge"><?php echo e(notificationsCount()); ?></span>
    <?php endif; ?>
    
  <i class="fa-regular fa-bell"></i>

  </a>

  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right " style="left: inherit;">
    <span class="dropdown-item dropdown-header"> <?php echo e(notificationsCount()); ?> Notifications</span>
    <?php
       $notificationsList = notificationsList();
    ?>

    <?php if(count($notificationsList) > 0): ?>
        <?php $__currentLoopData = $notificationsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
             $notifytime = convertToAgoTime($list->updated_at);
            ?>
        <div class="dropdown-divider"></div>
        <div class="notification-menu">
        <div class="align-items-center d-flex notification-list">
        <a href="<?php echo e(route('admin.notifications.markAsRead',encrypt_data($list->id))); ?>" class="dropdown-item pb-0 ">
        <i class="fas fa-envelope mr-2 mt-1"></i>
        <span class="notification-list-title"> <?php echo e($list->title); ?></span>
            </a>
        </div>
    <span class="text-muted text-sm notification-time">&nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; <?php echo e($notifytime); ?></span>
    </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <div class="dropdown-divider"></div>
    <a href="<?php echo e(route('admin.notifications.markAsRead',encrypt_data('All'))); ?>" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>

  
</div>

<div class="nav navbar-nav ml-auto avatar-drop dropdown">

       <a class="nav-link" data-toggle="dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar avatar-md">

                    <img class="avatar-img" src="<?php echo e(asset('user_image/' . Auth::user()->image)); ?>">

                </div>
            </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a class="dropdown-item" href="<?php echo e(route('admin.users.profile.edit')); ?>">Profile Update</a></li>

        <li><a class="dropdown-item" href="<?php echo e(route('admin.users.password.change')); ?>">Change Passowrd</a></li>

        <li>
            <hr class="dropdown-divider">
        </li>
        <li> <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                <?php echo e(trans('global.logout')); ?>

            </a></li>
    </ul>

  </div>

        
        

            


          </div>
        </header>


    <div class="app-body">
        <?php echo $__env->make('partials.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="main">


            <div style="padding-top: 20px" class="container-fluid">
                <?php if(session('message')): ?>
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert"><?php echo e(session('message')); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- <?php if($errors->count() > 0): ?>
                    <div class="alert alert-danger auto-hide">
                        <ul class="list-unstyled">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?> -->
                <?php echo $__env->yieldContent('content'); ?>

                <!-- START- DELETE MODEL -->
                <div class="modal fade" id="delete-model" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmation</h5>
                                <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this item?
                            </div>
                            <div class="modal-footer">
                                <!-- Perform the delete action when confirmed -->
                                <form class="delete-form" action="" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <!-- Close the modal dialog -->
                                <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <!-- END- DELETE MODEL -->

                </div>
        </main>
        <form id="logoutform" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo e(csrf_field()); ?>

        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script> -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="<?php echo e(asset('js/main.js')); ?>"></script>

    <!-- iziToast CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/iziToast.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/buttons.bootstrap4.min.css')); ?>">


    <!-- iziToast JS Scripts -->
    <script src="<?php echo e(asset('js/iziToast.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/sweetalert2.min.js')); ?>"></script>

    <script>
        //  $(function() {
        //     let copyButtonTrans = '<?php echo e(__('global.datatables.copy')); ?>';
        //     let csvButtonTrans = '<?php echo e(__('global.datatables.csv')); ?>';
        //     let excelButtonTrans = '<?php echo e(__('global.datatables.excel')); ?>';
        //     let pdfButtonTrans = '<?php echo e(__('global.datatables.pdf')); ?>';
        //     let printButtonTrans = '<?php echo e(__('global.datatables.print')); ?>';
        //     let colvisButtonTrans = '<?php echo e(__('global.datatables.colvis')); ?>';

        //     let languages = {
        //         'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
        //     };

        //     $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
        //         className: 'btn'
        //     })
        //     $.extend(true, $.fn.dataTable.defaults, {
        //         language: {
        //             url: languages['<?php echo e(app()->getLocale()); ?>']
        //         },
        //         //   columnDefs: [
        //         //     {
        //         //       orderable: false,
        //         //       className: 'select-checkbox',
        //         //       targets: 0
        //         //   }, {
        //         //       orderable: false,
        //         //       searchable: false,
        //         //       targets: -1
        //         //   }],
        //         //   select: {
        //         //     style:    'multi+shift',
        //         //     selector: 'td:first-child'
        //         //   },
        //         order: [],
        //         scrollX: true,
        //         pageLength: 10,
        //         dom: 'lBfrtip<"actions">',
        //         buttons: [
        //             {
        //                 extend: 'copy',
        //                 className: 'btn-default',
        //                 text: copyButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             },
        //             {
        //                 extend: 'csv',
        //                 className: 'btn-default',
        //                 text: csvButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             },
        //             {
        //                 extend: 'excel',
        //                 className: 'btn-default',
        //                 text: excelButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             },
        //             {
        //                 extend: 'pdf',
        //                 className: 'btn-default',
        //                 text: pdfButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             },
        //             {
        //                 extend: 'print',
        //                 className: 'btn-default',
        //                 text: printButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             },
        //             {
        //                 extend: 'colvis',
        //                 className: 'btn-default',
        //                 text: colvisButtonTrans,
        //                 exportOptions: {
        //                     columns: ':visible'
        //                 }
        //             }
        //         ]
        //     });

        //     $.fn.dataTable.ext.classes.sPageButton = '';
        // });
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>


    <?php echo $__env->yieldPushContent('js'); ?>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".auto-hide").slideUp(function() {
                    $(this).remove();
                });
            }, 10000);

            $(".close").on('click', function(){
                $(".manual-hide").alert('close');
            });
        });
    </script>

    <?php if(Session::has('success')): ?>
    <script>
        iziToast.success({
            title: 'Success',
            message: "<?= Session::get('success') ?>",
            position: 'topRight'
        });
    </script>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
    <script>
        iziToast.error({
            title: 'Error',
            message: "<?= Session::get('error') ?>",
            position: 'topRight'
        });
    </script>
    <?php endif; ?>





<script>
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('.datatable-User thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('.datatable-User thead');

        var table = $('.datatable-User').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            pageLength: 10,
            initComplete: function() {
                var api = this.api();
                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function(colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();

                        if (title.trim() == 'No' || title.trim() == 'ID' || title.trim() == 'Action') {
                            $(cell).html('');
                        } else {
                            $(cell).html('<input type="text" />');
                        }

                        // On every keypress in this input
                        $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                            .off('keyup change')
                            .on('change', function(e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != '' ?
                                        regexr.replace('{search}', '(((' + this.value + ')))') :
                                        '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function(e) {
                                e.stopPropagation();

                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            },
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
    });
</script>


<script type="text/javascript"
src="https://maps.google.com/maps/api/js?key=<?php echo e(config('app.google_map_key')); ?>&libraries=places" ></script>
<script>
    $(document).on('change', '#location', function() {
        $('.location-error').html('');
        $('#latitude').val('');
        $('#longitude').val('');
    });

    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {

        var input = document.getElementById('location');
        var options = {
            types: ['(regions)'],
            componentRestrictions: { country: 'us' } // Restrict to the United States
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function () {

            var place = autocomplete.getPlace();
            $('#latitude').val(place.geometry['location'].lat());
            $('#longitude').val(place.geometry['location'].lng());
        });

        // Function to populate the dropdown with suggestions
        function selectLocation(input) {
            var place = autocomplete.getPlace();
            if (place) {
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
            }
        }
    }

</script>








</body>




</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/layouts/admin.blade.php ENDPATH**/ ?>