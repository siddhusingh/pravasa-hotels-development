<table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
    <thead>
        <tr class="text-dark">
            <th>Sr. No.</th>
            <th>Facebook Form ID</th>
            <th>Facebook Form Name</th>
            <th>Created Date</th>
            <th>Updated Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($forms)) {
            $number = 1;
            foreach ($forms as $each_facebook_form) { ?>
                <tr>
                    <td><?= $number++; ?></td>
                    <td><?= htmlspecialchars($each_facebook_form->form_id) ?></td>
                    <td><?= htmlspecialchars($each_facebook_form->form_name) ?></td>
                    <td><?= date('d M Y h:i A', strtotime($each_facebook_form->created_at)) ?></td>
                    <td><?= date('d M Y h:i A', strtotime($each_facebook_form->updated_at)) ?></td>
                    <td class="table-action min-w-100">
                        <a href="javascript:void(0)" class="text-fade hover-primary edit-facebook-form"
                            data-record_id="<?= encrypt_id($each_facebook_form->id) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-edit-2 align-middle">
                                <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                            </svg>
                        </a>
                        <a href="javascript:void(0)" class="text-fade hover-primary delete-facebook-form"
                            data-record_id="<?= encrypt_id($each_facebook_form->id) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-trash align-middle">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4
                                         a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No Facebook forms found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
