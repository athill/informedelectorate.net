import { getParameterByName } from '../utils';

$(() => {
	const $results = $('#results');

	$('#address-form').submit(e => {
		e.preventDefault();
		var value = $('#addr').val();
		if (value.trim() !== '') {
			$results.html(`Loading results for ${value} <i class="fa fa-cog fa-spin fa-2x fa-fw"></i>`);
			fetch('/api/reps?addr='+encodeURIComponent(value))
				.then(response => response.json())
				.then(json => {
					$results.html(`<p>Displaying results for ${value}</p>`);
					if (json.error) {
						$results.append(`<div class="alert alert-warning" role="alert">${json.error}</div>`);
					} else {
						$results.append(renderReps(json));
					}
				})
				.catch(error => {
					console.log('error', error);
					$results.append('<div class="alert alert-dnager" role="alert">Something went wrong, please try again later.</div>');
				});
		}
	});

	const query = getParameterByName('q');
	if (query) {
		$('#addr').val(query);
		$('#address-form').submit();
	}	

	const getReps = results => {
		console.log('in getReps');
		fetch(`/api/reps?lat=${results.geometry.location.lat}&long=${results.geometry.location.lng}`)
			.then(response => response.json())
			.then(json => renderReps(json))
			.catch(error => console.log('error', error));
	};

	const renderReps = ({ fed, state }) => {
		const $response = $('<div />');
		$response.append('<h2>Your federal representatives:</h2>');
		fed.forEach(items => {
			for (let item in items) {
				const $row = $('<div class="row" />');
				$row.append('<div class="col-md-2">'+item+'</div>');
				const value = /^https?:\/\/.*/.test(items[item]) ? `<a href="${items[item]}" target="_blank">${items[item]}<a>` : items[item];
				$row.append('<div class="col-md-10">'+value+'</div>');
				$response.append($row);
			}
			$response.append('<hr />');
		});
		$response.append('<h2>Your state representatives:</h2>');
		state.forEach(items => {
			for (let item in items) {
				const $row = $('<div class="row" />');
				$row.append('<div class="col-md-2">'+item+'</div>');
				const value = /^https?:\/\/.*/.test(items[item]) ? `<a href="${items[item]}" target="_blank">${items[item]}<a>` : items[item];
				$row.append('<div class="col-md-10">'+value+'</div>');
				$response.append($row);
			}
			$response.append('<hr />');
		});		
		return $response;
	}
});