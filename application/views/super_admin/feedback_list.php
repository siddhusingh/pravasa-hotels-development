<!-- Content Wrapper. Contains page content -->
<style>
   .feedback-filter-row .select2-container {
      width: 100% !important;
   }

   .feedback-filter-row .select2-container .select2-selection--single {
      box-sizing: border-box !important;
      width: 100%;
   }

   .feedback-filter-row .select2-container .select2-selection--single .select2-selection__rendered {
      padding-left: 14px;
      padding-right: 34px;
   }
</style>
<div class="content-wrapper">
   <div class="container-full">

      <?php if (in_array($module, ['restaurant', 'banquet', 'room'], true)) { ?>
         <div class="custom-page-header">
            <div class="header-left">
               <div class="header-icon-box"><i class="fa fa-comments"></i></div>
               <div class="header-content">
                  <h2 class="header-title"><?= ucfirst($module) ?> Feedback</h2>
                  <ol class="custom-breadcrumb">
                     <li><i class="fa fa-home"></i></li><li>Super Admin</li>
                     <li><i class="fa fa-angle-right"></i></li><li>Feedback</li>
                     <li><i class="fa fa-angle-right"></i></li><li class="active"><?= ucfirst($module) ?> Feedback</li>
                  </ol>
               </div>
            </div>
            <div class="header-banner"><img src="<?= base_url('assets/new_img-add.png'); ?>" alt=""></div>
         </div>
      <?php } else { ?>
         <div class="content-header"><h4 class="page-title"><?= isset($page_title) ? $page_title : 'Feedback Listing' ?></h4></div>
      <?php } ?>

      <section class="content">

         <!-- FILTERS -->
         <div class="row mb-3 feedback-filter-row">
            <div class="col-md-4">
               <label><?= ($module === 'restaurant') ? 'Restaurant' : 'Property' ?></label>
               <select id="filter_item" class="form-control">
                  <option value=""><?= ($module === 'restaurant') ? 'All Restaurants' : 'All Properties' ?></option>
                  <?php if (!empty($items)) {
                     foreach ($items as $item) {
                        $value = ($module === 'restaurant') ? $item->id : $item->hotel_id;
                        $label = ($module === 'restaurant') ? $item->restaurant_name : $item->hotel_name;
                  ?>
                        <option value="<?= $value ?>"><?= $label ?></option>
                  <?php }
                  } ?>
               </select>
            </div>

            <?php if ($module === 'restaurant') { ?>
               <div class="col-md-2">
                  <label>Rating</label>
                  <select id="filter_rating" class="form-control">
                     <option value="">All</option>
                     <option value="5">5 ⭐</option>
                     <option value="4">4 ⭐</option>
                     <option value="3">3 ⭐</option>
                     <option value="2">2 ⭐</option>
                     <option value="1">1 ⭐</option>
                  </select>
               </div>
            <?php } ?>

            <div class="col-md-3">
               <label>Start Date</label>
               <input type="date" id="start_date" class="form-control">
            </div>

            <div class="col-md-3">
               <label>End Date</label>
               <input type="date" id="end_date" class="form-control">
            </div>

            <div class="col-md-1 d-flex align-items-end">
               <button id="applyFilter" class="btn btn-primary w-100">Apply</button>
            </div>
         </div>

         <!-- TABLE -->
         <div class="box<?= in_array($module, ['restaurant', 'banquet', 'room'], true) ? ' new_table_box' : '' ?>">
            <?php if (in_array($module, ['restaurant', 'banquet', 'room'], true)) { ?><div class="box-header"><h4 class="box-title"><?= ucfirst($module) ?> Feedback List</h4></div><?php } ?>
            <div class="box-body">
               <div class="table-responsive">
                  <table id="<?= $module ?>-feedback-data-table" class="text-fade table table-bordered display" style="width:100%">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th><?= ($module === 'restaurant') ? 'Restaurant' : 'Property' ?></th>
                           <th>Guest</th>
                           <th>Mobile</th>
                           <?php if ($module === 'restaurant') { ?>
                              <th>Rating</th>
                              <th>Remark</th>
                           <?php } elseif ($module === 'banquet') { ?>
                              <th>Overall</th>
                              <th>Reservation</th>
                              <th>Event</th>
                              <th>Decor</th>
                              <th>Lighting</th>
                              <th>Food / Beverage</th>
                              <th>Staff</th>
                              <th>Recommend</th>
                              <th>Comment</th>
                           <?php } else { ?>
                              <th>Room Experience</th>
                              <th>Food Quality</th>
                              <th>Staff Service</th>
                              <th>Cleanliness</th>
                              <th>Satisfaction</th>
                              <th>Recommend</th>
                              <th>Comment</th>
                           <?php } ?>
                           <th>Date</th>
                        </tr>
                     </thead>
                     <tbody id="feedback_tbody"></tbody>
                  </table>
               </div>
            </div>
         </div>

      </section>
   </div>
