<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="content-header">
         <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">Manage Senior Managers</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Senior Managers</li>
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
                     <h4 class="box-title">Manage Senior Managers</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm " id="open-modal">
                        Add +
                        </button>
                     </div>
                  </div>
                  <div class="box-body">
                     <div class="table-responsive">
                        <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
                           <thead>
                              <tr class="text-dark">
                                 <th>Sr. No.</th>
                                 <th>Full Name</th>
                                 <th>Email</th>
                                 <th>Phone</th>
                                 <th>Created Date</th>
                                 <th>Updated Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if(!empty($smngrs )) {
                                 $number=1;
                                 
                                 
                                 foreach ($smngrs as $each_smngrs) { ?>
                              <tr>
                                 <td><?php echo $number; $number++; ?></td>
                                 <td><?= $each_smngrs->full_name ?></td>
                                 <td><?= $each_smngrs->email ?></td>
                                 <td><?= $each_smngrs->phone ?></td>
                                 <td><?= $each_smngrs->created_at ?></td>
                                 <td><?= $each_smngrs->updated_at ?></td>
                                 <td class="table-action min-w-100">
                                    <a href="javascript:void(0)" class="text-fade hover-primary edit" data-record_id="<?php echo $each_smngrs->id ?>">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                                          <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                       </svg>
                                    </a>
                                    <a href="javascript:void(0)" class="text-fade hover-primary delete" data-record_id="<?php echo $each_smngrs->id ?> ?>">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                                          <polyline points="3 6 5 6 21 6"></polyline>
                                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                       </svg>
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
<div class="modal  modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-Senior Managers-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Add New Senior Manager</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body ps-3 pe-3">
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="row">
               
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Full Name</label>
                     <input class="form-control" type="text" id="full_name" required="" placeholder="Full Name">
                     <span id="full_name_error" class="validation text-danger"></span>
                  </div>
               </div>
               
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label"> Email</label>
                     <input class="form-control" type="email" id="email" required="" placeholder="Email">
                     <span id="email_error" class="validation text-danger"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Password</label>
                     <input class="form-control" type="password" id="password" required="" placeholder="Password">
                     <span id="password_error" class="validation text-danger"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Phone</label>
                     <input class="form-control" type="number" id="phone" required="" placeholder="Phone">
                     <span id="phone_error" class="validation text-danger"></span>
                  </div>
               </div>
            </div>
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
            <button type="button" id="SaveBtn" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal  modal-lg" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="add-Senior Managers-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Edit Senior Manager</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
          <div class="modal-body ps-3 pe-3">
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="row">
               
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Full Name</label>
                     <input class="form-control" type="text" id="full_name_edit" required="" placeholder="Full Name">
                     <input type="hidden" name="" id="record_id">
                     <span id="full_name_edit_error" class="validation text-danger"></span>
                  </div>
               </div>
               
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label"> Email</label>
                     <input class="form-control" type="email" id="email_edit" required="" placeholder="Email">
                     <span id="email_edit_error" class="validation text-danger"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Password</label>
                     <input class="form-control" type="password" id="password_edit" required="" placeholder="Email">
                     <span id="password_error" class="validation text-danger"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="username" class="form-label">Phone</label>
                     <input class="form-control" type="number" id="phone_edit" required="" placeholder="Phone">
                     <span id="phone_edit_error" class="validation text-danger"></span>
                  </div>
               </div>
            </div>
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
            <button type="button" id="updateBtn" class="btn btn-primary">Update changes</button>
         </div>
      </div>
   </div>
</div>
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
   
   
   
      <?php  if($this->session->flashdata('senior_managers_success_msg')!=""){ ?>
      
      
   
      toastr.success('<?php echo $this->session->flashdata('senior_managers_success_msg'); ?>')
   
    <?php  $this->session->set_flashdata('senior_managers_success_msg',''); } ?>
</script>
<script type="text/javascript">
   // add Senior Managers code start from here
   
     $("#open-modal").click(function(e) {
   
      e.preventDefault()
      
      $('#full_name').val("");
      $('#email').val("");
      $('#phone').val("");
      $('#password').val("");
   
     $('#add-modal').modal('show'); 
   });
   
   
   
   
   // validation rules
    
    $('#full_name').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#full_name_error').html('');
       } else {
         $('#full_name_error').html('Please Enter Full Name');
         $('#full_name').val('');
       }
     
   });
   
   
   
   
    $('#email').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#email_error').html('');
       } else {
         $('#email_error').html('Please Enter Email ');
         $('#email').val('');
       }
     
   });
   
   $('#phone').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#phone_error').html('');
       } else {
         $('#phone_error').html('Please Enter Phone Number ');
         $('#phone').val('');
       }
     
   });
   
    $('#password').focusout(function() {
     var value = this.value;
     var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,}$/; // At least 6 characters, one number, and one special character
     
     if (value != "") {
         if (!passwordRegex.test(value)) {
             $('#password_error').html('Password must be at least 6 characters long, contain at least one number and one special character');
             $('#password').val('');
         } else {
             $('#password_error').html('');
         }
     } else {
         $('#password_error').html('Please Enter Password');
         $('#password').val('');
     }
   });
   
    
   
      
   
   // submit function for add a new record
   
   $(document).on('click','#SaveBtn',function(e){
   e.preventDefault();
   
  
   $('#full_name').trigger('focusout');
   $('#email').trigger('password');
   $('#password').trigger('focusout');
   $('#phone').trigger('focusout');
   
   
  
   var full_name=$('#full_name').val();
   var email=$('#email').val();
   var password=$('#password').val();
   var phone=$('#phone').val();
   
   
   if(email!=''&&full_name!=''&&password!=''&&phone!=''){
   
     var formData = new FormData();
    
   
      formData.append('full_name', full_name);
      formData.append('email', email);
      formData.append('phone', phone);
      formData.append('password', password);
      
     
   $.ajax({
             url: '<?php echo base_url("insert-seniors") ?>',
             type: 'POST',
             data: formData,
             processData: false,
             contentType: false,
             dataType:'JSON',
             beforeSend:function () {
                $(this).html('saving..');
                $(this).attr('disabled',true);
   
             },
             success:function(response){
               
               $(".SaveBtn").attr('disabled',false);
               $(this).html('Add');
               $(this).attr('disabled',false);
                // mera man
               window.location.reload()
               // toastr.success('New Senior Managers has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })
   
   
   // edit-Senior Managers function start from here
   
   $(".edit").click(function(e) {
    e.preventDefault()
      
    $(".validation").html("")
     var id=$(this).attr('data-record_id');
   //     alert(id);
   
    $.ajax({
      url: '<?php echo base_url('edit-seniors')?>',
      type: 'post',
      dataType:'JSON',
      data: {
       id:id
     },
       success:function(res){
         //   alert(res);
         if (res){
          $("#record_id").val(res.id)
          $('#full_name_edit').val(res.full_name);
          
          $('#email_edit').val(res.email);
           
          $('#phone_edit').val(res.phone);          
   
         
         $('#edit-modal').modal('show');         
         }
        
       }
    })
   
   
     
   })
   
   
   // update Senior Managers code start from here
   
   // validation rules
    
    $('#full_name_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#full_name_edit_error').html('');
       } else {
         $('#full_name_edit_error').html('Please Enter Senior Managers Name');
         $('#full_name_edit').val('');
       }
     
   });
   
   
   
   
    $(document).on('click','#updateBtn',function(e){
   e.preventDefault();
   
   $('#full_name_edit').trigger('focusout');
   $('#email_edit').trigger('focusout');
   // $('#phone_edit').trigger('focusout');
   // $('#password').trigger('focusout');
   
   
   var record_id=$("#record_id").val();
   var full_name=$('#full_name_edit').val();
   var email=$('#email_edit').val();
   var password=$('#password_edit').val();
    var phone=$('#phone_edit').val();
  
// console.log(full_name)
// return
   
   if(full_name!=''&&email!=''&&phone!=''){
   
     var formData = new FormData();
    
     
      formData.append('full_name', full_name);
      formData.append('email', email);
      formData.append('password', password);
      formData.append('phone', phone);
       formData.append('record_id', record_id);
     
   
   $.ajax({
             url: '<?php echo base_url("update-seniors") ?>',
             type: 'POST',
             data: formData,
             processData: false,
             contentType: false,
             dataType:'JSON',
             beforeSend:function () {
                $(this).html('updating..');
                $(this).attr('disabled',true);
   
             },
             success:function(response){
               
               $("#updateBtn").attr('disabled',false);
               
               $(this).attr('disabled',false);
                // mera man
               window.location.reload()
               // toastr.success('New Senior Managers has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })
   
   
   
   // delete Senior Managers code is here
   
   $(".delete").click(function (argument) {
   Swal.fire({
   title: "Are you sure?",
   text:'You will not be able to recover this imaginary file!',
   icon: "question",
   showCancelButton: true,
   showCloseButton: true,
   
   confirmButtonText: "Yes Delete it",
   denyButtonText: `Cancel`
   }).then((result) => {
   
   if (result.isConfirmed) {
       
   
       var id=$(this).attr('data-record_id');
   $.ajax({
      url: '<?php echo base_url('delete-seniors')?>',
      method:"POST",
      data: {
       id:id
     },
       success:function(res){
        console.log(res)
         
          //Swal.fire("Senior Managers Has been Deleted Successfully!", "", "success");
          window.location.reload();
         
        
       }
   
    })
   
   
   
   
     
   } 
   });
   })
   
    
</script>