<!-- Content Wrapper. Contains page content -->
<style>
.fw-500 {
    font-size: 20px;
}

text.highcharts-credits {
    display: none;
}
</style>

<style>
.lead-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin: 0 !important;
}

.lead-kpi-row .col-xl-3.col-md-6.col-12 {
    margin-bottom: 14px;
}

.lead-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin: 0 !important;
}



.lead-kpi-row>[class*="col-"] {
    width: auto;
    max-width: none;
    padding: 0;
}

.lead-kpi-row a {
    display: block;
    height: 100%;
    color: inherit;
    text-decoration: none;
}

.premium-card {
    position: relative;
    box-sizing: border-box;
    min-height: 148px;
    height: 100%;
    overflow: hidden;
    padding: 16px;
    border: 1px solid #e9edf6;
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 7px 20px rgba(38, 27, 76, .06);
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    padding-top: 8px
}

.premium-card:hover {
    transform: translateY(-4px);
    border-color: #d4c8f5;
    box-shadow: 0 18px 34px rgba(84, 67, 145, .14);
}

.premium-card::before {
    content: "";
    /* position: absolute;
    z-index: 2;
    top: 14px;
    right: 16px;
    color: #91a0b8;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 2px;
    line-height: 1; */
}

.premium-card::after {
    content: "";
    position: absolute;
    z-index: 0;
    right: -54px;
    bottom: -66px;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: radial-gradient(circle, rgb(159 139 231 / 23%), rgb(159 139 231 / 3%) 68%);
    pointer-events: none;
}

.premium-card>* {
    position: relative;
    z-index: 1;
}

.card-top {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    height: 44px;
    margin: 0;
    padding-right: 0px;
}

.icon-box {
    position: absolute;
    top: 0px;
    left: 0px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 13px;
    color: #fff;
    font-size: 18px;
    box-shadow: 0 8px 16px rgba(31, 41, 55, .18);
}

.growth {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin: 0;
    padding: 7px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    line-height: 1;
    white-space: nowrap;
}

.up {
    color: #159947;
    background: #eaf8ef;
}

.down {
    color: #e53935;
    background: #fff0f0;
}

.stage-title {
    position: absolute;
    top: 73px;
    left: 16px;
    right: 16px;
    margin: 0;
    color: #000;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: .035em;
    line-height: 1.2;
    text-transform: uppercase;
    white-space: nowrap;
}

.lead-count {
    position: absolute;
    top: 73px;
    /* left: 16px; */
    right: 16px;
    margin: 0;
    color: #101828;
    font-size: 30px;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -.05em;
}

.lead-count[data-lead-status="total_revenue"] {
    font-size: 28px;
}

/* .sparkline {
    position: absolute;
    z-index: 1;
    right: 16px;
    bottom: -6px;
    left: 16px;
    display: block;
    height: 40px;
    margin: 0;
    background: center / 100% 100% no-repeat url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 280 48'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='0' y2='1'%3E%3Cstop stop-color='%239F8BE7' stop-opacity='.22'/%3E%3Cstop offset='1' stop-color='%239F8BE7' stop-opacity='0'/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d='M1 35 C14 44 23 38 34 30 S55 23 68 31 S89 39 103 26 S124 12 138 23 S158 38 174 29 S194 18 208 26 S229 38 244 25 S263 18 279 9 L279 48 L1 48Z' fill='url(%23g)'/%3E%3Cpath d='M1 35 C14 44 23 38 34 30 S55 23 68 31 S89 39 103 26 S124 12 138 23 S158 38 174 29 S194 18 208 26 S229 38 244 25 S263 18 279 9' fill='none' stroke='%239F8BE7' stroke-width='2.2' stroke-linecap='round'/%3E%3Ccircle cx='103' cy='26' r='2.7' fill='%239F8BE7'/%3E%3Ccircle cx='138' cy='23' r='2.7' fill='%239F8BE7'/%3E%3Ccircle cx='279' cy='9' r='2.7' fill='%239F8BE7'/%3E%3C/svg%3E");
} */

.sparkline {
    position: absolute;
    z-index: 1;
    /* right: 0px; */
    bottom: -3px;
    left: -1px;
    display: block;
    height: 52px;
    margin: 0;
    background: center / 100% 100% no-repeat url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 280 48'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='0' y2='1'%3E%3Cstop stop-color='%239F8BE7' stop-opacity='.22'/%3E%3Cstop offset='1' stop-color='%239F8BE7' stop-opacity='0'/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d='M1 35 C14 44 23 38 34 30 S55 23 68 31 S89 39 103 26 S124 12 138 23 S158 38 174 29 S194 18 208 26 S229 38 244 25 S263 18 279 9 L279 48 L1 48Z' fill='url(%23g)'/%3E%3Cpath d='M1 35 C14 44 23 38 34 30 S55 23 68 31 S89 39 103 26 S124 12 138 23 S158 38 174 29 S194 18 208 26 S229 38 244 25 S263 18 279 9' fill='none' stroke='%239F8BE7' stroke-width='2.2' stroke-linecap='round'/%3E%3Ccircle cx='103' cy='26' r='2.7' fill='%239F8BE7'/%3E%3Ccircle cx='138' cy='23' r='2.7' fill='%239F8BE7'/%3E%3Ccircle cx='279' cy='9' r='2.7' fill='%239F8BE7'/%3E%3C/svg%3E");
    width: 278px;
    background-size: cover;
    transform: rotate(-7deg);
    opacity: 0.3;
}

.sparkline span {
    display: none;
}

.sparkline span:nth-child(1) {
    height: 10px;
}

.sparkline span:nth-child(2) {
    height: 16px;
}

.sparkline span:nth-child(3) {
    height: 12px;
}

.sparkline span:nth-child(4) {
    height: 22px;
}

.sparkline span:nth-child(5) {
    height: 18px;
}

.sparkline span:nth-child(6) {
    height: 28px;
}

.sparkline span:nth-child(7) {
    height: 20px;
}

.sparkline span:nth-child(8) {
    height: 30px;
}

.revenue-box {
    position: absolute;
    z-index: 2;
    left: 16px;
    bottom: 5px;
    display: inline-flex;
    align-items: center;
    min-height: 27px;
    padding: 6px 9px;
    border-radius: 9px;
    background: #eaf8ef !important;
    color: #159947 !important;
    font-size: 11px;
    font-weight: 800;
    line-height: 1;
}

.lead-count[data-lead-status="total_revenue"]+.sparkline+.revenue-box {
    right: 16px;
    bottom: 5px;
    left: auto;
    background: #f1eaff !important;
    color: #7c3aed !important;
}

