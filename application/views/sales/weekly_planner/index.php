<style>
    #plannerModal .select2-container,
    #editPlannerModal .select2-container {
        width: 100% !important;
    }

    #plannerModal .select2-selection--single,
    #editPlannerModal .select2-selection--single {
        align-items: center;
        display: flex;
        height: 42px !important;
    }

    #plannerModal .select2-selection__rendered,
    #editPlannerModal .select2-selection__rendered {
        line-height: 40px !important;
        padding-left: 12px;
    }

    #plannerModal .select2-selection__arrow,
    #editPlannerModal .select2-selection__arrow {
        height: 40px !important;
    }

    .planner-approval-note {
        background: transparent;
        border: 0;
        color: #dc3545;
        margin: 0 0 16px;
        padding: 0;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">
                        <?= $is_planner_manager
                            ? 'Weekly Planner Approvals'
                            : 'Weekly Planner' ?>
                    </h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>
                            <?= $is_planner_manager
                                ? 'Sales Manager'
                                : 'Sales Executive' ?>
                        </li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Sales Operations</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Weekly Planner</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img
                    src="<?= base_url('assets/new_img-add.png') ?>"
                    alt=""
                >
            </div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div
                    class="box-header d-flex justify-content-between align-items-center"
                >
                    <h4 class="box-title mb-0">
                        <i class="fa fa-lightbulb-o"></i>
                        <?= $is_planner_manager
                            ? 'Manage Planner Approvals'
                            : 'Manage Weekly Planner' ?>
                    </h4>
                    <div class="d-flex align-items-center">
                        <div class="btn-group btn-group-sm mr-2" role="group">
                            <button
                                type="button"
                                id="btnTableView"
                                class="btn btn-primary active"
                                title="Table View"
                            >
                                <i
                                    class="fa fa-table"
                                    aria-hidden="true"
                                ></i>
                            </button>
                            <button
                                type="button"
                                id="btnCalendarView"
                                class="btn btn-outline-primary"
                                title="Calendar View"
                            >
                                <i
                                    class="fa fa-calendar"
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </div>
                        <?php if (!$is_planner_manager): ?>
                            <button
                                type="button"
                                class="btn btn-primary-light btn-sm"
                                id="open-add-modal"
                            >
                                <i
                                    class="fa fa-plus"
                                    aria-hidden="true"
                                ></i>
                                Add
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="box-body">
                    <div class="planner-approval-note" role="status">
                        <?php if ($is_planner_manager): ?>
                            Pending plans require your approval before they
                            become visible to the Sales Executive.
                        <?php else: ?>
                            New or edited plans are sent to the Sales Manager.
                            They appear here after approval.
                        <?php endif; ?>
                    </div>

                    <div id="tableViewWrapper" class="table-responsive">
                        <table
                            id="server-side-data-table"
                            class="text-fade table table-bordered display"
                            style="width:100%"
                        >
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <?php if ($is_planner_manager): ?>
                                        <th>Sales Executive</th>
                                    <?php endif; ?>
                                    <th>Date</th>
                                    <th>Activity Type</th>
                                    <th>Account / Activity</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="calendarViewWrapper" class="d-none">
                        <div id="weeklyPlannerCalendar"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php if (!$is_planner_manager): ?>
    <div
        class="modal fade new_modal_design"
        id="plannerModal"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="plannerForm" novalidate>
                    <div class="custom-page-header">
                        <div class="header-left">
                            <div class="header-icon-box">
                                <i class="fa fa-lightbulb-o"></i>
                            </div>
                            <div class="header-content">
                                <div class="modal-header hotel_modal_header">
                                    <h4 class="modal-title">
                                        Add Weekly Planner
                                    </h4>
                                    <div class="hotel_banner"></div>
                                </div>
                                <ol class="custom-breadcrumb">
                                    <li>
                                        <i class="fa fa-info-circle"></i>
                                        Plan visits and sales activities for
                                        the week.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="header-banner">
                            <img
                                src="<?= base_url(
                                    'assets/new_img-add.png'
                                ) ?>"
                                alt=""
                            >
                            <button
                                type="button"
                                class="btn-close planner-modal-close"
                                aria-label="Close"
                            ></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="planner_date">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="planner_date"
                                    id="planner_date"
                                    class="form-control"
                                    required
                                >
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="activity_type">
                                    Activity Type
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="activity_type"
                                    id="activity_type"
                                    class="form-control"
                                    required
                                >
                                    <option value="">Select</option>
                                    <option value="visit">Visit</option>
                                    <option value="other">
                                        Other Activity
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div id="visit_section" class="d-none">
                            <hr>
                            <div class="form-group">
                                <label for="account_type">
                                    Account Type
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="account_type"
                                    id="account_type"
                                    class="form-control"
                                >
                                    <option value="">Select</option>
                                    <option value="existing">
                                        Existing Customer
                                    </option>
                                    <option value="new">
                                        New Customer
                                    </option>
                                </select>
                            </div>

                            <div id="existing_section" class="d-none">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_id">
                                            Company
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            name="company_id"
                                            id="company_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                Select Company
                                            </option>
                                            <?php foreach (
                                                $companies as $company
                                            ): ?>
                                                <option
                                                    value="<?= html_escape(
                                                        encrypt_id(
                                                            $company->company_id
                                                        )
                                                    ) ?>"
                                                >
                                                    <?= html_escape(
                                                        $company->company_name
                                                    ) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_id">
                                            Contact
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            name="contact_id"
                                            id="contact_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                Select Person
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="new_section" class="d-none">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_person_name">
                                            Person Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="new_person_name"
                                            id="new_person_name"
                                            class="form-control"
                                            maxlength="100"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="new_person_mobile">
                                            Mobile Number
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="new_person_mobile"
                                            id="new_person_mobile"
                                            class="form-control"
                                            maxlength="15"
                                            inputmode="numeric"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="other_section" class="form-group d-none">
                            <label for="other_activity">
                                Activity
                                <span class="text-danger">*</span>
                            </label>
                            <select
                                name="other_activity"
                                id="other_activity"
                                class="form-control"
                            >
                                <option value="">Select Activity</option>
                                <option value="Fairs & Marts">
                                    Fairs & Marts
                                </option>
                                <option value="In House">In House</option>
                                <option value="Others">Others</option>
                                <option value="Sales Blitz">
                                    Sales Blitz
                                </option>
                                <option value="Tele Calling">
                                    Tele Calling
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button
                            type="button"
                            class="btn btn-primary-light planner-modal-close"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="saveBtn"
                        >
                            Submit for Approval
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div
        class="modal fade new_modal_design"
        id="editPlannerModal"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editPlannerForm" novalidate>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="custom-page-header">
                        <div class="header-left">
                            <div class="header-icon-box">
                                <i class="fa fa-lightbulb-o"></i>
                            </div>
                            <div class="header-content">
                                <div class="modal-header hotel_modal_header">
                                    <h4 class="modal-title">
                                        Edit Weekly Planner
                                    </h4>
                                    <div class="hotel_banner"></div>
                                </div>
                                <ol class="custom-breadcrumb">
                                    <li>
                                        <i class="fa fa-info-circle"></i>
                                        Update the planned visit or activity
                                        details.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="header-banner">
                            <img
                                src="<?= base_url(
                                    'assets/new_img-add.png'
                                ) ?>"
                                alt=""
                            >
                            <button
                                type="button"
                                class="btn-close edit-planner-modal-close"
                                aria-label="Close"
                            ></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_planner_date">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="planner_date"
                                    id="edit_planner_date"
                                    class="form-control"
                                    required
                                >
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_activity_type">
                                    Activity Type
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="activity_type"
                                    id="edit_activity_type"
                                    class="form-control"
                                    required
                                >
                                    <option value="">Select</option>
                                    <option value="visit">Visit</option>
                                    <option value="other">
                                        Other Activity
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div id="edit_visit_section" class="d-none">
                            <hr>
                            <div class="form-group">
                                <label for="edit_account_type">
                                    Account Type
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="account_type"
                                    id="edit_account_type"
                                    class="form-control"
                                >
                                    <option value="">Select</option>
                                    <option value="existing">
                                        Existing Customer
                                    </option>
                                    <option value="new">
                                        New Customer
                                    </option>
                                </select>
                            </div>

                            <div id="edit_existing_section" class="d-none">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_company_id">
                                            Company
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            name="company_id"
                                            id="edit_company_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                Select Company
                                            </option>
                                            <?php foreach (
                                                $companies as $company
                                            ): ?>
                                                <option
                                                    value="<?= html_escape(
                                                        encrypt_id(
                                                            $company->company_id
                                                        )
                                                    ) ?>"
                                                >
                                                    <?= html_escape(
                                                        $company->company_name
                                                    ) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_contact_id">
                                            Contact
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            name="contact_id"
                                            id="edit_contact_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                Select Person
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="edit_new_section" class="d-none">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_new_person_name">
                                            Person Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="new_person_name"
                                            id="edit_new_person_name"
                                            class="form-control"
                                            maxlength="100"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_new_person_mobile">
                                            Mobile Number
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="new_person_mobile"
                                            id="edit_new_person_mobile"
                                            class="form-control"
                                            maxlength="15"
                                            inputmode="numeric"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            id="edit_other_section"
                            class="form-group d-none"
                        >
                            <label for="edit_other_activity">
                                Activity
                                <span class="text-danger">*</span>
                            </label>
                            <select
                                name="other_activity"
                                id="edit_other_activity"
                                class="form-control"
                            >
                                <option value="">Select Activity</option>
                                <option value="Fairs & Marts">
                                    Fairs & Marts
                                </option>
                                <option value="In House">In House</option>
                                <option value="Others">Others</option>
                                <option value="Sales Blitz">
                                    Sales Blitz
                                </option>
                                <option value="Tele Calling">
                                    Tele Calling
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_description">Description</label>
                            <textarea
                                name="description"
                                id="edit_description"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button
                            type="button"
                            class="btn btn-primary-light edit-planner-modal-close"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="updateBtn"
                        >
                            Update & Resubmit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css"
