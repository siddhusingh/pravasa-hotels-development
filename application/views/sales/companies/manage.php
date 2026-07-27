<style>
    .required-asterisk {
        color: #dc3545;
        font-weight: 600;
    }

    .field-error {
        display: block;
        font-size: 0.85rem;
        margin-top: 4px;
        min-height: 18px;
    }

    #companyModal .select2-container {
        width: 100% !important;
    }

    #companyModal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #companyModal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 13px !important;
        padding-right: 35px !important;
    }

    #companyModal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .company-select2-dropdown .select2-search__field {
        height: 34px !important;
        min-height: 34px !important;
        padding: 5px 9px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-building"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Companies</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Sales Executive</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company &amp; Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Company Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img-add.png') ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Company List</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="openAddCompany">
                                    Add Company +
                                </button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Company Group</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Area User</th>
                                            <th>Mobile</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Status</th>
                                            <th width="120">Action</th>
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

<div class="modal modal-lg new_modal_design" id="companyModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="companyForm" class="modal-content" enctype="multipart/form-data">
            <input type="hidden" name="company_id" id="company_record_id">

            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-building"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 id="companyModalTitle">Add Company</h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                <span id="companyModalDescription">
                                    Fill in the details to add a company.
                                </span>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png') ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body ps-3 pe-3">
                <?php $this->load->view('sales/companies/form_fields'); ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveCompanyButton">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
window.CSRF = window.CSRF || {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
};

