<div class="modal fade" id="downloadLeadFormModal" tabindex="-1" role="dialog" aria-labelledby="downloadLeadFormModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <div>
               <h5 class="modal-title" id="downloadLeadFormModalLabel">Download Lead Form</h5>
               <small class="text-muted">Choose any defaults you want. All fields are optional.</small>
            </div>
         </div>

         <div class="modal-body">
            <div class="form-group">
               <label for="downloadLeadFormProperty">Default Property</label>
               <select class="form-control" id="downloadLeadFormProperty">
                  <option value="">Loading properties...</option>
               </select>
            </div>

            <div class="form-group">
               <label for="downloadLeadFormDepartment">Default Department</label>
               <select class="form-control" id="downloadLeadFormDepartment">
                  <option value="">Loading departments...</option>
               </select>
            </div>

            <div class="form-group d-none" id="downloadLeadFormVenueGroup">
               <label for="downloadLeadFormVenue" id="downloadLeadFormVenueLabel">Default Selection</label>
               <select class="form-control" id="downloadLeadFormVenue" disabled>
                  <option value="">Select a property first</option>
               </select>
            </div>

            <div class="alert alert-danger d-none mb-0" id="downloadLeadFormError" role="alert"></div>
         </div>

         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="downloadLeadFormSubmit">
               <i class="fa fa-download mr-1"></i>
               <span>Download Form</span>
            </button>
         </div>
      </div>
   </div>
</div>

