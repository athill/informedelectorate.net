import * as d3 from "d3";
import { geoAlbersUsa, geoPath } from "d3-geo";
import { json } from 'd3-request';
import { format } from 'd3-format';
import { select, selectAll, event as currentEvent } from 'd3-selection';
import { scaleLinear } from 'd3-scale';

import { NETWORK_FAILURE_ALERT, LOADING_ICON, getParameterByName } from '../utils';

fetch('/data/statetax/data.min.json')
	.then(response => response.json())
	.then(data => {
		$(() => {			
			app(data);
		});
	})
	.catch(error => console.error('oops', error));


const dollars = format('$00');		//// format function for dollars

//// props
// https://derekswingley.com/2016/08/01/using-and-bundling-individual-d3-modules/

const app = (data) => {
	const $link = $('#link');
	const $stateMap = $('#state_map');
	
	const max = {};		//// maximum values for primary areas
	const min = {};
	const rgbs = {};
	const scales = {};	//// scaling functions by area
	const colorcode = {	//// color multipliers
		r: 0.2,
		g: 1,
		b: 0.1
	};

	var w = 400;
	const windowWidth = $(window).width();
	if (windowWidth < w) {
		w = windowWidth * 0.9;
	}
	var h = w * 0.625;

	renderOptions();

	$stateMap.html('<strong>Loading state map '+LOADING_ICON+'</strong>');

	//// build max map
	Object.keys(data).forEach(state => {
		Object.keys(data[state]).forEach(type => {
			if (!(type in max) || parseInt(data[state][type]) > parseInt(max[type])) {
				max[type] = data[state][type];
			}
			if (!(type in min) || parseInt(data[state][type]) < parseInt(min[type])) {
				min[type] = data[state][type];
			}			
		});
	});

	//// Build basic scaling functions
	Object.keys(max).forEach(type => {
		scales[type] = scaleLinear()
			.domain([0, max[type]])
			.range([255, 0])
	});

	const getAreas = () => $('input[name=option]:checked').map(function() { return $(this).val(); }).get();

	const areas = getAreas();

	//// set up tooltip
	const tooltip = d3.select("body").append("div")
	    .attr("class", "tooltip")
	    .style("opacity", 0);	

	const showTooltip = d => {
       tooltip.transition()
         .duration(100)
         .style("opacity", .9);
       tooltip.html(function() {
	  		const areas = getAreas();
	  		const name = d.properties.NAME;
	    	return getTooltip(name, areas);
		})
        .style("left", (currentEvent.pageX) + "px")
        .style("top", (currentEvent.pageY - 28) + "px");
	};  

	const hideTooltip = d => {
		tooltip.transition()
         .duration(500)
         .style("opacity", 0);
	}

	//Define map projection
	const projection = geoAlbersUsa()
	   .translate([w/2, h/2])
	   .scale([500]);	  

	//Define path generator
	const path = geoPath()
		.projection(projection);	
	
	const updateLink = areas => {
		if (!areas || !areas.length) {
			$link.html(`Select at least one checkbox to see state tax comparisons.`);
			return;
		}		
		const $container = $('#options-container');
		let query = $container.serialize();
		if (areas.includes('total')) {
			areas = ['total'];
			query = 'option=total';
		}
		$link.html(`Showing results for <a href="/statetax?${query}">${areas.join('+')}</a>`);	
	};

	//Load in GeoJSON data
	json('/data/statetax/states.min.json', function(json) {	
		$stateMap.html('');
		const areas = getAreas();
		updateLink(areas);
		//Create SVG element
		const svg = select("#state_map")
					.append("svg")
					.attr("width", w)
					.attr("height", h);		
		// 	Bind data and create one path per GeoJSON feature
		svg.selectAll("path")
		   .data(json.features)
		   .enter()
		   .append("path")
		   .attr("d", path)
		   .attr('class', 'state')
		   .attr('id', function(d) { return d.properties.NAME; })
		   .style('fill', datum => {
		   		return getRgb(datum.properties.NAME, areas);
		   })
		   .on('click', function(data, i) {
		   		const key = data.properties.NAME;
		   		showTooltip(data);
		   })		   
		   .on('mouseover', showTooltip)
		   .on('mouseout', hideTooltip)
	});

	//// Change option
	$('#interface-container').on('click', 'input[name=option]', function(e)  {
		const areas = getAreas();
		updateLink(areas);
		const $states = $states || $('.state');
		$states.each(function(i, elem) {
			const name = $(this).attr('id');
			$(this).css('fill', getRgb(name, areas));
		});
	});	

	const getScale = (areas, key)  => {
		if (!scales[key]) {
			var mx = areas.reduce(function(p, c) { 
				return p + parseInt(max[c]); 
			}, 0);
			var mn = areas.reduce(function(p, c) { 
				return p + parseInt(min[c]); 
			}, 0);			
			scales[key] = scaleLinear()	
								.domain([mn, mx])
								.range([255, 0]);	
		}
		return scales[key];
	}

	const getRgb = (name, area)  => {
		const key = area.join(':');
		if (!(name in data)) return {};
		const rgb = {};
		const areas = getAreas();
		//// create area in scales if it doesn't exist
		const scale = getScale(areas, key);
		////  rgb
		if (name in rgbs && key in rgbs[name]) {
			return rgbs[name][key];
		} else {
			var value = areas.reduce(function(p, c) {
				return p + parseInt(data[name][c]);
			}, 0);
			for (var color in colorcode) {
				rgb[color] = Math.floor(scale(value)*colorcode[color]);
			}
			var rgbstr = 'rgb('+rgb.r+','+rgb.g+','+rgb.b+')';
			if (!rgbs[name]) {
				rgbs[name] = {};
			}
			rgbs[name][key] = rgbstr;
			return rgbstr;
		}
	};

	const getTooltip = (name, areas) => {
		let value = name;
		if (name in data) {
			const total = areas.reduce(function(p, c) {
				return p + parseInt(data[name][c]);
			}, 0);
			value += ' - ' + dollars(total);
		} 
		return value;	
	};
}

const renderOptions = () => {
	//// check boxes
	const areas = ['Corporate', 'Income', 'Property', 'Sales', 'Total*'];
	const $container = $('#options-container');
	
	areas.forEach(area => {
		const $row = $('<div class="row" />');
		const id = area.replace(/[^a-zA-Z]/g, '').toLowerCase();
		const display = area;
		$row.append('<div class="col-xs-1"><input type="checkbox" name="option" value="'+id+'" id="'+id+'" /></div>');
		$row.append('<div class="col-xs-10"><label for="'+id+'">'+display+'</label></div>');
		$container.append($row);
	});

	const option = getParameterByName('option');
	let options = option ? Array.isArray(option) ? option : [option] : areas.slice(0, 4);
	options.forEach(area => $(`#${area.toLowerCase()}`).prop('checked', true));
}
