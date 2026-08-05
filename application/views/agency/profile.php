<div class="content-wrapper">
  <div class="container-full">
    <div class="custom-page-header">
      <div class="header-left">
        <div class="header-icon-box"><i class="fa fa-user"></i></div>
        <div class="header-content">
          <h2 class="header-title">My Profile</h2>
          <ol class="custom-breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li>Agency Admin</li>
            <li><i class="fa fa-angle-right"></i></li>
            <li class="active">My Profile</li>
          </ol>
        </div>
      </div>
      <div class="header-banner">
        <img src="<?= base_url('assets/new_img/profile_img.png'); ?>" alt="Profile">
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane show active" id="settings">
                  <h5 class="mb-4 text-uppercase">
                    <i class="fa fa-user"></i> Personal Info
                  </h5>

                  <form id="agency-profile-form" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="mb-3">
                          <label for="agency_name" class="form-label">Agency Name</label>
                          <input type="text" class="form-control" id="agency_name"
                            value="<?= htmlspecialchars($profile_data->agency_name ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="mb-3">
                          <label for="full_name" class="form-label">Full Name</label>
                          <input type="text" class="form-control" id="full_name" name="name"
                            value="<?= htmlspecialchars($profile_data->contact_person ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="name">
                          <span class="text-danger" id="full_name_error"></span>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="mb-3">
                          <label for="phone" class="form-label">Phone Number</label>
                          <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($profile_data->phone ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="tel">
                          <span class="text-danger" id="phone_error"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="email" class="form-label">Email Address</label>
                          <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($profile_data->email ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="email">
                          <span class="text-danger" id="email_error"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="password" class="form-label">
                            New Password <span class="text-muted">(Optional)</span>
                          </label>
                          <input type="password" class="form-control" id="password" name="password"
                            autocomplete="new-password" placeholder="Leave blank to keep current password">
                          <small class="text-muted">Enter a new password only if you want to change it.</small>
                          <span class="text-danger d-block" id="password_error"></span>
                        </div>
                      </div>
                    </div>

                    <div class="text-end">
                      <button type="submit" class="btn btn-primary mt-2" id="updateBtn">
                        <i class="fa fa-save m-1"></i> Save Changes
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
  window.CSRF = window.CSRF || {
    name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
    hash: <?= json_encode($this->security->get_csrf_hash()); ?>
  };

  $(function() {
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/;
    var isReloading = false;
    var $form = $('#agency-profile-form');
    var $fullName = $('#full_name');
    var $email = $('#email');
    var $phone = $('#phone');
    var $password = $('#password');
    var $submitButton = $('#updateBtn');

    toastr.options = {
      closeButton: true,
      newestOnTop: true,
      positionClass: 'toast-top-right',
      preventDuplicates: true,
      timeOut: 5000
    };

    var profileSuccess = <?= json_encode((string) $this->session->flashdata('profile_success')); ?>;
    if (profileSuccess) {
      toastr.success(profileSuccess);
    }

    function setFieldError(fieldName, message) {
      $('#' + fieldName + '_error').text(message || '');
    }

    function validateFullName() {
      var isValid = $.trim($fullName.val()) !== '';
      setFieldError('full_name', isValid ? '' : 'Please Enter Full Name');
      return isValid;
    }

    function validateEmail() {
      var email = $.trim($email.val());
      var message = '';

      if (email === '') {
        message = 'Please Enter Email';
      } else if (!emailPattern.test(email)) {
        message = 'Please Enter a Valid Email Address';
      }

      setFieldError('email', message);
      return message === '';
    }

    function validatePhone() {
      var isValid = $.trim($phone.val()) !== '';
      setFieldError('phone', isValid ? '' : 'Please Enter Phone Number');
      return isValid;
    }

    function validatePassword() {
      var password = $password.val();
      var isValid = password === '' || passwordPattern.test(password);

      setFieldError('password', isValid
        ? ''
        : 'Password must be at least 6 characters long, contain at least one number and one special character'
      );
      return isValid;
    }

    function setProcessing(isProcessing) {
      $submitButton.prop('disabled', isProcessing).html(isProcessing
        ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...'
        : '<i class="fa fa-save m-1"></i> Save Changes'
      );
    }

    $fullName.on('blur input', validateFullName);
    $email.on('blur input', validateEmail);
    $phone.on('blur input', validatePhone);
    $password.on('blur input', validatePassword);

    $form.on('submit', function(event) {
      event.preventDefault();

      var isFullNameValid = validateFullName();
      var isEmailValid = validateEmail();
      var isPhoneValid = validatePhone();
      var isPasswordValid = validatePassword();

      if (!isFullNameValid || !isEmailValid || !isPhoneValid || !isPasswordValid) {
        return;
      }

      var formData = new FormData();
      formData.append('name', $.trim($fullName.val()));
      formData.append('email', $.trim($email.val()));
      formData.append('phone', $.trim($phone.val()));
      formData.append('password', $password.val());
      formData.append(window.CSRF.name, window.CSRF.hash);

      $.ajax({
        url: '<?= base_url('update-agency-profile'); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
          setProcessing(true);
        },
        success: function(response) {
          if (response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
          }

          if (response.status) {
            isReloading = true;
            window.location.reload();
            return;
          }

          toastr.error(response.message || 'Unable to update your profile.');
        },
        error: function(xhr) {
          var response = xhr.responseJSON || {};

          if (response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
          }

          if (response.errors) {
            setFieldError('full_name', response.errors.name || '');
            setFieldError('email', response.errors.email || '');
            setFieldError('phone', response.errors.phone || '');
            setFieldError('password', response.errors.password || '');
            return;
          }

          toastr.error(response.message || (xhr.status === 403
            ? 'Your session expired. Please refresh the page and try again.'
            : 'Unable to update your profile.'
          ));
        },
        complete: function() {
          if (!isReloading) {
            setProcessing(false);
          }
        }
      });
    });
  });
</script>
