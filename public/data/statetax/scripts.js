$(function() {

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
	//// default selection
	const $selected = $('#option_'+areas.join('-'));
	$selected.prop('checked', true);


	$.getJSON('/data/statetax/data.json', function(data) {
		var max = {};		//// maximum values for primary areas
		var scales = {};	//// scaling functions by area
		var colorcode = {	//// color multipliers
			r: 0.2,
			g: 1,
			b: 0.1
		};
		var dollars = d3.format('$0,0');


		var w = 400;
		const windowWidth = $(window).width();
		if (windowWidth < w) {
			w = windowWidth * 0.9;
		}
		var h = w * 0.625;

		//// set up tooltip
		var tip = d3.tip()
		  .attr('class', 'd3-tip')
		  .offset([0, -10])
		  .html(function(d) {
		  	var area = $('input[name=option]:checked').val();
		  	var name = d.properties.NAME;
		    return getTooltip(name, area);
		});

		//Define map projection
		var projection = d3.geo.albersUsa()
							   .translate([w/2, h/2])
							   .scale([w * 1.25]);

		//Define path generator
		var path = d3.geo.path()
						 .projection(projection);

		//// Build max map	
		for (var state in data) {
			for (var type in data[state]) {
				if (!(type in max) || parseInt(data[state][type]) > parseInt(max[type])) {
					max[type] = data[state][type];
				}
			}
		}



		//// Build basic scaling functions
		for (var type in max) {
			scales[type] = d3.scale.linear()
									.domain([0, max[type]])
									.range([255, 0])
		}
		//// area
		var area = $('input[name=option]:checked').val();

		//Create SVG element
		var svg = d3.select("#state_map")
					.append("svg")
					.attr("width", w)
					.attr("height", h);
		//// Initialize tooltip
		svg.call(tip);

		//Load in GeoJSON data
		d3.json("/data/statetax/states.json", function(json) {
			
			//Bind data and create one path per GeoJSON feature
			svg.selectAll("path")
			   .data(json.features)
			   .enter()
			   .append("path")
			   .attr("d", path)
			   .attr('class', 'state')
			   .attr('id', function(d) { return d.properties.NAME; })
			   .style("fill", function(d) {
			   		var name = d.properties.NAME;
			   		return getRgb(name, area)
			   	})
			   .on('mouseover', tip.show)
			   .on('mouseout', tip.hide)
		});

		//// Change option
		$('#interface-container').on('click', 'input[name=option]', function(e)  {
			var area = $('input[name=option]:checked').val();
			var $states = $states || $('.state');
			$states.each(function(i, elem) {
				var name = $(this).attr('id');
				$(this).css('fill', getRgb(name, area));
			});
		});	

		function getRgb(name, area) {

			if (!(name in data)) return {};
			const rgb = {};
			const areas = area.toLowerCase().split('+');
			//// create area in scales if it doesn't exist
			if (!(area in scales)) {
				var mx = areas.reduce(function(p, c) { 
					return p + parseInt(max[c]); 
				}, 0);
				scales[area] = d3.scale.linear()	
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
		}

		function getTooltip(name, area) {
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

	});
});
