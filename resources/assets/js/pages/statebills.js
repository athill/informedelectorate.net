import React from 'react';
import ReactDOM from 'react-dom';
import { Alert } from 'react-bootstrap';

import { formatDate, getParameterByName, LoadingIcon } from '../utils';
import Table, { Column, ColumnTypes } from '../utils/Table';


const statemap = {};
const statecache = {};

const StateForm = ({selected='', states=[], onChange=e => e}) =>  (
	<form action="" method="get" id="statebills-form" onSubmit={e => e.preventDefault()}>
		<label htmlFor="state">Select a state:</label>
		<select id="state" name="state" onChange={onChange} defaultValue={selected}>
			<option value=""></option>
			{
				states.map(state => (
					<option key={state.abbreviation} value={state.abbreviation}>{state.name}</option> 
				))
			}
		</select>
	</form>
);
const columns = [
	new Column('Bill', ColumnTypes.LINK),
	new Column('Created', ColumnTypes.DATE),
	new Column('Updated', ColumnTypes.DATE),
	new Column('Type', ColumnTypes.TEXT),
	new Column('Subjects', ColumnTypes.TEXT)

];
		// , 'Created', 'Updated', 'Type', 'Subjects'

const StateBills = ({selected='', data=[]}) => {
	if (!selected) {
		return null;
	} else if (data.length === 0) {
		return (<p> Loading results for {statemap[selected]} <LoadingIcon /></p>);
	} else {
		// const headers = ['Bills', 'Created', 'Updated', 'Type', 'Subjects']
		return (
			<div>
				<p>Showing {data.length} results for <a href={`?state=${selected}`}>{statemap[selected]}</a>:</p>
				<Table data={data} columns={columns} />
			</div>
		);
	}
};

class Page extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			data: [],									//// state bill data
			error: '',									//// error to display, if any
			states: [],									//// list of states for dropdown
			selected: getParameterByName('state')		//// currently selected state
		};

		this._onChange = this._onChange.bind(this);
		this._updateStateBills = this._updateStateBills.bind(this);
	}
	componentDidMount() {
		//// populate state dropdown
		fetch('/api/statebills')
			.then(response => response.json())
			.then(json => {
				this.setState({
					states: json
				});
				json.forEach(state => {
					statemap[state.abbreviation] = state.name;
				});
				if (this.state.selected) {
					this._updateStateBills(this.state.selected);
				}
			})
			.catch(error => {
				this.setState({
					error: 'Error trying to retrieve state data'
				});
				console.error('Error in floorupdates', error)
			});			
	}
	//// update results for selected state
	_updateStateBills(selected) {
		if (selected in statecache) {
			this.setState({
				error: '',
				data: statecache[selected],
				selected
			});
		} else {
			this.setState({
				error: '',
				data: [],
				selected
			});
			fetch(`/api/statebills/${selected}`)
				.then(response => response.json())
				.then(json => {
					const data = json.map(bill => {
						const url = `http://openstates.org/${selected}/bills/${bill.session}/${bill.bill_id.replace(' ', '')}`;
						const title = bill.title.replace('"', '&quot;');
						const bill_id = bill.bill_id.replace(' ', '\u00a0');
						return [
							<a href={url} target="_blank" title={bill.title} className="bill-link">{bill_id}</a>,
							formatDate(bill.created_at),
							formatDate(bill.updated_at),
							title,
							bill.type.join(', '),
						];
					});
					this.setState({
						data
					});
					statecache[selected] = data;
				})
				.catch(error => {
					this.setState({
						error: `Error trying to get data for ${statemap[selected]}`
					});
					console.error('Error in floorupdates', error);
				});
		}		
	}
	//// change handler for state dropdown
	_onChange(e) {
		const selected = e.target.value;
		this._updateStateBills(selected);

	}
	render() {
		return (
			<div>
				<h2>State Bills</h2>
				<StateForm states={this.state.states} selected={this.state.selected} onChange={this._onChange} />
				{ this.state.error && <Alert bsStyle="danger">{this.state.error}</Alert> }
				{ !this.state.error && <StateBills selected={this.state.selected} data={this.state.data} /> }
			</div>
		)		
	}
};

//// start the party
ReactDOM.render(
	React.createElement(Page), document.getElementById('root')
);