<?php include_once('settings.php'); ?>

<fieldset>
	<legend><?php _e('Captcha signup'); ?></legend><br>
	<?php $selectedCaptcha = $settings->getOption('integration-captcha'); ?>

	<p><?php _e('Require human verification on the registration form.'); ?></p>

	<div class="control-group">
		<label class="control-label" for="integration-disableCaptcha-enable"><?php _e('Disable captcha'); ?></label>
		<div class="controls">


			<label class="radio">
				<input type="radio" class="input-xlarge collapsed" id="integration-disableCaptcha-enable" name="integration-captcha" value="disableCaptcha" <?php if ($selectedCaptcha == 'disableCaptcha') echo 'checked="checked"'; ?>>
			</label>

		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="integration-reCAPTCHA-enable"><a href="http://www.google.com/recaptcha"><?php _e('reCAPTCHA'); ?></a></label>
		<div class="controls">

			<label class="radio">
				<input type="radio" class="input-xlarge collapsed" id="integration-reCAPTCHA-enable" name="integration-captcha" value="reCAPTCHA" <?php if ($selectedCaptcha == 'reCAPTCHA') echo 'checked="checked"'; ?>>
				<?php _e('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="input-xlarge" id="reCAPTCHA-public-key" name="reCAPTCHA-public-key" value="<?php echo $settings->getOption('reCAPTCHA-public-key'); ?>">
				<p class="help-inline"><?php _e('Public key'); ?></p>
			</label>

			<label>
				<input type="text" class="input-xlarge" id="reCAPTCHA-private-key" name="reCAPTCHA-private-key" value="<?php echo $settings->getOption('reCAPTCHA-private-key'); ?>">
				<p class="help-inline"><?php _e('Private key'); ?></p>
			</label>

			<p><?php echo sprintf(_('You must first <a href="%s">create a reCAPTCHA key</a>.'), 'http://www.google.com/recaptcha/whyrecaptcha'); ?></p>

			</div>

		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="integration-playThru-enable"><a href="http://areyouahuman.com/?utm_source=Jigowatt&utm_medium=Jigowatt&utm_campaign=Jigowatt"><?php _e('PlayThru'); ?></a></label>
		<div class="controls">

			<label class="radio">
				<input type="radio" class="input-xlarge collapsed" id="integration-playThru-enable" name="integration-captcha" value="playThru" <?php if ($selectedCaptcha == 'playThru') echo 'checked="checked"'; ?>>
				<?php _e('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="input-xlarge" id="playThru-publisher-key" name="playThru-publisher-key" value="<?php echo $settings->getOption('playThru-publisher-key'); ?>">
				<p class="help-inline"><?php _e('Publisher key'); ?></p>
			</label>

			<label>
				<input type="text" class="input-xlarge" id="playThru-scoring-key" name="playThru-scoring-key" value="<?php echo $settings->getOption('playThru-scoring-key'); ?>">
				<p class="help-inline"><?php _e('Scoring key'); ?></p>
			</label>

			<p><?php echo sprintf(_('You must first <a href="%s">signup to get a site key</a>.'), 'http://portal.areyouahuman.com/signup?utm_source=Jigowatt&utm_medium=Jigowatt&utm_campaign=Jigowatt'); ?></p>

			</div>

		</div>
	</div>

	<input type="hidden" name="integration-form" value="1">
</fieldset>