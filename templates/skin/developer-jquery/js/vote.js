var vote = {
	//==================
	// Опции
	//==================
	
	classes: {
		voted: 		'voted',                       
		plus: 		'plus',
		minus:  	'minus',
		positive:	'positive',
		negative:  	'negative',          
		total: 		'total',            
	},
   
   
	typeVote: {
		comment: {
			url: aRouter['ajax']+'vote/comment/',
			targetName: 'idComment'
		},
		topic: {
			url: aRouter['ajax']+'vote/topic/',
			targetName: 'idTopic'
		},
		blog: {
			url: aRouter['ajax']+'vote/blog/',
			targetName: 'idBlog'
		},
		user: {
			url: aRouter['ajax']+'vote/user/',
			targetName: 'idUser'
		}
	},
  
   
	//==================
	// Функции
	//==================
   
	vote: function(idTarget, objVote, value, type) {          
		if (!this.typeVote[type]) return false;
	   
		this.idTarget = idTarget;
		this.objVote = $(objVote);
		this.value = value;		
		thisObj = this;
				
		var params = {};
		params['value'] = value;
		params[this.typeVote[type].targetName] = idTarget;
		params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
		
		$.post(this.typeVote[type].url, params, function(result) {
			thisObj.onVote(result, thisObj);
		});        
	},
   
   
	onVote: function(result, thisObj) {    
		if (result.bStateError) {
			$.notifier.error(null, result.sMsg);
		} else {
			$.notifier.notice(null, result.sMsg);
		   
			var divVoting = thisObj.objVote.parent();
			divVoting.addClass(thisObj.classes.voted);
			
			if (thisObj.value > 0) {
				divVoting.addClass(thisObj.classes.plus);
			}
			if (thisObj.value < 0) {
				divVoting.addClass(thisObj.classes.minus);
			}
		   
			var divTotal = divVoting.children('.'+thisObj.classes.total); 
			
			result.iRating = parseFloat(result.iRating);  
			
			divVoting.removeClass(thisObj.classes.negative);    
			divVoting.removeClass(thisObj.classes.positive); 
			
			if (result.iRating > 0) {                        
				divVoting.addClass(thisObj.classes.positive);
				divTotal.text(result.iRating);
			}
			if (result.iRating < 0) {                        
				divVoting.addClass(thisObj.classes.negative);
				divTotal.text(result.iRating);
			}
			if (result.iRating == 0) {
				divTotal.text(0);
			}
			
			if (thisObj.type == 'user') {
				$('#user_skill_'+thisObj.idTarget).text(result.iSkill);
			}
		}      
	}
}