<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Calling History</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Calling History</li>
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
                            <h4 class="box-title">Calling History</h4>

                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Caller Number</th>
                                        <th>Destination Number</th>
                                        <th>Call Status</th>
                                        <th>Call Duration</th>
                                        <th>Timestamp</th>
                                        <th>Audio Recording</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($calls)) { ?>
                                        <?php foreach ($calls as $call) { ?>
                                            <tr>
                                                <td><?php echo $call['id']; ?></td>
                                                <td><?php echo $call['caller_number']; ?></td>
                                                <td><?php echo $call['destination_number']; ?></td>
                                                <td><?php echo $call['overall_call_status']; ?></td>
                                                <td><?php echo $call['overall_call_duration']; ?></td>
                                                <td><?php echo $call['timestamp']; ?></td>
                                                <td>
                                                    <?php if (!empty($call['recording_url'])) { ?>
                                                        <audio controls>
                                                            <source src="<?php echo $call['recording_url']; ?>" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    <?php } else { ?>
                                                        <span class="text-danger">No Recording</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No Call Records Found</td>
                                        </tr>
                                    <?php } ?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>