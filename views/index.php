<div class="wrap">
	<h1>NHL Stats</h1>
	<p>Choose the tab below to get stats from <a href="https://nhl.com/stats">NHL.com</a> direct in form of a shortcode to be used wherever you want</p>
	<h2 class="nav-tab-wrapper">
		<a href="#byplayer" class="nav-tab nav-tab-active">Statys By Player</a>
	</h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<section id="byplayer">
				<div class="meta-box-sortables ui-sortable searchbox--wrapper">
					<div class="postbox">
						<h2><span>Search for a player name to get his stats</span></h2>
						<div class="inside">
							<fieldset>
								<legend class="screen-reader-text"><span>Search For a Player</span></legend>
								<div class="radiobutton">
								<label for="nhl">
									<input type="radio" id="nhl" name="league" value="nhl" checked>
									NHL
								</label>
								<label for="cwhl">
									<input type="radio" id="cwhl" name="league" value="cwhl">
									CWHL
								</label>
								</div>
								<label for="search_for_player">
									<input name="search_for_player" type="search" id="searchPlayer" value="" placeholder="Search for a player name" />
								</label>
							</fieldset>
							<div id="searchResults" class="search--results"></div>
						</div>
					</div>
				</div>
				<div class="player__stats--wrapper"></div>
			</section>
		</div>
	</div>
</div>