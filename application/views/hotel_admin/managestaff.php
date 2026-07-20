<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fa fa-users"></i>
                </div>
                <div class="header-content">
                    <h2 class="header-title">View Staff Members</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Staff Management</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner">
                <img src="<?= base_url('assets/new_img-add.png'); ?>" alt="Staff management">
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box new_table_box">
                        <div class="box-header">
                            <h4 class="box-title">View Staff Members</h4>
                        </div>
                        <div class="box-body last_active_design_td">
                            <div class="table-responsive">
                                <table id="complex_header" class="text-fade table table-bordered display" style="width: 100%;">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Sr. No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Leads</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($members)) { ?>
                                            <?php foreach ($members as $index => $member) { ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td><?= htmlspecialchars($member->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($member->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($member->phone, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td class="table-action min-w-100">
                                                        <a href="<?= base_url('view-leads?staff_id=' . (int) $member->id); ?>"
                                                           class="btn btn-primary btn-sm">View Leads</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
