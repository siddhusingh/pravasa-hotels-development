  <thead>
      <tr>
          <th>Hotel Name</th>

          <?php
            // Print all department names as header
            // Assume $all_departments contains every department in the system
            foreach ($departments as $deptName): ?>
              <th><?= $deptName->department_name; ?></th>
              <th>UQ </th>

          <?php endforeach; ?>
          <th>Total</th>
          <th>Business </th>

          <th>Non Business</th>

          <th>Mat</th>
          <th>Mat%</th>
          <th>Performance</th>



      </tr>
  </thead>

  <tbody>
      <?php foreach ($report_data as $hotel): ?>
          <tr>
              <td><?= $hotel['hotel_name']; ?></td>



              <?php

                $business_leads = 0;
                $non_business_leads = 0;
                $materialized_leads = 0;
                $total_leads = 0;

                foreach ($departments as $deptName): ?>
                  <?php
                    // Default count = 0




                    // Search department count in hotel array
                    foreach ($hotel['departments'] as $d) {



                        if ($d['department_name'] == $deptName->department_name) {
                            // sum of all statuses for that department
                            $business_leads += $d['business_leads'];
                            $non_business_leads += $d['non_business_leads'];
                            $materialized_leads += $d['materialized_leads'];
                            $count = array_sum($d['status_counts']);
                            break;
                        }
                    }
                    ?>
                  <td><?= $count;
                        $total_leads += $count; ?></td>

                  <td><?= $d['open_leads']; ?></td>




              <?php endforeach; ?>
              <td><?= $total_leads ?></td>
              <td><?= $business_leads ?></td>
              <td><?= $non_business_leads ?></td>
              <td><?= $materialized_leads; ?></td>

              <td>
                  <?php
                    $percentage = 0;

                    if ($business_leads > 0) {
                        $percentage = ($materialized_leads / $business_leads) * 100;
                    }

                    // Format to 1 decimal place
                    echo number_format($percentage, 1);
                    ?>
              </td>

              <?php
                if ($percentage > 30) {
                    $performance = "Excellent";
                    $bg = "background-color: #4CAF50; color: #fff;"; // Green
                } elseif ($percentage >= 20 && $percentage <= 30) {
                    $performance = "Good";
                    $bg = "background-color: #FFC107; color: #000;"; // Amber/Yellow
                } elseif ($percentage >= 15 && $percentage < 20) {
                    $performance = "Average";
                    $bg = "background-color: #03A9F4; color: #fff;"; // Blue
                } else {
                    $performance = "Poor";
                    $bg = "background-color: #F44336; color: #fff;"; // Red
                }
                ?>


              <td style="<?php echo $bg; ?> padding:6px; text-align:center;">
                  <?php echo $performance; ?>
              </td>



          </tr>
      <?php endforeach; ?>
  </tbody>