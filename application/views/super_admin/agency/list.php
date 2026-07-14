<!-- Content Wrapper. Contains page content -->
<style>
    .error-label {
        font-size: 0.875rem;
        margin-top: 4px;
        display: none;
    }

    .required-asterisk {
        color: #dc3545;
        font-weight: 600;
    }

    #addAgencyModal .select2-container,
    #editAgencyModal .select2-container {
        width: 100% !important;
    }

    #addAgencyModal .select2-container--default .select2-selection--multiple,
    #editAgencyModal .select2-container--default .select2-selection--multiple {
        height: auto !important;
        min-height: 46px;
        max-height: none;
        overflow: visible;
        padding: 5px 8px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #addAgencyModal .select2-selection--multiple .select2-selection__rendered,
    #editAgencyModal .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        padding: 0;
        white-space: normal;
        overflow: visible;
    }

    #addAgencyModal .select2-selection--multiple .select2-selection__choice,
    #editAgencyModal .select2-selection--multiple .select2-selection__choice {
        margin: 0;
    }

    #addAgencyModal .select2-search--inline .select2-search__field,
    #editAgencyModal .select2-search--inline .select2-search__field {
        height: 30px !important;
        margin: 0;
        box-shadow: none !important;
    }

    .agency-select2-dropdown .select2-search__field {
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
                    <i class="fa fa-handshake-o"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Agency Management</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company & Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Agency Management</li>
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
                            <h4 class="box-title">Agency List</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-add-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Agency Name</th>
                                            <th>Contact Person</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Assigned Properties</th>
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

<!-- ========================= ADD AGENCY MODAL ========================= -->
<div class="modal modal-lg new_modal_design" id="addAgencyModal" tabindex="-1" aria-labelledby="addAgencyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-handshake-o"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title">Add New Agency</h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add a new agency.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <form id="addAgencyForm">
                <div class="modal-body ps-3 pe-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Agency Name <span class="required-asterisk">*</span></label>
                            <input type="text" name="agency_name" id="agency_name" class="form-control" />
                            <div class="error-label text-danger" id="agency_name_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Contact Person <span class="required-asterisk">*</span></label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control" />
                            <div class="error-label text-danger" id="contact_person_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email <span class="required-asterisk">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" />
                            <div class="error-label text-danger" id="email_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone <span class="required-asterisk">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" maxlength="10" />
                            <div class="error-label text-danger" id="phone_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Password <span class="required-asterisk">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary togglePassword" type="button"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="error-label text-danger" id="password_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address <span class="required-asterisk">*</span></label>
                            <textarea name="address" id="address" rows="2" class="form-control"></textarea>
                            <div class="error-label text-danger" id="address_error"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Assign Properties <span class="required-asterisk">*</span></label>
                            <select multiple name="property_ids[]" id="property_ids" class="form-control" style="width:100%;">
                                <?php foreach ($hotel_admin as $p): ?>
                                    <option value="<?= encrypt_id($p->hotel_id) ?>"><?= htmlspecialchars($p->hotel_name, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="error-label text-danger" id="property_ids_error"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================= EDIT AGENCY MODAL ========================= -->
<div class="modal modal-lg new_modal_design" id="editAgencyModal" tabindex="-1" aria-labelledby="editAgencyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-handshake-o"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title">Edit Agency</h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to update an agency.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <form id="editAgencyForm">
                <input type="hidden" name="agency_id" id="edit_agency_id">
                <div class="modal-body ps-3 pe-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Agency Name <span class="required-asterisk">*</span></label>
                            <input type="text" name="agency_name" id="edit_agency_name" class="form-control" />
                            <div class="error-label text-danger" id="edit_agency_name_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Contact Person <span class="required-asterisk">*</span></label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="form-control" />
                            <div class="error-label text-danger" id="edit_contact_person_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email <span class="required-asterisk">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" />
                            <div class="error-label text-danger" id="edit_email_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone <span class="required-asterisk">*</span></label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" maxlength="10" />
                            <div class="error-label text-danger" id="edit_phone_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Change Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="edit_password" class="form-control" placeholder="Leave blank to keep current" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary togglePassword" type="button"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address <span class="required-asterisk">*</span></label>
                            <textarea name="address" id="edit_address" rows="2" class="form-control"></textarea>
                            <div class="error-label text-danger" id="edit_address_error"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Assign Properties <span class="required-asterisk">*</span></label>
                            <select multiple name="property_ids[]" id="edit_property_ids" class="form-control" style="width:100%;">
                                <?php foreach ($hotel_admin as $p): ?>
                                    <option value="<?= encrypt_id($p->hotel_id) ?>"><?= htmlspecialchars($p->hotel_name, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="error-label text-danger" id="edit_property_ids_error"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
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
    var agencyTable;

    function initAgencySelects() {
        if ($.fn.select2) {
            $('#property_ids').select2({
                width: '100%',
                placeholder: 'Select Properties',
                closeOnSelect: false,
                dropdownParent: $('#addAgencyModal'),
                dropdownCssClass: 'agency-select2-dropdown'
            });

            $('#edit_property_ids').select2({
                width: '100%',
                placeholder: 'Select Properties',
                closeOnSelect: false,
                dropdownParent: $('#editAgencyModal'),
                dropdownCssClass: 'agency-select2-dropdown'
            });
        }
    }

    function setEncryptedMultiSelectValue(selector, values) {
        var selectedValues = [];
        $(selector + ' option[data-dynamic-selected="1"]').remove();

        $.each(values || [], function(_, item) {
            if ($(selector + ' option[value="' + item.id + '"]').length === 0) {
                var option = new Option(item.name || 'Selected', item.id, true, true);
                $(option).attr('data-dynamic-selected', '1');
                $(selector).append(option);
            }
            selectedValues.push(item.id);
        });

        $(selector).val(selectedValues).trigger('change');
    }

    function clearValidation(formSelector) {
        $(formSelector + ' .error-label').text('').hide();
        $(formSelector + ' input, ' + formSelector + ' textarea, ' + formSelector + ' select').removeClass('is-invalid');
    }

    function markInvalid(fieldSelector, message) {
        $(fieldSelector).addClass('is-invalid');
        $(fieldSelector + '_error').text(message).show();
    }

    function validateAgencyForm(prefix, requirePassword) {
        var valid = true;
        var fields = [
            ['agency_name', 'Please enter agency name'],
            ['contact_person', 'Please enter contact person'],
            ['email', 'Please enter email'],
            ['phone', 'Please enter phone'],
            ['address', 'Please enter address'],
            ['property_ids', 'Please assign at least one property']
        ];

        if (requirePassword) {
            fields.push(['password', 'Please enter password']);
        }

        $.each(fields, function(_, rule) {
            var id = prefix + rule[0];
            var $field = $('#' + id);
            var value = $field.val();

            if (value === '' || value === null || ($.isArray(value) && value.length === 0)) {
                markInvalid('#' + id, rule[1]);
                valid = false;
            }
        });

        var email = $('#' + prefix + 'email').val();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            markInvalid('#' + prefix + 'email', 'Please enter a valid email');
            valid = false;
        }

        var phone = $('#' + prefix + 'phone').val();
        if (phone && !/^[0-9]{10}$/.test(phone)) {
            markInvalid('#' + prefix + 'phone', 'Please enter a valid 10 digit phone');
            valid = false;
        }

        return valid;
    }

    agencyTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: [6, 7], orderable: false }
        ],
        ajax: {
            url: "<?= base_url('get-agencies-table') ?>",
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
        initAgencySelects();

        $(document).on('click', '#open-add-modal', function() {
            clearValidation('#addAgencyForm');
            $('#addAgencyForm')[0].reset();
            $('#property_ids').val(null).trigger('change');
            $('#addAgencyModal').modal('show');
        });

        $(document).on('click', '.togglePassword', function() {
            var input = $(this).closest('.input-group').find('input');
            var icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#addAgencyForm').on('submit', function(e) {
            e.preventDefault();
            clearValidation('#addAgencyForm');

            if (!validateAgencyForm('', true)) {
                return;
            }

            var submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "<?= base_url('agency-save') ?>",
                type: 'POST',
                data: $(this).serialize() + '&' + encodeURIComponent(window.CSRF.name) + '=' + encodeURIComponent(window.CSRF.hash),
                dataType: 'json',
                success: function(res) {
                    if (res.csrfHash) {
                        window.CSRF.hash = res.csrfHash;
                    }
                    if (res.status) {
                        toastr.success(res.message);
                        $('#addAgencyModal').modal('hide');
                        agencyTable.draw(false);
                    } else {
                        toastr.error(res.message || 'Failed to save agency');
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

        $(document).on('click', '.edit-agency', function() {
            var id = $(this).attr('data-record_id');
            clearValidation('#editAgencyForm');

            $.ajax({
                url: "<?= base_url('agency-details') ?>",
                type: 'POST',
                data: {
                    id: id,
                    [window.CSRF.name]: window.CSRF.hash
                },
                dataType: 'json',
                success: function(res) {
                    if (res.csrfHash) {
                        window.CSRF.hash = res.csrfHash;
                    }
                    if (!res.status) {
                        toastr.error(res.message || 'Unable to load agency details');
                        return;
                    }

                    var row = res.data;
                    $('#edit_agency_id').val(row.id);
                    $('#edit_agency_name').val(row.agency_name);
                    $('#edit_contact_person').val(row.contact_person);
                    $('#edit_email').val(row.email);
                    $('#edit_phone').val(row.phone);
                    $('#edit_address').val(row.address);
                    $('#edit_status').val(row.status);
                    $('#edit_password').val('');
                    setEncryptedMultiSelectValue('#edit_property_ids', row.selected_properties);
                    $('#editAgencyModal').modal('show');

                    if (row.unavailable_properties > 0) {
                        toastr.warning('One or more previously assigned properties are unavailable and were not selected.');
                    }
                },
                error: function() {
                    toastr.error('Server error while fetching data');
                }
            });
        });

        $('#editAgencyForm').on('submit', function(e) {
            e.preventDefault();
            clearValidation('#editAgencyForm');

            if (!validateAgencyForm('edit_', false)) {
                return;
            }

            var submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "<?= base_url('agency-save') ?>",
                type: 'POST',
                data: $(this).serialize() + '&' + encodeURIComponent(window.CSRF.name) + '=' + encodeURIComponent(window.CSRF.hash),
                dataType: 'json',
                success: function(res) {
                    if (res.csrfHash) {
                        window.CSRF.hash = res.csrfHash;
                    }
                    if (res.status) {
                        toastr.success(res.message);
                        $('#editAgencyModal').modal('hide');
                        agencyTable.draw(false);
                    } else {
                        toastr.error(res.message || 'Failed to update agency');
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

        $(document).on('click', '.delete-agency', function() {
            var id = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This agency will be removed from the active agency list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('agency-delete') ?>",
                        type: 'POST',
                        data: {
                            id: id,
                            [window.CSRF.name]: window.CSRF.hash
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.csrfHash) {
                                window.CSRF.hash = res.csrfHash;
                            }
                            if (res.status) {
                                toastr.success(res.message);
                                agencyTable.draw(false);
                            } else {
                                toastr.error(res.message || 'Delete failed');
                            }
                        },
                        error: function() {
                            toastr.error('Server error! Please try again');
                        }
                    });
                }
            });
        });
    });
</script>