window.addEventListener('load', function () {
    var table;
    var form = $('#companyForm');
    var modal = $('#companyModal');
    var saveButton = $('#saveCompanyButton');
    var requiredFields = [
        'company_group_id',
        'company_name',
        'email',
        'mobile_number',
        'country_id',
        'state_id',
        'city_id',
        'area_id',
        'address'
    ];

    function toast(type, message) {
        if (typeof window.showSalesToast === 'function') {
            window.showSalesToast(type, message);
            return;
        }
        toastr[type](message);
    }

    function updateCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function clearErrors() {
        form.find('.field-error').text('');
        form.find('.is-invalid').removeClass('is-invalid');
    }

    function showFieldError(field, message) {
        $('#company_' + field).addClass('is-invalid');
        $('#company_' + field + '_error').text(message);
    }

    function setProcessing(processing, mode) {
        saveButton.prop('disabled', processing);
        saveButton.html(
            processing
                ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    (mode === 'edit' ? 'Updating...' : 'Saving...')
                : (mode === 'edit' ? 'Update' : 'Save')
        );
    }

    function resetForm() {
        form[0].reset();
        $('#company_record_id').val('');
        form.find('option[data-transient="true"]').remove();
        form.find('select').val('').trigger('change');
        $('#company_creditibility').val('Credit Not Allowed').trigger('change');
        $('#company_status').val('1').trigger('change');
        clearErrors();
    }

    function initSelect2() {
        if (!$.fn.select2) {
            return;
        }

        form.find('.company-select2').each(function () {
            var select = $(this);
            if (select.hasClass('select2-hidden-accessible')) {
                return;
            }

            select.select2({
                width: '100%',
                placeholder: select.find('option:first').text(),
                allowClear: false,
                dropdownParent: modal,
                dropdownCssClass: 'company-select2-dropdown'
            });
        });
    }

    function setEncryptedSelectValue(selector, value, label) {
        var select = $(selector);
        if (!value) {
            select.val('').trigger('change');
            return;
        }

        var optionExists = select.find('option').filter(function () {
            return this.value === value;
        }).length > 0;

        if (!optionExists) {
            var option = new Option(label || 'Selected', value, true, true);
            option.setAttribute('data-transient', 'true');
            select.append(option);
        }

        select.val(value).trigger('change');
    }

    function validateForm() {
        var valid = true;
        clearErrors();

        requiredFields.forEach(function (field) {
            var value = String($('#company_' + field).val() || '').trim();
            if (value === '') {
                showFieldError(field, 'This field is required');
                valid = false;
            }
        });

        var email = String($('#company_email').val() || '').trim();
        var secondaryEmail = String($('#company_secondary_email').val() || '').trim();
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email !== '' && !emailPattern.test(email)) {
            showFieldError('email', 'Please enter a valid email address');
            valid = false;
        }
        if (secondaryEmail !== '' && !emailPattern.test(secondaryEmail)) {
            showFieldError('secondary_email', 'Please enter a valid secondary email');
            valid = false;
        }

        var creditFile = document.getElementById('company_credit_form_file');
        if (
            creditFile &&
            creditFile.files.length > 0 &&
            creditFile.files[0].size > 5 * 1024 * 1024
        ) {
            toast('error', 'Credit form file must not exceed 5 MB.');
            valid = false;
        }

        return valid;
    }

    function openAddModal() {
        resetForm();
        modal.attr('data-mode', 'add');
        $('#companyModalTitle').text('Add Company');
        $('#companyModalDescription').text('Fill in the details to add a company.');
        saveButton.text('Save');
        modal.modal('show');
    }

    function populateEditForm(company) {
        $('#company_record_id').val(company.company_id);
        setEncryptedSelectValue(
            '#company_company_group_id',
            company.company_group_id,
            company.company_group_name
        );
        $('#company_company_name').val(company.company_name);
        $('#company_email').val(company.email);
        $('#company_secondary_email').val(company.secondary_email);
        $('#company_phone_number').val(company.phone_number);
        $('#company_mobile_number').val(company.mobile_number);
        $('#company_gst_number').val(company.gst_number);
        setEncryptedSelectValue(
            '#company_country_id',
            company.country_id,
            company.country_name
        );
        setEncryptedSelectValue(
            '#company_state_id',
            company.state_id,
            company.state_name
        );
        setEncryptedSelectValue(
            '#company_city_id',
            company.city_id,
            company.city_name
        );
        setEncryptedSelectValue(
            '#company_area_id',
            company.area_id,
            company.area_name
        );
        $('#company_pincode').val(company.pincode);
        $('#company_address').val(company.address);
        $('#company_deals_in').val(company.deals_in);
        $('#company_details').val(company.details);
        $('#company_creditibility').val(company.company_creditibility).trigger('change');
        $('#company_status').val(String(company.status)).trigger('change');
    }

    initSelect2();

    table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 9, orderable: false, searchable: false }
        ],
        ajax: {
            url: '<?= base_url('sales/companies/table') ?>',
            type: 'POST',
            data: function (data) {
                data[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function (response) {
                updateCsrf(response);
                return response.data || [];
            },
            error: function () {
                toast('error', 'Unable to load companies.');
            }
        }
    });

    $(document).on('click', '#openAddCompany', openAddModal);

    $(document).on('click', '.edit-company', function () {
        var recordId = $(this).data('record_id');
        resetForm();

        $.ajax({
            url: '<?= base_url('sales/companies/details') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                company_id: recordId,
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function (response) {
                updateCsrf(response);
                if (!response.status) {
                    toast('error', response.message || 'Unable to load company details.');
                    return;
                }

                modal.attr('data-mode', 'edit');
                $('#companyModalTitle').text('Edit Company');
                $('#companyModalDescription').text('Fill in the details to update this company.');
                saveButton.text('Update');
                populateEditForm(response.data);
                modal.modal('show');

                if ((response.data.unavailable_dependencies || []).length > 0) {
                    toast(
                        'warning',
                        'Please select an active replacement for: ' +
                        response.data.unavailable_dependencies.join(', ') + '.'
                    );
                }
            },
            error: function () {
                toast('error', 'Server error while fetching company details.');
            }
        });
    });

    form.on('submit', function (event) {
        event.preventDefault();
        if (!validateForm()) {
            toast('error', 'Please correct the highlighted fields.');
            return;
        }

        var mode = modal.attr('data-mode') === 'edit' ? 'edit' : 'add';
        setProcessing(true, mode);

        var requestData = new FormData(form[0]);
        requestData.append(window.CSRF.name, window.CSRF.hash);

        $.ajax({
            url: '<?= base_url('sales/companies/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: requestData,
            contentType: false,
            processData: false,
            success: function (response) {
                updateCsrf(response);
                if (!response.status) {
                    toast('error', response.message || 'Unable to save company.');
                    return;
                }

                toast(
                    'success',
                    response.message ||
                    (mode === 'edit'
                        ? 'Company updated successfully.'
                        : 'Company added successfully.')
                );
                modal.modal('hide');
                resetForm();
                table.draw(false);
            },
            error: function () {
                toast('error', 'Server error! Please try again.');
            },
            complete: function () {
                setProcessing(false, mode);
            }
        });
    });

    $(document).on('click', '.delete-company', function () {
        var recordId = $(this).data('record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This company will be removed from the active company list.',
            icon: 'question',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '<?= base_url('sales/companies/delete') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: recordId,
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function (response) {
                    updateCsrf(response);
                    if (!response.status) {
                        toast('error', response.message || 'Unable to delete company.');
                        return;
                    }

                    toast('success', response.message || 'Company deleted successfully.');
                    table.draw(false);
                },
                error: function () {
                    toast('error', 'Server error! Please try again.');
                }
            });
        });
    });
});
</script>
