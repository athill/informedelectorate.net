@extends('app')

@section('content')
<h2>Key Words</h2>

<div class="alert alert-danger" role="alert">
The <a href="https://sunlightlabs.github.io/Capitol-Words/" target="_blank" rel="noopener">Capitol Words API</a>, which provides the data for this page, <a href="https://github.com/propublica/Capitol-Words/issues/103" target="_blank" rel="noopener">is currently down</a>.
</div>

<p>
Use this page to see how many times legislators have used key terms. For example, "free market" or "iraq". 
Uses the Sunlight <a href="http://sunlightfoundation.com/api/" target="_blank" rel="noopener">Capital Words API</a> for the 
data and <a href="http://d3js.org/" target="_blank" rel="noopener">D3.js</a> to render the graph.
</p>
<form action="" method="get" id="words-form">
<label for="words">Search for key words:</label>
<input id="words" type="text" name="words"/>
<input id="s" type="submit" name="s" value="Search"/>
</form>

<div id="chart"></div>
@endsection