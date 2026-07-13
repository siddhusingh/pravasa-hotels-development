  <thead>
      <tr>
          <th>Department Name</th>
          <th>Count</th>

      </tr>
  </thead>
  <tbody>

      <?php $grand_total = 0; ?>
      <?php foreach ($leads as $lead): ?>
          <tr>
              <td><?= htmlspecialchars($lead['department_name']) ?></td>
              <td>

                  <?php $grand_total += $lead['total']; ?>
                  <?= htmlspecialchars($lead['total']) ?></td>


          </tr>
      <?php endforeach; ?>

      <tr>
          <th>Total</th>
          <td><?php echo $grand_total ?></td>
      </tr>
  </tbody>