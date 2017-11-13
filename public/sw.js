var STATIC_CACHE = 'informed-cache-v6';
var urlsToCache = [
  '/',
  '/reps/',
  '/elections/',
  '/federalbills/',
  '/statebills/',
  '/statetax/',
  '/css/app.css',
  '/js/app.js',
  '//fonts.googleapis.com/css?family=UnifrakturMaguntia',
  'http://fonts.gstatic.com/s/unifrakturmaguntia/v8/7KWy3ymCVR_xfAvvcIXm33xeO3Ru8xeVcoZnah1CFiM.woff2'
];

self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  const requestUrl = new URL(event.request.url);
  if (requestUrl.pathname.startsWith('/api/')) {
    event.respondWith(onlineFirstStrategy(event.request));
    return;
  }

  event.respondWith(cacheFirstStrategy(event.request));
});

function cacheFirstStrategy(request) {
  return caches.match(request)
    .then(function(response) {
      // Cache hit - return response
      if (response) {
        return response;
      }

      // IMPORTANT: Clone the request. A request is a stream and
      // can only be consumed once. Since we are consuming this
      // once by cache and once by the browser for fetch, we need
      // to clone the response.
      var fetchRequest = request.clone();

      return fetch(fetchRequest).then(
        function(response) {
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
              cache.put(request, responseToCache);
            });

          return response;
        }
      )
    }); 
}

function onlineFirstStrategy(request) {
  return fetch(request)
    .then(function(response) {
      caches.open(STATIC_CACHE).then(function(cache) {
        cache.put(request, response.clone());
      });
      return response;
    }).catch(function(err) {
      caches.match(request).then(function(response) {
        if (response) {
          return response;
        }
        console.log('Cannot fetch resource');
        return;
      })
    })
}

//// remove old caches https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API/Using_Service_Workers
this.addEventListener('activate', function(event) {
  var cacheWhitelist = [STATIC_CACHE];

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

