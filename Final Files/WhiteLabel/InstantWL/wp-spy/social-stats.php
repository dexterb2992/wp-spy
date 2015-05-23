<div class="wpspy-wrapper">
	<?php 
		$page = 'wpspy-social-stats';
		include "_nav.php"; 

	?>
	<div class="wpspy-content">
		<div class="wpspy-form">
			<iframe src="about:blank" id="remember" name="remember" class="hidden"></iframe>
			<form method="post" action="" id="form_wpspy" target="remember">
				<input	type="text" name="wpspy_url" id="wpspy_url" placeholder="www.example.com" value="<?php echo isset($_GET['url']) && trim($_GET['url']) != "" ? 'http://'.$_GET['url'] : ''; ?>" />
				<input type="submit" class="wpspy_btn" name="wpspy_submit" data-page="social-stats" id="wpspy_submit" value="Go" />
			</form>
		</div>
		<?php 
			if( isset($_GET['url']) && trim($_GET['url']) != "" ){
				$cached = checkDataStatus('social_stats', 'http://'.$_GET['url']);

				if( ($cached !== 'false') && ( isset($cached["score_sentiment"]) && 
				$cached["score_sentiment"] != '-') ){
					$socialstats = new stdClass();
					$socialmention = new stdClass();
					$socialstats->social_shares = new stdClass();

					foreach ($cached as $key => $value) {
						$socialstats->social_shares->$key = $value;
					}

					$socialmention->score_strength = $cached["score_strength"];
					$socialmention->score_sentiment = $cached["score_sentiment"];
					$socialmention->score_passion = $cached["score_passion"];
					$socialmention->score_reach = $cached["score_reach"];

				}else{
					$cached = "false";
					function getsm(){
						return json_decode(getSocialMention("http://".$_GET['url']));
						// return json_decode(@file_get_contents(WPSPY_HOST."data.php?q=get_social_mention&domain=http://".$_GET['url']."&format=json"));
					}

					// $socialstats = json_decode(@file_get_contents(WPSPY_HOST."data.php?q=get_social_stats&domain=http://".$_GET['url']."&format=json"));
					$socialstats = json_decode(getSociaLStats("http://".$_GET['url'], 'json'));

					$limit = 5;
					
					for($x = 0; $x < $limit; $x++){
						$socialmention = getsm();
						if( count($socialmention) < 1 ){
							$socialmention = getsm();
						}else{
							break;
						}
					}

					$data_array = array();
					if( isset($socialstats) ){
						foreach ($socialstats->social_shares as $key => $value) {
							$data_array[$key] = (string) $value;
						}
					}

					if( isset($socialmention) ){
						foreach ($socialmention as $key => $value) {
							$data_array[$key] = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
						}
					}

					save_this_activity('http://'.$_GET['url'], $data_array);
				}
			}
		?>
		<div class="wpspy-results row">
			<div class="col-3">
				<div class="box social-sns">
					<div class="title">Social (SNS)</div>
					<div class="content">

						<div class="entry facebook_count">
							<div class="left">
								<span class="icon-facebook icon"></span>Facebook shares
							</div>
							<div class="right">
								<span id="facebook_likes">
									<?php echo isset($socialstats->social_shares->facebook_count) ? $socialstats->social_shares->facebook_count : '';?>
								</span>
							</div>
						</div>

						<div class="entry google_count">
							<div class="left">
								<span class="icon-gplus icon"></span>Google Plus
							</div>
							<div class="right">
								<span id="gplus">
									<?php echo isset($socialstats->social_shares->google_count) ? $socialstats->social_shares->google_count : '';?>
								</span>
							</div>
						</div>

						<div class="entry stumbleupon_count">
							<div class="left">
								<span class="icon-stumbleupon icon"></span>StumbleUpon
							</div>
							<div class="right">
								<span id="stumbleupon">
									<?php echo isset($socialstats->social_shares->stumbleupon_count) ? $socialstats->social_shares->stumbleupon_count : '';?>
								</span>
							</div>
						</div>

						<div class="entry twitter_count">
							<div class="left">
								<span class="icon-twitter icon"></span>Twitter tweets
							</div>
							<div class="right">
								<span id="twitter">
									<?php echo isset($socialstats->social_shares->twitter_count) ? $socialstats->social_shares->twitter_count : '';?>
								</span>
							</div>
						</div>

						<div class="entry linkedin_count">
							<div class="left">
								<span class="icon-linkedin icon"></span>LinkedIn
							</div>
							<div class="right">
								<span id="linkedin">
									<?php echo isset($socialstats->social_shares->linkedin_count) ? $socialstats->social_shares->linkedin_count : '';?>
								</span>
							</div>
						</div>

						<div class="entry pinterest_count">
							<div class="left">
								<span class="icon-pinterest icon"></span>Pinterest
							</div>
							<div class="right">
								<span id="pinterest">
									<?php echo isset($socialstats->social_shares->pinterest_count) ? $socialstats->social_shares->pinterest_count : '';?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="box social-metrics">
					<div class="title">Social Metrics</div>
					<div class="content">
						<div class="entry score_strength">
							<div class="left">
								Strength
							</div>
							<div class="right">
								<span id="strength">
									<?php echo isset($socialmention->score_strength) ? $socialmention->score_strength : '';?>
								</span>
							</div>
						</div>

						<div class="entry score_sentiment">
							<div class="left">
								Sentiment
							</div>
							<div class="right">
								<span id="sentiment">
									<?php echo isset($socialmention->score_sentiment) ? $socialmention->score_sentiment : '';?>
								</span>
							</div>
						</div>

						<div class="entry score_passion">
							<div class="left">
								Passion
							</div>
							<div class="right">
								<span id="passion">
									<?php echo isset($socialmention->score_passion) ? $socialmention->score_passion : '';?>
								</span>
							</div>
						</div>

						<div class="entry score_reach">
							<div class="left">
								Reach
							</div>
							<div class="right">
								<span id="reach">
									<?php echo isset($socialmention->score_reach) ? $socialmention->score_reach : '';?>
								</span>
							</div>
						</div>

						<div class="entry mentions">
							<div class="left">
								Mentions
							</div>
							<div class="right">
								<a <?php echo isset($socialmention) ? 'href="http://socialmention.com/search?t=all&q='.urlencode('http://'.$_GET['url']).'&btnG=Search" class="icon icon-eye"' : 'href="#"';?> target="_blank" id="view_social_mentions"></a>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="box recommended-tools">
					<div class="title">Recommended tools</div>
					<div class="content">
						<div class="entry">
							<div class="left">
								<a href="#" target="_blank">Affiliate link 1</a>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<a href="#" target="_blank">Affiliate link 2</a>
							</div>
						</div>

						<div class="entry">
							<div class="left">
								<a href="#" target="_blank">Affiliate link N</a>
							</div>
						</div>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>