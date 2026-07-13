<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Rate Types</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Rate Type Management</li>
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
                            <h4 class="box-title">Manage Rate Types</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-ratetype-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Ratetype Code</th>
                                            <th>Ratetype Name</th>
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

<div class="modal modal-lg new_modal_design" id="ratetype-crud-modal" tabindex="-1" role="dialog" aria-labelledby="ratetype-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-tags"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="crud-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a rate type.
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
                    <label class="form-label">Ratetype Code <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="ratetype_code" placeholder="Please Enter Ratetype Code">
                    <span id="ratetype_code_error" class="validation text-danger"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ratetype Name <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="ratetype_name" placeholder="Please Enter Rate Type Name">
                    <span id="ratetype_name_error" class="validation text-danger"></span>
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
            { targets: 5, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-ratetypes-table') ?>',
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

    function resetRateTypeForm() {
        $('.validation').text('');
        $('#ratetype_code').val('');
        $('#ratetype_name').val('');
    }

    function validateRateTypeForm() {
        var isValid = true;
        var fields = [
            ['ratetype_code', 'ratetype_code_error', 'Please enter rate type code'],
            ['ratetype_name', 'ratetype_name_error', 'Please enter rate type name']
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

    $('#open-ratetype-modal').click(function(e) {
        e.preventDefault();
        resetRateTypeForm();
        $('#crud-modal-title').text('Add New Ratetype');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#ratetype-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateRateTypeForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('ratetype_code', $('#ratetype_code').val());
        formData.append('ratetype_name', $('#ratetype_name').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('record_id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'insert-ratetype' : 'update-ratetype'),
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
                    $('#ratetype-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save rate type');
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

    $(document).on('click', '.edit-ratetype', function(e) {
        e.preventDefault();
        resetRateTypeForm();

        $.ajax({
            url: '<?php echo base_url('edit-ratetype') ?>',
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
                    toastr.error(res.message || 'Unable to load rate type details');
                    return;
                }

                $('#crud-modal-title').text('Edit Ratetype');
                $('#ratetype_code').val(res.data.ratetype_code);
                $('#ratetype_name').val(res.data.ratetype_name);
                $('#action-btn').text('Update').attr('data-key', res.id);
                $('#ratetype-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-ratetype', function() {
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
                    url: '<?php echo base_url('delete-ratetype') ?>',
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
                            toastr.error(res.message || 'Failed to delete rate type');
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
