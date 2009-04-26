var lsPanelClass = new Class({	
	initialize: function(){
		
	},
	
	putText: function(obj,text) {
		obj=$(obj);
		var scrollLeft=obj.scrollLeft;
		var scrollTop=obj.scrollTop;
		obj.insertAtCursor(text);
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
		obj.insertAroundCursor({
			before: textStart,
			defaultMiddle: '',
			after: textEnd
		});
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
	}
});

var lsPanel;

window.addEvent('domready', function() {  	
    lsPanel = new lsPanelClass();   
});