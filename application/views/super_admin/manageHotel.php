<style>
   #hotel-crud-modal .select2-container {
      width: 100% !important;
   }

   #hotel-crud-modal .select2-container .select2-selection--single {
      height: 46px !important;
      border: 1px solid #d9d9d9 !important;
      border-radius: 8px !important;
      box-shadow: 0 2px 5px rgb(0 0 0 / 18%);
   }

   #hotel-crud-modal .select2-selection--single .select2-selection__rendered {
      line-height: 44px !important;
      padding-left: 13px !important;
      padding-right: 35px !important;
   }

   #hotel-crud-modal .select2-selection--single .select2-selection__arrow {
      height: 44px !important;
   }

   .hotel-select2-dropdown .select2-search__field {
      height: 34px !important;
      min-height: 34px !important;
      padding: 5px 9px !important;
      border: 1px solid #d9d9d9 !important;
      border-radius: 5px !important;
      box-shadow: none !important;
   }

   #server-side-data-table .hotel-facebook-column {
      width: 170px !important;
      max-width: 170px;
   }

   .hotel-facebook-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      max-width: 100%;
      white-space: nowrap;
      color: #4267b2;
      font-weight: 500;
   }

   .hotel-facebook-link:hover {
      color: #2f4f8f;
      text-decoration: underline;
   }

   .hotel-table-ellipsis {
      display: block;
      max-width: 150px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
   }
</style>

<div class="content-wrapper">
   <div class="container-full">
      <div class="custom-page-header">
         <div class="header-left">
            <div class="header-icon-box">
               <i class="fa fa-hotel"></i>
            </div>
            <div class="header-content">
               <h2 class="header-title">Manage Hotels</h2>
               <ol class="custom-breadcrumb">
                  <li><i class="fa fa-home"></i></li>
                  <li>Super Admin</li>
                  <li><i class="fa fa-angle-right"></i></li>
                  <li>Property Management</li>
                  <li><i class="fa fa-angle-right"></i></li>
                  <li class="active">Hotel Management</li>
               </ol>
            </div>
         </div>
         <div class="header-banner">
            <img src="<?php echo base_url('assets/new_img/hotel_img.png'); ?>" alt="">
         </div>
      </div>

      <section class="content">
         <div class="row">
            <div class="col-12">
               <div class="box new_table_box">
                  <div class="box-header">
                     <h4 class="box-title">Manage Hotels</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-hotel-modal">Add +</button>
                     </div>
                  </div>
                  <div class="box-body">
                     <div class="table-responsive">
                        <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                           <thead>
                               <tr class="text-dark">
                                 <th>Sr. No.</th>
                                 <th>Hotel Code</th>
                                 <th>Hotel Name</th>
                                 <th>Country Name</th>
                                 <th>State Name</th>
                                 <th>City Name</th>
                                 <th>Facebook</th>
                                 <th>Lead Link</th>
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

<div class="modal modal-lg new_modal_design" id="hotel-crud-modal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="custom-page-header">
            <div class="header-left">
               <div class="header-icon-box">
                  <i class="fa fa-hotel"></i>
               </div>
               <div class="header-content">
                  <div class="modal-header hotel_modal_header">
                     <h4 class="modal-title" id="crud-modal-title"></h4>
                     <div class="hotel_banner"></div>
                  </div>
                  <ol class="custom-breadcrumb">
                     <li>
                        <i class="fa fa-info-circle"></i>
                        Fill in the details to add or update a hotel.
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
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Select Country <span class="required-asterisk">*</span></label>
                  <select class="form-control hotel-location-select" id="country_id">
                     <option value="">Select Country</option>
                     <?php foreach ($countries as $each) { ?>
                        <option value="<?php echo encrypt_id($each->country_id) ?>"><?php echo $each->country_name ?></option>
                     <?php } ?>
                  </select>
                  <span id="country_id_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Select State <span class="required-asterisk">*</span></label>
                  <select class="form-control hotel-location-select" id="state_id" disabled>
                     <option value="">Select State</option>
                  </select>
                  <span id="state_id_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Select City <span class="required-asterisk">*</span></label>
                  <select class="form-control hotel-location-select" id="city_id" disabled>
                     <option value="">Select City</option>
                  </select>
                  <span id="city_id_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Hotel Name <span class="required-asterisk">*</span></label>
                  <input class="form-control" type="text" id="hotel_name" placeholder="Hotel Name">
                  <span id="hotel_name_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Hotel Code <span class="required-asterisk">*</span></label>
                  <input class="form-control" type="text" id="hotel_code" placeholder="Hotel Code">
                  <span id="hotel_code_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Hotel Image</label>
                  <input type="file" class="form-control" id="hotel_image" accept="image/*">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Phone <span class="required-asterisk">*</span></label>
                  <input class="form-control" type="number" id="hotel_contact" placeholder="Phone">
                  <span id="hotel_contact_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Facebook Page ID <span class="required-asterisk">*</span></label>
                  <input class="form-control" type="text" id="facebook_page_id" placeholder="Facebook Page ID">
                  <span id="facebook_page_id_error" class="validation text-danger"></span>
               </div>
            </div>
            <div class="mb-3">
               <label class="form-label">Full Address</label>
               <textarea class="form-control" rows="4" id="hotel_address"></textarea>
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
      closeButton: true,
      positionClass: 'toast-top-right',
      timeOut: '5000',
      showMethod: 'fadeIn',
      hideMethod: 'fadeOut'
   };
