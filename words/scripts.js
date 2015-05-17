$(function() {
  $('#chart').html('<div id="loading"><i class="fa fa-cog fa-spin"></i><h2>Loading</h2></div>');
  console.log('getting json');
  $.getJSON('../api/'+window.location.search, function(data) {
    var deferreds = [];
    var legislators = [];
    for (var i = 0; i < data.results.length; i++) {
      var id = data.results[i].legislator;
      legislators.push(id);
      app.counts[id] = data.results[i].count;
      deferreds.push(app.async.getLegislatorData(id));
    }
    $.when.apply(null, deferreds).done(function() {
        // var data = {};
        var graph = {
          name: 'words',
          children: []
        };
        for (var id in app.data) {
          if (app.data[id].length > 0) {
            var datum = app.data[id][0]; 
            graph.children.push({
              name: id,
              children: [{
                name: datum.last_name+' ('+datum.party+' '+datum.chamber+' '+datum.state+')',
                size: app.counts[id]                
              }]
            });            
              // data[id] = datum;
              // data[id].count = app.counts[id];
          } else {
            app.fail.push(id);
          }

          // for (var id in data) {
          //   var datum = data[id];
 
          // }
        }
        $('#chart').html('');
        // console.log(graph);
        app.render(graph);
    });
  });
});

var app = {
  counts: {},
  data: {},
  fail: [],
  async: {
    getLegislatorData: function(id) {
      return $.getJSON('../api/?legislator='+id, function(data) {
        app.data[id] = {};
        if (data.results) {
          app.data[id] = data.results;
        } else {
          app.fail.push(id);
        }
        app.data[id].count = app.counts[id];
      });
    }
  },
  render: function(json) {
    var r = 960,
        format = d3.format(",d"),
        fill = d3.scale.category20c();

    var bubble = d3.layout.pack()
        .sort(null)
        .size([r, r])
        .padding(1.5);

    var vis = d3.select("#chart").append("svg")
        .attr("width", r)
        .attr("height", r)
        .attr("class", "bubble");


      var node = vis.selectAll("g.node")
          .data(bubble.nodes(classes(json))
          .filter(function(d) { return !d.children; }))
        .enter().append("g")
          .attr("class", "node")
          .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

      node.append("title")
          .text(function(d) { return d.className + ": " + format(d.value); });

      node.append("circle")
          .attr("r", function(d) { return d.r; })
          .style("fill", function(d) { return fill(d.packageName); });

      node.append("text")
          .attr("text-anchor", "middle")
          .attr("dy", ".3em")
          .text(function(d) { return d.className.substring(0, d.r / 3); });

    // Returns a flattened hierarchy containing all leaf nodes under the root.
    function classes(root) {
      var classes = [];

      function recurse(name, node) {
        if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
        else classes.push({packageName: name, className: node.name, value: node.size});
      }

      recurse(null, root);
      return {children: classes};
    }
  }    
};



//// respect:
//// http://jsfiddle.net/xsafy/


function render(json) {
  var r = 960,
      format = d3.format(",d"),
      fill = d3.scale.category20c();

  var bubble = d3.layout.pack()
      .sort(null)
      .size([r, r])
      .padding(1.5);

  var vis = d3.select("#chart").append("svg")
      .attr("width", r)
      .attr("height", r)
      .attr("class", "bubble");


    var node = vis.selectAll("g.node")
        .data(bubble.nodes(classes(json))
        .filter(function(d) { return !d.children; }))
      .enter().append("g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

    node.append("title")
        .text(function(d) { return d.className + ": " + format(d.value); });

    node.append("circle")
        .attr("r", function(d) { return d.r; })
        .style("fill", function(d) { return fill(d.packageName); });

    node.append("text")
        .attr("text-anchor", "middle")
        .attr("dy", ".3em")
        .text(function(d) { return d.className.substring(0, d.r / 3); });

  // Returns a flattened hierarchy containing all leaf nodes under the root.
  function classes(root) {
    var classes = [];

    function recurse(name, node) {
      if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
      else classes.push({packageName: name, className: node.name, value: node.size});
    }

    recurse(null, root);
    return {children: classes};
  }
}