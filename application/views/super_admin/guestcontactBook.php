<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-address-book"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Guest Contact Book</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Guest Contact Book</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Guest Contact Book</h4>
                            <div class="float-right" style="float:right;">
                                <a href="<?php echo base_url('add-lead'); ?>" class="btn btn-primary-light btn-sm">
                                    Add +
                                </a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>

                                            <th>Name</th>
                                            <th>Phone</th>

                                            <th>City </th>


                                            <th>Email</th>

                                            <th>Hotel Name</th>
                                            <th>Total Leads</th>
                                            <th>View Leads</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($guestcontactBook)) {
                                            $number = 1;
                                            foreach ($guestcontactBook as $each) { ?>
                                                <tr>
                                                    <td><?php echo $number++; ?></td>
                                                    <td><?= $each->user_name ?></td>
                                                    <td><?= $each->phone_number ?></td>
                                                    <td><?= $each->city_name ?></td>

                                                    <td><?php if (!empty($each->email)) {
                                                            echo $each->email;
                                                        } else {
                                                            echo "NA";
                                                        } ?></td>
                                                    <td><?= $each->hotel_name ?></td>
                                                    <td><?= $each->total_leads ?></td>

                                                    <td>
                                                        <a href="<?php echo base_url('manage-leads?phone=') ?><?= $each->phone_number ?>"><button class="btn btn-sm btn-warning">View Leads</button>
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
