<div class="wrap">
	<h1>NHL Stats - Settings</h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<form method="POST">
				<fieldset>
					<label for="metricSystem">Metric System:
						<input name="metricSystem" type="radio" value="I" <?php echo (get_option('nhlstats_metricsystem') === "I") ? 'checked': '' ?> >Imperial
						<input name="metricSystem" type="radio" value="M"<?php echo (get_option('nhlstats_metricsystem') === "M") ? 'checked': '' ?>>Metric
					</label>
				</fieldset>
				<?php submit_button( 'Save Settings' ); ?>
			</form>
		</div>
	</div>
</div>