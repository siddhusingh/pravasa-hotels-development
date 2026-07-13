<!-- Content Wrapper. Contains page content -->
<style>
	.fw-500 {
		font-size: 20px;
	}

	text.highcharts-credits {
		display: none;
	}
</style>

<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row">




				<!-- quick filters -->

				<div class="col-xxl-6 col-12">


					<div class="box">
						<div class="box-header no-border pb-0">
						</div>
						<div class="box-body">
							<div class="row">
								<div id="">

									<form id="filter_lead_stats_count">
										<div class="row g-3 align-items-end">
											<!-- Existing filters (City, Property, etc.) -->




											<div class="col-md-3">
												<label for="property" class="form-label">Property</label>
												<select name="property" class="form-select">
													<option value="">All Properties</option>
													<?php foreach ($properties as $property) { ?>
														<option value="<?= $property->hotel_id; ?>" <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
															<?= $property->hotel_name; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<div class="col-md-2">
												<label for="department" class="form-label">Department</label>
												<select name="department" class="form-select">
													<option value="">All Departments</option>
													<?php foreach ($departments as $dept) { ?>
														<option value="<?= $dept->department_id; ?>" <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
															<?= $dept->department_name; ?>
														</option>
													<?php } ?>
												</select>
											</div>


											<!-- 🆕 Date Filters -->
											<div class="col-md-2">
												<label for="start_date" class="form-label">Start Date</label>
												<input type="date" name="start_date" class="form-control" value="<?= $this->input->get('start_date'); ?>">
											</div>
											<div class="col-md-2">
												<label for="end_date" class="form-label">End Date</label>
												<input type="date" name="end_date" class="form-control" value="<?= $this->input->get('end_date'); ?>">
											</div>

											<div class="col-md-2 d-grid">
												<button type="button" class="btn btn-primary" id="filter_top_button">Filter</button>
											</div>

											<div class="col-md-3 d-grid">
												<h5>Total Leads : <span id="totalLeads"><?php echo $total_leads ?></span></h5>
											</div>

										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="box-body" id="top_stats_html">
							<div class="row">


								<div class="col-xxl-6 col-md-4 col-12">
									<a href="<?php echo base_url('view-agencys-leads?status=Open') ?>">
										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-danger-light rounded-circle"><span class="icon-Ticket"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Total Open </p>
												<h2 class="my-0 fw-500 text-danger" data-lead-status="Open"><?= $lead_status_counts['Open']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>

								<div class="col-xxl-6 col-md-4 col-12">
									<a href="<?php echo base_url('view-agencys-leads?status=In+Progress') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-info-light rounded-circle"><span class="fa fa-cogs"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Total In Progress </p>
												<h2 class="my-0 fw-500 text-info" data-lead-status="In Progress"><?= $lead_status_counts['In Progress']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>

								<div class="col-xxl-6 col-md-4 col-12">
									<a href="<?php echo base_url('view-agencys-leads?status=Closed') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-success-light rounded-circle"><span class=" fa fa-trophy"><span class="path1"></span><span class="path2"></span></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Total Closed </p>
												<h2 class="my-0 fw-500 text-success" data-lead-status="Closed"><?= $lead_status_counts['Closed']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>


								<div class="col-xxl-6 col-md-3 col-12">
									<a href="<?php echo base_url('view-agencys-leads?disposition=Information/Enquiry') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-warning-light rounded-circle"><span class="fa fa-thumbs-up"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Interested </p>
												<h2 class="my-0 fw-500 text-warning" data-lead-status="Information"><?= $lead_status_counts['Information']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>
								<div class="col-xxl-6 col-md-3 col-12">
									<a href="<?php echo base_url('view-agencys-leads?disposition=Shopping - Follow up') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-info-light rounded-circle"><span class="fa fa-calendar-check"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Follow up </p>
												<h2 class="my-0 fw-500 text-info" data-lead-status="followup"><?= $lead_status_counts['followup']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>
								<div class="col-xxl-6 col-md-3 col-12">
									<a href="<?php echo base_url('view-agencys-leads?disposition=Reservation') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-success-light rounded-circle"><span class="fa fa-calendar-alt"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Reservation </p>
												<h2 class="my-0 fw-500 text-success" data-lead-status="Reservation"><?= $lead_status_counts['Reservation']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>
								<div class="col-xxl-6 col-md-3 col-12">
									<a href="<?php echo base_url('view-agencys-leads?disposition=Denied') ?>">

										<div class="info-box bg-transparent no-shadow px-0 b-0">
											<span class="info-box-icon bg-danger-light rounded-circle"><span class="fa fa-times-circle"></span></span>
											<div class="info-box-content pb-0">
												<p class="mb-0 fs">Denied </p>
												<h2 class="my-0 fw-500 text-danger" data-lead-status="Denied"><?= $lead_status_counts['Denied']; ?> Leads</h2>
											</div>
										</div>
									</a>
								</div>


							</div>
						</div>
					</div>
				</div>




				<div class="col-xxl-6 col-12 d-none">
					<div class="box">
						<div class="box-header no-border pb-0">
							<h4 class="box-title">Quick Filters for Charts </h4>
						</div>
						<div class="box-body">
							<div class="row">
								<div id="">

									<form>
										<div class="row g-3 align-items-end">
											<form method="GET" action="<?= base_url('view-agencys-leads'); ?>" class="mb-4 px-3">
												<div class="row g-3 align-items-end">
													<!-- Existing filters (City, Property, etc.) -->



													<?php

													$property = $this->session->userdata('selected_hotel_id');
													$department = $this->session->userdata('selected_department_id');


													?>

													<input type="hidden" name="property_bottom" value='<?php echo $property ?>'>
													<input type="hidden" name="department_bottom" value='<?php echo $department ?>'>






													<!-- Date Filters -->
													<!-- 🆕 Date Filters -->
													<div class="col-md-2">
														<label for="start_date" class="form-label">Start Date</label>
														<input type="date" name="start_date_bottom" class="form-control" value="<?= $this->input->get('start_date'); ?>">
													</div>
													<div class="col-md-2">
														<label for="end_date" class="form-label">End Date</label>
														<input type="date" name="end_date_bottom" class="form-control" value="<?= $this->input->get('end_date'); ?>">
													</div>
													<div class="col-md-2 d-grid">
														<button type="button" id="filter_bottom_button" class="btn btn-primary">Filter</button>
													</div>

												</div>
											</form>



										</div>
									</form>


								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row d-none">
					<div class="col-md-6">
						<div class="box chart-container" id="container_department">

							<div id="chart_department" style="height: 400px;"></div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box chart-container" id="container_status">

							<div id="chart_status" style="height: 400px;"></div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box chart-container" id="container_disposition">

							<div id="chart_disposition" style="height: 400px;"></div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box chart-container" id="container_source">

							<div id="chart_source" style="height: 400px;"></div>
						</div>
					</div>



				</div>





			</div>
		</section>

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- Highcharts and modules -->
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>

		<style>
			.chart-container {
				border: 1px solid #ccc;
				padding: 10px;
				margin-bottom: 25px;
				background: #fff;
				position: relative;
			}

			.filter-row {
				display: none;
				justify-content: space-between;
				align-items: center;
				margin-bottom: 10px;
				gap: 10px;
				flex-wrap: wrap;
			}
		</style>


		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- Highcharts core and modules -->
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>
		<script src="https://code.highcharts.com/modules/full-screen.js"></script>






		<script>
			function renderHighchart(containerId, title, categories, series, type = 'column') {
				const isPie = type === 'pie';

				Highcharts.chart(containerId, {
					chart: {
						type: isPie ? 'pie' : type
					},
					title: {
						text: title
					},
					exporting: {
						enabled: true
					},
					accessibility: {
						enabled: true
					},
					legend: {
						enabled: true
					},
					tooltip: {
						pointFormat: isPie ? '<b>{point.y}</b>' : '<b>{point.y}</b>'
					},
					series: isPie ? [{
						name: title,
						colorByPoint: true,
						data: categories.map((label, index) => ({
							name: label,
							y: series[index]
						}))
					}] : [{
						name: title,
						data: series
					}],
					xAxis: !isPie ? {
						categories: categories
					} : undefined,
					yAxis: !isPie ? {
						min: 0,
						title: {
							text: 'Count'
						}
					} : undefined,
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.y}'
							}
						},
						series: {
							showInLegend: true
						}
					}
				});
			}

			function fetchAndRenderChart(endpoint, containerId, title, type) {
				const filters = {
					property: $('input[name="property_bottom"]').val(),
					type: $('input[name="department_bottom"]').val(),
					start_date: $('input[name="start_date_bottom"]').val(),
					end_date: $('input[name="end_date_bottom"]').val()
				};

				$.ajax({
					url: endpoint,
					type: 'GET',
					data: filters,
					dataType: 'json',
					success: function(data) {
						const categories = data.map(d => d.label);
						const counts = data.map(d => parseInt(d.count));
						renderHighchart(containerId, title, categories, counts, type);
					},
					error: function() {
						$('#' + containerId).html('<p>Error loading data.</p>');
					}
				});
			}

			$(document).ready(function() {
				const chartConfigs = [{
						id: 'chart_department',
						endpoint: '<?= base_url("agency/Main/department_chart_data") ?>',
						title: 'Leads by Department',
						type: 'column',
						startId: 'start_department',
						endId: 'end_department'
					},
					{
						id: 'chart_status',
						endpoint: '<?= base_url("agency/Main/status_chart_data") ?>',
						title: 'Leads by Status',
						type: 'pie',
						startId: 'start_status',
						endId: 'end_status'
					},
					{
						id: 'chart_disposition',
						endpoint: '<?= base_url("agency/Main/disposition_chart_data") ?>',
						title: 'Leads by Stage',
						type: 'bar',
						startId: 'start_disposition',
						endId: 'end_disposition'
					},
					{
						id: 'chart_source',
						endpoint: '<?= base_url("agency/Main/source_chart_data") ?>',
						title: 'Leads by Source',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_guest_type',
						endpoint: '<?= base_url("agency/Main/guest_type_chart_data") ?>',
						title: 'Leads by Guest Type',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_template_name',
						endpoint: '<?= base_url("agency/Main/template_chart_data") ?>',
						title: 'Leads by Templates',
						type: 'column',
						startId: 'start_source',
						endId: 'end_source'
					}
				];

				chartConfigs.forEach(config => {
					fetchAndRenderChart(config.endpoint, config.id, config.title, config.type);

					$('#' + config.startId + ', #' + config.endId).on('change', function() {
						const start = $('#' + config.startId).val();
						const end = $('#' + config.endId).val();
						fetchAndRenderChart(config.endpoint, config.id, config.title, config.type, start, end);
					});
				});


				function reloadAllCharts() {
					chartConfigs.forEach(config => {
						fetchAndRenderChart(config.endpoint, config.id, config.title, config.type);
					});
				}

				// Initial load
				reloadAllCharts();

				// Global filter button
				$('#filter_bottom_button').on('click', function(e) {
					e.preventDefault();
					reloadAllCharts();
				});
			});
		</script>


		<script>
			$(document).ready(function() {
				$('#filter_top_button').on('click', function() {
					let formData = $('#filter_lead_stats_count').serialize();

					$.ajax({
						type: "POST",
						url: "<?= base_url('agency/Main/dashboard_top_filter') ?>",
						data: formData,
						dataType: "json",
						success: function(response) {
							if (response) {
								$('[data-lead-status="Open"]').text(response.Open + ' Leads');
								$('[data-lead-status="In Progress"]').text(response['In Progress'] + ' Leads');
								$('[data-lead-status="On Hold"]').text(response['On Hold'] + ' Leads');
								$('[data-lead-status="Closed"]').text(response.Closed + ' Leads');
								$('[data-lead-status="Information"]').text(response.Information + ' Leads');
								$('[data-lead-status="followup"]').text(response.followup + ' Leads');
								$('[data-lead-status="Reservation"]').text(response.Reservation + ' Leads');
								$('[data-lead-status="Denied"]').text(response.Denied + ' Leads');
								$('[data-lead-status="total_calls"]').text(response.total_calls + ' Calls');
								$('[data-lead-status="total_answered_calls"]').text(response.total_answered_calls + ' Calls');
								$('[data-lead-status="total_missed_calls"]').text(response.total_missed_calls + ' Calls');
								$('[data-lead-status="total_revenue"]').text(response.total_revenue);
								$("#totalLeads").html(response.total_leads)
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error:', status, error);
						}
					});
				});
			});
		</script>