>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script>
window.CSRF = {
    name: <?= json_encode($this->security->get_csrf_token_name()) ?>,
    hash: <?= json_encode($this->security->get_csrf_hash()) ?>
};

window.addEventListener('load', function () {
    'use strict';

    var $ = window.jQuery;
    if (!$) {
        return;
    }

    var isManager = <?= $is_planner_manager ? 'true' : 'false' ?>;
    var plannerCalendar = null;
    var plannerTable = null;

    function csrfData(data) {
        data = data || {};
        data[window.CSRF.name] = window.CSRF.hash;
        return data;
    }

    function refreshCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function toast(type, message) {
        if (typeof window.showSalesToast === 'function') {
            window.showSalesToast(type, message);
        } else if (window.toastr && toastr[type]) {
            toastr[type](message);
        }
    }

    function initializeSelects() {
        if (!$.fn.select2 || isManager) {
            return;
        }

        [
            {
                selectors: '#activity_type, #account_type, #other_activity',
                modal: '#plannerModal'
            },
            {
                selectors: '#edit_activity_type, #edit_account_type, #edit_other_activity',
                modal: '#editPlannerModal'
            }
        ].forEach(function (group) {
            $(group.selectors)
                .not('.select2-hidden-accessible')
                .select2({
                    dropdownParent: $(group.modal),
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
        });

        $('#company_id, #contact_id')
            .not('.select2-hidden-accessible')
            .select2({
                dropdownParent: $('#plannerModal'),
                width: '100%'
            });
        $('#edit_company_id, #edit_contact_id')
            .not('.select2-hidden-accessible')
            .select2({
                dropdownParent: $('#editPlannerModal'),
                width: '100%'
            });
    }

    function updateSections(prefix) {
        var activity = $('#' + prefix + 'activity_type').val();
        var account = $('#' + prefix + 'account_type').val();

        $('#' + prefix + 'visit_section').toggleClass(
            'd-none',
            activity !== 'visit'
        );
        $('#' + prefix + 'other_section').toggleClass(
            'd-none',
            activity !== 'other'
        );
        $('#' + prefix + 'existing_section').toggleClass(
            'd-none',
            activity !== 'visit' || account !== 'existing'
        );
        $('#' + prefix + 'new_section').toggleClass(
            'd-none',
            activity !== 'visit' || account !== 'new'
        );
    }

    function ensureSelectValue(selector, value, label) {
        if (!value) {
            return;
        }

        if ($(selector + ' option[value="' + value + '"]').length === 0) {
            $(selector).append(
                new Option(label || 'Selected', value, true, true)
            );
        }
        $(selector).val(value).trigger('change.select2');
    }

    function loadContacts(companySelector, contactSelector, selectedContact) {
        var companyId = $(companySelector).val();
        var $contact = $(contactSelector);

        $contact
            .empty()
            .append(new Option('Select Person', ''))
            .trigger('change.select2');

        if (!companyId) {
            return;
        }

        $contact
            .empty()
            .append(new Option('Loading...', ''))
            .prop('disabled', true)
            .trigger('change.select2');

        $.ajax({
            url: <?= json_encode(
                base_url('sales/weekly-planner/contacts')
            ) ?>,
            type: 'POST',
            dataType: 'json',
            data: csrfData({
                company_id: companyId,
                selected_contact_id: selectedContact || ''
            }),
            success: function (response) {
                refreshCsrf(response);
                $contact.empty().append(new Option('Select Person', ''));

                if (response.status === 'success') {
                    $.each(response.data, function (_, contact) {
                        var name = $.trim(
                            contact.first_name + ' ' + contact.last_name
                        );
                        var label = name + (
                            contact.mobile_number
                                ? ' (' + contact.mobile_number + ')'
                                : ''
                        );
                        $contact.append(
                            new Option(
                                label,
                                contact.contact_id,
                                false,
                                selectedContact &&
                                    contact.contact_id === selectedContact
                            )
                        );
                    });
                }

                $contact
                    .prop('disabled', false)
                    .trigger('change.select2');
            },
            error: function (xhr) {
                var response = xhr.responseJSON || {};
                refreshCsrf(response);
                $contact
                    .empty()
                    .append(new Option('Unable to load contacts', ''))
                    .prop('disabled', false)
                    .trigger('change.select2');
                toast(
                    'error',
                    response.message || 'Unable to load company contacts'
                );
            }
        });
    }

    function initializeTable() {
        if (!$.fn.DataTable || plannerTable) {
            return;
        }

        var actionIndex = isManager ? 8 : 7;
        var nonOrderable = isManager ? [4, 5, 8] : [3, 4, 7];

        plannerTable = $('#server-side-data-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            order: [],
            ajax: {
                url: <?= json_encode(
                    base_url('sales/weekly-planner/table')
                ) ?>,
                type: 'POST',
                data: function (data) {
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function (response) {
                    refreshCsrf(response);
                    return response.data || [];
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toast(
                        'error',
                        response.message ||
                            'Unable to load weekly planners'
                    );
                }
            },
            columnDefs: [
                {
                    targets: nonOrderable,
                    orderable: false
                },
                {
                    targets: actionIndex,
                    searchable: false,
                    className: 'table-action min-w-100'
                }
            ]
        });
    }

    function refreshPlannerViews() {
        if (plannerTable) {
            plannerTable.ajax.reload(null, false);
        }
        if (plannerCalendar) {
            plannerCalendar.refetchEvents();
        }
    }

    function openEditPlanner(plannerId) {
        $.ajax({
            url: <?= json_encode(
                base_url('sales/weekly-planner/details')
            ) ?>,
            type: 'GET',
            dataType: 'json',
            data: { id: plannerId },
            success: function (response) {
                refreshCsrf(response);
                if (response.status !== 'success') {
                    toast('error', response.message || 'Planner not found');
                    return;
                }

                var planner = response.data;
                $('#editPlannerForm')[0].reset();
                $('#edit_id').val(planner.id);
                $('#edit_planner_date').val(planner.planner_date);
                $('#edit_activity_type')
                    .val(planner.activity_type)
                    .trigger('change.select2');
                $('#edit_account_type')
                    .val(planner.account_type)
                    .trigger('change.select2');
                ensureSelectValue(
                    '#edit_company_id',
                    planner.company_id,
                    planner.company_name
                );
                $('#edit_new_person_name').val(
                    planner.new_person_name || ''
                );
                $('#edit_new_person_mobile').val(
                    planner.new_person_mobile || ''
                );
                $('#edit_other_activity')
                    .val(planner.other_activity)
                    .trigger('change.select2');
                $('#edit_description').val(planner.description || '');
                updateSections('edit_');

                if (
                    planner.activity_type === 'visit' &&
                    planner.account_type === 'existing'
                ) {
                    loadContacts(
                        '#edit_company_id',
                        '#edit_contact_id',
                        planner.contact_id
                    );
                }

                $('#editPlannerModal').modal('show');
            },
            error: function (xhr) {
                var response = xhr.responseJSON || {};
                refreshCsrf(response);
                toast('error', response.message || 'Unable to load planner');
            }
        });
    }

    $(function () {
        initializeSelects();
        initializeTable();

        $('#btnTableView').on('click', function () {
            $(this)
                .addClass('btn-primary active')
                .removeClass('btn-outline-primary');
            $('#btnCalendarView')
                .removeClass('btn-primary active')
                .addClass('btn-outline-primary');
            $('#tableViewWrapper').removeClass('d-none');
            $('#calendarViewWrapper').addClass('d-none');
        });

        $('#btnCalendarView').on('click', function () {
            $(this)
                .addClass('btn-primary active')
                .removeClass('btn-outline-primary');
            $('#btnTableView')
                .removeClass('btn-primary active')
                .addClass('btn-outline-primary');
            $('#calendarViewWrapper').removeClass('d-none');
            $('#tableViewWrapper').addClass('d-none');

            if (!plannerCalendar) {
                plannerCalendar = new FullCalendar.Calendar(
                    document.getElementById('weeklyPlannerCalendar'),
                    {
                        initialView: 'dayGridWeek',
                        height: 'auto',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek'
                        },
                        events: <?= json_encode(
                            base_url('sales/weekly-planner/calendar')
                        ) ?>,
                        eventClick: function (info) {
                            if (!isManager) {
                                openEditPlanner(info.event.id);
                            }
                        }
                    }
                );
                plannerCalendar.render();
            } else {
                plannerCalendar.updateSize();
            }
        });

        if (isManager) {
            return;
        }

        $('#open-add-modal').on('click', function () {
            $('#plannerForm')[0].reset();
            $('#plannerForm select').val('').trigger('change.select2');
            $('#contact_id')
                .empty()
                .append(new Option('Select Person', ''))
                .trigger('change.select2');
            updateSections('');
            $('#plannerModal').modal('show');
        });

        $('.planner-modal-close').on('click', function () {
            $('#plannerModal').modal('hide');
        });
        $('.edit-planner-modal-close').on('click', function () {
            $('#editPlannerModal').modal('hide');
        });

        $('#activity_type, #account_type').on('change', function () {
            updateSections('');
        });
        $('#edit_activity_type, #edit_account_type').on(
            'change',
            function () {
                updateSections('edit_');
            }
        );
        $('#company_id').on('change', function () {
            loadContacts('#company_id', '#contact_id', '');
        });
        $('#edit_company_id').on('change', function () {
            loadContacts('#edit_company_id', '#edit_contact_id', '');
        });

        $('#plannerForm').on('submit', function (event) {
            event.preventDefault();
            var button = $('#saveBtn');
            button.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: <?= json_encode(
                    base_url('sales/weekly-planner/create')
                ) ?>,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize() + '&' + $.param(csrfData({})),
                success: function (response) {
                    refreshCsrf(response);
                    if (response.status === 'success') {
                        toast('success', response.message);
                        $('#plannerModal').modal('hide');
                        refreshPlannerViews();
                    } else {
                        toast(
                            'error',
                            response.message || 'Unable to submit planner'
                        );
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toast(
                        'error',
                        response.message || 'Unable to submit planner'
                    );
                },
                complete: function () {
                    button
                        .prop('disabled', false)
                        .text('Submit for Approval');
                }
            });
        });

        $('#editPlannerForm').on('submit', function (event) {
            event.preventDefault();
            var button = $('#updateBtn');
            button.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: <?= json_encode(
                    base_url('sales/weekly-planner/update')
                ) ?>,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize() + '&' + $.param(csrfData({})),
                success: function (response) {
                    refreshCsrf(response);
                    if (response.status === 'success') {
                        toast('success', response.message);
                        $('#editPlannerModal').modal('hide');
                        refreshPlannerViews();
                    } else {
                        toast(
                            'error',
                            response.message || 'Unable to update planner'
                        );
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toast(
                        'error',
                        response.message || 'Unable to update planner'
                    );
                },
                complete: function () {
                    button
                        .prop('disabled', false)
                        .text('Update & Resubmit');
                }
            });
        });
    });

    $(document).on('click', '.edit-planner', function () {
        openEditPlanner($(this).data('record_id'));
    });

    $(document).on('click', '.delete-planner', function () {
        var plannerId = $(this).data('record_id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This approved weekly planner will be removed.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: <?= json_encode(
                    base_url('sales/weekly-planner/delete')
                ) ?>,
                type: 'POST',
                dataType: 'json',
                data: csrfData({ id: plannerId }),
                success: function (response) {
                    refreshCsrf(response);
                    if (response.status === 'success') {
                        toast('success', response.message);
                        refreshPlannerViews();
                    } else {
                        toast(
                            'error',
                            response.message || 'Unable to delete planner'
                        );
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toast(
                        'error',
                        response.message || 'Unable to delete planner'
                    );
                }
            });
        });
    });

    $(document).on('click', '.approve-planner', function () {
        var plannerId = $(this).data('record_id');

        Swal.fire({
            title: 'Approve weekly planner?',
            text: 'The planner will become visible to the Sales Executive.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: <?= json_encode(
                    base_url('sales/weekly-planner/approve')
                ) ?>,
                type: 'POST',
                dataType: 'json',
                data: csrfData({ id: plannerId }),
                success: function (response) {
                    refreshCsrf(response);
                    if (response.status === 'success') {
                        toast('success', response.message);
                        refreshPlannerViews();
                    } else {
                        toast(
                            'error',
                            response.message || 'Unable to approve planner'
                        );
                    }
                },
                error: function (xhr) {
                    var response = xhr.responseJSON || {};
                    refreshCsrf(response);
                    toast(
                        'error',
                        response.message || 'Unable to approve planner'
                    );
                }
            });
        });
    });
});
</script>
