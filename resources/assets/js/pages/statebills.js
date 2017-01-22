import { formatDate } from '../utils';

$(() => {
	const $results = $('#results');
	const $form = $('#statebills-form');

	const $stateDropdown = $('#state');

	fetch('/api/statebills')
		.then(response => response.json())
		.then(json => {
			json.forEach(state => {
				$stateDropdown.append($(`<option value="${state.abbreviation}">${state.name}</option>`));

			});
		})
		.catch(error => console.error('Error in floorupdates', error));	

	$form.submit(e => {
		e.preventDefault();
		const state = $stateDropdown.val();
		if (state === '') {
			return;
		}
		fetch(`/api/statebills/${state}`)
			.then(response => response.json())
			.then(json => {
				const headers = ['Bill', 'Created', 'Updated', 'Type', 'Subjects'];
				const $table = $('<table class="data-table" />');
				const $tHeader = $table.append($('<thead><tr /></thead>'));
				headers.forEach(header => {
					$tHeader.append($(`<th scope="col">${header}</th>`));
				});
				$table.append($tHeader);
				const $tBody = $('<tbody />');
				json.forEach(bill => {
					const url = `http://openstates.org/${state}/bills/${bill.session}/${bill.bill_id.replace(' ', '')}`;
					const title = bill.title.replace('"', '&quot;');
					const cells = [
						`<a href="${url}" target="_blank" title="${title}" class="bill-link">${bill.bill_id}</a>`,
						formatDate(bill.created_at),
						formatDate(bill.updated_at),
						title,
						bill.type.join(', '),

					];
					const $tr = $('<tr />');
					cells.forEach(cell => $tr.append(`<td>${cell}</td>`));
					$tBody.append($tr);
				});
				$table.append($tBody);
				$results.html($table);
			})
			.catch(error => console.error('Error in floorupdates', error));			
	});

	$stateDropdown.change(e => {
		$form.submit();
	});
});