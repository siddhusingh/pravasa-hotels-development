<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-sitemap"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Manage Departments</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Departments</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?php echo base_url('assets/new_img/department_img.png'); ?>" alt=""></div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Manage Departments</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-department-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body last_active_design_td">
                    <div class="table-responsive">
                        <table id="hotel-departments-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Department Name</th>
                                    <th>Escalation Level 1</th>
                                    <th>Escalation Level 2</th>
                                    <th>Escalation Level 3</th>
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

<div class="modal modal-lg new_modal_design" id="department-crud-modal" tabindex="-1" role="dialog" aria-labelledby="department-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-sitemap"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="department-crud-modal-label"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Fill in the details to add or update a department.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?php echo base_url('assets/new_img/department_img.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="department_name">Department Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="department_name" placeholder="Department Name">
                        <div id="department_name_error" class="validation text-danger"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="escalation_level_1">Level 1 Escalation <span class="required-asterisk">*</span></label>
                        <input type="number" min="0" step="any" class="form-control" id="escalation_level_1" placeholder="Escalation in Hours">
                        <div id="escalation_level_1_error" class="validation text-danger"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="escalation_level_2">Level 2 Escalation <span class="required-asterisk">*</span></label>
                        <input type="number" min="0" step="any" class="form-control" id="escalation_level_2" placeholder="Escalation in Hours">
                        <div id="escalation_level_2_error" class="validation text-danger"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="escalation_level_3">Level 3 Escalation <span class="required-asterisk">*</span></label>
                        <input type="number" min="0" step="any" class="form-control" id="escalation_level_3" placeholder="Escalation in Hours">
                        <div id="escalation_level_3_error" class="validation text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="department-action-btn" class="btn btn-primary" data-record-id="">Create</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript">
    window.CSRF = {
        name: <?php echo json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?php echo json_encode($this->security->get_csrf_hash()); ?>
    };

    window.addEventListener('load', function() {
        toastr.options = {
            closeButton: true,
            positionClass: 'toast-top-right',
            timeOut: '5000',
            preventDuplicates: true
        };

        function refreshCsrf(response) {
            if (response && response.csrfHash) {
                window.CSRF.hash = response.csrfHash;
            }
        }

        var departmentTable = $('#hotel-departments-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [{ targets: 5, orderable: false }],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-departments-table'); ?>',
                type: 'POST',
                data: function(data) {
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function(response) {
                    refreshCsrf(response);
                    return response.data || [];
                }
            }
        });

        function resetDepartmentForm() {
            $('.validation').text('');
            $('#department_name, #escalation_level_1, #escalation_level_2, #escalation_level_3').val('');
            $('#department-action-btn').attr('data-record-id', '').text('Create').prop('disabled', false);
        }

        function validateDepartmentForm() {
            var fields = [
                ['department_name', 'Department name is required'],
                ['escalation_level_1', 'Level 1 escalation is required'],
                ['escalation_level_2', 'Level 2 escalation is required'],
                ['escalation_level_3', 'Level 3 escalation is required']
            ];
            var isValid = true;

            fields.forEach(function(field) {
                var value = $.trim($('#' + field[0]).val());
                $('#' + field[0]).val(value);
                $('#' + field[0] + '_error').text(value === '' ? field[1] : '');
                if (value === '') {
                    isValid = false;
                }
            });

            ['escalation_level_1', 'escalation_level_2', 'escalation_level_3'].forEach(function(field) {
                var value = $('#' + field).val();
                if (value !== '' && (!Number.isFinite(Number(value)) || Number(value) < 0)) {
                    $('#' + field + '_error').text('Escalation level must be zero or greater');
                    isValid = false;
                }
            });

            return isValid;
        }

        $('#department_name, #escalation_level_1, #escalation_level_2, #escalation_level_3').on('input change', function() {
            $('#' + this.id + '_error').text('');
        });

        $('#open-department-modal').on('click', function(event) {
            event.preventDefault();
            resetDepartmentForm();
            $('#department-crud-modal-label').text('Add New Department');
            $('#department-crud-modal').modal('show');
        });

        $(document).on('click', '#department-action-btn', function(event) {
            event.preventDefault();
            if (!validateDepartmentForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('department_name', $('#department_name').val());
            formData.append('escalation_level_1', $('#escalation_level_1').val());
            formData.append('escalation_level_2', $('#escalation_level_2').val());
            formData.append('escalation_level_3', $('#escalation_level_3').val());
            formData.append(window.CSRF.name, window.CSRF.hash);
            if (isUpdate) {
                formData.append('record_id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'update-department' : 'insert-department'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text(isUpdate ? 'Updating...' : 'Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status === true) {
                        toastr.success(response.message);
                        $('#department-crud-modal').modal('hide');
                        departmentTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Unable to save department');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again.');
                },
                complete: function() {
                    button.prop('disabled', false).text(buttonText);
                }
            });
        });

        $(document).on('click', '.edit-department', function(event) {
            event.preventDefault();
            resetDepartmentForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/edit-department'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status !== true) {
                        toastr.error(response.message || 'Unable to load department details');
                        return;
                    }

                    $('#department_name').val(response.data.department_name);
                    $('#escalation_level_1').val(response.data.escalation_level_1);
                    $('#escalation_level_2').val(response.data.escalation_level_2);
                    $('#escalation_level_3').val(response.data.escalation_level_3);
                    $('#department-crud-modal-label').text('Edit Department');
                    $('#department-action-btn').attr('data-record-id', response.id).text('Update');
                    $('#department-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Error fetching department details');
                }
            });
        });

        $(document).on('click', '.delete-department', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This department will be removed from the active department list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/delete-department'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: recordId,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        refreshCsrf(response);
                        if (response.status === true) {
                            toastr.success(response.message);
                            departmentTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Unable to delete department');
                        }
                    },
                    error: function() {
                        toastr.error('Server error! Please try again.');
                    }
                });
            });
        });
    });
</script>
