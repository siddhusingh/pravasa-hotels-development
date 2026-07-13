<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">View Department</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Hotel Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">View Department</li>
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
                            <h4 class="box-title">View Department</h4>
                            <div class="float-right" style="float:right;">

                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Department Name</th>
                                            <th>Escalation Level 1</th>
                                            <th>Escalation Level 2</th>
                                            <th>Escalation Level 3</th>

                                            <th>Leads</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($countries)) {
                                            $number = 1;


                                            foreach ($countries as $each_department) { ?>
                                                <tr>
                                                    <td><?php echo $number;
                                                        $number++; ?></td>

                                                    <td><?= $each_department->department_name ?></td>
                                                    <td><?= $each_department->escalation_level_1 ?> Hours</td>
                                                    <td><?= $each_department->escalation_level_2 ?> Hours</td>
                                                    <td><?= $each_department->escalation_level_3 ?> Hours</td>


                                                    <td class="table-action min-w-100">
                                                        <button type="button" class="btn btn-warning btn-sm">
                                                            View Leads <span class="badge badge-dark">4</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
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



    <?php if ($this->session->flashdata('department_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('department_success_msg'); ?>')

    <?php $this->session->set_flashdata('department_success_msg', '');
    } ?>
</script>
<script type="text/javascript">
    // add department code start from here

    $("#open-department-modal").click(function(e) {

        e.preventDefault()

        $('#department_name').val("");
        $('#escalation_level_1').val("");
        $('#escalation_level_2').val("");
        $('#escalation_level_3').val("");

        $('#add-department-modal').modal('show');
    });




    // validation rules

    $('#department_name').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#department_name_error').html('');
        } else {
            $('#department_name_error').html('Please Enter Department Name');
            $('#department_name').val('');
        }

    });

    $('#escalation_level_1').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level1_error').html('');
        } else {
            $('#level1_error').html('Please Enter Level 1 Escalation Code');
            $('#level1').val('');
        }

    });

    $('#escalation_level_2').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level2_error').html('');
        } else {
            $('#level2_error').html('Please Enter Level 2 Escalation Code');
            $('#level2').val('');
        }

    });


    $('#escalation_level_3').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level3_error').html('');
        } else {
            $('#level3_error').html('Please Enter Level 3 Escalation Code');
            $('#level3').val('');
        }

    });


    $('#department_name_edit').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#department_name_error_edit').html('');
        } else {
            $('#department_name_error_edit').html('Please Enter Department Name');
            $('#department_name').val('');
        }

    });

    $('#escalation_level_1_edit').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level1_error_edit').html('');
        } else {
            $('#level1_error_edit').html('Please Enter Level 1 Escalation Code');
            $('#level1').val('');
        }

    });

    $('#escalation_level_2_edit').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level2_error_edit').html('');
        } else {
            $('#level2_error_edit').html('Please Enter Level 2 Escalation Code');
            $('#level2').val('');
        }

    });


    $('#escalation_level_3_edit').focusout(function() {
        var value = this.value;
        if (value != "") {
            $('#level3_error_edit').html('');
        } else {
            $('#level3_error_edit').html('Please Enter Level 3 Escalation Code');
            $('#level3').val('');
        }

    });
</script>