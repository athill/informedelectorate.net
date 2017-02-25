import { LOADING_ICON, NETWORK_FAILURE_ALERT, formatDate, getParameterByName, getTableObject } from '../utils';

$(() => {
	const $results = $('#results');
	const $form = $('#statebills-form');
	const state = getParameterByName('state');

	const $stateDropdown = $('#state');
	const statemap = {};

	fetch('/api/statebills')
		.then(response => response.json())
		.then(json => {
			json.forEach(state => {
				statemap[state.abbreviation] = state.name;
				$stateDropdown.append($(`<option value="${state.abbreviation}">${state.name}</option>`));
			});
			$stateDropdown.val(state);
		})
		.catch(error => console.error('Error in floorupdates', error));	

	const getAndDisplayState = state => {
		$results.html(`Loading results for ${statemap[state]} ${LOADING_ICON}`);
		fetch(`/api/statebills/${state}`)
			.then(response => response.json())
			.then(json => {
				$results.html(`<p>Results for <a href="?state=${state}">${statemap[state]}</a>:</p>`);
				const headers = ['Bill', 'Created', 'Updated', 'Type', 'Subjects'];
				const { $tbody, $table } = getTableObject(headers);
				$results.append($table);				
				json.forEach(bill => {
					const url = `http://openstates.org/${state}/bills/${bill.session}/${bill.bill_id.replace(' ', '')}`;
					const title = bill.title.replace('"', '&quot;');
					const bill_id = bill.bill_id.replace(' ', '&nbsp;');
					const cells = [
						`<a href="${url}" target="_blank" title="${title}" class="bill-link">${bill_id}</a>`,
						formatDate(bill.created_at),
						formatDate(bill.updated_at),
						title,
						bill.type.join(', '),
					];
					const $tr = $('<tr />');
					cells.forEach(cell => $tr.append(`<td>${cell}</td>`));
					$tbody.append($tr);
				});
			})
			.catch(error => {
				$results.append(NETWORK_FAILURE_ALERT);
				console.error('Error in floorupdates', error);
			});	
	};


	if (state) {
		$stateDropdown.val(state);
		getAndDisplayState(state);
	}	



	$form.submit(e => {
		e.preventDefault();
		const state = $stateDropdown.val();
		if (state === '') {
			return;
		}
		getAndDisplayState(state);
	});

	$stateDropdown.change(e => {
		$form.submit();
	});
});