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

                                <form method="GET" action="<?= base_url('reports-agency'); ?>" class="mb-4 px-3">
                                    <div class="row align-items-end">
                                        <!-- Existing filters (City, Property, etc.) -->


                                        <div class="col-md-2">
                                            <label for="property" class="form-label">Property</label>
                                            <select name="property" class="form-select">
                                                <option value="">All Properties</option>
                                                <?php foreach ($hotel_admin as $property) { ?>
                                                    <option value="<?= $property->hotel_id; ?>" <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                                        <?= $property->hotel_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="department" class="form-label">Department</label>
                                            <select name="department" class="form-select">
                                                <option value="">All Departments</option>
                                                <?php foreach ($departments as $dept) { ?>
                                                    <option value="<?= $dept->department_id; ?>" <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
                                                        <?= $dept->department_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>



                                        <div class="col-md-2">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="">All</option>
                                                <option value="Open" <?= ($this->input->get('status') == 'Open') ? 'selected' : ''; ?>>Open</option>
                                                <option value="In Progress" <?= ($this->input->get('status') == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="On Hold" <?= ($this->input->get('status') == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                                <option value="Closed" <?= ($this->input->get('status') == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="channel" class="form-label">Lead Source</label>
                                            <select name="channel" class="form-select">
                                                <option value="">All</option>
                                                <?php foreach ($user_channel as $channelObj): ?>
                                                    <?php $channel = $channelObj->user_channel; ?>
                                                    <option value="<?= $channel ?>" <?= ($this->input->get('channel') == $channel) ? 'selected' : ''; ?>>
                                                        <?= strtoupper($channel) ?>
                                                    </option>
                                                <?php endforeach; ?>


                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="disposition" class="form-label">Stage</label>
                                            <select class="form-select" name="disposition">
                                                <option value="">Select Stage</option>
                                                <option value="Information/Enquiry" <?= ($this->input->get('disposition') == 'Information/Enquiry') ? 'selected' : ''; ?>>Information/Enquiry</option>
                                                <option value="Reservation" <?= ($this->input->get('disposition') == 'Reservation') ? 'selected' : ''; ?>>Reservation</option>
                                                <option value="Shopping - Follow up" <?= ($this->input->get('disposition') == 'Shopping - Follow up') ? 'selected' : ''; ?>>Shopping - Follow up</option>
                                                <option value="Shopping - No Follow up" <?= ($this->input->get('disposition') == 'Shopping - No Follow up') ? 'selected' : ''; ?>>Shopping - No Follow up</option>
                                                <option value="Shopping - Follow up (Reservation)" <?= ($this->input->get('disposition') == 'Shopping - Follow up (Reservation)') ? 'selected' : ''; ?>>Shopping - Follow up (Reservation)</option>
                                                <option value="Shopping - Follow up (No Reservation)" <?= ($this->input->get('disposition') == 'Shopping - Follow up (No Reservation)') ? 'selected' : ''; ?>>Shopping - Follow up (No Reservation)</option>
                                                <option value="Trash" <?= ($this->input->get('disposition') == 'Trash') ? 'selected' : ''; ?>>Trash</option>
                                                <option value="Enquiry not received" <?= ($this->input->get('disposition') == 'Enquiry not received') ? 'selected' : ''; ?>>Enquiry not received</option>
                                                <option value="Denied" <?= ($this->input->get('disposition') == 'Denied') ? 'selected' : ''; ?>>Denied</option>
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
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>




<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>





<script>
    $(document).ready(function() {


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