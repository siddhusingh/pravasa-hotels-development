<?php
// Hotel-admin standalone form. The assigned property is supplied by the
// hotel session in hotelAdmin/Leads::add_lead and cannot be changed.
$lead_form_role_label = $lead_form_role_label ?? 'Hotel Admin';
$lead_form_submit_url = $lead_form_submit_url ?? base_url('insert-lead-admin');
$lead_form_redirect_url = $lead_form_redirect_url ?? base_url('view-leads');
?>
<script>
window.CSRF = {
    name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
    hash: <?= json_encode($this->security->get_csrf_hash()); ?>
};
</script>
<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->

        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-phone-volume" aria-hidden="true"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Create New Lead</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li><?= html_escape($lead_form_role_label); ?></li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Create New Lead</li>
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
                        <div class="box-header">
                            <h4 class="box-title">Create New Lead</h4>

                        </div>
                        <div class="box-body">
                            <div class="container mt-1">
                                <!-- Include Bootstrap & FontAwesome CDN (if not already included) -->

                                <form id="leadForm" novalidate>
                                    <div class="row g-3">

                                        <!-- Phone Number -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number"><i class="fa fa-phone me-1 text-success"></i>Phone Number <span class="required-marker">*</span></label>
                                                <input type="number" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required
                                                    value="<?php if (!empty($_GET['phone'])) {
                                                                echo $_GET['phone'];
                                                            } ?>">
                                                    <span id="phone_number_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="username"><i class="fa fa-user me-1 text-primary"></i>Guest Name <span class="required-marker" id="guestNameRequiredMarker">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username">
                                                <span id="username_error" class="text-danger small"></span>
                                            </div>
                                        </div>



                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email"><i class="fa fa-envelope me-1 text-warning"></i>Email (Optional)</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                                <span id="email_error" class="text-danger small"></span>
                                            </div>
                                        </div>







                                        <!-- Hotel (Property) -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="property">
                                                    <i class="fa fa-hotel me-1 text-danger"></i>Hotel (Property) <span class="required-marker">*</span>
                                                </label>

                                                <select name="property" id="property" class="form-control" required disabled aria-readonly="true">
                                                    <option value="" disabled <?= empty($selected_property) ? 'selected' : '' ?>>
                                                        Please Select
                                                    </option>

                                                    <?php foreach ($hotel_admin as $each) { ?>
                                                        <option value="<?= $each->hotel_id; ?>"
                                                            <?= ($each->hotel_id == $selected_property) ? 'selected' : ''; ?>>
                                                            <?= $each->hotel_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                                <span id="property_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="type">
                                                    <i class="fa fa-sitemap me-1 text-muted"></i>Department (Type) <span class="required-marker">*</span>
                                                </label>

                                                <select name="type" id="type" class="form-control" required>
                                                    <option value="" disabled <?= empty($selected_department) ? 'selected' : '' ?>>
                                                        Please Select
                                                    </option>

                                                    <?php foreach ($departments as $each) { ?>
                                                        <option value="<?= $each->department_id; ?>"
                                                            data-name="<?= $each->department_name; ?>"
                                                            <?= ($each->department_id == $selected_department) ? 'selected' : ''; ?>>
                                                            <?= $each->department_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                                <span id="type_error" class="text-danger small"></span>
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <label>Assign Lead To</label>

                                            <select name="assigned_to" id="assigned_to" class="form-control">
                                                <option value="">Select User</option>

                                                <?php foreach ($all_assignable_users as $user) { ?>

                                                    <option
                                                        value="<?php echo $user['id']; ?>"
                                                        data-role="<?php echo $user['user_role']; ?>"
                                                        data-email="<?php echo $user['email']; ?>">
                                                        <?php echo $user['name'] . '-' . $user['user_role']; ?>
                                                    </option>

                                                <?php } ?>

                                            </select>
                                        </div>

                                        <input type="hidden" name="assigned_person_user_role" id="assigned_person_user_role">
                                        <input type="hidden" name="assigned_person_email" id="assigned_person_email">



                                        <!-- Lead Status -->




                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="lead_type">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Type
                                                </label>
                                                <select name="lead_type" id="lead_type" class="form-control">
                                                    <option value="Hot">Hot</option>
                                                    <option value="Warm">Warm</option>
                                                    <option value="Cold" selected>Cold</option>
                                                </select>
                                                <span id="lead_type_error" class="text-danger small"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="user_channel">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Source <span class="required-marker">*</span>
                                                </label>

                                                <select name="user_channel" id="user_channel" class="form-control">

                                                    <option value="phone" selected>Phone</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Walking">Walking</option>

                                                    <option value="IVR">IVR</option>
                                                    <option value="Sales Call TA">Sales Call TA</option>
                                                    <option value="Employee Referral">Employee Referral</option>
                                                    <option value="Travel Agent">Travel Agent</option>
                                                    <option value="Public Relations">Public Relations</option>
                                                    <option value="Sales Mail">Sales Mail</option>
                                                    <option value="Seminar Partner">Seminar Partner</option>
                                                    <option value="Walk ins">Walk ins</option>
                                                    <option value="Direct Calls">Direct Calls</option>
                                                    <option value="Advertisement">Advertisement</option>
                                                    <option value="TTF">TTF</option>
                                                    <option value="BLTM">BLTM</option>
                                                    <option value="Sales Call MICE">Sales Call MICE</option>
                                                    <option value="Wedmegood">Wedmegood</option>

                                                </select>

                                                <span id="user_channel_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="purpose">
                                                    <i class="fa fa-bullseye me-1 text-secondary"></i>Purpose
                                                </label>

                                                <select name="purpose" id="purpose" class="form-control">

                                                    <option value="">Select Purpose</option>
                                                    <option value="Corporate">Corporate</option>
                                                    <option value="Family">Family</option>
                                                    <option value="Vacation">Vacation</option>
                                                    <option value="Leisure">Leisure</option>
                                                    <option value="Social">Social</option>
                                                    <option value="Wedding">Wedding</option>
                                                    <option value="Pilgrimage">Pilgrimage</option>

                                                </select>

                                                <span id="purpose_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="disposition">
                                                    <i class="fa fa-list me-1 text-dark"></i>Stage <span class="required-marker">*</span>
                                                </label>

                                                <select class="form-control" name="disposition" id="disposition">
                                                    <option value="" selected>Select Stage</option>

                                                    <option value="Not Contacted">Not Contacted</option>
                                                    <option value="General Information">General Information</option>
                                                    <option value="Quotation Sent">Quotation Sent</option>
                                                    <option value="Negotiations">Negotiations</option>
                                                    <option value="Contract Done">Contract Done</option>
                                                    <option value="Advance Received">Advance Received</option>
                                                    <option value="Lead Won">Lead Won</option>
                                                    <option value="Lead Lost">Lead Lost</option>

                                                </select>

                                                <span id="disposition_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="lead_status"><i class="fa fa-info-circle me-1 text-secondary"></i>Lead Status <span class="required-marker">*</span></label>
                                                <input type="hidden" id="leadDepartment" name="leadDepartment">
                                                <input type="hidden" id="preserved_number_of_rooms" value="">
                                                <select name="lead_status" id="lead_status" class="form-control" disabled>
                                                    <option value="Open" selected>Open</option>

                                                    <option value="In Progress">In Progress</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                                <span id="lead_status_error" class="text-danger small"></span>
                                            </div>
                                        </div>




                                        <div id="dynamicFields" class="row g-3"></div>




                                        <!-- Query -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="query"><i class="fa fa-question-circle me-1 text-primary"></i>Query <span class="required-marker">*</span></label>
                                                <textarea name="query" id="query" class="form-control" rows="1" placeholder="Enter query" required></textarea>
                                                <span id="query_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remark"><i class="fa fa-question-circle me-1 text-primary"></i>remark</label>
                                                <textarea name="remark" id="remark" class="form-control" rows="1" placeholder="Enter remark"></textarea>
                                                <span id="remark_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-12 d-none" id="restaurantReservationPlacement">
                                            <div class="row"></div>
                                        </div>



                                        <!-- Submit -->
                                        <div class="col-md-12 mt-3 text-end">
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                                <i class="fa fa-arrow-left me-1"></i> Back
                                            </button>
                                            <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                                <i class="fa fa-paper-plane me-1"></i>Submit
                                            </button>
                                        </div>

                                    </div>
                                </form>




                                <div id="response" class="mt-3"></div>
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

<div class="modal fade" id="reserveTableModal" tabindex="-1" aria-labelledby="reserveTableModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <div class="d-flex">
               <span class="reserve-modal-icon"><i class="fa fa-cutlery"></i></span>
               <div><h5 class="modal-title" id="reserveTableModalLabel">Reserve Table</h5><p class="reserve-modal-subtitle">Select an available table for this reservation</p></div>
            </div>
            <button type="button" class="btn-close" id="closeReserveTableModal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row reservation-filters">
               <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-cutlery"></i>1. Restaurant</label><select class="reserve-control" id="reserve_restaurant_id"><option value="">Select Restaurant</option></select><div class="text-danger small mt-1" id="reserve_restaurant_error"></div></div>
               <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-calendar"></i>2. Booking Date</label><input class="reserve-control" id="reserve_booking_date" type="date"><div class="text-danger small mt-1" id="reserve_booking_date_error"></div></div>
               <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>3. Slot Type</label><select class="reserve-control" id="reserve_slot_type_id"><option value="">Select Slot Type</option></select><div class="text-danger small mt-1" id="reserve_slot_type_error"></div></div>
               <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>4. Time Slot</label><select class="reserve-control" id="reserve_time_slot_id" disabled><option value="">Select Slot Type first</option></select><div class="text-danger small mt-1" id="reserve_time_slot_error"></div></div>
               <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-th-large"></i>5. Table Category</label><select class="reserve-control" id="reserve_table_category_id"><option value="">Select Table Category</option></select><div class="text-danger small mt-1" id="reserve_table_category_error"></div></div>
            </div>
            <div class="reservation-summary">
               <div class="reservation-stat"><i class="fa fa-check-circle"></i><div><strong id="reserve_available_count">0</strong><span>Available</span></div></div><div class="reservation-stat stat-occupied"><i class="fa fa-times-circle"></i><div><strong id="reserve_occupied_count">0</strong><span>Occupied</span></div></div><div class="reservation-stat stat-reserved"><i class="fa fa-calendar-check-o"></i><div><strong id="reserve_reserved_count">0</strong><span>Reserved</span></div></div><div class="reservation-stat stat-blocked"><i class="fa fa-ban"></i><div><strong id="reserve_blocked_count">0</strong><span>Blocked</span></div></div><div class="reservation-stat stat-checkout"><i class="fa fa-clock-o"></i><div><strong id="reserve_checkout_count">0</strong><span>Expected Check-outs</span></div></div>
            </div>
            <div class="row g-3">
               <div class="col-12"><div class="reservation-panel"><div class="reservation-table-grid" id="reserve_table_grid"><div class="text-muted">Select restaurant, booking date, time slot and table category to view tables.</div></div><div class="text-danger small mt-2" id="reserve_table_error"></div></div></div>
            </div>
            <div class="row g-3 mt-1"><div class="col-lg-7"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-file-text-o"></i>Special Instructions <span class="text-muted fw-normal">(Optional)</span></label><textarea class="reserve-control reserve-instructions" id="reserve_special_request" maxlength="250" placeholder="Add any special request or notes for this reservation..."></textarea></div></div><div class="col-lg-5"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-check-circle"></i>Reservation Status</label><div class="reservation-status-options"><label><input type="radio" name="reserve_status" value="Reserved" checked> Reserved</label><label><input type="radio" name="reserve_status" value="Seated"> Seated</label><label><input type="radio" name="reserve_status" value="Completed"> Completed</label><label><input type="radio" name="reserve_status" value="Cancelled"> Cancelled</label></div><div class="text-danger small mt-1" id="reserve_status_error"></div></div></div></div>
         </div>
         <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-light border" id="cancelReserveTableModal">Cancel</button>
            <button type="button" class="btn btn-primary reserve-submit-btn" id="confirmTableReservation"><i class="fa fa-calendar-check-o me-2"></i>Reserve Table</button>
         </div>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Include jQuery Validation plugin -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Select2 CSS and JS for multi-select with checkboxes -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    /* The shared new_table_box style sets label icons to white. Keep them visible here. */
    .box.new_table_box #leadForm .form-group label i.fa {
        color: #000 !important;
        display: inline-block;
        width: 18px;
        margin-right: 6px !important;
        text-align: center;
    }

    #leadForm .required-marker {
        color: #dc3545;
        font-weight: 700;
        margin-left: 2px;
    }

    #leadForm .select2-container--default .select2-selection--single {
        height: 46px !important;
        padding: 11px 14px;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        background-color: #fff !important;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px !important;
    }

    #leadForm .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: 0;
        line-height: 22px;
    }

    #leadForm .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }

    #leadForm .table-multiselect-source {
        display: none !important;
    }

    #leadForm .table-multiselect {
        position: relative;
        width: 100%;
    }

    #leadForm .table-multiselect-toggle {
        align-items: center;
        background: #fff !important;
        border: 1px solid #b8c0cc !important;
        border-radius: 8px;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px;
        color: #495057 !important;
        display: flex;
        height: 46px;
        justify-content: space-between;
        padding: 0 14px;
        text-align: left;
        width: 100%;
    }

    #leadForm .table-multiselect-source.is-invalid + .table-multiselect .table-multiselect-toggle {
        border-color: #dc3545 !important;
    }

    #leadForm .table-multiselect.is-open .table-multiselect-toggle,
    #leadForm .table-multiselect-toggle:focus {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2) !important;
        outline: 0;
    }

    #leadForm .table-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        margin-left: 10px;
    }

    #leadForm .table-multiselect.is-open .table-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    #leadForm .table-multiselect-menu {
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

    #leadForm .table-multiselect.is-open .table-multiselect-menu {
        display: block;
    }

    #leadForm .table-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 9px;
        margin: 0;
        padding: 8px 12px;
    }

    #leadForm .table-multiselect-option:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    #leadForm .table-multiselect-option input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        accent-color: #1473d2;
        clip: auto !important;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 18px;
        height: 18px !important;
        left: auto !important;
        margin: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 18px !important;
    }

    #leadForm .table-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    #leadForm .table-multiselect-empty {
        color: #6c757d;
        padding: 9px 12px;
    }

    #restaurantReservationPlacement #openReserveTableModal {
        height: 46px;
        width: 100% !important;
    }

    #reserveTableModal .reservation-status-options {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    #reserveTableModal .reservation-status-options input[type="radio"] {
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        border: 2px solid #cbd3df;
        border-radius: 50%;
        clip: auto !important;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 18px;
        height: 18px;
        left: auto !important;
        margin: 0;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 18px;
    }

    #reserveTableModal .reservation-status-options input[type="radio"]:checked {
        background-color: #6b4ce6;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.4' d='M4 8.2 6.7 11 12 5.5'/%3E%3C/svg%3E");
        background-position: center;
        background-repeat: no-repeat;
        background-size: 12px 12px;
        border-color: #6b4ce6;
    }

    #reserveTableModal .reservation-status-options input[type="radio"]:focus-visible {
        box-shadow: 0 0 0 3px rgba(107, 76, 230, 0.2);
        outline: 0;
    }
