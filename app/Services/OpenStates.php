<?php

namespace App\Services;

class OpenStates extends Api {

	public function __construct() {
		parent::__construct(config('services.api.openstates.key'));
		$this->url = 'https://openstates.org/api/v1';
		$this->separator = '/';		
	}

	public function getData($method, $params=array()) {
		return parent::get($method, $params);
	}	

	public function getStateLegislatorsByLatLong($lat, $long) {
		$data = $this->getData('/legislators/geo', array('lat'=>$lat,'long'=>$long));
		return $data;
	}

	public function getStateMetadata() {
		$data = $this->getData('/metadata', array());		
		return $data;
	}

	public function getBillsByState($stateabbrev) {
		$data = $this->getData('/bills', array('state'=>$stateabbrev, 'search_window'=>'term'));
		return $data;
	}	
}

/*
graphql
http://graphql.org/learn/introspection/ // introspection
http://docs.openstates.org/en/latest/api/v2/ // open states api

endpoint: http://alpha.openstates.org/graphql

states: 
{
	jurisdictions {
		edges {
			node {
				name
			}
		}
	}
} 

bills (not all options) 
{
	bills(jurisdiction: "Indiana", first: 20) {
		edges {
			node {
				abstracts {
					abstract
				}
				actions {
					organization {
						name
					}
					description
					date
					classification
				}
				classification
				createdAt
				documents {
					note
					date
				}
				extras
				fromOrganization {
					name
				}
				id
				identifier
				legislativeSession {
					name
				}
				otherIdentifiers {
					identifier
				}
				otherTitles {
					title
				}
				relatedBills {
					identifier
					relatedBill {
						title
					}
				}
				sources {
					url
				}
				sponsorships {
					person {
						name
					}
				}
				subject
				title
				updatedAt
				versions {
					
					date
				}
				votes {
					edges {
						node {
							billAction {
								vote {
									identifier
								}
							}
						}
					}
				}
			}
		}
	}
}

meta:
queries:
{
	__schema {
		queryType {
			name
			kind 
			enumValues {
				name description
			}
			fields {
				name
				description
				isDeprecated 
				args {
					name
					defaultValue
				}
			}
			inputFields {
				name
				description
				defaultValue
			}
			
		}
	}
}

*/