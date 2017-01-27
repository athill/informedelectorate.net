import param from 'jquery-param';

$(() => {
  const $input = $('#words');
  const $wordsForm = $('#words-form');

  $wordsForm.submit(function(e) {
    e.preventDefault();
    alert('This form does not work, as the Capitol Words API is not functional.');
    return;
    const value = $input.val();
    if (value.trim() === '') {
      return;
    }
    $('#chart').html('<div id="loading"><h2>Loading</h2><i class="fa fa-cog fa-spin fa-2x"></i></div>');
    
    $.getJSON(`/api/words?words=${encodeURIComponent(value)}`, function(data) {
      var legislators = [];
      var ids = [];
      var params = {};
      for (var i = 0; i < data.results.length; i++) {
        var id = data.results[i].legislator;
        legislators.push(id);
        app.counts[id] = data.results[i].count;
        ids.push(id);
      }
      params.legislators = ids.join(',');
      console.log(params);
      $.getJSON('/api/words/?', param(params), function (data) {
          var graph = {
            name: 'words',
            children: []
          };
          ids.forEach(function(id) {
            if (data[id]) {
              var datum = data[id]; 
              graph.children.push({
                name: id,
                children: [{
                  name: datum.last_name+' ('+datum.party+' '+datum.chamber+' '+datum.state+')',
                  size: app.counts[id]                
                }]
              });
            } else {
              app.fail.push(id);
            }
          });
          $('#chart').html('');
          app.render(graph);
      });
    });
  });

});

var app = {
  counts: {},
  data: {},
  fail: [],
  //// respect:
  //// http://jsfiddle.net/xsafy/  
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