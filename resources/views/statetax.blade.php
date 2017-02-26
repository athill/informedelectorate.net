@extends('app')

@section('content')
<h2>State Taxes</h2>
<p>The data is out of date, but interesting in a <a href="https://en.wikipedia.org/wiki/Laboratories_of_democracy" target="_blank">laboratories of democracy</a> way. Use the radio buttons to view the various combinations of per capita state and local tax burdens. Lighter shades indicate lower taxes. Click on a state (hover on desktop) to see its per capita tax burden for the selected combination. Except for Total, I've offered all combinations of the other fields to see how various tax types accumulate when combined.</p>

<div class="row" id="interface-container">
    <div class="col-md-6 col-xs-12" id="state_map"></div>
    <div class="col-md-6 col-xs-12">
        <h5>Category</h5>
        <div id="link"></div>
        <div class="row" id="options-container"></div>
    </div>
</div>

<p>
<small>Source: US Census Bureau, 2011 reports.<br />
* The US Census Bureau does not classify revenue from Texas's margin tax as corporate income tax revenue.<br />
Note: "$0" means no tax was collected or the amount was too insignificant to count.<br />
State Tax Collections per Capita by Category, 2011</small>
</p>

<p>I found <a href="http://mercatus.org/publication/primer-state-and-local-tax-policy-trade-offs-among-tax-instruments" target="_blank">this report from Mercatus on the Trade-Offs among Tax Instruments</a> and found it interesting. The report is more than these numbers, looking at various tax instruments in <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_summary_v1(KP).pdf" target="_blank">terms of revenue collected, collection costs, fairness, transparency, and minimizing distortions to the economy</a> The actual data used here is from <a href="http://mercatus.org/sites/default/files/Ross_PrimerTaxPolicy_v2.pdf" target="_blank">page 33 of this PDF</a>. I believe Mr. Ross got his numbers by merging U.S. Census <a href="http://www.census.gov/govs/statetax/" target="_blank">state government tax collections</a> with <a href="http://www.census.gov/popest/data/state/totals/2013/index.html" target="_blank">state populations</a> among other data. There are taxes in the Total value not covered in the other categories. For example, Alaska has corportate taxes of $1003 per capita, income taxes of $0 per capita, sales tax of $0 per capita, and property tax of $255 per capita, yet has a total of $7708 per capita. So $6550, according to  <a href="http://en.wikipedia.org/wiki/State_tax_levels_in_the_United_States#State_Government_Tax_Collections_.282012.29" target="_blank">this Wikipedia table </a>, comes from license, inheritance, gift, and severance taxes.</p>

<p>This was created using the <a href="http://d3js.org/" target="_blank">D3</a> and <a href="http://jquery.com/" target="_blank">jQuery</a> libraries and the <a href="http://geojson.org/" target="_blank">GeoJson</a> encoding format. I learned a lot of what is used here in the book <a href="http://shop.oreilly.com/product/0636920026938.do" target="_blank"> Interactive Data Visualization for the Web </a>.</p>
@endsection
