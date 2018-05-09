<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use Psr\Http\Message\RequestInterface;

class OpenStates2 {

	private $client;

	public function __construct() {
		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());
		$stack->push($this->add_header('X-API-KEY', config('services.api.openstates.key')));
		$this->client = new Client([
			'base_uri' => 'http://alpha.openstates.org/graphql',
			'handler' => $stack
		]);		
	}

	public function getStates() {
		$query = "{
			jurisdictions {
				edges {
					node {
						name
					}
				}
			}
		}";
		$response = $this->request($query);
		$states = [];
		foreach ($response['data']['jurisdictions']['edges'] as $value) {
			$states[] = $value['node']['name'];
		}
		asort($states);
		return $states;
	}

	public function getState(string $state) {
		$query = '{
		  jurisdiction(name: "' . $state . '") {
		    name
		    url
		    legislativeSessions {
		      edges {
		        node {
		          name
		          identifier
		        }
		      }
		    }
			}
		}';
		$response = $this->request($query);
		$data = $response['data']['jurisdiction'];
		// return $response;
		$state = [
			'name' => $data['name'],
			'url' => $data['url'],
			'sessions' => []
		];
		foreach ($data['legislativeSessions']['edges'] as $value) {
			$state['sessions'][] = [
				'name' => $value['node']['name'],
				'id' => $value['node']['identifier']
			];
		}	
		$state['sessions'] = array_values(array_sort($state['sessions'], function ($value) {
		    return $value['id'];
		}));		
		return $state;	
	}

	public function getBills(string $state, string $sessionId) {
		// headers: Bill	Created	Updated	Type	Subjects
		$query = $this->getBillQuery($state, $sessionId);
		$response = $this->request($query);
		$data = $response['data']['bills']['edges'];

		return $data;
	}
	//// current bill data
	/*
		[
		     "title" => "A BILL FOR AN ACT to amend the Indiana Code concerning health.",
		     "created_at" => "2018-01-23 07:00:58",
		     "updated_at" => "2018-03-24 07:41:11",
		     "id" => "INB00009851",
		     "chamber" => "lower",
		     "state" => "in",
		     "session" => "2018",
		     "type" => [
		       "bill",
		     ],
		     "subjects" => [
		       "Drugs",
		       "Health",
		     ],
		     "bill_id" => "HB 1007",
		   ]
	*/

	private function request($query) {
		$response = $this->client->post('', ['json' => ['query' => $query]]);
		return json_decode($response->getBody()->getContents(), true);	
	}

	private function add_header($header, $value)
	{
	    return function (callable $handler) use ($header, $value) {
	        return function (
	            RequestInterface $request,
	            array $options
	        ) use ($handler, $header, $value) {
	            $request = $request->withHeader($header, $value);
	            return $handler($request, $options);
	        };
	    };
	}

	private function getBillQuery(string $state, string $sessionId) {
		return '{
			bills(jurisdiction: "' . $state . '" , session: "' . $sessionId . '", first: 1) { 
				pageInfo {
					endCursor
				}
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
		}';
	}	
}