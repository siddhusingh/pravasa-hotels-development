<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-building"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Manage Banquet</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Manage Banquet</li>
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
                    <h4 class="box-title">Manage Banquet</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-banquet-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="hotel-banquets-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Hotel Name</th>
                                    <th>Banquet Code</th>
                                    <th>Banquet Name</th>
                                    <th>Capacity</th>
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

<div class="modal modal-lg new_modal_design" id="banquet-crud-modal" tabindex="-1" role="dialog" aria-labelledby="banquet-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-building"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="banquet-crud-modal-label"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Fill in the details to add or update a banquet.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3 row">
                <div class="col-md-6 mb-3">
                    <label for="hotel_id">Parent Hotel <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="hotel_id" name="hotel_id" disabled aria-disabled="true">
                        <option value="<?php echo encrypt_id($hotel->hotel_id); ?>" selected>
                            <?php echo htmlspecialchars((string) $hotel->hotel_name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    </select>
                    <small class="text-muted">Your assigned hotel is selected automatically.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="banquet_code">Banquet Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="banquet_code" name="banquet_code" placeholder="Enter Banquet Code">
                    <div class="text-danger validation" id="banquet_code_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="banquet_name">Banquet Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="banquet_name" name="banquet_name" placeholder="Enter Banquet Name">
                    <div class="text-danger validation" id="banquet_name_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="capacity">Capacity <span class="required-asterisk">*</span></label>
                    <input type="number" min="1" step="1" class="form-control" id="capacity" name="capacity" placeholder="Enter Capacity">
                    <div class="text-danger validation" id="capacity_error"></div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="banquet-action-btn" class="btn btn-primary" data-record-id="">Add Banquet</button>
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

        var banquetTable = $('#hotel-banquets-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [{ targets: 5, orderable: false }],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-banquets-table'); ?>',
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

        function resetBanquetForm() {
            $('.validation').text('');
            $('#banquet_code, #banquet_name, #capacity').val('');
            $('#banquet-action-btn').attr('data-record-id', '').text('Add Banquet').prop('disabled', false);
        }

        function validateBanquetForm() {
            var fields = [
                ['banquet_code', 'Banquet code is required'],
                ['banquet_name', 'Banquet name is required'],
                ['capacity', 'Capacity is required']
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

            var capacity = $('#capacity').val();
            if (capacity !== '' && (!/^\d+$/.test(capacity) || Number(capacity) < 1)) {
                $('#capacity_error').text('Capacity must be a positive whole number');
                isValid = false;
            }

            return isValid;
        }

        $('#banquet_code, #banquet_name, #capacity').on('input change', function() {
            $('#' + this.id + '_error').text('');
        });

        $('#open-banquet-modal').on('click', function(event) {
            event.preventDefault();
            resetBanquetForm();
            $('#banquet-crud-modal-label').text('Add New Banquet');
            $('#banquet-crud-modal').modal('show');
        });

        $(document).on('click', '#banquet-action-btn', function(event) {
            event.preventDefault();
            if (!validateBanquetForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('banquet_code', $('#banquet_code').val());
            formData.append('banquet_name', $('#banquet_name').val());
            formData.append('capacity', $('#capacity').val());
            formData.append(window.CSRF.name, window.CSRF.hash);
            if (isUpdate) {
                formData.append('record_id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'update-banquet' : 'insert-banquet'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text(isUpdate ? 'Updating...' : 'Adding...');
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status === true) {
                        toastr.success(response.message);
                        $('#banquet-crud-modal').modal('hide');
                        banquetTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Unable to save banquet');
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

        $(document).on('click', '.edit-banquet', function(event) {
            event.preventDefault();
            resetBanquetForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/edit-banquet'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status !== true) {
                        toastr.error(response.message || 'Unable to load banquet details');
                        return;
                    }

                    $('#banquet_code').val(response.data.banquet_code);
                    $('#banquet_name').val(response.data.banquet_name);
                    $('#capacity').val(response.data.capacity);
                    $('#banquet-crud-modal-label').text('Edit Banquet');
                    $('#banquet-action-btn').attr('data-record-id', response.id).text('Update Banquet');
                    $('#banquet-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Error fetching banquet details');
                }
            });
        });

        $(document).on('click', '.delete-banquet', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This banquet will be removed from the active banquet list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/delete-banquet'); ?>',
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
                            banquetTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Unable to delete banquet');
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
