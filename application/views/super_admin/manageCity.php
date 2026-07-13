<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <div class="custom-page-header">
         <div class="header-left">
            <div class="header-icon-box">
               <i class="fa fa-map-marker"></i>
            </div>
            <div class="header-content">
               <h2 class="header-title">Manage City</h2>
               <ol class="custom-breadcrumb">
                  <li>
                     <i class="fa fa-home"></i>
                  </li>
                  <li>Super Admin</li>
                  <li>
                     <i class="fa fa-angle-right"></i>
                  </li>
                  <li>Location Setup</li>
                  <li>
                     <i class="fa fa-angle-right"></i>
                  </li>
                  <li class="active">
                     City Management
                  </li>
               </ol>
            </div>
         </div>
         <div class="header-banner">
            <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
         </div>
      </div>

      <section class="content">
         <div class="row">
            <div class="col-12">
               <div class="box new_table_box">
                  <div class="box-header">
                     <h4 class="box-title">Manage City</h4>
                     <div class="float-right float-end">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-city-modal">
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
                                 <th>Country Name</th>
                                 <th>State Name</th>
                                 <th>City Name</th>
                                 <th>Created Date</th>
                                 <th>Updated Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
</div>

<div class="modal modal-lg new_modal_design" id="city-crud-modal" tabindex="-1" role="dialog" aria-labelledby="city-crud-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="custom-page-header">
            <div class="header-left">
               <div class="header-icon-box">
                  <i class="fa fa-map-marker"></i>
               </div>
               <div class="header-content">
                  <div class="modal-header hotel_modal_header">
                     <h4 class="modal-title" id="crud-modal-title"></h4>
                     <div class="hotel_banner"></div>
                  </div>
                  <ol class="custom-breadcrumb">
                     <li>
                        <i class="fa fa-info-circle"></i>
                        Fill in the details to add or update a city.
                     </li>
                  </ol>
               </div>
            </div>
            <div class="header-banner">
               <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
         </div>
         <div class="modal-body ps-3 pe-3 row">
            <div class="col-sm-6 mb-3">
               <label for="country_id" class="form-label">Select Country <span class="required-asterisk">*</span></label>
               <select class="form-control" name="country_id" id="country_id">
                  <option value="">Select Country</option>
                  <?php foreach ($countries as $each) { ?>
                     <option value="<?php echo encrypt_id($each->country_id) ?>"><?php echo $each->country_name ?></option>
                  <?php } ?>
               </select>
               <span id="country_id_error" class="validation text-danger"></span>
            </div>
            <div class="col-sm-6 mb-3">
               <label for="state_id" class="form-label">Select State <span class="required-asterisk">*</span></label>
               <select class="form-control" name="state_id" id="state_id" disabled>
                  <option value="">Select State</option>
               </select>
               <span id="state_id_error" class="validation text-danger"></span>
            </div>
            <div class="col-sm-6 mb-3">
               <label for="city_name" class="form-label">City Name <span class="required-asterisk">*</span></label>
               <input class="form-control" type="text" id="city_name" required placeholder="Indore">
               <span id="city_name_error" class="validation text-danger"></span>
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-start">
            <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
            <button type="button" id="action-btn" class="btn btn-primary" data-key="">Save changes</button>
         </div>
      </div>
   </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
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

   <?php if ($this->session->flashdata('city_success_msg') != "") { ?>
      toastr.success('<?php echo $this->session->flashdata('city_success_msg'); ?>')
   <?php $this->session->set_flashdata('city_success_msg', '');
   } ?>
