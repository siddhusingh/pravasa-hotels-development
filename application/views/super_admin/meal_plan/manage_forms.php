<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-cutlery"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Meal Plan Management</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Meal Plan Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img/plan_img.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Manage Meal Plans</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-meal-plan-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Plan Name</th>
                                    <th>Plan Code</th>
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
        </section>
    </div>
</div>

<div class="modal modal-lg new_modal_design" id="meal-plan-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-cutlery"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a meal plan.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body ps-3 pe-3 row">
                <div class="col-sm-6 mb-3">
                    <label>Plan Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="plan_name" name="plan_name">
                    <span class="validation text-danger" id="plan_name_error"></span>
                </div>

                <div class="col-sm-6 mb-3">
                    <label>Plan Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="plan_code" name="plan_code">
                    <span class="validation text-danger" id="plan_code_error"></span>
                </div>

                <div class="col-sm-6 mb-3">
                    <label>Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        positionClass: 'toast-top-right',
        timeOut: '4000'
    };
</script>

<script>
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 6, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-mealplans-table') ?>',
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

    function resetMealPlanForm() {
        $('.validation').text('');
        $('#plan_name').val('');
        $('#plan_code').val('');
        $('#status').val('1');
    }

    function validateMealPlanForm() {
        var isValid = true;

        if ($('#plan_name').val().trim() === '') {
            $('#plan_name_error').text('Please enter plan name');
            isValid = false;
        } else {
            $('#plan_name_error').text('');
        }

        if ($('#plan_code').val().trim() === '') {
            $('#plan_code_error').text('Please enter plan code');
            isValid = false;
        } else {
            $('#plan_code_error').text('');
        }

        return isValid;
    }

    $('#open-meal-plan-modal').click(function(e) {
        e.preventDefault();
        resetMealPlanForm();
        $('#crud-modal-title').text('Add Meal Plan');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#meal-plan-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateMealPlanForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('plan_name', $('#plan_name').val());
        formData.append('plan_code', $('#plan_code').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'mealplans-add' : 'mealplans-update'),
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
                    $('#meal-plan-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save meal plan');
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

    $(document).on('click', '.edit-meal-plan', function() {
        resetMealPlanForm();

        $.ajax({
            url: '<?php echo base_url('mealplans-details') ?>',
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
                    toastr.error(response.message || 'Failed to fetch details');
                    return;
                }

                $('#crud-modal-title').text('Edit Meal Plan');
                $('#plan_name').val(response.data.plan);
                $('#plan_code').val(response.data.plan_code);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#meal-plan-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-meal-plan', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This meal plan will be removed from the active meal plan list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('mealplans-delete') ?>',
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
