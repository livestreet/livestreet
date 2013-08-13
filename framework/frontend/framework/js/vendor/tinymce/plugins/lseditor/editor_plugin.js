/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.PluginManager.requireLangPack('lseditor');
	
	tinymce.create('tinymce.plugins.LsEditor', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;
						
			ed.addCommand('mceLsEditorImage', function() {        		        		
        		ed.windowManager.open({
					file : url + '/image.htm?v=6',
					width : 480,
					height : 190,
					inline : 1
				}, {
					plugin_url : url,
					PATH_ROOT: PATH_ROOT,
					ajaxurl: aRouter['ajax'],
					LIVESTREET_SECURITY_KEY: LIVESTREET_SECURITY_KEY
				});        		
			});		
			ed.addButton('lsimage', {title : 'lseditor.image_title', cmd : 'mceLsEditorImage', image : url + '/img/img.gif'});	
			
			
			ed.addCommand('mceLsEditorLink', function() {
				var sel = ed.selection.getContent();
				var elm = ed.selection.getNode();
				var href ='http://';
				var action = 'insert';				
				var url = '';
				elm = ed.dom.getParent(elm, "A");
				if (elm != null && elm.nodeName == "A") {		
					action = 'update';	
					href = ed.dom.getAttrib(elm, 'href');				
				}				
				if (url=prompt(ed.getLang('lseditor.link_add'),href)) {
					if (action=='insert') {
						sel = "<a href=\""+url+"\">"+sel+'</a>';
						ed.selection.setContent(sel);		
					} else {
						ed.dom.setAttrib(elm, 'href', url);
					}					
        		}	
			});
			ed.addButton('lslink', {title : 'lseditor.link_title', cmd : 'mceLsEditorLink', image : url + '/img/link.gif'});			
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('lslink', co && n.nodeName != 'A');
				cm.setActive('lslink', n.nodeName == 'A' && !n.name);
			});		
			
			
			ed.addCommand('mceLsEditorVideo', function() {        		        		
        		ed.windowManager.open({
					file : url + '/video.htm?v=3',
					width : 480,
					height : 260,
					inline : 1
				}, {
					plugin_url : url,
					PATH_ROOT: PATH_ROOT
				});        		
			});		
			ed.addButton('lsvideo', {title : 'lseditor.video_title', cmd : 'mceLsEditorVideo', image : url + '/img/video.gif'});
            
                        ed.addCommand('mceLsEditorQuote', function() {
                            var sel = ed.selection.getContent();
                            if (!sel) return;
                            ed.selection.setContent('<blockquote>'+sel+'</blockquote>');
                        });
                        ed.addButton('lsquote', {title : 'lseditor.quote_title', cmd : 'mceLsEditorQuote', image : url + '/img/quote.gif'});
		},

		createControl: function(n, cm) {
        	switch (n) {
            	case 'lshselect':
                	var lb = cm.createListBox('lshselectListBox', {
                     title : 'lseditor.hselect_title',
                     onselect : function(v) {
                     	if (v=='') {
                     		return;
                     	}
                         
                         var ed=tinyMCE.activeEditor;
                         var sel = ed.selection.getContent();			
				
						sel = "<"+v+">"+sel+"</"+v+">";
						ed.selection.setContent(sel);
                     }
                	});
                
                	lb.add('H4', 'h4');
                	lb.add('H5', 'h5');
                	lb.add('H6', 'h6');  
                	return lb;           
        		}
        	return null;
    	},
		
		getInfo : function() {
			return {
				longname : 'LiveStreet editor for TinyMCE',
				author : 'Mzhelskiy Maxim',
				authorurl : 'http://livestreet.ru',
				infourl : 'http://livestreet.ru',
				version : "0.4"
			};
		}		
	});
	
	tinymce.PluginManager.add('lseditor', tinymce.plugins.LsEditor);
})();