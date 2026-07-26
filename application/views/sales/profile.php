<?php
$fullName = $profile_data->full_name ?? '';
$email = $profile_data->email ?? '';
$phone = $profile_data->phone ?? '';
?>

<div class="content-wrapper">
  <div class="container-full">
    <div class="custom-page-header">
      <div class="header-left">
        <div class="header-icon-box"><i class="fa fa-user"></i></div>
        <div class="header-content">
          <h2 class="header-title">My Profile</h2>
          <ol class="custom-breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li>Sales</li>
            <li><i class="fa fa-angle-right"></i></li>
            <li class="active">My Profile</li>
          </ol>
        </div>
      </div>
      <div class="header-banner">
        <img src="<?= base_url('assets/new_img/profile_img.png') ?>" alt="Profile">
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="card">
            <div class="card-body">
              <div id="profileMessage" class="alert d-none" role="alert"></div>

              <div class="tab-content">
                <div class="tab-pane show active" id="settings">
                  <h5 class="mb-4 text-uppercase">
                    <i class="fa fa-user-circle me-1"></i> Personal Info
                  </h5>

                  <form id="salesProfileForm" action="<?= base_url('sales/profile/update') ?>" method="post" novalidate>
                    <input
                      type="hidden"
                      name="<?= $this->security->get_csrf_token_name() ?>"
                      value="<?= $this->security->get_csrf_hash() ?>"
                    >

                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="full_name" class="form-label">Full Name</label>
                          <input
                            type="text"
                            class="form-control"
                            id="full_name"
                            name="full_name"
                            value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>"
                            minlength="3"
                            maxlength="100"
                            autocomplete="name"
                            required
                          >
                          <span class="text-danger field-error" id="full_name_error"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="email" class="form-label">Email Address</label>
                          <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                            maxlength="190"
                            autocomplete="email"
                            required
                          >
                          <span class="text-danger field-error" id="email_error"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="phone" class="form-label">Phone Number</label>
                          <input
                            type="tel"
                            class="form-control"
                            id="phone"
                            name="phone"
                            value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>"
                            inputmode="numeric"
                            pattern="[0-9]{10}"
                            maxlength="10"
                            autocomplete="tel"
                            required
                          >
                          <span class="text-danger field-error" id="phone_error"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="user_role" class="form-label">Role</label>
                          <input
                            type="text"
                            class="form-control"
                            id="user_role"
                            value="<?= htmlspecialchars($sales_role, ENT_QUOTES, 'UTF-8') ?>"
                            readonly
                          >
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="password" class="form-label">New Password</label>
                          <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            minlength="9"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Leave blank to keep current password"
                          >
                          <small class="text-muted">Leave blank to keep your existing password.</small>
                          <span class="text-danger field-error d-block" id="password_error"></span>
                        </div>
                      </div>
                    </div>

                    <div class="text-end">
                      <button type="submit" class="btn btn-primary mt-2" id="updateProfileBtn">
                        <i class="fa fa-save"></i> Save Changes
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

<script>
(function () {
  var form = document.getElementById('salesProfileForm');
  var button = document.getElementById('updateProfileBtn');
  var messageBox = document.getElementById('profileMessage');
  var csrfName = '<?= $this->security->get_csrf_token_name() ?>';

  function clearErrors() {
    form.querySelectorAll('.field-error').forEach(function (element) {
      element.textContent = '';
    });
    form.querySelectorAll('.is-invalid').forEach(function (element) {
      element.classList.remove('is-invalid');
    });
    messageBox.className = 'alert d-none';
    messageBox.textContent = '';
  }

  function showFieldError(field, message) {
    var input = document.getElementById(field);
    var error = document.getElementById(field + '_error');

    if (input) {
      input.classList.add('is-invalid');
    }
    if (error) {
      error.textContent = message;
    }
  }

  function showMessage(message, isSuccess) {
    if (typeof window.showSalesToast === 'function') {
      window.showSalesToast(isSuccess ? 'success' : 'error', message);
      return;
    }

    messageBox.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-danger');
    messageBox.textContent = message;
    messageBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function setProcessing(processing) {
    button.disabled = processing;
    button.innerHTML = processing
      ? '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...'
      : '<i class="fa fa-save"></i> Save Changes';
  }

  function validateForm() {
    var fullName = document.getElementById('full_name').value.trim();
    var emailInput = document.getElementById('email');
    var phone = document.getElementById('phone').value.trim();
    var password = document.getElementById('password').value;
    var valid = true;

    if (fullName.length < 3 || fullName.length > 100) {
      showFieldError('full_name', 'Full name must be between 3 and 100 characters.');
      valid = false;
    }
    if (!emailInput.value.trim() || !emailInput.validity.valid) {
      showFieldError('email', 'Please enter a valid email address.');
      valid = false;
    }
    if (!/^[0-9]{10}$/.test(phone)) {
      showFieldError('phone', 'Phone number must contain exactly 10 digits.');
      valid = false;
    }
    if (password !== '' && (password.length < 9 || password.length > 72)) {
      showFieldError('password', 'Password must be between 9 and 72 characters.');
      valid = false;
    }

    return valid;
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    clearErrors();

    if (!validateForm()) {
      showMessage('Please correct the highlighted fields.', false);
      return;
    }

    setProcessing(true);

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })
      .then(function (response) {
        return response.json().then(function (data) {
          return { ok: response.ok, data: data };
        });
      })
      .then(function (result) {
        var data = result.data;
        var csrfInput = form.querySelector('[name="' + csrfName + '"]');

        if (csrfInput && data.csrfHash) {
          csrfInput.value = data.csrfHash;
        }

        if (!result.ok || !data.status) {
          Object.keys(data.errors || {}).forEach(function (field) {
            showFieldError(field, data.errors[field]);
          });
          showMessage(data.message || 'Unable to update your profile.', false);
          return;
        }

        document.getElementById('password').value = '';
        document.querySelectorAll('[data-sales-profile-name]').forEach(function (element) {
          element.textContent = data.profile.full_name;
        });
        document.querySelectorAll('[data-sales-profile-email]').forEach(function (element) {
          element.textContent = data.profile.email;
          element.setAttribute('href', 'mailto:' + data.profile.email);
        });
        document.querySelectorAll('[data-sales-profile-phone]').forEach(function (element) {
          element.textContent = data.profile.phone;
          element.setAttribute('href', 'tel:' + data.profile.phone);
        });
        showMessage(data.message, true);
      })
      .catch(function () {
        showMessage('Unable to update your profile. Please try again.', false);
      })
      .finally(function () {
        setProcessing(false);
      });
  });
})();
</script>
