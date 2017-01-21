<!DOCTYPE html>
<html lang="en">
<head>
<title>InformedElectorate.net</title>
<meta charset="uft-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="keywords" content="informed,electorate,elections,U.S.,politics"/>
<meta name="description" content="Informed Electorate"/>
<meta name="author" content="Andy Hill"/>
<meta name="copyright" content="2017, informedelectorate.net"/>
<meta name="viewport" content="width=device-width"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}" />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=UnifrakturMaguntia" />
</head>
<body id="default" class="default">
<div id="skip" class="hide">
    <p>Skip to:</p>
    <ul>
        <li><a href="#content">Content</a></li>
        <li><a href="#search">Search</a></li>
        <li><a href="#primary-navigation">Primary Navigation</a></li>
    </ul>
</div>
<div id="page">
    <header id="header">
    <div id="page-title">InformedElectorate.net</div>
    <div id="citation"><q>Whenever the people are well-informed, they can be trusted with their own government</q> &ndash;<cite>Thomas Jefferson, Letter to Richard Price (8 January 1789)</cite></div>
    </header>
    <nav id="global-nav">
    <h3 id="primary-navigation" class="hide">Primary Navigation</h3>
    <ul class="sf-menu" id="global-nav-menu">
        @foreach ($tabs as $tab)
            <li class="{{ strpos($tab['href'], '/'.Request::path()) !== false ? 'active' : ''  }}">
                <a href="{{ $tab['href'] }}">{{ $tab['display'] }}</a>
            </li>
        @endforeach
    </ul>
    </nav>
    <div id="layout" class="row">
        <div id="content-wrapper" class="col-md-12">
            <main id="content">
            @yield('content')
        </main> <!-- /#content -->
        </div> <!-- /#content-wrapper -->
    </div> <!-- ./row -->
    <footer>
    &copy;InformedElectorate.net  2017
    </footer>
</div>
<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
