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
         <div class="d-flex align-items-center">
            <div class="me-auto">
               <h4 class="page-title">View DSR Details</h4>
               <div class="d-inline-block align-items-center">
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Admin </li>
                        <li class="breadcrumb-item active" aria-current="page">View DSR Details ><?php echo $hotel_data->hotel_name; ?></li>
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
                     <h3 class="" style="text-align: center;font-weight: bold;"><?php echo $hotel_data->hotel_name; ?></h3>
                     <h5 class="text-center">AS ON DATE <?php echo $daily_sale_report->report_date ?></h5>
                     <div class="float-right" style="float:right;">
                     </div>
                  </div>
                  <div class="box-body">
                     <div class="table-responsive">
                        <table id="complex_header" class="text-fade table table-bordered display text-center" style="width:100%">
                           <thead>
                              <tr class="text-white bg-dark">
                                 <th>A</th>
                                 <th colspan="3">Today</th>
                                 <th colspan="3">Monthly (MTD)</th>
                                 <th colspan="3">Yearly (YTD)</th>
                              </tr>
                              <!-- Room Analysis Section -->
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
                           </thead>
                           <tbody>
                              <?php foreach ($room_analysis as $each): ?>
                              <tr>
                                 <th><?php echo $each->room_analysis_name; ?></th>
                                 <td><?php echo number_format($each->today_sale, 2); ?></td>
                                 <td><?php echo number_format($each->daily_budget, 2); ?></td>
                                 <td><?php echo number_format($each->today_variance, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_variance, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_variance, 2); ?></td>
                              </tr>
                              <?php endforeach; ?>
                              <!-- Repeat similar blocks for Revenue and Other Revenue -->
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
                              <?php foreach ($restaurants as $each): ?>
                              <tr>
                                 <th><?php echo $each->restaurants_name; ?></th>
                                 <td><?php echo number_format($each->today_sale, 2); ?></td>
                                 <td><?php echo number_format($each->daily_budget, 2); ?></td>
                                 <td><?php echo number_format($each->today_variance, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_variance, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_variance, 2); ?></td>
                              </tr>
                              <?php endforeach; ?>
                              <!-- Add blocks for other revenue and collection details -->
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
                                 <th><?php echo $each->other_revenue_name; ?></th>
                                 <td><?php echo number_format($each->today_sale, 2); ?></td>
                                 <td><?php echo number_format($each->daily_budget, 2); ?></td>
                                 <td><?php echo number_format($each->today_variance, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->monthly_variance, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_sale, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_budget, 2); ?></td>
                                 <td><?php echo number_format($each->yearly_variance, 2); ?></td>
                              </tr>
                              <?php } ?>
                              <!-- Row for collection details -->
                              <tr class="text-white bg-dark">
                                 <th colspan="10" class="text-left" style="text-align: left;"><b>COLLECTION DETAILS</b></th>
                              </tr>
                              <!-- Cash collection row -->
                              <tr>
                                 <th>CASH</th>
                                 <td><?php echo isset($daily_sale_report->total_cash_today_sale) ? $daily_sale_report->total_cash_today_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_today_budget) ? $daily_sale_report->total_cash_today_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_today_variance) ? $daily_sale_report->total_cash_today_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_monthly_sale) ? $daily_sale_report->total_cash_monthly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_monthly_budget) ? $daily_sale_report->total_cash_monthly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_monthly_variance) ? $daily_sale_report->total_cash_monthly_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_yearly_sale) ? $daily_sale_report->total_cash_yearly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_yearly_budget) ? $daily_sale_report->total_cash_yearly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cash_yearly_variance) ? $daily_sale_report->total_cash_yearly_variance : 0; ?></td>
                              </tr>
                              <tr>
                                 <th>CREDIT CARD</th>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_today_sale) ? $daily_sale_report->total_credit_card_today_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_today_budget) ? $daily_sale_report->total_credit_card_today_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_today_variance) ? $daily_sale_report->total_credit_card_today_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_monthly_sale) ? $daily_sale_report->total_credit_card_monthly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_monthly_budget) ? $daily_sale_report->total_credit_card_monthly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_monthly_variance) ? $daily_sale_report->total_credit_card_monthly_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_yearly_sale) ? $daily_sale_report->total_credit_card_yearly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_yearly_budget) ? $daily_sale_report->total_credit_card_yearly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_credit_card_yearly_variance) ? $daily_sale_report->total_credit_card_yearly_variance : 0; ?></td>
                              </tr>
                              <tr>
                                 <th>CHEQUES</th>
                                 <td><?php echo isset($daily_sale_report->total_cheque_today_sale) ? $daily_sale_report->total_cheque_today_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_today_budget) ? $daily_sale_report->total_cheque_today_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_today_variance) ? $daily_sale_report->total_cheque_today_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_monthly_sale) ? $daily_sale_report->total_cheque_monthly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_monthly_budget) ? $daily_sale_report->total_cheque_monthly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_monthly_variance) ? $daily_sale_report->total_cheque_monthly_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_yearly_sale) ? $daily_sale_report->total_cheque_yearly_sale : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_yearly_budget) ? $daily_sale_report->total_cheque_yearly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_cheque_yearly_variance) ? $daily_sale_report->total_cheque_yearly_variance : 0; ?></td>
                              </tr>
                              <tr class="text-white bg-dark">
                                 <th class="text-left"><b>Total Collection</b></th>
                                 <td><?php echo isset($daily_sale_report->total_collection_today_actual) ? $daily_sale_report->total_collection_today_actual : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_today_budget) ? $daily_sale_report->total_collection_today_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_today_variance) ? $daily_sale_report->total_collection_today_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_monthly_actual) ? $daily_sale_report->total_collection_monthly_actual : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_monthly_budget) ? $daily_sale_report->total_collection_monthly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_monthly_variance) ? $daily_sale_report->total_collection_monthly_variance : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_yearly_actual) ? $daily_sale_report->total_collection_yearly_actual : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_yearly_budget) ? $daily_sale_report->total_collection_yearly_budget : 0; ?></td>
                                 <td><?php echo isset($daily_sale_report->total_collection_yearly_variance) ? $daily_sale_report->total_collection_yearly_variance : 0; ?></td>
                              </tr>
                           </tbody>
                        </table>
                       
                        <button onclick="window.history.back()" class="btn btn-primary">Go Back</button>

                       
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