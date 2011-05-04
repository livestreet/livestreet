/*
---
description: This class gives you a method to upload files 'the ajax way'

license: MIT-style

authors:
- Arian Stolwijk

requires: [Class, Options, Events, Element, Element.Event, Element.Style]

provides: [Element.iFrameFormRequest, iFrameFormRequest]

...
*/

/**
 * @author Arian Stolwijk
 * Idea taken from http://www.webtoolkit.info/ajax-file-upload.html
 */

var iFrameFormRequest = new Class({

	Implements: [Options, Events],

	options: { /*
		onRequest: function(){},
		onComplete: function(data){},
		onFailure: function(){}, */
		eventName: 'sbt',
		url: '',
		dataType: 'text',
		params: {}
	},

	initialize: function(form, options){
		this.setOptions(options);
		var frameId = this.frameId = 'iframe_'+Math.random();
		var loading = false;

		this.form = document.id(form);
		
		// добавляем поля из параметров
		$each(this.options.params,function(v,k){
			if (typeof(v)=="boolean") {
				v=v ? 1 : 0;
			}
			var input=new Element('input',{type:'hidden',name:k,value:v});
			this.form.adopt(input);
		}.bind(this));

		// подменяем URL
		if (this.options.url) {
			this.form.setProperty('action',this.options.url);
		}
		
		this.formEvent = function(){
			loading = true;
			this.fireEvent('request');
		}.bind(this);

		this.iframe = new IFrame({
			name: frameId,
			styles: {
				display: 'none'
			},
			src: 'about:blank',
			events: {
				load: function(){
					if (loading){
						var doc = this.iframe.contentWindow.document;
						if (doc && doc.location.href != 'about:blank'){
							var data=doc.body.innerHTML;
							if (this.options.dataType=='json') {
								var ta = doc.getElementsByTagName('textarea')[0];
								if (ta) {
									data = ta.value;
								}
								this.fireEvent('complete', JSON.decode(data));
							} else {
								this.fireEvent('complete', data);
							}
						} else {
							this.fireEvent('failure');
						}
						loading = false;
					}
				}.bind(this)
			}
		}).inject(document.body);

		this.attach();
	},

	send: function(){
		this.form.fireEvent(this.options.eventName);
		this.form.submit();
	},

	attach: function(){
		this.target = this.form.get('target');
		this.form.set('target', this.frameId)
			.addEvent(this.options.eventName, this.formEvent);
	},

	detach: function(){
		this.form.set('target', this.target)
			.removeEvent(this.options.eventName, this.formEvent);
	},

	toElement: function(){
		return this.iframe;
	}

});

Element.implement('iFrameFormRequest', function(options){
	this.store('iFrameFormRequest', new iFrameFormRequest(this, options));
	return this;
});