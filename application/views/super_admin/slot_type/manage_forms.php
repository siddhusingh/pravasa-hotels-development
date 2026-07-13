<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Slot Types</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Slot Type Management</li>
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
                            <h4 class="box-title">Manage Slot Types</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-slot-type-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Slot Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
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

<div class="modal modal-lg new_modal_design" id="slot-type-crud-modal" tabindex="-1" aria-labelledby="slot-type-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a slot type.
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
                    <label for="slot_name" class="form-label">Slot Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="slot_name" name="slot_name" placeholder="Enter slot name">
                    <span class="validation text-danger" id="slot_name_error"></span>
                </div>
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time <span class="required-asterisk">*</span></label>
                    <input type="time" class="form-control" id="start_time" name="start_time">
                    <span class="validation text-danger" id="start_time_error"></span>
                </div>
                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time <span class="required-asterisk">*</span></label>
                    <input type="time" class="form-control" id="end_time" name="end_time">
                    <span class="validation text-danger" id="end_time_error"></span>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" selected>Active</option>
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
            { targets: 7, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-slot-types-table') ?>',
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

    function resetSlotTypeForm() {
        $('.validation').text('');
        $('#slot_name').val('');
        $('#start_time').val('');
        $('#end_time').val('');
        $('#status').val('1');
    }

    function validateSlotTypeForm() {
        var isValid = true;
        $('.validation').text('');

        if ($('#slot_name').val().trim() === '') {
            $('#slot_name_error').text('Please enter slot name');
            isValid = false;
        }
        if ($('#start_time').val() === '') {
            $('#start_time_error').text('Please select start time');
            isValid = false;
        }
        if ($('#end_time').val() === '') {
            $('#end_time_error').text('Please select end time');
            isValid = false;
        }

        return isValid;
    }

    $('#open-slot-type-modal').click(function(e) {
        e.preventDefault();
        resetSlotTypeForm();
        $('#crud-modal-title').text('Add Slot Type');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#slot-type-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateSlotTypeForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('slot_name', $('#slot_name').val());
        formData.append('start_time', $('#start_time').val());
        formData.append('end_time', $('#end_time').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'slot-types-add' : 'slot-types-update'),
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
                    $('#slot-type-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save slot type');
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

    $(document).on('click', '.edit-slot-type', function() {
        resetSlotTypeForm();

        $.ajax({
            url: '<?php echo base_url('slot-types-details') ?>',
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
                    toastr.error(response.message || 'Failed to fetch slot type details');
                    return;
                }

                $('#crud-modal-title').text('Edit Slot Type');
                $('#slot_name').val(response.data.slot_name);
                $('#start_time').val(response.data.start_time);
                $('#end_time').val(response.data.end_time);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#slot-type-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-slot-type', function() {
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
                    url: '<?php echo base_url('slot-types-delete') ?>',
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
