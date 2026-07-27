<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-history"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Monitor Sales Visits</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Sales Manager</li>
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
                                Monitor Sales Visits
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
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label
                                        for="executiveFilter"
                                        class="form-label"
                                    >
                                        Sales Executive
                                    </label>
                                    <select
                                        id="executiveFilter"
                                        class="form-control"
                                    >
                                        <option value="">
                                            All Sales Executives
                                        </option>
                                        <?php foreach (
                                            $visit_executives as $executive
                                        ): ?>
                                            <option
                                                value="<?= html_escape(
                                                    encrypt_id($executive->id)
                                                ) ?>"
                                            >
                                                <?= html_escape(
                                                    $executive->full_name
                                                ) ?>
                                                <?= (int)$executive->status === 1
                                                    ? ''
                                                    : ' (Inactive)' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 d-none">
                                    <label
                                        for="visitTypeFilter"
                                        class="form-label"
                                    >
                                        Visit Type
                                    </label>
                                    <select
                                        id="visitTypeFilter"
                                        class="form-control"
                                    >
                                        <option value="">All Visit Types</option>
                                        <?php foreach (
                                            $visit_types as $visitType
                                        ): ?>
                                            <option
                                                value="<?= html_escape(
                                                    $visitType
                                                ) ?>"
                                            >
                                                <?= html_escape($visitType) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 d-none">
                                    <label
                                        for="visitModeFilter"
                                        class="form-label"
                                    >
                                        Visit Mode
                                    </label>
                                    <select
                                        id="visitModeFilter"
                                        class="form-control"
                                    >
                                        <option value="">All Visit Modes</option>
                                        <?php foreach (
                                            $visit_modes as $visitMode
                                        ): ?>
                                            <option
                                                value="<?= html_escape(
                                                    $visitMode
                                                ) ?>"
                                            >
                                                <?= html_escape($visitMode) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        for="companyFilter"
                                        class="form-label"
                                    >
                                        Company
                                    </label>
                                    <select
                                        id="companyFilter"
                                        class="form-control"
                                    >
                                        <option value="">All Companies</option>
                                        <?php foreach (
                                            $visit_companies as $company
                                        ): ?>
                                            <option
                                                value="<?= html_escape(
                                                    encrypt_id(
                                                        $company->company_id
                                                    )
                                                ) ?>"
                                            >
                                                <?= html_escape(
                                                    $company->company_name
                                                ) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        for="createdDateRangeFilter"
                                        class="form-label"
                                    >
                                        Created Date Range
                                    </label>
                                    <input
                                        type="text"
                                        id="createdDateRangeFilter"
                                        class="form-control"
                                        placeholder="Select Created Date Range"
                                        autocomplete="off"
                                        readonly
                                    >
                                </div>
                            </div>

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
<script
    defer
    src="<?= base_url(
        'assets/assets/vendor_components/moment/min/moment.min.js'
    ) ?>"
></script>
<script
    defer
    src="<?= base_url(
        'assets/assets/vendor_components/bootstrap-daterangepicker/' .
        'daterangepicker.js'
    ) ?>"
></script>

<script>
window.CSRF = window.CSRF || {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
};

