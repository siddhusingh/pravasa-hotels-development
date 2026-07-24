<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from tresto-admin-template.multipurposethemes.com/bs5/template/vertical/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 27 Dec 2024 15:28:51 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LMS Agency - Log in </title>

    <link href="https://assets.simplotel.com/simplotel/image/upload/x_138,y_0,w_312,h_312,c_crop/w_32,h_32,c_scale/crescent-spa-resorts/CRESCENT_LOGO_OPEN_rksvbu" rel="shortcut icon">

    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/skin_color.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/custom.css">
    

</head>

<body class="hold-transition theme-primary bg-img"
    style="
        background-image: url('<?php echo base_url('assets/login_bg.avif'); ?>');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        background-attachment: fixed;
      ">

    <div class="container h-p100 login_page">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <div class="text-center">
                                    <div class="d-flex align-items-center logo-box justify-content-start">
                                        <!-- Logo -->


                                        <div class="logo-lg" style="margin:auto">
                                            <span class="light-logo"><img style="height: 120px;" src="<?php echo base_url('assets/jardin-hotels-logo.avif') ?>" style="height: 120px!important;" alt="logo"></span>

                                        </div>

                                    </div>
                                </div>
                                <h2 class="text-primary fw-600"> Agent Login</h2>
                                <p class="mb-0 text-fade">Sign in to continue to LMS Agent.</p>
                            </div>
                            <div class="p-40">

                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
                                        <input type="text" class="form-control ps-15 bg-transparent" placeholder="Username" id="email">

                                    </div>
                                    <span class="text-danger" id="email-err"></span>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text  bg-transparent"><i class="text-fade ti-lock"></i></span>
                                        <input type="password" class="form-control ps-15 bg-transparent" placeholder="Password" id="password">

                                    </div>
                                    <span class="text-danger" id="password-err"></span>
                                </div>
                                <div class="row">

                                    <!-- /.col -->
                                    <div class="col-6">
                                        <div class="fog-pwd text-end">

                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-12 text-center">
                                        <button type="button" id="sign-in-btn" class="btn btn-primary w-p100 mt-10">SIGN IN</button>
                                    </div>
                                    <!-- /.col -->
                                </div>


                            </div>
                        </div>
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
    $(document).on('click', '#sign-in-btn', function() {
        var reg_email = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        var email = $("#email").val();
        var password = $("#password").val();
        var role_as = 'super_admin';
        if (email != "" && password != "" && reg_email.test(email)) {
            $("#email").removeClass("is-invalid");
            $("#email").addClass("is-valid");
            $("#email").parent().addClass("mb-3");
            $("#email-err").text("");
            $("#password").removeClass("is-invalid");
            $("#password").addClass("is-valid");
            $("#password").parent().addClass("mb-3");
            $("#password-err").text("");
            proceed_to_login();
        } else if (email == "") {
            $("#email").addClass("is-invalid");
            $("#email").parent().removeClass("mb-3");
            $("#email-err").text("Enter email address.*");
        } else if (role_as == "select") {
            $("#role_as").addClass("is-invalid");
            $("#role_as").parent().removeClass("mb-3");
            $("#role_as-err").text("Select One.*");
        } else if (!reg_email.test(email)) {
            $("#email").addClass("is-invalid");
            $("#email").parent().removeClass("mb-3");
            $("#email-err").text("Enter valid email address.*");
        } else if (password == "") {
            $("#password").addClass("is-invalid");
            $("#password").parent().removeClass("mb-3");
            $("#password-err").text("Enter your password.*");
        }

    })
</script>
<script>
    $(document).on('click', '#sign-in-btn', function() {
        var reg_email = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        var email = $("#email").val();
        var password = $("#password").val();

        if (email != "" && password != "" && reg_email.test(email)) {
            $("#email").removeClass("is-invalid").addClass("is-valid");
            $("#password").removeClass("is-invalid").addClass("is-valid");
            proceed_to_login();
        } else {
            if (email == "") {
                $("#email").addClass("is-invalid");
                $("#email-err").text("Enter email address.*");
            } else if (!reg_email.test(email)) {
                $("#email").addClass("is-invalid");
                $("#email-err").text("Enter valid email address.*");
            }
            if (password == "") {
                $("#password").addClass("is-invalid");
                $("#password-err").text("Enter your password.*");
            }
        }
    });

    function proceed_to_login() {
        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: '<?= base_url('agent/login/login_check') ?>',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#sign-in-btn").html('<div class="spinner-border text-light spinner-border-sm"></div> Signing...');
            },
            success: function(result) {
                var response = result.response_message;

                if (response == "account404") {
                    $("#email").addClass("is-invalid");
                    $("#email-err").text("Sorry, we couldn't find an account.");
                } else if (response == "disabled") {
                    $("#password").addClass("is-invalid");
                    $("#password-err").text("Your account is disabled. Please contact admin.");
                } else if (response == "WRONGPASS") {
                    $("#password").addClass("is-invalid");
                    $("#password-err").text("Wrong Password.*");
                } else if (response == "noHotels") {
                    alert("No hotels mapped to your account. Please contact Admin!");
                } else if (response == "logginSCS") {
                    // Directly logged in
                    window.location.href = "<?= base_url() ?>" + result.redirect_url;
                } else if (response == "selectHotel") {
                    // Multiple hotels, show modal
                    showHotelSelectionModal(result.hotels);
                }
            },
            complete: function() {
                $("#sign-in-btn").html('SIGN IN');
            }
        });
    }

    function showHotelSelectionModal(hotels) {
        // First, remove any existing modal if already present
        $('#hotelSelectModal').remove();

        var hotelOptions = '';

        $.each(hotels, function(index, hotel) {
            hotelOptions += `<option value="${hotel.hotel_id}">${hotel.hotel_name}</option>`;
        });

        var modalHtml = `
    <div class="modal modal-lg" id="hotelSelectModal" tabindex="-1" aria-labelledby="hotelSelectLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="hotelSelectLabel">Select Hotel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <select class="form-select" id="selectedHotel">
              <option value="">-- Select Hotel --</option>
              ${hotelOptions}
            </select>
            <span class="text-danger" id="hotel-err"></span>
          </div>
          <div class="modal-footer">
            <button type="button" id="confirmHotelBtn" class="btn btn-primary">Continue</button>
          </div>
        </div>
      </div>
    </div>
    `;

        $("body").append(modalHtml);
        $("#hotelSelectModal").modal('show');

        // Attach event ONCE after modal is shown
        $(document).off('click', '#confirmHotelBtn').on('click', '#confirmHotelBtn', function() {
            var selectedHotelId = $("#selectedHotel").val();
            if (selectedHotelId === "") {
                $("#hotel-err").text("Please select a hotel.");
            } else {

                setSelectedHotel(selectedHotelId);
            }
        });
    }


    function setSelectedHotel(hotelId) {
        $.ajax({
            url: '<?= base_url('agent/login/set_selected_hotel') ?>',
            type: 'POST',
            data: {
                hotel_id: hotelId
            },
            dataType: 'JSON',
            success: function(result) {
                if (result.status == 'success') {
                    window.location.href = "<?= base_url() ?>" + result.redirect_url;
                } else {
                    alert("Something went wrong. Try again!");
                }
            }
        });
    }
</script>


<script type="text/javascript"></script>