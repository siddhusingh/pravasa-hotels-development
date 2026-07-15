<!-- Content Wrapper. Contains page content -->
<style>
    .error-label {
        font-size: 0.875rem;
        margin-top: 4px;
        display: none;
    }

    /* Sales Visit Table */
    .sales-visit-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .sales-visit-table th,
    .sales-visit-table td {
        vertical-align: top;
    }

    .sales-date {
        font-weight: 600;
        background: #f8f9fa;
    }

    /* Column widths */
    .col-company {
        width: 22%;
    }

    .col-person {
        width: 12%;
    }

    .col-discussion {
        width: 36%;
    }

    .col-executive {
        width: 15%;
    }

    .col-action {
        width: 15%;
    }

    /* Discussion block */
    .discussion-block {
        line-height: 1.5;
    }

    /* Action icons */
    .action-icons img,
    .action-icons a {
        margin: 0 6px;
        cursor: pointer;
    }

    .action-icons i {
        font-size: 15px;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-history"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Sales History</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>Sales Operations</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Sales Visit History</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header d-flex justify-content-between align-items-center">

                            <h4 class="box-title mb-0">
                                <i class="fa fa-history"></i>
                                Manage Sales History
                            </h4>

                            <div class="d-flex align-items-center gap-2 " >

                                <!-- View Toggle -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" id="btnTableView" class="btn btn-primary active " title="Table View">
                                        <i class="fa fa-table" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" id="btnCalendarView" class="btn btn-outline-primary" title="Calendar View">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <!-- Add Button -->
                                <a href="<?= base_url('superAdmin/SalesVisits/add'); ?>"
                                    class="btn btn-primary-light btn-sm new_button">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add
                                </a>


                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <div class="box-body ">

                                    <div class="table-responsive">
                                        <div id="tableViewWrapper">

                                            <table class="text-fade table table-bordered display" id="salesVisitTable" class="table table-bordered table-striped sales-visit-table ">

                                                <!-- Date Header -->
                                                <thead class="">

                                                    <tr>
                                                        <th class="">Company Name</th>
                                                        <th class="">Person Met</th>
                                                        <th class="">Discussion Summary</th>
                                                        <th class="">Sales Executive</th>
                                                        <th class="">Report Date </th>
                                                        <th class="">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php if (!empty($sales_visits)) : ?>
                                                        <?php foreach ($sales_visits as $visit) : ?>

                                                            <tr>

                                                                <!-- Company Name -->
                                                                <td>
                                                                    <?php echo htmlspecialchars($visit->company_name); ?>
                                                                </td>

                                                                <!-- Person Met -->
                                                                <td>
                                                                    <?php echo htmlspecialchars($visit->first_name . '' . $visit->last_name); ?>
                                                                </td>

                                                                <!-- Agenda & Discussion -->
                                                                <td>
                                                                    <div class="discussion-block">
                                                                        <div>
                                                                            <strong>Agenda:</strong>
                                                                            <?php echo htmlspecialchars($visit->agenda); ?>
                                                                        </div>
                                                                        <div class="mt-1">
                                                                            <strong>Discussion:</strong>
                                                                            <?php echo nl2br(htmlspecialchars($visit->discussion_summary)); ?>
                                                                        </div>
                                                                    </div>
                                                                </td>





                                                                <!-- Sales Executive -->
                                                                <td class="text-center">
                                                                    <?php echo htmlspecialchars($visit->sales_user_name); ?>
                                                                </td>

                                                                <td class="text-center">
                                                                    <?php echo date('d-m-Y', strtotime($visit->report_date)); ?>
                                                                </td>

                                                                <!-- Actions -->
                                                                <td class="text-center action-icons">


                                                                    <a href="<?php echo base_url('edit-sales-visit/' . encrypt_id($visit->visit_id)); ?>"
                                                                        class="text-fade hover-primary"
                                                                        title="Edit Sales Visit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24"
                                                                            fill="none"
                                                                            stroke="currentColor"
                                                                            stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="feather feather-edit-2 align-middle">
                                                                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                                                        </svg>
                                                                    </a>

                                                                    <a href="javascript:void(0)" class="text-fade hover-primary delete-visit" data-record_id="<?php echo encrypt_id($visit->visit_id) ?>">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                        </svg>
                                                                    </a>




                                                                </td>

                                                            </tr>

                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted">
                                                                No sales visits found.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>

                                            </table>
                                        </div>

                                        <div id="calendarViewWrapper" class="d-none">


                                            <div id="salesVisitCalendar"></div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<!-- Modal -->
<!-- Add Travel Mode Modal -->
<!-- Add Travel Mode Modal -->


<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sales Visit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="visitModalBody">
                Loading...
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    // validation rules for comments
    toastr.options = {
        'closeButton': true,
        'debug': false,
        'newestOnTop': false,
        'progressBar': false,
        'positionClass': 'toast-top-right',
        'preventDuplicates': false,
        'showDuration': '1000',
        'hideDuration': '1000',
        'timeOut': '5000',
        'extendedTimeOut': '1000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
    }



    <?php if ($this->session->flashdata('travel_mode_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('travel_mode_success_msg'); ?>')

    <?php $this->session->set_flashdata('travel_mode_success_msg', '');
    } ?>
</script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script>
    $(document).on('click', '.delete-visit', function() {
        var visitId = $(this).data('record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This sales visit will be removed from the active visit list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            var requestData = { id: visitId };
            requestData[window.CSRF.name] = window.CSRF.hash;

            $.ajax({
                url: '<?= base_url('delete-sales-visit') ?>',
                type: 'POST',
                dataType: 'json',
                data: requestData,
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }

                    if (response.status) {
                        toastr.success(response.message);
                        window.location.reload();
                    } else {
                        toastr.error(response.message || 'Unable to delete sales visit');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    });

    $(document).ready(function() {

        var calendarEl = document.getElementById('salesVisitCalendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {

            initialView: 'dayGridWeek',
            height: 'auto',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },

            events: function(fetchInfo, successCallback, failureCallback) {

                $.ajax({
                    url: "<?= base_url('superAdmin/SalesVisits/getCalendarVisits') ?>",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        successCallback(res);
                    },
                    error: function() {
                        failureCallback();
                    }
                });

            },

            eventClick: function(info) {
                info.jsEvent.preventDefault();
                loadVisitDetails(info.event.id);
            }

        });

        calendar.render();
    });


    function loadVisitDetails(visit_id) {
        $('#visitModal').modal('show');
        $('#visitModalBody').html('Loading...');

        $.ajax({
            url: "<?= base_url('superAdmin/SalesVisits/getVisitDetails') ?>",
            type: "GET",
            data: {
                visit_id: visit_id
            },
            success: function(html) {
                $('#visitModalBody').html(html);
            }
        });
    }


    $(document).ready(function() {

        // Default View: Table
        $('#btnTableView').on('click', function() {

            $(this).addClass('btn-primary active')
                .removeClass('btn-outline-primary');

            $('#btnCalendarView').removeClass('btn-primary active')
                .addClass('btn-outline-primary');

            $('#tableViewWrapper').removeClass('d-none');
            $('#calendarViewWrapper').addClass('d-none');
        });

        // Calendar View
        $('#btnCalendarView').on('click', function() {

            $(this).addClass('btn-primary active')
                .removeClass('btn-outline-primary');

            $('#btnTableView').removeClass('btn-primary active')
                .addClass('btn-outline-primary');

            $('#calendarViewWrapper').removeClass('d-none');
            $('#tableViewWrapper').addClass('d-none');

            // IMPORTANT: re-render calendar when shown
            window.salesVisitCalendar = new FullCalendar.Calendar(
                document.getElementById('salesVisitCalendar'), {
                    initialView: 'dayGridWeek',
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek'
                    },
                    events: "<?= base_url('superAdmin/SalesVisits/getCalendarVisits') ?>",
                    eventClick: function(info) {
                        loadVisitDetails(info.event.id);
                    }
                }
            );

            window.salesVisitCalendar.render();

        });

    });
</script>
