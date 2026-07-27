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
                        <li><i class="fa fa-home"></i></li>
                        <li>Sales Executive</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Sales Operations</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Sales Visit History</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img-add.png') ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div
                            class="box-header d-flex justify-content-between align-items-center"
                        >
                            <h4 class="box-title mb-0">
                                <i class="fa fa-history"></i>
                                Manage Sales History
                            </h4>
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    class="btn-group btn-group-sm"
                                    role="group"
                                >
                                    <button
                                        type="button"
                                        id="btnTableView"
                                        class="btn btn-primary active"
                                        title="Table View"
                                    >
                                        <i
                                            class="fa fa-table"
                                            aria-hidden="true"
                                        ></i>
                                    </button>
                                    <button
                                        type="button"
                                        id="btnCalendarView"
                                        class="btn btn-outline-primary"
                                        title="Calendar View"
                                    >
                                        <i
                                            class="fa fa-calendar"
                                            aria-hidden="true"
                                        ></i>
                                    </button>
                                </div>
                                <a
                                    href="<?= base_url('sales/visits/add') ?>"
                                    class="btn btn-primary-light btn-sm new_button"
                                >
                                    <i
                                        class="fa fa-plus"
                                        aria-hidden="true"
                                    ></i>
                                    Add
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <div
                                id="tableViewWrapper"
                                class="table-responsive"
                            >
                                <table
                                    id="server-side-data-table"
                                    class="text-fade table table-bordered display"
                                    style="width:100%"
                                >
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Company Name</th>
                                            <th>Person Met</th>
                                            <th>Discussion Summary</th>
                                            <th>Visit Type</th>
                                            <th>Visit Mode</th>
                                            <th>Sales Executive</th>
                                            <th>Report Date</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="calendarViewWrapper" class="d-none">
                                <div id="salesVisitCalendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sales Visit Details</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body" id="visitModalBody">
                Loading...
            </div>
        </div>
    </div>
</div>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css"
>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script>
window.CSRF = window.CSRF || {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
};

window.addEventListener('load', function () {
    var calendar = null;

    var visitsTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        searchDelay: 500,
        order: [[7, 'desc']],
        ajax: {
            url: <?= json_encode(base_url('sales/visits/table')) ?>,
            type: 'POST',
            data: function (data) {
                data[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function (response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                return response.data || [];
            },
            error: function (xhr) {
                var response = xhr.responseJSON || {};
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                toastr.error(
                    response.message || 'Unable to load sales visits'
                );
            }
        },
        columnDefs: [
            { targets: [3, 9], orderable: false },
            {
                targets: 9,
                searchable: false,
                className: 'table-action min-w-100'
            }
        ]
    });

    function reloadVisitViews(resetPaging) {
        visitsTable.ajax.reload(function () {
            if (calendar) {
                calendar.refetchEvents();
            }
        }, resetPaging);
    }

    $(document).on('click', '.delete-visit', function () {
        var visitId = $(this).data('record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This sales visit will be removed from the active visit list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            var requestData = { id: visitId };
            requestData[window.CSRF.name] = window.CSRF.hash;

            $.ajax({
                url: <?= json_encode(base_url('sales/visits/delete')) ?>,
                type: 'POST',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }

                    if (response.status) {
                        toastr.success(response.message);
                        reloadVisitViews(false);
                    } else {
                        toastr.error(
                            response.message ||
                                'Unable to delete sales visit'
                        );
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    toastr.error(
                        response.message ||
                            'Unable to delete sales visit'
                    );
                }
            });
        });
    });

    function initializeCalendar() {
        if (calendar || typeof FullCalendar === 'undefined') {
            return;
        }

        calendar = new FullCalendar.Calendar(
            document.getElementById('salesVisitCalendar'),
            {
                initialView: 'dayGridWeek',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: <?= json_encode(
                    base_url('sales/visits/calendar')
                ) ?>,
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    loadVisitDetails(info.event.id);
                }
            }
        );
        calendar.render();
    }

    function loadVisitDetails(visitId) {
        $('#visitModal').modal('show');
        $('#visitModalBody').html('Loading...');

        $.ajax({
            url: <?= json_encode(base_url('sales/visits/details')) ?>,
            type: 'GET',
            data: { visit_id: visitId },
            success: function (html) {
                $('#visitModalBody').html(html);
            },
            error: function (xhr) {
                $('#visitModalBody').html(
                    xhr.responseText ||
                    '<div class="alert alert-danger mb-0">' +
                    'Unable to load visit details.</div>'
                );
            }
        });
    }

    $('#btnTableView').on('click', function () {
        $(this)
            .addClass('btn-primary active')
            .removeClass('btn-outline-primary');
        $('#btnCalendarView')
            .removeClass('btn-primary active')
            .addClass('btn-outline-primary');
        $('#tableViewWrapper').removeClass('d-none');
        $('#calendarViewWrapper').addClass('d-none');
    });

    $('#btnCalendarView').on('click', function () {
        $(this)
            .addClass('btn-primary active')
            .removeClass('btn-outline-primary');
        $('#btnTableView')
            .removeClass('btn-primary active')
            .addClass('btn-outline-primary');
        $('#calendarViewWrapper').removeClass('d-none');
        $('#tableViewWrapper').addClass('d-none');

        initializeCalendar();
        if (calendar) {
            calendar.updateSize();
        }
    });

    <?php if (!empty($sales_visit_success)): ?>
        var successMessage = <?= json_encode(
            $sales_visit_success,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        ) ?>;

        if (typeof window.showSalesToast === 'function') {
            window.showSalesToast('success', successMessage);
        } else if (window.toastr) {
            toastr.success(successMessage);
        }
    <?php endif; ?>
});
</script>
