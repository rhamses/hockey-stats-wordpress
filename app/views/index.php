<div class="wrap">
	<h1>NHL Stats</h1>
	<p>Choose the tab below to get stats from <a href="https://nhl.com/stats">NHL.com</a> direct in form of a shortcode to be used wherever you want</p>
	<h2 class="nav-tab-wrapper">
	    <a href="#byplayer" class="nav-tab nav-tab-active">Statys By Player</a>
	    <a href="#byteam" class="nav-tab">By Team</a>
	</h2>

	<section id="byplayer">
		<fieldset>
			<legend class="screen-reader-text"><span>Fieldset Example</span></legend>
			<label for="users_can_register">
				<input name="" type="text" id="searchPlayer" value="" placeholder="Search for a player" />
				<span><?php esc_attr_e( 'Search for a player name', 'nhlstats' ); ?></span>
			</label>
		</fieldset>
		<div id="searchResults"></div>
		<div class="player__stats--wrapper"></div>
	</section>

	<!-- <section id="byteam">
		search for players
	</section> -->
</div>