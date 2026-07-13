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
                                <li class="breadcrumb-item" aria-current="page">Hotel Admin</li>
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
                                <!-- Include Bootstrap & FontAwesome CDN (if not already included) -->
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

                                        <!-- Lead Status -->
                                        <div class="col-md-4">
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
                                        <div class="col-md-6">
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

                                        <!-- Stage -->
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
                                                    <option value="Enquiry not received" <?= ($lead->disposition == 'Enquiry not received') ? 'selected' : '' ?>>Enquiry not received</option>
                                                    <option value="Denied" <?= ($lead->disposition == 'Denied') ? 'selected' : '' ?>>Denied</option>
                                                </select>
                                                <span id="disposition_error" class="text-danger small"></span>
                                            </div>
                                        </div>




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

<!-- Include jQuery Validation plugin -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
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
</script>
<script>
    $(document).ready(function() {

        // Username
        $('#username').focusout(function() {
            let value = this.value;
            if (value === "") {
                $('#username_error').html('Please Enter Username');
            } else {
                $('#username_error').html('');
            }
        });

        // Phone Number
        $('#phone_number').focusout(function() {
            let value = this.value;
            let phoneRegex = /^[0-9]{10}$/;
            if (value === "") {
                $('#phone_number_error').html('Please Enter Phone Number');
            } else if (!phoneRegex.test(value)) {
                $('#phone_number_error').html('Invalid Phone Number (10 digits)');
            } else {
                $('#phone_number_error').html('');
            }
        });

        // Email
        $('#email').focusout(function() {
            let value = this.value;
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value !== "" && !emailRegex.test(value)) {
                $('#email_error').html('Invalid Email Format');
            } else {
                $('#email_error').html('');
            }
        });

        // Date
        $('#date').focusout(function() {
            if (!this.value) {
                $('#date_error').html('Please Select Booking Date');
            } else {
                $('#date_error').html('');
            }
        });

        // Time
        $('#time').focusout(function() {
            if (!this.value) {
                $('#time_error').html('Please Select Booking Time');
            } else {
                $('#time_error').html('');
            }
        });

        // Property
        $('#property').change(function() {
            if (this.value === "") {
                $('#property_error').html('Please Select a Hotel');
            } else {
                $('#property_error').html('');
            }
        });

        // Department
        $('#type').change(function() {
            if (this.value === "") {
                $('#type_error').html('Please Select a Department');
            } else {
                $('#type_error').html('');
            }
        });

        // Lead Status
        $('#lead_status').change(function() {
            if (this.value === "") {
                $('#lead_status_error').html('Please Select Lead Status');
            } else {
                $('#lead_status_error').html('');
            }
        });

        // Stage


        // Query
        $('#query').focusout(function() {
            if (this.value === "") {
                $('#query_error').html('Please Enter Query');
            } else {
                $('#query_error').html('');
            }
        });

        // Remark


        // Created Date
        // $('#created_date').focusout(function() {
        //     if (!this.value) {
        //         $('#created_date_error').html('Please Select Created Date');
        //     } else {
        //         $('#created_date_error').html('');
        //     }
        // });

        // Submit Form via AJAX

    });


    $("#disposition").change(function() {

        updateDynamicFields();

    })



    const existingLeadData = <?php echo json_encode($lead); ?>;





    function updateDynamicFields() {
        const status = $("#lead_status").val()?.toLowerCase();
        const disposition = $("#disposition").val()?.toLowerCase();
        let department = $('#type').find(':selected').data('name')?.toLowerCase();

        $('#leadDepartment').val(department);
        const container = $("#dynamicFields");

        // Reset previous fields
        container.html("");

        // Reservation - Closed
        if (disposition === "reservation" && status === "closed") {
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
    <label>Email F&B Team</label>
    <input type="email" name="fnb_email" class="form-control">
</div>
`);
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
        if ((disposition === "shopping - follow up") && (status === "in progress")) {
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

            if ($(this).attr('type') === 'file' && this.files.length > 0) {
                formData.append(name, this.files[0]); // Single file
            } else {
                formData.append(name, $(this).val());
            }
        });

        // AJAX request
        $.ajax({
            url: '<?php echo base_url("update-lead-agent"); ?>',
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