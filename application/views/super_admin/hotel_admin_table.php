    <?php if (!empty($hotel_admins)) {
        $i = 1;
        foreach ($hotel_admins as $row) {
            $encrypted_id = encrypt_id($row->id);
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $row->name ?></td>
                <td><?= $row->email ?></td>
                <td><?= $row->phone ?></td>
                <td><?= $row->hotel_name ?></td>
                <td class="text-center">
                    <label class="switch">
                        <input type="checkbox"
                            class="status-toggle"
                            data-record_id="<?= $encrypted_id ?>"
                            <?= ($row->status == 1) ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </td>

                <td><?= $row->created_at ?></td>

                <td class="table-action min-w-100">
                    <a href="javascript:void(0)" class="text-fade hover-primary edit" data-record_id="<?php echo $encrypted_id ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                        </svg>
                    </a>
                    <a href="javascript:void(0)" class="text-fade hover-primary delete" data-record_id="<?php echo $encrypted_id ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </a>
                </td>
            </tr>
    <?php }
    } ?>
