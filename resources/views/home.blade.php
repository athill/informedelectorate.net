@extends('app')

@section('content')
<h2>Welcome to InformedElectorate.net!</h2>
<p>
The idea of this site is to use technology to enable a more politically 
informed populace. I'm hoping to expand on the current offerings, especially drilling down locally and acquiring up-to-the-minute ballot information, so hopefully that will work out.  The current offerings are:
</p>
<h3><a href="/reps/">Find Your Federal and State Congressional Representatives</a></h3>
Type in you address to find your local and state representatives. Includes links to websites, twitter, facebook, etc.

{{-- <h3><a href="/words/">See What Federal Congressional Representives Are Using Key Words and How Often</a></h3>
Type in a term and see which representatives are using the term and how often. Bubble Graph generated with <a href="http://d3js.org/" target="_blank" rel="noopener">D3.js</a>. --}}

<h3><a href="/elections/">Election Information</a></h3>
View upcoming elections and view election information for your address.

<h3><a href="/federalbills/">Current Federal Bills</a></h3>
Recently updated federal bills. Also includes search.

<h3><a href="/statebills/">Current State Bills</a></h3>
Current bills in selected state. Includes links to <a href="http://openstates.org/" target="_blank" rel="noopener">Open States</a>

<h3><a href="/statetax/">Comparison of State Tax Revenues </a></h3>
State tax revenue per capita by instrument. Interactive map.

@endsection