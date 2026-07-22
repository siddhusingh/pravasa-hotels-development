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
    <div class="card crm-card shadow-sm mb-4 p-3 rounded-4 <?= $statusClass; ?>" id="lead_card-<?= $lead['id'] ?>">
       <div class="crm-card-header d-flex justify-content-between align-items-start mb-3 flex-wrap">
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
                        <?php $lead_history_route = 'view-agents-leads'; ?>
                        <a class="badge  text-dark" href="<?= base_url($lead_history_route . '?phone=' . urlencode($lead['phone_number'])) ?>" title="View Visit History">Repeatative Guest</a>
                    <?php else: ?>
                        <span class="badge bg-secondary-light">New Guest</span>
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

                    <button class="btn btn-warning-light btn-sm transferLeadBtn"
                        data-id="<?= $lead['id']; ?>"
                        style="<?= $canTransfer ? '' : 'display:none;' ?>">
                        <i class="fa fa-exchange"></i> Transfer Lead
                    </button>


                    <?php if ($logged_in_role === 'super_admin' && $this->session->userdata('user_role') == 1) { ?>
                        <button class="btn btn-warning-light btn-sm deleteLeadBtn"
                            data-id="<?php echo $lead['id']; ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>

                    <?php } ?>



                </div>
                <div class="lead_edtid_broder_child" title="template Name">
                    <i class="fa fa-book"></i> : <?= $lead['template_name']; ?>
                </div>
                <a href="javascript:void(0)" class="badge  editLeadDetails" data-lead-id="<?= $lead['id'] ?>"><i class="fa fa-edit"></i> Edit</a>
            </div>
        </div>

       <div class="row crm-card-body mb-3">
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

       <div class="guest-comment crm-comment mb-3"><?= $lead['query']; ?></div>

        <div class="lead_Quotation_details ">
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
        <style>

