<style>
    .review-card {
        border-radius: 15px;
        border: 1px solid #e1e1e1;
        padding: 20px;
        margin: 20px;
    }

    .status-btn {
        border-radius: 30px;
        font-weight: 500;
    }

    .rating-stars {
        background-color: #dfffdc;
        border-radius: 20px;
        padding: 5px 10px;
        font-weight: bold;
        color: #28a745;
    }

    .guest-comment {
        background-color: #f4f7fd;
        border-radius: 15px;
        padding: 15px;
        color: #666;
    }

    .reply-box {
        background-color: #fde7ed;
        border-radius: 15px;
        padding: 15px;
        font-size: 15px;
    }

    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 10px 15px;
    }

    .icon-btn {
        font-size: 18px;
        color: #777;
    }

    .reply-btn {
        border-radius: 25px;
        padding: 6px 20px;
        font-weight: 500;
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
                            <h4 class="box-title">Lead Reports</h4>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <input type="text" id="lead-search" class="form-control" placeholder="Search by name or phone...">
                                    <div id="search-results" class="list-group position-absolute" style="z-index:1000; display:none;"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right" style="float:right;">
                                        <button type="button" id="toggle-filters" class="btn btn-primary-light btn-sm">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                        <a href="<?php echo base_url('add-lead-agency') ?>">
                                            <button type="button" class="btn btn-primary-light btn-sm ">
                                                Add +
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>
                            <div id="filter-section" style="<?= $filterOpen ? '' : 'display: none;'; ?>">
                                <div class="mb-4 px-3">
                                    <div class="row g-3 align-items-end">


                                        <!-- Property -->
                                        <div class="col-md-4">
                                            <label>Property</label>
                                            <select name="property" class="form-control" required id="edit_property">
                                                <?php foreach ($properties as $property) { ?>
                                                    <option value="<?= $property->hotel_id; ?>" <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                                        <?= $property->hotel_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <!-- Department -->
                                        <div class="col-md-4">
                                            <label>Department</label>
                                            <select name="type" class="form-control" required id="edit_type">
                                                <?php foreach ($departments as $each): ?>
                                                    <option value="<?= $each->department_id ?>"
                                                        data-name="<?= $each->department_name; ?>"
                                                        <?= ($each->department_id == $lead->type) ? 'selected' : '' ?>>
                                                        <?= $each->department_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status[]" class="form-select filter-input" multiple id="status">
                                                <option value="Open">Open</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Closed">Closed</option>
                                            </select>
                                        </div>
                                        <!-- Lead Source -->
                                        <div class="col-md-3">
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
                                                <option value="" selected disabled>Select Stage</option>

                                                <option value="Not Contacted">Not Contacted</option>
                                                <option value="Contacted">Contacted</option>
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
                            <div class="">
                                <?php
                                $statuses = ['Open', 'In Progress', 'Closed'];
                                $statusColors = [
                                    'Open' => 'text-dark border border-dark',
                                    'In Progress' => 'bg-info-subtle text-info',

                                    'Closed' => 'bg-success-subtle text-success'
                                ];

                                $current_status = $this->input->get('status'); // get current status from URL

                                ?>
                                <div class="container mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <strong>Status Indicators:</strong>
                                        </div>
                                        <?php
                                        $statusMapping = [
                                            'Open' => 'open',
                                            'In Progress' => 'in_progress',
                                            'Closed' => 'closed'
                                        ];
                                        foreach ($statuses as $status):
                                            $active = ($current_status === $status) ? 'border border-2 border-primary' : '';
                                        ?>
                                            <div class="col-auto">
                                                <a href="javascript:void(0)"
                                                    class="status-filter text-decoration-none"
                                                    data-status="<?= $status ?>">
                                                    <span class="badge rounded-pill px-3 py-2 <?= $statusColors[$status] ?> <?= $active ?>"
                                                        id="status_count_<?= $statusMapping[$status] ?? strtolower($status) ?>">
                                                        <?= $status ?> (0)
                                                    </span>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-auto ms-auto">
                                            <span class="fw-semibold">
                                                Total Leads: <span id="total_leads_count"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto; overflow-x: auto;">
                                <div class="container my-4">
                                    <div id="lead_container"></div>
                                    <button id="load_more_btn" class="btn btn-primary mt-3">Load More</button>
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
<div class="modal modal-lg" id="statusHistoryModal" tabindex="-1" aria-labelledby="statusHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusHistoryLabel">Lead Status History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="status-history-content">
                    <!-- Timeline will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Call Confirmation Modal -->
<div class="modal modal-lg" id="confirmCallModal" tabindex="-1" aria-labelledby="confirmCallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Call</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to call <strong id="call-number"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCall">Yes, Call</button>
            </div>
        </div>
    </div>
</div>
<!-- Email Modal -->
<div class="modal modal-lg" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="emailForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">From</label>
                        <input type="email" class="form-control" name="from_email" value="crm@example.com" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">To</label>
                        <input type="email" class="form-control" name="to_email" id="toEmail" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Call Progress Modal -->
<div class="modal modal-lg" id="callProgressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class=" modal-content text-center p-4">
            <div class="modal-header">
                <h5 class="modal-title">Calling...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="call-animation">
                    📞
                </div>
                <h4>Calling <span id="progress-number"></span></h4>
                <h5>Duration: <span id="call-timer">00:00</span></h5>
            </div>
        </div>
    </div>
</div>
<!-- Call History Modal -->
<div class="modal modal-lg" id="callHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Increased size for wider view -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
                            <tr>
                                <td colspan="7" class="text-center">No call history available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Status Remark Modal -->
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



    <?php if ($this->session->flashdata('city_success_msg') != "") { ?>



        toastr.success('<?php echo $this->session->flashdata('city_success_msg'); ?>')

    <?php $this->session->set_flashdata('city_success_msg', '');
    } ?>
</script>
<script>
    $(document).ready(function() {
        let leadId, phoneNumber;
        let callTimer;
        let seconds = 0;

        // Call button click
        $(document).on('click', '.call-lead', function() {
            leadId = $(this).data("id");
            phoneNumber = $(this).data("number");
            $("#call-number").text(phoneNumber);
            $("#confirmCallModal").modal("show");
            console.log(phoneNumber)
        });

        // Confirm Call
        $("#confirmCall").click(function() {
            $("#confirmCallModal").modal("hide");
            $("#progress-number").text(phoneNumber);
            $("#callProgressModal").modal("show");




            // Start Timer
            seconds = 0;
            callTimer = setInterval(() => {
                seconds++;
                let min = String(Math.floor(seconds / 60)).padStart(2, '0');
                let sec = String(seconds % 60).padStart(2, '0');
                $("#call-timer").text(`${min}:${sec}`);
            }, 1000);

            // Initiate Call


            event.preventDefault();

            let data = {
                "callFlowId": "TUMspyjWoYb+Ul8vp2khpgWZix3lECvaXcJtTQ78KKLfDiJjaazlkJrCd2pEA3DmInBzC9KmR061nKW85NR3l4FS31CoVZBm8cPiJ7trrSE=",
                "customerId": "SAYAJI_HOT_Y6fD4Rfsi6zgGcAP6Gdg",
                "callType": "OUTBOUND",
                "metaData": {
                    "leadid": leadId
                },
                "callFlowConfiguration": {
                    "initiateCall_1": {
                        "callerId": 8048248828,
                        "mergingStrategy": "SEQUENTIAL",
                        "callBackURLs": [{
                                "eventType": "ALL",
                                "notifyURL": "...",
                                "method": "POST"
                            },
                            {
                                "eventType": "CDR",
                                "notifyURL": "https://rsgsoftech.com/crm/superAdmin/CallApiController/capture_call_data",
                                "method": "POST"
                            }
                        ],
                        "participants": [{
                            "participantAddress": <?php echo $this->session->userdata('agent_session')['phone'] ?>,
                            "callerId": 8048248828,
                            "participantName": "A",
                            "maxRetries": 1,
                            "maxTime": 0
                        }],
                        "maxTime": 0
                    },
                    "addParticipant_1": {
                        "mergingStrategy": "SEQUENTIAL",
                        "maxTime": 0,
                        "participants": [{
                            "participantAddress": phoneNumber,
                            "participantName": "B",
                            "maxRetries": 1,
                            "maxTime": 0
                        }]
                    }
                }
            };



            $.ajax({
                url: "https://iqtelephony.airtel.in/gateway/airtel-xchange/v2/execute/workflow",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                headers: {
                    "Cache-Control": "no-cache",
                    "Authorization": "Basic " + btoa("SAYAJI_HOT_Y6fD4Rfsi6zgGcAP6Gdg:Y6fD4Rfsi6zgGcAP6Gdg")
                },
                success: function(response) {

                    if (response.status === "success" && response.correlationId) {
                        // Send leadId and correlationId to backend to update
                        $.ajax({
                            url: "LeadController/update_correlation_id", // Update this to match your controller route
                            type: "POST",
                            data: {
                                lead_id: leadId,
                                correlation_id: response.correlationId
                            },
                            success: function(res) {
                                toastr.success("Correlation ID updated successfully!");
                            },
                            error: function(err) {
                                toastr.error("Failed to update Correlation ID.");
                            }
                        });
                    }



                },
                error: function(xhr, status, error) {
                    $("#response").html("<b>Error:</b> " + xhr.responseText);
                }
            });
        });

        // View Call History
        $(".view-calls").click(function() {
            let leadId = $(this).data("id");

            $.ajax({
                url: "<?= base_url('LeadController/get_call_history') ?>",
                type: "POST",
                data: {
                    lead_id: leadId
                },
                dataType: "json",
                success: function(data) {

                    console.log(data);

                    let rows = "";
                    data.forEach(call => {
                        rows += `<tr>
                  <td>${call.id}</td>
                  <td>${call.caller_number}</td>
                  <td>${call.destination_number}</td>
                  <td>${call.overall_call_status}</td>
                  <td>${call.conversation_duration_str}</td>
                  <td>${call.timestamp}</td>
                  <td>
                      ${call.recording_url 
                          ? `<audio controls><source src="${call.recording_url}" type="audio/mpeg"></audio>` 
                          : "No Recording"}
                  </td>
              </tr>`;
                    });
                    $("#call-history-body").html(rows);
                    $("#callHistoryModal").modal("show");
                },

            });
        });

    });
</script>
<script>
    $(document).on('click', '.view-status-history', function() {
        const leadId = $(this).data('lead-id');

        console.log("workin")

        $.ajax({
            url: '<?= base_url("LeadController/get_status_history") ?>',
            type: 'POST',
            data: {
                lead_id: leadId
            },
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.length > 0) {
                    html += '<ul class="timeline">';
                    data.forEach(item => {
                        html += `
                      <li class="timeline-item">
                          <h6>${item.status}</h6>
                          <small>${item.changed_at} | ${item.changed_by}</small>
                          <p>${item.remark ? item.remark : '<em>No remark provided</em>'}</p>
                      </li>
                  `;
                    });
                    html += '</ul>';
                } else {
                    html = '<p class="text-muted">No status history found for this lead.</p>';
                }

                $('#status-history-content').html(html);
                $('#statusHistoryModal').modal('show');
            },
            error: function() {
                $('#status-history-content').html('<p class="text-danger">Error fetching status history.</p>');
                $('#statusHistoryModal').modal('show');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#toggle-filters').click(function() {
            $('#filter-section').slideToggle();
        });
    });
</script>
<!-- edit lead section starts from here -->
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal modal-lg" id="editLeadDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLeadDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLeadDetailsLabel">Edit Lead Info</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leadEditForm">
                    <input type="hidden" name="edit_lead_id" id="edit_lead_id">
                    <div class="row g-3">
                        <!-- Phone Number -->
                        <div class="col-md-4">
                            <label>Phone</label>
                            <input type="number" name="phone_number" id="edit_phone_number" class="form-control" value="<?= $lead->phone_number ?>" required id="phone_number">
                        </div>
                        <input type="hidden" name="lead_id" value="<?= $lead->id ?>">
                        <!-- Guest Name -->
                        <div class="col-md-4">
                            <label>Name</label>
                            <input type="text" name="user_name" class="form-control" value="<?= $lead->user_name ?>" required id="edit_user_name">
                        </div>
                        <!-- Email -->
                        <div class="col-md-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $lead->email ?>" id="edit_email">
                        </div>
                        <!-- Property -->
                        <div class="col-md-4">
                            <label>Property</label>
                            <select name="property" class="form-control" required id="edit_property">
                                <?php foreach ($properties as $property) { ?>
                                    <option value="<?= $property->hotel_id; ?>" <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                        <?= $property->hotel_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- Department -->
                        <div class="col-md-4">
                            <label>Department</label>
                            <select name="type" class="form-control" required id="edit_type">
                                <?php foreach ($departments as $each): ?>
                                    <option value="<?= $each->department_id ?>"
                                        data-name="<?= $each->department_name; ?>"
                                        <?= ($each->department_id == $lead->type) ? 'selected' : '' ?>>
                                        <?= $each->department_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lead_type">
                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Type
                                </label>
                                <select name="lead_type" id="edit_lead_type" class="form-control">
                                    <option value="Hot" <?= ($lead->lead_type == 'Hot') ? 'selected' : '' ?>>Hot</option>
                                    <option value="Warm" <?= ($lead->lead_type == 'Warm') ? 'selected' : '' ?>>Warm</option>
                                    <option value="Cold" <?= ($lead->lead_type == 'Cold') ? 'selected' : '' ?>>Cold</option>
                                </select>
                                <span id="lead_type_error" class="text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="disposition"><i class="fa fa-list me-1 text-dark"></i>Stage</label>
                                <select class="form-control" name="disposition" id="edit_disposition">
                                    <option value="" disabled <?= empty($lead->disposition) ? 'selected' : '' ?>>Select Stage</option>
                                    <option value="Information/Enquiry" <?= ($lead->disposition == 'Information/Enquiry') ? 'selected' : '' ?>>Information/Enquiry</option>
                                    <option value="Reservation" <?= ($lead->disposition == 'Reservation') ? 'selected' : '' ?>>Reservation</option>
                                    <option value="Shopping - Follow up" <?= ($lead->disposition == 'Shopping - Follow up') ? 'selected' : '' ?>>Shopping - Follow up</option>
                                    <option value="Shopping - No Follow up" <?= ($lead->disposition == 'Shopping - No Follow up') ? 'selected' : '' ?>>Shopping - No Follow up</option>
                                    <option value="Trash" <?= ($lead->disposition == 'Trash') ? 'selected' : '' ?>>Trash</option>
                                    <option value="Denied" <?= ($lead->disposition == 'Denied') ? 'selected' : '' ?>>Denied</option>
                                </select>
                                <span id="disposition_error" class="text-danger small"></span>
                            </div>
                        </div>
                        <!-- Lead Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lead Status</label>
                                <select name="status" id="edit_lead_status" class="form-control" required disabled>
                                    <option value="Open" <?= ($lead->status == 'Open') ? 'selected' : '' ?>>Open</option>
                                    <option value="On Hold" <?= ($lead->status == 'On Hold') ? 'selected' : '' ?>>On Hold</option>
                                    <option value="In Progress" <?= ($lead->status == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Closed" <?= ($lead->status == 'Closed') ? 'selected' : '' ?>>Closed</option>
                                </select>
                                <span id="lead_status_error" class="text-danger"></span>
                            </div>
                        </div>
                        <!-- Lead Type -->
                        <!-- Stage -->
                        <input type="hidden" id="edit_leadDepartment" name="edit_leadDepartment">
                        <div id="Edit_dynamicFields"></div>
                        <!-- Query -->
                        <div class="col-md-6">
                            <label>Query</label>
                            <textarea name="query" class="form-control" id="edit_query"><?= $lead->query ?></textarea>
                        </div>
                        <!-- Remark -->
                        <div class="col-md-6">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" id="edit_remark"><?= $lead->remark ?></textarea>
                        </div>
                        <!-- Submit -->
                        <div class="col-md-12 text-end mt-3">
                            <!-- Back Button -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary float-right " id="updateLead">Update Lead</button>
            </div>
        </div>
    </div>
</div>
<!-- Lead Details Modal -->
<div class="modal modal-lg" id="viewLeadModal" tabindex="-1" aria-labelledby="viewLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLeadModalLabel">Lead Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewLeadContent">
                <!-- AJAX content will be loaded here -->
                <div class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading...
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {


        // form validation rules

        let existingLeadData = {};

        // Username
        $('#edit_user_name').focusout(function() {
            let value = this.value;
            if (value === "") {
                $('#username_error').html('Please Enter Username');
            } else {
                $('#username_error').html('');
            }
        });

        // Phone Number
        $('#edit_phone_number').focusout(function() {
            let value = this.value;
            let phoneRegex = /^[0-9]{10}$/;
            if (value === "") {
                $('#phone_number_error').html('Please Enter Phone Number');
            } else if (!phoneRegex.test(value)) {
                $('#phone_number_error').html('Invalid Phone Number (10 digits)');
            } else {
                $('#phone_number_error').html('');
            }
        });

        // Email
        $('#edit_email').focusout(function() {
            let value = this.value;
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value !== "" && !emailRegex.test(value)) {
                $('#email_error').html('Invalid Email Format');
            } else {
                $('#email_error').html('');
            }
        });



        // Property
        $('#edit_property').change(function() {
            if (this.value === "") {
                $('#property_error').html('Please Select a Hotel');
            } else {
                $('#property_error').html('');
            }
        });

        // Department
        $('#edit_type').change(function() {
            if (this.value === "") {
                $('#type_error').html('Please Select a Department');
            } else {
                $('#type_error').html('');
            }
        });

        // Lead Status
        $('#edit_lead_status').change(function() {
            if (this.value === "") {
                $('#lead_status_error').html('Please Select Lead Status');
            } else {
                $('#lead_status_error').html('');
            }
        });

        // Stage


        // Query
        $('#edit_query').focusout(function() {
            if (this.value === "") {
                $('#query_error').html('Please Enter Query');
            } else {
                $('#query_error').html('');
            }
        });



        $(document).on("click", ".editLeadDetails", function() {
            let lead_id = $(this).data('lead-id');

            $("#edit_phone_number").val()
            $("#edit_user_name").val()
            $("#edit_email").val()
            $("#edit_type").val()
            $("#edit_property").val()
            $("#edit_disposition").val()
            $("#edit_lead_status").val()
            $("#edit_query").val()
            $("#edit_remark").val()

            $("#Edit_dynamicFields").html('')



            $.ajax({
                url: "<?= base_url('LeadController/get_lead_details') ?>",
                type: "POST",
                data: {
                    lead_id: lead_id
                },
                dataType: 'JSON',

                success: function(data) {

                    existingLeadData = data;

                    $("#edit_phone_number").val(data.phone_number)
                    $("#edit_user_name").val(data.user_name)
                    $("#edit_email").val(data.email)
                    $("#edit_type").val(data.type)
                    $("#edit_property").val(data.property)
                    $("#edit_disposition").val(data.disposition)
                    $("#edit_lead_status").val(data.status)
                    $("#edit_query").val(data.query)
                    $("#edit_remark").val(data.remark)
                    $("#edit_lead_id").val(data.id);
                    updateDynamicFieldsForEdit(data);

                }
            });





            $("#editLeadDetails").modal('show')
        });


        $("#edit_disposition").change(function() {

            updateDynamicFieldsForEdit();

        })


        function updateDynamicFieldsForEdit(data = "") {
            const disposition = $("#edit_disposition").val();
            let department = $('#edit_type').find(':selected').data('name')?.toLowerCase();

            $('#edit_leadDepartment').val(department);

            let existingLeadData = data;


            const container = $("#Edit_dynamicFields");

            // Reset previous fields
            container.html("");


            if ((disposition === "Information/Enquiry") || (disposition === "Trash") || (disposition === "Denied") || (disposition === "Shopping - No Follow up")) {

                $("#edit_lead_status").val('Closed');

            }

            // Reservation - Closed
            if (disposition === "Reservation") {

                $("#edit_lead_status").val('Closed');

                if (department === "rooms") {
                    container.append(`
   <div class="mb-3">
    <label>Check-in Date</label>
    <input type="date" name="checkin_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>Check-out Date</label>
    <input type="date" name="checkout_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
   </div>
   <div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
   </div>
   <div class="mb-3">
    <label>Reservation Number</label>
    <input type="text" name="reservation_number" class="form-control">
   </div>
   <div class="mb-3">
    <label>Bill Attachment</label>
    <input type="file" name="bill_attachment" class="form-control">
    ${existingLeadData.bill_attachment ?
    `<a href="/uploads/bills/${existingLeadData.bill_attachment}" target="_blank">View Uploaded File</a>`
    : ""}
   </div>
   <div class="mb-3">
    <label>Email Reservation Team</label>
    <input type="email" name="reservation_email" class="form-control">
   </div>
   `);
                } else if (department === "restaurants") {
                    container.append(`
   <div class="mb-3">
    <label>Booking Date</label>
    <input type="date" name="booking_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
   </div>
   <div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
   </div>
   <div class="mb-3">
    <label>Email F&B Team</label>
    <input type="email" name="fnb_email" class="form-control">
   </div>
   `);
                } else if (department === "banquets") {
                    container.append(`
   <div class="mb-3">
    <label>Booking Date</label>
    <input type="date" name="booking_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" min="1">
   </div>
   <div class="mb-3">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" step="0.01">
   </div>
   <div class="mb-3">
    <label>Email Banquet Team</label>
    <input type="email" name="banquet_email" class="form-control">
   </div>
   `);
                }
            }

            // Shopping - Followup - In Progress
            if ((disposition === "Shopping - Follow up")) {

                $("#edit_lead_status").val('In Progress');

                container.append(`
   <div class="mb-3">
    <label>Booking Enquiry Date</label>
    <input type="date" name="booking_enquiry_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>Follow-up Date</label>
    <input type="date" name="followup_date" class="form-control">
   </div>
   <div class="mb-3">
    <label>2nd Follow-up Date</label>
    <input type="date" name="second_followup_date" class="form-control">
   </div>
   `);

                if (department === 'banquets') {
                    container.append(`
   <div class="mb-3">
    <label>Transfer Lead to Manager Level</label>
    <select name="transfer_to_manager" class="form-control">
        <option value="">Select</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
   </div>
   `);
                }
            }

            /** ✅ Auto-fill existing values */
            if (typeof existingLeadData !== "undefined") {
                for (let key in existingLeadData) {
                    const field = container.find(`[name="${key}"]`);
                    if (field.length) {
                        field.val(existingLeadData[key]);
                    }
                }
            }







        }



        $('#updateLead').on('click', function(e) {

            $("#edit_lead_status").prop('disabled', false);
            e.preventDefault();

            // Collect form values
            const formValues = {
                lead_id: $('#edit_lead_id').val(),
                user_name: $('input[name="user_name"]').val(),
                phone_number: $('#edit_phone_number').val(),
                email: $('#edit_email').val(),
                user_channel: $('input[name="user_channel"]').val(),
                property: $('#edit_property').val(),
                department: $('#edit_type').val(),
                status: $('#edit_lead_status').val(),
                query: $('#edit_query').val(),
                remark: $('#edit_remark').val(),
                lead_type: $('#edit_lead_type').val(),
                lead_status: $('#edit_lead_status').val(),
                leadDepartment: $('#leadDepartment').val(),
                disposition: $('#edit_disposition').val()
            };

            console.log(formValues);

            // Basic validation
            if (!formValues.user_name || !formValues.phone_number || !formValues.property ||
                !formValues.department || !formValues.status || !formValues.query) {
                alert("Please fill all required fields.");
                return;
            }

            // Build FormData
            let formData = new FormData();

            // Append fixed fields
            Object.keys(formValues).forEach(key => {
                if (formValues[key] !== null && formValues[key] !== undefined) {
                    formData.append(key, formValues[key]);
                }
            });

            // Append dynamic fields (inside #dynamicFields)
            $('#Edit_dynamicFields').find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (!name) return;

                if ($(this).attr('type') === 'file' && this.files.length > 0) {
                    formData.append(name, this.files[0]); // Single file
                } else {
                    formData.append(name, $(this).val());
                }
            });

            // AJAX request
            $.ajax({
                url: '<?php echo base_url("update-lead-super-admin"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    toastr.success('Lead details has been updated successfully')

                    $("#edit_lead_status").prop('disabled', true);


                    updateLeadCard($('#edit_lead_id').val(), response.data);



                    $("#editLeadDetails").modal('hide')

                },
                error: function() {
                    alert("An unexpected error occurred. Please try again.");
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('Save');
                }
            });
        });

        // Function to update card content dynamically
        function updateLeadCard(leadId, updatedLead) {

            const card = $(`#lead_card-${leadId}`);

            if (!card.length) {
                console.warn("Card not found for lead:", leadId);
                return;
            }

            card.find(".lead-department").text(updatedLead.department_name || '');
            card.find(".lead-user-name").text(updatedLead.user_name || '');
            card.find(".lead-phone").text(updatedLead.phone_number || '');
            card.find(".guest-comment").text(updatedLead.query || '');
            card.find(".lead-disposition").text(updatedLead.disposition || '');
            card.find(".lead-status").val(updatedLead.status || '');



            const statusClass = getStatusClass(updatedLead.status);
            card.removeClass("bg-success-subtle bg-info-subtle bg-warning-subtle text-dark").addClass(statusClass);
        }

        // helper for dynamic color class
        function getStatusClass(status) {
            switch (status) {
                case 'Closed':
                    return 'bg-success-subtle';
                case 'In Progress':
                    return 'bg-info-subtle';
                case 'On Hold':
                    return 'bg-warning-subtle';
                default:
                    return 'text-dark';
            }
        }









    })
