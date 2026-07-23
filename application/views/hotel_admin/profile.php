  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="custom-page-header">
        <div class="header-left">
          <div class="header-icon-box"><i class="fa fa-user"></i></div>
          <div class="header-content">
            <h2 class="header-title">My Profile</h2>
            <ol class="custom-breadcrumb">
              <li><i class="fa fa-home"></i></li>
              <li>Hotel Admin</li>
              <li><i class="fa fa-angle-right"></i></li>
              <li class="active">My Profile</li>
            </ol>
          </div>
        </div>
        <div class="header-banner">
          <img src="<?= base_url('assets/new_img/profile_img.png'); ?>" alt="">
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <div class="row">


          <div class="col-xl-12 col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">


                  <div class="tab-pane show active" id="settings">

                    <h5 class="mb-4 text-uppercase"><i class="fa fa-user-circle me-1"></i> Personal Info</h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="full_name" class="form-label">Full Name</label>
                          <input type="text" class="form-control" id="full_name" value="<?= htmlspecialchars($profile_data->name, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="name">
                          <input type="hidden" name="" value="<?php echo $profile_data->id; ?>" id="record_id">
                          <span class="text-danger" id="full_name_error"></span>


                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="useremail" class="form-label">Email Address</label>
                          <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($profile_data->email, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="email">
                          <span class="text-danger" id="email_error"></span>


                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="useremail" class="form-label">Phone Number</label>
                          <input type="tel" class="form-control" id="phone" value="<?= htmlspecialchars($profile_data->phone, ENT_QUOTES, 'UTF-8'); ?>" inputmode="numeric" maxlength="10" autocomplete="tel">
                          <span class="text-danger" id="phone_error"></span>


                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="userpassword" class="form-label">Password</label>
                          <input type="password" class="form-control" id="password" placeholder="Leave blank to keep current password" autocomplete="new-password">
                          <span class="text-danger" id="password_error"></span>


                        </div>
                      </div> <!-- end col -->

                    </div> <!-- end row -->


                    <div class="text-end">
                      <button type="button" class="btn btn-primary mt-2 " id="updateBtn"><i class="fa fa-save"></i> Save Changes</button>
                    </div>

                  </div>
                  <!-- end settings content-->

                </div> <!-- end tab-content -->
              </div> <!-- end card body -->
            </div> <!-- end card -->
          </div> <!-- end col -->
        </div>
        <!-- end row-->

      </section>
      <!-- /.content -->
    </div>
  </div>
  <!-- /.content-wrapper -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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



    <?php if ($this->session->flashdata('profile_success') != "") { ?>



      toastr.success('<?php echo $this->session->flashdata('profile_success'); ?>')

    <?php $this->session->set_flashdata('profile_success', '');
    } ?>
  </script>
  <script type="text/javascript">
    var profileCsrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var profileCsrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    /* FULL NAME */
    $('#full_name').focusout(function() {
      let value = $(this).val().trim();

      if (value === '') {
        $('#full_name_error').html('Full name is required');
        $(this).val('');
      } else if (value.length < 3) {
        $('#full_name_error').html('Full name must be at least 3 characters');
      } else {
        $('#full_name_error').html('');
      }
    });


    /* EMAIL */
    $('#email').focusout(function() {
      let value = $(this).val().trim();
      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (value === '') {
        $('#email_error').html('Email address is required');
        $(this).val('');
      } else if (!emailRegex.test(value)) {
        $('#email_error').html('Please enter a valid email address');
      } else {
        $('#email_error').html('');
      }
    });


    /* PHONE */
    $('#phone').focusout(function() {
      let value = $(this).val().trim();
      let phoneRegex = /^[6-9][0-9]{9}$/;

      if (value === '') {
        $('#phone_error').html('Phone number is required');
        $(this).val('');
      } else if (!phoneRegex.test(value)) {
        $('#phone_error').html('Please enter a valid 10-digit Indian mobile number');
      } else {
        $('#phone_error').html('');
      }
    });

    $('#password').focusout(function() {
      var value = this.value;
      var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 characters, one number, and one special character

      if (value != "") {
        if (!passwordRegex.test(value)) {
          $('#password_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
          $('#password').val('');
        } else {
          $('#password_error').html('');
        }
      } else {
        $('#password_error').html('');
      }
    });

    $(document).on('click', '#updateBtn', function(e) {
      e.preventDefault();

      var isValid = true; // Flag to check if form is valid

      var full_name = $('#full_name').val().trim();
      var email = $('#email').val().trim();
      var password = $('#password').val();
      var phone = $('#phone').val().trim();

      var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 characters, one number, one special character

      // Validate full_name
      if (full_name.length < 3) {
        $('#full_name_error').html('Full name must be at least 3 characters');
        isValid = false;
      } else {
        $('#full_name_error').html('');
      }

      // Validate email
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        $('#email_error').html('Please enter a valid email address');
        isValid = false;
      } else {
        $('#email_error').html('');
      }

      // Validate phone
      var phoneRegex = /^[6-9][0-9]{9}$/;
      if (!phoneRegex.test(phone)) {
        $('#phone_error').html('Please enter a valid 10-digit Indian mobile number');
        isValid = false;
      } else {
        $('#phone_error').html('');
      }

      // Password is optional; validate it only when the user enters one.
      if (password !== '' && !passwordRegex.test(password)) {
        $('#password_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
        isValid = false;
      } else {
        $('#password_error').html('');
      }


      // If all validations passed, then submit via Ajax
      if (isValid) {
        var formData = new FormData();

        formData.append('full_name', full_name);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('phone', phone);
        formData.append(profileCsrfName, profileCsrfHash);

        $.ajax({
          url: '<?php echo base_url("update-hotel-admin-profile") ?>',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'JSON',
          beforeSend: function() {
            $('#updateBtn').html('Updating...').attr('disabled', true);
          },
          success: function(response) {
            if (response.csrfHash) {
              profileCsrfHash = response.csrfHash;
            }
            if (response.status) {
              toastr.success(response.message || 'Your profile has been updated successfully.');
              window.setTimeout(function() {
                window.location.reload();
              }, 500);
              return;
            }

            toastr.error(response.message || 'Unable to update the profile.');
          },
          error: function(xhr) {
            var response = xhr.responseJSON || {};
            var errors = response.errors || {};

            if (response.csrfHash) {
              profileCsrfHash = response.csrfHash;
            }

            $.each(errors, function(field, message) {
              $('#' + field + '_error').html(message);
            });

            toastr.error(response.message || 'Unable to update the profile.');
          },
          complete: function() {
            $('#updateBtn').html('<i class="fa fa-save"></i> Save Changes').attr('disabled', false);
          }
        });
      }
    });
  </script>
