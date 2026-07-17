<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">

        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Add Sales Visit</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>Sales Visit</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Add Visit</li>
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

                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Sales Visit Details</h4>
                        </div>

                        <div class="box-body">
                            <form id="salesVisitForm">
                                <div class="row g-3">

                                    <!-- Hotel -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="property"><i class="fa fa-hotel me-1 text-danger"></i>Hotel (Property)</label>
                                            <select name="property" id="property" class="form-control" required>
                                                <option selected disabled value="">Select Hotel</option>
                                                <?php foreach ($hotel_admin as $each) { ?>
                                                    <option value="<?php echo encrypt_id($each->hotel_id) ?>"><?php echo $each->hotel_name ?></option>
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
                                                    <option value="<?php echo encrypt_id($each->department_id) ?>"
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

                                    <!-- Report Date -->
                                    <div class="col-md-4">
                                        <label>Report Date *</label>
                                        <input type="date" id="report_date" name="report_date" class="form-control" required>
                                        <span class="text-danger small" id="report_date_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Company *</label>
                                        <select name="company_id" id="company_id" class="form-control" required>
                                            <option value="">Select Company</option>
                                            <?php foreach ($companies as $c) { ?>
                                                <option value="<?= encrypt_id($c->company_id) ?>">
                                                    <?= $c->company_name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger small"></span>
                                    </div>

                                    <!-- Person Met -->
                                 <div class="col-md-4">
    <label>Person Met *</label>

    <select
        name="person_met[]"
        id="person_met"
        class="form-control multi-checkbox"
        multiple
        required>
    </select>

    <span class="text-danger small"></span>
</div>

<script>
$(document).ready(function () {
    initMultiCheckbox('#person_met');
});
</script>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disposition"><i class="fa fa-list me-1 text-dark"></i>Stage</label>
                                            <select class="form-control" name="disposition" id="disposition">
                                                <option value="" selected disabled>Select Stage</option>
                                                <option value="Not Contacted">Not Contacted</option>
                                                <option value="Contacted">Contacted</option>
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
                                            <label for="lead_status"><i class="fa fa-info-circle me-1 text-secondary"></i>Lead Status</label>
                                            <select name="lead_status" id="lead_status" class="form-control" disabled>
                                                <option value="Open" selected>Open</option>

                                                <option value="In Progress">In Progress</option>
                                                <option value="Closed">Closed</option>
                                            </select>
                                            <span id="lead_status_error" class="text-danger small"></span>
                                        </div>
                                    </div>


                                    <input type="hidden" id="leadDepartment" name="leadDepartment">


                                    <div id="dynamicFields"></div>




                                    <!-- Agenda -->
                                    <div class="col-sm-4">
                                        <label>Agenda</label>
                                        <textarea name="agenda" class="form-control" rows="2" id="agenda"></textarea>
                                    </div>

                                    <!-- Discussion Summary -->
                                    <div class="col-sm-4">
                                        <label>Discussion Summary *</label>
                                        <textarea name="discussion_summary" class="form-control" rows="3" required id="discussion_summary"></textarea>
                                    </div>

                                    <!-- Conclusion -->
                                    <div class="col-sm-4">
                                        <label>Conclusion</label>
                                        <textarea name="conclusion" class="form-control" rows="2" id="conclusion"></textarea>
                                    </div>

                                    <hr class="mt-3">

                                    <h5 class="mt-3">Conveyance Details</h5>

                                    <!-- Area Covered -->
                                    <div class="col-sm-4">
                                        <label>Area Covered</label>
                                        <textarea name="area_covered" class="form-control" rows="2" id="area_covered"></textarea>
                                    </div>

                                    <!-- Travel Mode -->
                                    <div class="col-sm-4">
                                        <label>Travel Mode </label>

                                        <select name="travel_mode" class="form-control" id="travel_mode">
                                            <option value="">Select</option>

                                            <?php if (!empty($travel_modes)) : ?>
                                                <?php foreach ($travel_modes as $mode) : ?>
                                                    <option value="<?php echo encrypt_id($mode->id); ?>">
                                                        <?php echo $mode->travel_mode_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- KMS -->
                                    <div class="col-sm-4">
                                        <label>Kms Run</label>
                                        <input type="number" step="0.01" name="kms_run" class="form-control" id="kms_run">
                                    </div>

                                    <!-- Rate -->
                                    <div class="col-sm-4">
                                        <label>Rate / Km</label>
                                        <input type="number" step="0.01" name="rate_per_km" class="form-control" id="rate_per_km">
                                    </div>

                                    <!-- Parking -->
                                    <div class="col-sm-4">
                                        <label>Parking / Toll</label>
                                        <input type="number" step="0.01" name="parking_charges" class="form-control" id="parking_charges">
                                    </div>

                                    <!-- Lunch -->
                                    <div class="col-sm-4">
                                        <label>Lunch</label>
                                        <input type="number" step="0.01" name="lunch" class="form-control" id="lunch">
                                    </div>

                                    <!-- Entertainment -->
                                    <div class="col-sm-4">
                                        <label>Entertainment</label>
                                        <input type="number" step="0.01" name="entertainment" class="form-control" id="entertainment">
                                    </div>

                                    <!-- Total -->
                                    <div class="col-sm-4">
                                        <label>Total Amount</label>
                                        <input type="number" step="0.01" name="total_amount" class="form-control" id="total_amount" readonly>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-sm-12">
                                        <label>Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-sm-12 text-end mt-3">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="window.history.back();">
                                            Back
                                        </button>

                                        <button type="submit" id="submitBtn"
                                            class="btn btn-primary px-4">
                                            Submit
                                        </button>
                                    </div>

                                </div>
                            </form>

                            <div id="response" class="mt-3"></div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    toastr.options = {
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
</script>

<script>
    function appendCsrf(formData) {
        if (window.CSRF) {
            formData.append(window.CSRF.name, window.CSRF.hash);
        }
    }

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
        updateDynamicFieldsForEdit("", property);

    })

    $("#type").change(function() {

        let property = $("#property").val();
        updateDynamicFieldsForEdit("", property);

    })

    $("#property").change(function() {

        let property = $("#property").val();
        updateDynamicFieldsForEdit("", property);

    })


    function updateDynamicFieldsForEdit(data = "") {
        const disposition = $("#disposition").val();
        let property = $("#property").val();

        let department = $('#type').find(':selected').data('name')?.toLowerCase();

        $('#leadDepartment').val(department);

        let existingLeadData = data;

        console.log(existingLeadData)


        const container = $("#dynamicFields");

        // Reset previous fields
        container.html("");


        if ((disposition === "Information/Enquiry") || (disposition === "Trash") || (disposition === "Denied") || (disposition === "Shopping - No Follow up")) {

            $("#lead_status").val('Closed');

        }



        // Reservation - Closed
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

        // Reservation - Closed
        if (disposition === "Reservation") {

            $("#lead_status").val('Closed');

            if (department === "rooms") {
                container.append(`
   <div class="row">
   <!-- Property -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Room Type</label>
        <select name="roomtype" class="form-select filter-input" id="roomtype">
            <?php foreach ($roomtype as $roomtype) { ?>
                <option value="<?= $roomtype->roomtype_code; ?>">
                    <?= $roomtype->roomtype_name; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Check-in -->
   <div class="col-md-3 mb-3">
    <label>Check-in Date</label>
    <input type="date" id="checkin_date" name="checkin_date" class="form-control">
    <span class="error-text text-danger"></span>
</div>

<div class="col-md-3 mb-3">
    <label>Check-out Date</label>
    <input type="date" id="checkout_date" name="checkout_date" class="form-control">
    <span class="error-text text-danger"></span>
</div>

<div class="col-md-2 mb-3">
    <button type="button" class="btn btn-primary btn-sm" style="margin-top:21px" id="checkAvailabilityBtn">
    Check Availability
</button>
</div>


<div class="col-md-3 mb-3">
    <label>Check-in Time</label>
    <input type="time" id="checkin_time" name="checkin_time" class="form-control">
    <span class="error-text text-danger"></span>
</div>



<div class="col-md-3 mb-3">
    <label>Check-out Time</label>
    <input type="time" id="checkout_time" name="checkout_time" class="form-control">
    <span class="error-text text-danger"></span>
</div>




    

    <!-- Number of Rooms -->
    <div class="col-md-3 mb-3">
        <label>Number of Rooms</label>
        <input type="number" name="number_of_rooms" class="form-control" min="1">
    </div>

    <!-- No. of Pax -->
    <div class="col-md-3 mb-3">
        <label>No. of Pax</label>
        <input type="number" name="pax" class="form-control" min="1">
    </div>

    <!-- Adults -->
    <div class="col-md-4 mb-3">
        <label>Adults</label>
        <input type="number" name="adults" class="form-control" min="1">
    </div>

    <!-- Kids -->
    <div class="col-md-4 mb-3">
        <label>Kids</label>
        <input type="number" name="kids" class="form-control" min="0">
    </div>

    

<table id="rateTypeTable" class="table table-bordered mt-4" style="display:none;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Rate Type</th>
            <th>Room Price</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


</div>



   
   
   `);
            } else if (department === "restaurants") {

                container.append(`
                 <div class="row">
    <div class="col-md-4 mb-3">
        <label>Booking Date</label>
        <input type="date" name="booking_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>Arrival Time</label>
        <input type="time" name="arrival_time" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>No. of Pax</label>
        <input type="number" name="pax" class="form-control" min="1">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Restaurant <span class="text-danger">*</span></label>
        <select name="restaurant_id" id="restaurant_id" class="form-select">
            <option value="">Select Restaurant</option>
        </select>
        <div class="text-danger error-label" id="restaurant_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Slot Type <span class="text-danger">*</span></label>
        <select name="slot_type_id" id="slot_type_id" class="form-select">
            <option value="">Select Slot</option>
        </select>
        <div class="text-danger error-label" id="slot_type_id_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label>Expected Revenue</label>
        <input type="number" name="amount" class="form-control" step="0.01">
    </div>
</div>


`);

                // Load restaurants via AJAX
                loadRestaurants(property);

                // Load slot types via AJAX
                loadSlotTypes();
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


    function loadRestaurants(hotel_id) {

        $('#restaurant_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('superAdmin/Restaurants/getByHotel') ?>",
            type: "POST",
            data: csrfData({
                hotel_id: hotel_id
            }),
            dataType: "json",
            success: function(res) {
                refreshCsrf(res);

                let html = '<option value="">Select Restaurant</option>';

                if (res.status === 'success') {
                    $.each(res.data, function(i, row) {
                        html += `<option value="${row.id}">${row.restaurant_name}</option>`;
                    });
                }

                $('#restaurant_id').html(html);
            }
        });
    }

    function loadSlotTypes() {

        $('#slot_type_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?= base_url('superAdmin/SlotType/getAll') ?>",
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
            }
        });
    }



    $('#salesVisitForm').on('submit', function(e) {

        e.preventDefault();

        $("#lead_status").prop('disabled', false);

        /* ================== BASIC FIELDS ================== */

        let userChannel = 'Sales Visit';
        let property = $('select[name="property"]').val();
        let department = $('select[name="type"]').val();
        let lead_status = $('select[name="lead_status"]').val();
        let lead_type = $('select[name="lead_type"]').val();

        let query = $('#discussion_summary').val();
        let remarks = $('textarea[name="remarks"]').val();

        let leadDepartment = $('#leadDepartment').val();
        let disposition = $('#disposition').val() || '';

        /* ================== SALES VISIT FIELDS ================== */
        let report_date = $('#report_date').val();
        let company_id = $('#company_id').val();
        let person_met = $('#person_met').val();
        let agenda = $('#agenda').val();
        let discussion_summary = $('#discussion_summary').val();
        let conclusion = $('#conclusion').val();
        let area_covered = $('#area_covered').val();
        let travel_mode = $('#travel_mode').val();
        let kms_run = $('#kms_run').val();
        let rate_per_km = $('#rate_per_km').val();
        let parking_charges = $('#parking_charges').val();
        let lunch = $('#lunch').val();
        let entertainment = $('#entertainment').val();
        let total_amount = $('#total_amount').val();

        /* ================== BASIC VALIDATION ================== */
        if (

            userChannel &&
            property &&
            department &&
            query
        ) {

            let formData = new FormData();

            /* ========== DYNAMIC FIELDS ========== */
            $('#dynamicFields')
                .find('input, select, textarea')
                .each(function() {

                    let name = $(this).attr('name');

                    if (!name) return;

                    if ($(this).attr('type') === 'file') {
                        if (this.files.length > 0) {
                            formData.append(name, this.files[0]);
                        }
                    } else {
                        formData.append(name, $(this).val());
                    }
                });

            /* ========== APPEND MAIN FIELDS ========== */
            formData.append('user_channel', userChannel);
            formData.append('property', property);
            formData.append('type', department);
            formData.append('status', lead_status);
            formData.append('query', query);
            formData.append('remarks', remarks);
            formData.append('lead_type', lead_type);
            formData.append('leadDepartment', leadDepartment);
            formData.append('disposition', disposition);

            /* ========== SALES VISIT DATA ========== */
            formData.append('report_date', report_date);
            formData.append('company_id', company_id);
            $.each(person_met, function(i, value) {
    formData.append('person_met[]', value);
});
            formData.append('agenda', agenda);
            formData.append('discussion_summary', discussion_summary);
            formData.append('conclusion', conclusion);
            formData.append('area_covered', area_covered);
            formData.append('travel_mode', travel_mode);
            formData.append('kms_run', kms_run);
            formData.append('rate_per_km', rate_per_km);
            formData.append('parking_charges', parking_charges);
            formData.append('lunch', lunch);
            formData.append('entertainment', entertainment);
            formData.append('total_amount', total_amount);
            appendCsrf(formData);

            /* ================== AJAX ================== */
            $.ajax({
                url: '<?php echo base_url("superAdmin/SalesVisits/insert"); ?>',
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

                    if (response.duplicate) {
                        alert('Failed to create lead: ' + response.message);
                        return;
                    }

                    if (response.status) {
                        window.location.href = '<?php echo base_url("sales-visits-history"); ?>';
                    } else {
                        alert('Failed to create lead: ' + response.message);
                    }
                }
            });

        } else {
            alert('Please fill all required fields.');
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
                        data: csrfData({
                            cli: cli
                        }),
                        dataType: 'json',
                        success: function(response) {
                            refreshCsrf(response);
                            if (response.status === 'success') {
                                $('#username').val(response.data.user_name);
                                $('#phone_number').val(response.data.phone_number); // optional
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

<script>
    $('#company_id').change(function () {

    let company_id = $(this).val();

    if (company_id != '') {

        $.ajax({

            url: "<?= base_url('superAdmin/SalesVisits/get_company_contacts') ?>",
            type: "POST",
            data: csrfData({
                company_id: company_id
            }),
            dataType: "json",

            success: function (res) {

                refreshCsrf(res);

                let options = '';

                if (res.status == 'success') {

                    $.each(res.data, function(i,row){

                        options += `
                        <option value="${row.contact_id}">
                            ${row.first_name} ${row.last_name} (${row.mobile_number})
                        </option>`;
                    });

                }

                $('#person_met').multiselect('destroy');

                $('#person_met').html(options);

                initMultiCheckbox('#person_met');

            }

        });

    } else {

        $('#person_met').multiselect('destroy');

        $('#person_met').html('');

        initMultiCheckbox('#person_met');

    }

});



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


    $(document).on("change", "#checkin_date, #checkout_date", function() {

        let checkin = $("#checkin_date").val();
        let checkout = $("#checkout_date").val();

        if (checkin === "" || checkout === "") return;
        if (checkout < checkin) return;

        generateRateRows(checkin, checkout);
    });

    function generateRateRows(startDate, endDate) {

        let tbody = $("#rateTypeTable tbody");
        tbody.empty();

        let start = new Date(startDate);
        let end = new Date(endDate);

        // IMPORTANT: Subtract 1 day to get hotel nights
        end.setDate(end.getDate() - 1);

        while (start <= end) {

            let dt = formatDate(start); // dd-mm-YYYY format

            let row = `
        <tr>
            <td>
                ${dt}
                <input type="hidden" name="rate_date[]" value="${dt}">
            </td>
            <td>
                <select name="rate_type[]" class="form-select rate-type-dd">
                <?php foreach ($ratetype as $ratetype) { ?>
                    <option value="<?= $ratetype->ratetype_code; ?>">
                        <?= $ratetype->ratetype_code; ?>
                    </option>
                <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" name="room_price[]" class="form-control room-price" min="0" value="0">
            </td>
        </tr>
        `;

            tbody.append(row);

            start.setDate(start.getDate() + 1);
        }

        $("#rateTypeTable").show();
    }



    // Date formatting function
    function formatDate(dateObj) {
        let d = dateObj.getDate().toString().padStart(2, '0');
        let m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        let y = dateObj.getFullYear();

        return `${d}-${m}-${y}`;
    }
</script>

<script>
    $(document).ready(function() {

        function calculateTotalAmount() {

            let kmsRun = parseFloat($('#kms_run').val()) || 0;
            let ratePerKm = parseFloat($('#rate_per_km').val()) || 0;
            let parkingCharges = parseFloat($('#parking_charges').val()) || 0;
            let lunch = parseFloat($('#lunch').val()) || 0;
            let entertainment = parseFloat($('#entertainment').val()) || 0;

            // Travel calculation
            let travelAmount = kmsRun * ratePerKm;

            // Final total
            let totalAmount = travelAmount + parkingCharges + lunch + entertainment;

            // Set total with 2 decimal points
            $('#total_amount').val(totalAmount.toFixed(2));
        }

        // Trigger calculation on change / keyup
        $('#kms_run, #rate_per_km, #parking_charges, #lunch, #entertainment')
            .on('keyup change', function() {
                calculateTotalAmount();
            });

    });
</script>
