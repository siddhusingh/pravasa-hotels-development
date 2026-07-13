<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Create New Lead</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Hotel Agent</li>
                                <li class="breadcrumb-item active" aria-current="page">Create New Lead</li>
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
                            <h4 class="box-title">Create New Lead</h4>

                        </div>
                        <div class="box-body">
                            <div class="container mt-5">
                                <!-- Include jQuery -->
                                <form id="leadForm">
                                    <div class="row g-3">

                                        <!-- Phone Number -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input type="number" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required>
                                                <span id="phone_number_error" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Guest Name</label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username">
                                                <span id="username_error" class="text-danger"></span>
                                            </div>
                                        </div>



                                        <!-- Email -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email (Optional)</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                                <span id="email_error" class="text-danger"></span>
                                            </div>
                                        </div>


                                        <!-- Lead Source -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="lead_type">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Source
                                                </label>
                                                <select name="user_channel" id="user_channel" class="form-control">
                                                    <option value="phone" selected>phone</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Walking">Walking</option>

                                                </select>
                                                <span id="user_channel_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Hotel -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="property"><i class="fa fa-hotel me-1 text-danger"></i>Hotel (Property)</label>
                                                <select name="property" id="property" class="form-control" required>
                                                    <option selected disabled value="">Select Hotel</option>
                                                    <?php foreach ($hotel_admin as $each) { ?>
                                                        <option value="<?php echo $each->hotel_id ?>"><?php echo $each->hotel_name ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span id="property_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="type"><i class="fa fa-sitemap me-1 text-muted"></i>Department (Type)</label>
                                                <select name="type" id="type" class="form-control" required>
                                                    <option selected disabled value="">Select Department</option>
                                                    <?php foreach ($departments as $each) { ?>
                                                        <option value="<?php echo $each->department_id ?>"
                                                            data-name="<?php echo $each->department_name; ?>"><?php echo $each->department_name ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span id="type_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="lead_type">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Type
                                                </label>
                                                <select name="lead_type" id="lead_type" class="form-control">
                                                    <option value="Hot">Hot</option>
                                                    <option value="Warm">Warm</option>
                                                    <option value="Cold" selected>Cold</option>
                                                </select>
                                                <span id="lead_type_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="disposition"><i class="fa fa-list me-1 text-dark"></i>Stage</label>
                                                <select class="form-control" name="disposition" id="disposition" disabled>
                                                    <option value="" selected disabled>Select Stage</option>

                                                    <option value="Not Contacted">Not Contacted</option>
                                                    <option value="General Information">General Information</option>
                                                    <option value="Quotation Sent">Quotation Sent</option>
                                                    <option value="Negotiations">Negotiations</option>
                                                    <option value="Contract Done">Contract Done</option>
                                                    <option value="Advance Received">Advance Received</option>
                                                    <option value="Lead Won">Lead Won</option>
                                                    <option value="Lead Lost">Lead Lost</option>
                                                </select>
                                                <span id="disposition_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Lead Status</label>
                                                <select name="lead_status" id="lead_status" class="form-control" disabled required>
                                                    <option value="Open" selected>Open</option>

                                                    <option value="In Progress">In Progress</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                                <span id="lead_status_error" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <input type="hidden" id="leadDepartment" name="leadDepartment">


                                        <div id="dynamicFields"></div>





                                        <!-- Query -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Query</label>
                                                <textarea name="query" id="query" class="form-control" rows="1" placeholder="Enter query" required></textarea>
                                                <span id="query_error" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remark"><i class="fa fa-question-circle me-1 text-primary"></i>remark</label>
                                                <textarea name="remark" id="remark" class="form-control" rows="1" placeholder="Enter remark"></textarea>
                                                <span id="remark_error" class="text-danger small"></span>
                                            </div>
                                        </div>




                                        <!-- Submit -->
                                        <div class="col-md-12 mt-3">
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                                <i class="fa fa-arrow-left me-1"></i> Back
                                            </button>
                                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
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
            let value = this.value.trim();
            if (value === "") {
                $('#username_error').html('Please Enter Username');
            } else {
                $('#username_error').html('');
            }
        });

        // Phone Number
        $('#phone_number').focusout(function() {
            let value = this.value.trim();
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
            let value = this.value.trim();
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
        $('#disposition').change(function() {
            if (this.value === "") {
                $('#disposition_error').html('Please Select a Stage');
            } else {
                $('#disposition_error').html('');
            }
        });

        // Query
        $('#query').focusout(function() {
            if (this.value.trim() === "") {
                $('#query_error').html('Please Enter Query');
            } else {
                $('#query_error').html('');
            }
        });

        // Remark
        $('#remark').focusout(function() {
            if (this.value.trim() === "") {
                $('#remark_error').html('Please Enter Remark');
            } else {
                $('#remark_error').html('');
            }
        });

        // Created Date
        $('#created_date').focusout(function() {
            if (!this.value) {
                $('#created_date_error').html('Please Select Created Date');
            } else {
                $('#created_date_error').html('');
            }
        });

        // Submit Form via AJAX

    });

    $("#disposition").change(function() {

        updateDynamicFields();
        console.log("working")

    })




    function updateDynamicFields() {
        const disposition = $("#disposition").val();
        let department = $('#type').find(':selected').data('name')?.toLowerCase();
        $('#leadDepartment').val(department); // hidden input now holds "rooms"



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
        if ((disposition == "Shopping - Follow up")) {

            $("#lead_status").val('In Progress');

            container.append(`
            <div class="mb-3">
                <label>Booking Enquiry Date</label>
                <input type="date" name="booking_date" class="form-control">
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

            if (department == 'Banquets') {
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
    }



    $('#leadForm').on('submit', function(e) {
        e.preventDefault();

        $("#lead_status").prop('disabled', false);


        // Grab field values
        let username = $('input[name="username"]').val().trim();
        let phone = $('input[name="phone_number"]').val().trim();
        let email = $('input[name="email"]').val().trim();

        let userChannel = $('select[name="user_channel"]').val().trim();
        let property = $('select[name="property"]').val();
        let department = $('select[name="type"]').val();
        let leadStatus = 'Open'
        let query = $('textarea[name="query"]').val().trim();
        let remark = $('textarea[name="remark"]').val().trim();
        let lead_type = $('select[name="lead_type"]').val();
        let lead_status = $('select[name="lead_status"]').val();

        leadDepartment = $('#leadDepartment').val();
        disposition = $('#disposition').val();


        if (disposition == null) {
            disposition = "";
        }


        // collect all dynamic inputs inside container





        // Simple validation condition
        if (
            username && phone && userChannel &&
            property && department && leadStatus && query
        ) {
            let formData = new FormData();

            $('#dynamicFields')
                .find('input, select, textarea')
                .each(function() {
                    let name = $(this).attr('name');
                    let value;

                    if ($(this).attr('type') === 'file') {
                        // file input
                        if (this.files.length > 0) {
                            formData.append(name, this.files[0]);
                        }
                    } else {
                        value = $(this).val();
                        formData.append(name, value);
                    }
                });


            // Append all fields
            formData.append('user_name', username);
            formData.append('phone_number', phone);
            formData.append('email', email);
            formData.append('disposition', disposition);

            formData.append('user_channel', userChannel);
            formData.append('property', property);
            formData.append('type', department);
            formData.append('status', lead_status);
            formData.append('query', query);
            formData.append('remark', remark);
            formData.append('lead_type', lead_type);
            formData.append('leadDepartment', leadDepartment);



            $.ajax({
                url: '<?php echo base_url("insert-lead-agency"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },

                success: function(response) {

                    if (response.duplicate) {
                        alert('Failed to create lead: ' + response.message);
                    }


                    if (response.status) {
                        window.location.href = '<?php echo base_url("view-agency-leads") ?>'
                    } else {
                        alert('Failed to create lead: ' + response.message);
                    }
                }
            });
        } else {
            alert("Please fill all required fields.");
        }
    });

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const cli = urlParams.get('CLI');

        console.log(cli)

        if (cli) {
            $.ajax({
                url: '<?= base_url('LeadController/get_last_lead_by_cli') ?>',
                type: 'POST',
                data: {
                    cli: cli
                },
                dataType: 'json',
                success: function(response) {

                    console.log(response)
                    if (response.status === 'success') {
                        $('#username').val(response.data.user_name);
                        $('#phone_number').val(response.data.phone_number);
                        $('#email').val(response.data.email);
                        $('#query').val(response.data.query);

                    } else {


                        console.log('No lead data found');
                    }
                },
                error: function(xhr) {
                    console.error('AJAX error:', xhr.responseText);
                }
            });
        }
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
</script>