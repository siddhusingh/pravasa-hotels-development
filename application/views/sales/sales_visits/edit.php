<?php
/*
 * Reuse the established Sales Visit edit form so its Select2 controls,
 * dynamic fields, camera/location handling and validation stay aligned.
 * Only the protected Sales-side endpoints and breadcrumb are changed.
 */
ob_start();
$this->load->view('super_admin/sales_visits/edit');
$salesVisitMarkup = ob_get_clean();

$routeReplacements = [
    base_url('superAdmin/Restaurants/getByHotel') => base_url('sales/visits/restaurants'),
    base_url('superAdmin/SlotType/getAll') => base_url('sales/visits/slot-types'),
    base_url('superAdmin/SalesVisits/update/') =>
        base_url('sales/visits/update/'),
    base_url('superAdmin/SalesVisits/get_company_contacts') =>
        base_url('sales/visits/company-contacts'),
    base_url('sales-visits-history') => base_url('sales/visits')
];

$salesVisitMarkup = strtr($salesVisitMarkup, $routeReplacements);
$salesVisitMarkup = str_replace(
    '<li>Super Admin</li>',
    '<li>Sales Executive</li>',
    $salesVisitMarkup
);
$csrfBootstrap = sprintf(
    "    window.CSRF = { name: %s, hash: %s };\n\n",
    json_encode($this->security->get_csrf_token_name()),
    json_encode($this->security->get_csrf_hash())
);
$salesVisitMarkup = str_replace(
    '    function appendCsrf(formData) {',
    $csrfBootstrap . '    function appendCsrf(formData) {',
    $salesVisitMarkup
);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?= $salesVisitMarkup ?>

<script>
(function () {
    $(document).ready(function () {
        $('#salesReserveTableModal select.select2-hidden-accessible').each(function () {
            $(this).select2('destroy');
        });
    });
})();
</script>
