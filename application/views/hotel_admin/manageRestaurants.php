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
                        <li>Hotel Admin</li>
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
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-restaurant-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="hotel-restaurants-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Hotel</th>
                                    <th>Restaurant Name</th>
                                    <th>Restaurant Code</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Status</th>
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

<div class="modal modal-lg new_modal_design" id="restaurant-crud-modal" tabindex="-1" role="dialog" aria-labelledby="restaurant-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-cutlery"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="restaurant-crud-modal-label"></h5>
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
                    <label for="restaurant_name">Restaurant Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="restaurant_name" name="restaurant_name">
                    <div class="text-danger validation" id="restaurant_name_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="restaurant_code">Restaurant Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="restaurant_code" name="restaurant_code">
                    <div class="text-danger validation" id="restaurant_code_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="text-danger validation" id="email_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="restaurant_image">Restaurant Image</label>
                    <input type="file" class="form-control" id="restaurant_image" name="restaurant_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    <small class="text-muted">JPG, JPEG, PNG or WEBP; maximum 2 MB.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <img id="restaurant_image_preview" alt="Restaurant preview" style="width:100px; display:none;">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="restaurant-action-btn" class="btn btn-primary" data-record-id="">Add Restaurant</button>
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

        var restaurantsTable = $('#hotel-restaurants-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [
                { targets: 7, orderable: false }
            ],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-restaurants-table'); ?>',
                type: 'POST',
                data: function(data) {
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function(response) {
                    refreshCsrf(response);
                    if (response.status === 'error' && response.message) {
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

        function resetRestaurantForm() {
            $('.validation').text('');
            $('#restaurant_name, #restaurant_code, #contact_number, #email').val('');
            $('#restaurant_image').val('');
            $('#restaurant_image_preview').hide().attr('src', '');
            $('#status').val('1');
            $('#restaurant-action-btn')
                .attr('data-record-id', '')
                .text('Add Restaurant')
                .prop('disabled', false);
        }

        function validateRestaurantForm() {
            var isValid = true;
            var requiredFields = [
                ['restaurant_name', 'restaurant_name_error', 'Restaurant name is required'],
                ['restaurant_code', 'restaurant_code_error', 'Restaurant code is required']
            ];

            requiredFields.forEach(function(field) {
                var value = $.trim($('#' + field[0]).val());
                $('#' + field[1]).text(value === '' ? field[2] : '');
                $('#' + field[0]).val(value);
                if (value === '') {
                    isValid = false;
                }
            });

            var email = $.trim($('#email').val());
            var emailIsValid = email === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            $('#email').val(email);
            $('#email_error').text(emailIsValid ? '' : 'Please enter a valid email address');

            return isValid && emailIsValid;
        }

        $('#restaurant_image').on('change', function() {
            var file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                $('#restaurant_image_preview').hide().attr('src', '');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                $('#restaurant_image_preview').attr('src', event.target.result).show();
            };
            reader.readAsDataURL(file);
        });

        $('#open-restaurant-modal').on('click', function(event) {
            event.preventDefault();
            resetRestaurantForm();
            $('#restaurant-crud-modal-label').text('Add Restaurant');
            $('#restaurant-crud-modal').modal('show');
        });

        $(document).on('click', '#restaurant-action-btn', function(event) {
            event.preventDefault();

            if (!validateRestaurantForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('restaurant_name', $('#restaurant_name').val());
            formData.append('restaurant_code', $('#restaurant_code').val());
            formData.append('contact_number', $.trim($('#contact_number').val()));
            formData.append('email', $('#email').val());
            formData.append('status', $('#status').val());
            formData.append(window.CSRF.name, window.CSRF.hash);

            var imageInput = $('#restaurant_image')[0];
            if (imageInput.files && imageInput.files[0]) {
                formData.append('restaurant_image', imageInput.files[0]);
            }
            if (isUpdate) {
                formData.append('id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'update-restaurant' : 'insert-restaurant'),
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
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        $('#restaurant-crud-modal').modal('hide');
                        restaurantsTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Unable to save restaurant');
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

        $(document).on('click', '.edit-restaurant', function(event) {
            event.preventDefault();
            resetRestaurantForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/edit-restaurant'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status !== 'success') {
                        toastr.error(response.message || 'Failed to fetch restaurant details');
                        return;
                    }

                    var data = response.data;
                    $('#restaurant_name').val(data.restaurant_name);
                    $('#restaurant_code').val(data.restaurant_code);
                    $('#contact_number').val(data.contact_number);
                    $('#email').val(data.email);
                    $('#status').val(String(data.status));
                    if (data.restaurant_image) {
                        $('#restaurant_image_preview')
                            .attr('src', '<?php echo base_url('uploads/restaurant_images/'); ?>' + data.restaurant_image + '?t=' + Date.now())
                            .show();
                    }
                    $('#restaurant-crud-modal-label').text('Edit Restaurant');
                    $('#restaurant-action-btn').attr('data-record-id', response.id).text('Update Restaurant');
                    $('#restaurant-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Error fetching restaurant details');
                }
            });
        });

        $(document).on('click', '.delete-restaurant', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This restaurant will be removed from the active restaurant list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/delete-restaurant'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: recordId,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        refreshCsrf(response);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            restaurantsTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Failed to delete restaurant');
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
