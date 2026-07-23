<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-th-list"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Room Types</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Room Type Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img/rooms.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Manage Room Types</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-roomtype-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Hotel Name</th>
                                            <th>RoomType Code</th>
                                            <th>RoomType Name</th>
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

<div class="modal modal-lg new_modal_design" id="roomtype-crud-modal" tabindex="-1" role="dialog" aria-labelledby="roomtype-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-th-list"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="crud-modal-title"></h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a room type.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img/rooms.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3 row">
                <div class="col-sm-6 mb-3">
                    <label>Parent Hotel <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="hotel_id" name="hotel_id">
                        <option value="">Select Hotel</option>
                        <?php foreach ($hotels as $each) { ?>
                            <option value="<?php echo encrypt_id($each->hotel_id); ?>"><?php echo $each->hotel_name; ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="hotel_id_error"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">RoomType Code <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="roomtype_code" placeholder="Please Enter RoomType Code">
                    <span id="roomtype_code_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">RoomType Name <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="roomtype_name" placeholder="Please Enter Room Type Name">
                    <span id="roomtype_name_error" class="validation text-danger"></span>
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
            { targets: 6, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-roomtypes-table') ?>',
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

    function resetRoomTypeForm() {
        $('.validation').text('');
        $('#hotel_id').val('');
        $('#roomtype_code').val('');
        $('#roomtype_name').val('');
    }

    function validateRoomTypeForm() {
        var isValid = true;
        var fields = [
            ['hotel_id', 'hotel_id_error', 'Please select hotel'],
            ['roomtype_code', 'roomtype_code_error', 'Please enter room type code'],
            ['roomtype_name', 'roomtype_name_error', 'Please enter room type name']
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

    $('#open-roomtype-modal').click(function(e) {
        e.preventDefault();
        resetRoomTypeForm();
        $('#crud-modal-title').text('Add New RoomType');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#roomtype-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateRoomTypeForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('hotel_id', $('#hotel_id').val());
        formData.append('roomtype_code', $('#roomtype_code').val());
        formData.append('roomtype_name', $('#roomtype_name').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('record_id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'insert-roomtype' : 'update-roomtype'),
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
                    $('#roomtype-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save room type');
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

    $(document).on('click', '.edit-roomtype', function(e) {
        e.preventDefault();
        resetRoomTypeForm();

        $.ajax({
            url: '<?php echo base_url('edit-roomtype') ?>',
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
                    toastr.error(res.message || 'Unable to load room type details');
                    return;
                }

                $('#crud-modal-title').text('Edit RoomType');
                if ($('#hotel_id option[value="' + res.data.hotel_id + '"]').length === 0) {
                    $('#hotel_id').append(new Option(res.data.hotel_name, res.data.hotel_id));
                }
                $('#hotel_id').val(res.data.hotel_id);
                $('#roomtype_code').val(res.data.roomtype_code);
                $('#roomtype_name').val(res.data.roomtype_name);
                $('#action-btn').text('Update').attr('data-key', res.id);
                $('#roomtype-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-roomtype', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This room type will be removed from the active room type list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('delete-roomtype') ?>',
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
                            toastr.error(res.message || 'Failed to delete room type');
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
