<?php namespace Classes;

class Curl {


	public function get($url) {
		global $site;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		// curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		//CURLOPT_SSLVERSION 4, 5, 6
		// CURLOPT_SSL_CIPHER_LIST => 'TLSv1' 
		$site['logger']->info('getting: '.$url);
		$content = curl_exec($ch);
		if ($content === false) {
			$site['logger']->info('fail: '.$url.': '.curl_error($ch));
		}
		curl_close($ch);
		return $content;
	}


	//// from http://www.phpied.com/simultaneuos-http-requests-in-php-with-curl/
	//// handles get request as [<url1>, <url2>, ...] or post request as 
	//// [[ 'url'=><url1>, 'post'=>[<postvars]], ...]
	public function getMulti($data, $options = array()) {
	 
	  // array of curl handles
	  $curly = array();
	  // data to be returned
	  $result = array();
	 
	  // multi handle
	  $mh = curl_multi_init();
	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($data as $id => $d) {
	 
	    $curly[$id] = curl_init();
	 
	    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
	    curl_setopt($curly[$id], CURLOPT_URL,            $url);
	    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
	    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

	    //CURLOPT_SSL_CIPHER_LIST => 'TLSv1' 
	 
	    // post?
	    if (is_array($d)) {
	      if (!empty($d['post'])) {
	        curl_setopt($curly[$id], CURLOPT_POST,       1);
	        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
	      }
	    }
	 
	    // extra options?
	    if (!empty($options)) {
	      curl_setopt_array($curly[$id], $options);
	    }
	 
	    curl_multi_add_handle($mh, $curly[$id]);
	  }
	 
	  // execute the handles
	  $running = null;
	  do {
	    curl_multi_exec($mh, $running);
	  } while($running > 0);
	 
	 
	  // get content and remove handles
	  foreach($curly as $id => $c) {
	    $result[$id] = curl_multi_getcontent($c);
	    curl_multi_remove_handle($mh, $c);
	  }
	  // all done
	  curl_multi_close($mh);
	 
	  return $result;
	}	
}