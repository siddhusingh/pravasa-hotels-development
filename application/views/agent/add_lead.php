<!-- Content Wrapper. Contains page content -->
<style>
    #dynamicFields {
        display: contents;
    }

    #dynamicFields .row {
        margin-top: 10px;
    }

    #dynamicFields .col-md-3,
    #dynamicFields .col-md-4,
    #dynamicFields .col-md-6 {
        margin-bottom: 10px;
        margin-left: 5px;
        margin-right: 5px;
    }

    #leadForm .required-marker {
        color: #dc3545;
        margin-left: 3px;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-phone-volume" aria-hidden="true"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Create New Lead</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Agent</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Create New Lead</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
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
                            <div class="container mt-1">
                                <!-- Include Bootstrap & FontAwesome CDN (if not already included) -->

                                <form id="leadForm" novalidate>
                                    <div class="row g-3">

                                        <!-- Phone Number -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number"><i class="fa fa-phone me-1 text-success"></i>Phone Number <span class="required-marker">*</span></label>
                                                <input type="number" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required
                                                    value="<?php if (!empty($_GET['phone'])) {
                                                                echo $_GET['phone'];
                                                            } ?>">
                                                    <span id="phone_number_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="username"><i class="fa fa-user me-1 text-primary"></i>Guest Name <span class="required-marker" id="guestNameRequiredMarker">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username">
                                                <span id="username_error" class="text-danger small"></span>
                                            </div>
                                        </div>



                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email"><i class="fa fa-envelope me-1 text-warning"></i>Email (Optional)</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                                <span id="email_error" class="text-danger small"></span>
                                            </div>
                                        </div>







                                        <!-- Hotel -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Hotel (Property) <span class="required-marker">*</span></label>
                                                <select name="property" id="property" class="form-control" required disabled>
                                                    <option selected disabled value="">Select Hotel</option>
                                                    <?php


                                                    $property = $this->session->userdata('selected_hotel_id');

                                                    foreach ($hotel_admin as $each) { ?>
                                                        <option <?php if ($property == $each->hotel_id) {
                                                                    echo "selected";
                                                                } ?> value="<?php echo $each->hotel_id ?>"><?php echo $each->hotel_name ?></option>
                                                    <?php } ?>
                                                </select>

                                                <span id="property_error" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="type"><i class="fa fa-sitemap me-1 text-muted"></i>Department (Type) <span class="required-marker">*</span></label>
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
                                        <!-- Lead Status -->



                                        <div class="col-md-3">
                                            <label>Assign Lead To</label>

                                            <select name="assigned_to" id="assigned_to" class="form-control">
                                                <option value="">Select User</option>

                                                <?php foreach ($all_assignable_users as $user) { ?>

                                                    <option
                                                        value="<?php echo $user['id']; ?>"
                                                        data-role="<?php echo $user['user_role']; ?>"
                                                        data-email="<?php echo $user['email']; ?>">
                                                        <?php echo $user['name'] . '-' . $user['user_role']; ?>
                                                    </option>

                                                <?php } ?>

                                            </select>
                                        </div>

                                        <input type="hidden" name="assigned_person_user_role" id="assigned_person_user_role">
                                        <input type="hidden" name="assigned_person_email" id="assigned_person_email">



                                        <!-- Lead Status -->




                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="user_channel">
                                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Source <span class="required-marker">*</span>
                                                </label>

                                                <select name="user_channel" id="user_channel" class="form-control">

                                                    <option value="phone" selected>Phone</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Walking">Walking</option>

                                                    <option value="IVR">IVR</option>
                                                    <option value="Sales Call TA">Sales Call TA</option>
                                                    <option value="Employee Referral">Employee Referral</option>
                                                    <option value="Travel Agent">Travel Agent</option>
                                                    <option value="Public Relations">Public Relations</option>
                                                    <option value="Sales Mail">Sales Mail</option>
                                                    <option value="Seminar Partner">Seminar Partner</option>
                                                    <option value="Walk ins">Walk ins</option>
                                                    <option value="Direct Calls">Direct Calls</option>
                                                    <option value="Advertisement">Advertisement</option>
                                                    <option value="TTF">TTF</option>
                                                    <option value="BLTM">BLTM</option>
                                                    <option value="Sales Call MICE">Sales Call MICE</option>
                                                    <option value="Wedmegood">Wedmegood</option>

                                                </select>

                                                <span id="user_channel_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="purpose">
                                                    <i class="fa fa-bullseye me-1 text-secondary"></i>Purpose
                                                </label>

                                                <select name="purpose" id="purpose" class="form-control">

                                                    <option value="">Select Purpose</option>
                                                    <option value="Corporate">Corporate</option>
                                                    <option value="Family">Family</option>
                                                    <option value="Vacation">Vacation</option>
                                                    <option value="Leisure">Leisure</option>
                                                    <option value="Social">Social</option>
                                                    <option value="Wedding">Wedding</option>
                                                    <option value="Pilgrimage">Pilgrimage</option>

                                                </select>

                                                <span id="purpose_error" class="text-danger small"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="disposition">
                                                    <i class="fa fa-list me-1 text-dark"></i>Stage <span class="required-marker">*</span>
                                                </label>

                                                <select class="form-control" name="disposition" id="disposition">
                                                    <option value="" selected>Select Stage</option>

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

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="lead_status"><i class="fa fa-info-circle me-1 text-secondary"></i>Lead Status <span class="required-marker">*</span></label>
                                                <input type="hidden" id="leadDepartment" name="leadDepartment">
                                                <select name="lead_status" id="lead_status" class="form-control" disabled>
                                                    <option value="Open" selected>Open</option>

                                                    <option value="In Progress">In Progress</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                                <span id="lead_status_error" class="text-danger small"></span>
                                            </div>
                                        </div>




                                        <div id="dynamicFields"></div>




                                        <!-- Query -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="query"><i class="fa fa-question-circle me-1 text-primary"></i>Query <span class="required-marker">*</span></label>
                                                <textarea name="query" id="query" class="form-control" rows="1" placeholder="Enter query" required></textarea>
                                                <span id="query_error" class="text-danger small"></span>
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
                                        <div class="col-md-12 mt-3 text-end">
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                                <i class="fa fa-arrow-left me-1"></i> Back
                                            </button>
                                            <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                                <i class="fa fa-paper-plane me-1"></i>Submit
                                            </button>
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

            // Remove country code if present
            value = value.replace(/^(\+91|91)/, '');

            let phoneRegex = /^[6-9][0-9]{9}$/;

            if (value === "") {
                $('#phone_number_error').html('Please Enter Phone Number');
            } else if (!phoneRegex.test(value)) {
                $('#phone_number_error').html('Invalid Indian Mobile Number');
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


        // Query
        $('#query').focusout(function() {
            if (this.value.trim() === "") {
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

        let property = $("#property").val();
        updateDynamicFields("", property);

    })

    $("#type").change(function() {

        let property = $("#property").val();
        updateDynamicFields("", property);

    })

    $("#property").change(function() {

        let property = $("#property").val();
        updateDynamicFields("", property);

    })


    $(document).on('change', '#assigned_to', function() {

        var role = $(this).find(':selected').data('role');
        var email = $(this).find(':selected').data('email');

        $('#assigned_person_user_role').val(role);
        $('#assigned_person_email').val(email);

    });

    window.CSRF = window.CSRF || {
        name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?= json_encode($this->security->get_csrf_hash()); ?>
    };
    window.CSRF.cookie = <?= json_encode($this->config->item('csrf_cookie_name')); ?>;

    function readCookie(name) {
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var index = 0; index < cookies.length; index++) {
            var parts = cookies[index].split('=');
            var cookieName = decodeURIComponent(parts.shift());

            if (cookieName === name) {
                return decodeURIComponent(parts.join('='));
            }
        }

        return '';
    }

    function currentCsrfHash() {
        return readCookie(window.CSRF.cookie) || window.CSRF.hash;
    }

    function csrfData(data) {
        data = data || {};
        data[window.CSRF.name] = currentCsrfHash();
        return data;
    }

    function csrfFormData(formData) {
        if (typeof formData.set === 'function') {
            formData.set(window.CSRF.name, currentCsrfHash());
        } else {
            formData.append(window.CSRF.name, currentCsrfHash());
        }

        return formData;
    }

    function refreshCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    $(document).ajaxComplete(function(event, xhr) {
        refreshCsrf(xhr.responseJSON);
    });

    var csrfAjaxQueue = $.Deferred().resolve().promise();

    function csrfAjax(options) {
        var method = (options.type || options.method || 'GET').toUpperCase();

        if (method !== 'POST') {
            return $.ajax(options);
        }

        var runRequest = function() {
            if (options.data instanceof FormData) {
                csrfFormData(options.data);
            } else {
                options.data = csrfData(options.data);
            }

            return $.ajax(options);
        };

        csrfAjaxQueue = csrfAjaxQueue.then(runRequest, runRequest);
        return csrfAjaxQueue;
    }

    function normalizeDepartmentName(name) {
        name = (name || '').toString().trim().toLowerCase();

        if (name === 'restaurants') return 'restaurant';
        if (name === 'banquets') return 'banquet';

        return name;
    }


    function updateDynamicFields(data = "") {

        const disposition = $("#disposition").val();
        let property = $("#property").val();
        let department = $('#type').find(':selected').data('name')?.toLowerCase();
        let department_id = $('#type').val();

        $('#leadDepartment').val(department);

        let existingLeadData = data;
        console.log(existingLeadData);

        const container = $("#dynamicFields");
        container.empty();


        // Reset previous fields
        container.html("");

        var today = new Date().toISOString().split('T')[0];




        if (

            disposition === "Contract Done" ||
            disposition === "Advance Received" ||
            disposition === "Lead Won" ||

            disposition === "Sold Out Dates" ||
            disposition === "General Information"
        ) {
            if (disposition === "Lead Won") {
                container.append(`
        <div class="col-md-3 mb-3">
    <label>Expected Revenue</label>
    <input type="number" name="amount" id="amount" class="form-control" step="0.01" >
</div>
        `);

            }
            $("#lead_status").val('Closed');
        }



        if (disposition === "Lead Lost") {

            $("#lead_status").val('Closed');

            container.append(`


            <div class="col-md-4 mb-3">
                <label class="form-label">Reason</label>
                <select name="reason" class="form-select filter-input" id="reason" required>
                    <option value="">Select Reason</option>
                    <option value="Budget Issue">Budget Issue</option>
                    <option value="Date Unavailable">Date Unavailable</option>
                    <option value="No Response">No Response</option>
                    <option value="Chose Competitor">Chose Competitor</option>
                    <option value="Not Interested">Not Interested</option>
                    <option value="Duplicate Lead">Duplicate Lead</option>
                </select>
            </div>


    `);

        }


        /* ---------- DENIED ---------- */

        if (disposition === "Denied") {

            container.append(`
        <div class="mb-3">
            <label>Check-in Date</label>
            <input type="date" name="checkin_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Check-out Date</label>
            <input type="date" name="checkout_date" class="form-control">
        </div>`);
        }



        if (disposition === "Quotation Sent") {


            $("#lead_status").val('In Progress');


            container.append(`


                <div class="col-md-3 mb-3">
                    <label class="form-label">Promotional Offer</label>
                    <select name="promotional_offers" class="form-select filter-input" id="promotional_offers">
                        
                    </select>
                </div>
                `


            )


            loadpromotional_offers(department_id, existingLeadData);


            /* ROOMS */

            if (department === "rooms") {

                container.append(`


                <div class="col-md-3 mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="roomtype" class="form-select filter-input" id="roomtype">
                        
                    </select>
                </div>

                 <div class="col-md-3 mb-3">
                    <label>Meal Plan <span class="text-danger">*</span></label>
                    <select name="meal_plan" id="meal_plan" class="form-select filter-input">
                        <option value="">Select Meal Plan</option>
                    </select>
                    <div class="text-danger error-label" id="meal_plan_error"></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Check-in Date</label>
                    <input type="date" id="checkin_date" name="checkin_date" class="form-control">
                    <span class="error-text text-danger"></span>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Check-out Date</label>
                    <input type="date" id="checkout_date" name="checkout_date"     class="form-control">
                    <span class="error-text text-danger"></span>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Number of Rooms</label>
                    <input type="number" name="number_of_rooms" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>No. of Pax</label>
                    <input type="number" name="pax" class="form-control" min="1">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Adults</label>
                    <input type="number" name="adults" class="form-control" min="1">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Kids</label>
                    <input type="number" name="kids" class="form-control" min="0">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Room Revenue</label>
                    <input type="number" name="revenue_room" id="revenue_room" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>F&B Revenue</label>
                    <input type="number" name="revenue_fnb" id="revenue_fnb" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Other Revenue</label>
                    <input type="number" name="revenue_other" id="revenue_other" class="form-control revenue-field" step="0.01">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly>
                </div>

                

           
            `);

                loadRoomTypes(property, existingLeadData);
                loadMealPlan(existingLeadData);


            } else if (department === "restaurants") {

                container.append(`


                <div class="col-md-4 mb-3">
                    <label>Booking Date</label>
                    <input type="date" name="booking_date" class="form-control" value="${today}">
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
                    <select name="table_id" id="table_id" class="form-select">
                        <option value="">Select Table</option>
                    </select>
                    <div class="text-danger error-label" id="table_id_error"></div>
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
            }


            /* BANQUETS */
            else if (department === "banquets") {

                container.append(`


            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            <div class="col-md-4 mb-3">
                <label>Banquet <span class="text-danger">*</span></label>
                <select name="banquet_id" id="banquet_id" class="form-select">
                    <option value="">Select Banquet</option>
                </select>
                <div class="text-danger error-label" id="banquet_id_error"></div>
            </div>

           

           <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
        

    `);

                loadbanquets(property, existingLeadData);

            } else if (department === "spa") {

                container.append(`


            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-4 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            

        
          <div class="col-md-6">
                    <label>Special Request</label>
                    <textarea name="special_request" class="form-control"></textarea>
                    </div>
            

        

           <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
        

    `);


            } else if (department === "water park") {

                container.append(`


            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-4 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
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


            } else if (department == "wedding") {



                container.append(`
            
                <div class="col-md-3 mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="roomtype" class="form-select filter-input" id="roomtype">
                        
                    </select>
                </div>

                 <div class="col-md-3 mb-3">
                    <label>Meal Plan <span class="text-danger">*</span></label>
                    <select name="meal_plan" id="meal_plan" class="form-select filter-input">
                        <option value="">Select Meal Plan</option>
                    </select>
                    <div class="text-danger error-label" id="meal_plan_error"></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Check-in Date</label>
                    <input type="date" id="checkin_date" name="checkin_date" class="form-control">
                    <span class="error-text text-danger"></span>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Check-out Date</label>
                    <input type="date" id="checkout_date" name="checkout_date"     class="form-control">
                    <span class="error-text text-danger"></span>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Number of Rooms</label>
                    <input type="number" name="number_of_rooms" class="form-control" min="1">
                </div>

                <div class="col-md-3 mb-3">
                    <label>No. of Pax</label>
                    <input type="number" name="pax" class="form-control" min="1">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Adults</label>
                    <input type="number" name="adults" class="form-control" min="1">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Kids</label>
                    <input type="number" name="kids" class="form-control" min="0">
                </div>

            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

          

            <div class="col-md-4 mb-3">
                <label>Banquet <span class="text-danger">*</span></label>
                <select name="banquet_id" id="banquet_id" class="form-select">
                    <option value="">Select Banquet</option>
                </select>
                <div class="text-danger error-label" id="banquet_id_error"></div>
            </div>

           

           <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
            
            
            
            `)

                loadbanquets(property, existingLeadData);
                loadRoomTypes(property, existingLeadData);
                loadMealPlan(existingLeadData);



            }

            container.append(`
                <div class="col-md-4 mb-3">
        <label class="form-label">Follow-up Date</label>
        <input type="date" name="followup_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">2nd Follow-up Date</label>
        <input type="date" name="second_followup_date" class="form-control">
    </div>
            `)




        }



        // Common fields HTML
        let followupFields = `
    <div class="col-md-4 mb-3">
        <label class="form-label">Booking Enquiry Date</label>
        <input type="date" name="booking_date" class="form-control" value="${today}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Follow-up Date</label>
        <input type="date" name="followup_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">2nd Follow-up Date</label>
        <input type="date" name="second_followup_date" class="form-control">
    </div>
`;

        if (disposition === "Negotiations") {

            $("#lead_status").val('In Progress');
            container.append(followupFields);

        }

        if (disposition === "Not Contacted" || disposition === "Unconfirmed Dates") {

            container.append(followupFields);

        }

        if (disposition === "Advance Received") {

            $("#lead_status").val('Closed');
            container.append(followupFields);

        }



        if (typeof existingLeadData !== "undefined") {

            for (let key in existingLeadData) {

                const field = container.find(`[name="${key}"]`);

                if (field.length) {
                    field.val(existingLeadData[key]);
                }

            }
        }

    }


    function loadRestaurants(hotel_id, existingLeadData) {

        $('#restaurant_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-restaurants') ?>",
            type: "POST",
            data: {
                hotel_id: hotel_id
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Restaurant</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.restaurant_name}</option>`;
                    });
                }

                $('#restaurant_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.restaurant_id) {
                    $('#restaurant_id').val(existingLeadData.restaurant_id);
                }
            }
        });
    }


    function loadbanquets(hotel_id, existingLeadData) {

        $('#banquet_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-banquets') ?>",
            type: "POST",
            data: {
                hotel_id: hotel_id
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Banquet</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.banquet_id}">${row.banquet_name}</option>`;
                    });
                }

                $('#banquet_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.banquet_id) {
                    $('#banquet_id').val(existingLeadData.banquet_id);
                }
            }
        });
    }




    function loadMealPlan(existingLeadData) {

        $('#meal_plan').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-meal-plans') ?>",
            type: "POST",
            data: {

            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Plan</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.plan}</option>`;
                    });
                }

                $('#meal_plan').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.id) {
                    $('#meal_plan').val(existingLeadData.id);
                }
            }
        });
    }


    function loadpromotional_offers(department, existingLeadData) {

        $('#promotional_offers').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-promotional-offers') ?>",
            type: "POST",
            data: {
                department_id: department
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Offer</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.offer_name}</option>`;
                    });
                }

                $('#promotional_offers').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.id) {
                    $('promotional_offers').val(existingLeadData.id);
                }
            }
        });
    }

    function loadRoomTypes(hotel_id, existingLeadData) {

        $('#roomtype').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-room-types') ?>",
            type: "POST",
            data: {
                hotel_id: hotel_id
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select roomtype</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.roomtype_id}">${row.roomtype_name}</option>`;
                    });
                }

                $('#roomtype').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.roomtype) {
                    $('#roomtype').val(existingLeadData.roomtype);
                }
            }
        });
    }

    function loadSlotTypes(existingLeadData) {

        $('#slot_type_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('lead/get-slot-types') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Slot</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.slot_name} (${row.start_time} - ${row.end_time})
                        </option>`;
                    });
                }

                $('#slot_type_id').html(html);
                if (typeof existingLeadData !== "undefined" && existingLeadData.slot_type_id) {
                    $('#slot_type_id').val(existingLeadData.slot_type_id);
                }
            }
        });
    }

    $(document).on('change', '#slot_type_id', function() {
        let slotTypeId = $(this).val();

        if (slotTypeId) {
            loadTimeSlots(slotTypeId);
        } else {
            $('#time_slot_id').html('<option value="">Select Time Slot</option>');
        }
    });


    function loadTimeSlots(slotTypeId, selectedTimeSlotId = null) {

        $('#time_slot_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-time-slots') ?>",
            type: "POST",
            data: {
                slot_type_id: slotTypeId
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Time Slot</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.start_time} - ${row.end_time}
                        </option>`;
                    });
                }

                $('#time_slot_id').html(html);


                if (selectedTimeSlotId !== null && selectedTimeSlotId !== '') {
                    $('#time_slot_id').val(selectedTimeSlotId);
                }


            }
        });
    }


    $(document).on('change', '#restaurant_id', function() {
        let restaurantId = $(this).val();

        if (restaurantId) {
            loadTableCategories(restaurantId);
        } else {
            $('#table_category_id').html('<option value="">Select Category</option>');
        }
    });

    function loadTableCategories(restaurantId, selectedCategoryId = null) {

        $('#table_category_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-table-categories') ?>",
            type: "POST",
            data: {
                restaurant_id: restaurantId
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Category</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            ${row.category_name}
                        </option>`;
                    });
                }

                $('#table_category_id').html(html);

                // ✅ For edit case
                if (selectedCategoryId !== null && selectedCategoryId !== "") {
                    $('#table_category_id').val(selectedCategoryId);
                }
            }
        });
    }


    $(document).on('change', '#table_category_id', function() {
        let categoryId = $(this).val();
        let restaurantId = $('#restaurant_id').val(); // 🔥 important

        if (categoryId && restaurantId) {
            loadTables(restaurantId, categoryId);
        } else {
            $('#table_id').html('<option value="">Select Table</option>');
        }
    });


    function loadTables(restaurantId, categoryId, selectedTableId = null) {

        $('#table_id').html('<option value="">Loading...</option>');

        csrfAjax({
            url: "<?= base_url('lead/get-tables') ?>",
            type: "POST",
            data: {
                restaurant_id: restaurantId,
                category_id: categoryId
            },
            dataType: "json",
            success: function(res) {

                let html = '<option value="">Select Table</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `
                        <option value="${row.id}">
                            Table ${row.table_name} (${row.capacity} Seats)
                        </option>`;
                    });
                }

                $('#table_id').html(html);

                // ✅ Edit case
                if (selectedTableId !== null && selectedTableId !== "") {
                    $('#table_id').val(selectedTableId);
                }
            }
        });
    }


    $(document).on("input", "input[name='adults'], input[name='kids']", function() {

        let adults = parseInt($("input[name='adults']").val()) || 0;
        let kids = parseInt($("input[name='kids']").val()) || 0;

        let pax = adults + kids;

        $("input[name='pax']").val(pax);

    });


    $(document).on('input', '.revenue-field', function() {

        var room = parseFloat($('#revenue_room').val()) || 0;
        var fnb = parseFloat($('#revenue_fnb').val()) || 0;
        var other = parseFloat($('#revenue_other').val()) || 0;

        var total = room + fnb + other;

        $('#amount').val(total.toFixed(2));

    });

    function leadField(field) {
        return $('[name="' + field + '"]').first();
    }

    function showLeadFieldError(field, message) {
        var input = leadField(field);
        var error = $('#' + field + '_error');

        input.addClass('is-invalid').attr('aria-invalid', 'true');
        if (!error.length) {
            error = $('<div>', {
                id: field + '_error',
                class: 'text-danger small lead-validation-error'
            });
            input.closest('.form-group, .mb-3, [class*="col-"]').first().append(error);
        }
        error.text(message);
    }

    function clearLeadValidation() {
        $('#leadForm .is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
        $('#leadForm [id$="_error"]').text('');
        $('#leadForm .lead-validation-error').text('');
    }

    function showLeadValidationErrors(errors) {
        var firstInvalid = null;

        $.each(errors || {}, function(field, message) {
            var input = leadField(field);
            if (!input.length) return;

            showLeadFieldError(field, message);

            if (!firstInvalid) firstInvalid = input;
        });

        if (firstInvalid) {
            firstInvalid.trigger('focus');
            $('html, body').animate({
                scrollTop: Math.max(firstInvalid.offset().top - 140, 0)
            }, 250);
        }
    }

    function validateLeadForm() {
        var errors = {};
        var phone = (leadField('phone_number').val() || '').replace(/\D/g, '').slice(-10);
        var disposition = leadField('disposition').val() || '';
        var department = normalizeDepartmentName($('#type option:selected').data('name'));
        var email = $.trim(leadField('email').val() || '');

        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            errors.phone_number = 'Enter a valid 10-digit Indian mobile number.';
        }
        if (disposition !== 'Not Contacted' && $.trim(leadField('username').val() || '') === '') {
            errors.username = 'Guest name is required.';
        }
        if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Enter a valid email address.';
        }

        $.each({
            property: 'The selected hotel is required.',
            type: 'Please select a department.',
            user_channel: 'Please select a lead source.',
            disposition: 'Please select a stage.',
            lead_status: 'Please select a lead status.',
            query: 'Query is required.'
        }, function(field, message) {
            if ($.trim(leadField(field).val() || '') === '') errors[field] = message;
        });

        if (disposition === 'Lead Lost' && $.trim(leadField('reason').val() || '') === '') {
            errors.reason = 'Please select a reason.';
        }

        if (disposition === 'Quotation Sent') {
            if ($.inArray(department, ['rooms', 'wedding']) !== -1 && !leadField('meal_plan').val()) {
                errors.meal_plan = 'Please select a meal plan.';
            }
            if ($.inArray(department, ['banquet', 'wedding']) !== -1 && !leadField('banquet_id').val()) {
                errors.banquet_id = 'Please select a banquet.';
            }
            if (department === 'restaurant') {
                $.each({
                    restaurant_id: 'Please select a restaurant.',
                    slot_type_id: 'Please select a slot type.',
                    time_slot_id: 'Please select a time slot.',
                    table_category_id: 'Please select a table category.',
                    table_id: 'Please select a table.'
                }, function(field, message) {
                    if (!leadField(field).val()) errors[field] = message;
                });
            }
        }

        return errors;
    }

    $(document).on('input change', '#leadForm input, #leadForm select, #leadForm textarea', function() {
        var field = ($(this).attr('name') || '').replace('[]', '');
        $(this).removeClass('is-invalid').removeAttr('aria-invalid');
        if (field) $('#' + field + '_error').text('');
    });

    $(document).on('change', '#disposition', function() {
        $('#guestNameRequiredMarker').toggle($(this).val() !== 'Not Contacted');
    });

    $('#leadForm').on('submit', function(e) {
        e.preventDefault();
        clearLeadValidation();

        var leadStatus = $('#lead_status');
        leadStatus.prop('disabled', false);
        var errors = validateLeadForm();

        if (Object.keys(errors).length) {
            showLeadValidationErrors(errors);
            leadStatus.prop('disabled', true);
            return;
        }

        var phone = (leadField('phone_number').val() || '').replace(/\D/g, '').slice(-10);
        var formData = new FormData(this);
        formData.set('user_name', $.trim(leadField('username').val() || ''));
        formData.set('phone_number', phone);
        formData.set('status', leadStatus.val() || 'Open');
        formData.set('leadDepartment', normalizeDepartmentName($('#type option:selected').data('name')));
        csrfFormData(formData);

        csrfAjax({
            url: '<?php echo base_url("insert-lead-agents"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                refreshCsrf(response);
                if (response.status) {
                    window.location.href = '<?php echo base_url("view-agents-leads"); ?>';
                    return;
                }
                showLeadValidationErrors(response.errors || {});
                alert(response.message || 'Failed to create lead.');
            },
            error: function(xhr) {
                var response = xhr.responseJSON || {};
                refreshCsrf(response);
                showLeadValidationErrors(response.errors || {});
                alert(response.message || 'Unable to save the lead. Please try again.');
            },
            complete: function() {
                leadStatus.prop('disabled', true);
                $('#submitBtn').prop('disabled', false).text('Submit');
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
                    csrfAjax({
                        url: '<?= base_url('LeadController/get_last_lead_by_cli') ?>',
                        type: 'POST',
                        data: {
                            cli: cli
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#username').val(response.data.user_name);
                                $('#phone_number').val(
                                    response.data.phone_number ?
                                    response.data.phone_number.toString().replace(/\D/g, '').slice(-10) :
                                    ''
                                );
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
                }, typingDelay);
            }
        });
    });
</script>

<!-- Bootstrap Modal -->
<div class="modal modal-lg" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Table -->
                <table class="table table-bordered" id="roomsRateTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Room Type</th>
                            <th>Total Rooms</th>
                            <th>Available Rooms</th>
                            <th>In House</th>
                            <th>Confirmed</th>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<div id="processingLoader"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">

    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">

        <div class="spinner-border text-light" style="width: 4rem; height: 4rem;" role="status"></div>

        <div style="color:white; font-size:20px; margin-top:15px; font-weight:600;">
            Processing...
        </div>

    </div>
</div>





<script>
    $(document).on("click", "#checkAvailabilityBtn", function() {

        let checkin = $("#checkin_date").val();
        let checkout = $("#checkout_date").val();
        let roomtype = $("#roomtype").val();


        if (!checkin || !checkout) {
            alert("Please select check-in and check-out dates");
            return;
        }

        csrfAjax({
            url: "<?= base_url('LeadController/getRoomRateAvailabilityAjax') ?>",
            type: "POST",
            data: {
                chain_code: "00051",
                hotel_code: "E0701",
                date_arrive: checkin,
                date_depart: checkout,
                adults: 1,
                youths: 0,
                kids: 0,
                number_of_rooms: "",
                price_from: 0,
                price_to: 0,
                room_type_code: ''
            },
            dataType: "json",
            beforeSend: function() {
                $("#processingLoader").show(); // Show loader
            },

            success: function(res) {
                if (res.status) {
                    renderRateTable(res.data.availability);

                    // Open modal
                    let myModal = new bootstrap.Modal(document.getElementById('availabilityModal'));
                    myModal.show();
                } else {
                    alert("No data found");
                }
            },
            complete: function() {
                $("#processingLoader").hide(); // Hide loader ALWAYS
            }
        });

    });

    function renderRateTable(data) {
        let html = "";

        data.forEach(function(item) {
            html += `
            <tr>
                <td>${item.date}</td>
                <td>${item.room_type_code}</td>
                <td>${item.rooms_total}</td>
                <td>${item.available_rooms}</td>
                <td>${item.rooms_inhouse}</td>
                <td>${item.rooms_confirmed}</td>
               
            </tr>
        `;
        });

        $("#roomsRateTable tbody").html(html);
    }

    // Delegated event for dynamic fields
    $(document).on("change", "#checkin_date", function() {
        let today = new Date().toISOString().split("T")[0];
        let checkin = $(this).val();

        if (checkin < today) {
            showError($(this), "Check-in date cannot be in the past");
        } else {
            hideError($(this));
        }
    });

    $(document).on("change", "#checkout_date", function() {
        let checkin = $("#checkin_date").val();
        let checkout = $(this).val();

        if (checkout < checkin) {
            showError($(this), "Check-out date must be same or future date");
        } else {
            hideError($(this));
        }
    });

    function showError(input, msg) {
        input.addClass("is-invalid");
        input.siblings(".error-text").text(msg);
    }

    function hideError(input) {
        input.removeClass("is-invalid");
        input.siblings(".error-text").text("");
    }



    // Date formatting function
    function formatDate(dateObj) {
        let d = dateObj.getDate().toString().padStart(2, '0');
        let m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        let y = dateObj.getFullYear();

        return `${d}-${m}-${y}`;
    }
</script>
