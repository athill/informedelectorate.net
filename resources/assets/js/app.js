require('./bootstrap');
import IndexController from './IndexController';

new IndexController(document.getElementById('content'));

var page = location.pathname.replace(/\/([^\/]+)(\/|$)/, '$1');
if (page === '/') {
	page = 'home';
}

require('./pages/'+page);
