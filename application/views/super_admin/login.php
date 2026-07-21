<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from tresto-admin-template.multipurposethemes.com/bs5/template/vertical/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 27 Dec 2024 15:28:51 GMT -->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">



  <?php
  $CI = &get_instance();
  $CI->load->database();

  $branding = $CI->db
    ->select('logo, login_bg_image, site_title,favicon')
    ->from('software_settings')
    ->limit(1)
    ->get()
    ->row();

  $loginBg = !empty($branding->login_bg_image)
    ? base_url($branding->login_bg_image)
    : base_url('assets/login_bg.avif');

  $favicon = !empty($branding->favicon)
    ? base_url($branding->favicon)
    : base_url('assets/default-favicon.ico');
  ?>



  <title><?php echo $branding->site_title ?> Super Admin - Log in </title>
  <link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">

  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/vendors_css.css">

  <!-- Style-->
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/skin_color.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/custom.css">

  <style>
    .bg-white {
      --bs-bg-opacity: 1;
      background-color: rgb(255 255 255 / 2%) !important;
    }



    /* Force black bold inputs for login */
    .rounded10 .form-control {
      background-color: transparent !important;
      border: 2px solid #000 !important;
      border-color: #000 !important;
      color: #000 !important;
      font-weight: 600 !important;
    }

    /* Placeholder text */
    .rounded10 .form-control::placeholder {
      color: #000 !important;
      font-weight: 600 !important;
      opacity: 0.85 !important;
    }

    /* Input group icon wrapper */
    .rounded10 .input-group-text {
      background-color: transparent !important;
      border: 2px solid #000 !important;
      border-color: #000 !important;
    }

    /* Icon color */
    .rounded10 .input-group-text i {
      color: #000 !important;
      font-weight: 600 !important;
    }

    /* Focus state */
    .rounded10 .form-control:focus {
      border: 2px solid #000 !important;
      border-color: #000 !important;
      box-shadow: none !important;
      background-color: transparent !important;
      color: #000 !important;
    }

    .bg-white {
      --bs-bg-opacity: 1;
      background-color: rgb(223 213 213 / 69%) !important;
    }
  </style>

</head>

<body class="hold-transition theme-primary bg-img"
  style="
        background-image: url('<?= $loginBg ?>');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        background-attachment: fixed;
      ">



  <div class="container h-p100">
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
                      <span class="light-logo"><img src="<?= !empty($branding->logo)
                                                            ? base_url($branding->logo)
                                                            : base_url('assets/images/default-logo.png') ?>" style="height: 120px!important;" alt="logo"></span>

                    </div>

                  </div>
                </div>
                <h2 class="text-primary fw-600">Super Admin Login</h2>
                <!-- <p class="mb-0 ">Sign in to continue to LMS Admin.</p> -->
              </div>
              <div class="p-40 login-card">

                <?php if ($this->session->flashdata('success')): ?>
                  <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                  <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
                <?php endif; ?>

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
                  <div class="col-6">
                    <!-- <div class="checkbox">
											<input type="checkbox" id="basic_checkbox_1" >
											<label for="basic_checkbox_1">Remember Me</label>
										  </div> -->
                  </div>
                  <!-- /.col -->
                  <div class="col-6">
                    <div class="fog-pwd text-end">
                      <a href="<?php echo base_url('forget-password-super-admin') ?>" class="text-primary fw-500 hover-primary"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
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
  window.CSRF = {
    name: "<?php echo $this->security->get_csrf_token_name(); ?>",
    hash: "<?php echo $this->security->get_csrf_hash(); ?>"
  };

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
  function proceed_to_login() {
    var email = $("#email").val();
    var passsword = $("#password").val();
    var role_as = $("#role_as").val();



    //console.log(role_as);return;

    $.ajax({
      url: '<?= base_url('superAdmin/login/login_check') ?>',
      type: 'post',
      data: {
        email: email,
        password: passsword,
        role_as: role_as,
        [window.CSRF.name]: window.CSRF.hash
      },
      dataType: 'JSON',
      beforeSend: function() {
        $("#acc-deact-err").text("");
        $("#password").removeClass("is-invalid");
        $("#password").parent().addClass("mb-3");
        $("#password-err").text("");
        $("#email").removeClass("is-invalid");
        $("#email").parent().addClass("mb-3");
        $("#email-err").text("");
        $("#sign-btn-container").html('<div class="spinner-border text-warning text-right"></div>');
      },
      success: function(result) {
        window.CSRF.hash = result.csrfHash;
        //console.log(response);  
        response = result.response_message;

        if (response == "account404") {
          $("#email").addClass("is-invalid");
          $("#email").parent().removeClass("mb-3");
          $("#email-err").text("Sorry,we couldn't find an account.");
        }
        if (response == "ACC0") {
          $("#acc-deact-err").text("Account not active contact admin.!");
        }
        if (response == "WRONGPASS") {
          $("#password").addClass("is-invalid");
          $("#password").parent().removeClass("mb-3");
          $("#password-err").text("Wrong Password.*");
        }

        if (response == "disabled") {
          $("#password").addClass("is-invalid");
          $("#password").parent().removeClass("mb-3");
          $("#password-err").text("Your Account is disabled please check with admin*");
        }
        if (response == "logginSCS") {
          redirect_url = result.redirect_url;
          window.location.href = "<?= base_url() ?>" + redirect_url;
        }
      },
      complete: function(data) {
        $("#sign-btn-container").html('<button type="button" class="btn btn-warning btn-block" id="sign-in-btn">Sign In</button>');
      }
    });
  }
</script>

<script type="text/javascript"></script>
