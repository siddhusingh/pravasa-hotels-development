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
</style>

<div class="content-wrapper">
    <div class="container-full">

        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-building"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Companies</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company & Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Company Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header d-flex justify-content-between">
                    <h4 class="box-title">Company List</h4>
                    <button class="btn btn-primary btn-sm" id="open-add-company">
                        Add Company +
                    </button>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="companies-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Company Group</th>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Area User</th>
                                    <th>Mobile</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>




<!-- ================= ADD COMPANY MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="addCompanyModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="addCompanyForm" enctype="multipart/form-data">
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
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3 row">

                    <div class="col-md-4 mb-3">
                        <label>Company Group <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="company_group_id" id="add_company_group_id">
                            <option value="">Select</option>
                            <?php foreach ($company_groups as $g) { ?>
                                <option value="<?= encrypt_id($g->id) ?>"><?= $g->company_group_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="add_company_group_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Company Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" name="company_name" id="add_company_name">
                        <div class="error-label text-danger" id="add_company_name_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Email <span class="required-asterisk">*</span></label>
                        <input type="email" class="form-control" name="email" id="add_email">
                        <div class="error-label text-danger" id="add_email_error"></div>
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
                        <input type="text" class="form-control" name="mobile_number" id="add_mobile_number">
                        <div class="error-label text-danger" id="add_mobile_number_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>GST Number</label>
                        <input type="text" class="form-control" name="gst_number">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Country <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="country_id" id="add_country_id">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c) { ?>
                                <option value="<?= encrypt_id($c->country_id) ?>"><?= $c->country_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="add_country_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>State <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="state_id" id="add_state_id">
                            <option value="">Select State</option>
                            <?php foreach ($states as $s) { ?>
                                <option value="<?= encrypt_id($s->state_id) ?>"><?= $s->state_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="add_state_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>City <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="city_id" id="add_city_id">
                            <option value="">Select City</option>
                            <?php foreach ($cities as $c) { ?>
                                <option value="<?= encrypt_id($c->city_id) ?>"><?= $c->city_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="add_city_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Area <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="area_id" id="add_area_id">
                            <option value="">Select</option>
                            <?php foreach ($areas as $a) { ?>
                                <option value="<?= encrypt_id($a->area_id) ?>"><?= $a->area_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="add_area_id_error"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pincode">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Address <span class="required-asterisk">*</span></label>
                        <textarea class="form-control" name="address" id="add_address"></textarea>
                        <div class="error-label text-danger" id="add_address_error"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Deals In</label>
                        <input type="text" class="form-control" name="deals_in">
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
                        <select class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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

<!-- ================= EDIT COMPANY MODAL ================= -->
<div class="modal modal-lg new_modal_design" id="editCompanyModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="editCompanyForm" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-building"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h5>Edit Company</h5>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Fill in the details to update a company.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3 row">
                    <input type="hidden" name="company_id" id="edit_company_id">

                    <!-- Company Group -->
                    <div class="col-md-4 mb-3">
                        <label>Company Group <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="company_group_id" id="edit_company_group_id">
                            <option value="">Select</option>
                            <?php foreach ($company_groups as $g) { ?>
                                <option value="<?= encrypt_id($g->id) ?>">
                                    <?= $g->company_group_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="edit_company_group_id_error"></div>
                    </div>

                    <!-- Company Name -->
                    <div class="col-md-4 mb-3">
                        <label>Company Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" name="company_name" id="edit_company_name">
                        <div class="error-label text-danger" id="edit_company_name_error"></div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-3">
                        <label>Email <span class="required-asterisk">*</span></label>
                        <input type="email" class="form-control" name="email" id="edit_email">
                        <div class="error-label text-danger" id="edit_email_error"></div>
                    </div>

                    <!-- Secondary Email -->
                    <div class="col-md-4 mb-3">
                        <label>Secondary Email</label>
                        <input type="email" class="form-control" name="secondary_email" id="edit_secondary_email">
                    </div>

                    <!-- Phone -->
                    <div class="col-md-4 mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone_number" id="edit_phone_number">
                    </div>

                    <!-- Mobile -->
                    <div class="col-md-4 mb-3">
                        <label>Mobile <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" name="mobile_number" id="edit_mobile_number">
                        <div class="error-label text-danger" id="edit_mobile_number_error"></div>
                    </div>

                    <!-- GST -->
                    <div class="col-md-4 mb-3">
                        <label>GST Number</label>
                        <input type="text" class="form-control" name="gst_number" id="edit_gst_number">
                    </div>

                    <!-- Country -->
                    <div class="col-md-4 mb-3">
                        <label>Country <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="country_id" id="edit_country_id">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c) { ?>
                                <option value="<?= encrypt_id($c->country_id) ?>">
                                    <?= $c->country_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="edit_country_id_error"></div>
                    </div>

                    <!-- State -->
                    <div class="col-md-4 mb-3">
                        <label>State <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="state_id" id="edit_state_id">
                            <option value="">Select State</option>
                            <?php foreach ($states as $s) { ?>
                                <option value="<?= encrypt_id($s->state_id) ?>">
                                    <?= $s->state_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="edit_state_id_error"></div>
                    </div>

                    <!-- City -->
                    <div class="col-md-4 mb-3">
                        <label>City <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="city_id" id="edit_city_id">
                            <option value="">Select City</option>
                            <?php foreach ($cities as $c) { ?>
                                <option value="<?= encrypt_id($c->city_id) ?>">
                                    <?= $c->city_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="edit_city_id_error"></div>
                    </div>

                    <!-- Area -->
                    <div class="col-md-4 mb-3">
                        <label>Area <span class="required-asterisk">*</span></label>
                        <select class="form-select" name="area_id" id="edit_area_id">
                            <option value="">Select Area</option>
                            <?php foreach ($areas as $a) { ?>
                                <option value="<?= encrypt_id($a->area_id) ?>">
                                    <?= $a->area_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="error-label text-danger" id="edit_area_id_error"></div>
                    </div>

                    <!-- Pincode -->
                    <div class="col-md-4 mb-3">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pincode" id="edit_pincode">
                    </div>

                    <!-- Address -->
                    <div class="col-md-12 mb-3">
                        <label>Address <span class="required-asterisk">*</span></label>
                        <textarea class="form-control" name="address" id="edit_address"></textarea>
                        <div class="error-label text-danger" id="edit_address_error"></div>
                    </div>

                    <!-- Deals In -->
                    <div class="col-md-6 mb-3">
                        <label>Deals In</label>
                        <input type="text" class="form-control" name="deals_in" id="edit_deals_in">
                    </div>

                    <!-- Details -->
                    <div class="col-md-6 mb-3">
                        <label>Details</label>
                        <textarea class="form-control" name="details" id="edit_details"></textarea>
                    </div>

                    <!-- Creditibility -->
                    <div class="col-md-4 mb-3">
                        <label>Creditibility</label>
                        <select class="form-select" name="company_creditibility" id="edit_company_creditibility">
                            <option value="Credit Not Allowed">Credit Not Allowed</option>
                            <option value="Credit Allowed">Credit Allowed</option>
                        </select>
                    </div>

                    <!-- Credit Form -->
                    <div class="col-md-4 mb-3">
                        <label>Credit Form</label>
                        <input type="file" class="form-control" name="credit_form_file">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select class="form-select" name="status" id="edit_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
    var companyTable;

    function setEncryptedSelectValue(selector, value, label) {
        if (!value) {
            $(selector).val('').trigger('change');
            return;
        }

        if ($(selector + ' option[value="' + value + '"]').length === 0) {
            $(selector).append(new Option(label || 'Selected', value, true, true));
        }

        $(selector).val(value).trigger('change');
    }

    function ensureModuleDataTable(callback) {
        if ($.fn.DataTable) {
            callback();
            return;
        }

        $.getScript("<?= base_url('assets/assets/vendor_components/datatable/datatables.min.js') ?>", callback);
    }

    function initCompaniesModule() {
        if ($.fn.DataTable.isDataTable('#companies-data-table')) {
            return;
        }

        companyTable = $('#companies-data-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [
                { targets: 11, orderable: false }
            ],
            ajax: {
                url: "<?= base_url('get-companies-table') ?>",
                type: "POST",
                data: function(d) {
                    d[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function(json) {
                    if (json.csrfHash) {
                        window.CSRF.hash = json.csrfHash;
                    }
                    return json.data;
                }
            }
        });
    }

    $(window).on('load', function() {
        ensureModuleDataTable(function() {
            initCompaniesModule();
        });
    });

    $(document).ready(function() {
        /* ================= OPEN ADD MODAL ================= */
        $(document).on('click', '#open-add-company', function() {
            $('.error-label').text('').hide();
            $('#addCompanyForm')[0].reset();

            $('input, select, textarea').removeClass('is-invalid');
            $('#addCompanyModal').modal('show');
        });


        /* ================= ADD COMPANY ================= */
        $('#addCompanyForm').on('submit', function(e) {
            e.preventDefault();

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');

            let hasError = false;

            let requiredFields = [
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

            requiredFields.forEach(function(field) {
                if ($('#add_' + field).val() === '') {
                    $('#add_' + field + '_error').text('This field is required').show();
                    $('#add_' + field).addClass('is-invalid');
                    hasError = true;
                }
            });

            if (hasError) return false;

            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Saving...');

            let formData = new FormData(this);
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: "<?= base_url('company-save') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {
                        toastr.success(response.message || 'Company added successfully');
                        $('#addCompanyModal').modal('hide');
                        $('#addCompanyForm')[0].reset();
                        companyTable.draw(false);
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

        $(document).on('click', '.editCompany', function() {

            let company_id = $(this).data('id');

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');

            $.ajax({
                url: "<?= base_url('company-details') ?>",
                type: "POST",
                data: {
                    company_id: company_id,
                    [window.CSRF.name]: window.CSRF.hash
                },
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {

                        let c = response.data;

                        $('#edit_company_id').val(c.company_id);
                        setEncryptedSelectValue('#edit_company_group_id', c.company_group_id, c.company_group_name);
                        $('#edit_company_name').val(c.company_name);
                        $('#edit_email').val(c.email);
                        $('#edit_secondary_email').val(c.secondary_email);
                        $('#edit_phone_number').val(c.phone_number);
                        $('#edit_mobile_number').val(c.mobile_number);
                        $('#edit_gst_number').val(c.gst_number);
                        setEncryptedSelectValue('#edit_country_id', c.country_id, c.country_name);
                        setEncryptedSelectValue('#edit_state_id', c.state_id, c.state_name);
                        setEncryptedSelectValue('#edit_city_id', c.city_id, c.city_name);
                        setEncryptedSelectValue('#edit_area_id', c.area_id, c.area_name);
                        $('#edit_pincode').val(c.pincode);
                        $('#edit_address').val(c.address);
                        $('#edit_deals_in').val(c.deals_in);
                        $('#edit_details').val(c.details);
                        $('#edit_company_creditibility').val(c.company_creditibility);
                        $('#edit_status').val(c.status);

                        $('#editCompanyModal').modal('show');

                    } else {
                        toastr.error(response.message || 'Failed to load company details');
                    }
                },
                error: function() {
                    toastr.error('Server error while fetching data');
                }
            });

        });


        /* ================= UPDATE COMPANY ================= */
        $('#editCompanyForm').on('submit', function(e) {
            e.preventDefault();

            $('.error-label').text('').hide();
            $('input, select, textarea').removeClass('is-invalid');

            let hasError = false;

            let requiredFields = [
                'edit_company_group_id',
                'edit_company_name',
                'edit_email',
                'edit_mobile_number',
                'edit_country_id',
                'edit_state_id',
                'edit_city_id',
                'edit_area_id',
                'edit_address'
            ];

            requiredFields.forEach(function(field) {
                if ($('#' + field).val() === '') {
                    $('#' + field + '_error').text('This field is required').show();
                    $('#' + field).addClass('is-invalid');
                    hasError = true;
                }
            });

            if (hasError) return false;

            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            let formData = new FormData(this);
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: "<?= base_url('company-save') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
                    if (response.status) {
                        toastr.success(response.message || 'Company updated successfully');
                        $('#editCompanyModal').modal('hide');
                        companyTable.draw(false);
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


    /* ================= DELETE COMPANY ================= */
    $(document).on('click', '.deleteCompany', function() {

        let company_id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: 'You will not be able to recover this record!',
            icon: "question",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes Delete it",
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('company-delete') ?>",
                    type: "POST",
                    data: {
                        id: company_id,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.csrfHash) {
                            window.CSRF.hash = response.csrfHash;
                        }
                        if (response.status) {
                            toastr.success(response.message || 'Company deleted successfully');
                            companyTable.draw(false);
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

    function refreshCompanyTable() {
        $('#companies-data-table').DataTable().draw(false);
    }
</script>
