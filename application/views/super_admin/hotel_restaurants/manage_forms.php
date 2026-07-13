<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-cutlery"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Restaurants</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Restaurant Management</li>
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
                    <h4 class="box-title">Restaurant Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-add-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Hotel</th>
                                    <th>Restaurant Name</th>
                                    <th>Restaurant Code</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
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

<div class="modal modal-lg new_modal_design" id="restaurant-crud-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-cutlery"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a restaurant.
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
                    <select class="form-select" id="hotel_id" name="hotel_id">
                        <option value="">Select Hotel</option>
                        <?php foreach ($hotels as $each) { ?>
                            <option value="<?php echo encrypt_id($each->hotel_id); ?>"><?php echo $each->hotel_name; ?></option>
                        <?php } ?>
                    </select>
                    <div class="text-danger validation" id="hotel_id_error"></div>
                </div>
                <div class="mb-3">
                    <label>Restaurant Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="restaurant_name" name="restaurant_name">
                    <div class="text-danger validation" id="restaurant_name_error"></div>
                </div>
                <div class="mb-3">
                    <label>Restaurant Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="restaurant_code" name="restaurant_code">
                    <div class="text-danger validation" id="restaurant_code_error"></div>
                </div>
                <div class="mb-3">
                    <label>Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label>Restaurant Image</label>
                    <input type="file" class="form-control" id="restaurant_image" name="restaurant_image" accept="image/*">
                </div>
                <div class="mb-3">
                    <img id="restaurant_image_preview" style="width:100px; display:none;">
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="action-btn" class="btn btn-primary" data-key="">Save</button>
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
            url: "<?= base_url('get-restaurants-table') ?>",
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

    function resetRestaurantForm() {
        $('#hotel_id, #restaurant_name, #restaurant_code, #contact_number, #email').val('');
        $('#status').val('1');
        $('#restaurant_image').val('');
        $('#restaurant_image_preview').hide().attr('src', '');
        $('.validation').text('');
    }

    function validateRestaurantForm() {
        var isValid = true;
        var rules = {
            hotel_id: 'Hotel is required',
            restaurant_name: 'Restaurant name is required',
            restaurant_code: 'Restaurant code is required'
        };

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

    $('#restaurant_image').on('change', function() {
        if (!this.files || !this.files[0]) {
            $('#restaurant_image_preview').hide().attr('src', '');
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#restaurant_image_preview').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(this.files[0]);
    });

    $(document).on('click', '#open-add-modal', function() {
        resetRestaurantForm();
        $('#crud-modal-title').text('Add Restaurant');
        $('#action-btn').text('Add Restaurant').attr('data-key', '');
        $('#restaurant-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();
        if (!validateRestaurantForm()) {
            return;
        }

        var $button = $(this);
        var key = $button.attr('data-key');
        var text = $button.text();
        var formData = new FormData();

        formData.append('hotel_id', $('#hotel_id').val());
        formData.append('restaurant_name', $('#restaurant_name').val());
        formData.append('restaurant_code', $('#restaurant_code').val());
        formData.append('contact_number', $('#contact_number').val());
        formData.append('email', $('#email').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);
        if ($('#restaurant_image')[0].files[0]) {
            formData.append('restaurant_image', $('#restaurant_image')[0].files[0]);
        }
        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: (key === '') ? "<?= base_url('superAdmin/Restaurants/add') ?>" : "<?= base_url('superAdmin/Restaurants/update') ?>",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $button.prop('disabled', true).text((key === '') ? 'Adding...' : 'Updating...');
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#restaurant-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function() {
                toastr.error('Server error! Please try again.');
            },
            complete: function() {
                $button.prop('disabled', false).text(text);
            }
        });
    });

    $(document).on('click', '.edit-restaurant', function() {
        resetRestaurantForm();

        $.ajax({
            url: "<?= base_url('superAdmin/Restaurants/getDetails') ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                if (response.status !== 'success') {
                    toastr.error(response.message || 'Failed to fetch restaurant details');
                    return;
                }

                var data = response.data;
                ensureSelectedOption($('#hotel_id'), data.hotel_id, data.hotel_name);
                $('#hotel_id').val(data.hotel_id);
                $('#restaurant_name').val(data.restaurant_name);
                $('#restaurant_code').val(data.restaurant_code);
                $('#contact_number').val(data.contact_number);
                $('#email').val(data.email);
                $('#status').val(data.status);
                if (data.restaurant_image) {
                    $('#restaurant_image_preview').attr('src', '<?= base_url("uploads/restaurant_images/") ?>' + data.restaurant_image + '?t=' + new Date().getTime()).show();
                }
                $('#crud-modal-title').text('Edit Restaurant');
                $('#action-btn').text('Update Restaurant').attr('data-key', response.id);
                $('#restaurant-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Error fetching restaurant details');
            }
        });
    });

    $(document).on('click', '.delete-restaurant', function() {
        var id = $(this).attr('data-record_id');
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
                    url: "<?= base_url('superAdmin/Restaurants/delete') ?>",
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
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            data_table.draw();
                        } else {
                            toastr.error(response.message || 'Failed to delete restaurant');
                        }
                    },
                    error: function() {
                        toastr.error('Server error! Please try again.');
                    }
                });
            }
        });
    });
</script>
