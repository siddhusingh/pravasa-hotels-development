<style>
    #category-crud-modal .select2-container { width: 100% !important; }
    #category-crud-modal .select2-selection--single { height: 38px; padding: 5px 8px; }
    #category-crud-modal .select2-selection__arrow { height: 36px; }
    #category-crud-modal .select2-search--dropdown { padding: 8px; }
    #category-crud-modal .select2-search--dropdown .select2-search__field {
        height: 48px;
        padding: 10px 14px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        background-color: #fff;
        color: #1f1f2e;
        font-size: 16px;
        outline: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
    }
    #category-crud-modal .select2-search--dropdown .select2-search__field:focus {
        border-color: #9b87e8;
        box-shadow: 0 0 0 2px rgba(155, 135, 232, 0.15);
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Table Categories</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Table Category Management</li>
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
                    <h4 class="box-title">Table Category Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-category-modal">Add +</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
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

<div class="modal modal-lg new_modal_design" id="category-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-list-alt"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a table category.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body ps-3 pe-3 row">
                <div class="col-sm-4 mb-3">
                    <label>Restaurant <span class="required-asterisk">*</span></label>
                    <select class="form-select category-select" id="restaurant_id" name="restaurant_id">
                        <option value="">Select Restaurant</option>
                        <?php foreach ($restaurants as $each) { ?>
                            <option value="<?= (int) $each->id ?>"><?= html_escape($each->restaurant_name) ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="restaurant_id_error"></span>
                </div>

                <div class="col-sm-4 mb-3">
                    <label>Category Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="category_name" name="category_name">
                    <span class="validation text-danger" id="category_name_error"></span>
                </div>
                <div class="col-sm-4 mb-3">
                    <label>Status</label>
                    <select class="form-select category-select" id="status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <span class="validation text-danger" id="status_error"></span>
                </div>

                <div class="col-sm-12 mb-3">
                    <label>Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
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
            { targets: 5, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-table-categories-table') ?>',
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

    $(document).ready(function() {
        initializeCategorySelects();
        $('#restaurant_id, #status').on('change', function() {
            $(this).siblings('.validation').text('');
        });
    });

    function resetCategoryForm() {
        $('.validation').text('');
        setCategorySelectValue('#restaurant_id', '');
        $('#category_name').val('');
        $('#description').val('');
        setCategorySelectValue('#status', 'active');
    }

    function validateCategoryForm() {
        var isValid = true;

        if ($('#restaurant_id').val() === '') {
            $('#restaurant_id_error').text('Please select restaurant');
            isValid = false;
        } else {
            $('#restaurant_id_error').text('');
        }

        if ($('#category_name').val().trim() === '') {
            $('#category_name_error').text('Please enter category name');
            isValid = false;
        } else {
            $('#category_name_error').text('');
        }

        return isValid;
    }

    $('#open-category-modal').click(function(e) {
        e.preventDefault();
        resetCategoryForm();
        $('#crud-modal-title').text('Add Table Category');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#category-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateCategoryForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var $button = $(this);
        var formData = new FormData();

        formData.append('restaurant_id', $('#restaurant_id').val());
        formData.append('category_name', $('#category_name').val());
        formData.append('description', $('#description').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'table-categories-add' : 'table-categories-update'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $button.prop('disabled', true).text((key !== '') ? 'Updating..' : 'Saving..');
            },
            success: function(response) {
                if (response.csrfHash) {
                    window.CSRF.hash = response.csrfHash;
                }
                if (response.status) {
                    toastr.success(response.message);
                    $('#category-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save category');
                }
            },
            error: function() {
                toastr.error('Something went wrong');
            },
            complete: function() {
                $button.prop('disabled', false).text(btn_txt);
            }
        });
    });

    $(document).on('click', '.edit-category', function() {
        resetCategoryForm();

        $.ajax({
            url: '<?php echo base_url('table-categories-details') ?>',
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

                $('#crud-modal-title').text('Edit Table Category');
                setCategorySelectValue('#restaurant_id', response.data.restaurant_id);
                $('#category_name').val(response.data.category_name);
                $('#description').val(response.data.description);
                setCategorySelectValue('#status', response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#category-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Error fetching details');
            }
        });
    });

    $(document).on('click', '.delete-category', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This category will be removed from the active category list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('table-categories-delete') ?>',
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
