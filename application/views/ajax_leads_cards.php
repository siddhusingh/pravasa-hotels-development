<?php foreach ($leads as $lead): ?>
    <?php
    // Determine background class based on status
    $statusClass = '';
    switch ($lead['status']) {
        case 'Open':
            $statusClass = ' text-dark';
            break;
        case 'Closed':
            $statusClass = 'bg-success-subtle ';
            break;
        case 'In Progress':
            $statusClass = 'bg-info-subtle';
            break;
        case 'On Hold':
            $statusClass = 'bg-warning-subtle ';
            break;
    }

    // Lead type badge color
    $leadTypeColors = ['hot' => 'danger', 'warm' => 'warning', 'cold' => 'secondary'];
    $leadType = strtolower($lead['lead_type']);
    $badgeColor = $leadTypeColors[$leadType] ?? 'secondary';



    // Business / Non-Business flag
    $nonBusinessStages = ['Information/Enquiry', 'Trash', 'Denied'];
    $isBusiness = (!in_array($lead['disposition'], $nonBusinessStages)) ? true : false;
    $businessBadge = $isBusiness
        ? '<span class="badge bg-success-light text-dark">Business Lead</span>'
        : '<span class="badge bg-secondary-light text-dark">Non-Business Lead</span>';


    ?>
    <div class="card lead_card_ac shadow-sm mb-4 p-3 rounded-4 border <?= $statusClass; ?>" id="lead_card-<?= $lead['id'] ?>">
        <div class="lead_card_ac-header d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="lead_edtid_broder_child_name">
                     <i class="fa fa-map-marker" aria-hidden="true"></i>
                     <strong><?= $lead['city_name']; ?> - <?= $lead['hotel_name']; ?></strong>
                </div>
                <div class="lead_edtid_broder_child_name_bnt">
                    <span class="badge bg-success rounded-pill text-capitalize"><?= $lead['user_channel']; ?></span>
                    <span class="badge bg-<?= $badgeColor ?> rounded-pill" title="Lead Type"><?= ucfirst($lead['lead_type']); ?></span>
                    <?= $businessBadge ?>
                </div>
            </div>
            <div class="lead_edtid_broder">
                <div style="">
                    <?php if (!empty($lead['is_repeatative']) && $lead['is_repeatative']): ?>
                        <?php $lead_history_route = $this->session->userdata('role_as') === 'super_admin' ? 'manage-leads' : 'view-leads'; ?>
                        <a class="badge  text-dark" href="<?= base_url($lead_history_route . '?phone=' . urlencode($lead['phone_number'])) ?>" title="View Visit History">Repeatative Guest</a>
                    <?php else: ?>
                        <span class="badge ">New Guest</span>
                    <?php endif; ?>


                    <?php
                    $logged_in_role      = $this->session->userdata('role_as');
                    $agentSession        = $this->session->userdata('agent_session');
                    $logged_in_user_id   = $agentSession['id'] ?? null;

                    // normalize status
                    $lead_status = strtolower(trim($lead['status']));

                    // Allow:
                    // 1. Assigned user
                    // 2. super_admin
                    // 3. hotel_admin
                    // and lead should not be closed

                    $canTransfer =
                        (
                            (
                                ($lead['is_assigned'] == 1) &&
                                ($lead['assigned_to'] == $logged_in_user_id)
                            )
                            ||
                            in_array($logged_in_role, ['super_admin', 'hotel_admin', 'admin'])
                        )
                        &&
                        ($lead_status !== 'closed');
                    ?>

                    <button class="btn  btn-sm transferLeadBtn"
                        data-id="<?= $lead['id']; ?>"
                        style="<?= $canTransfer ? '' : 'display:none;' ?>">
                        <i class="fa fa-exchange"></i> Transfer Lead
                    </button>


                    <?php if ($logged_in_role === 'super_admin' && $this->session->userdata('user_role') == 1) { ?>
                        <button class="btn  btn-sm deleteLeadBtn"
                            data-id="<?php echo $lead['id']; ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>

                    <?php } ?>



                </div>

                <div class="lead_edtid_broder_child" title="template Name">
                    <span class="badge "><i class="fa fa-book"></i> : <?= $lead['template_name']; ?></span>
                </div>

                <a href="javascript:void(0)" class="badge  editLeadDetails" data-lead-id="<?= $lead['id'] ?>"><i class="fa fa-edit"></i> Edit</a>
            </div>
        </div>

        <div class="row lead_list_main_data mb-3">
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-building text-secondary me-1"></i>
                <strong>Department:</strong> <span class="lead-department"><?= $lead['department_name']; ?></span>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-user text-secondary me-1"></i>
                <strong>Name:</strong> <span class="lead-user-name"><?= $lead['user_name']; ?></span>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-phone text-secondary me-1"></i>
                <strong>Phone:</strong> <span class="lead-phone"><?= $lead['phone_number']; ?></span>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-calendar-alt text-secondary me-1"></i>
                <strong>Date:</strong> <?= date(' M j, Y g:i A', strtotime($lead['created_at'])); ?>
            </div>
        </div>

        <div class="guest-comment crm-comment mb-2"><?= $lead['query']; ?></div>

        <div class="lead_Quotation_details">
            <?php if ($lead['disposition'] != ''): ?>
                <span id="disposition-<?= $lead['id']; ?>" title="Stage">
                    Dis. : <b><span class="lead-disposition"><?= $lead['disposition']; ?></span></b>
                </span>
            <?php endif; ?>

            <?php if ($lead['status'] != 'Closed'): ?>
                <button class="btn btn-outline-primary btn-sm call-lead" data-id="<?= $lead['id']; ?>" data-number="<?= $lead['phone_number']; ?>"><i class="fa fa-phone me-1"></i> Call</button>

                <a class="btn btn-outline-success btn-sm" target="_blank" href="https://wa.me/91<?= substr(preg_replace('/[^0-9]/', '', $lead['phone_number']), -10); ?>?text=Hi%20<?= urlencode($lead['user_name']); ?>,%20I%20would%20like%20to%20discuss%20your%20lead."><i class="fa fa-whatsapp me-1"></i> WhatsApp</a>
                <a class="btn btn-outline-success btn-sm sendWhastappTempMsg"
                    data-property_id="<?= $lead['property']; ?>"
                    data-phone_number="<?= substr(preg_replace('/\D/', '', $lead['phone_number']), -10); ?>"
                    data-user_name="<?= $lead['user_name']; ?>"
                    data-created_at="<?= date(' M j, Y g:i A', strtotime($lead['created_at'])); ?>"

                    href="javascript:void(0)">
                    <i class="fa fa-whatsapp me-1"></i> WhatsApp Templates
                </a>


                <!-- <?php if (!empty($lead['email'])): ?>
                    <button class="btn btn-outline-dark btn-sm email-lead" data-bs-toggle="modal" data-bs-target="#emailModal" data-email="<?= $lead['email']; ?>" data-name="<?= $lead['user_name']; ?>"><i class="fa fa-envelope me-1"></i> Email</button>
                <?php endif; ?> -->
            <?php endif; ?>

            <button class="btn btn-outline-secondary btn-sm view-calls" data-id="<?= $lead['id']; ?>"><i class="fa fa-history me-1"></i> View Calls</button>
            <button class="btn btn-outline-primary btn-sm view-status-history" data-lead-id="<?= $lead['id']; ?>">View Status History</button>
            <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm view-lead-details" data-lead-id="<?= $lead['id']; ?>">
                View Details
            </a>
        </div>
    </div>
<?php endforeach; ?>
