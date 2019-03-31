jQuery(document).ready(function($) {
	$("#searchPlayer").on('keyup', function(){
		let inputVal = $(this).val();
		if (!localStorage.getItem('nhlstats_players')) {
		 	const data = {
		    action: 'by_player'
		  };

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
		} else {
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
		}
	}).on('blur', function(){
		$('#searchResults').removeClass('active');
	});

	$("body").on('click', ".player__item", function(e){
		e.preventDefault();

		const playerID = $(this).data('id');

		const data = {
	    action: 'player_stats',
	    id: playerID
	  };

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
				<p>Copy and paste the following shortcode <code>[nhl-stats player="${playerID}"]</code> to render the same table below on your template</p>
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
});