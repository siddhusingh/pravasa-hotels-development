<!DOCTYPE html>
<html lang="en">

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

  <title><?php echo $branding->site_title ?> Hotel Admin - Log in</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">

  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/vendors_css.css">

  <!-- Style-->
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/skin_color.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/custom.css">

</head>




<body class="hold-transition theme-primary bg-img"
  style="
        background-image: url('<?= $loginBg ?>');
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
                      <span class="light-logo"><img src="<?= !empty($branding->logo)
                                                            ? base_url($branding->logo)
                                                            : base_url('assets/images/default-logo.png') ?>" style="height: 120px!important;" alt="logo"></span>

                    </div>

                  </div>
                </div>
                <h2 class="text-primary fw-600">Hotel Admin Login</h2>
                <p class="mb-0 text-fade">Sign in to continue to LMS Admin.</p>
              </div>
              <div class="p-40">

                <form id="login-form" novalidate>
                    <div class="form-group">
                        <div class="input-group mb-3" id="email-group">
                            <span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
                            <input
                                type="email"
                                class="form-control ps-15 bg-transparent"
                                placeholder="Email address"
                                id="email"
                                name="email"
                                autocomplete="username"
                            >
                        </div>
                        <span class="text-danger" id="email-err"></span>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3" id="password-group">
                            <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                            <input
                                type="password"
                                class="form-control ps-15 bg-transparent"
                                placeholder="Password"
                                id="password"
                                name="password"
                                autocomplete="current-password"
                            >
                        </div>
                        <span class="text-danger" id="password-err"></span>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="sign-in-btn" class="btn btn-primary w-p100 mt-10">
                                SIGN IN
                            </button>
                        </div>
                    </div>
                </form>


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

<script>
    window.CSRF = {
        name: "<?= $this->security->get_csrf_token_name(); ?>",
        hash: "<?= $this->security->get_csrf_hash(); ?>"
    };

    $(function () {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var isRedirecting = false;
        var $form = $('#login-form');
        var $email = $('#email');
        var $password = $('#password');
        var $submitButton = $('#sign-in-btn');

        function setFieldState($field, errorMessage) {
            var errorSelector = '#' + $field.attr('id') + '-err';
            var groupSelector = '#' + $field.attr('id') + '-group';

            $field.toggleClass('is-invalid', Boolean(errorMessage));
            $field.toggleClass('is-valid', !errorMessage && $.trim($field.val()) !== '');
            $(groupSelector).toggleClass('mb-3', !errorMessage);
            $(errorSelector).text(errorMessage || '');
        }

        function validateEmail() {
            var email = $.trim($email.val());
            var errorMessage = '';

            if (email === '') {
                errorMessage = 'Please enter your email address.';
            } else if (!emailPattern.test(email)) {
                errorMessage = 'Please enter a valid email address.';
            }

            setFieldState($email, errorMessage);
            return errorMessage === '';
        }

        function validatePassword() {
            var errorMessage = $password.val() === '' ? 'Please enter your password.' : '';

            setFieldState($password, errorMessage);
            return errorMessage === '';
        }

        function setProcessing(isProcessing) {
            $submitButton.prop('disabled', isProcessing);
            $submitButton.html(isProcessing
                ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...'
                : 'SIGN IN'
            );
        }

        $email.on('blur', validateEmail);
        $password.on('blur', validatePassword);

        $email.on('input', function () {
            if ($(this).hasClass('is-invalid')) {
                validateEmail();
            }
        });

        $password.on('input', function () {
            if ($(this).hasClass('is-invalid')) {
                validatePassword();
            }
        });

        $form.on('submit', function (event) {
            event.preventDefault();

            var isEmailValid = validateEmail();
            var isPasswordValid = validatePassword();

            if (!isEmailValid || !isPasswordValid) {
                $form.find('.is-invalid').first().trigger('focus');
                return;
            }

            var requestData = {
                email: $.trim($email.val()),
                password: $password.val()
            };
            requestData[window.CSRF.name] = window.CSRF.hash;

            $.ajax({
                url: '<?= base_url('hotelAdmin/login/login_check'); ?>',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                beforeSend: function () {
                    setProcessing(true);
                },
                success: function (result) {
                    if (result.csrfHash) {
                        window.CSRF.hash = result.csrfHash;
                    }

                    switch (result.response_message) {
                        case 'account404':
                            setFieldState($email, "Sorry, we couldn't find an account with this email.");
                            break;
                        case 'WRONGPASS':
                            setFieldState($password, 'The password you entered is incorrect.');
                            break;
                        case 'disabled':
                            setFieldState($email, 'Your account is disabled. Please contact the administrator.');
                            break;
                        case 'logginSCS':
                            isRedirecting = true;
                            $submitButton.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Redirecting...');
                            window.location.href = '<?= base_url(); ?>' + result.redirect_url;
                            break;
                        default:
                            setFieldState($email, 'Unable to sign in. Please try again.');
                    }
                },
                error: function (xhr) {
                    var message = xhr.status === 403
                        ? 'Your session expired. Please refresh the page and try again.'
                        : 'Unable to sign in right now. Please try again.';

                    setFieldState($email, message);
                },
                complete: function () {
                    if (!isRedirecting) {
                        setProcessing(false);
                    }
                }
            });
        });
    });
</script>
</body>

</html>
