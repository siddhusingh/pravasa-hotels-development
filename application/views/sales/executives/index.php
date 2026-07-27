<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-male"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Sales Executives</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Sales Manager</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Sales Executive Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img
                    src="<?= base_url('assets/new_img/sales_user_img.png') ?>"
                    alt=""
                >
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Manage Sales Executives</h4>
                            <div class="float-right" style="float:right;">
                                <button
                                    type="button"
                                    class="btn btn-primary-light btn-sm"
                                    id="openExecutiveModal"
                                >
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table
                                    id="server-side-data-table"
                                    class="text-fade table table-bordered display"
                                    style="width:100%"
                                >
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>User Role</th>
                                            <th>Team Group</th>
                                            <th>Assigned Hotels</th>
                                            <th>City</th>
                                            <th>State</th>
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

<div
    class="modal modal-lg new_modal_design"
    id="executiveModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form id="executiveForm" class="modal-content" novalidate>
                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-male"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h4
                                    class="modal-title"
                                    id="executiveModalTitle"
                                >
                                    Add New Sales Executive
                                </h4>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    <span id="executiveModalHelp">
                                        Fill in the details to add a Sales Executive.
                                    </span>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img
                            src="<?= base_url('assets/new_img-add.png') ?>"
                            alt=""
                        >
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                </div>

                <div class="modal-body ps-3 pe-3">
                    <input type="hidden" name="record_id" id="record_id">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                                <input
                                    class="form-control"
                                    type="text"
                                    name="full_name"
                                    id="full_name"
                                    placeholder="Enter Full Name"
                                >
                                <span id="full_name_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="required-asterisk">*</span></label>
                                <input
                                    class="form-control"
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="Enter Email"
                                >
                                <span id="email_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                    <span
                                        id="passwordRequired"
                                        class="required-asterisk"
                                    >*</span>
                                </label>
                                <input
                                    class="form-control"
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Enter Password"
                                    autocomplete="new-password"
                                >
                                <span id="password_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number <span class="required-asterisk">*</span></label>
                                <input
                                    class="form-control"
                                    type="number"
                                    name="phone"
                                    id="phone"
                                    placeholder="Enter Phone Number"
                                >
                                <span id="phone_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="user_role" class="form-label">User Role <span class="required-asterisk">*</span></label>
                                <select
                                    class="form-control"
                                    id="user_role"
                                    disabled
                                >
                                    <option value="Sales Executive" selected>
                                        Sales Executive
                                    </option>
                                </select>
                                <span id="user_role_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="state_id" class="form-label">State</label>
                                <select
                                    class="form-control"
                                    name="state_id"
                                    id="state_id"
                                >
                                    <option value=""></option>
                                    <?php foreach ($states as $state): ?>
                                        <option
                                            value="<?= html_escape(
                                                encrypt_id($state->state_id)
                                            ) ?>"
                                            data-raw-id="<?= (int)$state->state_id ?>"
                                        >
                                            <?= html_escape($state->state_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="state_id_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city_id" class="form-label">City</label>
                                <select
                                    class="form-control"
                                    name="city_id"
                                    id="city_id"
                                >
                                    <option value=""></option>
                                    <?php foreach ($cities as $city): ?>
                                        <option
                                            value="<?= html_escape(
                                                encrypt_id($city->city_id)
                                            ) ?>"
                                            data-state-id="<?= (int)$city->state_id ?>"
                                        >
                                            <?= html_escape($city->city_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="city_id_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Zip Code -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="zipcode" class="form-label">Zip Code</label>
                                <input
                                    class="form-control"
                                    type="number"
                                    name="zipcode"
                                    id="zipcode"
                                    placeholder="Enter Zip Code"
                                >
                                <span id="zipcode_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select
                                    class="form-select"
                                    name="status"
                                    id="status"
                                >
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span id="status_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Team Group -->
                        <div class="col-md-12">
                            <div>
                                <label for="team_group" class="form-label">Team Group</label>
                                <select
                                    class="form-control"
                                    name="team_group[]"
                                    id="team_group"
                                    multiple
                                >
                                    <?php foreach ($team_groups as $group): ?>
                                        <option
                                            value="<?= html_escape(
                                                encrypt_id($group->id)
                                            ) ?>"
                                            data-raw-id="<?= (int)$group->id ?>"
                                        >
                                            <?= html_escape(
                                                $group->team_group_name
                                            ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="team_group_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Hotels -->
                        <div class="col-md-12">
                            <div class="mt-3">
                                <label for="hotel_id" class="form-label">Select Hotels (Multiple)</label>
                                <select
                                    class="form-control"
                                    name="hotel_id[]"
                                    id="hotel_id"
                                    multiple
                                >
                                    <?php foreach ($hotels as $hotel): ?>
                                        <option
                                            value="<?= html_escape(
                                                encrypt_id($hotel->hotel_id)
                                            ) ?>"
                                            data-raw-id="<?= (int)$hotel->hotel_id ?>"
                                        >
                                            <?= html_escape($hotel->hotel_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="hotel_id_error" class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Company -->
                        <div class="col-md-6">
                            <div class="mt-3">
                                <label for="company" class="form-label">Company</label>
                                <textarea
                                    class="form-control"
                                    name="company"
                                    id="company"
                                    rows="2"
                                ></textarea>
                                <span class="validation text-danger"></span>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <div class="mt-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea
                                    class="form-control"
                                    name="address"
                                    id="address"
                                    rows="3"
                                ></textarea>
                                <span class="validation text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-start">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="submit"
                        id="saveExecutive"
                        class="btn btn-primary"
                    >
                        Save changes
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
window.addEventListener('load', function () {
    var $ = window.jQuery;
    var csrfName = <?= json_encode($this->security->get_csrf_token_name()) ?>;
    var csrfHash = <?= json_encode($this->security->get_csrf_hash()) ?>;
    var tableUrl = <?= json_encode(base_url('sales/executives/table')) ?>;
    var createUrl = <?= json_encode(base_url('sales/executives/create')) ?>;
    var detailsUrl = <?= json_encode(base_url('sales/executives/details')) ?>;
    var updateUrl = <?= json_encode(base_url('sales/executives/update')) ?>;
    var deleteUrl = <?= json_encode(base_url('sales/executives/delete')) ?>;
    var cityOptions = $('#city_id option').clone();

    function updateCsrf(response) {
        if (response && response.csrfHash) {
            csrfHash = response.csrfHash;
        }
    }

    function post(url, data) {
        data = data || {};
        data[csrfName] = csrfHash;
        return $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json'
        }).done(updateCsrf);
    }

    function initSelect2() {
        if (!$.fn.select2) {
            return;
        }
        $('#team_group, #hotel_id').select2({
            width: '100%',
            placeholder: 'Select',
            closeOnSelect: false,
            selectionCssClass: 'h-auto py-1',
            dropdownParent: $('#executiveModal')
        });
        $('#team_group, #hotel_id').each(function () {
            $(this)
                .next('.select2')
                .find('.select2-selection__rendered')
                .addClass('d-flex flex-wrap align-items-center');
            $(this)
                .next('.select2')
                .find('.select2-search--inline')
                .addClass('d-none');
        });
        $('#state_id, #city_id, #status').select2({
            width: '100%',
            minimumResultsForSearch: 5,
            dropdownParent: $('#executiveModal')
        });
    }

    function filterCities(rawStateId, keepValue) {
        var current = keepValue || '';
        var $city = $('#city_id');
        $city.empty();
        cityOptions.each(function () {
            var $option = $(this);
            if (!$option.val() ||
                String($option.data('state-id')) === String(rawStateId)) {
                $city.append($option.clone());
            }
        });
        $city.val(current).trigger('change.select2');
    }

    function clearErrors() {
        $('.validation', '#executiveModal').text('');
    }

    function showErrors(errors) {
        clearErrors();
        $.each(errors || {}, function (field, message) {
            $('#' + field + '_error').text(message);
        });
    }

    function resetForm() {
        document.getElementById('executiveForm').reset();
        $('.edit-selected-option', '#executiveModal').remove();
        $('#record_id').val('');
        $('#team_group, #hotel_id').val(null).trigger('change');
        $('#state_id').val('').trigger('change');
        filterCities('');
        $('#status').val('1').trigger('change');
        $('#passwordRequired').show();
        $('#password').attr('placeholder', 'Enter Password');
        $('#executiveModalTitle').text('Add New Sales Executive');
        $('#executiveModalHelp').text(
            'Fill in the details to add a Sales Executive.'
        );
        $('#saveExecutive').text('Save changes');
        clearErrors();
    }

    function ensureOptions(selector, items) {
        var $select = $(selector);
        var values = [];
        $select.find('.edit-selected-option').remove();

        $.each(items || [], function (_, item) {
            var $option = $select.find('option').filter(function () {
                return String($(this).data('raw-id')) ===
                    String(item.raw_id);
            }).first();

            if (!$option.length) {
                var option = new Option(item.label, item.id, true, true);
                $option = $(option)
                    .addClass('edit-selected-option')
                    .attr('data-raw-id', item.raw_id);
                $select.append($option);
            }

            if (values.indexOf(String($option.val())) === -1) {
                values.push(String($option.val()));
            }
        });
        $select.val(values).trigger('change');
    }

    var executivesTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: tableUrl,
            type: 'POST',
            data: function (data) {
                data[csrfName] = csrfHash;
            },
            dataSrc: function (response) {
                updateCsrf(response);
                return response.data || [];
            },
            error: function () {
                toastr.error('Unable to load Sales Executives.');
            }
        },
        columnDefs: [
            {orderable: false, targets: [5, 6, 12]},
            {className: 'table-action min-w-120', targets: 12}
        ]
    });

    function reloadTable() {
        executivesTable.ajax.reload(null, false);
    }

    $('#state_id').on('change', function () {
        var rawStateId = $(this).find(':selected').data('raw-id') || '';
        filterCities(rawStateId);
    });

    $('#openExecutiveModal').on('click', function () {
        resetForm();
        $('#executiveModal').modal('show');
    });

    $(document).on('click', '.edit-executive', function () {
        var id = $(this).data('id');
        resetForm();
        post(detailsUrl, {id: id}).done(function (response) {
            if (!response.status) {
                toastr.error(response.message);
                return;
            }
            var data = response.data;
            $('#record_id').val(data.id);
            $('#full_name').val(data.full_name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#zipcode').val(data.zipcode);
            $('#company').val(data.company);
            $('#address').val(data.address);
            $('#status').val(String(data.status)).trigger('change');

            if (data.state_id) {
                var stateOption = new Option(
                    data.state_name,
                    data.state_id,
                    true,
                    true
                );
                $(stateOption).addClass('edit-selected-option');
                $('#state_id').append(stateOption);
                $('#state_id option:selected').attr(
                    'data-raw-id',
                    data.city_state_id
                );
                $('#state_id').trigger('change.select2');
                filterCities(data.city_state_id);
            }
            if (data.city_id) {
                var cityOption = new Option(
                    data.city_name,
                    data.city_id,
                    true,
                    true
                );
                $(cityOption).addClass('edit-selected-option');
                $('#city_id').append(cityOption)
                    .val(data.city_id)
                    .trigger('change.select2');
            }
            ensureOptions('#team_group', data.selected_team_groups);
            ensureOptions('#hotel_id', data.selected_hotels);
            $('#passwordRequired').hide();
            $('#password').attr(
                'placeholder',
                'Leave blank to keep current password'
            );
            $('#executiveModalTitle').text('Edit Sales Executive');
            $('#executiveModalHelp').text(
                'Fill in the details to update a Sales Executive.'
            );
            $('#saveExecutive').text('Update changes');
            $('#executiveModal').modal('show');
        }).fail(function () {
            toastr.error('Unable to load the Sales Executive.');
        });
    });

    $('#executiveForm').on('submit', function (event) {
        event.preventDefault();
        clearErrors();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        var url = $('#record_id').val() ? updateUrl : createUrl;
        $('#saveExecutive').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (response) {
            updateCsrf(response);
            if (!response.status) {
                showErrors(response.errors);
                toastr.error(response.message || 'Unable to save.');
                return;
            }
            $('#executiveModal').modal('hide');
            toastr.success(response.message);
            reloadTable();
        }).fail(function () {
            toastr.error('Unable to save the Sales Executive.');
        }).always(function () {
            $('#saveExecutive').prop('disabled', false);
        });
    });

    $(document).on('click', '.delete-executive', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Delete Sales Executive?',
            text: 'This action will remove the Sales Executive.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }
            post(deleteUrl, {id: id}).done(function (response) {
                if (!response.status) {
                    toastr.error(response.message);
                    return;
                }
                toastr.success(response.message);
                reloadTable();
            }).fail(function () {
                toastr.error('Unable to delete the Sales Executive.');
            });
        });
    });

    initSelect2();
    resetForm();
});
</script>
