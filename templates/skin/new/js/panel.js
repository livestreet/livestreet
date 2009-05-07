var lsPanelClass = new Class({	
	initialize: function(){
		
	},
	
	putText: function(obj,text) {
		obj=$(obj);
		var scrollLeft=obj.scrollLeft;
		var scrollTop=obj.scrollTop;		
		if (Browser.Engine.trident && document.selection) {
			obj.focus();
			sel=document.selection.createRange();
			sel.text=text;
		} else {
			obj.insertAtCursor(text);
		}		
		obj.scrollLeft=scrollLeft;
		obj.scrollTop=scrollTop;
	}, 
	
	putTag: function(obj,tag) {
		this.putText(obj,'<'+tag+'/>');
	},
	
	putTextAround: function(obj,textStart,textEnd) {
		obj=$(obj);
		var scrollLeft=obj.scrollLeft;
		var scrollTop=obj.scrollTop;	
		if (Browser.Engine.trident && document.selection) {
			obj.focus();
			sel=document.selection.createRange();
			sel.text = textStart+sel.text+textEnd;
		} else {
			obj.insertAroundCursor({
				before: textStart,
				defaultMiddle: '',
				after: textEnd
			});
		}
		obj.scrollLeft=scrollLeft;
		obj.scrollTop=scrollTop;
	},
	
	putTagAround: function(obj,tagStart,tagEnd) {
		if (!tagEnd) {
			tagEnd=tagStart;
		}
		this.putTextAround(obj,'<'+tagStart+'>','</'+tagEnd+'>');
	},
	
	putTagUrl: function(obj,sPromt) {
		obj=$(obj);
		if (url=prompt(sPromt,'http://')) {
			var sel=obj.getSelectedText();
        	this.putText(obj,'<a href="'+url+'">'+sel+'</a>');
        }
	},
	
	putQuote: function(obj) {
		obj=$(obj);
		if (selText=this.getSelectedText()) {			
			this.putText(obj,'<blockquote>'+selText+'</blockquote>');
		} else {
			this.putTagAround(obj,'blockquote');
		}
	},
	
	getSelectedText: function(){
		if (Browser.Engine.trident) return document.selection.createRange().text;
		//if (window.khtml) return window.getSelection();
		return document.getSelection();
	}
});

var lsPanel;

window.addEvent('domready', function() {
    lsPanel = new lsPanelClass();   
});