<link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css"
    onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css';">

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
            <div class="header-banner"><img src="<?= base_url('assets/new_img/Software_img.png'); ?>" alt=""></div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row settings_card">
                <div class="col-12">
                    <div class="  software_settings_page">
                    
                        <div class="box-body">
                            <form id="basicSettingsForm">
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

                                    <div class="col-12 mt-3 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Save Basic Information
                                        </button>
                                    </div>
                                </div>
                            </form>


                            <h5 class="section_title">Branding</h5>
                            <div class="row">

                                    <!-- LOGO -->
                                    <div class="col-md-4">
                                        <label>Logo</label>
                                        <input type="file" name="logo" class="branding-upload dropify"
                                            data-default-file="<?= !empty($settings->logo) ? base_url($settings->logo) : '' ?>"
                                            data-allowed-file-extensions="jpg jpeg png ico webp" data-max-file-size="6M">

                                        <small class="text-danger error"></small>
                                    </div>

                                    <!-- FAVICON -->
                                    <div class="col-md-4">
                                        <label>Favicon</label>
                                        <input type="file" name="favicon" class="branding-upload dropify"
                                            data-default-file="<?= !empty($settings->favicon) ? base_url($settings->favicon) : '' ?>"
                                            data-allowed-file-extensions="jpg jpeg png ico webp" data-max-file-size="6M">

                                        <small class="text-danger error"></small>
                                    </div>

                                    <!-- LOGIN BG -->
                                    <div class="col-md-4">
                                        <label>Login Background</label>
                                        <input type="file" name="login_bg_image" class="branding-upload dropify"
                                            data-default-file="<?= !empty($settings->login_bg_image) ? base_url($settings->login_bg_image) : '' ?>"
                                            data-allowed-file-extensions="jpg jpeg png ico webp" data-max-file-size="6M">

                                        <small class="text-danger error"></small>
                                    </div>

                            </div>

                            <form id="smtpSettingsForm">
                                <h5 class="section_title">SMTP Settings</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>SMTP Host</label>
                                        <input type="text" name="smtp_host" class="form-control" value="<?= html_escape($settings->smtp_host ?? '') ?>"
                                            placeholder="Enter SMTP host">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>Port</label>
                                        <input type="number" name="smtp_port" class="form-control" value="<?= html_escape($settings->smtp_port ?? '') ?>"
                                            min="1" max="65535" placeholder="Enter SMTP port">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>User</label>
                                        <input type="text" name="smtp_user" class="form-control" value="<?= html_escape($settings->smtp_user ?? '') ?>"
                                            placeholder="Enter SMTP user">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-3">
                                        <label>Password</label>
                                        <input type="password" name="smtp_pass" class="form-control"
                                            placeholder="<?= $smtp_has_password ? 'Leave blank to keep existing password' : 'Enter SMTP password' ?>"
                                            autocomplete="new-password">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label>Encryption</label>
                                        <select name="smtp_encryption" class="form-control">
                                            <option value="tls" <?= ($settings->smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                            <option value="ssl" <?= ($settings->smtp_encryption ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                            <option value="none" <?= ($settings->smtp_encryption ?? '') === 'none' ? 'selected' : '' ?>>None</option>
                                        </select>
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label>From Email</label>
                                        <input type="email" name="smtp_from_email" class="form-control"
                                            value="<?= html_escape($settings->smtp_from_email ?? '') ?>" placeholder="Enter from email">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label>From Name</label>
                                        <input type="text" name="smtp_from_name" class="form-control"
                                            value="<?= html_escape($settings->smtp_from_name ?? '') ?>" placeholder="Enter from name">
                                        <small class="text-danger error"></small>

                                    </div>

                                    <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testSmtpModal">
                                            <i class="fa fa-paper-plane mr-1"></i> Send Test Email
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Save SMTP Settings
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="airtelSettingsForm">
                                <h5 class="section_title">Airtel IQ Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>API URL</label>
                                        <input type="url" name="api_url" class="form-control"
                                            value="<?= html_escape($airtel_config->api_url ?? '') ?>"
                                            placeholder="Enter Airtel API URL">
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-md-6">
                                        <label>API Key</label>
                                        <input type="password" name="api_key" class="form-control"
                                            placeholder="<?= $airtel_has_api_key ? 'Leave blank to keep existing API key' : 'Enter Airtel API key' ?>"
                                            autocomplete="new-password">
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label>Caller ID</label>
                                        <input type="text" name="caller_id" class="form-control"
                                            value="<?= html_escape($airtel_config->caller_id ?? '') ?>"
                                            placeholder="Enter caller ID">
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-md-8 mt-3">
                                        <label>Customer ID</label>
                                        <input type="text" name="customer_id" class="form-control"
                                            value="<?= html_escape($airtel_config->customer_id ?? '') ?>"
                                            placeholder="Enter customer ID">
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label>Call Flow ID</label>
                                        <textarea name="call_flow_id" class="form-control" rows="2"
                                            placeholder="Enter tagged call flow ID"><?= html_escape($airtel_config->call_flow_id ?? '') ?></textarea>
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label>Notify URL</label>
                                        <input type="url" name="notify_url" class="form-control"
                                            value="<?= html_escape($airtel_config->notify_url ?? '') ?>"
                                            placeholder="Enter CDR notify URL">
                                        <small class="text-danger error"></small>
                                    </div>

                                    <div class="col-12 mt-3 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Save Airtel Configuration
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="section_title mb-0">PMS Integrations</h5>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mycloudConfigModal">
                                        <i class="fa fa-plus mr-1"></i>
                                        <?= $mycloud_config ? 'Edit PMS' : 'Add PMS' ?>
                                    </button>
                                </div>

                                <?php if ($mycloud_config) { ?>
                                    <div class="card border rounded p-3 mb-0">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <h5 class="mb-1">MyCloud Hospitality</h5>
                                                <div class="text-muted">
                                                    Client ID: <?= html_escape($mycloud_config->client_id) ?>
                                                    <span class="mx-2">&bull;</span>
                                                    Chain Code: <?= html_escape($mycloud_config->chain_code) ?>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary mt-2 mt-md-0" data-toggle="modal" data-target="#mycloudConfigModal">
                                                <i class="fa fa-edit mr-1"></i> Edit Configuration
                                            </button>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="card border rounded p-4 mb-0 text-center">
                                        <h5 class="mb-1">No PMS has been configured</h5>
                                        <p class="text-muted mb-0">Use Add PMS to configure MyCloud Hospitality.</p>
                                    </div>
                                <?php } ?>
                            </div>

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

<div class="modal fade" id="mycloudConfigModal" tabindex="-1" role="dialog" aria-labelledby="mycloudConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="mycloudConfigModalLabel">MyCloud PMS Configuration</h5>
                    <small class="text-muted">Configure the MyCloud account used by the booking integration.</small>
                </div>
            </div>

            <form id="mycloudSettingsForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>API Base URL</label>
                            <input type="url" name="mycloud_api_url" class="form-control"
                                value="<?= html_escape($mycloud_config->api_url ?? '') ?>"
                                maxlength="500"
                                placeholder="https://example.com/api/pms/reservation">
                            <small class="text-danger error"></small>
                            <small class="form-text text-muted">Enter the common URL before processbookings, searchbookings or getroomrateavailability.</small>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Auth Code</label>
                            <input type="password" name="mycloud_auth_code" class="form-control"
                                placeholder="<?= $mycloud_has_auth_code ? 'Leave blank to keep existing Auth Code' : 'Enter MyCloud Auth Code' ?>"
                                autocomplete="new-password">
                            <small class="text-danger error"></small>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Client ID</label>
                            <input type="text" name="mycloud_client_id" class="form-control"
                                value="<?= html_escape($mycloud_config->client_id ?? '') ?>"
                                maxlength="150"
                                placeholder="Enter MyCloud Client ID">
                            <small class="text-danger error"></small>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Chain Code</label>
                            <input type="text" name="mycloud_chain_code" class="form-control"
                                value="<?= html_escape($mycloud_config->chain_code ?? '') ?>"
                                maxlength="100"
                                placeholder="Enter MyCloud Chain Code">
                            <small class="text-danger error"></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save MyCloud Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="testSmtpModal" tabindex="-1" role="dialog" aria-labelledby="testSmtpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="testSmtpModalLabel">Send Test Email</h5>
                    <small class="text-muted">The test message will only be sent to the email entered below.</small>
                </div>
            </div>

            <form id="testSmtpForm">
                <div class="modal-body">
                    <label>Recipient Email</label>
                    <input type="email" name="test_email" class="form-control"
                        maxlength="254" placeholder="Enter a valid email address" autocomplete="email">
                    <small class="text-danger error"></small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane mr-1"></i> Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script defer src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"
    onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js';"></script>
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

    $(function() {
        const brandingInputs = $('.dropify');

        if ($.fn.dropify) {
            brandingInputs.dropify();
        }

        brandingInputs.on('change', function() {
            const input = this;
            const file = input.files && input.files[0];

            if (!file) {
                return;
            }

            const field = input.name;
            const extension = file.name.split('.').pop().toLowerCase();
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'ico', 'webp'];

            if (!allowedExtensions.includes(extension) || file.size > (6048 * 1024)) {
                toastr.error('Please select a JPG, JPEG, PNG, ICO or WEBP image up to 6 MB');
                return;
            }

            const formData = new FormData();
            formData.append(field, file);
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: "<?= base_url('software-settings/upload-branding') ?>",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $(input).prop('disabled', true);
                },
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }

                    if (!response.status) {
                        toastr.error(response.message || 'Unable to upload branding image');
                        return;
                    }

                    toastr.success(response.message);
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.csrfHash) {
                        window.CSRF.hash = xhr.responseJSON.csrfHash;
                    }
                    toastr.error('Unable to upload branding image');
                },
                complete: function() {
                    $(input).prop('disabled', false);
                }
            });
        });
    });

    function showSectionErrors(form, errors) {
        Object.keys(errors || {}).forEach(function(field) {
            form.find('[name="' + field + '"]').next('.error').text(errors[field]);
        });
    }

    function bindSettingsForm(config) {
        $(config.form).on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const button = form.find('button[type="submit"]');
            form.find('.error').text('');

            if (config.validate && !config.validate(form)) {
                return;
            }

            const formData = new FormData(this);
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: config.url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }

                    if (response.status) {
                        toastr.success(response.message);
                        if (config.onSuccess) {
                            config.onSuccess(form, response);
                        }
                    } else {
                        showSectionErrors(form, response.errors);
                        toastr.error(response.message || 'Unable to update settings');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.csrfHash) {
                        window.CSRF.hash = xhr.responseJSON.csrfHash;
                    }
                    toastr.error('Unable to update settings');
                },
                complete: function() {
                    button.prop('disabled', false).text(config.buttonText);
                }
            });
        });
    }

    bindSettingsForm({
        form: '#basicSettingsForm',
        url: "<?= base_url('software-settings/update-basic') ?>",
        buttonText: 'Save Basic Information',
        validate: function(form) {
            const title = form.find('[name="site_title"]');

            if ($.trim(title.val()) === '') {
                title.next('.error').text('Site title is required');
                return false;
            }

            return true;
        }
    });

    let smtpHasPassword = <?= $smtp_has_password ? 'true' : 'false' ?>;

    bindSettingsForm({
        form: '#smtpSettingsForm',
        url: "<?= base_url('software-settings/update-smtp') ?>",
        buttonText: 'Save SMTP Settings',
        validate: function(form) {
            const requiredFields = {
                smtp_host: 'SMTP host is required',
                smtp_port: 'SMTP port is required',
                smtp_user: 'SMTP user is required',
                smtp_from_email: 'From email is required',
                smtp_from_name: 'From name is required'
            };
            let isValid = true;

            Object.keys(requiredFields).forEach(function(field) {
                const input = form.find('[name="' + field + '"]');
                if ($.trim(input.val()) === '') {
                    input.next('.error').text(requiredFields[field]);
                    isValid = false;
                }
            });

            const port = form.find('[name="smtp_port"]');
            const portValue = Number(port.val());
            if ($.trim(port.val()) !== '' && (!Number.isInteger(portValue) || portValue < 1 || portValue > 65535)) {
                port.next('.error').text('Enter a valid port between 1 and 65535');
                isValid = false;
            }

            const password = form.find('[name="smtp_pass"]');
            if (!smtpHasPassword && $.trim(password.val()) === '') {
                password.next('.error').text('SMTP password is required');
                isValid = false;
            }

            const email = form.find('[name="smtp_from_email"]');
            const value = $.trim(email.val());
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value !== '' && !emailRegex.test(value)) {
                email.next('.error').text('Enter a valid email');
                isValid = false;
            }

            return isValid;
        },
        onSuccess: function(form) {
            const password = form.find('[name="smtp_pass"]');
            smtpHasPassword = true;
            password.val('').attr('placeholder', 'Leave blank to keep existing password');
        }
    });

    bindSettingsForm({
        form: '#testSmtpForm',
        url: "<?= base_url('software-settings/test-smtp') ?>",
        buttonText: 'Send Test Email',
        validate: function(form) {
            const email = form.find('[name="test_email"]');
            const value = $.trim(email.val());
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value === '' || !emailRegex.test(value)) {
                email.next('.error').text('Enter a valid email address');
                return false;
            }

            return true;
        },
        onSuccess: function(form) {
            form[0].reset();
            $('#testSmtpModal').modal('hide');
        }
    });

    let airtelHasApiKey = <?= $airtel_has_api_key ? 'true' : 'false' ?>;

    bindSettingsForm({
        form: '#airtelSettingsForm',
        url: "<?= base_url('software-settings/update-airtel') ?>",
        buttonText: 'Save Airtel Configuration',
        validate: function(form) {
            const requiredFields = {
                api_url: 'API URL is required',
                caller_id: 'Caller ID is required',
                customer_id: 'Customer ID is required',
                call_flow_id: 'Call flow ID is required',
                notify_url: 'Notify URL is required'
            };
            let isValid = true;

            Object.keys(requiredFields).forEach(function(field) {
                const input = form.find('[name="' + field + '"]');
                if ($.trim(input.val()) === '') {
                    input.next('.error').text(requiredFields[field]);
                    isValid = false;
                }
            });

            const apiKey = form.find('[name="api_key"]');
            if (!airtelHasApiKey && $.trim(apiKey.val()) === '') {
                apiKey.next('.error').text('API key is required');
                isValid = false;
            }

            return isValid;
        },
        onSuccess: function(form) {
            const apiKey = form.find('[name="api_key"]');
            airtelHasApiKey = true;
            apiKey.val('').attr('placeholder', 'Leave blank to keep existing API key');
        }
    });

    let mycloudHasAuthCode = <?= $mycloud_has_auth_code ? 'true' : 'false' ?>;

    bindSettingsForm({
        form: '#mycloudSettingsForm',
        url: "<?= base_url('software-settings/update-mycloud') ?>",
        buttonText: 'Save MyCloud Configuration',
        validate: function(form) {
            const requiredFields = {
                mycloud_api_url: 'API Base URL is required',
                mycloud_client_id: 'Client ID is required',
                mycloud_chain_code: 'Chain Code is required'
            };
            let isValid = true;

            Object.keys(requiredFields).forEach(function(field) {
                const input = form.find('[name="' + field + '"]');
                if ($.trim(input.val()) === '') {
                    input.next('.error').text(requiredFields[field]);
                    isValid = false;
                }
            });

            const authCode = form.find('[name="mycloud_auth_code"]');
            if (!mycloudHasAuthCode && $.trim(authCode.val()) === '') {
                authCode.next('.error').text('Auth Code is required');
                isValid = false;
            }

            const apiUrl = form.find('[name="mycloud_api_url"]');
            if ($.trim(apiUrl.val()) !== '' && !/^https:\/\//i.test($.trim(apiUrl.val()))) {
                apiUrl.next('.error').text('Enter a valid HTTPS API base URL');
                isValid = false;
            }

            return isValid;
        },
        onSuccess: function(form) {
            const authCode = form.find('[name="mycloud_auth_code"]');
            mycloudHasAuthCode = true;
            authCode.val('').attr('placeholder', 'Leave blank to keep existing Auth Code');
            $('#mycloudConfigModal').modal('hide');
            setTimeout(function() {
                window.location.reload();
            }, 700);
        }
    });
</script>
