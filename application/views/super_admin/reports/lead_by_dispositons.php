<!-- Content Wrapper. Contains page content -->
<style>
    .theme-primary .dt-buttons .dt-button {
        background-color: #f3f1edff !important
    }

    .theme-primary .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border: 1px solid #23211d;
        background-color: #ffffff;
    }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-bar-chart"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Stage-wise Lead Report</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Super Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Reports</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Stage-wise Lead Report</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="">
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Stage-wise Lead Report</h4>
                            <div class="float-right" style="float:right;">





                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>

                            <div>

                                <form method="GET" action="<?= base_url('Reports'); ?>" class="mb-4">
                                    <div class="row g-3 align-items-end">
                                        <!-- Existing filters (City, Property, etc.) -->

                                        <div class="col-md-3">
                                            <label for="property" class="form-label">Property</label>
                                            <select name="property[]" class="form-select filter-input" multiple id="property">
                                                <?php foreach ($properties as $property) { ?>
                                                    <option value="<?= $property->hotel_id; ?>"><?= $property->hotel_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
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


                                <div>
                                    <table id="leadReportTable" class="text-fade table table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Stage</th>
                                                <th>Count</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $grand_total = 0; ?>
                                            <?php foreach ($leads as $lead): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($lead['disposition']) ?></td>
                                                    <td>

                                                        <?php $grand_total += $lead['total']; ?>
                                                        <?= htmlspecialchars($lead['total']) ?></td>


                                                </tr>
                                            <?php endforeach; ?>

                                            <tr>
                                                <th>Total</th>
                                                <td><?php echo $grand_total ?></td>
                                            </tr>
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





<script>
    (function($) {
    $(document).ready(function() {
        var table;

        // Function to initialize DataTable
        function initDataTable() {
            table = $('#leadReportTable').DataTable({
                dom: 'lfrtip',
                pageLength: 50,
                ordering: false,
                scrollX: true,
                responsive: true,
                destroy: true // Important for re-initialization
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
            filters[window.CSRF.name] = window.CSRF.hash;

            $.ajax({
                url: "<?= base_url('Reports/filter_lead_by_dispostion') ?>",
                method: "POST",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    if (reset) $('#leadReportTable').html('<p>Loading...</p>');
                },
                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }
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
    })(window.jQuery);
</script>
