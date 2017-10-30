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
<link rel="manifest" href="/manifest.json"/>


<meta property="og:image" content="https://informedelectorate.net/images/informedelectorate-header-short.png" />
<meta property="og:title" content="InformedElectorate.net" />
<meta property="og:description" content="The idea of this site is to use technology to enable a more politically informed United States populace. " />

<link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=UnifrakturMaguntia" />
</head>
<body id="default" class="default">
@if ($google_analytics_key)
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', '{{ $google_analytics_key }}', 'auto');
      ga('send', 'pageview');
    </script>
@endif
<div id="skip" class="hide">
    <p>Skip to:</p>
    <ul>
        <li><a href="#content">Content</a></li>
        <li><a href="#search">Search</a></li>
        <li><a href="#primary-navigation">Primary Navigation</a></li>
    </ul>
</div>
<div id="page" class="container">
    <header id="header">
    <div id="page-title">InformedElectorate.net</div>
    <div id="citation"><q>Whenever the people are well-informed, they can be trusted with their own government</q> &ndash;<cite>Thomas Jefferson, Letter to Richard Price (8 January 1789)</cite></div>
    </header>
    <nav class="navbar navbar-default"  id="primary-navigation">
        <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <!-- <a class="navbar-brand" href="#">Brand</a> -->
            </div>        
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @foreach ($tabs as $tab)
                    <li class="{{ strpos($tab['href'], '/'.Request::path()) !== false ? 'active' : ''  }}">
                        <a href="{{ $tab['href'] }}">{{ $tab['display'] }}</a>
                    </li>
                    @endforeach                
                </ul>
            </div>
        </div>
    </nav>
    <div id="layout" class="row">
        <div id="content-wrapper" class="col-md-12">
            <main id="content">
            @yield('content')
        </main> <!-- /#content -->
        </div> <!-- /#content-wrapper -->
    </div> <!-- ./row -->
    <footer>
    &copy;InformedElectorate.net  {{ date('Y') }}
    </footer>
</div>
<script type="text/javascript">
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}
</script>
<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
