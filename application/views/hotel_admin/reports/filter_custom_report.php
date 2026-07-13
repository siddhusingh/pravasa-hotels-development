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
         <th>Created Date</th>
         <th>Response Date</th>
         <th>Completed Date</th>
         <th>Booking Date</th>
         <th>Check In Date</th>
         <th>Check Out Date</th>

         <th>Follow up 1</th>
         <th>Follow up 2</th>
         <th>Pax</th>

         <th>Query</th>
         <th>Remark</th>
         <th>Revenue</th>
         <th>Materialization</th>

     </tr>
 </thead>
 <tbody>
     <?php foreach ($leads as $lead): ?>
         <tr>
             <td><?= htmlspecialchars($lead['id']) ?></td>
             <td><?= htmlspecialchars($lead['city_name']) ?></td>
             <td><?= htmlspecialchars($lead['hotel_name']) ?></td>
             <td><?= htmlspecialchars($lead['department_name']) ?></td>
             <td><?= htmlspecialchars($lead['user_name']) ?></td>
             <td><?= htmlspecialchars($lead['phone_number']) ?></td>
             <td><?= htmlspecialchars($lead['email']) ?></td>
             <td><?= htmlspecialchars($lead['status']) ?></td>
             <td><?= htmlspecialchars($lead['disposition']) ?></td>
             <td><?= htmlspecialchars($lead['user_channel']) ?></td>




             <td><?= date('d M Y, h:i A', strtotime($lead['created_at'])) ?></td>


             <td>
                 <?= !empty($lead['responded_time']) && strtotime($lead['responded_time']) ? date('d M Y, h:i A', strtotime($lead['responded_time'])) : 'NA' ?>
             </td>
             <td>
                 <?= !empty($lead['completed_time']) && strtotime($lead['completed_time']) ? date('d M Y, h:i A', strtotime($lead['completed_time'])) : 'NA' ?>
             </td>








             <td>
                 <?= !empty($lead['booking_enquiry_date']) && strtotime($lead['booking_enquiry_date']) ? date('d M Y, h:i A', strtotime($lead['booking_enquiry_date'])) : 'NA' ?>
             </td>
             <td>
                 <?= !empty($lead['checkin_date']) && strtotime($lead['checkin_date']) ? date('d M Y, h:i A', strtotime($lead['checkin_date'])) : 'NA' ?>
             </td>
             <td>
                 <?= !empty($lead['checkout_date']) && strtotime($lead['checkout_date']) ? date('d M Y, h:i A', strtotime($lead['checkout_date'])) : 'NA' ?>
             </td>

             <td>
                 <?= !empty($lead['followup_date']) && strtotime($lead['followup_date']) ? date('d M Y', strtotime($lead['followup_date'])) : 'NA' ?>
             </td>
             <td>
                 <?= !empty($lead['second_followup_date']) && strtotime($lead['second_followup_date']) ? date('d M Y', strtotime($lead['second_followup_date'])) : 'NA' ?>
             </td>
             <td>
                 <?= !empty($lead['pax']) ? htmlspecialchars($lead['pax']) : 'NA' ?>
             </td>




             <td><?= nl2br(htmlspecialchars($lead['query'])) ?></td>
             <td><?= nl2br(htmlspecialchars($lead['remark'])) ?></td>
             <td>
                 <?= number_format((float)($lead['amount'] ?? 0), 2) ?>
             </td>
             <td>
                 <?php
                    $isMaterialized = (
                        strtolower($lead['disposition']) === 'reservation' &&
                        strtolower($lead['status']) === 'closed'
                    );

                    echo $isMaterialized ? 'Yes' : 'No';
                    ?>
             </td>



         </tr>
     <?php endforeach; ?>
 </tbody>