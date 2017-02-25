@extends('app')

@section('content')
<h2>State Bills</h2>
<form action="" method="get" id="statebills-form">
	<label for="state">Select a state:</label>
	<select id="state" name="state">
		<option value=""></option>
	</select>
</form>

<div id="results"></div>
@endsection