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
</table>