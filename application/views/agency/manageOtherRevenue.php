<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="content-header">
         <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">Manage Other Revenues</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Other Revenues</li>
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
                     <h4 class="box-title">Manage Other Revenues</h4>
                     <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm " id="open-other_revenue-modal">
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
                                 <th>Other Revenue Name</th>
                                 <th>Daily  Budget</th>
                                <!--  <th>Weekly Actual Budget</th> -->
                                 <th>Monthly  Budget</th>
                                 <th>Yearly  Budget</th>
                                 <th>Created Date</th>
                                 <th>Updated Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if(!empty($other_revenue)) {
                                 $number=1;
                                 
                                 
                                 foreach ($other_revenue as $each_other_revenue) { ?>
                              <tr>
                                 <td><?php echo $number; $number++; ?></td>
                                 <td><?= $each_other_revenue->other_revenue_name ?></td>
                                 <td><?= $each_other_revenue->daily_budget ?></td>
                                 <!-- <td><?= $each_other_revenue->weekly_budget ?></td> -->
                                 <td><?= $each_other_revenue->monthly_budget ?></td>
                                 <td><?= $each_other_revenue->yearly_budget ?></td>
                                 <td><?= $each_other_revenue->created_at ?></td>
                                 <td><?= $each_other_revenue->updated_at ?></td>
                                 <td class="table-action min-w-100">
                                    <a href="javascript:void(0)" class="text-fade hover-primary edit-other_revenue" data-record_id="<?php echo $each_other_revenue->other_revenue_id ?>">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                                          <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                       </svg>
                                    </a>
                                    <a href="javascript:void(0)" class="text-fade hover-primary delete-other_revenue" data-record_id="<?php echo $each_other_revenue->other_revenue_id ?>">
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
<div class="modal  modal-lg" id="add-other_revenue-modal" tabindex="-1" role="dialog" aria-labelledby="add-other_revenue-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Add New Other Revenue</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body ps-3 pe-3">
            <!--  <form class="ps-3 pe-3" action="#"> -->
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">Other Revenue Name</label>
               <input class="form-control" type="email" id="other_revenue_name" required="" placeholder="">
               <span id="other_revenue_name_error" class="validation text-danger"></span>
            </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">Daily Budget</label>
               <input class="form-control" type="number" id="daily_budget" required="" placeholder="">
               <span id="daily_budget_error" class="validation text-danger"></span>
            </div>
              </div>
              <!-- <div class="col-md-6">
                 <div class="mb-3">
               <label for="username" class="form-label">weekly Budget</label>
               <input class="form-control" type="number" id="weekly_budget" required="" placeholder="">
               <span id="weekly_budget_error" class="validation text-danger"></span>
            </div>
              </div> -->
              <div class="col-md-6">
                 <div class="mb-3">
               <label for="username" class="form-label">Monthly Budget</label>
               <input class="form-control" type="number" id="monthly_budget" required="" placeholder="">
               <span id="monthly_budget_error" class="validation text-danger"></span>
            </div>
              </div>
              <div class="col-md-6">
                 <div class="mb-3">
               <label for="username" class="form-label">yearly Budget</label>
               <input class="form-control" type="number" id="yearly_budget" required="" placeholder="">
               <span id="yearly_budget_error" class="validation text-danger"></span>
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
<div class="modal  modal-lg" id="edit-other_revenue-modal" tabindex="-1" role="dialog" aria-labelledby="add-other_revenue-modalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Edit Other Revenue </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body ps-3 pe-3">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">Other Revenue Name</label>
               <input class="form-control" type="email" id="other_revenue_name_edit" required="" placeholder="">
               <span id="other_revenue_name_edit_error" class="validation text-danger"></span>
               <input type="hidden" name="" id="record_id">
            </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">Daily Budget</label>
               <input class="form-control" type="number" id="daily_budget_edit" required="" placeholder="">
               <span id="daily_budget_edit_error" class="validation text-danger"></span>
            </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">weekly Budget</label>
               <input class="form-control" type="number" id="weekly_budget_edit" required="" placeholder="">
               <span id="weekly_budget_edit_error" class="validation text-danger"></span>
            </div>
              </div> -->
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">Monthly Budget</label>
               <input class="form-control" type="number" id="monthly_budget_edit" required="" placeholder="">
               <span id="monthly_budget_edit_error" class="validation text-danger"></span>
            </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
               <label for="username" class="form-label">yearly Budget</label>
               <input class="form-control" type="number" id="yearly_budget_edit" required="" placeholder="">
               <span id="yearly_budget_edit_error" class="validation text-danger"></span>
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
   
   
   
      <?php  if($this->session->flashdata('other_revenue_success_msg')!=""){ ?>
      
      
   
      toastr.success('<?php echo $this->session->flashdata('other_revenue_success_msg'); ?>')
   
    <?php  $this->session->set_flashdata('other_revenue_success_msg',''); } ?>
</script>
<script type="text/javascript">
   // add other_revenue code start from here
   
     $("#open-other_revenue-modal").click(function(e) {
   
     	e.preventDefault()
      
      $('#other_revenue_name').val("");
      $('#daily_budget').val("");
      // $('#weekly_budget').val("");
      $('#monthly_budget').val("");
      $('#yearly_budget').val("");
      
   
     $('#add-other_revenue-modal').modal('show'); 
   });
   
   
   
   
   // validation rules
    
    $('#other_revenue_name').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#other_revenue_name_error').html('');
       } else {
         $('#other_revenue_name_error').html('Please Enter Room Analysis Name');
         $('#other_revenue_name').val('');
       }
     
   });
   
    $('#daily_budget').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#daily_budget_error').html('');
       } else {
         $('#daily_budget_error').html('Please Enter Daily Budget');
         $('#daily_budget').val('');
       }
     
   });

    $('#daily_budget').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#daily_budget_error').html('');
       } else {
         $('#daily_budget_error').html('Please Enter Daily Budget');
         $('#daily_budget').val('');
       }
     
   });

    $('#weekly_budget').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#weekly_budget_error').html('');
       } else {
         $('#weekly_budget_error').html('Please Enter Weekly Budget');
         $('#weekly_budget').val('');
       }
     
   });

    $('#monthly_budget').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#monthly_budget_error').html('');
       } else {
         $('#monthly_budget_error').html('Please Enter monthly Budget');
         $('#monthly_budget').val('');
       }
     
   });

    $('#yearly_budget').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#yearly_budget_error').html('');
       } else {
         $('#yearly_budget_error').html('Please Enter Yearly Budget');
         $('#yearly_budget').val('');
       }
     
   });
   
   
   // submit function for add a new record
   
   $(document).on('click','#SaveBtn',function(e){
   e.preventDefault();
   
   $('#other_revenue_name').trigger('focusout');
   $('#daily_budget').trigger('focusout');
   // $('#weekly_budget').trigger('focusout');
   $('#monthly_budget').trigger('focusout');
   $('#yearly_budget').trigger('focusout');
   
   
   var other_revenue_name=$('#other_revenue_name').val();
   var daily_budget=$('#daily_budget').val();
   // var weekly_budget=$('#weekly_budget').val();
   var monthly_budget=$('#monthly_budget').val();
   var yearly_budget=$('#yearly_budget').val();
   
   
   if(other_revenue_name!=''&&daily_budget!=''&&monthly_budget!=''&&yearly_budget!=''){
   
     var formData = new FormData();
     formData.append('other_revenue_name', other_revenue_name);
     formData.append('daily_budget', daily_budget);
     // formData.append('weekly_budget', weekly_budget);
     formData.append('monthly_budget', monthly_budget);
     formData.append('yearly_budget', yearly_budget);


    
   
   $.ajax({
             url: '<?php echo base_url("insert-other-revenue") ?>',
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
               // toastr.success('New Room Analysis has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })
   
   
   // edit-other_revenue function start from here
   
   $(".edit-other_revenue").click(function(e) {
   	e.preventDefault()
      
      $('#other_revenue_name_edit').val("");
      $('#daily_budget').val("");
      // $('#weekly_budget').val("");
      $('#monthly_budget').val("");
      $('#yearly_budget').val("");
     
   
      $(".validation").html("")
     var id=$(this).attr('data-record_id');
   //     alert(id);
   
    $.ajax({
      url: '<?php echo base_url('edit-other-revenue')?>',
      type: 'post',
      dataType:'JSON',
      data: {
       id:id
     },
       success:function(res){
         //   alert(res);
         if (res){
         $("#record_id").val(id)
          $('#other_revenue_name_edit').val(res.other_revenue_name);
          $('#daily_budget_edit').val(res.daily_budget);
          // $('#weekly_budget_edit').val(res.weekly_budget);
          $('#monthly_budget_edit').val(res.monthly_budget);
          $('#yearly_budget_edit').val(res.yearly_budget);
   
         
         $('#edit-other_revenue-modal').modal('show');         
         }
        
       }
    })
   
   
     
   })
   

   // update other_revenue code start from here

   // validation rules
    
    $('#other_revenue_name_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#other_revenue_name_edit_error').html('');
       } else {
         $('#other_revenue_name_edit_error').html('Please Enter Room Analysis Name');
         $('#other_revenue_name_edit').val('');
       }
     
   });


    $('#daily_budget_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#daily_budget_edit_error').html('');
       } else {
         $('#daily_budget_edit_error').html('Please Enter Daily Budget');
         $('#daily_budget_edit').val('');
       }
     
   });

    $('#weekly_budget_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#weekly_budget_edit_error').html('');
       } else {
         $('#weekly_budget_edit_error').html('Please Enter Weekly Budget');
         $('#weekly_budget_edit').val('');
       }
     
   });

    $('#monthly_budget_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#monthly_budget_edit_error').html('');
       } else {
         $('#monthly_budget_edit_error').html('Please Enter monthly Budget');
         $('#monthly_budget_edit').val('');
       }
     
   });

    $('#yearly_budget_edit').focusout(function() {
     var value = this.value;
     if (value!="") {
         $('#yearly_budget_edit_error').html('');
       } else {
         $('#yearly_budget_edit_error').html('Please Enter Yearly Budget');
         $('#yearly_budget_edit').val('');
       }
     
   });
   
   


    $(document).on('click','#updateBtn',function(e){
   e.preventDefault();
   
   $('#other_revenue_name_edit').trigger('focusout');
   $('#daily_budget_edit').trigger('focusout');
   // $('#weekly_budget_edit').trigger('focusout');
   $('#monthly_budget_edit').trigger('focusout');
   $('#yearly_budget_edit').trigger('focusout');
   
   
   var other_revenue_name=$('#other_revenue_name_edit').val();
   var daily_budget=$('#daily_budget_edit').val();
   // var weekly_budget=$('#weekly_budget_edit').val();
   var monthly_budget=$('#monthly_budget_edit').val();
   var yearly_budget=$('#yearly_budget_edit').val();
   var record_id=$('#record_id').val();
   
   
   if(other_revenue_name!=''&&daily_budget!=''&&monthly_budget!=''&&yearly_budget!=''){
   
     var formData = new FormData();
     formData.append('other_revenue_name', other_revenue_name);
     formData.append('daily_budget', daily_budget);
     // formData.append('weekly_budget', weekly_budget);
     formData.append('monthly_budget', monthly_budget);
     formData.append('yearly_budget', yearly_budget);
     formData.append('record_id',record_id);
   
   $.ajax({
             url: '<?php echo base_url("update-other-revenue") ?>',
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
               // toastr.success('New Room Analysis has been added Successfully') 
             }
         });
   
   
   
   }
   
   
   
   })

   
   
   // delete other_revenue code is here
   
   $(".delete-other_revenue").click(function (argument) {
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
      url: '<?php echo base_url('delete-other-revenue')?>',
      method:"POST",
      data: {
       id:id
     },
       success:function(res){
       	console.log(res)
         
         	//Swal.fire("Room Analysis Has been Deleted Successfully!", "", "success");
         	window.location.reload();
         
       	
       }
   
    })
   
   
   
   
     
   } 
   });
   })
   
    
</script>