@extends('app')

@section('content')
		<h1>Find Your Representatives</h1>
			<p>
			I use <a href="https://developers.google.com/maps/" target="_blank">Google Maps API</a> to translate the address to 
	latitude and longitude and then use data from <a href="http://sunlightfoundation.com/api/" target="_blank">the 
	Sunlight Foundation</a>.
			</p>
			<form action="" method="get">
			<label for="addr">Search by address:</label>
			<input id="addr" type="text" name="addr"/>
			<input id="s" type="submit" name="s" value="Search"/>
			</form>
@endsection