var lsPanelClass = new Class({	
	initialize: function(){
		
	},
	
	putText: function(obj,text) {
		obj=$(obj);
		obj.insertAtCursor(text);
	}, 
	
	putTag: function(obj,tag) {
		this.putText(obj,'<'+tag+'/>');
	},
	
	putTextAround: function(obj,textStart,textEnd) {
		obj=$(obj);
		obj.insertAroundCursor({
			before: textStart,
			defaultMiddle: '',
			after: textEnd
		});
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