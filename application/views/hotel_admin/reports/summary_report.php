<!-- Content Wrapper. Contains page content -->
<style>
    .theme-primary .dt-buttons .dt-button {
        background-color: #f3f1edff !important
    }

    .theme-primary .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border: 1px solid #23211d;
        background-color: #ffffff;
    }

    .dataTables_scrollHeadInner {
        width: 100%important;
    }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Summary Report</h4>
                            <div class="float-right" style="float:right;">





                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>

                            <div class="row">

                                <form method="GET" action="<?= base_url('Reports'); ?>" class="mb-4 px-3">
                                    <div class="row g-3 align-items-end">
                                        <!-- Existing filters (City, Property, etc.) -->

                                        <!-- Department -->
                                        <div class="col-md-3">
                                            <label for="department" class="form-label">Department</label>
                                            <select name="department[]" class="form-select filter-input" multiple id="department">
                                                <?php foreach ($departments as $dept) { ?>
                                                    <option value="<?= $dept->department_id; ?>"><?= $dept->department_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>




                                        <!-- Date Filters -->
                                        <!-- 🆕 Date Filters -->
                                        <div class="col-md-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $this->input->get('start_date'); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $this->input->get('end_date'); ?>">
                                        </div>


                                    </div>
                                </form>





                            </div>

                            <div class="">


                            </div>

                            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto; overflow-x: auto;">


                                <div class="container mt-4">
                                    <table id="leadReportTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Row Label</th>
                                                <th>Total Leads</th>
                                                <th>Business</th>
                                                <th>Non-Business</th>
                                                <th>Materialized</th>
                                                <th>Revenue</th>
                                                <th>Mat %</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php foreach ($report_data as $dept): ?>

                                                <?php
                                                // Reset department totals
                                                $dept_total = 0;
                                                $dept_business = 0;
                                                $dept_non_business = 0;
                                                $dept_materialized = 0;
                                                $dept_revenue = 0;

                                                // Calculate department totals
                                                foreach ($dept['channels'] as $ch) {
                                                    $dept_total        += $ch['total_leads'];
                                                    $dept_business     += $ch['business'];
                                                    $dept_non_business += $ch['non_business'];
                                                    $dept_materialized += $ch['materialized'];
                                                    $dept_revenue      += $ch['revenue_sum'];
                                                }

                                                $matPercent = ($dept_business > 0)
                                                    ? number_format(($dept_materialized / $dept_business) * 100, 1)
                                                    : 0;
                                                ?>

                                                <!-- ✅ DEPARTMENT MAIN ROW -->
                                                <tr style="background:#f0f0f0; font-weight:bold;">
                                                    <td><?= $dept['department_name'] ?></td>
                                                    <td><?= $dept_total ?></td>
                                                    <td><?= $dept_business ?></td>
                                                    <td><?= $dept_non_business ?></td>
                                                    <td><?= $dept_materialized ?></td>
                                                    <td><?= number_format($dept_revenue, 2) ?></td>
                                                    <td><?= $matPercent ?>%</td>
                                                </tr>

                                                <!-- ✅ SUB ROWS (USER CHANNELS) -->
                                                <?php foreach ($dept['channels'] as $channelName => $ch): ?>

                                                    <?php
                                                    $total        = $ch['total_leads'];
                                                    $business     = $ch['business'];
                                                    $non_business = $ch['non_business'];
                                                    $materialized = $ch['materialized'];
                                                    $revenue      = $ch['revenue_sum'];

                                                    $matPercentCh = ($business > 0)
                                                        ? number_format(($materialized / $business) * 100, 1)
                                                        : 0;
                                                    ?>

                                                    <tr>
                                                        <td style="padding-left:25px;">→ <?= $channelName ?></td>
                                                        <td><?= $total ?></td>
                                                        <td><?= $business ?></td>
                                                        <td><?= $non_business ?></td>
                                                        <td><?= $materialized ?></td>
                                                        <td><?= number_format($revenue, 2) ?></td>
                                                        <td><?= $matPercentCh ?>%</td>
                                                    </tr>

                                                <?php endforeach; ?>

                                            <?php endforeach; ?>

                                        </tbody>






                                    </table>


                                </div>
                            </div>
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






<script>
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>




<script>
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>





<script>
    $(document).ready(function() {
        var table;
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yy = String(today.getFullYear()).slice(-2);
        var fileName = 'Lead_Report_' + dd + '-' + mm + '-' + yy;

        // Function to initialize DataTable
        function initDataTable() {
            table = $('#leadReportTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                scrollX: true,
                ordering: false,

                responsive: true,
                stripeClasses: [], // ✅ Remove row striping (THIS FIXES YOUR UI)

                info: true,
                autoWidth: false,
                responsive: false,

                stripeClasses: [], // ✅ Remove row striping (THIS FIXES YOUR UI)
                destroy: true, // <-- Important for re-initialization
                buttons: [{
                        extend: 'colvis',
                        text: 'Select Columns'
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Lead Report',
                        filename: fileName,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Lead Report',
                        filename: fileName,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Lead Report',
                        filename: fileName,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Lead Report',
                        filename: fileName,
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });
        }

        // Initialize initially
        initDataTable();

        // Function to fetch filtered data
        function fetchLeads(reset = false) {
            let filters = {
                property: $('#property').val(),
                department: $('#department').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
            };

            $.ajax({
                url: "<?= base_url('hotelAdmin/Reports/filter_summary_report') ?>",
                method: "POST",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    if (reset) $('#leadReportTable').html('<p>Loading...</p>');
                },
                success: function(response) {
                    if (response.status) {
                        // Replace table body HTML only
                        $('#leadReportTable').html(response.html);

                        // Reinitialize DataTable with new data
                        initDataTable();
                    } else {
                        $('#leadReportTable').html('<p>No records found.</p>');
                    }
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        }

        // Trigger fetch on filter change
        $(document).on('change', '#property, #department, #start_date, #end_date', function() {
            console.log("Filters changed");
            fetchLeads(true);
        });
    });
</script>