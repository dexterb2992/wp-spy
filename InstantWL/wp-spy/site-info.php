<div class="wpspy-wrapper">
	<?php 
		$page = 'wpspy-site-info';
		include "_nav.php"; 
	?>
	<div class="wpspy-content">
		<div class="wpspy-form">
			<iframe src="about:blank" id="remember" name="remember" class="hidden"></iframe>
			<form method="post" action="" id="form_wpspy" target="remember">
				<input	type="text" name="wpspy_url" id="wpspy_url" placeholder="www.example.com" value="<?php echo isset($_GET['url']) ? 'http://'.$_GET['url'] : ''; ?>"/>
				<input type="submit" class="wpspy_btn" name="wpspy_submit" data-page="site-info" id="wpspy_submit" value="Go" />
			</form>
		</div>
		<?php 
			if( isset($_GET['url']) ){
				$cached = checkDataStatus('site_info', 'http://'.$_GET['url']);
				
				if( ($cached !== 'false') && ( isset($cached['ip']) && $cached["ip"] != "N/A" ) ){

					$onsite = new stdClass();
					$onsite->robot = $cached['robot'];
					$onsite->sitemap_index = $cached['sitemap_index'];

					$whois = new stdClass();
					$whois->geolocation = new stdClass();
					$whois->geolocation->ip = $cached['ip'];
					$whois->geolocation->city = $cached['city'];
					$whois->geolocation->country = $cached['country'];
					$whois->geolocation->country_code = $cached['country_code'];
					$whois->dns = json_decode($cached["dns"]);

					$wordpress_data = json_decode($cached['wordpress_data']);
				}else{
					
					$onsite = json_decode(getOnSite("http://".$_GET['url'], 'json'));
					$whois = json_decode(getWhOIS("http://".$_GET['url'], 'json'));
					$wordpress_data = json_decode(getWordpressData("http://".$_GET['url'], 'json'));

					$data_array = array();
				}
			}
		?>
		<div class="wpspy-results row">
			<div class="col-5">
				<div class="on-site box">
					<div class="title">On-site</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<span class="icon icon-bw"></span>BuiltWith
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="http://builtwith.com/'.$_GET['url'].'" class="icon icon-eye"' : '#' ?> target="_blank" id="builtwith"></a>
							</div>
						</div>
						<div class="entry">
							<div class="left">
								<span class="icon icon-robots"></span>robots.txt
							</div>
							<div class="right">
								<?php 
									if(isset($_GET['url'])){
										if( isset($onsite->robot) && $onsite->robot == 'true' ){
											$data_array["robot"] = $onsite->robot;
											echo '<span id="robots" class="icon-check icon"></span>';
										}else{
											echo isset($_GET['url']) ? '<span>N/A</span>' : '';
										}
									}else{
										echo '<span id="robots"></span>';
									}
								?>
							</div>
						</div>
						<div class="entry">
							<div class="left">
								<span class="icon icon-sitemap"></span>sitemap.xml
							</div>
							<div class="right">
								<?php 
									if(isset($_GET['url'])){
										if( isset($onsite->sitemap_index) && $onsite->sitemap_index == 'true' ){
											$data_array["sitemap_index"] = $onsite->sitemap_index;
											echo '<span id="sitemap" class="icon-check icon"></span>';
										}else{
											echo isset($_GET['url']) ? '<span>N/A</span>' : '';
										}
									}else{
										echo '<span id="sitemap"></span>';
									}
								?>
							</div>
						</div>
						<div class="entry">
							<div class="left">
								<span class="icon icon-sourcecode"></span>Sourcecode
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="view-source:http://'.$_GET['url'].'" class="icon icon-eye"' : '#' ?> id="source_code" target="_blank"></a>
							</div>
						</div>
					</div>
				</div>

				<div class="domain-info box">
					<div class="title">Domain Info</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<span class="icon icon-ipwhois"></span>WHOIS Lookup
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="http://who.is/whois/'.$_GET['url'].'" class="icon icon-eye"' : '' ?> target="_blank" id="whois_lookup"></a>
							</div>
						</div>
						<div class="dns">
							<?php 
							$x = 1;
								if( isset($whois->dns) ){
									$data_array["dns"] = json_encode($whois->dns);
									foreach ($whois->dns as $key => $value) {
										?>
										<div class="entry">
											<div class="left">
												<span class="icon icon-dns"></span>DNS <?php echo $x;?>
											</div>
											<div class="right">
												<span><?php echo $value;?></span>
											</div>
										</div>
										<?php
										$x++;
									}
								}
							?>
						</div>
					</div>
				</div>
				<div class="site-security box">
					<div class="title">Site Security</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<span class="icon icon-mcafee"></span>
								McAfee Site Advisor
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="http://www.siteadvisor.com/sites/http://'.$_GET['url'].'" class="icon icon-eye"' : 'href="#"'; ?> target="_blank" id="mcafee"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<span class="icon icon-norton"></span>
								Norton Safe Web
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="http://safeweb.norton.com/report/show?url=http://'.$_GET['url'].'" class="icon icon-eye"' : 'href="#"'; ?> target="_blank" id="norton"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<span class="icon icon-wot"></span>
								WOT
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="https://www.mywot.com/en/scorecard/http://'.$_GET['url'].'" class="icon icon-eye"' : 'href="#"'; ?> target="_blank" id="wot"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<span class="icon icon-wot"></span>
								Sucuri
							</div>
							<div class="right">
								<a <?php echo isset($_GET['url']) ? 'href="http://sitecheck.sucuri.net/results/http://'.$_GET['url'].'" class="icon icon-eye"' : 'href="#"'; ?> target="_blank" id="sucuri"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-5">
				<div class="geolocation box">
					<div class="title">Geolocation</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<span class="icon icon-ip"></span>IP
							</div>
							<div class="right">
								<span id="ip">
									<?php
										if(	isset($whois->geolocation) ){
											$data_array["ip"] = $whois->geolocation->ip;
											$data_array["city"] = $whois->geolocation->city;
											$data_array["country_code"] = $whois->geolocation->country_code;
											$data_array["country"] = $whois->geolocation->country;
										} 
										echo isset($whois->geolocation->ip) ? $whois->geolocation->ip : ''; 
									?>
								</span>
							</div>
						</div>
						<div class="entry">
							<div class="left">
								<span class="icon icon-city"></span>City
							</div>
							<div class="right">
								<span id="city">
									<?php echo isset($whois->geolocation->city) ? $whois->geolocation->city : ''; ?>
								</span>
							</div>
						</div>
						<div class="entry">
							<div class="left">
								<span class="icon icon-country"></span>Country
							</div>
							<div class="right">
								<span id="country">
									<?php echo isset($whois->geolocation->country) ? 
											'<span class="flag flag-'.$whois->geolocation->country_code.'"></span>
											<span>'.$whois->geolocation->country.'</span>' : ''; 
									?>
								</span>
							</div>
						</div>
					</div>
				</div>

				<div class="wordpress-data box">
					<div class="title">Wordpress Data</div>
					<div class="content">

						<div class="entry">
							<div class="left">
								Wordpress Version
							</div>
							<div class="right">
								<span id="wordpress_version">
									<?php 
										isset($wordpress_data) ? $data_array["wordpress_data"] = json_encode($wordpress_data) : '';
										echo (isset($wordpress_data) && $wordpress_data->version != 0) ? $wordpress_data->version : 'N/A'; 
									?>
								</span>
							</div>
						</div>
						<div class="plugins">
							<?php 
								if( isset($wordpress_data->free_plugins) && $wordpress_data->free_plugins != 0 ){
									foreach ($wordpress_data->free_plugins as $key) {
										?>
										<div class="entry">
											<div class="left"><?php echo cleanStr($key->name); ?></div>
											<div class="right">
												<?php 
												echo isset($key->download) ? '<a href="'.$key->download.'" class="icon icon-download"></a>' : '';
												echo isset($key->link) ? '<a href="'.$key->link.'" target="_blank" class="icon icon-eye"></a>' : '';
												?>
												<span class="icon icon-plugin"></span>
											</div>
										</div>
										<?php
									}
								}

								if( isset($wordpress_data->commercial_plugins)  && $wordpress_data->commercial_plugins != 0 ){
									foreach ($wordpress_data->commercial_plugins as $key) {
										?>
										<div class="entry">
											<div class="left"><?php echo cleanStr($key->name); ?></div>
											<div class="right">
												<?php 
												echo ($key->download != "N/A") ? '<a href="'.$key->download.'" class="icon icon-download"></a>' : '';
												echo ($key->link != "N/A") ? '<a href="'.$key->link.'" target="_blank" class="icon icon-eye"></a>' : '<a href="http://google.com/search?q=wordpress%20'.$key->name.'" target="_blank" class="icon icon-eye"></a>';
												?>
												<span class="icon icon-plugin"></span>
											</div>
										</div>
										<?php
									}
								}
							?>
						</div>
						<div class="theme">
							<?php 
								if( isset($wordpress_data->theme) ){
									?>
									<div class="entry">
										<div class="left"><?php echo cleanStr($wordpress_data->theme->name); ?></div>
										<div class="right">
											<?php 
												if( $wordpress_data->theme->name != null && $wordpress_data->theme->name != '' ){
													echo ($wordpress_data->theme->download == "N/A") ? '' : '<a href="'.$wordpress_data->theme->download.'" class="icon icon-download"></a>';
													echo ($wordpress_data->theme->link != "N/A") ? '<a href="'.$wordpress_data->theme->link.'" target="_blank" class="icon icon-eye"></a>' : '<a href="http://google.com/search?q=wordpress%20'.$wordpress_data->theme->name.'" target="_blank" class="icon icon-eye"></a>'; 
													echo '<span class="icon icon-theme"></span>';
												}
											?>
										</div>
									</div>
									<?php
								}

								// Save to database
								if( isset($cached) && $cached == 'false' ){
									$status = save_this_activity("http://".$_GET['url'], $data_array);
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>