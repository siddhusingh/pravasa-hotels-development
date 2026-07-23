<!-- Content Wrapper. Contains page content -->
<style>
    .theme-primary .dt-buttons .dt-button {
        background-color: #f3f1edff !important
    }

    .theme-primary .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border: 1px solid #23211d;
        background-color: #ffffff;
    }

    .lead-report-filters .report-multiselect-source {
        display: none !important;
    }

    .lead-report-filters .report-multiselect {
        position: relative;
        width: 100%;
    }

     

    .lead-report-filters .report-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        margin-left: 10px;
    }

    .lead-report-filters .report-multiselect.is-open .report-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    .lead-report-filters .report-multiselect-menu {
         background: #fff;
        border: 1px solid #fff;
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
        z-index: 1055;
    }

    .lead-report-filters .report-multiselect.is-open .report-multiselect-menu {
        display: block;
    }

    .lead-report-filters .report-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 9px;
        margin: 0;
        padding: 8px 12px;
    }

    .lead-report-filters .report-multiselect-option:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    .lead-report-filters .report-multiselect-option input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        accent-color: #1473d2;
        clip: auto !important;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 18px;
        height: 18px !important;
        margin: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 18px !important;
    }

    .lead-report-filters .report-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    .lead-report-filters .report-multiselect-empty {
        color: #6c757d;
        padding: 9px 12px;
    }


    .dataTables_scrollHeadInner {
        width: 100%important;
    }
.new_table_box table tbody td:first-child {
    text-align: left!important;
}

