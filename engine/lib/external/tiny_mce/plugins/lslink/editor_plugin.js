/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.LsLink', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;		
			
			ed.addCommand('mceLsLink', function() {
				var sel = ed.selection.getContent();
				var elm = ed.selection.getNode();
				var href ='http://';
				var action = 'insert';
				
				elm = ed.dom.getParent(elm, "A");
				if (elm != null && elm.nodeName == "A") {		
					action = 'update';	
					href = ed.dom.getAttrib(elm, 'href');				
				}
				
				if (url=prompt('Введите ссылку',href)) {
					if (action=='insert') {
						sel = "<a href=\""+url+"\">"+sel+'</a>';
						ed.selection.setContent(sel);		
					} else {
						ed.dom.setAttrib(elm, 'href', url);
					}					
        		}	
			});
			

			ed.addButton('lslink', {title : 'lslink.lslink_desc', cmd : 'mceLsLink', image : url + '/img/link.gif'});
			
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('lslink', co && n.nodeName != 'A');
				cm.setActive('lslink', n.nodeName == 'A' && !n.name);
			});
			
		},

		getInfo : function() {
			return {
				longname : 'LiveStreet link insert',
				author : 'Mzhelskiy Maxim',
				authorurl : 'http://livestreet.ru',
				infourl : 'http://livestreet.ru',
				version : "0.3"
			};
		}		
	});
	
	tinymce.PluginManager.add('lslink', tinymce.plugins.LsLink);
})();