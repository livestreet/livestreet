var lsProfilerClass = new Class({
	
	Implements: Options,
	
	options: { 
		img : {
			path: 		DIR_STATIC_SKIN+'/images/',
			openName:  	'open.gif', 
			closeName: 	'close.gif'
		},
		classes: {
			visible: 	'lsProfiler_visible',
			hidden:  	'lsProfiler_hidden',
			openImg:  	'lsProfiler_open',
			closeImg:  	'lsProfiler_close'
		},
		prefix: { 
			img: 'img_',
			td:  'report_'
		},
		path: {
			loadReport: aRouter['profiler']+'ajaxloadreport/'
		}
	},
	
	initialize: function(options){
		this.setOptions(options);
		this.make();
	},

	make: function(){
		var thisObj = this;
		var aImgFolding=$$('img.folding');
		aImgFolding.each(function(img, i){thisObj.makeImg(img);});
	},
		
	makeImg: function(img) {
		var thisObj = this;
		img.setStyles({
			'cursor'  : 'pointer',
			'display' : 'inline'
		});
		img.removeClass(this.options.classes.closeImg);
		img.addClass(this.options.classes.openImg);
		img.removeEvents('click');
		img.addEvent('click',function(){
			thisObj.toggleNode(img);
		});
	},
	
	toggleNode: function(img) {
		if (img.hasClass(this.options.classes.closeImg)) {	
			this.collapseNode(img);
		} else {
			this.expandNode(img);
		}
	},
	
	expandNode: function(img) {
		var thisObj = this;
		
		img.setProperties({'src': this.options.img.path + this.options.img.closeName});
		img.removeClass(this.options.classes.openImg);
		img.addClass(this.options.classes.closeImg);

		reportId=img.get('id').replace(this.options.prefix.img,this.options.prefix.td);
		var trReport = $(reportId);
		
		if(trReport){
			trReport.show();
		} else {
			thisObj.loadReport(img.getParent('tr'),reportId);
		}
	},
	
	loadReport: function(obj,reportId) {
		var thisObj=this;
		var trCurrent = obj;
		
		JsHttpRequest.query(
        	'POST '+thisObj.options.path.loadReport,
        	{ reportId: reportId, security_ls_key: LIVESTREET_SECURITY_KEY },
        	function(result, errors) {         		 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}
        		if (result.bStateError) {        			
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {
					var trReport=new Element('tr', {'id':reportId});
					trReport.adopt(new Element('td',{
						'colspan': 5,
						'html'   : result.sReportText
					}));
					trReport.inject(trCurrent,'after');
        		}                           
	        },
        	true
       );
	},
		
	collapseNode: function(img) {
		var thisObj = this;
		img.setProperties({'src': this.options.img.path + this.options.img.openName});
		img.removeClass(this.options.classes.closeImg);
		img.addClass(this.options.classes.openImg);

		reportId=img.get('id').replace(this.options.prefix.img,this.options.prefix.td);	
		var trReport = $(reportId);

		trReport.hide();
	}
});

var lsProfiler;

window.addEvent('domready', function() {
    lsProfiler = new lsProfilerClass({
    	img: {
    		path: DIR_STATIC_SKIN+'/images/'
    	},
    	classes: {
    		openImg:  'folding-open',
    		closeImg: 'folding'
    	}
    });
});