.bg-red {
    background: linear-gradient(135deg, #fb7185, #e91e4d) !important;
}

.bg-green {
    background: linear-gradient(135deg, #26cf91, #00a76f) !important;
}

.bg-blue {
    background: linear-gradient(135deg, #4b95ff, #1768e8) !important;
}

.bg-orange {
    background: linear-gradient(135deg, #ffb51c, #f57c00) !important;
}

.bg-cyan {
    background: linear-gradient(135deg, #1cc6df, #0797b5) !important;
}

.bg-purple {
    background: linear-gradient(135deg, #a663ff, #7021db) !important;
}

.premium-card:has(.bg-red) .sparkline {
    filter: hue-rotate(90deg) saturate(1.45);
}

.premium-card:has(.bg-green) .sparkline {
    filter: hue-rotate(250deg) saturate(1.35);
}

.premium-card:has(.bg-blue) .sparkline {
    filter: hue-rotate(315deg) saturate(1.4);
}

.premium-card:has(.bg-orange) .sparkline {
    filter: hue-rotate(130deg) saturate(1.55);
}

.premium-card:has(.bg-cyan) .sparkline {
    filter: hue-rotate(290deg) saturate(1.35);
}

.premium-card:has(.bg-red) .revenue-box {
    background: #fff0f1 !important;
    color: #e91e4d !important;
}

.premium-card:has(.bg-orange) .revenue-box {
    background: #fff4e6 !important;
    color: #ed7c00 !important;
}

@media (max-width: 1199px) {
    .lead-kpi-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 575px) {
    .lead-kpi-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .premium-card {
        min-height: 210px;
        padding: 15px;
    }
}

@keyframes grow {

    from {

        opacity: .55;

    }

    to {

        opacity: 1;

    }

}
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">




                <!-- quick filters -->

                <div class="col-xxl-12 col-12">


                    <div class="box_supar_admin">
                        <div class="box-header no-border pb-0">
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div id="">

                                    <style>
                                    /* ===== Professional Filter Panel UI ===== */

                                    /* #filter_lead_stats_count {
											background: #ffffff;
											border: 1px solid #e9eef5;
											border-radius: 18px;
											padding: 18px;
											box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
											margin-bottom: 20px;
										} */



                                    /* #filter_lead_stats_count .form-control,
										#filter_lead_stats_count .form-select {
											border-radius: 12px;
											border: 1px solid #dbe3ee;
											font-size: 14px;
											box-shadow: none !important;
											transition: .25s ease;
										} */

                                    #filter_lead_stats_count .form-control:focus,
                                    #filter_lead_stats_count .form-select:focus {
                                        border-color: #3b82f6;
                                        box-shadow: 0 0 0 3px rgba(59, 130, 246, .08) !important;
                                    }

                                    #filter_lead_stats_count .row {
                                        row-gap: 14px;
                                    }

                                    #filter_lead_stats_count .select2-container,
                                    .quick-filter-box .select2-container {
                                        height: 56px !important;
                                        max-height: 56px !important;
                                        min-height: 56px !important;
                                        width: 100% !important;
                                    }

                                    #filter_lead_stats_count .select2-container .select2-selection--single,
                                    .quick-filter-box .select2-container .select2-selection--single {
                                        background: #fff;
                                        border: 1px solid #dbe3ee;
                                        border-radius: 12px;
                                        box-sizing: border-box !important;
                                        height: 56px !important;
                                        min-height: 56px !important;
                                        max-height: 56px !important;
                                        padding: 0 14px !important;
                                    }

                                    #filter_lead_stats_count .select2-container .select2-selection--single .select2-selection__rendered,
                                    .quick-filter-box .select2-container .select2-selection--single .select2-selection__rendered {
                                        color: #1e293b;
                                        height: 54px !important;
                                        line-height: 54px !important;
                                        padding-bottom: 0 !important;
                                        padding-left: 0 !important;
                                        padding-right: 28px !important;
                                        padding-top: 0 !important;
                                    }

                                    #filter_lead_stats_count .select2-container .select2-selection--single .select2-selection__arrow,
                                    .quick-filter-box .select2-container .select2-selection--single .select2-selection__arrow {
                                        height: 54px !important;
                                        right: 8px !important;
                                        top: 0 !important;
                                    }

                                    #filter_lead_stats_count .select2-container.select2-container--focus .select2-selection--single,
                                    #filter_lead_stats_count .select2-container.select2-container--open .select2-selection--single,
                                    .quick-filter-box .select2-container.select2-container--focus .select2-selection--single,
                                    .quick-filter-box .select2-container.select2-container--open .select2-selection--single {
                                        border-color: #2563eb;
                                        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
                                    }

                                    #filter_lead_stats_count h5 {
                                        background: linear-gradient(135deg, #2563eb, #1d4ed8);
                                        color: #fff;
                                        margin: 0;
                                        height: 31px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 8px;
                                        font-size: 15px;
                                        font-weight: 700;
                                        padding: 0 15px;
                                    }

                                    #filter_lead_stats_count h5 span {
                                        margin-left: 6px;
                                        font-size: 18px;
                                    }

                                    @media(max-width:768px) {
                                        #filter_lead_stats_count {
                                            padding: 15px;
                                        }

                                        #filter_lead_stats_count h5 {
                                            margin-top: 4px;
                                        }
                                    }
                                    </style>



                                    <form id="filter_lead_stats_count">
                                        <div class="row align-items-end">

                                            <!-- Property -->
                                            <div class="col-md-3">
                                                <label for="top_filter_property" class="form-label">Property</label>
                                                <select name="property" id="top_filter_property" class="form-select">
                                                    <option value="">All Properties</option>
                                                    <?php foreach ($properties as $property) { ?>
                                                    <option value="<?= $property->hotel_id; ?>"
                                                        <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                                        <?= $property->hotel_name; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- Department -->
                                            <div class="col-md-3">
                                                <label for="top_filter_department" class="form-label">Department</label>
                                                <select name="department" id="top_filter_department"
                                                    class="form-select">
                                                    <option value="">All Departments</option>
                                                    <?php foreach ($departments as $dept) { ?>
                                                    <option value="<?= $dept->department_id; ?>"
                                                        <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
                                                        <?= $dept->department_name; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- Assigned To -->
                                            <div class="col-md-3">
                                                <label for="top_filter_assigned_to" class="form-label">Assigned
                                                    User</label>
                                                <select name="assigned_to" id="top_filter_assigned_to"
                                                    class="form-control">
                                                    <option value="">All Assigned Users</option>

                                                    <?php foreach ($assigned_users as $user):
														$value = $user->id . '|' . $user->role;
														$selected = (isset($_GET['assigned_to']) && $_GET['assigned_to'] == $value) ? 'selected' : '';
													?>
                                                    <option value="<?= $value ?>" <?= $selected ?>
                                                        data-id="<?= $user->id ?>" data-role="<?= $user->role ?>">
                                                        <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                                        (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Created By -->
                                            <div class="col-md-3">
                                                <label for="top_filter_created_by" class="form-label">Created By</label>
                                                <select name="created_by" id="top_filter_created_by"
                                                    class="form-control">
                                                    <option value="">All Creators</option>

                                                    <?php foreach ($creators as $user):
														$value = $user->id . '|' . $user->role;
														$selected = (isset($_GET['created_by']) && $_GET['created_by'] == $value) ? 'selected' : '';
													?>
                                                    <option value="<?= $value ?>" <?= $selected ?>
                                                        data-id="<?= $user->id ?>" data-role="<?= $user->role ?>">
                                                        <?= htmlspecialchars($user->name ?? 'Unknown') ?>
                                                        (<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Lead Source -->
                                            <div class="col-md-3">
                                                <label for="top_filter_channel" class="form-label">Lead Source</label>
                                                <select name="channel" id="top_filter_channel"
                                                    class="form-select filter-input">
                                                    <option value="">All Sources</option>
                                                    <?php foreach ($user_channel as $channelObj): ?>
                                                    <?php $channel = $channelObj->user_channel; ?>
                                                    <option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Stage -->
                                            <div class="col-md-2">
                                                <label for="top_filter_disposition" class="form-label">Stage</label>
                                                <select name="disposition" id="top_filter_disposition"
                                                    class="form-select filter-input">
                                                    <option value="">All Stages</option>
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

                                            <!-- Start Date -->
                                            <div class="col-md-2">
                                                <label for="top_filter_start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" id="top_filter_start_date"
                                                    class="form-control"
                                                    value="<?= $this->input->get('start_date'); ?>">
                                            </div>

                                            <!-- End Date -->
                                            <div class="col-md-2">
                                                <label for="top_filter_end_date" class="form-label">End Date</label>
                                                <input type="date" name="end_date" id="top_filter_end_date"
                                                    class="form-control" value="<?= $this->input->get('end_date'); ?>">
                                            </div>

                                            <!-- Total Leads -->
                                            <div class="col-md-3">
                                                <h5>Total Leads : <span id="totalLeads"><?= $total_leads ?></span></h5>
                                            </div>

                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>

                        <div class="box-body" id="top_stats_html">







                            <div class="row lead-kpi-row">

                                <!-- Total Open -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?status=Open') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-red">
                                                    <i class="fa fa-folder-open"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 18%
                                                </span>
                                            </div>
                                            <div class="stage-title">Total Open</div>
                                            <div class="lead-count" data-lead-status="Open">
                                                <?= $lead_status_counts['Open']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" style="background:#fef2f2;color:#dc2626;">
                                                ₹ <?= number_format($lead_revenue['Open']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>



                                <!-- In Progress -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?status=In+Progress') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-cyan">
                                                    <i class="fa fa-spinner"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 12%
                                                </span>
                                            </div>
                                            <div class="stage-title">In Progress</div>
                                            <div class="lead-count" data-lead-status="In Progress">
                                                <?= $lead_status_counts['In Progress']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['In Progress']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>



                                <!-- Total Closed -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?status=Closed') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-green">
                                                    <i class="fa fa-check-circle"></i>
                                                </div>
                                                <span class="growth down">
                                                    <i class="fa fa-arrow-down"></i> 5%
                                                </span>
                                            </div>
                                            <div class="stage-title">Total Closed</div>
                                            <div class="lead-count" data-lead-status="Closed">
                                                <?= $lead_status_counts['Closed']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['Closed']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>



                                <!-- Not Assigned -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?status=Not-assigned') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-user-times"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 23%
                                                </span>
                                            </div>
                                            <div class="stage-title">Not Assigned</div>
                                            <div class="lead-count" data-lead-status="Not Assigned">
                                                <?= $lead_status_counts['Not_Assigned']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" style="background:#fff7ed;color:#ea580c;">
                                                ₹ <?= number_format($lead_revenue['Not_Assigned']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>




                            <style type="text/plain">
                                /* ===== Modern Horizontal KPI Card ===== */

                            .stage-card {
                                background: #ffffff;
                                border-radius: 18px;
                                padding: 16px 18px;
                                border: 1px solid #edf1f7;
                                box-shadow: 0 8px 22px rgba(0, 0, 0, .05);
                                transition: .30s ease;
                                height: 100%;
                                position: relative;
                                overflow: hidden;
                                box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
                            }

                            .stage-card:hover {
                                transform: translateY(-4px);
                                box-shadow: 0 14px 28px rgba(0, 0, 0, .08);
                            }

                            .stage-card:before {
                                content: "";
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 5px;
                                height: 100%;
                                border-radius: 20px;
                            }

                            .border-blue:before {
                                background: #2563eb;
                            }

                            .border-orange:before {
                                background: #ea580c;
                            }

                            .border-cyan:before {
                                background: #0891b2;
                            }

                            .border-purple:before {
                                background: #7c3aed;
                            }

                            .stage-flex {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                gap: 15px;
                            }

                            .left-content {
                                flex: 1;
                            }

                           .stage-title {
    position: absolute;
    top: 73px;
    left: 16px;
    right: 16px;
    margin: 0;
    color: #000000;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: .035em;
    line-height: 1.2;
    text-transform: uppercase;
    white-space: nowrap;
}

                            .lead-count {
                                font-size: 28px;
                                font-weight: 800;
                                line-height: 1;
                                margin-bottom: 8px;
                            }

                            .revenue-box {
                                display: inline-block;
                                font-size: 12px;
                                font-weight: 700;
                                padding: 5px 10px;
                                border-radius: 30px;
                                background: #f0fdf4;
                                color: #16a34a;
                            }

                            .icon-box {
                                width: 58px;
                                height: 58px;
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 24px;
                                color: #fff;
                                flex-shrink: 0;
                            }

                            /* Colors */
                            .bg-blue {
                                background: linear-gradient(135deg, #3b82f6, #2563eb);
                            }

                            .bg-orange {
                                background: linear-gradient(135deg, #f59e0b, #ea580c);
                            }

                            .bg-cyan {
                                background: linear-gradient(135deg, #06b6d4, #0891b2);
                            }

                            .bg-purple {
                                background: linear-gradient(135deg, #8b5cf6, #6d28d9);
                            }

                            @media(max-width:768px) {
                                .lead-count {

                                    font-size: 22px;
                                }

                                .icon-box {
                                    width: 48px;
                                    height: 48px;
                                    font-size: 20px;
                                }
                            }
                            </style>



                            <div class="row lead-kpi-row">

                                <!-- Not Contacted -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Not Contacted') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-blue">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 30%
                                                </span>
                                            </div>
                                            <div class="stage-title">Not Contacted</div>
                                            <div class="lead-count" data-lead-status="Not_Contacted">
                                                <?= $lead_status_counts['Not_Contacted']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Not_Contacted">
                                                ₹ <?= number_format($lead_revenue['Not_Contacted']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Quotation Sent -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Quotation Sent') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 15%
                                                </span>
                                            </div>
                                            <div class="stage-title">Quotation Sent</div>
                                            <div class="lead-count" data-lead-status="Quotation_Sent">
                                                <?= $lead_status_counts['Quotation_Sent']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Quotation_Sent">
                                                ₹ <?= number_format($lead_revenue['Quotation_Sent']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Negotiations -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Negotiations') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-cyan">
                                                    <i class="fa fa-handshake"></i>
                                                </div>
                                                <span class="growth down">
                                                    <i class="fa fa-arrow-down"></i> 3%
                                                </span>
                                            </div>
                                            <div class="stage-title">Negotiations</div>
                                            <div class="lead-count" data-lead-status="Negotiations">
                                                <?= $lead_status_counts['Negotiations']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Negotiations">
                                                ₹ <?= number_format($lead_revenue['Negotiations']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Contract Done -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Contract Done') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-purple">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 8%
                                                </span>
                                            </div>
                                            <div class="stage-title">Contract Done</div>
                                            <div class="lead-count" data-lead-status="Contract_Done">
                                                <?= $lead_status_counts['Contract_Done']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Contract_Done">
                                                ₹ <?= number_format($lead_revenue['Contract_Done']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>



                            <div class="row  lead-kpi-row">

                                <!-- Advance Received -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Advance Received') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-money-bill"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 10%
                                                </span>
                                            </div>
                                            <div class="stage-title">Advance Received</div>
                                            <div class="lead-count" data-lead-status="Advance_Received">
                                                <?= $lead_status_counts['Advance_Received']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Advance_Received">
                                                ₹ <?= number_format($lead_revenue['Advance_Received']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Lead Won -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Lead Won') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-green">
                                                    <i class="fa fa-trophy"></i>
                                                </div>
                                                <span class="growth up">
                                                    <i class="fa fa-arrow-up"></i> 40%
                                                </span>
                                            </div>
                                            <div class="stage-title">Lead Won</div>
                                            <div class="lead-count" data-lead-status="Lead_Won">
                                                <?= $lead_status_counts['Lead_Won']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" data-lead-revenue="Lead_Won">
                                                ₹ <?= number_format($lead_revenue['Lead_Won']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Lead Lost -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <a href="<?= base_url('manage-leads?disposition=Lead Lost') ?>">
                                        <div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-red">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <span class="growth down">
                                                    <i class="fa fa-arrow-down"></i> 15%
                                                </span>
                                            </div>
                                            <div class="stage-title">Lead Lost</div>
                                            <div class="lead-count" data-lead-status="Lead_Lost">
                                                <?= $lead_status_counts['Lead_Lost']; ?>
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="revenue-box" style="background:#fef2f2;color:#dc2626;"
                                                data-lead-revenue="Lead_Lost">
                                                ₹ <?= number_format($lead_revenue['Lead_Lost']); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>


                                <!-- Total Revenue -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="premium-card">
                                        <div class="card-top">
                                            <div class="icon-box bg-purple">
                                                <i class="fa fa-inr"></i>
                                            </div>
                                            <span class="growth up">
                                                <i class="fa fa-arrow-up"></i> 25%
                                            </span>
                                        </div>
                                        <div class="stage-title">Total <br> Revenue</div>
                                        <div class="lead-count" data-lead-status="total_revenue">
                                            ₹ <?= number_format($total_revenue, 2); ?>
                                        </div>
                                        <div class="sparkline">
                                            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        </div>
                                        <div class="revenue-box">
                                            Overall Collection
                                        </div>
                                    </div>
                                </div>

                            </div>





                        </div>
                    </div>
                </div>




                <style>
                /* ===== Quick Filters Modern UI ===== */

                .quick-filter-box {
                    background: #ffffff;
                    border: 1px solid #e9eef5;
                    border-radius: 18px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
                    overflow: hidden;
                }

                .quick-filter-box .box-header {
                    padding: 18px 20px 10px;
                    border-bottom: 1px solid #eef2f7;
                    background: linear-gradient(135deg, #f8fafc, #ffffff);
                }

                .quick-filter-box .box-title {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 700;
                    color: #1e293b;
                }

                .quick-filter-box .box-body {
                    padding: 18px 20px;
                }

                .quick-filter-box .form-label {
                    font-size: 13px;
                    font-weight: 700;
                    color: #000;
                    margin-bottom: 6px;
                }
                </style>



                <div class="col-xxl-12 col-12">
                    <div class="box quick-filter-box">

                        <div class="box-header no-border pb-0">
                            <h4 class="box-title">Quick Filters for Charts</h4>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <form>
                                    <div class="">

                                        <form method="GET" action="<?= base_url('manage-leads'); ?>" class="mb-4 px-3">

                                            <div class="row align-items-end">

                                                <!-- Property -->
                                                <div class="col-md-3">
                                                    <label for="property" class="form-label">Property</label>
                                                    <select name="property_bottom" class="form-select">
                                                        <option value="">All Properties</option>
                                                        <?php foreach ($properties as $property) { ?>
                                                        <option value="<?= $property->hotel_id; ?>"
                                                            <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
                                                            <?= $property->hotel_name; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- Department -->
                                                <div class="col-md-3">
                                                    <label for="department" class="form-label">Department</label>
                                                    <select name="department_bottom" class="form-select">
                                                        <option value="">All Departments</option>
                                                        <?php foreach ($departments as $dept) { ?>
                                                        <option value="<?= $dept->department_id; ?>"
                                                            <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
                                                            <?= $dept->department_name; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- Start Date -->
                                                <div class="col-md-2">
                                                    <label for="start_date" class="form-label">Start Date</label>
                                                    <input type="date" name="start_date_bottom" class="form-control"
                                                        value="<?= $this->input->get('start_date'); ?>">
                                                </div>

                                                <!-- End Date -->
                                                <div class="col-md-2">
                                                    <label for="end_date" class="form-label">End Date</label>
                                                    <input type="date" name="end_date_bottom" class="form-control"
                                                        value="<?= $this->input->get('end_date'); ?>">
                                                </div>

                                                <!-- Button -->
                                                <div class="col-md-2 d-grid">
                                                    <button type="button" id="filter_bottom_button"
                                                        class="btn btn-primary">
                                                        Filter
                                                    </button>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="chart_box_main">
                    <div class="chart_box_main_list row">
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_department_line"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_status_new"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_stage_bar"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_guest_type"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <div class="sales-funnel-box">
                                <div id="sales_funnel_chart"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_polar_area"></canvas>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="box chart-container" id="container_department">

                            <div id="chart_department" style="height: 400px;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box chart-container" id="container_status">
                            <div id="chart_status" style="height: 400px;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box chart-container" id="container_disposition">
                            <div id="chart_disposition" style="height: 400px;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box chart-container" id="container_source">

                            <div id="chart_source" style="height: 400px;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box chart-container" id="container_guest_type">

                            <div id="chart_guest_type" style="height: 400px;"></div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="box chart-container" id="container_template_name">

                            <div id="chart_template_name" style="height: 400px;"></div>
                        </div>
                    </div>

                </div> -->
            </div>
        </section>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Highcharts and modules -->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.highcharts.com/modules/funnel.js"></script>

        <style>
        .chart_box_main_items canvas {
            background: #fff;
            border-radius: 25px;
            padding: 20px;
            box-shadow:
                0 15px 35px rgba(0, 0, 0, .12),
                0 5px 10px rgba(0, 0, 0, .08);
            width: 100%;
            height: 360px !important;
            margin-bottom: 30px
        }

        .sales-funnel-box #sales_funnel_chart {
            width: 100%;
            height: 360px;
            margin-bottom: 30px;
        }

        .sales-funnel-card {
            box-sizing: border-box;
            height: 360px;
            padding: 23px 24px;
            border: 1px solid #edf0f6;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .09);
        }

        .sales-funnel-card__title {
            margin: 0 0 19px;
            color: #172033;
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
        }

        .sales-funnel-card__content {
            display: flex;
            align-items: center;
            gap: 26px;
        }

        .sales-funnel-visual {
            display: flex;
            flex: 0 0 47%;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .sales-funnel-step {
            height: 36px;
            clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 10px 100%);
        }

        .sales-funnel-step:last-child {
            height: 38px;
            clip-path: polygon(10px 0, calc(100% - 10px) 0, calc(100% - 17px) 100%, 17px 100%);
        }

        .sales-funnel-legend {
            display: grid;
            flex: 1;
            gap: 12px;
        }

        .sales-funnel-legend__item {
            display: grid;
            grid-template-columns: 11px minmax(0, 1fr) auto;
            align-items: center;
            column-gap: 9px;
            color: #667085;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
        }

        .sales-funnel-legend__dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .sales-funnel-legend__value {
            color: #172033;
            font-weight: 800;
            white-space: nowrap;
        }

        @media (max-width: 575px) {
            .sales-funnel-card {
                height: auto;
                min-height: 440px;
                padding: 20px;
            }

            .sales-funnel-card__content {
                flex-direction: column;
                gap: 22px;
            }

            .sales-funnel-visual {
                width: 100%;
            }

            .sales-funnel-legend {
                width: 100%;
            }
        }

        canvas#chart_status_new {
            width: 100% !important;
            object-fit: contain;
        }
        </style>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Highcharts core and modules -->
        <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/modules/full-screen.js"></script>



        <script>
        if (false) Highcharts.chart('sales_funnel_chart', {

            chart: {
                type: 'funnel',
                backgroundColor: 'transparent',
                spacingTop: 20,
                spacingBottom: 20,
                spacingLeft: 10,
                spacingRight: 20
            },

            title: {
                text: 'Sales Funnel',
                align: 'left',
                margin: 25,
                style: {
                    fontSize: '22px',
                    fontWeight: '700',
                    color: '#1f2937'
                }
            },
            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false
            },

            plotOptions: {

                series: {

                    width: '68%',
                    neckWidth: '45%',
                    neckHeight: '50%',

                    borderWidth: 3,
                    borderColor: '#ffffff',

                    dataLabels: {
                        enabled: true,
                        distance: 18,
                        softConnector: false,
                        connectorWidth: 2,
                        connectorColor: '#bdbdbd',
                        style: {
                            textOutline: 'none',
                            fontSize: '14px',
                            fontWeight: '600'
                        }
                    },

                    states: {
                        hover: {
                            brightness: 0.15
                        }
                    }

                }

            },

            colors: [

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5EA8FF'],
                        [1, '#2F6FE4']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5ED4FF'],
                        [1, '#2497E3']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#48E58C'],
                        [1, '#22C55E']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#A8F05C'],
                        [1, '#7ED321']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFD65A'],
                        [1, '#F4B400']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFA94D'],
                        [1, '#FF7A00']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FF5FA2'],
                        [1, '#E91E63']
                    ]
                }

            ],

            series: [{

                name: 'Leads',

                data: [

                    ['Enquiry', 4083],

                    ['Qualified', 2891],

                    ['Contacted', 2103],

                    ['Demo', 1203],

                    ['Negotiation', 563],

                    ['Quotation', 183],

                    ['Won', 84]

                ]

            }]

        });
        </script>

        <script>
        (function() {
            const funnelStages = [{
                    name: 'Enquiry',
                    value: 4083,
                    color: '#3b82f6',
                    width: 100
                },
                {
                    name: 'Qualified',
                    value: 2891,
                    color: '#38bdf8',
                    width: 91
                },
                {
                    name: 'Contacted',
                    value: 2103,
                    color: '#34d399',
                    width: 81
                },
                {
                    name: 'Demo',
                    value: 1203,
                    color: '#62c76b',
                    width: 70
                },
                {
                    name: 'Negotiation',
                    value: 563,
                    color: '#f7ad20',
                    width: 58
                },
                {
                    name: 'Quotation',
                    value: 183,
                    color: '#f57c2d',
                    width: 46
                },
                {
                    name: 'Won',
                    value: 84,
                    color: '#e83d87',
                    width: 34
                }
            ];

            const funnelTarget = document.getElementById('sales_funnel_chart');
            const funnelBase = funnelStages[0].value;

            if (!funnelTarget) return;

            const funnelSteps = funnelStages.map(function(stage) {
                return '<div class="sales-funnel-step" style="width:' + stage.width + '%;background:' +
                    stage.color + '"></div>';
            }).join('');

            const funnelLegend = funnelStages.map(function(stage) {
                const percentage = (stage.value / funnelBase * 100).toFixed(1).replace('.0', '');
                return '<div class="sales-funnel-legend__item">' +
                    '<span class="sales-funnel-legend__dot" style="background:' + stage.color +
                    '"></span>' +
                    '<span>' + stage.name + '</span>' +
                    '<strong class="sales-funnel-legend__value">' + stage.value.toLocaleString('en-IN') +
                    ' (' + percentage + '%)</strong>' +
                    '</div>';
            }).join('');

            funnelTarget.innerHTML = '<section class="sales-funnel-card" aria-label="Sales Funnel">' +
                '<h3 class="sales-funnel-card__title">Sales Funnel</h3>' +
                '<div class="sales-funnel-card__content">' +
                '<div class="sales-funnel-visual" aria-hidden="true">' + funnelSteps + '</div>' +
                '<div class="sales-funnel-legend">' + funnelLegend + '</div>' +
                '</div></section>';
        })();
        </script>

        <script>
        const polarCtx = document.getElementById("chart_polar_area").getContext("2d");

        new Chart(polarCtx, {

            type: "polarArea",

            data: {

                labels: [
                    "Open",
                    "In Progress",
                    "Closed",
                    "Won",
                    "Lost"
                ],

                datasets: [{

                    data: [300, 180, 120, 90, 60],

                    backgroundColor: [

                        "rgba(59,130,246,.85)",
                        "rgba(139,92,246,.85)",
                        "rgba(34,197,94,.85)",
                        "rgba(251,191,36,.85)",
                        "rgba(239,68,68,.85)"

                    ],

                    borderColor: "#ffffff",

                    borderWidth: 3,

                    hoverBorderWidth: 5,

                    hoverOffset: 20

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1800
                },

                plugins: {

                    title: {

                        display: true,

                        text: "Lead Distribution",

                        color: "#111827",

                        font: {
                            size: 22,
                            weight: "700"
                        },

                        padding: {
                            bottom: 20
                        }

                    },

                    legend: {

                        position: "bottom",

                        labels: {

                            usePointStyle: true,

                            pointStyle: "circle",

                            padding: 18,

                            font: {
                                size: 14,
                                weight: "600"
                            }

                        }

                    },

                    tooltip: {

                        backgroundColor: "#23211D",

                        titleColor: "#fff",

                        bodyColor: "#fff",

                        cornerRadius: 12,

                        padding: 12,

                        displayColors: true

                    }

                },

                scales: {

                    r: {

                        grid: {
                            color: "rgba(0,0,0,.08)"
                        },

                        angleLines: {
                            color: "rgba(0,0,0,.08)"
                        },

                        ticks: {
                            display: false
                        },

                        pointLabels: {
                            display: false
                        }

                    }

                }

            }

        });
        </script>


        <!-- <script>
			function renderHighchart(containerId, title, categories, series, type = 'column') {
				const isPie = type === 'pie';

				Highcharts.chart(containerId, {
					chart: {
						type: isPie ? 'pie' : type
					},
					title: {
						text: title
					},
					exporting: {
						enabled: true
					},
					accessibility: {
						enabled: true
					},
					legend: {
						enabled: true
					},
					tooltip: {
						pointFormat: isPie ? '<b>{point.y}</b>' : '<b>{point.y}</b>'
					},
					series: isPie ? [{
						name: title,
						colorByPoint: true,
						data: categories.map((label, index) => ({
							name: label,
							y: series[index]
						}))
					}] : [{
						name: title,
						data: series
					}],
					xAxis: !isPie ? {
						categories: categories
					} : undefined,
					yAxis: !isPie ? {
						min: 0,
						title: {
							text: 'Count'
						}
					} : undefined,
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.y}'
							}
						},
						series: {
							showInLegend: true
						}
					}
				});
			}

			function fetchAndRenderChart(endpoint, containerId, title, type) {
				const filters = {
					city: $('select[name="city_bottom"]').val(),
					property: $('select[name="property_bottom"]').val(),
					type: $('select[name="department_bottom"]').val(),
					start_date: $('input[name="start_date_bottom"]').val(),
					end_date: $('input[name="end_date_bottom"]').val()
				};

				$.ajax({
					url: endpoint,
					type: 'GET',
					data: filters,
					dataType: 'json',
					success: function(data) {
						const categories = data.map(d => d.label);
						const counts = data.map(d => parseInt(d.count));
						renderHighchart(containerId, title, categories, counts, type);
					},
					error: function() {
						$('#' + containerId).html('<p>Error loading data.</p>');
					}
				});
			}

			$(document).ready(function() {
				const chartConfigs = [{
						id: 'chart_department',
						endpoint: '<?= base_url("superAdmin/Main/department_chart_data") ?>',
						title: 'Leads by Department',
						type: 'column',
						startId: 'start_department',
						endId: 'end_department'
					},
					{
						id: 'chart_status',
						endpoint: '<?= base_url("superAdmin/Main/status_chart_data") ?>',
						title: 'Leads by Status',
						type: 'pie',
						startId: 'start_status',
						endId: 'end_status'
					},
					{
						id: 'chart_disposition',
						endpoint: '<?= base_url("superAdmin/Main/disposition_chart_data") ?>',
						title: 'Leads by Stage',
						type: 'bar',
						startId: 'start_disposition',
						endId: 'end_disposition'
					},
					{
						id: 'chart_source',
						endpoint: '<?= base_url("superAdmin/Main/source_chart_data") ?>',
						title: 'Leads by Source',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_guest_type',
						endpoint: '<?= base_url("superAdmin/Main/guest_type_chart_data") ?>',
						title: 'Leads by Guest Type',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_template_name',
						endpoint: '<?= base_url("superAdmin/Main/template_chart_data") ?>',
						title: 'Leads by Templates',
						type: 'column',
						startId: 'start_source',
						endId: 'end_source'
					}
				];

				chartConfigs.forEach(config => {
					fetchAndRenderChart(config.endpoint, config.id, config.title, config.type);

					$('#' + config.startId + ', #' + config.endId).on('change', function() {
						const start = $('#' + config.startId).val();
						const end = $('#' + config.endId).val();
						fetchAndRenderChart(config.endpoint, config.id, config.title, config.type, start, end);
					});
				});


				function reloadAllCharts() {
					chartConfigs.forEach(config => {
						fetchAndRenderChart(config.endpoint, config.id, config.title, config.type);
					});
				}

				// Initial load
				reloadAllCharts();

				// Global filter button
				$('#filter_bottom_button').on('click', function(e) {
					e.preventDefault();
					reloadAllCharts();
				});
			});
		</script> -->

        <!-- ====== =chart1======= -->

        <script>
        const data = {
            labels: ['🏨 Rooms', '🍽 Restaurant', '🎉 Banquets'],
            datasets: [{
                label: 'Leads by Department',
                data: [65, 45, 80],

                borderColor: '#9F8BE7',
                borderWidth: 5,


                backgroundColor: function(context) {

                    const chart = context.chart;
                    const {
                        ctx,
                        chartArea
                    } = chart;

                    if (!chartArea) {
                        return '#9F8BE7';
                    }

                    const gradient = ctx.createLinearGradient(
                        0,
                        chartArea.top,
                        0,
                        chartArea.bottom
                    );

                    gradient.addColorStop(0, '#9f8be798');
                    gradient.addColorStop(.5, '#9f8be737');
                    gradient.addColorStop(1, 'rgba(255,255,255,0)');

                    return gradient;
                },

                fill: true,

                tension: .55,

                pointRadius: 8,
                pointHoverRadius: 12,

                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#9F8BE7',
                pointBorderWidth: 4,

                cubicInterpolationMode: 'monotone'
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {

                responsive: true,

                maintainAspectRatio: true,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: [{
                    id: 'labelShadow',

                    beforeDraw(chart) {

                        const ctx = chart.ctx;

                        ctx.save();
                        ctx.shadowColor = 'rgba(0,0,0,.12)';
                        ctx.shadowBlur = 4;
                        ctx.shadowOffsetY = 2;

                    },

                    afterDraw(chart) {
                        chart.ctx.restore();
                    }
                }],

                elements: {

                    line: {
                        borderJoinStyle: 'round'
                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false,
                            drawBorder: false
                        },

                        border: {
                            display: true
                        },

                        ticks: {

                            color: '#000',

                            padding: 15,

                            font: {
                                size: 15,
                                weight: '600',
                                // family: "'Poppins', sans-serif"
                            }

                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: 'rgba(0,0,0,.06)'
                        },

                        ticks: {
                            color: '#666'
                        }

                    }

                }

            },
        };
        new Chart(
            document.getElementById('chart_department_line'),
            config
        );
        </script>

        <script>
        const statusData = {

            labels: [
                'Open',
                'In Progress',
                'Closed',
                'Lost'
            ],

            datasets: [{

                data: [55, 32, 21, 12],

                borderWidth: 0,

                spacing: 6,

                hoverOffset: 18,

                cutout: '72%',

                borderRadius: 25,

                backgroundColor: function(context) {

                    const chart = context.chart;

                    const {
                        ctx,
                        chartArea
                    } = chart;

                    if (!chartArea) {

                        return [
                            '#3B82F6',
                            '#8B5CF6',
                            '#22C55E',
                            '#EF4444'
                        ];

                    }

                    function gradient(c1, c2) {

                        const g = ctx.createLinearGradient(0, 0, 0, chartArea.bottom);

                        g.addColorStop(0, c1);

                        g.addColorStop(1, c2);

                        return g;

                    }

                    return [

                        gradient('#60A5FA', '#2563EB'),

                        gradient('#C084FC', '#7C3AED'),

                        gradient('#4ADE80', '#16A34A'),

                        gradient('#FB7185', '#DC2626')

                    ];

                }

            }]

        };

        const centerText = {

            id: 'centerText',

            beforeDraw(chart) {

                const {
                    ctx
                } = chart;

                const meta = chart.getDatasetMeta(0);

                if (!meta.data.length) return;

                const x = meta.data[0].x;

                const y = meta.data[0].y;

                ctx.save();

                ctx.textAlign = 'center';

                ctx.fillStyle = '#111827';

                ctx.font = '700 34px Poppins';

                ctx.fillText('120', x, y - 6);

                ctx.font = '500 15px Poppins';

                ctx.fillStyle = '#9F8BE7';

                ctx.fillText('Total Leads', x, y + 22);

                ctx.restore();

            }

        };

        const shadowPlugin = {

            id: 'shadow',

            beforeDatasetsDraw(chart) {

                const ctx = chart.ctx;

                ctx.save();

                ctx.shadowColor = 'rgba(0,0,0,.20)';

                ctx.shadowBlur = 30;

                ctx.shadowOffsetY = 12;

            },

            afterDatasetsDraw(chart) {

                chart.ctx.restore();

            }

        };
        new Chart(

            document.getElementById('chart_status_new'),

            {

                type: 'doughnut',

                data: statusData,

                plugins: [centerText, shadowPlugin],

                options: {

                    responsive: true,

                    maintainAspectRatio: true,

                    animation: {

                        animateRotate: true,

                        animateScale: true,

                        duration: 2500,

                        easing: 'easeOutElastic'

                    },

                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                padding: 25,

                                boxWidth: 16,

                                boxHeight: 16,

                                usePointStyle: true,

                                pointStyle: 'circle',

                                font: {

                                    size: 14,

                                    weight: 'bold'

                                }

                            }

                        }

                    }

                }

            }

        );
        </script>

        <script>
        const stageData = {

            labels: [

                "📞 Not Contacted",
                "📄 Quotation",
                "🤝 Negotiation",
                "📃 Contract",
                "💰 Advance",
                "🏆 Won",
                "❌ Lost"

            ],

            datasets: [{

                label: "Lead Stage",

                data: [35, 58, 76, 48, 65, 90, 18],

                borderRadius: 0,

                borderSkipped: false,

                barThickness: 50,

                hoverBorderWidth: 2,

                hoverBorderColor: "#ffffff",

                backgroundColor: function(context) {

                    const chart = context.chart;
                    const {
                        ctx,
                        chartArea
                    } = chart;

                    if (!chartArea) {

                        return "#4F46E5";

                    }

                    function gradient(c1, c2) {

                        const g = ctx.createLinearGradient(
                            chartArea.left,
                            0,
                            chartArea.right,
                            0
                        );

                        g.addColorStop(0, c1);
                        g.addColorStop(1, c2);

                        return g;

                    }

                    return [

                        gradient("#60A5FA", "#2563EB"),
                        gradient("#38BDF8", "#0284C7"),
                        gradient("#A78BFA", "#7C3AED"),
                        gradient("#FB923C", "#EA580C"),
                        gradient("#34D399", "#059669"),
                        gradient("#FACC15", "#EAB308"),
                        gradient("#FB7185", "#DC2626")

                    ];

                }

            }]

        };

        const stageConfig = {

            type: "bar",

            data: stageData,

            options: {

                // indexAxis:"y",   // ❌ Remove this line
                // ya
                indexAxis: "x", // ✅ Vertical Bar Chart

                responsive: true,

                maintainAspectRatio: true,

                animation: {
                    duration: 2200,
                    easing: "easeOutQuart"
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    title: {
                        display: true,
                        text: "Lead Stage Overview",
                        font: {
                            size: 20,
                            weight: "bold"
                        }
                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: "#111",
                            font: {
                                size: 13,
                                weight: "600"
                            }
                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: "rgba(0,0,0,.05)"
                        },

                        ticks: {
                            color: "#555"
                        }

                    }

                }

            }

        };

        new Chart(
            document.getElementById("chart_stage_bar"),
            stageConfig
        );
        </script>

        <script>
        const ctx = document.getElementById("chart_guest_type").getContext("2d");

        // Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, "#9f8be745");
        gradient.addColorStop(1, "#9f8be702");

        new Chart(ctx, {

            type: "line",

            data: {

                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],

                datasets: [{

                    label: "Guest",

                    data: [65, 59, 80, 81, 56, 55, 40],

                    borderColor: "#9F8BE7",

                    backgroundColor: gradient,

                    fill: true,

                    tension: 0,

                    borderWidth: 3,

                    pointRadius: 5,

                    pointHoverRadius: 8,

                    pointBackgroundColor: "#fff",

                    pointBorderColor: "#9F8BE7",

                    pointBorderWidth: 3,

                    hoverBorderWidth: 4
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: "index"
                },

                plugins: {
                    title: {
                        display: true,
                        text: "Guest Type Trend",
                        color: "#000",
                        font: {
                            size: 20,
                            weight: "700"
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    legend: {
                        display: false
                    }
                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: "#000"
                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: "#9f8be741"
                        },

                        ticks: {
                            color: "#000"
                        }

                    }

                },

                animation: {

                    duration: 1800,

                    easing: "easeOutQuart"

                }

            }

        });
        </script>







        <script>
        $(document).ready(function() {

            if ($.fn.select2) {
                $('#filter_lead_stats_count select, .quick-filter-box select').each(function() {
                    const $select = $(this);

                    if (!$select.hasClass('select2-hidden-accessible')) {
                        $select.select2({
                            width: '100%',
                            minimumResultsForSearch: 0
                        });
                    }

                    const $container = $select.next('.select2-container');
                    const $selection = $container.find('.select2-selection--single');
                    const $rendered = $selection.find('.select2-selection__rendered');
                    const $arrow = $selection.find('.select2-selection__arrow');

                    [$container, $selection].forEach(function($element) {
                        if ($element.length) {
                            $element[0].style.setProperty('box-sizing', 'border-box',
                                'important');
                        }
                    });

                    if ($selection.length) {
                        $selection[0].style.setProperty('padding', '0 14px', 'important');
                    }
                    if ($rendered.length) {
                        $rendered[0].style.setProperty('height', '54px', 'important');
                        $rendered[0].style.setProperty('line-height', '54px', 'important');
                        $rendered[0].style.setProperty('padding-top', '0', 'important');
                        $rendered[0].style.setProperty('padding-bottom', '0', 'important');
                    }
                    if ($arrow.length) {
                        $arrow[0].style.setProperty('height', '54px', 'important');
                        $arrow[0].style.setProperty('top', '0', 'important');
                    }
                });
            }

            /* =========================================
               AUTO FILTER ON CHANGE
            ========================================= */
            $('#top_filter_property, \
		#top_filter_department, \
		#top_filter_channel, \
		#top_filter_disposition, \
		#top_filter_start_date, \
		#top_filter_end_date, \
		#top_filter_created_by, \
		#top_filter_assigned_to')
                .on('change', function() {
                    applyTopFilters();
                });

        });


        /* =========================================
           SPLIT USER VALUE => id|role
        ========================================= */
        function getUserFilterData(selector) {

            let val = $(selector).val();

            if (!val) {
                return {
                    id: '',
                    role: ''
                };
            }

            let parts = val.split('|');

            return {
                id: parts[0] || '',
                role: parts[1] || ''
            };
        }

        applyTopFilters();

        /* =========================================
           MAIN FILTER FUNCTION
        ========================================= */
        function applyTopFilters() {

            let createdUser = getUserFilterData('#top_filter_created_by');
            let assignedUser = getUserFilterData('#top_filter_assigned_to');


            let filters = {

                property: $('#top_filter_property').val(),
                department: $('#top_filter_department').val(),
                channel: $('#top_filter_channel').val(),
                disposition: $('#top_filter_disposition').val(),

                start_date: $('#top_filter_start_date').val(),
                end_date: $('#top_filter_end_date').val(),

                created_id: createdUser.id,
                created_role: createdUser.role,

                assigned_id: assignedUser.id,
                assigned_role: assignedUser.role
            };
            filters[window.CSRF.name] = window.CSRF.hash;

            console.log(filters)


            $.ajax({
                type: "POST",
                url: "<?= base_url('superAdmin/Main/dashboard_top_filter') ?>",
                data: filters,
                dataType: "json",

                beforeSend: function() {
                    $('#totalLeads').html('...');
                },

                success: function(response) {
                    if (response.csrfHash) {
                        window.CSRF.hash = response.csrfHash;
                    }

                    /* =========================
                       OVERALL STATUS COUNTS
                    ========================= */
                    $('[data-lead-status="Open"]').text(response.Open);
                    $('[data-lead-status="In Progress"]').text(response['In Progress']);
                    $('[data-lead-status="On Hold"]').text(response['On Hold']);
                    $('[data-lead-status="Closed"]').text(response.Closed);
                    $('[data-lead-status="Not Assigned"]').text(response.Not_Assigned);


                    /* =========================
                       STAGE COUNTS
                    ========================= */
                    $('[data-lead-status="Not_Contacted"]').text(response.Not_Contacted);
                    $('[data-lead-status="Quotation_Sent"]').text(response.Quotation_Sent);
                    $('[data-lead-status="Negotiations"]').text(response.Negotiations);
                    $('[data-lead-status="Contract_Done"]').text(response.Contract_Done);
                    $('[data-lead-status="Advance_Received"]').text(response.Advance_Received);
                    $('[data-lead-status="Lead_Won"]').text(response.Lead_Won);
                    $('[data-lead-status="Lead_Lost"]').text(response.Lead_Lost);



                    $('[data-lead-revenue="Not_Contacted"]').text(formatIndianCurrency(response
                        .Not_Contacted_Revenue));
                    $('[data-lead-revenue="Quotation_Sent"]').text(formatIndianCurrency(response
                        .Quotation_Sent_Revenue));
                    $('[data-lead-revenue="Negotiations"]').text(formatIndianCurrency(response
                        .Negotiations_Revenue));
                    $('[data-lead-revenue="Contract_Done"]').text(formatIndianCurrency(response
                        .Contract_Done_Revenue));
                    $('[data-lead-revenue="Advance_Received"]').text(formatIndianCurrency(response
                        .Advance_Received_Revenue));
                    $('[data-lead-revenue="Lead_Won"]').text(formatIndianCurrency(response
                        .Lead_Won_Revenue));
                    $('[data-lead-revenue="Lead_Lost"]').text(formatIndianCurrency(response
                        .Lead_Lost_Revenue));

                    $('[data-lead-status="total_revenue"]').text(formatIndianCurrency(response
                        .total_revenue));

                    $('#totalLeads').html(response.total_leads);
                },

                error: function(xhr, status, error) {
                    console.log(error);
                }
            });

        }

        function numberFormat(amount) {

            amount = parseFloat(amount);

            if (isNaN(amount)) {
                amount = 0;
            }

            return amount.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }


        function formatIndianCurrency(amount) {

            amount = parseFloat(amount) || 0;

            if (amount >= 10000000) {
                return '₹ ' + (amount / 10000000).toFixed(2) + ' Cr';
            }

            if (amount >= 100000) {
                return '₹ ' + (amount / 100000).toFixed(2) + ' Lakh';
            }

            if (amount >= 1000) {
                return '₹ ' + (amount / 1000).toFixed(2) + ' K';
            }

            return '₹ ' + amount.toLocaleString('en-IN');
        }
        </script>