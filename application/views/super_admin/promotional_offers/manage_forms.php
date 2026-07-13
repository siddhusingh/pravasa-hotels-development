<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-bullhorn"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Promotional Offers Management</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Master Settings</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Promotional Offers</li>
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
                    <h4 class="box-title">Manage Promotional Offers</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-offer-modal">Add +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Department</th>
                                    <th>Offer Name</th>
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

<div class="modal modal-lg new_modal_design" id="offer-crud-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-bullhorn"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 id="crud-modal-title"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add or update a promotional offer.
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
                    <label>Department <span class="required-asterisk">*</span></label>
                    <select class="form-select" id="department_id" name="department_id">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept) { ?>
                            <option value="<?= encrypt_id($dept->department_id) ?>"><?= $dept->department_name ?></option>
                        <?php } ?>
                    </select>
                    <span class="validation text-danger" id="department_id_error"></span>
                </div>
                <div class="mb-3">
                    <label>Offer Name <span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="offer_name" name="offer_name">
                    <span class="validation text-danger" id="offer_name_error"></span>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1">Active</option>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        positionClass: 'toast-top-right',
        timeOut: '4000'
    };
</script>

<script>
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 6, orderable: false }
        ],
        ajax: {
            url: '<?php echo base_url('get-promotional-offers-table') ?>',
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

    function resetOfferForm() {
        $('.validation').text('');
        $('#department_id').val('');
        $('#offer_name').val('');
        $('#status').val('1');
    }

    function validateOfferForm() {
        var isValid = true;

        if ($('#department_id').val() === '') {
            $('#department_id_error').text('Please select department');
            isValid = false;
        } else {
            $('#department_id_error').text('');
        }

        if ($('#offer_name').val().trim() === '') {
            $('#offer_name_error').text('Please enter offer name');
            isValid = false;
        } else {
            $('#offer_name_error').text('');
        }

        return isValid;
    }

    $('#open-offer-modal').click(function(e) {
        e.preventDefault();
        resetOfferForm();
        $('#crud-modal-title').text('Add Promotional Offer');
        $('#action-btn').text('Create').attr('data-key', '');
        $('#offer-crud-modal').modal('show');
    });

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();

        if (!validateOfferForm()) {
            return;
        }

        var key = $(this).attr('data-key');
        var btn_txt = $(this).text();
        var formData = new FormData();
        formData.append('department_id', $('#department_id').val());
        formData.append('offer_name', $('#offer_name').val());
        formData.append('status', $('#status').val());
        formData.append(window.CSRF.name, window.CSRF.hash);

        if (key !== '') {
            formData.append('id', key);
        }

        $.ajax({
            url: '<?php echo base_url();?>' + ((key === '') ? 'promotional-offers-add' : 'promotional-offers-update'),
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
                    $('#offer-crud-modal').modal('hide');
                    data_table.draw();
                } else {
                    toastr.error(response.message || 'Failed to save promotional offer');
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

    $(document).on('click', '.edit-offer', function() {
        resetOfferForm();

        $.ajax({
            url: '<?php echo base_url('promotional-offers-details') ?>',
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

                if ($('#department_id option[value="' + response.data.department_id + '"]').length === 0) {
                    $('#department_id').append(new Option(response.data.department_name, response.data.department_id));
                }

                $('#crud-modal-title').text('Edit Promotional Offer');
                $('#department_id').val(response.data.department_id);
                $('#offer_name').val(response.data.offer_name);
                $('#status').val(response.data.status);
                $('#action-btn').text('Update').attr('data-key', response.id);
                $('#offer-crud-modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-offer', function() {
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
                    url: '<?php echo base_url('promotional-offers-delete') ?>',
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
