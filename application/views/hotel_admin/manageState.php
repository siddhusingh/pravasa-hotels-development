<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="content-header">
         <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">Manage State</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Manage State</li>
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
                     <h4 class="box-title">Manage State</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm " id="open-state-modal">
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
                                 <th>Country Name</th>
                                 <th>state Name</th>
                                 <th>Created Date</th>
                                 <th>Updated Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if(!empty($states)) {
                                 $number=1;
                                 
                                 
                                 foreach ($states as $each_state) { ?>
                              <tr>
                                 <td><?php echo $number; $number++; ?></td>
                                 <td><?= $each_state->country_name ?></td>
                                 <td><?= $each_state->state_name ?></td>
                                 <td><?= $each_state->created_at ?></td>
                                 <td><?= $each_state->updated_at ?></td>
                                 <td class="table-action min-w-100">
                                    <a href="javascript:void(0)" class="text-fade hover-primary edit-state" data-record_id="<?php echo $each_state->state_id ?>">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                                          <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                       </svg>
                                    </a>
                                    <a href="javascript:void(0)" class="text-fade hover-primary delete-state" data-record_id="<?php echo $each_state->state_id ?>">
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
<div class="modal  modal-lg" id="add-state-modal" tabindex="-1" role="dialog" aria-labelledby="add-state-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Add New state</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body ps-3 pe-3">
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="mb-3">
               <label for="username" class="form-label">Select Country</label>
               <select class="form-control" type="text" name="country_id" id="country_id">
                     <option selected="" disabled="" value="" >Select Country </option>
                     <?php foreach($countries as $each){ ?>
                     <option  value="<?php echo $each->country_id ?>"><?php echo $each->country_name ?></option>
                     <?php
                        } ?>
                  </select>
                                 <input type="hidden" name="" id="edit_record_id">

               <span id="country_id_error" class="validation text-danger"></span>
            </div>
            <div class="mb-3">
               <label for="username" class="form-label">state Name</label>
               <input class="form-control" type="email" id="state_name" required="" placeholder="Madhya Pradesh">
               <span id="state_name_error" class="validation text-danger"></span>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button>
            <button type="button" id="SaveBtn" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal  modal-lg" id="edit-state-modal" tabindex="-1" role="dialog" aria-labelledby="add-state-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Edit state</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body ps-3 pe-3">
            <!--  <form class="ps-3 pe-3" action="#"> -->
             <div class="mb-3">
               <label for="username" class="form-label">Select Country</label>
               <select class="form-control" type="text" name="country_id_edit" id="country_id_edit">
                     <option selected="" disabled="" value="" >Select Country </option>
                     <?php foreach($countries as $each){ ?>
                     <option  value="<?php echo $each->country_id ?>"><?php echo $each->country_name ?></option>
                     <?php
                        } ?>
                  </select>
               <span id="country_id_edit_error" class="validation text-danger"></span>
            </div>
            <div class="mb-3">
               <label for="username" class="form-label">State Name</label>
               <input class="form-control" type="email" id="state_name_edit" required="" placeholder="India">
               <span id="state_name_edit_error" class="validation text-danger"></span>
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
   
   
   
      <?php  if($this->session->flashdata('state_success_msg')!=""){ ?>
      
      
   
      toastr.success('<?php echo $this->session->flashdata('state_success_msg'); ?>')
   
    <?php  $this->session->set_flashdata('state_success_msg',''); } ?>
</script>
<script type="text/javascript">
   // add state code start from here
   
     $("#open-state-modal").click(function(e) {
   
     	e.preventDefault()
      
      $('#state_name').val("");
      $('#state_code').val("");
   
     $('#add-state-modal').modal('show'); 
   });
   
   
   
   
   // validation rules
    
    $('#state_name').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#state_name_error').html('');
       } else {
         $('#state_name_error').html('Please Enter state Name');
         $('#state_name').val('');
       }
     
   });
   
    $('#country_id').focusout(function() {
     var value = this.value;
     if (value!=null) {
         $('#country_id_error').html('');
       } else {
         $('#country_id_error').html('Please Select  Country');
         $('#state_code').val('');
       }
     
   });
   
   
   // submit function for add a new record
   
   $(document).on('click','#SaveBtn',function(e){
   e.preventDefault();
   
   $('#state_name').trigger('focusout');
   $('#country_id').trigger('focusout');
   
   var state_name=$('#state_name').val();
   var country_id=$('#country_id').val();
   
   if(state_name!=''&&country_id!=''){
   
     var formData = new FormData();
     formData.append('state_name', state_name);
     formData.append('country_id', country_id);
   
   $.ajax({
             url: '<?php echo base_url("insert-state") ?>',
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
               // toastr.success('New state has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })
   
   
   // edit-state function start from here
   
   $(".edit-state").click(function(e) {
   	e.preventDefault()
      
      $('#state_name_edit').val("");
      $('#country_id_edit').val("");
   
      $(".validation").html("")
     var id=$(this).attr('data-record_id');
   //     alert(id);
   
    $.ajax({
      url: '<?php echo base_url('edit-state')?>',
      type: 'post',
      dataType:'JSON',
      data: {
       id:id
     },
       success:function(res){
         //   alert(res);
         if (res){
         $("#edit_record_id").val(res.state_id)
          $('#state_name_edit').val(res.state_name);
          $('#country_id_edit').val(res.country_id);
   
         
         $('#edit-state-modal').modal('show');         
         }
        
       }
    })
   
   
     
   })
   

   // update state code start from here

   // validation rules
    
    $('#state_name_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#state_name_edit_error').html('');
       } else {
         $('#state_name_edit_error').html('Please Enter state Name');
         $('#state_name_edit').val('');
       }
     
   });
   
    $('#country_id_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#country_id_edit_error').html('');
       } else {
         $('#country_id_edit_error').html('Please Enter state Code');
         $('#state_code_edit').val('');
       }
     
   });


    $(document).on('click','#updateBtn',function(e){
   e.preventDefault();
   
   $('#state_name_edit').trigger('focusout');
   $('#country_id_edit').trigger('focusout');
   
   var record_id=$("#edit_record_id").val();
   var state_name=$('#state_name_edit').val();
   var country_id=$('#country_id_edit').val();

   
   if(state_name!=''&&country_id!=''){
   
     var formData = new FormData();
     formData.append('state_name', state_name);
     formData.append('country_id', country_id);
     formData.append('record_id',record_id);
   
   $.ajax({
             url: '<?php echo base_url("update-state") ?>',
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
               // toastr.success('New state has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })

   
   
   // delete state code is here
   
   $(".delete-state").click(function (argument) {
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
      url: '<?php echo base_url('delete-state')?>',
      method:"POST",
      data: {
       id:id
     },
       success:function(res){
       	console.log(res)
         
         	//Swal.fire("state Has been Deleted Successfully!", "", "success");
         	window.location.reload();
         
       	
       }
   
    })
   
   
   
   
     
   } 
   });
   })
   
    
</script>