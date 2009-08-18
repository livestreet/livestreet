/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.LsHSelect', {
		init : function(ed, url) {
			var t = this;

			
		},

		createControl: function(n, cm) {
        	switch (n) {
            	case 'lshselect':
                	var lb = cm.createListBox('lshselectListBox', {
                     title : 'Заголовок',
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
                
                	lb.add('H3', 'h3');
                	lb.add('H4', 'h4');
                	lb.add('H5', 'h5');  
                	return lb;           
        		}
        	return null;
    	},
    	
		getInfo : function() {
			return {
				longname : 'LiveStreet H* select',
				author : 'Mzhelskiy Maxim',
				authorurl : 'http://livestreet.ru',
				infourl : 'http://livestreet.ru',
				version : "0.3"
			};
		}		
	});
	
	tinymce.PluginManager.add('lshselect', tinymce.plugins.LsHSelect);
})();