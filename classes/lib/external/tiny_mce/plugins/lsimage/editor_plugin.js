/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.LsImage', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;
						
			ed.addCommand('mceLsImage', function() {        		        		
        		ed.windowManager.open({
					file : url + '/image.htm',
					width : 480 + parseInt(ed.getLang('advimage.delta_width', 0)),
					height : 190 + parseInt(ed.getLang('advimage.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url,
					DIR_WEB_ROOT: DIR_WEB_ROOT,
					msgErrorBox: msgErrorBox,
					msgNoticeBox: msgNoticeBox
				});        		
			});		
			ed.addButton('lsimage', {title : 'lsimage.lsimage_desc', cmd : 'mceLsImage', image : url + '/img/img.gif'});			
		},

		getInfo : function() {
			return {
				longname : 'LiveStreet image insert',
				author : 'Mzhelskiy Maxim',
				authorurl : 'http://livestreet.ru',
				infourl : 'http://livestreet.ru',
				version : "0.3"
			};
		}		
	});
	
	tinymce.PluginManager.add('lsimage', tinymce.plugins.LsImage);
})();