</script>
<script type="text/javascript">
   var data_table = $('#server-side-data-table').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      searching: true,
      columnDefs: [
         { targets: 6, orderable: false }
      ],
      ajax: {
         url: '<?php echo base_url('get-cities-table') ?>',
         type: 'POST',
         data: function(d) {
            d[window.CSRF.name] = window.CSRF.hash;
         },
         drawCallback: function(setting) {
            if (setting.json && setting.json.csrfHash) {
               window.CSRF.hash = setting.json.csrfHash;
            }
         },
         dataSrc: function(json) {
            if (json.csrfHash) {
               window.CSRF.hash = json.csrfHash;
            }
            return json.data;
         }
      }
   });

   function renderCityTable() {
      data_table.draw();
   }

   function resetCityForm() {
      $('#country_id').val('');
      $('#state_id').prop('disabled', true).html('<option value="">Select State</option>').val('');
      $('#city_name').val('');
      $('.validation').text('');
   }

   function validateCityForm() {
      var isValid = true;
      var cityName = $.trim($('#city_name').val());
      var countryId = $('#country_id').val();
      var stateId = $('#state_id').val();

      if (!countryId) {
         $('#country_id_error').text('Please select country');
         isValid = false;
      } else {
         $('#country_id_error').text('');
      }

      if (!stateId) {
         $('#state_id_error').text('Please select state');
         isValid = false;
      } else {
         $('#state_id_error').text('');
      }

      if (cityName == '') {
         $('#city_name_error').text('Please enter city name');
         isValid = false;
      } else {
         $('#city_name_error').text('');
         $('#city_name').val(cityName);
      }

      return isValid;
   }

   function ensureSelectedOption($select, value, label) {
      if (!value || $select.find('option[value="' + value + '"]').length) {
         return;
      }

      $select.append($('<option></option>').val(value).text(label));
   }

   function loadStatesByCountry(countryId, selectedStateId) {
      $('#state_id').prop('disabled', true).html('<option value="">Loading...</option>').val('');

      if (!countryId) {
         $('#state_id').prop('disabled', true).html('<option value="">Select State</option>').val('');
         return;
      }

      $.ajax({
         url: '<?php echo base_url('city/get-states-by-country') ?>',
         type: 'POST',
         dataType: 'JSON',
         data: {
            country_id: countryId,
            selected_state_id: selectedStateId || '',
            [window.CSRF.name]: window.CSRF.hash
         },
         success: function(response) {
            if (response.csrfHash) {
               window.CSRF.hash = response.csrfHash;
            }
            $('#state_id').html('<option value="">Select State</option>');

            if (response.data && response.data.length > 0) {
               $.each(response.data, function(index, state) {
                  $('#state_id').append('<option value="' + state.state_id + '">' + state.state_name + '</option>');
               });
            }

            if (selectedStateId) {
               $('#state_id').val(selectedStateId);
            } else {
               $('#state_id').val('');
            }

            $('#state_id').prop('disabled', false);
         },
         error: function() {
            $('#state_id').prop('disabled', true).html('<option value="">Select State</option>').val('');
            toastr.error('Failed to fetch states. Please try again.');
         }
      });
   }

   $('#country_id').on('change', function() {
      $('#country_id_error').text('');
      loadStatesByCountry($(this).val(), '');
   });

   $('#state_id').on('change', function() {
      $('#state_id_error').text('');
   });

   $('#city_name').on('input', function() {
      if ($.trim($(this).val()) != '') {
         $('#city_name_error').text('');
      }
   });

   $('#open-city-modal').click(function(e) {
      e.preventDefault();
      resetCityForm();
      $('#crud-modal-title').text('Add New City');
      $('#action-btn').text('Create').attr('data-key', '').prop('disabled', false);
      $('#city-crud-modal').modal('show');
   });

   $(document).on('click', '#action-btn', function(e) {
      e.preventDefault();

      if (!validateCityForm()) {
         return;
      }

      var $button = $(this);
      var key = $button.attr('data-key');
      var buttonText = $button.text();
      var formData = new FormData();

      formData.append('city_name', $('#city_name').val());
      formData.append('country_id', $('#country_id').val());
      formData.append('state_id', $('#state_id').val());
      formData.append(window.CSRF.name, window.CSRF.hash);

      if (key != '') {
         formData.append('record_id', key);
      }

      $.ajax({
         url: '<?php echo base_url();?>' + ((key == '') ? 'insert-city' : 'update-city'),
         type: 'POST',
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'JSON',
         beforeSend: function() {
            $button.prop('disabled', true).text((key != '') ? 'Updating..' : 'Saving..');
         },
         success: function(response) {
            if (response.csrfHash) {
               window.CSRF.hash = response.csrfHash;
            }

            if (response.status) {
               toastr.success(response.message);
               $('#city-crud-modal').modal('hide');
               renderCityTable();
            } else {
               toastr.error(response.message || 'Failed to save city');
            }
         },
         error: function() {
            toastr.error('Something went wrong');
         },
         complete: function() {
            $button.prop('disabled', false).text(buttonText).attr('data-key', key);
         }
      });
   });

   $(document).on('click', '.edit-city', function(e) {
      e.preventDefault();
      resetCityForm();

      $.ajax({
         url: '<?php echo base_url('edit-city') ?>',
         type: 'POST',
         dataType: 'JSON',
         data: {
            id: $(this).attr('data-record_id'),
            [window.CSRF.name]: window.CSRF.hash
         },
         success: function(res) {
            if (res.csrfHash) {
               window.CSRF.hash = res.csrfHash;
            }

            if (res.status) {
               $('#crud-modal-title').text('Edit City');
               $('#city_name').val(res.data.city_name);
               ensureSelectedOption($('#country_id'), res.data.country_id, res.data.country_name);
               $('#country_id').val(res.data.country_id);
               loadStatesByCountry(res.data.country_id, res.data.state_id);
               $('#action-btn').text('Update').attr('data-key', res.id).prop('disabled', false);
               $('#city-crud-modal').modal('show');
            } else {
               toastr.error(res.message || 'Unable to load city details');
            }
         },
         error: function() {
            toastr.error('Something went wrong');
         }
      });
   });

   $(document).on('click', '.delete-city', function() {
      var $button = $(this);

      Swal.fire({
         title: 'Are you sure?',
         text: 'You will not be able to recover this record!',
         icon: 'question',
         showCancelButton: true,
         showCloseButton: true,
         confirmButtonText: 'Yes Delete it',
         denyButtonText: 'Cancel'
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: '<?php echo base_url('delete-city') ?>',
               method: 'POST',
               dataType: 'JSON',
               data: {
                  id: $button.attr('data-record_id'),
                  [window.CSRF.name]: window.CSRF.hash
               },
               success: function(res) {
                  if (res.csrfHash) {
                     window.CSRF.hash = res.csrfHash;
                  }

                  if (res.status) {
                     toastr.success(res.message);
                     renderCityTable();
                  } else {
                     toastr.error(res.message || 'Failed to delete city');
                  }
               },
               error: function() {
                  toastr.error('Something went wrong');
               }
            });
         }
      });
   });

</script>