</script>
<!-- 
   lead filters from start is here -->
<script>
    $(document).ready(function() {

        let offset = 0; // pagination offset
        const limit = 100; // initial records to load

        // -------- GET PARAM READER (works for arrays too) --------
        function getUrlParamsArray(param) {
            const url = new URL(window.location.href);
            return url.searchParams.getAll(param); // support ?status=Open&status=Closed
        }

        var statusFromGet = getUrlParamsArray('status');

        console.log(statusFromGet)

        // Pre-select multi-select status dropdown if GET exists
        if (statusFromGet.length > 0) {
            $("#status").val(statusFromGet).trigger('change'); // for select2 or normal select
            fetchLeads(true);
        } else {
            fetchLeads(true); // default initial load
        }





        // Function to fetch leads
        function fetchLeads(reset = false) {
            if (reset) offset = 0; // reset offset if new filters applied


            <?php

            $property = $this->session->userdata('selected_hotel_id');
            $department = $this->session->userdata('selected_department_id');



            ?>







            var property = [<?php echo $property; ?>];

            var department = [<?php echo $department; ?>];


            let filters = {
                property: $('#property').val(),
                department: $('#department').val(),
                status: $('#status').val(),
                channel: $('#channel').val(),
                disposition: $('#disposition').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                search: $('#lead-search').val(),
                business_type: $('#business_type').val() || [],

                offset: offset,
                limit: limit
            };

            $.ajax({
                url: "<?= base_url('LeadController/fetch_leads_ajax_agency') ?>",
                method: "POST",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    if (reset) $('#lead_container').html('<p>Loading...</p>');
                    $('#load_more_btn').prop('disabled', true).text('Loading...');
                },
                success: function(response) {

                    $('#status_count_open').text('Open (' + response.totalCounts.open + ')');
                    $('#status_count_in_progress').text('In Progress (' + response.totalCounts.in_progress + ')');
                    $('#status_count_closed').text('Closed (' + response.totalCounts.closed + ')');

                    $('#total_leads_count').text(response.totalCounts.total);


                    if (reset) {
                        $('#lead_container').html(response.html);
                    } else {
                        $('#lead_container').append(response.html);
                    }

                    // Update offset
                    offset += response.count;

                    // Show/Hide Load More button
                    if (response.count < limit) {
                        $('#load_more_btn').hide();
                    } else {
                        $('#load_more_btn').show().prop('disabled', false).text('Load More');
                    }
                },
                error: function() {
                    alert('Something went wrong!');
                    $('#load_more_btn').prop('disabled', false).text('Load More');
                }
            });
        }

        // Initial load
        fetchLeads(true);

        // Trigger fetch when any filter changes
        $('#property, #department, #status, #channel, #disposition, #start_date, #end_date').on('change', function() {
            fetchLeads(true);
        });

        // Search input
        $('#lead-search').on('keyup', function() {
            fetchLeads(true);
        });


        $('#business_type').on('change', function() {
            fetchLeads(true); // existing function
        });




        // Load more button
        $('#load_more_btn').on('click', function() {
            fetchLeads(false);
        });



        $(document).on('click', '.status-filter', function() {
            var status = $(this).data('status');
            var select = $('#status'); // Select2 multi-select dropdown

            // Ensure it's actually a multiple select
            if (!select.prop('multiple')) {
                select.prop('multiple', true);
            }

            // Highlight only the clicked badge
            $('.status-filter .badge').removeClass('border border-2 border-primary');
            $(this).find('.badge').addClass('border border-2 border-primary');

            // ✅ Always set Select2 value as array (not string)
            var selectedArray = Array.isArray(status) ? status : [status];
            select.val(selectedArray).trigger('change.select2'); // Works for Select2


            // ✅ Trigger reload with updated filter
            fetchLeads(true);
        });













    });
