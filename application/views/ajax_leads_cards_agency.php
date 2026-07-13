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
    $nonBusinessStages = ['Information/Enquiry', 'Trash'];
    $isBusiness = (!in_array($lead['disposition'], $nonBusinessStages)) ? true : false;
    $businessBadge = $isBusiness
        ? '<span class="badge bg-success-light text-dark">Business Lead</span>'
        : '<span class="badge bg-secondary-light text-dark">Non-Business Lead</span>';


    ?>
    <div class="card shadow-sm mb-4 p-3 rounded-4 border <?= $statusClass; ?>" id="lead_card-<?= $lead['id'] ?>">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <strong><?= $lead['city_name']; ?> - <?= $lead['hotel_name']; ?></strong>
                <span class="badge bg-success rounded-pill text-capitalize"><?= $lead['user_channel']; ?></span>
                <span class="badge bg-<?= $badgeColor ?> rounded-pill" title="Lead Type"><?= ucfirst($lead['lead_type']); ?></span>
                <?= $businessBadge ?>

            </div>

            <div style="margin-left:100px">
                <?php if (!empty($lead['is_repeatative']) && $lead['is_repeatative']): ?>
                    <a class="badge bg-warning-light text-dark" href="javascript:void(0)" title="View Visit History">Repeatative Guest</a>
                <?php else: ?>
                    <span class="badge bg-secondary-light">New Guest</span>
                <?php endif; ?>
            </div>

            <div title="template Name">
                <span class="badge bg-warning-light"><i class="fa fa-book"></i> : <?= $lead['template_name']; ?></span>
            </div>


        </div>

        <div class="row mb-2">
            <div class="col-md-4 col-sm-6 mb-2">
                <i class="fa fa-building text-secondary me-1"></i>
                <strong>Department:</strong> <span class="lead-department"><?= $lead['department_name']; ?></span>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <i class="fa fa-user text-secondary me-1"></i>
                <strong>Name:</strong> <span class="lead-user-name"><?= $lead['user_name']; ?></span>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-phone text-secondary me-1"></i>
                <strong>Phone:</strong> <span class="lead-phone"><?= $lead['phone_number']; ?></span>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <i class="fa fa-calendar-alt text-secondary me-1"></i>
                <strong>Date:</strong> <?= date('D, M j, Y g:i A', strtotime($lead['created_at'])); ?>
            </div>
        </div>

        <div class="guest-comment mb-2"><?= $lead['query']; ?></div>

        <div class="d-flex justify-content-end gap-2 mt-2">
            <?php if ($lead['disposition'] != ''): ?>
                <span id="disposition-<?= $lead['id']; ?>" title="Stage">
                    Dis. : <b><span class="lead-disposition"><?= $lead['disposition']; ?></span></b>
                </span>
            <?php endif; ?>



            <button class="btn btn-outline-primary btn-sm view-status-history" data-lead-id="<?= $lead['id']; ?>">View Status History</button>
            <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm view-lead-details" data-lead-id="<?= $lead['id']; ?>">
                View Details
            </a>
        </div>
    </div>
<?php endforeach; ?>