<table class="table table-striped table-bordered mb-0">
    <tr>
        <th>Company</th>
        <td><?= html_escape($visit->company_name ?? '-') ?></td>
    </tr>
    <tr>
        <th>Person Met</th>
        <td>
            <?php
            $personMet = trim(
                ($visit->first_name ?? '') . ' ' . ($visit->last_name ?? '')
            );
            echo html_escape($personMet !== '' ? $personMet : '-');
            ?>
        </td>
    </tr>
    <tr>
        <th>Sales User</th>
        <td><?= html_escape($visit->sales_user_name ?? '-') ?></td>
    </tr>
    <tr>
        <th>Visit Type</th>
        <td><?= html_escape($visit->visit_type ?? '-') ?></td>
    </tr>
    <tr>
        <th>Visit Mode</th>
        <td><?= html_escape($visit->visit_mode ?? '-') ?></td>
    </tr>
    <tr>
        <th>Report Date</th>
        <td>
            <?= !empty($visit->report_date)
                ? date('d-m-Y', strtotime($visit->report_date))
                : '-' ?>
        </td>
    </tr>
    <tr>
        <th>Created Date</th>
        <td>
            <?= !empty($visit->created_at)
                ? date('d-m-Y h:i A', strtotime($visit->created_at))
                : '-' ?>
        </td>
    </tr>
    <tr>
        <th>Agenda</th>
        <td><?= html_escape($visit->agenda ?? '-') ?></td>
    </tr>
    <tr>
        <th>Discussion</th>
        <td><?= nl2br(html_escape($visit->discussion_summary ?? '-')) ?></td>
    </tr>
    <tr>
        <th>Attachment</th>
        <td>
            <?php if (!empty($visit->attachment_image)): ?>
                <?php
                $attachmentPath = ltrim(
                    str_replace('\\', '/', $visit->attachment_image),
                    '/'
                );
                $attachmentUrl = base_url($attachmentPath);
                ?>
                <a
                    href="<?= html_escape($attachmentUrl) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="<?= html_escape($attachmentUrl) ?>"
                        alt="Sales visit attachment"
                        class="img-thumbnail"
                        style="max-height:220px;max-width:100%;"
                    >
                </a>
            <?php else: ?>
                <span class="text-muted">No attachment</span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Visit Location</th>
        <td>
            <?php if (
                $visit->latitude !== null &&
                $visit->longitude !== null
            ): ?>
                <div>
                    <?= html_escape(
                        !empty($visit->location_details)
                            ? $visit->location_details
                            : $visit->latitude . ', ' . $visit->longitude
                    ) ?>
                </div>
                <a
                    href="https://www.google.com/maps?q=<?= rawurlencode(
                        $visit->latitude . ',' . $visit->longitude
                    ) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    View on Google Maps
                </a>
            <?php else: ?>
                <span class="text-muted">Location not captured</span>
            <?php endif; ?>
        </td>
    </tr>
</table>
