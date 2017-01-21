@extends('app')

@section('content')
<h2>Key Words</h2>
<p>
Use this page to see how many times legislators have used key terms. For example, "free market" or "iraq". 
Uses the Sunlight <a href="http://sunlightfoundation.com/api/" target="_blank">Capital Words API</a> for the 
data and <a href="http://d3js.org/" target="_blank">D3.js</a> to render the graph.
</p>
<form action="" method="get">
<label for="words">Search for key words:</label>
<input id="words" type="text" name="words"/>
<input id="s" type="submit" name="s" value="Search"/>
</form>
@endsection