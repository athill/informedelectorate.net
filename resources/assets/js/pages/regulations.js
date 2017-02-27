
import { LOADING_ICON, NETWORK_FAILURE_ALERT, formatDate, getParameterByName, getTableObject } from '../utils';

$(() => {
	const $results = $('#results');
	const $form = $('#filter');

	fetch('/api/regulationoptions')
	.then(response => response.json())
	.then(json => {
		json.forEach(field => {
			console.log(field);
			// statemap[state.abbreviation] = state.name;
			// $stateDropdown.append($(`<option value="${state.abbreviation}">${state.name}</option>`));
		});
		// $stateDropdown.val(state);
	})
	.catch(error => {
		$results.append(NETWORK_FAILURE_ALERT);
		console.error('Error in regulations', error)
	});	
});