<style>
    #slot-crud-modal .select2-container { width: 100% !important; }
    #slot-crud-modal .select2-selection--single { height: 38px; padding: 5px 8px; }
    #slot-crud-modal .select2-selection__arrow { height: 36px; }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="custom-page-header">
            <div class="header-left">
                <div class="header-icon-box"><i class="fa fa-clock-o"></i></div>
                <div class="header-content">
                    <h2 class="header-title">Manage Time Slots</h2>
                    <ol class="custom-breadcrumb">
                        <li><i class="fa fa-home"></i></li>
                        <li>Hotel Admin</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>Property Management</li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li class="active">Time Slots</li>
                    </ol>
                </div>
            </div>
            <div class="header-banner"><img src="<?php echo base_url('assets/new_img/time_slot_img.png'); ?>" alt=""></div>
        </div>

        <section class="content">
            <div class="box new_table_box">
                <div class="box-header">
                    <h4 class="box-title">Time Slot Management</h4>
                    <div class="float-right" style="float:right;">
                        <button type="button" class="btn btn-primary-light btn-sm" id="open-slot-modal">Add Time Slot +</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="hotel-time-slots-table" class="text-fade table table-bordered display" style="width:100%">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sr. No.</th>
                                    <th>Slot Type</th>
                                    <th>Time Slot</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal modal-lg new_modal_design" id="slot-crud-modal" tabindex="-1" role="dialog" aria-labelledby="slot-crud-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="custom-page-header">
                <div class="header-left">
                    <div class="header-icon-box"><i class="fa fa-clock-o"></i></div>
                    <div class="header-content">
                        <div class="modal-header hotel_modal_header">
                            <h5 class="modal-title" id="slot-crud-modal-label"></h5>
                            <div class="hotel_banner"></div>
                        </div>
                        <ol class="custom-breadcrumb">
                            <li><i class="fa fa-info-circle"></i> Fill in the details to add or update a time slot.</li>
                        </ol>
                    </div>
                </div>
                <div class="header-banner">
                    <img src="<?php echo base_url('assets/new_img/time_slot_img.png'); ?>" alt="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ps-3 pe-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="slot_type_id">Slot Type <span class="required-asterisk">*</span></label>
                        <select class="form-select slot-select" id="slot_type_id" name="slot_type_id">
                            <option value="">Select Slot Type</option>
                            <?php foreach ($slot_types as $slotType) { ?>
                                <option value="<?php echo (int) $slotType->id; ?>"><?php echo html_escape($slotType->slot_name); ?></option>
                            <?php } ?>
                        </select>
                        <div class="validation text-danger" id="slot_type_id_error"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="start_time">Start Time <span class="required-asterisk">*</span></label>
                        <input type="time" class="form-control" id="start_time" name="start_time">
                        <div class="validation text-danger" id="start_time_error"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time">End Time <span class="required-asterisk">*</span></label>
                        <input type="time" class="form-control" id="end_time" name="end_time">
                        <div class="validation text-danger" id="end_time_error"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select class="form-select slot-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="slot-action-btn" class="btn btn-primary" data-record-id="">Create</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript">
    window.CSRF = {
        name: <?php echo json_encode($this->security->get_csrf_token_name()); ?>,
        hash: <?php echo json_encode($this->security->get_csrf_hash()); ?>
    };

    window.addEventListener('load', function() {
        toastr.options = {
            closeButton: true,
            positionClass: 'toast-top-right',
            timeOut: '5000',
            preventDuplicates: true
        };

        function refreshCsrf(response) {
            if (response && response.csrfHash) {
                window.CSRF.hash = response.csrfHash;
            }
        }

        function initializeTimeSlotSelects() {
            $('#slot_type_id').select2({
                dropdownParent: $('#slot-crud-modal'),
                placeholder: 'Select Slot Type',
                width: '100%'
            });
            $('#status').select2({
                dropdownParent: $('#slot-crud-modal'),
                minimumResultsForSearch: Infinity,
                width: '100%'
            });
        }

        function setTimeSlotSelectValue(selector, value) {
            $(selector).val(value).trigger('change.select2');
        }

        initializeTimeSlotSelects();

        var timeSlotsTable = $('#hotel-time-slots-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            columnDefs: [{ targets: 6, orderable: false }],
            ajax: {
                url: '<?php echo base_url('hotel-admin/get-time-slots-table'); ?>',
                type: 'POST',
                data: function(data) {
                    data[window.CSRF.name] = window.CSRF.hash;
                },
                dataSrc: function(response) {
                    refreshCsrf(response);
                    return response.data || [];
                }
            }
        });

        function resetTimeSlotForm() {
            $('.validation').text('');
            setTimeSlotSelectValue('#slot_type_id', '');
            $('#start_time, #end_time').val('');
            setTimeSlotSelectValue('#status', 'active');
            $('#slot-action-btn').attr('data-record-id', '').text('Create').prop('disabled', false);
        }

        function validateTimeSlotForm() {
            var fields = [
                ['slot_type_id', 'Please select slot type'],
                ['start_time', 'Please enter start time'],
                ['end_time', 'Please enter end time']
            ];
            var isValid = true;

            fields.forEach(function(field) {
                var value = $('#' + field[0]).val();
                $('#' + field[0] + '_error').text(value === '' ? field[1] : '');
                if (value === '') {
                    isValid = false;
                }
            });

            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();
            if (startTime !== '' && endTime !== '' && endTime <= startTime) {
                $('#end_time_error').text('End time must be later than start time');
                isValid = false;
            }

            return isValid;
        }

        $('#slot_type_id, #start_time, #end_time').on('input change', function() {
            $('#' + this.id + '_error').text('');
        });

        $('#open-slot-modal').on('click', function(event) {
            event.preventDefault();
            resetTimeSlotForm();
            $('#slot-crud-modal-label').text('Add Time Slot');
            $('#slot-crud-modal').modal('show');
        });

        $(document).on('click', '#slot-action-btn', function(event) {
            event.preventDefault();
            if (!validateTimeSlotForm()) {
                return;
            }

            var button = $(this);
            var recordId = button.attr('data-record-id');
            var isUpdate = recordId !== '';
            var buttonText = button.text();
            var formData = new FormData();
            formData.append('slot_type_id', $('#slot_type_id').val());
            formData.append('start_time', $('#start_time').val());
            formData.append('end_time', $('#end_time').val());
            formData.append('status', $('#status').val());
            formData.append(window.CSRF.name, window.CSRF.hash);
            if (isUpdate) {
                formData.append('id', recordId);
            }

            $.ajax({
                url: '<?php echo base_url('hotel-admin/'); ?>' + (isUpdate ? 'time-slots-update' : 'time-slots-add'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text(isUpdate ? 'Updating...' : 'Saving...');
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status === true) {
                        toastr.success(response.message);
                        $('#slot-crud-modal').modal('hide');
                        timeSlotsTable.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Unable to save time slot');
                    }
                },
                error: function() {
                    toastr.error('Server error! Please try again.');
                },
                complete: function() {
                    button.prop('disabled', false).text(buttonText);
                }
            });
        });

        $(document).on('click', '.edit-slot', function(event) {
            event.preventDefault();
            resetTimeSlotForm();

            $.ajax({
                url: '<?php echo base_url('hotel-admin/time-slots-details'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $(this).attr('data-record_id'),
                    [window.CSRF.name]: window.CSRF.hash
                },
                success: function(response) {
                    refreshCsrf(response);
                    if (response.status !== true) {
                        toastr.error(response.message || 'Unable to load time slot details');
                        return;
                    }

                    setTimeSlotSelectValue('#slot_type_id', String(response.data.slot_type_id));
                    $('#start_time').val(response.data.start_time);
                    $('#end_time').val(response.data.end_time);
                    setTimeSlotSelectValue('#status', response.data.status);
                    $('#slot-crud-modal-label').text('Edit Time Slot');
                    $('#slot-action-btn').attr('data-record-id', response.id).text('Update');
                    $('#slot-crud-modal').modal('show');
                },
                error: function() {
                    toastr.error('Error fetching time slot details');
                }
            });
        });

        $(document).on('click', '.delete-slot', function(event) {
            event.preventDefault();
            var recordId = $(this).attr('data-record_id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This time slot will be removed from the active time slot list.',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes Delete it'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url('hotel-admin/time-slots-delete'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: recordId,
                        [window.CSRF.name]: window.CSRF.hash
                    },
                    success: function(response) {
                        refreshCsrf(response);
                        if (response.status === true) {
                            toastr.success(response.message);
                            timeSlotsTable.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Unable to delete time slot');
                        }
                    },
                    error: function() {
                        toastr.error('Server error! Please try again.');
                    }
                });
            });
        });
    });
</script>
