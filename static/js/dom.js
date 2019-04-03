jQuery(document).ready(function($) {
	$("#searchPlayer").on('keyup', function(){
		let inputVal = $(this).val();
		filterPlayers(inputVal, function(results){
			// Logic
			let line = '';
			for (let player of results) {
				line += `<li><a class="player__item" href="#" data-id="${player.id}">${player.fullName}</a>`;
			}
			// Interface
			const html = `<ul>${line}</ul>`;
			$('#searchResults').children('ul').remove();
			$('#searchResults').addClass('active').append(html);
		});
	}).on('blur', function(){
		$('#searchResults').removeClass('active');
	});

	$("body").on('click', ".player__item", function(e){
		e.preventDefault();

		const playerID = $(this).data('id');
		const league = sessionStorage.getItem('hockeystats_league');

		const data = {
			_ajax_nonce: ajaxurl.nonce,
	    action: 'player_stats',
	    id: playerID,
	    league: league
	  };

	  playerDetails(data, league);
	});

	$('input[name="league"]').on('click', function(){
		const league = $(this).val();
		sessionStorage.setItem('hockeystats_league', league);
	});
});