import React from 'react';
import ReactDOM from 'react-dom';
import changeCase from 'change-case';
import { Alert, Button } from 'react-bootstrap';

import { Address, DefinitionList, Email, LoadingIcon, Phone, formatDateOnly, getParameterByName } from '../utils';
import Table, { Column, ColumnTypes } from '../utils/Table';

const columns = [
	new Column('Date', ColumnTypes.DATE),
	new Column('Name', ColumnTypes.TEXT)
];

const AddressForm = ({address='', onSubmit=e => e}) =>  (
	<form action="" method="get" id="address-form" onSubmit={e => {e.preventDefault(); onSubmit(e)}}>
		<label htmlFor="addr">Search by address:</label>
		<input id="addr" name="addr" defaultValue={address} autoFocus={true} autoComplete="on" />
		<Button bsSize="small" bsStyle="primary" type="submit">Search</Button>
	</form>
);

const getContestData = contest => {
	const data = [
		{ key: 'Type', value: contest.type },
		{ key: 'Office', value: contest.office },
		{ key: 'Name', value: contest.district.name },
		{ key: 'Scope', value: contest.district.scope },
		{ key: 'Number Elected', value: contest.numberElected },
		{ key: 'Ballot Placement', value: contest.ballotPlacement },
		{ key: 'Candidates', value: contest.candidates.map(candidate => candidate.name).join(', ') },
		{ key: 'Sources', value: contest.sources.map(source => `${source.name} -- ${source.official ? 'Official' : 'Unofficial'}`) },
	];
	return data;

};

const getPollingLocationData = location => {
	const data = [
		{ key: 'Address', value: <Address addrs={[location.address]} />  },
		{ key: 'Notes', value: location.notes },
		{ key: 'Hours', value: location.pollingHours }
	];
	return data;

};

const PollingLocation = ({data}) => (
	<div>
		<DefinitionList items={getPollingLocationData(data)} />
		<hr />
	</div>
);

const getEarlyVoteSite = location => {
	const data = [
		{ key: 'Address', value: <Address addrs={[location.address]} />  },
		{ key: 'Notes', value: location.notes },
		{ key: 'Dates', value: `${formatDateOnly(location.startDate)} to ${formatDateOnly(location.endDate)}` },
		{ key: 'Hours', value: location.pollingHours }
	];
	return data;
};

const EarlyVoteSite = ({data}) => (
	<div>
		<DefinitionList items={getEarlyVoteSite(data)} />
		<hr />
	</div>
);

const getState = state => {
	const electionAdministrativeBodyKeys = ['electionInfoUrl', 'electionRegistrationUrl', 'electionRegistrationConfirmationUrl', 'absenteeVotingInfoUrl', 'votingLocationFinderUrl', 'ballotInfoUrl', 'electionRulesUrl', 	];
	const titles = electionAdministrativeBodyKeys.map(key => changeCase.title(changeCase.sentence(key)));
	const data = [{ key: 'Name', value: state.name  }];
	electionAdministrativeBodyKeys.forEach(key => {
		const label = changeCase.title(changeCase.sentence(key));
		const url = state.electionAdministrationBody[key];
		const value = <a href={url} target="_blank" rel="noopener">{url}</a>;
		data.push({
			key: label, 
			value
		});
	});
	data.push({ key: 'Jurisdiction', value: state.local_jurisdiction.name });
	const body = state.local_jurisdiction.electionAdministrationBody;
	data.push({key: 'URL', value: <a href={body.electionInfoUrl} target="_blank" rel="noopener">{body.electionInfoUrl}</a>});
	data.push({key: 'Address', value: <Address addrs={[body.physicalAddress]} />});
	data.push({key: 'Phone', value: <Phone number={body.electionOfficials[0].officePhoneNumber} /> });
	data.push({key: 'Email', value: <Email email={body.electionOfficials[0].emailAddress} /> });

	return data;
};

const State = ({data}) => (
	<div>
		<DefinitionList items={getState(data)} />
		<hr />
	</div>
);

const Contest = ({data}) => (
	<div>
		<DefinitionList items={getContestData(data)} />
		<hr />
	</div>
);

const Election = ({ address, data }) => {
	return (
	<div>
		<p>Results for {address}</p>
		<h3>{data.election.name} on {formatDateOnly(data.election.electionDay)}</h3>
		<h4>Contests</h4>
		{ data.contests.map(contest => <Contest key={contest.office} data={contest} />) }
		<h4>Polling Locations</h4>
		{ data.pollingLocations.map((pollingLocation, i) => <PollingLocation  key={i} data={pollingLocation} /> )}
		<h4>Early Voting Sites</h4>
		{ data.earlyVoteSites.map((earlyVoteSite, i) => <EarlyVoteSite  key={i} data={earlyVoteSite} /> )}
		<h3>State</h3>
		{ data.state.map((state, i) => <State key={i} data={state} />) }		
	</div>

)};

const Elections = ({data=[]}) => {
	if (data.length === 0) {
		return (<p> Loading results  <LoadingIcon /></p>);
	} else {
		const tableData = data.elections.map(election => [formatDateOnly(election.electionDay), election.name]);
		return  <Table data={tableData} columns={columns} />;
	}
};



class Page extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			data: [],									//// representative data
			error: '',									//// error to display, if any
			address: getParameterByName('addr'),		//// current address
			electionData: {}
		};

		this._onSubmit = this._onSubmit.bind(this);
		this._updateElectionInfo = this._updateElectionInfo.bind(this);

	}
	componentDidMount() {
		//// query string param
		if (this.state.address) {
			this._updateElectionInfo(this.state.address);
		}
		fetch('/api/elections')
			.then(response => response.json())
			.then(json => {
				if (json.error) {
					this.setState({ error: json.error });
					return;
				}
				this.setState({
					data: json
				});
			})
			.catch(error => {
				this.setState({
					error: 'Error trying to retrieve election data'
				});
				console.error('Error in floorupdates', error)
			});			
	}
	//// update results for address
	_updateElectionInfo(address) {
		this.setState({
			error: '',
			address
		});
		fetch(`/api/elections/?addr=${address}`)
			.then(response => response.json())
			.then(json => {
				if (json.error) {
					this.setState({
						error: json.error.message
					});
					return;
				}
				this.setState({
					electionData: json
				});
			})
			.catch(error => {
				this.setState({
					error: `Error trying to get data for ${address}`
				});
				console.error('Error in elections', error);
			});	
	}
	//// change handler 
	_onSubmit(e) {
		const address = document.getElementById('addr').value;
		this._updateElectionInfo(address);

	}
	render() {
		const { address, data, electionData, error } = this.state;
		return (
			<div>
				<h2>Elections</h2>
				<p>Data is from <a href="https://developers.google.com/civic-information/" target="_blank" rel="noopener">the Google Civic Information API.</a></p>
				<Elections data={data} />
				<p>If your state is in the list above, you can get election information by address.</p>
				<AddressForm address={address} onSubmit={this._onSubmit} />
				{ electionData.election && <Election address={address} data={electionData} /> }
				{ error && <Alert bsStyle="danger">{error}</Alert> }
			</div>
		)		
	}
};

//// start the party
ReactDOM.render(
	React.createElement(Page), document.getElementById('root')
);