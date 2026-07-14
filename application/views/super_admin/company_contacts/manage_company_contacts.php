<style>
    .error-label {
        font-size: 0.85rem;
        margin-top: 4px;
        display: none;
    }

    .required-asterisk {
        color: #dc3545;
        font-weight: 600;
    }

    #addContactModal .select2-container,
    #editContactModal .select2-container {
        width: 100% !important;
    }

    #addContactModal .select2-container .select2-selection--single,
    #editContactModal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #addContactModal .select2-selection--single .select2-selection__rendered,
    #editContactModal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 13px !important;
        padding-right: 35px !important;
    }

    #addContactModal .select2-selection--single .select2-selection__arrow,
    #editContactModal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .company-contact-select2-dropdown .select2-search__field {
        height: 34px !important;
        min-height: 34px !important;
        padding: 5px 9px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">

        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-address-book"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Company Contacts</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company & Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Company Contact Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Contact List</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="openAddContact">
                                    Add Contact +
                                </button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>Full Name</th>
                                            <th>Designation</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Phone</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Status</th>
                                            <th width="120">Action</th>
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

<!-- ================= ADD CONTACT MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="addContactModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="addContactForm">
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
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3">
                    <div class="row">

                        <!-- Company -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company Name <span class="required-asterisk">*</span></label>
                            <select class="form-control" name="company_id" id="company_id">
                                <option value="" selected disabled>Select Company</option>
                                <?php foreach ($companies as $company) { ?>
                                    <option value="<?= encrypt_id($company->company_id); ?>">
                                        <?= htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger validation" id="company_id_error"></span>
                        </div>

                        <!-- Title -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Title <span class="required-asterisk">*</span></label>
                            <select class="form-select" name="title" id="add_title">
                                <option value="">Select</option>
                                <option>Mr</option>
                                <option>Mrs</option>
                                <option>Ms</option>
                                <option>Dr</option>
                            </select>
                            <span class="text-danger" id="add_title_error"></span>
                        </div>

                        <!-- First Name -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">First Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="add_first_name">
                            <span class="text-danger" id="add_first_name_error"></span>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Last Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="add_last_name">
                            <span class="text-danger" id="add_last_name_error"></span>
                        </div>

                        <!-- Designation -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation</label>
                            <select class="form-control" name="designation_id">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $designation) { ?>
                                    <option value="<?= encrypt_id($designation->id); ?>">
                                        <?= htmlspecialchars($designation->designation_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Grade -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input type="email" class="form-control" name="email" id="add_email">
                            <span class="text-danger" id="add_email_error"></span>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone_number">
                        </div>

                        <!-- Mobile -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mobile <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="mobile_number" id="add_mobile_number">
                            <span class="text-danger" id="add_mobile_number_error"></span>
                        </div>

                        <!-- Country -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country</label>
                            <select class="form-control" name="country_id" id="country_id">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $each) { ?>
                                    <option value="<?= encrypt_id($each->country_id); ?>">
                                        <?= htmlspecialchars($each->country_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- State -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <select class="form-control" name="state_id" id="state_id">
                                <option value="">Select State</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?= encrypt_id($state->state_id); ?>">
                                        <?= htmlspecialchars($state->state_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger" id="state_id_error"></span>
                        </div>

                        <!-- City -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <select class="form-control" name="city" id="city">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city) { ?>
                                    <option value="<?= encrypt_id($city->city_id); ?>">
                                        <?= htmlspecialchars($city->city_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger" id="city_error"></span>
                        </div>

                        <!-- Pincode -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" class="form-control" name="pincode">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Anniversary</label>
                            <input type="date" class="form-control" name="date_of_anniversary">
                        </div>

                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>

            </div>
        </form>
    </div>
</div>


<!-- ================= EDIT CONTACT MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="editContactModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="editContactForm">
            <div class="modal-content">

                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-address-book"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h5>Edit Contact</h5>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Fill in the details to update a company contact.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3">
                    <div class="row">

                        <!-- Hidden ID -->
                        <input type="hidden" name="contact_id" id="edit_contact_id">

                        <!-- Company -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company Name <span class="required-asterisk">*</span></label>
                            <select class="form-control" name="company_id" id="edit_company_id">
                                <option value="" disabled>Select Company</option>
                                <?php foreach ($companies as $company) { ?>
                                    <option value="<?= encrypt_id($company->company_id); ?>">
                                        <?= htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger" id="edit_company_id_error"></span>
                        </div>

                        <!-- Title -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Title <span class="required-asterisk">*</span></label>
                            <select class="form-select" name="title" id="edit_title">
                                <option value="">Select</option>
                                <option>Mr</option>
                                <option>Mrs</option>
                                <option>Ms</option>
                                <option>Dr</option>
                            </select>
                            <span class="text-danger" id="edit_title_error"></span>
                        </div>

                        <!-- First Name -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">First Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="edit_first_name">
                            <span class="text-danger" id="edit_first_name_error"></span>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Last Name <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="edit_last_name">
                            <span class="text-danger" id="edit_last_name_error"></span>
                        </div>

                        <!-- Designation -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation</label>
                            <select class="form-control" name="designation_id" id="edit_designation_id">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $designation) { ?>
                                    <option value="<?= encrypt_id($designation->id); ?>">
                                        <?= htmlspecialchars($designation->designation_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Grade -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade" id="edit_grade">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input type="email" class="form-control" name="email" id="edit_email">
                            <span class="text-danger" id="edit_email_error"></span>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone_number" id="edit_phone_number">
                        </div>

                        <!-- Mobile -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mobile <span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" name="mobile_number" id="edit_mobile_number">
                            <span class="text-danger" id="edit_mobile_number_error"></span>
                        </div>

                        <!-- Country -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country</label>
                            <select class="form-control" name="country_id" id="edit_country_id">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $each) { ?>
                                    <option value="<?= encrypt_id($each->country_id); ?>">
                                        <?= htmlspecialchars($each->country_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- State -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <select class="form-control" name="state_id" id="edit_state_id">
                                <option value="">Select State</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?= encrypt_id($state->state_id); ?>">
                                        <?= htmlspecialchars($state->state_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger" id="edit_state_id_error"></span>
                        </div>

                        <!-- City -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <select class="form-control" name="city" id="edit_city">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city) { ?>
                                    <option value="<?= encrypt_id($city->city_id); ?>">
                                        <?= htmlspecialchars($city->city_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger" id="edit_city_error"></span>
                        </div>

                        <!-- Pincode -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" class="form-control" name="pincode" id="edit_pincode">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" id="edit_date_of_birth">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Anniversary</label>
                            <input type="date" class="form-control" name="date_of_anniversary" id="edit_date_of_anniversary">
                        </div>

                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>

            </div>
        </form>
    </div>
</div>





<!-- ================= JS ================= -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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



    <?php if ($this->session->flashdata('travel_mode_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('travel_mode_success_msg'); ?>')

    <?php $this->session->set_flashdata('travel_mode_success_msg', '');
    } ?>
</script>

<script>
    var contactTable;

    function setEncryptedSelectValue(selector, value, label) {
        if (!value) {
            $(selector).val('').trigger('change');
            return;
        }

        var optionExists = $(selector).find('option').filter(function() {
            return this.value === value;
        }).length > 0;

        if (!optionExists) {
            var option = new Option(label || 'Selected', value, true, true);
            option.setAttribute('data-transient', 'true');
            $(selector).append(option);
        }

        $(selector).val(value).trigger('change');
    }

    function initCompanyContactSelect2() {
        if (!$.fn.select2) {
            return;
        }

        var groups = [
            {
                modal: '#addContactModal',
                selectors: '#company_id, #addContactModal select[name="designation_id"], #country_id, #state_id, #city'
            },
            {
                modal: '#editContactModal',
                selectors: '#edit_company_id, #edit_designation_id, #edit_country_id, #edit_state_id, #edit_city'
            }
        ];

        $.each(groups, function(index, group) {
            $(group.selectors).each(function() {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $select.select2({
                    width: '100%',
                    placeholder: $select.find('option:first').text(),
                    allowClear: false,
                    dropdownParent: $(group.modal),
                    dropdownCssClass: 'company-contact-select2-dropdown'
                });
            });
        });
    }

    contactTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 11, orderable: false }
        ],
        ajax: {
            url: "<?= base_url('get-company-contacts-table') ?>",
            type: "POST",
            data: function(d) {
                d[window.CSRF.name] = window.CSRF.hash;
            },
            drawCallback: function(settings) {
                if (settings.json && settings.json.csrfHash) {
                    window.CSRF.hash = settings.json.csrfHash;
                }
            },
            dataSrc: function(json) {
                if (json.csrfHash) {
                    window.CSRF.hash = json.csrfHash;
                }
                return json.data;
            }
        }
    });

    $(document).ready(function() {
        initCompanyContactSelect2();

        /* ================= OPEN ADD MODAL ================= */
        $(document).on('click', '#openAddContact', function() {
            $('#addContactForm')[0].reset();
            $('#addContactForm select').trigger('change');
            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');
            $('#addContactModal').modal('show');
        });

        /* ================= ADD CONTACT ================= */
        $('#addContactForm').on('submit', function(e) {
            e.preventDefault();

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');

            let hasError = false;

            let requiredFields = [
                'company_id',
                'add_title',
                'add_first_name',
                'add_last_name',
                'add_email',
                'add_mobile_number'
            ];

            requiredFields.forEach(function(field) {
                if (($('#' + field).val() || '').trim() === '') {
                    $('#' + field + '_error').text('This field is required').show();
                    $('#' + field).addClass('is-invalid');
                    hasError = true;
                }
            });

            if (hasError) return false;

            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "<?= base_url('company-contact-save') ?>",
                type: "POST",
                data: $(this).serialize() + '&' + encodeURIComponent(window.CSRF.name) + '=' + encodeURIComponent(window.CSRF.hash),
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {
                        toastr.success(response.message || 'Contact added successfully');
                        $('#addContactModal').modal('hide');
                        $('#addContactForm')[0].reset();
                        $('#addContactForm select').trigger('change');
                        contactTable.draw(false);
                    } else if (response.errors) {
                        $.each(response.errors, function(key, val) {
                            $('#' + key + '_error').text(val).show();
                            $('#' + key).addClass('is-invalid');
                        });
                    } else {
                        toastr.error(response.message || 'Something went wrong');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Save');
                }
            });
        });

    });


    /* ================= OPEN EDIT MODAL ================= */
    $(document).ready(function() {

        $(document).on('click', '.edit-contact', function() {

            let contact_id = $(this).data('record_id');

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');
            $('#editContactForm select').each(function() {
                $(this).find('option[data-transient="true"]').remove();
                $(this).val('').trigger('change');
            });

            $.ajax({
                url: "<?= base_url('company-contact-details') ?>",
                type: "POST",
                data: {
                    contact_id: contact_id,
                    [window.CSRF.name]: window.CSRF.hash
                },
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {

                        let c = response.data;

                        // Hidden ID
                        $('#edit_contact_id').val(c.contact_id);

                        // Company
                        setEncryptedSelectValue('#edit_company_id', c.company_id, c.company_name);

                        // Personal Info
                        $('#edit_title').val(c.title);
                        $('#edit_first_name').val(c.first_name);
                        $('#edit_last_name').val(c.last_name);

                        // Professional Info
                        setEncryptedSelectValue('#edit_designation_id', c.designation, c.designation_name);
                        $('#edit_grade').val(c.grade);

                        // Contact Info
                        $('#edit_email').val(c.email);
                        $('#edit_phone_number').val(c.phone_number);
                        $('#edit_mobile_number').val(c.mobile_number);

                        // Location
                        setEncryptedSelectValue('#edit_country_id', c.country, c.country_name);
                        setEncryptedSelectValue('#edit_state_id', c.state, c.state_name);
                        setEncryptedSelectValue('#edit_city', c.city, c.city_name);

                        // Address
                        $('#edit_address').val(c.address);
                        $('#edit_pincode').val(c.pincode);

                        // Dates
                        $('#edit_date_of_birth').val(c.date_of_birth);
                        $('#edit_date_of_anniversary').val(c.date_of_anniversary);

                        // Status
                        $('#edit_status').val(c.status);


                        $('#editContactModal').modal('show');

                        if ((c.unavailable_dependencies || []).length > 0) {
                            toastr.warning(
                                'Please select an active replacement for: ' +
                                c.unavailable_dependencies.join(', ') + '.'
                            );
                        }

                    } else {
                        toastr.error(response.message || 'Failed to fetch contact details');
                    }
                },
                error: function() {
                    toastr.error('Server error while fetching data');
                }
            });

        });


        /* ================= UPDATE CONTACT ================= */
        $('#editContactForm').on('submit', function(e) {
            e.preventDefault();

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');

            let hasError = false;

            let requiredFields = [
                'edit_title',
                'edit_first_name',
                'edit_last_name',
                'edit_email',
                'edit_mobile_number'
            ];

            requiredFields.forEach(function(field) {
                if ($('#' + field).val().trim() === '') {
                    $('#' + field + '_error').text('This field is required').show();
                    $('#' + field).addClass('is-invalid');
                    hasError = true;
                }
            });

            if (hasError) return false;

            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "<?= base_url('company-contact-save') ?>",
                type: "POST",
                data: $(this).serialize() + '&' + encodeURIComponent(window.CSRF.name) + '=' + encodeURIComponent(window.CSRF.hash),
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {
                        toastr.success(response.message || 'Contact updated successfully');
                        $('#editContactModal').modal('hide');
                        contactTable.draw(false);
                    } else if (response.errors) {
                        $.each(response.errors, function(key, val) {
                            $('#edit_' + key + '_error').text(val).show();
                            $('#edit_' + key).addClass('is-invalid');
                        });
                    } else {
                        toastr.error(response.message || 'Update failed');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Update');
                }
            });
        });

    });


    /* ================= DELETE CONTACT ================= */
    $(document).on('click', '.delete-contact', function() {

        let contact_id = $(this).data('record_id');

        Swal.fire({
            title: "Are you sure?",
            text: 'This contact will be removed from the active company contact list.',
            icon: "question",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes, delete it",
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('company-contact-delete') ?>",
                    type: "POST",
                    data: {
                        id: contact_id,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.csrfHash) {
                            window.CSRF.hash = response.csrfHash;
                        }
                        if (response.status) {
                            toastr.success(response.message || 'Contact deleted successfully');
                            contactTable.draw(false);
                        } else {
                            toastr.error(response.message || 'Delete failed');
                        }
                    },
                    error: function() {
                        toastr.error('Server error! Please try again');
                    }
                });
            }
        });
    });

    function refreshContactTable() {
        $('#server-side-data-table').DataTable().draw(false);
    }
</script>
