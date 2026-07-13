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
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Lead Reports</h4>
                            <div class="float-right" style="float:right;">





                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>

                            <div>

                                <div id="filter-section">
                                    <div class="mb-4 px-3">
                                        <div class="row g-3 align-items-end">
                                            <!-- Property -->

                                            <!-- Department -->
                                            <div class="col-md-4">
                                                <label for="department" class="form-label">Department</label>
                                                <select name="department[]" class="form-select filter-input" multiple id="department">
                                                    <?php foreach ($departments as $dept) { ?>
                                                        <option value="<?= $dept->department_id; ?>"><?= $dept->department_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!-- Status -->
                                            <div class="col-md-4">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status[]" class="form-select filter-input" multiple id="status">
                                                    <option value="Open">Open</option>
                                                    <option value="In Progress">In Progress</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                            </div>
                                            <!-- Lead Source -->
                                            <div class="col-md-4">
                                                <label for="channel" class="form-label">Lead Source</label>
                                                <select name="channel[]" class="form-select filter-input" multiple id="channel">
                                                    <?php foreach ($user_channel as $channelObj): ?>
                                                        <?php $channel = $channelObj->user_channel; ?>
                                                        <option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Stage -->
                                            <div class="col-md-3">
                                                <label for="disposition" class="form-label">Stage</label>
                                                <select class="form-select filter-input" name="disposition[]" multiple id="disposition">
                                                    <option value="Not Contacted">Not Contacted</option>
                                                    <option value="General Information">General Information</option>
                                                    <option value="Quotation Sent">Quotation Sent</option>
                                                    <option value="Negotiations">Negotiations</option>
                                                    <option value="Contract Done">Contract Done</option>
                                                    <option value="Advance Received">Advance Received</option>
                                                    <option value="Lead Won">Lead Won</option>
                                                    <option value="Lead Lost">Lead Lost</option>
                                                </select>
                                            </div>
                                            <!-- Business / Non-Business -->
                                            <div class="col-md-3">
                                                <label for="business_type" class="form-label">Business Type</label>
                                                <select name="business_type[]" class="form-select filter-input" id="business_type">
                                                    <option value="">Select Business</option>
                                                    <option value="business">Business</option>
                                                    <option value="non_business">Non-Business</option>
                                                </select>
                                            </div>
                                            <!-- Start Date -->
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" class="form-control filter-input" id="start_date">
                                            </div>
                                            <!-- End Date -->
                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" name="end_date" class="form-control filter-input" id="end_date">
                                            </div>
                                            <!-- Search -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="">


                            </div>

                            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto; overflow-x: auto;">


                                <div class="container mt-4">
                                    <table id="leadReportTable" class="display nowrap table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>

                                                <th>Lead ID</th>
                                                <th>City</th>
                                                <th>Property</th>
                                                <th>Department</th>
                                                <th>Guest Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Stage</th>
                                                <th>Source</th>
                                                <th>Created Date</th>
                                                <th>Response Date</th>
                                                <th>Completed Date</th>
                                                <th>Booking Date</th>
                                                <th>Check In Date</th>
                                                <th>Check Out Date</th>

                                                <th>Follow up 1</th>
                                                <th>Follow up 2</th>
                                                <th>Pax</th>

                                                <th>Query</th>
                                                <th>Remark</th>
                                                <th>Revenue</th>
                                                <th>Materialization</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($leads as $lead): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($lead['id']) ?></td>
                                                    <td><?= htmlspecialchars($lead['city_name']) ?></td>
                                                    <td><?= htmlspecialchars($lead['hotel_name']) ?></td>
                                                    <td><?= htmlspecialchars($lead['department_name']) ?></td>
                                                    <td><?= htmlspecialchars($lead['user_name']) ?></td>
                                                    <td><?= htmlspecialchars($lead['phone_number']) ?></td>
                                                    <td><?= htmlspecialchars($lead['email']) ?></td>
                                                    <td><?= htmlspecialchars($lead['status']) ?></td>
                                                    <td><?= htmlspecialchars($lead['disposition']) ?></td>
                                                    <td><?= htmlspecialchars($lead['user_channel']) ?></td>




                                                    <td><?= date('d M Y, h:i A', strtotime($lead['created_at'])) ?></td>


                                                    <td>
                                                        <?= !empty($lead['responded_time']) && strtotime($lead['responded_time']) ? date('d M Y, h:i A', strtotime($lead['responded_time'])) : 'NA' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($lead['completed_time']) && strtotime($lead['completed_time']) ? date('d M Y, h:i A', strtotime($lead['completed_time'])) : 'NA' ?>
                                                    </td>








                                                    <td>
                                                        <?= !empty($lead['booking_enquiry_date']) && strtotime($lead['booking_enquiry_date']) ? date('d M Y, h:i A', strtotime($lead['booking_enquiry_date'])) : 'NA' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($lead['checkin_date']) && strtotime($lead['checkin_date']) ? date('d M Y, h:i A', strtotime($lead['checkin_date'])) : 'NA' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($lead['checkout_date']) && strtotime($lead['checkout_date']) ? date('d M Y, h:i A', strtotime($lead['checkout_date'])) : 'NA' ?>
                                                    </td>

                                                    <td>
                                                        <?= !empty($lead['followup_date']) && strtotime($lead['followup_date']) ? date('d M Y', strtotime($lead['followup_date'])) : 'NA' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($lead['second_followup_date']) && strtotime($lead['second_followup_date']) ? date('d M Y', strtotime($lead['second_followup_date'])) : 'NA' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($lead['pax']) ? htmlspecialchars($lead['pax']) : 'NA' ?>
                                                    </td>




                                                    <td><?= nl2br(htmlspecialchars($lead['query'])) ?></td>
                                                    <td><?= nl2br(htmlspecialchars($lead['remark'])) ?></td>
                                                    <td>
                                                        <?= number_format((float)($lead['amount'] ?? 0), 2) ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $isMaterialized = (
                                                            strtolower($lead['disposition']) === 'reservation' &&
                                                            strtolower($lead['status']) === 'closed'
                                                        );

                                                        echo $isMaterialized ? 'Yes' : 'No';
                                                        ?>
                                                    </td>



                                                </tr>
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function() {

        // 🔹 Toggle filter section
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });

        // 🔹 Set dynamic export filename
        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const yy = String(today.getFullYear()).slice(-2);
        const fileName = 'Lead_Report_' + dd + '-' + mm + '-' + yy;

        let table; // keep a reference to DataTable instance

        // 🔹 Initialize DataTable
        function initDataTable() {
            // Destroy existing DataTable before reinitializing
            if ($.fn.DataTable.isDataTable('#leadReportTable')) {
                $('#leadReportTable').DataTable().destroy();
            }

            table = $('#leadReportTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                scrollX: true,
                ordering: false,
                responsive: true,
                stripeClasses: [],
                info: true,
                autoWidth: false,
                destroy: true,
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
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });
        }


        initDataTable();

        // 🔹 Fetch and re-render leads
        function fetchLeads(reset = false) {
            const filters = {
                property: $('#property').val(),
                department: $('#department').val(),
                status: $('#status').val(),
                channel: $('#channel').val(),
                disposition: $('#disposition').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                search: $('#lead-search').val(),
                business_type: $('#business_type').val() || []
            };

            $.ajax({
                url: "<?= base_url('hotelAdmin/Reports/filter_custom_report') ?>",
                method: "POST",
                data: filters,
                dataType: "json",

                beforeSend: function() {
                    if (reset) $('#leadReportTable').html('<p>Loading...</p>');
                },
                success: function(response) {
                    if (response.status) {
                        $('#leadReportTable').html(response.html);
                        initDataTable(); // ✅ re-init DataTable with new content
                    } else {
                        $('#leadReportTable').html('<p>No data found.</p>');
                    }
                },
                error: function() {
                    toastr.error('Failed to fetch leads. Please try again.');
                }
            });
        }



        // 🔹 Event binding for all filters (works for dynamic elements too)
        $(document).on('change', '#property, #department, #status, #channel, #disposition, #start_date, #end_date, #business_type', function() {
            fetchLeads(true);
        });

        // 🔹 Search input live filter
        $(document).on('keyup', '#lead-search', function() {
            fetchLeads(true);
        });

    });
</script>