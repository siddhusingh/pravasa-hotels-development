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
              <li><i class="fa fa-home"></i></li><li>Super Admin</li>
              <li><i class="fa fa-angle-right"></i></li><li class="active">My Profile</li>
            </ol>
          </div>
        </div>
        <div class="header-banner"><img src="<?= base_url('assets/new_img-add.png'); ?>" alt=""></div>
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
                          <input type="text" class="form-control" id="full_name" value="<?php echo $profile_data->full_name; ?>">
                          <span class="text-danger" id="full_name_error"></span>

                          <input type="hidden" name="" value="<?php echo $profile_data->id; ?>" id="record_id">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="useremail" class="form-label">Email Address</label>
                          <input type="email" class="form-control" id="email" value="<?php echo $profile_data->email; ?>">
                          <span class="text-danger" id="email_error"></span>


                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="useremail" class="form-label">Phone Number</label>
                          <input type="Number" class="form-control" id="phone" value="<?php echo $profile_data->phone; ?>">
                          <span class="text-danger" id="phone_error"></span>


                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="userpassword" class="form-label">Password</label>
                          <input type="password" class="form-control" id="password">
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
    // add Senior Managers code start from here





    // validation rules

    $('#full_name').focusout(function() {
      var value = this.value;
      if (value != "") {
        $('#full_name_error').html('');
      } else {
        $('#full_name_error').html('Please Enter Full Name');
        $('#full_name').val('');
      }

    });




    $('#email').focusout(function() {
      var value = this.value;
      if (value != "") {
        $('#email_error').html('');
      } else {
        $('#email_error').html('Please Enter Email ');
        $('#email').val('');
      }

    });

    $('#phone').focusout(function() {
      var value = this.value;
      if (value != "") {
        $('#phone_error').html('');
      } else {
        $('#phone_error').html('Please Enter Phone Number ');
        $('#phone').val('');
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
        $('#password_error').html('Please Enter Password');
        $('#password').val('');
      }
    });



    $(document).on('click', '#updateBtn', function(e) {
      e.preventDefault();

      var isValid = true; // Flag to check if form is valid

      var record_id = $("#record_id").val();
      var full_name = $('#full_name').val().trim();
      var email = $('#email').val().trim();
      var password = $('#password').val();
      var phone = $('#phone').val().trim();

      var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 characters, one number, one special character

      // Validate full_name
      if (full_name == '') {
        $('#full_name_error').html('Please Enter Full Name');
        isValid = false;
      } else {
        $('#full_name_error').html('');
      }

      // Validate email
      if (email == '') {
        $('#email_error').html('Please Enter Email');
        isValid = false;
      } else {
        $('#email_error').html('');
      }

      // Validate phone
      if (phone == '') {
        $('#phone_error').html('Please Enter Phone Number');
        isValid = false;
      } else {
        $('#phone_error').html('');
      }

      // Validate password
      if (password == '') {
        $('#password_error').html('Please Enter Password');
        isValid = false;
      } else if (!passwordRegex.test(password)) {
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
        formData.append('id', record_id);
        formData.append(window.CSRF.name, window.CSRF.hash);

        $.ajax({
          url: '<?php echo base_url("update-super-admin-profile") ?>',
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
              window.CSRF.hash = response.csrfHash;
            }
            window.location.reload();
          }
        });
      }
    });
  </script>
