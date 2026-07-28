<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row">


				<div class="col-xxl-12 col-12">


					<div class="box_supar_admin">
					
						<div class="box-body">
							<div class="row">
								<div id="">
									<div class="admin_name_details">

										<div class="admin_left">

											<h2>
												Good Morning, Umesh <?php echo $profile_data->name; ?>
												👋
											</h2>

											<p>
												Here's what's happening with your lead pipeline today.
											</p>

										</div>

										<div class="admin_right">

											<a href="<?= base_url('manage-leads	') ?>" class="btn btn-primary-light btn-sm ">
												<i class="fa fa-plus"></i>
												Add Lead
											</a>
											<a href="" class="btn btn-primary-light btn-sm ">
												<!-- Total Leads -->
																			
												Total Leads : <span id="totalLeads"><?= $total_leads ?></span>
																			
											</a>

										</div>

									</div>
									<form id="filter_lead_stats_count">
										<div class="row align-items-end">

											<!-- Property -->
											<div class="col-sm-3 ">
												<label for="top_filter_property" class="form-label">Property</label>
								<select name="property" id="top_filter_property" class="form-select dashboard-filter-select">
													<option value="">All Properties</option>
													<?php foreach ($properties as $property) { ?>
														<option value="<?= $property->hotel_id; ?>" <?= ($this->input->get('property') == $property->hotel_id) ? 'selected' : ''; ?>>
															<?= $property->hotel_name; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<!-- Department -->
											<div class="col-sm-3 ">
												<label for="top_filter_department" class="form-label">Department</label>
								<select name="department" id="top_filter_department" class="form-select dashboard-filter-select">
													<!-- <option value="">All Departments</option> -->
													<?php foreach ($departments as $dept) { ?>
														<option value="<?= $dept->department_id; ?>" <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
															<?= $dept->department_name; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<!-- Assigned To -->
											<div class="col-sm-3 ">
												<!-- <label for="top_filter_assigned_to d-none" class="form-label">Assigned User</label> -->
								<select name="assigned_to" id="top_filter_assigned_to" class="form-control dashboard-filter-select">
													<option value="">All Assigned Users</option>

													<?php foreach ($assigned_users as $user):
														$value = $user->id . '|' . $user->role;
														$selected = (isset($_GET['assigned_to']) && $_GET['assigned_to'] == $value) ? 'selected' : '';
													?>
														<option value="<?= $value ?>" <?= $selected ?>
															data-id="<?= $user->id ?>"
															data-role="<?= $user->role ?>">
															<?= htmlspecialchars($user->name ?? 'Unknown') ?>
															(<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
														</option>
													<?php endforeach; ?>
												</select>
											</div>

											<!-- Created By -->
											<div class="col-sm-3 ">
												<label for="top_filter_created_by" class="form-label">Created By</label>
								<select name="created_by" id="top_filter_created_by" class="form-control dashboard-filter-select">
													<option value="">All Creators</option>

													<?php foreach ($creators as $user):
														$value = $user->id . '|' . $user->role;
														$selected = (isset($_GET['created_by']) && $_GET['created_by'] == $value) ? 'selected' : '';
													?>
														<option value="<?= $value ?>" <?= $selected ?>
															data-id="<?= $user->id ?>"
															data-role="<?= $user->role ?>">
															<?= htmlspecialchars($user->name ?? 'Unknown') ?>
															(<?= ucfirst(str_replace('_', ' ', $user->role)) ?>)
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-sm-3">
                                                <a type="button" id="toggleFilterBtn" class="">
                                                    <i class="fa fa-filter"></i> More Filters
                                                </a>
                                            </div>

											<!-- Lead Source -->
											<div class="col-sm-3 more-filter d-none">
												<label for="top_filter_channel" class="form-label">Lead Source</label>
								<select name="channel" id="top_filter_channel" class="form-select filter-input dashboard-filter-select">
													<!-- <option value="">All Sources</option> -->
													<?php foreach ($user_channel as $channelObj): ?>
														<?php $channel = $channelObj->user_channel; ?>
														<option value="<?= $channel ?>"><?= strtoupper($channel) ?></option>
													<?php endforeach; ?>
												</select>
											</div>

											

											<!-- Stage -->
											<div class="col-sm-3 more-filter d-none">
												<label for="top_filter_disposition" class="form-label">Stage</label>
								<select name="disposition" id="top_filter_disposition" class="form-select filter-input dashboard-filter-select">
													<option value="">All Stages</option>
													<option value="Not Contacted">Not Contacted</option>
													<option value="Contacted">Contacted</option>
													<option value="Quotation Sent">Quotation Sent</option>
													<option value="Negotiations">Negotiations</option>
													<option value="Contract Done">Contract Done</option>
													<option value="Advance Received">Advance Received</option>
													<option value="Lead Won">Lead Won</option>
													<option value="Lead Lost">Lead Lost</option>
												</select>
											</div>

											<!-- Start Date -->
											<div class="col-sm-3 more-filter d-none">
												<!-- <label for="top_filter_start_date" class="form-label">Start Date</label> -->
												<input type="date"
													name="start_date"
													id="top_filter_start_date"
													class="form-control"
													value="<?= $this->input->get('start_date'); ?>">
											</div>

											<!-- End Date -->
											<div class="col-sm-3 more-filter d-none">
												<!-- <label for="top_filter_end_date" class="form-label">End Date</label> -->
												<input type="date"
													name="end_date"
													id="top_filter_end_date"
													class="form-control"
													value="<?= $this->input->get('end_date'); ?>">
											</div>


										</div>
									</form>


								</div>
							</div>
						</div>

						<div class="box-body" id="top_stats_html">
							<div class="row main_row_data_desh">

								<!-- Total Open -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-agents-leads?status=Open') ?>">
										<div class="stage-card border-red">
											<div class="stage-flex">

												<div class="left-content">
													<div class="stage-title">Total Open</div>

													<div class="lead-count text-danger" data-lead-status="Open">
														<?= $lead_status_counts['Open']; ?>
													</div>

													<div class="revenue-box d-none" style="background:#fef2f2;color:#dc2626;">
														₹ <?= number_format($lead_revenue['Open']); ?>
													</div>
												</div>

												<div class="icon-box bg-red">
													<i class="fa fa-folder-open"></i>
												</div>

											</div>
										</div>
									</a>
								</div>

								<!-- In Progress -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-agents-leads?status=In+Progress') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-cyan">
                                                    <i class="fa fa-spinner"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">In Progress</div>

                                                    <div class="lead-count" data-lead-status="In Progress">
                                                        <?= $lead_status_counts['In Progress']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['In Progress']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Total Closed -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-agents-leads?status=Closed') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-green">
                                                    <i class="fa fa-check-circle"></i>
                                                </div>
                                                <div class="">
                                                    <div class="stage-title">Total Closed</div>
                                                    <div class="lead-count" data-lead-status="Closed">
                                                        <?= $lead_status_counts['Closed']; ?>
                                                    </div>
                                                </div>
                                            </div>                                      
                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['Closed']); ?> 
                                            </div>
                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Not Assigned -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-agents-leads?status=Not-assigned') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-user-times"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Not Assigned</div>

                                                    <div class="lead-count" data-lead-status="Not Assigned">
                                                        <?= $lead_status_counts['Not_Assigned']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['Not_Assigned']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Not Contacted -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Not Contacted') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-blue">
                                                    <i class="fa fa-phone"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Not Contacted</div>

                                                    <div class="lead-count" data-lead-status="Not_Contacted">
                                                        <?= $lead_status_counts['Not_Contacted']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Not_Contacted">
                                                ₹ <?= number_format($lead_revenue['Not_Contacted']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Quotation Sent -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Quotation Sent') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-file-text"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Quotation Sent</div>

                                                    <div class="lead-count" data-lead-status="Quotation_Sent">
                                                        <?= $lead_status_counts['Quotation_Sent']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Quotation_Sent">
                                                ₹ <?= number_format($lead_revenue['Quotation_Sent']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Negotiations -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Negotiations') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-cyan">
                                                    <i class="fa fa-handshake"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Negotiations</div>

                                                    <div class="lead-count" data-lead-status="Negotiations">
                                                        <?= $lead_status_counts['Negotiations']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Negotiations">
                                                ₹ <?= number_format($lead_revenue['Negotiations']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
										</div>
									</a>
								</div>

								<!-- Contract Done -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Contract Done') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-purple">
                                                    <i class="fa fa-file"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Contract Done</div>

                                                    <div class="lead-count" data-lead-status="Contract_Done">
                                                        <?= $lead_status_counts['Contract_Done']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Contract_Done">
                                                ₹ <?= number_format($lead_revenue['Contract_Done']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Advance Received -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Advance Received') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-orange">
                                                    <i class="fa fa-money-bill"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Advance Received</div>

                                                    <div class="lead-count" data-lead-status="Advance_Received">
                                                        <?= $lead_status_counts['Advance_Received']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Advance_Received">
                                                ₹ <?= number_format($lead_revenue['Advance_Received']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Lead Won -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Lead Won') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-green">
                                                    <i class="fa fa-trophy"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Lead Won</div>

                                                    <div class="lead-count" data-lead-status="Lead_Won">
                                                        <?= $lead_status_counts['Lead_Won']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Lead_Won">
                                                ₹ <?= number_format($lead_revenue['Lead_Won']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Lead Lost -->
								<div class="col-sm-2 col-12">
									<a href="<?= base_url('view-agents-leads?disposition=Lead Lost') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-red">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Lead Lost</div>

                                                    <div class="lead-count" data-lead-status="Lead_Lost">
                                                        <?= $lead_status_counts['Lead_Lost']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box" data-lead-revenue="Lead_Lost">
                                                ₹ <?= number_format($lead_revenue['Lead_Lost']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- Total Revenue -->
								<div class="col-sm-2 col-12">
									<div class="premium-card">
                                        <div class="card-top">
                                            <div class="icon-box bg-purple">
                                                <i class="fa fa-inr"></i>
                                            </div>

                                            <div>
                                                <div class="stage-title">Total Revenue</div>

                                                <div class="lead-count" data-lead-status="total_revenue">
                                                    ₹ <?= number_format($total_revenue, 2); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="revenue-box">
                                            Overall Collection
                                        </div>

                                        <div class="sparkline">
                                            <span></span><span></span><span></span><span></span>
                                            <span></span><span></span><span></span><span></span>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>



				<div class="col-xxl-12 col-12">
					<div class="box quick-filter-box box-body">
					<form>
										<div class="">
											<form method="GET" action="<?= base_url('view-agents-leads'); ?>" class="mb-4 px-3">
												<div class="row align-items-end">
													<!-- Existing filters (City, Property, etc.) -->



													<?php

													$property = $this->session->userdata('selected_hotel_id');
													$department = $this->session->userdata('selected_department_id');


													?>

													<input type="hidden" name="property_bottom" value='<?php echo $property ?>'>
											<input type="hidden" name="department_bottom" value="">






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

				<div class="chart_box_main box-body">
                    <div class="chart_box_main_list row">
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_department_line"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_status_new"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_stage_bar"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_guest_type"></canvas>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <div class="sales-funnel-box">
                                <div id="sales_funnel_chart"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 chart_box_main_items">
                            <canvas id="chart_revenue_vs_leads"></canvas>
                        </div>
                    </div>
                </div>
				<!-- <div class="row">
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

					<div class="col-md-6">
						<div class="box chart-container" id="container_guest_type">

							<div id="chart_guest_type" style="height: 400px;"></div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box chart-container" id="container_template_name">

							<div id="chart_template_name" style="height: 400px;"></div>
						</div>
					</div>

				</div> -->
			</div>
		</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.highcharts.com/modules/funnel.js"></script>

		<script>
			var agentDashboardCharts = {};

			function renderDashboardChart(containerId, title, categories, series, type = 'column') {
				const isPie = type === 'pie';
				const isHorizontal = type === 'bar';
				const container = document.getElementById(containerId);

				if (!container || typeof ApexCharts === 'undefined') {
					return;
				}

				if (agentDashboardCharts[containerId]) {
					agentDashboardCharts[containerId].destroy();
				}

				var chartOptions = {
					chart: {
						type: isPie ? 'pie' : 'bar',
						height: 380,
						toolbar: {
							show: true
						}
					},
					title: {
						text: title,
						align: 'center'
					},
					plotOptions: {
						bar: {
							horizontal: isHorizontal,
							columnWidth: '55%'
						}
					},
					noData: {
						text: 'No data available'
					}
				};

				if (isPie) {
					chartOptions.series = series;
					chartOptions.labels = categories;
				} else {
					chartOptions.series = [{
						name: title,
						data: series
					}];
					chartOptions.xaxis = {
						categories: categories
					};
					chartOptions.yaxis = {
						title: {
							text: 'Count'
						}
					};
				}

				agentDashboardCharts[containerId] = new ApexCharts(container, chartOptions);

				agentDashboardCharts[containerId].render();
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
						renderDashboardChart(containerId, title, categories, counts, type);
					},
					error: function() {
						$('#' + containerId).html('<p>Error loading data.</p>');
					}
				});
			}

			window.addEventListener('load', function() {
				var $ = window.jQuery;

				if (!$ || typeof ApexCharts === 'undefined') {
					return;
				}

				const chartConfigs = [{
						id: 'chart_department',
						endpoint: '<?= base_url("agent/Main/department_chart_data") ?>',
						title: 'Leads by Department',
						type: 'column',
						startId: 'start_department',
						endId: 'end_department'
					},
					{
						id: 'chart_status',
						endpoint: '<?= base_url("agent/Main/status_chart_data") ?>',
						title: 'Leads by Status',
						type: 'pie',
						startId: 'start_status',
						endId: 'end_status'
					},
					{
						id: 'chart_disposition',
						endpoint: '<?= base_url("agent/Main/disposition_chart_data") ?>',
						title: 'Leads by Stage',
						type: 'bar',
						startId: 'start_disposition',
						endId: 'end_disposition'
					},
					{
						id: 'chart_source',
						endpoint: '<?= base_url("agent/Main/source_chart_data") ?>',
						title: 'Leads by Source',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_guest_type',
						endpoint: '<?= base_url("agent/Main/guest_type_chart_data") ?>',
						title: 'Leads by Guest Type',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_template_name',
						endpoint: '<?= base_url("agent/Main/template_chart_data") ?>',
						title: 'Leads by Templates',
						type: 'column',
						startId: 'start_source',
						endId: 'end_source'
					}
				];

				chartConfigs.forEach(config => {
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
			window.addEventListener('load', function() {
				var $ = window.jQuery;

				if (!$) {
					return;
				}

				if ($.fn.select2) {
					$('.dashboard-filter-select').each(function() {
						var $select = $(this);

						if (!$select.hasClass('select2-hidden-accessible')) {
							$select.select2({
								width: '100%',
								minimumResultsForSearch: 0
							});
						}
					});
				}

				/* =========================================
				   AUTO FILTER ON CHANGE
				========================================= */
				$('#top_filter_property, \
       #top_filter_department, \
       #top_filter_channel, \
       #top_filter_disposition, \
       #top_filter_start_date, \
       #top_filter_end_date, \
       #top_filter_created_by, \
       #top_filter_assigned_to')
					.off('change.agentDashboardFilters')
					.on('change.agentDashboardFilters', function() {
						applyTopFilters();
					});

				applyTopFilters();
			});


			/* =========================================
			   SPLIT USER VALUE => id|role
			========================================= */
			function getUserFilterData(selector) {

				let val = $(selector).val();

				if (!val) {
					return {
						id: '',
						role: ''
					};
				}

				let parts = val.split('|');

				return {
					id: parts[0] || '',
					role: parts[1] || ''
				};
			}

			/* =========================================
			   MAIN FILTER FUNCTION
			========================================= */
			function applyTopFilters() {

				let createdUser = getUserFilterData('#top_filter_created_by');
				let assignedUser = getUserFilterData('#top_filter_assigned_to');


				let filters = {

					property: $('#top_filter_property').val(),
					department: $('#top_filter_department').val(),
					channel: $('#top_filter_channel').val(),
					disposition: $('#top_filter_disposition').val(),

					start_date: $('#top_filter_start_date').val(),
					end_date: $('#top_filter_end_date').val(),

					created_id: createdUser.id,
					created_role: createdUser.role,

					assigned_id: assignedUser.id,
					assigned_role: assignedUser.role
				};
				var csrfTokenName = <?= json_encode($this->security->get_csrf_token_name()); ?>;
				var csrfCookieName = <?= json_encode($this->config->item('csrf_cookie_name')); ?>;
				var csrfHash = getDashboardCookieValue(csrfCookieName);

				if (csrfHash) {
					filters[csrfTokenName] = csrfHash;
				}


				$.ajax({
					type: "POST",
					url: "<?= base_url('agent/Main/dashboard_top_filter') ?>",
					data: filters,
					dataType: "json",

					beforeSend: function() {
						$('#totalLeads').html('...');
					},

					success: function(response) {

						/* =========================
						   OVERALL STATUS COUNTS
						========================= */
						$('[data-lead-status="Open"]').text(response.Open);
						$('[data-lead-status="In Progress"]').text(response['In Progress']);
						$('[data-lead-status="On Hold"]').text(response['On Hold']);
						$('[data-lead-status="Closed"]').text(response.Closed);
						$('[data-lead-status="Not Assigned"]').text(response.Not_Assigned);


						/* =========================
						   STAGE COUNTS
						========================= */
						$('[data-lead-status="Not_Contacted"]').text(response.Not_Contacted);
						$('[data-lead-status="Quotation_Sent"]').text(response.Quotation_Sent);
						$('[data-lead-status="Negotiations"]').text(response.Negotiations);
						$('[data-lead-status="Contract_Done"]').text(response.Contract_Done);
						$('[data-lead-status="Advance_Received"]').text(response.Advance_Received);
						$('[data-lead-status="Lead_Won"]').text(response.Lead_Won);
						$('[data-lead-status="Lead_Lost"]').text(response.Lead_Lost);



						$('[data-lead-revenue="Not_Contacted"]').text(formatIndianCurrency(response.Not_Contacted_Revenue));
						$('[data-lead-revenue="Quotation_Sent"]').text(formatIndianCurrency(response.Quotation_Sent_Revenue));
						$('[data-lead-revenue="Negotiations"]').text(formatIndianCurrency(response.Negotiations_Revenue));
						$('[data-lead-revenue="Contract_Done"]').text(formatIndianCurrency(response.Contract_Done_Revenue));
						$('[data-lead-revenue="Advance_Received"]').text(formatIndianCurrency(response.Advance_Received_Revenue));
						$('[data-lead-revenue="Lead_Won"]').text(formatIndianCurrency(response.Lead_Won_Revenue));
						$('[data-lead-revenue="Lead_Lost"]').text(formatIndianCurrency(response.Lead_Lost_Revenue));

						$('[data-lead-status="total_revenue"]').text(formatIndianCurrency(response.total_revenue));

						$('#totalLeads').html(response.total_leads);
					},

					error: function(xhr, status, error) {
						console.log(error);
					}
				});

			}

			function getDashboardCookieValue(cookieName) {
				var encodedName = encodeURIComponent(cookieName) + '=';
				var cookies = document.cookie ? document.cookie.split('; ') : [];

				for (var index = 0; index < cookies.length; index++) {
					if (cookies[index].indexOf(encodedName) === 0) {
						return decodeURIComponent(cookies[index].substring(encodedName.length));
					}
				}

				return '';
			}

			function numberFormat(amount) {

				amount = parseFloat(amount);

				if (isNaN(amount)) {
					amount = 0;
				}

				return amount.toLocaleString('en-IN', {
					minimumFractionDigits: 0,
					maximumFractionDigits: 2
				});
			}


			function formatIndianCurrency(amount) {

				amount = parseFloat(amount) || 0;

				if (amount >= 10000000) {
					return '₹ ' + (amount / 10000000).toFixed(2) + ' Cr';
				}

				if (amount >= 100000) {
					return '₹ ' + (amount / 100000).toFixed(2) + ' Lakh';
				}

				if (amount >= 1000) {
					return '₹ ' + (amount / 1000).toFixed(2) + ' K';
				}

				return '₹ ' + amount.toLocaleString('en-IN');
			}
		</script>


<script>
    
$("#toggleFilterBtn").click(function () {

    var filters = $(".more-filter");

    if ($(this).hasClass("open")) {

        $(this)
            .removeClass("open")
            .html('<i class="fa fa-filter"></i> More Filters');

        filters.removeClass("show-filter");

        setTimeout(function () {
            filters.addClass("d-none");
        }, 350);

    } else {

        $(this)
            .addClass("open")
            .html('<i class="fa fa-times"></i> Hide Filters');

        filters.removeClass("d-none");

        setTimeout(function () {
            filters.addClass("show-filter");
        }, 20);
    }

});
</script>