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

    #companyContactModal .select2-container {
        width: 100% !important;
    }

    #companyContactModal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #companyContactModal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 13px !important;
        padding-right: 35px !important;
    }

    #companyContactModal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .company-contact-select2-dropdown .select2-search__field {
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
                    <i class="fa fa-address-book"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Company Contacts</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Sales Executive</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Company &amp; Corporate</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Company Contact Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img/company_img.png') ?>" alt="Company contacts">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Contact List</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm" id="openAddContact">
                                    Add Contact +
                                </button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>Full Name</th>
                                            <th>Designation</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Phone</th>
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

<div class="modal modal-lg new_modal_design" id="companyContactModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="companyContactForm" class="modal-content">
            <input type="hidden" name="contact_id" id="contact_record_id">

            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-address-book"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 id="contactModalTitle">Add Contact</h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                <span id="contactModalDescription">
                                    Fill in the details to add a company contact.
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
                <?php
                $this->load->view(
                    'sales/company_contacts/form_fields',
                    ['field_prefix' => 'contact_']
                );
                ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveContactButton">
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
    var form = $('#companyContactForm');
    var modal = $('#companyContactModal');
    var saveButton = $('#saveContactButton');
    var requiredFields = [
        'company_id',
        'title',
        'first_name',
        'last_name',
        'email',
        'mobile_number'
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
        $('#contact_' + field).addClass('is-invalid');
        $('#contact_' + field + '_error').text(message);
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
        $('#contact_record_id').val('');
        form.find('option[data-transient="true"]').remove();
        form.find('select').val('').trigger('change');
        $('#contact_status').val('Active').trigger('change');
        clearErrors();
    }

    function initSelect2() {
        if (!$.fn.select2) {
            return;
        }

        form.find('.contact-select2').each(function () {
            var select = $(this);
            if (select.hasClass('select2-hidden-accessible')) {
                return;
            }

            select.select2({
                width: '100%',
                placeholder: select.find('option:first').text(),
                allowClear: false,
                dropdownParent: modal,
                dropdownCssClass: 'company-contact-select2-dropdown'
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
            var value = String($('#contact_' + field).val() || '').trim();
            if (value === '') {
                showFieldError(field, 'This field is required');
                valid = false;
            }
        });

        var email = String($('#contact_email').val() || '').trim();
        if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showFieldError('email', 'Please enter a valid email address');
            valid = false;
        }

        return valid;
    }

    function openAddModal() {
        resetForm();
        modal.attr('data-mode', 'add');
        $('#contactModalTitle').text('Add Contact');
        $('#contactModalDescription').text('Fill in the details to add a company contact.');
        saveButton.text('Save');
        modal.modal('show');
    }

    function populateEditForm(contact) {
        $('#contact_record_id').val(contact.contact_id);
        setEncryptedSelectValue(
            '#contact_company_id',
            contact.company_id,
            contact.company_name
        );
        $('#contact_title').val(contact.title);
        $('#contact_first_name').val(contact.first_name);
        $('#contact_last_name').val(contact.last_name);
        setEncryptedSelectValue(
            '#contact_designation_id',
            contact.designation,
            contact.designation_name
        );
        $('#contact_grade').val(contact.grade);
        $('#contact_email').val(contact.email);
        $('#contact_phone_number').val(contact.phone_number);
        $('#contact_mobile_number').val(contact.mobile_number);
        setEncryptedSelectValue(
            '#contact_country_id',
            contact.country,
            contact.country_name
        );
        setEncryptedSelectValue(
            '#contact_state_id',
            contact.state,
            contact.state_name
        );
        setEncryptedSelectValue(
            '#contact_city',
            contact.city,
            contact.city_name
        );
        $('#contact_pincode').val(contact.pincode);
        $('#contact_address').val(contact.address);
        $('#contact_date_of_birth').val(contact.date_of_birth);
        $('#contact_date_of_anniversary').val(contact.date_of_anniversary);
        $('#contact_status').val(contact.status).trigger('change');
    }

    initSelect2();

    table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        columnDefs: [
            { targets: 11, orderable: false, searchable: false }
        ],
        ajax: {
            url: '<?= base_url('sales/company-contacts/table') ?>',
            type: 'POST',
            data: function (data) {
                data[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function (response) {
                updateCsrf(response);
                return response.data || [];
            },
            error: function () {
                toast('error', 'Unable to load company contacts.');
            }
        }
    });

    $(document).on('click', '#openAddContact', openAddModal);

    $(document).on('click', '.edit-contact', function () {
        var recordId = $(this).data('record_id');
        resetForm();

        $.ajax({
            url: '<?= base_url('sales/company-contacts/details') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                contact_id: recordId,
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function (response) {
                updateCsrf(response);
                if (!response.status) {
                    toast('error', response.message || 'Unable to load contact details.');
                    return;
                }

                modal.attr('data-mode', 'edit');
                $('#contactModalTitle').text('Edit Contact');
                $('#contactModalDescription').text(
                    'Fill in the details to update this company contact.'
                );
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
                toast('error', 'Server error while fetching contact details.');
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

        var requestData = form.serializeArray();
        requestData.push({
            name: window.CSRF.name,
            value: window.CSRF.hash
        });

        $.ajax({
            url: '<?= base_url('sales/company-contacts/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $.param(requestData),
            success: function (response) {
                updateCsrf(response);
                if (!response.status) {
                    toast('error', response.message || 'Unable to save contact.');
                    return;
                }

                toast(
                    'success',
                    response.message ||
                    (mode === 'edit' ? 'Contact updated successfully.' : 'Contact added successfully.')
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

    $(document).on('click', '.delete-contact', function () {
        var recordId = $(this).data('record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This contact will be removed from the active company contact list.',
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
                url: '<?= base_url('sales/company-contacts/delete') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: recordId,
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function (response) {
                    updateCsrf(response);
                    if (!response.status) {
                        toast('error', response.message || 'Unable to delete contact.');
                        return;
                    }

                    toast('success', response.message || 'Contact deleted successfully.');
                    table.draw(false);
                },
                error: function () {
                    toast('error', 'Server error! Please try again.');
                }
            });
        });
    });

    modal.on('hidden.bs.modal', function () {
        resetForm();
    });
});
</script>
