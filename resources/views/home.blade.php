@extends('app')

@section('content')
<p>
Welcome to InformedElectorate.net! The idea of this site is to use technology to enable a more politically 
informed populace. The data is currently all from from <a href="http://sunlightfoundation.com/api/" target="_blank">the 
Sunlight Foundation</a>. I'm hoping to expand on the current offerings, especially drilling down locally and acquiring up-to-the-minute ballot information, so hopefully that will work out.  The current offerings are:
</p>
<h3><a href="/reps/">Find Your Federal and State Congressional Representatives</a></h3>
<p>Type in you address, and via <a href="https://developers.google.com/maps/" target="_blank">Google Maps API</a>, find your local and state representatives. Includes links to websites, twitter, facebook, etc.</p>
<h3><a href="/words/">See What Federal Congressional Representives Are Using Key Words and How Often</a></h3>
<p>Type in a term and see which representatives are using the term and how often. Bubble Graph generated with <a href="http://d3js.org/" target="_blank">D3.js</a>.</p>
<h3><a href="/floorupdates/">The Federal Congressional Record</a></h3>
<p>Find out what the House and Senate recorded recently. Includes links to <a href="http://www.opencongress.org/" target="_blank">Open Congress</a> for related bills.</p>
<h3><a href="/statebills/">Current State Bills</a></h3>
<p>Current bills in selected state. Includes links to <a href="http://openstates.org/" target="_blank">Open States</a></p>
<h3><a href="/statetax/">Comparison of State Tax Revenues </a></h3>
<p>State tax revenue per capita by instrument. Interactive map.</p>
@endsection