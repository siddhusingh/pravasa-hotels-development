<table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
    <thead>
        <tr class="text-dark">
            <th>Sr. No.</th>
            <th>Date</th>
            <th>Activity Type</th>
            <th>Account / Activity</th>
            <th>Description</th>
            <th>Created Date</th>
            <th>Updated Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($planners)) {
            $number = 1;
            foreach ($planners as $row) { ?>
                <tr>
                    <td><?= $number++; ?></td>

                    <!-- Date -->
                    <td><?= date('d M Y', strtotime($row->planner_date)); ?></td>

                    <!-- Activity Type -->
                    <td class="text-capitalize">
                        <?= htmlspecialchars($row->activity_type); ?>
                    </td>

                    <!-- Account / Activity -->
                    <td>
                        <?php if ($row->activity_type == 'visit') { ?>

                            <?php if ($row->account_type == 'existing') { ?>
                                <span class="badge badge-info">Existing Customer</span>
                            <?php } else { ?>
                                <span class="badge badge-warning">New Customer</span><br>
                                <small>
                                    <?= htmlspecialchars($row->new_person_name); ?>
                                    (<?= htmlspecialchars($row->new_person_mobile); ?>)
                                </small>
                            <?php } ?>

                        <?php } else { ?>
                            <?= htmlspecialchars($row->other_activity); ?>
                        <?php } ?>
                    </td>

                    <!-- Description -->
                    <td>
                        <?= !empty($row->description)
                            ? htmlspecialchars($row->description)
                            : '<span class="text-muted">-</span>'; ?>
                    </td>

                    <!-- Created -->
                    <td><?= date('d M Y h:i A', strtotime($row->created_at)); ?></td>

                    <!-- Updated -->
                    <td>
                        <?= !empty($row->updated_at)
                            ? date('d M Y h:i A', strtotime($row->updated_at))
                            : '<span class="text-muted">-</span>'; ?>
                    </td>

                    <!-- Action -->
                    <td class="table-action min-w-100">
                        <a href="javascript:void(0)"
                            class="text-fade hover-primary edit-weekly-planner"
                            data-record_id="<?= encrypt_id($row->id) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-edit-2 align-middle">
                                <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                            </svg>
                        </a>

                        <a href="javascript:void(0)"
                            class="text-fade hover-primary delete-weekly-planner"
                            data-record_id="<?= encrypt_id($row->id) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-trash align-middle">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7
                                         a2 2 0 0 1-2-2V6m3 0V4
                                         a2 2 0 0 1 2-2h4
                                         a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="8" class="text-center text-muted">
                    No Weekly Planner records found.
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
