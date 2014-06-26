/**
 * Админка
 * 
 * @module ls/admin
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};
ls.admin = ls.admin || {};

ls.admin.blogCategories = (function ($) {
	this.showForm = function( url, form ) {
		ls.ajax.submit( url, form, function( result ) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle, result.sMsg);
			} else {
				window.location.href = window.location.href;
			}
		});
	};

	this.showFormAdd = function( form ) {
		this.showForm( aRouter.admin + 'blogcategory/add/', form );
	};

	this.showFormEdit = function( form ) {
		this.showForm( aRouter.admin + 'blogcategory/edit/', form );
	};

	return this;
}).call( ls.admin.blogCategories || {}, jQuery );