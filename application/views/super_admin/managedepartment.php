<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Departments</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Department Management</li>
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
                            <h4 class="box-title">Manage Departments</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-department-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body last_active_design_td">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Department Name</th>
                                            <th>Escalation Level 1</th>
                                            <th>Escalation Level 2</th>
                                            <th>Escalation Level 3</th>
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

<div class="modal modal-lg new_modal_design" id="department-crud-modal" tabindex="-1" role="dialog" aria-labelledby="department-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="crud-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a department.
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="text" id="department_name" placeholder="Department Name">
                            <span id="department_name_error" class="validation text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Level 1 Escalation <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="escalation_level_1" placeholder="Escalation in Hours">
                            <span id="level1_error" class="validation text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Level 2 Escalation <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="escalation_level_2" placeholder="Escalation in Hours">
                            <span id="level2_error" class="validation text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Level 3 Escalation <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="escalation_level_3" placeholder="Escalation in Hours">
                            <span id="level3_error" class="validation text-danger"></span>
                        </div>
                    </div>
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

<script type="text/javascript">
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 7, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-departments-table') ?>',
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

    function resetDepartmentForm() {
        $('.validation').text('');
        $('#department_name').val('');
        $('#escalation_level_1').val('');
        $('#escalation_level_2').val('');
        $('#escalation_level_3').val('');
    }

    function validateDepartmentForm() {
        var isValid = true;
        var fields = [
            ['department_name', 'department_name_error', 'Please enter department name'],
            ['escalation_level_1', 'level1_error', 'Please enter level 1 escalation'],
            ['escalation_level_2', 'level2_error', 'Please enter level 2 escalation'],
            ['escalation_level_3', 'level3_error', 'Please enter level 3 escalation']
        ];

        fields.forEach(function(field) {
            if ($('#' + field[0]).val() === '') {
                $('#' + field[1]).text(field[2]);
                isValid = false;
            } else {
                $('#' + field[1]).text('');
            }
        });

        return isValid;
    }

    $('#open-department-modal').click(function(e) {
        e.preventDefault();
        resetDepartmentForm();
        $('#crud-modal-title').text('Add New Department');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#department-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateDepartmentForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();

        formData.append('department_name', $('#department_name').val());
        formData.append('escalation_level_1', $('#escalation_level_1').val());
        formData.append('escalation_level_2', $('#escalation_level_2').val());
        formData.append('escalation_level_3', $('#escalation_level_3').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('record_id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'insert-department' : 'update-department'),
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
                    $('#department-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save department');
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

    $(document).on('click', '.edit-department', function(e) {
        e.preventDefault();
        resetDepartmentForm();

        $.ajax({
            url: '<?php echo base_url('edit-department') ?>',
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
                    toastr.error(res.message || 'Unable to load department details');
                    return;
                }

                $('#crud-modal-title').text('Edit Department');
                $('#department_name').val(res.data.department_name);
                $('#escalation_level_1').val(res.data.escalation_level_1);
                $('#escalation_level_2').val(res.data.escalation_level_2);
                $('#escalation_level_3').val(res.data.escalation_level_3);
                $('#action-btn').text('Update').attr('data-key', res.id);
                $('#department-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-department', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This department will be removed from the active department list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('delete-department') ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(res) {
                        if (res.csrfHash) {
                            window.CSRF.hash = res.csrfHash;
                        }
                        if (res.status) {
                            toastr.success(res.message);
                            data_table.draw();
                        } else {
                            toastr.error(res.message || 'Failed to delete department');
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
