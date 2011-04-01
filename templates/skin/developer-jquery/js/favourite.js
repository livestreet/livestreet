var favourite = {
	//==================
	// Опции
	//==================

	classes: {                     
		active:    	'active',
		favourite:  'favourite'                                    
	},
   
	typeFavourite: {                
		topic: {
			url: 			aRouter['ajax']+'favourite/topic/',
			targetName: 	'idTopic'
		},
		talk: {
			url: 			aRouter['ajax']+'favourite/talk/',
			targetName: 	'idTalk'                	                	
		},
		comment: {
			url: 			aRouter['ajax']+'favourite/comment/',
			targetName: 	'idComment'                	                	
		}
	},
   
   
	//==================
	// Функции
	//==================
	
	// Добавить/удалить из избранного
	toggle: function(idTarget, objFavourite, type) {          
		if (!this.typeFavourite[type]) { return false; }

		this.objFavourite = $(objFavourite);  
		this.type = type;  
		thisObj = this;
		
		var value = 1;      
		if (this.objFavourite.hasClass(this.classes.active)) {
			value = 0;
		}
		
		var params = {};
		params['type'] = value;
		params[this.typeFavourite[type].targetName] = idTarget;
		params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
		
		$.post(this.typeFavourite[type].url, params, function(result) {
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.notifier.notice(null, result.sMsg);
			   
				var divFavourite = thisObj.objFavourite;
				divFavourite.removeClass(thisObj.classes.active);
				
				if (result.bState) {
					divFavourite.addClass(thisObj.classes.active);
				}
			}  
		});    
	}
}