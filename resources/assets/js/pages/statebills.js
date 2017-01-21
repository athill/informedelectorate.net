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
		const value = $stateDropdown.val();
		if (value === '') {
			return;
		}
		fetch(`/api/statebills/${value}`)
			.then(response => response.json())
			.then(json => {
				console.log(json);
			})
			.catch(error => console.error('Error in floorupdates', error));			
	});

	$stateDropdown.change(e => {
		$form.submit();
	});

	// fetch('/api/floorupdates')
	// 	.then(response => response.json())
	// 	.then(json => {
	// 		json.forEach(update => {
	// 			const $tr = $('<tr />');
	// 			$tr.append('<td>'+moment(update.timestamp).format("MMMM Do YYYY, h:mm:ss a")+'</td>');
	// 			$tr.append('<td>'+ucFirst(update.chamber)+'</td>');
	// 			$tr.append('<td>'+update.update+'</td>');
	// 			let $bills = '';
	// 			if (update.bill_ids.length) {
	// 				$bills = $('<ul />');
	// 				update.bill_ids.forEach(bill_id => {
	// 					$bills.append($(`<li><a href="http://www.opencongress.org/bill/${bill_id}/show" target="_blank">${bill_id}</li>`));
	// 				});
	// 			}
	// 			const $td = $('<td />');
	// 			$td.append($bills);
	// 			$tr.append($td);
	// 			$results.append($tr);

	// 		});
	// 	})
	// 	.catch(error => console.error('Error in floorupdates', error));
});