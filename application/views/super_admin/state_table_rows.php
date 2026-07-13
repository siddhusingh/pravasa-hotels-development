<?php if (!empty($states)) {
    $number = 1;
    foreach ($states as $each_state) { ?>
        <tr>
            <td><?php echo $number;
                $number++; ?></td>
            <td><?= htmlspecialchars($each_state->country_name, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($each_state->state_name, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($each_state->created_at, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($each_state->updated_at, ENT_QUOTES, 'UTF-8') ?></td>
            <td class="table-action min-w-100">
                <a href="javascript:void(0)" class="text-fade hover-primary edit-state" data-record_id="<?php echo $each_state->state_id ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="text-fade hover-primary delete-state" data-record_id="<?php echo $each_state->state_id ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </a>
            </td>
        </tr>
<?php }
} ?>