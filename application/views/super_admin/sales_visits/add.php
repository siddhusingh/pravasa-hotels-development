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
                                        <label for="report_date"><span class="field-label-icon fa fa-calendar" aria-hidden="true"></span>Report Date <span class="required-asterisk">*</span></label>
                                        <input type="date" id="report_date" name="report_date" class="form-control" required>
                                        <span class="text-danger small validation-message" id="report_date_error"></span>
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

                                    <!-- Remarks -->
                                    <div class="col-sm-12">
                                        <label>Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2"></textarea>
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

<script>
    toastr.options = {
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
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
            return $.ajax(options);
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
        const department = normalizeSalesDepartment($('#type option:selected').data('name'));
        const hotelId = $('#property option:selected').data('raw-id') || '';
        const departmentId = $('#type option:selected').data('raw-id') || '';
        const $container = $('#dynamicFields');
        const today = new Date().toISOString().split('T')[0];

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
                    <div class="col-md-3 mb-3"><label>Booking Date</label><input type="date" name="booking_date" class="form-control" value="${today}"></div>
                    <div class="col-md-3 mb-3"><label>No. of Pax</label><input type="number" name="pax" class="form-control" min="1"></div>
                    <div class="col-md-3 mb-3"><label>Restaurant <span class="text-danger">*</span></label><select name="restaurant_id" id="restaurant_id" class="form-select"><option value="">Select Restaurant</option></select><div class="text-danger error-label" id="restaurant_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Slot Type <span class="text-danger">*</span></label><select name="slot_type_id" id="slot_type_id" class="form-select"><option value="">Select Slot</option></select><div class="text-danger error-label" id="slot_type_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Time Slot <span class="text-danger">*</span></label><select name="time_slot_id" id="time_slot_id" class="form-select"><option value="">Select Time Slot</option></select><div class="text-danger error-label" id="time_slot_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Arrival Time</label><input type="time" name="arrival_time" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Table Category <span class="text-danger">*</span></label><select name="table_category_id" id="table_category_id" class="form-select"><option value="">Select Category</option></select><div class="text-danger error-label" id="table_category_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Tables <span class="text-danger">*</span></label><select name="table_id[]" id="table_id" class="form-control" multiple></select><div class="text-danger error-label" id="table_id_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Table Reservation Status <span class="text-danger">*</span></label><select name="table_reservation_status" id="table_reservation_status" class="form-select"><option value="">Select Status</option><option value="Reserved">Reserved</option><option value="Seated">Seated</option><option value="Completed">Completed</option><option value="Cancelled">Cancelled</option></select><div class="text-danger error-label" id="table_reservation_status_error"></div></div>
                    <div class="col-md-3 mb-3"><label>Expected Revenue</label><input type="number" name="amount" class="form-control" step="0.01"></div>
                    <div class="col-md-6 mb-3"><label>Special Occasion (if any)</label><input type="text" name="special_occasion" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Special Request</label><textarea name="special_request" class="form-control"></textarea></div>`);
                loadSalesRestaurants(hotelId);
                loadSalesSlotTypes();
                initializeSalesTableMultiSelect();
            } else if (department === 'banquet') {
                $container.append(`
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

    function salesVisitField(field) {
        return $('#' + field);
    }

    function showSalesVisitFieldError(field, message) {
        const $field = salesVisitField(field);
        let $error = $('#' + field + '_error');

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
            return $.trim(String(salesVisitField(field).val() || ''));
        };

        if (!value('property')) errors.property = 'Please select a hotel.';
        if (!value('type')) errors.type = 'Please select a department.';
        if (!value('report_date')) errors.report_date = 'Please select a report date.';
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
        let remarks = $('textarea[name="remarks"]').val();

        let leadDepartment = $('#leadDepartment').val();
        let disposition = $('#disposition').val() || '';

        /* ================== SALES VISIT FIELDS ================== */
        let report_date = $('#report_date').val();
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

        /* ================== BASIC VALIDATION ================== */
        if (userChannel && property && department && report_date && company_id && person_met && query) {

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
            formData.append('remarks', remarks);
            formData.append('lead_type', lead_type);
            formData.append('leadDepartment', leadDepartment);
            formData.append('disposition', disposition);

            /* ========== SALES VISIT DATA ========== */
            formData.append('report_date', report_date);
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
