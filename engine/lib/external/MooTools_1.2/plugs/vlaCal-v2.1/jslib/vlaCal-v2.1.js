  /*********************************************************/
 /*   vlaCalendar version 2.1 for mootools release 1.2    */
/*********************************************************/

var vlaCalendar = new Class({
	'slideDuration': 500,
	'fadeDuration': 500,
	'transition': Fx.Transitions.Quart.easeOut,
	'startMonday': false,
	'filePath': 'inc/',
	'defaultView': 'month',
	'style': '',
	
	initialize: function(_container, _options) {
		//Add the provided options to this object by extending
		if(_options) $extend(this, _options);
		
		this.loading = false;
		this.container = _container = $(_container);
		var _class = this;
		
		//Insert the base into the container and initialize elements
		var  pars = 'defaultView='+ this.defaultView;
		if(this.picker) {
			if($type(this.prefillDate) == 'object' && this.getInputDate(this.prefillDate)) pars += '&pickedDate='+ this.getInputDate(this.prefillDate);
			if(this.linkWithInput) pars += '&gotoPickedDate=1';
		}
		this.u('base', pars, function() { 
			_class.mainLoader = _container.getElement('div[class=loaderA]');
			_class.tempLoader = _container.getElement('div[class=loaderB]');
			_class.label 	  = _container.getElement('span[class=label]');
			_class.arrowLeft  = _container.getElement('div[class=arrowLeft]');
			_class.arrowRight = _container.getElement('div[class=arrowRight]');				
			_class.initializeCalendarFunctions();
			
			//Prefill/load picker date elements
			if(_class.picker) {
				if($type(_class.prefillDate) == 'object' && _class.getInputDate(_class.prefillDate)) _class.pick(_class.prefillDate);
				else if(_class.prefillDate == true) _class.pick(JSON.decode(_class.label.getProperty('date')));
			}
		}, _container);
	},
	
	initializeCalendarFunctions: function() {
		this.resetArrows();
		
		//Retrieve data (label, timestamp etc) which are stored as a Json string in the table attribute summary
		var vars = JSON.decode(this.mainLoader.getElement('table').getProperty('summary'));
		var _class = this; 
		
		//Change the label
		this.label.removeClass('noHover').set('html', vars.label)
			.onclick = vars.parent ? function() { _class.u(vars.parent, 'ts=' + vars.ts + '&parent=' + vars.current, function() { _class.fade() }) } : null;
			
		//Hide arrows if necessary and add arrow click events
		if(vars.hide_left_arrow) this.hideLeftArrow();
		else if(vars.hide_right_arrow) this.hideRightArrow();
		
		this.arrowLeft.onclick  = function() { _class.u(vars.current, 'ts=' + vars.pr_ts, function() { _class.slideLeft() }) }
		this.arrowRight.onclick = function() { _class.u(vars.current, 'ts=' + vars.nx_ts, function() { _class.slideRight() }) }		
		
		//Add cell click events
		var clickables = this.mainLoader.getElements('td');
		switch(vars.current) {
			case 'month':
				if(this.picker) {
					clickables.each(function(_clickable) {
						_clickable.onclick = function() { 
							_class.pick(JSON.decode(_clickable.getProperty('date')));
							_class.mainLoader.getElements('td').each(function(_clickable) { _clickable.removeClass('selected') });
							this.addClass('selected'); 
						}
					});
				}
				break;
			case 'year':
				clickables.each(function(_clickable) {
					_clickable.onclick = function() { _class.u('month', 'ts=' + _clickable.getProperty('ts'), function() { _class.fade() }) }
				});
				break;
			case 'decade':
				this.label.addClass('noHover');
				clickables.each(function(_clickable) {
					_clickable.onclick = function() { _class.u('year', 'ts=' + _clickable.getProperty('ts') + '&m_ts=' + _clickable.getProperty('m_ts'), function() { _class.fade() }) }
				});
				break;
		}
	},
	
	//Ajax updater function which handles all requests
	u: function(_url, _pars, _onComplete, _id) {
		if(!this.loading && !this.transitioning) {
			var _class = this;
			this.loading = true;
			var element = $(_id ? _id : this.tempLoader);
			_pars += '&picker=' + (this.picker ? 1 : 0) + '&startMonday=' + (this.startMonday ? 1 : 0) + '&style=' +  this.style;
			if(this.picker && this.getInputDate()) _pars += '&pickedDate='+ this.getInputDate();
			new Request({ method: 'post',
						  url: this.filePath + _url + '.php',
						  onComplete: function(data) { element.set('html', data); _onComplete(); _class.loading = false; }
						}).send(_pars);
		}
	},
	
	slideLeft: function() {
		var _class = this;
		this.transitioning = true;	
		this.tempLoader.setStyle('opacity', 1).set('tween', { duration: this.slideDuration, transition: this.transition }).tween('margin-left', [-164, 0]);
		this.mainLoader.setStyle('opacity', 1).set('tween', { duration: this.slideDuration, transition: this.transition, onComplete: function() { _class.transitioning = false } })
			.tween('margin-left', [0, 164]);
		this.switchLoaders();
	},
	
	slideRight: function() {
		var _class = this;
		this.transitioning = true;
		this.mainLoader.setStyle('opacity', 1).set('tween', { duration: this.slideDuration, transition: this.transition }).tween('margin-left', [0, -164]);
		this.tempLoader.setStyle('opacity', 1).set('tween', { duration: this.slideDuration, transition: this.transition, onComplete: function() { _class.transitioning = false } })
			.tween('margin-left', [164, 0]);
		this.switchLoaders();
	},
	
	fade: function(overRuleTrans) {
		var _class = this;
		this.transitioning = overRuleTrans ? false : true;
		this.tempLoader.setStyles({'opacity': 0, 'margin-left': 0});
		this.mainLoader.set('tween', { duration: this.fadeDuration, transition: this.transition}).fade('out');
		this.tempLoader.set('tween', { duration: this.fadeDuration, transition: this.transition, 
			onComplete: function() { 
					_class.tempLoader.setStyles({'opacity': 1, 'margin-left': -999});
					_class.transitioning = false;
				} 
			}).fade('in');
		this.switchLoaders();
	},
	
	switchLoaders: function() {
		this.mainLoader = this.mainLoader.className == 'loaderA' ? this.container.getElement('div[class=loaderB]') : this.container.getElement('div[class=loaderA]');
		this.tempLoader = this.tempLoader.className == 'loaderA' ? this.container.getElement('div[class=loaderB]') : this.container.getElement('div[class=loaderA]');
		this.initializeCalendarFunctions();
	},
	
	resetArrows: function() {
		this.arrowLeft.setStyle('visibility', 'visible');
		this.arrowRight.setStyle('visibility', 'visible');
	},
	
	hideLeftArrow: function() {
		this.arrowLeft.setStyle('visibility', 'hidden');
	},
	
	hideRightArrow: function() {
		this.arrowRight.setStyle('visibility', 'hidden');
	} 
});