</div>
<!-- /.content-wrapper -->

<?php if (!in_array($module, ['restaurant', 'banquet', 'room'], true)) { ?>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<?php } ?>
<script>
$(document).ready(function() {
   if (!$.fn.select2) {
      return;
   }

   $('#filter_item, #filter_rating').each(function() {
      const $select = $(this);

      if (!$select.length || $select.hasClass('select2-hidden-accessible')) {
         return;
      }

      $select.select2({
         width: '100%',
         minimumResultsForSearch: 0
      });

      const controlHeight = Math.round($('#start_date').outerHeight()) || 46;
      const innerHeight = Math.max(controlHeight - 2, 1);
      const $container = $select.next('.select2-container');
      const $selection = $container.find('.select2-selection--single');
      const $rendered = $selection.find('.select2-selection__rendered');
      const $arrow = $selection.find('.select2-selection__arrow');

      [$container, $selection].forEach(function($element) {
         if ($element.length) {
            $element[0].style.setProperty('height', controlHeight + 'px', 'important');
            $element[0].style.setProperty('min-height', controlHeight + 'px', 'important');
            $element[0].style.setProperty('max-height', controlHeight + 'px', 'important');
         }
      });

      if ($rendered.length) {
         $rendered[0].style.setProperty('height', innerHeight + 'px', 'important');
         $rendered[0].style.setProperty('line-height', innerHeight + 'px', 'important');
         $rendered[0].style.setProperty('padding-top', '0', 'important');
         $rendered[0].style.setProperty('padding-bottom', '0', 'important');
      }

      if ($arrow.length) {
         $arrow[0].style.setProperty('height', innerHeight + 'px', 'important');
         $arrow[0].style.setProperty('top', '0', 'important');
      }
   });
});

<?php if (in_array($module, ['restaurant', 'banquet', 'room'], true)) { ?>
   var feedbackTable = $('#<?= $module ?>-feedback-data-table').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      searching: true,
      order: [[<?= ($module === 'restaurant') ? 6 : (($module === 'banquet') ? 13 : 11) ?>, 'desc']],
      ajax: {
         url: '<?= base_url('feedback/' . $module . '_feedback_list') ?>',
         type: 'POST',
         data: function(d) {
            <?php if ($module === 'restaurant') { ?>
            d.res_id = $('#filter_item').val();
            d.rating = $('#filter_rating').val();
            <?php } else { ?>
            d.property_id = $('#filter_item').val();
            <?php } ?>
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d[window.CSRF.name] = window.CSRF.hash;
         },
         dataSrc: function(json) {
            if (json.csrfHash) window.CSRF.hash = json.csrfHash;
            return json.data;
         }
      },
      columnDefs: [{ orderable: false, targets: [0] }]
   });

   $('#applyFilter').on('click', function() {
      feedbackTable.draw();
   });
