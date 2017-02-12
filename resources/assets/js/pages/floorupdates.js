import { ucFirst } from 'change-case';
import moment from 'moment';

$(() => {
	const $results = $('#results');
	$results.html('Loading results <i class="fa fa-cog fa-spin fa-2x fa-fw"></i>')
	fetch('/api/floorupdates')
		.then(response => response.json())
		.then(json => {
			const $tablewrap = $('<div class="table-responsive" />');
			const $table = $('<table class="table table-hover table-border" />');
			$tablewrap.append($table);
			const $thead = $('<thead />');
			['Date', 'Chamber', 'Event', 'Bills'].forEach(header => $thead.append(`<th>${header}</th>`));
			const $tbody = $('<tbody />');
			$table.append($thead, $tbody);
			$tablewrap.append($table);
			$results.html($tablewrap);
			json.forEach(update => {
// <table class="data-table" id="results">
// 	<thead>
// 	<tr>
// 		<th>Date</th>
// 		<th>Chamber</th>
// 		<th>Event</th>
// 		<th>Bills</th>
// 	</tr>
// 	</thead>
// 	<tbody>
// 	</tbody>
// </table>
				

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
		.catch(error => console.error('Error in floorupdates', error));
});