$(function() {
	var $mobilemenu = $('#mobile-menu');
	//// topmenu
	$('#header .top-menu ul').addClass('sf-menu').superfish();
	//// mobile menu
	$('#menu-toggle').click(function(e) {
		e.preventDefault();
		$mobilemenu.toggle('slow');
	});

	$('#mobile-menu').metisMenu({  
    	 doubleTapToGo: true
    });

	//// breadcrumbs
	//// TODO: jquery plugin
	$allbutlast = $('#header .breadcrumbs ul li:not(:last-child)');
	// console.log($allbutlast.length);
	$allbutlast.after(' &gt; ');
	if ($.treeview) {
		$('#sidebar-menu ul').treeview({persist: 'cookie'});	
	}
});