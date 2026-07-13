<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Edit Lead</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Lead</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Edit Lead</h4>

                        </div>
                        <div class="box-body">
                            <div class="container mt-1">


                                <form id="leadEditForm">
                                    <input type="hidden" name="lead_id" value="<?= $lead->id ?>">
                                    <div class="row g-3">

                                        <!-- Phone Number -->
                                        <div class="col-md-4">
                                            <label>Phone</label>
                                            <input type="number" name="phone_number" class="form-control" value="<?= $lead->phone_number ?>" required id="phone_number">
                                        </div>

                                        <input type="hidden" name="lead_id" value="<?= $lead->id ?>">



                                        <!-- Guest Name -->
                                        <div class="col-md-4">
                                            <label>Name</label>
                                            <input type="text" name="user_name" class="form-control" value="<?= $lead->user_name ?>" required id="user_name">
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-4">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= $lead->email ?>" id="email">
                                        </div>

                                        <!-- Property -->
                                        <div class="col-md-4">
                                            <label>Property</label>
                                            <select name="property" class="form-control" required id="property">
                                                <?php foreach ($hotel_admin as $each): ?>
                                                    <option value="<?= $each->hotel_id ?>" <?= ($each->hotel_id == $lead->property) ? 'selected' : '' ?>>
                                                        <?= $each->hotel_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-4">
                                            <label>Department</label>
                                            <select name="type" class="form-control" required id="type">
                                                <?php foreach ($departments as $each): ?>
                                                    <option value="<?= $each->department_id ?>"
                                                        data-name="<?= $each->department_name; ?>"
                                                        <?= ($each->department_id == $lead->type) ? 'selected' : '' ?>>
                                                        <?= $each->department_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="lead_type">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Type
                                                </label>
                                                <select name="lead_type" id="lead_type" class="form-control">
                                                    <option value="Hot" <?= ($lead->lead_type == 'Hot') ? 'selected' : '' ?>>Hot</option>
                                                    <option value="Warm" <?= ($lead->lead_type == 'Warm') ? 'selected' : '' ?>>Warm</option>
                                                    <option value="Cold" <?= ($lead->lead_type == 'Cold') ? 'selected' : '' ?>>Cold</option>
                                                </select>
                                                <span id="lead_type_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="disposition"><i class="fa fa-list me-1 text-dark"></i>Stage</label>
                                                <select class="form-control" name="disposition" id="disposition">
                                                    <option value="" disabled <?= empty($lead->disposition) ? 'selected' : '' ?>>Select Stage</option>
                                                    <option value="Information/Enquiry" <?= ($lead->disposition == 'Information/Enquiry') ? 'selected' : '' ?>>Information/Enquiry</option>
                                                    <option value="Reservation" <?= ($lead->disposition == 'Reservation') ? 'selected' : '' ?>>Reservation</option>
                                                    <option value="Shopping - Follow up" <?= ($lead->disposition == 'Shopping - Follow up') ? 'selected' : '' ?>>Shopping - Follow up</option>
                                                    <option value="Shopping - No Follow up" <?= ($lead->disposition == 'Shopping - No Follow up') ? 'selected' : '' ?>>Shopping - No Follow up</option>
                                                    <option value="Trash" <?= ($lead->disposition == 'Trash') ? 'selected' : '' ?>>Trash</option>
                                                    <option value="Denied" <?= ($lead->disposition == 'Denied') ? 'selected' : '' ?>>Denied</option>
                                                </select>
                                                <span id="disposition_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Lead Status -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lead Status</label>
                                                <select name="status" id="lead_status" class="form-control" required>
                                                    <option value="Open" <?= ($lead->status == 'Open') ? 'selected' : '' ?>>Open</option>
                                                    <option value="On Hold" <?= ($lead->status == 'On Hold') ? 'selected' : '' ?>>On Hold</option>
                                                    <option value="In Progress" <?= ($lead->status == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="Closed" <?= ($lead->status == 'Closed') ? 'selected' : '' ?>>Closed</option>
                                                </select>
                                                <span id="lead_status_error" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <!-- Lead Type -->


                                        <!-- Stage -->





                                        <input type="hidden" id="leadDepartment" name="leadDepartment">


                                        <div id="dynamicFields"></div>


                                        <!-- Query -->
                                        <div class="col-md-6">
                                            <label>Query</label>
                                            <textarea name="query" class="form-control" id="query"><?= $lead->query ?></textarea>
                                        </div>

                                        <!-- Remark -->
                                        <div class="col-md-6">
                                            <label>Remark</label>
                                            <textarea name="remark" class="form-control" id="remark"><?= $lead->remark ?></textarea>
                                        </div>

                                        <!-- Submit -->
                                        <div class="col-md-12 text-end mt-3">
                                            <!-- Back Button -->
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                                <i class="fa fa-arrow-left me-1"></i> Back
                                            </button>

                                            <button type="submit" class="btn btn-primary">Update Lead</button>
                                        </div>
                                    </div>
                                </form>






                                <div id="response" class="mt-3"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include jQuery Validation plugin -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
    .select2-dropdown .table-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select2-dropdown .table-checkbox {
        pointer-events: auto;
        cursor: pointer;
        margin: 0;
        accent-color: #0d6efd;
    }
</style>
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
</script>
<script>
    $(document).ready(function() {



        // Remark



    });


    $("#disposition").change(function() {

        updateDynamicFields();

    })



    const existingLeadData = <?php echo json_encode($lead); ?>;





    function updateDynamicFields() {
        const disposition = $("#disposition").val();
        let department = $('#type').find(':selected').data('name')?.toLowerCase();

        $('#leadDepartment').val(department);
        const container = $("#dynamicFields");

        // Reset previous fields
        container.html("");


        if ((disposition === "Information/Enquiry") || (disposition === "Trash") || (disposition === "Denied") || (disposition === "Shopping - No Follow up")) {

            $("#lead_status").val('Closed');

        }

        // Reservation - Closed
        if (disposition === "Reservation") {

            $("#lead_status").val('Closed');

            if (department === "rooms") {
                container.append(`
<div class="mb-3">
    <label>Check-in Date</label>
    <input type="date" name="checkin_date" class="form-control">
</div>
<div class="mb-3">
    <label>Check-out Date</label>
    <input type="date" name="checkout_date" class="form-control">
</div>
<div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
</div>
<div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
</div>
<div class="mb-3">
    <label>Reservation Number</label>
    <input type="text" name="reservation_number" class="form-control">
</div>
<div class="mb-3">
    <label>Bill Attachment</label>
    <input type="file" name="bill_attachment" class="form-control">
    ${existingLeadData.bill_attachment ?
    `<a href="/uploads/bills/${existingLeadData.bill_attachment}" target="_blank">View Uploaded File</a>`
    : ""}
</div>
<div class="mb-3">
    <label>Email Reservation Team</label>
    <input type="email" name="reservation_email" class="form-control">
</div>
`);
            } else if (department === "restaurants") {
                container.append(`
<div class="col-md-4 mb-3">
    <label>Booking Date</label>
    <input type="date" name="booking_date" class="form-control">
</div>
<div class="col-md-4 mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
</div>
<div class="col-md-4 mb-3">
    <label>Restaurant <span class="text-danger">*</span></label>
    <select name="restaurant_id" id="restaurant_id" class="form-select">
        <option value="">Select Restaurant</option>
    </select>
    <div class="text-danger error-label" id="restaurant_id_error"></div>
</div>
<div class="col-md-4 mb-3">
    <label>Table Category <span class="text-danger">*</span></label>
    <select name="table_category_id" id="table_category_id" class="form-select">
        <option value="">Select Category</option>
    </select>
    <div class="text-danger error-label" id="table_category_id_error"></div>
</div>
<div class="col-md-4 mb-3">
    <label>Tables <span class="text-danger">*</span></label>
    <select name="table_id[]" id="table_id" class="form-control ca-select-tag" multiple>
        <option value="">Select Table</option>
    </select>
    <div class="text-danger error-label" id="table_id_error"></div>
    <small class="d-block text-muted mt-1">You can select multiple tables using checkboxes</small>
</div>
<div class="col-md-4 mb-3">
    <label>Slot Type <span class="text-danger">*</span></label>
    <select name="slot_type_id" id="slot_type_id" class="form-select">
        <option value="">Select Slot</option>
    </select>
    <div class="text-danger error-label" id="slot_type_id_error"></div>
</div>
<div class="col-md-4 mb-3">
    <label>Time Slot <span class="text-danger">*</span></label>
    <select name="time_slot_id" id="time_slot_id" class="form-select">
        <option value="">Select Time Slot</option>
    </select>
    <div class="text-danger error-label" id="time_slot_id_error"></div>
</div>
<div class="col-md-4 mb-3">
    <label>Arrival Time</label>
    <input type="time" name="arrival_time" class="form-control">
</div>
<div class="col-md-6">
    <label>Special Occasion (if any)</label>
    <input type="text" name="special_occasion" class="form-control">
</div>
<div class="col-md-6">
    <label>Special Request</label>
    <textarea name="special_request" class="form-control" row=1></textarea>
</div>
<div class="col-md-4 mb-3">
    <label>Expected Revenue</label>
    <input type="number" name="amount" class="form-control" step="0.01">
</div>
`);

                loadRestaurants(property, existingLeadData);
                loadSlotTypes(existingLeadData);

                setTimeout(() => {
                    initializeTableSelect2();
                }, 100);
            } else if (department === "banquets") {
                container.append(`
<div class="mb-3">
    <label>Booking Date</label>
    <input type="date" name="booking_date" class="form-control">
</div>
<div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
</div>
<div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
</div>
<div class="mb-3">
    <label>Email Banquet Team</label>
    <input type="email" name="banquet_email" class="form-control">
</div>
`);
            }
        }

        // Shopping - Followup - In Progress
        if ((disposition === "Shopping - Follow up")) {

            $("#lead_status").val('In Progress');

            container.append(`
<div class="mb-3">
    <label>Booking Enquiry Date</label>
    <input type="date" name="booking_enquiry_date" class="form-control">
</div>
<div class="mb-3">
    <label>Follow-up Date</label>
    <input type="date" name="followup_date" class="form-control">
</div>
<div class="mb-3">
    <label>2nd Follow-up Date</label>
    <input type="date" name="second_followup_date" class="form-control">
</div>
`);

            if (department === 'banquets') {
                container.append(`
<div class="mb-3">
    <label>Transfer Lead to Manager Level</label>
    <select name="transfer_to_manager" class="form-control">
        <option value="">Select</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
</div>
`);
            }
        }




        /** ✅ Auto-fill existing values */
        if (typeof existingLeadData !== "undefined") {
            for (let key in existingLeadData) {
                const field = container.find(`[name="${key}"]`);
                if (field.length) {
                    field.val(existingLeadData[key]);
                }
            }
        }
    }





    $('#leadEditForm').on('submit', function(e) {
        e.preventDefault();

        // Collect form values
        const formValues = {
            lead_id: $('input[name="lead_id"]').val(),
            user_name: $('input[name="user_name"]').val(),
            phone_number: $('input[name="phone_number"]').val(),
            email: $('input[name="email"]').val(),
            user_channel: $('input[name="user_channel"]').val(),
            property: $('select[name="property"]').val(),
            department: $('select[name="type"]').val(),
            status: $('select[name="status"]').val(),
            query: $('textarea[name="query"]').val(),
            remark: $('textarea[name="remark"]').val(),
            lead_type: $('select[name="lead_type"]').val(),
            lead_status: $('select[name="lead_status"]').val(),
            leadDepartment: $('#leadDepartment').val(),
            disposition: $('#disposition').val()
        };

        // Basic validation
        if (!formValues.user_name || !formValues.phone_number || !formValues.property ||
            !formValues.department || !formValues.status || !formValues.query) {
            alert("Please fill all required fields.");
            return;
        }

        // Build FormData
        let formData = new FormData();

        // Append fixed fields
        Object.keys(formValues).forEach(key => {
            if (formValues[key] !== null && formValues[key] !== undefined) {
                formData.append(key, formValues[key]);
            }
        });

        // Append dynamic fields (inside #dynamicFields)
        $('#dynamicFields').find('input, select, textarea').each(function() {
            const name = $(this).attr('name');
            if (!name) return;

            if (name === 'table_id[]') {
                return; // Skip multi-table values here, handle below
            }

            if ($(this).attr('type') === 'file' && this.files.length > 0) {
                formData.append(name, this.files[0]); // Single file
            } else {
                formData.append(name, $(this).val());
            }
        });

        const selectedTables = $('#table_id').val();
        if (selectedTables && Array.isArray(selectedTables) && selectedTables.length > 0) {
            formData.append('table_id', selectedTables.join(','));
        } else if (selectedTables) {
            formData.append('table_id', selectedTables);
        }

        // AJAX request
        $.ajax({
            url: '<?php echo base_url("update-lead-super-admin"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                console.log(response);
                if (response.status) {
                    window.history.back();

                } else {
                    alert('Failed to update lead: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert("An unexpected error occurred. Please try again.");
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).text('Save');
            }
        });
    });





    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 300; // milliseconds delay after user stops typing

        $('#phone_number').on('input', function() {
            clearTimeout(typingTimer);
            const cli = $(this).val();

            if (/^\d{10}$/.test(cli)) {
                typingTimer = setTimeout(function() {
                    // Perform AJAX call only for valid 10-digit numbers
                    $.ajax({
                        url: '<?= base_url('LeadController/get_last_lead_by_cli') ?>',
                        type: 'POST',
                        data: {
                            cli: cli
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#username').val(response.data.user_name);
                                $('#phone_number').val(response.data.phone_number); // optional
                                $('#email').val(response.data.email);
                            } else {
                                console.log('No lead data found');
                            }
                        },
                        error: function(xhr) {
                            console.error('AJAX error:', xhr.responseText);
                        }
                    });
                }, typingDelay);
            }
        });
    });

    $(document).ready(function() {
        updateDynamicFields();
    })
</script>