<style>
    .report-filter-card {
        padding: 18px;
        margin-bottom: 22px;
        border: 1px solid #ececf3;
        border-radius: 10px;
        background: #fff;
    }

    .report-filter-card .form-label {
        margin-bottom: 6px;
        font-weight: 500;
    }

    .report-filter-card .select2-container {
        width: 100% !important;
    }

    .report-filter-card .select2-container .select2-selection--single {
        height: 40px;
        border: 1px solid #d9d9e3;
        border-radius: 5px;
    }

    .report-filter-card .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-left: 12px;
    }

    .report-filter-card .select2-container .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .report-filter-card .lead-filter-multiselect-source {
        display: none !important;
    }

    .report-filter-card .lead-filter-multiselect {
        position: relative;
        width: 100%;
    }

  

    .report-filter-card .lead-filter-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        flex: 0 0 auto;
        margin-left: 10px;
    }

    .report-filter-card .lead-filter-multiselect.is-open .lead-filter-multiselect-toggle,
    .report-filter-card .lead-filter-multiselect-toggle:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        outline: 0;
    }

    .report-filter-card .lead-filter-multiselect.is-open .lead-filter-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    .report-filter-card .lead-filter-multiselect-menu {
        background: #fff;
        border: 1px solid #d2d2d2;
        border-radius: 6px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.16);
        display: none;
        left: 0;
        max-height: 260px;
        overflow-y: auto;
        padding: 6px 0;
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        z-index: 1080;
    }

    .report-filter-card .lead-filter-multiselect.is-open .lead-filter-multiselect-menu {
        display: block;
    }

    .report-filter-card .lead-filter-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 10px;
        margin: 0;
        padding: 9px 14px;
    }

    .report-filter-card .lead-filter-multiselect-option:hover {
        background: #f4f4f4;
    }

    .report-filter-card .lead-filter-multiselect-option input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        accent-color: #1473d2;
        clip: auto !important;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 20px;
        height: 20px !important;
        left: auto !important;
        margin: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 20px !important;
    }

    .report-filter-card .lead-filter-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    #report-filter-error {
        display: none;
        margin-top: 10px;
    }

    #server-side-data-table th,
    #server-side-data-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .theme-primary .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border: 1px solid #23211d;
        background-color: #fff;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-file-text"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Lead Reports</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Agent</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Custom Report</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Lead Reports</h4>
                        </div>
                        <div class="box-body">
                            <form id="report-filter-form" class="report-filter-card" autocomplete="off">
                                <div class="row g-3 align-items-end">
                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_property" class="form-label">Hotel</label>
                                        <select id="report_property" class="form-select report-select2" disabled>
                                            <option value="<?= (int) ($property->hotel_id ?? 0); ?>" selected>
                                                <?= htmlspecialchars($property->hotel_name ?? 'Selected Hotel', ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_department" class="form-label">Department</label>
                                        <select id="report_department" name="department[]" class="form-select lead-filter-multiselect-source" data-placeholder="All Departments" multiple>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?= (int) $department->department_id; ?>">
                                                    <?= htmlspecialchars($department->department_name, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_status" class="form-label">Status</label>
                                        <select id="report_status" name="status[]" class="form-select lead-filter-multiselect-source" data-placeholder="All Statuses" multiple>
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_channel" class="form-label">Lead Source</label>
                                        <select id="report_channel" name="channel[]" class="form-select lead-filter-multiselect-source" data-placeholder="All Sources" multiple>
                                            <?php foreach ($channels as $channel): ?>
                                                <option value="<?= htmlspecialchars($channel, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars(strtoupper($channel), ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_disposition" class="form-label">Stage</label>
                                        <select id="report_disposition" name="disposition[]" class="form-select lead-filter-multiselect-source" data-placeholder="All Stages" multiple>
                                            <?php foreach ($dispositions as $disposition): ?>
                                                <option value="<?= htmlspecialchars($disposition, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($disposition, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_start_date" class="form-label">Start Date</label>
                                        <input type="date" id="report_start_date" name="start_date" class="form-control">
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="report_end_date" class="form-label">End Date</label>
                                        <input type="date" id="report_end_date" name="end_date" class="form-control">
                                    </div>

                                    <div class="col-xl-3 col-md-6 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <button type="button" id="reset-report-filters" class="btn btn-primary-light">Reset</button>
                                    </div>
                                </div>
                                <div id="report-filter-error" class="text-danger" role="alert"></div>
                            </form>

                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Lead ID</th>
                                            <th>City</th>
                                            <th>Property</th>
                                            <th>Department</th>
                                            <th>Guest Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Stage</th>
                                            <th>Source</th>
                                            <th>Created Date</th>
                                            <th>Response Date</th>
                                            <th>Completed Date</th>
                                            <th>Booking Date</th>
                                            <th>Check In Date</th>
                                            <th>Check Out Date</th>
                                            <th>Follow up 1</th>
                                            <th>Follow up 2</th>
                                            <th>Pax</th>
                                            <th>Query</th>
                                            <th>Remark</th>
                                            <th>Revenue</th>
                                            <th>Materialization</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    window.CSRF = window.CSRF || {
        name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?= json_encode($this->security->get_csrf_hash()); ?>
    };

    window.addEventListener('load', function () {
        var $ = window.jQuery;
        if (!$ || !$.fn.DataTable) {
            return;
        }

        $('.report-select2').each(function () {
            var $select = $(this);
            $select.select2({
                width: '100%',
                placeholder: $select.data('placeholder') || '',
                allowClear: !$select.prop('disabled')
            });
        });

        function syncReportMultiSelect($select, $widget) {
            var selectedValues = ($select.val() || []).map(String);
            var $items = $widget.find('.lead-filter-multiselect-item');
            var selectedCount = selectedValues.length;
            var total = $items.length;

            $items.each(function () {
                $(this).prop('checked', selectedValues.indexOf(String($(this).val())) !== -1);
            });

            var $selectAll = $widget.find('.lead-filter-multiselect-all');
            $selectAll.prop('checked', total > 0 && selectedCount === total);
            $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < total);

            var summary = $select.data('placeholder') || 'Select Options';
            if (selectedCount > 0 && selectedCount === total) {
                summary = 'All selected (' + selectedCount + ')';
            } else if (selectedCount > 0) {
                summary = selectedCount + ' selected';
            }

            $widget.find('.lead-filter-multiselect-summary').text(summary);
        }

        function initializeReportMultiSelect(select) {
            var $select = $(select);
            var placeholder = $select.data('placeholder') || 'Select Options';

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.next('.lead-filter-multiselect').remove();

            var $widget = $('<div>', { class: 'lead-filter-multiselect' });
            var $toggle = $('<button>', {
                type: 'button',
                class: 'lead-filter-multiselect-toggle',
                'aria-expanded': 'false',
                'aria-haspopup': 'true',
                'aria-label': placeholder
            }).append($('<span>', {
                class: 'lead-filter-multiselect-summary',
                text: placeholder
            }));
            var $menu = $('<div>', {
                class: 'lead-filter-multiselect-menu',
                role: 'group',
                'aria-label': placeholder
            });
            var $options = $select.find('option').filter(function () {
                return String(this.value).trim() !== '' && !this.disabled;
            });

            if ($options.length) {
                $menu.append(
                    $('<label>', {
                        class: 'lead-filter-multiselect-option lead-filter-multiselect-select-all'
                    }).append(
                        $('<input>', {
                            type: 'checkbox',
                            class: 'lead-filter-multiselect-all'
                        }),
                        $('<span>', { text: 'Select all' })
                    )
                );

                $options.each(function () {
                    $menu.append(
                        $('<label>', { class: 'lead-filter-multiselect-option' }).append(
                            $('<input>', {
                                type: 'checkbox',
                                class: 'lead-filter-multiselect-item',
                                value: this.value
                            }),
                            $('<span>').text($(this).text().trim())
                        )
                    );
                });
            } else {
                $menu.append($('<div>', {
                    class: 'lead-filter-multiselect-option text-muted',
                    text: 'No options available'
                }));
            }

            $widget.append($toggle, $menu);
            $select.after($widget);

            $toggle.on('click', function () {
                var isOpen = !$widget.hasClass('is-open');
                $('.report-filter-card .lead-filter-multiselect').not($widget)
                    .removeClass('is-open')
                    .find('.lead-filter-multiselect-toggle').attr('aria-expanded', 'false');
                $widget.toggleClass('is-open', isOpen);
                $toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
            });

            $widget.on('change', '.lead-filter-multiselect-all', function () {
                var values = this.checked
                    ? $widget.find('.lead-filter-multiselect-item').map(function () {
                        return this.value;
                    }).get()
                    : [];
                $select.val(values).trigger('change');
            });

            $widget.on('change', '.lead-filter-multiselect-item', function () {
                var values = $widget.find('.lead-filter-multiselect-item:checked').map(function () {
                    return this.value;
                }).get();
                $select.val(values).trigger('change');
            });

            $select.off('change.reportMultiSelect').on('change.reportMultiSelect', function () {
                syncReportMultiSelect($select, $widget);
            });

            syncReportMultiSelect($select, $widget);
        }

        $('#report_department, #report_status, #report_channel, #report_disposition').each(function () {
            initializeReportMultiSelect(this);
        });

        $(document).off('click.reportMultiSelect').on('click.reportMultiSelect', function (event) {
            if (!$(event.target).closest('.lead-filter-multiselect').length) {
                $('.report-filter-card .lead-filter-multiselect').removeClass('is-open')
                    .find('.lead-filter-multiselect-toggle').attr('aria-expanded', 'false');
            }
        });

        var $error = $('#report-filter-error');
        var table = $('#server-side-data-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[10, 'desc']],
            ajax: {
                url: <?= json_encode(base_url('agent/Reports/data_table')); ?>,
                type: 'POST',
                data: function (data) {
                    data.department = $('#report_department').val() || [];
                    data.status = $('#report_status').val() || [];
                    data.channel = $('#report_channel').val() || [];
                    data.disposition = $('#report_disposition').val() || [];
                    data.start_date = $('#report_start_date').val();
                    data.end_date = $('#report_end_date').val();
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function (json) {
                    if (json.csrfHash) {
                        window.CSRF.hash = json.csrfHash;
                    }
                    $error.hide().text('');
                    return json.data || [];
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    $error.text(response.message || 'Unable to load the report. Please refresh and try again.').show();
                }
            }
        });

        $('#report-filter-form').on('submit', function (event) {
            event.preventDefault();
            var start = $('#report_start_date').val();
            var end = $('#report_end_date').val();

            if (start && end && start > end) {
                $error.text('Start Date cannot be after End Date.').show();
                return;
            }

            $error.hide().text('');
            table.ajax.reload();
        });

        $('#reset-report-filters').on('click', function () {
            $('#report-filter-form')[0].reset();
            $('.lead-filter-multiselect-source').val([]).trigger('change');
            $error.hide().text('');
            table.search('').ajax.reload();
        });
    });
</script>
