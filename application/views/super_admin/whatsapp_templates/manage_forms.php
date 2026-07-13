<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-whatsapp"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage WhatsApp Templates</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">WhatsApp Template Management</li>
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
                            <h4 class="box-title">Manage WhatsApp Templates</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="open-template-modal">Add +</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Property</th>
                                            <th>Template Name</th>
                                            <th>ORAI Template Code</th>
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

<div class="modal modal-lg new_modal_design" id="template-crud-modal" tabindex="-1" aria-labelledby="template-crud-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-whatsapp"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a WhatsApp template.
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
                    <label for="property_id" class="form-label">Property <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="property_id" name="property_id">
                        <option value="">Select Property</option>
                        <?php foreach ($properties as $p) { ?>
                            <option value="<?php echo encrypt_id($p->hotel_id); ?>"><?php echo htmlspecialchars($p->hotel_name); ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="property_id_error"></span>
                </div>
                <div class="mb-3">
                    <label for="template_name" class="form-label">Template Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="template_name" name="template_name" placeholder="Enter template name">
                    <span class="validation text-danger" id="template_name_error"></span>
                </div>
                <div class="mb-3">
                    <label for="orai_template_code" class="form-label">ORAI Template Code <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="orai_template_code" name="orai_template_code" placeholder="Enter ORAI template code">
                    <span class="validation text-danger" id="orai_template_code_error"></span>
                </div>
                <div class="mb-3">
                    <label for="api_key" class="form-label">API Key <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Enter API key">
                    <span class="validation text-danger" id="api_key_error"></span>
                </div>
                <div class="mb-3">
                    <label for="api_endpoint" class="form-label">API EndPoint <span class="required-asterisk">*</span></label>
                    <input type="url" class="form-control" id="api_endpoint" name="api_endpoint" placeholder="Enter API endpoint">
                    <span class="validation text-danger" id="api_endpoint_error"></span>
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
            url: '<?php echo base_url('get-whatsapp-templates-table') ?>',
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

    function resetTemplateForm() {
        $('.validation').text('');
        $('#property_id').val('');
        $('#template_name').val('');
        $('#orai_template_code').val('');
        $('#api_key').val('');
        $('#api_endpoint').val('');
        $('#status').val('1');
    }

    function validateTemplateForm() {
        var isValid = true;
        $('.validation').text('');

        if ($('#property_id').val() === '') {
            $('#property_id_error').text('Please select property');
            isValid = false;
        }
        if ($('#template_name').val().trim() === '') {
            $('#template_name_error').text('Please enter template name');
            isValid = false;
        }
        if ($('#orai_template_code').val().trim() === '') {
            $('#orai_template_code_error').text('Please enter ORAI template code');
            isValid = false;
        }
        if ($('#api_key').val().trim() === '') {
            $('#api_key_error').text('Please enter API key');
            isValid = false;
        }
        if ($('#api_endpoint').val().trim() === '') {
            $('#api_endpoint_error').text('Please enter API endpoint');
            isValid = false;
        }

        return isValid;
    }

    $('#open-template-modal').click(function(e) {
        e.preventDefault();
        resetTemplateForm();
        $('#crud-modal-title').text('Add WhatsApp Template');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#template-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateTemplateForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('property_id', $('#property_id').val());
        formData.append('template_name', $('#template_name').val());
        formData.append('orai_template_code', $('#orai_template_code').val());
        formData.append('api_key', $('#api_key').val());
        formData.append('api_endpoint', $('#api_endpoint').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'whatsapp-templates-add' : 'whatsapp-templates-update'),
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
                    $('#template-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save WhatsApp template');
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

    $(document).on('click', '.edit-template', function() {
        resetTemplateForm();

        $.ajax({
            url: '<?php echo base_url('whatsapp-templates-details') ?>',
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
                    toastr.error(response.message || 'Failed to fetch WhatsApp template details');
                    return;
                }

                if ($('#property_id option[value="' + response.data.property_id + '"]').length === 0) {
                    $('#property_id').append(new Option(response.data.property_name, response.data.property_id));
                }

                $('#crud-modal-title').text('Edit WhatsApp Template');
                $('#property_id').val(response.data.property_id);
                $('#template_name').val(response.data.template_name);
                $('#orai_template_code').val(response.data.orai_template_code);
                $('#api_key').val(response.data.api_key);
                $('#api_endpoint').val(response.data.api_endpoint);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#template-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-template', function() {
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
                    url: '<?php echo base_url('whatsapp-templates-delete') ?>',
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
