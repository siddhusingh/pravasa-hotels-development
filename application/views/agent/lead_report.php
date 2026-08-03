<style>
    #editRestaurantReservationPlacement #openEditReserveTableModal { height: 46px; width: 100% !important; }
    #editReserveTableModal { z-index: 1080; }
    #editReserveTableModal .modal-dialog { max-width: 1280px; }
    #editReserveTableModal .modal-content { border: 0; border-radius: 18px; box-shadow: 0 24px 65px rgba(20, 29, 53, .25); overflow: hidden; }
    #editReserveTableModal .modal-header { align-items: flex-start; border: 0; padding: 24px 30px 14px; }
    #editReserveTableModal .modal-title { color: #182033; font-size: 21px; font-weight: 700; }
    #editReserveTableModal .modal-body { background: #fff; padding: 16px 30px 20px; }
    #editReserveTableModal .modal-footer { border-top: 1px solid #edf0f4; padding: 16px 30px; }
    #editReserveTableModal .reservation-status-options { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    #editReserveTableModal .reservation-status-options input[type="radio"] { -webkit-appearance: none; appearance: none; background-color: #fff; border: 2px solid #cbd3df; border-radius: 50%; clip: auto !important; cursor: pointer; display: inline-block !important; flex: 0 0 18px; height: 18px; left: auto !important; margin: 0; opacity: 1 !important; pointer-events: auto !important; position: static !important; visibility: visible !important; width: 18px; }
    #editReserveTableModal .reservation-status-options input[type="radio"]:checked { background-color: #6b4ce6; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.4' d='M4 8.2 6.7 11 12 5.5'/%3E%3C/svg%3E"); background-position: center; background-repeat: no-repeat; background-size: 12px 12px; border-color: #6b4ce6; }
    #editReserveTableModal .reservation-status-options input[type="radio"]:focus-visible { box-shadow: 0 0 0 3px rgba(107, 76, 230, 0.2); outline: 0; }
    @media (max-width: 575px) { #editReserveTableModal .modal-header, #editReserveTableModal .modal-body, #editReserveTableModal .modal-footer { padding-left: 16px; padding-right: 16px; } }

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

    #leadEditForm .required-marker {
        color: #dc3545;
        font-weight: 700;
        margin-left: 2px;
    }

    #leadEditForm .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px !important;
        height: 46px !important;
        padding: 11px 14px;
    }

    #leadEditForm .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px;
        margin-top: 0;
        padding-left: 0;
    }

    #leadEditForm .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }

    #leadEditForm select.is-invalid + .select2-container .select2-selection--single {
        border-color: #dc3545 !important;
    }

    #leadEditForm .select2-container--focus .select2-selection--single,
    #leadEditForm .select2-container--open .select2-selection--single {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2) !important;
    }

    #leadEditForm .edit-select2-dropdown-parent {
        position: relative;
    }

    #leadEditForm .edit-select2-dropdown-parent > .select2-container--open .select2-dropdown {
        z-index: 1070;
    }

    #leadEditForm .edit-table-multiselect-source {
        display: none !important;
    }

    #leadEditForm .edit-table-multiselect {
        position: relative;
        width: 100%;
    }

    #leadEditForm .edit-table-multiselect-toggle {
        align-items: center;
        background: #fff;
        border: 1px solid transparent;
        border-radius: 8px;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px;
        color: #495057;
        display: flex;
        height: 46px;
        justify-content: space-between;
        padding: 0 14px;
        text-align: left;
        width: 100%;
    }

    #leadEditForm .edit-table-multiselect-source.is-invalid + .edit-table-multiselect .edit-table-multiselect-toggle {
        border-color: #dc3545;
    }

    #leadEditForm .edit-table-multiselect.is-open .edit-table-multiselect-toggle,
    #leadEditForm .edit-table-multiselect-toggle:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        outline: 0;
    }

    #leadEditForm .edit-table-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        margin-left: 10px;
    }

    #leadEditForm .edit-table-multiselect.is-open .edit-table-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    #leadEditForm .edit-table-multiselect-menu {
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.16);
        display: none;
        left: 0;
        max-height: 260px;
        overflow-y: auto;
        padding: 6px 0;
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        z-index: 1080;
    }

    #leadEditForm .edit-table-multiselect.is-open .edit-table-multiselect-menu {
        display: block;
    }

    #leadEditForm .edit-table-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 9px;
        margin: 0;
        padding: 8px 12px;
    }

    #leadEditForm .edit-table-multiselect-option:hover {
        background: #f5f3ff;
    }

    #leadEditForm .edit-table-multiselect-option input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        accent-color: #8f73df;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 18px;
        height: 18px !important;
        margin: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 18px !important;
    }

    #leadEditForm .edit-table-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    #leadEditForm .edit-table-multiselect-empty {
        color: #6c757d;
        padding: 9px 12px;
    }
</style>
<style>
    #filter-section .lead-filter-multiselect-source {
        display: none !important;
    }

    #filter-section .lead-filter-multiselect {
        position: relative;
        width: 100%;
    }

    #filter-section .lead-filter-multiselect-toggle {
        align-items: center;
        background: #fff;
        border: 1px solid transparent;
        border-radius: 8px;
        box-shadow: rgba(50, 50, 93, 0.25) 0 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0 1px 3px -1px;
        color: #495057;
        display: flex;
        height: 46px;
        justify-content: space-between;
        padding: 0 14px;
        text-align: left;
        width: 100%;
    }

    #filter-section .lead-filter-multiselect-toggle::after {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #6c757d;
        content: '';
        flex: 0 0 auto;
        margin-left: 10px;
    }

    #filter-section .lead-filter-multiselect.is-open .lead-filter-multiselect-toggle,
    #filter-section .lead-filter-multiselect-toggle:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        outline: 0;
    }

    #filter-section .lead-filter-multiselect.is-open .lead-filter-multiselect-toggle::after {
        border-bottom: 6px solid #6c757d;
        border-top: 0;
    }

    #filter-section .lead-filter-multiselect-menu {
        background: #fff;
        border: 1px solid #d2d2d2;
        border-radius: 6px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.16);
        display: none;
        left: 0;
        max-height: 320px;
        overflow-y: auto;
        padding: 6px 0;
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        z-index: 1080;
    }

    #filter-section .lead-filter-multiselect.is-open .lead-filter-multiselect-menu {
        display: block;
    }

    #filter-section .lead-filter-multiselect-option {
        align-items: center;
        cursor: pointer;
        display: flex;
        gap: 10px;
        margin: 0;
        padding: 9px 14px;
    }

    #filter-section .lead-filter-multiselect-option:hover {
        background: #f4f4f4;
    }

    #filter-section .lead-filter-multiselect-option input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        accent-color: #1473d2;
        clip: auto !important;
        cursor: pointer;
        display: inline-block !important;
        flex: 0 0 20px;
        height: 20px !important;
        left: auto !important;
        margin: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        position: static !important;
        visibility: visible !important;
        width: 20px !important;
    }

    #filter-section .lead-filter-multiselect-select-all {
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
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

<style>
    #editLeadDetails #Edit_dynamicFields {
        display: contents;
    }

    #Edit_dynamicFields .form-control,
    #Edit_dynamicFields .form-select,
    #Edit_dynamicFields .select2-container {
        width: 100% !important;
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
                    <div class="box lead_report_container ">
                        <div class="box-header">
                            <h4 class="box-title">Lead Reports</h4>
                            <div class="row mt-2 responsive_lead-report">
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
                                        <a href="<?php echo base_url('add-lead-agents') ?>">
                                            <button type="button" class="btn btn-primary-light btn-sm ">
                                                Add +
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body ">
                            <?php
                            // Check if any GET filter is set
                            $filterOpen = !empty($this->input->get());
                            ?>
                            <div id="filter-section" style="<?= $filterOpen ? '' : 'display: none;'; ?>">
                                <div class="mb-4 px-3">
                                    <div class="row align-items-end">

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status[]" class="form-select filter-input lead-filter-multiselect-source" multiple id="status">
                                                <option value="Open">Open</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Closed">Closed</option>
                                                <option value="Not Assigned">Not-assigned</option>
                                            </select>
                                        </div>
                                        <!-- Lead Source -->
                                        <div class="col-md-3">
                                            <label for="channel" class="form-label">Lead Source</label>
                                            <select name="channel[]" class="form-select filter-input lead-filter-multiselect-source" multiple id="channel">
                                                <?php foreach ($user_channel as $channelObj): ?>
                                                    <?php $channel = $channelObj->user_channel; ?>
                                                    <option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <!-- Stage -->
                                        <div class="col-md-3">
                                            <label for="disposition" class="form-label">Stage</label>
                                            <select class="form-select filter-input lead-filter-multiselect-source" name="disposition[]" multiple id="disposition">
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
                                            <span class="input_brek_space"></span>
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
                                $statuses = ['Open', 'In Progress', 'Closed', 'Not Assigned'];

                                $statusColors = [
                                    'Open' => 'text-dark border border-dark',
                                    'In Progress' => 'bg-info-subtle text-info',
                                    'Closed' => 'bg-success-subtle text-success',
                                    'Not Assigned' => 'bg-secondary-subtle text-secondary'
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
                                            'Closed' => 'closed',
                                            'Not Assigned' => 'not_assigned'
                                        ];

                                        foreach ($statuses as $status):
                                            $active = ($current_status === $status) ? 'border border-2 border-primary' : '';
                                        ?>
                                            <div class="col-auto">
                                                <a href="javascript:void(0)"
                                                    class="status-filter text-decoration-none"
                                                    data-status="<?= $status ?>">
                                                    <span class="badge rounded-pill px-3 py-2 <?= $statusColors[$status] ?> <?= $active ?>"
                                                        id="status_count_<?= $statusMapping[$status] ?>">
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


<style>
   /*==================================================
        LEAD STATUS HISTORY MODAL
==================================================*/

#statusHistoryModal .modal-content{
    border:0;
    border-radius:18px;
    overflow:hidden;
    background:#f8faff;
    box-shadow:0 25px 60px rgba(0,0,0,.15);
}

#statusHistoryModal .modal-header{
    background:#fff;
    border-bottom:1px solid #edf2f7;
    padding:20px 28px;
}

#statusHistoryModal .modal-title{
    font-size:28px;
    font-weight:700;
    color:#1e293b;
}

#statusHistoryModal .btn-close{
    width:42px;
    height:42px;
    border-radius:12px;
    transition:.3s;
}

#statusHistoryModal .btn-close:hover{
    background:#eef4ff;
    transform:rotate(90deg);
}

#statusHistoryModal .modal-body{
    background:#f8faff;
    padding:30px;
}


/*==========================
TIMELINE
===========================*/

.timeline{

    list-style:none;

    margin:0;

    padding:0 0 0 45px;

    position:relative;

}

.timeline::before{

    content:"";

    position:absolute;

    left:10px;

    top:0;

    bottom:0;

    width:3px;

    border-radius:50px;

    background:linear-gradient(#4f7cff,#9db7ff);

}


/*==========================
ITEM
===========================*/

.timeline-item{

    position:relative;

    margin-bottom:22px;

    padding:20px;

    background:#fff;

    border-radius:16px;

    border:1px solid #edf2f7;

    box-shadow:0 6px 18px rgba(0,0,0,.05);

    transition:.3s;

}

.timeline-item:last-child{

    margin-bottom:0;

}

.timeline-item:hover{

    transform:translateX(8px);

    box-shadow:0 18px 35px rgba(79,124,255,.12);

    border-color:#4f7cff;

}


/*==========================
DOT
===========================*/

.timeline-item::before{

    content:"";

    position:absolute;

    left:-44px;

    top:26px;

    width:18px;

    height:18px;

    border-radius:50%;

    background:#4f7cff;

    border:4px solid #fff;

    box-shadow:0 0 0 6px rgba(79,124,255,.18);

}


/*==========================
STATUS
===========================*/

.timeline-item h6{

    margin:0;

    color:#2563eb;

    font-size:18px;

    font-weight:700;

}


/*==========================
DATE
===========================*/

.timeline-item small{

    display:inline-block;

    margin:14px 0;

    background:#eef4ff;

    color:#64748b;

    padding:8px 16px;

    border-radius:50px;

    font-size:13px;

    font-weight:600;

}


/*==========================
REMARK
===========================*/

.timeline-item p{

    margin:0;

    background:#f8fafc;

    border-left:4px solid #4f7cff;

    border-radius:10px;

    padding:14px 18px;

    color:#000;

    line-height:1.7;

}


/*==========================
SCROLLBAR
===========================*/

#statusHistoryModal .modal-body::-webkit-scrollbar{

    width:8px;

}

#statusHistoryModal .modal-body::-webkit-scrollbar-thumb{

    background:#c4d2ff;

    border-radius:30px;

}


/*==========================
RESPONSIVE
===========================*/

@media(max-width:768px){

#statusHistoryModal .modal-body{

padding:18px;

}

#statusHistoryModal .modal-title{

font-size:22px;

}

.timeline{

padding-left:30px;

}

.timeline::before{

left:8px;

}

.timeline-item{

padding:16px;

}

.timeline-item::before{

left:-30px;

width:14px;

height:14px;

}

.timeline-item h6{

font-size:16px;

}

.timeline-item small{

font-size:12px;

padding:6px 12px;

}

