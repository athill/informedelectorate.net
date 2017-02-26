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
		});
	});

	//// Build basic scaling functions
	Object.keys(max).forEach(type => {
		scales[type] = scaleLinear()
			.domain([0, max[type]])
			.range([255, 0])
	});

	const getArea = () => $('input[name=option]:checked').val();

	const area = getArea();

	//// set up tooltip
	const tooltip = d3.select("body").append("div")
	    .attr("class", "tooltip")
	    .style("opacity", 0);	

	const showTooltip = d => {
       tooltip.transition()
         .duration(100)
         .style("opacity", .9);
       tooltip.html(function() {
	  		const area = getArea();
	  		const name = d.properties.NAME;
	    	return getTooltip(name, area);
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
	
	const updateLink = area => $link.html(`Showing results for <a href="/statetax?option=${area}">${area}</a>`);

	//Load in GeoJSON data
	json('/data/statetax/states.min.json', function(json) {	
		$stateMap.html('');
		updateLink(area);
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
		   		return getRgb(datum.properties.NAME, area);
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
		const area = $('input[name=option]:checked').val();
		updateLink(area);
		const $states = $states || $('.state');
		$states.each(function(i, elem) {
			const name = $(this).attr('id');
			$(this).css('fill', getRgb(name, area));
		});
	});	

	const getRgb = (name, area)  => {
		if (!(name in data)) return {};
		const rgb = {};
		const areas = area.toLowerCase().split('+');
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
		return rgbstr;
	};

	const getTooltip = (name, area) => {
		var value = name;
		if (name in data) {
			var areas = area.toLowerCase().split('+');
			var total = areas.reduce(function(p, c) {
				return p + parseInt(data[name][c]);
			}, 0);
			value += ' - ' + dollars(total);
		} 
		return value;	
	};
}

const renderOptions = () => {
	//// radio buttons
	const areas = ['Corporate', 'Income', 'Property', 'Sales'];
	const combos = [];
	for (let i = 0; i < areas.length; i++) {
		const area1 = areas[i];
		combos.push(area1);
		for (let j = i+1; j < areas.length; j++) {
			combos.push(area1+'+'+areas[j]);
			if (j < areas.length) {
				for (let k = j+1; k < areas.length; k++) {
					combos.push(area1+'+'+areas[j]+'+'+areas[k]);
					if (k < areas.length) {
						for (let m = k+1; m < areas.length; m++) {
							combos.push(area1+'+'+areas[j]+'+'+areas[k]+'+'+areas[m]);
						}
					}
				}

			}
		}
	}
	combos.push('Total');

	const $container = $('#options-container');
	const middle = Math.ceil(combos.length / 2);
	const left = combos.slice(0, middle);
	const right = combos.slice(middle, combos.length);
	

	[left, right].forEach(function(column) {
		const $column = $('<div class="col-md-6 col-xs-12" />');
		column.forEach(function(combo) {
			const $row = $('<div class="row" />');
			const id = 'option_'+combo.replace(/\+/g, '-');
			// const display = combo.replace(/\+/g, '+\u200B');
			const display = combo;
			$row.append('<div class="col-xs-1"><input type="radio" name="option" value="'+combo+'" id="'+id+'" /></div>');
			$row.append('<div class="col-xs-10"><label for="'+id+'">'+display+'</label></div>');
			$column.append($row);
		});
		$container.append($column);
	});
	const option = getParameterByName('option');
	let $selected;
	if (option) {

		$selected = $(`input[value="${option.replace(/ /g, '+')}"]`);
	} else {
		$selected = $('#option_'+areas.join('-'));
	}
	$selected.prop('checked', true);		
}



