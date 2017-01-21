@extends('app')

@section('content')
<form action="" method="get" id="statebills-form">
	<label for="state">Select a state:</label>
	<select id="state" name="state">
		<option value=""></option>
	</select>
	<input id="s" type="submit" name="s" value="View Bills"/>
</form>

<div id="results"></div>
@endsection