window.addEventListener('load', function () {
    var calendar = null;
    var filterTimer = null;
    var visitsTable = null;
    var createdStartDate = '';
    var createdEndDate = '';

    function initializeVisitFilters() {
        $('#executiveFilter').select2({
            width: '100%',
            placeholder: 'All Sales Executives',
            allowClear: true
        });
        $('#visitTypeFilter').select2({
            width: '100%',
            placeholder: 'All Visit Types',
            allowClear: true
        });
        $('#visitModeFilter').select2({
            width: '100%',
            placeholder: 'All Visit Modes',
            allowClear: true
        });
        $('#companyFilter').select2({
            width: '100%',
            placeholder: 'All Companies',
            allowClear: true
        });
    }

    function initializeCreatedDateRangeFilter() {
        if (
            typeof moment === 'undefined' ||
            typeof $.fn.daterangepicker !== 'function'
        ) {
            return;
        }

        var $dateRange = $('#createdDateRangeFilter');
        $dateRange.daterangepicker({
            autoUpdateInput: false,
            opens: 'right',
            buttonClasses: 'btn btn-sm',
            cancelClass: 'btn-light',
            applyClass: 'btn-primary',
            locale: {
                format: 'DD-MM-YYYY',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel'
            }
        });

        var picker = $dateRange.data('daterangepicker');
        picker.container
            .find('.calendar')
            .wrapAll('<div class="clearfix"></div>');
        var $pickerFooter = $(
            '<div class="d-flex justify-content-start"></div>'
        )
            .css({
                clear: 'both',
                width: '100%'
            })
            .appendTo(picker.container);
        picker.container
            .find('.ranges')
            .css({
                float: 'none',
                width: 'auto'
            })
            .appendTo($pickerFooter);
        picker.container
            .find('.range_inputs')
            .addClass('d-flex justify-content-start');
        picker.container
            .find('.cancelBtn')
            .insertBefore(picker.container.find('.applyBtn'));
        picker.container.find('.applyBtn').addClass('ml-2');

        $dateRange.on(
            'apply.daterangepicker',
            function (event, picker) {
                createdStartDate = picker.startDate.format('YYYY-MM-DD');
                createdEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(
                    picker.startDate.format('DD-MM-YYYY') +
                    ' - ' +
                    picker.endDate.format('DD-MM-YYYY')
                );
                reloadVisitViews(true);
            }
        );

        $dateRange.on('cancel.daterangepicker', function () {
            createdStartDate = '';
            createdEndDate = '';
            $(this).val('');
            reloadVisitViews(true);
        });
    }

    function initializeVisitsTable() {
        visitsTable = $('#server-side-data-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            paging: false,
            searching: false,
            searchDelay: 500,
            order: [[8, 'desc']],
            ajax: {
                url: <?= json_encode(base_url('sales/visits/table')) ?>,
                type: 'POST',
                data: function (data) {
                    data.executive_id =
                        $('#executiveFilter').val() || '';
                    data.visit_type =
                        $('#visitTypeFilter').val() || '';
                    data.visit_mode =
                        $('#visitModeFilter').val() || '';
                    data.company_id =
                        $('#companyFilter').val() || '';
                    data.created_start_date = createdStartDate;
                    data.created_end_date = createdEndDate;
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
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 9],
                    orderable: false
                },
                {
                    targets: 9,
                    searchable: false,
                    className: 'table-action min-w-100'
                }
            ]
        });
    }

    initializeVisitFilters();
    initializeVisitsTable();
    initializeCreatedDateRangeFilter();

    function reloadVisitViews(resetPaging) {
        visitsTable.ajax.reload(function () {
            if (calendar) {
                calendar.refetchEvents();
            }
        }, resetPaging);
    }

    $(
        '#executiveFilter, #visitTypeFilter, ' +
        '#visitModeFilter, #companyFilter'
    ).on('change', function () {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(function () {
            reloadVisitViews(true);
        }, 250);
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
                events: {
                    url: <?= json_encode(
                        base_url('sales/visits/calendar')
                    ) ?>,
                    extraParams: function () {
                        return {
                            executive_id:
                                $('#executiveFilter').val() || '',
                            visit_type:
                                $('#visitTypeFilter').val() || '',
                            visit_mode:
                                $('#visitModeFilter').val() || '',
                            company_id:
                                $('#companyFilter').val() || '',
                            created_start_date: createdStartDate,
                            created_end_date: createdEndDate
                        };
                    }
                },
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

    $(document).on('click', '.view-visit', function () {
        loadVisitDetails($(this).data('record_id'));
    });

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
});
</script>
