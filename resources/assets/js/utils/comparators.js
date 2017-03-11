import React from 'react';
import moment from 'moment';

const sort = (a, b) => {
	if (a < b) {
		return -1;
	}
	if (a > b) {
		return 1;
	}
	// names must be equal
	return 0;		
}

export const sortByText = (key) => {
	return (a, b) => {
		const nameA = ('' + a[key]).toUpperCase();
		const nameB = ('' + b[key]).toUpperCase();
		return sort(nameA, nameB);
	}
};

export const sortByDate = (key, format) => {
	return (a, b) => {
		const nameA = moment(a[key], format).toDate();
		const nameB = moment(b[key], format).toDate();
		return sort(nameA, nameB);
	}
};

export const sortByLink = (key) => {
	return (a, b) => {
		const nameA = ('' + a[key].props.children).toUpperCase();
		const nameB = ('' + b[key].props.children).toUpperCase();
		return sort(nameA, nameB);
	}
};





