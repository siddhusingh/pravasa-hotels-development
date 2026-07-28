<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row">

				<!-- quick filters -->

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
											<div class="col-sm-3  ">
												<label for="top_filter_property" class="form-label">Property</label>
												<select name="property" id="top_filter_property" class="form-select">
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
												<select name="department" id="top_filter_department" class="form-select">
													<option value="">All Departments</option>
													<?php foreach ($departments as $dept) { ?>
														<option value="<?= $dept->department_id; ?>" <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
															<?= $dept->department_name; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<!-- Assigned To -->
											<div class="col-sm-3  ">
												<label for="top_filter_assigned_to" class="form-label">Assigned User</label>
												<select name="assigned_to" id="top_filter_assigned_to" class="form-control">
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
												<select name="created_by" id="top_filter_created_by" class="form-control">
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
												<select name="channel" id="top_filter_channel" class="form-select filter-input">
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
												<select name="disposition" id="top_filter_disposition" class="form-select filter-input">
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
												<label for="top_filter_start_date" class="form-label">Start Date</label>
												<input type="date"
													name="start_date"
													id="top_filter_start_date"
													class="form-control"
													value="<?= $this->input->get('start_date'); ?>">
											</div>
													

											<!-- End Date -->
											<div class="col-sm-3 more-filter d-none">
												<label for="top_filter_end_date" class="form-label">End Date</label>
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
									<a href="<?= base_url('view-leads?status=Open') ?>">
										<div class="premium-card">
                                            <div class="card-top">
                                                <div class="icon-box bg-red">
                                                    <i class="fa fa-folder-open"></i>
                                                </div>

                                                <div>
                                                    <div class="stage-title">Total Open</div>

                                                    <div class="lead-count" data-lead-status="Open">
                                                        <?= $lead_status_counts['Open']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="revenue-box">
                                                ₹ <?= number_format($lead_revenue['Open']); ?>
                                            </div>

                                            <div class="sparkline">
                                                <span></span><span></span><span></span><span></span>
                                                <span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
									</a>
								</div>

								<!-- In Progress -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-leads?status=In+Progress') ?>">
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
									<a href="<?= base_url('view-leads?status=Closed') ?>">
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
									<a href="<?= base_url('view-leads?status=Not-assigned') ?>">
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


								<!-- Not Contacted -->
								<div class="col-sm-3 col-12">
									<a href="<?= base_url('view-leads?disposition=Not Contacted') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Quotation Sent') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Negotiations') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Contract Done') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Advance Received') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Lead Won') ?>">
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
									<a href="<?= base_url('view-leads?disposition=Lead Lost') ?>">
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


				<div class="col-xxl-12 col-12 box-body">
					<form id="chart-filter-form" class="mb-4 px-3">
						<div class="row align-items-end">
									<div class="col-md-4">
										<!-- <label for="department_bottom" class="form-label">Department</label> -->
										<select name="department_bottom" id="department_bottom" class="form-select">
											<option value="">All Departments</option>
											<?php foreach ($departments as $dept) { ?>
												<option value="<?= $dept->department_id; ?>" <?= ($this->input->get('department') == $dept->department_id) ? 'selected' : ''; ?>>
													<?= $dept->department_name; ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-4">
										<!-- <label for="start_date_bottom" class="form-label">Start Date</label> -->
										<input type="date" name="start_date_bottom" id="start_date_bottom" class="form-control" value="<?= $this->input->get('start_date'); ?>">
									</div>
									<div class="col-md-3">
										<!-- <label for="end_date_bottom" class="form-label">End Date</label> -->
										<input type="date" name="end_date_bottom" id="end_date_bottom" class="form-control" value="<?= $this->input->get('end_date'); ?>">
									</div>
									<div class="col-md-1 d-grid">
										<button type="button" id="filter_bottom_button" class="btn btn-primary">Filter</button>
									</div>
								</div>
							</form>
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


		<!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Highcharts and modules -->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.highcharts.com/modules/funnel.js"></script>



        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Highcharts core and modules -->
        <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/modules/full-screen.js"></script>




		<!-- <script>
			window.CSRF = {
				name: "<?= $this->security->get_csrf_token_name(); ?>",
				hash: "<?= $this->security->get_csrf_hash(); ?>"
			};

			function validateDateRange(startSelector, endSelector) {
				const startInput = $(startSelector)[0];
				const endInput = $(endSelector)[0];

				if (!startInput || !endInput) {
					return true;
				}

				const isValid = !startInput.value || !endInput.value || startInput.value <= endInput.value;
				endInput.setCustomValidity(isValid ? '' : 'End date must be on or after the start date.');

				if (!isValid) {
					endInput.reportValidity();
				}

				return isValid;
			}

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
					type: $('select[name="department_bottom"]').val(),
					start_date: $('input[name="start_date_bottom"]').val(),
					end_date: $('input[name="end_date_bottom"]').val()
				};

				return $.ajax({
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
						endpoint: '<?= base_url("hotelAdmin/Main/department_chart_data") ?>',
						title: 'Leads by Department',
						type: 'column',
						startId: 'start_department',
						endId: 'end_department'
					},
					{
						id: 'chart_status',
						endpoint: '<?= base_url("hotelAdmin/Main/status_chart_data") ?>',
						title: 'Leads by Status',
						type: 'pie',
						startId: 'start_status',
						endId: 'end_status'
					},
					{
						id: 'chart_disposition',
						endpoint: '<?= base_url("hotelAdmin/Main/disposition_chart_data") ?>',
						title: 'Leads by Stage',
						type: 'bar',
						startId: 'start_disposition',
						endId: 'end_disposition'
					},
					{
						id: 'chart_source',
						endpoint: '<?= base_url("hotelAdmin/Main/source_chart_data") ?>',
						title: 'Leads by Source',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_guest_type',
						endpoint: '<?= base_url("hotelAdmin/Main/guest_type_chart_data") ?>',
						title: 'Leads by Guest Type',
						type: 'pie',
						startId: 'start_source',
						endId: 'end_source'
					},
					{
						id: 'chart_template_name',
						endpoint: '<?= base_url("hotelAdmin/Main/template_chart_data") ?>',
						title: 'Leads by Templates',
						type: 'column',
						startId: 'start_source',
						endId: 'end_source'
					}
				];

				function reloadAllCharts() {
					return chartConfigs.map(config => {
						return fetchAndRenderChart(config.endpoint, config.id, config.title, config.type);
					});
				}

				// Initial load
				reloadAllCharts();

				// Global filter button
				$('#filter_bottom_button').on('click', function(e) {
					e.preventDefault();

					if (!validateDateRange('#start_date_bottom', '#end_date_bottom')) {
						return;
					}

					const $button = $(this);
					$button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Filtering...');

					const requests = reloadAllCharts();
					let completedRequests = 0;

					requests.forEach(function(request) {
						request.always(function() {
							completedRequests++;

							if (completedRequests === requests.length) {
								$button.prop('disabled', false).text('Filter');
							}
						});
					});
				});
			});
		</script> -->


		<script>
			let topFilterRequest = null;
			let topFilterQueued = false;
			let topFilterTimer = null;

			function initializeDashboardSelects() {
				if (!$.fn.select2) {
					return;
				}

				$('#filter_lead_stats_count select, .quick-filter-box select').each(function() {
					const $select = $(this);

					if (!$select.hasClass('select2-hidden-accessible')) {
						$select.select2({
							width: '100%',
							minimumResultsForSearch: 0
						});
					}

					const $container = $select.next('.select2-container');
					const $selection = $container.find('.select2-selection--single');
					const $rendered = $selection.find('.select2-selection__rendered');
					const $arrow = $selection.find('.select2-selection__arrow');

					[$container, $selection].forEach(function($element) {
						if ($element.length) {
							$element[0].style.setProperty('width', '100%', 'important');
							$element[0].style.setProperty('height', '54px', 'important');
							$element[0].style.setProperty('min-height', '54px', 'important');
							$element[0].style.setProperty('border-radius', '10px', 'important');
						}
					});

					if ($selection.length) {
						$selection[0].style.setProperty('padding', '0 14px', 'important');
					}

					if ($rendered.length) {
						$rendered[0].style.setProperty();
						$rendered[0].style.setProperty();
						$rendered[0].style.setProperty();
						$rendered[0].style.setProperty();
					}

					if ($arrow.length) {
						$arrow[0].style.setProperty();
						$arrow[0].style.setProperty('top', '0', 'important');
					}
				});
			}

			function scheduleTopFilters() {
				window.clearTimeout(topFilterTimer);
				topFilterTimer = window.setTimeout(applyTopFilters, 250);
			}

			$(document).ready(function() {
				initializeDashboardSelects();

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
					.on('change', scheduleTopFilters);

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
				if (!validateDateRange('#top_filter_start_date', '#top_filter_end_date')) {
					return;
				}

				if (topFilterRequest && topFilterRequest.readyState !== 4) {
					topFilterQueued = true;
					return;
				}

				topFilterQueued = false;

				const createdUser = getUserFilterData('#top_filter_created_by');
				const assignedUser = getUserFilterData('#top_filter_assigned_to');


				const filters = {

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
				const previousTotalLeads = $('#totalLeads').text();
				let requestSucceeded = false;
				filters[window.CSRF.name] = window.CSRF.hash;

				topFilterRequest = $.ajax({
					type: 'POST',
					url: "<?= base_url('hotelAdmin/Main/dashboard_top_filter'); ?>",
					data: filters,
					dataType: 'json',

					beforeSend: function() {
						$('#totalLeads').html('...');
					},

					success: function(response) {
						requestSucceeded = true;

						if (response.csrfHash) {
							window.CSRF.hash = response.csrfHash;
						}

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

						$('#totalLeads').text(response.total_leads);
					},

					error: function(xhr) {
						$('#totalLeads').text(previousTotalLeads);
						console.error('Unable to apply dashboard filters.', xhr.status);
					},

					complete: function() {
						topFilterRequest = null;

						if (topFilterQueued && requestSucceeded) {
							topFilterQueued = false;
							scheduleTopFilters();
						}
					}
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