</script>
<script>
    $(document).on('click', '.view-lead-details', function() {
        var leadId = $(this).data('lead-id');

        $.ajax({
            url: '<?= base_url("LeadController/get_lead_details_new") ?>',
            type: 'POST',
            data: {
                lead_id: leadId
            },
            dataType: 'JSON',
            success: function(res) {
                if (res.status === 'success') {
                    var data = res.data;

                    function formatDate(dt) {
                        if (!dt) return 'NA';
                        var d = new Date(dt);
                        return d.toLocaleString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    }

                    let html = `
<div class="ticket-container">

    <!-- Section 1 -->
    <div class="row ticket-section">
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Property</strong></div><div class="ticket-value">${data.hotel_name}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>City</strong></div><div class="ticket-value">${data.city_name}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Department</strong></div><div class="ticket-value">${data.department_name}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Name</strong></div><div class="ticket-value">${data.user_name}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Phone</strong></div><div class="ticket-value">${data.phone_number}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Email</strong></div><div class="ticket-value">${data.email}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Lead Source</strong></div><div class="ticket-value">${data.user_channel}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Lead Status</strong></div><div class="ticket-value">${data.status}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Stage</strong></div><div class="ticket-value">${data.disposition}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Repeatative Guest</strong></div><div class="ticket-value">${data.is_repeatative ? 'Yes' : 'No'}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Created By</strong></div><div class="ticket-value">${data.created_by_name ?? 'NA'}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Assigned To</strong></div><div class="ticket-value">${data.assigned_to_name ?? 'NA'}</div></div>
    </div>

    <!-- Section 2 -->
    <div class="row ticket-section">
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Created Date</strong></div><div class="ticket-value">${formatDate(data.created_at)}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Responded Time</strong></div><div class="ticket-value">${formatDate(data.responded_time)}</div></div>
        <div class="col-md-4 mb-3"><div class="ticket-label"><strong>Completed Time</strong></div><div class="ticket-value">${formatDate(data.completed_time)}</div></div>
    </div>

    

    <!-- Section 3 -->
<div class="row ticket-section">

    ${data.query ? `
        <div class="col-md-6 mb-3">
            <div class="ticket-label"><strong>Description</strong></div>
            <div class="ticket-value">${data.query}</div>
        </div>
    ` : ''}

    ${data.remark ? `
        <div class="col-md-6 mb-3">
            <div class="ticket-label"><strong>Remark</strong></div>
            <div class="ticket-value">${data.remark}</div>
        </div>
    ` : ''}

    ${data.checkin_date ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Check-in</strong></div>
            <div class="ticket-value">${data.checkin_date}</div>
        </div>
    ` : ''}

    ${data.checkout_date ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Check-out</strong></div>
            <div class="ticket-value">${data.checkout_date}</div>
        </div>
    ` : ''}

    ${data.booking_date ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Booking Date</strong></div>
            <div class="ticket-value">${data.booking_date}</div>
        </div>
    ` : ''}

    ${data.pax ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Pax</strong></div>
            <div class="ticket-value">${data.pax}</div>
        </div>
    ` : ''}

   ${data.revenue_room > 0 ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Room Revenue</strong></div>
    <div class="ticket-value">${data.revenue_room}</div>
</div>
` : ''}

${data.revenue_fnb > 0 ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>F&B Revenue</strong></div>
    <div class="ticket-value">${data.revenue_fnb}</div>
</div>
` : ''}

${data.revenue_other > 0 ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Other Revenue</strong></div>
    <div class="ticket-value">${data.revenue_other}</div>
</div>
` : ''}

${data.amount > 0 ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Amount</strong></div>
    <div class="ticket-value">${data.amount}</div>
</div>
` : ''}

    ${data.restaurant_name ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Restaurant Name</strong></div>
            <div class="ticket-value">${data.restaurant_name}</div>
        </div>
    ` : ''}

    ${data.banquet_name ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Banquet Name</strong></div>
            <div class="ticket-value">${data.banquet_name}</div>
        </div>
    ` : ''}

    ${data.special_occasion ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>special_occasion</strong></div>
            <div class="ticket-value">${data.special_occasion}</div>
        </div>
    ` : ''}

     ${data.special_request ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>special_request</strong></div>
            <div class="ticket-value">${data.special_request}</div>
        </div>
    ` : ''}

     ${data.sitting_style ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>sitting_style</strong></div>
            <div class="ticket-value">${data.sitting_style}</div>
        </div>
    ` : ''}

    ${data.audio_visual ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>audio_visual</strong></div>
            <div class="ticket-value">${data.audio_visual}</div>
        </div>
    ` : ''}

    ${data.btr ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>btr</strong></div>
            <div class="ticket-value">${data.btr}</div>
        </div>
    ` : ''}


    ${data.purpose ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>purpose</strong></div>
            <div class="ticket-value">${data.purpose}</div>
        </div>
    ` : ''}



    ${data.reservation_number ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Reservation No.</strong></div>
            <div class="ticket-value">${data.reservation_number}</div>
        </div>
    ` : ''}

    ${data.followup_date ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Follow-up Date</strong></div>
            <div class="ticket-value">${data.followup_date}</div>
        </div>
    ` : ''}

    ${data.second_followup_date ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>2nd Follow-up Date</strong></div>
            <div class="ticket-value">${data.second_followup_date}</div>
        </div>
    ` : ''}

    ${data.lead_transfer_manager ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Transferred To</strong></div>
            <div class="ticket-value">${data.lead_transfer_manager}</div>
        </div>
    ` : ''}

    ${data.bill_attachment ? `
        <div class="col-md-4 mb-3">
            <div class="ticket-label"><strong>Bill Attachment</strong></div>
            <a href="<?= base_url('uploads/bills/') ?>${data.bill_attachment}" 
               target="_blank" 
               class="btn btn-sm btn-outline-primary">
               View Attachment
            </a>
        </div>
    ` : ''}

</div>

</div>
`;


                    $('#viewLeadContent').html(html);
                    $('#viewLeadModal').modal('show');

                } else {
                    alert(res.message);
                }
            }
        });
    });
</script>