<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from tresto-admin-template.multipurposethemes.com/bs5/template/vertical/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 27 Dec 2024 15:28:51 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://sayajihotels.com/favicon.ico">
    <script src="https://use.fontawesome.com/76ecd250b9.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Register Your Lead </title>

    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/skin_color.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/custom.css">

</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url(<?php echo base_url('assets/') ?>/crescent_login_bg.webp)">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-12 col-md-12 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <div class="text-center">
                                    <div class="d-flex align-items-center logo-box justify-content-start">

                                    </div>
                                </div>
                                <h2 class="text-primary fw-600">Welcome to <?php echo $hotel_info->hotel_name ?> Register Your Lead
                                </h2>
                                <p class="text-center text-muted">Please fill out the form below and our team will get in touch with you shortly.</p>
                            </div>
                            <div class="p-40">
                                <div class="box-body">
                                    <div class="container mt-1">
                                        <!-- Include Bootstrap & FontAwesome CDN (if not already included) -->

                                        <form id="leadForm">
                                            <div class="row g-3">

                                                <!-- Username -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="username"><i class="fa fa-user me-1 text-primary"></i>Username</label>
                                                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter username">
                                                        <span id="username_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Phone Number -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phone_number"><i class="fa fa-phone me-1 text-success"></i>Phone Number</label>
                                                        <input type="number" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required>
                                                        <span id="phone_number_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email"><i class="fa fa-envelope me-1 text-warning"></i>Email (Optional)</label>
                                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                                        <span id="email_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Booking Date -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date"><i class="fa fa-calendar-alt me-1 text-info"></i>Booking Date</label>
                                                        <input type="date" name="date" id="date" class="form-control" required>
                                                        <span id="date_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Booking Time -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="time"><i class="fa fa-clock me-1 text-secondary"></i>Booking Time</label>
                                                        <input type="time" name="time" id="time" class="form-control" required>
                                                        <span id="time_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Lead Source -->






                                                <!-- Query -->
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="query"><i class="fa fa-question-circle me-1 text-primary"></i>Query</label>
                                                        <textarea name="query" id="query" class="form-control" rows="3" placeholder="Enter query" required></textarea>
                                                        <span id="query_error" class="text-danger small"></span>
                                                    </div>
                                                </div>

                                                <!-- Remark -->


                                                <!-- Submit -->
                                                <div class="col-md-12 mt-3 text-end">
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Thank You Modal -->
        <div class="modal modal-lg" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class=" modal-content shadow rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-success" id="thankYouModalLabel">
                            <i class="fa fa-check-circle me-2"></i>Thank You!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-3">Your lead has been registered successfully.</p>
                        <p>Our team will reach out to you shortly.</p>
                        <i class="fa fa-smile fa-2x text-warning mt-2"></i>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Vendor JS -->
        <script src="<?php echo base_url('assets/') ?>js/vendors.min.js"></script>
        <script src="<?php echo base_url('assets/') ?>js/pages/chat-popup.js"></script>
        <script src="<?php echo base_url('assets/') ?>assets/icons/feather-icons/feather.min.js"></script>

</body>

<!-- Mirrored from tresto-admin-template.multipurposethemes.com/bs5/template/vertical/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 27 Dec 2024 15:28:52 GMT -->

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#togglePassword').click(function() {
            var passwordInput = $('#password');
            var passwordToggle = $('#togglePassword i');

            // Toggle password visibility
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                $(this).removeClass('fa-eye');
                $(this).addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                $(this).removeClass('fa-eye-slash');
                $(this).addClass('fa-eye');
            }
        });
    });
</script>

<script>
    $(document).on('focusout', '#email', function() {
        var reg_email = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        if ($(this).val() != "") {
            if (!reg_email.test($(this).val())) {
                $(this).addClass("is-invalid");
                $(this).parent().removeClass("mb-3");
                $("#email-err").text("Enter valid email address.*");
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                $(this).parent().addClass("mb-3");
                $("#email-err").text("");
            }
        } else {
            $(this).removeClass("is-invalid");
            $(this).removeClass("is-valid");
            $(this).parent().addClass("mb-3");
            $("#email-err").text("");
        }
    })
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

    $('#leadForm').on('submit', function(e) {
        e.preventDefault();

        // Grab field values
        let username = $('input[name="username"]').val().trim();
        let phone = $('input[name="phone_number"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let bookingDate = $('input[name="date"]').val().trim();
        let bookingTime = $('input[name="time"]').val().trim();
        let userChannel = "Google Business";
        let property = '<?php echo $this->uri->segment('2') ?>';
        let department = '<?php echo $this->uri->segment('3') ?>';
        let leadStatus = 'Open'
        let disposition = "";
        let query = $('textarea[name="query"]').val().trim();
        let remark = "";

        // Simple validation condition
        if (
            username && phone && bookingDate && bookingTime && userChannel &&
            property && department && leadStatus && query
        ) {
            let formData = new FormData();

            // Append all fields
            formData.append('user_name', username);
            formData.append('phone_number', phone);
            formData.append('email', email);
            formData.append('date', bookingDate);
            formData.append('time', bookingTime);
            formData.append('user_channel', userChannel);
            formData.append('property', property);
            formData.append('type', department);
            formData.append('status', 'Open');
            formData.append('disposition', disposition);
            formData.append('query', query);
            formData.append('remark', remark);

            $.ajax({
                url: '<?php echo base_url("LeadController/insert_lead_google_business"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    console.log(response)
                    if (response.status) {
                        $("#thankYouModal").modal("show");

                    } else {
                        alert('Failed to create lead: ' + response.message);
                    }
                }
            });
        } else {
            alert("Please fill all required fields.");
        }
    });
</script>