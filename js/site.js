$(function() {
	$('#header .top-menu ul').addClass('sf-menu').superfish();
	//// TODO: jquery plugin
	// $('#breadcrumbs ul li:').css({
	// 	display: 'inline'
	// });	
	$allbutlast = $('#header .breadcrumbs ul li:not(:last-child)');
	// console.log($allbutlast.length);
	$allbutlast.after(' &gt; ');
	if ($.treeview) {
		$('#sidebar-menu ul').treeview({persist: 'cookie'});	
	}
	
});