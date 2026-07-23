<style>
    #add-staff-modal .select2-container,
    #edit-staff-modal .select2-container {
        width: 100% !important;
    }

    #add-staff-modal .select2-container .select2-selection--single,
    #edit-staff-modal .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
    }

    #add-staff-modal .select2-selection--single .select2-selection__rendered,
    #edit-staff-modal .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 13px !important;
        padding-right: 35px !important;
    }

    #add-staff-modal .select2-selection--single .select2-selection__arrow,
    #edit-staff-modal .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .staff-select2-dropdown .select2-search__field {
        height: 34px !important;
        min-height: 34px !important;
        padding: 5px 9px !important;
        border: 1px solid #d9d9d9 !important;
        border-radius: 5px !important;
        box-shadow: none !important;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-users"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Manage Staff Members</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>User Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Staff Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img/staff_members_img.png'); ?>" alt="">
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Manage Staff Members</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm " id="open-staff-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>

                                            <th> Name</th>
                                            <th> Email</th>
                                            <th>Phone</th>

                                            <th>Created on</th>
                                            <th>Updated on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($members)) {
                                            $number = 1;
                                            foreach ($members as $each) { ?>
                                                <tr>
                                                    <td><?php echo $number++; ?></td>
                                                    <td><?= $each->name ?></td>
                                                    <td><?= $each->email ?></td>
                                                    <td><?= $each->phone ?></td>
                                                    <td><?= $each->created_at ?></td>
                                                    <td><?= $each->updated_at ?></td>
                                                    <td class="table-action min-w-100">
                                                        <a href="javascript:void(0)" class="text-fade hover-primary view-staff"
                                                            data-record_id="<?= $each->id ?>" data-name="<?= $each->name ?>">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="javascript:void(0)" class="text-fade hover-primary edit-staff"
                                                            data-record_id="<?= $each->id ?>">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <a href="javascript:void(0)" class="text-fade hover-primary delete-staff"
                                                            data-record_id="<?= $each->id ?>">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>

                                </table>
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
<!-- Modal -->
<div class="modal  modal-lg new_modal_design" id="add-staff-modal" tabindex="-1" role="dialog" aria-labelledby="add-staff-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add New Staff</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to add a staff member.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <!--  <form class="ps-3 pe-3" action="#"> -->


                <div class="row">


                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="text" id="name" required="" placeholder="Full Name">
                            <span id="name_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="email" id="email" required="" placeholder="Email">
                            <span id="email_error" class="validation text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="password" id="password" required="" placeholder="Password">
                            <span id="password_error" class="validation text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="phone" required="" placeholder="Phone">
                            <span id="phone_error" class="validation text-danger"></span>
                        </div>
                    </div>

                </div>

                <div id="new__ver_seq">
                    <!-- First row (visible by default) -->
                    <div id="row__ver_seq" class="row__par_div">
                        <div class="row">

                            <!-- Hotel Select -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Hotel <span class="required-asterisk">*</span></label>
                                    <select class="form-control" name="hotel_id[]">
                                        <option selected disabled value="">Select Hotel</option>
                                        <?php foreach ($hotel_admin as $each) { ?>
                                            <option value="<?php echo encrypt_id($each->hotel_id); ?>">
                                                <?php echo htmlspecialchars($each->hotel_name, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>

                            <!-- Department Select -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Department <span class="required-asterisk">*</span></label>
                                    <select class="form-control" name="department_id[]">
                                        <option selected disabled value="">Select Department</option>
                                        <?php foreach ($departments as $each) { ?>
                                            <option value="<?php echo encrypt_id($each->department_id); ?>">
                                                <?php echo htmlspecialchars($each->department_name, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>

                            <!-- Escalation Level Select -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Escalation Level <span class="required-asterisk">*</span></label>
                                    <select name="level[]" class="form-control">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-primary btn sm" id="add__ver_seq" type="button">
                                    +
                                </button>   
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Add button (below the first row) -->




            </div>
            <div class="modal-footer" d-flex justify-content-start>
                <button type="button" class="btn btn-primary " data-bs-dismiss="modal">Close</button>
                <button type="button" id="SaveBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>



<!-- Edit Staff Modal -->
<div class="modal modal-lg new_modal_design" id="edit-staff-modal" tabindex="-1" role="dialog" aria-labelledby="edit-staff-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h4 class="modal-title" id="editStaffModalLabel">Edit Staff Details</h4>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Fill in the details to update a staff member.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <input type="hidden" id="edit_record_id" name="edit_record_id">

                <div class="row">
                    <!-- Full Name -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="text" id="name_edit" required placeholder="Full Name">
                            <span id="name_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="email" id="email_edit" required placeholder="Email">
                            <span id="email_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Phone <span class="required-asterisk">*</span></label>
                            <input class="form-control" type="number" id="phone_edit" required placeholder="Phone">
                            <span id="phone_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" id="password_edit" required placeholder="Password">
                            <span id="password_edit_error" class="validation text-danger"></span>
                        </div>
                    </div>
                </div>

                <!-- Hotel, Department & Escalation Level -->
                <div id="edit__ver_seq">
                    <!-- First row (visible by default) -->
                    <div id="edit_row__ver_seq" class="row__par_div">
                        <div class="row">
                            <!-- Hotel Select -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Hotel <span class="required-asterisk">*</span></label>
                                    <select class="form-control" name="hotel_id_edit[]">
                                        <option selected disabled value="">Select Hotel</option>
                                        <?php foreach ($hotel_admin as $each) { ?>
                                            <option value="<?php echo encrypt_id($each->hotel_id); ?>">
                                                <?php echo htmlspecialchars($each->hotel_name, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>

                            <!-- Department Select -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Department <span class="required-asterisk">*</span></label>
                                    <select class="form-control" name="department_id_edit[]">
                                        <option selected disabled value="">Select Department</option>
                                        <?php foreach ($departments as $each) { ?>
                                            <option value="<?php echo encrypt_id($each->department_id); ?>">
                                                <?php echo htmlspecialchars($each->department_name, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>

                            <!-- Escalation Level Select -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Escalation Level <span class="required-asterisk">*</span></label>
                                    <select name="level_edit[]" class="form-control">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                    <span class="validation text-danger"></span>
                                </div>
                            </div>

                            <!-- Remove button -->
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn btn-danger btn-sm remove-row" type="button">
                                    -
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Row Button -->
                <div class="mt-2">
                    <button class="btn btn-primary btn-sm" id="add__ver_seq_edit" type="button">+ Add More</button>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="updateBtn" class="btn btn-primary">Update Staff</button>
            </div>
        </div>
    </div>
</div>




<!-- View Staff Modal -->
<div class="modal modal-lg new_modal_design" id="viewStaffModal" tabindex="-1" aria-labelledby="viewStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="viewStaffModalLabel">Staff Details</h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                Review assigned hotels, departments, and escalation levels.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <h4 id="staffName"></h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hotel</th>
                            <th>Department</th>
                            <th>Escalation Level</th>
                        </tr>
                    </thead>
                    <tbody id="staffDetails"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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



    <?php if ($this->session->flashdata('staff_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('staff_success_msg'); ?>')

    <?php $this->session->set_flashdata('staff_success_msg', '');
    } ?>
</script>
<script type="text/javascript">
    var staffTable = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: '<?php echo base_url('get-staff-table'); ?>',
            type: 'POST',
            data: function(d) {
                d[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function(json) {
                if (json.csrfHash) {
                    window.CSRF.hash = json.csrfHash;
                }
                return json.data;
            }
        },
        columnDefs: [{ orderable: false, targets: 6 }]
    });

    function updateStaffCsrf(response) {
        if (response && response.csrfHash) {
            window.CSRF.hash = response.csrfHash;
        }
    }

    function showStaffErrors(errors) {
        if (!errors) {
            return;
        }
        $.each(errors, function(field, message) {
            $('#' + field + '_error, #' + field + '_edit_error').text(message);
        });
    }

    function initStaffMappingSelect2(container, modalSelector) {
        if (!$.fn.select2) {
            return;
        }

        $(container).find(
            'select[name="hotel_id[]"], select[name="department_id[]"], ' +
            'select[name="hotel_id_edit[]"], select[name="department_id_edit[]"]'
        ).each(function() {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }

            var isHotel = $select.attr('name').indexOf('hotel_id') === 0;
            $select.select2({
                width: '100%',
                placeholder: isHotel ? 'Select Hotel' : 'Select Department',
                allowClear: false,
                dropdownParent: $(modalSelector),
                dropdownCssClass: 'staff-select2-dropdown'
            });
        });
    }

    function buildStaffOptions(options, selectedId, placeholder) {
        var html = '<option value="">' + placeholder + '</option>';

        $.each(options || [], function(index, option) {
            var selected = option.id === selectedId ? ' selected' : '';
            html += '<option value="' + $('<div>').text(option.id).html() + '"' + selected + '>' +
                $('<div>').text(option.text).html() + '</option>';
        });

        return html;
    }

    var staffHotelOptions = <?php echo json_encode(array_map(function ($hotel) {
        return [
            'id' => encrypt_id($hotel->hotel_id),
            'text' => $hotel->hotel_name
        ];
    }, $hotel_admin), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
    var staffDepartmentOptions = <?php echo json_encode(array_map(function ($department) {
        return [
            'id' => encrypt_id($department->department_id),
            'text' => $department->department_name
        ];
    }, $departments), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

    $(function() {
        initStaffMappingSelect2('#add-staff-modal', '#add-staff-modal');
        initStaffMappingSelect2('#edit-staff-modal', '#edit-staff-modal');
    });

    // Open Add Staff Modal
    $("#open-staff-modal").click(function(e) {
        e.preventDefault();

        $('#name').val("");
        $('#email').val("");
        $('#phone').val("");
        $('#password').val("");
        $('#designation').val("");

        $('#new__ver_seq .row__par_div').not(':first').remove();
        $('#new__ver_seq select[name="hotel_id[]"], #new__ver_seq select[name="department_id[]"]')
            .val(null)
            .trigger('change');
        $('#new__ver_seq select[name="level[]"]').val('1');

        $('#add-staff-modal').modal('show');
    });

    // Validation Rules
    $('#name').focusout(function() {
        var value = this.value.trim();
        if (value === "") {
            $('#name_error').html('Please Enter Staff Name');
        } else {
            $('#name_error').html('');
        }
    });

    // Validate at least one Hotel is selected
    $('input[name="hotel_id[]"]').change(function() {
        if ($('input[name="hotel_id[]"]:checked').length > 0) {
            $('#hotel_id_error').html('');
        } else {
            $('#hotel_id_error').html('Please Select at least one Hotel');
        }
    });

    // Validate at least one Department is selected
    $('input[name="department_id[]"]').change(function() {
        if ($('input[name="department_id[]"]:checked').length > 0) {
            $('#department_id_error').html('');
        } else {
            $('#department_id_error').html('Please Select at least one Department');
        }
    });

    // Validate at least one Level is selected
    $('input[name="level[]"]').change(function() {
        if ($('input[name="level[]"]:checked').length > 0) {
            $('#level_error').html('');
        } else {
            $('#level_error').html('Please Select at least one Escalation Level');
        }
    });

    // Validate Email
    $('#email').focusout(function() {
        var value = this.value.trim();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value === "") {
            $('#email_error').html('Please Enter Email');
        } else if (!emailRegex.test(value)) {
            $('#email_error').html('Invalid Email Format');
        } else {
            $('#email_error').html('');
        }
    });

    // Validate Phone
    $('#phone').focusout(function() {
        var value = this.value.trim();
        var phoneRegex = /^[0-9]{10}$/; // Only 10-digit numbers

        if (value === "") {
            $('#phone_error').html('Please Enter Phone Number');
        } else if (!phoneRegex.test(value)) {
            $('#phone_error').html('Invalid Phone Number (must be 10 digits)');
        } else {
            $('#phone_error').html('');
        }
    });

    // Validate Password
    $('#password').focusout(function() {
        var value = this.value;
        var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 chars, one number & one special char

        if (value === "") {
            $('#password_error').html('Please Enter Password');
        } else if (!passwordRegex.test(value)) {
            $('#password_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
            $('#password').val('');
        } else {
            $('#password_error').html('');
        }
    });

    $('#password_edit').focusout(function() {
        var value = this.value;
        var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 chars, one number & one special char

        if (value === "") {
            $('#password_edit_error').html('');
        } else if (!passwordRegex.test(value)) {
            $('#password_edit_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
        } else {
            $('#password_edit_error').html('');
        }
    });


    // submit function for add a new record

    $(document).on('click', '#SaveBtn', function(e) {
        e.preventDefault();

        // Trigger validation on all fields
        $('#name').trigger('focusout');
        $('#email').trigger('focusout');
        $('#password').trigger('focusout');
        $('#phone').trigger('focusout');


        // Iterate over dynamically added hotel, department, and level fields
        let hotels = [];
        let departments = [];
        let levels = [];

        $("select[name='hotel_id[]']").each(function() {
            let val = $(this).val();
            if (val) hotels.push(val);
        });

        $("select[name='department_id[]']").each(function() {
            let val = $(this).val();
            if (val) departments.push(val);
        });

        $("select[name='level[]']").each(function() {
            let val = $(this).val();
            if (val) levels.push(val);
        });

        // Get other single input fields
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var phone = $('#phone').val();
        var designation = $("#designation").val();

        // Validate required fields
        if (name && email && password && phone && hotels.length > 0 && departments.length > 0 && levels.length > 0) {

            var formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('phone', phone);

            // Append multiple hotel, department, and level values as JSON
            formData.append('hotels', JSON.stringify(hotels));
            formData.append('departments', JSON.stringify(departments));
            formData.append('levels', JSON.stringify(levels));
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: '<?php echo base_url("insert-staff") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#SaveBtn").text('Saving..').attr('disabled', true);
                },
                success: function(response) {
                    updateStaffCsrf(response);
                    $("#SaveBtn").text('Add').attr('disabled', false);
                    if (response.status) {
                        toastr.success(response.message);
                        $('#add-staff-modal').modal('hide');
                        staffTable.draw(false);
                    } else {
                        showStaffErrors(response.errors);
                        toastr.error(response.message || 'Failed to save staff');
                    }
                },
                error: function() {
                    $("#SaveBtn").text('Add').attr('disabled', false);
                    alert("Error in saving data.");
                }
            });
        } else {
            alert("Please fill in all required fields.");
        }
    });



    // edit-staff function start from here


    $(document).on("click", ".edit-staff", function(e) {
        e.preventDefault();

        var staffId = $(this).attr("data-record_id");

        // Clear previous values
        $("#edit__ver_seq").html(""); // Remove previous dynamic fields
        $("#name_edit, #email_edit, #phone_edit").val("");

        $.ajax({
            url: '<?php echo base_url("get_staff_details"); ?>',
            type: "POST",
            dataType: "JSON",
            data: {
                staff_id: staffId,
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                updateStaffCsrf(res);

                if (res.status) {
                    // Populate Staff Details
                    $("#edit_record_id").val(staffId);
                    $("#name_edit").val(res.staff.name);
                    $("#email_edit").val(res.staff.email);
                    $("#phone_edit").val(res.staff.phone);

                    $.each(res.mappings, function(index, mapping) {
                        var hotelOptions = buildStaffOptions(
                            res.hotel_options,
                            mapping.hotel_id,
                            'Select Hotel'
                        );
                        var departmentOptions = buildStaffOptions(
                            res.department_options,
                            mapping.department_id,
                            'Select Department'
                        );

                        var levelOptions = '';
                        for (let level = 1; level <= 3; level++) {
                            var selected = (level == mapping.level) ? 'selected' : '';
                            levelOptions += `<option value="${level}" ${selected}>${level}</option>`;
                        }

                        var newRow = `
        <div class="row row__par_div">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Select Hotel</label>
                    <select class="form-control" name="hotel_id_edit[]">
                        ${hotelOptions}
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Select Department</label>
                    <select class="form-control" name="department_id_edit[]">
                        ${departmentOptions}
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Escalation Level</label>
                    <select name="level_edit[]" class="form-control">
                        ${levelOptions}
                    </select>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-danger btn-sm remove-row" type="button">-</button>
            </div>
        </div>
    `;
                        $("#edit__ver_seq").append(newRow);
                    });

                    initStaffMappingSelect2('#edit__ver_seq', '#edit-staff-modal');

                    var unavailableMapping = (res.mappings || []).some(function(mapping) {
                        return !mapping.hotel_id || !mapping.department_id;
                    });
                    if (unavailableMapping) {
                        toastr.warning(
                            'A previously assigned hotel or department is unavailable. Please select an active replacement.'
                        );
                    }

                    // Show Modal
                    $("#edit-staff-modal").modal("show");
                } else {
                    alert("Error fetching staff details!");
                }
            },
            error: function() {
                alert("Error in AJAX request!");
            }
        });
    });

    // Add More Rows for Hotel, Department & Level
    $("#add__ver_seq_edit").click(function() {
        var newRow = `
<div class="row row__par_div">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Select Hotel</label>
            <select class="form-control" name="hotel_id_edit[]">
                ${buildStaffOptions(staffHotelOptions, null, 'Select Hotel')}
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Select Department</label>
            <select class="form-control" name="department_id_edit[]">
                ${buildStaffOptions(staffDepartmentOptions, null, 'Select Department')}
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Escalation Level</label>
            <select name="level_edit[]" class="form-control">
                <option>1</option>
                <option>2</option>
                <option>3</option>
            </select>
        </div>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button class="btn btn-danger btn-sm remove-row" type="button">-</button>
    </div>
</div>
`;
        $("#edit__ver_seq").append(newRow);
        initStaffMappingSelect2('#edit__ver_seq .row__par_div:last', '#edit-staff-modal');
    });

    // Remove Row
    $(document).on("click", ".remove-row", function() {
        $(this).closest(".row__par_div").remove();
    });




    $(document).on('click', '#updateBtn', function(e) {
        e.preventDefault();

        $('#name_edit').trigger('focusout');

        var record_id = $("#edit_record_id").val();
        var name = $('#name_edit').val();
        var email = $('#email_edit').val();
        var phone = $('#phone_edit').val();
        var password = $('#password_edit').val();




        var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 chars, one number & one special char

        if (password !== "" && !passwordRegex.test(password)) {
            $('#password_edit_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
            return;
        }

        $('#password_edit_error').html('');


        var hotels = [];
        var departments = [];
        var levels = [];

        // Loop through each mapping row
        $('.row__par_div').each(function() {
            var hotel_id = $(this).find('[name="hotel_id_edit[]"]').val();
            var department_id = $(this).find('[name="department_id_edit[]"]').val();
            var level = $(this).find('[name="level_edit[]"]').val();

            if (hotel_id && department_id && level) {
                hotels.push(hotel_id);
                departments.push(department_id);
                levels.push(level);
            }
        });

        if (name && email && phone && hotels.length > 0 && departments.length > 0 && levels.length > 0) {
            var formData = new FormData();
            formData.append('record_id', record_id);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('password', password);

            formData.append('hotels', JSON.stringify(hotels));
            formData.append('departments', JSON.stringify(departments));
            formData.append('levels', JSON.stringify(levels));
            formData.append(window.CSRF.name, window.CSRF.hash);

            $.ajax({
                url: '<?php echo base_url("update-staff") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#updateBtn").text('Updating...').attr('disabled', true);
                },
                success: function(response) {
                    updateStaffCsrf(response);
                    $("#updateBtn").text('Update').attr('disabled', false);

                    if (response.status) {
                        toastr.success(response.message);
                        $('#edit-staff-modal').modal('hide');
                        staffTable.draw(false);
                    } else {
                        showStaffErrors(response.errors);
                        toastr.error(response.message || 'Failed to update staff');
                    }
                },
                error: function() {
                    $("#updateBtn").text('Update').attr('disabled', false);
                    alert('Error in updating staff data.');
                }
            });
        } else {
            alert("Please fill in all required fields.");
        }
    });




    // delete staff code is here

    $(document).on("click", ".delete-staff", function(argument) {
        var id = $(this).attr('data-record_id');
        Swal.fire({
            title: "Are you sure?",
            text: 'This staff member will be removed from the active staff list.',
            icon: "question",
            showCancelButton: true,
            showCloseButton: true,

            confirmButtonText: "Yes, delete it",
            denyButtonText: `Cancel`
        }).then((result) => {

            if (result.isConfirmed) {


                $.ajax({
                    url: '<?php echo base_url('delete-staff') ?>',
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(res) {
                        updateStaffCsrf(res);
                        if (res.status) {
                            toastr.success(res.message);
                            staffTable.draw(false);
                        } else {
                            toastr.error(res.message || 'Something went wrong');
                        }
                    }

                })





            }
        });
    })
</script>

<script type="text/javascript">
    $("#add__ver_seq").click(function() {
        var hotelOptions = buildStaffOptions(staffHotelOptions, null, 'Select Hotel');
        var departmentOptions = buildStaffOptions(staffDepartmentOptions, null, 'Select Department');
        var newAdd__VS =
            '<div id="row__ver_seq" class="row__par_div">' +
            '<div class="row">' +

            // Hotel Select
            '<div class="col-md-4">' +
            '<div class="mb-3">' +
            '<label class="form-label">Select Hotel</label>' +
            '<select class="form-control" name="hotel_id[]">' +
            hotelOptions +
            '</select>' +
            '<span class="validation text-danger"></span>' +
            '</div></div>' +

            // Department Select
            '<div class="col-md-4">' +
            '<div class="mb-3">' +
            '<label class="form-label">Select Department</label>' +
            '<select class="form-control" name="department_id[]">' +
            departmentOptions +
            '</select>' +
            '<span class="validation text-danger"></span>' +
            '</div></div>' +

            // Escalation Level Select
            '<div class="col-md-3">' +
            '<div class="mb-3">' +
            '<label class="form-label">Escalation Level</label>' +
            '<select name="level[]" class="form-control">' +
            '<option>1</option>' +
            '<option>2</option>' +
            '<option>3</option>' +
            '</select>' +
            '<span class="validation text-danger"></span>' +
            '</div></div>' +

            // Delete Button
            '<div class="col-md-1 d-flex align-items-end">' +
            '<button class="btn btn-danger del_btnvs" id="del__ver_seq" type="button">' +
            '<i class="fa-solid fa-xmark"></i></button>' +
            '</div>' +

            '</div></div>';

        $('#new__ver_seq').append(newAdd__VS);
        initStaffMappingSelect2('#new__ver_seq .row__par_div:last', '#add-staff-modal');
    });

    // Remove dynamically added row
    $("body").on("click", "#del__ver_seq", function() {
        $(this).closest("#row__ver_seq").remove();
    });
</script>


<script>
    $(document).on('click', '.view-staff', function() {
        var staffId = $(this).data('record_id');
        var staffName = $(this).data('name');

        // Update Modal Title with Staff Name
        $('#staffName').text("Details for: " + staffName);

        // AJAX Call to Fetch Data
        $.ajax({
            url: '<?php echo base_url("view-staff"); ?>',
            type: 'POST',
            data: {
                staff_id: staffId,
                [window.CSRF.name]: window.CSRF.hash
            },
            dataType: 'json',
            beforeSend: function() {
                $('#staffDetails').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
            },
            success: function(response) {
                updateStaffCsrf(response);
                console.log(response);
                if (response.status) {
                    var html = '';
                    $.each(response.data, function(index, item) {
                        html += '<tr>' +
                            '<td>' + item.hotel_name + '</td>' +
                            '<td>' + item.department_name + '</td>' +
                            '<td>' + item.level + '</td>' +
                            '</tr>';
                    });
                    $('#staffDetails').html(html);
                } else {
                    $('#staffDetails').html('<tr><td colspan="3" class="text-center">No records found.</td></tr>');
                }
            }

        });

        // Show Modal
        $('#viewStaffModal').modal('show');
    });
</script>
