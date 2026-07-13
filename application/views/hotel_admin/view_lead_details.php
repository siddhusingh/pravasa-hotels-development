<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ticket Details</title>
    <style>
        .ticket-label {
            font-weight: 600;
            color: #555;
        }

        .ticket-value {
            color: #000;
        }

        .ticket-section {
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 0;
        }

        .ticket-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
    </style>

    <style>
        /* Timeline Container */
        .timeline {
            position: relative !important;
            margin: 0 !important;
            padding-left: 40px !important;
            list-style: none !important;
            border-left: 3px solid #007bff !important;
        }

        /* Timeline Item */
        .timeline-item {
            position: relative !important;
            margin-bottom: 30px !important;
            padding-left: 20px !important;
            transition: all 0.3s ease !important;
            background-color: transparent !important;
            border-radius: 0 !important;
        }

        .timeline-item:hover {
            background-color: #f8f9fa !important;
            border-radius: 5px !important;
            padding-left: 25px !important;
        }

        /* Timeline Dot */
        .timeline-item::before {
            content: '' !important;
            position: absolute !important;
            left: -12px !important;
            top: 0 !important;
            width: 16px !important;
            height: 16px !important;
            background-color: #fff !important;
            border: 3px solid #007bff !important;
            border-radius: 50% !important;
            z-index: 1 !important;
            transition: transform 0.3s ease !important;
        }

        .timeline-item:hover::before {
            transform: scale(1.2) !important;
        }

        /* Status Header */
        .timeline-item h6 {
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: #007bff !important;
            margin-bottom: 5px !important;
        }

        /* Time & Byline */
        .timeline-item small {
            display: block !important;
            font-size: 0.85rem !important;
            color: #6c757d !important;
            margin-bottom: 6px !important;
        }

        /* Remarks */
        .timeline-item p {
            margin: 0 !important;
            font-size: 0.95rem !important;
            color: #333 !important;
        }

        /* No Remark Text */
        .timeline-item p em {
            color: #999 !important;
            font-style: italic !important;
        }
    </style>

</head>

