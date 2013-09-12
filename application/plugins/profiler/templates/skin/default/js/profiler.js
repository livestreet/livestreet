var ls = ls || {};

ls.profiler = (function ($) {
	
	this.options = { 
		img : {
			path: 		DIR_PLUGIN_SKIN+'images/',
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
	}
	
	this.init = function(options) {
		if (options) {
			$.extend(true,this.options,options);
		}
		this.make();
	}
	
	
	this.make = function(expandClass){
		var aImgFolding=(!expandClass)?$('img.folding'):$('img.folding.'+expandClass);
		$.each(aImgFolding,function(k,v){
			this.makeImg(v);
		}.bind(this));
	}
	
	this.makeImg = function(img) {
		img=$(img);
		img.css({
			'cursor'  : 'pointer',
			'display' : 'inline'
		});
		img.removeClass(this.options.classes.closeImg);
		img.addClass(this.options.classes.openImg);
		img.unbind('click');
		img.bind('click',function(){
			this.toggleNode(img);
		}.bind(this));
	}
	
	this.toggleNode = function(img) {
		if (img.hasClass(this.options.classes.closeImg)) {	
			this.collapseNode(img);
		} else {
			this.expandNode(img);
		}
	}
	
	this.expandNode = function(img) {
		img.attr({'src': this.options.img.path + this.options.img.closeName});
		img.removeClass(this.options.classes.openImg);
		img.addClass(this.options.classes.closeImg);
		
		if(img.hasClass(this.options.classes.treeNode)) {
			// Это элемент дерева - обрабатываем его соответствующим образом
			ids = img.attr('id').replace(this.options.prefix.tree,'').split('_');
			
			reportId=ids[0];
			var trReportId=this.options.prefix.treeNode+ids[0]+'_'+ids[1];
			var trReport=$('#'+trReportId);
			var parentId=ids[1];
		} else {
			reportId=img.attr('id').replace(this.options.prefix.img,this.options.prefix.td);
			var trReport = $('#'+reportId);
			var parentId = 0;
			var trReportId = 0;
		}
		
		if(trReport.length){
			trReport.show();
		} else {
			this.loadReport(img.parent('td').parent('tr'),reportId,parentId,trReportId);
		}
	}
	
	
	this.loadReport = function(obj,reportId,parentId,namedId) {
		var trCurrent = obj;
		
		ls.ajax(this.options.path.loadReport, {reportId: reportId, bTreeView: 1, parentId: parentId}, function(result) {
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				var trReport=$('<tr id="'+(new String(!namedId?reportId:namedId))+'"><td colspan="6">'+result.sReportText+'</td></tr>');
				console.log(trCurrent);				
				trCurrent.after(trReport);				
				$.each(trReport.find('img'),function(k,v){
					this.makeImg(v);
				}.bind(this));
			}
		}.bind(this));
	}
	
	
	this.collapseNode = function(img) {
		img.attr({'src': this.options.img.path + this.options.img.openName});
		img.removeClass(this.options.classes.closeImg);
		img.addClass(this.options.classes.openImg);

		if(img.hasClass(this.options.classes.treeNode)) {
			// Это элемент дерева - обрабатываем его соответствующим образом
			trReport=img.parent('td').parent('tr').next('tr');
		} else {
			reportId=img.attr('id').replace(this.options.prefix.img,this.options.prefix.td);
			var trReport = $('#'+reportId);
		}		
		
		trReport.hide();
	}
	
	this.toggleEntriesByClass = function(reportId,name,link) {
		$('a.profiler').removeClass('active');
		$('a.profiler.'+name).addClass('active');

		ls.ajax(this.options.path.loadEntries+name+'/', {reportId: reportId}, function(result) {
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				var trReport = $('#'+this.options.prefix.td+reportId).empty();
				trReport.append('<td colspan="5" >'+result.sReportText+'</td>');
								
				$.each(trReport.find('img'),function(k,v){
					this.makeImg(v);
				}.bind(this));
			}

		}.bind(this));
	}
	
	this.filterNode = function(obj) {
		var iTime=$(obj).attr('value');
		if(iTime!='' && parseFloat(iTime)){ this.highlightFilterNode(iTime); }
	},
	
	this.highlightFilterNode = function(iTime) {
		$.each($('.time'),function(k,v){
			$(v).parent('tr').removeClass(this.options.classes.filterNode);
			
			if($(v).text()>iTime) {
				$(v).parent('tr').addClass(this.options.classes.filterNode);
			}
		}.bind(this));
	}
	
	return this;
}).call(ls.profiler || {},jQuery);


jQuery(window).load(function () {
	ls.profiler.init({
    	img: {
    		path: DIR_PLUGIN_SKIN+'images/'
    	},
    	classes: {
    		openImg:  'folding-open',
    		closeImg: 'folding',
    		filterNode: 'filter'
    	}
    });
});