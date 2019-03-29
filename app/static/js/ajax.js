jQuery(document).ready(function($) {
	$("#btnTeste").on("click", function(){
	 	const data = {
	    action: 'by_player'
	  };

	  $.ajax({
	  	url: ajaxurl,
	  	type: 'POST',
	  	dataType: 'json',
	  	data: data,
	  })
	  .done(function(response) {
	  	console.log("success", response);
	  })
	  .fail(function(e,h) {
	  	console.log("error",e,h);
	  });
	});

	$("#searchPlayer").on('keyup', function(){
		if (!localStorage.getItem('players')) {
		 	const data = {
		    action: 'by_player'
		  };

			$.ajax({
		  	url: ajaxurl,
		  	type: 'POST',
		  	dataType: 'json',
		  	data: data,
		  })
		  .done(function(response) {
		  	localStorage.setItem('players', JSON.stringify(response));
		  	filterPlayers();
		  })
		  .fail(function(e,h) {
		  	console.log("error",e,h);
		  });
		} else {
			filterPlayers($(this).val(), function(results){
				$('#searchResults').children('ul').remove();
				let line = '';
				for (player of results) {
					line += `<li><a class="player__item" href="#" data-id="${player.id}">${player.fullName}</a>`;
				}
				var html = `<ul>${line}</ul>`;
				$('#searchResults').append(html);
			});
		}
	});

	$("body").on('click', ".player__item", function(e){
		e.preventDefault();

		const data = {
	    action: 'player_stats',
	    id: $(this).data('id')
	  };

		$.ajax({
	  	url: ajaxurl,
	  	type: 'POST',
	  	dataType: 'json',
	  	data: data,
	  })
	  .done(function(response) {
	  	const html = playerPreview(response);
	  	$(".player__stats--wrapper").children().remove();
	  	$(".player__stats--wrapper").append(html);
	  })
	  .fail(function(e,h) {
	  	console.log("error",e,h);
	  });
	});
});