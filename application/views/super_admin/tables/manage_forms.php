<style>
    #table-crud-modal .select2-container { width: 100% !important; }
    #table-crud-modal .select2-selection--single { height: 38px; padding: 5px 8px; }
    #table-crud-modal .select2-selection__arrow { height: 36px; }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Tables</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Table Management</li>
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
                    <h4 class="box-title">Table Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-table-modal">Add Table +</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Restaurant</th>
                                    <th>Category</th>
                                    <th>Table Name</th>
                                    <th>Table Number</th>
                                    <th>Capacity</th>
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

<div class="modal modal-lg new_modal_design" id="table-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-table"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a table.
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
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Restaurant <span class="required-asterisk">*</span></label>
                        <select class="form-select table-select" id="restaurant_id" name="restaurant_id">
                            <option value="">Select Restaurant</option>
                            <?php foreach ($restaurants as $r) { ?>
                                <option value="<?= (int) $r->id ?>"><?= html_escape($r->restaurant_name) ?></option>
                            <?php } ?>
                        </select>
                        <span class="validation text-danger" id="restaurant_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Category <span class="required-asterisk">*</span></label>
                        <select class="form-select table-select" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                        </select>
                        <span class="validation text-danger" id="category_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Table Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="table_name" name="table_name" maxlength="100">
                        <span class="validation text-danger" id="table_name_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Table Number <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="table_number" name="table_number" maxlength="50">
                        <span class="validation text-danger" id="table_number_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Capacity <span class="required-asterisk">*</span></label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" step="1">
                        <span class="validation text-danger" id="capacity_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select class="form-select table-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: 'toast-top-right',
        timeOut: '5000'
    };
</script>

<script>
    var availableCategories = <?php echo json_encode($categories, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 7, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-tables-table') ?>',
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

    function initializeTableSelects() {
        $('#restaurant_id, #category_id').select2({
            dropdownParent: $('#table-crud-modal'),
            width: '100%'
        });
        $('#status').select2({
            dropdownParent: $('#table-crud-modal'),
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
    }

    function setTableSelectValue(selector, value) {
        $(selector).val(value).trigger('change.select2');
    }

    function populateCategorySelect(restaurantId, selectedCategoryId) {
        var $category = $('#category_id');
        var restaurantKey = String(restaurantId || '');

        $category.empty().append(new Option('Select Category', ''));
        availableCategories.forEach(function(category) {
            if (String(category.restaurant_id) === restaurantKey) {
                $category.append(new Option(category.category_name, category.id));
            }
        });
        setTableSelectValue('#category_id', selectedCategoryId || '');
    }

    $(document).ready(function() {
        initializeTableSelects();
        populateCategorySelect('', '');

        $('#restaurant_id').on('change', function() {
            populateCategorySelect($(this).val(), '');
            $('#restaurant_id_error').text('');
        });
        $('#category_id, #status').on('change', function() {
            $(this).siblings('.validation').text('');
        });
    });

    function resetTableForm() {
        $('.validation').text('');
        setTableSelectValue('#restaurant_id', '');
        populateCategorySelect('', '');
        $('#table_name').val('');
        $('#table_number').val('');
        $('#capacity').val('');
        setTableSelectValue('#status', 'active');
    }

    function validateTableForm() {
        var isValid = true;
        var fields = [
            ['restaurant_id', 'restaurant_id_error', 'Please select restaurant'],
            ['category_id', 'category_id_error', 'Please select category'],
            ['table_name', 'table_name_error', 'Please enter table name'],
            ['table_number', 'table_number_error', 'Please enter table number'],
            ['capacity', 'capacity_error', 'Please enter capacity']
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

    $('#open-table-modal').click(function(e) {
        e.preventDefault();
        resetTableForm();
        $('#crud-modal-title').text('Add Table');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#table-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateTableForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var $button = $(this);
        var formData = new FormData();

        formData.append('restaurant_id', $('#restaurant_id').val());
        formData.append('category_id', $('#category_id').val());
        formData.append('table_name', $('#table_name').val());
        formData.append('table_number', $('#table_number').val());
        formData.append('capacity', $('#capacity').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'tables-add' : 'tables-update'),
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
                    $('#table-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save table');
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

    $(document).on('click', '.edit-table', function() {
        resetTableForm();

        $.ajax({
            url: '<?php echo base_url('tables-details') ?>',
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

                $('#crud-modal-title').text('Edit Table');
                setTableSelectValue('#restaurant_id', response.data.restaurant_id);
                populateCategorySelect(response.data.restaurant_id, response.data.category_id);
                $('#table_name').val(response.data.table_name);
                $('#table_number').val(response.data.table_number);
                $('#capacity').val(response.data.capacity);
                setTableSelectValue('#status', response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#table-crud-modal').modal('show');
            },
            error: function() {
                toastr.error('Error fetching details');
            }
        });
    });

    $(document).on('click', '.delete-table', function() {
        var id = $(this).attr('data-record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This table will be removed from the active table list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes Delete it',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('tables-delete') ?>',
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
