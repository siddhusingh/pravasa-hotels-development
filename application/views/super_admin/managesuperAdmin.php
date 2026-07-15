<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-user-secret"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Super Admin</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Super Admin Management</li>
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
                            <h4 class="box-title">Manage Super Admin</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-admin-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
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

<div class="modal modal-lg new_modal_design" id="admin-crud-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="crud-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a super admin.
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
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Full Name <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="full_name" placeholder="Full name">
                    <span id="full_name_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Email <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="email" id="email" placeholder="admin@example.com">
                    <span id="email_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Phone <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="phone" placeholder="9876543210">
                    <span id="phone_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" id="password_label">Password <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="password" id="password" placeholder="Password">
                    <span id="password_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3" id="status-wrapper">
                    <label class="form-label">Status</label>
                    <select class="form-control" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <span id="status_error" class="validation text-danger"></span>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <div id="action-btn-container">
                    <button type="button" id="action-btn" class="btn btn-primary" data-key="">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        positionClass: 'toast-top-right',
        timeOut: '5000'
    };

    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: '<?php echo base_url('get-super-admins-table') ?>',
            type: 'POST',
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

    function clearAdminErrors() {
        $('.validation').text('');
    }

    function resetAdminForm() {
        $('#full_name, #email, #phone, #password').val('');
        $('#status').val('active');
        clearAdminErrors();
    }

    function validateAdminForm(isEdit) {
        clearAdminErrors();

        var fullName = $('#full_name').val().trim();
        var email = $('#email').val().trim();
        var phone = $('#phone').val().trim();
        var password = $('#password').val();
        var status = $('#status').val();
        var hasError = false;

        if (fullName.length < 3) {
            $('#full_name_error').text('Full name must be at least 3 characters');
            hasError = true;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email_error').text('Please enter a valid email');
            hasError = true;
        }

        if (!/^[0-9]{10}$/.test(phone)) {
            $('#phone_error').text('Phone number must be 10 digits');
            hasError = true;
        }

        if (!isEdit && password.length < 6) {
            $('#password_error').text('Password must be at least 6 characters');
            hasError = true;
        }

        if (isEdit && password !== '' && password.length < 6) {
            $('#password_error').text('Password must be at least 6 characters');
            hasError = true;
        }

        if (isEdit && status !== 'active' && status !== 'inactive') {
            $('#status_error').text('Please select a valid status');
            hasError = true;
        }

        return !hasError;
    }

    function restoreActionButton(text, key) {
        $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary" data-key="' + (key || '') + '">' + text + '</button>');
    }

    $(document).on('keyup change', '#admin-crud-modal input, #admin-crud-modal select', function() {
        $('#' + $(this).attr('id') + '_error').text('');
    });

    $('#open-admin-modal').on('click', function(e) {
        e.preventDefault();
        resetAdminForm();
        $('#crud-modal-title').text('Add Super Admin');
        $('#password_label').text('Password');
        $('#status-wrapper').hide();
        $('#action-btn').text('Create').attr('data-key', '');
        $('#admin-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        var key = $(this).attr('data-key');
        var isEdit = key !== '';
        var btnText = $(this).text();

        if (!validateAdminForm(isEdit)) {
            return;
        }

        var formData = new FormData();
        formData.append('full_name', $('#full_name').val().trim());
        formData.append('email', $('#email').val().trim());
        formData.append('phone', $('#phone').val().trim());
        formData.append('password', $('#password').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (isEdit) {
            formData.append('id', key);
            formData.append('status', $('#status').val());
        }

        $.ajax({
            url: '<?php echo base_url(); ?>' + (isEdit ? 'update-super-admin' : 'insert-super-admin'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                restoreActionButton(isEdit ? 'Updating...' : 'Saving...', key);
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }

                if (response.status === 'success') {
                    toastr.success(isEdit ? 'Super Admin updated successfully' : 'Super Admin added successfully');
                    $('#admin-crud-modal').modal('hide');
                    data_table.draw(false);
                    return;
                }

                if (response.errors) {
                    $.each(response.errors, function(field, message) {
                        $('#' + field + '_error').text(message);
                    });
                } else {
                    toastr.error(response.message || 'Request failed');
                }
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                restoreActionButton(btnText, key);
            }
        });
    });

    $(document).on('click', '.edit-admin', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?php echo base_url('edit-super-admin') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }

                if (!response.status) {
                    toastr.error(response.message || 'Unable to load super admin details');
                    return;
                }

                resetAdminForm();
                $('#crud-modal-title').text('Edit Super Admin');
                $('#full_name').val(response.data.full_name);
                $('#email').val(response.data.email);
                $('#phone').val(response.data.phone);
                $('#status').val(response.data.status);
                $('#password_label').text('Password (Leave blank to keep current)');
                $('#status-wrapper').show();
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#admin-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-admin', function(e) {
        e.preventDefault();
        var $button = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This Super Admin will be removed from the active administrator list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('delete-super-admin') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: $button.attr('data-record_id'),
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        if (response.csrfHash) {
                            window.CSRF.hash = response.csrfHash;
                        }

                        if (response.status === 'success') {
                            toastr.success('Super Admin deleted successfully');
                            data_table.draw(false);
                        } else {
                            toastr.error(response.message || 'Error deleting Super Admin');
                        }
                    }
                });
            }
        });
    });
</script>
