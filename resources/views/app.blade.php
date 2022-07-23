<!DOCTYPE html>
<html lang="en" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<title>InformedElectorate.net</title>
<meta charset="uft-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="keywords" content="informed,electorate,elections,U.S.,politics"/>
<meta name="description" content="Informed Electorate"/>
<meta name="author" content="Andy Hill"/>
<meta name="copyright" content="2017, informedelectorate.net"/>
<meta name="viewport" content="width=device-width"/>
<!-- PWA -->
<link rel="manifest" href="/manifest.json"/>
<meta name="theme-color" content="#8B0000"/>
<link href="/images/touch/informed_48x48.png" rel="shortcut icon" type="image/png"/>
<!-- opengraph -->
<meta property="og:image" content="https://informedelectorate.net/images/informedelectorate-header-short.png" />
<meta property="og:title" content="InformedElectorate.net" />
<meta property="og:description" content="The idea of this site is to use technology to enable a more politically informed United States populace. " />

<link rel="stylesheet" type="text/css"  href="{{ asset('/css/app.css') }}" />
</head>
<body id="default" class="default">
<div id="skip" class="hide">
    <p>Skip to:</p>
    <ul>
        <li><a href="#content">Content</a></li>
    </ul>
</div>
<div id="page" class="container">
    <header id="header">
    <div id="page-title">InformedElectorate.net</div>
    <div id="citation"><q>Whenever the people are well-informed, they can be trusted with their own government</q> &ndash;<cite>Thomas Jefferson, Letter to Richard Price (8 January 1789)</cite></div>
    </header>
    <div id="layout" class="row">
        <div id="content-wrapper" class="col-md-12">
            <main id="content">
            @yield('content')
            </main> 
        </div> 
    </div> 
    <footer>
    &copy;InformedElectorate.net  {{ date('Y') }}
    </footer>
</div>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=UnifrakturMaguntia" />
<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