.timeline-item p{

font-size:14px;

}

}
#statusHistoryModal .modal-dialog{
    max-width:1000px;
}
</style>
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
        <div class="modal-content Lead_Call_History">
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
    window.CSRF = window.CSRF || {
        name: <?= json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?= json_encode($this->security->get_csrf_hash()); ?>
    };
    window.CSRF.cookie = <?= json_encode($this->config->item('csrf_cookie_name')); ?>;

    function readAgentCsrfCookie(name) {
        var cookies = document.cookie ? document.cookie.split('; ') : [];
        for (var index = 0; index < cookies.length; index++) {
            var parts = cookies[index].split('=');
            if (decodeURIComponent(parts.shift()) === name) {
                return decodeURIComponent(parts.join('='));
            }
        }
        return '';
    }

    function currentAgentCsrfHash() {
        return readAgentCsrfCookie(window.CSRF.cookie) || window.CSRF.hash || '';
    }

    function refreshAgentCsrf(response) {
        if (response && response.csrfHash) window.CSRF.hash = response.csrfHash;
    }

    function agentCsrfData(data) {
        var payload = data || {};
        if (!(payload instanceof FormData)) {
            payload[window.CSRF.name] = currentAgentCsrfHash();
        }
        return payload;
    }

    function agentCsrfFormData(formData) {
        if (typeof formData.set === 'function') {
            formData.set(window.CSRF.name, currentAgentCsrfHash());
        } else {
            formData.append(window.CSRF.name, currentAgentCsrfHash());
        }
        return formData;
    }

    function isAgentSameOriginRequest(url) {
        if (!url || url.indexOf('http') !== 0) return true;
        try {
            return new URL(url, window.location.href).origin === window.location.origin;
        } catch (error) {
            return true;
        }
    }

    var agentCsrfAjaxQueue = $.Deferred().resolve().promise();

    function csrfAjax(options) {
        var requestOptions = $.extend({}, options);
        var method = (requestOptions.type || requestOptions.method || 'GET').toUpperCase();

        if (method !== 'POST' || !isAgentSameOriginRequest(requestOptions.url)) {
            return $.ajax(requestOptions);
        }

        var runRequest = function() {
            if (requestOptions.data instanceof FormData) {
                agentCsrfFormData(requestOptions.data);
            } else {
                requestOptions.data = agentCsrfData(requestOptions.data);
            }
            return $.ajax(requestOptions);
        };

        agentCsrfAjaxQueue = agentCsrfAjaxQueue.then(runRequest, runRequest);
        return agentCsrfAjaxQueue;
    }

    $(document).ajaxComplete(function(event, xhr) {
        refreshAgentCsrf(xhr.responseJSON);
    });

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
                            "participantAddress": <?= json_encode((string) ($this->session->userdata('agent_session')['phone'] ?? '')); ?>,
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



            csrfAjax({
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
                        csrfAjax({
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
        $(document).on("click", ".view-calls", function() {

            let leadId = $(this).data("id");

            csrfAjax({
                url: "<?= base_url('LeadController/get_call_history') ?>",
                type: "POST",
                data: {
                    lead_id: leadId
                },
                dataType: "json",
                beforeSend: function() {
                    $("#processingLoader").show(); // Show loader
                },
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
                complete: function() {
                    $("#processingLoader").hide(); // Hide loader ALWAYS
                }

            });
        });

    });
</script>
<script>
    $(document).on('click', '.view-status-history', function() {
        const leadId = $(this).data('lead-id');

        console.log("workin")

        csrfAjax({
            url: '<?= base_url("LeadController/get_status_history") ?>',
            type: 'POST',
            data: {
                lead_id: leadId
            },
            dataType: 'json',
            beforeSend: function() {
                $("#processingLoader").show(); // Show loader
            },
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
            complete: function() {
                $("#processingLoader").hide(); // Hide loader ALWAYS
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLeadDetailsLabel">Edit Lead Info</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leadEditForm" novalidate>
                    <input type="hidden" name="edit_lead_id" id="edit_lead_id">
                    <div class="row g-3">
                        <!-- Phone Number -->
                        <div class="col-md-4">
                            <label>Phone <span class="required-marker">*</span></label>
                            <input type="number" name="phone_number" id="edit_phone_number" class="form-control" value="" required>
                        </div>
                        <!-- Guest Name -->
                        <div class="col-md-4">
                            <label>Name <span class="required-marker" id="editGuestNameRequiredMarker">*</span></label>
                            <input type="text" name="user_name" class="form-control" value="" required id="edit_user_name">
                        </div>
                        <!-- Email -->
                        <div class="col-md-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="" id="edit_email">
                        </div>
                        <!-- Property -->
                        <div class="col-md-4">
                            <label>Property <span class="required-marker">*</span></label>
                            <select name="property" class="form-control" required id="edit_property" disabled>
                                <?php foreach ($properties as $property) { ?>
                                    <option
                                        value="<?= $property->hotel_id; ?>"
                                        data-hotel_code="<?= htmlspecialchars($property->hotel_code); ?>"
                                        <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($property->hotel_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Department -->
                        <div class="col-md-4">
                            <label>Department <span class="required-marker">*</span></label>
                            <select name="type" class="form-control" required id="edit_type">
                                <?php foreach ($departments as $each): ?>
                                    <option value="<?= $each->department_id ?>"
                                        data-name="<?= $each->department_name; ?>"
                                        >
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
                                    <option value="Hot">Hot</option>
                                    <option value="Warm">Warm</option>
                                    <option value="Cold" selected>Cold</option>
                                </select>
                                <span id="lead_type_error" class="text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="disposition"><i class="fa fa-list me-1 text-dark"></i>Stage <span class="required-marker">*</span></label>
                                <select class="form-control" name="disposition" id="edit_disposition">
                                    <option value="" disabled selected>Select Stage</option>

                                    <option value="Not Contacted">Not Contacted</option>

                                    <option value="General Information">General Information</option>

                                    <option value="Quotation Sent">Quotation Sent</option>

                                    <option value="Negotiations">Negotiations</option>

                                    <option value="Contract Done">Contract Done</option>

                                    <option value="Advance Received">Advance Received</option>

                                    <option value="Lead Won">Lead Won</option>

                                    <option value="Lead Lost">Lead Lost</option>
                                </select>

                                <span id="disposition_error" class="text-danger small"></span>
                            </div>
                        </div>
                        <!-- Lead Status -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lead Status <span class="required-marker">*</span></label>
                                <select name="status" id="edit_lead_status" class="form-control" required disabled>
                                    <option value="Open" selected>Open</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Closed">Closed</option>


                                </select>
                                <span id="lead_status_error" class="text-danger"></span>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label>Assign Lead To</label>

                            <select name="assigned_to" id="assigned_to" class="form-control">
                                <option value="">Select User</option>

                                <?php foreach ($all_assignable_users as $user) { ?>

                                    <option
                                        value="<?php echo $user['id']; ?>"
                                        data-role="<?php echo $user['user_role']; ?>"
                                        data-email="<?php echo $user['email']; ?>">
                                        <?php echo $user['name'] . '-' . $user['user_role']; ?>
                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <input type="hidden" name="assigned_person_user_role" id="assigned_person_user_role">
                        <input type="hidden" name="assigned_person_email" id="assigned_person_email">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_channel">
                                    <i class="fa fa-fire me-1 text-secondary"></i>Lead Source <span class="required-marker">*</span>
                                </label>

                                <select name="user_channel" id="user_channel" class="form-control">

                                    <option value="phone" selected>Phone</option>
                                    <option value="Email">Email</option>
                                    <option value="Walking">Walking</option>

                                    <option value="IVR">IVR</option>
                                    <option value="Sales Call TA">Sales Call TA</option>
                                    <option value="Employee Referral">Employee Referral</option>
                                    <option value="Travel Agent">Travel Agent</option>
                                    <option value="Public Relations">Public Relations</option>
                                    <option value="Sales Mail">Sales Mail</option>
                                    <option value="Seminar Partner">Seminar Partner</option>
                                    <option value="Walk ins">Walk ins</option>
                                    <option value="Direct Calls">Direct Calls</option>
                                    <option value="Advertisement">Advertisement</option>
                                    <option value="TTF">TTF</option>
                                    <option value="BLTM">BLTM</option>
                                    <option value="Sales Call MICE">Sales Call MICE</option>
                                    <option value="Wedmegood">Wedmegood</option>

                                </select>

                                <span id="user_channel_error" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose">
                                    <i class="fa fa-bullseye me-1 text-secondary"></i>Purpose
                                </label>

                                <select name="purpose" id="edit_purpose" class="form-control">

                                    <option value="">Select Purpose</option>
                                    <option value="Corporate">Corporate</option>
                                    <option value="Family">Family</option>
                                    <option value="Vacation">Vacation</option>
                                    <option value="Leisure">Leisure</option>
                                    <option value="Social">Social</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Pilgrimage">Pilgrimage</option>

                                </select>

                                <span id="purpose_error" class="text-danger small"></span>
                            </div>
                        </div>





                        <!-- Lead Type -->
                        <!-- Stage -->
                        <input type="hidden" id="edit_leadDepartment" name="edit_leadDepartment">
                        <input type="hidden" id="edit_preserved_number_of_rooms" value="">
                        <div id="Edit_dynamicFields" class="row g-3"></div>
                        <!-- Query -->
                        <div class="col-md-6">
                            <label>Query <span class="required-marker">*</span></label>
                            <textarea name="query" class="form-control" id="edit_query"></textarea>
                        </div>
                        <!-- Remark -->
                        <div class="col-md-6">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" id="edit_remark"></textarea>
                        </div>
                        <div class="col-md-12 d-none" id="editRestaurantReservationPlacement">
                            <div class="row"></div>
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
<div class="modal fade" id="editReserveTableModal" tabindex="-1" aria-labelledby="editReserveTableModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex"><span class="reserve-modal-icon"><i class="fa fa-cutlery"></i></span><div><h5 class="modal-title" id="editReserveTableModalLabel">Reserve Table</h5><p class="reserve-modal-subtitle">Select an available table for this reservation</p></div></div>
                <button type="button" class="btn-close" id="closeEditReserveTableModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row reservation-filters">
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-cutlery"></i>1. Restaurant</label><select class="reserve-control" id="edit_reserve_restaurant_id"><option value="">Select Restaurant</option></select><div class="text-danger small mt-1" id="edit_reserve_restaurant_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-calendar"></i>2. Booking Date</label><input class="reserve-control" id="edit_reserve_booking_date" type="date"><div class="text-danger small mt-1" id="edit_reserve_booking_date_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>3. Slot Type</label><select class="reserve-control" id="edit_reserve_slot_type_id"><option value="">Select Slot Type</option></select><div class="text-danger small mt-1" id="edit_reserve_slot_type_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-clock-o"></i>4. Time Slot</label><select class="reserve-control" id="edit_reserve_time_slot_id" disabled><option value="">Select Slot Type first</option></select><div class="text-danger small mt-1" id="edit_reserve_time_slot_error"></div></div>
                    <div class="col-lg col-md-6"><label class="reserve-field-label"><i class="fa fa-th-large"></i>5. Table Category</label><select class="reserve-control" id="edit_reserve_table_category_id"><option value="">Select Table Category</option></select><div class="text-danger small mt-1" id="edit_reserve_table_category_error"></div></div>
                </div>
                <div class="reservation-summary"><div class="reservation-stat"><i class="fa fa-check-circle"></i><div><strong id="edit_reserve_available_count">0</strong><span>Available</span></div></div><div class="reservation-stat stat-occupied"><i class="fa fa-times-circle"></i><div><strong id="edit_reserve_occupied_count">0</strong><span>Occupied</span></div></div><div class="reservation-stat stat-reserved"><i class="fa fa-calendar-check-o"></i><div><strong id="edit_reserve_reserved_count">0</strong><span>Reserved</span></div></div><div class="reservation-stat stat-blocked"><i class="fa fa-ban"></i><div><strong id="edit_reserve_blocked_count">0</strong><span>Blocked</span></div></div><div class="reservation-stat stat-checkout"><i class="fa fa-clock-o"></i><div><strong id="edit_reserve_checkout_count">0</strong><span>Expected Check-outs</span></div></div></div>
                <div class="row g-3"><div class="col-12"><div class="reservation-panel"><div class="reservation-table-grid" id="edit_reserve_table_grid"><div class="text-muted">Select restaurant, booking date, time slot and table category to view tables.</div></div><div class="text-danger small mt-2" id="edit_reserve_table_error"></div></div></div></div>
                <div class="row g-3 mt-1"><div class="col-lg-7"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-file-text-o"></i>Special Instructions <span class="text-muted fw-normal">(Optional)</span></label><textarea class="reserve-control reserve-instructions" id="edit_reserve_special_request" maxlength="250" placeholder="Add any special request or notes for this reservation..."></textarea></div></div><div class="col-lg-5"><div class="reservation-panel"><label class="reserve-field-label"><i class="fa fa-check-circle"></i>Reservation Status</label><div class="reservation-status-options"><label><input type="radio" name="edit_reserve_status" value="Reserved" checked> Reserved</label><label><input type="radio" name="edit_reserve_status" value="Seated"> Seated</label><label><input type="radio" name="edit_reserve_status" value="Completed"> Completed</label><label><input type="radio" name="edit_reserve_status" value="Cancelled"> Cancelled</label></div><div class="text-danger small mt-1" id="edit_reserve_status_error"></div></div></div></div>
            </div>
            <div class="modal-footer d-flex justify-content-between"><button type="button" class="btn btn-light border" id="cancelEditReserveTableModal">Cancel</button><button type="button" class="btn btn-primary reserve-submit-btn" id="confirmEditTableReservation"><i class="fa fa-calendar-check-o me-2"></i>Reserve Table</button></div>
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

        function initializeEditSingleSelect2(scope) {
            if (!$.fn.select2) return;

            var selectScope = scope ? $(scope) : $('#leadEditForm');
            var selects = selectScope.is('select')
                ? selectScope.filter('select:not([multiple])')
                : selectScope.find('select:not([multiple])');

            selects.each(function() {
                var select = $(this);
                var formGroup = select.closest('.form-group');
                var dropdownParent = formGroup.length
                    ? formGroup
                    : select.closest('[class*="col-"]');

                dropdownParent.addClass('edit-select2-dropdown-parent');

                if (!select.hasClass('select2-hidden-accessible')) {
                    select.select2({
                        dropdownParent: dropdownParent,
                        width: '100%'
                    });
                }
            });
        }

        function refreshEditSingleSelect2(select) {
            if (!$.fn.select2) return;

            var field = $(select);
            initializeEditSingleSelect2(field);
            field.trigger('change.select2');
        }

        function syncEditTableMultiSelect(select, widget) {
            var selectedValues = (select.val() || []).map(String);
            var items = widget.find('.edit-table-multiselect-item');
            var selectedCount = selectedValues.length;

            items.each(function() {
                $(this).prop('checked', selectedValues.includes(String($(this).val())));
            });

            var selectAll = widget.find('.edit-table-multiselect-all');
            selectAll.prop('checked', items.length > 0 && selectedCount === items.length);
            selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < items.length);

            var summary = 'Select Table';
            if (selectedCount > 0 && selectedCount === items.length) {
                summary = 'All selected (' + selectedCount + ')';
            } else if (selectedCount > 0) {
                summary = selectedCount + ' selected';
            }
            widget.find('.edit-table-multiselect-summary').text(summary);
        }

        function initializeEditTableMultiSelect() {
            var select = $('#table_id');
            if (!select.length) return;

            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }

            select.next('.edit-table-multiselect').remove();
            select.addClass('edit-table-multiselect-source');

            var widget = $('<div>', { class: 'edit-table-multiselect' });
            var toggle = $('<button>', {
                type: 'button',
                class: 'edit-table-multiselect-toggle',
                'aria-expanded': 'false'
            }).append($('<span>', {
                class: 'edit-table-multiselect-summary',
                text: 'Select Table'
            }));
            var menu = $('<div>', { class: 'edit-table-multiselect-menu' });
            var options = select.find('option').filter(function() {
                return String(this.value).trim() !== '';
            });

            if (options.length) {
                var selectAll = $('<input>', {
                    type: 'checkbox',
                    class: 'edit-table-multiselect-all'
                });
                menu.append(
                    $('<label>', { class: 'edit-table-multiselect-option edit-table-multiselect-select-all' })
                        .append(selectAll, $('<span>', { text: 'Select all' }))
                );

                options.each(function() {
                    var checkbox = $('<input>', {
                        type: 'checkbox',
                        class: 'edit-table-multiselect-item',
                        value: this.value
                    });
                    menu.append(
                        $('<label>', { class: 'edit-table-multiselect-option' })
                            .append(checkbox, $('<span>').text($(this).text().trim()))
                    );
                });
            } else {
                menu.append($('<div>', {
                    class: 'edit-table-multiselect-empty',
                    text: 'No tables available'
                }));
            }

            widget.append(toggle, menu);
            select.after(widget);

            toggle.on('click', function() {
                var isOpen = !widget.hasClass('is-open');
                $('.edit-table-multiselect').not(widget).removeClass('is-open')
                    .find('.edit-table-multiselect-toggle').attr('aria-expanded', 'false');
                widget.toggleClass('is-open', isOpen);
                toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
            });

            widget.on('change', '.edit-table-multiselect-all', function() {
                var values = this.checked
                    ? widget.find('.edit-table-multiselect-item').map(function() { return this.value; }).get()
                    : [];
                select.val(values).trigger('change');
            });

            widget.on('change', '.edit-table-multiselect-item', function() {
                var values = widget.find('.edit-table-multiselect-item:checked')
                    .map(function() { return this.value; }).get();
                select.val(values).trigger('change');
            });

            select.off('change.editTableMultiSelect').on('change.editTableMultiSelect', function() {
                syncEditTableMultiSelect(select, widget);
            });

            syncEditTableMultiSelect(select, widget);
        }

        $(document).off('click.editTableMultiSelect').on('click.editTableMultiSelect', function(e) {
            if (!$(e.target).closest('.edit-table-multiselect').length) {
                $('.edit-table-multiselect').removeClass('is-open')
                    .find('.edit-table-multiselect-toggle').attr('aria-expanded', 'false');
            }
        });


        function normalizeEditDepartmentName(name) {
            var department = String(name || '').trim().toLowerCase().replace(/\s+/g, ' ');
            if (department === 'restaurants') return 'restaurant';
            if (department === 'banquets') return 'banquet';
            return department;
        }

        function editLeadField(field) {
            var names = {
                username: 'user_name',
                lead_status: 'status'
            };

            if (
                ['booking_date', 'restaurant_id', 'table_category_id', 'table_id', 'slot_type_id', 'time_slot_id', 'table_reservation_status'].includes(field)
                && $('#openEditReserveTableModal').length
            ) {
                return $('#openEditReserveTableModal');
            }

            if (field === 'table_id') {
                return $('#Edit_dynamicFields #table_id');
            }

            return $('#leadEditForm [name="' + (names[field] || field) + '"]').first();
        }

        function clearEditLeadValidation() {
            $('#leadEditForm .is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
            $('#leadEditForm .edit-lead-validation-error').remove();
            $('#edit_restaurant_reservation_error').text('');
        }

        function showEditLeadFieldError(field, message) {
            var input = editLeadField(field);
            if (!input.length) return;

            if (input.is('#openEditReserveTableModal')) {
                input.addClass('is-invalid').attr('aria-invalid', 'true');
                $('#edit_restaurant_reservation_error').text('Please complete the table reservation.');
                return;
            }

            input.addClass('is-invalid').attr('aria-invalid', 'true');
            $('<div>', {
                class: 'text-danger small edit-lead-validation-error',
                text: message
            }).appendTo(input.closest('.form-group, [class*="col-"]').first());
        }

        function showEditLeadValidationErrors(errors) {
            clearEditLeadValidation();
            var firstField = null;

            $.each(errors || {}, function(field, message) {
                showEditLeadFieldError(field, message);
                if (!firstField) firstField = editLeadField(field);
            });

            if (firstField && firstField.length) {
                var modalBody = $('#editLeadDetails .modal-body');
                var container = firstField.closest('[class*="col-"]');
                modalBody.animate({
                    scrollTop: Math.max((container.position() || { top: 0 }).top - 20, 0)
                }, 200);
                if (firstField.hasClass('select2-hidden-accessible')) {
                    firstField.next('.select2-container').find('.select2-selection').trigger('focus');
                } else {
                    firstField.trigger('focus');
                }
            }
        }

        function validateEditLeadForm() {
            var errors = {};
            var value = function(name) {
                var fieldValue = $('#leadEditForm [name="' + name + '"]').first().val() || '';
                return $.trim(String(Array.isArray(fieldValue) ? fieldValue.join(',') : fieldValue));
            };
            var phone = value('phone_number').replace(/\D/g, '').slice(-10);
            var email = value('email');
            var disposition = value('disposition');
            var department = normalizeEditDepartmentName($('#edit_leadDepartment').val());

            if (!/^[6-9][0-9]{9}$/.test(phone)) {
                errors.phone_number = phone
                    ? 'Enter a valid 10-digit Indian mobile number.'
                    : 'Phone number is required.';
            }
            if (disposition !== 'Not Contacted' && !value('user_name')) {
                errors.username = 'Guest name is required.';
            }
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.email = 'Enter a valid email address.';
            }
            if (!value('property')) errors.property = 'The selected hotel is required.';
            if (!value('type')) errors.type = 'Please select a department.';
            if (!value('user_channel')) errors.user_channel = 'Please select a lead source.';
            if (!disposition) errors.disposition = 'Please select a stage.';
            if (!value('status')) errors.lead_status = 'Please select a lead status.';
            if (!value('query')) errors.query = 'Query is required.';

            if (disposition === 'Lead Lost' && !value('reason')) {
                errors.reason = 'Please select a reason.';
            }

            if (disposition === 'Quotation Sent') {
                if ($('#edit_is_room_required').is(':checked')) {
                    var checkinDate = value('checkin_date');
                    var checkoutDate = value('checkout_date');
                    var today = new Date().toISOString().split('T')[0];

                    if (!checkinDate) {
                        errors.checkin_date = 'Check-in date is required.';
                    } else if (checkinDate < today) {
                        errors.checkin_date = 'Check-in date cannot be in the past.';
                    }
                    if (!checkoutDate) {
                        errors.checkout_date = 'Check-out date is required.';
                    } else if (checkinDate && checkoutDate < checkinDate) {
                        errors.checkout_date = 'Check-out date must be the same as or after check-in date.';
                    }
                }
                if ($.inArray(department, ['rooms', 'wedding']) !== -1 && !value('meal_plan')) {
                    errors.meal_plan = 'Please select a meal plan.';
                }
                if ($.inArray(department, ['banquet', 'wedding']) !== -1 && !value('banquet_id')) {
                    errors.banquet_id = 'Please select a banquet.';
                }
                if (department === 'restaurant') {
                    if (!value('booking_date')) errors.booking_date = 'Please select a booking date.';
                    if (!value('restaurant_id')) errors.restaurant_id = 'Please select a restaurant.';
                    if (!value('slot_type_id')) errors.slot_type_id = 'Please select a slot type.';
                    if (!value('time_slot_id')) errors.time_slot_id = 'Please select a time slot.';
                    if (!value('table_category_id')) errors.table_category_id = 'Please select a table category.';
                    if (!$('#Edit_dynamicFields #table_id').val() || !$('#Edit_dynamicFields #table_id').val().length) {
                        errors.table_id = 'Please select at least one table.';
                    }
                    if (!value('table_reservation_status')) {
                        errors.table_reservation_status = 'Please select a reservation status.';
                    }
                }
            }

            showEditLeadValidationErrors(errors);
            return Object.keys(errors).length === 0;
        }

        $('#editLeadDetails').on('shown.bs.modal', function() {
            initializeEditSingleSelect2('#leadEditForm');
            $('#leadEditForm select:not([multiple])').trigger('change.select2');
        }).on('hidden.bs.modal', function() {
            clearEditLeadValidation();
        });

        $(document).on('input change', '#leadEditForm input, #leadEditForm select, #leadEditForm textarea', function() {
            $(this).removeClass('is-invalid').removeAttr('aria-invalid');
            $(this).closest('.form-group, [class*="col-"]').first()
                .find('.edit-lead-validation-error').remove();
        });

        $(document).on('change', '#edit_is_room_required', function() {
            var roomRequired = this.checked;
            var dateFields = $('#Edit_dynamicFields .edit-room-required-date-fields');
            var dateInputs = dateFields.find('input[type="date"]');

            dateFields.toggle(roomRequired);
            dateInputs.prop('required', roomRequired);
            if (!roomRequired) {
                dateInputs.val('').removeClass('is-invalid').removeAttr('aria-invalid');
                dateFields.find('.edit-lead-validation-error').remove();
            }
        });

        $(document).on('change', '#edit_checkin_date', function() {
            var today = new Date().toISOString().split('T')[0];
            $('#edit_checkout_date').attr('min', $(this).val() || today);
        });

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

            clearEditLeadValidation();
            $("#edit_phone_number").val('')
            $("#edit_user_name").val('')
            $("#edit_email").val('')
            $("#edit_type").val('')
            $("#edit_disposition").val('')
            $("#assigned_to").val('');

            $("#assigned_to").val('');
            $("#assigned_to").val('').trigger("change");
            $("#assigned_person_user_role").val('');
            $("#assigned_person_email").val('');
            $("#edit_lead_status").val('Open')
            $("#edit_query").val('')
            $("#edit_remark").val('')
            $("#edit_preserved_number_of_rooms").val('')

            $("#Edit_dynamicFields").html('')



            csrfAjax({
                url: "<?= base_url('agent/Leads/get_lead_details') ?>",
                type: "POST",
                data: {
                    lead_id: lead_id
                },
                dataType: 'JSON',
                beforeSend: function() {
                    $("#processingLoader").show(); // Show loader
                },

                success: function(res) {


                    // ✅ If error → show toaster and stop execution
                    if (res.status === "error") {
                        toastr.error(res.message);
                        return; // Stop further execution
                    }

                    let data = res.data;



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

                    $("#edit_purpose").val(data.purpose);
                    $("#edit_preserved_number_of_rooms").val(data.number_of_rooms || '');
                    $("#edit_lead_type").val(data.lead_type || 'Cold');
                    let sourceSelect = $('#leadEditForm select[name="user_channel"]');
                    let sourceValue = String(data.user_channel || '').toLowerCase();
                    let matchedSource = sourceSelect.find('option').filter(function() {
                        return String(this.value || '').toLowerCase() === sourceValue;
                    }).first().val();
                    sourceSelect.val(matchedSource || '');



                    $("#assigned_to option").each(function() {

                        let optionId = $(this).val();
                        let optionRole = $(this).data("role");
                        let optionEmail = $(this).data("email");


                        if (
                            optionId == data.assigned_to &&
                            optionRole == data.assigned_person_user_role
                        ) {
                            $(this).prop("selected", true);
                            $("#assigned_person_user_role").val(optionRole);
                            $("#assigned_person_email").val(optionEmail);
                        }

                    });

                    updateDynamicFieldsForEdit(data, data.property);

                    existingLeadData = data; // ✅ store globally
                    $("#editLeadDetails").modal('show')


                },
                error: function(xhr) {
                    let response = xhr.responseJSON || {};
                    toastr.error(response.message || 'Unable to load lead details.');
                },
                complete: function() {
                    $("#processingLoader").hide(); // Hide loader ALWAYS
                }
            });





        });


        $("#edit_disposition").change(function() {

            let property = $("#edit_property").val();
            updateDynamicFieldsForEdit(existingLeadData, property);

        });

        $("#edit_type").change(function() {

            let property = $("#edit_property").val();
            updateDynamicFieldsForEdit(existingLeadData, property);

        });

        $("#edit_property").change(function() {

            let property = $("#edit_property").val();
            updateDynamicFieldsForEdit(existingLeadData, property);

        });


        $(document).on('change', '#assigned_to', function() {

            var role = $(this).find(':selected').data('role');
            var email = $(this).find(':selected').data('email');

            $('#assigned_person_user_role').val(role);
            $('#assigned_person_email').val(email);

        });


        $(document).on('input', '.revenue-field', function() {

            var room = parseFloat($('#revenue_room').val()) || 0;
            var fnb = parseFloat($('#revenue_fnb').val()) || 0;
            var other = parseFloat($('#revenue_other').val()) || 0;

            var total = room + fnb + other;

            $('#amount').val(total.toFixed(2));

        });

        function updateDynamicFieldsForEdit(data = "") {

            const disposition = $("#edit_disposition").val();
            let property = $("#edit_property").val();
            let department = $('#edit_type').find(':selected').data('name')?.toLowerCase();
            let department_id = $('#edit_type').val();

            console.log(department);

            $('#edit_leadDepartment').val(department);
            $('#editGuestNameRequiredMarker').toggle(disposition !== 'Not Contacted');
            $('#edit_user_name').prop('required', disposition !== 'Not Contacted');

            let existingLeadData = data;

            console.log(existingLeadData);

            const container = $("#Edit_dynamicFields");
            $('#editRestaurantReservationPlacement').addClass('d-none').find('.row').empty();
            const currentNumberOfRooms = container.find('[name="number_of_rooms"]');
            if (currentNumberOfRooms.length) {
                $('#edit_preserved_number_of_rooms').val(currentNumberOfRooms.val());
            }
            container.find('select.select2-hidden-accessible').each(function() {
                if (!$.fn.select2) return false;
                $(this).select2('destroy');
            });
            container.empty();

            var today = new Date().toISOString().split('T')[0];


            /* CLOSED STATUS */

            if (

                disposition === "Contract Done" ||
                disposition === "Advance Received" ||
                disposition === "Lead Won" ||
                disposition === "Sold Out Dates" ||
                disposition === "General Information"
            ) {

                if (disposition === "Lead Won") {
                    container.append(`
        <div class="col-md-3 mb-3">
    <label>Expected Revenue</label>
    <input type="number" name="amount" id="amount" class="form-control" step="0.01" >
</div>
        `);
                }


                $("#edit_lead_status").val('Closed');
            }


            if (disposition == "") {
                $("#edit_lead_status").val('Open');
            }


            /* LEAD LOST */

            if (disposition === "Lead Lost") {

                $("#edit_lead_status").val('Closed');

                container.append(`

        <div class="col-md-4 mb-3">
            <label class="form-label">Reason <span class="required-marker">*</span></label>
            <select name="reason" class="form-select" id="reason" required>
                <option value="">Select Reason</option>
                <option value="Budget Issue">Budget Issue</option>
                <option value="Date Unavailable">Date Unavailable</option>
                <option value="No Response">No Response</option>
                <option value="Chose Competitor">Chose Competitor</option>
                <option value="Not Interested">Not Interested</option>
                <option value="Duplicate Lead">Duplicate Lead</option>
            </select>
            <span class="text-danger" id="reason_error"></span>
        </div>
        

        `);
            }


            /* QUOTATION SENT */

            if (disposition === "Quotation Sent" || disposition === "Contacted") {

                $("#edit_lead_status").val('In Progress');

                container.append(`

        <div class="col-md-3 mb-3">
            <label class="form-label">Promotional Offer </label>
            <select name="promotional_offers" class="form-select" id="edit_promotional_offers"></select>
        </div>

        `);

                loadpromotional_offers(department_id, existingLeadData);


                /* ROOMS */

                if (department === "rooms") {

                    container.append(`

<div class="col-md-3 mb-3">
    <label class="form-label">Room Type</label>
    <select name="roomtype" class="form-select" id="edit_roomtype"></select>
</div>

<div class="col-md-3 mb-3">
    <label>Meal Plan <span class="required-marker">*</span></label>
    <select name="meal_plan" id="edit_meal_plan" class="form-select">
        <option value="">Select Meal Plan</option>
    </select>
</div>

${disposition === "Contacted" ? `<div class="col-md-3 mb-3">
    <label>Check-in Date</label>
    <input type="date" name="checkin_date" class="form-control"
        value="${existingLeadData?.checkin_date ?? ''}">
</div>

<div class="col-md-3 mb-3">
    <label>Check-out Date</label>
    <input type="date" name="checkout_date" class="form-control"
        value="${existingLeadData?.checkout_date ?? ''}">
</div>` : ''}

<div class="col-md-3 mb-3">
    <label>Number of Rooms</label>
    <input type="number" name="number_of_rooms" class="form-control"
        value="${$('#edit_preserved_number_of_rooms').val() || existingLeadData?.number_of_rooms || ''}">
</div>

<div class="col-md-3 mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" 
        value="${existingLeadData?.pax ?? ''}">
</div>

<div class="col-md-4 mb-3">
    <label>Adults</label>
    <input type="number" name="adults" class="form-control" 
        value="${existingLeadData?.adults ?? ''}">
</div>

<div class="col-md-4 mb-3">
    <label>Kids</label>
    <input type="number" name="kids" class="form-control" 
        value="${existingLeadData?.kids ?? ''}">
</div>

 <div class="col-md-3 mb-3">
        <label>Room Revenue</label>
        <input type="number" name="revenue_room" id="revenue_room" class="form-control revenue-field" step="0.01">
    </div>

    <div class="col-md-3 mb-3">
        <label>F&B Revenue</label>
        <input type="number" name="revenue_fnb" id="revenue_fnb" class="form-control revenue-field" step="0.01">
    </div>

    <div class="col-md-3 mb-3">
        <label>Other Revenue</label>
        <input type="number" name="revenue_other" id="revenue_other" class="form-control revenue-field" step="0.01">
    </div>

    <div class="col-md-3 mb-3">
        <label>Expected Revenue</label>
        <input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly>
    </div>



`);

                    loadRoomTypes(property, existingLeadData);
                    loadMealPlan(existingLeadData);

                }


                /* RESTAURANTS */
                else if (department === "restaurants") {

                    container.append(`
<input type="hidden" name="booking_date" id="edit_restaurant_booking_date" value="">
<input type="hidden" name="restaurant_id" id="edit_restaurant_id" value="">
<input type="hidden" name="table_category_id" id="table_category_id" value="">
<select name="table_id[]" id="table_id" multiple style="display:none"></select>
<input type="hidden" name="slot_type_id" id="slot_type_id" value="">
<input type="hidden" name="time_slot_id" id="time_slot_id" value="">
<input type="hidden" name="table_reservation_status" id="table_reservation_status" value="">
<input type="hidden" name="special_request" id="edit_restaurant_special_request" value="">

            <div class="col-lg-4 col-md-6 col-sm-12">
                <label>Arrival Time</label>
                <input type="time" name="arrival_time" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <label>Expected Revenue</label>
                <input type="number" name="amount" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <label>Special Occasion</label>
                <input type="text" name="special_occasion" class="form-control">
            </div>

            `);

                    const savedTableIds = existingLeadData && existingLeadData.table_ids && existingLeadData.table_ids.length
                        ? existingLeadData.table_ids
                        : (existingLeadData && existingLeadData.table_id ? String(existingLeadData.table_id).split(',').map(id => id.trim()).filter(Boolean) : []);
                    const $savedTables = container.find('#table_id').empty();
                    savedTableIds.forEach(function(tableId) {
                        $savedTables.append($('<option>', { value: tableId, text: tableId, selected: true }));
                    });

                    const hasSavedReservation = existingLeadData && existingLeadData.restaurant_id && existingLeadData.booking_date && savedTableIds.length;
                    $('#editRestaurantReservationPlacement').removeClass('d-none').find('.row').html(`
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Table Reservation <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-primary w-100" id="openEditReserveTableModal"><i class="fa ${hasSavedReservation ? 'fa-pencil' : 'fa-calendar-check-o'} me-2"></i>${hasSavedReservation ? 'Edit Reservation' : 'Reserve Table'}</button>
                            <div class="small ${hasSavedReservation ? 'text-success' : 'text-muted'} mt-2" id="edit_restaurant_reservation_summary">${hasSavedReservation ? `${existingLeadData.booking_date} • ${savedTableIds.length} table(s) • ${existingLeadData.table_reservation_status || 'Reserved'}` : 'No table reserved yet.'}</div>
                            <div class="text-danger error-label" id="edit_restaurant_reservation_error"></div>
                        </div>
                    `);
                }


                /* BANQUETS */
                else if (department === "banquets") {

                    const roomRequired = Boolean(existingLeadData?.checkin_date || existingLeadData?.checkout_date);
                    const roomDateDisplay = roomRequired ? '' : 'display:none;';
                    const roomDateRequired = roomRequired ? 'required' : '';
                    const roomRequiredChecked = roomRequired ? 'checked' : '';
                    const checkoutMinDate = existingLeadData?.checkin_date > today
                        ? existingLeadData.checkin_date
                        : today;

                    container.append(`

            <div class="col-md-4 mb-3 d-flex align-items-end">
                <input type="hidden" name="room_requirement_controlled" value="1">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_room_required"
                        id="edit_is_room_required" value="1" ${roomRequiredChecked}>
                    <label class="form-check-label" for="edit_is_room_required">Is Room Required?</label>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Banquet <span class="required-marker">*</span></label>
                <select name="banquet_id" id="edit_banquet_id" class="form-select"></select>
            </div>

            <div class="col-md-6 mb-3 edit-room-required-date-fields" style="${roomDateDisplay}">
                <label for="edit_checkin_date">Check-in Date <span class="required-marker">*</span></label>
                <input type="date" id="edit_checkin_date" name="checkin_date" class="form-control"
                    min="${today}" value="${existingLeadData?.checkin_date ?? ''}" ${roomDateRequired}>
            </div>

            <div class="col-md-6 mb-3 edit-room-required-date-fields" style="${roomDateDisplay}">
                <label for="edit_checkout_date">Check-out Date <span class="required-marker">*</span></label>
                <input type="date" id="edit_checkout_date" name="checkout_date" class="form-control"
                    min="${checkoutMinDate}" value="${existingLeadData?.checkout_date ?? ''}" ${roomDateRequired}>
            </div>

           

            <div class="col-md-4 mb-3">
                <label>Expected Revenue</label>
                <input type="number" name="amount" class="form-control">
            </div>

            `);

                    loadbanquets(property, existingLeadData);
                } else if (department === "spa") {

                    container.append(`


            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-4 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            

        
          <div class="col-md-6">
                    <label>Special Request</label>
                    <textarea name="special_request" class="form-control"></textarea>
                    </div>
            

        

           <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
        

    `);


                } else if (department == "water park") {

                    container.append(`


            <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="${today}">
            </div>

            <div class="col-md-4 mb-3">
                    <label>Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control">
                </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control" min="1">
            </div>

            

        
          <div class="col-md-6">
                    <label>Special Request</label>
                    <textarea name="special_request" class="form-control" row=1></textarea>
                    </div>
            

        

           <div class="col-md-4 mb-3">
                    <label>Expected Revenue</label>
                    <input type="number" name="amount" class="form-control" step="0.01">
                </div>
        

    `);


                } else if (department == "wedding") {


                    container.append(`

<div class="col-md-3 mb-3">
    <label class="form-label">Room Type</label>
    <select name="roomtype" class="form-select" id="edit_roomtype"></select>
</div>

<div class="col-md-3 mb-3">
    <label>Meal Plan <span class="required-marker">*</span></label>
    <select name="meal_plan" id="edit_meal_plan" class="form-select">
        <option value="">Select Meal Plan</option>
    </select>
</div>

${disposition === "Contacted" ? `<div class="col-md-3 mb-3">
    <label>Check-in Date</label>
    <input type="date" name="checkin_date" class="form-control"
        value="${existingLeadData?.checkin_date ?? ''}">
</div>

<div class="col-md-3 mb-3">
    <label>Check-out Date</label>
    <input type="date" name="checkout_date" class="form-control"
        value="${existingLeadData?.checkout_date ?? ''}">
</div>` : ''}

<div class="col-md-3 mb-3">
    <label>Number of Rooms</label>
    <input type="number" name="number_of_rooms" class="form-control"
        value="${$('#edit_preserved_number_of_rooms').val() || existingLeadData?.number_of_rooms || ''}">
</div>

<div class="col-md-3 mb-3">
    <label>No. of Pax</label>
    <input type="number" name="pax" class="form-control" 
        value="${existingLeadData?.pax ?? ''}">
</div>

<div class="col-md-4 mb-3">
    <label>Adults</label>
    <input type="number" name="adults" class="form-control" 
        value="${existingLeadData?.adults ?? ''}">
</div>

<div class="col-md-4 mb-3">
    <label>Kids</label>
    <input type="number" name="kids" class="form-control" 
        value="${existingLeadData?.kids ?? ''}">
</div>



    
    <div class="col-md-4 mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>No. of Pax</label>
                <input type="number" name="pax" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Banquet <span class="required-marker">*</span></label>
                <select name="banquet_id" id="edit_banquet_id" class="form-select"></select>
            </div>

           

            <div class="col-md-4 mb-3">
                <label>Expected Revenue</label>
                <input type="number" name="amount" class="form-control">
            </div>



`);

                    loadRoomTypes(property, existingLeadData);
                    loadMealPlan(existingLeadData);
                    loadbanquets(property, existingLeadData);




                }


                container.append(`
                <div class="col-md-4 mb-3">
        <label class="form-label">Follow-up Date</label>
        <input type="date" name="followup_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">2nd Follow-up Date</label>
        <input type="date" name="second_followup_date" class="form-control">
    </div>
            `)

            }


            /* FOLLOWUP FIELDS */

            let followupFields = `

        <div class="col-md-4 mb-3">
            <label>Booking Enquiry Date</label>
            <input type="date" name="booking_enquiry_date" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
            <label>Follow-up Date</label>
            <input type="date" name="followup_date" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
            <label>2nd Follow-up Date</label>
            <input type="date" name="second_followup_date" class="form-control">
        </div>

    `;


            if (disposition === "Negotiations") {

                $("#edit_lead_status").val('In Progress');
                container.append(followupFields);
            }

            if (disposition === "Not Contacted" || disposition === "Unconfirmed Dates") {

                container.append(followupFields);
            }

            if (disposition === "Advance Received") {

                $("#edit_lead_status").val('Closed');
                container.append(followupFields);
            }


            /* AUTO FILL EXISTING DATA */

            if (typeof existingLeadData !== "undefined") {

                for (let key in existingLeadData) {

                    const field = container.find(`[name="${key}"]`);

                    if (field.length) {
                        field.val(existingLeadData[key]);
                    }
                }
            }

            initializeEditSingleSelect2(container);
            container.find('select:not([multiple])').trigger('change.select2');
            $('#edit_lead_status').trigger('change.select2');

        }


        function loadRestaurants(hotel_id, existingLeadData) {

            $('#edit_restaurant_id').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-restaurants') ?>",
                type: "POST",
                data: {
                    hotel_id: hotel_id
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Restaurant</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `<option value="${row.id}">${row.restaurant_name}</option>`;
                        });
                    }

                    $('#edit_restaurant_id').html(html);

                    if (existingLeadData && existingLeadData.restaurant_id) {
                        $('#edit_restaurant_id').val(existingLeadData.restaurant_id);
                    }
                    refreshEditSingleSelect2('#edit_restaurant_id');

                    if ($('#edit_restaurant_id').val()) {
                        loadTableCategories(
                            $('#edit_restaurant_id').val(),
                            existingLeadData?.table_category_id ?? null,
                            existingLeadData?.table_id ?? null
                        );
                    }
                }
            });
        }

        function loadbanquets(hotel_id, existingLeadData) {

            $('#edit_banquet_id').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-banquets') ?>",
                type: "POST",
                data: {
                    hotel_id: hotel_id
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Banquet</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `<option value="${row.banquet_id}">${row.banquet_name}</option>`;
                        });
                    }

                    $('#edit_banquet_id').html(html);

                    if (existingLeadData && existingLeadData.banquet_id) {
                        $('#edit_banquet_id').val(existingLeadData.banquet_id);
                    }
                    refreshEditSingleSelect2('#edit_banquet_id');
                }
            });
        }




        function loadMealPlan(existingLeadData) {

            $('#edit_meal_plan').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-meal-plans') ?>",
                type: "POST",
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Plan</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `<option value="${row.id}">${row.plan}</option>`;
                        });
                    }

                    $('#edit_meal_plan').html(html);

                    if (existingLeadData && existingLeadData.meal_plan) {
                        $('#edit_meal_plan').val(existingLeadData.meal_plan);
                    }
                    refreshEditSingleSelect2('#edit_meal_plan');
                }
            });
        }


        function loadpromotional_offers(department, existingLeadData) {

            $('#edit_promotional_offers').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-promotional-offers') ?>",
                type: "POST",
                data: {
                    department_id: department
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Offer</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `<option value="${row.id}">${row.offer_name}</option>`;
                        });
                    }

                    $('#edit_promotional_offers').html(html);

                    if (existingLeadData && existingLeadData.promotional_offers) {
                        $('#edit_promotional_offers').val(existingLeadData.promotional_offers);
                    }
                    refreshEditSingleSelect2('#edit_promotional_offers');
                }
            });
        }

        function loadRoomTypes(hotel_id, existingLeadData) {

            $('#edit_roomtype').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-room-types') ?>",
                type: "POST",
                data: {
                    hotel_id: hotel_id
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select roomtype</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `<option value="${row.roomtype_id}">${row.roomtype_name}</option>`;
                        });
                    }

                    $('#edit_roomtype').html(html);

                    if (existingLeadData && existingLeadData.roomtype) {
                        $('#edit_roomtype').val(existingLeadData.roomtype);
                    }
                    refreshEditSingleSelect2('#edit_roomtype');
                }
            });
        }




        function loadSlotTypes(existingLeadData) {

            $('#slot_type_id').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-slot-types') ?>",
                type: "GET",
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Slot</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `
                        <option value="${row.id}">
                            ${row.slot_name} (${row.start_time} - ${row.end_time})
                        </option>`;
                        });
                    }

                    $('#slot_type_id').html(html);
                    if (typeof existingLeadData !== "undefined" && existingLeadData.slot_type_id) {
                        $('#slot_type_id').val(existingLeadData.slot_type_id);
                    }
                    refreshEditSingleSelect2('#slot_type_id');

                    if ($('#slot_type_id').val()) {
                        loadTimeSlots(
                            $('#slot_type_id').val(),
                            existingLeadData?.time_slot_id ?? null
                        );
                    }
                }
            });
        }

        $(document).on('change', '#slot_type_id', function() {
            let slotTypeId = $(this).val();

            if (slotTypeId) {
                loadTimeSlots(slotTypeId);
            } else {
                $('#time_slot_id').html('<option value="">Select Time Slot</option>');
            }
        });


        function loadTimeSlots(slotTypeId, selectedTimeSlotId = null) {

            $('#time_slot_id').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-time-slots') ?>",
                type: "POST",
                data: {
                    slot_type_id: slotTypeId
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Time Slot</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `
                        <option value="${row.id}">
                            ${row.start_time} - ${row.end_time}
                        </option>`;
                        });
                    }

                    $('#time_slot_id').html(html);


                    if (typeof existingLeadData !== "undefined" && existingLeadData.time_slot_id) {
                        $('#time_slot_id').val(existingLeadData.time_slot_id);
                    }
                    refreshEditSingleSelect2('#time_slot_id');


                }
            });
        }


        $(document).on('change', '#edit_restaurant_id', function() {
            let restaurantId = $(this).val();

            if (restaurantId) {
                loadTableCategories(restaurantId);
            } else {
                $('#table_category_id').html('<option value="">Select Category</option>');
                $('#table_id').html('');
                initializeEditTableMultiSelect();
            }
        });

        function loadTableCategories(restaurantId, selectedCategoryId = null, selectedTableId = null) {

            $('#table_category_id').html('<option value="">Loading...</option>');

            csrfAjax({
                url: "<?= base_url('lead/get-table-categories') ?>",
                type: "POST",
                data: {
                    restaurant_id: restaurantId
                },
                dataType: "json",
                success: function(res) {

                    let html = '<option value="">Select Category</option>';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `
                        <option value="${row.id}">
                            ${row.category_name}
                        </option>`;
                        });
                    }

                    $('#table_category_id').html(html);

                    // ✅ For edit case
                    if (selectedCategoryId !== null && selectedCategoryId !== "") {
                        $('#table_category_id').val(selectedCategoryId);
                    }
                    refreshEditSingleSelect2('#table_category_id');

                    if ($('#table_category_id').val()) {
                        loadTables(restaurantId, $('#table_category_id').val(), selectedTableId);
                    } else {
                        $('#table_id').html('');
                        initializeEditTableMultiSelect();
                    }
                }
            });
        }


        $(document).on('change', '#table_category_id', function() {
            let categoryId = $(this).val();
            let restaurantId = $('#edit_restaurant_id').val(); // 🔥 important

            if (categoryId && restaurantId) {
                loadTables(restaurantId, categoryId);
            } else {
                $('#table_id').html('');
                initializeEditTableMultiSelect();
            }
        });


        function loadTables(restaurantId, categoryId, selectedTableId = null) {

            if ($('#table_id').hasClass('select2-hidden-accessible')) {
                $('#table_id').select2('destroy');
            }
            $('#table_id').html('');

            csrfAjax({
                url: "<?= base_url('lead/get-tables') ?>",
                type: "POST",
                data: {
                    restaurant_id: restaurantId,
                    category_id: categoryId
                },
                dataType: "json",
                success: function(res) {

                    let html = '';

                    if (res.status === 'success') {
                        $.each(res.data, function(i, row) {
                            html += `
                        <option value="${row.id}">
                            Table ${row.table_name} (${row.capacity} Seats)
                        </option>`;
                        });
                    }

                    const table = $('#table_id').html(html);

                    initializeEditTableMultiSelect();

                    // ✅ Edit case
                    if (selectedTableId !== null && selectedTableId !== "") {
                        const tableIds = Array.isArray(selectedTableId)
                            ? selectedTableId
                            : String(selectedTableId).split(',').map(function(id) { return id.trim(); });
                        table.val(tableIds).trigger('change');
                    }
                }
            });
        }



      let editReservationModalCanClose = false;
      let editReservationTables = [];
      let editReservationAvailabilityRequest = 0;

      function formatEditReservationTime(time) {
         if (!time) return '';
         const parts = String(time).split(':');
         let hour = parseInt(parts[0], 10);
         if (Number.isNaN(hour)) return time;
         const minutes = parts[1] || '00';
         const suffix = hour >= 12 ? 'PM' : 'AM';
         hour = hour % 12 || 12;
         return `${String(hour).padStart(2, '0')}:${minutes} ${suffix}`;
      }

      function resetEditReservationMessages() {
         $('#editReserveTableModal .is-invalid').removeClass('is-invalid');
         $('#edit_reserve_restaurant_error, #edit_reserve_booking_date_error, #edit_reserve_slot_type_error, #edit_reserve_time_slot_error, #edit_reserve_table_category_error, #edit_reserve_table_error, #edit_reserve_status_error').text('');
      }

      function resetEditReservationStats() {
         $('#edit_reserve_available_count, #edit_reserve_occupied_count, #edit_reserve_reserved_count, #edit_reserve_blocked_count, #edit_reserve_checkout_count').text('0');
      }

      function selectedEditReservationTableIds() {
         return $('#edit_reserve_table_grid .reservation-table-card.selected').map(function() {
            return String($(this).data('table-id'));
         }).get();
      }

      function renderEditReservationTables(conflictingTables = [], selectedTableIds = []) {
         const $grid = $('#edit_reserve_table_grid').empty();
         const conflictLabels = conflictingTables.map(label => String(label).trim().toLowerCase());
         const selectedIds = selectedTableIds.map(String);
         let availableCount = 0;
         let reservedCount = 0;

         if (!editReservationTables.length) {
            $grid.html('<div class="text-muted">No tables are available for the selected restaurant and category.</div>');
            resetEditReservationStats();
            return;
         }

         editReservationTables.forEach(function(table) {
            const labels = [table.table_name, table.table_number, table.table_number ? `Table ${table.table_number}` : '']
               .filter(Boolean).map(label => String(label).trim().toLowerCase());
            const unavailable = labels.some(label => conflictLabels.includes(label));
            const tableId = String(table.id);
            const selected = !unavailable && selectedIds.includes(tableId);
            const tableNumber = table.table_number || table.table_name || `T${table.id}`;
            const capacity = table.capacity ? `${table.capacity} Guests` : 'Guests not specified';
            const category = $('#edit_reserve_table_category_id option:selected').text() || 'Table';
            unavailable ? reservedCount++ : availableCount++;

            const $card = $('<div>', {
               class: `reservation-table-card${unavailable ? ' unavailable' : ''}${selected ? ' selected' : ''}`,
               'data-table-id': tableId,
               'data-available': unavailable ? '0' : '1'
            });
            $card.append(
               $('<span>', { class: 'table-icon' }).append($('<i>', { class: 'fa fa-cutlery' })),
               $('<div>', { class: 'table-number', text: tableNumber }),
               $('<div>', { class: 'table-details' }).append(document.createTextNode(capacity), $('<br>'), document.createTextNode(category)),
               $('<span>', { class: `table-status ${unavailable ? 'reserved' : 'available'}`, text: unavailable ? 'Reserved' : 'Available' })
            );
            $grid.append($card);
         });

         $('#edit_reserve_available_count').text(availableCount);
         $('#edit_reserve_reserved_count').text(reservedCount);
         $('#edit_reserve_occupied_count, #edit_reserve_blocked_count, #edit_reserve_checkout_count').text('0');
      }

      function refreshEditReservationAvailability(selectedTableIds = null) {
         const bookingDate = $('#edit_reserve_booking_date').val();
         const restaurantId = $('#edit_reserve_restaurant_id').val();
         const categoryId = $('#edit_reserve_table_category_id').val();
         const timeSlotId = $('#edit_reserve_time_slot_id').val();
         const slotTypeId = $('#edit_reserve_slot_type_id').val();
         const allTableIds = editReservationTables.map(table => table.id);
         const preservedSelection = selectedTableIds === null ? selectedEditReservationTableIds() : selectedTableIds.map(String);

         if (!bookingDate || !restaurantId || !categoryId || !timeSlotId || !slotTypeId || !allTableIds.length) {
            renderEditReservationTables([], preservedSelection);
            return;
         }

         const requestId = ++editReservationAvailabilityRequest;
         $('#edit_reserve_table_grid').addClass('opacity-50');
         csrfAjax({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: { booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: allTableIds, slot_type_id: slotTypeId, exclude_lead_id: $('#edit_lead_id').val() },
            dataType: 'json',
            success: function(res) {
               if (requestId !== editReservationAvailabilityRequest) return;
               const selectedSlot = (res.data || []).find(slot => String(slot.id) === String(timeSlotId));
               renderEditReservationTables(selectedSlot ? (selectedSlot.conflicting_tables || []) : [], preservedSelection);
            },
            error: function(xhr) {
               if (requestId !== editReservationAvailabilityRequest) return;
               renderEditReservationTables([], preservedSelection);
               $('#edit_reserve_table_error').text((xhr.responseJSON || {}).message || 'Unable to check table availability.');
            },
            complete: function() {
               if (requestId === editReservationAvailabilityRequest) $('#edit_reserve_table_grid').removeClass('opacity-50');
            }
         });
      }

      function loadEditReservationTables(restaurantId, categoryId, selectedTableIds = []) {
         editReservationTables = [];
         resetEditReservationStats();
         $('#edit_reserve_table_grid').html('<div class="text-muted">Loading tables...</div>');
         if (!restaurantId || !categoryId) {
            $('#edit_reserve_table_grid').html('<div class="text-muted">Select a restaurant and table category to view tables.</div>');
            return;
         }
         csrfAjax({
            url: "<?= base_url('lead/get-tables') ?>",
            type: 'POST',
            data: { restaurant_id: restaurantId, category_id: categoryId },
            dataType: 'json',
            success: function(res) {
               editReservationTables = res.status === 'success' ? (res.data || []) : [];
               renderEditReservationTables([], selectedTableIds);
               refreshEditReservationAvailability(selectedTableIds);
            },
            error: function() {
               editReservationTables = [];
               renderEditReservationTables();
               $('#edit_reserve_table_error').text('Unable to load restaurant tables.');
            }
         });
      }

      function loadEditReservationCategories(restaurantId, selectedCategoryId = '', selectedTableIds = []) {
         const $category = $('#edit_reserve_table_category_id').html('<option value="">Loading...</option>').prop('disabled', true);
         editReservationTables = [];
         resetEditReservationStats();
         $('#edit_reserve_table_grid').html('<div class="text-muted">Select a table category to view tables.</div>');
         if (!restaurantId) {
            $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
            return;
         }
         csrfAjax({
            url: "<?= base_url('lead/get-table-categories') ?>",
            type: 'POST',
            data: { restaurant_id: restaurantId },
            dataType: 'json',
            success: function(res) {
               $category.empty().append($('<option>', { value: '', text: 'Select Table Category' }));
               if (res.status === 'success') (res.data || []).forEach(row => $category.append($('<option>', { value: row.id, text: row.category_name })));
               if (selectedCategoryId) $category.val(String(selectedCategoryId));
               else if ($category.find('option[value!=""]').length === 1) $category.val($category.find('option[value!=""]').val());
               $category.prop('disabled', false);
               if ($category.val()) loadEditReservationTables(restaurantId, $category.val(), selectedTableIds);
            },
            error: function() {
               $category.html('<option value="">Select Table Category</option>').prop('disabled', false);
               $('#edit_reserve_table_category_error').text('Unable to load table categories.');
            }
         });
      }

      function loadEditReservationRestaurants(selectedRestaurantId = '', selectedCategoryId = '', selectedTableIds = []) {
         const $restaurant = $('#edit_reserve_restaurant_id').html('<option value="">Loading...</option>').prop('disabled', true);
         csrfAjax({
            url: "<?= base_url('lead/get-restaurants') ?>",
            type: 'POST',
            data: { hotel_id: $('#edit_property').val() },
            dataType: 'json',
            success: function(res) {
               $restaurant.empty().append($('<option>', { value: '', text: 'Select Restaurant' }));
               if (res.status === 'success') (res.data || []).forEach(row => $restaurant.append($('<option>', { value: row.id, text: row.restaurant_name })));
               if (selectedRestaurantId) $restaurant.val(String(selectedRestaurantId));
               else if ($restaurant.find('option[value!=""]').length === 1) $restaurant.val($restaurant.find('option[value!=""]').val());
               $restaurant.prop('disabled', false);
               loadEditReservationCategories($restaurant.val(), selectedCategoryId, selectedTableIds);
            },
            error: function() {
               $restaurant.html('<option value="">Select Restaurant</option>').prop('disabled', false);
               $('#edit_reserve_restaurant_error').text('Unable to load restaurants.');
            }
         });
      }

      function loadEditReservationTimeSlots(slotTypeId, selectedTimeSlotId = '') {
         const $timeSlot = $('#edit_reserve_time_slot_id').html(`<option value="">${slotTypeId ? 'Loading...' : 'Select Slot Type first'}</option>`).prop('disabled', true);
         if (!slotTypeId) {
            refreshEditReservationAvailability();
            return;
         }
         $.ajax({ url: "<?= base_url('lead/get-time-slots') ?>", type: 'GET', data: { slot_type_id: slotTypeId }, dataType: 'json' })
            .done(function(response) {
               $timeSlot.empty().append($('<option>', { value: '', text: 'Select Time Slot' }));
               if (response.status === 'success') {
                  (response.data || []).forEach(function(slot) {
                     const label = slot.start_time && slot.end_time ? `${formatEditReservationTime(slot.start_time)} - ${formatEditReservationTime(slot.end_time)}` : (slot.slot_name || 'Time Slot');
                     $timeSlot.append($('<option>', { value: slot.id, text: label }));
                  });
               }
               if (selectedTimeSlotId) $timeSlot.val(String(selectedTimeSlotId));
               $timeSlot.prop('disabled', false);
               refreshEditReservationAvailability();
            })
            .fail(function() {
               $timeSlot.html('<option value="">Select Time Slot</option>').prop('disabled', false);
               $('#edit_reserve_time_slot_error').text('Unable to load time slots.');
            });
      }

      function loadEditReservationSlotTypes(selectedSlotTypeId = '', selectedTimeSlotId = '') {
         const $slotType = $('#edit_reserve_slot_type_id').html('<option value="">Loading...</option>').prop('disabled', true);
         $('#edit_reserve_time_slot_id').html('<option value="">Select Slot Type first</option>').prop('disabled', true);
         $.ajax({ url: "<?= base_url('lead/get-slot-types') ?>", type: 'GET', dataType: 'json' })
            .done(function(response) {
               $slotType.empty().append($('<option>', { value: '', text: 'Select Slot Type' }));
               if (response.status === 'success') (response.data || []).forEach(row => $slotType.append($('<option>', { value: row.id, text: row.slot_name || row.name || 'Slot Type' })));
               if (selectedSlotTypeId) $slotType.val(String(selectedSlotTypeId));
               $slotType.prop('disabled', false);
               if ($slotType.val()) loadEditReservationTimeSlots($slotType.val(), selectedTimeSlotId);
            })
            .fail(function() {
               $slotType.html('<option value="">Select Slot Type</option>').prop('disabled', false);
               $('#edit_reserve_slot_type_error').text('Unable to load slot types.');
            });
      }

      function openEditReservationModal() {
         resetEditReservationMessages();
         editReservationModalCanClose = false;
         const selectedTableIds = ($('#table_id').val() || []).map(String);
         const status = $('#table_reservation_status').val() || 'Reserved';
         $('#edit_reserve_booking_date').removeAttr('min').val($('#edit_restaurant_booking_date').val() || '');
         $('#edit_reserve_special_request').val($('#edit_restaurant_special_request').val() || '');
         $(`input[name="edit_reserve_status"][value="${status}"]`).prop('checked', true);
         loadEditReservationRestaurants($('#edit_restaurant_id').val() || '', $('#table_category_id').val() || '', selectedTableIds);
         loadEditReservationSlotTypes($('#slot_type_id').val() || '', $('#time_slot_id').val() || '');
         $('#editReserveTableModal').modal({ backdrop: 'static', keyboard: false });
         $('#editReserveTableModal').modal('show');
      }

      function closeEditReservationModal() {
         editReservationModalCanClose = true;
         $('#editReserveTableModal').modal('hide');
      }

      function commitEditReservation() {
         const restaurantId = $('#edit_reserve_restaurant_id').val();
         const bookingDate = $('#edit_reserve_booking_date').val();
         const timeSlotId = $('#edit_reserve_time_slot_id').val();
         const slotTypeId = $('#edit_reserve_slot_type_id').val();
         const categoryId = $('#edit_reserve_table_category_id').val();
         const tableIds = selectedEditReservationTableIds();
         const status = $('input[name="edit_reserve_status"]:checked').val();
         const errors = {};
         resetEditReservationMessages();
         if (!restaurantId) errors.restaurant = 'Please select a restaurant.';
         if (!bookingDate) errors.bookingDate = 'Please select a booking date.';
         if (!slotTypeId) errors.slotType = 'Please select a slot type.';
         if (!timeSlotId) errors.timeSlot = 'Please select a time slot.';
         if (!categoryId) errors.category = 'Please select a table category.';
         if (!tableIds.length) errors.tables = 'Please select at least one available table.';
         if (!status) errors.status = 'Please select a reservation status.';
         if (errors.restaurant) $('#edit_reserve_restaurant_id').addClass('is-invalid');
         if (errors.bookingDate) $('#edit_reserve_booking_date').addClass('is-invalid');
         if (errors.slotType) $('#edit_reserve_slot_type_id').addClass('is-invalid');
         if (errors.timeSlot) $('#edit_reserve_time_slot_id').addClass('is-invalid');
         if (errors.category) $('#edit_reserve_table_category_id').addClass('is-invalid');
         $('#edit_reserve_restaurant_error').text(errors.restaurant || '');
         $('#edit_reserve_booking_date_error').text(errors.bookingDate || '');
         $('#edit_reserve_slot_type_error').text(errors.slotType || '');
         $('#edit_reserve_time_slot_error').text(errors.timeSlot || '');
         $('#edit_reserve_table_category_error').text(errors.category || '');
         $('#edit_reserve_table_error').text(errors.tables || '');
         $('#edit_reserve_status_error').text(errors.status || '');
         if (Object.keys(errors).length) return;

         const $button = $('#confirmEditTableReservation').prop('disabled', true);
         const buttonHtml = $button.html();
         $button.text('Checking availability...');
         csrfAjax({
            url: "<?= base_url('lead/check-restaurant-availability') ?>",
            type: 'POST',
            data: { booking_date: bookingDate, restaurant_id: restaurantId, table_category_id: categoryId, table_ids: tableIds, slot_type_id: slotTypeId, exclude_lead_id: $('#edit_lead_id').val() },
            dataType: 'json',
            success: function(res) {
               const selectedSlot = (res.data || []).find(slot => String(slot.id) === String(timeSlotId));
               if (!selectedSlot || !selectedSlot.available) {
                  $('#edit_reserve_table_error').text(selectedSlot ? selectedSlot.reason : 'The selected time slot is unavailable.');
                  refreshEditReservationAvailability(tableIds);
                  return;
               }

               $('#edit_restaurant_booking_date').val(bookingDate);
               $('#edit_restaurant_id').val(restaurantId);
               $('#table_category_id').val(categoryId);
               $('#slot_type_id').val(slotTypeId);
               $('#time_slot_id').val(timeSlotId);
               $('#table_reservation_status').val(status);
               $('#edit_restaurant_special_request').val($('#edit_reserve_special_request').val());
               const $tables = $('#table_id').empty();
               tableIds.forEach(tableId => $tables.append($('<option>', { value: tableId, text: tableId, selected: true })));
               existingLeadData.booking_date = bookingDate;
               existingLeadData.restaurant_id = restaurantId;
               existingLeadData.table_category_id = categoryId;
               existingLeadData.table_ids = tableIds.slice();
               existingLeadData.table_id = tableIds[0] || '';
               existingLeadData.slot_type_id = slotTypeId;
               existingLeadData.time_slot_id = timeSlotId;
               existingLeadData.table_reservation_status = status;
               existingLeadData.special_request = $('#edit_reserve_special_request').val();
               $('#edit_restaurant_reservation_summary').removeClass('text-muted').addClass('text-success').text(`${$('#edit_reserve_restaurant_id option:selected').text()} • ${bookingDate} • ${$('#edit_reserve_slot_type_id option:selected').text()} • ${$('#edit_reserve_time_slot_id option:selected').text()} • ${$('#edit_reserve_table_category_id option:selected').text()} • ${tableIds.length} table(s) • ${status}`);
               $('#openEditReserveTableModal').removeClass('is-invalid').removeAttr('aria-invalid').html('<i class="fa fa-pencil me-2"></i>Edit Reservation');
               $('#edit_restaurant_reservation_error').text('');
               closeEditReservationModal();
            },
            error: function(xhr) {
               $('#edit_reserve_table_error').text((xhr.responseJSON || {}).message || 'Unable to verify table availability.');
               refreshEditReservationAvailability(tableIds);
            },
            complete: function() { $button.prop('disabled', false).html(buttonHtml); }
         });
      }

      $(document).on('click', '#openEditReserveTableModal', openEditReservationModal);
      $(document).on('click', '#closeEditReserveTableModal, #cancelEditReserveTableModal', closeEditReservationModal);
      $(document).on('click', '#confirmEditTableReservation', commitEditReservation);
      $(document).on('change', '#edit_reserve_restaurant_id', function() { resetEditReservationMessages(); loadEditReservationCategories($(this).val()); });
      $(document).on('change', '#edit_reserve_table_category_id', function() { resetEditReservationMessages(); loadEditReservationTables($('#edit_reserve_restaurant_id').val(), $(this).val()); });
      $(document).on('change', '#edit_reserve_slot_type_id', function() { resetEditReservationMessages(); loadEditReservationTimeSlots($(this).val()); });
      $(document).on('change', '#edit_reserve_booking_date, #edit_reserve_time_slot_id', function() { resetEditReservationMessages(); refreshEditReservationAvailability(); });
      $(document).on('click', '#edit_reserve_table_grid .reservation-table-card', function() {
         if (String($(this).data('available')) !== '1') return;
         $(this).toggleClass('selected');
         $('#edit_reserve_table_error').text('');
      });
      $('#editReserveTableModal')
         .on('shown.bs.modal', function() { $('.modal-backdrop').last().css('z-index', 1075); })
         .on('hide.bs.modal', function(event) { if (!editReservationModalCanClose) event.preventDefault(); })
         .on('hidden.bs.modal', function() {
            editReservationModalCanClose = false;
            if ($('#editLeadDetails').hasClass('show')) $('body').addClass('modal-open');
         });




        $(document).on("input", "input[name='adults'], input[name='kids']", function() {

            let adults = parseInt($("input[name='adults']").val()) || 0;
            let kids = parseInt($("input[name='kids']").val()) || 0;

            let pax = adults + kids;

            $("input[name='pax']").val(pax);

        });

        $('#updateLead').on('click', function(e) {

            e.preventDefault();
            var statusField = $("#edit_lead_status");
            statusField.prop('disabled', false);

            if (!validateEditLeadForm()) {
                statusField.prop('disabled', true);
                return;
            }

            // Collect form values
            const formValues = {
                lead_id: $('#edit_lead_id').val(),
                user_name: $('#edit_user_name').val(),
                phone_number: ($('#edit_phone_number').val() || '').replace(/\D/g, '').slice(-10),
                email: $('#edit_email').val(),
                user_channel: $('#leadEditForm select[name="user_channel"]').val(),
                property: $('#edit_property').val(),
                type: $('#edit_type').val(),
                status: $('#edit_lead_status').val(),
                query: $('#edit_query').val(),
                remark: $('#edit_remark').val(),
                lead_type: $('#edit_lead_type').val(),
                leadDepartment: normalizeEditDepartmentName($('#edit_leadDepartment').val()),
                disposition: $('#edit_disposition').val()
            };

            // Build FormData
            let formData = new FormData();

            // Append fixed fields
            Object.keys(formValues).forEach(key => {
                if (formValues[key] !== null && formValues[key] !== undefined) {
                    formData.append(key, formValues[key]);
                }
            });


            let assigned_person_user_role = $('#assigned_person_user_role').val();

            let assigned_person_email = $('#assigned_person_email').val();;

            let assigned_to = $('select[name="assigned_to"]').val();

            let purpose = $('#edit_purpose').val();



            formData.append('assigned_person_user_role', assigned_person_user_role);
            formData.append('assigned_to', assigned_to);
            formData.append('assigned_person_email', assigned_person_email);

            formData.append('purpose', purpose);

            if (!formData.has('number_of_rooms') && $('#edit_preserved_number_of_rooms').val() !== '') {
                formData.append('number_of_rooms', $('#edit_preserved_number_of_rooms').val());
                formData.append('cross_department_room_count_controlled', '1');
            }

            // Append dynamic fields (inside #dynamicFields)
            $('#Edit_dynamicFields').find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (!name) return;

                if ($(this).attr('type') === 'file' && this.files.length > 0) {
                    formData.append(name, this.files[0]); // Single file
                } else if ($(this).attr('type') === 'checkbox') {
                    if (this.checked) {
                        formData.append(name, $(this).val());
                    }
                } else if ($(this).is('select[multiple]')) {
                    ($(this).val() || []).forEach(function(value) {
                        formData.append(name, value);
                    });
                } else {
                    formData.append(name, $(this).val());
                }
            });

            // AJAX request
            csrfAjax({
                url: '<?php echo base_url("update-lead-agent"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#updateLead').prop('disabled', true).text('Updating...');
                },

                success: function(response) {
                    if (response.status) {
                        toastr.success('Lead details has been updated successfully')
                        if (typeof window.fetchAgentLeads === 'function') {
                            window.fetchAgentLeads(true);
                        } else {
                            updateLeadCard($('#edit_lead_id').val(), response.data);
                        }
                        $("#editLeadDetails").modal('hide')
                    } else {
                        showEditLeadValidationErrors(response.errors || {});
                        toastr.error(response.message || 'Unable to update lead.');
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON || {};
                    showEditLeadValidationErrors(response.errors || {});
                    toastr.error(response.message || 'An unexpected error occurred. Please try again.');
                },
                complete: function() {
                    statusField.prop('disabled', true);
                    $('#updateLead').prop('disabled', false).text('Update Lead');
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

<script>
    $(document).ready(function() {

        let offset = 0; // pagination offset
        const limit = 100; // initial records to load

        function syncLeadFilterMultiSelect($select, $widget) {
            const selectedValues = ($select.val() || []).map(String);
            const $items = $widget.find('.lead-filter-multiselect-item');
            const total = $items.length;
            const selectedCount = selectedValues.length;

            $items.each(function() {
                $(this).prop('checked', selectedValues.includes(String($(this).val())));
            });

            const $selectAll = $widget.find('.lead-filter-multiselect-all');
            $selectAll.prop('checked', total > 0 && selectedCount === total);
            $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < total);

            let summary = 'Select Options';
            if (selectedCount > 0 && selectedCount === total) {
                summary = `All selected (${selectedCount})`;
            } else if (selectedCount > 0) {
                summary = `${selectedCount} selected`;
            }

            $widget.find('.lead-filter-multiselect-summary').text(summary);
        }

        function initializeLeadFilterMultiSelect(select) {
            const $select = $(select);
            if (!$select.length) return;

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.next('.lead-filter-multiselect').remove();

            const $widget = $('<div>', { class: 'lead-filter-multiselect' });
            const $toggle = $('<button>', {
                type: 'button',
                class: 'lead-filter-multiselect-toggle',
                'aria-expanded': 'false',
                'aria-haspopup': 'true'
            }).append($('<span>', {
                class: 'lead-filter-multiselect-summary',
                text: 'Select Options'
            }));
            const $menu = $('<div>', {
                class: 'lead-filter-multiselect-menu',
                role: 'group'
            });
            const $options = $select.find('option').filter(function() {
                return String(this.value).trim() !== '' && !this.disabled;
            });

            if ($options.length) {
                $menu.append(
                    $('<label>', {
                        class: 'lead-filter-multiselect-option lead-filter-multiselect-select-all'
                    }).append(
                        $('<input>', {
                            type: 'checkbox',
                            class: 'lead-filter-multiselect-all'
                        }),
                        $('<span>', { text: 'Select all' })
                    )
                );

                $options.each(function() {
                    $menu.append(
                        $('<label>', { class: 'lead-filter-multiselect-option' }).append(
                            $('<input>', {
                                type: 'checkbox',
                                class: 'lead-filter-multiselect-item',
                                value: this.value
                            }),
                            $('<span>').text($(this).text().trim())
                        )
                    );
                });
            }

            $widget.append($toggle, $menu);
            $select.after($widget);

            $toggle.on('click', function() {
                const isOpen = !$widget.hasClass('is-open');
                $('.lead-filter-multiselect').not($widget).removeClass('is-open')
                    .find('.lead-filter-multiselect-toggle').attr('aria-expanded', 'false');
                $widget.toggleClass('is-open', isOpen);
                $toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
            });

            $widget.on('change', '.lead-filter-multiselect-all', function() {
                const values = this.checked
                    ? $widget.find('.lead-filter-multiselect-item').map(function() {
                        return this.value;
                    }).get()
                    : [];
                $select.val(values).trigger('change');
            });

            $widget.on('change', '.lead-filter-multiselect-item', function() {
                const values = $widget.find('.lead-filter-multiselect-item:checked')
                    .map(function() {
                        return this.value;
                    }).get();
                $select.val(values).trigger('change');
            });

            $select
                .off('change.leadFilterMultiSelect')
                .on('change.leadFilterMultiSelect', function() {
                    syncLeadFilterMultiSelect($select, $widget);
                });

            syncLeadFilterMultiSelect($select, $widget);
        }


        // -------- GET PARAM READER (works for arrays too) --------
        function getUrlParamsArray(param) {
            const url = new URL(window.location.href);
            return url.searchParams.getAll(param); // support ?status=Open&status=Closed
        }

        var statusFromGet = getUrlParamsArray('status');
        var dispositionFromGet = getUrlParamsArray('disposition');

        var phoneFromGet = new URL(window.location.href).searchParams.get('phone') || '';
        var csrfTokenName = <?= json_encode($this->security->get_csrf_token_name()); ?>;
        var csrfCookieName = <?= json_encode($this->config->item('csrf_cookie_name')); ?>;

        function getCookieValue(cookieName) {
            var encodedName = encodeURIComponent(cookieName) + '=';
            var cookies = document.cookie ? document.cookie.split('; ') : [];

            for (var index = 0; index < cookies.length; index++) {
                if (cookies[index].indexOf(encodedName) === 0) {
                    return decodeURIComponent(cookies[index].substring(encodedName.length));
                }
            }

            return '';
        }






        // Pre-select multi-select status dropdown if GET exists
        if (statusFromGet.length > 0) {
            $("#status").val(statusFromGet);
        }


        if (dispositionFromGet.length > 0) {
            $("#disposition").val(dispositionFromGet);
        }

        $('#status, #channel, #disposition').each(function() {
            initializeLeadFilterMultiSelect(this);
        });

        $(document)
            .off('click.leadFilterMultiSelect')
            .on('click.leadFilterMultiSelect', function(event) {
                if (!$(event.target).closest('.lead-filter-multiselect').length) {
                    $('.lead-filter-multiselect').removeClass('is-open')
                        .find('.lead-filter-multiselect-toggle').attr('aria-expanded', 'false');
                }
            });


        // Function to fetch leads
        function fetchLeads(reset = false) {
            if (reset) offset = 0; // reset offset if new filters applied


            let filters = {
                status: $('#status').val(),
                channel: $('#channel').val(),
                disposition: $('#disposition').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                search: $('#lead-search').val(),
                business_type: $('#business_type').val() || [],
                phone: phoneFromGet,

                showfollowupleads: "<?= isset($showfollowupleads) ? $showfollowupleads : 'no' ?>",


                offset: offset,
                limit: limit
            };

            var currentCsrfHash = getCookieValue(csrfCookieName);
            if (currentCsrfHash) {
                filters[csrfTokenName] = currentCsrfHash;
            }

            csrfAjax({
                url: "<?= base_url('agent/Leads/fetch_leads_ajax') ?>",
                method: "POST",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    $("#processingLoader").show(); // Show loader
                },
                success: function(response) {

                    const totalCounts = response.totalCounts || {};

                    $('#status_count_open').text('Open (' + (totalCounts.open || 0) + ')');
                    $('#status_count_in_progress').text('In Progress (' + (totalCounts.in_progress || 0) + ')');
                    $('#status_count_closed').text('Closed (' + (totalCounts.closed || 0) + ')');

                    $('#status_count_not_assigned').text('Not Assigned (' + (totalCounts.not_assigned || 0) + ')');


                    $('#total_leads_count').text(totalCounts.total || 0);


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
                complete: function() {
                    $("#processingLoader").hide(); // Hide loader ALWAYS
                },
                error: function(xhr) {
                    $('#lead_container').html('<div class="alert alert-danger">Unable to load leads. Please refresh and try again.</div>');
                    $('#load_more_btn').hide();
                    console.error('Agent leads request failed:', xhr.status, xhr.responseText);
                }
            });
        }

        window.fetchAgentLeads = fetchLeads;

        // Initial load
        fetchLeads(true);

        // Trigger fetch when any filter changes
        $('#status, #channel, #disposition, #start_date, #end_date').on('change', function() {
            fetchLeads(true);
        });

        // Search input
        let searchTimer;
        $('#lead-search').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                fetchLeads(true);
            }, 300);
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
            select.val(selectedArray).trigger('change');
        });













    });






    $(document).on('click', '.view-lead-details', function() {
        var leadId = $(this).data('lead-id');

        csrfAjax({
            url: '<?= base_url("LeadController/get_lead_details_new") ?>',
            type: 'POST',
            data: {
                lead_id: leadId
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#processingLoader").show(); // Show loader
            },
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

                    const disposition = (data.disposition ?? '').toString().toLowerCase();
                    const status = (data.status ?? '').toString().toLowerCase();


                    let html = `
<div class="ticket-container">

<!-- Section 1 : Guest / Lead Info -->
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

<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Repeat Guest</strong></div><div class="ticket-value">${data.is_repeatative ? 'Yes' : 'No'}</div></div>
<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Created By</strong></div><div class="ticket-value">${data.created_by_name ?? 'NA'}</div></div>
<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Assigned To</strong></div><div class="ticket-value">${data.assigned_to_name ?? 'NA'}</div></div>

</div>


<!-- Section 2 : Timeline -->
<div class="row ticket-section">

<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Created Date</strong></div><div class="ticket-value">${formatDate(data.created_at)}</div></div>

<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Responded Time</strong></div><div class="ticket-value">${formatDate(data.responded_time)}</div></div>

<div class="col-md-4 mb-3"><div class="ticket-label"><strong>Completed Time</strong></div><div class="ticket-value">${formatDate(data.completed_time)}</div></div>

</div>


<!-- Section 3 : Lead Details -->
<div class="row ticket-section">

${data.query ? `
<div class="col-md-6 mb-3">
<div class="ticket-label"><strong>Description</strong></div>
<div class="ticket-value">${data.query}</div>
</div>` : ''}

${data.remark ? `
<div class="col-md-6 mb-3">
<div class="ticket-label"><strong>Remark</strong></div>
<div class="ticket-value">${data.remark}</div>
</div>` : ''}

${data.reason ? `
<div class="col-md-6 mb-3">
<div class="ticket-label"><strong>Reason</strong></div>
<div class="ticket-value">${data.reason}</div>
</div>` : ''}


${data.purpose ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Purpose</strong></div>
<div class="ticket-value">${data.purpose}</div>
</div>` : ''}





${data.plan ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Meal Plan</strong></div>
<div class="ticket-value">${data.plan}</div>
</div>` : ''}

${data.offer_name ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Special Offer</strong></div>
<div class="ticket-value">${data.offer_name}</div>
</div>` : ''}

${data.roomtype_name ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Room Type</strong></div>
<div class="ticket-value">${data.roomtype_name}</div>
</div>` : ''}




${data.checkin_date && data.checkin_date !== '0000-00-00' ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Check-in</strong></div>
<div class="ticket-value">${formatDate(data.checkin_date)}</div>
</div>` : ''}

${data.checkout_date && data.checkout_date !== '0000-00-00' ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Check-out</strong></div>
<div class="ticket-value">${formatDate(data.checkout_date)}</div>
</div>` : ''}

${data.number_of_rooms ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Number of Rooms</strong></div>
<div class="ticket-value">${data.number_of_rooms}</div>
</div>` : ''}


${data.checkin_time ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Check-in Time</strong></div>
<div class="ticket-value">${formatTime(data.checkin_time)}</div>
</div>` : ''}

${data.checkout_time ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Check-out Time</strong></div>
<div class="ticket-value">${formatTime(data.checkout_time)}</div>
</div>` : ''}

${data.arrival_time ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Arrival Time</strong></div>
<div class="ticket-value">${formatTime(data.arrival_time)}</div>
</div>` : ''}

${data.booking_date ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Booking Date</strong></div>
<div class="ticket-value">${formatDate(data.booking_date)}</div>
</div>` : ''}

${data.pax ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Pax</strong></div>
<div class="ticket-value">${data.pax}</div>
</div>` : ''}

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
</div>` : ''}

${data.slot_name ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Slot Type</strong></div>
    <div class="ticket-value">${data.slot_name}</div>
</div>` : ''}

${data.start_time && data.end_time ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Time Slot</strong></div>
    <div class="ticket-value">${data.start_time} - ${data.end_time}</div>
</div>` : ''}



${data.category_name ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Table Category</strong></div>
    <div class="ticket-value">${data.category_name}</div>
</div>` : ''}

${data.table_name ? `
<div class="col-md-4 mb-3">
    <div class="ticket-label"><strong>Table</strong></div>
    <div class="ticket-value">
        ${data.table_number} (${data.capacity} Seats)
    </div>
</div>` : ''}

${data.banquet_name ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Banquet Name</strong></div>
<div class="ticket-value">${data.banquet_name}</div>
</div>` : ''}

${data.special_occasion ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Special Occasion</strong></div>
<div class="ticket-value">${data.special_occasion}</div>
</div>` : ''}

${data.special_request ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Special Request</strong></div>
<div class="ticket-value">${data.special_request}</div>
</div>` : ''}

${data.sitting_style ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Sitting Style</strong></div>
<div class="ticket-value">${data.sitting_style}</div>
</div>` : ''}

${data.audio_visual ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Audio Visual</strong></div>
<div class="ticket-value">${data.audio_visual}</div>
</div>` : ''}

${data.btr ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>BTR</strong></div>
<div class="ticket-value">${data.btr}</div>
</div>` : ''}



${data.reservation_number ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Reservation No.</strong></div>
<div class="ticket-value">${data.reservation_number}</div>
</div>` : ''}

${data.followup_date && data.followup_date !== '0000-00-00' ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Follow-up Date</strong></div>
<div class="ticket-value">${formatDate(data.followup_date)}</div>
</div>` : ''}

${data.second_followup_date && data.second_followup_date !== '0000-00-00' ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>2nd Follow-up Date</strong></div>
<div class="ticket-value">${formatDate(data.second_followup_date)}</div>
</div>` : ''}

${data.lead_transfer_manager ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Transferred To</strong></div>
<div class="ticket-value">${data.lead_transfer_manager}</div>
</div>` : ''}

${data.bill_attachment ? `
<div class="col-md-4 mb-3">
<div class="ticket-label"><strong>Bill Attachment</strong></div>
<a href="<?= base_url('uploads/bills/') ?>${data.bill_attachment}" target="_blank" class="btn btn-sm btn-outline-primary">View Attachment</a>
</div>` : ''}

</div>

</div>
`;




                    $('#viewLeadContent').html(html);
                    $('#viewLeadModal').modal('show');

                } else {
                    alert(res.message);
                }
            },
            complete: function() {
                $("#processingLoader").hide(); // Hide loader ALWAYS
            }
        });

        function formatTime(time) {
            if (!time) return '';

            let t = new Date("1970-01-01T" + time);
            return t.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
    });

    $(document).on("click", ".deleteLeadBtn", function() {
        let leadId = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This lead will be removed from active leads.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel"
        }).then(function(result) {

            if (result.isConfirmed) {

                csrfAjax({
                    url: "<?= base_url('LeadController/deleteLead') ?>", // ✅ CHANGE to your actual controller method
                    type: "POST",
                    data: {
                        id: leadId
                    },
                    beforeSend: function() {
                        $("#processingLoader").show(); // Show loader
                    },
                    success: function(response) {

                        // Convert JSON response
                        let res = response;

                        if (res.status === true) {
                            toastr.success("Lead deleted successfully!");


                            $("#lead_card-" + leadId).fadeOut();


                        } else {
                            toastr.error("Failed to delete lead!");
                        }
                    },
                    error: function() {
                        toastr.error("Something went wrong!");
                    },
                    complete: function() {
                        $("#processingLoader").hide(); // Hide loader ALWAYS
                    }
                });

            }
        });
    });

    // Delegated event for dynamic fields
    $(document).on("change", "#checkin_date", function() {
        let today = new Date().toISOString().split("T")[0];
        let checkin = $(this).val();

        if (checkin < today) {
            showError($(this), "Check-in date cannot be in the past");
        } else {
            hideError($(this));
        }
    });

    $(document).on("change", "#checkout_date", function() {
        let checkin = $("#checkin_date").val();
        let checkout = $(this).val();

        if (checkout < checkin) {
            showError($(this), "Check-out date must be same or future date");
        } else {
            hideError($(this));
        }
    });

    function showError(input, msg) {
        input.addClass("is-invalid");
        input.siblings(".error-text").text(msg);
    }

    function hideError(input) {
        input.removeClass("is-invalid");
        input.siblings(".error-text").text("");
    }


    $(document).on("change", "#checkin_date, #checkout_date", function() {

        let checkin = $("#checkin_date").val();
        let checkout = $("#checkout_date").val();

        if (checkin === "" || checkout === "") return;
        if (checkout < checkin) return;

        generateRateRows(checkin, checkout);
    });

    function generateRateRows(startDate, endDate) {

        let tbody = $("#rateTypeTable tbody");
        tbody.empty();

        let start = new Date(startDate);
        let end = new Date(endDate);

        // IMPORTANT: Subtract 1 day to get hotel nights
        end.setDate(end.getDate() - 1);

        while (start <= end) {

            let dt = formatDate(start); // dd-mm-YYYY format

            let row = `
        <tr>
            <td>
                ${dt}
                <input type="hidden" name="rate_date[]" value="${dt}">
            </td>
            <td>
                <select name="rate_type[]" class="form-select rate-type-dd">
                <?php foreach ($ratetype as $ratetype) { ?>
                    <option value="<?= $ratetype->ratetype_code; ?>">
                        <?= $ratetype->ratetype_code; ?>
                    </option>
                <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" name="room_price[]" class="form-control room-price" min="0" value="0">
            </td>
        </tr>
        `;

            tbody.append(row);

            start.setDate(start.getDate() + 1);
        }

        $("#rateTypeTable").show();
    }



    // Date formatting function
    function formatDate(dateObj) {
        let d = dateObj.getDate().toString().padStart(2, '0');
        let m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        let y = dateObj.getFullYear();

        return `${d}-${m}-${y}`;
    }
