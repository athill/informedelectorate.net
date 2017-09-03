import React from 'react';
import ReactDOM from 'react-dom';
import { Alert, Button } from 'react-bootstrap';

import { Address, DefinitionList, NETWORK_FAILURE_ALERT, LoadingIcon, getParameterByName, Phone } from '../utils';

const AddressForm = ({address='', onSubmit=e => e}) =>  (
	<form action="" method="get" id="address-form" onSubmit={e => {e.preventDefault(); onSubmit(e)}}>
		<p>Data is from <a href="https://developers.google.com/civic-information/" target="_blank">the Google Civic Information API.</a></p>
		<label htmlFor="addr">Search by address:</label>
		<input id="addr" name="addr" defaultValue={address} autoFocus={true} autoComplete="on" />
		<Button bsSize="small" bsStyle="primary" type="submit">Search</Button>
	</form>
);

const channelMap = {
	'GooglePlus': 'https://plus.google.com/',
	'Facebook': 'https://www.facebook.com/',
	'Twitter': 'https://twitter.com/',
	'YouTube': 'https://www.youtube.com/'
};

const channelLink = (channel, i) => {
	let url = channelMap[channel.type];
	if (channel.type === 'YouTube') {
		url += (channel.id.length > 20) ? 'channel/' : 'user/';
	}
	url += channel.id;
	return <a key={i} href={url} target="_blank">{url}</a>;
};

const getRepChannels = channels => {
	const map = {};
	channels.forEach((channel, i) => {
		if (map[channel.type]) {
			map[channel.type].push(channelLink(channel, i));
		} else {
			map[channel.type] = [channelLink(channel, i)];
		}
	});
	const result = [];
	for (let type in map) {
		if (map.hasOwnProperty(type)) {
			result.push({ type, url: map[type]  });
		}
	}
	return result;

};

const getRepItems = rep => {
	const items = [{ key: 'Name', value: rep.name }];
	rep.party && items.push({ key: 'Party', value: rep.party });
	items.push({ key: 'Phone', value: rep.phones.map((phone, i) => <span key={phone}><Phone number={phone} /> { i > 0 ? ' ' : '' }</span> ) });
	rep.emails && rep.emails.length && items.push({ key: 'Email', value: rep.emails.map((email, i) => <span key={email}><a href={`mailto:${email}`}>{email}</a> { i > 0 ? ' ' : '' }</span> ) });
	rep.urls && rep.urls.length && items.push({ key: 'URL', value: rep.urls.map((url, i) => <span key={url}><a href={url} target="_blank">{url}</a> { i > 0 ? ' ' : '' }</span> ) });
	items.push({key: 'Address', value: <Address addrs={rep.address} />});
	rep.channels && getRepChannels(rep.channels).forEach(channel => items.push({
		key: channel.type, 
		value: channel.url.map((url, i) => <span key={i}>{url} { i > 0 ? ' ' : '' }</span>)
	}));
	return items;
};


const Representative = ({rep}) => (
	<div>
		<DefinitionList items={getRepItems(rep)} />	
		<hr />
	</div>
);

const Office = ({ office }) => (
	<div>
		<h3>{office.title}</h3>
		{ office.reps.map(rep => <Representative key={rep.name} rep={rep} />) }
	</div>	
);

const Representatives = ({address='', data=[]}) => {
	if (!address) {
		return null;
	} else if (data.length === 0) {
		return (<p> Loading results for {address} <LoadingIcon /></p>);
	} else {
		console.log(data);
		return <div>{ data.filter(office => office.reps.length).map(office => <Office key={office.title} office={office} />) }</div>;
	}
};


class Page extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			data: [],									//// representative data
			error: '',									//// error to display, if any
			address: getParameterByName('address')		//// current address
		};

		this._onSubmit = this._onSubmit.bind(this);
		this._updateRepresentatives = this._updateRepresentatives.bind(this);
	}
	componentDidMount() {
	}
	//// update results for address
	_updateRepresentatives(address) {
		this.setState({
			error: '',
			data: [],
			address
		});
		fetch(`/api/reps/?addr=${address}`)
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
					error: `Error trying to get data for ${address}`
				});
				console.error('Error in representatives', error);
			});	
	}
	//// change handler for state dropdown
	_onSubmit(e) {
		const address = document.getElementById('addr').value;
		this._updateRepresentatives(address);

	}
	render() {
		return (
			<div>
				<h2>Find Your Representatives</h2>
				<AddressForm address={this.state.address} onSubmit={this._onSubmit} />
				{ this.state.error && <Alert bsStyle="danger">{this.state.error}</Alert> }
				{ !this.state.error && <Representatives address={this.state.address} data={this.state.data} /> }
			</div>
		)		
	}
};


//// start the party
ReactDOM.render(
	React.createElement(Page), document.getElementById('root')
);
