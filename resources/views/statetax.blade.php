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
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=UnifrakturMaguntia" />
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
<div id="page" class="container">
    <header id="header">
    <div id="page-title">InformedElectorate.net</div>
    <div id="citation"><q>Whenever the people are well-informed, they can be trusted with their own government</q> &ndash;<cite>Thomas Jefferson, Letter to Richard Price (8 January 1789)</cite></div>
    </header>
    <nav class="navbar navbar-default" id="primary-navigation">
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
<h2>State Taxes</h2>
<p>Use the radio buttons on the right to view the various combinations of per capita state and local tax burdens. Lighter shades indicate lower taxes. Hover over a state to see its per capita tax burden for the selected combination. Except for Total, I've offered all combinations of the other fields to see how various tax types accumulate when combined.</p>
<div class="row" id="interface-container">

	<div class="col-md-6 col-xs-12" id="state_map"></div>

	<div class="col-md-6 col-xs-12">
	<h5>Category</h5>
	<div id="options-container" class="row"></div>
	</div>

</div>
<p>
<small>Source: US Census Bureau, 2011 reports.<br />
* The US Census Bureau does not classify revenue from Texas's margin tax as corporate income tax revenue.<br />
Note: "$0" means no tax was collected or the amount was too insignificant to count.<br />
State Tax Collections per Capita by Category, 2011</small>
</p>
<p>I found <a href="http://mercatus.org/publication/primer-state-and-local-tax-policy-trade-offs-among-tax-instruments" target="_blank">this report from Mercatus on the Trade-Offs among Tax Instruments</a> and found it interesting. The report is more than these numbers, looking at various tax instruments in <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_summary_v1(KP).pdf" target="_blank">terms of revenue collected, collection costs, fairness, transparency, and minimizing distortions to the economy</a> The actual data used here is from <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_v2.pdf" target="_blank">page 33 of this PDF</a>. I believe Mr. Ross got his numbers by merging U.S. Census <a href="http://www.census.gov/govs/statetax/" target="_blank">state government tax collections</a> with <a href="http://www.census.gov/popest/data/state/totals/2013/index.html" target="_blank">state populations</a> among other data. There are taxes in the Total value not covered in the other categories. For example, Alaska has corportate taxes of $1003 per capita, income taxes of $0 per capita, sales tax of $0 per capita, and property tax of $255 per capita, yet has a total of $7708 per capita. So $6550, according to  <a href="http://en.wikipedia.org/wiki/State_tax_levels_in_the_United_States#State_Government_Tax_Collections_.282012.29" target="_blank">this Wikipedia table </a>, comes from license, inheritance, gift, and severance taxes.</p>
<p>This was created using the <a href="http://d3js.org/" target="_blank">D3</a>, <a href="https://github.com/caged/d3-tip" target="_blank">d3-tip</a>, and <a href="http://jquery.com/" target="_blank">jQuery</a> libraries and the <a href="http://geojson.org/" target="_blank">GeoJson</a> encoding format. I learned a lot of what is used here in the book <a href="http://shop.oreilly.com/product/0636920026938.do" target="_blank"> Interactive Data Visualization for the Web </a>.</p>

        </main> <!-- /#content -->
        </div> <!-- /#content-wrapper -->
    </div> <!-- ./row -->
    <footer>
    &copy;InformedElectorate.net  {{ date('Y') }}
    </footer>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"
		integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
		crossorigin="anonymous"></script>
<script src="{{ asset('/data/statetax/d3-3.4.3.min.js') }}"></script>
<script src="{{ asset('/data/statetax/d3.geo.projection.v0.min.js') }}"></script>
<script src="{{ asset('/data/statetax/d3-tip/index.js') }}"></script>
<script src="{{ asset('/data/statetax/bootstrap.min.js') }}"></script>

<script src="{{ asset('/data/statetax/scripts.js') }}"></script>
</body>
</html>