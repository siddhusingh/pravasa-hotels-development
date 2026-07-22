<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-history"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">Access & Activity Logs</h2>
                    <ol class="custom-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                        </li>
                        <li>Super Admin</li>
                        <li>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="active">Access & Activity Logs</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?php echo base_url('assets/new_img/activity_logs_img.png'); ?>" alt="">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">Access & Activity Logs</h4>
                        </div>
                        <div class="box-body">
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" id="start_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" id="end_date" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="applyFilter" class="btn btn-primary-light w-100">Apply</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="activity-logs-data-table" class="text-fade table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Module</th>
                                            <th>Action</th>
                                            <th>Actor</th>
                                            <th>Role</th>
                                            <th>IP Address</th>
                                            <th>Date / Time</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
var activityLogsTable = $('#activity-logs-data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        order: [[6, 'desc']],
        ajax: {
            url: '<?php echo base_url('activity-logs-data-table'); ?>',
            type: 'POST',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d[window.CSRF.name] = window.CSRF.hash;
            },
            dataSrc: function (json) {
                if (json.csrfHash) {
                    window.CSRF.hash = json.csrfHash;
                }
                return json.data;
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 7] },
            { className: 'text-wrap', targets: 7 }
        ]
});

$('#applyFilter').on('click', function () {
    activityLogsTable.draw();
});
</script>
