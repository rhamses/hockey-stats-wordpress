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