</script>



<!-- Bootstrap Modal -->
<div class="modal modal-lg" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Table -->
                <table class="table table-bordered" id="roomsRateTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Room Type</th>
                            <th>Total Rooms</th>
                            <th>Available Rooms</th>
                            <th>In House</th>
                            <th>Confirmed</th>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<div id="processingLoader"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">

    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">

        <div class="spinner-border text-light" style="width: 4rem; height: 4rem;" role="status"></div>

        <div style="color:white; font-size:20px; margin-top:15px; font-weight:600;">
            Processing...
        </div>

    </div>
</div>



<script>
    $(document).on("click", "#checkAvailabilityBtn", function() {

        let checkin = $("#checkin_date").val().trim();
        let checkout = $("#checkout_date").val().trim();
        let roomtype = $("#roomtype").val();
        let hotelCode = $('#edit_property option:selected').data('hotel_code');



        console.log("Checkin value:", $("#checkin_date").val());
        console.log("Checkout value:", $("#checkout_date").val());
        console.log("Checkin length:", $("#checkin_date").val()?.length);
        console.log("Checkout length:", $("#checkout_date").val()?.length);


        if (!checkin || !checkout) {
            alert("Please select check-in and check-out dates");
            return false;
        }

        csrfAjax({
            url: "<?= base_url('LeadController/getRoomRateAvailabilityAjax') ?>",
            type: "POST",
            data: {
                chain_code: "00051",
                hotel_code: hotelCode,
                date_arrive: checkin,
                date_depart: checkout,
                adults: 1,
                youths: 0,
                kids: 0,
                number_of_rooms: "",
                price_from: 0,
                price_to: 0,
                room_type_code: ''
            },
            dataType: "json",
            beforeSend: function() {
                $("#processingLoader").show(); // Show loader
            },

            success: function(res) {
                if (res.status) {
                    renderRateTable(res.data.availability);

                    // Open modal
                    let myModal = new bootstrap.Modal(document.getElementById('availabilityModal'));
                    myModal.show();
                } else {
                    alert("No data found");
                }
            },
            complete: function() {
                $("#processingLoader").hide(); // Hide loader ALWAYS
            }
        });

    });

    function renderRateTable(data) {
        let html = "";

        data.forEach(function(item) {
            html += `
            <tr>
                <td>${item.date}</td>
                <td>${item.room_type_code}</td>
                <td>${item.rooms_total}</td>
                <td>${item.available_rooms}</td>
                <td>${item.rooms_inhouse}</td>
                <td>${item.rooms_confirmed}</td>
             
            </tr>
        `;
        });

        $("#roomsRateTable tbody").html(html);
    }
