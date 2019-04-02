function filterPlayers(search, callback) {
	let players = localStorage.getItem('nhlstats_players');
	players = JSON.parse(players);

	let regex = new RegExp(search, 'gi');
	let select = players.filter(el => {
		let name = el.fullName.replace(' ','');
		if (el.fullName.match(regex)) {
			return el;
		}
	});

	callback(select);
}

function checkTransient(){
	jQuery(document).ready(function($) {
		const data = {
			_ajax_nonce: ajaxurl.nonce,
			action: 'get_players'
		};

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: data
		})
		.done(function(response) {
			if (response.status) {
				window.location.href = response.url;
			}
		})
		.fail(function(e,h) {
			console.log("error",e,h);
		});
	});
}

function searchPlayer(data) {
	jQuery(document).ready(function($) {
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: data
		})
		.done(function(response) {
			localStorage.setItem('nhlstats_players', JSON.stringify(response));
		})
		.fail(function(e,h) {
			console.log("error",e,h);
		});
	});
}

function playerDetails(data) {
	jQuery(document).ready(function($) {
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: data,
			beforeSend: function(){
				$(".inside").append('<div class="loader"></div>')
			}
		})
		.done(function(response) {
			const notification = `<div class="notice notice-success">
			<p>Copy and paste the following shortcode <code>[nhl-stats player="${data.id}"]</code> to render the same table below on your template</p>
			</div>`;
			$('#searchResults').removeClass('active');
			$(".player__stats--wrapper").children().remove();
			$(".player__stats--wrapper").append(notification + response['data']);
			$(".loader").remove();
		})
		.fail(function(e,h) {
			console.log("error",e,h);
		});
	});
}