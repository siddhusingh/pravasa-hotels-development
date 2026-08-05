<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="https://sayajihotels.com/favicon.ico">

    <title>LMS Agency - Log in</title>

    <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/vendors_css.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/style.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/skin_color.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/custom.css">
</head>

<body class="hold-transition theme-primary bg-img"
    style="background: url('<?php echo base_url('assets/crescent_login_bg.webp'); ?>') center / cover no-repeat">
    <div class="container h-p100 login_page">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <div class="text-center">
                                    <div class="d-flex align-items-center logo-box justify-content-start">
                                        <div class="logo-lg" style="margin: auto">
                                            <span class="light-logo">
                                                <img src="<?php echo base_url('assets/jardin-hotels-logo.avif'); ?>"
                                                    style="height: 120px" alt="logo">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="text-primary fw-600">Agency Login</h2>
                                <p class="mb-0 text-fade">Sign in to continue to LMS Agency Admin.</p>
                            </div>

                            <div class="p-40">
                                <form id="login-form" novalidate>
                                    <div class="form-group">
                                        <div class="input-group mb-3" id="email-group">
                                            <span class="input-group-text bg-transparent">
                                                <i class="text-fade ti-user"></i>
                                            </span>
                                            <input type="email" class="form-control ps-15 bg-transparent"
                                                placeholder="Email address" id="email" name="email"
                                                autocomplete="username">
                                        </div>
                                        <span class="text-danger" id="email-err"></span>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3" id="password-group">
                                            <span class="input-group-text bg-transparent">
                                                <i class="text-fade ti-lock"></i>
                                            </span>
                                            <input type="password" class="form-control ps-15 bg-transparent"
                                                placeholder="Password" id="password" name="password"
                                                autocomplete="current-password">
                                        </div>
                                        <span class="text-danger" id="password-err"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" id="sign-in-btn"
                                                class="btn btn-primary w-p100 mt-10">SIGN IN</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/'); ?>js/vendors.min.js"></script>
    <script src="<?php echo base_url('assets/'); ?>js/pages/chat-popup.js"></script>
    <script src="<?php echo base_url('assets/'); ?>assets/icons/feather-icons/feather.min.js"></script>

    <script>
        window.CSRF = {
            name: "<?= $this->security->get_csrf_token_name(); ?>",
            hash: "<?= $this->security->get_csrf_hash(); ?>"
        };

        $(function() {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var isRedirecting = false;
            var $form = $('#login-form');
            var $email = $('#email');
            var $password = $('#password');
            var $submitButton = $('#sign-in-btn');

            function setFieldState($field, errorMessage) {
                var fieldId = $field.attr('id');

                $field.toggleClass('is-invalid', Boolean(errorMessage));
                $field.toggleClass('is-valid', !errorMessage && $.trim($field.val()) !== '');
                $('#' + fieldId + '-group').toggleClass('mb-3', !errorMessage);
                $('#' + fieldId + '-err').text(errorMessage || '');
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
                $submitButton.prop('disabled', isProcessing).html(isProcessing
                    ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...'
                    : 'SIGN IN'
                );
            }

            $email.on('blur', validateEmail).on('input', function() {
                if ($email.hasClass('is-invalid')) {
                    validateEmail();
                }
            });

            $password.on('blur', validatePassword).on('input', function() {
                if ($password.hasClass('is-invalid')) {
                    validatePassword();
                }
            });

            $form.on('submit', function(event) {
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
                    url: '<?= base_url('AgencyLogin/login_check'); ?>',
                    type: 'POST',
                    data: requestData,
                    dataType: 'json',
                    beforeSend: function() {
                        setProcessing(true);
                    },
                    success: function(result) {
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
                    error: function(xhr) {
                        var message = xhr.status === 403
                            ? 'Your session expired. Please refresh the page and try again.'
                            : 'Unable to sign in right now. Please try again.';

                        setFieldState($email, message);
                    },
                    complete: function() {
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
