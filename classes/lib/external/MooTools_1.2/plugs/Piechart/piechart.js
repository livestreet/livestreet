/*
Script: piechart.js

Dependencies:
	mootools-beta-1.2b2.js
	moocanvas.js

Author:
	Greg Houston, <http://greghoustondesign.com/>

Credits:
	Based on Stoyan Stefanov's Canvas Pie:< http://www.phpied.com/canvas-pie/>
	Color algorithm inspired by Jim Bumgardner:<http://www.krazydad.com/makecolors.php>

License:
	MIT License, <http://en.wikipedia.org/wiki/MIT_License>	
	
Example Usage:
	
<table class="pieChart">
    <tr><th>Browser</th> <th>Value</th></tr>
    <tr><td>Safari </td> <td>180  </td></tr>
    <tr><td>Firefox</td> <td>210  </td></tr>
    <tr><td>IE     </td> <td>30   </td></tr>
    <tr><td>Opera  </td> <td>120  </td></tr>
</table>

*/


var PieChart = new Class({
	options: {
		pieChartRadius: 60,  // Half the width of your pie chart
    	td_label_index: 0,    // which TD contains the label			
    	td_index: 1,           // which TD contains the data	
    	index: 1	
	},
	initialize: function(elData,el,options){
		this.setOptions(options);
		
		// Initialize variables
		this.pieChartRadius = this.options.pieChartRadius;	
		this.pieChartDiameter = this.pieChartRadius * 2;
		this.pieVertices = 12; // Does not include the center vertex
		this.arcIncrementMultiplier = 1 / this.pieVertices;
		this.index = 0;
		this.tableIndex = this.options.index;
		this.areaIndex = 1;
		this.canvas = '';
		this.container = '';
		this.data_table = '';
		
		
		this.insertElements(el);		
    	aData=this.makeData(elData);		
		this.makePieChart(aData);		
		this.addToolTips();		
	},
	insertElements: function(el){
		
    	///// STEP 1 - Insert HTML Elements
	
		// Insert a div that will contain both the pie chart and it's legend
		this.container = new Element('div', {
			id: 'pieChartContainer' + this.tableIndex
		}).injectBefore($(el)).addClass('pieChartContainer');

		// Pull the table out of the page and put it in the pie chart container
		this.data_table = el.clone().injectBottom(this.container);
		el.dispose();
		
		// Insert a div that will contain both the pie chart canvas and it's image map overlay		
		new Element('div', {
			id: 'pieChartWrapper' + this.tableIndex
		}).injectTop(this.container).addClass('pieChartWrapper');
		
		// Insert the canvas to draw the pie on
		this.canvas = new Element('canvas', {
			id: 'canvas' + this.tableIndex,
			width: this.pieChartDiameter,
			height: this.pieChartDiameter
		}).injectInside(this.container.getElement('.pieChartWrapper')).addClass('pieChartCanvas');
		
		// Insert the map element. The area elements will be added later
		new Element('map', {		
			id: 'pieChartMap' + this.tableIndex,
			name: 'pieChartMap' + this.tableIndex
		}).injectBottom(this.container).addClass('pieChartMap');
		
		// Insert the blank transparent gif that is used for the image map
		new Asset.image(DIR_STATIC_SKIN+'/images/spacer.gif', {
			alt: '',
			usemap: '#pieChartMap' + this.tableIndex,
			width: this.pieChartDiameter,
			height: this.pieChartDiameter
		}).injectInside(this.container.getElement('.pieChartWrapper')).setStyles({
				'width': this.pieChartDiameter,
				'height': this.pieChartDiameter
			});

		// Insert a div to insure layout integrity
		new Element('div').injectBottom(this.container).addClass('clear');
	
	},
	makeData: function(elData) {
		var aData=[];
		elData.getChildren().each(function(el) {
			var data=el.getChildren();
			if ($(data[0])) {
				var color = data[0].getStyle('background').match(/#\w+/i);
				aData.extend([{
					'label': el.get('text'),
    				'value': data[1].get('text'),
    				'color': data[0].getStyle('background').match(/#\w+/i)[0]
				}]);			
			}
		});
		return aData;
	},	
	makePieChart: function(aData){

    	///// STEP 2 - Get the data    	
    	var  data = [], color, colors = [], labels = [], values = [], total = 0, value=0; 	
    	aData.each(function(item, index){    		
			labels[colors.length] = item.label;       	
			value=parseFloat(item.value);
			values[colors.length] = value;
        	total += value;       	
        	colors[colors.length] = item.color.hexToRgb();           	
		},this);		
    	
    	///// STEP 3 - Draw pie on canvas

    	// get canvas context, determine radius and center
    	var ctx = this.canvas.getContext('2d');

		var tableLength = colors.length;
    	for (piece=0; piece < tableLength; piece++) {

        	var thisvalue = values[piece] / total;		
			var arcStartAngle = Math.PI * (- 0.5 + 2 * this.index); // -0.5 sets set the start to be top
			var arcEndAngle = Math.PI * (- 0.5 + 2 * (this.index + thisvalue));		

			ctx.beginPath();
			ctx.moveTo(this.pieChartRadius, this.pieChartRadius); // Center of the pie
			ctx.arc(  // draw next arc
            	this.pieChartRadius,
            	this.pieChartRadius,
            	this.pieChartRadius,
            	arcStartAngle,
            	arcEndAngle,
            	false
        	);
        	ctx.closePath();		
        	ctx.fillStyle = colors[piece]; // Color
        	ctx.fill();
		
			// Draw the same thing again to draw stroke properly in IE
			ctx.lineWidth = 1;
        	ctx.beginPath();
        	ctx.moveTo(this.pieChartRadius, this.pieChartRadius);

        	ctx.arc(  // draw next arc
            	this.pieChartRadius,
            	this.pieChartRadius,
            	this.pieChartRadius,
            	arcStartAngle, 
            	arcEndAngle,
            	false
        	);
        	ctx.closePath();		
			ctx.strokeStyle = "#FFF";
			ctx.stroke();

    		///// STEP 4 - Draw pie on image map
		
			var arcIncrement = (arcEndAngle - arcStartAngle) * this.arcIncrementMultiplier;
		
			var xx = this.pieChartRadius + Math.round(Math.cos(arcStartAngle) * this.pieChartRadius);
			var yy = this.pieChartRadius + Math.round(Math.sin(arcStartAngle) * this.pieChartRadius);
			
			var coord = [];
			var coordIndex = 1;
						
			for (i = 0; i < ((this.pieVertices * 2) - 2); i = i+2) {				
				var arcAngle = arcStartAngle + arcIncrement * coordIndex;
				coord[i] = this.pieChartRadius + Math.round(Math.cos(arcAngle) * this.pieChartRadius);				
				coord[i+1] = this.pieChartRadius + Math.round(Math.sin(arcAngle) * this.pieChartRadius);
				coordIndex++;			
			}
		
			var xxEnd = this.pieChartRadius + Math.round(Math.cos(arcEndAngle) * this.pieChartRadius);
			var yyEnd = this.pieChartRadius + Math.round(Math.sin(arcEndAngle) * this.pieChartRadius);			

			var myArea = 'area' + this.tableIndex + '-' + piece;
		
			new Element('area', {
				'id': myArea ,
				'shape': 'poly',
				'coords': this.pieChartRadius + ',' + this.pieChartRadius + ',' + xx + ',' + yy + ',' + coord.join(',') +  ',' + xxEnd + ',' + yyEnd,
				'title': labels[piece]
			}).injectInside(this.container.getElement('.pieChartMap'));

        	this.index += thisvalue; // increment progress tracker
    	}

	this.tableIndex++;
    	
	},
	addToolTips: function(){    

    		///// STEP 5 - Add Tooltips

			new Tips($$(document.getElementsByTagName('area')), {
				showDelay: 10,
				hideDelay: 10
			});	
			
	},
	getColor: function(i, totalSteps){
		var colori = i * 100 / totalSteps;		
		var frequency = Math.PI*2 / totalSteps;
		var center = 190;
		var amplitude = 60;
        var rgb = [];	
		rgb[0]   = Math.round(Math.sin(frequency * i + 0) * amplitude + center);
		rgb[1] = Math.round(Math.sin(frequency * i + 2) * amplitude + center);
		rgb[2]  = Math.round(Math.sin(frequency * i + 4) * amplitude + center);	
		return 'rgb(' + rgb.join(',') + ')';
	}	
});
PieChart.implement(new Options);