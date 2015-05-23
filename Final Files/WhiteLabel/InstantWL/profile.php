<?php include_once('classes/profile.class.php');?>
<?php include_once('header.php');?>

<h1>

	<a href="http://gravatar.com/emails/" class="a-tooltip" data-rel="tooltip-bottom" title="<?php _e('Change your avatar at Gravatar.com'); ?>">
		<img class="gravatar thumbnail" src="<?php echo $profile->get_gravatar($profile->getField('email'), false, 54); ?>"/>
	</a>

	<?php echo $profile->getField('username') . ' (' . $profile->getField('name') . ')'; ?>

</h1>

<br>

<div class="tabs-left">

	<ul class="nav nav-tabs">

		<?php if ( !$profile->guest ) : ?>
			<li class="active"><a href="#usr-control" data-toggle="tab"><i class="icon-cog"></i> <?php _e('General'); ?></a></li>
		<?php endif; ?>

		<?php $profile->generateProfileTabs($profile->guest); ?>
		<?php if (!$profile->guest && !$profile->denyAccessLogs()) : ?>
		<li><a href="#usr-access-logs" data-toggle="tab"><i class="icon-list-alt"></i> <?php _e('Access logs'); ?></a></li>
		<?php endif; ?>

		<?php if ( !$profile->guest && !empty( $jigowatt_integration->enabledMethods ) ) : ?>
		<li><a href="#usr-integration" data-toggle="tab"><i class="icon-random"></i> <?php _e('Integration'); ?></a></li>
		<?php endif; ?>

	</ul>

	<form class="form-horizontal" method="post" action="profile.php">
	<div class="tab-content">

		<?php if ( !$profile->guest ) : ?>
		<div class="tab-pane fade in active" id="usr-control">
			<fieldset>
				<legend><?php _e('General'); ?></legend>
				<div class="control-group">
					<label class="control-label" for="CurrentPass"><?php _e('Current password'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="input-xlarge" id="CurrentPass" name="CurrentPass">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="name"><?php _e('Name'); ?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="name" name="name" value="<?php echo $profile->getField('name'); ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email"><?php _e('Email'); ?></label>
					<div class="controls">
						<input type="email" class="input-xlarge" id="email" name="email" value="<?php echo $profile->getField('email'); ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password"><?php _e('New password'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="input-xlarge" id="password" name="password" placeholder="<?php _e('Leave blank for no change'); ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="confirm"><?php _e('New password again'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="input-xlarge" id="confirm" name="confirm">
					</div>
				</div>

				<?php if ( $profile->getOption('profile-public-enable') ) : ?>
				<div class="control-group">
					<label class="control-label" for="confirm"><?php _e('Your public link'); ?></label>
					<div class="controls">
						<span class="uneditable-input"><?php echo SITE_PATH . 'profile.php?uid=' . $profile->getField('user_id'); ?></span>
					</div>
				</div>
				<?php endif; ?>

			</fieldset>
		</div>
		<?php endif; ?>

		<?php $profile->generateProfilePanels($profile->guest); ?>

		<?php if (!$profile->guest && !$profile->denyAccessLogs()) : ?>
		<div class="tab-pane fade" id="usr-access-logs">
			<fieldset>
				<legend><?php _e('Access Logs'); ?></legend>
				<?php $profile->generateAccessLogs(); ?>
			</fieldset>
		</div>
		<?php endif; ?>

		<?php if ( !$profile->guest ) : ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php _e('Save changes'); ?></button>
		</div>
		<?php endif; ?>

	</div>
	</form>
</div>

<?php include ('footer.php'); ?>