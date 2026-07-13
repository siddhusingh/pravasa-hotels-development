<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="content-header">
         <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">View Daily Sale Reports</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">View Daily Sale Reports</li>
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
                     <h4 class="box-title">View Daily Sale Reports</h4>
                     <div class="float-right" style="float:right;">
                        <a href="<?php echo base_url('add-hotel-dsr') ?>">
                        <button type="button" class="btn btn-primary-light btn-sm " >
                        Add New Report
                        </button>
                        </a>
                     </div>
                  </div>
                  <div class="box-body">
                     <div class="table-responsive">
                        <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                           <thead>
                              <tr class="text-dark">
                                 <th>Sr. No.</th>
                                 <th>Report ID</th>
                                 <th>Report Date</th>
                                 <th>Uploaded On</th>
                                 <th>Updated On</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if(!empty($daily_sale_report)) {
                                 $number=1;
                                 
                                 
                                 foreach ($daily_sale_report as $each) { ?>
                              <tr>
                                 <td><?php echo $number; $number++; ?></td>
                                 <td><?= $each->reportUid ?></td>
                                 <td><?= $each->report_date ?></td>
                                 <td><?= $each->created_at ?></td>
                                 <td><?= $each->updated_at ?></td>
                                 <td class="table-action min-w-100">
                                   
                                    <a href="<?php echo base_url('hotelAdmin/ManageDSR/viewDSRDetails/') ?>/<?php echo $each->reportUid ?>" class=" btn btn-primary-light hover-primary " >
                                     <i class="fa fa-eye"></i>  Details
                                       
                                    </a>
                                 </td>
                              </tr>
                              <?php
                                 } } ?>
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
   
   
   
      <?php  if($this->session->flashdata('dsr_success_msg')!=""){ ?>
      
      
   
      toastr.success('<?php echo $this->session->flashdata('dsr_success_msg'); ?>')
   
    <?php  $this->session->set_flashdata('dsr_success_msg',''); } ?>
</script>
