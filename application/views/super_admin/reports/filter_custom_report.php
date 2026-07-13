<thead>
    <tr>
        <th>Lead ID</th>
        <th>City</th>
        <th>Property</th>
        <th>Department</th>
        <th>Guest Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Status</th>
        <th>Stage</th>
        <th>Source</th>
        <th>Creator Name</th>
        <th>Assigned To</th>
        <th>Created Date</th>
        <th>Response Date</th>
        <th>Completed Date</th>
        <th>Booking Date</th>
        <th>Check In Date</th>
        <th>Check Out Date</th>
        <th>Follow Up 1</th>
        <th>Follow Up 2</th>
        <th>Pax</th>
        <th>Query</th>
        <th>Revenue</th>
        <th>Materialization</th>
        <th>Remark</th>
        <th>Reason</th>
    </tr>
</thead>

<tbody>
    <?php if (!empty($leads)): ?>
        <?php foreach ($leads as $lead): ?>
            <tr>
                <td><?= htmlspecialchars($lead['id'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['city_name'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['hotel_name'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['department_name'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['user_name'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['phone_number'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['email'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['status'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['disposition'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['user_channel'] ?? 'NA') ?></td>

                <td><?= htmlspecialchars($lead['creator_name'] ?? 'NA') ?></td>
                <td><?= htmlspecialchars($lead['assigned_person_name'] ?? 'NA') ?></td>

                <!-- Created -->
                <td>
                    <?= !empty($lead['created_at']) && strtotime($lead['created_at'])
                        ? date('d M Y, h:i A', strtotime($lead['created_at']))
                        : 'NA' ?>
                </td>

                <!-- Response -->
                <td>
                    <?= !empty($lead['responded_time']) && strtotime($lead['responded_time'])
                        ? date('d M Y, h:i A', strtotime($lead['responded_time']))
                        : 'NA' ?>
                </td>

                <!-- Completed -->
                <td>
                    <?= !empty($lead['completed_time']) && strtotime($lead['completed_time'])
                        ? date('d M Y, h:i A', strtotime($lead['completed_time']))
                        : 'NA' ?>
                </td>

                <!-- Booking -->
                <td>
                    <?= !empty($lead['booking_enquiry_date']) && strtotime($lead['booking_enquiry_date'])
                        ? date('d M Y, h:i A', strtotime($lead['booking_enquiry_date']))
                        : 'NA' ?>
                </td>

                <!-- Check-in -->
                <td>
                    <?= !empty($lead['checkin_date']) && strtotime($lead['checkin_date'])
                        ? date('d M Y, h:i A', strtotime($lead['checkin_date']))
                        : 'NA' ?>
                </td>

                <!-- Check-out -->
                <td>
                    <?= !empty($lead['checkout_date']) && strtotime($lead['checkout_date'])
                        ? date('d M Y, h:i A', strtotime($lead['checkout_date']))
                        : 'NA' ?>
                </td>

                <!-- Follow-up 1 -->
                <td>
                    <?= !empty($lead['followup_date']) && strtotime($lead['followup_date'])
                        ? date('d M Y', strtotime($lead['followup_date']))
                        : 'NA' ?>
                </td>

                <!-- Follow-up 2 -->
                <td>
                    <?= !empty($lead['second_followup_date']) && strtotime($lead['second_followup_date'])
                        ? date('d M Y', strtotime($lead['second_followup_date']))
                        : 'NA' ?>
                </td>

                <!-- Pax -->
                <td><?= !empty($lead['pax']) ? htmlspecialchars($lead['pax']) : 'NA' ?></td>

                <!-- Query -->
                <td><?= !empty($lead['query']) ? nl2br(htmlspecialchars($lead['query'])) : 'NA' ?></td>

                <!-- Revenue -->
                <td>
                    <?= isset($lead['amount']) ? number_format((float)$lead['amount'], 2) : '0.00' ?>
                </td>

                <!-- Materialization -->
                <td>
                    <?php
                    $isMaterialized = (
                        !empty($lead['disposition']) &&
                        !empty($lead['status']) &&
                        strtolower($lead['disposition']) === 'reservation' &&
                        strtolower($lead['status']) === 'closed'
                    );
                    echo $isMaterialized ? 'Yes' : 'No';
                    ?>
                </td>

                <!-- Remark -->
                <td><?= !empty($lead['remark']) ? nl2br(htmlspecialchars($lead['remark'])) : 'NA' ?></td>

                <!-- Reason -->
                <td><?= !empty($lead['reason']) ? nl2br(htmlspecialchars($lead['reason'])) : 'NA' ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="26" style="text-align:center;">No data available</td>
        </tr>
    <?php endif; ?>
</tbody>