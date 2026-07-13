<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
        <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="header-content">
                        <h2 class="header-title">Manage Country</h2>
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
                                Country Management
                            </li>
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
               <div class="box new_table_box">
                  <div class="box-header">
                     <h4 class="box-title">Manage Countries</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm " id="open-country-modal">
                           Add +
                        </button>
                     </div>
                  </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="server-side-data-table" class="text-fade table table-bordered display" style="width:100%">
                               <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Country Code</th>
                                        <th>Country Name</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th width="120">Action</th>
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
<div class="modal  modal-lg new_modal_design" id="country-crud-modal" tabindex="-1" role="dialog" aria-labelledby="add-country-modalLabel" aria-hidden="true">
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
                           Fill in the details to add a new hotel to the system.
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
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="col-sm-6 mb-3">
               <label for="username" class="form-label">Country Code</label>
               <input class="form-control" type="email" id="country_code" required="" placeholder="+91">
               <span id="country_code_error" class="validation text-danger"></span>
            </div>
            <div class="col-sm-6 mb-3">
               <label for="username" class="form-label">Country Name</label>
               <input class="form-control" type="email" id="country_name" required="" placeholder="India">
               <span id="country_name_error" class="validation text-danger"></span>
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

    <?php if ($this->session->flashdata('country_success_msg') != "") { ?>
        toastr.success('<?php echo $this->session->flashdata('country_success_msg'); ?>')
    <?php $this->session->set_flashdata('country_success_msg', '');
   } ?>
</script>

<script type="text/javascript">
    // Server Side Datatable Initialization
    
    var data_table = $('#server-side-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url: '<?php echo base_url();?>countries-data-table',
            type: "POST",
            data: function(d){
                d[window.CSRF.name] = window.CSRF.hash;
            },
            drawCallback: function (setting) {
               if (setting.json && setting.json.csrfHash) {
                  window.CSRF.hash = setting.json.csrfHash;
               }
            },
            dataSrc:function(json){
                if (json.csrfHash) {
                    window.CSRF.hash = json.csrfHash;
                }
                return json.data;
            }
        }
    });

    // add country code start from here
    $("#open-country-modal").click(function(e) {
        e.preventDefault()
        $('#crud-modal-title').text('Add New Country');
        $('#country_code_error').text('');
        $('#country_name_error').text('');
        $('#country_name').val("");
        $('#country_code').val("");
        $('#action-btn').text('Create');
        $('#action-btn').attr('data-key', '');
        $('#country-crud-modal').modal('show');
    });

   // validation rules & submit function for add/update record

    $(document).on('click', '#action-btn', function(e) {
        e.preventDefault();
        
        if ($('#country_code').val() == '') {
            $('#country_code_error').text('Please Enter Country Name');
        } else {
            $('#country_code_error').text('');
        }
        
        if ($('#country_name').val() == '') {
            $('#country_name_error').text('Please Enter Country Name');
        } else {
            $('#country_name_error').text('');
        }
        
        if ($('#country_name').val() != '' && $('#country_code').val() != '') {
            
            let key = $(this).attr('data-key');
            let btn_txt = $(this).text();

            var formData = new FormData();
            formData.append('country_name', $('#country_name').val());
            formData.append('country_code', $('#country_code').val());
            formData.append(window.CSRF.name, window.CSRF.hash);
            
            if (key != "") {
                formData.append('record_id', key);
            }

            $.ajax({
                url: '<?php echo base_url();?>'+((key == "") ? "insert-country" : "update-country"),
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
                        $('#country-crud-modal').modal('hide');
                        data_table.draw();
                    } else {
                      toastr.error(response.message || 'Failed to add country');
                   }
                },
                error: function() {
                   $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary">'+btn_txt+'</button>')
                   toastr.error('Something went wrong');
                },
                complete: function (jqXHR) {
                    $('#action-btn-container').html('<button type="button" id="action-btn" class="btn btn-primary">'+btn_txt+'</button>')
                },
            });
        }
    });

    $(document).on('click', '.edit-country', function(e) {
        e.preventDefault()
        $.ajax({
            url: '<?php echo base_url('edit-country') ?>',
            type: 'post',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-record_id'),
                [window.CSRF.name]: window.CSRF.hash
            },
            success: function(res) {
                if (res) {
                    if (res.csrfHash) {
                        window.CSRF.hash = res.csrfHash;
                    }
                    if (!res.status) {
                        toastr.error(res.message || 'Unable to load country details');
                        return;
                    }
                    $('#crud-modal-title').text('Edit Country');
                    $('#country_code_error').text('');
                    $('#country_name_error').text('');
                    $('#country_name').val(res.data.country_name);
                    $('#country_code').val(res.data.country_code);
                    $('#action-btn').text('Update');
                    $('#action-btn').attr('data-key', res.id);
                    $('#country-crud-modal').modal('show');
                }
            }
      })
   })

   // delete country code is here
   $(document).on('click', '.delete-country', function(argument) {
      var $button = $(this);
      Swal.fire({
         title: "Are you sure?",
         text: 'You will not be able to recover this record!',
         icon: "question",
         showCancelButton: true,
         showCloseButton: true,

         confirmButtonText: "Yes Delete it",
         denyButtonText: `Cancel`
      }).then((result) => {

         if (result.isConfirmed) {
            var id = $button.attr('data-record_id');
            $.ajax({
               url: '<?php echo base_url('delete-country') ?>',
               method: "POST",
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
                        data_table.draw();
                    } else {
                        toastr.error(res.message || 'Failed to delete country');
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
