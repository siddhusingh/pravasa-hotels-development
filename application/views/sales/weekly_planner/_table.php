<table
    id="weekly-planner-table"
    class="text-fade table table-bordered display"
    style="width:100%"
    data-has-rows="<?= !empty($planners) ? '1' : '0' ?>"
>
    <thead>
        <tr class="text-dark">
            <th>Sr. No.</th>
            <?php if ($is_planner_manager): ?>
                <th>Sales Executive</th>
            <?php endif; ?>
            <th>Date</th>
            <th>Activity Type</th>
            <th>Account / Activity</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($planners)): ?>
            <?php foreach ($planners as $index => $planner): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <?php if ($is_planner_manager): ?>
                        <td><?= html_escape(
                            $planner->sales_user_name ?: '-'
                        ) ?></td>
                    <?php endif; ?>
                    <td>
                        <?= !empty($planner->planner_date)
                            ? date(
                                'd M Y',
                                strtotime($planner->planner_date)
                            )
                            : '-' ?>
                    </td>
                    <td class="text-capitalize">
                        <?= html_escape($planner->activity_type) ?>
                    </td>
                    <td>
                        <?php if ($planner->activity_type === 'visit'): ?>
                            <?php if ($planner->account_type === 'existing'): ?>
                                <span class="badge badge-info">
                                    Existing Customer
                                </span>
                                <div class="small mt-1">
                                    <?= html_escape(
                                        $planner->company_name ?: '-'
                                    ) ?>
                                    <?php
                                    $contactName = trim(
                                        ($planner->first_name ?? '') . ' ' .
                                        ($planner->last_name ?? '')
                                    );
                                    ?>
                                    <?php if ($contactName !== ''): ?>
                                        — <?= html_escape($contactName) ?>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    New Customer
                                </span>
                                <div class="small mt-1">
                                    <?= html_escape(
                                        $planner->new_person_name ?: '-'
                                    ) ?>
                                    <?php if (!empty(
                                        $planner->new_person_mobile
                                    )): ?>
                                        (<?= html_escape(
                                            $planner->new_person_mobile
                                        ) ?>)
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <?= html_escape($planner->other_activity ?: '-') ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= !empty($planner->description)
                            ? nl2br(html_escape($planner->description))
                            : '<span class="text-muted">-</span>' ?>
                    </td>
                    <td>
                        <?php if (
                            $planner->approval_status === 'pending'
                        ): ?>
                            <span class="badge badge-warning">
                                Pending Approval
                            </span>
                        <?php else: ?>
                            <span class="badge badge-success">Approved</span>
                            <?php if (
                                $is_planner_manager &&
                                !empty($planner->approver_name)
                            ): ?>
                                <div class="small text-muted mt-1">
                                    By <?= html_escape(
                                        $planner->approver_name
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= !empty($planner->created_at)
                            ? date(
                                'd M Y h:i A',
                                strtotime($planner->created_at)
                            )
                            : '-' ?>
                    </td>
                    <td class="table-action min-w-100">
                        <?php if ($is_planner_manager): ?>
                            <?php if (
                                $planner->approval_status === 'pending'
                            ): ?>
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm approve-planner"
                                    data-record_id="<?= html_escape(
                                        encrypt_id($planner->id)
                                    ) ?>"
                                >
                                    <i
                                        class="fa fa-check"
                                        aria-hidden="true"
                                    ></i>
                                    Approve
                                </button>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <a
                                href="javascript:void(0)"
                                class="text-fade hover-primary edit-planner"
                                data-record_id="<?= html_escape(
                                    encrypt_id($planner->id)
                                ) ?>"
                                title="Edit Weekly Planner"
                                aria-label="Edit Weekly Planner"
                            >
                                <i
                                    class="fa fa-edit fa-lg"
                                    aria-hidden="true"
                                ></i>
                            </a>
                            <a
                                href="javascript:void(0)"
                                class="text-fade hover-primary delete-planner ml-2"
                                data-record_id="<?= html_escape(
                                    encrypt_id($planner->id)
                                ) ?>"
                                title="Delete Weekly Planner"
                                aria-label="Delete Weekly Planner"
                            >
                                <i
                                    class="fa fa-trash-o fa-lg"
                                    aria-hidden="true"
                                ></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td
                    colspan="<?= $is_planner_manager ? 9 : 8 ?>"
                    class="text-center text-muted"
                >
                    <?= $is_planner_manager
                        ? 'No sales weekly planners found.'
                        : 'No approved weekly planners found.' ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
