<!-- Content Wrapper. Contains page content -->

<style type="text/css">
  .table > thead > tr > td, .table > thead > tr > th {
    padding: 8px!important;
    vertical-align: middle;
}

</style>
<div class="content-wrapper">
   <div class="container-full">
      <!-- Content Header (Page header) -->
      <div class="content-header">
         <!-- <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">Upload Daily Sales Report</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Admin Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Upload Daily Sales Report</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div> -->
      </div>
      <!-- Main content -->
      <section class="content">
         <div class="row">
            <div class="col-12">
               <div class="box">
                  <div class="box-header">
                     <h3 class="" style="text-align: center;font-weight: bold;"><?php echo $hotel_data->hotel_name; ?></h3>
                     
                     <div class="float-right" style="float:right;">
                        
                     </div>
                  </div>
                  <div class="box-body">
                     <div class="table-responsive">
                      <form action="<?php echo base_url('insert-hotel-dsr') ?>" method="POST"> 
                        <table id="complex_header" class="text-fade table table-bordered display text-center" style="width:100%" >
                           <thead >
                              <tr class="text-white bg-dark">
                                 <th>Report Date : <input type="date" name="report_date" value="<?php echo date('d-M-Y'); ?>"></th>
                                 <th colspan="3">Today</th>
                                 <th colspan="3">Monthly (MTD)</th>
                                 <th colspan="3">Yearly (YTD)</th>
                                 
                              </tr>
                              
                            <!-- Room Analysis section starts from here -->

                              <tr class="text-white bg-dark">
                                <th>Room Analysis</th>
                                <td>Actual</td>
                                <td>Budget</td>

                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>

                                
                              </tr>
                             <?php foreach ($room_analysis as $each) { ?>
                             
                              <tr>
                                <th><?php echo $each->room_analysis_name ?></th>
                                <td>
                                  
                                  <input type="number" name="today_sale[]" class="form-control">
                                  <input type="hidden" name="today_budget[]" value="<?php echo floatval($each->daily_budget); ?>">
                                  
                                  <input type="hidden" name="item_id[]" value="<?php echo $each->room_analysis_id ?>">
                                  <input type="hidden" name="item_name[]" value="<?php echo $each->room_analysis_name ?>">

                                  <input type="hidden" name="item_type[]" value="room_analysis">


                                </td>

                                <td><?php echo floatval($each->daily_budget); ?></td>
                                
                                <td> <input type="number" name="today_variance[]" class="form-control" value="0"></td>
                                
                                
                                <td><input type="number" name="monthly_sale[]" class="form-control">
                                  <input type="hidden" name="monthly_budget[]" value="<?php echo floatval($each->monthly_budget); ?>">
                                  </td>
                                <td><?php echo floatval($each->monthly_budget); ?></td>
                                <td><input type="number" name="monthly_variance[]" class="form-control" value="0"></td>

                                <td><input type="number" name="yearly_sale[]" class="form-control">
                                  <input type="hidden" name="yearly_budget[]" value="<?php echo floatval($each->yearly_budget); ?>">
                                  
                                <td><?php echo floatval($each->yearly_budget); ?></td>
                                <td><input type="number" name="yearly_variance[]" class="form-control" value="0"></td></td>

                              </tr>

                               <?php } ?>


                               <!-- Revenue  section starts from here -->

                              <tr class="text-white bg-dark">
                                <th>Revenue</th>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>

                              </tr>
                             <?php foreach ($restaurants as $each) { ?>
                             
                              <tr>
                                <th><?php echo $each->restaurants_name ?></th>
                                <td>
                                  <input type="number" name="today_sale[]" class="form-control">
                                  <input type="hidden" name="today_budget[]" value="<?php echo floatval($each->daily_budget); ?>">
                                  

                                  <input type="hidden" name="item_id[]" value="<?php echo $each->restaurants_id ?>">
                                  <input type="hidden" name="item_name[]" value="<?php echo $each->restaurants_name ?>">
                                  <input type="hidden" name="item_type[]" value="restaurants">


                                </td>
                                 <td><?php echo floatval($each->daily_budget); ?></td>
                                <td> <input type="number" name="today_variance[]" class="form-control" value="0"></td>
                               
                                
                                <td><input type="number" name="monthly_sale[]" class="form-control">
                                  <input type="hidden" name="monthly_budget[]" value="<?php echo floatval($each->monthly_budget); ?>">
                                  </td>
                                <td><?php echo floatval($each->monthly_budget); ?></td>
                                <td><input type="number" name="monthly_variance[]" class="form-control" value="0"></td>

                                <td><input type="number" name="yearly_sale[]" class="form-control">
                                  <input type="hidden" name="yearly_budget[]" value="<?php echo floatval($each->yearly_budget); ?>">
                                  
                                <td><?php echo floatval($each->yearly_budget); ?></td>
                                <td><input type="number" name="yearly_variance[]" class="form-control" value="0"></td></td>

                              </tr>

                               <?php } ?>

                               <!-- other Revenue  section starts from here -->

                              <tr class="text-white bg-dark">
                                <th>Other Revenue</th>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>
                                <td>Actual</td>
                                <td>Budget</td>
                                <td>Variance</td>

                                
                              </tr>
                             <?php foreach ($other_revenue as $each) { ?>
                             
                                <tr>
                                <th><?php echo $each->other_revenue_name ?></th>
                                <td>
                                  <input type="number" name="today_sale[]" class="form-control">
                                  <input type="hidden" name="today_budget[]" value="<?php echo floatval($each->daily_budget); ?>">
                                  <input type="hidden" name="item_id[]" value="<?php echo $each->other_revenue_id  ?>">
                                  <input type="hidden" name="item_name[]" value="<?php  echo $each->other_revenue_name ?>">
                                  <input type="hidden" name="item_type[]" value="other_revenue">


                                </td>
                                <td><?php echo floatval($each->daily_budget); ?></td>
                                <td> <input type="number" name="today_variance[]" class="form-control"></td>
                                
                                
                                <td><input type="number" name="monthly_sale[]" class="form-control">
                                  <input type="hidden" name="monthly_budget[]" value="<?php echo floatval($each->monthly_budget); ?>">
                                  </td>
                                <td><?php echo floatval($each->monthly_budget); ?></td>
                                <td><input type="number" name="monthly_variance[]" class="form-control"></td>

                                <td><input type="number" name="yearly_sale[]" class="form-control">
                                  <input type="hidden" name="yearly_budget[]" value="<?php echo floatval($each->yearly_budget); ?>">
                                  
                                <td><?php echo floatval($each->yearly_budget); ?></td>
                                <td><input type="number" name="yearly_variance[]" class="form-control"></td></td>

                              </tr>

                               <?php } ?>


                             <!--   row for collection details start from here-->

                             

                              <tr style="text-align: left;" class="text-white bg-dark">
                                <th colspan="10" class="text-left"><b>COLLECTION DETAILS</b></th>
                              </tr>

                              <tr>
                                <th>CASH</th>

                                <td><input type="number" name="total_cash_today_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cash_today_budget" class="form-control" value="0"></td>
                                
                                <td><input type="number" name="total_cash_today_variance" class="form-control" value="0"></td>

                                <td><input type="number" name="total_cash_monthly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cash_monthly_budget" class="form-control" value="0"></td>
                                
                                <td><input type="number" name="total_cash_monthly_variance" class="form-control" value="0"></td>

                                <td><input type="number" name="total_cash_yearly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cash_yearly_budget" class="form-control" value="0"></td>
                                
                                <td><input type="number" name="total_cash_yearly_variance" class="form-control" value="0"></td>
                               

                                
                              </tr>

                              <tr>
                                <th>CREDIT CARD</th>

                                <td><input type="number" name="total_credit_card_today_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_credit_card_today_budget" class="form-control" value="0"></td>
                                <td><input type="number" name="total_credit_card_today_variance" class="form-control" value="0"></td>

                                
                                <td><input type="number" name="total_credit_card_monthly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_credit_card_monthly_budget" class="form-control" value="0"></td>
                                <td><input type="number" name="total_credit_card_monthly_variance" class="form-control" value="0"></td>

                                <td><input type="number" name="total_credit_card_yearly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_credit_card_yearly_budget" class="form-control" value="0"></td>
                                
                                <td><input type="number" name="total_credit_card_yearly_variance" class="form-control" value="0"></td>
                                

                                
                              </tr>

                               <tr>
                                <th>CHEQUES</th>


                                <td><input type="number" name="total_cheque_today_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cheque_today_budget" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cheque_today_variance" class="form-control" value="0"></td>

                                <td><input type="number" name="total_cheque_monthly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cheque_monthly_budget" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cheque_monthly_variance" class="form-control" value="0"></td>


                                 <td><input type="number" name="total_cheque_yearly_sale" class="form-control" value="0"></td>
                                <td><input type="number" name="total_cheque_yearly_budget" class="form-control" value="0"></td>
                               <td><input type="number" name="total_cheque_yearly_variance" class="form-control" value="0"></td>
                                

                                
                              </tr>

                               <!--   row for total collection  start from here-->

                              <tr style="text-align: center;" class="text-white bg-dark">
                                <th  class="text-left"><b>Total Collection</b></th>
                                 
                                 <td><input type="number" name="total_collection_today_actual" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_today_budget" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_today_variance" class="form-control" value="0"></td>

                                 <td><input type="number" name="total_collection_monthly_actual" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_monthly_budget" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_monthly_variance" class="form-control" value="0"></td>


                                 <td><input type="number" name="total_collection_yearly_actual" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_yearly_budget" class="form-control" value="0"></td>
                                 <td><input type="number" name="total_collection_yearly_variance" class="form-control" value="0"></td>

                                 
                                
                               
                              </tr>

                           </thead>
                           
                        </table>

                      <a href="<?php echo base_url('view-hotel-dsr') ?>">
                        <button onclick="window.history.back()" class="btn btn-primary">Go Back</button>

                      </a>
                      <button class="btn btn-warning float-right" style="float: right;" type="submit"> Submit </button>
                      
                      
                        </form>
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