<style>
    #salesVisitForm .select2-container { width: 100% !important; }
    #salesVisitForm .select2-selection--single { height: 46px !important; padding: 11px 14px; }
    #salesVisitForm .select2-selection__rendered { line-height: 22px; padding-left: 0; }
    #salesVisitForm .select2-selection__arrow { height: 44px; }

    #salesVisitForm .field-label-icon {
        display: inline-block;
        width: 18px;
        margin-right: 5px;
        color: #7e5aef;
        text-align: center;
    }

    #salesVisitForm .required-asterisk {
        color: #dc3545;
        font-weight: 700;
    }

    #salesVisitForm .form-control.is-invalid {
        border-color: #dc3545 !important;
    }

    #salesVisitForm select.is-invalid + .select2-container .select2-selection,
    #salesVisitForm .select2-selection.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12) !important;
    }

    #salesVisitForm .validation-message {
        display: block;
        min-height: 18px;
        margin-top: 4px;
    }

    #openSalesReserveTableModal { height: 46px; }
    #salesReserveTableModal .modal-dialog { max-width: min(92vw, 1500px); }
    #salesReserveTableModal .reservation-status-options { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    #salesReserveTableModal .reservation-status-options input[type="radio"] {
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
    #salesReserveTableModal .reservation-status-options input[type="radio"]:checked {
        background-color: #6b4ce6;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.4' d='M4 8.2 6.7 11 12 5.5'/%3E%3C/svg%3E");
        background-position: center;
        background-repeat: no-repeat;
        background-size: 12px 12px;
        border-color: #6b4ce6;
    }
    #salesReserveTableModal .reservation-status-options input[type="radio"]:focus-visible {
        box-shadow: 0 0 0 3px rgba(107, 76, 230, 0.2);
        outline: 0;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">

        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Edit Sales Visit</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>Sales Visit</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Edit Visit</li>
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
                            <h4 class="box-title">Sales Visit Details</h4>
                        </div>

                        <div class="box-body">
                            <form id="salesVisitForm" novalidate>

                                <input type="hidden" name="visit_id" value="<?= encrypt_id($sales_visit->visit_id) ?>">

                                <div class="row g-3">

                                    <!-- Hotel -->
                                    <div class="col-md-4">
                                        <label for="property"><span class="field-label-icon fa fa-building" aria-hidden="true"></span>Hotel (Property) <span class="required-asterisk">*</span></label>
                                        <select name="property" id="property" class="form-control" required>
                                            <option value="">Select Hotel</option>
                                            <?php foreach ($hotel_admin as $each) { ?>
                                                <option value="<?= encrypt_id($each->hotel_id) ?>"
                                                    data-raw-id="<?= (int) $each->hotel_id ?>"
                                                    <?= ($sales_visit->property == $each->hotel_id) ? 'selected' : '' ?>>
                                                    <?= html_escape($each->hotel_name) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span id="property_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Department -->
                                    <div class="col-md-4">
                                        <label for="type"><span class="field-label-icon fa fa-sitemap" aria-hidden="true"></span>Department (Type) <span class="required-asterisk">*</span></label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">Select Department</option>
                                            <?php foreach ($departments as $each) { ?>
                                                <option value="<?= encrypt_id($each->department_id) ?>"
                                                    data-raw-id="<?= (int) $each->department_id ?>"
                                                    data-name="<?= html_escape($each->department_name) ?>"
                                                    <?= ($sales_visit->type == $each->department_id) ? 'selected' : '' ?>>
                                                    <?= html_escape($each->department_name) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span id="type_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Lead Type -->
                                    <div class="col-md-4">
                                        <label for="lead_type"><span class="field-label-icon fa fa-fire" aria-hidden="true"></span>Lead Type</label>
                                        <select name="lead_type" id="lead_type" class="form-control">
                                            <option value="Hot" <?= ($sales_visit->lead_type == 'Hot') ? 'selected' : '' ?>>Hot</option>
                                            <option value="Warm" <?= ($sales_visit->lead_type == 'Warm') ? 'selected' : '' ?>>Warm</option>
                                            <option value="Cold" <?= ($sales_visit->lead_type == 'Cold') ? 'selected' : '' ?>>Cold</option>
                                        </select>
                                    </div>

                                    <!-- Visit Date -->
                                    <div class="col-md-4">
                                        <label for="report_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Visit Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="report_date" name="report_date"
                                            value="<?= date('Y-m-d', strtotime($sales_visit->report_date)) ?>"
                                            class="form-control" required>
                                        <span id="report_date_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="follow_up_1_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Follow Up 1 Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="follow_up_1_date" name="follow_up_1_date"
                                            value="<?= !empty($sales_visit->follow_up_1_date) ? html_escape(date('Y-m-d', strtotime($sales_visit->follow_up_1_date))) : '' ?>"
                                            class="form-control" required>
                                        <span id="follow_up_1_date_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="follow_up_2_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Follow Up 2 Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="follow_up_2_date" name="follow_up_2_date"
                                            value="<?= !empty($sales_visit->follow_up_2_date) ? html_escape(date('Y-m-d', strtotime($sales_visit->follow_up_2_date))) : '' ?>"
                                            class="form-control" required>
                                        <span id="follow_up_2_date_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="visit_type"><span class="field-label-icon fa fa-handshake-o" aria-hidden="true"></span>Visit Type <span class="required-asterisk">*</span></label>
                                        <select name="visit_type" id="visit_type" class="form-control" required>
                                            <?php $selectedVisitType = $sales_visit->visit_type ?: 'Relationship Visit'; ?>
                                            <option value="Relationship Visit" <?= $selectedVisitType === 'Relationship Visit' ? 'selected' : '' ?>>Relationship Visit</option>
                                            <option value="Follow-up Visit" <?= $selectedVisitType === 'Follow-up Visit' ? 'selected' : '' ?>>Follow-up Visit</option>
                                            <option value="Support &amp; Service" <?= $selectedVisitType === 'Support & Service' ? 'selected' : '' ?>>Support &amp; Service</option>
                                        </select>
                                        <span id="visit_type_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="visit_mode"><span class="field-label-icon fa fa-map-marker" aria-hidden="true"></span>Visit Mode <span class="required-asterisk">*</span></label>
                                        <select name="visit_mode" id="visit_mode" class="form-control" required>
                                            <?php $selectedVisitMode = $sales_visit->visit_mode ?: 'Physical Visit'; ?>
                                            <option value="Physical Visit" <?= $selectedVisitMode === 'Physical Visit' ? 'selected' : '' ?>>Physical Visit</option>
                                            <option value="Online Meeting" <?= $selectedVisitMode === 'Online Meeting' ? 'selected' : '' ?>>Online Meeting</option>
                                            <option value="Phone Call" <?= $selectedVisitMode === 'Phone Call' ? 'selected' : '' ?>>Phone Call</option>
                                            <option value="Teams Meeting" <?= $selectedVisitMode === 'Teams Meeting' ? 'selected' : '' ?>>Teams Meeting</option>
                                            <option value="Google Meet" <?= $selectedVisitMode === 'Google Meet' ? 'selected' : '' ?>>Google Meet</option>
                                        </select>
                                        <span id="visit_mode_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Company -->
                                    <div class="col-md-4">
                                        <label for="company_id"><span class="field-label-icon fa fa-building-o" aria-hidden="true"></span>Company <span class="required-asterisk">*</span></label>
                                        <select name="company_id" id="company_id" class="form-control" required>
                                            <option value="">Select Company</option>
                                            <?php foreach ($companies as $c) { ?>
                                                <option value="<?= encrypt_id($c->company_id) ?>"
                                                    <?= ($sales_visit->company_id == $c->company_id) ? 'selected' : '' ?>>
                                                    <?= html_escape($c->company_name) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span id="company_id_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Person Met -->
                                    <div class="col-md-4">
                                        <label for="person_met"><span class="field-label-icon fa fa-user" aria-hidden="true"></span>Person Met <span class="required-asterisk">*</span></label>
                                        <select name="person_met" id="person_met" class="form-control" required>
                                            <option value="<?= encrypt_id($sales_visit->person_met) ?>" selected>
                                                Loading...
                                            </option>
                                        </select>
                                        <span id="person_met_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Stage -->
                                    <div class="col-md-4">
                                        <label for="disposition"><span class="field-label-icon fa fa-list" aria-hidden="true"></span>Stage</label>
                                        <select name="disposition" id="disposition" class="form-control">
                                            <?php
                                            $dispositions = [
                                                'Not Contacted',
                                                'Contacted',
                                                'Quotation Sent',
                                                'Negotiations',
                                                'Contract Done',
                                                'Advance Received',
                                                'Lead Won',
                                                'Lead Lost'
                                            ];
                                            if (!empty($sales_visit->disposition) && !in_array($sales_visit->disposition, $dispositions, true)) {
                                                array_unshift($dispositions, $sales_visit->disposition);
                                            }
                                            foreach ($dispositions as $d) { ?>
                                                <option value="<?= html_escape($d) ?>"
                                                    <?= ($sales_visit->disposition == $d) ? 'selected' : '' ?>>
                                                    <?= html_escape($d) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Lead Status -->
                                    <div class="col-md-4">
                                        <label for="lead_status"><span class="field-label-icon fa fa-info-circle" aria-hidden="true"></span>Lead Status</label>
                                        <select name="lead_status" id="lead_status" class="form-control" disabled>
                                            <option value="Open" <?= ($sales_visit->lead_status == 'Open') ? 'selected' : '' ?>>Open</option>
                                            <option value="In Progress" <?= ($sales_visit->lead_status == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                            <option value="Closed" <?= ($sales_visit->lead_status == 'Closed') ? 'selected' : '' ?>>Closed</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="leadDepartment" id="leadDepartment"
                                        value="<?= strtolower($sales_visit->lead_department) ?>">

                                    <div class="col-12">
                                        <div id="dynamicFields" class="row g-3"></div>
                                    </div>

                                    <!-- Agenda -->
                                    <div class="col-sm-4">
                                        <label for="agenda"><span class="field-label-icon fa fa-list-alt" aria-hidden="true"></span>Agenda</label>
                                        <textarea name="agenda" id="agenda" class="form-control" rows="2"><?= html_escape($sales_visit->agenda) ?></textarea>
                                    </div>

                                    <!-- Discussion Summary -->
                                    <div class="col-sm-4">
                                        <label for="discussion_summary"><span class="field-label-icon fa fa-comments" aria-hidden="true"></span>Discussion Summary <span class="required-asterisk">*</span></label>
                                        <textarea name="discussion_summary" id="discussion_summary" class="form-control" rows="3" required><?= html_escape($sales_visit->discussion_summary) ?></textarea>
                                        <span id="discussion_summary_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Conclusion -->
                                    <div class="col-sm-4">
                                        <label for="conclusion"><span class="field-label-icon fa fa-check-circle" aria-hidden="true"></span>Conclusion</label>
                                        <textarea name="conclusion" id="conclusion" class="form-control" rows="2"><?= html_escape($sales_visit->conclusion) ?></textarea>
                                    </div>

                                    <hr class="mt-3">

                                    <h5 class="mt-3">Conveyance Details</h5>

                                    <div class="col-sm-4">
                                        <label>Area Covered</label>
                                        <textarea name="area_covered" id="area_covered" class="form-control" rows="2"><?= html_escape($sales_visit->area_covered) ?></textarea>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Travel Mode</label>
                                        <select name="travel_mode" class="form-control" id="travel_mode">
                                            <option value="">Select</option>
                                            <?php foreach ($travel_modes as $mode) { ?>
                                                <option value="<?= encrypt_id($mode->id) ?>"
                                                    <?= ($sales_visit->travel_mode == $mode->id) ? 'selected' : '' ?>>
                                                    <?= html_escape($mode->travel_mode_name) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="kms_run">Kms Run</label>
                                        <input type="number"
                                            id="kms_run"
                                            name="kms_run"
                                            value="<?= html_escape($sales_visit->kms_run) ?>"
                                            min="0" step="0.01"
                                            class="form-control">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="rate_per_km">Rate / Km</label>
                                        <input type="number"
                                            id="rate_per_km"
                                            name="rate_per_km"
                                            value="<?= html_escape($sales_visit->rate_per_km) ?>"
                                            min="0" step="0.01"
                                            class="form-control">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="parking_charges">Parking / Toll</label>
                                        <input type="number"
                                            id="parking_charges"
                                            name="parking_charges"
                                            value="<?= html_escape($sales_visit->parking_charges) ?>"
                                            min="0" step="0.01"
                                            class="form-control">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="lunch">Lunch</label>
                                        <input type="number"
                                            id="lunch"
                                            name="lunch"
                                            value="<?= html_escape($sales_visit->lunch) ?>"
                                            min="0" step="0.01"
                                            class="form-control">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="entertainment">Entertainment</label>
                                        <input type="number"
                                            id="entertainment"
                                            name="entertainment"
                                            value="<?= html_escape($sales_visit->entertainment) ?>"
                                            min="0" step="0.01"
                                            class="form-control">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="total_amount">Total Amount</label>
                                        <input type="number"
                                            id="total_amount"
                                            name="total_amount"
                                            value="<?= html_escape($sales_visit->total_amount) ?>"
                                            step="0.01"
                                            class="form-control"
                                            readonly>
                                    </div>

                                    <hr class="mt-3">

                                    <h5 class="mt-3">Visit Attachment &amp; Location</h5>

                                    <div class="col-md-6">
                                        <label class="form-label">Attachment Image</label>
                                        <button type="button" id="openVisitCamera" class="btn btn-outline-primary">
                                            <i class="fa fa-camera me-1" aria-hidden="true"></i>
                                            <?= !empty($sales_visit->attachment_image) ? 'Retake Photo' : 'Open Camera' ?>
                                        </button>
                                        <small class="text-muted d-block mt-2">Capture a replacement attachment using the live camera.</small>
                                        <div id="visitAttachmentName" class="small mt-1">
                                            <?= !empty($sales_visit->attachment_image) ? html_escape(basename($sales_visit->attachment_image)) : '' ?>
                                        </div>
                                        <img id="visitAttachmentPreview"
                                            class="img-thumbnail mt-2 <?= empty($sales_visit->attachment_image) ? 'd-none' : '' ?>"
                                            src="<?= !empty($sales_visit->attachment_image) ? html_escape(base_url($sales_visit->attachment_image)) : '' ?>"
                                            alt="Sales visit attachment"
                                            style="max-height: 180px; max-width: 100%;">
                                        <div id="visitCameraStatus" class="small mt-2 text-muted"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="visit_location_details">Current Visit Location</label>
                                        <div class="input-group">
                                            <input type="text" id="visit_location_details" name="visit_location_details"
                                                class="form-control" placeholder="Location not captured"
                                                value="<?= html_escape($sales_visit->location_details ?? '') ?>" readonly>
                                            <button type="button" id="captureVisitLocation" class="btn btn-outline-primary">
                                                <i class="fa fa-map-marker me-1" aria-hidden="true"></i>
                                                <?= isset($sales_visit->latitude, $sales_visit->longitude) ? 'Capture Again' : 'Capture Location' ?>
                                            </button>
                                        </div>
                                        <input type="hidden" id="visit_latitude" name="visit_latitude"
                                            value="<?= isset($sales_visit->latitude) ? html_escape($sales_visit->latitude) : '' ?>">
                                        <input type="hidden" id="visit_longitude" name="visit_longitude"
                                            value="<?= isset($sales_visit->longitude) ? html_escape($sales_visit->longitude) : '' ?>">
                                        <div id="visitLocationStatus" class="small mt-2 text-muted">
                                            <?= isset($sales_visit->latitude, $sales_visit->longitude)
                                                ? 'Saved visit location. Capture again to replace it.'
                                                : 'Capture the current location during the sales visit.' ?>
                                        </div>
                                        <a id="visitGoogleMapsLink"
                                            class="small <?= isset($sales_visit->latitude, $sales_visit->longitude) ? '' : 'd-none' ?>"
                                            href="<?= isset($sales_visit->latitude, $sales_visit->longitude)
                                                ? 'https://www.openstreetmap.org/?mlat=' . rawurlencode($sales_visit->latitude) .
                                                    '&mlon=' . rawurlencode($sales_visit->longitude) . '#map=17/' .
                                                    rawurlencode($sales_visit->latitude) . '/' . rawurlencode($sales_visit->longitude)
                                                : '#' ?>"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa fa-map me-1" aria-hidden="true"></i> View on OpenStreetMap
                                        </a>
                                        <div id="visitLocationMap" class="border rounded mt-2 overflow-hidden" style="height: 340px; width: 100%;">
                                            <div class="h-100 d-flex align-items-center justify-content-center text-muted text-center px-3">
                                                Loading development map...
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2"><?= html_escape($sales_visit->remarks) ?></textarea>
                                    </div>

                                </div>

                                <div class="col-md-12 text-end mt-3">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back();">
                                        Back
                                    </button>

                                    <button type="submit" id="submitBtn"
                                        class="btn btn-primary px-4">
                                        Update
                                    </button>
                                </div>


                            </form>

                            <div id="response" class="mt-3"></div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="salesReserveTableModal" tabindex="-1" aria-labelledby="salesReserveTableModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex"><span class="reserve-modal-icon"><i class="fa fa-cutlery"></i></span><div><h5 class="modal-title" id="salesReserveTableModalLabel">Reserve Table</h5><p class="reserve-modal-subtitle">Select an available table for this reservation</p></div></div>
                <button type="button" class="btn-close" id="closeSalesReserveTableModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row reservation-filters">
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-cutlery"></i>1. Restaurant</label><select class="reserve-control" id="sales_reserve_restaurant_id"><option value="">Select Restaurant</option></select><div class="text-danger small mt-1" id="sales_reserve_restaurant_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-calendar"></i>2. Booking Date</label><input class="reserve-control" id="sales_reserve_booking_date" type="date"><div class="text-danger small mt-1" id="sales_reserve_booking_date_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>3. Slot Type</label><select class="reserve-control" id="sales_reserve_slot_type_id"><option value="">Select Slot Type</option></select><div class="text-danger small mt-1" id="sales_reserve_slot_type_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>4. Time Slot</label><select class="reserve-control" id="sales_reserve_time_slot_id" disabled><option value="">Select Slot Type first</option></select><div class="text-danger small mt-1" id="sales_reserve_time_slot_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-th-large"></i>5. Table Category</label><select class="reserve-control" id="sales_reserve_table_category_id"><option value="">Select Table Category</option></select><div class="text-danger small mt-1" id="sales_reserve_table_category_error"></div></div>
                </div>
                <div class="reservation-summary">
                    <div class="reservation-stat"><i class="fa fa-check-circle"></i><div><strong id="sales_reserve_available_count">0</strong><span>Available</span></div></div>
                    <div class="reservation-stat stat-occupied"><i class="fa fa-times-circle"></i><div><strong id="sales_reserve_occupied_count">0</strong><span>Occupied</span></div></div>
                    <div class="reservation-stat stat-reserved"><i class="fa fa-calendar-check-o"></i><div><strong id="sales_reserve_reserved_count">0</strong><span>Reserved</span></div></div>
                    <div class="reservation-stat stat-blocked"><i class="fa fa-ban"></i><div><strong id="sales_reserve_blocked_count">0</strong><span>Blocked</span></div></div>
                    <div class="reservation-stat stat-checkout"><i class="fa fa-clock-o"></i><div><strong id="sales_reserve_checkout_count">0</strong><span>Expected Check-outs</span></div></div>
                </div>
                <div class="reservation-panel"><div class="reservation-table-grid" id="sales_reserve_table_grid"><div class="text-muted">Select restaurant, booking date, time slot and table category to view tables.</div></div><div class="text-danger small mt-2" id="sales_reserve_table_error"></div></div>
                <div class="row g-3 mt-1">
                    <div class="col-lg-7"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-file-text-o"></i>Special Instructions <span class="text-muted fw-normal">(Optional)</span></label><textarea class="reserve-control reserve-instructions" id="sales_reserve_special_request" maxlength="250" placeholder="Add any special request or notes for this reservation..."></textarea></div></div>
                    <div class="col-lg-5"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-check-circle"></i>Reservation Status</label><div class="reservation-status-options"><label><input type="radio" name="sales_reserve_status" value="Reserved"> Reserved</label><label><input type="radio" name="sales_reserve_status" value="Seated"> Seated</label><label><input type="radio" name="sales_reserve_status" value="Completed"> Completed</label><label><input type="radio" name="sales_reserve_status" value="Cancelled"> Cancelled</label></div><div class="text-danger small mt-1" id="sales_reserve_status_error"></div></div></div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between"><button type="button" class="btn btn-light border" id="cancelSalesReserveTableModal">Cancel</button><button type="button" class="btn btn-primary reserve-submit-btn" id="confirmSalesTableReservation"><i class="fa fa-calendar-check-o me-2"></i>Reserve Table</button></div>
        </div>
    </div>
</div>

<!-- ================= SALES VISIT CAMERA MODAL ================= -->
<style>
    #visitCameraModal .visit-camera-dialog {
        max-width: 440px;
    }

    #visitCameraModal #visitCameraVideo {
        display: block;
        width: 100%;
        height: clamp(360px, 60vh, 560px);
        object-fit: cover;
        background: #111;
    }

    @media (max-width: 575.98px) {
        #visitCameraModal .visit-camera-dialog {
            max-width: calc(100% - 24px);
            margin-left: auto;
            margin-right: auto;
        }

        #visitCameraModal #visitCameraVideo {
            height: 55vh;
            min-height: 320px;
        }
    }
</style>
<div class="modal fade" id="visitCameraModal" tabindex="-1" aria-labelledby="visitCameraModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered visit-camera-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visitCameraModalLabel">
                    <i class="fa fa-camera me-2" aria-hidden="true"></i>Capture Attachment
                </h5>
            </div>
            <div class="modal-body">
                <video id="visitCameraVideo" class="rounded" autoplay muted playsinline></video>
                <canvas id="visitCameraCanvas" class="d-none"></canvas>
                <div id="visitCameraModalStatus" class="small mt-2 text-muted">Opening camera...</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelVisitCamera" class="btn btn-secondary">Cancel</button>
                <button type="button" id="captureVisitPhoto" class="btn btn-primary" disabled>
                    <i class="fa fa-camera me-1" aria-hidden="true"></i> Capture &amp; Add
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIINfQ3ynhtKqsOSAKtMZIF3EVJp6y69COQ=" crossorigin="">
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    toastr.options = {
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
</script>

<script>
    let salesVisitMap = null;
    let salesVisitMarker = null;

    function salesVisitMarkerIcon() {
        return L.divIcon({
            className: '',
            html: '<span aria-hidden="true" style="display:block;width:28px;height:28px;background:#8f72dc;border:3px solid #fff;border-radius:50% 50% 50% 0;box-shadow:0 2px 6px rgba(0,0,0,.35);transform:rotate(-45deg);"></span>',
            iconSize: [34, 42],
            iconAnchor: [17, 38]
        });
    }

    function showSalesVisitMapLocation(latitude, longitude) {
        if (!salesVisitMap) {
            return;
        }

        const coordinates = [Number(latitude), Number(longitude)];
        salesVisitMap.setView(coordinates, 17);

        if (salesVisitMarker) {
            salesVisitMarker.setLatLng(coordinates);
        } else {
            salesVisitMarker = L.marker(coordinates, {
                title: 'Current sales visit location',
                icon: salesVisitMarkerIcon()
            }).addTo(salesVisitMap);
        }
    }

    window.initSalesVisitMap = function() {
        const mapElement = document.getElementById('visitLocationMap');
        if (!mapElement || !window.L) {
            return;
        }

        const savedLatitude = <?= isset($sales_visit->latitude) ? json_encode((float) $sales_visit->latitude) : 'null' ?>;
        const savedLongitude = <?= isset($sales_visit->longitude) ? json_encode((float) $sales_visit->longitude) : 'null' ?>;
        const hasSavedLocation = savedLatitude !== null && savedLongitude !== null;

        mapElement.innerHTML = '';
        salesVisitMap = L.map(mapElement).setView(
            hasSavedLocation ? [savedLatitude, savedLongitude] : [22.9734, 78.6569],
            hasSavedLocation ? 17 : 5
        );
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(salesVisitMap);

        if (hasSavedLocation) {
            showSalesVisitMapLocation(savedLatitude, savedLongitude);
        }
    };

    window.initSalesVisitMap();

    $(document).ready(function() {
        let attachmentPreviewUrl = '';
        let visitCameraStream = null;
        let visitLocationWatchId = null;
        let visitLocationTimer = null;
        window.salesVisitCapturedImage = null;

        function displayVisitAttachment(file) {
            if (attachmentPreviewUrl) {
                URL.revokeObjectURL(attachmentPreviewUrl);
            }

            attachmentPreviewUrl = URL.createObjectURL(file);
            $('#visitAttachmentName').text(file.name);
            $('#visitAttachmentPreview').attr('src', attachmentPreviewUrl).removeClass('d-none');
        }

        function stopVisitCamera() {
            if (visitCameraStream) {
                visitCameraStream.getTracks().forEach(function(track) {
                    track.stop();
                });
                visitCameraStream = null;
            }

            const video = document.getElementById('visitCameraVideo');
            if (video) {
                video.srcObject = null;
            }
            $('#captureVisitPhoto').prop('disabled', true);
        }

        $('#openVisitCamera').on('click', async function() {
            const $status = $('#visitCameraStatus');
            const $modalStatus = $('#visitCameraModalStatus');

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                $('#visitCameraModal').modal('show');
                $modalStatus.removeClass('text-muted text-success').addClass('text-danger')
                    .text('Live camera is not supported by this browser or device.');
                return;
            }

            stopVisitCamera();
            $status.removeClass('text-danger text-success').addClass('text-muted').text('Opening camera...');
            $modalStatus.removeClass('text-danger text-success').addClass('text-muted').text('Opening camera...');
            $('#visitCameraModal').modal('show');

            try {
                visitCameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: 'environment'
                        }
                    },
                    audio: false
                });

                const video = document.getElementById('visitCameraVideo');
                video.srcObject = visitCameraStream;
                await video.play();
                $('#captureVisitPhoto').prop('disabled', false);
                $status.text('');
                $modalStatus.removeClass('text-muted text-danger').addClass('text-success')
                    .text('Camera is ready. Position the attachment and tap Capture & Add.');
            } catch (error) {
                stopVisitCamera();
                const permissionDenied = error && (error.name === 'NotAllowedError' || error.name === 'SecurityError');
                const message = permissionDenied
                    ? 'Camera permission was denied. Please allow camera access and try again.'
                    : 'Unable to open the camera on this device.';
                $status.removeClass('text-muted text-success').addClass('text-danger').text(message);
                $modalStatus.removeClass('text-muted text-success').addClass('text-danger').text(message);
            }
        });

        $('#captureVisitPhoto').on('click', function() {
            const video = document.getElementById('visitCameraVideo');
            const canvas = document.getElementById('visitCameraCanvas');

            if (!video.videoWidth || !video.videoHeight) {
                $('#visitCameraModalStatus').removeClass('text-muted text-success').addClass('text-danger')
                    .text('Camera image is not ready yet.');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(function(blob) {
                if (!blob) {
                    $('#visitCameraModalStatus').removeClass('text-muted text-success').addClass('text-danger')
                        .text('Unable to capture the photo.');
                    return;
                }

                window.salesVisitCapturedImage = new File(
                    [blob],
                    'sales_visit_' + Date.now() + '.jpg',
                    { type: 'image/jpeg' }
                );
                displayVisitAttachment(window.salesVisitCapturedImage);
                stopVisitCamera();
                $('#visitCameraModal').modal('hide');
                $('#visitCameraStatus').removeClass('text-muted text-danger').addClass('text-success')
                    .text('Replacement photo captured successfully.');
                $('#openVisitCamera').html('<i class="fa fa-camera me-1" aria-hidden="true"></i> Retake Photo');
            }, 'image/jpeg', 0.9);
        });

        $('#visitCameraModal').on('hidden.bs.modal', function() {
            stopVisitCamera();
        });

        $('#cancelVisitCamera').on('click', function() {
            stopVisitCamera();
            $('#visitCameraModal').modal('hide');
        });

        function stopVisitLocationWatch() {
            if (visitLocationWatchId !== null) {
                navigator.geolocation.clearWatch(visitLocationWatchId);
                visitLocationWatchId = null;
            }
            if (visitLocationTimer) {
                window.clearTimeout(visitLocationTimer);
                visitLocationTimer = null;
            }
        }

        function displayVisitLocation(position, isFinal) {
            const latitude = position.coords.latitude.toFixed(8);
            const longitude = position.coords.longitude.toFixed(8);
            const accuracy = Math.round(position.coords.accuracy || 0);

            $('#visit_latitude').val(latitude);
            $('#visit_longitude').val(longitude);
            $('#visitGoogleMapsLink')
                .attr('href', 'https://www.openstreetmap.org/?mlat=' + encodeURIComponent(latitude) +
                    '&mlon=' + encodeURIComponent(longitude) + '#map=17/' +
                    encodeURIComponent(latitude) + '/' + encodeURIComponent(longitude))
                .removeClass('d-none');

            showSalesVisitMapLocation(latitude, longitude);

            if (isFinal) {
                $('#visit_location_details').val('Finding full address...');

                const finishAddressLookup = function(address, lookupSucceeded) {
                    $('#visit_location_details').val(address);
                    $('#visitLocationStatus')
                        .removeClass('text-muted text-danger text-success')
                        .addClass(lookupSucceeded ? 'text-success' : 'text-danger')
                        .text(
                            lookupSucceeded
                                ? 'Current address captured (accuracy ±' + accuracy + ' metres).'
                                : 'Coordinates captured, but the full address could not be found.'
                        );
                    $('#captureVisitLocation').prop('disabled', false)
                        .html('<i class="fa fa-map-marker me-1" aria-hidden="true"></i> Capture Again');
                };

                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
                    encodeURIComponent(latitude) + '&lon=' + encodeURIComponent(longitude), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Address lookup failed');
                        }
                        return response.json();
                    })
                    .then(function(result) {
                        if (result && result.display_name) {
                            finishAddressLookup(result.display_name, true);
                        } else {
                            finishAddressLookup('Latitude: ' + latitude + ', Longitude: ' + longitude, false);
                        }
                    })
                    .catch(function() {
                        finishAddressLookup('Latitude: ' + latitude + ', Longitude: ' + longitude, false);
                    });
            } else {
                $('#visit_location_details').val('Locating address...');
                $('#visitLocationStatus').removeClass('text-danger text-success').addClass('text-muted')
                    .text('Improving location accuracy... Current accuracy ±' + accuracy + ' metres.');
            }
        }

        $('#captureVisitLocation').on('click', function() {
            const $button = $(this);
            const $status = $('#visitLocationStatus');

            if (!navigator.geolocation) {
                $status.removeClass('text-muted text-success').addClass('text-danger')
                    .text('Location capture is not supported by this browser.');
                return;
            }

            stopVisitLocationWatch();
            let bestPosition = null;

            $button.prop('disabled', true).text('Locating...');
            $status.removeClass('text-danger text-success').addClass('text-muted')
                .text('Waiting for a high-accuracy device location...');

            visitLocationWatchId = navigator.geolocation.watchPosition(function(position) {
                if (!bestPosition || position.coords.accuracy < bestPosition.coords.accuracy) {
                    bestPosition = position;
                    displayVisitLocation(bestPosition, false);
                }

                if (position.coords.accuracy <= 25) {
                    stopVisitLocationWatch();
                    displayVisitLocation(bestPosition, true);
                }
            }, function(error) {
                stopVisitLocationWatch();

                if (bestPosition) {
                    displayVisitLocation(bestPosition, true);
                    return;
                }

                let message = 'Unable to capture the current location.';
                if (error.code === error.PERMISSION_DENIED) {
                    message = 'Location permission was denied.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message = 'The current location is unavailable.';
                } else if (error.code === error.TIMEOUT) {
                    message = 'Location capture timed out. Please try again.';
                }

                $status.removeClass('text-muted text-success').addClass('text-danger').text(message);
                $button.prop('disabled', false)
                    .html('<i class="fa fa-map-marker me-1" aria-hidden="true"></i> Capture Location');
            }, {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0
            });

            visitLocationTimer = window.setTimeout(function() {
                stopVisitLocationWatch();
                if (bestPosition) {
                    displayVisitLocation(bestPosition, true);
                } else {
                    $status.removeClass('text-muted text-success').addClass('text-danger')
                        .text('No location reading was received. Please check device location settings and try again.');
                    $button.prop('disabled', false)
                        .html('<i class="fa fa-map-marker me-1" aria-hidden="true"></i> Capture Location');
                }
            }, 10000);
        });

        $(window).on('beforeunload', function() {
            stopVisitCamera();
            stopVisitLocationWatch();
        });
    });
