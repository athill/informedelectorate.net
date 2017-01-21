@extends('app')

@section('content')
<h1>State Taxes</h1>
<p>Use the radio buttons on the right to view the various combinations of per capita state and local tax burdens. Lighter shades indicate lower taxes. Hover over a state to see its per capita tax burden for the selected combination. Except for Total, I've offered all combinations of the other fields to see how various tax types accumulate when combined.</p>
<table id="interface-container">
<tbody>
<tr>
	<td id="state_map"></td>
	<td>
	<h5>Category</h5>
	<table 1>
	<tbody>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate" id="option_corporate"/>
		<label for="option_corporate">Corporate</label>
		</td>
		<td>
		<input type="radio" name="option" value="income+property" id="option_income+property"/>
		<label for="option_income+property">Income+Property</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+income" id="option_corporate+income"/>
		<label for="option_corporate+income">Corporate+Income</label>
		</td>
		<td>
		<input type="radio" name="option" value="income+property+sales" id="option_income+property+sales"/>
		<label for="option_income+property+sales">Income+Property+Sales</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+income+property" id="option_corporate+income+property"/>
		<label for="option_corporate+income+property">Corporate+Income+Property</label>
		</td>
		<td>
		<input type="radio" name="option" value="income+sales" id="option_income+sales"/>
		<label for="option_income+sales">Income+Sales</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+income+sales" id="option_corporate+income+sales"/>
		<label for="option_corporate+income+sales">Corporate+Income+Sales</label>
		</td>
		<td>
		<input type="radio" name="option" value="income+sales+property+corporate" checked="checked" id="option_income+sales+property+corporate"/>
		<label for="option_income+sales+property+corporate">Income+Sales+Property+Corporate</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+property" id="option_corporate+property"/>
		<label for="option_corporate+property">Corporate+Property</label>
		</td>
		<td>
		<input type="radio" name="option" value="property" id="option_property"/>
		<label for="option_property">Property</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+property+sales" id="option_corporate+property+sales"/>
		<label for="option_corporate+property+sales">Corporate+Property+Sales</label>
		</td>
		<td>
		<input type="radio" name="option" value="property+sales" id="option_property+sales"/>
		<label for="option_property+sales">Property+Sales</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="corporate+sales" id="option_corporate+sales"/>
		<label for="option_corporate+sales">Corporate+Sales</label>
		</td>
		<td>
		<input type="radio" name="option" value="sales" id="option_sales"/>
		<label for="option_sales">Sales</label>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="option" value="income" id="option_income"/>
		<label for="option_income">Income</label>
		</td>
		<td>
		<input type="radio" name="option" value="total" id="option_total"/>
		<label for="option_total">Total</label>
		</td>
	</tr>
	<tr>
	</tr>
	</tbody>
	</table>
	</td>
</tr>
</tbody>
</table>
<p>
<small>Source: US Census Bureau, 2011 reports.<br />
* The US Census Bureau does not classify revenue from Texas's margin tax as corporate income tax revenue.<br />
Note: "$0" means no tax was collected or the amount was too insignificant to count.<br />
State Tax Collections per Capita by Category, 2011</small>
</p>
<p>I found <a href="http://mercatus.org/publication/primer-state-and-local-tax-policy-trade-offs-among-tax-instruments" target="_blank">this report from Mercatus on the Trade-Offs among Tax Instruments</a> and found it interesting. The report is more than these numbers, looking at various tax instruments in <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_summary_v1(KP).pdf" target="_blank">terms of revenue collected, collection costs, fairness, transparency, and minimizing distortions to the economy</a> The actual data used here is from <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_v2.pdf" target="_blank">page 33 of this PDF</a>. I believe Mr. Ross got his numbers by merging U.S. Census <a href="http://www.census.gov/govs/statetax/" target="_blank">state government tax collections</a> with <a href="http://www.census.gov/popest/data/state/totals/2013/index.html" target="_blank">state populations</a> among other data. There are taxes in the Total value not covered in the other categories. For example, Alaska has corportate taxes of $1003 per capita, income taxes of $0 per capita, sales tax of $0 per capita, and property tax of $255 per capita, yet has a total of $7708 per capita. So $6550, according to  <a href="http://en.wikipedia.org/wiki/State_tax_levels_in_the_United_States#State_Government_Tax_Collections_.282012.29" target="_blank">this Wikipedia table </a>, comes from license, inheritance, gift, and severance taxes.</p>
<p>This was created using the <a href="http://d3js.org/" target="_blank">D3</a>, <a href="https://github.com/caged/d3-tip" target="_blank">d3-tip</a>, and <a href="http://jquery.com/" target="_blank">jQuery</a> libraries and the <a href="http://geojson.org/" target="_blank">GeoJson</a> encoding format. I learned a lot of what is used here in the book <a href="http://shop.oreilly.com/product/0636920026938.do" target="_blank"> Interactive Data Visualization for the Web </a>.</p>

@endsection