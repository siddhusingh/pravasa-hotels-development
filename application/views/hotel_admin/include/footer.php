<style>
   .main-footer {
      font-size: 13px;
      color: #6b7280;
   }

   .main-footer a {
      font-weight: 600;
      color: #111827;
      text-decoration: none;
   }

   .main-footer a:hover {
      text-decoration: underline;
   }

   .main-footer .text-muted {
      color: #9ca3af !important;
   }
</style>
<footer class="main-footer bt-1">
   <div class="pull-right d-none d-sm-inline-block">
      <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
         <!-- future links -->
      </ul>
   </div>

   &copy;
   <script>
      document.write(new Date().getFullYear());
   </script>
   <a href="javascript:void(0)">LMS Software</a>
   <span class="mx-1">•</span>
   <span class="text-muted">Version 3.3</span>
   <span class="mx-1">•</span>
   All Rights Reserved.
</footer>
<!-- Side panel -->
<!-- quick_user_toggle -->

<?php

$this->load->model('Comman_model');
$login_id = $this->session->userdata('hotel_admin_session')['id'];
$profile_data = $this->Comman_model->get_single_record('hotel_admins', ['hotel_id' => $login_id]);


?>



<div class="modal modal-right fade" id="quick_user_toggle" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content slim-scroll3">
         <div class="modal modal-right fade show" id="quick_user_toggle" tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog">
               <div class="modal-content slim-scroll3 ps ps--active-y">
                  <div class="modal-body  bg-white">
                     <div class="d-flex align-items-center justify-content-between pb-30">
                        <h4 class="m-0">User Profile

                        </h4>
                        <a href="#" class="btn btn-icon btn-danger-light btn-sm no-shadow" data-bs-dismiss="modal">
                           <span class="fa fa-close"></span>
                        </a>
                     </div>
                     <div>
                        <div class="d-flex flex-row">

                           <div class="ps-20">
                              <h5 class="mb-0"><?php echo $profile_data->name; ?></h5>
                              <p class="my-5 text-fade">

                                 <?php if ($this->session->userdata('is_regional_manager') == 'true') {
                                    echo 'Regional Manager';
                                 } else {
                                    echo 'Admin';
                                 } ?> </p>
                              <p><a href="javascript:void(0)"><span class="icon-Mail-notification me-5 text-success"><span class="path1"></span><span class="path2"></span></span> <?php echo $profile_data->email; ?></a></p>
                              <p>
                                 <a href="javascript:void(0)"><span class="fa fa-phone me-5 text-success"><span class="path1"></span><span class="path2"></span></span> <?php echo $profile_data->phone; ?></a>
                              </p>

                              <a class="btn btn-danger-light btn-sm mt-5" href="<?php echo base_url('hotel-admin-sign-out') ?>">
                                 <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="dropdown-divider my-30"></div>
                     <div>
                        <div class="d-flex align-items-center mb-30">
                           <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                              <span class="icon-Library fs-24"><span class="path1"></span><span class="path2"></span></span>
                           </div>
                           <div class="d-flex flex-column fw-500">
                              <a href="<?php echo base_url('hotel-admin-profile') ?>" class="text-dark hover-primary mb-1 fs-16">My Profile</a>
                              <span class="text-fade">Account settings and more</span>
                           </div>
                        </div>
                        <div class="d-flex align-items-center mb-30">
                           <!-- <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                              <span class="icon-Group-chat fs-24"><span class="path1"></span><span class="path2"></span></span>
                           </div> -->
                           <!-- <div class="d-flex flex-column fw-500">
                              <a href="<?php echo base_url('hotel-admin-account-settings') ?>" class="text-dark hover-success mb-1 fs-16">Settings</a>
                              <span class="text-fade">Accout Settings</span>
                           </div> -->
                        </div>
                     </div>
                     <div class="dropdown-divider my-30"></div>
                  </div>
                  <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                     <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                  </div>
                  <div class="ps__rail-y" style="top: 0px; right: 0px; height: 551px;">
                     <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 196px;"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /quick_user_toggle -->