</script>

<script>
    function appendCsrf(formData) {
        if (window.CSRF) {
            formData.append(window.CSRF.name, window.CSRF.hash);
        }
    }

    function csrfData(data) {
        if (window.CSRF) {
            data[window.CSRF.name] = window.CSRF.hash;
        }
        return data;
    }

    function refreshCsrf(response) {
        if (response && response.csrfHash && window.CSRF) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function initializeSalesVisitSelects(scope) {
        $(scope).find('select').not('[multiple], .select2-hidden-accessible').each(function() {
            var noSearch = ['lead_type', 'disposition', 'lead_status', 'status', 'visit_type', 'visit_mode'].indexOf(this.id) !== -1;
            $(this).select2({
                dropdownParent: $('#salesVisitForm'),
                minimumResultsForSearch: noSearch ? Infinity : 0,
                width: '100%'
            });
        });
    }

    $(document).ready(function() {
        initializeSalesVisitSelects(document);

        var dynamicFields = document.getElementById('dynamicFields');
        if (dynamicFields) {
            new MutationObserver(function() {
                initializeSalesVisitSelects(dynamicFields);
            }).observe(dynamicFields, { childList: true, subtree: true });
        }

        // Pass full object safely to JS
        const salesVisit = <?= json_encode($sales_visit, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        salesVisit.restaurant_id = <?= json_encode(!empty($sales_visit->restaurant_id) ? (string) $sales_visit->restaurant_id : '') ?>;
        salesVisit.slot_type_id = <?= json_encode(!empty($sales_visit->slot_type_id) ? (string) $sales_visit->slot_type_id : '') ?>;

        refreshEditSalesDynamicFields(salesVisit);
    });



    $(document).ready(function() {




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
            let phoneRegex = /^[0-9]{10}$/;
            if (value === "") {
                $('#phone_number_error').html('Please Enter Phone Number');
            } else if (!phoneRegex.test(value)) {
                $('#phone_number_error').html('Invalid Phone Number (10 digits)');
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
        // if (!this.value) {
        // $('#created_date_error').html('Please Select Created Date');
        // } else {
        // $('#created_date_error').html('');
        // }
        // });

        // Submit Form via AJAX

    });


    let editSalesDynamicRefreshTimer = null;

    function scheduleEditSalesDynamicRefresh() {
        window.clearTimeout(editSalesDynamicRefreshTimer);
        editSalesDynamicRefreshTimer = window.setTimeout(function() {
            refreshEditSalesDynamicFields();
        }, 0);
    }

    $(document).ready(function() {
        $('#disposition, #type, #property')
            .off('change.salesVisitDynamicFields')
            .on('change.salesVisitDynamicFields select2:select.salesVisitDynamicFields select2:clear.salesVisitDynamicFields', function() {
                scheduleEditSalesDynamicRefresh();
            });
    });


    function updateDynamicFieldsForEdit(data = "") {
        const disposition = $("#disposition").val();
        let property = $("#property").val();
        const leadStatusByStage = {
            'Not Contacted': 'Open',
            'Contacted': 'In Progress',
            'Quotation Sent': 'In Progress',
            'Negotiations': 'In Progress',
            'Contract Done': 'Closed',
            'Advance Received': 'Closed',
            'Lead Won': 'Closed',
            'Lead Lost': 'Closed'
        };

        let department = $('#type').find(':selected').data('name')?.toLowerCase();

        $('#leadDepartment').val(department);
        $('#lead_status').val(leadStatusByStage[disposition] || 'Open').trigger('change.select2');

        let existingLeadData = data;

        const container = $("#dynamicFields");

        // Reset previous fields
        container.html("");


        if ((disposition === "Information/Enquiry") || (disposition === "Trash") || (disposition === "Denied") || (disposition === "Shopping - No Follow up")) {

            $("#lead_status").val('Closed');

        }



        // Reservation - Closed
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

        // Reservation - Closed
        if (disposition === "Reservation") {

            $("#lead_status").val('Closed');

            if (department === "rooms") {
                container.append(`
<div class="row">
    <!-- Property -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Room Type</label>
        <select name="roomtype" class="form-select filter-input" id="roomtype">
            <?php foreach ($roomtype as $roomtype) { ?>
                <option value="<?= $roomtype->roomtype_code; ?>">
                    <?= $roomtype->roomtype_name; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Check-in -->
    <div class="col-md-3 mb-3">
        <label>Check-in Date</label>
        <input type="date" id="checkin_date" name="checkin_date" class="form-control">
        <span class="error-text text-danger"></span>
    </div>

    <div class="col-md-3 mb-3">
        <label>Check-out Date</label>
        <input type="date" id="checkout_date" name="checkout_date" class="form-control">
        <span class="error-text text-danger"></span>
    </div>

    <div class="col-md-2 mb-3">
        <button type="button" class="btn btn-primary btn-sm" style="margin-top:21px" id="checkAvailabilityBtn">
            Check Availability
        </button>
    </div>


    <div class="col-md-3 mb-3">
        <label>Check-in Time</label>
        <input type="time" id="checkin_time" name="checkin_time" class="form-control">
        <span class="error-text text-danger"></span>
    </div>



    <div class="col-md-3 mb-3">
        <label>Check-out Time</label>
        <input type="time" id="checkout_time" name="checkout_time" class="form-control">
        <span class="error-text text-danger"></span>
    </div>






    <!-- Number of Rooms -->
    <div class="col-md-3 mb-3">
        <label>Number of Rooms</label>
        <input type="number" name="number_of_rooms" class="form-control" min="1">
    </div>

    <!-- No. of Pax -->
    <div class="col-md-3 mb-3">
        <label>No. of Pax</label>
        <input type="number" name="pax" class="form-control" min="1">
    </div>

    <!-- Adults -->
    <div class="col-md-4 mb-3">
        <label>Adults</label>
        <input type="number" name="adults" class="form-control" min="1">
    </div>

    <!-- Kids -->
    <div class="col-md-4 mb-3">
        <label>Kids</label>
        <input type="number" name="kids" class="form-control" min="0">
    </div>



    <table id="rateTypeTable" class="table table-bordered mt-4" style="display:none;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Rate Type</th>
                <th>Room Price</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>


</div>





`);
            } else if (department === "restaurants") {

                container.append(`
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Booking Date</label>
        <input type="date" name="booking_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>Arrival Time</label>
        <input type="time" name="arrival_time" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>No. of Pax</label>
        <input type="number" name="pax" class="form-control" min="1">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Restaurant <span class="text-danger">*</span></label>
        <select name="restaurant_id" id="restaurant_id" class="form-select">
            <option value="">Select Restaurant</option>
        </select>
        <div class="text-danger error-label" id="restaurant_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Slot Type <span class="text-danger">*</span></label>
        <select name="slot_type_id" id="slot_type_id" class="form-select">
            <option value="">Select Slot</option>
        </select>
        <div class="text-danger error-label" id="slot_type_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Expected Revenue</label>
        <input type="number" name="amount" class="form-control" step="0.01">
    </div>
</div>


`);

                // Load restaurants via AJAX
                loadRestaurants(property, existingLeadData);

                // Load slot types via AJAX
                loadSlotTypes(existingLeadData);
            } else if (department === "banquets") {
                container.append(`

<div class="mb-3">
    <label>Booking Date</label>
    <input type="date" name="booking_date" class="form-control">
</div>
<div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
</div>
<div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
</div>


`);
            }
        }

        // Shopping - Followup - In Progress
        if ((disposition === "Shopping - Follow up")) {

            $("#lead_status").val('In Progress');

            container.append(`
<div class="mb-3">
    <label>Booking Enquiry Date</label>
    <input type="date" name="booking_enquiry_date" class="form-control">
</div>
<div class="mb-3">
    <label>Follow-up Date</label>
    <input type="date" name="followup_date" class="form-control">
</div>
<div class="mb-3">
    <label>2nd Follow-up Date</label>
    <input type="date" name="second_followup_date" class="form-control">
</div>
`);

            if (department === 'banquets') {
                container.append(`
<div class="mb-3">
    <label>Transfer Lead to Manager Level</label>
    <select name="transfer_to_manager" class="form-control">
        <option value="">Select</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
</div>
`);
            }
        }

        /** ✅ Auto-fill existing values */
        if (typeof existingLeadData !== "undefined") {
            for (let key in existingLeadData) {
                const field = container.find(`[name="${key}"]`);
                if (field.length) {
                    field.val(existingLeadData[key]);
                }
            }
        }







    }


    function loadRestaurants(hotel_id, existingLeadData) {

        $('#restaurant_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('superAdmin/Restaurants/getByHotel') ?>",
            type: "POST",
            data: csrfData({
                hotel_id: hotel_id
            }),
            dataType: "json",
            success: function(res) {
                refreshCsrf(res);

                let html = '<option value="">Select Restaurant</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.restaurant_name}</option>`;
                    });
                }

                $('#restaurant_id').html(html);

                if (typeof existingLeadData !== "undefined" && existingLeadData.restaurant_id) {
                    $('#restaurant_id').val(existingLeadData.restaurant_id);
                }
            }
        });
    }

    function loadSlotTypes(existingLeadData) {

        $('#slot_type_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('superAdmin/SlotType/getAll') ?>",
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

                $('#slot_type_id').html(html);

                if (typeof existingLeadData !== "undefined" && existingLeadData.slot_type_id) {
                    $('#slot_type_id').val(existingLeadData.slot_type_id);
                }
            }
        });
    }

    let editSalesDynamicGeneration = 0;
    let editSalesDynamicData = {};
    let editSalesDynamicAjaxQueue = $.Deferred().resolve().promise();

    function editSalesDynamicRequest(options) {
        const runRequest = function() {
            if ((options.type || 'GET').toUpperCase() === 'POST') {
                options.data = csrfData(options.data || {});
            }
            return $.ajax(options);
        };

        editSalesDynamicAjaxQueue = editSalesDynamicAjaxQueue.then(runRequest, runRequest);
        editSalesDynamicAjaxQueue.done(refreshCsrf);
        return editSalesDynamicAjaxQueue;
    }

    function normalizeEditSalesDepartment(name) {
        name = String(name || '').trim().toLowerCase();
        if (name === 'restaurants') return 'restaurant';
        if (name === 'banquets') return 'banquet';
        return name;
    }

    function editSalesDynamicValue(data, key) {
        if (!data || typeof data !== 'object' || data[key] === null || typeof data[key] === 'undefined') {
            return '';
        }
        return data[key];
    }

    function editSalesDynamicValues(data, key) {
        const value = editSalesDynamicValue(data, key);
        if (Array.isArray(value)) {
            return value.map(String);
        }
        if (value === '') {
            return [];
        }
        return String(value).split(',').map(function(item) {
            return item.trim();
        }).filter(Boolean);
    }

    function resetEditSalesDynamicFields() {
        $('#dynamicFields select.select2-hidden-accessible').each(function() {
            $(this).select2('destroy');
        });
        $('#dynamicFields').empty();
    }

    function applyEditSalesDynamicValues(data) {
        if (!data || typeof data !== 'object') {
            return;
        }

        $.each(data, function(key, value) {
            const $field = $('#dynamicFields').find('[name="' + key + '"]');
            if ($field.length && value !== null && typeof value !== 'undefined') {
                $field.val(value);
            }
        });
    }

    function initializeEditSalesDynamicSelects() {
        initializeSalesVisitSelects($('#dynamicFields'));
        $('#dynamicFields select:not([multiple])').trigger('change.select2');
    }

    function loadEditSalesSelect(options) {
        const requestGeneration = editSalesDynamicGeneration;
        const $select = $(options.selector).html('<option value="">Loading...</option>');

        const requestOptions = {
            url: options.url,
            type: options.type || 'POST',
            dataType: 'json',
            success: function(response) {
                refreshCsrf(response);

                if (requestGeneration !== editSalesDynamicGeneration || !$(options.selector).length) {
                    return;
                }

                let html = '<option value="">' + options.placeholder + '</option>';
                $.each(response.data || [], function(_, row) {
                    html += '<option value="' + row[options.valueKey] + '">' + options.label(row) + '</option>';
                });

                $select.html(html);
                if (options.selected !== null && typeof options.selected !== 'undefined' && options.selected !== '') {
                    $select.val(String(options.selected));
                }
                $select.trigger('change.select2');

                if (typeof options.afterLoad === 'function') {
                    options.afterLoad($select);
                }
            }
        };

        requestOptions.data = options.data || {};
        editSalesDynamicRequest(requestOptions);
    }

    function loadEditSalesBanquets(hotelId, selected) {
        loadEditSalesSelect({
            url: "<?= base_url('lead/get-banquets') ?>",
            type: 'GET',
            data: {hotel_id: hotelId},
            selector: '#banquet_id',
            placeholder: 'Select Banquet',
            valueKey: 'banquet_id',
            label: function(row) { return row.banquet_name; },
            selected: selected
        });
    }

    function loadEditSalesRoomTypes(hotelId, selected) {
        loadEditSalesSelect({
            url: "<?= base_url('lead/get-room-types') ?>",
            type: 'GET',
            data: {hotel_id: hotelId},
            selector: '#roomtype',
            placeholder: 'Select Room Type',
            valueKey: 'roomtype_id',
            label: function(row) { return row.roomtype_name; },
            selected: selected
        });
    }

    function loadEditSalesMealPlans(selected) {
        loadEditSalesSelect({
            url: "<?= base_url('lead/get-meal-plans') ?>",
            type: 'GET',
            data: {},
            selector: '#meal_plan',
            placeholder: 'Select Meal Plan',
            valueKey: 'id',
            label: function(row) { return row.plan; },
            selected: selected
        });
    }

    function loadEditSalesPromotionalOffers(departmentId, selected) {
        loadEditSalesSelect({
            url: "<?= base_url('lead/get-promotional-offers') ?>",
            type: 'GET',
            data: {department_id: departmentId},
            selector: '#promotional_offers',
            placeholder: 'Select Offer',
            valueKey: 'id',
            label: function(row) { return row.offer_name; },
            selected: selected
        });
    }

    function refreshEditSalesDynamicFields(existingData) {
        editSalesDynamicGeneration += 1;
        editSalesDynamicData = existingData && typeof existingData === 'object' ? existingData : {};

        const stage = $('#disposition').val() || '';
        const department = normalizeEditSalesDepartment($('#type option:selected').data('name'));
        const hotelId = $('#property option:selected').data('raw-id') || '';
        const departmentId = $('#type option:selected').data('raw-id') || '';
        const today = new Date().toISOString().split('T')[0];
        const leadStatusByStage = {
            'Not Contacted': 'Open',
            'Contacted': 'In Progress',
            'Quotation Sent': 'In Progress',
            'Negotiations': 'In Progress',
            'Contract Done': 'Closed',
            'Advance Received': 'Closed',
            'Lead Won': 'Closed',
            'Lead Lost': 'Closed'
        };
        const $container = $('#dynamicFields');

        $('#lead_status').val(leadStatusByStage[stage] || 'Open').trigger('change.select2');
        $('#leadDepartment').val(department);
        resetEditSalesDynamicFields();

        if (stage === 'Lead Lost') {
            $container.append(`
                <div class="col-md-3 mb-3">
                    <label>Reason <span class="text-danger">*</span></label>
                    <select name="reason" id="reason" class="form-select">
                        <option value="">Select Reason</option>
                        <option value="Budget Issue">Budget Issue</option>
                        <option value="Date Unavailable">Date Unavailable</option>
                        <option value="No Response">No Response</option>
                        <option value="Chose Competitor">Chose Competitor</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Duplicate Lead">Duplicate Lead</option>
                    </select>
                    <div class="text-danger error-label" id="reason_error"></div>
                </div>`);
        }

        if (stage === 'Lead Won') {
            $container.append(`
                <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01">
                </div>`);
        }

        if (stage === 'Quotation Sent') {
            $container.append(`
                <div class="col-md-3 mb-3">
                    <label>Promotional Offer</label>
                    <select name="promotional_offers" id="promotional_offers" class="form-select">
                        <option value="">Select Offer</option>
                    </select>
                </div>`);

            if (department === 'rooms') {
                $container.append(`
                    <div class="col-md-3 mb-3"><label>Room Type</label><select name="roomtype" id="roomtype" class="form-select"><option value="">Select Room Type</option></select></div>
                    <div class="col-md-3 mb-3"><label>Meal Plan <span class="text-danger">*</span></label><select name="meal_plan" id="meal_plan" class="form-select"><option value="">Select Meal Plan</option></select><div class="text-danger error-label" id="meal_plan_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Check-in Date</label><input type="date" name="checkin_date" id="checkin_date" class="form-control"><span class="error-text text-danger"></span></div>
                    <div class="col-md-3 mb-3"><label>Check-out Date</label><input type="date" name="checkout_date" id="checkout_date" class="form-control"><span class="error-text text-danger"></span></div>
                    <div class="col-md-3 mb-3"><label>Number of Rooms</label><input type="number" name="number_of_rooms" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Adults</label><input type="number" name="adults" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Kids</label><input type="number" name="kids" class="form-control" min="0"></div>
                    <div class="col-md-3 mb-3"><label>Room Revenue</label><input type="number" name="revenue_room" id="revenue_room" class="form-control edit-revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>F&amp;B Revenue</label><input type="number" name="revenue_fnb" id="revenue_fnb" class="form-control edit-revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>Other Revenue</label><input type="number" name="revenue_other" id="revenue_other" class="form-control edit-revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly></div>`);
                loadEditSalesRoomTypes(hotelId, editSalesDynamicValue(editSalesDynamicData, 'roomtype'));
                loadEditSalesMealPlans(editSalesDynamicValue(editSalesDynamicData, 'meal_plan'));
            } else if (department === 'restaurant') {
                $container.append(`
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Arrival Time</label><input type="time" name="arrival_time" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" class="form-control" step="0.01"></div>
                    <div class="col-md-6 mb-3"><label>Special Occasion (if any)</label><input type="text" name="special_occasion" class="form-control"></div>
                    <div class="col-md-4 mb-3"><label>Table Reservation <span class="text-danger">*</span></label><button type="button" class="btn btn-primary w-100" id="openSalesReserveTableModal"><i class="fa fa-calendar-check-o me-2"></i>Edit Reservation</button><div class="small text-success mt-2" id="sales_restaurant_reservation_summary">Existing reservation loaded.</div><div class="text-danger error-label" id="sales_restaurant_reservation_error"></div></div>
                    <input type="hidden" name="booking_date" id="restaurant_booking_date">
                    <input type="hidden" name="restaurant_id" id="restaurant_id">
                    <input type="hidden" name="slot_type_id" id="slot_type_id">
                    <input type="hidden" name="time_slot_id" id="time_slot_id">
                    <input type="hidden" name="table_category_id" id="table_category_id">
                    <select name="table_id[]" id="table_id" multiple hidden></select>
                    <input type="hidden" name="table_reservation_status" id="table_reservation_status">
                    <input type="hidden" name="special_request" id="restaurant_special_request">`);

                const existingTableIds = editSalesDynamicValues(editSalesDynamicData, 'reserved_table_ids').length
                    ? editSalesDynamicValues(editSalesDynamicData, 'reserved_table_ids')
                    : editSalesDynamicValues(editSalesDynamicData, 'table_id');
                existingTableIds.forEach(function(tableId) {
                    $('#table_id').append($('<option>', {value: tableId, text: tableId, selected: true}));
                });
                $('#sales_restaurant_reservation_summary').text(
                    existingTableIds.length ? `${existingTableIds.length} existing table(s) selected. Open the reservation to review.` : 'No table reserved yet.'
                ).toggleClass('text-success', existingTableIds.length > 0).toggleClass('text-muted', existingTableIds.length === 0);
            } else if (department === 'banquet') {
                const roomRequired = Boolean(editSalesDynamicValue(editSalesDynamicData, 'checkin_date') || editSalesDynamicValue(editSalesDynamicData, 'checkout_date'));
                $container.append(`
                    <input type="hidden" name="room_requirement_controlled" value="1">
                    <div class="col-md-3 mb-3 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_room_required" id="sales_is_room_required" value="1" ${roomRequired ? 'checked' : ''}><label class="form-check-label" for="sales_is_room_required">Is Room Required?</label></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="${roomRequired ? '' : 'display:none;'}"><label>Check-in Date <span class="text-danger">*</span></label><input type="date" name="checkin_date" id="checkin_date" class="form-control"><div class="text-danger error-label" id="checkin_date_error"></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="${roomRequired ? '' : 'display:none;'}"><label>Check-out Date <span class="text-danger">*</span></label><input type="date" name="checkout_date" id="checkout_date" class="form-control"><div class="text-danger error-label" id="checkout_date_error"></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="${roomRequired ? '' : 'display:none;'}"><label>Number of Rooms</label><input type="number" name="number_of_rooms" id="number_of_rooms" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Booking Date</label><input type="date" name="booking_date" class="form-control" value="${today}"></div>
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Banquet <span class="text-danger">*</span></label><select name="banquet_id" id="banquet_id" class="form-select"><option value="">Select Banquet</option></select><div class="text-danger error-label" id="banquet_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" class="form-control" step="0.01"></div>`);
                loadEditSalesBanquets(hotelId, editSalesDynamicValue(editSalesDynamicData, 'banquet_id'));
            }

            $container.append(`
                <div class="col-md-4 mt-3 mb-4"><label>Follow-up Date</label><input type="date" name="followup_date" class="form-control"></div>
                <div class="col-md-4 mt-3 mb-4"><label>2nd Follow-up Date</label><input type="date" name="second_followup_date" class="form-control"></div>`);
            loadEditSalesPromotionalOffers(departmentId, editSalesDynamicValue(editSalesDynamicData, 'promotional_offers'));
        }

        if (stage === 'Negotiations' || stage === 'Not Contacted' || stage === 'Advance Received') {
            $container.append(`
                <div class="col-md-3 mb-3"><label>Booking Enquiry Date</label><input type="date" name="booking_date" class="form-control" value="${today}"></div>
                <div class="col-md-3 mb-3"><label>Follow-up Date</label><input type="date" name="followup_date" class="form-control"></div>
                <div class="col-md-3 mb-3"><label>2nd Follow-up Date</label><input type="date" name="second_followup_date" class="form-control"></div>`);
        }

        if ($('#table_id').length) {
            editSalesDynamicData.table_id = editSalesDynamicValues(editSalesDynamicData, 'reserved_table_ids').length
                ? editSalesDynamicValues(editSalesDynamicData, 'reserved_table_ids')
                : editSalesDynamicValues(editSalesDynamicData, 'table_id');
        }
        applyEditSalesDynamicValues(editSalesDynamicData);
        initializeEditSalesDynamicSelects();
        bindEditSalesDynamicDependencies();
    }

    function bindEditSalesDynamicDependencies() {
        $('#dynamicFields input[name="adults"], #dynamicFields input[name="kids"]')
            .off('.editSalesPax')
            .on('input.editSalesPax', function() {
            const adults = parseInt($('#dynamicFields input[name="adults"]').val(), 10) || 0;
            const kids = parseInt($('#dynamicFields input[name="kids"]').val(), 10) || 0;
            $('#dynamicFields input[name="pax"]').val(adults + kids);
        });

        $('#dynamicFields .edit-revenue-field')
            .off('.editSalesRevenue')
            .on('input.editSalesRevenue', function() {
            const total = (parseFloat($('#revenue_room').val()) || 0) +
                (parseFloat($('#revenue_fnb').val()) || 0) +
                (parseFloat($('#revenue_other').val()) || 0);
            $('#amount').val(total.toFixed(2));
        });
    }

    $(document).on('change.editSalesRoomRequired', '#sales_is_room_required', function() {
        const required = this.checked;
        $('.sales-room-required-fields').toggle(required);
        $('.sales-room-required-fields input').prop('required', required);
        if (!required) {
            $('.sales-room-required-fields input').val('').removeClass('is-invalid').removeAttr('aria-invalid');
            $('#checkin_date_error, #checkout_date_error').text('');
        }
    });

    $(document).on('change.editSalesRoomDates', '#checkin_date', function() {
        $('#checkout_date').attr('min', $(this).val() || '');
        if ($('#checkout_date').val() && $('#checkout_date').val() < $(this).val()) {
            $('#checkout_date').val('');
        }
    });

    let salesReservationModalCanClose = false;
    let salesReservationTables = [];
    let salesReservationAvailabilityRequest = 0;

    function salesReservationToday() {
        return new Date().toISOString().split('T')[0];
    }

    function formatSalesReservationTime(time) {
        if (!time) return '';
        const parts = String(time).split(':');
        let hour = parseInt(parts[0], 10);
        if (Number.isNaN(hour)) return time;
        const minutes = parts[1] || '00';
        const suffix = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12;
        return `${String(hour).padStart(2, '0')}:${minutes} ${suffix}`;
    }

    function resetSalesReservationMessages() {
        $('#salesReserveTableModal .is-invalid').removeClass('is-invalid');
        $('#sales_reserve_restaurant_error, #sales_reserve_booking_date_error, #sales_reserve_slot_type_error, #sales_reserve_time_slot_error, #sales_reserve_table_category_error, #sales_reserve_table_error, #sales_reserve_status_error').text('');
    }

    function resetSalesReservationStats() {
        $('#sales_reserve_available_count, #sales_reserve_occupied_count, #sales_reserve_reserved_count, #sales_reserve_blocked_count, #sales_reserve_checkout_count').text('0');
    }

    function salesReservationTableLabels(table) {
        return [table.table_name, table.table_number, table.table_number ? `Table ${table.table_number}` : '']
            .filter(Boolean)
            .map(label => String(label).trim().toLowerCase());
    }

    function selectedSalesReservationTableIds() {
        return $('#sales_reserve_table_grid .reservation-table-card.selected').map(function() {
            return String($(this).data('table-id'));
        }).get();
    }

    function renderSalesReservationTables(conflictingTables = [], selectedTableIds = []) {
        const $grid = $('#sales_reserve_table_grid').empty();
        const conflicts = conflictingTables.map(label => String(label).trim().toLowerCase());
        const selectedIds = selectedTableIds.map(String);
        let availableCount = 0;
        let reservedCount = 0;

        if (!salesReservationTables.length) {
            $grid.append($('<div>', {class: 'text-muted', text: 'No tables are available for the selected restaurant and category.'}));
            resetSalesReservationStats();
            return;
        }

        salesReservationTables.forEach(function(table) {
            const tableId = String(table.id);
            const unavailable = salesReservationTableLabels(table).some(label => conflicts.includes(label));
            const selected = !unavailable && selectedIds.includes(tableId);
            const tableNumber = table.table_number || table.table_name || `T${table.id}`;
            const capacity = table.capacity ? `${table.capacity} Guests` : 'Guests not specified';
            const category = $('#sales_reserve_table_category_id option:selected').text() || 'Table';
            unavailable ? reservedCount++ : availableCount++;

            const $card = $('<div>', {
                class: `reservation-table-card${unavailable ? ' unavailable' : ''}${selected ? ' selected' : ''}`,
                'data-table-id': tableId,
                'data-available': unavailable ? '0' : '1'
            });
            $card.append(
                $('<span>', {class: 'table-icon'}).append($('<i>', {class: 'fa fa-cutlery'})),
                $('<div>', {class: 'table-number', text: tableNumber}),
                $('<div>', {class: 'table-details'}).append(document.createTextNode(capacity), $('<br>'), document.createTextNode(category)),
                $('<span>', {class: `table-status ${unavailable ? 'reserved' : 'available'}`, text: unavailable ? 'Reserved' : 'Available'})
            );
            $grid.append($card);
        });

        $('#sales_reserve_available_count').text(availableCount);
        $('#sales_reserve_reserved_count').text(reservedCount);
        $('#sales_reserve_occupied_count, #sales_reserve_blocked_count, #sales_reserve_checkout_count').text('0');
    }

    function refreshSalesReservationAvailability(selectedTableIds = null) {
        const bookingDate = $('#sales_reserve_booking_date').val();
        const restaurantId = $('#sales_reserve_restaurant_id').val();
        const categoryId = $('#sales_reserve_table_category_id').val();
        const timeSlotId = $('#sales_reserve_time_slot_id').val();
        const slotTypeId = $('#sales_reserve_slot_type_id').val();
        const tableIds = salesReservationTables.map(table => table.id);
        const preserved = selectedTableIds === null ? selectedSalesReservationTableIds() : selectedTableIds.map(String);

        if (!bookingDate || !restaurantId || !categoryId || !timeSlotId || !slotTypeId || !tableIds.length) {
            renderSalesReservationTables([], preserved);
            return;
        }

        const requestId = ++salesReservationAvailabilityRequest;
        $('#sales_reserve_table_grid').addClass('opacity-50');
        editSalesDynamicRequest({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: {booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: tableIds, slot_type_id: slotTypeId, exclude_lead_id: <?= (int) $sales_visit->lead_id_againts_visit ?>},
            dataType: 'json'
        }).done(function(res) {
            refreshCsrf(res);
            if (requestId !== salesReservationAvailabilityRequest) return;
            const selectedSlot = (res.data || []).find(slot => String(slot.id) === String(timeSlotId));
            renderSalesReservationTables(selectedSlot ? (selectedSlot.conflicting_tables || []) : [], preserved);
        }).fail(function(xhr) {
            if (requestId !== salesReservationAvailabilityRequest) return;
            renderSalesReservationTables([], preserved);
            $('#sales_reserve_table_error').text((xhr.responseJSON || {}).message || 'Unable to check table availability.');
        }).always(function() {
            if (requestId === salesReservationAvailabilityRequest) $('#sales_reserve_table_grid').removeClass('opacity-50');
        });
    }

    function loadSalesReservationTables(restaurantId, categoryId, selectedTableIds = []) {
        salesReservationTables = [];
        resetSalesReservationStats();
        $('#sales_reserve_table_grid').html('<div class="text-muted">Loading tables...</div>');
        if (!restaurantId || !categoryId) {
            $('#sales_reserve_table_grid').html('<div class="text-muted">Select a restaurant and table category to view tables.</div>');
            return;
        }
        $.ajax({
            url: "<?= base_url('lead/get-tables') ?>",
            type: 'GET',
            data: {restaurant_id: restaurantId, category_id: categoryId},
            dataType: 'json'
        }).done(function(res) {
            refreshCsrf(res);
            salesReservationTables = res.status === 'success' ? (res.data || []) : [];
            renderSalesReservationTables([], selectedTableIds);
            refreshSalesReservationAvailability(selectedTableIds);
        }).fail(function() {
            salesReservationTables = [];
            renderSalesReservationTables();
            $('#sales_reserve_table_error').text('Unable to load restaurant tables.');
        });
    }

    function loadSalesReservationCategories(restaurantId, selectedCategoryId = '', selectedTableIds = []) {
        const $category = $('#sales_reserve_table_category_id').html('<option value="">Loading...</option>').prop('disabled', true);
        salesReservationTables = [];
        resetSalesReservationStats();
        $('#sales_reserve_table_grid').html('<div class="text-muted">Select a table category to view tables.</div>');
        if (!restaurantId) {
            $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
            return;
        }
        $.ajax({url: "<?= base_url('lead/get-table-categories') ?>", type: 'GET', data: {restaurant_id: restaurantId}, dataType: 'json'})
            .done(function(res) {
                refreshCsrf(res);
                $category.empty().append($('<option>', {value: '', text: 'Select Table Category'}));
                if (res.status === 'success') (res.data || []).forEach(row => $category.append($('<option>', {value: row.id, text: row.category_name})));
                if (selectedCategoryId) $category.val(String(selectedCategoryId));
                else if ($category.find('option[value!=""]').length === 1) $category.val($category.find('option[value!=""]').val());
                $category.prop('disabled', false);
                if ($category.val()) loadSalesReservationTables(restaurantId, $category.val(), selectedTableIds);
            })
            .fail(function() {
                $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
                $('#sales_reserve_table_category_error').text('Unable to load table categories.');
            });
    }

    function loadSalesReservationRestaurants(selectedRestaurantId = '', selectedCategoryId = '', selectedTableIds = []) {
        const hotelId = $('#property option:selected').data('raw-id') || '';
        const $restaurant = $('#sales_reserve_restaurant_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $.ajax({url: "<?= base_url('lead/get-restaurants') ?>", type: 'GET', data: {hotel_id: hotelId}, dataType: 'json'})
            .done(function(res) {
                refreshCsrf(res);
                $restaurant.empty().append($('<option>', {value: '', text: 'Select Restaurant'}));
                if (res.status === 'success') (res.data || []).forEach(row => $restaurant.append($('<option>', {value: row.id, text: row.restaurant_name})));
                if (selectedRestaurantId) $restaurant.val(String(selectedRestaurantId));
                else if ($restaurant.find('option[value!=""]').length === 1) $restaurant.val($restaurant.find('option[value!=""]').val());
                $restaurant.prop('disabled', false);
                loadSalesReservationCategories($restaurant.val(), selectedCategoryId, selectedTableIds);
            })
            .fail(function() {
                $restaurant.html('<option value="">Select Restaurant</option>').prop('disabled', false);
                $('#sales_reserve_restaurant_error').text('Unable to load restaurants.');
            });
    }

    function loadSalesReservationTimeSlots(slotTypeId, selectedTimeSlotId = '') {
        const $timeSlot = $('#sales_reserve_time_slot_id').html(`<option value="">${slotTypeId ? 'Loading...' : 'Select Slot Type first'}</option>`).prop('disabled', true);
        if (!slotTypeId) {
            refreshSalesReservationAvailability();
            return;
        }
        $.ajax({url: "<?= base_url('lead/get-time-slots') ?>", type: 'GET', data: {slot_type_id: slotTypeId}, dataType: 'json'})
            .done(function(res) {
                $timeSlot.empty().append($('<option>', {value: '', text: 'Select Time Slot'}));
                if (res.status === 'success') (res.data || []).forEach(function(slot) {
                    const label = slot.start_time && slot.end_time ? `${formatSalesReservationTime(slot.start_time)} - ${formatSalesReservationTime(slot.end_time)}` : (slot.slot_name || 'Time Slot');
                    $timeSlot.append($('<option>', {value: slot.id, text: label}));
                });
                if (selectedTimeSlotId) $timeSlot.val(String(selectedTimeSlotId));
                $timeSlot.prop('disabled', false);
                refreshSalesReservationAvailability();
            })
            .fail(function() {
                $timeSlot.html('<option value="">Select Time Slot</option>').prop('disabled', false);
                $('#sales_reserve_time_slot_error').text('Unable to load time slots.');
            });
    }

    function loadSalesReservationSlotTypes(selectedSlotTypeId = '', selectedTimeSlotId = '') {
        const $slotType = $('#sales_reserve_slot_type_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#sales_reserve_time_slot_id').html('<option value="">Select Slot Type first</option>').prop('disabled', true);
        $.ajax({url: "<?= base_url('lead/get-slot-types') ?>", type: 'GET', dataType: 'json'})
            .done(function(res) {
                $slotType.empty().append($('<option>', {value: '', text: 'Select Slot Type'}));
                if (res.status === 'success') (res.data || []).forEach(row => $slotType.append($('<option>', {value: row.id, text: row.slot_name || row.name || 'Slot Type'})));
                if (selectedSlotTypeId) $slotType.val(String(selectedSlotTypeId));
                $slotType.prop('disabled', false);
                if ($slotType.val()) loadSalesReservationTimeSlots($slotType.val(), selectedTimeSlotId);
            })
            .fail(function() {
                $slotType.html('<option value="">Select Slot Type</option>').prop('disabled', false);
                $('#sales_reserve_slot_type_error').text('Unable to load slot types.');
            });
    }

    function openSalesReservationModal() {
        resetSalesReservationMessages();
        salesReservationModalCanClose = false;
        const selectedTableIds = ($('#table_id').val() || []).map(String);
        const status = $('#table_reservation_status').val() || 'Reserved';
        const savedBookingDate = $('#restaurant_booking_date').val() || salesReservationToday();
        $('#sales_reserve_booking_date').attr('min', savedBookingDate < salesReservationToday() ? savedBookingDate : salesReservationToday()).val(savedBookingDate);
        $('#sales_reserve_special_request').val($('#restaurant_special_request').val() || '');
        $(`input[name="sales_reserve_status"][value="${status}"]`).prop('checked', true);
        loadSalesReservationRestaurants($('#restaurant_id').val() || '', $('#table_category_id').val() || '', selectedTableIds);
        loadSalesReservationSlotTypes($('#slot_type_id').val() || '', $('#time_slot_id').val() || '');
        $('#salesReserveTableModal').modal({backdrop: 'static', keyboard: false}).modal('show');
    }

    function closeSalesReservationModal() {
        salesReservationModalCanClose = true;
        $('#salesReserveTableModal').modal('hide');
    }

    function commitSalesReservation() {
        const restaurantId = $('#sales_reserve_restaurant_id').val();
        const bookingDate = $('#sales_reserve_booking_date').val();
        const slotTypeId = $('#sales_reserve_slot_type_id').val();
        const timeSlotId = $('#sales_reserve_time_slot_id').val();
        const categoryId = $('#sales_reserve_table_category_id').val();
        const tableIds = selectedSalesReservationTableIds();
        const status = $('input[name="sales_reserve_status"]:checked').val();
        const errors = {};
        resetSalesReservationMessages();
        if (!restaurantId) errors.restaurant = 'Please select a restaurant.';
        if (!bookingDate) errors.bookingDate = 'Please select a booking date.';
        if (!slotTypeId) errors.slotType = 'Please select a slot type.';
        if (!timeSlotId) errors.timeSlot = 'Please select a time slot.';
        if (!categoryId) errors.category = 'Please select a table category.';
        if (!tableIds.length) errors.tables = 'Please select at least one available table.';
        if (!status) errors.status = 'Please select a reservation status.';
        if (errors.restaurant) $('#sales_reserve_restaurant_id').addClass('is-invalid');
        if (errors.bookingDate) $('#sales_reserve_booking_date').addClass('is-invalid');
        if (errors.slotType) $('#sales_reserve_slot_type_id').addClass('is-invalid');
        if (errors.timeSlot) $('#sales_reserve_time_slot_id').addClass('is-invalid');
        if (errors.category) $('#sales_reserve_table_category_id').addClass('is-invalid');
        $('#sales_reserve_restaurant_error').text(errors.restaurant || '');
        $('#sales_reserve_booking_date_error').text(errors.bookingDate || '');
        $('#sales_reserve_slot_type_error').text(errors.slotType || '');
        $('#sales_reserve_time_slot_error').text(errors.timeSlot || '');
        $('#sales_reserve_table_category_error').text(errors.category || '');
        $('#sales_reserve_table_error').text(errors.tables || '');
        $('#sales_reserve_status_error').text(errors.status || '');
        if (Object.keys(errors).length) return;

        const $button = $('#confirmSalesTableReservation').prop('disabled', true);
        const originalHtml = $button.html();
        $button.text('Checking availability...');
        editSalesDynamicRequest({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: {booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: tableIds, slot_type_id: slotTypeId, exclude_lead_id: <?= (int) $sales_visit->lead_id_againts_visit ?>},
            dataType: 'json'
        }).done(function(res) {
            refreshCsrf(res);
            const selectedSlot = (res.data || []).find(slot => String(slot.id) === String(timeSlotId));
            if (!selectedSlot || !selectedSlot.available) {
                $('#sales_reserve_table_error').text(selectedSlot ? selectedSlot.reason : 'The selected time slot is unavailable.');
                refreshSalesReservationAvailability(tableIds);
                return;
            }
            $('#restaurant_booking_date').val(bookingDate);
            $('#restaurant_id').val(restaurantId);
            $('#slot_type_id').val(slotTypeId);
            $('#time_slot_id').val(timeSlotId);
            $('#table_category_id').val(categoryId);
            $('#table_reservation_status').val(status);
            $('#restaurant_special_request').val($('#sales_reserve_special_request').val());
            const $tableField = $('#table_id').empty();
            tableIds.forEach(tableId => $tableField.append($('<option>', {value: tableId, text: tableId, selected: true})));
            const summary = [
                $('#sales_reserve_restaurant_id option:selected').text(), bookingDate,
                $('#sales_reserve_slot_type_id option:selected').text(), $('#sales_reserve_time_slot_id option:selected').text(),
                $('#sales_reserve_table_category_id option:selected').text(), `${tableIds.length} table(s)`, status
            ].join(' • ');
            $('#sales_restaurant_reservation_summary').removeClass('text-muted').addClass('text-success').text(summary);
            $('#openSalesReserveTableModal').html('<i class="fa fa-pencil me-2"></i>Edit Reservation').removeClass('is-invalid');
            $('#sales_restaurant_reservation_error').text('');
            closeSalesReservationModal();
        }).fail(function(xhr) {
            $('#sales_reserve_table_error').text((xhr.responseJSON || {}).message || 'Unable to verify table availability.');
            refreshSalesReservationAvailability(tableIds);
        }).always(function() {
            $button.prop('disabled', false).html(originalHtml);
        });
    }

    $(document).on('click', '#openSalesReserveTableModal', openSalesReservationModal);
    $(document).on('click', '#closeSalesReserveTableModal, #cancelSalesReserveTableModal', closeSalesReservationModal);
    $(document).on('click', '#confirmSalesTableReservation', commitSalesReservation);
    $(document).on('change', '#sales_reserve_restaurant_id', function() { resetSalesReservationMessages(); loadSalesReservationCategories($(this).val()); });
    $(document).on('change', '#sales_reserve_table_category_id', function() { resetSalesReservationMessages(); loadSalesReservationTables($('#sales_reserve_restaurant_id').val(), $(this).val()); });
    $(document).on('change', '#sales_reserve_slot_type_id', function() { resetSalesReservationMessages(); loadSalesReservationTimeSlots($(this).val()); });
    $(document).on('change', '#sales_reserve_booking_date, #sales_reserve_time_slot_id', function() { resetSalesReservationMessages(); refreshSalesReservationAvailability(); });
    $(document).on('click', '#sales_reserve_table_grid .reservation-table-card', function() {
        if (String($(this).data('available')) !== '1') return;
        $(this).toggleClass('selected');
        $('#sales_reserve_table_error').text('');
    });
    $('#salesReserveTableModal').on('hide.bs.modal', function(event) {
        if (!salesReservationModalCanClose) event.preventDefault();
    }).on('hidden.bs.modal', function() {
        salesReservationModalCanClose = false;
    });


    function salesVisitField(field) {
        if (['booking_date', 'restaurant_id', 'slot_type_id', 'time_slot_id', 'table_category_id', 'table_id', 'table_reservation_status'].includes(field) && $('#openSalesReserveTableModal').length) {
            return $('#openSalesReserveTableModal');
        }
        return $('#' + field);
    }

    function showSalesVisitFieldError(field, message) {
        const $field = salesVisitField(field);
        const isRestaurantReservationError = $field.is('#openSalesReserveTableModal');
        let $error = isRestaurantReservationError
            ? $('#sales_restaurant_reservation_error')
            : $('#' + field + '_error');

        $field.addClass('is-invalid').attr('aria-invalid', 'true');
        $field.next('.select2-container').find('.select2-selection').addClass('is-invalid');

        if (!$error.length) {
            $error = $('<span>', {
                id: field + '_error',
                class: 'text-danger small validation-message'
            });
            $field.closest('.form-group, .mb-3, [class*="col-"]').first().append($error);
        }

        $error.text(message);
    }

    function clearSalesVisitValidation() {
        $('#salesVisitForm .is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
        $('#salesVisitForm [id$="_error"]').text('');
        $('#sales_restaurant_reservation_error').text('');
    }

    function showSalesVisitValidationErrors(errors) {
        clearSalesVisitValidation();
        let $firstField = null;

        $.each(errors, function(field, message) {
            showSalesVisitFieldError(field, message);
            if (!$firstField) {
                $firstField = salesVisitField(field);
            }
        });

        if ($firstField && $firstField.length) {
            const $focusTarget = $firstField.hasClass('select2-hidden-accessible')
                ? $firstField.next('.select2-container').find('.select2-selection')
                : $firstField;

            $('html, body').animate({
                scrollTop: Math.max($focusTarget.offset().top - 140, 0)
            }, 250);
            $focusTarget.trigger('focus');
        }
    }

    function validateSalesVisitForm() {
        const errors = {};
        const value = function(field) {
            return $.trim(String($('#' + field).val() || ''));
        };

        if (!value('property')) errors.property = 'Please select a hotel.';
        if (!value('type')) errors.type = 'Please select a department.';
        if (!value('report_date')) errors.report_date = 'Please select a report date.';
        if (!value('follow_up_1_date')) errors.follow_up_1_date = 'Please select the first follow-up date.';
        if (!value('follow_up_2_date')) errors.follow_up_2_date = 'Please select the second follow-up date.';
        if (!value('visit_type')) errors.visit_type = 'Please select a visit type.';
        if (!value('visit_mode')) errors.visit_mode = 'Please select a visit mode.';
        if (!value('company_id')) errors.company_id = 'Please select a company.';
        if (!value('person_met')) errors.person_met = 'Please select the person met.';
        if (!value('discussion_summary')) errors.discussion_summary = 'Discussion summary is required.';

        if ($('#restaurant_id').length && !value('restaurant_id')) {
            errors.restaurant_id = 'Please select a restaurant.';
        }
        if ($('#slot_type_id').length && !value('slot_type_id')) {
            errors.slot_type_id = 'Please select a slot type.';
        }

        const stage = value('disposition');
        const department = normalizeEditSalesDepartment($('#leadDepartment').val());

        if (stage === 'Lead Lost' && !value('reason')) {
            errors.reason = 'Please select a reason.';
        }

        if (stage === 'Quotation Sent') {
            if (department === 'rooms' && !value('meal_plan')) {
                errors.meal_plan = 'Please select a meal plan.';
            }
            if (department === 'banquet' && !value('banquet_id')) {
                errors.banquet_id = 'Please select a banquet.';
            }
            if (department === 'banquet' && $('#sales_is_room_required').is(':checked')) {
                const checkinDate = value('checkin_date');
                const checkoutDate = value('checkout_date');
                if (!checkinDate) errors.checkin_date = 'Check-in date is required.';
                if (!checkoutDate) errors.checkout_date = 'Check-out date is required.';
                else if (checkinDate && checkoutDate < checkinDate) errors.checkout_date = 'Check-out date must be the same as or after check-in date.';
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

        $.each(['kms_run', 'rate_per_km', 'parking_charges', 'lunch', 'entertainment'], function(_, field) {
            const rawValue = value(field);
            if (rawValue !== '' && (!$.isNumeric(rawValue) || Number(rawValue) < 0)) {
                errors[field] = 'Enter a valid non-negative amount.';
            }
        });

        showSalesVisitValidationErrors(errors);
        return Object.keys(errors).length === 0;
    }

    $(document).on('input change', '#salesVisitForm input, #salesVisitForm select, #salesVisitForm textarea', function() {
        const field = this.id || ($(this).attr('name') || '').replace('[]', '');

        $(this).removeClass('is-invalid').removeAttr('aria-invalid');
        $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        if (field) {
            $('#' + field + '_error').text('');
        }
    });

    $('#salesVisitForm').on('submit', function(e) {

        e.preventDefault();

        /* ================== BASIC FIELDS ================== */

        let userChannel = 'Sales Visit';
        let property = $('select[name="property"]').val();
        let department = $('select[name="type"]').val();
        let lead_status = $('select[name="lead_status"]').val();
        let lead_type = $('select[name="lead_type"]').val();

        let query = $('#discussion_summary').val();
        let remarks = $('textarea[name="remarks"]').val();

        let leadDepartment = $('#leadDepartment').val();
        let disposition = $('#disposition').val() || '';

        /* ================== SALES VISIT FIELDS ================== */
        let report_date = $('#report_date').val();
        let follow_up_1_date = $('#follow_up_1_date').val();
        let follow_up_2_date = $('#follow_up_2_date').val();
        let visit_type = $('#visit_type').val();
        let visit_mode = $('#visit_mode').val();
        let company_id = $('#company_id').val();
        let person_met = $('#person_met').val();
        let agenda = $('#agenda').val();
        let discussion_summary = $('#discussion_summary').val();
        let conclusion = $('#conclusion').val();
        let area_covered = $('#area_covered').val();
        let travel_mode = $('#travel_mode').val();
        let kms_run = $('#kms_run').val();
        let rate_per_km = $('#rate_per_km').val();
        let parking_charges = $('#parking_charges').val();
        let lunch = $('#lunch').val();
        let entertainment = $('#entertainment').val();
        let total_amount = $('#total_amount').val();
        let visit_latitude = $('#visit_latitude').val();
        let visit_longitude = $('#visit_longitude').val();
        let visit_location_details = $('#visit_location_details').val();

        /* ================== BASIC VALIDATION ================== */

        if (validateSalesVisitForm()) {

            let formData = new FormData();

            /* ========== DYNAMIC FIELDS ========== */
            $('#dynamicFields')
                .find('input, select, textarea')
                .each(function() {

                    let name = $(this).attr('name');

                    if (!name) return;

                    if ($(this).attr('type') === 'file') {
                        if (this.files.length > 0) {
                            formData.append(name, this.files[0]);
                        }
                    } else if ($(this).attr('type') === 'checkbox' && !this.checked) {
                        // Unchecked optional controls must not submit their value.
                    } else if (name === 'table_id[]') {
                        // Appended separately so each selected table is submitted.
                    } else {
                        formData.append(name, $(this).val());
                    }
                });

            const selectedTables = $('#table_id').val();
            if (selectedTables) {
                $.each(Array.isArray(selectedTables) ? selectedTables : [selectedTables], function(_, tableId) {
                    formData.append('table_id[]', tableId);
                });
            }

            /* ========== APPEND MAIN FIELDS ========== */
            formData.append('user_channel', userChannel);
            formData.append('property', property);
            formData.append('type', department);
            formData.append('status', lead_status);
            formData.append('query', query);
            formData.append('remarks', remarks);
            formData.append('lead_type', lead_type);
            formData.append('leadDepartment', leadDepartment);
            formData.append('disposition', disposition);

            /* ========== SALES VISIT DATA ========== */
            formData.append('report_date', report_date);
            formData.append('follow_up_1_date', follow_up_1_date);
            formData.append('follow_up_2_date', follow_up_2_date);
            formData.append('visit_type', visit_type);
            formData.append('visit_mode', visit_mode);
            formData.append('company_id', company_id);
            formData.append('person_met', person_met);
            formData.append('agenda', agenda);
            formData.append('discussion_summary', discussion_summary);
            formData.append('conclusion', conclusion);
            formData.append('area_covered', area_covered);
            formData.append('travel_mode', travel_mode);
            formData.append('kms_run', kms_run);
            formData.append('rate_per_km', rate_per_km);
            formData.append('parking_charges', parking_charges);
            formData.append('lunch', lunch);
            formData.append('entertainment', entertainment);
            formData.append('total_amount', total_amount);
            if (window.salesVisitCapturedImage) {
                formData.append('visit_attachment', window.salesVisitCapturedImage);
            }
            formData.append('visit_latitude', visit_latitude);
            formData.append('visit_longitude', visit_longitude);
            formData.append('visit_location_details', visit_location_details);
            appendCsrf(formData);

            /* ================== AJAX ================== */
            $.ajax({
                url: '<?php echo base_url("superAdmin/SalesVisits/update/"); ?><?= encrypt_id($sales_visit->visit_id) ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('updating...');
                },
                success: function(response) {
                    refreshCsrf(response);

                    if (response.status) {
                        window.location.href = '<?php echo base_url("sales-visits-history"); ?>';
                    } else {
                        toastr.error(response.message || 'Failed to update sales visit');
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toastr.error(response.message || 'Unable to update the sales visit');
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('Update');
                    $('#lead_status').prop('disabled', true);
                }
            });
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
                    $.ajax({
                        url: '<?= base_url('LeadController/get_last_lead_by_cli') ?>',
                        type: 'POST',
                        data: csrfData({
                            cli: cli
                        }),
                        dataType: 'json',
                        success: function(response) {
                            refreshCsrf(response);
                            if (response.status === 'success') {
                                $('#username').val(response.data.user_name);
                                $('#phone_number').val(response.data.phone_number); // optional
                                $('#email').val(response.data.email);
                                $('#query').val(response.data.query);
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

<script>
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


    $(document).on("change", "#checkin_date, #checkout_date", function() {

        let checkin = $("#checkin_date").val();
        let checkout = $("#checkout_date").val();

        if (checkin === "" || checkout === "") return;
        if (checkout < checkin) return;

        generateRateRows(checkin, checkout);
    });

    function generateRateRows(startDate, endDate) {

        let tbody = $("#rateTypeTable tbody");
        tbody.empty();

        let start = new Date(startDate);
        let end = new Date(endDate);

        // IMPORTANT: Subtract 1 day to get hotel nights
        end.setDate(end.getDate() - 1);

        while (start <= end) {

            let dt = formatDate(start); // dd-mm-YYYY format

            let row = `
        <tr>
            <td>
                ${dt}
                <input type="hidden" name="rate_date[]" value="${dt}">
            </td>
            <td>
                <select name="rate_type[]" class="form-select rate-type-dd">
                <?php foreach ($ratetype as $ratetype) { ?>
                    <option value="<?= $ratetype->ratetype_code; ?>">
                        <?= $ratetype->ratetype_code; ?>
                    </option>
                <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" name="room_price[]" class="form-control room-price" min="0" value="0">
            </td>
        </tr>
        `;

            tbody.append(row);

            start.setDate(start.getDate() + 1);
        }

        $("#rateTypeTable").show();
    }



    // Date formatting function
    function formatDate(dateObj) {
        let d = dateObj.getDate().toString().padStart(2, '0');
        let m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        let y = dateObj.getFullYear();

        return `${d}-${m}-${y}`;
    }
</script>

<script>
    $(document).ready(function() {

        function calculateTotalAmount() {

            let kmsRun = parseFloat($('#kms_run').val()) || 0;
            let ratePerKm = parseFloat($('#rate_per_km').val()) || 0;
            let parkingCharges = parseFloat($('#parking_charges').val()) || 0;
            let lunch = parseFloat($('#lunch').val()) || 0;
            let entertainment = parseFloat($('#entertainment').val()) || 0;

            // Travel calculation
            let travelAmount = kmsRun * ratePerKm;

            // Final total
            let totalAmount = travelAmount + parkingCharges + lunch + entertainment;

            // Set total with 2 decimal points
            $('#total_amount').val(totalAmount.toFixed(2));
        }

        // Trigger calculation on change / keyup
        $('#kms_run, #rate_per_km, #parking_charges, #lunch, #entertainment')
            .on('keyup change', function() {
                calculateTotalAmount();
            });

    });


    $(document).ready(function() {
        let companyId = <?= json_encode(encrypt_id($sales_visit->company_id)) ?>;
        let selectedPerson = <?= json_encode(encrypt_id($sales_visit->person_met)) ?>;

        $(document)
            .off('change.salesVisitContacts', '#company_id')
            .on('change.salesVisitContacts', '#company_id', function() {
                loadCompanyContacts($(this).val());
            });

        if (companyId) {
            loadCompanyContacts(companyId, selectedPerson);
        } else {
            loadCompanyContacts('');
        }
    });

    function loadCompanyContacts(companyId, selectedPerson = '') {
        let $personSelect = $('#person_met');

        if (!companyId) {
            $personSelect
                .empty()
                .append(new Option('Select Person', ''))
                .prop('disabled', true)
                .trigger('change.select2');
            return;
        }

        $personSelect
            .empty()
            .append(new Option('Loading persons...', ''))
            .prop('disabled', true)
            .trigger('change.select2');

        $.ajax({
            url: "<?= base_url('superAdmin/SalesVisits/get_company_contacts') ?>",
            type: "POST",
            data: csrfData({
                company_id: companyId,
                selected_contact_id: selectedPerson
            }),
            dataType: "json",
            success: function(res) {
                refreshCsrf(res);
                $personSelect.empty().append(new Option('Select Person', ''));

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        let contactName = $.trim(row.first_name + ' ' + row.last_name);
                        let label = contactName + (row.mobile_number ? ' (' + row.mobile_number + ')' : '');
                        $personSelect.append(new Option(label, row.contact_id, false, row.contact_id == selectedPerson));
                    });
                } else {
                    $personSelect.append(new Option('No contacts found', ''));
                }

                $personSelect.prop('disabled', false).trigger('change.select2');
            },
            error: function(xhr) {
                let response = xhr.responseJSON || {};
                refreshCsrf(response);
                $personSelect
                    .empty()
                    .append(new Option('Unable to load contacts', ''))
                    .prop('disabled', false)
                    .trigger('change.select2');
                toastr.error(response.message || 'Unable to load company contacts');
            }
        });
    }
</script>
