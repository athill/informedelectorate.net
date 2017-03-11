
import { LOADING_ICON, NETWORK_FAILURE_ALERT, formatDate, getParameterByName, getTableObject, getTableRowObject } from '../utils';

$(() => {
	const $results = $('#results');
	const $form = $('#regulations-form');

	// renderForm();

	renderResults();

	function renderResults() {
		$results.html(`Loading regulations ${LOADING_ICON}`);
		fetch('/api/regulations')
		.then(response => response.json())
		.then(json => {
			console.log(json);
			$results.html(`Displaying at most 25 of ${json.totalNumRecords}`);

/*
documentId
agencyAcronym
docketType
docketTitle/docketType
postedDate
title
*/	
			//// build table structure
			const headers = ['Title', 'Agency', 'Docket Type', 'Docket', 'Posted'];
			const { $tbody, $table } = getTableObject(headers);
			$results.html($table);
			// console.log(json);
			//// populate table with data
			json.documents.forEach(entry => {
				const $tr = getTableRowObject([
					`${entry.documentId}|${entry.title}`,
					entry.agencyAcronym,
					entry.docketType,
					`${entry.docketId}|${entry.docketTitle}`,
					formatDate(entry.postedDate)
				]);

				// $tr.append('<td>'+moment(update.timestamp).format("MMMM Do YYYY, h:mm:ss a")+'</td>');
				// $tr.append('<td>'+ucFirst(update.chamber)+'</td>');
				// $tr.append('<td>'+update.update+'</td>');
				// $tr.append('<td>'+update.update+'</td>');
				// $tr.append('<td>'+update.update+'</td>');
				$tbody.append($tr);
			});		
		})
		.catch(error => {
			$results.append(NETWORK_FAILURE_ALERT);
			console.error('Error in regulations', error)
		});		
	}

	function renderForm() {
		const fieldSequence = ["s","dct","dktid","dkt","cp","a","cs","np","cmsd","cmd","crd","rd","pd","cat","dktst","dktst2","docst"];
		$form.html(`Loading form ${LOADING_ICON}`);
		fetch('/api/regulationoptions')
		.then(response => response.json())
		.then(json => {
			$form.html('');
			let $row;
			// console.log(JSON.stringify(Object.keys(json)));
			fieldSequence.forEach((key, index) => {
				const cols = 3;
				const colClass  = `col-md-${(12/cols)/2}`;
				const field = json[key];
				const $formGroup = $('<div class="form-group" />');
				if (index % cols === 0) {
					$row = $('<div class="row" />');
				}
				$formGroup.append(`<label class="${colClass}" for="${key}">${field.label}: </label>`);
				const $div = $(`<div class="${colClass}" />`);
				if (field.options.length) {
					const $select = $(`<select name="${key}" id="${key}" class="form-control ${colClass}" />`);
					$select.append('<option />');
					field.options.forEach(option => {
						$select.append(`<option value="${option.value}">${option.display}</option>`);
					});
					$div.append($select);
				} else {
					switch (field.type) {
						case 'string':
							$div.append(`<input type="text" name="${key}" id="${key}" class="form-control ${colClass}" />`);
							break;
						case 'date':
							$div.append(`<input type="date" name="${key}" id="${key}" class="form-control ${colClass}" />`);
							break;
						case 'daterange':
							
							$div.append(`<input type="date" name="${key}" id="${key}" class="form-control" />`);
							$div.append(`<label class="sr-only" for="${key}-end">End date for ${field.label}: </label>`);
							$div.append(`<input type="date" name="${key}-end" id="${key}-end" class="form-control" />`);
							
							break;
						default:
							console.error(`Unknown field type: [${field.type}]`);
					}
				}
				$formGroup.append($div);
				$row.append($formGroup);
				if (index % cols === cols-1 || index === fieldSequence.length - 1) {
					$form.append($row);
				}
			});
			$form.append('<button type="submit" class="btn btn-default">Search</button>');
		})
		.catch(error => {
			$form.html(NETWORK_FAILURE_ALERT);
			console.error('Error in regulations', error)
		});	
	};
});