<!-- Control Sidebar -->
<aside class="control-sidebar">
   <div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger" data-toggle="control-sidebar"><i class="ion ion-close text-white"></i></span> </div>
   <!-- Create the tabs -->
   <ul class="nav nav-tabs control-sidebar-tabs">
      <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab"><i class="mdi mdi-message-text"></i></a></li>
      <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i class="mdi mdi-playlist-check"></i></a></li>
   </ul>
   <!-- Tab panes -->
   <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
         <div class="flexbox">
            <a href="javascript:void(0)" class="text-grey">
               <i class="ti-more"></i>
            </a>
            <p>Users</p>
            <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
         </div>
         <div class="lookup lookup-sm lookup-right d-none d-lg-block">
            <input type="text" name="s" placeholder="Search" class="w-p100">
         </div>
         <div class="media-list media-list-hover mt-20">
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-success" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/1.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                  </p>
                  <p>Praesent tristique diam...</p>
                  <span>Just now</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-danger" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/2.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Luke</strong></a>
                  </p>
                  <p>Cras tempor diam ...</p>
                  <span>33 min ago</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-warning" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/3.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Evan</strong></a>
                  </p>
                  <p>In posuere tortor vel...</p>
                  <span>42 min ago</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-primary" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/4.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Evan</strong></a>
                  </p>
                  <p>In posuere tortor vel...</p>
                  <span>42 min ago</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-success" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/1.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                  </p>
                  <p>Praesent tristique diam...</p>
                  <span>Just now</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-danger" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/2.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Luke</strong></a>
                  </p>
                  <p>Cras tempor diam ...</p>
                  <span>33 min ago</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-warning" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/3.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Evan</strong></a>
                  </p>
                  <p>In posuere tortor vel...</p>
                  <span>42 min ago</span>
               </div>
            </div>
            <div class="media py-10 px-0">
               <a class="avatar avatar-lg status-primary" href="#">
                  <img src="<?php echo base_url('images/') ?>/avatar/4.jpg" alt="...">
               </a>
               <div class="media-body">
                  <p class="fs-16">
                     <a class="hover-primary" href="#"><strong>Evan</strong></a>
                  </p>
                  <p>In posuere tortor vel...</p>
                  <span>42 min ago</span>
               </div>
            </div>
         </div>
      </div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
         <div class="flexbox">
            <a href="javascript:void(0)" class="text-grey">
               <i class="ti-more"></i>
            </a>
            <p>Todo List</p>
            <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
         </div>
         <ul class="todo-list mt-20">
            <li class="py-15 px-5 by-1">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_1" class="filled-in">
               <label for="basic_checkbox_1" class="mb-0 h-15"></label>
               <!-- todo text -->
               <span class="text-line">Nulla vitae purus</span>
               <!-- Emphasis label -->
               <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
               <!-- General tools such as edit or delete-->
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_2" class="filled-in">
               <label for="basic_checkbox_2" class="mb-0 h-15"></label>
               <span class="text-line">Phasellus interdum</span>
               <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5 by-1">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_3" class="filled-in">
               <label for="basic_checkbox_3" class="mb-0 h-15"></label>
               <span class="text-line">Quisque sodales</span>
               <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_4" class="filled-in">
               <label for="basic_checkbox_4" class="mb-0 h-15"></label>
               <span class="text-line">Proin nec mi porta</span>
               <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5 by-1">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_5" class="filled-in">
               <label for="basic_checkbox_5" class="mb-0 h-15"></label>
               <span class="text-line">Maecenas scelerisque</span>
               <small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_6" class="filled-in">
               <label for="basic_checkbox_6" class="mb-0 h-15"></label>
               <span class="text-line">Vivamus nec orci</span>
               <small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5 by-1">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_7" class="filled-in">
               <label for="basic_checkbox_7" class="mb-0 h-15"></label>
               <!-- todo text -->
               <span class="text-line">Nulla vitae purus</span>
               <!-- Emphasis label -->
               <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
               <!-- General tools such as edit or delete-->
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_8" class="filled-in">
               <label for="basic_checkbox_8" class="mb-0 h-15"></label>
               <span class="text-line">Phasellus interdum</span>
               <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5 by-1">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_9" class="filled-in">
               <label for="basic_checkbox_9" class="mb-0 h-15"></label>
               <span class="text-line">Quisque sodales</span>
               <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
            <li class="py-15 px-5">
               <!-- checkbox -->
               <input type="checkbox" id="basic_checkbox_10" class="filled-in">
               <label for="basic_checkbox_10" class="mb-0 h-15"></label>
               <span class="text-line">Proin nec mi porta</span>
               <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
               <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
               </div>
            </li>
         </ul>
      </div>
      <!-- /.tab-pane -->
   </div>
