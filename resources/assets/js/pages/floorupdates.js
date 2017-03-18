import { ucFirst } from 'change-case';
import React from 'react';
import ReactDOM from 'react-dom';
import { Alert } from 'react-bootstrap';

import { formatDate, getParameterByName, LoadingIcon } from '../utils';
import Table, { Column, ColumnTypes } from '../utils/Table';


const columns = [
	new Column('Date', ColumnTypes.DATE),
	new Column('Chamber', ColumnTypes.TEXT),
	new Column('Event', ColumnTypes.TEXT),
	new Column('Bills', ColumnTypes.TEXT),
];

const FloorUpdates = ({ data=[] }) => {
	if (data.length === 0) {
		return <p> Loading results <LoadingIcon /></p>;
	} else {
		return <Table data={data} columns={columns} />;
	}
};

class Page extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			data: [],									//// state bill data
			error: '',									//// error to display, if any
		};
	}

	componentDidMount() {
		//// populate state dropdown
		fetch('/api/floorupdates')
			.then(response => response.json())
			.then(json => {
				const data = json.map(update => {
					let bills = null;
					if (update.bill_ids.length) {
						bills = (
							<ul>
							{
								update.bill_ids.map(bill_id => (
									<li key={bill_id}>
										<a href={`http://www.opencongress.org/bill/${bill_id}/show`} target="_blank">{bill_id}</a>
									</li>
								))
							}
							</ul>
						);
					}				
					return [
						formatDate(update.timestamp),
						ucFirst(update.chamber),
						update.update,
						bills
					];
				});
				this.setState({
					data
				});
			})
			.catch(error => {
				this.setState({
					error: 'Error trying to retrieve Floor Updates'
				});
				console.error('Error in floorupdates', error)
			});			
	}
	render() {
		return (
			<div>
				<h2>Floor Updates</h2>
				{ this.state.error && <Alert bsStyle="danger">{this.state.error}</Alert> }
				{ !this.state.error && <FloorUpdates data={this.state.data} /> }
			</div>
		)		
	}
};

//// start the party
ReactDOM.render(
	React.createElement(Page), document.getElementById('root')
);