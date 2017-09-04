import React from 'react';
import ReactDOM from 'react-dom';
import { Alert, Button } from 'react-bootstrap';

import { DefinitionList, formatDateOnly, getParameterByName, LoadingIcon } from '../utils';


const OptionForm = ({search='', states=[], onSubmit=e => e}) =>  (
	<form action="" method="get" id="federalbills-form" onSubmit={e => { e.preventDefault(); onSubmit(e)}}>
		<label htmlFor="search">Search Bills:</label>
		<input id="search" name="search" defaultValue={search} />
		<Button bsSize="small" bsStyle="primary" type="submit">Search</Button>
	</form>
);

const BillLinks = ({data}) => (
	<span>
		<a href={data.gpo_pdf_uri} target="_blank">gpo.gov</a>,  

		<a href={data.congressdotgov_url} target="_blank">congress.gov</a>, 

		<a href={data.govtrack_url} target="_blank">govtrack</a>
	</span>
);

const getBillStatus = bill => {
	let status = '';
	if (bill.introduced_date) {
		status += `Introduced, ${formatDateOnly(bill.introduced_date)}. `
	}
	if (bill.house_passage) {
		status += `Passed house, ${formatDateOnly(bill.house_passage)}. `
	}
	if (bill.senate_passage) {
		status += `Passed senate, ${formatDateOnly(bill.senate_passage)}. `
	}	
	if (bill.enacted) {
		status += `Enacted, ${formatDateOnly(bill.enacted)}. `
	}	
	if (bill.vetoed) {
		status += `Vetoed, ${formatDateOnly(bill.vetoed)}. `
	}		
	return status;

};

const getBillData = bill => {
	const data = [
		{ key: 'Number', value: bill.number },
		{ key: 'Sponsor', value: `${bill.sponsor_name} (${bill.sponsor_party}, ${bill.sponsor_state}). ${bill.cosponsors} cosponsors` },
		{ key: 'Last Major Action', value: `${formatDateOnly(bill.latest_major_action_date)}, ${bill.latest_major_action}` },
		{ key: 'Committees', value: bill.committees },
		{ key: 'Primary Subject', value: bill.primary_subject },
		{ key: 'Summary', value: bill.summary },
		{ key: 'Links', value: <BillLinks data={bill} /> },
		{ key: 'Status', value: getBillStatus(bill) },
	];
	return data;

};

const Bill = ({data}) => (
	<div>
		<h3>{ data.title }</h3>
		<DefinitionList items={getBillData(data)} />		
		<hr />
	</div>
);

const FederalBills = ({data=[]}) => {
	if (data.length === 0) {
		return (<p> Loading results <LoadingIcon /></p>);
	} else {
		return (
				<div>
				{
					data.map((bill, i) => <Bill key={`${bill.title}-${i}`} data={bill} />)
				}
				</div>
		);
	}
};

class Page extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			data: [],									//// bill data
			error: '',									//// error to display, if any
			search: getParameterByName('search')
		};

		this._onSubmit = this._onSubmit.bind(this);
		this._currentFederalBills = this._currentFederalBills.bind(this);
		this._searchFederalBills = this._searchFederalBills.bind(this);
	}
	componentDidMount() {
		if (this.state.search) {
			this._searchFederalBills(this.state.search);
			return;
		}
		this._currentFederalBills();

	}

	_currentFederalBills() {
		fetch('/api/federalbills')
			.then(response => response.json())
			.then(json => {
				this.setState({
					data: json.results[0].bills
				});
			})
			.catch(error => {
				this.setState({
					error: `Error trying to retrieve federal bill data`
				});
				console.error('Error in federalbills', error)
			});		
	}	
	//// update results for search
	_searchFederalBills(search) {
		fetch(`/api/federalbills?search=${search}`)
			.then(response => response.json())
			.then(json => {
				this.setState({
					data: json.results[0].bills
				});
			})
			.catch(error => {
				this.setState({
					error: `Error trying to retrieve federal bill data for "${search}"`
				});
				console.error('Error in federalbills', error)
			});	
	}
	//// change handler 
	_onSubmit(e) {
		const search = document.getElementById('search').value;
		this.setState({
			search,
			data: []
		});
		if (search) {
			this._searchFederalBills(search);	
		} else {
			this._currentFederalBills();
		}
		

	}
	render() {
		const { data, error, search } = this.state;
		const title = search ? `Results for "${search}"` : 'Recently Updated Bills';
		return (
			<div>
				<h2>Federal Bills</h2>
				<p>Data is from the <a href="https://projects.propublica.org/api-docs/congress-api/" target="_blank">Propublica Congress API</a></p>
				<OptionForm search={search} onSubmit={this._onSubmit} />
				<p>{title}</p>
				{ error && <Alert bsStyle="danger">{error}</Alert> }
				{ !error && <FederalBills data={data} /> }
			</div>
		)		
	}
};

//// start the party
ReactDOM.render(
	React.createElement(Page), document.getElementById('root')
);