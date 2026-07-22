<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-facebook"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Facebook Forms</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Department Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active"><?= html_escape($department_info->department_name) ?> Facebook Forms</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img/feedback_img.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">
                                Manage Facebook Forms for <b><?= html_escape($department_info->department_name) ?></b>
                            </h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-facebook-form-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="facebook-forms-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Facebook Form ID</th>
                                            <th>Facebook Form Name</th>
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

<!-- Add/Edit Facebook Form Modal -->
<div class="modal modal-lg new_modal_design" id="facebook-form-crud-modal" tabindex="-1" role="dialog" aria-labelledby="facebook-form-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-facebook"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="facebook-form-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Enter the Facebook form details for <?= html_escape($department_info->department_name) ?>.
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
                    <label for="facebook_form_id" class="form-label">
                        Facebook Form ID <span class="text-danger">*</span>
                    </label>
                    <input class="form-control" type="text" id="facebook_form_id" maxlength="255" placeholder="Enter Facebook Form ID" autocomplete="off">
                    <span id="facebook_form_id_error" class="validation text-danger"></span>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="facebook_form_name" class="form-label">
                        Facebook Form Name <span class="text-danger">*</span>
                    </label>
                    <input class="form-control" type="text" id="facebook_form_name" maxlength="255" placeholder="Enter Facebook Form Name" autocomplete="off">
                    <span id="facebook_form_name_error" class="validation text-danger"></span>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="facebook_form_status" class="form-label">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-control" id="facebook_form_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span id="facebook_form_status_error" class="validation text-danger"></span>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <div id="facebook-form-action-container">
                    <button type="button" id="facebook-form-action" class="btn btn-primary" data-key="">Create</button>
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
        debug: false,
        newestOnTop: false,
        progressBar: false,
        positionClass: 'toast-top-right',
        preventDuplicates: false,
        showDuration: '1000',
        hideDuration: '1000',
        timeOut: '5000',
        extendedTimeOut: '1000',
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    const facebookFormsDepartment = <?= json_encode($department_token) ?>;

    function updateFacebookFormsCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function clearFacebookFormErrors() {
        $('#facebook_form_id_error, #facebook_form_name_error, #facebook_form_status_error').text('');
        $('#facebook_form_id, #facebook_form_name, #facebook_form_status').removeClass('is-invalid');
    }

    function resetFacebookFormModal() {
        clearFacebookFormErrors();
        $('#facebook_form_id').val('');
        $('#facebook_form_name').val('');
        $('#facebook_form_status').val('1');
        $('#facebook-form-action').attr('data-key', '');
    }

    function showFacebookFormError(selector, errorSelector, message) {
        $(selector).addClass('is-invalid');
        $(errorSelector).text(message);
    }

    function validateFacebookForm() {
        clearFacebookFormErrors();

        const formId = $('#facebook_form_id').val().trim();
        const formName = $('#facebook_form_name').val().trim();
        const status = $('#facebook_form_status').val();
        let valid = true;

        if (formId === '') {
            showFacebookFormError('#facebook_form_id', '#facebook_form_id_error', 'Please enter Facebook Form ID');
            valid = false;
        } else if (!/^[A-Za-z0-9_-]+$/.test(formId)) {
            showFacebookFormError('#facebook_form_id', '#facebook_form_id_error', 'Use only letters, numbers, hyphens and underscores');
            valid = false;
        }

        if (formName === '') {
            showFacebookFormError('#facebook_form_name', '#facebook_form_name_error', 'Please enter Facebook Form Name');
            valid = false;
        } else if (formName.length < 2) {
            showFacebookFormError('#facebook_form_name', '#facebook_form_name_error', 'Facebook Form Name must contain at least 2 characters');
            valid = false;
        } else if (/<[^>]*>/.test(formName)) {
            showFacebookFormError('#facebook_form_name', '#facebook_form_name_error', 'Facebook Form Name contains invalid content');
            valid = false;
        }

        if (status !== '0' && status !== '1') {
            showFacebookFormError('#facebook_form_status', '#facebook_form_status_error', 'Please select a valid status');
            valid = false;
        }

        return valid;
    }

    const facebookFormsTable = $('#facebook-forms-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: '<?= base_url('facebook-forms-data-table') ?>',
            type: 'POST',
            data: function(data) {
                data.department = facebookFormsDepartment;
                data[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function(response) {
                updateFacebookFormsCsrf(response);
                if (response.status === false && response.message) {
                    toastr.error(response.message);
                }
                return response.data || [];
            },
            error: function() {
                toastr.error('Unable to load Facebook forms');
            }
        }
    });

    $('#open-facebook-form-modal').on('click', function(event) {
        event.preventDefault();
        resetFacebookFormModal();
        $('#facebook-form-modal-title').text('Add Facebook Form');
        $('#facebook-form-action').text('Create').attr('data-key', '');
        $('#facebook-form-crud-modal').modal('show');
    });

    $('#facebook_form_id, #facebook_form_name, #facebook_form_status').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#' + this.id + '_error').text('');
    });

    $(document).on('click', '#facebook-form-action', function(event) {
        event.preventDefault();

        if (!validateFacebookForm()) {
            return;
        }

        const recordToken = $(this).attr('data-key');
        const buttonText = $(this).text();
        const formData = new FormData();
        formData.append('department', facebookFormsDepartment);
        formData.append('form_id', $('#facebook_form_id').val().trim());
        formData.append('form_name', $('#facebook_form_name').val().trim());
        formData.append('status', $('#facebook_form_status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (recordToken !== '') {
            formData.append('id', recordToken);
        }

        $.ajax({
            url: recordToken === '' ? '<?= base_url('facebook-form-add') ?>' : '<?= base_url('facebook-form-update') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#facebook-form-action-container').html(
                    '<button type="button" class="btn btn-primary" disabled>' +
                    (recordToken === '' ? 'Saving...' : 'Updating...') +
                    '</button>'
                );
            },
            success: function(response) {
                updateFacebookFormsCsrf(response);
                if (response.status) {
                    toastr.success(response.message);
                    $('#facebook-form-crud-modal').modal('hide');
                    facebookFormsTable.draw(false);
                } else {
                    toastr.error(response.message || 'Unable to save Facebook form');
                }
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                const button = $('<button>', {
                    type: 'button',
                    id: 'facebook-form-action',
                    class: 'btn btn-primary',
                    text: buttonText
                }).attr('data-key', recordToken);
                $('#facebook-form-action-container').empty().append(button);
            }
        });
    });

    $(document).on('click', '.edit-facebook-form', function(event) {
        event.preventDefault();
        const recordToken = $(this).attr('data-record_id');

        $.ajax({
            url: '<?= base_url('facebook-form-details') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id: recordToken,
                department: facebookFormsDepartment,
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(response) {
                updateFacebookFormsCsrf(response);
                if (!response.status) {
                    toastr.error(response.message || 'Unable to load Facebook form');
                    return;
                }

                clearFacebookFormErrors();
                $('#facebook-form-modal-title').text('Edit Facebook Form');
                $('#facebook_form_id').val(response.data.form_id);
                $('#facebook_form_name').val(response.data.form_name);
                $('#facebook_form_status').val(response.data.status);
                $('#facebook-form-action').text('Update').attr('data-key', response.id);
                $('#facebook-form-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Unable to load Facebook form');
            }
        });
    });

    $(document).on('click', '.delete-facebook-form', function() {
        const recordToken = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This Facebook form will be removed from the active list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes, delete it',
            denyButtonText: 'Cancel'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '<?= base_url('facebook-form-delete') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: recordToken,
                    department: facebookFormsDepartment,
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    updateFacebookFormsCsrf(response);
                    if (response.status) {
                        toastr.success(response.message);
                        facebookFormsTable.draw(false);
                    } else {
                        toastr.error(response.message || 'Unable to delete Facebook form');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    });
</script>