<script>
(function ($) {
   'use strict';

   var urls = {
      properties: <?= json_encode(base_url('api/property-list'), JSON_UNESCAPED_SLASHES) ?>,
      departments: <?= json_encode(base_url('api/department-list'), JSON_UNESCAPED_SLASHES) ?>,
      restaurants: <?= json_encode(base_url('api/restaurant-list'), JSON_UNESCAPED_SLASHES) ?>,
      banquets: <?= json_encode(base_url('api/banquet-list'), JSON_UNESCAPED_SLASHES) ?>,
      download: <?= json_encode(base_url('download-lead-form'), JSON_UNESCAPED_SLASHES) ?>
   };
   var csrfCookieName = <?= json_encode($this->config->item('csrf_cookie_name')) ?>;
   var lookupsLoaded = false;
   var venueRequestId = 0;

   function showError(message) {
      $('#downloadLeadFormError').text(message).removeClass('d-none');
   }

   function clearError() {
      $('#downloadLeadFormError').text('').addClass('d-none');
   }

   function readCookie(name) {
      var prefix = encodeURIComponent(name) + '=';
      var cookies = document.cookie ? document.cookie.split(';') : [];

      for (var i = 0; i < cookies.length; i++) {
         var cookie = cookies[i].trim();
         if (cookie.indexOf(prefix) === 0) {
            return decodeURIComponent(cookie.substring(prefix.length));
         }
      }

      return '';
   }

   function replaceOptions(select, placeholder, rows, idKey, nameKey) {
      var fragment = document.createDocumentFragment();
      fragment.appendChild(new Option(placeholder, ''));

      rows.forEach(function (row) {
         var id = row[idKey];
         var name = String(row[nameKey] || '').trim();
         if (!id || !name) return;
         fragment.appendChild(new Option(name, id));
      });

      select.replaceChildren(fragment);
      select.disabled = false;
   }

   async function fetchJson(url, options) {
      var response = await fetch(url, options || {
         method: 'GET',
         headers: { 'Accept': 'application/json' }
      });
      var result = await response.json();

      if (!response.ok || !result.status || !Array.isArray(result.data)) {
         throw new Error(result.message || 'Unable to load options.');
      }

      return result.data;
   }

   async function loadBaseLookups() {
      if (lookupsLoaded) return;

      var propertySelect = document.getElementById('downloadLeadFormProperty');
      var departmentSelect = document.getElementById('downloadLeadFormDepartment');
      propertySelect.disabled = true;
      departmentSelect.disabled = true;

      try {
         var results = await Promise.all([
            fetchJson(urls.properties),
            fetchJson(urls.departments)
         ]);

         replaceOptions(propertySelect, 'No default property', results[0], 'property_id', 'property_name');
         replaceOptions(departmentSelect, 'No default department', results[1], 'department_id', 'department_name');
         lookupsLoaded = true;
      } catch (error) {
         propertySelect.replaceChildren(new Option('Unable to load properties', ''));
         departmentSelect.replaceChildren(new Option('Unable to load departments', ''));
         showError(error.message || 'Unable to load default options.');
      }
   }

   function selectedDepartmentName() {
      var select = document.getElementById('downloadLeadFormDepartment');
      return String(select.selectedOptions[0]?.text || '').trim().toLowerCase();
   }

   async function refreshVenueOptions() {
      var requestId = ++venueRequestId;
      var department = selectedDepartmentName();
      var propertyId = $('#downloadLeadFormProperty').val() || '';
      var group = $('#downloadLeadFormVenueGroup');
      var label = $('#downloadLeadFormVenueLabel');
      var select = document.getElementById('downloadLeadFormVenue');
      var isRestaurant = department === 'restaurant' || department === 'restaurants';
      var isBanquet = department === 'banquet' || department === 'banquets';

      clearError();
      select.replaceChildren(new Option(propertyId ? 'Loading...' : 'Select a property first', ''));
      select.disabled = true;

      if (!isRestaurant && !isBanquet) {
         group.addClass('d-none');
         return;
      }

      group.removeClass('d-none');
      label.text(isRestaurant ? 'Default Restaurant' : 'Default Banquet');

      if (!propertyId) return;

      try {
         var rows = await fetchJson(isRestaurant ? urls.restaurants : urls.banquets, {
            method: 'POST',
            headers: {
               'Accept': 'application/json',
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({ hotel_id: propertyId })
         });

         if (requestId !== venueRequestId) return;

         replaceOptions(
            select,
            isRestaurant ? 'No default restaurant' : 'No default banquet',
            rows,
            isRestaurant ? 'restaurant_id' : 'banquet_id',
            isRestaurant ? 'restaurant_name' : 'banquet_name'
         );
      } catch (error) {
         if (requestId !== venueRequestId) return;
         select.replaceChildren(new Option(error.message || 'No options found', ''));
      }
   }

   function resetModal() {
      venueRequestId++;
      clearError();
      $('#downloadLeadFormProperty').val('');
      $('#downloadLeadFormDepartment').val('');
      $('#downloadLeadFormVenueGroup').addClass('d-none');
      document.getElementById('downloadLeadFormVenue').replaceChildren(new Option('Select a property first', ''));
   }

   function downloadBlob(blob, filename) {
      var objectUrl = URL.createObjectURL(blob);
      var link = document.createElement('a');
      link.href = objectUrl;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      link.remove();
      setTimeout(function () { URL.revokeObjectURL(objectUrl); }, 1000);
   }

   document.addEventListener('click', function (event) {
      var trigger = event.target.closest('[data-target="#downloadLeadFormModal"]');
      if (trigger) {
         clearError();
         loadBaseLookups();
      }

      var dismissButton = event.target.closest('#downloadLeadFormModal [data-dismiss="modal"]');
      if (dismissButton) {
         resetModal();
      }
   });

   $('#downloadLeadFormProperty, #downloadLeadFormDepartment').on('change', function () {
      refreshVenueOptions();
   });

   $('#downloadLeadFormSubmit').on('click', async function () {
      var button = this;
      var buttonText = button.querySelector('span');
      var department = selectedDepartmentName();
      var formData = new FormData();
      var propertyId = $('#downloadLeadFormProperty').val() || '';
      var departmentId = $('#downloadLeadFormDepartment').val() || '';
      var venueId = $('#downloadLeadFormVenue').val() || '';
      var csrfHash = readCookie(csrfCookieName) || (window.CSRF && window.CSRF.hash) || '';

      clearError();
      formData.append('property_id', propertyId);
      formData.append('department_id', departmentId);

      if ((department === 'restaurant' || department === 'restaurants') && venueId) {
         formData.append('restaurant_id', venueId);
      }
      if ((department === 'banquet' || department === 'banquets') && venueId) {
         formData.append('banquet_id', venueId);
      }
      if (window.CSRF && window.CSRF.name && csrfHash) {
         formData.append(window.CSRF.name, csrfHash);
      }

      button.disabled = true;
      buttonText.textContent = 'Preparing...';

      try {
         var response = await fetch(urls.download, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'Accept': 'application/octet-stream' }
         });

         if (!response.ok) {
            throw new Error('Unable to prepare the lead form download.');
         }

         var contentType = response.headers.get('Content-Type') || '';
         if (contentType.indexOf('application/octet-stream') === -1) {
            throw new Error('The server returned an unexpected download response.');
         }

         downloadBlob(await response.blob(), 'leadform.html');
         if (window.CSRF) {
            window.CSRF.hash = readCookie(csrfCookieName) || window.CSRF.hash;
         }
      } catch (error) {
         showError(error.message || 'Unable to download the lead form.');
      } finally {
         button.disabled = false;
         buttonText.textContent = 'Download Form';
      }
   });
})(window.jQuery);
</script>
