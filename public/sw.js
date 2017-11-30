var STATIC_CACHE = 'informed-cache-v11';
var DYNAMIC_CACHE = 'informed-static-cache-v3';
// foo

var urlsToCache = [
  '/',
  '/reps',
  '/elections',
  '/federalbills',
  '/statebills',
  '/statetax',
  '/css/app.css',
  '/js/app.js',
  'https://fonts.googleapis.com/css?family=UnifrakturMaguntia',
  'https://fonts.gstatic.com/s/unifrakturmaguntia/v8/7KWy3ymCVR_xfAvvcIXm33xeO3Ru8xeVcoZnah1CFiM.woff2'
];

self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(function(cache) {
        cache.addAll(urlsToCache.map(function(urlToPrefetch) {
          return new Request(urlToPrefetch, {mode: 'no-cors'});
        })).then(function() {
          console.log('All resources have been fetched and cached.');
        });
      })
  );
});

self.addEventListener('fetch', function(event) {
  const requestUrl = new URL(event.request.url);
  if (requestUrl.pathname.startsWith('/api/')) {
    event.respondWith(onlineFirstStrategy(event));
    return;
  }

  event.respondWith(cacheFirstStrategy(event));
});

function toast(event, message) {
    // Exit early if we don't have access to the client.
    // Eg, if it's cross-origin.
    if (!event.clientId) return;

    // Get the client.
    clients.get(event.clientId)
      .then(function(client) {
        client.postMessage({
          msg: message,
        });       
      })
      .catch(function(err)  {
        return;
      });
}

function cacheFirstStrategy(event) {
  return caches.match(event.request)
    .then(function(response) {
      // Cache hit - return response
      if (response) {
        return response;
      }

      // IMPORTANT: Clone the request. A request is a stream and
      // can only be consumed once. Since we are consuming this
      // once by cache and once by the browser for fetch, we need
      // to clone the response.
      var fetchRequest = event.request.clone();

      return fetch(fetchRequest)
        .then(function(response) {
          // Check if we received a valid response
          if(!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }

          // IMPORTANT: Clone the response. A response is a stream
          // and because we want the browser to consume the response
          // as well as the cache consuming the response, we need
          // to clone it so we have two streams.
          var responseToCache = response.clone();

          caches.open(STATIC_CACHE)
            .then(function(cache) {
              cache.put(event.request, responseToCache);
            });

          return response;
      })
      .catch(function(err) {
        toast(event, 'You appear to be offline. Please check your internet connection.');
        return;
      });
    }); 
}

function onlineFirstStrategy(event) {
  return fetch(event.request)
    .then(function(response) {
      var responseToCache = response.clone();
      caches.open(DYNAMIC_CACHE).then(function(cache) {
         cache.put(event.request, responseToCache);
      })
      return response;
    })
    .catch(function() {
      return caches.match(event.request, { cacheName: DYNAMIC_CACHE })
        .then(function(match) {
          if (match) {
            toast(event, 'You appear to be offline. Returning result from local cache.');
            return match;            
          } else {
            toast(event, 'You appear to be offline. Please check your internet connection.');
            return;              
          }
        })
    })
}

//// remove old caches https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API/Using_Service_Workers
this.addEventListener('activate', function(event) {
  var cacheWhitelist = [STATIC_CACHE, DYNAMIC_CACHE];

  event.waitUntil(
    caches.keys().then(function(keyList) {
      return Promise.all(keyList.map(function(key) {
        if (cacheWhitelist.indexOf(key) === -1) {
          return caches.delete(key);
        }
      }));
    })
  );
});

self.addEventListener('message', function(event) {
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});

