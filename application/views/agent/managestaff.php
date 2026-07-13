<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">View Staff Members</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Hotel Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">View Staff Members</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">View Staff Members</h4>
                            <div class="float-right" style="float:right;">
                                <button type="button" class="btn btn-primary-light btn-sm " id="open-staff-modal">
                                    Add +
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>

                                            <th> Name</th>
                                            <th> Email</th>
                                            <th>Phone</th>


                                            <th>Leads</th>
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

                                                    <td class="table-action min-w-100">
                                                        <button type="button" class="btn btn-warning btn-sm">
                                                            View Leads <span class="badge badge-dark">4</span>
                                                        </button>
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





<!-- View Staff Modal -->
<div class="modal modal-lg" id="viewStaffModal" tabindex="-1" aria-labelledby="viewStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStaffModalLabel">Staff Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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



    <?php if ($this->session->flashdata('staff_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('staff_success_msg'); ?>')

    <?php $this->session->set_flashdata('staff_success_msg', '');
    } ?>
</script>
<script type="text/javascript">
    // Open Add Staff Modal
    $("#open-staff-modal").click(function(e) {
        e.preventDefault();

        $('#name').val("");
        $('#email').val("");
        $('#phone').val("");
        $('#password').val("");
        $('#designation').val("");

        // Uncheck all multi-select checkboxes for hotels, departments, and levels
        $('input[name="hotel_id[]"]').prop("checked", false);
        $('input[name="department_id[]"]').prop("checked", false);
        $('input[name="level[]"]').prop("checked", false);

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
                    $("#SaveBtn").text('Add').attr('disabled', false);
                    window.location.reload(); // Reload after success
                    // toastr.success('New staff has been added Successfully') 
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
                staff_id: staffId
            },
            success: function(res) {

                console.log(res)
                if (res.status) {
                    // Populate Staff Details
                    $("#edit_record_id").val(res.staff.staff_id);
                    $("#name_edit").val(res.staff.name);
                    $("#email_edit").val(res.staff.email);
                    $("#phone_edit").val(res.staff.phone);

                    // Populate Hotels, Departments & Levels
                    $.each(res.mappings, function(index, mapping) {
                        var newRow = `
                        <div class="row row__par_div">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Hotel</label>
                                    <select class="form-control" name="hotel_id_edit[]">
                                        <option value="${mapping.hotel_id}" selected>${mapping.hotel_name}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Department</label>
                                    <select class="form-control" name="department_id_edit[]">
                                        <option value="${mapping.department_id}" selected>${mapping.department_name}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Escalation Level</label>
                                    <select name="level_edit[]" class="form-control">
                                        <option value="${mapping.level}" selected>${mapping.level}</option>
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
                        <?php foreach ($hotel_admin as $each) { ?>
                            <option value="<?php echo $each->hotel_id ?>"><?php echo $each->hotel_name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Select Department</label>
                    <select class="form-control" name="department_id_edit[]">
                        <?php foreach ($departments as $each) { ?>
                            <option value="<?php echo $each->department_id ?>"><?php echo $each->department_name ?></option>
                        <?php } ?>
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
    });

    // Remove Row
    $(document).on("click", ".remove-row", function() {
        $(this).closest(".row__par_div").remove();
    });




    $(document).on('click', '#updateBtn', function(e) {
        e.preventDefault();

        $('#name_edit').trigger('focusout');
        $('#hotel_id_edit').trigger('focusout');
        $('#department_id _edit').trigger('focusout');

        var record_id = $("#edit_record_id").val();
        var name = $('#name_edit').val();
        var hotel_id = $('#hotel_id_edit').val();
        var department_id = $('#department_id _edit').val();


        if (name != '' && hotel_id != '' && department_id != '') {

            var formData = new FormData();
            formData.append('name', name);
            formData.append('hotel_id', hotel_id);
            formData.append('department_id ', department_id);
            formData.append('record_id', record_id);

            $.ajax({
                url: '<?php echo base_url("update-staff") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                beforeSend: function() {
                    $(this).html('updating..');
                    $(this).attr('disabled', true);

                },
                success: function(response) {

                    $("#updateBtn").attr('disabled', false);

                    $(this).attr('disabled', false);
                    // mera man
                    window.location.reload()
                    // toastr.success('New staff has been added Successfully') 
                }
            });



        }



    })



    // delete staff code is here

    $(".delete-staff").click(function(argument) {
        Swal.fire({
            title: "Are you sure?",
            text: 'You will not be able to recover this imaginary file!',
            icon: "question",
            showCancelButton: true,
            showCloseButton: true,

            confirmButtonText: "Yes Delete it",
            denyButtonText: `Cancel`
        }).then((result) => {

            if (result.isConfirmed) {


                var id = $(this).attr('data-record_id');
                $.ajax({
                    url: '<?php echo base_url('delete-staff') ?>',
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function(res) {
                        console.log(res)

                        //Swal.fire("staff Has been Deleted Successfully!", "", "success");
                        window.location.reload();


                    }

                })





            }
        });
    })
</script>

<script type="text/javascript">
    $("#add__ver_seq").click(function() {
        var newAdd__VS =
            '<div id="row__ver_seq" class="row__par_div">' +
            '<div class="row">' +

            // Hotel Select
            '<div class="col-md-4">' +
            '<div class="mb-3">' +
            '<label class="form-label">Select Hotel</label>' +
            '<select class="form-control" name="hotel_id[]">' +
            '<option selected disabled value="">Select Hotel</option>' +
            '<?php foreach ($hotel_admin as $each) { ?>' +
            '<option value="<?php echo $each->hotel_id ?>"><?php echo $each->hotel_name ?></option>' +
            '<?php } ?>' +
            '</select>' +
            '<span class="validation text-danger"></span>' +
            '</div></div>' +

            // Department Select
            '<div class="col-md-4">' +
            '<div class="mb-3">' +
            '<label class="form-label">Select Department</label>' +
            '<select class="form-control" name="department_id[]">' +
            '<option selected disabled value="">Select Department</option>' +
            '<?php foreach ($departments as $each) { ?>' +
            '<option value="<?php echo $each->department_id ?>"><?php echo $each->department_name ?></option>' +
            '<?php } ?>' +
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
                staff_id: staffId
            },
            dataType: 'json',
            beforeSend: function() {
                $('#staffDetails').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
            },
            success: function(response) {
                console.log(response)
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
            },
            error: function() {
                $('#staffDetails').html('<tr><td colspan="3" class="text-center">Error fetching data.</td></tr>');
            }
        });

        // Show Modal
        $('#viewStaffModal').modal('show');
    });
</script>