</aside>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>



<!-- ✅ jQuery - Use FULL version instead of slim for Flot -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Bootstrap dependencies -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- ✅ Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ✅ Core Vendors -->
<script src="<?php echo base_url('assets/') ?>/js/vendors.min.js"></script>

<!-- ✅ Flot Charts (before dashboard.js to avoid $.plot error) -->
<script src="<?php echo base_url('assets/') ?>vendor_components/Flot/jquery.flot.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.resize.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.categories.js"></script>

<!-- ✅ ApexCharts and ZingChart -->
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/apexcharts-bundle/irregular-data-series.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/zingchart_branded_version/zingchart.min.js"></script>

<!-- ✅ Feather Icons -->
<script src="<?php echo base_url('assets/assets/') ?>icons/feather-icons/feather.min.js"></script>

<!-- ✅ AmCharts (optional if used in dashboard) -->
<script src="<?php echo base_url('assets/') ?>/lib/4/core.js"></script>
<script src="<?php echo base_url('assets/') ?>/lib/4/maps.js"></script>
<script src="<?php echo base_url('assets/') ?>/lib/4/geodata/worldLow.js"></script>
<script src="<?php echo base_url('assets/') ?>/lib/4/themes/dataviz.js"></script>
<script src="<?php echo base_url('assets/') ?>/lib/4/themes/animated.js"></script>

<!-- ✅ SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url('assets/assets/') ?>/vendor_components/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>

<!-- ✅ Chat popup if used -->
<script src="<?php echo base_url('assets/') ?>/js/pages/chat-popup.js"></script>

<!-- ✅ Tresto Template Scripts -->
<script src="<?php echo base_url('assets/') ?>/js/template.js"></script>
<script src="<?php echo base_url('assets/') ?>/js/demo.js"></script>

<!-- ✅ Dashboard JS should come at last -->
<script src="<?php echo base_url('assets/') ?>/js/pages/dashboard.js"></script>


<!-- ✅ DataTables CSS & Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- ✅ DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ Buttons Extension -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
   $(document).ready(function() {
      $('#complex_header').DataTable();

   });

   $(document).ready(function() {
      $('.form-select[multiple]').select2({
         placeholder: "Select Options",
         closeOnSelect: false, // allows multiple selection without closing
         width: '100%',
         allowClear: true
      });
   });
</script>



<!-- ✅ Custom Inline Script -->
<script type="text/javascript">
   $(".switch").trigger('click');
</script>


<script>
   $(document).ready(function() {

      // Get today's date in YYYY-MM-DD format
      var today = new Date().toISOString().split('T')[0];

      // Apply min date to all date inputs EXCEPT those with allow-past-date class
      // $('input[type="date"]').not('.allow-past-date').each(function() {
      //    $(this).attr('min', today);
      // });

   });
</script>

<!-- check room availibility -->


<?php
$CI = &get_instance();
$CI->load->model('Common_model');

$properties = $CI->Common_model->getAllData('hotel_admin', ['hotel_code!=' => '']);
?>


