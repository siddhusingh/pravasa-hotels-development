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
$login_id = $this->session->userdata('super_admin_session')['id'];
$profile_data = $this->Comman_model->get_single_record('super_admin', ['id' => $login_id]);


?>



<div class="modal modal-right fade profile_drawer" id="quick_user_toggle">

    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-body">
                     <div class="d-flex align-items-center justify-content-between pb-30">
                        <h4 class="m-0">User Profile

                        </h4>
                        <a href="#" class="btn btn-icon close_bnt  btn-sm no-shadow" data-bs-dismiss="modal">
                           <span class="fa fa-close"></span>
                        </a>
                     </div>
                     <div>
                        <div class="profile-avatar">
                           <!-- <img src="<?php echo base_url('assets/images/user.png'); ?>" alt="profile img " loading="lazy> -->
                            <img src="https://img.magnific.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740&q=80" alt="profile img " loading="lazy" class="img-fluid rounded-circle">
                        </div>
                        <div class="profile-user-card">

                           <div class="profile-user-info">
                              <h5 class="mb-0"><?php echo $profile_data->full_name; ?></h5>
                              <p class="my-5 text-fade"><?php if ($profile_data->user_role == 1) {
                                                            echo "Super Admin";
                                                         } else {
                                                            echo "Senior Manager";
                                                         } ?></p>
                              <p><a href="javascript:void(0)"><span class="icon-Mail-notification me-5 "><span class="path1"></span><span class="path2"></span></span> <?php echo $profile_data->email; ?></a></p>
                              <p>
                                 <a href="javascript:void(0)"><span class="fa fa-phone me-5 "><span class="path1"></span><span class="path2"></span></span> <?php echo $profile_data->phone; ?></a>
                              </p>

                              <a class="btn close_bnt  btn-sm mt-5" href="<?php echo base_url('super-admin-sign-out') ?>">
                                 <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="dropdown-divider my-30"></div>
                     <div>
                        <div class="profile-menu-item">
                           <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                              <span class="icon-Library fs-24"><span class="path1"></span><span class="path2"></span></span>
                           </div>
                           <div class="d-flex flex-column fw-500">
                              <a href="<?php echo base_url('super-admin-profile') ?>" class="text-dark hover-primary mb-1 fs-16">My Profile</a>
                              <span class="text-fade">Account settings and more</span>
                           </div>
                        </div>
                        <div class="profile-menu-item">
                           <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                              <span class="icon-Group-chat fs-24"><span class="path1"></span><span class="path2"></span></span>
                           </div> 
                           <div class="d-flex flex-column fw-500">
                              <a href="<?php echo base_url('super-admin-account-settings') ?>" class="text-dark hover-success mb-1 fs-16">Settings</a>
                              <span class="text-fade">Accout Settings</span>
                           </div>
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


<!-- ✅ Bootstrap dependencies -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- ✅ Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ✅ Core Vendors -->
<script src="<?php echo base_url('assets/') ?>/js/vendors.min.js"></script>

<!-- ✅ Flot Charts (before dashboard.js to avoid $.plot error) -->
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.resize.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>vendor_components/Flot/jquery.flot.categories.js"></script>



<!-- ✅ Feather Icons -->
<script src="<?php echo base_url('assets/assets/') ?>icons/feather-icons/feather.min.js"></script>

<!-- ✅ AmCharts (optional if used in dashboard) -->
<!-- <script src="<?php echo base_url('assets') ?>/lib/4/core.js"></script>
<script src="<?php echo base_url('assets') ?>/lib/4/maps.js"></script>
<script src="<?php echo base_url('assets') ?>/lib/4/charts.js"></script>
<script src="<?php echo base_url('assets') ?>/lib/4/themes/animated.js"></script>
<script src="<?php echo base_url('assets') ?>/lib/4/geodata/worldLow.js"></script>
<script src="<?php echo base_url('assets') ?>/lib/4/themes/dataviz.js"></script> -->

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>

<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>

<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/dataviz.js"></script>


<!-- ✅ SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url('assets/assets/') ?>/vendor_components/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo base_url('assets/assets/') ?>/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>

<!-- ✅ Chat popup if used -->

<!-- ✅ Tresto Template Scripts -->
<script src="<?php echo base_url('assets/') ?>/js/template.js"></script>
<script src="<?php echo base_url('assets/') ?>/js/demo.js"></script>

<!-- ✅ Dashboard JS should come at last -->
<script src="<?php echo base_url('assets/') ?>/js/pages/dashboard.js"></script>

<!-- ✅ Custom Inline Script -->
<script type="text/javascript">
   $(".switch").trigger('click');
</script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

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


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>
<script>
function initMultiCheckbox(selector) {

    $(selector).multiselect({

        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%',
        maxHeight: 300,
        nonSelectedText: 'Select Person',
        filterPlaceholder: 'Search Person...',
        selectAllText: 'Select All',
        allSelectedText: 'All Selected',

        buttonText: function(options, select) {

    if (options.length === 0) {
        return 'Select Person';
    }

    if (options.length > 3) {
        return options.length + ' Persons Selected';
    }

    var labels = [];

    options.each(function() {
        labels.push($(this).text());
    });

    return labels.join(', ');
}

    });

}
</script>




</body>

</html>

<script type="text/javascript">
   $(".switch").trigger('click')
</script>


<script>
   $(document).ready(function() {

      // Get today's date in YYYY-MM-DD format
      var today = new Date().toISOString().split('T')[0];

      // Apply min date to all date inputs EXCEPT those with allow-past-date class
      // $('input[type="date"]').not('.allow-past-date').each(function() {
      //    $(this).attr('min', today);
      // });



      // setInterval(function() {

      //    var agentNumber = "<?php echo $profile_data->phone; ?>"; // dynamic later
      //    let base_url = "<?php echo base_url(); ?>";

      //    $.ajax({
      //       url: base_url + 'Ivr/get_latest_call',
      //       type: 'GET',
      //       data: {
      //          agent_number: agentNumber
      //       },
      //       success: function(res) {

      //          let response = JSON.parse(res);

      //          if (response.status === 'success') {

      //             let call = response.data;

      //             // Redirect with correct params
      //             window.location.href = base_url + "add-lead?" +
      //                "phone=" + encodeURIComponent(call.clid) +
      //                "&property_id=" + encodeURIComponent(response.property_id) +
      //                "&department_id=" + encodeURIComponent(response.department_id);
      //          }
      //       }
      //    });

      // }, 3000); // every 3 seconds







   });
</script>

<!-- check room availibility -->


<?php
$CI = &get_instance();
$CI->load->model('Common_model');

$properties = $CI->Common_model->getAllData('hotel_admin', ['hotel_code!=' => '']);
?>


<div class="modal fade" id="availabilitySearchModal" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered modal-lg">
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
   <div class="modal-dialog modal-dialog-centered modal-lg">
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


<!-- Select2 CSS -->

<!-- jQuery (already you have, else add) -->

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- <script>
   $(document).ready(function() {
      $('select').select2({
         theme: 'bootstrap-5',

         width: '100%'
      });
   });
</script> -->
