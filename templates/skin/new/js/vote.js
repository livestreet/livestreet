var lsVote;

var lsVoteClass = new Class({
                                           
        Implements: Options,

        options: {
                classes_action: {
                        voted:          'voted',                       
                        plus:           'plus',
                        minus:          'minus',
                        positive:       'positive',
                        negative:       'negative',
                        quest:          'quest'
                },
                classes_element: {
                        voting:         'voting',
                        count:          'count',                       
                        total:          'total',                       
                        plus:           'plus',
                        minus:          'minus'
                }              
        },
       
        typeVote: {
                comment: {
                        url: DIR_WEB_ROOT+'/include/ajax/voteComment.php',
                        targetName: 'idComment'
                },
                topic: {
                        url: DIR_WEB_ROOT+'/include/ajax/voteTopic.php',
                        targetName: 'idTopic'
                },
                blog: {
                        url: DIR_WEB_ROOT+'/include/ajax/voteBlog.php',
                        targetName: 'idBlog'
                },
                user: {
                        url: DIR_WEB_ROOT+'/include/ajax/voteUser.php',
                        targetName: 'idUser'
                }
        },

        initialize: function(options){         
                this.setOptions(options);                      
        },
       
        vote: function(idTarget,objVote,value,type) {          
                if (!this.typeVote[type]) {
                        return false;
                }
               
                this.idTarget=idTarget;
                this.objVote=$(objVote);
                this.value=value;
                this.type=type;        
                thisObj=this;
                        
                var params = new Hash();
                params['value']=value;
                params[this.typeVote[type].targetName]=idTarget;
                params['security_ls_key']=LIVESTREET_SECURITY_KEY;
                
                JsHttpRequest.query(
                        'POST '+this.typeVote[type].url,                       
                        params,
                        function(result, errors) {     
                                thisObj.onVote(result, errors, thisObj);                               
                        },
                        true
                );             
        },
       
        onVote: function(result, errors, thisObj) {            
        	if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}      
        	if (result.bStateError) {
                msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        	} else {
                msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
               
                var divVoting=thisObj.objVote.getParent('.'+thisObj.options.classes_element.voting);                
                divVoting.addClass(thisObj.options.classes_action.voted);
               
                if (this.value>0) {
                        divVoting.addClass(thisObj.options.classes_action.plus);
                }
                if(this.value<0) {
                        divVoting.addClass(thisObj.options.classes_action.minus);
                }              
                var divCount=divVoting.getChildren('.'+thisObj.options.classes_element.count);
                if (divCount && divCount[0]) {
                	divCount.set('text',result.iCountVote);
                }
               
                var divTotal=divVoting.getChildren('.'+thisObj.options.classes_element.total);              
                result.iRating=parseFloat(result.iRating);  
                divVoting.removeClass(thisObj.options.classes_action.negative);    
                divVoting.removeClass(thisObj.options.classes_action.positive);         
                if (result.iRating>0) {                        
                        divVoting.addClass(thisObj.options.classes_action.positive);
                        divTotal.set('text','+'+result.iRating);
                }
                if (result.iRating<0) {                        
                        divVoting.addClass(thisObj.options.classes_action.negative);
                        divTotal.set('text',result.iRating);
                }
                if (result.iRating==0) {
                        divTotal.set('text','0');
                }
                
                if (thisObj.type=='user' && $('user_skill_'+thisObj.idTarget)) {
                	$('user_skill_'+thisObj.idTarget).set('text',result.iSkill);
                }
        	}      
        }
       
});

window.addEvent('domready', function() {       
      lsVote=new lsVoteClass();
});