var vlaDatePicker = new Class({
	Extends: vlaCalendar,
	
	'separateInput': false,
	'prefillDate': true,
	'linkWithInput': true,
	'leadingZero': true,
	'twoDigitYear': false,
	'separator': '/',
	'format': 'd/m/y',
	'openWith': null,
	'alignX': 'right',
	'alignY': 'inputTop',
	'offset': { 'x': 0, 'y': 0 },
	'style': '',
	'ieTransitionColor' : '#ffffff',
	'toggleDuration': 350,
	
	initialize: function(_element, _options) {
		//Add the provided options to this object by extending
		if(_options) $extend(this, _options);
		
		this.element = $(_element);
		if(!this.element) throw 'No (existing) element to create a datepicker for specified: new vlaDatePicker(ELEMENT, [options])';
		
		//Check if the user wants multiple input
		if(this.separateInput) {
			this.element.day   = this.element.getElement('input[name='+ this.separateInput.day +']');
			this.element.month = this.element.getElement('input[name='+ this.separateInput.month +']');
			this.element.year  = this.element.getElement('input[name='+ this.separateInput.year +']');
		}
		
		//Create the picker and calendar and inject in in the body
		this.picker = new Element('div', { 'class': 'vlaCalendarPicker' + (this.style != '' ? ' ' + this.style : '') }).injectTop($(document.body));
		this.pickerContent = new Element('div', { 'class': 'pickerBackground' }).injectTop(this.picker);
		this.parent(this.pickerContent);
		
		//Add events for showing and hiding the picker
		var _class = this;
		(this.openWith ? $(this.openWith) : this.element)
			.addEvent('focus',  function() { _class.show(); })
			.addEvent('click',  function() { _class.openWith ? _class.toggle() : _class.show() })
			.addEvent('change', function() { _class.hide(); });
		
		//If the datepicker is visible an outside click makes it hide
		document.addEvent('mousedown', function(e) { if(_class.outsideHide && _class.outsideClick(e, _class.picker)) _class.hide() });
		
		//linkWithInput
		if(this.linkWithInput) {
			if(this.separateInput) {
				this.element.day.addEvent('keyup',  function() { _class.linkedUpdate() });
				this.element.month.addEvent('keyup',  function() { _class.linkedUpdate() });
				this.element.year.addEvent('keyup',  function() { _class.linkedUpdate() });
			} else {
				this.element.addEvent('keyup',  function() { _class.linkedUpdate() });
			}
		}
		
		this.visible = false;
		this.outsideHide = false;
	},
	
	//Position the picker
	position: function() {
		var top, left;
		
		switch(this.alignX) {
			case 'left':
				left = this.element.getLeft();
				break;
			case 'center':
				var pickerMiddle = this.pickerContent.getStyle('width').toInt() / 2;
				if(pickerMiddle == 0) pickerMiddle = 83;
				left = this.element.getLeft() + (this.element.getSize().x / 2) - pickerMiddle -
						((parseInt(this.pickerContent.getStyle('padding-left')) + parseInt(this.pickerContent.getStyle('padding-right'))) / 2);
				break;
			case 'right': default:
				left = this.element.getLeft() + this.element.getSize().x;
				break;
		}
		
		switch(this.alignY) {
			case 'bottom':
				top = this.getPos(this.element).y + this.element.getSize().y;
				break;
			case 'top': 
				top = this.getPos(this.element).y - parseInt(this.pickerContent.getStyle('height')) - 
					(parseInt(this.pickerContent.getStyle('padding-top')) + parseInt(this.pickerContent.getStyle('padding-bottom')));
				break;
			case 'inputTop': default:
				top = this.getPos(this.element).y;
		}
		
		if(this.isNumber(this.offset.x)) left += this.offset.x;
		if(this.isNumber(this.offset.y)) top += this.offset.y;
		
		this.picker.setStyles({ 'top': top, 'left': left });
	},
	
	show: function() {
		this.position();
		if(!this.visible) {
			this.visible = true;
			var _class = this;
			this.picker.setStyles({ 'opacity': 0, 'display': 'inline' });
			if(Browser.Engine.trident5) this.picker.setStyle('background-color', this.ieTransitionColor); //Ugly transition fix for IE7
			this.picker.set('tween', { onComplete: function() { 
					if(Browser.Engine.trident5) _class.picker.setStyle('background-color', 'transparent');
					_class.outsideHide = true; 
				}, duration: this.toggleDuration }).fade('in');
		}
	},
	
	hide: function() {
		if(this.visible) {
			this.visible = false;
			var _class = this;
			if(Browser.Engine.trident5) this.picker.setStyle('background-color', this.ieTransitionColor); //Ugly transition fix for IE7
			this.picker.set('tween', { onComplete: function() { _class.picker.setStyle('display', 'none'); _class.outsideHide = false; }, duration: this.toggleDuration }).fade('out');
		}
	},
	
	toggle: function() {
		if(this.visible) this.hide();
		else this.show();
	},
	
	pick: function(_date) {
		if(this.leadingZero) {
			if(_date.day < 10)   _date.day = '0' + _date.day;
			if(_date.month < 10) _date.month = '0' + _date.month;
		}
		if(this.twoDigitYear) _date.year = _date.year.toString().substring(2, 4);
		
		if(this.separateInput) {
			if(this.element.day)   this.element.day.set('value', _date.day);
			if(this.element.month) this.element.month.set('value', _date.month);
			if(this.element.year)  this.element.year.set('value', _date.year);
			this.hide();
		} else {
			switch(this.format) {
				case "m/d/y": this.element.set('value', _date.month + this.separator + _date.day + this.separator + _date.year); break;
				case "y/m/d": this.element.set('value', _date.year + this.separator + _date.month + this.separator + _date.day); break;
				case "y/d/m": this.element.set('value', _date.year + this.separator +  _date.day + this.separator + _date.month); break;
				case "d/m/y": default: this.element.set('value', _date.day + this.separator + _date.month + this.separator + _date.year);
			}
			this.hide();
		}
	},
	
	getInputDate: function(_date) {
		var day, month, year;
		
		if(_date) {
			day = _date.day;
			month = _date.month;
			year = _date.year;
		} else if(this.separateInput) {
			day = this.element.day.get('value').toInt();
			month = this.element.month.get('value').toInt();
			year = this.element.year.get('value').toInt();
		} else {
			var date = this.element.get('value').split(this.separator);
			if(date.length != 3) return null;
			switch(this.format) {
				case "m/d/y": day = date[1]; month = date[0]; year = date[2]; break;
				case "y/m/d": day = date[2]; month = date[1]; year = date[0]; break;
				case "y/d/m": day = date[1]; month = date[2]; year = date[0]; break;
				case "d/m/y": default: day = date[0]; month = date[1]; year = date[2];
			}
		}
		
		if( !this.isNumber(day) || !this.isNumber(month) || !this.isNumber(year) ||	day == 0 || month == 0 || year == '0' ||
		    (this.twoDigitYear && year > 99) || (!this.twoDigitYear && year < 1979) || (!this.twoDigitYear && year > 2030) || month > 12 || day > 31 ) return null;
		
		if(this.twoDigitYear && this.isNumber(year) && year < 100) {
			year = year.toInt();
			if(year < 10) year = '200'+  year;
			else if(year < 70) year = '20'+  year;
			else if(year > 69) year = '19'+  year;
			else year = new Date().getFullYear();
		}
		
		return day +'/'+ month +'/'+ year;
	},
	
	//This function is being called on keyup event if linkWithInput is set to true and when a date is picked
	//If the full date is inserted the picker will change itself to that specific date (month view)
	linkedUpdate: function() {
		var _class = this;
		var date = this.getInputDate();
		if(date && this.pickedDate != date) {
			this.u('month', 'gotoPickedDate=1', function() { _class.fade(true) });
			this.pickedDate = date;
		}
	},
	
	outsideClick: function(_event, _element) {
		var mousePos = this.getMousePos(_event);
		var elementData = _element.getCoordinates();
		return (mousePos.x > elementData.left && mousePos.x < (elementData.left + elementData.width)) &&
			   (mousePos.y > elementData.top  && mousePos.y < (elementData.top + elementData.height)) ? false : true;
	},
	
	getMousePos: function(_event) {
		if(document.all) {
			return { 'x': window.event.clientX + window.getScrollLeft(),
					 'y': window.event.clientY + window.getScrollTop() };
		} else {
			return { 'x': _event.page['x'],
					 'y': _event.page['y'] };
		}
	},
	
	isNumber: function(_number) {
		if(_number == '') return false;
		return (_number >= 0) || (_number < 0) ? true : false;
	},
	
	//Retrieving positition funtions (like getCoordinates, getTop etc) don't seem to return correct values in some situations in mootools 1.2; 
	//Opera returns wrong values, IE returns too small values. This function returns the correct coordinates.
	getPos: function(_element) { 
		var x, y = 0;
		if(_element.offsetParent) {
			do {
				x += _element.offsetLeft;
				y += _element.offsetTop;
			} while(_element = _element.offsetParent);
		} else if(_element.x) {
			x += _element.x;
			y += _element.y;
		}
		return { 'x': x, 'y': y };
	}
});