.row.crm-card-body.mb-3 .col-md-3.col-sm-6.mb-2 {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    background: #fff;
    border-radius: 8px;
    padding: 7px 9px;
    width: 24%;
}
.row.crm-card-body.mb-3 {
    gap: 21px;
    justify-content: center;
    margin-top: 10px;

}
.row.crm-card-body.mb-3 .col-md-3.col-sm-6.mb-2 {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    background: #fff;
    border-radius: 8px;
    padding: 11px 9px;
    width: 22%;
    display: flex;
    align-items: center;
    position: relative;
    z-index: 99;
    margin-top: 10px;
}
.row.crm-card-body.mb-3 .col-md-3.col-sm-6.mb-2 i {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #fff;
    position: absolute;
    top: -15px;
    left: -12px;
}
.row.crm-card-body.mb-3 .col-md-3.col-sm-6.mb-2 strong {
    margin-left: 19px;
}
.row.crm-card-body.mb-3 .col-md-3.col-sm-6.mb-2 span {
    font-size: 14px;
    padding-left: 5px;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
.lead_edtid_broder {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: center;
}
.lead_edtid_broder a, .lead_edtid_broder_child,.lead_edtid_broder span.badge.bg-secondary-light {
    background: transparent;
    background-color: transparent;
    padding: 9px 11px;
    border-radius: 8px;
    /* border: 1px solid #00000021 !important; */
    color: #000!important;
    display: block;
    overflow: hidden;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    font-size: 12px;
    opacity: 1;
}
.card.crm-card{

    position: relative;
    overflow: hidden;

    border-radius: 22px;

    /* border: 1px solid rgba(226,232,240,.9); */

    background:
        linear-gradient(135deg,
        #ffffff 0%,
        #fcfdff 35%,
        #f5f8ff 100%) !important;

    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;

    transition: all .35s ease;
border-bottom:2px solid #000!important;
}


/* Hover */
.card.crm-card:hover {
    transform: translateY(-5px);
    border-color: #090909;
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset!important;
    border:0px solid #000!important;

}


/* Top Right Glow */

.card.crm-card::before{

    content:"";

    position:absolute;

    top:-90px;

    right:-90px;

    width:240px;

    height:240px;

    border-radius:50%;

    background:
    radial-gradient(circle,
    rgba(59,130,246,.10) 0%,
    rgba(59,130,246,.05) 35%,
    transparent 75%);

}


/* Bottom Left Glow */

.card.crm-card::after{

    content:"";

    position:absolute;

    bottom:-110px;

    left:-90px;

    width:260px;

    height:260px;

    border-radius:50%;

    background:
    radial-gradient(circle,
    rgba(168,85,247,.08) 0%,
    rgba(168,85,247,.04) 35%,
    transparent 75%);

}


/* Light shine */

.card.crm-card .crm-card-header{

    position:relative;

    z-index:2;

}

.card.crm-card .crm-card-body,
.card.crm-card .crm-comment,
.card.crm-card .lead_Quotation_details{

    position:relative;

    z-index:2;

}

/*=====================================
      Lead Action Toolbar
======================================*/

.lead_Quotation_details{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    margin-top:0px;
    padding-top:18px;
    border-top:1px solid #e9ecef;
}

/* Disposition */

.lead_Quotation_details [id^="disposition"] {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 7px 12px;
    font-size: 15px;
    font-weight: 600;
    color: #495057;
    /* margin-right: auto; */
}

.lead-disposition{
    color:#000;
    font-weight:700;
}

/* Buttons */

.lead_Quotation_details .btn {
    height: auto;
    padding: 7px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .3s;
    box-shadow: none;
}

.lead_Quotation_details .btn i{

    margin-right:8px;

    font-size:15px;

}

/* Call */

.call-lead{

    background:#0d6efd;

    color:#fff !important;

    border:none;

}

.call-lead:hover{

    background:#0b5ed7;

}

/* WhatsApp */

.lead_Quotation_details a[href*="wa.me"]{

    background:#25D366;

    color:#fff !important;

    border:none;

}

.lead_Quotation_details a[href*="wa.me"]:hover{

    background:#1da851;

}

/* WhatsApp Template */

.sendWhastappTempMsg{

    background:#198754;

    color:#fff !important;

    border:none;

}

.sendWhastappTempMsg:hover{

    background:#157347;

}

/* View Calls */

.view-calls{

    background:#6c757d;

    color:#fff !important;

    border:none;

}

.view-calls:hover{

    background:#5c636a;

}

/* Status */

.view-status-history{

    background:#6f42c1;

    color:#fff !important;

    border:none;

}

.view-status-history:hover{

    background:#5a32a3;

}

/* Details */

.view-lead-details{

    background:#212529;

    color:#fff !important;

    border:none;

}

.view-lead-details:hover{

    background:#000;

}

/* Hover */

.lead_Quotation_details .btn:hover{

    transform:translateY(-2px);

    box-shadow:0 8px 18px rgba(0,0,0,.12);

}

/* Responsive */

@media(max-width:768px){

.lead_Quotation_details{

flex-direction:column;

align-items:stretch;

}

.lead_Quotation_details [id^="disposition"]{

margin-right:0;

text-align:center;

width:100%;

}

.lead_Quotation_details .btn{

width:100%;

}

}
.guest-comment {
    background-color: #ffffff;
    border-radius: 5px;
    padding: 11px;
    color: #000000;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
}
.lead_edtid_broder_child_name {
    display: flex;
    align-items: center;
    gap: 5px;
    position: relative;
    margin-right: 20px;
}
.lead_edtid_broder_child_name:after {
    content: "";
    position: absolute;
    bottom: -4px;
    border-bottom: 1px solid;
    background-color: #000000;
    left: 0;
    width: 100%;
    height: 2px;
    border-radius: 50%;
}
.lead_edtid_broder_child_name strong {
    font-weight: 500;
    font-size: 14px;
}
.lead_edtid_broder_child_name i {
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    margin-bottom: 3px;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    margin-right: 3px;
}

.lead_edtid_broder_child span.badge {
    color: #000;
}
.lead_edtid_broder_child_name_bnt span {
    font-size: 12px;
    border-radius: 5px!important;
    padding: 5px 12px;
}
.lead_edtid_broder_child_name_bnt {
    gap: 6px;
    display: flex;
}

.lead_edtid_broder_child span.badge {
    padding: 0;
    margin: 0;
    /* height: auto; */
    /* padding-top: 0; */
}

        </style>
    </div>
<?php endforeach; ?>
