<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-building"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Banquet</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Banquet Management</li>
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
                            <h4 class="box-title">Manage Banquet</h4>
                            <div class="float-right" style="float:right;">
                                <button class="btn btn-primary-light btn-sm" id="open-banquet-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Hotel Name</th>
                                            <th>Banquet Code</th>
                                            <th>Banquet Name</th>
                                            <th>Capacity</th>
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

<div class="modal modal-lg new_modal_design" id="banquet-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-building"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="crud-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a banquet.
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
                <div class="mb-3">
                    <label>Parent Hotel <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="hotel_id">
                        <option value="">Select Hotel</option>
                        <?php foreach ($hotels as $each) { ?>
                            <option value="<?= encrypt_id($each->hotel_id) ?>"><?= $each->hotel_name ?></option>
                        <?php } ?>
                    </select>
                    <span class="text-danger validation" id="hotel_id_error"></span>
                </div>
                <div class="mb-3">
                    <label>Banquet Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="banquet_code" placeholder="Enter Banquet Code">
                    <span class="text-danger validation" id="banquet_code_error"></span>
                </div>
                <div class="mb-3">
                    <label>Banquet Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="banquet_name" placeholder="Enter Banquet Name">
                    <span class="text-danger validation" id="banquet_name_error"></span>
                </div>
                <div class="mb-3">
                    <label>Capacity <span class="required-asterisk">*</span></label>
                    <input type="number" class="form-control" id="capacity" placeholder="Enter Capacity">
                    <span class="text-danger validation" id="capacity_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="action-btn" data-key="">Save</button>
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
            url: '<?php echo base_url('get-banquets-table') ?>',
            type: 'POST',
            data: function(d) {
                d[window.CSRF.name] = window.CSRF.hash;
            },
            drawCallback: function(setting) {
                if (setting.json && setting.json.csrfHash) {
                    window.CSRF.hash = setting.json.csrfHash;
                }
            },
            dataSrc: function(json) {
                if (json.csrfHash) {
                    window.CSRF.hash = json.csrfHash;
                }
                return json.data;
            }
        }
    });

    function ensureSelectedOption($select, value, label) {
        if (value && !$select.find('option[value="' + value + '"]').length) {
            $select.append($('<option></option>').val(value).text(label));
        }
    }

    function resetBanquetForm() {
        $('#hotel_id, #banquet_name, #banquet_code, #capacity').val('');
        $('.validation').text('');
    }

    function validateBanquetForm() {
        var rules = {
            hotel_id: 'Hotel is required',
            banquet_code: 'Banquet code is required',
            banquet_name: 'Banquet name is required',
            capacity: 'Capacity is required'
        };
        var isValid = true;
        $.each(rules, function(field, message) {
            var value = $.trim($('#' + field).val());
            if (value === '') {
                $('#' + field + '_error').text(message);
                isValid = false;
            } else {
                $('#' + field + '_error').text('');
                $('#' + field).val(value);
            }
        });
        return isValid;
    }

    $('#hotel_id, #banquet_name, #banquet_code, #capacity').on('input change', function() {
        $('#' + this.id + '_error').text('');
    });

    $('#open-banquet-modal').on('click', function(e) {
        e.preventDefault();
        resetBanquetForm();
        $('#crud-modal-title').text('Add New Banquet');
        $('#action-btn').text('Save').attr('data-key', '');
        $('#banquet-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();
        if (!validateBanquetForm()) {
            return;
        }

        var $button = $(this);
        var key = $button.attr('data-key');
        var buttonText = $button.text();
        var formData = new FormData();
        formData.append('hotel_id', $('#hotel_id').val());
        formData.append('banquet_name', $('#banquet_name').val());
        formData.append('banquet_code', $('#banquet_code').val());
        formData.append('capacity', $('#capacity').val());
        formData.append(window.CSRF.name, window.CSRF.hash);
        if (key !== '') {
            formData.append('record_id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'insert-banquet' : 'update-banquet'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $button.prop('disabled', true).text((key === '') ? 'Saving..' : 'Updating..');
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                if (response.status) {
                    toastr.success(response.message);
                    $('#banquet-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save banquet');
                }
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                $button.prop('disabled', false).text(buttonText);
            }
        });
    });

    $(document).on('click', '.edit-banquet', function(e) {
        e.preventDefault();
        resetBanquetForm();
        $.ajax({
            url: '<?php echo base_url('edit-banquet') ?>',
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
                    toastr.error(res.message || 'Unable to load banquet details');
                    return;
                }

                ensureSelectedOption($('#hotel_id'), res.data.hotel_id, res.data.hotel_name);
                $('#hotel_id').val(res.data.hotel_id);
                $('#banquet_name').val(res.data.banquet_name);
                $('#banquet_code').val(res.data.banquet_code);
                $('#capacity').val(res.data.capacity);
                $('#crud-modal-title').text('Edit Banquet');
                $('#action-btn').text('Update').attr('data-key', res.id);
                $('#banquet-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Something went wrong');
            }
        });
    });

    $(document).on('click', '.delete-banquet', function() {
        var $button = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this record!',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('delete-banquet') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: $button.attr('data-record_id'),
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
                            toastr.error(res.message || 'Failed to delete banquet');
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
