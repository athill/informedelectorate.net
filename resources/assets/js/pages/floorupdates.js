import { ucFirst } from 'change-case';
import moment from 'moment';

$(() => {
	const $results = $('tbody', '#results');

	fetch('/api/floorupdates')
		.then(response => response.json())
		.then(json => {
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
				$results.append($tr);

			});
		})
		.catch(error => console.error('Error in floorupdates', error));
});