<?php
defined('BASEPATH') or exit('No direct script access allowed');

$sharedLeadFormData = compact(
    'hotel_admin',
    'departments',
    'all_assignable_users',
    'selected_property',
    'selected_department',
    'lead_form_role_label',
    'lead_form_submit_url',
    'lead_form_redirect_url'
);

$this->load->view('hotel_admin/add_lead', $sharedLeadFormData);
?>

<script>
    (function($) {
        'use strict';

        $(function() {
            const $property = $('#property');
            const $assignLeadField = $('#assigned_to').closest('.col-md-3');
            const $quotationSentOption = $('#disposition option[value="Quotation Sent"]');

            $assignLeadField.remove();
            $('#assigned_person_user_role, #assigned_person_email').remove();

            $property
                .prop('disabled', false)
                .removeAttr('aria-readonly')
                .trigger('change.select2');

            $quotationSentOption.prop('disabled', true);
            $('#disposition').trigger('change.select2');
        });
    })(jQuery);
</script>