</script>


<div class="modal fade" id="whatsappTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-whatsapp text-success"></i>
                    Select WhatsApp Template
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="wa_property_id">
                <input type="hidden" id="wa_phone_number">
                <input type="hidden" id="wa_user_name">
                <input type="hidden" id="wa_created_at">

                <div class="form-group">
                    <label class="form-label">Select WhatsApp Template</label>
                    <select class="form-control" id="whatsapp_template_id">
                        <option value="">-- Select Template --</option>
                    </select>
                </div>

                <div class="text-end mt-3">
                    <button type="button" class="btn btn-success" id="sendWhatsappMessage">
                        <i class="fa fa-paper-plane"></i> Send Message
                    </button>
                </div>



            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).on('click', '.sendWhastappTempMsg', function() {

        let property_id = $(this).data('property_id');
        let phone_number = $(this).data('phone_number');
        let user_name = $(this).data('user_name');
        let created_at = $(this).data('created_at');

        $('#wa_property_id').val(property_id);
        $('#wa_phone_number').val(phone_number);
        $('#wa_user_name').val(user_name);
        $('#wa_created_at').val(created_at);

        $('#whatsappTemplateList').html(
            '<div class="text-center text-muted">Loading templates...</div>'
        );

        $('#whatsappTemplateModal').modal('show');

        csrfAjax({
            url: "<?= base_url('LeadController/getwhatsappTempByProperty'); ?>",
            type: "POST",
            data: {
                property_id: property_id
            },
            dataType: "json",
            success: function(res) {

                if (res.status === 'success' && res.data.length > 0) {

                    let html = '<option value="">-- Select Template --</option>';

                    $.each(res.data, function(i, row) {
                        html += `
        <option value="${row.id}">
            ${row.template_name} (${row.orai_template_code})
        </option>`;
                    });

                    $('#whatsapp_template_id').html(html);



                } else {
                    $('#whatsappTemplateList').html(
                        '<div class="text-center text-muted">No templates found.</div>'
                    );
                }
            }
        });
    });

    $(document).on('click', '#sendWhatsappMessage', function() {

        let template_id = $('#whatsapp_template_id').val();
        let phone_number = $('#wa_phone_number').val();
        let property_id = $('#wa_property_id').val();
        let user_name = $('#wa_user_name').val();
        let created_at = $('#wa_created_at').val();

        if (!template_id) {
            toastr.warning('Please select a WhatsApp template');
            return;
        }

        csrfAjax({
            url: "<?= base_url('LeadController/sendwhatsappMessage'); ?>",
            type: "POST",
            data: {
                template_id: template_id,
                phone_number: phone_number,
                property_id: property_id,
                user_name: user_name,
                created_at: created_at
            },
            dataType: "json",
            beforeSend: function() {
                $('#sendWhatsappMessage').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Sending...');
            },
            success: function(res) {

                if (res.status === 'success') {
                    toastr.success(res.message || 'WhatsApp message sent successfully');
                    $('#whatsappTemplateModal').modal('hide');
                } else {
                    toastr.error(res.message || 'Failed to send WhatsApp message');
                }
            },
            error: function() {
                toastr.error('Something went wrong. Please try again');
            },
            complete: function() {
                $('#sendWhatsappMessage').prop('disabled', false)
                    .html('<i class="fa fa-paper-plane"></i> Send Message');
            }
        });
    });
</script>
