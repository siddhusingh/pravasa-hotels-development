<?php
/*
 * Keep the Sales Executive form aligned with the established Super Admin
 * Sales Visit form while routing every protected action through Sales-only
 * controllers. The Super Admin view remains unchanged.
 */
ob_start();
$this->load->view('super_admin/sales_visits/add');
$salesVisitMarkup = ob_get_clean();

$routeReplacements = [
    base_url('company-save') => base_url('sales/companies/save'),
    base_url('company-contact-save') => base_url('sales/company-contacts/save'),
    base_url('superAdmin/Restaurants/getByHotel') => base_url('sales/visits/restaurants'),
    base_url('superAdmin/SlotType/getAll') => base_url('sales/visits/slot-types'),
    base_url('superAdmin/SalesVisits/insert') => base_url('sales/visits/create'),
    base_url('superAdmin/SalesVisits/get_company_contacts') => base_url('sales/visits/company-contacts'),
    base_url('sales-visits-history') => base_url('sales/visits')
];

$salesVisitMarkup = strtr($salesVisitMarkup, $routeReplacements);
$salesVisitMarkup = str_replace(
    '<li>Super Admin</li>',
    '<li>Sales Executive</li>',
    $salesVisitMarkup
);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
window.CSRF = window.CSRF || {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
};
</script>

<?= $salesVisitMarkup ?>

<script>
(function () {
    if (typeof window.validateSalesVisitForm !== 'function') {
        return;
    }

    var validateSuperAdminSalesVisitForm = window.validateSalesVisitForm;

    window.validateSalesVisitForm = function () {
        var isValid = validateSuperAdminSalesVisitForm.apply(this, arguments);
        var stage = document.getElementById('disposition');
        var hasStage = stage && String(stage.value || '').trim() !== '';

        if (!hasStage && stage) {
            stage.classList.add('is-invalid');

            var stageError = document.getElementById('disposition_error');
            if (stageError) {
                stageError.textContent = 'Please select a stage.';
            }
        }

        return isValid && hasStage;
    };
})();
</script>

<?php if (!empty($sales_visit_success)): ?>
    <script>
    window.addEventListener('load', function () {
        var message = <?= json_encode(
            $sales_visit_success,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        ) ?>;

        if (typeof window.showSalesToast === 'function') {
            window.showSalesToast('success', message);
        } else if (window.toastr) {
            toastr.success(message);
        }
    });
    </script>
<?php endif; ?>