</style>

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
</script>
<script>
    function initializeSingleSelect2(scope) {
        const $scope = scope ? $(scope) : $('#leadForm');
        const $selects = $scope.is('select')
            ? $scope.filter('select:not([multiple])')
            : $scope.find('select:not([multiple])');

        $selects.each(function() {
            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    width: '100%'
                });
            }

            if ($select.is('#property, #type, #disposition')) {
                $select
                    .off('.leadDynamicFields')
                    .on(
                        'change.leadDynamicFields select2:select.leadDynamicFields select2:clear.leadDynamicFields',
                        scheduleDynamicFieldsRefresh
                    );
            }

            if ($select.is('#restaurant_id')) {
                $select
                    .off('.leadTableCategories')
                    .on(
                        'change.leadTableCategories select2:select.leadTableCategories select2:clear.leadTableCategories',
                        scheduleTableCategoriesRefresh
                    );
            }

            if ($select.is('#table_category_id')) {
                $select
                    .off('.leadTables')
                    .on(
                        'change.leadTables select2:select.leadTables select2:clear.leadTables',
                        scheduleTablesRefresh
                    );
            }

            if ($select.is('#slot_type_id')) {
                $select
                    .off('.leadTimeSlots')
                    .on(
                        'change.leadTimeSlots select2:select.leadTimeSlots select2:clear.leadTimeSlots',
                        scheduleTimeSlotsRefresh
                    );
            }
        });
    }

    $(document).ready(function() {

        initializeSingleSelect2('#leadForm');

        // Username
        $('#username').focusout(function() {
            let value = this.value.trim();
            if (value === "") {
                $('#username_error').html('Please Enter Username');
            } else {
                $('#username_error').html('');
            }
        });

        // Phone Number
        $('#phone_number').focusout(function() {
            let value = this.value.trim();

            // Remove country code if present
            value = value.replace(/^(\+91|91)/, '');

            let phoneRegex = /^[6-9][0-9]{9}$/;

            if (value === "") {
                $('#phone_number_error').html('Please Enter Phone Number');
            } else if (!phoneRegex.test(value)) {
                $('#phone_number_error').html('Invalid Indian Mobile Number');
            } else {
                $('#phone_number_error').html('');
            }
        });

        // Email
        $('#email').focusout(function() {
            let value = this.value.trim();
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value !== "" && !emailRegex.test(value)) {
                $('#email_error').html('Invalid Email Format');
            } else {
                $('#email_error').html('');
            }
        });

        // Date
        $('#date').focusout(function() {
            if (!this.value) {
                $('#date_error').html('Please Select Booking Date');
            } else {
                $('#date_error').html('');
            }
        });

        // Time
        $('#time').focusout(function() {
            if (!this.value) {
                $('#time_error').html('Please Select Booking Time');
            } else {
                $('#time_error').html('');
            }
        });

        // Property
        $('#property').change(function() {
            if (this.value === "") {
                $('#property_error').html('Please Select a Hotel');
            } else {
                $('#property_error').html('');
            }
        });

        // Department
        $('#type').change(function() {
            if (this.value === "") {
                $('#type_error').html('Please Select a Department');
            } else {
                $('#type_error').html('');
            }
        });

        // Lead Status
        $('#lead_status').change(function() {
            if (this.value === "") {
                $('#lead_status_error').html('Please Select Lead Status');
            } else {
                $('#lead_status_error').html('');
            }
        });

        // Stage


        // Query
        $('#query').focusout(function() {
            if (this.value.trim() === "") {
                $('#query_error').html('Please Enter Query');
            } else {
                $('#query_error').html('');
            }
        });

        // Remark


        // Created Date
        // $('#created_date').focusout(function() {
        //     if (!this.value) {
        //         $('#created_date_error').html('Please Select Created Date');
        //     } else {
        //         $('#created_date_error').html('');
        //     }
        // });

        // Submit Form via AJAX

    });


    let dynamicFieldsRefreshTimer = null;

    function scheduleDynamicFieldsRefresh() {
        clearTimeout(dynamicFieldsRefreshTimer);
        dynamicFieldsRefreshTimer = setTimeout(function() {
            updateDynamicFields("");
        }, 0);
    }

    $(document).on('change', '#assigned_to', function() {

        var role = $(this).find(':selected').data('role');
        var email = $(this).find(':selected').data('email');

        $('#assigned_person_user_role').val(role);
        $('#assigned_person_email').val(email);

    });

    if (window.CSRF) {
        window.CSRF.cookie = "<?= $this->config->item('csrf_cookie_name') ?>";
    }

    function readCookie(name) {
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0; i < cookies.length; i++) {
            var parts = cookies[i].split('=');
            var cookieName = decodeURIComponent(parts.shift());

            if (cookieName === name) {
                return decodeURIComponent(parts.join('='));
            }
        }

        return '';
    }

    function currentCsrfHash() {
        if (!window.CSRF) {
            return '';
        }

        return readCookie(window.CSRF.cookie) || window.CSRF.hash;
    }

    function csrfData(data) {
        data = data || {};

        if (window.CSRF) {
            data[window.CSRF.name] = currentCsrfHash();
        }

        return data;
    }

    function csrfFormData(formData) {
        if (window.CSRF) {
            if (typeof formData.set === 'function') {
                formData.set(window.CSRF.name, currentCsrfHash());
            } else {
                formData.append(window.CSRF.name, currentCsrfHash());
            }
        }

        return formData;
    }

    function refreshCsrf(response) {
        if (response && response.csrfHash && window.CSRF) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    $(document).ajaxComplete(function(event, xhr) {
        refreshCsrf(xhr.responseJSON);
    });

    var csrfAjaxQueue = $.Deferred().resolve().promise();

    function csrfAjax(options) {
        var method = (options.type || options.method || 'GET').toUpperCase();

        if (method !== 'POST') {
            return $.ajax(options);
        }

        var runRequest = function() {
            if (options.data instanceof FormData) {
                csrfFormData(options.data);
            } else {
                options.data = csrfData(options.data);
            }

            return $.ajax(options);
        };

        csrfAjaxQueue = csrfAjaxQueue.then(runRequest, runRequest);

        return csrfAjaxQueue;
    }


    function normalizeDepartmentName(name) {
        name = (name || '').toString().trim().toLowerCase();

        if (name === 'restaurants') {
            return 'restaurant';
        }

        if (name === 'banquets') {
            return 'banquet';
        }

        return name;
    }

    function resetDynamicFields() {
        const numberOfRoomsField = $('#dynamicFields [name="number_of_rooms"]');
        if (numberOfRoomsField.length) {
            $('#preserved_number_of_rooms').val(numberOfRoomsField.val());
        }

        $('#dynamicFields select.select2-hidden-accessible:not([multiple])').each(function() {
            $(this).select2('destroy');
        });

        if ($('#table_id').length && $('#table_id').hasClass('select2-hidden-accessible')) {
            $('#table_id').select2('destroy');
        }

        $('#dynamicFields').empty();
        $('#dynamicFields .error-label, #dynamicFields .error-text').html('');
        $('#restaurantReservationPlacement').addClass('d-none').find('.row').empty();
        $('#lead_status').val('Open');
    }

    function updateDynamicFields(data = "") {

        const disposition = $("#disposition").val();
        $('#guestNameRequiredMarker').toggle(disposition !== 'Not Contacted');
        let property = $("#property").val();
        let department = normalizeDepartmentName($('#type').find(':selected').data('name'));
        let department_id = $('#type').val();

        $('#leadDepartment').val(department);

        let existingLeadData = data;
        console.log(existingLeadData);

        const container = $("#dynamicFields");
        resetDynamicFields();

        var today = new Date().toISOString().split('T')[0];




        if (

            disposition === "Contract Done" ||
            disposition === "Advance Received" ||
            disposition === "Lead Won" ||

            disposition === "Sold Out Dates" ||
            disposition === "General Information"
        ) {
            if (disposition === "Lead Won") {
                container.append(`
        <div class="col-md-3 mb-3">
    <label>Expected Revenue</label>
    <input type="number" name="amount" id="amount" class="form-control" step="0.01" >
</div>
        `);

            }
            $("#lead_status").val('Closed');
        }



        if (disposition === "Lead Lost") {

            $("#lead_status").val('Closed');

            container.append(`


            <div class="col-md-3 mb-3">
                <label class="form-label">Reason <span class="required-marker">*</span></label>
                <select name="reason" class="form-select filter-input" id="reason" required>
                    <option value="">Select Reason</option>
                    <option value="Budget Issue">Budget Issue</option>
                    <option value="Date Unavailable">Date Unavailable</option>
                    <option value="No Response">No Response</option>
                    <option value="Chose Competitor">Chose Competitor</option>
                    <option value="Not Interested">Not Interested</option>
                    <option value="Duplicate Lead">Duplicate Lead</option>
                </select>
            </div>


    `);

        }


        /* ---------- DENIED ---------- */

        if (disposition === "Denied") {

            container.append(`
        <div class="mb-3">
            <label>Check-in Date</label>
            <input type="date" name="checkin_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Check-out Date</label>
            <input type="date" name="checkout_date" class="form-control">
        </div>`);
        }



        if (disposition === "Quotation Sent") {


            $("#lead_status").val('In Progress');

            container.append(`


                <div class="col-md-3 mb-3">
                    <label class="form-label">Promotional Offer</label>
                    <select name="promotional_offers" class="form-select filter-input" id="promotional_offers">
                        
                    </select>
                </div>
                `


            )


            loadpromotional_offers(department_id, existingLeadData);


            /* ROOMS */

            if (department === "rooms") {

                container.append(`


                <div class="col-md-3 mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="roomtype" class="form-select filter-input" id="roomtype">
                        
                    </select>
                </div>

                 <div class="col-md-3 mb-3">
                    <label>Meal Plan <span class="text-danger">*</span></label>
                    <select name="meal_plan" id="meal_plan" class="form-select filter-input">
                        <option value="">Select Meal Plan</option>
                    </select>
                    <div class="text-danger error-label" id="meal_plan_error"></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Number of Rooms</label>
                    <input type="number" name="number_of_rooms" class="form-control" min="1"
                        value="${$('#preserved_number_of_rooms').val() || ''}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>No. of Pax</label>
                    <input type="number" name="pax" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Adults</label>
                    <input type="number" name="adults" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Kids</label>
                    <input type="number" name="kids" class="form-control" min="0">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Room Revenue</label>
                    <input type="number" name="revenue_room" id="revenue_room" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>F&B Revenue</label>
                    <input type="number" name="revenue_fnb" id="revenue_fnb" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Other Revenue</label>
                    <input type="number" name="revenue_other" id="revenue_other" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly>
                </div>

                

           
            `);

                loadRoomTypes(property, existingLeadData);
                loadMealPlan(existingLeadData);


            } else if (department === "restaurant") {

                container.append(`

<input type="hidden" name="booking_date" id="restaurant_booking_date" value="">
<input type="hidden" name="restaurant_id" id="restaurant_id" value="">
<input type="hidden" name="table_category_id" id="table_category_id" value="">
<select name="table_id[]" id="table_id" multiple style="display:none"></select>
<input type="hidden" name="slot_type_id" id="slot_type_id" value="">
<input type="hidden" name="time_slot_id" id="time_slot_id" value="">
<input type="hidden" name="table_reservation_status" id="table_reservation_status" value="">
<input type="hidden" name="special_request" id="restaurant_special_request" value="">

<div class="col-md-3 mb-3">
    <label>Arrival Time</label>
    <input type="time" name="arrival_time" class="form-control">
</div>

<div class="col-md-3 mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
</div>

<div class="col-md-3 mb-3">
    <label>Expected Revenue</label>
    <input type="number" name="amount" class="form-control" step="0.01">
</div>

<div class="col-md-4">
    <label>Special Occasion (if any)</label>
    <input type="text" name="special_occasion" class="form-control">
</div>
            `);

                $('#restaurantReservationPlacement')
                    .removeClass('d-none')
                    .find('.row')
                    .html(`
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <label>Table Reservation <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-primary w-100" id="openReserveTableModal">
                                <i class="fa fa-calendar-check-o me-2"></i>Reserve Table
                            </button>
                            <div class="small text-muted mt-2" id="restaurant_reservation_summary">No table reserved yet.</div>
                            <div class="text-danger error-label" id="restaurant_reservation_error"></div>
                        </div>
                    `);
            }


            /* BANQUETS */
            else if (department === "banquet") {

                container.append(`

            <div class="col-md-3 mb-3 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_room_required"
                        id="is_room_required" value="1">
                    <label class="form-check-label" for="is_room_required">Is Room Required?</label>
                </div>
            </div>

            <div class="col-md-3 mb-3 room-required-date-fields" style="display:none;">
                <label for="checkin_date">Check-in Date <span class="required-marker">*</span></label>
                <input type="date" id="checkin_date" name="checkin_date" class="form-control" min="${today}">
                <span class="error-text text-danger"></span>
            </div>

            <div class="col-md-3 mb-3 room-required-date-fields" style="display:none;">
                <label for="checkout_date">Check-out Date <span class="required-marker">*</span></label>
                <input type="date" id="checkout_date" name="checkout_date" class="form-control" min="${today}">
                <span class="error-text text-danger"></span>
            </div>

            <div class="col-md-3 mb-3 room-required-count-field" style="display:none;">
                <label for="number_of_rooms">Number of Rooms <span class="required-marker">*</span></label>
                <input type="number" id="number_of_rooms" name="number_of_rooms" class="form-control" min="1" step="1"
                    value="${$('#preserved_number_of_rooms').val() || ''}">
                <span class="error-text text-danger"></span>
            </div>

            <div class="col-md-3 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-3 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            <div class="col-md-3 mb-3">
                <label>Banquet <span class="text-danger">*</span></label>
                <select name="banquet_id" id="banquet_id" class="form-select">
                    <option value="">Select Banquet</option>
                </select>
                <div class="text-danger error-label" id="banquet_id_error"></div>
            </div>



           <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
        

    `);

                loadbanquets(property, existingLeadData);

            } else if (department === "spa") {

                container.append(`


            <div class="col-md-3 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-3 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-3 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
            

        
          <div class="col-md-12">
                    <label>Special Request</label>
                    <textarea name="special_request" class="form-control"></textarea>
                    </div>
            

        


        

    `);


            } else if (department === "water park") {

                container.append(`


            <div class="col-md-3 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-3 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-3 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>

            

        
          <div class="col-md-12">
                    <label>Special Request</label>
                    <textarea name="special_request" class="form-control"></textarea>
                    </div>






    `);


            } else if (department == "wedding") {



                container.append(`
            
                <div class="col-md-3 mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="roomtype" class="form-select filter-input" id="roomtype">
                        
                    </select>
                </div>

                 <div class="col-md-3 mb-3">
                    <label>Meal Plan <span class="text-danger">*</span></label>
                    <select name="meal_plan" id="meal_plan" class="form-select filter-input">
                        <option value="">Select Meal Plan</option>
                    </select>
                    <div class="text-danger error-label" id="meal_plan_error"></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Number of Rooms</label>
                    <input type="number" name="number_of_rooms" class="form-control" min="1"
                        value="${$('#preserved_number_of_rooms').val() || ''}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>No. of Pax</label>
                    <input type="number" name="pax" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Adults</label>
                    <input type="number" name="adults" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Kids</label>
                    <input type="number" name="kids" class="form-control" min="0">
                </div>

            <div class="col-md-3 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

          

            <div class="col-md-3 mb-3">
                <label>Banquet <span class="text-danger">*</span></label>
                <select name="banquet_id" id="banquet_id" class="form-select">
                    <option value="">Select Banquet</option>
                </select>
                <div class="text-danger error-label" id="banquet_id_error"></div>
            </div>

           

           <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
            
            
            
            `)

                loadbanquets(property, existingLeadData);
                loadRoomTypes(property, existingLeadData);
                loadMealPlan(existingLeadData);



            }

            container.append(`
                <div class="col-md-4 mt-3 mb-4">
        <label class="form-label">Follow-up Date</label>
        <input type="date" name="followup_date" class="form-control">
    </div>

    <div class="col-md-4 mt-3 mb-4">
        <label class="form-label">2nd Follow-up Date</label>
        <input type="date" name="second_followup_date" class="form-control">
    </div>
            `)




        }



        // Common fields HTML
        let followupFields = `
    <div class="col-md-3 mb-3">
        <label class="form-label">Booking Enquiry Date</label>
        <input type="date" name="booking_date" class="form-control" value="${today}">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Follow-up Date</label>
        <input type="date" name="followup_date" class="form-control">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">2nd Follow-up Date</label>
        <input type="date" name="second_followup_date" class="form-control">
    </div>
`;

        if (disposition === "Negotiations") {

            $("#lead_status").val('In Progress');
            container.append(followupFields);

        }

        if (disposition === "Not Contacted" || disposition === "Unconfirmed Dates") {

            container.append(followupFields);

        }

        if (disposition === "Advance Received") {

            $("#lead_status").val('Closed');
            container.append(followupFields);

        }



        if (typeof existingLeadData !== "undefined") {

            for (let key in existingLeadData) {

                const field = container.find(`[name="${key}"]`);

                if (field.length) {
                    field.val(existingLeadData[key]);
                }

            }
        }

        initializeSingleSelect2(container);
        container.find('select:not([multiple])').trigger('change.select2');
        $('#lead_status').trigger('change.select2');

    }

    $(document).ajaxComplete(function() {
        const $singleDynamicSelects = $('#dynamicFields select:not([multiple])');
        initializeSingleSelect2($singleDynamicSelects);
        $singleDynamicSelects.trigger('change.select2');
    });


    function loadRestaurants(hotel_id, existingLeadData) {

        $('#restaurant_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-restaurants') ?>",
            type: "POST",
            data: csrfData({
                hotel_id: hotel_id
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Restaurant</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.restaurant_name}</option>`;
                    });
                }

                const $restaurant = $('#restaurant_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.restaurant_id) {
                    $restaurant.val(existingLeadData.restaurant_id);
                } else {
                    const $availableRestaurants = $restaurant.find('option').filter(function() {
                        return String(this.value).trim() !== '';
                    });

                    if ($availableRestaurants.length === 1) {
                        $restaurant.val($availableRestaurants.first().val());
                    }
                }

                initializeSingleSelect2($restaurant);
                $restaurant.trigger('change.select2');

                // This field is rendered dynamically, so load its dependency
                // directly instead of relying on a delegated change event.
                if ($restaurant.val()) {
                    loadTableCategories($restaurant.val());
                } else {
                    $('#table_category_id').html('<option value="">Select Category</option>');
                }
            }
        });
    }


    function loadbanquets(hotel_id, existingLeadData) {

        $('#banquet_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-banquets') ?>",
            type: "POST",
            data: csrfData({
                hotel_id: hotel_id
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Banquet</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.banquet_id}">${row.banquet_name}</option>`;
                    });
                }

                $('#banquet_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.banquet_id) {
                    $('#banquet_id').val(existingLeadData.banquet_id);
                }
            }
        });
    }




    function loadMealPlan(existingLeadData) {

        $('#meal_plan').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-meal-plans') ?>",
            type: "POST",
            data: csrfData({

            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Plan</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.plan}</option>`;
                    });
                }

                $('#meal_plan').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.id) {
                    $('#meal_plan').val(existingLeadData.id);
                }
            }
        });
    }


    function loadpromotional_offers(department, existingLeadData) {

        $('#promotional_offers').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-promotional-offers') ?>",
            type: "POST",
            data: csrfData({
                department_id: department
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Offer</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.offer_name}</option>`;
                    });
                }

                $('#promotional_offers').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.id) {
                    $('promotional_offers').val(existingLeadData.id);
                }
            }
        });
    }

    function loadRoomTypes(hotel_id, existingLeadData) {

        $('#roomtype').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-room-types') ?>",
            type: "POST",
            data: csrfData({
                hotel_id: hotel_id
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select roomtype</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.roomtype_id}">${row.roomtype_name}</option>`;
                    });
                }

                $('#roomtype').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.roomtype) {
                    $('#roomtype').val(existingLeadData.roomtype);
                }
            }
        });
    }

    function loadSlotTypes(existingLeadData) {

        $('#slot_type_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('lead/get-slot-types') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Slot</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.slot_name} (${row.start_time} - ${row.end_time})
                        </option>`;
                    });
                }

                const $slotType = $('#slot_type_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.slot_type_id) {
                    $slotType.val(existingLeadData.slot_type_id);
                }

                initializeSingleSelect2($slotType);

                if ($slotType.val()) {
                    loadTimeSlots($slotType.val());
                }
            }
        });
    }

    let slotTypeDependencyTimer = null;

    function scheduleTimeSlotsRefresh() {
        const slotTypeId = $(this).val();

        clearTimeout(slotTypeDependencyTimer);
        slotTypeDependencyTimer = setTimeout(function() {
            if (slotTypeId) {
                loadTimeSlots(slotTypeId);
            } else {
                $('#time_slot_id')
                    .html('<option value="">Select Time Slot</option>')
                    .trigger('change.select2');
            }
        }, 0);
    }


    function loadTimeSlots(slotTypeId, selectedTimeSlotId = null) {

        $('#time_slot_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-time-slots') ?>",
            type: "POST",
            data: csrfData({
                slot_type_id: slotTypeId
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Time Slot</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.start_time} - ${row.end_time}
                        </option>`;
                    });
                }

                $('#time_slot_id').html(html);


                if (typeof existingLeadData !== "undefined" && existingLeadData.time_slot_id) {
                    $('#time_slot_id').val(existingLeadData.time_slot_id);
                }


            }
        });
    }


    let restaurantDependencyTimer = null;

    function scheduleTableCategoriesRefresh() {
        const restaurantId = $(this).val();

        clearTimeout(restaurantDependencyTimer);
        restaurantDependencyTimer = setTimeout(function() {
            if (restaurantId) {
                loadTableCategories(restaurantId);
            } else {
                $('#table_category_id')
                    .html('<option value="">Select Category</option>')
                    .trigger('change.select2');
                $('#table_id').html('<option value="">Select Table</option>');
                initializeTableMultiSelect();
            }
        }, 0);
    }

    function loadTableCategories(restaurantId, selectedCategoryId = null) {

        $('#table_category_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-table-categories') ?>",
            type: "POST",
            data: csrfData({
                restaurant_id: restaurantId
            }),
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Category</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.category_name}
                        </option>`;
                    });
                }

                const $category = $('#table_category_id').html(html);

                // ✅ For edit case
                if (selectedCategoryId !== null && selectedCategoryId !== "") {
                    $category.val(selectedCategoryId);
                } else {
                    const $availableCategories = $category.find('option').filter(function() {
                        return String(this.value).trim() !== '';
                    });

                    if ($availableCategories.length === 1) {
                        $category.val($availableCategories.first().val());
                    }
                }

                initializeSingleSelect2($category);
                $category.trigger('change.select2');

                // Continue the dynamic dependency chain directly.
                const restaurantId = $('#restaurant_id').val();
                if ($category.val() && restaurantId) {
                    loadTables(restaurantId, $category.val());
                } else {
                    $('#table_id').html('<option value="">Select Table</option>');
                    initializeTableMultiSelect();
                }
            }
        });
    }


    let tableCategoryDependencyTimer = null;

    function scheduleTablesRefresh() {
        const categoryId = $(this).val();
        const restaurantId = $('#restaurant_id').val();

        clearTimeout(tableCategoryDependencyTimer);
        tableCategoryDependencyTimer = setTimeout(function() {
            if (categoryId && restaurantId) {
                loadTables(restaurantId, categoryId);
            } else {
                $('#table_id').html('<option value="">Select Table</option>');
                initializeTableMultiSelect();
            }
        }, 0);
    }


    function syncTableMultiSelect($select, $widget) {
        const selectedValues = ($select.val() || []).map(String);
        const total = $widget.find('.table-multiselect-item').length;
        const selectedCount = selectedValues.length;

        $widget.find('.table-multiselect-item').each(function() {
            $(this).prop('checked', selectedValues.includes(String($(this).val())));
        });

        const $selectAll = $widget.find('.table-multiselect-all');
        $selectAll.prop('checked', total > 0 && selectedCount === total);
        $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < total);

        let summary = 'Select Table';
        if (selectedCount > 0 && selectedCount === total) {
            summary = `All selected (${selectedCount})`;
        } else if (selectedCount > 0) {
            summary = `${selectedCount} selected`;
        }

        $widget.find('.table-multiselect-summary').text(summary);
    }

    // Page-only checkbox multi-select. The original table_id[] select remains the
    // form value source, so submission and database storage are unchanged.
    function initializeTableMultiSelect() {
        const $select = $('#table_id');
        if (!$select.length) return;

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.next('.table-multiselect').remove();
        $select.addClass('table-multiselect-source');

        const $widget = $('<div>', { class: 'table-multiselect' });
        const $toggle = $('<button>', {
            type: 'button',
            class: 'table-multiselect-toggle',
            'aria-expanded': 'false'
        }).append($('<span>', { class: 'table-multiselect-summary', text: 'Select Table' }));
        const $menu = $('<div>', { class: 'table-multiselect-menu' });
        const availableOptions = $select.find('option').filter(function() {
            return String(this.value).trim() !== '';
        });

        if (availableOptions.length) {
            const $selectAll = $('<input>', {
                type: 'checkbox',
                class: 'table-multiselect-all'
            });
            $menu.append(
                $('<label>', { class: 'table-multiselect-option table-multiselect-select-all' })
                    .append($selectAll, $('<span>', { text: 'Select all' }))
            );

            availableOptions.each(function() {
                const $checkbox = $('<input>', {
                    type: 'checkbox',
                    class: 'table-multiselect-item',
                    value: this.value
                });
                $menu.append(
                    $('<label>', { class: 'table-multiselect-option' })
                        .append($checkbox, $('<span>').text($(this).text().trim()))
                );
            });
        } else {
            $menu.append($('<div>', {
                class: 'table-multiselect-empty',
                text: 'No tables available'
            }));
        }

        $widget.append($toggle, $menu);
        $select.after($widget);

        $toggle.on('click', function() {
            const isOpen = !$widget.hasClass('is-open');
            $('.table-multiselect').not($widget).removeClass('is-open')
                .find('.table-multiselect-toggle').attr('aria-expanded', 'false');
            $widget.toggleClass('is-open', isOpen);
            $toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
        });

        $widget.on('change', '.table-multiselect-all', function() {
            const values = this.checked
                ? $widget.find('.table-multiselect-item').map(function() { return this.value; }).get()
                : [];
            $select.val(values).trigger('change');
        });

        $widget.on('change', '.table-multiselect-item', function() {
            const values = $widget.find('.table-multiselect-item:checked')
                .map(function() { return this.value; }).get();
            $select.val(values).trigger('change');
        });

        $select.off('change.tableMultiSelect').on('change.tableMultiSelect', function() {
            syncTableMultiSelect($select, $widget);
        });

        syncTableMultiSelect($select, $widget);
    }

    $(document).off('click.tableMultiSelect').on('click.tableMultiSelect', function(e) {
        if (!$(e.target).closest('.table-multiselect').length) {
            $('.table-multiselect').removeClass('is-open')
                .find('.table-multiselect-toggle').attr('aria-expanded', 'false');
        }
    });


    // Load tables
    function loadTables(restaurantId, categoryId, selectedTableId = null) {

        // Destroy previous Select2
        if ($('#table_id').hasClass('select2-hidden-accessible')) {
            $('#table_id').select2('destroy');
        }

        $('#table_id').html('<option value="">Select Table</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-tables') ?>",
            type: "POST",
            data: csrfData({
                restaurant_id: restaurantId,
                category_id: categoryId
            }),
            dataType: "json",
            success: function(res) {

                let html = '';

                if (res.status === 'success') {

                    $.each(res.data, function(i, row) {

                        html += `
                        <option value="${row.id}">
                            Table ${row.table_name} (${row.capacity} Seats)
                        </option>`;
                    });
                }

                $('#table_id').html(html);

                // Rebuild the checkbox list with the loaded tables.
                initializeTableMultiSelect();

                // Edit case (multiple selected tables)
                if (selectedTableId !== null && selectedTableId !== "") {

                    let tableIds = [];

                    if (Array.isArray(selectedTableId)) {

                        tableIds = selectedTableId;

                    } else {

                        tableIds = String(selectedTableId)
                            .split(',')
                            .map(id => id.trim());
                    }

                    $('#table_id')
                        .val(tableIds)
                        .trigger('change');
                }
            }
        });
    }


    $(document).on("input", "input[name='adults'], input[name='kids']", function() {

        let adults = parseInt($("input[name='adults']").val()) || 0;
        let kids = parseInt($("input[name='kids']").val()) || 0;

        let pax = adults + kids;

        $("input[name='pax']").val(pax);

    });


    $(document).on('input', '.revenue-field', function() {

        var room = parseFloat($('#revenue_room').val()) || 0;
        var fnb = parseFloat($('#revenue_fnb').val()) || 0;
        var other = parseFloat($('#revenue_other').val()) || 0;

        var total = room + fnb + other;

        $('#amount').val(total.toFixed(2));

    });

    let reservationModalCanClose = false;
    let reservationTables = [];
    let reservationAvailabilityRequest = 0;

    function reservationToday() {
        const now = new Date();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${now.getFullYear()}-${month}-${day}`;
    }

    function formatReservationTime(time) {
        if (!time) return '';
        const parts = String(time).split(':');
        let hour = parseInt(parts[0], 10);
        if (Number.isNaN(hour)) return time;
        const minutes = parts[1] || '00';
        const suffix = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12;
        return `${String(hour).padStart(2, '0')}:${minutes} ${suffix}`;
    }

    function resetReservationMessages() {
        $('#reserveTableModal .is-invalid').removeClass('is-invalid');
        $('#reserve_restaurant_error, #reserve_booking_date_error, #reserve_slot_type_error, #reserve_time_slot_error, #reserve_table_category_error, #reserve_table_error, #reserve_status_error').text('');
    }

    function resetReservationStats() {
        $('#reserve_available_count, #reserve_occupied_count, #reserve_reserved_count, #reserve_blocked_count, #reserve_checkout_count').text('0');
    }

    function reservationTableLabels(table) {
        return [
            table.table_name,
            table.table_number,
            table.table_number ? `Table ${table.table_number}` : ''
        ].filter(Boolean).map(function(label) {
            return String(label).trim().toLowerCase();
        });
    }

    function renderReservationTables(conflictingTables = [], selectedTableIds = []) {
        const $grid = $('#reserve_table_grid').empty();
        const conflictLabels = conflictingTables.map(function(label) {
            return String(label).trim().toLowerCase();
        });
        const selectedIds = selectedTableIds.map(String);
        let availableCount = 0;
        let reservedCount = 0;

        if (!reservationTables.length) {
            $grid.append($('<div>', {
                class: 'text-muted',
                text: 'No tables are available for the selected restaurant and category.'
            }));
            resetReservationStats();
            return;
        }

        reservationTables.forEach(function(table) {
            const tableId = String(table.id);
            const unavailable = reservationTableLabels(table).some(function(label) {
                return conflictLabels.includes(label);
            });
            const selected = !unavailable && selectedIds.includes(tableId);
            const tableNumber = table.table_number || table.table_name || `T${table.id}`;
            const capacity = table.capacity ? `${table.capacity} Guests` : 'Guests not specified';
            const category = $('#reserve_table_category_id option:selected').text() || 'Table';

            if (unavailable) {
                reservedCount++;
            } else {
                availableCount++;
            }

            const $card = $('<div>', {
                class: `reservation-table-card${unavailable ? ' unavailable' : ''}${selected ? ' selected' : ''}`,
                'data-table-id': tableId,
                'data-available': unavailable ? '0' : '1'
            });
            $card.append(
                $('<span>', { class: 'table-icon' }).append($('<i>', { class: 'fa fa-cutlery' })),
                $('<div>', { class: 'table-number', text: tableNumber }),
                $('<div>', { class: 'table-details' }).append(
                    document.createTextNode(capacity),
                    $('<br>'),
                    document.createTextNode(category)
                ),
                $('<span>', {
                    class: `table-status ${unavailable ? 'reserved' : 'available'}`,
                    text: unavailable ? 'Reserved' : 'Available'
                })
            );
            $grid.append($card);
        });

        $('#reserve_available_count').text(availableCount);
        $('#reserve_reserved_count').text(reservedCount);
        $('#reserve_occupied_count, #reserve_blocked_count, #reserve_checkout_count').text('0');
    }

    function selectedReservationTableIds() {
        return $('#reserve_table_grid .reservation-table-card.selected').map(function() {
            return String($(this).data('table-id'));
        }).get();
    }

    function refreshReservationAvailability(selectedTableIds = null) {
        const bookingDate = $('#reserve_booking_date').val();
        const restaurantId = $('#reserve_restaurant_id').val();
        const categoryId = $('#reserve_table_category_id').val();
        const timeSlotId = $('#reserve_time_slot_id').val();
        const slotTypeId = $('#reserve_slot_type_id').val();
        const allTableIds = reservationTables.map(function(table) { return table.id; });
        const preservedSelection = selectedTableIds === null
            ? selectedReservationTableIds()
            : selectedTableIds.map(String);

        if (!bookingDate || !restaurantId || !categoryId || !timeSlotId || !slotTypeId || !allTableIds.length) {
            renderReservationTables([], preservedSelection);
            return;
        }

        const requestId = ++reservationAvailabilityRequest;
        $('#reserve_table_grid').addClass('opacity-50');

        csrfAjax({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: csrfData({
                booking_date: bookingDate,
                restaurant_id: restaurantId,
                table_category_id: categoryId,
                table_ids: allTableIds,
                slot_type_id: slotTypeId
            }),
            dataType: 'json',
            success: function(res) {
                if (requestId !== reservationAvailabilityRequest) return;
                const selectedSlot = (res.data || []).find(function(slot) {
                    return String(slot.id) === String(timeSlotId);
                });
                renderReservationTables(selectedSlot ? (selectedSlot.conflicting_tables || []) : [], preservedSelection);
            },
            error: function(xhr) {
                if (requestId !== reservationAvailabilityRequest) return;
                const response = xhr.responseJSON || {};
                renderReservationTables([], preservedSelection);
                $('#reserve_table_error').text(response.message || 'Unable to check table availability.');
            },
            complete: function() {
                if (requestId === reservationAvailabilityRequest) {
                    $('#reserve_table_grid').removeClass('opacity-50');
                }
            }
        });
    }

    function loadReservationTables(restaurantId, categoryId, selectedTableIds = []) {
        reservationTables = [];
        resetReservationStats();
        $('#reserve_table_grid').html('<div class="text-muted">Loading tables...</div>');

        if (!restaurantId || !categoryId) {
            $('#reserve_table_grid').html('<div class="text-muted">Select a restaurant and table category to view tables.</div>');
            return;
        }

        csrfAjax({
            url: "<?= base_url('lead/get-tables') ?>",
            type: 'POST',
            data: csrfData({
                restaurant_id: restaurantId,
                category_id: categoryId
            }),
            dataType: 'json',
            success: function(res) {
                reservationTables = res.status === 'success' ? (res.data || []) : [];
                renderReservationTables([], selectedTableIds);
                refreshReservationAvailability(selectedTableIds);
            },
            error: function() {
                reservationTables = [];
                renderReservationTables();
                $('#reserve_table_error').text('Unable to load restaurant tables.');
            }
        });
    }

    function loadReservationCategories(restaurantId, selectedCategoryId = '', selectedTableIds = []) {
        const $category = $('#reserve_table_category_id')
            .html('<option value="">Loading...</option>')
            .prop('disabled', true);
        reservationTables = [];
        resetReservationStats();
        $('#reserve_table_grid').html('<div class="text-muted">Select a table category to view tables.</div>');

        if (!restaurantId) {
            $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
            return;
        }

        csrfAjax({
            url: "<?= base_url('lead/get-table-categories') ?>",
            type: 'POST',
            data: csrfData({ restaurant_id: restaurantId }),
            dataType: 'json',
            success: function(res) {
                $category.empty().append($('<option>', { value: '', text: 'Select Table Category' }));
                if (res.status === 'success') {
                    (res.data || []).forEach(function(row) {
                        $category.append($('<option>', { value: row.id, text: row.category_name }));
                    });
                }
                if (selectedCategoryId) {
                    $category.val(String(selectedCategoryId));
                } else if ($category.find('option[value!=""]').length === 1) {
                    $category.val($category.find('option[value!=""]').val());
                }
                $category.prop('disabled', false);
                if ($category.val()) {
                    loadReservationTables(restaurantId, $category.val(), selectedTableIds);
                }
            },
            error: function() {
                $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
                $('#reserve_table_category_error').text('Unable to load table categories.');
            }
        });
    }

    function loadReservationRestaurants(selectedRestaurantId = '', selectedCategoryId = '', selectedTableIds = []) {
        const hotelId = $('#property').val();
        const $restaurant = $('#reserve_restaurant_id')
            .html('<option value="">Loading...</option>')
            .prop('disabled', true);

        csrfAjax({
            url: "<?= base_url('lead/get-restaurants') ?>",
            type: 'POST',
            data: csrfData({ hotel_id: hotelId }),
            dataType: 'json',
            success: function(res) {
                $restaurant.empty().append($('<option>', { value: '', text: 'Select Restaurant' }));
                if (res.status === 'success') {
                    (res.data || []).forEach(function(row) {
                        $restaurant.append($('<option>', { value: row.id, text: row.restaurant_name }));
                    });
                }
                if (selectedRestaurantId) {
                    $restaurant.val(String(selectedRestaurantId));
                } else if ($restaurant.find('option[value!=""]').length === 1) {
                    $restaurant.val($restaurant.find('option[value!=""]').val());
                }
                $restaurant.prop('disabled', false);
                loadReservationCategories($restaurant.val(), selectedCategoryId, selectedTableIds);
            },
            error: function() {
                $restaurant.html('<option value="">Select Restaurant</option>').prop('disabled', false);
                $('#reserve_restaurant_error').text('Unable to load restaurants.');
            }
        });
    }

    function loadReservationSlotTypes(selectedSlotTypeId = '', selectedTimeSlotId = '') {
        const $slotType = $('#reserve_slot_type_id')
            .html('<option value="">Loading...</option>')
            .prop('disabled', true);
        const $timeSlot = $('#reserve_time_slot_id')
            .html('<option value="">Select Slot Type first</option>')
            .prop('disabled', true);

        $.ajax({
            url: "<?= base_url('lead/get-slot-types') ?>",
            type: 'GET',
            dataType: 'json'
        }).done(function(slotTypeResponse) {
            const slotTypes = slotTypeResponse.status === 'success' ? (slotTypeResponse.data || []) : [];
            $slotType.empty().append($('<option>', { value: '', text: 'Select Slot Type' }));
            slotTypes.forEach(function(row) {
                $slotType.append($('<option>', {
                    value: row.id,
                    text: row.slot_name || row.name || 'Slot Type'
                }));
            });
            if (selectedSlotTypeId) {
                $slotType.val(String(selectedSlotTypeId));
            }
            $slotType.prop('disabled', false);

            if ($slotType.val()) {
                loadReservationTimeSlots($slotType.val(), selectedTimeSlotId);
            }
        }).fail(function() {
            $slotType.html('<option value="">Select Slot Type</option>').prop('disabled', false);
            $('#reserve_slot_type_error').text('Unable to load slot types.');
        });
    }

    function loadReservationTimeSlots(slotTypeId, selectedTimeSlotId = '') {
        const $timeSlot = $('#reserve_time_slot_id')
            .html(`<option value="">${slotTypeId ? 'Loading...' : 'Select Slot Type first'}</option>`)
            .prop('disabled', true);

        if (!slotTypeId) {
            refreshReservationAvailability();
            return;
        }

        $.ajax({
            url: "<?= base_url('lead/get-time-slots') ?>",
            type: 'GET',
            data: { slot_type_id: slotTypeId },
            dataType: 'json'
        }).done(function(response) {
            $timeSlot.empty().append($('<option>', { value: '', text: 'Select Time Slot' }));
            if (response.status === 'success') {
                (response.data || []).forEach(function(slot) {
                    const label = slot.start_time && slot.end_time
                        ? `${formatReservationTime(slot.start_time)} - ${formatReservationTime(slot.end_time)}`
                        : (slot.slot_name || 'Time Slot');
                    $timeSlot.append($('<option>', { value: slot.id, text: label }));
                });
            }
            if (selectedTimeSlotId) {
                $timeSlot.val(String(selectedTimeSlotId));
            }
            $timeSlot.prop('disabled', false);
            refreshReservationAvailability();
        }).fail(function() {
            $timeSlot.html('<option value="">Select Time Slot</option>').prop('disabled', false);
            $('#reserve_time_slot_error').text('Unable to load time slots.');
        });
    }

    function openReservationModal() {
        resetReservationMessages();
        reservationModalCanClose = false;

        const bookingDate = $('#restaurant_booking_date').val() || reservationToday();
        const selectedRestaurantId = $('#restaurant_id').val() || '';
        const selectedCategoryId = $('#table_category_id').val() || '';
        const selectedSlotTypeId = $('#slot_type_id').val() || '';
        const selectedTimeSlotId = $('#time_slot_id').val() || '';
        const selectedTableIds = ($('#table_id').val() || []).map(String);
        const status = $('#table_reservation_status').val() || 'Reserved';

        $('#reserve_booking_date').attr('min', reservationToday()).val(bookingDate);
        $('#reserve_special_request').val($('#restaurant_special_request').val() || '');
        $(`input[name="reserve_status"][value="${status}"]`).prop('checked', true);

        loadReservationRestaurants(selectedRestaurantId, selectedCategoryId, selectedTableIds);
        loadReservationSlotTypes(selectedSlotTypeId, selectedTimeSlotId);
        $('#reserveTableModal').modal({ backdrop: 'static', keyboard: false });
        $('#reserveTableModal').modal('show');
    }

    function closeReservationModal() {
        reservationModalCanClose = true;
        $('#reserveTableModal').modal('hide');
    }

    function commitReservation() {
        const restaurantId = $('#reserve_restaurant_id').val();
        const bookingDate = $('#reserve_booking_date').val();
        const $timeSlotOption = $('#reserve_time_slot_id option:selected');
        const timeSlotId = $('#reserve_time_slot_id').val();
        const slotTypeId = $('#reserve_slot_type_id').val();
        const categoryId = $('#reserve_table_category_id').val();
        const tableIds = selectedReservationTableIds();
        const status = $('input[name="reserve_status"]:checked').val();
        const errors = {};

        resetReservationMessages();
        if (!restaurantId) errors.restaurant = 'Please select a restaurant.';
        if (!bookingDate) errors.bookingDate = 'Please select a booking date.';
        if (!slotTypeId) errors.slotType = 'Please select a slot type.';
        if (!timeSlotId) errors.timeSlot = 'Please select a time slot.';
        if (!categoryId) errors.category = 'Please select a table category.';
        if (!tableIds.length) errors.tables = 'Please select at least one available table.';
        if (!status) errors.status = 'Please select a reservation status.';

        if (errors.restaurant) $('#reserve_restaurant_id').addClass('is-invalid');
        if (errors.bookingDate) $('#reserve_booking_date').addClass('is-invalid');
        if (errors.slotType) $('#reserve_slot_type_id').addClass('is-invalid');
        if (errors.timeSlot) $('#reserve_time_slot_id').addClass('is-invalid');
        if (errors.category) $('#reserve_table_category_id').addClass('is-invalid');
        $('#reserve_restaurant_error').text(errors.restaurant || '');
        $('#reserve_booking_date_error').text(errors.bookingDate || '');
        $('#reserve_slot_type_error').text(errors.slotType || '');
        $('#reserve_time_slot_error').text(errors.timeSlot || '');
        $('#reserve_table_category_error').text(errors.category || '');
        $('#reserve_table_error').text(errors.tables || '');
        $('#reserve_status_error').text(errors.status || '');
        if (Object.keys(errors).length) return;

        const $confirmButton = $('#confirmTableReservation').prop('disabled', true);
        const originalButtonHtml = $confirmButton.html();
        $confirmButton.text('Checking availability...');

        csrfAjax({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: csrfData({
                booking_date: bookingDate,
                restaurant_id: restaurantId,
                table_category_id: categoryId,
                table_ids: tableIds,
                slot_type_id: slotTypeId
            }),
            dataType: 'json',
            success: function(res) {
                const selectedSlot = (res.data || []).find(function(slot) {
                    return String(slot.id) === String(timeSlotId);
                });
                if (!selectedSlot || !selectedSlot.available) {
                    $('#reserve_table_error').text(selectedSlot
                        ? selectedSlot.reason
                        : 'The selected time slot is unavailable.');
                    refreshReservationAvailability(tableIds);
                    return;
                }

                $('#restaurant_booking_date').val(bookingDate);
                $('#restaurant_id').val(restaurantId);
                $('#table_category_id').val(categoryId);
                $('#slot_type_id').val(slotTypeId);
                $('#time_slot_id').val(timeSlotId);
                $('#table_reservation_status').val(status);
                $('#restaurant_special_request').val($('#reserve_special_request').val());

                const $tableField = $('#table_id').empty();
                tableIds.forEach(function(tableId) {
                    $tableField.append($('<option>', {
                        value: tableId,
                        text: tableId,
                        selected: true
                    }));
                });

                const restaurantName = $('#reserve_restaurant_id option:selected').text();
                const categoryName = $('#reserve_table_category_id option:selected').text();
                const slotTypeName = $('#reserve_slot_type_id option:selected').text();
                const timeSlotName = $timeSlotOption.text();
                $('#restaurant_reservation_summary')
                    .removeClass('text-muted')
                    .addClass('text-success')
                    .text(`${restaurantName} • ${bookingDate} • ${slotTypeName} • ${timeSlotName} • ${categoryName} • ${tableIds.length} table(s) • ${status}`);
                $('#openReserveTableModal').html('<i class="fa fa-pencil me-2"></i>Edit Reservation');
                $('#restaurant_reservation_error').text('');
                closeReservationModal();
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                $('#reserve_table_error').text(response.message || 'Unable to verify table availability.');
                refreshReservationAvailability(tableIds);
            },
            complete: function() {
                $confirmButton.prop('disabled', false).html(originalButtonHtml);
            }
        });
    }

    $(document).on('click', '#openReserveTableModal', openReservationModal);
    $(document).on('click', '#closeReserveTableModal, #cancelReserveTableModal', closeReservationModal);
    $(document).on('click', '#confirmTableReservation', commitReservation);

    $(document).on('change', '#reserve_restaurant_id', function() {
        resetReservationMessages();
        loadReservationCategories($(this).val());
    });

    $(document).on('change', '#reserve_table_category_id', function() {
        resetReservationMessages();
        loadReservationTables($('#reserve_restaurant_id').val(), $(this).val());
    });

    $(document).on('change', '#reserve_slot_type_id', function() {
        resetReservationMessages();
        loadReservationTimeSlots($(this).val());
    });

    $(document).on('change', '#reserve_booking_date, #reserve_time_slot_id', function() {
        resetReservationMessages();
        refreshReservationAvailability();
    });

    $(document).on('click', '#reserve_table_grid .reservation-table-card', function() {
        if ($(this).data('available') !== 1 && String($(this).data('available')) !== '1') return;
        $(this).toggleClass('selected');
        $('#reserve_table_error').text('');
    });

    $('#reserveTableModal')
        .on('hide.bs.modal', function(event) {
            if (!reservationModalCanClose) {
                event.preventDefault();
            }
        })
        .on('hidden.bs.modal', function() {
            reservationModalCanClose = false;
        });

    function leadField(field) {
        if (
            ['booking_date', 'restaurant_id', 'table_category_id', 'table_id', 'slot_type_id', 'time_slot_id', 'table_reservation_status'].includes(field)
            && $('#openReserveTableModal').length
        ) {
            return $('#openReserveTableModal');
        }

        return field === 'table_id' ? $('#table_id') : $('[name="' + field + '"]').first();
    }

    function showLeadFieldError(field, message) {
        const input = leadField(field);
        let error = $('#' + field + '_error');

        if (input.is('#openReserveTableModal')) {
            input.addClass('is-invalid').attr('aria-invalid', 'true');
            $('#restaurant_reservation_error').text('Please complete the table reservation.');
            return;
        }

        input.addClass('is-invalid').attr('aria-invalid', 'true');
        if (!error.length) {
            error = $('<div>', {
                id: field + '_error',
                class: 'text-danger small lead-validation-error'
            });
            input.closest('.form-group, .mb-3, [class*="col-"]').first().append(error);
        }
        error.text(message);
    }

    function clearLeadValidation() {
        $('#leadForm .is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
        $('#leadForm [id$="_error"]').text('');
        $('#leadForm .lead-validation-error').text('');
        $('#restaurant_reservation_error').text('');
    }

    function showLeadValidationErrors(errors) {
        clearLeadValidation();
        let firstField = null;

        $.each(errors, function(field, message) {
            showLeadFieldError(field, message);
            if (!firstField) {
                firstField = leadField(field);
            }
        });

        if (firstField && firstField.length) {
            $('html, body').animate({
                scrollTop: Math.max(firstField.offset().top - 140, 0)
            }, 250);
            firstField.trigger('focus');
        }
    }

    function validateLeadForm() {
        const errors = {};
        const enforceRoleFollowupDateOrder = <?= json_encode(in_array(strtolower($lead_form_role_label), ['agent', 'hotel admin', 'agency'], true)); ?>;
        const value = function(name) {
            return $.trim(String($('[name="' + name + '"]').first().val() || ''));
        };
        const phone = value('phone_number').replace(/^(\+91|91)/, '');
        const email = value('email');
        const disposition = value('disposition');
        const department = normalizeDepartmentName($('#leadDepartment').val());

        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            errors.phone_number = phone ? 'Enter a valid 10-digit Indian mobile number.' : 'Phone number is required.';
        }
        if (disposition !== 'Not Contacted' && !value('username')) {
            errors.username = 'Guest name is required.';
        }
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Enter a valid email address.';
        }
        if (!value('property')) errors.property = 'Please select a hotel.';
        if (!value('type')) errors.type = 'Please select a department.';
        if (!value('user_channel')) errors.user_channel = 'Please select a lead source.';
        if (!disposition) errors.disposition = 'Please select a stage.';
        if (!value('lead_status')) errors.lead_status = 'Please select a lead status.';
        if (!value('query')) errors.query = 'Query is required.';

        if (disposition === 'Lead Lost' && !value('reason')) {
            errors.reason = 'Please select a reason.';
        }

        if (enforceRoleFollowupDateOrder) {
            const bookingDate = value('booking_date');
            const followupDate = value('followup_date');
            const secondFollowupDate = value('second_followup_date');

            if (bookingDate && followupDate && followupDate >= bookingDate) {
                errors.followup_date = 'Follow-up Date must be before Booking Date.';
            }
            if (bookingDate && secondFollowupDate && secondFollowupDate >= bookingDate) {
                errors.second_followup_date = '2nd Follow-up Date must be before Booking Date.';
            }
            if (secondFollowupDate && !followupDate) {
                errors.followup_date = 'Follow-up Date is required before entering a 2nd Follow-up Date.';
            } else if (followupDate && secondFollowupDate && secondFollowupDate <= followupDate) {
                errors.second_followup_date = '2nd Follow-up Date must be later than Follow-up Date.';
            }
        }

        if (disposition === 'Quotation Sent') {
            if ($('#is_room_required').is(':checked')) {
                const checkinDate = value('checkin_date');
                const checkoutDate = value('checkout_date');
                const numberOfRooms = value('number_of_rooms');
                const today = new Date().toISOString().split('T')[0];

                if (!checkinDate) {
                    errors.checkin_date = 'Check-in date is required.';
                } else if (checkinDate < today) {
                    errors.checkin_date = 'Check-in date cannot be in the past.';
                }
                if (!checkoutDate) {
                    errors.checkout_date = 'Check-out date is required.';
                } else if (checkinDate && checkoutDate < checkinDate) {
                    errors.checkout_date = 'Check-out date must be the same as or after check-in date.';
                }
                if ($('.room-required-count-field').length && !/^[1-9][0-9]*$/.test(numberOfRooms)) {
                    errors.number_of_rooms = numberOfRooms
                        ? 'Number of rooms must be a positive whole number.'
                        : 'Number of rooms is required.';
                }
            }
            if ((department === 'rooms' || department === 'wedding') && !value('meal_plan')) {
                errors.meal_plan = 'Please select a meal plan.';
            }
            if ((department === 'banquet' || department === 'wedding') && !value('banquet_id')) {
                errors.banquet_id = 'Please select a banquet.';
            }
            if (department === 'restaurant') {
                if (!value('restaurant_id')) errors.restaurant_id = 'Please select a restaurant.';
                if (!value('slot_type_id')) errors.slot_type_id = 'Please select a slot type.';
                if (!value('time_slot_id')) errors.time_slot_id = 'Please select a time slot.';
                if (!value('table_category_id')) errors.table_category_id = 'Please select a table category.';
                if (!$('#table_id').val() || $('#table_id').val().length === 0) errors.table_id = 'Please select at least one table.';
                if (!value('table_reservation_status')) errors.table_reservation_status = 'Please select a reservation status.';
            }
        }

        showLeadValidationErrors(errors);
        return Object.keys(errors).length === 0;
    }

    $(document).on('input change', '#leadForm input, #leadForm select, #leadForm textarea', function() {
        const field = this.id === 'table_id' ? 'table_id' : ($(this).attr('name') || '').replace('[]', '');
        $(this).removeClass('is-invalid').removeAttr('aria-invalid');
        if (field) $('#' + field + '_error').text('');
    });

    $(document).on('change', '#is_room_required', function() {
        const roomRequired = this.checked;
        const roomFields = $('.room-required-date-fields, .room-required-count-field');
        const roomInputs = roomFields.find('input');

        roomFields.toggle(roomRequired);
        roomInputs.prop('required', roomRequired);
        if (!roomRequired) {
            roomInputs.val('').removeClass('is-invalid').removeAttr('aria-invalid');
            $('#preserved_number_of_rooms').val('');
            roomFields.find('.error-text, .lead-validation-error').text('');
        }
    });

    $(document).on('change', '#checkin_date', function() {
        const today = new Date().toISOString().split('T')[0];
        $('#checkout_date').attr('min', $(this).val() || today);
    });

    $('#leadForm').on('submit', function(e) {

        e.preventDefault();

        $("#lead_status").prop('disabled', false);

        if (!validateLeadForm()) {
            $("#lead_status").prop('disabled', true);
            return false;
        }

        // Grab field values
        let username = $('input[name="username"]').val().trim();
        let phone = $('input[name="phone_number"]').val().trim();

        phone = phone.trim();
        phone = phone.replace(/^(\+91|91)/, '');

        let email = $('input[name="email"]').val().trim();
        let userChannel = $('select[name="user_channel"]').val();
        let property = $('select[name="property"]').val();
        let department = $('select[name="type"]').val();
        let leadStatus = 'Open'
        let query = $('textarea[name="query"]').val().trim();
        let remark = $('textarea[name="remark"]').val().trim();
        let lead_type = $('select[name="lead_type"]').val();
        let lead_status = $('select[name="lead_status"]').val();
        let assigned_person_user_role = $('input[name="assigned_person_user_role"]').val();
        let assigned_person_email = $('#assigned_person_email').val();
        let purpose = $('#purpose').val();
        let promotional_offers = $('#promotional_offers').val();
        let assigned_to = $('select[name="assigned_to"]').val();

        let leadDepartment = normalizeDepartmentName($('#leadDepartment').val());
        let disposition = $('#disposition').val();

        if (disposition == null) {
            disposition = "";
        }

        // Simple validation condition
        if (
            phone && userChannel &&
            property && department && leadStatus && query &&
            (disposition === 'Not Contacted' || username)
        ) {
            let formData = new FormData();

            $('#dynamicFields')
                .find('input, select, textarea')
                .each(function() {
                    let name = $(this).attr('name');
                    let value;

                    if ($(this).attr('type') === 'file') {
                        // file input
                        if (this.files.length > 0) {
                            formData.append(name, this.files[0]);
                        }
                    } else if ($(this).attr('type') === 'checkbox') {
                        if (this.checked) {
                            formData.append(name, $(this).val());
                        }
                    } else if (name === 'table_id[]') {
                        // Skip table_id[] - we'll handle it separately below
                    } else {
                        value = $(this).val();
                        formData.append(name, value);
                    }
                });

            // Handle table_id separately - post each selected table as table_id[]
            let selectedTables = $('#table_id').val();
            if (selectedTables && Array.isArray(selectedTables) && selectedTables.length > 0) {
                selectedTables.forEach(function(tableId) {
                    formData.append('table_id[]', tableId);
                });
            } else if (selectedTables) {
                formData.append('table_id[]', selectedTables);
            }


            // Append all fields
            formData.append('user_name', username);
            formData.append('phone_number', phone);
            formData.append('email', email);
            formData.append('disposition', disposition);

            formData.append('assigned_person_user_role', assigned_person_user_role);
            formData.append('assigned_to', assigned_to);
            formData.append('assigned_person_email', assigned_person_email);

            formData.append('user_channel', userChannel);
            formData.append('purpose', purpose);

            formData.append('property', property);
            formData.append('type', department);
            formData.append('status', lead_status);
            formData.append('query', query);
            formData.append('remark', remark);
            formData.append('lead_type', lead_type);
            formData.append('leadDepartment', leadDepartment);
            if (!formData.has('number_of_rooms') && $('#preserved_number_of_rooms').val() !== '') {
                formData.append('number_of_rooms', $('#preserved_number_of_rooms').val());
                formData.append('cross_department_room_count_controlled', '1');
            }
            // here also want to add table reservation status for restaurant department
            if (leadDepartment === 'restaurant') {
                let tableReservationStatus = $('#table_reservation_status').val();
                formData.append('table_reservation_status', tableReservationStatus);
            }
            csrfFormData(formData);

            csrfAjax({
                url: <?= json_encode($lead_form_submit_url); ?>,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },
                success: function(response) {

                    if (response.duplicate) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Duplicate Lead Detected',
                            text: response.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#8f73df'
                        }).then(function() {
                            window.location.href = <?= json_encode($lead_form_redirect_url); ?>;
                        });
                        return;
                    }

                    if (response.status) {
                        window.location.href = <?= json_encode($lead_form_redirect_url); ?>
                    } else {
                        alert('Failed to create lead: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let message = 'Unable to submit lead. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        showLeadValidationErrors(xhr.responseJSON.errors);
                    }
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        message = xhr.responseText;
                    }

                    if (!xhr.responseJSON || !xhr.responseJSON.errors) {
                        alert(message);
                    }
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('Submit');
                }
            });
        } else {
            alert("Please fill all required fields.");
        }
    });


    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 300; // milliseconds delay after user stops typing

        $('#phone_number').on('input', function() {
            clearTimeout(typingTimer);
            const cli = $(this).val();

            if (/^\d{10}$/.test(cli)) {
                typingTimer = setTimeout(function() {
                    // Perform AJAX call only for valid 10-digit numbers
                    csrfAjax({
                        url: '<?= base_url('LeadController/get_last_lead_by_cli') ?>',
                        type: 'POST',
                        data: csrfData({
                            cli: cli
                        }),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#username').val(response.data.user_name);
                                $('#phone_number').val(
                                    response.data.phone_number ?
                                    response.data.phone_number.toString().replace(/\D/g, '').slice(-10) :
                                    ''
                                );
                                $('#email').val(response.data.email);
                                $('#query').val(response.data.query);
                            } else {
                                console.log('No lead data found');
                            }
                        },
                        error: function(xhr) {
                            console.error('AJAX error:', xhr.responseText);
                        }
                    });
                }, typingDelay);
            }
        });
    });
