<style>
    .staff-mapping-row .select2-container { width: 100% !important; }
    .staff-mapping-row .select2-selection--single { height: 38px; padding: 5px 8px; }
    .staff-mapping-row .select2-selection__arrow { height: 36px; }
    .staff-action-links a { white-space: nowrap; margin-right: 8px; }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-users"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Manage Staff Members</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Staff Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?= base_url('assets/new_img-add.png'); ?>" alt="Staff management"></div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Manage Staff Members</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-staff-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body last_active_design_td">
                    <div class="table-responsive">
                        <table id="hotel-staff-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created On</th>
                                    <th>Updated On</th>
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

<div class="modal modal-lg new_modal_design" id="staff-crud-modal" tabindex="-1" role="dialog" aria-labelledby="staff-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-users"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="staff-modal-title">Add New Staff</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Fill in the staff details and department assignments.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="staff_name">Full Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="staff_name" placeholder="Full Name">
                        <span class="validation text-danger" id="staff_name_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="staff_email">Email <span class="required-asterisk">*</span></label>
                        <input type="email" class="form-control" id="staff_email" placeholder="Email">
                        <span class="validation text-danger" id="staff_email_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="staff_phone">Phone <span class="required-asterisk">*</span></label>
                        <input type="text" inputmode="numeric" maxlength="10" class="form-control" id="staff_phone" placeholder="Phone">
                        <span class="validation text-danger" id="staff_phone_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="staff_password">Password <span class="required-asterisk" id="password-required">*</span></label>
                        <input type="password" class="form-control" id="staff_password" placeholder="Password">
                        <small id="password-help" class="text-muted d-none">Leave blank to keep the current password.</small>
                        <span class="validation text-danger" id="staff_password_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="assigned_hotel">Hotel <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="assigned_hotel" value="<?= html_escape($hotel->hotel_name); ?>" disabled aria-readonly="true">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Department Assignments</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-mapping-row">+ Add More</button>
                </div>
                <div id="staff-mapping-rows"></div>
                <span class="validation text-danger" id="staff_mapping_error"></span>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-staff-button" data-record-id="">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-lg new_modal_design" id="view-staff-modal" tabindex="-1" role="dialog" aria-labelledby="view-staff-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-users"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="view-staff-title">Staff Details</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Assigned hotel, departments, and escalation levels.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <h4 id="view-staff-name"></h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead><tr><th>Hotel</th><th>Department</th><th>Escalation Level</th></tr></thead>
                        <tbody id="view-staff-mappings"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript">
    window.CSRF = {
        name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?= json_encode($this->security->get_csrf_hash()); ?>
    };

    window.addEventListener('load', function() {
        var departmentOptions = <?= json_encode(array_map(function ($department) {
            return ['id' => encrypt_id($department->department_id), 'text' => $department->department_name];
        }, $departments), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

        toastr.options = { closeButton: true, positionClass: 'toast-top-right', timeOut: '5000', preventDuplicates: true };

        function updateCsrf(response) {
            if (response && response.csrfHash) {
                window.CSRF.hash = response.csrfHash;
            }
        }

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function buildDepartmentOptions(selectedId) {
            var html = '<option value="">Select Department</option>';
            $.each(departmentOptions, function(index, option) {
                html += '<option value="' + escapeHtml(option.id) + '"' + (String(option.id) === String(selectedId) ? ' selected' : '') + '>' + escapeHtml(option.text) + '</option>';
            });
            return html;
        }

        function addMappingRow(mapping) {
            mapping = mapping || {};
            var row = $('<div class="row staff-mapping-row mb-2">' +
                '<div class="col-md-5"><label>Department <span class="required-asterisk">*</span></label>' +
                '<select class="form-control mapping-department">' + buildDepartmentOptions(mapping.department_id || '') + '</select></div>' +
                '<div class="col-md-5"><label>Escalation Level <span class="required-asterisk">*</span></label>' +
                '<select class="form-control mapping-level"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></div>' +
                '<div class="col-md-2 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm remove-mapping-row">- Remove</button></div>' +
                '</div>');
            row.find('.mapping-level').val(String(mapping.level || '1'));
            $('#staff-mapping-rows').append(row);
            if ($.fn.select2) {
                row.find('.mapping-department').select2({ width: '100%', dropdownParent: $('#staff-crud-modal'), placeholder: 'Select Department' });
            }
        }

        function resetStaffForm() {
            $('.validation').text('');
            $('#staff_name, #staff_email, #staff_phone, #staff_password').val('');
            $('#staff-mapping-rows').empty();
            $('#save-staff-button').attr('data-record-id', '').text('Save changes').prop('disabled', false);
            $('#staff-modal-title').text('Add New Staff');
            $('#password-required').removeClass('d-none');
            $('#password-help').addClass('d-none');
            addMappingRow();
        }

        function showErrors(errors) {
            if (!errors) return;
            if (errors.name) $('#staff_name_error').text(errors.name);
            if (errors.email) $('#staff_email_error').text(errors.email);
            if (errors.phone) $('#staff_phone_error').text(errors.phone);
            if (errors.password) $('#staff_password_error').text(errors.password);
            if (errors.mapping) $('#staff_mapping_error').text(errors.mapping);
        }

        function collectMappings() {
            var departments = [];
            var levels = [];
            var complete = true;
            $('#staff-mapping-rows .staff-mapping-row').each(function() {
                var department = $(this).find('.mapping-department').val();
                var level = $(this).find('.mapping-level').val();
                if (!department || !level) complete = false;
                departments.push(department || '');
                levels.push(level || '');
            });
            return { departments: departments, levels: levels, complete: complete && departments.length > 0 };
        }

        function validateStaffForm(isEdit) {
            $('.validation').text('');
            var name = $('#staff_name').val().trim();
            var email = $('#staff_email').val().trim();
            var phone = $('#staff_phone').val().trim();
            var password = $('#staff_password').val();
            var valid = true;
            if (name.length < 3) { $('#staff_name_error').text('Full name must be at least 3 characters'); valid = false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { $('#staff_email_error').text('Please enter a valid email'); valid = false; }
            if (!/^[0-9]{10}$/.test(phone)) { $('#staff_phone_error').text('Phone number must be 10 digits'); valid = false; }
            if ((!isEdit || password !== '') && !/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/.test(password)) {
                $('#staff_password_error').text('Password must be at least 6 characters long, contain at least one number and one special character');
                valid = false;
            }
            if (!collectMappings().complete) { $('#staff_mapping_error').text('Please select a department and escalation level for each row'); valid = false; }
            return valid;
        }

        var staffTable = $('#hotel-staff-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [{ targets: 6, orderable: false, className: 'staff-action-links' }],
            ajax: {
                url: '<?= base_url('hotel-admin/get-staff-table'); ?>',
                type: 'POST',
                data: function(data) { data[window.CSRF.name] = window.CSRF.hash; },
                dataSrc: function(response) { updateCsrf(response); return response.data || []; }
            }
        });

        $('#open-staff-modal').on('click', function(event) {
            event.preventDefault();
            resetStaffForm();
            $('#staff-crud-modal').modal('show');
        });

        $('#add-mapping-row').on('click', function() { addMappingRow(); });
        $(document).on('click', '.remove-mapping-row', function() {
            if ($('#staff-mapping-rows .staff-mapping-row').length === 1) {
                $('#staff_mapping_error').text('At least one department assignment is required');
                return;
            }
            $(this).closest('.staff-mapping-row').remove();
        });

        $('#staff_name, #staff_email, #staff_phone, #staff_password').on('input', function() {
            $('#' + this.id + '_error').text('');
        });
        $(document).on('change', '.mapping-department, .mapping-level', function() { $('#staff_mapping_error').text(''); });

        $('#save-staff-button').on('click', function(event) {
            event.preventDefault();
            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isEdit = recordId !== '';
            if (!validateStaffForm(isEdit)) return;

            var mappings = collectMappings();
            var formData = new FormData();
            formData.append('name', $('#staff_name').val().trim());
            formData.append('email', $('#staff_email').val().trim());
            formData.append('phone', $('#staff_phone').val().trim());
            formData.append('password', $('#staff_password').val());
            formData.append('departments', JSON.stringify(mappings.departments));
            formData.append('levels', JSON.stringify(mappings.levels));
            formData.append(window.CSRF.name, window.CSRF.hash);
            if (isEdit) formData.append('record_id', recordId);

            $.ajax({
                url: '<?= base_url('hotel-admin/'); ?>' + (isEdit ? 'update-staff' : 'insert-staff'),
                type: 'POST', data: formData, processData: false, contentType: false, dataType: 'json',
                beforeSend: function() { button.prop('disabled', true).text(isEdit ? 'Updating...' : 'Saving...'); },
                success: function(response) {
                    updateCsrf(response);
                    if (response.status === true) {
                        toastr.success(response.message);
                        $('#staff-crud-modal').modal('hide');
                        staffTable.ajax.reload(null, false);
                    } else {
                        showErrors(response.errors);
                        toastr.error(response.message || 'Unable to save staff member');
                    }
                },
                error: function() { toastr.error('Server error! Please try again.'); },
                complete: function() { button.prop('disabled', false).text(isEdit ? 'Update Staff' : 'Save changes'); }
            });
        });

        $(document).on('click', '.edit-staff', function(event) {
            event.preventDefault();
            var staffId = $(this).attr('data-record_id');
            resetStaffForm();
            $('#staff-mapping-rows').empty();
            $.ajax({
                url: '<?= base_url('hotel-admin/get-staff-details'); ?>', type: 'POST', dataType: 'json',
                data: { staff_id: staffId, [window.CSRF.name]: window.CSRF.hash },
                success: function(response) {
                    updateCsrf(response);
                    if (response.status !== true) { toastr.error(response.message || 'Unable to load staff member'); return; }
                    $('#staff_name').val(response.staff.name);
                    $('#staff_email').val(response.staff.email);
                    $('#staff_phone').val(response.staff.phone);
                    departmentOptions = response.department_options || departmentOptions;
                    $.each(response.mappings || [], function(index, mapping) { addMappingRow(mapping); });
                    if ((response.mappings || []).length === 0) addMappingRow();
                    $('#staff-modal-title').text('Edit Staff Details');
                    $('#password-required').addClass('d-none');
                    $('#password-help').removeClass('d-none');
                    $('#save-staff-button').attr('data-record-id', staffId).text('Update Staff');
                    $('#staff-crud-modal').modal('show');
                },
                error: function() { toastr.error('Error fetching staff details'); }
            });
        });

        $(document).on('click', '.view-staff', function(event) {
            event.preventDefault();
            var staffId = $(this).attr('data-record_id');
            var staffName = $(this).attr('data-name');
            $.ajax({
                url: '<?= base_url('hotel-admin/view-staff'); ?>', type: 'POST', dataType: 'json',
                data: { staff_id: staffId, [window.CSRF.name]: window.CSRF.hash },
                success: function(response) {
                    updateCsrf(response);
                    if (response.status !== true) { toastr.error(response.message || 'No mapping data found'); return; }
                    $('#view-staff-name').text(staffName);
                    var rows = '';
                    $.each(response.data, function(index, item) {
                        rows += '<tr><td>' + escapeHtml(item.hotel_name || '-') + '</td><td>' + escapeHtml(item.department_name || '-') + '</td><td>Level ' + escapeHtml(item.level || '-') + '</td></tr>';
                    });
                    $('#view-staff-mappings').html(rows);
                    $('#view-staff-modal').modal('show');
                },
                error: function() { toastr.error('Error fetching mapping details'); }
            });
        });

        $(document).on('click', '.delete-staff', function(event) {
            event.preventDefault();
            var staffId = $(this).attr('data-record_id');
            Swal.fire({
                title: 'Are you sure?', text: 'This staff member will be removed from this hotel.', icon: 'question',
                showCancelButton: true, showCloseButton: true, confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '<?= base_url('hotel-admin/delete-staff'); ?>', type: 'POST', dataType: 'json',
                    data: { id: staffId, [window.CSRF.name]: window.CSRF.hash },
                    success: function(response) {
                        updateCsrf(response);
                        if (response.status === true) { toastr.success(response.message); staffTable.ajax.reload(null, false); }
                        else toastr.error(response.message || 'Unable to delete staff member');
                    },
                    error: function() { toastr.error('Server error! Please try again.'); }
                });
            });
        });
    });
</script>
