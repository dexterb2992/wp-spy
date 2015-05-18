<div class="wpspy-wrapper">
	<?php 
		$page = 'wpspy-links';
		include "_nav.php"; 

	?>
	<div class="wpspy-content">
		<div class="wpspy-form">
			<iframe src="about:blank" id="remember" name="remember" class="hidden"></iframe>
			<form method="post" action="" id="form_wpspy" target="remember">
				<input	type="text" name="url" id="wpspy_url" placeholder="www.example.com" title="Please avoid using https" value="<?php echo isset($_GET['url']) ? 'http://'.$_GET['url'] : ''; ?>" />
				<input type="submit" class="wpspy_btn" name="wpspy_submit" data-page="links" id="wpspy_submit" value="Go" />
			</form>
		</div>
		<?php
			if( isset($_GET['url']) ){
				$_url = 'http://'.$_GET['url'];
				
				$links = getLinks($_url);
				$data_array = array();
			}
		?>
		<div class="wpspy-results row">
			<div class="col-7">
				<div class="box">
					<table class="table tbl-links">
						<thead>
							<tr>
								<th>#</th>
								<th>URL</th>
								<th>Anchor Text</th>
							</tr>
						</thead>
						<tbody>
							<tr class="external-links-outer-row">
								<td colspan="3">
									<strong>
										External links: 
										<span id="external_links_count">
											<?php echo isset($links["external_links"]["links"]) ? count($links["external_links"]["links"]) : '';   ?>	
										</span>
										(<span id="external_nofollow_count">
											<?php echo isset($links["external_links"]["links"]) ? $links["external_links"]["nofollow"] : '0';   ?>
										</span> nofollow)
									</strong>
								</td>
							</tr>
							<?php  
								$data_array["external_links"] = json_encode(
									array(
										"links" => isset($links["external_links"]["links"]) ? count($links["external_links"]["links"]) : '0',
										"nofollow" => isset($links["external_links"]["links"]) ? $links["external_links"]["nofollow"] : '0'
									)
								);

								$data_array["internal_links"] = json_encode(
									array(
										"links" => isset($links["internal_links"]["links"]) ? count($links["internal_links"]["links"]) : '0',
										"nofollow" => isset($links["internal_links"]["links"]) ? $links["internal_links"]["nofollow"] : '0'
									)
								);

								$status = save_this_activity("http://".$_GET['url'], $data_array);

								if( isset($links["external_links"]["links"]) ){

									$x = 1;
									foreach ($links["external_links"]["links"] as $link) {
										echo '
											<tr class="url external-link">
												<td>'.$x.':</td>
												<td>
													<a href="'.$link["url"].'" target="_blank">'.$link["url"].'</a>
												</td>
												<td><div class="anchor-text">'.$link["text"].'</div></td>
											</tr>
										';
										$x++;
									}
								}
							?>
							<tr>
								<td colspan="3"></td>
							</tr>
							<tr class="internal-links-outer-row">
								<td colspan="3">
									<strong>
										Internal links: 
										<span id="internal_links_count">
											<?php echo isset($links["internal_links"]["links"]) ? count($links["internal_links"]["links"]) : '';   ?>
										</span>
										(<span id="internal_nofollow_count">
											<?php echo isset($links["internal_links"]["links"]) ? $links["internal_links"]["nofollow"] : '0';   ?>
										</span> nofollow)
									</strong>
								</td>
							</tr>
							<?php 
								if( isset($links["internal_links"]["links"]) ){
									$x = 1;
									foreach ($links["internal_links"]["links"] as $link) {
										echo '
											<tr class="url internal-links">
												<td>'.$x.':</td>
												<td>
													<a href="'.$link["url"].'" target="_blank">'.$link["url"].'</a>
												</td>
												<td><div class="anchor-text">'.$link["text"].'</div></td>
											</tr>
										';
										$x++;
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-2">
				<div class="search-engine-results box">
					<div class="title">Search engine Results</div>
					<div class="content">
						<div class="entry">
							<div class="left">Google</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://www.google.com/search?q=site%3A+'.urlencode($_GET['url']) : '#'; ?>" data-link="http://www.google.com/search?q=site%3A+" id="google_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">Bing</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://www.bing.com/search?q=site%3A+'.urlencode($_GET['url']).'&go=Submit' : '#'; ?>" data-link="http://www.bing.com/search?q=site%3A+" id="bing_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">Yahoo</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://search.yahoo.com/?p=site:%20+'.urlencode($_GET['url']).'&go=Submit' : '#'; ?>" data-link="http://search.yahoo.com/?p=site:%20+" id="yahoo_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">Ask</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://www.ask.com/web?q='.urlencode($_GET['url']).'&qsrc=0&o=0&l=dir&qo=homepageSearchBox' : '#'; ?>" data-link="http://www.ask.com/web?q="  id="ask_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">Aol Search</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://search.aol.com/aol/search?s_chn=prt_aol20&v_t=comsearch&q='.urlencode($_GET['url']).'&s_it=topsearchbox.search' : '#';?>" data-link="http://search.aol.com/aol/search?s_chn=prt_aol20&v_t=comsearch&q=" id="aol_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">DuckDuckGo</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'https://duckduckgo.com/?q='.urlencode($_GET['url']) : '#'; ?>" data-link="https://duckduckgo.com/?q=" id="duckduckgo_search_results"></a>
							</div>
						</div>

						<div class="entry">
							<div class="left">Blekko</div>
							<div class="right">	
								<a class="<?php echo isset($_url) ? 'icon-eye icon' : ''; ?>" target="_blank" href="<?php echo isset($_url) ? 'http://blekko.com/#ws/?q='.urlencode($_GET['url']) : '#'; ?>" data-link="http://blekko.com/#ws/?q=" id="blekko_search_results"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
					// TEMPORARY LIMIT 10
						$tmp = getRecommendedToolsLimit();
				?>
				<div id="settings_dialog" class="hidden" title="Recommended tools limit">
					<iframe src="about:blank" class="hidden" id="remember" name="remember"></iframe>
					<form id="rtl_settings" method="post" action="" target="remember">
						<input type="text" data-type="number" required="required" id="settings" value="<?php echo ( $tmp < 1) ? 10 : $tmp;?>" placeholder="Set limit">
						<button type="submit" name="save_settings" id="save_settings" class="wpspy_btn">Save</button>
					</form>
				</div>
				<div class="box recommended-tools">
					<div class="title">Recommended tools<span class="pull-right settings" id="recommended_tools_settings">Setting</span></div>
					<div class="content">
						<?php 
						
						$limit = ( $tmp < 1) ? 10 : $tmp;
						$count = count($links["external_links"]["links"]);
							if( isset($links["external_links"]["links"]) ){
								if( $count > 0 ){
									$random = array_rand($links["external_links"]["links"], $limit);
									for ($x = 0; $x < $limit; $x++) {

										if( $count >= $x ){
											echo '
												<div class="entry">
													<div class="left">
														<a class="anchor-text" href="'.$links["external_links"]["links"][ $random[$x] ]["url"].'" target="_blank">'.$links["external_links"]["links"][ $random[$x] ]["url"].'</a>
													</div>
												</div>
											';
										}
									}
								}
							}else{
								echo '<span>No data available</span>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>