/* interface.js */

var GeoModel = Backbone.Model.extend({
	url: 'json.php'
});

var StationModel = Backbone.Model.extend({
	url: 'json.php'
})


var Interface = Backbone.View.extend({
	
	_map: null,
	
	initialize: function() {
				
		var mapOptions = {
			zoom: 7,
		    center: new google.maps.LatLng(53.726668, -127.647621),
		    mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		
		this._map = new google.maps.Map(d3.select("#map").node(), mapOptions);
		
		
		this.model.geoModel.bind("change", this.renderHeatmap, this);
		this.model.geoModel.fetch({
			"data":{"dataset":"frequency"}
		});
		
		
		this.model.stationModel.bind("change", this.renderStations, this);
		this.model.stationModel.fetch({
			"data":{"dataset":"stations"}
		});
		
		
		
	},
	
	renderStations:function() {
	
		var data = this.model.stationModel.toJSON();
		var overlay  = new google.maps.OverlayView();
		
		
		
		
		
		
		
		//for(var i = 0; i < data.dataset.length; i++) {
			//var point = data.dataset[i].point;
			//console.log(point);
			
			//var _data = data.dataset[i];
			
			overlay.onAdd = function() {
				
				var layer = d3.select(this.getPanes().overlayLayer).append("div").attr("class", "stations");
				
				
				
				overlay.draw = function() {
					
					
					
					var projection = this.getProjection();
					var padding = 10;
					
					//console.log(_data.point);
					
					var marker = layer.selectAll("svg")
				          .data(d3.entries(data.dataset))
				          .each(transform) // update existing markers
				        .enter().append("svg:svg")
				          .each(transform)
				          .attr("class", "marker");
				          
					marker.append("svg:line")
			          .attr("x1", 0)
			          .attr("y1", 0)
			          //.attr("x2", 0)
			          .attr("y2", 100)
			          .attr("stroke-width", 4)
			          .attr("stroke", "white").attr("transform", function(d) {
			          	var direction = parseInt(d.value.wind_data[0].wind.direction, 10);
			          	console.log(direction);
			          	
			          	return "rotate(" + direction + " 50 50)"
			          }).call(lineAnimate);
			          
			          
					function transform(d) {
						
						
						
						d = new google.maps.LatLng(parseFloat(d.value.point.lat), parseFloat(d.value.point.lng));
						d = projection.fromLatLngToDivPixel(d);
					
						
						return d3.select(this)
							.style("left", (d.x - padding) + "px")
							.style("top", (d.y - padding) + "px");
						
						
					}
					
					
					
					function lineAnimate(selection) {
					
						
					    selection
					    .style('opacity', 0.5)
					    .transition()
					        .ease('linear')
					        .duration(1000)
					        .delay(function(d) {
					        	console.log(d);
					        	//return d*10;
					        	return 100;
					        })
					        .attr('x2', 1000)
					    .transition()
					        .duration(1000)
					        .style('opacity', 0)
					    .each('end', function() {d3.select(this).call(lineAnimate)});
					}
					
					
				}
				
				
			}
			
			overlay.setMap(this._map);
			
			
		//}
	},

	renderHeatmap:function() {
		
		
		
		var data = this.model.geoModel.toJSON();
		var self = this;
		
		
		
		var pointArray = new google.maps.MVCArray();
		heatmap = new google.maps.visualization.HeatmapLayer({
		    data: pointArray
	    });
		
		heatmap.setMap(this._map);
               
		
		// I decided to chunk the data
		for(var i = 0; i < data.dataset.length; i++) {
			// var p = new Parallel([ data.dataset[i], self.taxiData ]);
			// 
			setTimeout(
				(function(objData) {
					return function() {
						for(var j = 0; j < objData.process.length; j++) {
							pointArray.push(new google.maps.LatLng(  parseFloat(objData.process[j].point.lat), parseFloat(objData.process[j].point.lng) ))
						}
					}
			})(data.dataset[i]), 1);
     		
		}
		
		
	}
	
	
});

google.maps.event.addDomListener(window, 'load', function() {
	
	
	//var model = new Backbone.Model();
	//model.set();
	
	var objInterface = new Interface({
		model: {geoModel: new GeoModel(), stationModel: new StationModel()}
	});
	
});

