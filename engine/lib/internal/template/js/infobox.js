var ls = ls || {};

/**
 * Всплывающие поп-апы
 */
ls.infobox = (function ($) {

	this.oInfobox;
	this.aOptDef={
		'width': 200,
		'zIndex' : 100,
		'offsetY' : 5
	};
	/**
	 * Шаблон поп-апа
	 */
	this.sTemplate=['<div class="infobox">',
						'<div class="infobox-content"></div>',
						'<div class="infobox-arrow"></div>',
					'</div>'].join('');
	/**
	 * Шаблон процесс-бара
	 */
	this.sTemplateProcess=['<div class="infobox-process">process..',
							'</div>'].join('');

	this.init = function() {
		this.oInfobox=$(this.sTemplate);
		this.oInfobox.appendTo('body')
		.css({position: 'absolute', display: 'none'});

		this.oInfobox.bind('mouseout',function(e){
			//console.log(e);
			//this.hide();
		}.bind(this));
	};

	this.show = function(oLink,sContent,aOpt) {
		aOpt=$.extend(true,{},this.aOptDef,aOpt || {});
		if (!this.oInfobox) {
			this.init();
		}
		this.oInfobox.data('oLink',oLink);
		$oLink=$(oLink);


		var iLinkWidth = $oLink.innerWidth();
		var iLinkHeight = $oLink.innerHeight();
		var iLinkTop  = $oLink.offset().top;
		var iLinkLeft = $oLink.offset().left;

		this.oInfobox.find('.infobox-content').html(sContent);

		this.oInfobox.css({
			left: parseInt(iLinkLeft+iLinkWidth/2-aOpt.width/2),
			top: parseInt(iLinkTop+iLinkHeight+aOpt.offsetY),
			width: aOpt.width,
			zIndex: aOpt.zIndex
		}).show();

		return false;
	};

	this.hide = function() {
		this.oInfobox.hide();
		return false;
	};

	this.toggle = function(oLink,sContent,aOpt) {
		if (!this.oInfobox) {
			this.init();
		}
		if (this.oInfobox.is(':visible') && this.oInfobox.data('oLink')==oLink) {
			this.hide();
		} else {
			this.show(oLink,sContent,aOpt);
		}
		return false;
	};

	this.showProcess = function(oLink) {
		this.show(oLink,this.sTemplateProcess);
	};

	this.showInfoBlog = function(oLink,iBlogId) {
		if (this.oInfobox && this.oInfobox.is(':visible') && this.oInfobox.data('oLink')==oLink) {
			return this.hide();
		}

		this.showProcess(oLink);
		var url = aRouter['ajax']+'infobox/info/blog/';
		var params = {iBlogId: iBlogId};
		'*showInfoBlogBefore*'; '*/showInfoBlogBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
				this.hide();
			} else {
				this.show(oLink,result.sText);
				ls.hook.run('ls_infobox_show_info_blog_after',[oLink, iBlogId, result]);
			}
		}.bind(this));
		return false;
	};

	return this;
}).call(ls.infobox || {},jQuery);