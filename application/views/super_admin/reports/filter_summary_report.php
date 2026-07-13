   <thead>
       <tr>
           <th>Row Label</th>
           <th>Total Leads</th>
           <th>Business</th>
           <th>Non-Business</th>
           <th>Materialized</th>
           <th>Revenue</th>
           <th>Mat %</th>
       </tr>
   </thead>

   <tbody>

       <?php foreach ($report_data as $dept): ?>

           <?php
            // Reset department totals
            $dept_total = 0;
            $dept_business = 0;
            $dept_non_business = 0;
            $dept_materialized = 0;
            $dept_revenue = 0;

            // Calculate department totals
            foreach ($dept['channels'] as $ch) {
                $dept_total        += $ch['total_leads'];
                $dept_business     += $ch['business'];
                $dept_non_business += $ch['non_business'];
                $dept_materialized += $ch['materialized'];
                $dept_revenue      += $ch['revenue_sum'];
            }

            $matPercent = ($dept_business > 0)
                ? number_format(($dept_materialized / $dept_business) * 100, 1)
                : 0;
            ?>

           <!-- ✅ DEPARTMENT MAIN ROW -->
           <tr style="background:#f0f0f0; font-weight:bold;">
               <td><?= $dept['department_name'] ?></td>
               <td><?= $dept_total ?></td>
               <td><?= $dept_business ?></td>
               <td><?= $dept_non_business ?></td>
               <td><?= $dept_materialized ?></td>
               <td><?= number_format($dept_revenue, 2) ?></td>
               <td><?= $matPercent ?>%</td>
           </tr>

           <!-- ✅ SUB ROWS (USER CHANNELS) -->
           <?php foreach ($dept['channels'] as $channelName => $ch): ?>

               <?php
                $total        = $ch['total_leads'];
                $business     = $ch['business'];
                $non_business = $ch['non_business'];
                $materialized = $ch['materialized'];
                $revenue      = $ch['revenue_sum'];

                $matPercentCh = ($business > 0)
                    ? number_format(($materialized / $business) * 100, 1)
                    : 0;
                ?>

               <tr>
                   <td style="padding-left:25px;">→ <?= $channelName ?></td>
                   <td><?= $total ?></td>
                   <td><?= $business ?></td>
                   <td><?= $non_business ?></td>
                   <td><?= $materialized ?></td>
                   <td><?= number_format($revenue, 2) ?></td>
                   <td><?= $matPercentCh ?>%</td>
               </tr>

           <?php endforeach; ?>

       <?php endforeach; ?>

   </tbody>