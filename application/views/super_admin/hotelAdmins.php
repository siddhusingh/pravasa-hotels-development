<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #dc3545;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #28a745;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-user-secret"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Hotel Admins</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Hotel Admin Management</li>
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
                            <h4 class="box-title">Hotel Admin List</h4>
                            <div class="float-right" style="float:right;">
                                <button class="btn btn-primary-light btn-sm" id="open-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display" id="server-side-data-table" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Hotel</th>
                                            <th>Status</th>
                                            <th>Created</th>
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

<div class="modal fade modal-lg new_modal_design" id="hotel-admin-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="modal-title">Add Hotel Admin</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a hotel admin.
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
                    <div class="col-md-6 mb-3">
                        <label>Full Name <span class="required-asterisk">*</span></label>
                        <input type="text" id="full_name" class="form-control">
                        <span class="text-danger validation" id="name_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email <span class="required-asterisk">*</span></label>
                        <input type="email" id="email" class="form-control">
                        <span class="text-danger validation" id="email_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label id="password-label">Password <span class="required-asterisk">*</span></label>
                        <input type="password" id="password" class="form-control">
                        <span class="text-danger validation" id="password_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Phone <span class="required-asterisk">*</span></label>
                        <input type="text" id="phone" class="form-control">
                        <span class="text-danger validation" id="phone_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Select Hotel <span class="required-asterisk">*</span></label>
                        <select id="hotel_id" class="form-control">
                            <option value="">Select Hotel</option>
                            <?php foreach ($hotels as $hotel) { ?>
                                <option value="<?= encrypt_id($hotel->hotel_id) ?>"><?= $hotel->hotel_name ?></option>
                            <?php } ?>
                        </select>
                        <span class="text-danger validation" id="hotel_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3" id="status-wrapper">
                        <label>Status</label>
                        <select id="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="text-danger validation" id="status_error"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-start gap-2">
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <div id="action-btn-container" class="d-inline-flex">
                    <button class="btn btn-primary" id="action-btn" data-key="">Save</button>
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
            url: '<?= base_url('get-hotel-admins-table') ?>',
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

    function clearErrors() {
        $('.validation').text('');
    }

    function resetForm() {
        $('#full_name, #email, #password, #phone').val('');
        $('#hotel_id').val('');
        $('#status').val('1');
        clearErrors();
    }

    function ensureSelectedOption($select, value, label) {
        if (!value || $select.find('option[value="' + value + '"]').length) {
            $select.val(value);
            return;
        }

        $select.append(new Option(label || 'Selected', value, true, true));
        $select.val(value);
    }

    function validateForm(isEdit) {
        clearErrors();

        var name = $('#full_name').val().trim();
        var email = $('#email').val().trim();
        var password = $('#password').val();
        var phone = $('#phone').val().trim();
        var hotelId = $('#hotel_id').val();
        var status = $('#status').val();
        var hasError = false;

        if (name.length < 3) {
            $('#name_error').text('Full name must be at least 3 characters');
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

        if (!hotelId) {
            $('#hotel_id_error').text('Please select a hotel');
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

        if (isEdit && status !== '1' && status !== '0') {
            $('#status_error').text('Please select a valid status');
            hasError = true;
        }

        return !hasError;
    }

    function restoreActionButton(text, key) {
        $('#action-btn-container').html('<button class="btn btn-primary" id="action-btn" data-key="' + (key || '') + '">' + text + '</button>');
    }

    $(document).on('keyup change', '#hotel-admin-modal input, #hotel-admin-modal select', function() {
        var errorId = $(this).attr('id') === 'full_name' ? 'name_error' : $(this).attr('id') + '_error';
        $('#' + errorId).text('');
    });

    $('#open-modal').on('click', function() {
        resetForm();
        $('#modal-title').text('Add Hotel Admin');
        $('#password-label').text('Password');
        $('#status-wrapper').hide();
        $('#action-btn').text('Save').attr('data-key', '');
        $('#hotel-admin-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        var key = $(this).attr('data-key');
        var isEdit = key !== '';
        var btnText = $(this).text();

        if (!validateForm(isEdit)) {
            return;
        }

        var fd = new FormData();
        fd.append('name', $('#full_name').val().trim());
        fd.append('email', $('#email').val().trim());
        fd.append('password', $('#password').val());
        fd.append('phone', $('#phone').val().trim());
        fd.append('hotel_id', $('#hotel_id').val());
        fd.append(window.CSRF.name, window.CSRF.hash);

        if (isEdit) {
            fd.append('record_id', key);
            fd.append('status', $('#status').val());
        }

        $.ajax({
            url: '<?= base_url() ?>' + (isEdit ? 'update-hotel-admin' : 'insert-hotel-admin'),
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                restoreActionButton(isEdit ? 'Updating...' : 'Saving...', key);
            },
            success: function(res) {
                if (res.csrfHash) {
                    window.CSRF.hash = res.csrfHash;
                }

                if (res.status) {
                    toastr.success(res.message);
                    $('#hotel-admin-modal').modal('hide');
                    data_table.draw(false);
                    return;
                }

                if (res.errors) {
                    $.each(res.errors, function(field, message) {
                        $('#' + field + '_error').text(message);
                    });
                } else {
                    toastr.error(res.message || 'Request failed');
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

    $(document).on('click', '.edit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('edit-hotel-admin') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                if (res.csrfHash) {
                    window.CSRF.hash = res.csrfHash;
                }

                if (!res.status) {
                    toastr.error(res.message || 'Unable to load hotel admin details');
                    return;
                }

                resetForm();
                $('#modal-title').text('Edit Hotel Admin');
                $('#full_name').val(res.data.name);
                $('#email').val(res.data.email);
                $('#phone').val(res.data.phone);
                $('#password').val('');
                $('#status').val(String(res.data.status));
                ensureSelectedOption($('#hotel_id'), res.data.hotel_id, res.data.hotel_name);
                $('#password-label').text('Password (Optional)');
                $('#status-wrapper').show();
                $('#action-btn').text('Update').attr('data-key', res.id);
                $('#hotel-admin-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this hotel admin?')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('delete-hotel-admin') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                if (res.csrfHash) {
                    window.CSRF.hash = res.csrfHash;
                }

                if (res.status) {
                    toastr.success(res.message);
                    data_table.draw(false);
                } else {
                    toastr.error(res.message || 'Something went wrong');
                }
            }
        });
    });

    $(document).on('pointerdown keydown', '.status-toggle', function(e) {
        if (e.type === 'keydown' && e.key !== ' ' && e.key !== 'Enter') {
            return;
        }

        $(this).data('user-intent', true);
    });

    $(document).on('change', '.status-toggle', function() {
        var $toggle = $(this);

        if (!$toggle.data('user-intent')) {
            return;
        }

        $toggle.removeData('user-intent');

        $.ajax({
            url: '<?= base_url('update-hotel-admin-status') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $toggle.attr('data-record_id'),
                status: $toggle.is(':checked') ? 1 : 0,
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                if (res.csrfHash) {
                    window.CSRF.hash = res.csrfHash;
                }

                if (res.status) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message || 'Unable to update status');
                    $toggle.prop('checked', !$toggle.is(':checked'));
                }
            },
            error: function() {
                toastr.error('Unable to update status');
                $toggle.prop('checked', !$toggle.is(':checked'));
            }
        });
    });
</script>
