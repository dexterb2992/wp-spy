<div class="wpspy-wrapper">
	<?php 
		$page = 'wpspy-traffic';
		include "_nav.php"; 
	?>
	<div class="wpspy-content">
		<div class="wpspy-form">
			<?php 
				if( isset( $_GET['url'] ) ){
					$site_metrics = get_site_metrics('http://'.$_GET['url']);
					pre($site_metrics);
					$site_metrics = $site_metrics[0];
				}
			?>
			<iframe src="about:blank" id="remember" name="remember" class="hidden"></iframe>
			<form method="post" action="" id="form_wpspy" target="remember">
				<input	type="text" name="wpspy_url" id="wpspy_url" placeholder="www.example.com" value="<?php echo isset($_GET['url']) ? 'http://'.$_GET['url'] : ''; ?>"/>
				<input type="submit" class="wpspy_btn" name="wpspy_submit" data-page="traffic" id="wpspy_submit" value="Go" />
			</form>
		</div>
		<div class="wpspy-results row">
			<div class="col-7">
				<div class="traffic-graphs box">
					<div class="title">Traffic graphs</div>
					<div class="content">
						<div class="entry">	
							<div class="center">
								<div>
									<select id="choose_traffic_graph">
										<option value="daily_pageview">Alexa - Daily Pageview per Visitor</option>
										<option value="daily_reach">Alexa - Daily Reach (percent)</option>
										<option value="traffic_trend2y">Alexa - Traffic Rank Trend (2 Years)</option>
										<option value="traffic_trend6m">Alexa - Traffic Rank Trend (6 Months)</option>
										<option value="search_visits">Alexa - Search Visits (percent)</option>
									</select>
								</div>
								<div id="canvas-holder">
									<div id="traffic-graph-area">
										<img id="traffic_graph" src="http://traffic.alexa.com/graph?w=570&h=220&o=f&c=1&y=p&b=ffffff&;r=6m&u=<?php echo isset($_GET['url']) ? $_GET['url'] : '';?>">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="box traffic">
					<div class="title">Traffic</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<span class="icon-alexa icon"></span>Alexa Traffic Rank
							</div>
							<div class="right">
								<span id="alexa_rank">
									<?php 
										echo isset($site_metrics["alexa_rank"]) && $site_metrics["alexa_rank"] != 0 ? $site_metrics["alexa_rank"] : "N/A"; 
									?>
								</span>
							</div>
						</div>

						<div class="entry">
							<div class="">
								<div>
									<span class="icon-alexa icon"></span>Alexa Traffic Rank in Country
								</div>
								<span id="alexa_rank_in_country">
									<?php 
										$alexa_rank_in_country = json_decode(stripslashes($site_metrics["alexa_rank_in_country"]));
										pre($alexa_rank_in_country);
										echo '<table class="rank-in-country">
											<thead>
												<tr>
													<th colspan="2">Country</th>
													<th>Percent of Visitors</th>
													<th>Rank in Country</th>
												</tr>
											</thead>
											<tbody>
										';
										if( is_array($alexa_rank_in_country) || is_object($alexa_rank_in_country) ){
											if(count($alexa_rank_in_country) > 1){
												foreach ($alexa_rank_in_country as $key) {
													echo '<tr>
														<td>
															<span class="flag flag-'.$key->country_code.'"></span>
														</td>
														<td>
															<span>'.$key->country.'</span>
														</td>
														<td>'.$key->percent_of_visitors.'</td>
														<td>'.$key->rank.'</td>
													</tr>';
												}
											}
										}
										echo '</tbody></table>';
									?>
								</span>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<span class="icon-quantcast icon"></span>Quantcast Traffic Rank
							</div>
							<div class="right">
								<span id="quantcast_traffic_rank">
									<?php 
										if(isset($site_metrics["quantcast_traffic_rank"])){
											if($site_metrics["quantcast_traffic_rank"] == 0){
												echo get_quantcast_rank("http://".$_GET['url']);
											}else{
												echo $site_metrics["quantcast_traffic_rank"];
											}
										}else{
											echo "N/A";
										}
									?>
									<?php
										// echo isset($site_metrics["quantcast_traffic_rank"]) ? $site_metrics["quantcast_traffic_rank"] : "N/A"; 
									?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="col-4">
				<div class="box site-metrics">
					<div class="title">Site Metrics</div>
					<div class="content">
						<div class="entry">
							<div class="left">Bounce Rate</div>
							<div class="right">
								<span id="bounce_rate">
									<?php echo isset($site_metrics["bounce_rate"]) ? $site_metrics["bounce_rate"] : '';  ?>
								</span>
							</div>
						</div>

						<div class="entry">
							<div class="left">Daily Pageviews per Visitor</div>
							<div class="right">
								<span id="daily_pageviews_per_visitor">
									<?php echo isset($site_metrics["daily_pageviews_per_visitor"]) ? $site_metrics["daily_pageviews_per_visitor"] : '';  ?>
								</span>
							</div>
						</div>

						<div class="entry">
							<div class="left">Daily Time on Site</div>
							<div class="right">
								<span id="dailytime_onsite">
									<?php echo isset($site_metrics["dailytime_onsite"]) ? $site_metrics["dailytime_onsite"] : '';  ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>