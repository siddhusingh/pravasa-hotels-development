<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Tables</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Table Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Table Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-table-modal">Add Table +</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Restaurant</th>
                                    <th>Category</th>
                                    <th>Table Name</th>
                                    <th>Table Number</th>
                                    <th>Capacity</th>
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

<div class="modal modal-lg new_modal_design" id="table-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-table"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a table.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body ps-3 pe-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Restaurant <span class="required-asterisk">*</span></label>
                        <select class="form-select" id="restaurant_id" name="restaurant_id">
                            <option value="">Select Restaurant</option>
                            <?php foreach ($restaurants as $r) { ?>
                                <option value="<?= $r->id ?>"><?= $r->restaurant_name ?></option>
                            <?php } ?>
                        </select>
                        <span class="validation text-danger" id="restaurant_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Category <span class="required-asterisk">*</span></label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $c) { ?>
                                <option value="<?= $c->id ?>"><?= $c->category_name ?></option>
                            <?php } ?>
                        </select>
                        <span class="validation text-danger" id="category_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Table Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="table_name" name="table_name">
                        <span class="validation text-danger" id="table_name_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Table Number <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="table_number" name="table_number">
                        <span class="validation text-danger" id="table_number_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Capacity <span class="required-asterisk">*</span></label>
                        <input type="number" class="form-control" id="capacity" name="capacity">
                        <span class="validation text-danger" id="capacity_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: 'toast-top-right',
        timeOut: '5000'
    };
</script>

<script>
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 9, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-tables-table') ?>',
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

    function resetTableForm() {
        $('.validation').text('');
        $('#restaurant_id').val('');
        $('#category_id').val('');
        $('#table_name').val('');
        $('#table_number').val('');
        $('#capacity').val('');
        $('#status').val('active');
    }

    function validateTableForm() {
        var isValid = true;
        var fields = [
            ['restaurant_id', 'restaurant_id_error', 'Please select restaurant'],
            ['category_id', 'category_id_error', 'Please select category'],
            ['table_name', 'table_name_error', 'Please enter table name'],
            ['table_number', 'table_number_error', 'Please enter table number'],
            ['capacity', 'capacity_error', 'Please enter capacity']
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

    $('#open-table-modal').click(function(e) {
        e.preventDefault();
        resetTableForm();
        $('#crud-modal-title').text('Add Table');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#table-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateTableForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();

        formData.append('restaurant_id', $('#restaurant_id').val());
        formData.append('category_id', $('#category_id').val());
        formData.append('table_name', $('#table_name').val());
        formData.append('table_number', $('#table_number').val());
        formData.append('capacity', $('#capacity').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'tables-add' : 'tables-update'),
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
                    $('#table-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save table');
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

    $(document).on('click', '.edit-table', function() {
        resetTableForm();

        $.ajax({
            url: '<?php echo base_url('tables-details') ?>',
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

                $('#crud-modal-title').text('Edit Table');
                $('#restaurant_id').val(response.data.restaurant_id);
                $('#category_id').val(response.data.category_id);
                $('#table_name').val(response.data.table_name);
                $('#table_number').val(response.data.table_number);
                $('#capacity').val(response.data.capacity);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#table-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Error fetching details');
            }
        });
    });

    $(document).on('click', '.delete-table', function() {
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
                    url: '<?php echo base_url('tables-delete') ?>',
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
