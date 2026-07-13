<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-male"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Sales Users</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Sales User Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Manage Sales Users</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm " id="open-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>User Role</th>
                                            <th>Team Group</th>
                                            <th>Assigned Hotels</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($sales_users)) {
                                            $number = 1;
                                            foreach ($sales_users as $each) { ?>
                                                <tr>
                                                    <td><?php echo $number++; ?></td>

                                                    <td><?= $each->full_name ?></td>
                                                    <td><?= $each->email ?></td>
                                                    <td><?= $each->phone ?></td>

                                                    <td><?= $each->user_role ?></td>

                                                    <td>
                                                        <?= !empty($each->team_group) ? $each->team_group : '-' ?>
                                                    </td>

                                                    <td>
                                                        <?= !empty($each->assigned_hotels) ? $each->assigned_hotels : '-' ?>
                                                    </td>

                                                    <td><?= $each->city_name ?? '-' ?></td>
                                                    <td><?= $each->state_name ?? '-' ?></td>

                                                    <td>
                                                        <?php if ($each->status == 1) { ?>
                                                            <span class="badge badge-success">Active</span>
                                                        <?php } else { ?>
                                                            <span class="badge badge-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>

                                                    <td><?= date('d-m-Y', strtotime($each->created_at)) ?></td>
                                                    <td><?= date('d-m-Y', strtotime($each->updated_at)) ?></td>

                                                    <td class="table-action min-w-120">
                                                        <a href="javascript:void(0)"
                                                            class="text-fade hover-primary edit"
                                                            data-record_id="<?= $each->id ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-edit-2">
                                                                <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                                            </svg>
                                                        </a>

                                                        <a href="javascript:void(0)"
                                                            class="text-fade hover-danger delete"
                                                            data-record_id="<?= $each->id ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7
                                         a2 2 0 0 1-2-2V6m3 0V4
                                         a2 2 0 0 1 2-2h4
                                         a2 2 0 0 1 2 2v2">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal  modal-lg new_modal_design" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-Sales Users-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-male"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add New Sales User</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add a sales user.
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

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="text" id="full_name" name="full_name" placeholder="Enter Full Name">
                            <span id="full_name_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="email" id="email" name="email" placeholder="Enter Email">
                            <span id="email_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="password" id="password" name="password" placeholder="Enter Password">
                            <span id="password_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="phone" name="phone" placeholder="Enter Phone Number">
                            <span id="phone_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- User Role -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_role" class="form-label">User Role <span class="required-asterisk">*</span></label>
                            <select class="form-control" name="user_role" id="user_role">
                                <option value="" selected disabled>Select User Role</option>
                                <option value="RSO">RSO - Regional Sales Officer</option>
                                <option value="Sales Manager">Sales Manager</option>
                                <option value="Sales Executive">Sales Executive</option>
                            </select>
                            <span id="user_role_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Team Group -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="team_group" class="form-label">Team Group</label>
                            <select class="form-control" name="team_group[]" id="team_group" multiple>
                                <option disabled>Select Team Group</option>
                                <?php foreach ($team_group as $each) { ?>
                                    <option value="<?php echo encrypt_id($each->id); ?>">
                                        <?php echo $each->team_group_name ?? $each->team_group ?? ''; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="team_group_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Hotels -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hotel_id" class="form-label">Select Hotels (Multiple)</label>
                            <select class="form-control" name="hotel_id[]" id="hotel_id" multiple>
                                <option disabled>Select Hotels</option>
                                <?php foreach ($hotels as $each) { ?>
                                    <option value="<?php echo encrypt_id($each->hotel_id); ?>">
                                        <?php echo $each->hotel_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="hotel_id_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-control" name="city" id="city">
                                <option value="" selected disabled>Select City</option>
                                <?php foreach ($cities as $city) { ?>
                                    <option value="<?php echo encrypt_id($city->city_id); ?>">
                                        <?php echo $city->city_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="city_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="state_id" class="form-label">State</label>
                            <select class="form-control" name="state_id" id="state_id">
                                <option value="" selected disabled>Select State</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?php echo encrypt_id($state->state_id); ?>">
                                        <?php echo $state->state_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="state_id_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Zip Code -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="zipcode" class="form-label">Zip Code</label>
                            <input class="form-control" type="number" id="zipcode" name="zipcode" placeholder="Enter Zip Code">
                            <span id="zipcode_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company" class="form-label">Company</label>
                            <textarea class="form-control" id="company" name="company" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="SaveBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal  modal-lg new_modal_design" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="add-Sales Users-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-male"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Sales User</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to update a sales user.
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
                <!--  <form class="ps-3 pe-3" action="#"> -->
                <div class="row">

                    <!-- Record ID -->
                    <input type="hidden" id="record_id">

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name_edit" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="text" id="full_name_edit" placeholder="Enter Full Name">
                            <span id="full_name_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email_edit" class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="email" id="email_edit" placeholder="Enter Email">
                            <span id="email_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Password (Optional on Edit) -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_edit" class="form-label">Password</label>
                            <input class="form-control" type="password" id="password_edit" placeholder="Leave blank to keep current">
                            <span id="password_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone_edit" class="form-label">Phone Number <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="phone_edit" placeholder="Enter Phone Number">
                            <span id="phone_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- User Role -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_role_edit" class="form-label">User Role <span class="required-asterisk">*</span></label>
                            <select class="form-control" id="user_role_edit">
                                <option value="" disabled>Select User Role</option>
                                <option value="RSO">RSO - Regional Sales Officer</option>
                                <option value="Sales Manager">Sales Manager</option>
                                <option value="Sales Executive">Sales Executive</option>
                            </select>
                            <span id="user_role_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Team Group -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="team_group_edit" class="form-label">Team Group</label>
                            <select class="form-control" id="team_group_edit" multiple>
                                <?php foreach ($team_group as $each) { ?>
                                    <option value="<?php echo encrypt_id($each->id); ?>">
                                        <?php echo $each->team_group_name ?? $each->team_group ?? ''; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="team_group_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Hotels -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hotel_id_edit" class="form-label">Select Hotels (Multiple)</label>
                            <select class="form-control" id="hotel_id_edit" multiple>
                                <?php foreach ($hotels as $each) { ?>
                                    <option value="<?php echo encrypt_id($each->hotel_id); ?>">
                                        <?php echo $each->hotel_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="hotel_id_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="city_edit" class="form-label">City</label>
                            <select class="form-control" id="city_edit">
                                <option value="" disabled>Select City</option>
                                <?php foreach ($cities as $city) { ?>
                                    <option value="<?php echo encrypt_id($city->city_id); ?>">
                                        <?php echo $city->city_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="city_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="state_id_edit" class="form-label">State</label>
                            <select class="form-control" id="state_id_edit">
                                <option value="" disabled>Select State</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?php echo encrypt_id($state->state_id); ?>">
                                        <?php echo $state->state_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span id="state_id_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Zip Code -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="zipcode_edit" class="form-label">Zip Code</label>
                            <input class="form-control" type="number" id="zipcode_edit" placeholder="Enter Zip Code">
                            <span id="zipcode_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_edit" class="form-label">Company</label>
                            <textarea class="form-control" id="company_edit" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="address_edit" class="form-label">Address</label>
                            <textarea class="form-control" id="address_edit" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status_edit" class="form-label">Status</label>
                            <select class="form-select" id="status_edit">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="updateBtn" class="btn btn-primary">Update changes</button>
            </div>
        </div>
    </div>
</div>
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



    <?php if ($this->session->flashdata('regional_managers_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('regional_managers_success_msg'); ?>')

    <?php $this->session->set_flashdata('regional_managers_success_msg', '');
    } ?>
</script>
<script type="text/javascript">
    var salesUsersTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: '<?php echo base_url('get-sales-users-table'); ?>',
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
        },
        columnDefs: [{ orderable: false, targets: 12 }]
    });

    function clearSalesErrors() {
        $('.validation').text('');
    }

    function applySalesErrors(errors, suffix) {
        $.each(errors || {}, function(field, message) {
            $('#' + field + (suffix || '') + '_error').text(message);
        });
    }

    function updateCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function salesFormData(isEdit) {
        var fd = new FormData();
        var suffix = isEdit ? '_edit' : '';
        fd.append('full_name', $('#full_name' + suffix).val().trim());
        fd.append('email', $('#email' + suffix).val().trim());
        fd.append('password', $('#password' + suffix).val());
        fd.append('phone', $('#phone' + suffix).val().trim());
        fd.append('user_role', $('#user_role' + suffix).val() || '');
        fd.append('team_group', ($('#team_group' + suffix).val() || []).join(','));
        fd.append('hotel_id', ($('#hotel_id' + suffix).val() || []).join(','));
        fd.append('city', $('#city' + suffix).val() || '');
        fd.append('state_id', $('#state_id' + suffix).val() || '');
        fd.append('zipcode', $('#zipcode' + suffix).val().trim());
        fd.append('company', $('#company' + suffix).val().trim());
        fd.append('address', $('#address' + suffix).val().trim());
        fd.append('status', $('#status' + suffix).val() || '');
        fd.append(window.CSRF.name, window.CSRF.hash);

        if (isEdit) {
            fd.append('record_id', $('#record_id').val());
        }

        return fd;
    }

    $("#open-modal").click(function(e) {
        e.preventDefault();
        clearSalesErrors();
        $('#add-modal input, #add-modal textarea').val('');
        $('#user_role').val('');
        $('#team_group, #hotel_id').val(null).trigger('change');
        $('#city, #state_id').val('');
        $('#status').val('1');
        $('#add-modal').modal('show');
    });

    $(document).on('click', '#SaveBtn', function(e) {
        e.preventDefault();
        clearSalesErrors();

        $.ajax({
            url: '<?php echo base_url("insert-sales-users"); ?>',
            type: 'POST',
            data: salesFormData(false),
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('#SaveBtn').attr('disabled', true).text('Saving...');
            },
            success: function(response) {
                updateCsrf(response);
                if (response.status === true) {
                    toastr.success(response.message);
                    $('#add-modal').modal('hide');
                    salesUsersTable.draw(false);
                    return;
                }
                applySalesErrors(response.errors, '');
                toastr.error(response.message || 'Please correct the form');
            },
            error: function() {
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function() {
                $('#SaveBtn').attr('disabled', false).text('Save changes');
            }
        });
    });

    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        clearSalesErrors();

        $.ajax({
            url: '<?php echo base_url("edit-sales-users"); ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                updateCsrf(res);
                if (!res.status) {
                    toastr.error(res.message || 'Unable to fetch record details');
                    return;
                }

                var data = res.data;
                $('#record_id').val(res.id);
                $('#full_name_edit').val(data.full_name);
                $('#email_edit').val(data.email);
                $('#phone_edit').val(data.phone);
                $('#password_edit').val('');
                $('#user_role_edit').val(data.user_role);
                $('#team_group_edit').val(data.team_group ? data.team_group.split(',') : []).trigger('change');
                $('#hotel_id_edit').val(data.assigned_hotels ? data.assigned_hotels.split(',') : []).trigger('change');
                $('#city_edit').val(data.city_id);
                $('#state_id_edit').val(data.state_id);
                $('#zipcode_edit').val(data.zipcode);
                $('#company_edit').val(data.company);
                $('#address_edit').val(data.address);
                $('#status_edit').val(String(data.status));
                $('#edit-modal').modal('show');
            },
            error: function() {
                toastr.error('Unable to fetch record details');
            }
        });
    });

    $(document).on('click', '#updateBtn', function(e) {
        e.preventDefault();
        clearSalesErrors();

        $.ajax({
            url: '<?php echo base_url("update-sales-users"); ?>',
            type: 'POST',
            data: salesFormData(true),
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('#updateBtn').attr('disabled', true).text('Updating...');
            },
            success: function(response) {
                updateCsrf(response);
                if (response.status === true) {
                    toastr.success(response.message);
                    $('#edit-modal').modal('hide');
                    salesUsersTable.draw(false);
                    return;
                }
                applySalesErrors(response.errors, '_edit');
                toastr.error(response.message || 'Please correct the form');
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                $('#updateBtn').attr('disabled', false).text('Update changes');
            }
        });
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: "Are you sure?",
            text: 'This sales user will be deleted.',
            icon: "question",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes Delete it"
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url('delete-sales-users') ?>',
                method: "POST",
                dataType: 'JSON',
                data: {
                    id: id,
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(res) {
                    updateCsrf(res);
                    if (res.status) {
                        toastr.success(res.message);
                        salesUsersTable.draw(false);
                    } else {
                        toastr.error(res.message || 'Something went wrong');
                    }
                }
            });
        });
    });
</script>
