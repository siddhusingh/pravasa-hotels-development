<style>
    #salesVisitForm .select2-container {
        width: 100% !important;
    }

    #salesVisitForm .select2-container--default .select2-selection--single {
        height: 46px !important;
        padding: 11px 14px;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        background-color: #fff !important;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px !important;
    }

    #salesVisitForm .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: 0;
        line-height: 22px;
        padding-left: 0;
    }

    #salesVisitForm .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }

    #salesVisitForm .select2-container--default.select2-container--focus .select2-selection--single,
    #salesVisitForm .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2) !important;
    }

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

    #salesVisitForm .company-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    #salesVisitForm .quick-add-button {
        padding: 3px 10px;
        line-height: 1.35;
        white-space: nowrap;
    }

    #salesVisitForm .quick-add-button:disabled {
        cursor: not-allowed;
        opacity: 0.55;
    }

    #quickAddCompanyModal .error-label {
        display: none;
        margin-top: 4px;
        font-size: 0.85rem;
    }

    #quickAddCompanyModal .select2-container {
        width: 100% !important;
    }

    #quickAddCompanyModal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #quickAddCompanyModal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-right: 35px !important;
        padding-left: 13px !important;
    }

    #quickAddCompanyModal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    #quickAddCompanyModal select.is-invalid + .select2-container .select2-selection {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12) !important;
    }

    .quick-add-company-select2-dropdown .select2-search__field {
        min-height: 34px !important;
        height: 34px !important;
        padding: 5px 9px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
    }

    #quickAddContactModal .contact-validation {
        display: none;
        margin-top: 4px;
        font-size: 0.85rem;
    }

    #quickAddContactModal .select2-container {
        width: 100% !important;
    }

    #quickAddContactModal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #quickAddContactModal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-right: 35px !important;
        padding-left: 13px !important;
    }

    #quickAddContactModal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    #quickAddContactModal select.is-invalid + .select2-container .select2-selection {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12) !important;
    }

    .quick-add-contact-select2-dropdown .select2-search__field {
        min-height: 34px !important;
        height: 34px !important;
        padding: 5px 9px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
    }

    #salesVisitForm .table-multiselect-source {
        display: none !important;
    }

    #salesVisitForm .table-multiselect {
        position: relative;
        width: 100%;
    }

    #salesVisitForm .table-multiselect-toggle {
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

    #salesVisitForm .table-multiselect-source.is-invalid + .table-multiselect .table-multiselect-toggle {
        border-color: #dc3545 !important;
    }

    #salesVisitForm .table-multiselect.is-open .table-multiselect-toggle,
    #salesVisitForm .table-multiselect-toggle:focus {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2) !important;
        outline: 0;
    }

    #salesVisitForm .table-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        margin-left: 10px;
    }

    #salesVisitForm .table-multiselect.is-open .table-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    #salesVisitForm .table-multiselect-menu {
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

    #salesVisitForm .table-multiselect.is-open .table-multiselect-menu {
        display: block;
    }

    #salesVisitForm .table-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 9px;
        margin: 0;
        padding: 8px 12px;
    }

    #salesVisitForm .table-multiselect-option:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    #salesVisitForm .table-multiselect-option input[type="checkbox"] {
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

    #salesVisitForm .table-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    #salesVisitForm .table-multiselect-empty {
        color: #6c757d;
        padding: 9px 12px;
    }

    #openSalesReserveTableModal {
        height: 46px;
    }

    #salesReserveTableModal .modal-dialog {
        max-width: min(92vw, 1500px);
    }

    #salesReserveTableModal .reservation-status-options {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

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
                    <h2 class="header-title">Add Sales Visit</h2>
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
                        <li class="active">Add Visit</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img/add_sales_img.png'); ?>" alt="">
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
                                <div class="row g-3">

                                    <!-- Hotel -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="property"><span class="field-label-icon fa fa-building" aria-hidden="true"></span>Hotel (Property) <span class="required-asterisk">*</span></label>
                                            <select name="property" id="property" class="form-control" required>
                                                <option selected disabled value="">Select Hotel</option>
                                                <?php foreach ($hotel_admin as $each) { ?>
                                                    <option value="<?php echo encrypt_id($each->hotel_id) ?>" data-raw-id="<?php echo (int) $each->hotel_id ?>"><?php echo $each->hotel_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <span id="property_error" class="text-danger small validation-message"></span>
                                        </div>
                                    </div>

                                    <!-- Department -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="type"><span class="field-label-icon fa fa-sitemap" aria-hidden="true"></span>Department (Type) <span class="required-asterisk">*</span></label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option selected disabled value="">Select Department</option>
                                                <?php foreach ($departments as $each) { ?>
                                                    <option value="<?php echo encrypt_id($each->department_id) ?>" data-raw-id="<?php echo (int) $each->department_id ?>"
                                                        data-name="<?php echo $each->department_name; ?>"><?php echo $each->department_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <span id="type_error" class="text-danger small validation-message"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lead_type">
                                                <span class="field-label-icon fa fa-fire" aria-hidden="true"></span>Lead Type
                                            </label>
                                            <select name="lead_type" id="lead_type" class="form-control">
                                                <option value="Hot">Hot</option>
                                                <option value="Warm">Warm</option>
                                                <option value="Cold" selected>Cold</option>
                                            </select>
                                            <span id="lead_type_error" class="text-danger small"></span>
                                        </div>
                                    </div>

                                    <!-- Report Date -->
                                    <div class="col-md-4">
                                        <label for="report_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Visit Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="report_date" name="report_date" class="form-control" required>
                                        <span class="text-danger small validation-message" id="report_date_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="follow_up_1_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Follow Up 1 Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="follow_up_1_date" name="follow_up_1_date" class="form-control" required>
                                        <span class="text-danger small validation-message" id="follow_up_1_date_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="follow_up_2_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Follow Up 2 Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="follow_up_2_date" name="follow_up_2_date" class="form-control" required>
                                        <span class="text-danger small validation-message" id="follow_up_2_date_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="visit_type"><span class="field-label-icon fa fa-handshake-o" aria-hidden="true"></span>Visit Type <span class="required-asterisk">*</span></label>
                                        <select name="visit_type" id="visit_type" class="form-control" required>
                                            <option value="Relationship Visit" selected>Relationship Visit</option>
                                            <option value="Follow-up Visit">Follow-up Visit</option>
                                            <option value="Support &amp; Service">Support &amp; Service</option>
                                        </select>
                                        <span class="text-danger small validation-message" id="visit_type_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="visit_mode"><span class="field-label-icon fa fa-map-marker" aria-hidden="true"></span>Visit Mode <span class="required-asterisk">*</span></label>
                                        <select name="visit_mode" id="visit_mode" class="form-control" required>
                                            <option value="Physical Visit" selected>Physical Visit</option>
                                            <option value="Online Meeting">Online Meeting</option>
                                            <option value="Phone Call">Phone Call</option>
                                            <option value="Teams Meeting">Teams Meeting</option>
                                            <option value="Google Meet">Google Meet</option>
                                        </select>
                                        <span class="text-danger small validation-message" id="visit_mode_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="company-label-row">
                                            <label for="company_id"><span class="field-label-icon fa fa-building-o" aria-hidden="true"></span>Company <span class="required-asterisk">*</span></label>
                                            <button type="button" class="btn btn-primary-light btn-sm quick-add-button" id="open-quick-add-company">Quick Add</button>
                                        </div>
                                        <select name="company_id" id="company_id" class="form-control" required>
                                            <option value="">Select Company</option>
                                            <?php foreach ($companies as $c) { ?>
                                                <option value="<?= encrypt_id($c->company_id) ?>">
                                                    <?= $c->company_name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span id="company_id_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Person Met -->
                                 <div class="col-md-4">
    <div class="company-label-row">
        <label for="person_met"><span class="field-label-icon fa fa-user" aria-hidden="true"></span>Person Met <span class="required-asterisk">*</span></label>
        <button type="button" class="btn btn-primary-light btn-sm quick-add-button" id="open-quick-add-contact" disabled>Quick Add</button>
    </div>

    <select
        name="person_met"
        id="person_met"
        class="form-control"
        disabled
        required>
        <option value="">Select Person</option>
    </select>

    <span id="person_met_error" class="text-danger small validation-message"></span>
</div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disposition"><span class="field-label-icon fa fa-list" aria-hidden="true"></span>Stage</label>
                                            <select class="form-control" name="disposition" id="disposition">
                                                <option value="" selected disabled>Select Stage</option>
                                                <option value="Not Contacted">Not Contacted</option>
                                                <option value="Contacted">Contacted</option>
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lead_status"><span class="field-label-icon fa fa-info-circle" aria-hidden="true"></span>Lead Status</label>
                                            <select name="lead_status" id="lead_status" class="form-control" disabled>
                                                <option value="Open" selected>Open</option>

                                                <option value="In Progress">In Progress</option>
                                                <option value="Closed">Closed</option>
                                            </select>
                                            <span id="lead_status_error" class="text-danger small"></span>
                                        </div>
                                    </div>


                                    <input type="hidden" id="leadDepartment" name="leadDepartment">


                                    <div id="dynamicFields" class="row g-3"></div>




                                    <!-- Agenda -->
                                    <div class="col-sm-4">
                                        <label for="agenda"><span class="field-label-icon fa fa-list-alt" aria-hidden="true"></span>Agenda</label>
                                        <textarea name="agenda" class="form-control" rows="2" id="agenda"></textarea>
                                    </div>

                                    <!-- Discussion Summary -->
                                    <div class="col-sm-4">
                                        <label for="discussion_summary"><span class="field-label-icon fa fa-comments" aria-hidden="true"></span>Discussion Summary <span class="required-asterisk">*</span></label>
                                        <textarea name="discussion_summary" class="form-control" rows="3" required id="discussion_summary"></textarea>
                                        <span id="discussion_summary_error" class="text-danger small validation-message"></span>
                                    </div>

                                    <!-- Conclusion -->
                                    <div class="col-sm-4">
                                        <label for="conclusion"><span class="field-label-icon fa fa-check-circle" aria-hidden="true"></span>Conclusion</label>
                                        <textarea name="conclusion" class="form-control" rows="2" id="conclusion"></textarea>
                                    </div>

                                    <hr class="mt-3">

                                    <h5 class="mt-3">Conveyance Details</h5>

                                    <!-- Area Covered -->
                                    <div class="col-sm-4">
                                        <label>Area Covered</label>
                                        <textarea name="area_covered" class="form-control" rows="2" id="area_covered"></textarea>
                                    </div>

                                    <!-- Travel Mode -->
                                    <div class="col-sm-4">
                                        <label>Travel Mode </label>

                                        <select name="travel_mode" class="form-control" id="travel_mode">
                                            <option value="">Select</option>

                                            <?php if (!empty($travel_modes)) : ?>
                                                <?php foreach ($travel_modes as $mode) : ?>
                                                    <option value="<?php echo encrypt_id($mode->id); ?>">
                                                        <?php echo $mode->travel_mode_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- KMS -->
                                    <div class="col-sm-4">
                                        <label>Kms Run</label>
                                        <input type="number" step="0.01" name="kms_run" class="form-control" id="kms_run">
                                    </div>

                                    <!-- Rate -->
                                    <div class="col-sm-4">
                                        <label>Rate / Km</label>
                                        <input type="number" step="0.01" name="rate_per_km" class="form-control" id="rate_per_km">
                                    </div>

                                    <!-- Parking -->
                                    <div class="col-sm-4">
                                        <label>Parking / Toll</label>
                                        <input type="number" step="0.01" name="parking_charges" class="form-control" id="parking_charges">
                                    </div>

                                    <!-- Lunch -->
                                    <div class="col-sm-4">
                                        <label>Lunch</label>
                                        <input type="number" step="0.01" name="lunch" class="form-control" id="lunch">
                                    </div>

                                    <!-- Total -->
                                    <div class="col-sm-4">
                                        <label>Total Amount</label>
                                        <input type="number" step="0.01" name="total_amount" class="form-control" id="total_amount" readonly>
                                    </div>

                                    <hr class="mt-3">

                                    <h5 class="mt-3">Visit Attachment &amp; Location</h5>

                                    <div class="col-md-6">
                                        <label class="form-label">Attachment Image</label>
                                        <button type="button" id="openVisitCamera" class="btn btn-outline-primary">
                                            <i class="fa fa-camera me-1" aria-hidden="true"></i> Open Camera
                                        </button>
                                        <small class="text-muted d-block mt-2">Capture the attachment using the live camera.</small>
                                        <div id="visitAttachmentName" class="small mt-1"></div>
                                        <img id="visitAttachmentPreview" class="img-thumbnail mt-2 d-none" alt="Selected sales visit attachment" style="max-height: 180px; max-width: 100%;">
                                        <div id="visitCameraStatus" class="small mt-2 text-muted"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="visit_location_details">Current Visit Location</label>
                                        <div class="input-group">
                                            <input type="text" id="visit_location_details" name="visit_location_details" class="form-control" placeholder="Location not captured" readonly>
                                            <button type="button" id="captureVisitLocation" class="btn btn-outline-primary">
                                                <i class="fa fa-map-marker me-1" aria-hidden="true"></i> Capture Location
                                            </button>
                                        </div>
                                        <input type="hidden" id="visit_latitude" name="visit_latitude">
                                        <input type="hidden" id="visit_longitude" name="visit_longitude">
                                        <div id="visitLocationStatus" class="small mt-2 text-muted">Capture the current location during the sales visit.</div>
                                        <a id="visitGoogleMapsLink" class="small d-none" href="#" target="_blank" rel="noopener noreferrer">
                                            <i class="fa fa-map me-1" aria-hidden="true"></i> View on OpenStreetMap
                                        </a>
                                        <div id="visitLocationMap" class="border rounded mt-2 overflow-hidden" style="height: 340px; width: 100%;">
                                            <div class="h-100 d-flex align-items-center justify-content-center text-muted text-center px-3">
                                                Loading development map...
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-sm-12 text-end mt-3">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="window.history.back();">
                                            Back
                                        </button>

                                        <button type="submit" id="submitBtn"
                                            class="btn btn-primary px-4">
                                            Submit
                                        </button>
                                    </div>

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

<!-- Restaurant reservation modal is used only by Add Sales Visit. -->
<div class="modal fade" id="salesReserveTableModal" tabindex="-1" aria-labelledby="salesReserveTableModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <span class="reserve-modal-icon"><i class="fa fa-cutlery"></i></span>
                    <div><h5 class="modal-title" id="salesReserveTableModalLabel">Reserve Table</h5><p class="reserve-modal-subtitle">Select an available table for this reservation</p></div>
                </div>
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
                <div class="reservation-panel">
                    <div class="reservation-table-grid" id="sales_reserve_table_grid"><div class="text-muted">Select restaurant, booking date, time slot and table category to view tables.</div></div>
                    <div class="text-danger small mt-2" id="sales_reserve_table_error"></div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-lg-7"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-file-text-o"></i>Special Instructions <span class="text-muted fw-normal">(Optional)</span></label><textarea class="reserve-control reserve-instructions" id="sales_reserve_special_request" maxlength="250" placeholder="Add any special request or notes for this reservation..."></textarea></div></div>
                    <div class="col-lg-5"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-check-circle"></i>Reservation Status</label><div class="reservation-status-options"><label><input type="radio" name="sales_reserve_status" value="Reserved" checked> Reserved</label><label><input type="radio" name="sales_reserve_status" value="Seated"> Seated</label><label><input type="radio" name="sales_reserve_status" value="Completed"> Completed</label><label><input type="radio" name="sales_reserve_status" value="Cancelled"> Cancelled</label></div><div class="text-danger small mt-1" id="sales_reserve_status_error"></div></div></div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light border" id="cancelSalesReserveTableModal">Cancel</button>
                <button type="button" class="btn btn-primary reserve-submit-btn" id="confirmSalesTableReservation"><i class="fa fa-calendar-check-o me-2"></i>Reserve Table</button>
            </div>
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

<!-- ================= QUICK ADD COMPANY MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="quickAddCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="quickAddCompanyForm" enctype="multipart/form-data" novalidate>
            <div class="modal-content">
                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-building"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h5>Add Company</h5>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Fill in the details to add a company.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3 row">
                    <div class="col-md-4 mb-3">
                        <label>Company Group <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="company_group_id" id="quick_company_group_id">
                            <option value="">Select</option>
                            <?php foreach ($company_groups as $g) { ?>
                                <option value="<?= encrypt_id($g->id) ?>"><?= htmlspecialchars($g->company_group_name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="quick_company_group_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Company Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" name="company_name" id="quick_company_name">
                        <div class="error-label text-danger" id="quick_company_name_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Email <span class="required-asterisk">*</span></label>
                        <input type="email" class="form-control" name="email" id="quick_email">
                        <div class="error-label text-danger" id="quick_email_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Secondary Email</label>
                        <input type="email" class="form-control" name="secondary_email">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone_number">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Mobile <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" name="mobile_number" id="quick_mobile_number">
                        <div class="error-label text-danger" id="quick_mobile_number_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>GST Number</label>
                        <input type="text" class="form-control" name="gst_number">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Country <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="country_id" id="quick_country_id">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c) { ?>
                                <option value="<?= encrypt_id($c->country_id) ?>"><?= htmlspecialchars($c->country_name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="quick_country_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>State <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="state_id" id="quick_state_id">
                            <option value="">Select State</option>
                            <?php foreach ($states as $s) { ?>
                                <option value="<?= encrypt_id($s->state_id) ?>"><?= htmlspecialchars($s->state_name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="quick_state_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>City <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="city_id" id="quick_city_id">
                            <option value="">Select City</option>
                            <?php foreach ($cities as $c) { ?>
                                <option value="<?= encrypt_id($c->city_id) ?>"><?= htmlspecialchars($c->city_name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="quick_city_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Area <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="area_id" id="quick_area_id">
                            <option value="">Select</option>
                            <?php foreach ($areas as $a) { ?>
                                <option value="<?= encrypt_id($a->area_id) ?>"><?= htmlspecialchars($a->area_name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="quick_area_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pincode">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Address <span class="required-asterisk">*</span></label>
                        <textarea class="form-control" name="address" id="quick_address"></textarea>
                        <div class="error-label text-danger" id="quick_address_error"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Details</label>
                        <textarea class="form-control" name="details"></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Creditibility</label>
                        <select class="form-select" name="company_creditibility">
                            <option value="Credit Not Allowed">Credit Not Allowed</option>
                            <option value="Credit Allowed">Credit Allowed</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Credit Form</label>
                        <input type="file" class="form-control" name="credit_form_file">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select class="form-select" name="status" id="quick_company_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= QUICK ADD CONTACT MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="quickAddContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="quickAddContactForm" novalidate>
            <div class="modal-content">
                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-address-book"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h5>Add Contact</h5>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Fill in the details to add a company contact.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company Name <span class="required-asterisk">*</span></label>
                            <select class="form-control" id="quick_contact_company_display" disabled>
                                <option value="">Select Company</option>
                                <?php foreach ($companies as $company) { ?>
                                    <option value="<?= encrypt_id($company->company_id); ?>">
                                        <?= htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="company_id" id="quick_contact_company_id">
                            <span class="text-danger contact-validation" id="quick_contact_company_id_error"></span>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Title <span class="required-asterisk">*</span></label>
                            <select class="form-select" name="title" id="quick_contact_title">
                                <option value="">Select</option>
                                <option>Mr</option>
                                <option>Mrs</option>
                                <option>Ms</option>
                                <option>Dr</option>
                            </select>
                            <span class="text-danger contact-validation" id="quick_contact_title_error"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">First Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="quick_contact_first_name">
                            <span class="text-danger contact-validation" id="quick_contact_first_name_error"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Last Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="quick_contact_last_name">
                            <span class="text-danger contact-validation" id="quick_contact_last_name_error"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation</label>
                            <select class="form-control" name="designation_id" id="quick_contact_designation_id">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $designation) { ?>
                                    <option value="<?= encrypt_id($designation->id); ?>">
                                        <?= htmlspecialchars($designation->designation_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input type="email" class="form-control" name="email" id="quick_contact_email">
                            <span class="text-danger contact-validation" id="quick_contact_email_error"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone_number">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mobile <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="mobile_number" id="quick_contact_mobile_number">
                            <span class="text-danger contact-validation" id="quick_contact_mobile_number_error"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country</label>
                            <select class="form-control" name="country_id" id="quick_contact_country_id">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $each) { ?>
                                    <option value="<?= encrypt_id($each->country_id); ?>">
                                        <?= htmlspecialchars($each->country_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <select class="form-control" name="state_id" id="quick_contact_state_id">
                                <option value="">Select State</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?= encrypt_id($state->state_id); ?>">
                                        <?= htmlspecialchars($state->state_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <select class="form-control" name="city" id="quick_contact_city">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city) { ?>
                                    <option value="<?= encrypt_id($city->city_id); ?>">
                                        <?= htmlspecialchars($city->city_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" class="form-control" name="pincode">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Anniversary</label>
                            <input type="date" class="form-control" name="date_of_anniversary">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="quick_contact_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
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

    window.initSalesVisitMap = function() {
        const mapElement = document.getElementById('visitLocationMap');
        if (!mapElement || !window.L) {
            return;
        }

        mapElement.innerHTML = '';
        salesVisitMap = L.map(mapElement).setView([22.9734, 78.6569], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(salesVisitMap);
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
                attachmentPreviewUrl = '';
            }

            if (!file) {
                $('#visitAttachmentName').text('');
                $('#visitAttachmentPreview').attr('src', '').addClass('d-none');
                return;
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
                $('#visitCameraStatus').removeClass('text-muted text-danger').addClass('text-success').text('Photo captured successfully.');
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
            const location = {
                lat: Number(latitude),
                lng: Number(longitude)
            };

            $('#visit_latitude').val(latitude);
            $('#visit_longitude').val(longitude);
            $('#visitGoogleMapsLink')
                .attr('href', 'https://www.openstreetmap.org/?mlat=' + encodeURIComponent(latitude) +
                    '&mlon=' + encodeURIComponent(longitude) + '#map=17/' +
                    encodeURIComponent(latitude) + '/' + encodeURIComponent(longitude))
                .removeClass('d-none');

            if (salesVisitMap) {
                salesVisitMap.setView([location.lat, location.lng], 17);
                if (salesVisitMarker) {
                    salesVisitMarker.setLatLng([location.lat, location.lng]);
                } else {
                    const markerIcon = L.divIcon({
                        className: '',
                        html: '<span aria-hidden="true" style="display:block;width:28px;height:28px;background:#8f72dc;border:3px solid #fff;border-radius:50% 50% 50% 0;box-shadow:0 2px 6px rgba(0,0,0,.35);transform:rotate(-45deg);"></span>',
                        iconSize: [34, 42],
                        iconAnchor: [17, 38]
                    });
                    salesVisitMarker = L.marker([location.lat, location.lng], {
                        title: 'Current sales visit location',
                        icon: markerIcon
                    }).addTo(salesVisitMap);
                }
            }

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
                $button.prop('disabled', false).html('<i class="fa fa-map-marker me-1" aria-hidden="true"></i> Capture Location');
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

    function initializeSalesVisitSelect2(scope) {
        if (!$.fn.select2) {
            return;
        }

        const $scope = scope ? $(scope) : $('#salesVisitForm');
        const $selects = $scope.is('select')
            ? $scope.filter('select')
            : $scope.find('select');

        $selects.each(function() {
            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    width: '100%'
                });
            }
        });
    }

    function initializeQuickAddCompanySelect2() {
        if (!$.fn.select2) {
            return;
        }

        $('#quick_company_group_id, #quick_country_id, #quick_state_id, #quick_city_id, #quick_area_id').each(function() {
            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    width: '100%',
                    placeholder: $select.find('option:first').text(),
                    allowClear: false,
                    dropdownParent: $('#quickAddCompanyModal'),
                    dropdownCssClass: 'quick-add-company-select2-dropdown'
                });
            }
        });
    }

    function resetQuickAddCompanyForm() {
        const $form = $('#quickAddCompanyForm');
        $form[0].reset();
        $form.find('.error-label').text('').hide();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('select').trigger('change.select2');
    }

    function showQuickAddCompanyFieldError(field, message) {
        $('#quick_' + field + '_error').text(message).show();
        $('#quick_' + field).addClass('is-invalid');
    }

    $(document).ready(function() {
        initializeQuickAddCompanySelect2();

        $(document).on('click', '#open-quick-add-company', function() {
            resetQuickAddCompanyForm();
            $('#quickAddCompanyModal').modal('show');
        });

        $('#quickAddCompanyForm').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const requiredFields = [
                'company_group_id',
                'company_name',
                'email',
                'mobile_number',
                'country_id',
                'state_id',
                'city_id',
                'area_id',
                'address'
            ];
            let hasError = false;

            $form.find('.error-label').text('').hide();
            $form.find('.is-invalid').removeClass('is-invalid');

            requiredFields.forEach(function(field) {
                const value = String($('#quick_' + field).val() || '').trim();

                if (value === '') {
                    showQuickAddCompanyFieldError(field, 'This field is required');
                    hasError = true;
                }
            });

            const email = String($('#quick_email').val() || '').trim();
            if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showQuickAddCompanyFieldError('email', 'Please enter a valid email address');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            const companyName = String($('#quick_company_name').val() || '').trim();
            const companyStatus = $('#quick_company_status').val();
            const $submitButton = $form.find('button[type="submit"]');
            const formData = new FormData(this);
            appendCsrf(formData);

            $.ajax({
                url: "<?= base_url('company-save') ?>",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $submitButton.prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);

                    if (!response.status) {
                        toastr.error(response.message || 'Unable to add the company');
                        return;
                    }

                    toastr.success(response.message || 'Company added successfully');
                    $('#quickAddCompanyModal').modal('hide');

                    if (companyStatus === '1' && response.record_id) {
                        const companyOption = new Option(companyName, response.record_id, true, true);
                        $('#company_id').append(companyOption).trigger('change');
                        $('#quick_contact_company_display')
                            .append(new Option(companyName, response.record_id, false, false))
                            .trigger('change.select2');
                    } else {
                        toastr.info('The inactive company was saved but is not available for this sales visit.');
                    }

                    resetQuickAddCompanyForm();
                },
                error: function() {
                    toastr.error('Server error! Please try again');
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text('Save');
                }
            });
        });
    });

    function initializeQuickAddContactSelect2() {
        if (!$.fn.select2) {
            return;
        }

        $('#quick_contact_company_display, #quick_contact_designation_id, #quick_contact_country_id, #quick_contact_state_id, #quick_contact_city').each(function() {
            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    width: '100%',
                    placeholder: $select.find('option:first').text(),
                    allowClear: false,
                    dropdownParent: $('#quickAddContactModal'),
                    dropdownCssClass: 'quick-add-contact-select2-dropdown'
                });
            }
        });
    }

    function resetQuickAddContactForm() {
        const $form = $('#quickAddContactForm');
        $('#quick_contact_company_display option[data-transient="true"]').remove();
        $form[0].reset();
        $form.find('.contact-validation').text('').hide();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('select').trigger('change.select2');
    }

    function showQuickAddContactFieldError(field, message) {
        $('#quick_contact_' + field + '_error').text(message).show();

        if (field === 'company_id') {
            $('#quick_contact_company_display').addClass('is-invalid');
        } else {
            $('#quick_contact_' + field).addClass('is-invalid');
        }
    }

    function syncQuickAddContactButton() {
        const hasCompany = Boolean($('#company_id').val());
        $('#open-quick-add-contact')
            .prop('disabled', !hasCompany)
            .attr('title', hasCompany ? 'Quick add a person' : 'Select a company first');
    }

    $(document).ready(function() {
        initializeQuickAddContactSelect2();
        syncQuickAddContactButton();

        $(document).on('change.quickAddContact', '#company_id', syncQuickAddContactButton);

        $(document).on('click', '#open-quick-add-contact', function() {
            const companyId = $('#company_id').val();
            const companyName = $.trim($('#company_id option:selected').text());

            if (!companyId) {
                toastr.info('Please select a company first.');
                return;
            }

            resetQuickAddContactForm();
            $('#quick_contact_company_id').val(companyId);

            const $companyDisplay = $('#quick_contact_company_display');
            if ($companyDisplay.find('option').filter(function() {
                return this.value === companyId;
            }).length === 0) {
                const companyOption = new Option(companyName, companyId, true, true);
                companyOption.setAttribute('data-transient', 'true');
                $companyDisplay.append(companyOption);
            }

            $companyDisplay.val(companyId).trigger('change');
            $('#quickAddContactModal').modal('show');
        });

        $('#quickAddContactForm').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const requiredFields = [
                'company_id',
                'title',
                'first_name',
                'last_name',
                'email',
                'mobile_number'
            ];
            let hasError = false;

            $form.find('.contact-validation').text('').hide();
            $form.find('.is-invalid').removeClass('is-invalid');

            requiredFields.forEach(function(field) {
                const value = String($('#quick_contact_' + field).val() || '').trim();

                if (value === '') {
                    showQuickAddContactFieldError(field, 'This field is required');
                    hasError = true;
                }
            });

            const email = String($('#quick_contact_email').val() || '').trim();
            if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showQuickAddContactFieldError('email', 'Please enter a valid email address');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            const companyId = $('#quick_contact_company_id').val();
            const contactStatus = $('#quick_contact_status').val();
            const $submitButton = $form.find('button[type="submit"]');
            const requestData = $form.serialize() + '&' +
                encodeURIComponent(window.CSRF.name) + '=' + encodeURIComponent(window.CSRF.hash);

            $.ajax({
                url: "<?= base_url('company-contact-save') ?>",
                type: 'POST',
                data: requestData,
                dataType: 'json',
                beforeSend: function() {
                    $submitButton.prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);

                    if (!response.status) {
                        toastr.error(response.message || 'Unable to add the contact');
                        return;
                    }

                    toastr.success(response.message || 'Contact added successfully');
                    $('#quickAddContactModal').modal('hide');

                    if (contactStatus === 'Active' && response.record_id) {
                        loadCompanyContacts(companyId, response.record_id);
                    } else {
                        toastr.info('The inactive contact was saved but is not available for this sales visit.');
                    }

                    resetQuickAddContactForm();
                },
                error: function() {
                    toastr.error('Server error! Please try again');
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text('Save');
                }
            });
        });
    });

    $(document).ready(function() {
        initializeSalesVisitSelect2('#salesVisitForm');

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
        //     if (!this.value) {
        //         $('#created_date_error').html('Please Select Created Date');
        //     } else {
        //         $('#created_date_error').html('');
        //     }
        // });

        // Submit Form via AJAX

    });


    $(".legacy-sales-dynamic-disabled").change(function() {

        let property = $("#property").val();
        updateDynamicFieldsForEdit("", property);

    })

    $(".legacy-sales-dynamic-disabled").change(function() {

        let property = $("#property").val();
        updateDynamicFieldsForEdit("", property);

    })

    $(".legacy-sales-dynamic-disabled").change(function() {

        let property = $("#property").val();
        updateDynamicFieldsForEdit("", property);

    })


    function updateDynamicFieldsForEdit(data = "") {
        const disposition = $("#disposition").val();
        let property = $("#property").val();

        let department = $('#type').find(':selected').data('name')?.toLowerCase();

        $('#leadDepartment').val(department);

        let existingLeadData = data;

        console.log(existingLeadData)


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
                loadRestaurants(property);

                // Load slot types via AJAX
                loadSlotTypes();
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

        initializeSalesVisitSelect2(container);
        $('#lead_status').trigger('change.select2');

    }


    function loadRestaurants(hotel_id) {

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

                $('#restaurant_id').html(html).trigger('change.select2');
            }
        });
    }

    function loadSlotTypes() {

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

                $('#slot_type_id').html(html).trigger('change.select2');
            }
        });
    }

    let salesDynamicAjaxQueue = $.Deferred().resolve().promise();
    let salesDynamicGeneration = 0;

    function salesDynamicRequest(options) {
        const runRequest = function() {
            if ((options.type || 'GET').toUpperCase() === 'POST') {
                options.data = csrfData(options.data || {});
            }
            const request = $.ajax(options);
            request.done(refreshCsrf);
            return request;
        };

        salesDynamicAjaxQueue = salesDynamicAjaxQueue.then(runRequest, runRequest);
        return salesDynamicAjaxQueue;
    }

    function normalizeSalesDepartment(name) {
        name = String(name || '').trim().toLowerCase();
        if (name === 'restaurants') return 'restaurant';
        if (name === 'banquets') return 'banquet';
        return name;
    }

    function resetSalesDynamicFields() {
        $.each(salesDynamicDependencyObservers, function(_, observer) {
            observer.disconnect();
        });
        salesDynamicDependencyObservers.length = 0;

        $('#dynamicFields select.select2-hidden-accessible').each(function() {
            $(this).select2('destroy');
        });
        $('#dynamicFields').empty();
    }

    function initializeSalesDynamicSelects() {
        initializeSalesVisitSelect2($('#dynamicFields select:not([multiple])'));
        $('#dynamicFields select:not([multiple])').trigger('change.select2');
        observeSalesDynamicDependencies();
    }

    let salesDynamicRefreshTimer = null;
    const salesDynamicObservers = [];
    const salesDynamicDependencyObservers = [];

    function scheduleSalesDynamicRefresh() {
        window.clearTimeout(salesDynamicRefreshTimer);
        salesDynamicRefreshTimer = window.setTimeout(refreshSalesDynamicFields, 0);
    }

    function observeSalesDynamicControls() {
        if (!window.MutationObserver) {
            return;
        }

        $.each(['property', 'type', 'disposition'], function(_, fieldId) {
            const renderedSelection = document.getElementById('select2-' + fieldId + '-container');

            if (!renderedSelection || renderedSelection.dataset.salesDynamicObserved === 'true') {
                return;
            }

            renderedSelection.dataset.salesDynamicObserved = 'true';
            const observer = new MutationObserver(scheduleSalesDynamicRefresh);
            observer.observe(renderedSelection, {
                childList: true,
                characterData: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['title']
            });
            salesDynamicObservers.push(observer);
        });
    }

    function observeSalesDynamicDependencies() {
        if (!window.MutationObserver) {
            return;
        }

        const dependencies = {
            restaurant_id: function(value) {
                if (value) {
                    loadSalesTableCategories(value);
                } else {
                    $('#table_category_id').html('<option value="">Select Category</option>').trigger('change.select2');
                    $('#table_id').empty();
                    initializeSalesTableMultiSelect();
                }
            },
            slot_type_id: function(value) {
                if (value) {
                    loadSalesTimeSlots(value);
                } else {
                    $('#time_slot_id').html('<option value="">Select Time Slot</option>').trigger('change.select2');
                }
            },
            table_category_id: function(value) {
                const restaurantId = $('#restaurant_id').val();
                if (restaurantId && value) {
                    loadSalesTables(restaurantId, value);
                } else {
                    $('#table_id').empty();
                    initializeSalesTableMultiSelect();
                }
            }
        };

        $.each(dependencies, function(fieldId, loadDependency) {
            const $field = $('#' + fieldId);
            const renderedSelection = document.getElementById('select2-' + fieldId + '-container');

            if (!$field.length || !renderedSelection) {
                return;
            }

            let previousValue = String($field.val() || '');
            const observer = new MutationObserver(function() {
                const currentValue = String($field.val() || '');
                if (currentValue === previousValue) {
                    return;
                }

                previousValue = currentValue;
                loadDependency(currentValue);
            });

            observer.observe(renderedSelection, {
                childList: true,
                characterData: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['title']
            });
            salesDynamicDependencyObservers.push(observer);
        });
    }

    function refreshSalesDynamicFields() {
        salesDynamicGeneration += 1;
        const stage = $('#disposition').val() || '';
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
        const department = normalizeSalesDepartment($('#type option:selected').data('name'));
        const hotelId = $('#property option:selected').data('raw-id') || '';
        const departmentId = $('#type option:selected').data('raw-id') || '';
        const $container = $('#dynamicFields');
        const today = new Date().toISOString().split('T')[0];

        $('#lead_status').val(leadStatusByStage[stage] || 'Open').trigger('change.select2');
        $('#leadDepartment').val(department);
        resetSalesDynamicFields();

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
                    <div class="col-md-3 mb-3"><label>Room Revenue</label><input type="number" name="revenue_room" id="revenue_room" class="form-control revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>F&amp;B Revenue</label><input type="number" name="revenue_fnb" id="revenue_fnb" class="form-control revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>Other Revenue</label><input type="number" name="revenue_other" id="revenue_other" class="form-control revenue-field" step="0.01"></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly></div>`);
                loadSalesRoomTypes(hotelId);
                loadSalesMealPlans();
            } else if (department === 'restaurant') {
                $container.append(`
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Arrival Time</label><input type="time" name="arrival_time" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" class="form-control" step="0.01"></div>
                    <div class="col-md-6 mb-3"><label>Special Occasion (if any)</label><input type="text" name="special_occasion" class="form-control"></div>
                    <div class="col-md-4 mb-3">
                        <label>Table Reservation <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-primary w-100" id="openSalesReserveTableModal"><i class="fa fa-calendar-check-o me-2"></i>Reserve Table</button>
                        <div class="small text-muted mt-2" id="sales_restaurant_reservation_summary">No table reserved yet.</div>
                        <div class="text-danger error-label" id="sales_restaurant_reservation_error"></div>
                    </div>
                    <input type="hidden" name="booking_date" id="restaurant_booking_date" value="${today}">
                    <input type="hidden" name="restaurant_id" id="restaurant_id">
                    <input type="hidden" name="slot_type_id" id="slot_type_id">
                    <input type="hidden" name="time_slot_id" id="time_slot_id">
                    <input type="hidden" name="table_category_id" id="table_category_id">
                    <select name="table_id[]" id="table_id" multiple hidden></select>
                    <input type="hidden" name="table_reservation_status" id="table_reservation_status">
                    <input type="hidden" name="special_request" id="restaurant_special_request">`);
            } else if (department === 'banquet') {
                $container.append(`
                    <div class="col-md-3 mb-3 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_room_required" id="sales_is_room_required" value="1"><label class="form-check-label" for="sales_is_room_required">Is Room Required?</label></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="display:none;"><label>Check-in Date <span class="text-danger">*</span></label><input type="date" name="checkin_date" id="checkin_date" class="form-control" min="${today}"><div class="text-danger error-label" id="checkin_date_error"></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="display:none;"><label>Check-out Date <span class="text-danger">*</span></label><input type="date" name="checkout_date" id="checkout_date" class="form-control" min="${today}"><div class="text-danger error-label" id="checkout_date_error"></div></div>
                    <div class="col-md-3 mb-3 sales-room-required-fields" style="display:none;"><label>Number of Rooms <span class="text-danger">*</span></label><input type="number" name="number_of_rooms" id="number_of_rooms" class="form-control" min="1" step="1"><div class="text-danger error-label" id="number_of_rooms_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Booking Date</label><input type="date" name="booking_date" class="form-control" value="${today}"></div>
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Banquet <span class="text-danger">*</span></label><select name="banquet_id" id="banquet_id" class="form-select"><option value="">Select Banquet</option></select><div class="text-danger error-label" id="banquet_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" class="form-control" step="0.01"></div>`);
                loadSalesBanquets(hotelId);
            }

            $container.append(`
                <div class="col-md-4 mt-3 mb-4"><label>Follow-up Date</label><input type="date" name="followup_date" class="form-control"></div>
                <div class="col-md-4 mt-3 mb-4"><label>2nd Follow-up Date</label><input type="date" name="second_followup_date" class="form-control"></div>`);
            loadSalesPromotionalOffers(departmentId);
        }

        if (stage === 'Negotiations' || stage === 'Not Contacted' || stage === 'Advance Received') {
            $container.append(`
                <div class="col-md-3 mb-3"><label>Booking Enquiry Date</label><input type="date" name="booking_date" class="form-control" value="${today}"></div>
                <div class="col-md-3 mb-3"><label>Follow-up Date</label><input type="date" name="followup_date" class="form-control"></div>
                <div class="col-md-3 mb-3"><label>2nd Follow-up Date</label><input type="date" name="second_followup_date" class="form-control"></div>`);
        }

        initializeSalesDynamicSelects();
    }

    function loadSalesSelect(url, data, selector, placeholder, valueKey, labelBuilder, afterLoad) {
        const requestGeneration = salesDynamicGeneration;
        const $select = $(selector).html(`<option value="">Loading...</option>`);
        salesDynamicRequest({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                refreshCsrf(res);
                if (requestGeneration !== salesDynamicGeneration || !$(selector).length) {
                    return;
                }
                let html = `<option value="">${placeholder}</option>`;
                if (res.status === 'success' || res.status === true) {
                    $.each(res.data || [], function(_, row) {
                        html += `<option value="${row[valueKey]}">${labelBuilder(row)}</option>`;
                    });
                }
                $select.html(html).trigger('change.select2');
                if (typeof afterLoad === 'function') {
                    afterLoad($select);
                }
            }
        });
    }

    function loadSalesRestaurants(hotelId) {
        loadSalesSelect(
            "<?= base_url('lead/get-restaurants') ?>",
            {hotel_id: hotelId},
            '#restaurant_id',
            'Select Restaurant',
            'id',
            row => row.restaurant_name,
            function($restaurant) {
                const $available = $restaurant.find('option').filter(function() {
                    return String(this.value).trim() !== '';
                });
                if ($available.length === 1) {
                    $restaurant.val($available.first().val()).trigger('change');
                }
            }
        );
    }

    function loadSalesBanquets(hotelId) {
        loadSalesSelect("<?= base_url('lead/get-banquets') ?>", {hotel_id: hotelId}, '#banquet_id', 'Select Banquet', 'banquet_id', row => row.banquet_name);
    }

    function loadSalesRoomTypes(hotelId) {
        loadSalesSelect("<?= base_url('lead/get-room-types') ?>", {hotel_id: hotelId}, '#roomtype', 'Select Room Type', 'roomtype_id', row => row.roomtype_name);
    }

    function loadSalesMealPlans() {
        loadSalesSelect("<?= base_url('lead/get-meal-plans') ?>", {}, '#meal_plan', 'Select Meal Plan', 'id', row => row.plan);
    }

    function loadSalesPromotionalOffers(departmentId) {
        loadSalesSelect("<?= base_url('lead/get-promotional-offers') ?>", {department_id: departmentId}, '#promotional_offers', 'Select Offer', 'id', row => row.offer_name);
    }

    function loadSalesSlotTypes() {
        const requestGeneration = salesDynamicGeneration;
        const $select = $('#slot_type_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "<?= base_url('lead/get-slot-types') ?>",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (requestGeneration !== salesDynamicGeneration || !$('#slot_type_id').length) {
                    return;
                }
                let html = '<option value="">Select Slot</option>';
                $.each(res.data || [], function(_, row) {
                    html += `<option value="${row.id}">${row.slot_name} (${row.start_time} - ${row.end_time})</option>`;
                });
                $select.html(html).trigger('change.select2');
            }
        });
    }

    function loadSalesTimeSlots(slotTypeId) {
        loadSalesSelect("<?= base_url('lead/get-time-slots') ?>", {slot_type_id: slotTypeId}, '#time_slot_id', 'Select Time Slot', 'id', row => `${row.start_time} - ${row.end_time}`);
    }

    function loadSalesTableCategories(restaurantId) {
        loadSalesSelect(
            "<?= base_url('lead/get-table-categories') ?>",
            {restaurant_id: restaurantId},
            '#table_category_id',
            'Select Category',
            'id',
            row => row.category_name,
            function($category) {
                const $available = $category.find('option').filter(function() {
                    return String(this.value).trim() !== '';
                });
                if ($available.length === 1) {
                    $category.val($available.first().val()).trigger('change');
                }
            }
        );
    }

    function loadSalesTables(restaurantId, categoryId) {
        const requestGeneration = salesDynamicGeneration;
        salesDynamicRequest({
            url: "<?= base_url('lead/get-tables') ?>",
            type: 'POST',
            data: {restaurant_id: restaurantId, category_id: categoryId},
            dataType: 'json',
            success: function(res) {
                refreshCsrf(res);
                if (requestGeneration !== salesDynamicGeneration || !$('#table_id').length) {
                    return;
                }
                let html = '';
                $.each(res.data || [], function(_, row) {
                    html += `<option value="${row.id}">Table ${row.table_name} (${row.capacity} Seats)</option>`;
                });
                $('#table_id').html(html);
                initializeSalesTableMultiSelect();
            }
        });
    }

    function syncSalesTableMultiSelect($select, $widget) {
        const values = ($select.val() || []).map(String);
        const total = $widget.find('.table-multiselect-item').length;
        const selectedCount = values.length;
        $widget.find('.table-multiselect-item').each(function() {
            $(this).prop('checked', values.includes(String($(this).val())));
        });
        $widget.find('.table-multiselect-all')
            .prop('checked', total > 0 && selectedCount === total)
            .prop('indeterminate', selectedCount > 0 && selectedCount < total);

        let summary = 'Select Table';
        if (selectedCount > 0 && selectedCount === total) {
            summary = `All selected (${selectedCount})`;
        } else if (selectedCount > 0) {
            summary = `${selectedCount} selected`;
        }

        $widget.find('.table-multiselect-summary').text(summary);
    }

    function initializeSalesTableMultiSelect() {
        const $select = $('#table_id');
        if (!$select.length) return;

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.next('.table-multiselect').remove();
        $select.addClass('table-multiselect-source');
        const $widget = $('<div>', {class: 'table-multiselect'});
        const $toggle = $('<button>', {type: 'button', class: 'table-multiselect-toggle', 'aria-expanded': 'false'})
            .append($('<span>', {class: 'table-multiselect-summary', text: 'Select Table'}));
        const $menu = $('<div>', {class: 'table-multiselect-menu'});
        const $options = $select.find('option').filter(function() { return String(this.value).trim() !== ''; });

        if ($options.length) {
            $menu.append($('<label>', {class: 'table-multiselect-option table-multiselect-select-all'})
                .append($('<input>', {type: 'checkbox', class: 'table-multiselect-all'}), $('<span>', {text: 'Select all'})));
            $options.each(function() {
                $menu.append($('<label>', {class: 'table-multiselect-option'})
                    .append($('<input>', {type: 'checkbox', class: 'table-multiselect-item', value: this.value}), $('<span>').text($(this).text().trim())));
            });
        } else {
            $menu.append($('<div>', {class: 'table-multiselect-empty', text: 'No tables available'}));
        }

        $widget.append($toggle, $menu);
        $select.after($widget);
        $toggle.on('click', function() {
            const open = !$widget.hasClass('is-open');
            $('.table-multiselect').not($widget).removeClass('is-open')
                .find('.table-multiselect-toggle').attr('aria-expanded', 'false');
            $widget.toggleClass('is-open', open);
            $toggle.attr('aria-expanded', open ? 'true' : 'false');
        });
        $widget.on('change', '.table-multiselect-all', function() {
            const values = this.checked ? $widget.find('.table-multiselect-item').map(function() { return this.value; }).get() : [];
            $select.val(values).trigger('change');
        });
        $widget.on('change', '.table-multiselect-item', function() {
            const values = $widget.find('.table-multiselect-item:checked').map(function() { return this.value; }).get();
            $select.val(values).trigger('change');
        });
        $select.off('change.salesTables').on('change.salesTables', function() {
            syncSalesTableMultiSelect($select, $widget);
        });
        syncSalesTableMultiSelect($select, $widget);
    }

    $('#property, #type, #disposition')
        .off('.salesLeadDynamic')
        .on('change.salesLeadDynamic select2:select.salesLeadDynamic select2:clear.salesLeadDynamic', scheduleSalesDynamicRefresh);

    $(observeSalesDynamicControls);

    $(document)
        .off('change.salesTimeSlots', '#slot_type_id')
        .on('change.salesTimeSlots', '#slot_type_id', function() {
            const value = $(this).val();
            if (value) loadSalesTimeSlots(value);
            else $('#time_slot_id').html('<option value="">Select Time Slot</option>').trigger('change.select2');
        })
        .off('change.salesTableCategories', '#restaurant_id')
        .on('change.salesTableCategories', '#restaurant_id', function() {
            const value = $(this).val();
            if (value) loadSalesTableCategories(value);
            else $('#table_category_id').html('<option value="">Select Category</option>').trigger('change.select2');
        })
        .off('change.salesTables', '#table_category_id')
        .on('change.salesTables', '#table_category_id', function() {
            const restaurantId = $('#restaurant_id').val();
            const categoryId = $(this).val();
            if (restaurantId && categoryId) {
                loadSalesTables(restaurantId, categoryId);
            } else {
                $('#table_id').empty();
                initializeSalesTableMultiSelect();
            }
        });

    $(document).on('click.salesTables', function(event) {
        if (!$(event.target).closest('.table-multiselect').length) {
            $('.table-multiselect').removeClass('is-open').find('.table-multiselect-toggle').attr('aria-expanded', 'false');
        }
    });

    $(document).on('input.salesPax', '#dynamicFields input[name="adults"], #dynamicFields input[name="kids"]', function() {
        const adults = parseInt($('#dynamicFields input[name="adults"]').val(), 10) || 0;
        const kids = parseInt($('#dynamicFields input[name="kids"]').val(), 10) || 0;
        $('#dynamicFields input[name="pax"]').val(adults + kids);
    });

    $(document).on('input.salesRevenue', '#dynamicFields .revenue-field', function() {
        const total = (parseFloat($('#revenue_room').val()) || 0) +
            (parseFloat($('#revenue_fnb').val()) || 0) +
            (parseFloat($('#revenue_other').val()) || 0);
        $('#amount').val(total.toFixed(2));
    });

    $(document).on('change.salesRoomRequired', '#sales_is_room_required', function() {
        const required = this.checked;
        $('.sales-room-required-fields').toggle(required);
        $('.sales-room-required-fields input').prop('required', required);
        if (!required) {
            $('.sales-room-required-fields input').val('').removeClass('is-invalid').removeAttr('aria-invalid');
            $('#checkin_date_error, #checkout_date_error, #number_of_rooms_error').text('');
        }
    });

    $(document).on('change.salesRoomDates', '#checkin_date', function() {
        $('#checkout_date').attr('min', $(this).val() || new Date().toISOString().split('T')[0]);
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
        salesDynamicRequest({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: {booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: tableIds, slot_type_id: slotTypeId},
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
        $('#sales_reserve_booking_date').attr('min', salesReservationToday()).val($('#restaurant_booking_date').val() || salesReservationToday());
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
        salesDynamicRequest({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: {booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: tableIds, slot_type_id: slotTypeId},
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
        const department = normalizeSalesDepartment($('#leadDepartment').val());

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
                const numberOfRooms = value('number_of_rooms');
                const today = new Date().toISOString().split('T')[0];
                if (!checkinDate) errors.checkin_date = 'Check-in date is required.';
                else if (checkinDate < today) errors.checkin_date = 'Check-in date cannot be in the past.';
                if (!checkoutDate) errors.checkout_date = 'Check-out date is required.';
                else if (checkinDate && checkoutDate < checkinDate) errors.checkout_date = 'Check-out date must be the same as or after check-in date.';
                if (!/^[1-9][0-9]*$/.test(numberOfRooms)) {
                    errors.number_of_rooms = numberOfRooms
                        ? 'Number of rooms must be a positive whole number.'
                        : 'Number of rooms is required.';
                }
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

        $.each(['kms_run', 'rate_per_km', 'parking_charges', 'lunch'], function(_, field) {
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

        if (!validateSalesVisitForm()) {
            return false;
        }

        $("#lead_status").prop('disabled', false);

        /* ================== BASIC FIELDS ================== */

        let userChannel = 'Sales Visit';
        let property = $('select[name="property"]').val();
        let department = $('select[name="type"]').val();
        let lead_status = $('select[name="lead_status"]').val();
        let lead_type = $('select[name="lead_type"]').val();

        let query = $('#discussion_summary').val();

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
        let total_amount = $('#total_amount').val();
        let visit_latitude = $('#visit_latitude').val();
        let visit_longitude = $('#visit_longitude').val();
        let visit_location_details = $('#visit_location_details').val();

        /* ================== BASIC VALIDATION ================== */
        if (userChannel && property && department && report_date && follow_up_1_date && follow_up_2_date && visit_type && visit_mode && company_id && person_met && query) {

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
                        // Unchecked optional controls are intentionally omitted.
                    } else if (name === 'table_id[]') {
                        // Appended separately so every selected table is submitted.
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
            formData.append('total_amount', total_amount);
            const attachmentFile = window.salesVisitCapturedImage;
            if (attachmentFile) {
                formData.append('visit_attachment', attachmentFile);
            }
            formData.append('visit_latitude', visit_latitude);
            formData.append('visit_longitude', visit_longitude);
            formData.append('visit_location_details', visit_location_details);
            appendCsrf(formData);

            /* ================== AJAX ================== */
            $.ajax({
                url: '<?php echo base_url("superAdmin/SalesVisits/insert"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);

                    if (response.duplicate) {
                        alert('Failed to create lead: ' + response.message);
                        return;
                    }

                    if (response.status) {
                        window.location.href = '<?php echo base_url("sales-visits-history"); ?>';
                    } else {
                        alert('Failed to create lead: ' + response.message);
                    }
                },
                error: function() {
                    toastr.error('Unable to save the sales visit. Please try again.');
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('Submit');
                    $('#lead_status').prop('disabled', true);
                }
            });

        } else {
            validateSalesVisitForm();
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

<script>
function loadCompanyContacts(companyId, selectedPerson = '') {
    const company_id = companyId || '';

    const $personSelect = $('#person_met');

    $personSelect
        .prop('disabled', true)
        .empty()
        .append(new Option('Loading persons...', ''))
        .trigger('change.select2');

    if (company_id !== '') {

        $.ajax({

            url: "<?= base_url('superAdmin/SalesVisits/get_company_contacts') ?>",
            type: "POST",
            data: csrfData({
                company_id: company_id,
                selected_contact_id: selectedPerson
            }),
            dataType: "json",

            success: function (res) {

                refreshCsrf(res);

                $personSelect.empty().append(new Option('Select Person', ''));

                if (res.status === 'success') {

                    $.each(res.data, function(i,row){
                        const fullName = [row.first_name, row.last_name]
                            .filter(Boolean)
                            .join(' ');
                        const label = row.mobile_number
                            ? `${fullName} (${row.mobile_number})`
                            : fullName;

                        $personSelect.append(new Option(
                            label,
                            row.contact_id,
                            false,
                            row.contact_id === selectedPerson
                        ));
                    });

                } else {
                    $personSelect
                        .empty()
                        .append(new Option('No active persons found', ''));
                }

                $personSelect.prop('disabled', false).trigger('change.select2');

            },
            error: function (xhr) {
                $personSelect
                    .empty()
                    .append(new Option('Unable to load persons', ''))
                    .prop('disabled', false)
                    .trigger('change.select2');
                console.error('Unable to load company contacts:', xhr.responseText);
            }

        });

    } else {

        $personSelect
            .empty()
            .append(new Option('Select Person', ''))
            .prop('disabled', true)
            .trigger('change.select2');

    }

}

$(document).ready(function () {
    $(document)
        .off('change.salesVisitContacts', '#company_id')
        .on('change.salesVisitContacts', '#company_id', function () {
            loadCompanyContacts($(this).val());
        });

    if ($('#company_id').val()) {
        loadCompanyContacts($('#company_id').val());
    }
});



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

        initializeSalesVisitSelect2(tbody);
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

            // Travel calculation
            let travelAmount = kmsRun * ratePerKm;

            // Final total
            let totalAmount = travelAmount + parkingCharges + lunch;

            // Set total with 2 decimal points
            $('#total_amount').val(totalAmount.toFixed(2));
        }

        // Trigger calculation on change / keyup
        $('#kms_run, #rate_per_km, #parking_charges, #lunch')
            .on('keyup change', function() {
                calculateTotalAmount();
            });

    });
</script>
