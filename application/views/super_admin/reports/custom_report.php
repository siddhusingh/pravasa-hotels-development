<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-file-text"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Lead Reports</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li><li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li><li>Reports</li>
                        <li><i class="fa fa-angle-right"></i></li><li class="active">Lead Reports</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?= base_url('assets/new_img/report_img.png'); ?>" alt=""></div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Lead Reports</h4>
                            <div class="float-right" style="float:right;">





                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>

                            <div id="report-results" style="display: none;">

                                <div id="filter-section" class="lead-report-filters">
                                    <div class="mb-4">
                                        <div class="row align-items-end">
                                            <!-- Property -->
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


                                            <div class="col-md-3">
                                                <select name="assigned_to" id="assigned_to_filter" class="form-control">
                                                    <option value="">All Assigned Users</option>

                                                    <?php foreach ($assigned_users as $user):
                                                        $value = $user->id . '|' . $user->role;
                                                        $selected = (isset($_GET['assigned_to']) && $_GET['assigned_to'] == $value) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $value ?>" <?= $selected ?>
                                                            data-id="<?= $user->id ?>"
                                                            data-role="<?= $user->role ?>">

                                                            <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                                            (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="created_by" id="created_by" class="form-control">
                                                    <option value="">All Creators</option>

                                                    <?php foreach ($creators as $user):
                                                        $value = $user->id . '|' . $user->role;
                                                        $selected = (isset($_GET['created_by']) && $_GET['created_by'] == $value) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $value ?>" <?= $selected ?>
                                                            data-id="<?= $user->id ?>"
                                                            data-role="<?= $user->role ?>">

                                                            <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                                            (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Status -->
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status[]" class="form-control filter-input report-multiselect-source" multiple id="status">
                                                    <option value="Open">Open</option>
                                                    <option value="In Progress">In Progress</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                            </div>
                                            <!-- Lead Source -->
                                            <div class="col-md-3">
                                                <label for="channel" class="form-label">Lead Source</label>
                                                <select name="channel[]" class="form-control filter-input report-multiselect-source" multiple id="channel">
                                                    <?php foreach ($user_channel as $channelObj): ?>
                                                        <?php $channel = $channelObj->user_channel; ?>
                                                        <option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Stage -->
                                            <div class="col-md-3">
                                                <label for="disposition" class="form-label">Stage</label>
                                                <select class="form-control filter-input report-multiselect-source" name="disposition[]" multiple id="disposition">
                                                    <option value="" selected disabled>Select Stage</option>

                                                    <option value="Not Contacted">Not Contacted</option>
                                                    <option value="General Information">General Information</option>
                                                    <option value="Quotation Sent">Quotation Sent</option>
                                                    <option value="Negotiations">Negotiations</option>
                                                    <option value="Contract Done">Contract Done</option>
                                                    <option value="Advance Received">Advance Received</option>
                                                    <option value="Lead Won">Lead Won</option>
                                                    <option value="Lead Lost">Lead Lost</option>
                                                </select>
                                                
                                            </div>
                                            <!-- Business / Non-Business -->
                                            <div class="col-md-3">
                                                <label for="business_type" class="form-label">Business Type</label>
                                                <select name="business_type[]" class="form-select filter-input" id="business_type">
                                                    <option value="">Select Business</option>
                                                    <option value="business">Business</option>
                                                    <option value="non_business">Non-Business</option>
                                                </select>
                                            </div>
                                            <!-- Start Date -->
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" class="form-control filter-input" id="start_date">
                                            </div>
                                            <!-- End Date -->
                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" name="end_date" class="form-control filter-input" id="end_date">
                                            </div>
                                            <!-- Search -->
                                        </div>
                                    </div>
                                </div>

                            <div class="">


                            </div>

                            <div>


                                <div>
                                    <table id="leadReportTable" class="text-fade table table-bordered display" style="width:100%">

                                        <thead>
                                            <tr>
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
                                                <th>Creator Name</th>
                                                <th>Assigned To</th>
                                                <th>Created Date</th>
                                                <th>Response Date</th>
                                                <th>Completed Date</th>
                                                <th>Booking Date</th>
                                                <th>Check In Date</th>
                                                <th>Check Out Date</th>
                                                <th>Follow Up 1</th>
                                                <th>Follow Up 2</th>
                                                <th>Pax</th>
                                                <th>Query</th>
                                                <th>Revenue</th>
                                                <th>Materialization</th>
                                                <th>Remark</th>
                                                <th>Reason</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php if (!empty($leads)): ?>
                                                <?php foreach ($leads as $lead): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($lead['id'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['city_name'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['hotel_name'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['department_name'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['user_name'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['phone_number'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['email'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['status'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['disposition'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['user_channel'] ?? 'NA') ?></td>

                                                        <td><?= htmlspecialchars($lead['creator_name'] ?? 'NA') ?></td>
                                                        <td><?= htmlspecialchars($lead['assigned_person_name'] ?? 'NA') ?></td>

                                                        <!-- Created -->
                                                        <td>
                                                            <?= !empty($lead['created_at']) && strtotime($lead['created_at'])
                                                                ? date('d M Y, h:i A', strtotime($lead['created_at']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Response -->
                                                        <td>
                                                            <?= !empty($lead['responded_time']) && strtotime($lead['responded_time'])
                                                                ? date('d M Y, h:i A', strtotime($lead['responded_time']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Completed -->
                                                        <td>
                                                            <?= !empty($lead['completed_time']) && strtotime($lead['completed_time'])
                                                                ? date('d M Y, h:i A', strtotime($lead['completed_time']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Booking -->
                                                        <td>
                                                            <?= !empty($lead['booking_enquiry_date']) && strtotime($lead['booking_enquiry_date'])
                                                                ? date('d M Y, h:i A', strtotime($lead['booking_enquiry_date']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Check-in -->
                                                        <td>
                                                            <?= !empty($lead['checkin_date']) && strtotime($lead['checkin_date'])
                                                                ? date('d M Y, h:i A', strtotime($lead['checkin_date']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Check-out -->
                                                        <td>
                                                            <?= !empty($lead['checkout_date']) && strtotime($lead['checkout_date'])
                                                                ? date('d M Y, h:i A', strtotime($lead['checkout_date']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Follow-up 1 -->
                                                        <td>
                                                            <?= !empty($lead['followup_date']) && strtotime($lead['followup_date'])
                                                                ? date('d M Y', strtotime($lead['followup_date']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Follow-up 2 -->
                                                        <td>
                                                            <?= !empty($lead['second_followup_date']) && strtotime($lead['second_followup_date'])
                                                                ? date('d M Y', strtotime($lead['second_followup_date']))
                                                                : 'NA' ?>
                                                        </td>

                                                        <!-- Pax -->
                                                        <td><?= !empty($lead['pax']) ? htmlspecialchars($lead['pax']) : 'NA' ?></td>

                                                        <!-- Query -->
                                                        <td><?= !empty($lead['query']) ? nl2br(htmlspecialchars($lead['query'])) : 'NA' ?></td>

                                                        <!-- Revenue -->
                                                        <td>
                                                            <?= isset($lead['amount']) ? number_format((float)$lead['amount'], 2) : '0.00' ?>
                                                        </td>

                                                        <!-- Materialization -->
                                                        <td>
                                                            <?php
                                                            $isMaterialized = (
                                                                !empty($lead['disposition']) &&
                                                                !empty($lead['status']) &&
                                                                strtolower($lead['disposition']) === 'reservation' &&
                                                                strtolower($lead['status']) === 'closed'
                                                            );
                                                            echo $isMaterialized ? 'Yes' : 'No';
                                                            ?>
                                                        </td>

                                                        <!-- Remark -->
                                                        <td><?= !empty($lead['remark']) ? nl2br(htmlspecialchars($lead['remark'])) : 'NA' ?></td>

                                                        <!-- Reason -->
                                                        <td><?= !empty($lead['reason']) ? nl2br(htmlspecialchars($lead['reason'])) : 'NA' ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="26" style="text-align:center;">No data available</td>
                                                </tr>
                                            <?php endif; ?>
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

<div class="new_modal_design report-filter-overlay" id="report-filter-modal" tabindex="-1"
    role="dialog" aria-labelledby="report-filter-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
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
                        <div class="col-md-4 mb-3">
                            <label for="modal_property" class="form-label">Property</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_property">
                                <?php foreach ($properties as $property) { ?>
                                    <option value="<?= $property->hotel_id; ?>"><?= $property->hotel_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_department" class="form-label">Department</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_department">
                                <?php foreach ($departments as $dept) { ?>
                                    <option value="<?= $dept->department_id; ?>"><?= $dept->department_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_assigned_to_filter" class="form-label">Assigned User</label>
                            <select id="modal_assigned_to_filter" class="form-control">
                                <option value="">All Assigned Users</option>
                                <?php foreach ($assigned_users as $user): ?>
                                    <option value="<?= $user->id . '|' . $user->role ?>">
                                        <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                        (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_created_by" class="form-label">Creator</label>
                            <select id="modal_created_by" class="form-control">
                                <option value="">All Creators</option>
                                <?php foreach ($creators as $user): ?>
                                    <option value="<?= $user->id . '|' . $user->role ?>">
                                        <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                        (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_status" class="form-label">Status</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_status">
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_channel" class="form-label">Lead Source</label>
                            <select class="form-control report-multiselect-source" multiple id="modal_channel">
                                <?php foreach ($user_channel as $channelObj): ?>
                                    <?php $channel = $channelObj->user_channel; ?>
                                    <option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_disposition" class="form-label">Stage </label>
                            <select class="form-control report-multiselect-source" multiple id="modal_disposition">
                                <option value="Not Contacted">Not Contacted</option>
                                <option value="General Information">General Information</option>
                                <option value="Quotation Sent">Quotation Sent</option>
                                <option value="Negotiations">Negotiations</option>
                                <option value="Contract Done">Contract Done</option>
                                <option value="Advance Received">Advance Received</option>
                                <option value="Lead Won">Lead Won</option>
                                <option value="Lead Lost">Lead Lost</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_business_type" class="form-label">Business Type</label>
                            <select class="form-control" id="modal_business_type">
                                <option value="">Select Business</option>
                                <option value="business">Business</option>
                                <option value="non_business">Non-Business</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="modal_start_date" required>
                        </div>
                        <div class="col-md-4 mb-3">
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

    function initializeSingleSelect2() {
        if (!$.fn.select2) return;

        $('#assigned_to_filter, #created_by, #business_type, #modal_assigned_to_filter, #modal_created_by, #modal_business_type').each(function() {
            const $select = $(this);
            if (!$select.hasClass('select2-hidden-accessible')) {
                const options = {
                    width: '100%',
                    minimumResultsForSearch: 0
                };
                const $overlay = $select.closest('.modal, .report-filter-overlay');
                if ($overlay.length) options.dropdownParent = $overlay;
                $select.select2(options);
            }
        });
    }

    $(document).ready(function() {

        initializeReportMultiSelect('#property', 'Select Options');
        initializeReportMultiSelect('#department', 'Select Options');
        initializeReportMultiSelect('#status', 'Select Options');
        initializeReportMultiSelect('#channel', 'Select Options');
        initializeReportMultiSelect('#disposition', 'Select Options');
        initializeReportMultiSelect('#modal_property', 'Select Options');
        initializeReportMultiSelect('#modal_department', 'Select Options');
        initializeReportMultiSelect('#modal_status', 'Select Options');
        initializeReportMultiSelect('#modal_channel', 'Select Options');
        initializeReportMultiSelect('#modal_disposition', 'Select Options');
        initializeSingleSelect2();

        $(document).off('click.reportMultiSelect').on('click.reportMultiSelect', function(e) {
            if (!$(e.target).closest('.report-multiselect').length) {
                $('.report-multiselect').removeClass('is-open')
                    .find('.report-multiselect-toggle').attr('aria-expanded', 'false');
            }
        });

        // 🔹 Toggle filter section
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });

        let table; // keep a reference to DataTable instance
        let filtersReady = false;

        // 🔹 Initialize DataTable
        function initDataTable() {
            // Destroy existing DataTable before reinitializing
            if ($.fn.DataTable.isDataTable('#leadReportTable')) {
                $('#leadReportTable').DataTable().destroy();
            }

            table = $('#leadReportTable').DataTable({
                dom: 'lfrtip',
                pageLength: 50,
                scrollX: true,
                processing: true,
                serverSide: true,
                ordering: true,
                order: [[12, 'desc']],
                responsive: true,
                stripeClasses: [],
                info: true,
                autoWidth: false,
                destroy: true,
                ajax: {
                    url: "<?= base_url('Reports/filter_custom_report') ?>",
                    type: 'POST',
                    data: function(d) {
                        let createdUser = getUserFilterData('#created_by');
                        let assignedUser = getUserFilterData('#assigned_to_filter');
                        d.property = $('#property').val();
                        d.department = $('#department').val();
                        d.status = $('#status').val();
                        d.channel = $('#channel').val();
                        d.disposition = $('#disposition').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.created_id = createdUser.id;
                        d.created_role = createdUser.role;
                        d.assigned_id = assignedUser.id;
                        d.assigned_role = assignedUser.role;
                        d.business_type = $('#business_type').val();
                        d[window.CSRF.name] = window.CSRF.hash;
                    },
                    dataSrc: function(json) {
                        if (json.csrfHash) window.CSRF.hash = json.csrfHash;
                        return json.data;
                    }
                }
            });
        }

        function getUserFilterData(selector) {
            let val = $(selector).val();

            if (!val) {
                return {
                    id: '',
                    role: ''
                };
            }

            let parts = val.split('|');

            return {
                id: parts[0] || '',
                role: parts[1] || ''
            };
        }

        // 🔹 Event binding for all filters (works for dynamic elements too)
        $(document).on('change', '#property, #department, #status, #channel, #disposition, #start_date, #end_date, #business_type, #created_by, #assigned_to_filter', function() {
            if (!filtersReady || !table) return;
            table.ajax.reload(null, true);
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
            $('#status').val($('#modal_status').val()).trigger('change.reportMultiSelect');
            $('#channel').val($('#modal_channel').val()).trigger('change.reportMultiSelect');
            $('#disposition').val($('#modal_disposition').val()).trigger('change.reportMultiSelect');
            $('#assigned_to_filter').val($('#modal_assigned_to_filter').val()).trigger('change.select2');
            $('#created_by').val($('#modal_created_by').val()).trigger('change.select2');
            $('#business_type').val($('#modal_business_type').val()).trigger('change.select2');
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

            filtersReady = true;
            $('#report-results').show();
            $('#report-filter-modal').removeClass('show').attr('aria-hidden', 'true');
            initDataTable();
        });

        $('#report-filter-modal')
            .appendTo('.content-wrapper')
            .addClass('show')
            .attr('aria-hidden', 'false');

    });
    })(window.jQuery);
</script>
