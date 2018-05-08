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
}