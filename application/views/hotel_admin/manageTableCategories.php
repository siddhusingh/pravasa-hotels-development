<style>
    #category-crud-modal .select2-container { width: 100% !important; }
    #category-crud-modal .select2-selection--single { height: 38px; padding: 5px 8px; }
    #category-crud-modal .select2-selection__arrow { height: 36px; }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-list-alt"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Manage Table Categories</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Tables Categories</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt=""></div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Table Category Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-category-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="hotel-table-categories-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Restaurant</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
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

<div class="modal modal-lg new_modal_design" id="category-crud-modal" tabindex="-1" role="dialog" aria-labelledby="category-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-list-alt"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="category-crud-modal-label"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Fill in the details to add or update a table category.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3 row">
                <div class="col-sm-4 mb-3">
                    <label for="restaurant_id">Restaurant <span class="required-asterisk">*</span></label>
                    <select class="form-select category-select" id="restaurant_id" name="restaurant_id">
                        <option value="">Select Restaurant</option>
                        <?php foreach ($restaurants as $restaurant) { ?>
                            <option value="<?php echo (int) $restaurant->id; ?>"><?php echo html_escape($restaurant->restaurant_name); ?></option>
                        <?php } ?>
                    </select>
                    <div class="validation text-danger" id="restaurant_id_error"></div>
                </div>
                <div class="col-sm-4 mb-3">
                    <label for="category_name">Category Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="category_name" name="category_name">
                    <div class="validation text-danger" id="category_name_error"></div>
                </div>
                <div class="col-sm-4 mb-3">
                    <label for="status">Status</label>
                    <select class="form-select category-select" id="status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-sm-12 mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="category-action-btn" class="btn btn-primary" data-record-id="">Create</button>
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

        function initializeCategorySelects() {
            $('#restaurant_id').select2({
                dropdownParent: $('#category-crud-modal'),
                placeholder: 'Select Restaurant',
                width: '100%'
            });
            $('#status').select2({
                dropdownParent: $('#category-crud-modal'),
                minimumResultsForSearch: Infinity,
                width: '100%'
            });
        }

        function setCategorySelectValue(selector, value) {
            $(selector).val(value).trigger('change.select2');
        }

        initializeCategorySelects();

        var categoryTable = $('#hotel-table-categories-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [{ targets: 5, orderable: false }],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-table-categories-table'); ?>',
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

        function resetCategoryForm() {
            $('.validation').text('');
            setCategorySelectValue('#restaurant_id', '');
            $('#category_name, #description').val('');
            setCategorySelectValue('#status', 'active');
            $('#category-action-btn').attr('data-record-id', '').text('Create').prop('disabled', false);
        }

        function validateCategoryForm() {
            var restaurantId = $('#restaurant_id').val();
            var categoryName = $.trim($('#category_name').val());
            $('#category_name').val(categoryName);
            $('#restaurant_id_error').text(restaurantId ? '' : 'Please select restaurant');
            $('#category_name_error').text(categoryName ? '' : 'Please enter category name');
            return Boolean(restaurantId && categoryName);
        }

        $('#restaurant_id, #category_name').on('input change', function() {
            $('#' + this.id + '_error').text('');
        });

        $('#open-category-modal').on('click', function(event) {
            event.preventDefault();
            resetCategoryForm();
            $('#category-crud-modal-label').text('Add Table Category');
            $('#category-crud-modal').modal('show');
        });

        $(document).on('click', '#category-action-btn', function(event) {
            event.preventDefault();
            if (!validateCategoryForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('restaurant_id', $('#restaurant_id').val());
            formData.append('category_name', $('#category_name').val());
            formData.append('description', $.trim($('#description').val()));
            formData.append('status', $('#status').val());
            formData.append(window.CSRF.name, window.CSRF.hash);
            if (isUpdate) {
                formData.append('id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'table-categories-update' : 'table-categories-add'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text(isUpdate ? 'Updating...' : 'Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status === true) {
                        toastr.success(response.message);
                        $('#category-crud-modal').modal('hide');
                        categoryTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Unable to save category');
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

        $(document).on('click', '.edit-category', function(event) {
            event.preventDefault();
            resetCategoryForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/table-categories-details'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status !== true) {
                        toastr.error(response.message || 'Unable to load category details');
                        return;
                    }

                    setCategorySelectValue('#restaurant_id', String(response.data.restaurant_id));
                    $('#category_name').val(response.data.category_name);
                    $('#description').val(response.data.description);
                    setCategorySelectValue('#status', response.data.status);
                    $('#category-crud-modal-label').text('Edit Table Category');
                    $('#category-action-btn').attr('data-record-id', response.id).text('Update');
                    $('#category-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Error fetching category details');
                }
            });
        });

        $(document).on('click', '.delete-category', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be removed from the active category list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/table-categories-delete'); ?>',
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
                            categoryTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Unable to delete category');
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
