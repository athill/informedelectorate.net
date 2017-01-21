@extends('app')

@section('content')
<form action="" method="get">
	<label for="state">Select a state:</label>
	<select id="state" name="state">
		<option value=""></option>
	</select>
	<input id="s" type="submit" name="s" value="View Bills"/>
</form>
@endsection