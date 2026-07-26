<?php $prefix = $field_prefix ?? 'contact_'; ?>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>company_id">
            Company Name <span class="required-asterisk">*</span>
        </label>
        <select class="form-control contact-select2" name="company_id" id="<?= $prefix ?>company_id">
            <option value="">Select Company</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= htmlspecialchars(encrypt_id($company->company_id), ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="text-danger field-error" id="<?= $prefix ?>company_id_error"></span>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label" for="<?= $prefix ?>title">
            Title <span class="required-asterisk">*</span>
        </label>
        <select class="form-select" name="title" id="<?= $prefix ?>title">
            <option value="">Select</option>
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Ms">Ms</option>
            <option value="Dr">Dr</option>
        </select>
        <span class="text-danger field-error" id="<?= $prefix ?>title_error"></span>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label" for="<?= $prefix ?>first_name">
            First Name <span class="required-asterisk">*</span>
        </label>
        <input type="text" class="form-control" name="first_name" id="<?= $prefix ?>first_name" maxlength="100">
        <span class="text-danger field-error" id="<?= $prefix ?>first_name_error"></span>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label" for="<?= $prefix ?>last_name">
            Last Name <span class="required-asterisk">*</span>
        </label>
        <input type="text" class="form-control" name="last_name" id="<?= $prefix ?>last_name" maxlength="100">
        <span class="text-danger field-error" id="<?= $prefix ?>last_name_error"></span>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>designation_id">Designation</label>
        <select class="form-control contact-select2" name="designation_id" id="<?= $prefix ?>designation_id">
            <option value="">Select Designation</option>
            <?php foreach ($designations as $designation): ?>
                <option value="<?= htmlspecialchars(encrypt_id($designation->id), ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($designation->designation_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>grade">Grade</label>
        <input type="text" class="form-control" name="grade" id="<?= $prefix ?>grade" maxlength="100">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>email">
            Email <span class="required-asterisk">*</span>
        </label>
        <input type="email" class="form-control" name="email" id="<?= $prefix ?>email" maxlength="190">
        <span class="text-danger field-error" id="<?= $prefix ?>email_error"></span>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>phone_number">Phone</label>
        <input type="text" class="form-control" name="phone_number" id="<?= $prefix ?>phone_number" maxlength="30">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>mobile_number">
            Mobile <span class="required-asterisk">*</span>
        </label>
        <input type="text" class="form-control" name="mobile_number" id="<?= $prefix ?>mobile_number" maxlength="30">
        <span class="text-danger field-error" id="<?= $prefix ?>mobile_number_error"></span>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>country_id">Country</label>
        <select class="form-control contact-select2" name="country_id" id="<?= $prefix ?>country_id">
            <option value="">Select Country</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars(encrypt_id($country->country_id), ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($country->country_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>state_id">State</label>
        <select class="form-control contact-select2" name="state_id" id="<?= $prefix ?>state_id">
            <option value="">Select State</option>
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars(encrypt_id($state->state_id), ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($state->state_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="text-danger field-error" id="<?= $prefix ?>state_id_error"></span>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>city">City</label>
        <select class="form-control contact-select2" name="city" id="<?= $prefix ?>city">
            <option value="">Select City</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= htmlspecialchars(encrypt_id($city->city_id), ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($city->city_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="text-danger field-error" id="<?= $prefix ?>city_error"></span>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>pincode">Pincode</label>
        <input type="text" class="form-control" name="pincode" id="<?= $prefix ?>pincode" maxlength="20">
    </div>

    <div class="col-md-8 mb-3">
        <label class="form-label" for="<?= $prefix ?>address">Address</label>
        <textarea class="form-control" name="address" id="<?= $prefix ?>address" rows="2"></textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>date_of_birth">Date of Birth</label>
        <input type="date" class="form-control allow-past-date" name="date_of_birth" id="<?= $prefix ?>date_of_birth">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>date_of_anniversary">Date of Anniversary</label>
        <input type="date" class="form-control allow-past-date" name="date_of_anniversary" id="<?= $prefix ?>date_of_anniversary">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="<?= $prefix ?>status">Status</label>
        <select class="form-select" name="status" id="<?= $prefix ?>status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
    </div>
</div>
