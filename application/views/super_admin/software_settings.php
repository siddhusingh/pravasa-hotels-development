<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-cogs"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Software Settings</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li><li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li><li class="active">Software Settings</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?= base_url('assets/new_img-add.png'); ?>" alt=""></div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row settings_card">
                <div class="col-12">
                    <div class="  software_settings_page">
                    
                        <div class="box-body">
                            <form id="softwareSettingsForm" enctype="multipart/form-data">

                                <h5 class="section_title">Basic Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Site Title</label>
                                        <input type="text" name="site_title" class="form-control" value="<?= $settings->site_title ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-6">
                                        <label>Tagline</label>
                                        <input type="text" name="site_tagline" class="form-control" value="<?= $settings->site_tagline ?? '' ?>">

                                    </div>
                                </div>


                                <h5 class="section_title">Branding</h5>
                                <div class="row">

                                    <!-- LOGO -->
                                    <div class="col-md-4">
                                        <label>Logo</label>
                                        <input type="file" name="logo" class="form-control">

                                        <?php if (!empty($settings->logo)) { ?>
                                            <div class="mt-1">
                                                <a href="<?= base_url($settings->logo) ?>" target="_blank" class="text-primary">
                                                    <i class="fa fa-eye"></i> View current logo
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <small class="text-danger error"></small>
                                    </div>

                                    <!-- FAVICON -->
                                    <div class="col-md-4">
                                        <label>Favicon</label>
                                        <input type="file" name="favicon" class="form-control">

                                        <?php if (!empty($settings->favicon)) { ?>
                                            <div class="mt-1">
                                                <a href="<?= base_url($settings->favicon) ?>" target="_blank" class="text-primary">
                                                    <i class="fa fa-eye"></i> View current favicon
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <small class="text-danger error"></small>
                                    </div>

                                    <!-- LOGIN BG -->
                                    <div class="col-md-4">
                                        <label>Login Background</label>
                                        <input type="file" name="login_bg_image" class="form-control">

                                        <?php if (!empty($settings->login_bg_image)) { ?>
                                            <div class="mt-1">
                                                <a href="<?= base_url($settings->login_bg_image) ?>" target="_blank" class="text-primary">
                                                    <i class="fa fa-eye"></i> View current background
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <small class="text-danger error"></small>
                                    </div>

                                </div>

                                <h5 class="section_title">SMTP Settings</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>SMTP Host</label>
                                        <input type="text" name="smtp_host" class="form-control" value="<?= $settings->smtp_host ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>Port</label>
                                        <input type="text" name="smtp_port" class="form-control" value="<?= $settings->smtp_port ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>User</label>
                                        <input type="text" name="smtp_user" class="form-control" value="<?= $settings->smtp_user ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>Password</label>
                                        <input type="password" name="smtp_pass" class="form-control">
                                        <small class="text-danger error"></small>

                                    </div>

                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label>Encryption</label>
                                        <select name="smtp_encryption" class="form-control">
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                            <option value="none">None</option>
                                        </select>
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-4">
                                        <label>From Email</label>
                                        <input type="email" name="smtp_from_email" class="form-control"
                                            value="<?= $settings->smtp_from_email ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-4">
                                        <label>From Name</label>
                                        <input type="text" name="smtp_from_name" class="form-control"
                                            value="<?= $settings->smtp_from_name ?? '' ?>">
                                        <small class="text-danger error"></small>

                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Save Settings
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<!-- Modal -->

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



    <?php if ($this->session->flashdata('regional_managers_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('regional_managers_success_msg'); ?>')

    <?php $this->session->set_flashdata('regional_managers_success_msg', '');
    } ?>
</script>
<script type="text/javascript">
    // add Sales Users code start from here

    $('#softwareSettingsForm').on('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        let form = $(this);

        // Clear old errors
        form.find('.error').text('');

        // TEXT / SELECT / EMAIL required fields
        form.find('.required').each(function() {
            if ($.trim($(this).val()) === '') {
                $(this).next('.error').text('This field is required');
                isValid = false;
            }
        });

        // FILE required fields
        form.find('.required-file').each(function() {
            if ($(this).get(0).files.length === 0) {
                $(this).next('.error').text('File is required');
                isValid = false;
            }
        });

        // Email validation
        let emailField = $('input[name="smtp_from_email"]');
        let emailVal = emailField.val().trim();
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailVal === '') {
            emailField.next('.error').text('Email is required');
            isValid = false;
        } else if (!emailRegex.test(emailVal)) {
            emailField.next('.error').text('Enter a valid email');
            isValid = false;
        }

        // Stop if validation fails
        if (!isValid) {
            return false;
        }

        // Submit via AJAX
        let formData = new FormData(this);
        formData.append(window.CSRF.name, window.CSRF.hash);

        $.ajax({
            url: "<?= base_url('superAdmin/Software_settings/update') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function(res) {
                if (res.csrfHash) {
                    window.CSRF.hash = res.csrfHash;
                }
                if (res.status) {
                    alert('Settings updated successfully');
                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).text('Save Settings');
            }
        });
    });
</script>
