<!-- Content Wrapper. Contains page content -->
<style>
    .error-label {
        font-size: 0.875rem;
        margin-top: 4px;
        display: none;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Manage Facebook Forms for <b><?php echo $department_info->department_name; ?></b></h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Facebook Forms for <b><?php echo $department_info->department_name; ?></b></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Manage Facebook Forms for <b><?php echo $department_info->department_name; ?></b></h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm " id="open-add-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Facebook Form ID</th>
                                            <th>facebook Form Name</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($forms)) {
                                            $number = 1;


                                            foreach ($forms as $each_facebook_form) { ?>
                                                <tr>
                                                    <td><?php echo $number;
                                                        $number++; ?></td>
                                                    <td><?= $each_facebook_form->form_id ?></td>
                                                    <td><?= $each_facebook_form->form_name ?></td>
                                                    <td><?= $each_facebook_form->created_at ?></td>
                                                    <td><?= $each_facebook_form->updated_at ?></td>
                                                    <td class="table-action min-w-100">
                                                        <a href="javascript:void(0)" class="text-fade hover-primary edit-facebook_form" data-record_id="<?php echo $each_facebook_form->id ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                                                                <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                                            </svg>
                                                        </a>
                                                        <a href="javascript:void(0)" class="text-fade hover-primary delete-facebook_form" data-record_id="<?php echo $each_facebook_form->id ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
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
<!-- Add Facebook Form Modal -->
<!-- Add Facebook Form Modal -->
<div class="modal modal-lg" id="addFormModal" tabindex="-1" aria-labelledby="addFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class=" modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addFormModalLabel">Add Facebook Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addForm" method="post" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="department_id" name="department_id" value="<?= $department_id ?>">

                    <div class="mb-3">
                        <label for="form_id" class="form-label">Form ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="form_id" name="form_id" placeholder="Enter form ID">
                        <div class="text-danger error-label" id="form_id_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="form_name" class="form-label">Form Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="form_name" name="form_name" placeholder="Enter form name">
                        <div class="text-danger error-label" id="form_name_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Form</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Edit Facebook Form Modal -->
<div class="modal modal-lg" id="editFormModal" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class=" modal-content">
            <form id="editForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFormLabel">Edit Facebook Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_department_id" name="department_id" value="<?= $department_id ?>">

                    <div class="mb-3">
                        <label for="edit_form_id" class="form-label">Form ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_form_id" name="form_id" placeholder="Enter form ID">
                        <small class="text-danger error-message" id="edit_form_id_error"></small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_form_name" class="form-label">Form Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_form_name" name="form_name" placeholder="Enter form name">
                        <small class="text-danger error-message" id="edit_form_name_error"></small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Form</button>
                </div>
            </form>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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



    <?php if ($this->session->flashdata('facebook_form_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('facebook_form_success_msg'); ?>')

    <?php $this->session->set_flashdata('facebook_form_success_msg', '');
    } ?>
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', '#open-add-modal', function() {
            // Reset the form fields
            $('#addForm')[0].reset();
            $('.error-message').text(''); // Clear any previous errors
            $('#addFormModal').modal('show');
        });

        $('#addForm').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.error-label').text('').hide();
            $('input, select').removeClass('is-invalid');

            let hasError = false;

            let form_id = $('#form_id').val().trim();
            let form_name = $('#form_name').val().trim();

            // Field validation
            if (form_id === '') {
                $('#form_id_error').text('Form ID is required').show();
                $('#form_id').addClass('is-invalid');
                hasError = true;
            }

            if (form_name === '') {
                $('#form_name_error').text('Form Name is required').show();
                $('#form_name').addClass('is-invalid');
                hasError = true;
            }

            if (hasError) return false;

            // Disable submit button
            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Adding...');

            $.ajax({
                url: "<?= base_url('superAdmin/FacebookformController/addForm') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message || 'Form added successfully!');

                        // Close modal
                        $('#addFormModal').modal('hide');

                        // Reset form
                        $('#addForm')[0].reset();

                        // Refresh table content
                        refreshFormTable();
                    } else if (response.errors) {
                        // Handle server-side validation errors (if returned)
                        $.each(response.errors, function(key, val) {
                            $('#' + key + '_error').text(val).show();
                            $('#' + key).addClass('is-invalid');
                        });
                    } else {
                        toastr.error(response.message || 'Something went wrong!');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Add Form');
                }
            });
        });

    });


    $(document).ready(function() {

        // 🟢 Open Edit Modal and Load Data
        $(document).on('click', '.edit-facebook_form', function() {
            const record_id = $(this).data('record_id');

            $.ajax({
                url: "<?= base_url('superAdmin/FacebookformController/getFormDetails') ?>",
                type: "POST",
                data: {
                    id: record_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        const data = response.data;
                        $('#edit_id').val(data.id);
                        $('#edit_form_id').val(data.form_id);
                        $('#edit_form_name').val(data.form_name);
                        $('#edit_status').val(data.status);

                        $('.error-message').text(''); // clear errors
                        $('#editFormModal').modal('show');
                    } else {
                        toastr.error(response.message || 'Failed to fetch form details');
                    }
                },
                error: function() {
                    toastr.error('Error fetching form details');
                }
            });
        });


        // 🟢 Handle Edit Form Submit
        $('#editForm').on('submit', function(e) {
            e.preventDefault();

            // Clear old errors
            $('.error-message').text('');
            $('input, select').removeClass('is-invalid');

            let hasError = false;
            let form_id = $('#edit_form_id').val().trim();
            let form_name = $('#edit_form_name').val().trim();

            if (form_id === '') {
                $('#edit_form_id_error').text('Form ID is required');
                $('#edit_form_id').addClass('is-invalid');
                hasError = true;
            }
            if (form_name === '') {
                $('#edit_form_name_error').text('Form Name is required');
                $('#edit_form_name').addClass('is-invalid');
                hasError = true;
            }

            if (hasError) return false;

            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "<?= base_url('superAdmin/FacebookformController/updateForm') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message || 'Form updated successfully!');
                        $('#editFormModal').modal('hide');
                        refreshFormTable();
                    } else if (response.errors) {
                        $.each(response.errors, function(key, val) {
                            $('#edit_' + key + '_error').text(val);
                            $('#edit_' + key).addClass('is-invalid');
                        });
                    } else {
                        toastr.error(response.message || 'Update failed!');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Update Form');
                }
            });
        });

    });


    // Delete Form
    $(document).on('click', '.delete-facebook_form', function() {
        let formId = $(this).data('record_id');

        // Sweet and clean confirmation
        if (!confirm('Are you sure you want to delete this form?')) {
            return;
        }

        $.ajax({
            url: "<?= base_url('superAdmin/FacebookformController/deleteForm') ?>",
            type: "POST",
            data: {
                id: formId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message || 'Form deleted successfully');
                    refreshFormTable(); // Refresh table
                } else {
                    toastr.error(response.message || 'Failed to delete form');
                }
            },
            error: function() {
                toastr.error('Server error! Please try again.');
            }
        });
    });



    function refreshFormTable() {
        var department_id = $("#department_id").val(); // get department id from hidden input

        $.ajax({
            url: "<?= base_url('superAdmin/FacebookformController/fetchForms') ?>",
            type: "POST",
            data: {
                department_id: department_id
            },
            success: function(data) {
                $('#complex_header').html(data);
            },
            error: function() {
                console.error('Error refreshing table');
            }
        });
    }
</script>