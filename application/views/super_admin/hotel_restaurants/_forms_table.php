<table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
    <thead>
        <tr class="text-dark">
            <th>Sr. No.</th>
            <th>Hotel</th>
            <th>Restaurant Name</th>
            <th>Restaurant Code</th>

            <th>Contact Number</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Updated Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($hotel_restaurants)) {
            $number = 1;
            foreach ($hotel_restaurants as $row) {
                $encrypted_id = encrypt_id($row->id);
                ?>
                <tr>
                    <td><?= $number++; ?></td>

                    <td><?= htmlspecialchars($row->hotel_name ?? '-') ?></td>

                    <td><?= htmlspecialchars($row->restaurant_name) ?></td>

                    <td><?= htmlspecialchars($row->restaurant_code) ?></td>



                    <td><?= htmlspecialchars($row->contact_number) ?></td>

                    <td><?= htmlspecialchars($row->email) ?></td>

                    <td><?= ($row->status == 1) ? 'Active' : 'Inactive'; ?></td>

                    <td><?= date('d M Y h:i A', strtotime($row->created_at)) ?></td>

                    <td><?= date('d M Y h:i A', strtotime($row->updated_at)) ?></td>

                    <td class="table-action min-w-100">
                        <a href="javascript:void(0)"
                            class="text-fade hover-primary edit-restaurant"
                            data-record_id="<?= $encrypted_id ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-edit-2 align-middle">
                                <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                            </svg>
                        </a>

                        <a href="javascript:void(0)"
                            class="text-fade hover-primary delete-restaurant"
                            data-record_id="<?= $encrypted_id ?>">
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
                <td colspan="13" class="text-center text-muted">
                    No Restaurant found.
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
