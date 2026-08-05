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
												<?= htmlspecialchars(get_time_based_greeting(), ENT_QUOTES, 'UTF-8'); ?>,
												<?= htmlspecialchars($agent_name, ENT_QUOTES, 'UTF-8'); ?> 👋
											</h2>

											<p>
												Here's what's happening with your lead pipeline today.
											</p>

										</div>

										<div class="admin_right">

											<a href="<?= base_url('add-lead-agents') ?>" class="btn btn-primary-light btn-sm ">
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
								<?php
									$selectedPropertyId = $this->session->userdata('selected_hotel_id');
									$selectedPropertyName = 'Selected Property';
									foreach ($properties as $property) {
										if ((string) $property->hotel_id === (string) $selectedPropertyId) {
											$selectedPropertyName = $property->hotel_name;
											break;
										}
									}
								?>
								<select name="property" id="top_filter_property" class="form-select" disabled aria-disabled="true">
													<option value="<?= htmlspecialchars($selectedPropertyId); ?>" selected>
														<?= htmlspecialchars($selectedPropertyName); ?>
													</option>
												</select>
											</div>

											<!-- Department -->
											<div class="col-sm-3 ">
												<label for="top_filter_department" class="form-label">Department</label>
								<select name="department" id="top_filter_department" class="form-select dashboard-filter-select">
													<option value="">All Departments</option>
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
													<option value="">All Sources</option>
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
								<div class="d-none col-sm-3 col-12">
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
											<div class="col-md-6">
												<div id="graph_filter_error" class="text-danger mt-2" role="alert" style="display:none;"></div>
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

		<script>
			window.addEventListener('load', function() {
				var $ = window.jQuery;

				if (!$ || typeof Chart === 'undefined' || typeof Chart.getChart !== 'function') {
					return;
				}

				var $graphFilterButton = $('#filter_bottom_button');

				function graphFilters() {
					return {
						start_date: $('input[name="start_date_bottom"]').val(),
						end_date: $('input[name="end_date_bottom"]').val()
					};
				}

				function dashboardChart(id) {
					var canvas = document.getElementById(id);
					return canvas ? Chart.getChart(canvas) : null;
				}

				function replaceSingleDataset(chartId, rows) {
					var chart = dashboardChart(chartId);
					if (!chart) return;

					chart.data.labels = rows.map(function(row) { return row.label; });
					chart.data.datasets[0].data = rows.map(function(row) {
						return Number(row.count) || 0;
					});
					chart.canvas.setAttribute('role', 'img');
					chart.canvas.setAttribute('aria-label', rows.map(function(row) {
						return row.label + ': ' + (Number(row.count) || 0);
					}).join(', '));
					chart.update();
				}

				function renderSalesFunnel(totalLeads, stages) {
					var $target = $('#sales_funnel_chart');
					if (!$target.length) return;

					var colors = ['#3b82f6', '#38bdf8', '#34d399', '#62c76b', '#f7ad20',
						'#f57c2d', '#e83d87', '#8b5cf6', '#ef4444'
					];
					var sortedStages = stages.filter(function(stage) {
						return Number(stage.count) > 0;
					}).sort(function(firstStage, secondStage) {
						return Number(secondStage.count) - Number(firstStage.count);
					});
					var rows = [{
						label: 'Total Leads',
						count: Number(totalLeads) || 0
					}].concat(sortedStages);
					var base = Math.max(Number(totalLeads) || 0, 1);

					var steps = rows.map(function(row, index) {
						var percentage = (Number(row.count) / base) * 100;
						var width = Math.max(percentage, Number(row.count) > 0 ? 18 : 8);
						return '<div class="sales-funnel-step" style="width:' + width + '%;background:' +
							colors[index % colors.length] + '"></div>';
					}).join('');

					var legend = rows.map(function(row, index) {
						var percentage = ((Number(row.count) / base) * 100).toFixed(1).replace('.0', '');
						return '<div class="sales-funnel-legend__item">' +
							'<span class="sales-funnel-legend__dot" style="background:' +
							colors[index % colors.length] + '"></span>' +
							'<span>' + row.label + '</span>' +
							'<strong class="sales-funnel-legend__value">' +
							Number(row.count).toLocaleString('en-IN') + ' (' + percentage + '%)</strong>' +
							'</div>';
					}).join('');

					$target.html('<section class="sales-funnel-card" aria-label="Sales Funnel">' +
						'<h3 class="sales-funnel-card__title">Sales Funnel</h3>' +
						'<div class="sales-funnel-card__content">' +
						'<div class="sales-funnel-visual" aria-hidden="true">' + steps + '</div>' +
						'<div class="sales-funnel-legend">' + legend + '</div>' +
						'</div></section>');
				}

				function updateDashboardGraphs(response) {
					replaceSingleDataset('chart_department_line', response.departments || []);
					replaceSingleDataset('chart_status_new', response.statuses || []);
					replaceSingleDataset('chart_stage_bar', response.stages || []);

					var monthly = response.monthly || [];
					var guestChart = dashboardChart('chart_guest_type');
					if (guestChart) {
						guestChart.data.labels = monthly.map(function(row) { return row.label; });
						guestChart.data.datasets[0].data = monthly.map(function(row) {
							return Number(row.guests) || 0;
						});
						guestChart.canvas.setAttribute('role', 'img');
						guestChart.canvas.setAttribute('aria-label', monthly.map(function(row) {
							return row.label + ' guests: ' + (Number(row.guests) || 0);
						}).join(', '));
						guestChart.update();
					}

					var revenueChart = dashboardChart('chart_revenue_vs_leads');
					if (revenueChart) {
						revenueChart.data.labels = monthly.map(function(row) { return row.label; });
						revenueChart.data.datasets[0].data = monthly.map(function(row) {
							return Number(row.leads) || 0;
						});
						revenueChart.data.datasets[0].yAxisID = 'y';
						revenueChart.data.datasets[1].data = monthly.map(function(row) {
							return Number(row.revenue) || 0;
						});
						revenueChart.data.datasets[1].yAxisID = 'y1';
						revenueChart.canvas.setAttribute('role', 'img');
						revenueChart.canvas.setAttribute('aria-label', monthly.map(function(row) {
							return row.label + ' leads: ' + (Number(row.leads) || 0) +
								', revenue: ₹' + Number(row.revenue || 0).toLocaleString('en-IN');
						}).join('; '));
						revenueChart.options.scales.y1 = {
							beginAtZero: true,
							position: 'right',
							grid: { drawOnChartArea: false },
							ticks: {
								callback: function(value) {
									return '₹' + Number(value).toLocaleString('en-IN');
								}
							}
						};
						revenueChart.update();
					}

					renderSalesFunnel(response.total_leads, response.stages || []);
				}

				function loadDashboardGraphs() {
					var filters = graphFilters();
					var $graphFilterError = $('#graph_filter_error');

					if (filters.start_date && filters.end_date && filters.start_date > filters.end_date) {
						$graphFilterError.text('Start date cannot be after end date.').show();
						return;
					}

					$graphFilterError.hide().text('');

					$.ajax({
						url: '<?= base_url("agent/Main/dashboard_graph_data") ?>',
						type: 'GET',
						data: filters,
						dataType: 'json',
						beforeSend: function() {
							$graphFilterButton.prop('disabled', true).text('Loading...');
						},
						success: function(response) {
							updateDashboardGraphs(response);
						},
						error: function() {
							$graphFilterError.text('Unable to load dashboard graphs. Please try again.').show();
						},
						complete: function() {
							$graphFilterButton.prop('disabled', false).text('Filter');
						}
					});
				}

				$graphFilterButton.off('click.agentDashboardGraphs').on('click.agentDashboardGraphs', function(event) {
					event.preventDefault();
					loadDashboardGraphs();
				});

				loadDashboardGraphs();
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
(function () {
    var toggleButton = document.getElementById('toggleFilterBtn');

    if (!toggleButton) {
        return;
    }

    toggleButton.addEventListener('click', function (event) {
        event.preventDefault();

        var filters = document.querySelectorAll('.more-filter');
        var isOpen = toggleButton.classList.contains('open');

        if (isOpen) {
            toggleButton.classList.remove('open');
            toggleButton.innerHTML = '<i class="fa fa-filter"></i> More Filters';

            filters.forEach(function (filter) {
                filter.classList.remove('show-filter');
            });

            setTimeout(function () {
                filters.forEach(function (filter) {
                    filter.classList.add('d-none');
                });
            }, 350);
            return;
        }

        toggleButton.classList.add('open');
        toggleButton.innerHTML = '<i class="fa fa-times"></i> Hide Filters';

        filters.forEach(function (filter) {
            filter.classList.remove('d-none');
        });

        setTimeout(function () {
            filters.forEach(function (filter) {
                filter.classList.add('show-filter');
            });
        }, 20);
    });
})();
</script>
