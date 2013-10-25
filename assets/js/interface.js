/* interface.js */

var GeoModel = Backbone.Model.extend({
	url: 'json.php'
});


var Interface = Backbone.View.extend({
	
	_map: null,
	
	initialize: function() {
		
		var mapOptions = {
			zoom: 7,
		    center: new google.maps.LatLng(53.726668, -127.647621),
		    mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		
		this.map = new google.maps.Map(document.getElementById('map'), mapOptions);
		
		
		this.model.bind("change", this.render, this);
		this.model.fetch();
		 
		 
	
		/*
		
			new google.maps.LatLng(37.782551, -122.445368)
		*/
		
		
		
		
	},

	render:function() {
		var data = this.model.toJSON();
		var self = this;
		
		
		
		var pointArray = new google.maps.MVCArray();
		heatmap = new google.maps.visualization.HeatmapLayer({
		    data: pointArray
	    });
		
		heatmap.setMap(this.map);
               
		
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
	var objGeoModel = new GeoModel;
	
	var objInterface = new Interface({
		model: objGeoModel
	});
});

