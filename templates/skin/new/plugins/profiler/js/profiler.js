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
			closeImg:  	'lsProfiler_close',
			treeNode:   'lsProfiler_tree',
			filterNode: 'lsProfiler_filter'
		},
		prefix: {
			img: 'img_',
			td:  'report_',
			entry: 'entry_',
			tree: 'tree_',
			treeNode: 'tree_node_'
		},
		path: {
			loadReport: aRouter['profiler']+'ajaxloadreport/',
			loadEntries: aRouter['profiler']+'ajaxloadentriesbyfilter/'
		}
	},
	
	initialize: function(options){
		this.setOptions(options);
		this.make();
	},

	make: function(expandClass){
		var thisObj = this;
		var aImgFolding=(!expandClass)?$$('img.folding'):$$('img.folding.'+expandClass);
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
		
		if(img.hasClass(thisObj.options.classes.treeNode)) {
			// Это элемент дерева - обрабатываем его соответствующим образом
			ids = img.get('id').replace(this.options.prefix.tree,'').split('_');
			
			reportId=ids[0];
			var trReportId=this.options.prefix.treeNode+ids[0]+'_'+ids[1];
			var trReport=$(trReportId);
			var parentId=ids[1];
		} else {
			reportId=img.get('id').replace(this.options.prefix.img,this.options.prefix.td);
			var trReport = $(reportId);
			var parentId = 0;
			var trReportId = 0;
		}
		
		if(trReport){
			trReport.show();
		} else {
			thisObj.loadReport(img.getParent('tr'),reportId,parentId,trReportId);
		}
	},
	
	loadReport: function(obj,reportId,parentId,namedId) {
		var thisObj=this;
		var trCurrent = obj;
		
		JsHttpRequest.query(
        	'POST '+thisObj.options.path.loadReport,
        	{ 
        		reportId: reportId, 
        		bTreeView: true, 
        		parentId: parentId, 
        		security_ls_key: LIVESTREET_SECURITY_KEY 
        	},
        	function(result, errors) {         		 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}
        		if (result.bStateError) {        			
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {
					var trReport=new Element('tr', {'id':(!namedId)?reportId:namedId});
					trReport.adopt(new Element('td',{
						'colspan': 6,
						'html'   : result.sReportText
					}));
					trReport.inject(trCurrent,'after');
					trReport.getElements('img').each(function(img, i){thisObj.makeImg(img);});
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

		if(img.hasClass(thisObj.options.classes.treeNode)) {
			// Это элемент дерева - обрабатываем его соответствующим образом
			trReport=img.getParent('tr').getNext('tr');
		} else {
			reportId=img.get('id').replace(this.options.prefix.img,this.options.prefix.td);
			var trReport = $(reportId);
		}		
		
		trReport.hide();
	},
	
	toggleEntriesByClass: function(reportId,name,link) {		
		var thisObj=this;
		$$('a.profiler').removeClass('active');
		$$('a.profiler.'+name).addClass('active');

		//var trCurrent = link.getParent('tr').getPrevious('tr');
		
		JsHttpRequest.query(
        	'POST '+thisObj.options.path.loadEntries+name+'/',
        	{ 
        		reportId: reportId, 
        		security_ls_key: LIVESTREET_SECURITY_KEY 
        	},
        	function(result, errors) {         		 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}
        		if (result.bStateError) {        			
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {
        			var trReport = $(thisObj.options.prefix.td+reportId).empty();
					trReport.adopt(new Element('td',{
						'colspan': 5,
						'html'   : result.sReportText
					}));
					trReport.getElements('img').each(function(img, i){thisObj.makeImg(img);});
        		}
	        },
        	true
       );
	},
	
	filterNode: function(obj) {
		var thisObj = this;
		var iTime=obj.get('value');
		if(iTime!='' && parseFloat(iTime)){ thisObj.highlightFilterNode(iTime); }
	},
	
	highlightFilterNode: function(iTime) {
		var thisObj = this;

		$$('.time').each(function(el,i){
			el.getParent('tr').removeClass(thisObj.options.classes.filterNode);
			if(el.get('text')>iTime) {
				el.getParent('tr').addClass(thisObj.options.classes.filterNode);
			}
		});
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
    		closeImg: 'folding',
    		filterNode: 'filter'
    	}
    });
});