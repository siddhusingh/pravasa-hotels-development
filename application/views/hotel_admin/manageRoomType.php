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
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Room Type Management</li>
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
                            <h4 class="box-title">Manage Room Types</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-roomtype-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="hotel-roomtypes-table" class="text-fade table table-bordered display" style="width:100%">
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

<div class="modal modal-lg new_modal_design" id="roomtype-crud-modal" tabindex="-1" role="dialog" aria-labelledby="roomtype-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-th-list"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="roomtype-crud-modal-label"></h4>
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
                    <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3 row">
                <div class="col-sm-6 mb-3">
                    <label for="hotel_id">Parent Hotel <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="hotel_id" name="hotel_id" disabled aria-disabled="true">
                        <option value="<?php echo encrypt_id($hotel->hotel_id); ?>" selected>
                            <?php echo htmlspecialchars((string) $hotel->hotel_name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    </select>
                    <small class="text-muted">Your assigned hotel is selected automatically.</small>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="roomtype_code">RoomType Code <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="roomtype_code" placeholder="Please Enter RoomType Code">
                    <span id="roomtype_code_error" class="validation text-danger"></span>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="roomtype_name">RoomType Name <span class="required-asterisk">*</span></label>
                    <input class="form-control" type="text" id="roomtype_name" placeholder="Please Enter Room Type Name">
                    <span id="roomtype_name_error" class="validation text-danger"></span>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="roomtype-action-btn" class="btn btn-primary" data-record-id="">Create</button>
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

        var roomTypesTable = $('#hotel-roomtypes-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [
                { targets: 6, orderable: false }
            ],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-roomtypes-table'); ?>',
                type: 'POST',
                data: function(data) {
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function(response) {
                    refreshCsrf(response);
                    if (response.status === false && response.message) {
                        toastr.error(response.message);
                    }
                    return response.data || [];
                }
            }
        });

        function refreshCsrf(response) {
            if (response && response.csrfHash) {
                window.CSRF.hash = response.csrfHash;
            }
        }

        function resetRoomTypeForm() {
            $('.validation').text('');
            $('#roomtype_code').val('');
            $('#roomtype_name').val('');
            $('#roomtype-action-btn').data('record-id', '').attr('data-record-id', '').text('Create').prop('disabled', false);
        }

        function validateRoomTypeForm() {
            var isValid = true;
            var fields = [
                ['roomtype_code', 'roomtype_code_error', 'Please enter room type code'],
                ['roomtype_name', 'roomtype_name_error', 'Please enter room type name']
            ];

            fields.forEach(function(field) {
                var value = $.trim($('#' + field[0]).val());
                $('#' + field[1]).text(value === '' ? field[2] : '');
                if (value === '') {
                    isValid = false;
                }
            });

            return isValid;
        }

        $('#open-roomtype-modal').on('click', function(event) {
            event.preventDefault();
            resetRoomTypeForm();
            $('#roomtype-crud-modal-label').text('Add New RoomType');
            $('#roomtype-crud-modal').modal('show');
        });

        $(document).on('click', '#roomtype-action-btn', function(event) {
            event.preventDefault();

            if (!validateRoomTypeForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('roomtype_code', $.trim($('#roomtype_code').val()));
            formData.append('roomtype_name', $.trim($('#roomtype_name').val()));
            formData.append(window.CSRF.name, window.CSRF.hash);

            if (isUpdate) {
                formData.append('record_id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'update-roomtype' : 'insert-roomtype'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text(isUpdate ? 'Updating..' : 'Saving..');
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status) {
                        toastr.success(response.message);
                        $('#roomtype-crud-modal').modal('hide');
                        roomTypesTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Failed to save room type');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                },
                complete: function() {
                    button.prop('disabled', false).text(buttonText);
                }
            });
        });

        $(document).on('click', '.edit-roomtype', function(event) {
            event.preventDefault();
            resetRoomTypeForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/edit-roomtype'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (!response.status) {
                        toastr.error(response.message || 'Unable to load room type details');
                        return;
                    }

                    $('#roomtype-crud-modal-label').text('Edit RoomType');
                    $('#roomtype_code').val(response.data.roomtype_code);
                    $('#roomtype_name').val(response.data.roomtype_name);
                    $('#roomtype-action-btn').attr('data-record-id', response.id).text('Update');
                    $('#roomtype-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });

        $(document).on('click', '.delete-roomtype', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This room type will be removed from the active room type list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/delete-roomtype'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: recordId,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        refreshCsrf(response);
                        if (response.status) {
                            toastr.success(response.message);
                            roomTypesTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Failed to delete room type');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });
    });
</script>
