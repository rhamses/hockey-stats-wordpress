function filterPlayers(search, callback) {
	if (localStorage.getItem('players')) {
		let players = localStorage.getItem('players');
		players = JSON.parse(players);

		let regex = new RegExp(search, 'gi');
		let select = players.filter(el => {
			if (el.fullName.search(regex) === 0) {
				return el;
			}
		});

		callback(select);
	}
}

function renderPlayerTable(data) {
	let headers = '';
	let cells = '';

	if (data.stats) {
		for (let key in data.stats) {
			headers += `<th>${key}</th>`;
			if (data.stats.hasOwnProperty(key)) {
				cells += `<td>${data.stats[key]}</td>`;
			}
		}
	}

	return `<h2 class="player__title">#${data.infos.primaryNumber} ${data.infos.fullName}</h2>
	<span class="player__title--complementary">${data.infos.shootsCatches}</span>
	<table class="player__stats" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th rowspan="3"><figure class="player__image"><img alt="Headshot of ${data.infos.fullName}" src="${data.image}"></figure></th>
		${headers}
	</tr>
	<tr>${cells}</tr></table>`;
}

function playerPreview(data) {
	const playerTable = renderPlayerTable(data);
	const preview = `<div class="notice notice-info inline">
			<p>Applied the following shortcode <code>[nhl-stats player="${data.infos.id}"]</code> on your theme so the table below could be rendered on your site.</p>
		</div>`;

	return `${preview}${playerTable}`;
}