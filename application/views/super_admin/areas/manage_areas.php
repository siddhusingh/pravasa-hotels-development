<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-users"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Area Users</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company & Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Area User Management</li>
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
                            <h4 class="box-title">Manage Area Users</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-area-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Area Name</th>
                                            <th>State</th>
                                            <th>Primary User</th>
                                            <th>Secondary User</th>
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
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal modal-lg new_modal_design" id="area-crud-modal" tabindex="-1" aria-labelledby="area-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update an area user.
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
                <div class="mb-3">
                    <label for="area_name" class="form-label">Area Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="area_name" name="area_name" placeholder="Enter area name">
                    <span class="validation text-danger" id="area_name_error"></span>
                </div>
                <div class="mb-3">
                    <label for="area_description" class="form-label">Area Description</label>
                    <textarea class="form-control" id="area_description" name="area_description" placeholder="Enter area description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="state" class="form-label">State <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="state" name="state">
                        <option value="">Select State</option>
                        <?php foreach ($states as $state) { ?>
                            <option value="<?php echo encrypt_id($state->state_id); ?>"><?php echo htmlspecialchars($state->state_name); ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="state_error"></span>
                </div>
                <div class="mb-3">
                    <label for="primary_user" class="form-label">Primary User <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="primary_user" name="primary_user">
                        <option value="">Select User</option>
                        <?php foreach ($primary_users as $user) { ?>
                            <option value="<?php echo encrypt_id($user->id); ?>"><?php echo htmlspecialchars($user->full_name); ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="primary_user_error"></span>
                </div>
                <div class="mb-3">
                    <label for="secondary_user" class="form-label">Secondary User</label>
                    <select class="form-select" id="secondary_user" name="secondary_user">
                        <option value="">Select User</option>
                        <?php foreach ($secondary_users as $user) { ?>
                            <option value="<?php echo encrypt_id($user->id); ?>"><?php echo htmlspecialchars($user->full_name); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Active" selected>Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <div id="action-btn-container">
                    <button type="button" id="action-btn" class="btn btn-primary" data-key="">Save changes</button>
                </div>
            </div>
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
    };
</script>

<script>
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 8, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-area-users-table') ?>',
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

    function resetAreaForm() {
        $('.validation').text('');
        $('#area_name').val('');
        $('#area_description').val('');
        $('#state').val('');
        $('#primary_user').val('');
        $('#secondary_user').val('');
        $('#status').val('Active');
    }

    function validateAreaForm() {
        var isValid = true;
        $('.validation').text('');

        if ($('#area_name').val().trim() === '') {
            $('#area_name_error').text('Please enter area name');
            isValid = false;
        }
        if ($('#state').val() === '') {
            $('#state_error').text('Please select state');
            isValid = false;
        }
        if ($('#primary_user').val() === '') {
            $('#primary_user_error').text('Please select primary user');
            isValid = false;
        }

        return isValid;
    }

    function ensureSelectedOption(selector, value, text) {
        if (value !== '' && $(selector + ' option[value="' + value + '"]').length === 0) {
            $(selector).append(new Option(text || 'Selected', value));
        }
        $(selector).val(value);
    }

    $('#open-area-modal').click(function(e) {
        e.preventDefault();
        resetAreaForm();
        $('#crud-modal-title').text('Add Area');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#area-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateAreaForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('area_name', $('#area_name').val());
        formData.append('area_description', $('#area_description').val());
        formData.append('state', $('#state').val());
        formData.append('primary_user', $('#primary_user').val());
        formData.append('secondary_user', $('#secondary_user').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'area-users-add' : 'area-users-update'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('#action-btn-container').html('<button type="button" class="btn btn-primary">' + ((key !== '') ? 'Updating..' : 'Saving..') + '</button>');
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                if (response.status) {
                    toastr.success(response.message);
                    $('#area-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save area');
                }
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary">' + btn_txt + '</button>');
            }
        });
    });

    $(document).on('click', '.edit-area', function() {
        resetAreaForm();

        $.ajax({
            url: '<?php echo base_url('area-users-details') ?>',
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
                    toastr.error(response.message || 'Failed to fetch area details');
                    return;
                }

                $('#crud-modal-title').text('Edit Area');
                $('#area_name').val(response.data.area_name);
                $('#area_description').val(response.data.area_description);
                ensureSelectedOption('#state', response.data.state, response.data.state_name);
                ensureSelectedOption('#primary_user', response.data.primary_user, response.data.primary_user_name);
                ensureSelectedOption('#secondary_user', response.data.secondary_user, response.data.secondary_user_name);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#area-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-area', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this record!',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('area-users-delete') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        if (response.csrfHash) {
                            window.CSRF.hash = response.csrfHash;
                        }
                        if (response.status) {
                            toastr.success(response.message);
                            data_table.draw();
                        } else {
                            toastr.error(response.message || 'Delete failed');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            }
        });
    });
</script>
