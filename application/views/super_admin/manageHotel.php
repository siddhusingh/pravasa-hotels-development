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
            <img src="<?php echo base_url('assets/new_img-add.png'); ?>" alt="">
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
                                 <th>Country Name</th>
                                 <th>State Name</th>
                                 <th>City Name</th>
                                 <th>FB page ID</th>
                                 <th>Hotel Code</th>
                                 <th>Hotel Name</th>
                                 <th>Created Date</th>
                                 <th>Updated Date</th>
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
               <div class="col-md-6 mb-3">
                  <label class="form-label">Select Country <span class="required-asterisk">*</span></label>
                  <select class="form-control" id="country_id">
                     <option value="">Select Country</option>
                     <?php foreach ($countries as $each) { ?>
                        <option value="<?php echo encrypt_id($each->country_id) ?>"><?php echo $each->country_name ?></option>
                     <?php } ?>
                  </select>
                  <span id="country_id_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-6 mb-3">
                  <label class="form-label">Select State <span class="required-asterisk">*</span></label>
                  <select class="form-control" id="state_id" disabled>
                     <option value="">Select State</option>
                  </select>
                  <span id="state_id_error" class="validation text-danger"></span>
               </div>
               <div class="col-md-6 mb-3">
                  <label class="form-label">Select City <span class="required-asterisk">*</span></label>
                  <select class="form-control" id="city_id" disabled>
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
                  <img id="hotel_image_preview" src="" style="width:100px;display:none;">
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
         <div class="modal-footer">
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
      columnDefs: [
         { targets: [9, 10], orderable: false }
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

   function resetHotelForm() {
      $('#country_id').val('');
      $('#state_id').prop('disabled', true).html('<option value="">Select State</option>').val('');
      $('#city_id').prop('disabled', true).html('<option value="">Select City</option>').val('');
      $('#hotel_name, #hotel_code, #hotel_contact, #facebook_page_id, #hotel_address').val('');
      $('#hotel_image').val('');
      $('#hotel_image_preview').attr('src', '').hide();
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
      $('#state_id').prop('disabled', true).html('<option value="">Loading...</option>').val('');
      $('#city_id').prop('disabled', true).html('<option value="">Select City</option>').val('');

      if (!countryId) {
         $('#state_id').prop('disabled', true).html('<option value="">Select State</option>');
         return;
      }

      $.ajax({
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
            $('#state_id').html('<option value="">Select State</option>');
            $.each(response.data || [], function(index, state) {
               $('#state_id').append($('<option></option>').val(state.state_id).text(state.state_name));
            });
            $('#state_id').prop('disabled', false).val(selectedStateId || '');
         },
         error: function() {
            toastr.error('Unable to fetch states');
         }
      });
   }

   function loadCitiesByState(stateId, selectedCityId) {
      $('#city_id').prop('disabled', true).html('<option value="">Loading...</option>').val('');

      if (!stateId) {
         $('#city_id').prop('disabled', true).html('<option value="">Select City</option>');
         return;
      }

      $.ajax({
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
            $('#city_id').html('<option value="">Select City</option>');
            $.each(response.data || [], function(index, city) {
               $('#city_id').append($('<option></option>').val(city.city_id).text(city.city_name));
            });
            $('#city_id').prop('disabled', false).val(selectedCityId || '');
         },
         error: function() {
            toastr.error('Unable to fetch cities');
         }
      });
   }

   $('#country_id').on('change', function() {
      $('#country_id_error').text('');
      loadStatesByCountry($(this).val(), '');
   });

   $('#state_id').on('change', function() {
      $('#state_id_error').text('');
      loadCitiesByState($(this).val(), '');
   });

   $('#city_id, #hotel_name, #hotel_code, #hotel_contact, #facebook_page_id').on('input change', function() {
      $('#' + this.id + '_error').text('');
   });

   $('#open-hotel-modal').on('click', function(e) {
      e.preventDefault();
      resetHotelForm();
      $('#crud-modal-title').text('Add New Hotel');
      $('#action-btn').text('Create').attr('data-key', '');
      $('#hotel-crud-modal').modal('show');
   });

   $('#hotel_image').on('change', function() {
      if (!this.files || !this.files[0]) {
         $('#hotel_image_preview').attr('src', '').hide();
         return;
      }
      var reader = new FileReader();
      reader.onload = function(e) {
         $('#hotel_image_preview').attr('src', e.target.result).show();
      };
      reader.readAsDataURL(this.files[0]);
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
            $('#country_id').val(res.data.country_id);
            loadStatesByCountry(res.data.country_id, res.data.state_id);
            loadCitiesByState(res.data.state_id, res.data.city_id);

            if (res.data.hotel_image) {
               $('#hotel_image_preview').attr('src', '<?php echo base_url("uploads/hotel_images/") ?>' + res.data.hotel_image).show();
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
         text: 'You will not be able to recover this record!',
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
