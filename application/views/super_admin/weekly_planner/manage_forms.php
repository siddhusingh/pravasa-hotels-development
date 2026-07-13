<!-- Content Wrapper -->
<style>
    .error-label {
        font-size: 0.875rem;
        margin-top: 4px;
        display: none;
    }
</style>

<!-- Content Wrapper -->
<style>
    .error-label {
        font-size: 0.875rem;
        margin-top: 4px;
        display: none;
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
                    <h2 class="header-title">Weekly Planner</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>Sales Operations</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Weekly Planner</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Weekly Planner Management</h4>
                    <div class="float-right" style="float:right;">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" id="btnTableView" class="btn btn-primary active" title="Table View">
                                <i class="fa fa-table" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="btnCalendarView" class="btn btn-outline-primary" title="Calendar View">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-add-modal">
                            Add +
                        </button>
                    </div>
                </div>

                <div class="box-body">

                    <div class="table-responsive">

                        <div id="tableViewWrapper">

                            <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                            </table>
                        </div>

                        <div id="calendarViewWrapper" class="d-none">


                            <div id="weeklyPlannerCalendar"></div>
                        </div>


                    </div>

                </div>
            </div>
        </section>

    </div>
</div>

</div>
</div>

<!-- ADD MODAL -->
<div class="modal modal-lg new_modal_design" id="plannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="plannerForm">
                <input type="hidden" name="id" id="planner_id">

                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-lightbulb-o"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h4 class="modal-title" id="modalTitle">Add Weekly Planner</h4>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Plan visits and sales activities for the week.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="planner_date" id="planner_date" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Activity Type <span class="text-danger">*</span></label>
                            <select name="activity_type" id="activity_type" class="form-select">
                                <option value="">Select</option>
                                <option value="visit">Visit</option>
                                <option value="other">Other Activity</option>
                            </select>
                        </div>
                    </div>

                    <!-- VISIT -->
                    <div id="visit_section" style="display:none;">
                        <hr>
                        <label>Account Type</label>
                        <select name="account_type" id="account_type" class="form-select mb-2">
                            <option value="">Select</option>
                            <option value="existing">Existing Customer</option>
                            <option value="new">New Customer</option>
                        </select>

                        <div id="existing_section" style="display:none;">
                            <div class="row">

                                <div class="col-md-6">
                                    <label>Company *</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="">Select Company</option>
                                        <?php foreach ($companies as $c) { ?>
                                            <option value="<?= encrypt_id($c->company_id) ?>">
                                                <?= $c->company_name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger small"></span>
                                </div>

                                <div class="col-md-6">
                                    <label>Contact ID *</label>
                                    <select name="contact_id" id="contact_id" class="form-control">
                                        <option value="">Select Person</option>
                                    </select>
                                    <span class="text-danger small"></span>
                                </div>

                            </div>




                        </div>

                        <div id="new_section" style="display:none;">

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Person Name*</label>
                                    <input type="text" name="new_person_name" class="form-control mb-2" placeholder="Person Name">

                                    <span class="text-danger small"></span>
                                </div>

                                <div class="col-md-6">
                                    <label>Person Mobile Number*</label>
                                    <input type="text" name="new_person_mobile" class="form-control" placeholder="Mobile Number">


                                    <span class="text-danger small"></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- OTHER -->
                    <div class="mb-3" id="other_section" style="display:none;">
                        <label for="other_activity" class="form-label">
                            Activity <span class="text-danger">*</span>
                        </label>

                        <select class="form-select" name="other_activity" id="other_activity">
                            <option value="">Select Activity</option>
                            <option value="Fairs & Marts">Fairs & Marts</option>
                            <option value="In House">In House</option>
                            <option value="Others">Others</option>
                            <option value="Sales Blitz">Sales Blitz</option>
                            <option value="Tele Calling">Tele Calling</option>
                        </select>
                    </div>


                    <div class="mt-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal modal-lg new_modal_design" id="editPlannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editPlannerForm" method="post">
                <input type="hidden" id="edit_id" name="id">

                <div class="custom-page-header">
                    <div class="header-left">
                        <div class="header-icon-box">
                            <i class="fa fa-lightbulb-o"></i>
                        </div>
                        <div class="header-content">
                            <div class="modal-header hotel_modal_header">
                                <h4 class="modal-title">Edit Weekly Planner</h4>
                                <div class="hotel_banner"></div>
                            </div>
                            <ol class="custom-breadcrumb">
                                <li>
                                    <i class="fa fa-info-circle"></i>
                                    Update the planned visit or activity details.
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="header-banner">
                        <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="planner_date" id="edit_planner_date" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Activity Type <span class="text-danger">*</span></label>
                            <select name="activity_type" id="edit_activity_type" class="form-select">
                                <option value="">Select</option>
                                <option value="visit">Visit</option>
                                <option value="other">Other Activity</option>
                            </select>
                        </div>
                    </div>

                    <div id="edit_visit_section" style="display:none;">
                        <hr>
                        <label>Account Type</label>
                        <select name="account_type" id="edit_account_type" class="form-select mb-2">
                            <option value="">Select</option>
                            <option value="existing">Existing Customer</option>
                            <option value="new">New Customer</option>
                        </select>

                        <div id="edit_existing_section" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Company *</label>
                                    <select name="company_id" id="edit_company_id" class="form-control">
                                        <option value="">Select Company</option>
                                        <?php foreach ($companies as $c) { ?>
                                            <option value="<?= encrypt_id($c->company_id) ?>">
                                                <?= $c->company_name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Contact ID *</label>
                                    <select name="contact_id" id="edit_contact_id" class="form-control">
                                        <option value="">Select Person</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="edit_new_section" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Person Name*</label>
                                    <input type="text" name="new_person_name" id="edit_new_person_name" class="form-control mb-2" placeholder="Person Name">
                                </div>

                                <div class="col-md-6">
                                    <label>Person Mobile Number*</label>
                                    <input type="text" name="new_person_mobile" id="edit_new_person_mobile" class="form-control" placeholder="Mobile Number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="edit_other_section" style="display:none;">
                        <label for="edit_other_activity" class="form-label">
                            Activity <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="other_activity" id="edit_other_activity">
                            <option value="">Select Activity</option>
                            <option value="Fairs & Marts">Fairs & Marts</option>
                            <option value="In House">In House</option>
                            <option value="Others">Others</option>
                            <option value="Sales Blitz">Sales Blitz</option>
                            <option value="Tele Calling">Tele Calling</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateBtn">Update Planner</button>
                </div>
            </form>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    // validation rules for comments
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
    }



    <?php if ($this->session->flashdata('team_group_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('team_group_success_msg'); ?>')

    <?php $this->session->set_flashdata('team_group_success_msg', '');
    } ?>
</script>


<script>
    function csrfData(data) {
        if (window.CSRF) {
            data[window.CSRF.name] = window.CSRF.hash;
        }
        return data;
    }

    function refreshCsrf(response) {
        if (response && response.csrfHash && window.CSRF) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function ensureSelectValue(selector, value, label) {
        if (!value) return;

        if ($(selector + ' option[value="' + value + '"]').length === 0) {
            $(selector).append(new Option(label || 'Selected', value, true, true));
        }

        $(selector).val(value);
    }

    function loadPlannerContacts(companySelector, contactSelector, selectedContact) {
        let company_id = $(companySelector).val();
        $(contactSelector).html('<option value="">Loading...</option>');

        if (company_id !== '') {
            $.ajax({
                url: "<?= base_url('superAdmin/SalesVisits/get_company_contacts') ?>",
                type: "POST",
                data: csrfData({
                    company_id: company_id,
                    selected_contact_id: selectedContact || ''
                }),
                dataType: "json",
                success: function(res) {
                    refreshCsrf(res);

                    let options = '<option value="">Select Person</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            let selected = selectedContact && row.contact_id == selectedContact ? 'selected' : '';
                            options += `<option ${selected} value="${row.contact_id}">
                                ${row.first_name} ${row.last_name} (${row.mobile_number})
                            </option>`;
                        });
                    } else {
                        options += '<option value="">No contacts found</option>';
                    }

                    $(contactSelector).html(options);
                }
            });
        } else {
            $(contactSelector).html('<option value="">Select Person</option>');
        }
    }

    $(document).ready(function() {

        $('#company_id').change(function() {
            loadPlannerContacts('#company_id', '#contact_id');
        });

        $('#edit_company_id').change(function() {
            loadPlannerContacts('#edit_company_id', '#edit_contact_id');
        });

        $('#open-add-modal').click(function() {

            $('#plannerForm')[0].reset();
            $('#planner_id').val('');
            $('#modalTitle').text('Add Weekly Planner');
            $('#visit_section, #other_section, #existing_section, #new_section').hide();
            $('#plannerModal').modal('show');
        });

        $('#activity_type').change(function() {

            $('#visit_section, #other_section').hide();

            if (this.value === 'visit') $('#visit_section').show();
            if (this.value === 'other') $('#other_section').show();
        });

        $('#account_type').change(function() {

            $('#existing_section, #new_section').hide();

            if (this.value === 'existing') $('#existing_section').show();
            if (this.value === 'new') $('#new_section').show();
        });

        $('#plannerForm').on('submit', function(e) {
            e.preventDefault();
            insertWeeklyPlanner();
        });

        function insertWeeklyPlanner() {

            let btn = $('#saveBtn');
            btn.prop('disabled', true).text('Adding...');

            $.ajax({
                url: '<?= base_url("insert-weekly-planner") ?>',
                type: 'POST',
                data: $('#plannerForm').serialize() + '&' + $.param(csrfData({})),
                dataType: 'json',
                success: function(res) {
                    refreshCsrf(res);

                    if (res.status === 'success') {
                        toastr.success(res.message);
                        $('#plannerModal').modal('hide');
                        // $('#plannerForm')[0].reset();
                        fetchPlannerTable();
                    } else {
                        toastr.error(res.message || 'Insert failed');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save');
                }
            });
        }

    });


    $(document).on('click', '.delete-weekly-planner', function() {

        let id = $(this).data('record_id');

        if (!confirm('Are you sure you want to delete this weekly planner record?')) return;

        $.ajax({
            url: "<?= base_url('superAdmin/WeeklyPlanner/delete') ?>",
            type: "POST",
            data: csrfData({
                id: id
            }),
            dataType: "json",
            success: function(response) {
                refreshCsrf(response);
                if (response.status === 'success') {
                    toastr.success(response.message);
                    fetchPlannerTable();
                }
            }
        });
    });


    fetchPlannerTable();

    function fetchPlannerTable() {

        $.ajax({
            url: "<?= base_url('superAdmin/WeeklyPlanner/fetch') ?>",
            type: "GET",
            success: function(data) {
                $('#tableViewWrapper').html(data);
            }
        });
    }


    $(document).ready(function() {

        $(document).on('click', '.edit-weekly-planner', function() {

            let id = $(this).data('record_id');

            $.ajax({
                url: "<?= base_url('edit-weekly-planner') ?>",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(res) {

                    if (res.status === 'success') {

                        let d = res.data;

                        $('#edit_id').val(d.id);
                        $('#edit_planner_date').val(d.planner_date);
                        $('#edit_activity_type').val(d.activity_type);
                        $('#edit_account_type').val(d.account_type);
                        ensureSelectValue('#edit_company_id', d.company_id, d.company_name);
                        $('#edit_new_person_name').val(d.new_person_name);
                        $('#edit_new_person_mobile').val(d.new_person_mobile);
                        $('#edit_other_activity').val(d.other_activity);
                        $('#edit_description').val(d.description);

                        $('#edit_visit_section, #edit_other_section').hide();
                        $('#edit_existing_section, #edit_new_section').hide();

                        if (d.activity_type === 'visit') {

                            $('#edit_visit_section').show();

                            if (d.account_type === 'existing') {
                                $('#edit_existing_section').show();
                                loadPlannerContacts('#edit_company_id', '#edit_contact_id', d.contact_id);
                            }

                            if (d.account_type === 'new') {
                                $('#edit_new_section').show();
                            }
                        }

                        if (d.activity_type === 'other') {
                            $('#edit_other_section').show();
                        }

                        $('#editPlannerModal').modal('show');
                    }
                }
            });
        });

        $('#edit_activity_type').change(function() {

            $('#edit_visit_section, #edit_other_section').hide();

            if (this.value === 'visit') $('#edit_visit_section').show();
            if (this.value === 'other') $('#edit_other_section').show();
        });

        $('#edit_account_type').change(function() {

            $('#edit_existing_section, #edit_new_section').hide();

            if (this.value === 'existing') $('#edit_existing_section').show();
            if (this.value === 'new') $('#edit_new_section').show();
        });

        $('#editPlannerForm').on('submit', function(e) {

            e.preventDefault();

            let btn = $('#updateBtn');
            btn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "<?= base_url('update-weekly-planner') ?>",
                type: "POST",
                data: $(this).serialize() + '&' + $.param(csrfData({})),
                dataType: "json",
                success: function(res) {
                    refreshCsrf(res);

                    if (res.status === 'success') {
                        toastr.success(res.message);
                        $('#editPlannerModal').modal('hide');
                        fetchPlannerTable();
                    } else {
                        toastr.error(res.message || 'Update failed');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Update Planner');
                }
            });
        });

    });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script>
    $(document).ready(function() {

        var calendarEl = document.getElementById('salesVisitCalendar');
        if (!calendarEl) {
            return;
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {

            initialView: 'dayGridWeek',
            height: 'auto',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },

            events: function(fetchInfo, successCallback, failureCallback) {

                $.ajax({
                    url: "<?= base_url('superAdmin/SalesVisits/getCalendarVisits') ?>",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        successCallback(res);
                    },
                    error: function() {
                        failureCallback();
                    }
                });

            },

            eventClick: function(info) {
                info.jsEvent.preventDefault();
                loadVisitDetails(info.event.id);
            }

        });

        calendar.render();
    });


    function loadVisitDetails(visit_id) {
        $('#visitModal').modal('show');
        $('#visitModalBody').html('Loading...');

        $.ajax({
            url: "<?= base_url('superAdmin/SalesVisits/getVisitDetails') ?>",
            type: "GET",
            data: {
                visit_id: visit_id
            },
            success: function(html) {
                $('#visitModalBody').html(html);
            }
        });
    }


    $(document).ready(function() {

        // Default View: Table
        $('#btnTableView').on('click', function() {

            $(this).addClass('btn-primary active')
                .removeClass('btn-outline-primary');

            $('#btnCalendarView').removeClass('btn-primary active')
                .addClass('btn-outline-primary');

            $('#tableViewWrapper').removeClass('d-none');
            $('#calendarViewWrapper').addClass('d-none');
        });

        let weeklyPlannerCalendar = null;

        $('#btnCalendarView').on('click', function() {

            $(this).addClass('btn-primary active')
                .removeClass('btn-outline-primary');

            $('#btnTableView').removeClass('btn-primary active')
                .addClass('btn-outline-primary');

            $('#calendarViewWrapper').removeClass('d-none');
            $('#tableViewWrapper').addClass('d-none');

            // Initialize calendar only once
            if (!weeklyPlannerCalendar) {

                weeklyPlannerCalendar = new FullCalendar.Calendar(
                    document.getElementById('weeklyPlannerCalendar'), {
                        initialView: 'dayGridWeek',
                        height: 'auto',

                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek'
                        },

                        events: "<?= base_url('superAdmin/WeeklyPlanner/getCalendarPlans') ?>",

                        eventClick: function(info) {
                            loadWeeklyPlannerDetails(info.event.id);
                        }
                    }
                );

                weeklyPlannerCalendar.render();
            } else {
                // Important when toggling hidden divs
                weeklyPlannerCalendar.updateSize();
            }



        });



        function loadWeeklyPlannerDetails(id) {

            $.ajax({
                url: "<?= base_url('edit-weekly-planner') ?>",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(res) {

                    if (res.status === 'success') {

                        let d = res.data;

                        $('#edit_id').val(d.id);
                        $('#edit_planner_date').val(d.planner_date);
                        $('#edit_activity_type').val(d.activity_type).trigger('change');
                        $('#edit_account_type').val(d.account_type).trigger('change');

                        ensureSelectValue('#edit_company_id', d.company_id, d.company_name);
                        if (d.activity_type === 'visit' && d.account_type === 'existing') {
                            loadPlannerContacts('#edit_company_id', '#edit_contact_id', d.contact_id);
                        }
                        $('#edit_new_person_name').val(d.new_person_name);
                        $('#edit_new_person_mobile').val(d.new_person_mobile);
                        $('#edit_other_activity').val(d.other_activity);
                        $('#edit_description').val(d.description);

                        $('#editPlannerModal').modal('show');
                    }
                }
            });
        }




    });
</script>