<body class="bg-light">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Lead Details</h4>
                                <div class="float-right" style="float:right;">

                                    <!-- <a href="<?= base_url("LeadController/edit_lead/" . $lead_details->id) ?>" class="badge bg-warning-light"><i class="fa fa-edit"></i> Edit</a> -->


                                    <button type="button" class="btn btn-primary-light btn-sm" onclick="window.history.back();">
                                        Go Back
                                    </button>

                                </div>
                            </div>

                            <div class="container ">
                                <div class="ticket-container">

                                    <!-- Section 1 -->
                                    <div class="row ticket-section">
                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Property </div>
                                            <div class="ticket-value"><?= $lead_details->hotel_name ?></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">City</div>
                                            <div class="ticket-value">
                                                <div class="ticket-value"><?= $lead_details->city_name ?></div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Department</div>
                                            <div class="ticket-value">
                                                <div class="ticket-value"><?= $lead_details->department_name ?></div>

                                            </div>
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Name</div>
                                            <div class="ticket-value"><?= $lead_details->user_name ?></div>


                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Phone</div>
                                            <div class="ticket-value"><?= $lead_details->phone_number ?></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Email</div>
                                            <div class="ticket-value"><?= $lead_details->email ?></div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Lead Source</div>
                                            <div class="ticket-value"><?= $lead_details->user_channel ?></div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Lead Status</div>
                                            <div class="ticket-value"><?= $lead_details->status ?></div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Stage</div>
                                            <div class="ticket-value"><?= $lead_details->disposition ?></div>

                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Repeatative Guest</div>
                                            <div class="ticket-value">
                                                <?= !empty($lead_details->is_repeatative) && $lead_details->is_repeatative ? 'Yes' : 'No' ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Created By</div>
                                            <div class="ticket-value">
                                                <?php
                                                if (!empty($lead_details->creator_user_role) && !empty($lead_details->created_by)) {

                                                    if ($lead_details->creator_user_role == 'agent') {
                                                        $this->db->select('name');
                                                        $this->db->from('staff_members');
                                                        $this->db->where('id', $lead_details->created_by);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->name : 'NA';
                                                    } else if ($lead_details->creator_user_role == 'admin') {
                                                        $this->db->select('username');
                                                        $this->db->from('hotel_admin');
                                                        $this->db->where('hotel_id', $lead_details->created_by);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->username : 'NA';
                                                    } else if ($lead_details->creator_user_role == 'super_admin') {
                                                        $this->db->select('full_name');
                                                        $this->db->from('super_admin');
                                                        $this->db->where('id', $lead_details->created_by);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->full_name : 'NA';
                                                    } else {
                                                        echo "NA";
                                                    }
                                                } else {
                                                    echo "NA";
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Assigned to</div>
                                            <div class="ticket-value">
                                                <?php
                                                if (!empty($lead_details->assigned_person_user_role) && !empty($lead_details->assigned_to)) {
                                                    if ($lead_details->assigned_person_user_role == 'agent') {
                                                        $this->db->select('name');
                                                        $this->db->from('staff_members');
                                                        $this->db->where('id', $lead_details->assigned_to);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->name : 'NA';
                                                    } else if ($lead_details->assigned_person_user_role == 'admin') {
                                                        $this->db->select('username');
                                                        $this->db->from('hotel_admin');
                                                        $this->db->where('hotel_id', $lead_details->assigned_to);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->username : 'NA';
                                                    } else if ($lead_details->assigned_person_user_role == 'super_admin') {
                                                        $this->db->select('full_name');
                                                        $this->db->from('super_admin');
                                                        $this->db->where('id', $lead_details->assigned_to);
                                                        $query = $this->db->get();
                                                        $result = $query->row();
                                                        echo !empty($result) ? $result->full_name : 'NA';
                                                    } else {
                                                        echo "NA";
                                                    }
                                                } else {
                                                    echo "NA";
                                                }
                                                ?>
                                            </div>
                                        </div>




                                    </div>

                                    <!-- Section 2 -->
                                    <div class="row ticket-section">
                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Created Date</div>
                                            <div class="ticket-value">
                                                <?= !empty($lead_details->created_at)
                                                    ? date('M j, Y h:i A', strtotime($lead_details->created_at))
                                                    : 'NA' ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Responded Time</div>
                                            <div class="ticket-value">
                                                <?= !empty($lead_details->responded_time)
                                                    ? date('M j, Y h:i A', strtotime($lead_details->responded_time))
                                                    : 'NA' ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="ticket-label">Completed Time</div>
                                            <div class="ticket-value">
                                                <?= !empty($lead_details->completed_time)
                                                    ? date('M j, Y h:i A', strtotime($lead_details->completed_time))
                                                    : 'NA' ?>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Section 3 -->
                                    <div class="row ticket-section">
                                        <div class="col-md-6 mb-3">
                                            <div class="ticket-label">Description</div>
                                            <div class="ticket-value"><?= $lead_details->query ?></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="ticket-label">Remark</div>
                                            <div class="ticket-value"><?= $lead_details->remark ?></div>
                                        </div>


                                    </div>

                                    <div class="row">



                                        <?php if (!empty($lead_details->disposition) || !empty($lead_details->status)) : ?>
                                            <?php
                                            $dept = $lead_details->department_name;
                                            $disp = strtolower($lead_details->disposition);
                                            $status = strtolower($lead_details->status);
                                            ?>

                                            <!-- Room Reservation Closed -->
                                            <?php if ($disp === 'reservation' && $status === 'closed' && $dept === 'Rooms') : ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Check-in</div>
                                                    <div class="ticket-value"><?= $lead_details->checkin_date ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Check-out</div>
                                                    <div class="ticket-value"><?= $lead_details->checkout_date ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Pax</div>
                                                    <div class="ticket-value"><?= $lead_details->pax ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Amount</div>
                                                    <div class="ticket-value"><?= $lead_details->amount ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Reservation No.</div>
                                                    <div class="ticket-value"><?= $lead_details->reservation_number ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Bill Attachment</div>
                                                    <?php if (!empty($lead_details->bill_attachment)) : ?>
                                                        <div class="ticket-value">
                                                            <a href="<?= base_url('uploads/bills/' . $lead_details->bill_attachment) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                View Attachment
                                                            </a>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="ticket-value text-muted">No Attachment</div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Resto & Banquet Reservation Closed -->
                                            <?php if ($disp === 'reservation' && $status === 'closed' && ($dept === 'Restaurants' || $dept === 'Banquets')) : ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Booking Date</div>
                                                    <div class="ticket-value"><?= $lead_details->booking_date ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Pax</div>
                                                    <div class="ticket-value"><?= $lead_details->pax ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Amount</div>
                                                    <div class="ticket-value"><?= $lead_details->amount ?? 'NA' ?></div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Shopping-Followup In Progress (All Depts) -->
                                            <?php if ($disp === 'shopping - follow up' && $status === 'in progress') : ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Booking Enquiry Date</div>
                                                    <div class="ticket-value"><?= $lead_details->booking_date ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">Follow-up Date</div>
                                                    <div class="ticket-value"><?= $lead_details->followup_date ?? 'NA' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="ticket-label">2nd Follow-up Date</div>
                                                    <div class="ticket-value"><?= $lead_details->second_followup_date ?? 'NA' ?></div>
                                                </div>
                                                <?php if (!empty($lead_details->lead_transfer_manager)) : ?>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="ticket-label">Transferred To</div>
                                                        <div class="ticket-value"><?= $lead_details->lead_transfer_manager ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>



                                    <div class="row ticket-section">
                                        <h5 class="font-weight-bold my-2">Call History</h5>
                                        <div class="table-responsive">
                                            <!-- Makes table scrollable on smaller screens -->
                                            <table class="table table-bordered table-striped">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Caller Number</th>
                                                        <th>Destination Number</th>
                                                        <th>Call Status</th>
                                                        <th>Call Duration</th>
                                                        <th>Timestamp</th>
                                                        <th>Audio Recording</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="call-history-body">

                                                    <?php if (!empty($call_history)) : ?>
                                                        <?php foreach ($call_history as $call) : ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($call['id']) ?></td>
                                                                <td><?= htmlspecialchars($call['caller_number']) ?></td>
                                                                <td><?= htmlspecialchars($call['destination_number']) ?></td>
                                                                <td><?= htmlspecialchars($call['overall_call_status']) ?></td>
                                                                <td><?= htmlspecialchars($call['conversation_duration_str']) ?></td>
                                                                <td><?= htmlspecialchars($call['timestamp']) ?></td>
                                                                <td>
                                                                    <?php if (!empty($call['recording_url'])) : ?>
                                                                        <audio controls>
                                                                            <source src="<?= htmlspecialchars($call['recording_url']) ?>" type="audio/mpeg">
                                                                            Your browser does not support the audio element.
                                                                        </audio>
                                                                    <?php else : ?>
                                                                        No Recording
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted">No call history found.</td>
                                                        </tr>
                                                    <?php endif; ?>


                                                </tbody>
                                            </table>
                                        </div>




                                    </div>

                                    <div class="row ticket-section">
                                        <h5 class="font-weight-bold my-2">Change History</h5>
                                        <?php if (!empty($change_history)) : ?>
                                            <ul class="timeline">
                                                <?php foreach ($change_history as $item) : ?>
                                                    <li class="timeline-item">
                                                        <h6><?= htmlspecialchars($item['status']) ?></h6>
                                                        <small><?= htmlspecialchars($item['changed_at']) ?> | <?= htmlspecialchars($item['changed_by']) ?></small>
                                                        <p>
                                                            <?= !empty($item['remark']) ? htmlspecialchars($item['remark']) : '<em>No remark provided</em>' ?>
                                                        </p>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else : ?>
                                            <p class="text-muted">No status history found for this lead.</p>
                                        <?php endif; ?>




                                    </div>



                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

</html>