#report-filter-modal.report-filter-compact .modal-content,
#report-filter-modal.report-filter-compact .modal-body {
    overflow: visible !important;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-pie-chart"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Summary Report</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li><li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li><li>Reports</li>
                        <li><i class="fa fa-angle-right"></i></li><li class="active">Summary Report</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?= base_url('assets/new_img/summary_report.png'); ?>" alt=""></div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Summary Report</h4>
                            <div class="float-right" style="float:right;">





                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>

                            <div id="report-results" style="display: none;">
                            <div class="row">

                                <form method="GET" action="<?= base_url('Reports'); ?>" class="mb-4 lead-report-filters">
                                    <div class="row align-items-end">
                                        <!-- Existing filters (City, Property, etc.) -->

                                        <div class="col-md-3">
                                            <label for="property" class="form-label">Property</label>
                                            <select name="property[]" class="form-control filter-input report-multiselect-source" multiple id="property">
                                                <?php foreach ($properties as $property) { ?>
                                                    <option value="<?= $property->hotel_id; ?>"><?= $property->hotel_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <!-- Department -->
                                        <div class="col-md-3">
                                            <label for="department" class="form-label">Department</label>
                                            <select name="department[]" class="form-control filter-input report-multiselect-source" multiple id="department">
                                                <?php foreach ($departments as $dept) { ?>
                                                    <option value="<?= $dept->department_id; ?>"><?= $dept->department_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>




                                        <!-- Date Filters -->
                                        <!-- 🆕 Date Filters -->
                                        <div class="col-md-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $this->input->get('start_date'); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $this->input->get('end_date'); ?>">
                                        </div>


                                    </div>
                                </form>





                            </div>

                            <div class="">


                            </div>

                            <div>


                                <div>
                                    <table id="leadReportTable" class="text-fade table table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Row Label</th>
                                                <th>Total Leads</th>
                                                <th>Business</th>
                                                <th>Non-Business</th>
                                                <th>Materialized</th>
                                                <th>Revenue</th>
                                                <th>Mat %</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php foreach ($report_data as $dept): ?>

                                                <?php
                                                // Reset department totals
                                                $dept_total = 0;
                                                $dept_business = 0;
                                                $dept_non_business = 0;
                                                $dept_materialized = 0;
                                                $dept_revenue = 0;

                                                // Calculate department totals
                                                foreach ($dept['channels'] as $ch) {
                                                    $dept_total        += $ch['total_leads'];
                                                    $dept_business     += $ch['business'];
                                                    $dept_non_business += $ch['non_business'];
                                                    $dept_materialized += $ch['materialized'];
                                                    $dept_revenue      += $ch['revenue_sum'];
                                                }

                                                $matPercent = ($dept_business > 0)
                                                    ? number_format(($dept_materialized / $dept_business) * 100, 1)
                                                    : 0;
                                                ?>

                                                <!-- ✅ DEPARTMENT MAIN ROW -->
                                                <tr style="background:#f0f0f0; font-weight:bold;">
                                                    <td><?= $dept['department_name'] ?></td>
                                                    <td><?= $dept_total ?></td>
                                                    <td><?= $dept_business ?></td>
                                                    <td><?= $dept_non_business ?></td>
                                                    <td><?= $dept_materialized ?></td>
                                                    <td><?= number_format($dept_revenue, 2) ?></td>
                                                    <td><?= $matPercent ?>%</td>
                                                </tr>

                                                <!-- ✅ SUB ROWS (USER CHANNELS) -->
                                                <?php foreach ($dept['channels'] as $channelName => $ch): ?>

                                                    <?php
                                                    $total        = $ch['total_leads'];
                                                    $business     = $ch['business'];
                                                    $non_business = $ch['non_business'];
                                                    $materialized = $ch['materialized'];
                                                    $revenue      = $ch['revenue_sum'];

                                                    $matPercentCh = ($business > 0)
                                                        ? number_format(($materialized / $business) * 100, 1)
                                                        : 0;
                                                    ?>

                                                    <tr>
                                                        <td style="padding-left:25px;">→ <?= $channelName ?></td>
                                                        <td><?= $total ?></td>
                                                        <td><?= $business ?></td>
                                                        <td><?= $non_business ?></td>
                                                        <td><?= $materialized ?></td>
                                                        <td><?= number_format($revenue, 2) ?></td>
                                                        <td><?= $matPercentCh ?>%</td>
                                                    </tr>

                                                <?php endforeach; ?>

                                            <?php endforeach; ?>

                                        </tbody>






                                    </table>


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

<div class="new_modal_design report-filter-overlay report-filter-compact" id="report-filter-modal" tabindex="-1"
    role="dialog" aria-labelledby="report-filter-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title" id="report-filter-modal-title">Report Filters</h4>
                    <p class="mb-0 text-muted">Select a required date range and any additional filters.</p>
                </div>
            </div>
            <form id="initial-report-filter-form" autocomplete="off" novalidate>
                <div class="modal-body lead-report-filters">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <label for="modal_property" class="form-label">Property</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_property">
                                <?php foreach ($properties as $property) { ?>
                                    <option value="<?= $property->hotel_id; ?>"><?= $property->hotel_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_department" class="form-label">Department</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_department">
                                <?php foreach ($departments as $dept) { ?>
                                    <option value="<?= $dept->department_id; ?>"><?= $dept->department_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="modal_start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="modal_end_date" required>
                        </div>
                        <div class="col-12">
                            <div id="report-filter-error" class="text-danger" role="alert" aria-live="polite"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit-report-filters">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>






<script>
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>




<script>
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>





<script>
    (function($) {
    function syncReportMultiSelect($select, $widget, placeholder) {
        const selectedValues = ($select.val() || []).map(String);
        const total = $widget.find('.report-multiselect-item').length;
        const selectedCount = selectedValues.length;

        $widget.find('.report-multiselect-item').each(function() {
            $(this).prop('checked', selectedValues.includes(String($(this).val())));
        });

        const $selectAll = $widget.find('.report-multiselect-all');
        $selectAll.prop('checked', total > 0 && selectedCount === total);
        $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < total);

        let summary = placeholder;
        if (selectedCount > 0 && selectedCount === total) {
            summary = `All selected (${selectedCount})`;
        } else if (selectedCount > 0) {
            summary = `${selectedCount} selected`;
        }

        $widget.find('.report-multiselect-summary').text(summary);
    }

    function initializeReportMultiSelect(selector, placeholder) {
        const $select = $(selector);
        if (!$select.length) return;

        if ($select.hasClass('select2-hidden-accessible') && $.fn.select2) {
            $select.select2('destroy');
        }

        $select.next('.report-multiselect').remove();
        $select.addClass('report-multiselect-source');

        const $widget = $('<div>', { class: 'report-multiselect' });
        const $toggle = $('<button>', {
            type: 'button',
            class: 'report-multiselect-toggle',
            'aria-expanded': 'false'
        }).append($('<span>', {
            class: 'report-multiselect-summary',
            text: placeholder
        }));
        const $menu = $('<div>', { class: 'report-multiselect-menu' });
        const availableOptions = $select.find('option').filter(function() {
            return String(this.value).trim() !== '';
        });

        if (availableOptions.length) {
            const $selectAll = $('<input>', {
                type: 'checkbox',
                class: 'report-multiselect-all'
            });
            $menu.append(
                $('<label>', { class: 'report-multiselect-option report-multiselect-select-all' })
                    .append($selectAll, $('<span>', { text: 'Select all' }))
            );

            availableOptions.each(function() {
                const $checkbox = $('<input>', {
                    type: 'checkbox',
                    class: 'report-multiselect-item',
                    value: this.value
                });
                $menu.append(
                    $('<label>', { class: 'report-multiselect-option' })
                        .append($checkbox, $('<span>').text($(this).text().trim()))
                );
            });
        } else {
            $menu.append($('<div>', {
                class: 'report-multiselect-empty',
                text: 'No options available'
            }));
        }

        $widget.append($toggle, $menu);
        $select.after($widget);

        $toggle.on('click', function() {
            const isOpen = !$widget.hasClass('is-open');
            $('.report-multiselect').not($widget).removeClass('is-open')
                .find('.report-multiselect-toggle').attr('aria-expanded', 'false');
            $widget.toggleClass('is-open', isOpen);
            $toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
        });

        $widget.on('change', '.report-multiselect-all', function() {
            const values = this.checked
                ? $widget.find('.report-multiselect-item').map(function() { return this.value; }).get()
                : [];
            $select.val(values).trigger('change');
        });

        $widget.on('change', '.report-multiselect-item', function() {
            const values = $widget.find('.report-multiselect-item:checked')
                .map(function() { return this.value; }).get();
            $select.val(values).trigger('change');
        });

        $select.off('change.reportMultiSelect').on('change.reportMultiSelect', function() {
            syncReportMultiSelect($select, $widget, placeholder);
        });

        syncReportMultiSelect($select, $widget, placeholder);
    }


    $(document).ready(function() {
        var table;
        var filtersReady = false;

        initializeReportMultiSelect('#property', 'Select Options');
        initializeReportMultiSelect('#department', 'Select Options');
        initializeReportMultiSelect('#modal_property', 'Select Options');
        initializeReportMultiSelect('#modal_department', 'Select Options');

        $(document).off('click.reportMultiSelect').on('click.reportMultiSelect', function(e) {
            if (!$(e.target).closest('.report-multiselect').length) {
                $('.report-multiselect').removeClass('is-open')
                    .find('.report-multiselect-toggle').attr('aria-expanded', 'false');
            }
        });

        // Function to initialize DataTable
        function initDataTable() {
            table = $('#leadReportTable').DataTable({
                dom: 'lfrtip',
                pageLength: 50,
                scrollX: true,
                ordering: false,

                responsive: true,
                stripeClasses: [], // ✅ Remove row striping (THIS FIXES YOUR UI)

                info: true,
                autoWidth: false,
                responsive: false,

                stripeClasses: [],
                destroy: true
            });
        }

        // Function to fetch filtered data
        function fetchLeads(reset = false) {
            let filters = {
                property: $('#property').val(),
                department: $('#department').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
            };
            filters[window.CSRF.name] = window.CSRF.hash;

            $.ajax({
                url: "<?= base_url('Reports/filter_summary_report') ?>",
                method: "POST",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    if (reset) $('#leadReportTable').html('<p>Loading...</p>');
                },
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {
                        // Replace table body HTML only
                        $('#leadReportTable').html(response.html);

                        // Reinitialize DataTable with new data
                        initDataTable();
                    } else {
                        $('#leadReportTable').html('<p>No records found.</p>');
                    }
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        }

        // Trigger fetch on filter change
        $(document).on('change', '#property, #department, #start_date, #end_date', function() {
            if (!filtersReady) return;
            fetchLeads(true);
        });

        $('#initial-report-filter-form').on('submit', function(e) {
            e.preventDefault();

            const startDate = $('#modal_start_date').val();
            const endDate = $('#modal_end_date').val();
            const $error = $('#report-filter-error');
            $error.text('');

            if (!startDate || !endDate) {
                $error.text('Start Date and End Date are required.');
                return;
            }

            if (endDate < startDate) {
                $error.text('End Date must be the same as or later than Start Date.');
                return;
            }

            $('#property').val($('#modal_property').val()).trigger('change.reportMultiSelect');
            $('#department').val($('#modal_department').val()).trigger('change.reportMultiSelect');
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

            filtersReady = true;
            $('#report-results').show();
            $('#report-filter-modal').removeClass('show').attr('aria-hidden', 'true');
            fetchLeads(true);
        });

        $('#report-filter-modal')
            .appendTo('.content-wrapper')
            .addClass('show')
            .attr('aria-hidden', 'false');
    });
    })(window.jQuery);
</script>
