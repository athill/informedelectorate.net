import { ucFirst } from 'change-case';
import moment from 'moment';

import { NETWORK_FAILURE_ALERT, LOADING_ICON, getTableObject } from '../utils';

$(() => {
	const $results = $('#results');
	$results.html('Loading results '+LOADING_ICON);
	fetch('/api/floorupdates')
		.then(response => response.json())
		.then(json => {
			//// build table structure
			const headers = ['Date', 'Chamber', 'Event', 'Bills'];
			const { $tbody, $table } = getTableObject(headers);
			$results.html($table);
			//// populate table with data
			json.forEach(update => {
				const $tr = $('<tr />');
				$tr.append('<td>'+moment(update.timestamp).format("MMMM Do YYYY, h:mm:ss a")+'</td>');
				$tr.append('<td>'+ucFirst(update.chamber)+'</td>');
				$tr.append('<td>'+update.update+'</td>');
				let $bills = '';
				if (update.bill_ids.length) {
					$bills = $('<ul />');
					update.bill_ids.forEach(bill_id => {
						$bills.append($(`<li><a href="http://www.opencongress.org/bill/${bill_id}/show" target="_blank">${bill_id}</li>`));
					});
				}
				const $td = $('<td />');
				$td.append($bills);
				$tr.append($td);
				$tbody.append($tr);
			});
		})
		.catch(error => {
			$results.html(NETWORK_FAILURE_ALERT);
			console.error('Error in floorupdates', error)
		});
});