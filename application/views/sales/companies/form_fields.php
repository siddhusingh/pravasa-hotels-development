<div class="row">
    <div class="col-md-4 mb-3">
        <label>Company Group <span class="required-asterisk">*</span></label>
        <select class="form-select company-select2" name="company_group_id" id="company_company_group_id">
            <option value="">Select Company Group</option>
            <?php foreach ($company_groups as $group): ?>
                <option value="<?= encrypt_id($group->id) ?>">
                    <?= html_escape($group->company_group_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="field-error text-danger" id="company_company_group_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Company Name <span class="required-asterisk">*</span></label>
        <input type="text" class="form-control" name="company_name" id="company_company_name" maxlength="190">
        <div class="field-error text-danger" id="company_company_name_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Email <span class="required-asterisk">*</span></label>
        <input type="email" class="form-control" name="email" id="company_email" maxlength="190">
        <div class="field-error text-danger" id="company_email_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Secondary Email</label>
        <input type="email" class="form-control" name="secondary_email" id="company_secondary_email" maxlength="190">
        <div class="field-error text-danger" id="company_secondary_email_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Phone</label>
        <input type="text" class="form-control" name="phone_number" id="company_phone_number" maxlength="30">
    </div>

    <div class="col-md-4 mb-3">
        <label>Mobile <span class="required-asterisk">*</span></label>
        <input type="text" class="form-control" name="mobile_number" id="company_mobile_number" maxlength="30">
        <div class="field-error text-danger" id="company_mobile_number_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>GST Number</label>
        <input type="text" class="form-control" name="gst_number" id="company_gst_number" maxlength="50">
    </div>

    <div class="col-md-4 mb-3">
        <label>Country <span class="required-asterisk">*</span></label>
        <select class="form-select company-select2" name="country_id" id="company_country_id">
            <option value="">Select Country</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?= encrypt_id($country->country_id) ?>">
                    <?= html_escape($country->country_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="field-error text-danger" id="company_country_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>State <span class="required-asterisk">*</span></label>
        <select class="form-select company-select2" name="state_id" id="company_state_id">
            <option value="">Select State</option>
            <?php foreach ($states as $state): ?>
                <option
                    value="<?= encrypt_id($state->state_id) ?>"
                    data-country-id="<?= encrypt_id($state->country_id) ?>"
                >
                    <?= html_escape($state->state_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="field-error text-danger" id="company_state_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>City <span class="required-asterisk">*</span></label>
        <select class="form-select company-select2" name="city_id" id="company_city_id">
            <option value="">Select City</option>
            <?php foreach ($cities as $city): ?>
                <option
                    value="<?= encrypt_id($city->city_id) ?>"
                    data-country-id="<?= encrypt_id($city->country_id) ?>"
                    data-state-id="<?= encrypt_id($city->state_id) ?>"
                >
                    <?= html_escape($city->city_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="field-error text-danger" id="company_city_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Area <span class="required-asterisk">*</span></label>
        <select class="form-select company-select2" name="area_id" id="company_area_id">
            <option value="">Select Area</option>
            <?php foreach ($areas as $area): ?>
                <option
                    value="<?= encrypt_id($area->area_id) ?>"
                    data-state-id="<?= encrypt_id($area->state_id) ?>"
                >
                    <?= html_escape($area->area_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="field-error text-danger" id="company_area_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Pincode</label>
        <input type="text" class="form-control" name="pincode" id="company_pincode" maxlength="20">
    </div>

    <div class="col-md-12 mb-3">
        <label>Address <span class="required-asterisk">*</span></label>
        <textarea class="form-control" name="address" id="company_address"></textarea>
        <div class="field-error text-danger" id="company_address_error"></div>
    </div>

    <div class="d-none col-md-6 mb-3">
        <label>Deals In</label>
        <input type="text" class="form-control" name="deals_in" id="company_deals_in">
    </div>

    <div class="col-md-12 mb-3">
        <label>Details</label>
        <textarea class="form-control" name="details" id="company_details"></textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label>Creditibility</label>
        <select class="form-select company-select2" name="company_creditibility" id="company_creditibility">
            <option value="Credit Not Allowed">Credit Not Allowed</option>
            <option value="Credit Allowed">Credit Allowed</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Credit Form</label>
        <input
            type="file"
            class="form-control"
            name="credit_form_file"
            id="company_credit_form_file"
            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
        >
        <small class="text-muted">PDF, image, DOC or DOCX; maximum 5 MB.</small>
    </div>

    <div class="col-md-4 mb-3">
        <label>Status</label>
        <select class="form-select company-select2" name="status" id="company_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
</div>