<div class="modal fade" id="availabilitySearchModal" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">

         <div class="modal-header">
            <h5 class="modal-title">Check Room Availability</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>

         <div class="modal-body">
            <div class="row">

               <!-- Hotel -->
               <div class="col-md-4 mb-3">
                  <label>Hotel</label>
                  <select id="_outer_hotel_code" class="form-select">
                     <option value="">Select Hotel</option>

                     <?php if (!empty($properties)) : ?>
                        <?php foreach ($properties as $row) : ?>
                           <option value="<?= $row->hotel_code ?>">
                              <?= $row->hotel_name ?>
                           </option>
                        <?php endforeach; ?>
                     <?php endif; ?>

                  </select>
               </div>


               <!-- Check-in -->
               <div class="col-md-4 mb-3">
                  <label>Check-in Date</label>
                  <input type="date" id="_outer_checkin_date" class="form-control">
                  <span class="text-danger error-text"></span>
               </div>

               <!-- Check-out -->
               <div class="col-md-4 mb-3">
                  <label>Check-out Date</label>
                  <input type="date" id="_outer_checkout_date" class="form-control">
                  <span class="text-danger error-text"></span>
               </div>

            </div>
         </div>

         <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" id="outer_checkAvailabilityBtn">
               Check Availability
            </button>
         </div>

      </div>
   </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal modal-lg" id="_outer_availabilityModal" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Room Availability</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>

         <div class="modal-body">

            <!-- Table -->
            <table class="table table-bordered" id="roomsRateTable">
               <thead class="bg-dark">
                  <tr>
                     <th>Date</th>
                     <th>Room Type</th>
                     <th>Total Rooms</th>
                     <th>Available Rooms</th>
                     <th>In House</th>
                     <th>Confirmed</th>
                     <!-- <th>On Hold</th> -->
                     <!-- <th>Waitlisted</th> -->
                     <!-- <th>Closed Arrival</th>
                     <th>Closed Departure</th>
                     <th>Valid Rate</th>
                     <th>Available</th> -->
                  </tr>
               </thead>
               <tbody></tbody>
            </table>

         </div>

         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
               Close
            </button>
         </div>

      </div>
   </div>
</div>

<div id="processingLoader"
   style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">

   <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">

      <div class="spinner-border text-light" style="width: 4rem; height: 4rem;" role="status"></div>

      <div style="color:white; font-size:20px; margin-top:15px; font-weight:600;">
         Processing...
      </div>

   </div>
</div>




<script>
   $(document).on("click", "#openAvailabilityPopup", function() {
      let modal = new bootstrap.Modal(
         document.getElementById("availabilitySearchModal")
      );

      $("#_outer_hotel_code").val('');
      $("#_outer_checkin_date").val('');
      $("#_outer_checkout_date").val('');
      modal.show();
   });




   $(document).on("click", "#outer_checkAvailabilityBtn", function() {

      let hotelCode = $("#_outer_hotel_code").val();
      let checkin = $("#_outer_checkin_date").val();
      let checkout = $("#_outer_checkout_date").val();

      if (!hotelCode || !checkin || !checkout) {
         alert("Please select hotel, check-in and check-out dates");
         return;
      }

      $.ajax({
         url: "<?= base_url('LeadController/getRoomRateAvailabilityAjax') ?>",
         type: "POST",
         data: {
            chain_code: "00051",
            hotel_code: hotelCode,
            date_arrive: checkin,
            date_depart: checkout,
            adults: 1,
            youths: 0,
            kids: 0,
            number_of_rooms: "",
            price_from: 0,
            price_to: 0,
            room_type_code: ''
         },
         dataType: "json",
         beforeSend: function() {
            $("#processingLoader").show();
         },
         success: function(res) {

            if (res.status && res.data.availability.length > 0) {

               renderRateTable(res.data.availability);

               // close search modal
               bootstrap.Modal
                  .getInstance(document.getElementById("availabilitySearchModal"))
                  .hide();

               // open result modal
               new bootstrap.Modal(
                  document.getElementById("_outer_availabilityModal")
               ).show();

            } else {
               alert("No availability found");
            }
         },
         complete: function() {
            $("#processingLoader").hide();
         }
      });
   });

   function renderRateTable(data) {
      let html = "";

      data.forEach(function(item) {
         html += `
            <tr>
                <td>${item.date}</td>
                <td>${item.room_type_code}</td>
                <td>${item.rooms_total}</td>
                <td>${item.available_rooms}</td>
                <td>${item.rooms_inhouse}</td>
                <td>${item.rooms_confirmed}</td>
                <td>${item.rooms_onhold}</td>
            
            </tr>
        `;
      });

      $("#roomsRateTable tbody").html(html);
   }
</script>