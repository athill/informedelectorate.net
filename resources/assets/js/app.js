require('./bootstrap');

import swURL from "file?name=sw.js!babel!./sw";

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register(swURL).then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}

var page = location.pathname.replace(/\/([^\/]+)(\/|$)/, '$1');
if (page === '/') {
	page = 'home';
}

require('./pages/'+page);
