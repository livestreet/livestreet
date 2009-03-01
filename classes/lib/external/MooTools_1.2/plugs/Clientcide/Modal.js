/*
Script: Clientcide.js
	The Clientcide namespace.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/


/*
Script: dbug.js
	A wrapper for Firebug console.* statements.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/
var dbug = {
	logged: [],	
	timers: {},
	firebug: false, 
	enabled: false, 
	log: function() {
		dbug.logged.push(arguments);
	},
	nolog: function(msg) {
		dbug.logged.push(arguments);
	},
	time: function(name){
		dbug.timers[name] = new Date().getTime();
	},
	timeEnd: function(name){
		if (dbug.timers[name]) {
			var end = new Date().getTime() - dbug.timers[name];
			dbug.timers[name] = false;
			dbug.log('%s: %s', name, end);
		} else dbug.log('no such timer: %s', name);
	},
	enable: function(silent) { 
		if(dbug.firebug) {
			try {
				dbug.enabled = true;
				dbug.log = function(){
						(console.debug || console.log).apply(console, arguments);
				};
				dbug.time = function(){
					console.time.apply(console, arguments);
				};
				dbug.timeEnd = function(){
					console.timeEnd.apply(console, arguments);
				};
				if(!silent) dbug.log('enabling dbug');
				for(var i=0;i<dbug.logged.length;i++){ dbug.log.apply(console, dbug.logged[i]); }
				dbug.logged=[];
			} catch(e) {
				dbug.enable.delay(400);
			}
		}
	},
	disable: function(){ 
		if(dbug.firebug) dbug.enabled = false;
		dbug.log = dbug.nolog;
		dbug.time = function(){};
		dbug.timeEnd = function(){};
	},
	cookie: function(set){
		var value = document.cookie.match('(?:^|;)\\s*jsdebug=([^;]*)');
		var debugCookie = value ? unescape(value[1]) : false;
		if((!$defined(set) && debugCookie != 'true') || ($defined(set) && set)) {
			dbug.enable();
			dbug.log('setting debugging cookie');
			var date = new Date();
			date.setTime(date.getTime()+(24*60*60*1000));
			document.cookie = 'jsdebug=true;expires='+date.toGMTString()+';path=/;';
		} else dbug.disableCookie();
	},
	disableCookie: function(){
		dbug.log('disabling debugging cookie');
		document.cookie = 'jsdebug=false;path=/;';
	}
};

(function(){
	var fb = typeof console != "undefined";
	var debugMethods = ['debug','info','warn','error','assert','dir','dirxml'];
	var otherMethods = ['trace','group','groupEnd','profile','profileEnd','count'];
	function set(methodList, defaultFunction) {
		for(var i = 0; i < methodList.length; i++){
			dbug[methodList[i]] = (fb && console[methodList[i]])?console[methodList[i]]:defaultFunction;
		}
	};
	set(debugMethods, dbug.log);
	set(otherMethods, function(){});
})();
if (typeof console != "undefined" && console.warn){
	dbug.firebug = true;
	var value = document.cookie.match('(?:^|;)\\s*jsdebug=([^;]*)');
	var debugCookie = value ? unescape(value[1]) : false;
	if(window.location.href.indexOf("jsdebug=true")>0 || debugCookie=='true') dbug.enable();
	if(debugCookie=='true')dbug.log('debugging cookie enabled');
	if(window.location.href.indexOf("jsdebugCookie=true")>0){
		dbug.cookie();
		if(!dbug.enabled)dbug.enable();
	}
	if(window.location.href.indexOf("jsdebugCookie=false")>0)dbug.disableCookie();
}


/*
Script: Occlude.js
	Prevents a class from being applied to a DOM element twice.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/
var Occlude = new Class({
	// usage: if(this.occlude()) return this.occluded;
	occlude: function(property, element) {
		element = $(element || this.element);
		var instance = element.retrieve(property || this.property);
		if (instance && (this.occluded === null || this.occluded)) {
			this.occluded = instance; 
		} else {
			this.occluded = false;
			element.store(property || this.property, this);
		}
		return this.occluded||false;
	}
});

/*
Script: ToElement.js
	Defines the toElement method for a class.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/
var ToElement = new Class({
	toElement: function(){
		return this.element;
	}
});

/*
Script: IframeShim.js
	Defines IframeShim, a class for obscuring select lists and flash objects in IE.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/	
var IframeShim = new Class({
	Implements: [Options, Events, Occlude, ToElement],
	options: {
		className:'iframeShim',
		display:false,
		zindex: null,
		margin: 0,
		offset: {
			x: 0,
			y: 0
		},
		browsers: (Browser.Engine.trident4 || (Browser.Engine.gecko && !Browser.Engine.gecko19 && Browser.Platform.mac))
	},
	property: 'IframeShim',
	initialize: function (element, options){
		this.element = $(element);
		if (this.occlude()) return this.occluded;
		this.setOptions(options);
		this.makeShim();
		return;
	},
	makeShim: function(){
		this.shim = new Element('iframe').store('IframeShim', this);
		if(!this.options.browsers) return;
		if(this.element.getStyle('z-Index').toInt()<1 || isNaN(this.element.getStyle('z-Index').toInt()))
			this.element.setStyle('z-Index',5);
		var z = this.element.getStyle('z-Index')-1;
		
		if($chk(this.options.zindex) && 
			 this.element.getStyle('z-Index').toInt() > this.options.zindex)
			 z = this.options.zindex;
			
 		this.shim.set({
			src: (window.location.protocol == 'https') ? '://0' : 'javascript:void(0)',
			frameborder:'0',
			scrolling:'no',
			styles: {
				position: 'absolute',
				zIndex: z,
				border: 'none',
				filter: 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)'
			},
			'class':this.options.className
		});

		var inject = function(){
			this.shim.inject(this.element, 'after');
			if(this.options.display) this.show();
			else this.hide();
			this.fireEvent('onInject');
		};
		if(Browser.Engine.trident && !IframeShim.ready) window.addEvent('load', inject.bind(this));
		else inject.run(null, this);
	},
	position: function(shim){
		if(!this.options.browsers || !IframeShim.ready) return this;
		var size = this.element.measure(function(){ return this.getSize(); });
		if($type(this.options.margin)){
			size.x = size.x-(this.options.margin*2);
			size.y = size.y-(this.options.margin*2);
			this.options.offset.x += this.options.margin; 
			this.options.offset.y += this.options.margin;
		}
 		this.shim.set({
			'width': size.x,
			'height': size.y
		}).setPosition({
			relativeTo: this.element,
			offset: this.options.offset
		});
		return this;
	},
	hide: function(){
		if(this.options.browsers) this.shim.hide();
		return this;
	},
	show: function(){
		if(!this.options.browsers) return this;
		this.shim.show();
		return this.position();
	},
	dispose: function(){
		if(this.options.browsers) this.shim.dispose();
		return this;
	}
});
window.addEvent('load', function(){
	IframeShim.ready = true;
});


/*
Script: Element.Measure.js
	Extends the Element native object to include methods useful in measuring dimensions.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

Element.implement({

	// Daniel Steigerwald - MIT licence
	measure: function(fn) {
		var restore = this.expose();
		var result = fn.apply(this);
		restore();
		return result;
	},

	expose: function(){
		var style = this.style;
    var cssText = style.cssText;
    style.visibility = 'hidden';
    style.position = 'absolute';
    if (style.display == 'none') style.display = '';
		return (function(){ return this.set('style', cssText); }).bind(this);
	},
	
	getDimensions: function(options) {
		options = $merge({computeSize: false},options);
		var dim = {};
		function getSize(el, options){
			return (options.computeSize)?el.getComputedSize(options):el.getSize();
		};
		if(this.getStyle('display') == 'none'){
			var restore = this.expose();
			dim = getSize(this, options); //works now, because the display isn't none
			restore(); //put it back where it was
		} else {
			try { //safari sometimes crashes here, so catch it
				dim = getSize(this, options);
			}catch(e){}
		}
		return $chk(dim.x)?$extend(dim, {width: dim.x, height: dim.y}):$extend(dim, {x: dim.width, y: dim.height});
	},
	
	getComputedSize: function(options){
		options = $merge({
			styles: ['padding','border'],
			plains: {height: ['top','bottom'], width: ['left','right']},
			mode: 'both'
		}, options);
		var size = {width: 0,height: 0};
		switch (options.mode){
			case 'vertical':
				delete size.width;
				delete options.plains.width;
				break;
			case 'horizontal':
				delete size.height;
				delete options.plains.height;
				break;
		};
		var getStyles = [];
		//this function might be useful in other places; perhaps it should be outside this function?
		$each(options.plains, function(plain, key){
			plain.each(function(edge){
				options.styles.each(function(style){
					getStyles.push((style=="border")?style+'-'+edge+'-'+'width':style+'-'+edge);
				});
			});
		});
		var styles = this.getStyles.apply(this, getStyles);
		var subtracted = [];
		$each(options.plains, function(plain, key){ //keys: width, height, plains: ['left','right'], ['top','bottom']
			size['total'+key.capitalize()] = 0;
			size['computed'+key.capitalize()] = 0;
			plain.each(function(edge){ //top, left, right, bottom
				size['computed'+edge.capitalize()] = 0;
				getStyles.each(function(style,i){ //padding, border, etc.
					//'padding-left'.test('left') size['totalWidth'] = size['width']+[padding-left]
					if(style.test(edge)) {
						styles[style] = styles[style].toInt(); //styles['padding-left'] = 5;
						if(isNaN(styles[style]))styles[style]=0;
						size['total'+key.capitalize()] = size['total'+key.capitalize()]+styles[style];
						size['computed'+edge.capitalize()] = size['computed'+edge.capitalize()]+styles[style];
					}
					//if width != width (so, padding-left, for instance), then subtract that from the total
					if(style.test(edge) && key!=style && 
						(style.test('border') || style.test('padding')) && !subtracted.contains(style)) {
						subtracted.push(style);
						size['computed'+key.capitalize()] = size['computed'+key.capitalize()]-styles[style];
					}
				});
			});
		});
		if($chk(size.width)) {
			size.width = size.width+this.offsetWidth+size.computedWidth;
			size.totalWidth = size.width + size.totalWidth;
			delete size.computedWidth;
		}
		if($chk(size.height)) {
			size.height = size.height+this.offsetHeight+size.computedHeight;
			size.totalHeight = size.height + size.totalHeight;
			delete size.computedHeight;
		}
		return $extend(styles, size);
	}
});


/*
Script: Element.Pin.js
	Extends the Element native object to include the pin method useful for fixed positioning for elements.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

window.addEvent('domready', function(){
	var test = new Element('div').setStyles({
		position: 'fixed',
		top: 0,
		right: 0
	}).inject(document.body);
	var supported = (test.offsetTop === 0);
	test.dispose();
	Browser.supportsPositionFixed = supported;
});

Element.implement({
	pin: function(enable){
		if(!Browser.loaded) dbug.log('cannot pin ' + this + ' natively because the dom is not ready');
		if (this.getStyle('display') == 'none') {
			dbug.log('cannot pin ' + this + ' because it is hidden');
			return;
		}
		if(enable!==false) {
			var p = this.getPosition();
			if(!this.retrieve('pinned')) {
				var pos = {
					top: (p.y - window.getScroll().y),
					left: (p.x - window.getScroll().x)
				};
				if(Browser.supportsPositionFixed) {
					this.setStyle('position','fixed').setStyles(pos);
				} else {
					this.store('pinnedByJS', true);
					this.setStyles({
						position: 'absolute',
						top: p.y,
						left: p.x
					});
					this.store('scrollFixer', function(){
						if(this.retrieve('pinned')) {
							var to = {
								top: (pos.top.toInt() + window.getScroll().y),
								left: (pos.left.toInt() + window.getScroll().x)
							};
							this.setStyles(to);
						}
					}.bind(this));
					window.addEvent('scroll', this.retrieve('scrollFixer'));
				}
				this.store('pinned', true);
			}
		} else {
			var op;
			if (!Browser.Engine.trident) {
				if (this.getParent().getComputedStyle('position') != 'static') op = this.getParent();
				else op = this.getParent().getOffsetParent();
			}
			var p = this.getPosition(op);
			this.store('pinned', false);
			var reposition;
			if (Browser.supportsPositionFixed && !this.retrieve('pinnedByJS')) {
				reposition = {
					top: (p.y + window.getScroll().y),
					left: (p.x + window.getScroll().x)
				};
			} else {
				this.store('pinnedByJS', false);
				window.removeEvent('scroll', this.retrieve('scrollFixer'));
				reposition = {
					top: (p.y),
					left: (p.x)
				};
			}
			this.setStyles($merge(reposition, {position: 'absolute'}));
		}
		return this.addClass('isPinned');
	},
	unpin: function(){
		return this.pin(false).removeClass('isPinned');
	},
	togglepin: function(){
		this.pin(!this.retrieve('pinned'));
	}
});


/*
Script: Element.Position.js
	Extends the Element native object to include methods useful positioning elements relative to others.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

Element.Properties.position = {

	set: function(options){
		this.setPosition(options);
	},

	get: function(options){
		if (options) this.setPosition(options);
		return this.getPosition();
	}

};

Element.implement({

	setPosition: function(options){
		$each(options||{}, function(v, k){ if (!$defined(v)) delete options[k]; });
		options = $merge({
			relativeTo: document.body,
			position: {
				x: 'center', //left, center, right
				y: 'center' //top, center, bottom
			},
			edge: false,
			offset: {x: 0, y: 0},
			returnPos: false,
			relFixedPosition: false,
			ignoreMargins: false,
			allowNegative: false
		}, options);
		//compute the offset of the parent positioned element if this element is in one
		var parentOffset = {x: 0, y: 0};
		var parentPositioned = false;
		/* dollar around getOffsetParent should not be necessary, but as it does not return
		 * a mootools extended element in IE, an error occurs on the call to expose. See:
		 * http://mootools.lighthouseapp.com/projects/2706/tickets/333-element-getoffsetparent-inconsistency-between-ie-and-other-browsers */
		var offsetParent = this.measure(function(){
			return $(this.getOffsetParent());
		});
		if (offsetParent && offsetParent != this.getDocument().body){
			parentOffset = offsetParent.measure(function(){
				return this.getPosition();
			});
			parentPositioned = true;
			options.offset.x = options.offset.x - parentOffset.x;
			options.offset.y = options.offset.y - parentOffset.y;
		}
		//upperRight, bottomRight, centerRight, upperLeft, bottomLeft, centerLeft
		//topRight, topLeft, centerTop, centerBottom, center
		var fixValue = function(option){
			if ($type(option) != "string") return option;
			option = option.toLowerCase();
			var val = {};
			if (option.test('left')) val.x = 'left';
			else if (option.test('right')) val.x = 'right';
			else val.x = 'center';
			if (option.test('upper') || option.test('top')) val.y = 'top';
			else if (option.test('bottom')) val.y = 'bottom';
			else val.y = 'center';
			return val;
		};
		options.edge = fixValue(options.edge);
		options.position = fixValue(options.position);
		if (!options.edge){
			if (options.position.x == 'center' && options.position.y == 'center') options.edge = {x:'center', y:'center'};
			else options.edge = {x:'left', y:'top'};
		}

		this.setStyle('position', 'absolute');
		var rel = $(options.relativeTo) || document.body;
		var calc = rel == document.body ? window.getScroll() : rel.getPosition();
		var top = calc.y;
		var left = calc.x;

		if (Browser.Engine.trident){
			var scrolls = rel.getScrolls();
			top += scrolls.y;
			left += scrolls.x;
		}

		var dim = this.getDimensions({computeSize: true, styles:['padding', 'border','margin']});
		if (options.ignoreMargins){
			options.offset.x = options.offset.x - dim['margin-left'];
			options.offset.y = options.offset.y - dim['margin-top'];
		}
		var pos = {};
		var prefY = options.offset.y;
		var prefX = options.offset.x;
		var winSize = window.getSize();
		switch(options.position.x){
			case 'left':
				pos.x = left + prefX;
				break;
			case 'right':
				pos.x = left + prefX + rel.offsetWidth;
				break;
			default: //center
				pos.x = left + ((rel == document.body ? winSize.x : rel.offsetWidth)/2) + prefX;
				break;
		};
		switch(options.position.y){
			case 'top':
				pos.y = top + prefY;
				break;
			case 'bottom':
				pos.y = top + prefY + rel.offsetHeight;
				break;
			default: //center
				pos.y = top + ((rel == document.body ? winSize.y : rel.offsetHeight)/2) + prefY;
				break;
		};

		if (options.edge){
			var edgeOffset = {};

			switch(options.edge.x){
				case 'left':
					edgeOffset.x = 0;
					break;
				case 'right':
					edgeOffset.x = -dim.x-dim.computedRight-dim.computedLeft;
					break;
				default: //center
					edgeOffset.x = -(dim.x/2);
					break;
			};
			switch(options.edge.y){
				case 'top':
					edgeOffset.y = 0;
					break;
				case 'bottom':
					edgeOffset.y = -dim.y-dim.computedTop-dim.computedBottom;
					break;
				default: //center
					edgeOffset.y = -(dim.y/2);
					break;
			};
			pos.x = pos.x + edgeOffset.x;
			pos.y = pos.y + edgeOffset.y;
		}
		pos = {
			left: ((pos.x >= 0 || parentPositioned || options.allowNegative) ? pos.x : 0).toInt(),
			top: ((pos.y >= 0 || parentPositioned || options.allowNegative) ? pos.y : 0).toInt()
		};
		if (rel.getStyle('position') == "fixed" || options.relFixedPosition){
			var winScroll = window.getScroll();
			pos.top = pos.top.toInt() + winScroll.y;
			pos.left = pos.left.toInt() + winScroll.x;
		}

		if (options.returnPos) return pos;
		else this.setStyles(pos);
		return this;
	}

});


