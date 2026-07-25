<table class="table table-striped table-bordered">
    <tr>
        <th>Company</th>
        <td><?= $visit->company_name ?></td>
    </tr>
    <tr>
        <th>Person Met</th>
        <td><?= $visit->first_name ?> <?= $visit->last_name ?></td>
    </tr>
    <tr>
        <th>Sales User</th>
        <td><?= $visit->sales_user_name ?></td>
    </tr>
    <tr>
        <th>Agenda</th>
        <td><?= $visit->agenda ?></td>
    </tr>
    <tr>
        <th>Discussion</th>
        <td><?= nl2br($visit->discussion_summary) ?></td>
    </tr>
    <tr>
        <th>Attachment</th>
        <td>
            <?php if (!empty($visit->attachment_image)) : ?>
                <?php $attachmentUrl = base_url('uploads/sales_visits/' . rawurlencode(basename($visit->attachment_image))); ?>
                <a href="<?= html_escape($attachmentUrl) ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?= html_escape($attachmentUrl) ?>" alt="Sales visit attachment" class="img-thumbnail" style="max-height: 220px; max-width: 100%;">
                </a>
            <?php else : ?>
                <span class="text-muted">No attachment</span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Visit Location</th>
        <td>
            <?php if (isset($visit->latitude, $visit->longitude)) : ?>
                <div><?= html_escape($visit->location_details ?: $visit->latitude . ', ' . $visit->longitude) ?></div>
                <a href="https://www.google.com/maps?q=<?= rawurlencode($visit->latitude . ',' . $visit->longitude) ?>" target="_blank" rel="noopener noreferrer">
                    View on Google Maps
                </a>
            <?php else : ?>
                <span class="text-muted">Location not captured</span>
            <?php endif; ?>
        </td>
    </tr>
</table>
