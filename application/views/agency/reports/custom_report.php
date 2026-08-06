<!-- Content Wrapper. Contains page content -->
<style>
    .theme-primary .dt-buttons .dt-button {
        background-color: #f3f1edff !important
    }

    .theme-primary .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border: 1px solid #23211d;
        background-color: #ffffff;
    }

    .agency-report-filters .select2-container {
        width: 100% !important;
    }

    .agency-report-filters .select2-container .select2-selection--single {
        align-items: center;
        background: #fff;
        border: 1px solid transparent;
        border-radius: 8px;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px;
        display: flex;
        height: 46px;
        padding: 0 14px;
    }

    .agency-report-filters .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 0 !important;
        padding-right: 24px !important;
    }

    .agency-report-filters .select2-selection__arrow {
        height: 44px !important;
        right: 8px !important;
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

                                <form method="POST" action="<?= base_url('reports-agency'); ?>" class="mb-4 px-3 agency-report-filters">
                                    <input type="hidden"
                                        name="<?= htmlspecialchars($this->security->get_csrf_token_name(), ENT_QUOTES, 'UTF-8'); ?>"
                                        value="<?= htmlspecialchars($this->security->get_csrf_hash(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="row align-items-end">
                                        <!-- Existing filters (City, Property, etc.) -->


                                        <div class="col-md-2">
                                            <label for="property" class="form-label">Property</label>
                                            <select id="property" name="property" class="form-select agency-report-select">
                                                <option value="">All Properties</option>
                                                <?php foreach ($hotel_admin as $property) { ?>
                                                    <option value="<?= (int) $property->hotel_id; ?>" <?= ((string) $filters['property'] === (string) $property->hotel_id) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($property->hotel_name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="department" class="form-label">Department</label>
                                            <select id="department" name="department" class="form-select agency-report-select">
                                                <option value="">All Departments</option>
                                                <?php foreach ($departments as $dept) { ?>
                                                    <option value="<?= (int) $dept->department_id; ?>" <?= ((string) $filters['department'] === (string) $dept->department_id) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($dept->department_name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>



                                        <div class="col-md-2">
                                            <label for="status" class="form-label">Status</label>
                                            <select id="status" name="status" class="form-select agency-report-select">
                                                <option value="">All</option>
                                                <option value="Open" <?= ($filters['status'] === 'Open') ? 'selected' : ''; ?>>Open</option>
                                                <option value="In Progress" <?= ($filters['status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="On Hold" <?= ($filters['status'] === 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                                <option value="Closed" <?= ($filters['status'] === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="channel" class="form-label">Lead Source</label>
                                            <select id="channel" name="channel" class="form-select agency-report-select">
                                                <option value="">All</option>
                                                <?php foreach ($user_channel as $channelObj): ?>
                                                    <?php $channel = $channelObj->user_channel; ?>
                                                    <option value="<?= htmlspecialchars($channel, ENT_QUOTES, 'UTF-8'); ?>" <?= ($filters['channel'] === $channel) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars(strtoupper($channel), ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>


                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="disposition" class="form-label">Stage</label>
                                            <select id="disposition" class="form-select agency-report-select" name="disposition">
                                                <option value="">Select Stage</option>
                                                <option value="Information/Enquiry" <?= ($filters['disposition'] === 'Information/Enquiry') ? 'selected' : ''; ?>>Information/Enquiry</option>
                                                <option value="Reservation" <?= ($filters['disposition'] === 'Reservation') ? 'selected' : ''; ?>>Reservation</option>
                                                <option value="Shopping - Follow up" <?= ($filters['disposition'] === 'Shopping - Follow up') ? 'selected' : ''; ?>>Shopping - Follow up</option>
                                                <option value="Shopping - No Follow up" <?= ($filters['disposition'] === 'Shopping - No Follow up') ? 'selected' : ''; ?>>Shopping - No Follow up</option>
                                                <option value="Shopping - Follow up (Reservation)" <?= ($filters['disposition'] === 'Shopping - Follow up (Reservation)') ? 'selected' : ''; ?>>Shopping - Follow up (Reservation)</option>
                                                <option value="Shopping - Follow up (No Reservation)" <?= ($filters['disposition'] === 'Shopping - Follow up (No Reservation)') ? 'selected' : ''; ?>>Shopping - Follow up (No Reservation)</option>
                                                <option value="Trash" <?= ($filters['disposition'] === 'Trash') ? 'selected' : ''; ?>>Trash</option>
                                                <option value="Enquiry not received" <?= ($filters['disposition'] === 'Enquiry not received') ? 'selected' : ''; ?>>Enquiry not received</option>
                                                <option value="Denied" <?= ($filters['disposition'] === 'Denied') ? 'selected' : ''; ?>>Denied</option>
                                            </select>
                                        </div>

                                        <!-- Date Filters -->
                                        <!-- 🆕 Date Filters -->
                                        <div class="col-md-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-2 d-grid">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>

                                    </div>
                                </form>
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






<script>
    window.addEventListener('load', function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>




<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>





<script>
    $(document).ready(function() {

        $('.agency-report-select').select2({
            width: '100%'
        });


        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yy = String(today.getFullYear()).slice(-2);
        var fileName = 'Lead_Report_' + dd + '-' + mm + '-' + yy;


        $('#leadReportTable').DataTable({

            pageLength: 50,
            scrollX: true,
            responsive: true

        });
    });
</script>