</script>
<script type="text/javascript">
   var data_table = $('#server-side-data-table').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      searching: true,
      autoWidth: false,
      columnDefs: [
         { targets: 6, width: '170px', className: 'hotel-facebook-column' },
         { targets: [7, 8], orderable: false }
      ],
      ajax: {
         url: '<?php echo base_url('get-hotels-table') ?>',
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

   function ensureSelectedOption($select, value, label) {
      if (value && !$select.find('option[value="' + value + '"]').length) {
         $select.append($('<option></option>').val(value).text(label));
      }
   }

   function refreshHotelSelect($select, value) {
      $select.val(value || '');
      if ($select.hasClass('select2-hidden-accessible')) {
         $select.trigger('change.select2');
      }
   }

   function initHotelSelect2() {
      if (!$.fn.select2) {
         return;
      }

      $('.hotel-location-select').each(function() {
         var $select = $(this);
         if ($select.hasClass('select2-hidden-accessible')) {
            return;
         }

         $select.select2({
            width: '100%',
            placeholder: $select.find('option:first').text(),
            allowClear: false,
            dropdownParent: $('#hotel-crud-modal'),
            dropdownCssClass: 'hotel-select2-dropdown'
         });
      });
   }

   function bindHotelLocationEvents() {
      $(document)
         .off('change.hotelLocation', '#country_id')
         .on('change.hotelLocation', '#country_id', function() {
            $('#country_id_error').text('');
            loadStatesByCountry($(this).val(), '');
         })
         .off('change.hotelLocation', '#state_id')
         .on('change.hotelLocation', '#state_id', function() {
            $('#state_id_error').text('');
            loadCitiesByState($(this).val(), '');
         });
   }

   function resetHotelForm() {
      refreshHotelSelect($('#country_id'), '');
      $('#state_id').prop('disabled', true).html('<option value="">Select State</option>');
      refreshHotelSelect($('#state_id'), '');
      $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
      refreshHotelSelect($('#city_id'), '');
      $('#hotel_name, #hotel_code, #hotel_contact, #facebook_page_id, #hotel_address').val('');
      $('#hotel_image').val('');
      $('.validation').text('');
   }

   function validateHotelForm() {
      var rules = {
         country_id: 'Please select country',
         state_id: 'Please select state',
         city_id: 'Please select city',
         hotel_name: 'Please enter hotel name',
         hotel_code: 'Please enter hotel code',
         hotel_contact: 'Please enter phone number',
         facebook_page_id: 'Please enter Facebook page ID'
      };
      var isValid = true;

      $.each(rules, function(field, message) {
         var value = $.trim($('#' + field).val());
         if (value === '') {
            $('#' + field + '_error').text(message);
            isValid = false;
         } else {
            $('#' + field + '_error').text('');
            $('#' + field).val(value);
         }
      });

      return isValid;
   }

   function loadStatesByCountry(countryId, selectedStateId) {
      $('#state_id').prop('disabled', true).html('<option value="">Loading...</option>');
      refreshHotelSelect($('#state_id'), '');
      $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
      refreshHotelSelect($('#city_id'), '');

      if (!countryId) {
         $('#state_id').prop('disabled', true).html('<option value="">Select State</option>');
         refreshHotelSelect($('#state_id'), '');
         return null;
      }

      return $.ajax({
         url: '<?php echo base_url('hotel/get-states-by-country') ?>',
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

            if (!response.status) {
               $('#state_id').prop('disabled', true).html('<option value="">Select State</option>');
               refreshHotelSelect($('#state_id'), '');
               toastr.error(response.message || 'Unable to fetch states');
               return;
            }

            var states = response.data || [];
            $('#state_id').html('<option value="">Select State</option>');
            $.each(states, function(index, state) {
               $('#state_id').append($('<option></option>').val(state.state_id).text(state.state_name));
            });
            $('#state_id').prop('disabled', states.length === 0);
            refreshHotelSelect($('#state_id'), selectedStateId);
         },
         error: function() {
            $('#state_id').prop('disabled', true).html('<option value="">Select State</option>');
            refreshHotelSelect($('#state_id'), '');
            toastr.error('Unable to fetch states');
         }
      });
   }

   function loadCitiesByState(stateId, selectedCityId) {
      $('#city_id').prop('disabled', true).html('<option value="">Loading...</option>');
      refreshHotelSelect($('#city_id'), '');

      if (!stateId) {
         $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
         refreshHotelSelect($('#city_id'), '');
         return null;
      }

      return $.ajax({
         url: '<?php echo base_url('hotel/get-cities-by-state') ?>',
         type: 'POST',
         dataType: 'JSON',
         data: {
            state_id: stateId,
            selected_city_id: selectedCityId || '',
            [window.CSRF.name]: window.CSRF.hash
         },
         success: function(response) {
            if (response.csrfHash) {
               window.CSRF.hash = response.csrfHash;
            }

            if (!response.status) {
               $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
               refreshHotelSelect($('#city_id'), '');
               toastr.error(response.message || 'Unable to fetch cities');
               return;
            }

            var cities = response.data || [];
            $('#city_id').html('<option value="">Select City</option>');
            $.each(cities, function(index, city) {
               $('#city_id').append($('<option></option>').val(city.city_id).text(city.city_name));
            });
            $('#city_id').prop('disabled', cities.length === 0);
            refreshHotelSelect($('#city_id'), selectedCityId);
         },
         error: function() {
            $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
            refreshHotelSelect($('#city_id'), '');
            toastr.error('Unable to fetch cities');
         }
      });
   }

   $('#city_id, #hotel_name, #hotel_code, #hotel_contact, #facebook_page_id').on('input change', function() {
      $('#' + this.id + '_error').text('');
   });

   $(document).ready(function() {
      initHotelSelect2();
      bindHotelLocationEvents();
   });

   $('#open-hotel-modal').on('click', function(e) {
      e.preventDefault();
      resetHotelForm();
      $('#crud-modal-title').text('Add New Hotel');
      $('#action-btn').text('Create').attr('data-key', '');
      $('#hotel-crud-modal').modal('show');
   });

   $(document).on('click', '#action-btn', function(e) {
      e.preventDefault();
      if (!validateHotelForm()) {
         return;
      }

      var $button = $(this);
      var key = $button.attr('data-key');
      var buttonText = $button.text();
      var formData = new FormData();

      formData.append('country_id', $('#country_id').val());
      formData.append('state_id', $('#state_id').val());
      formData.append('city_id', $('#city_id').val());
      formData.append('hotel_name', $('#hotel_name').val());
      formData.append('hotel_code', $('#hotel_code').val());
      formData.append('hotel_contact', $('#hotel_contact').val());
      formData.append('facebook_page_id', $('#facebook_page_id').val());
      formData.append('hotel_address', $('#hotel_address').val());
      formData.append(window.CSRF.name, window.CSRF.hash);

      if ($('#hotel_image')[0].files[0]) {
         formData.append('hotel_image', $('#hotel_image')[0].files[0]);
      }
      if (key !== '') {
         formData.append('record_id', key);
      }

      $.ajax({
         url: '<?php echo base_url();?>' + ((key === '') ? 'insert-hotel' : 'update-hotel'),
         type: 'POST',
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'JSON',
         beforeSend: function() {
            $button.prop('disabled', true).text((key === '') ? 'Saving..' : 'Updating..');
         },
         success: function(response) {
            if (response.csrfHash) {
               window.CSRF.hash = response.csrfHash;
            }
            if (response.status) {
               toastr.success(response.message);
               $('#hotel-crud-modal').modal('hide');
               data_table.draw();
            } else {
               toastr.error(response.message || 'Failed to save hotel');
            }
         },
         error: function() {
            toastr.error('Something went wrong');
         },
         complete: function() {
            $button.prop('disabled', false).text(buttonText);
         }
      });
   });

   $(document).on('click', '.edit-hotel', function(e) {
      e.preventDefault();
      resetHotelForm();

      $.ajax({
         url: '<?php echo base_url('edit-hotel') ?>',
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
            if (!res.status) {
               toastr.error(res.message || 'Unable to load hotel details');
               return;
            }

            $('#crud-modal-title').text('Edit Hotel');
            $('#hotel_name').val(res.data.hotel_name);
            $('#hotel_code').val(res.data.hotel_code);
            $('#facebook_page_id').val(res.data.facebook_page_id);
            $('#hotel_contact').val(res.data.hotel_contact);
            $('#hotel_address').val(res.data.hotel_address);
            ensureSelectedOption($('#country_id'), res.data.country_id, res.data.country_name);
            refreshHotelSelect($('#country_id'), res.data.country_id);
            var stateRequest = loadStatesByCountry(res.data.country_id, res.data.state_id);
            if (stateRequest) {
               stateRequest.done(function(response) {
                  if (response.status) {
                     loadCitiesByState(res.data.state_id, res.data.city_id);
                  }
               });
            }

            $('#action-btn').text('Update').attr('data-key', res.id);
            $('#hotel-crud-modal').modal('show');
         },
         error: function() {
            toastr.error('Something went wrong');
         }
      });
   });

   $(document).on('click', '.delete-hotel', function() {
      var $button = $(this);
      Swal.fire({
         title: 'Are you sure?',
         text: 'This hotel will be removed from the active hotel list.',
         icon: 'question',
         showCancelButton: true,
         showCloseButton: true,
         confirmButtonText: 'Yes Delete it',
         denyButtonText: 'Cancel'
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: '<?php echo base_url('delete-hotel') ?>',
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
                     data_table.draw();
                  } else {
                     toastr.error(res.message || 'Failed to delete hotel');
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