<?php } else { ?>
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

   $(document).ready(function() {
      loadFeedback();

      $('#applyFilter').click(function() {
         loadFeedback();
      });

      function buildRow(index, row) {
         let cols = '<tr>';
         cols += '<td>' + index + '</td>';
         cols += '<td>' + (row.restaurant_name || row.hotel_name || 'N/A') + '</td>';
         cols += '<td>' + (row.guest_name || 'N/A') + '</td>';
         cols += '<td>' + (row.mobile || 'N/A') + '</td>';

         if ('<?= $module ?>' === 'restaurant') {
            let stars = '';
            for (let s = 1; s <= 5; s++) {
               stars += (s <= row.rating) ? '⭐' : '☆';
            }
            cols += '<td>' + stars + '</td>';
            cols += '<td>' + (row.remark || '-') + '</td>';
         } else if ('<?= $module ?>' === 'banquet') {
            cols += '<td>' + (row.overall_experience || '-') + '</td>';
            cols += '<td>' + (row.reservation_experience || '-') + '</td>';
            cols += '<td>' + (row.event_experience || '-') + '</td>';
            cols += '<td>' + (row.decor_ambience || '-') + '</td>';
            cols += '<td>' + (row.lighting_air_condition || '-') + '</td>';
            cols += '<td>' + (row.food_beverage_quality || '-') + '</td>';
            cols += '<td>' + (row.staff_service || '-') + '</td>';
            cols += '<td>' + (row.recommendation_score || '-') + '</td>';
            cols += '<td>' + (row.comment || '-') + '</td>';
         } else {
            cols += '<td>' + (row.room_stay_experience || '-') + '</td>';
            cols += '<td>' + (row.food_quality || '-') + '</td>';
            cols += '<td>' + (row.staff_service || '-') + '</td>';
            cols += '<td>' + (row.cleanliness_hygiene || '-') + '</td>';
            cols += '<td>' + (row.overall_satisfaction || '-') + '</td>';
            cols += '<td>' + (row.recommendation_score || '-') + '</td>';
            cols += '<td>' + (row.comment || '-') + '</td>';
         }

         cols += '<td>' + formatDate(row.created_at) + '</td>';
         cols += '</tr>';
         return cols;
      }

      function getListUrl() {
         switch ('<?= $module ?>') {
            case 'banquet':
               return '<?= base_url('feedback/banquet_feedback_list') ?>';
            case 'room':
               return '<?= base_url('feedback/room_feedback_list') ?>';
            default:
               return '<?= base_url('feedback/restaurant_feedback_list') ?>';
         }
      }

      function loadFeedback() {
         const url = getListUrl();
         const params = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val()
         };

         if ('<?= $module ?>' === 'restaurant') {
            params.res_id = $('#filter_item').val();
            params.rating = $('#filter_rating').val();
         } else {
            params.property_id = $('#filter_item').val();
         }

         $.ajax({
            url: url,
            type: 'GET',
            data: params,
            dataType: 'json',
            beforeSend: function() {
               $('#feedback_tbody').html('<tr><td colspan="<?= ($module === 'restaurant') ? 7 : 12 ?>">Loading...</td></tr>');
            },
            success: function(res) {
               let html = '';
               if (res.data && res.data.length > 0) {
                  res.data.forEach(function(row, index) {
                     html += buildRow(index + 1, row);
                  });
               } else {
                  html = '<tr><td colspan="<?= ($module === 'restaurant') ? 7 : 12 ?>">No data found</td></tr>';
               }
               $('#feedback_tbody').html(html);
            },
            error: function() {
               $('#feedback_tbody').html('<tr><td colspan="<?= ($module === 'restaurant') ? 7 : 12 ?>">Unable to load data</td></tr>');
            }
         });
      }

      function formatDate(dateStr) {
         if (!dateStr) return '-';
         const d = new Date(dateStr);
         if (isNaN(d.getTime())) return dateStr;
         return d.toLocaleString('en-GB', { hour12: false });
      }
   });
<?php } ?>
</script>
