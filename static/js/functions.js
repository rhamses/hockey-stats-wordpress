function filterPlayers(search, callback) {
	const league = sessionStorage.getItem('hockeystats_league');
	let players = localStorage.getItem(`${league}stats_players`);
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

function checkTransient(league){
	jQuery(document).ready(function($) {
		const data = {
			_ajax_nonce: ajaxurl.nonce,
			action: 'get_players'
		};

		if (league) {
			data.league = league;
		} else {
			data.loader = 'loader';
		}

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: data
		})
		.done(function(response) {
			if (response.status) {
				if (data.loader) {
					sessionStorage.setItem('hockeystats_league', response.leagues[0].league);
					for (let players of response.leagues) {
						localStorage.setItem(`${players.league}stats_players`, JSON.stringify(players.players));	
					}
					window.location.href = response.url;
				}
			} else {
				alert("PAM");
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

function playerDetails(data, league) {
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
			<p>Copy and paste the following shortcode <code>[nhl-stats player="${data.id}" league="${league}"]</code> to render the same table below on your template</p>
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