<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="custom-page-header">
         <div class="header-left">
            <div class="header-icon-box">
               <i class="fa fa-map"></i>
            </div>
            <div class="header-content">
               <h2 class="header-title">Manage State</h2>
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
                     State Management
                  </li>
               </ol>
            </div>
         </div>
         <div class="header-banner">
            <img src="<?php echo base_url('assets/new_img/state_img.png'); ?>" alt="">
         </div>
      </div>
      <!-- Main content -->
      <section class="content">
         <div class="row">
            <div class="col-12">
               <div class="box new_table_box">
                  <div class="box-header">
                     <h4 class="box-title">Manage State</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm " id="open-state-modal">
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
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </section>
      <!-- /.content -->
   </div>
</div>
<!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal  modal-lg new_modal_design" id="state-crud-modal" tabindex="-1" role="dialog" aria-labelledby="state-crud-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="custom-page-header">
            <div class="header-left">
               <div class="header-icon-box">
                  <i class="fa fa-map"></i>
               </div>
               <div class="header-content">
                  <div class="modal-header hotel_modal_header">
                     <h4 class="modal-title" id="crud-modal-title"></h4>
                     <div class="hotel_banner"></div>
                  </div>
                  <ol class="custom-breadcrumb">
                     <li>
                        <i class="fa fa-info-circle"></i>
                        Fill in the details to add or update a state.
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
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="mb-3">
               <label for="country_id" class="form-label">Select Country <span class="required-asterisk">*</span></label>
               <select class="form-control select2" type="text" name="country_id" id="country_id">
                  <option selected="" disabled="" value="">Select Country </option>
                  <?php foreach ($countries as $each) { ?>
                     <option value="<?php echo encrypt_id($each->country_id) ?>" data-country-code="<?php echo html_escape($each->country_code) ?>"><?php echo $each->country_name ?></option>
                  <?php
                  } ?>
               </select>
               <span id="country_id_error" class="validation text-danger"></span>
            </div>
            <div class="mb-3">
               <label for="state_name" class="form-label">State Name <span class="required-asterisk">*</span></label>
               <input class="form-control" type="text" id="state_name" required="" placeholder="Madhya Pradesh">
               <span id="state_name_error" class="validation text-danger"></span>
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-start">
            <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
            <div id="action-btn-container">
               <button type="button" id="action-btn" class="btn btn-primary" data-key="">Save changes</button>
            </div>
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

   <?php if ($this->session->flashdata('state_success_msg') != "") { ?>
      toastr.success('<?php echo $this->session->flashdata('state_success_msg'); ?>')
   <?php $this->session->set_flashdata('state_success_msg', '');
   } ?>
</script>
<script type="text/javascript">
   var data_table = $('#server-side-data-table').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      searching: true,
      columnDefs: [
         { targets: 5, orderable: false }
      ],
      ajax: {
         url: '<?php echo base_url('get-states-table') ?>',
         type: "POST",
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

   function renderStateTable() {
      data_table.draw();
   }

   $("#open-state-modal").click(function(e) {
      e.preventDefault();
      $('#crud-modal-title').text('Add New State');
      $('#state_name').val("");
      $('#country_id').val("");
      $('#country_id').trigger('change');
      $('#country_id_error').text('');
      $('#state_name_error').text('');
      $('#action-btn').text('Create');
      $('#action-btn').attr('data-key', '');
      $('#state-crud-modal').modal('show');
   });

   $(document).on('click', '#action-btn', function(e) {
      e.preventDefault();

      if ($('#country_id').val() == '' || $('#country_id').val() == null) {
         $('#country_id_error').text('Please select country');
      } else {
         $('#country_id_error').text('');
      }

      if ($('#state_name').val() == '') {
         $('#state_name_error').text('Please enter state name');
      } else {
         $('#state_name_error').text('');
      }

      if ($('#state_name').val() != '' && $('#country_id').val() != '' && $('#country_id').val() != null) {
         let key = $(this).attr('data-key');
         let btn_txt = $(this).text();

         var formData = new FormData();
         formData.append('state_name', $('#state_name').val());
         formData.append('country_id', $('#country_id').val());
         formData.append(window.CSRF.name, window.CSRF.hash);

         if (key != "") {
            formData.append('record_id', key);
         }

         $.ajax({
            url: '<?php echo base_url();?>'+((key == "") ? "insert-state" : "update-state"),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
               $('#action-btn-container').html('<button type="button" class="btn btn-primary">'+((key != "")?'Updating..':'Saving..')+'</button>');
            },
            success: function(response) {
               if (response.csrfHash) {
                  window.CSRF.hash = response.csrfHash;
               }
               if (response.status) {
                  toastr.success(response.message);
                  $('#state-crud-modal').modal('hide');
                  renderStateTable();
               } else {
                  toastr.error(response.message || 'Failed to save state');
               }
            },
            error: function() {
               $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary">'+btn_txt+'</button>')
               toastr.error('Something went wrong');
            },
            complete: function() {
               $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary">'+btn_txt+'</button>')
            }
         });
      }
   });

   $(document).on('click', '.edit-state', function(e) {
      e.preventDefault();
      $('#state_name').val("");
      $('#country_id').val("");
      $('.validation').text('');
      var id = $(this).attr('data-record_id');

      $.ajax({
         url: '<?php echo base_url('edit-state') ?>',
         type: 'post',
         dataType: 'JSON',
         data: {
            id: id,
            [window.CSRF.name]: window.CSRF.hash
         },
         success: function(res) {
            if (res) {
               if (res.csrfHash) {
                  window.CSRF.hash = res.csrfHash;
               }
               if (!res.status) {
                  toastr.error(res.message || 'Unable to load state details');
                  return;
               }
               $('#crud-modal-title').text('Edit State');
               $('#state_name').val(res.data.state_name);
               var $countryOption = $('#country_id option').filter(function() {
                  return $(this).attr('data-country-code') === res.data.country_code;
               }).first();

               if (!$countryOption.length) {
                  toastr.error('The country assigned to this state is unavailable');
                  return;
               }

               $('#country_id').val($countryOption.val()).trigger('change');
               $('#state_name_error').text('');
               $('#country_id_error').text('');
               $('#action-btn').text('Update');
               $('#action-btn').attr('data-key', res.id);
               $('#state-crud-modal').modal('show');
            }
         }
      });
   });

   $(document).on('click', '.delete-state', function(argument) {
      var $button = $(this);
      Swal.fire({
         title: "Are you sure?",
         text: 'This state will be removed from the active state list.',
         icon: "question",
         showCancelButton: true,
         showCloseButton: true,

         confirmButtonText: "Yes Delete it",
         denyButtonText: `Cancel`
      }).then((result) => {
         if (result.isConfirmed) {
            var id = $button.attr('data-record_id');
            $.ajax({
               url: '<?php echo base_url('delete-state') ?>',
               method: 'POST',
               dataType: 'json',
               data: {
                  id: id,
                  [window.CSRF.name]: window.CSRF.hash
               },
               success: function(res) {
                  if (res.csrfHash) {
                     window.CSRF.hash = res.csrfHash;
                  }
                  if (res.status) {
                     toastr.success(res.message);
                     renderStateTable();
                  } else {
                     toastr.error(res.message || 'Failed to delete state');
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
