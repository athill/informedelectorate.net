import * as d3 from "d3";
import tip  from "d3-tip";
import { geoAlbersUsa, geoPath } from "d3-geo";
import { json } from 'd3-request';
import { format } from 'd3-format';
import { select, selectAll } from 'd3-selection';
import { scaleLinear } from 'd3-scale';


//// retrieve data files asynchronously
const promises = [
	fetch('/data/statetax/data.json'),
	fetch('/data/statetax/states.json'),
];

fetch('/data/statetax/states.json')
	.then(response => response.json())
	.then(data => {
		$(() => {
			app(data);
		});
	})
	.catch(error => console.error('oops', error));


var w = 400;
var h = 250;

//// props
// https://derekswingley.com/2016/08/01/using-and-bundling-individual-d3-modules/

const app = (data) => {
	const max = {};		//// maximum values for primary areas
	const scales = {};	//// scaling functions by area
	const colorcode = {	//// color multipliers
		r: 0.2,
		g: 1,
		b: 0.1
	};
	const dollars = format('00');		//// format function for dollars
	const width = 400;
	const height = 250;
	console.log('here');
	//// build max map
	Object.keys(data).forEach(state => {
		Object.keys(data[state]).forEach(type => {
			if (!(type in max) || parseInt(data[state][type]) > parseInt(max[type])) {
				max[type] = data[state][type];
			}
		});
	});

	//// Build basic scaling functions
	Object.keys(max).forEach(type => {
		scales[type] = scaleLinear()
			.domain([0, max[type]])
			.range([255, 0])
	});

	//// set up tooltip
	const tooltip = tip()
	  .attr('class', 'd3-tip')
	  .offset([0, -10])
	  .html(function(d) {
	  	const area = $('input[name=option]:checked').val();
	  	const name = d.properties.NAME;
	    return getTooltip(data, name, area);
	});

	//Define map projection
	const projection = geoAlbersUsa()
	   .translate([w/2, h/2])
	   .scale([500]);	  

	//Define path generator
	const path = geoPath()
		.projection(projection);

	// //// area
	const area = $('input[name=option]:checked').val();
	
	//Create SVG element
	const svg = select("#state_map")
				.append("svg")
				.attr("width", w)
				.attr("height", h);

	// //// Initialize tooltip
	// svg.call(tooltip);

	//Load in GeoJSON data
	json('/data/statetax/states.json', function(json) {	
	// 	Bind data and create one path per GeoJSON feature
		svg.selectAll("path")
		   .data(json.features)
		   .enter()
		   .append("path")
		   .attr("d", path)
		   .attr('class', 'state')
		   .attr('id', function(d) { return d.properties.NAME; })
		   .style("fill", function(d) {
		   		var name = d.properties.NAME;
		   		return getRgb(data, name, area)
		   	  })
		   // .on('mouseover', tooltip.show)
		   // .on('mouseout', tooltip.hide)
	});

	//// Change option
	$('#interface-container').on('click', 'input[name=option]', function(e)  {
		var area = $('input[name=option]:checked').val();
		var $states = $states || $('.state');
		$states.each(function(i, elem) {
			var name = $(this).attr('id');
			$(this).css('fill', getRgb(data, name, area));
		});
	});		
}

const getRgb = (data, name, area) => {
	if (!(name in data)) return {};
	var rgb = {};
	var areas = area.split('+');
	//// create area in scales if it doesn't exist
	if (!(area in scales)) {
		var mx = areas.reduce(function(p, c) { 
			return p + parseInt(max[c]); 
		}, 0);
		scales[area] = scaleLinear()	
							.domain([0, mx])
							.range([255, 0]);
	}
	//// build rgb
	var value = areas.reduce(function(p, c) {
		return p + parseInt(data[name][c]);
	}, 0);
	for (var color in colorcode) {
		rgb[color] = Math.floor(scales[area](value)*colorcode[color]);
	}
	var rgbstr = 'rgb('+rgb.r+','+rgb.g+','+rgb.b+')';
	console.log(rgbstr);
	return rgbstr;
}

const getTooltip = (data, name, area) => {
	var value = name;
	if (name in data) {
		var areas = area.split('+');
		var total = areas.reduce(function(p, c) {
			return p + parseInt(data[name][c]);
		}, 0);
		value += ' - ' + dollars(total);
	} 
	return value;	
}