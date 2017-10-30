require('./bootstrap');

var page = location.pathname.replace(/\/([^\/]+)(\/|$)/, '$1');
if (page === '/') {
	page = 'home';
}

require('./pages/'+page);