/*
Script: Element.Shortcuts.js
	Extends the Element native object to include some shortcut methods.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

Element.implement({
	isVisible: function() {
		return this.getStyle('display') != 'none';
	},
	toggle: function() {
		return this[this.isVisible() ? 'hide' : 'show']();
	},
	hide: function() {
		var d;
		try {
			//IE fails here if the element is not in the dom
			if ('none' != this.getStyle('display')) d = this.getStyle('display');
		} catch(e){}
		this.store('originalDisplay', d||'block'); 
		this.setStyle('display','none');
		return this;
	},
	show: function(display) {
		original = this.retrieve('originalDisplay')?this.retrieve('originalDisplay'):this.get('originalDisplay');
		this.setStyle('display',(display || original || 'block'));
		return this;
	},
	swapClass: function(remove, add) {
		return this.removeClass(remove).addClass(add);
	},
	//TODO
	//DO NOT USE THIS METHOD
	//it is temporary, as Mootools 1.1 will negate its requirement
	fxOpacityOk: function(){
		return !Browser.Engine.trident4;
	} 
});


/*
Script: modalizer.js
	Defines Modalizer: functionality to overlay the window contents with a semi-transparent layer that prevents interaction with page content until it is removed

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/
var Modalizer = new Class({
	defaultModalStyle: {
		display:'block',
		position:'fixed',
		top:0,
		left:0,	
		'z-index':5000,
		'background-color':'#444',
		opacity:0.8
	},
	setModalOptions: function(options){
		this.modalOptions = $merge({
			width:(window.getScrollSize().x+300),
			height:(window.getScrollSize().y+300),
			elementsToHide: 'select',
			hideOnClick: true,
			modalStyle: {},
			updateOnResize: true,
			layerId: 'modalOverlay',
			onModalHide: $empty,
			onModalShow: $empty
		}, this.modalOptions, options);
		return this;
	},
	layer: function(){
		if (!this.modalOptions.layerId) this.setModalOptions();
		return $(this.modalOptions.layerId) || new Element('div', {id: this.modalOptions.layerId}).inject(document.body);
	},
	resize: function(){
		if(this.layer()) {
			this.layer().setStyles({
				width:(window.getScrollSize().x+300),
				height:(window.getScrollSize().y+300)
			});
		}
	},
	setModalStyle: function (styleObject){
		this.modalOptions.modalStyle = styleObject;
		this.modalStyle = $merge(this.defaultModalStyle, {
			width:this.modalOptions.width,
			height:this.modalOptions.height
		}, styleObject);
		if(this.layer()) this.layer().setStyles(this.modalStyle);
		return(this.modalStyle);
	},
	modalShow: function(options){
		this.setModalOptions(options);
		this.layer().setStyles(this.setModalStyle(this.modalOptions.modalStyle));
		if(Browser.Engine.trident4) this.layer().setStyle('position','absolute');
		this.layer().removeEvents('click').addEvent('click', function(){
			this.modalHide(this.modalOptions.hideOnClick);
		}.bind(this));
		this.bound = this.bound||{};
		if(!this.bound.resize && this.modalOptions.updateOnResize) {
			this.bound.resize = this.resize.bind(this);
			window.addEvent('resize', this.bound.resize);
		}
		if ($type(this.modalOptions.onModalShow)  == "function") this.modalOptions.onModalShow();
		this.togglePopThroughElements(0);
		this.layer().setStyle('display','block');
		return this;
	},
	modalHide: function(override, force){
		if(override === false) return false; //this is internal, you don't need to pass in an argument
		this.togglePopThroughElements(1);
		if ($type(this.modalOptions.onModalHide) == "function") this.modalOptions.onModalHide();
		this.layer().setStyle('display','none');
		if(this.modalOptions.updateOnResize) {
			this.bound = this.bound||{};
			if(!this.bound.resize) this.bound.resize = this.resize.bind(this);
			window.removeEvent('resize', this.bound.resize);
		}
		return this;
	},
	togglePopThroughElements: function(opacity){
		if(Browser.Engine.trident4 || (Browser.Engine.gecko && Browser.Platform.mac)) {
			$$(this.modalOptions.elementsToHide).each(function(sel){
				sel.setStyle('opacity', opacity);
			});
		}
	}
});

/*
Script: StyleWriter.js

Provides a simple method for injecting a css style element into the DOM if it's not already present.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

var StyleWriter = new Class({
	createStyle: function(css, id) {
		window.addEvent('domready', function(){
			try {
				if($(id) && id) return;
				var style = new Element('style', {id: id||''}).inject($$('head')[0]);
				if (Browser.Engine.trident) style.styleSheet.cssText = css;
				else style.set('text', css);
			}catch(e){dbug.log('error: %s',e);}
		}.bind(this));
	}
});

/*
Script: StickyWin.js

Creates a div within the page with the specified contents at the location relative to the element you specify; basically an in-page popup maker.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/

var StickyWin = new Class({
	Implements: [Options, Events, StyleWriter, ToElement],
	options: {
//		onDisplay: $empty,
//		onClose: $empty,
		closeClassName: 'closeSticky',
		pinClassName: 'pinSticky',
		content: '',
		zIndex: 10000,
		className: '',
//		id: ... set above in initialize function
/*  	these are the defaults for setPosition anyway
		************************************************
		edge: false, //see Element.setPosition
		position: 'center', //center, corner == upperLeft, upperRight, bottomLeft, bottomRight
		offset: {x:0,y:0},
		relativeTo: document.body, */
		width: false,
		height: false,
		timeout: -1,
		allowMultipleByClass: false,
		allowMultiple: true,
		showNow: true,
		useIframeShim: true,
		iframeShimSelector: ''
	},
	css: '.SWclearfix:after {content: "."; display: block; height: 0; clear: both; visibility: hidden;}'+
			 '.SWclearfix {display: inline-table;}'+
			 '* html .SWclearfix {height: 1%;}'+
			 '.SWclearfix {display: block;}',
	initialize: function(options){
		this.options.inject = {
			target: document.body,
			where: 'bottom' 
		};
		this.setOptions(options);
		
		this.id = this.options.id || 'StickyWin_'+new Date().getTime();
		this.makeWindow();

		if(this.options.content) this.setContent(this.options.content);
		if(this.options.timeout > 0) {
			this.addEvent('onDisplay', function(){
				this.hide.delay(this.options.timeout, this)
			}.bind(this));
		}
		if(this.options.showNow) this.show();
		//add css for clearfix
		this.createStyle(this.css, 'StickyWinClearFix');
	},
	makeWindow: function(){
		this.destroyOthers();
		if(!$(this.id)) {
			this.win = new Element('div', {
				id:		this.id
			}).addClass(this.options.className).addClass('StickyWinInstance').addClass('SWclearfix').setStyles({
			 	display:'none',
				position:'absolute',
				zIndex:this.options.zIndex
			}).inject(this.options.inject.target, this.options.inject.where).store('StickyWin', this);			
		} else this.win = $(this.id);
		this.element = this.win;
		if(this.options.width && $type(this.options.width.toInt())=="number") this.win.setStyle('width', this.options.width.toInt());
		if(this.options.height && $type(this.options.height.toInt())=="number") this.win.setStyle('height', this.options.height.toInt());
		return this;
	},
	show: function(suppressEvent){
		this.showWin();
		if (!suppressEvent) this.fireEvent('onDisplay');
		if(this.options.useIframeShim) this.showIframeShim();
		this.visible = true;
		return this;
	},
	showWin: function(){
		if(!this.positioned) this.position();
		this.win.show();
	},
	hide: function(suppressEvent){
		if(!suppressEvent) this.fireEvent('onClose');
		this.hideWin();
		if(this.options.useIframeShim) this.hideIframeShim();
		this.visible = false;
		return this;
	},
	hideWin: function(){
		this.win.setStyle('display','none');
	},
	destroyOthers: function() {
		if(!this.options.allowMultipleByClass || !this.options.allowMultiple) {
			$$('div.StickyWinInstance').each(function(sw) {
				if(!this.options.allowMultiple || (!this.options.allowMultipleByClass && sw.hasClass(this.options.className))) 
					sw.retrieve('StickyWin').destroy();
			}, this);
		}
	},
	setContent: function(html) {
		if(this.win.getChildren().length>0) this.win.empty();
		if($type(html) == "string") this.win.set('html', html);
		else if ($(html)) this.win.adopt(html);
		this.win.getElements('.'+this.options.closeClassName).each(function(el){
			el.addEvent('click', this.hide.bind(this));
		}, this);
		this.win.getElements('.'+this.options.pinClassName).each(function(el){
			el.addEvent('click', this.togglepin.bind(this));
		}, this);
		return this;
	},	
	position: function(options){
		this.positioned = true;
		this.setOptions(options);
		this.win.setPosition({
			allowNegative: true,
			relativeTo: this.options.relativeTo,
			position: this.options.position,
			offset: this.options.offset,
			edge: this.options.edge
		});
		if(this.shim) this.shim.position();
		return this;
	},
	pin: function(pin) {
		if(!this.win.pin) {
			dbug.log('you must include element.pin.js!');
			return this;
		}
		this.pinned = $pick(pin, true);
		this.win.pin(pin);
		return this;
	},
	unpin: function(){
		return this.pin(false);
	},
	togglepin: function(){
		return this.pin(!this.pinned);
	},
	makeIframeShim: function(){
		if(!this.shim){
			var el = (this.options.iframeShimSelector)?this.win.getElement(this.options.iframeShimSelector):this.win;
			this.shim = new IframeShim(el, {
				display: false,
				name: 'StickyWinShim'
			});
		}
	},
	showIframeShim: function(){
		if(this.options.useIframeShim) {
			this.makeIframeShim();
			this.shim.show();
		}
	},
	hideIframeShim: function(){
		if(this.shim) this.shim.hide();
	},
	destroy: function(){
		if (this.win) this.win.dispose();
		if(this.options.useIframeShim && this.shim) this.shim.dispose();
		if($('modalOverlay'))$('modalOverlay').dispose();
	}
});


/*
Script: StickyWin.Modal.js

This script extends StickyWin and StickyWin.Fx classes to add Modalizer functionality.

License:
	http://www.clientcide.com/wiki/cnet-libraries#license
*/
(function(){
var modalWinBase = function(extend){
	return {
		Extends: extend,
		initialize: function(options){
			options = options||{};
			this.setModalOptions($merge(options.modalOptions||{}, {
				onModalHide: function(){
						this.hide(false);
					}.bind(this)
				}));
			this.parent(options);
		},
		show: function(showModal){
			if($pick(showModal, true)) {
				this.modalShow();
				if (this.modalOptions.elementsToHide) this.win.getElements(this.modalOptions.elementsToHide).setStyle('opacity', 1);
			}
			this.parent();
		},
		hide: function(hideModal){
			if($pick(hideModal, true)) this.modalHide();
			this.parent();
		}
	}
};

StickyWin.Modal = new Class(modalWinBase(StickyWin));
StickyWin.Modal.implement(new Modalizer());
})();
//legacy
var StickyWinModal = StickyWin.Modal;