</script>

<!-- Bootstrap Modal -->
<div class="modal modal-lg" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Table -->
                <table class="table table-bordered" id="leadRoomsRateTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Room Type</th>
                            <th>Total Rooms</th>
                            <th>Available Rooms</th>
                            <th>In House</th>
                            <th>Confirmed</th>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<div id="leadProcessingLoader"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">

    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">

        <div class="spinner-border text-light" style="width: 4rem; height: 4rem;" role="status"></div>

        <div style="color:white; font-size:20px; margin-top:15px; font-weight:600;">
            Processing...
        </div>

    </div>
</div>





<script>
    $(document).on("click", "#checkAvailabilityBtn", function() {

        let checkin = $("#checkin_date").val();
        let checkout = $("#checkout_date").val();
        let roomtype = $("#roomtype").val();


        if (!checkin || !checkout) {
            alert("Please select check-in and check-out dates");
            return;
        }

        csrfAjax({
            url: "<?= base_url('LeadController/getRoomRateAvailabilityAjax') ?>",
            type: "POST",
            data: csrfData({
                hotel_code: "E0701",
                date_arrive: checkin,
                date_depart: checkout,
                adults: 1,
                youths: 0,
                kids: 0,
                number_of_rooms: "",
                price_from: 0,
                price_to: 0,
                room_type_code: ''
            }),
            dataType: "json",
            beforeSend: function() {
                $("#leadProcessingLoader").show(); // Show loader
            },

            success: function(res) {
                if (res.status) {
                    renderRateTable(res.data.availability);

                    // Open modal
                    let myModal = new bootstrap.Modal(document.getElementById('availabilityModal'));
                    myModal.show();
                } else {
                    alert("No data found");
                }
            },
            complete: function() {
                $("#leadProcessingLoader").hide(); // Hide loader ALWAYS
            }
        });

    });

    function renderRateTable(data) {
        let html = "";

        data.forEach(function(item) {
            html += `
            <tr>
                <td>${item.date}</td>
                <td>${item.room_type_code}</td>
                <td>${item.rooms_total}</td>
                <td>${item.available_rooms}</td>
                <td>${item.rooms_inhouse}</td>
                <td>${item.rooms_confirmed}</td>
               
            </tr>
        `;
        });

        $("#leadRoomsRateTable tbody").html(html);
    }

    // Delegated event for dynamic fields
    $(document).on("change", "#checkin_date", function() {
        let today = new Date().toISOString().split("T")[0];
        let checkin = $(this).val();

        if (checkin < today) {
            showError($(this), "Check-in date cannot be in the past");
        } else {
            hideError($(this));
        }
    });

    $(document).on("change", "#checkout_date", function() {
        let checkin = $("#checkin_date").val();
        let checkout = $(this).val();

        if (checkout < checkin) {
            showError($(this), "Check-out date must be same or future date");
        } else {
            hideError($(this));
        }
    });

    function showError(input, msg) {
        input.addClass("is-invalid");
        input.siblings(".error-text").text(msg);
    }

    function hideError(input) {
        input.removeClass("is-invalid");
        input.siblings(".error-text").text("");
    }



    // Date formatting function
    function formatDate(dateObj) {
        let d = dateObj.getDate().toString().padStart(2, '0');
        let m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        let y = dateObj.getFullYear();

        return `${d}-${m}-${y}`;
    }
</script>
