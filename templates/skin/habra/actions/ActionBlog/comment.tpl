<div id="form_comment" style="display: none;">
	<div class="comment_text" id="text_preview"></div>
	<div style="clear: both;"></div><br>
	<div class="panel_topic_form" style="background: #eaecea; ">   				
	 			<a href="#" onclick="voidPutB('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 			<a href="#" onclick="voidPutI('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 			<a href="#" onclick="voidPutU('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 			<a href="#" onclick="voidPutS('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 			&nbsp;
	 			<a href="#" onclick="voidPutURL('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 			<a href="#" onclick="voidPutCode('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/code.gif" width="30" height="20" title="код"></a>
	 </div>
 	<form action="" method="POST">
    	<textarea class="input_comments_reply" name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>    	
    	<input type="submit" name="submit_comment" value="добавить">  <input type="submit" name="submit_preview" value="предпросмотр" onclick="ajaxTextPreview(document.getElementById('form_comment_text').value,false); return false;">  	
    	<input type="hidden" name="reply" value="" id="form_comment_reply">
    </form>
</div>


{literal}
<script type="text/javascript">
var currentShowForm=-1;

function showCommentForm(reply) {	
	if (document.getElementById('reply_'+currentShowForm)) {		
		var div_form_comment=document.getElementById('reply_'+currentShowForm);
		div_form_comment.style.display='none';
	} else {
		var div_form_comment=document.getElementById('form_comment');
	}
	var div_reply=document.getElementById('reply_'+reply);
	
	var form_comment_reply=document.getElementById('form_comment_reply');
	
	document.getElementById('text_preview').innerHTML='';
	
	form_comment_reply.value=reply;
	var s=div_form_comment.innerHTML;
	div_form_comment.innerHTML='';	
	div_reply.innerHTML=s;		
	div_reply.style.display='block';	
	currentShowForm=reply;
	document.getElementById('form_comment_text').focus();
}
</script>
{/literal}





	<div id="commentsdiv">
		<div class="text_comments"> 
		
		{if count($aComments)}
		
  			<div class="head_comments_holder">
  				<a name="comments"><img src="{$DIR_STATIC_SKIN}/img/comment.gif"></a>
  				<span class="news_page_comments_title">комментарии({$oTopic->getCountComment()}):</span>&nbsp;  				
  			</div> 
  			
  			{foreach from=$aComments item=oComment}
  			
			<div class="{if $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}comment_item_self{else}{if $dDateTopicRead<=$oComment->getDate()}comment_item_new2{else}comment_item{/if}{/if}" style="margin-left: {$oComment->getLevel()*30}px;">  
				<a name="comment{$oComment->getId()}" href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/"><img class="comments_avatar"   src="{$oComment->getUserProfileAvatarPath(24)}" width="24" height="24" alt="" title="{$oComment->getUserLogin()}" border="0"></a>
   				<div class="service_text_comments_holder">
   					<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/" class="comments_nickname">{$oComment->getUserLogin()}</a>  
   					<span class="comments_date">{date_format date=$oComment->getDate()}</span> 
   					<a href="#comment{$oComment->getId()}" class="small" title=" ссылка ">#</a> 
				</div>
   				<div class="rating_comment_holder" id="voter{$oComment->getId()}">
   					<span class="comments_rating_off" style="color: {if $oComment->getRating()<0}#d00000{else}#008000{/if};"  id="comment_rating_{$oComment->getId()}">{$oComment->getRating()}</span>&nbsp;
   					
   					<span id="comment_vote_self_{$oComment->getId()}" style="display: none;">
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_down_gray.gif" border="0" alt="-" title="нельзя голосовать за свой комментарий"> 
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up_gray.gif" border="0" alt="+" title="нельзя голосовать за свой комментарий">
					</span>	
					<span id="comment_vote_is_vote_up_{$oComment->getId()}" style="display: none;">
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_down_gray.gif" border="0" alt="-" title="вы уже голосовали за этот комментарий"> 
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up.gif" border="0" alt="+" title="вы уже голосовали за этот комментарий">
					</span>
					<span id="comment_vote_is_vote_down_{$oComment->getId()}" style="display: none;">
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_down.gif" border="0" alt="-" title="вы уже голосовали за этот комментарий"> 
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up_gray.gif" border="0" alt="+" title="вы уже голосовали за этот комментарий">
					</span>
					<span id="comment_vote_ok_{$oComment->getId()}" style="display: none;">
						<a href="#" onclick="ajaxVoteComment({$oComment->getId()},-1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_comment_down.gif" border="0" alt="-" title="плохой комментарий"></a>
						<a href="#" onclick="ajaxVoteComment({$oComment->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_comment_up.gif" border="0" alt="+" title="хороший комментарий"></a>							
					</span>
					<span id="comment_vote_anonim_{$oComment->getId()}" style="display: none;">
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_down_gray.gif" border="0" alt="-" title="для голосования необходимо авторизоваться"> 
						<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up_gray.gif" border="0" alt="+" title="для голосования необходимо авторизоваться">
					</span>
   					
   					{if $oUserCurrent}  
   						{if $oComment->getUserId()==$oUserCurrent->getId()}
   							<script>showCommentVote('comment_vote_self',{$oComment->getId()});</script>
   						{else}	
   							{if $oComment->getUserIsVote()}		
   								{if $oComment->getUserVoteDelta()>0}
   									<script>showCommentVote('comment_vote_is_vote_up',{$oComment->getId()});</script>
   								{else}
   									<script>showCommentVote('comment_vote_is_vote_down',{$oComment->getId()});</script>
   								{/if}
   							{else}		
   								<script>showCommentVote('comment_vote_ok',{$oComment->getId()});</script>
   							{/if}
						{/if}
					{else}
						<script>showCommentVote('comment_vote_anonim',{$oComment->getId()});</script>					
					{/if}													
					
   				</div>
   				<div class="comment_text">         			
         			{if $oComment->isBad()}
						<div style="display: none;" id="comment_text_{$oComment->getId()}">
					    	{$oComment->getText()}
					    </div>
					    <a href="#" onclick="$('comment_text_{$oComment->getId()}').style.display='block';$(this).style.display='none';return false;">раскрыть комментарий</a>
					{else}	
					    {$oComment->getText()}
					{/if}
       			</div>       			
       			{if $oUserCurrent}
       			<div class="comments_reply">
    				<div class="reply_word_holder">(<a href="javascript:showCommentForm({$oComment->getId()});">ответить</a>)</div>    				
    				<div style="display: none;" id="reply_{$oComment->getId()}"></div>
    			</div>
    			{/if}
      		</div>
      		
      		{/foreach}
      		{/if}
      			
      	{if $oUserCurrent}			
			<div class="WriteCommentHolder">
  				<img src="{$DIR_STATIC_SKIN}/img/comment.gif"> <a name="comment" href="javascript:showCommentForm(0);" class="news_page_comments_title">написать комментарий</a>
  				<br />
  				<span class="form_note">
  					<a href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/"><img  class="img_border" src="{if $oUserCurrent->getProfileAvatar()}{$oUserCurrent->getProfileAvatarPath(24)}{else}{$DIR_STATIC_SKIN}/img/avatar_24x24.jpg{/if}" width="24" height="24" alt="" title=" это я! " border="0"></a>
  					&nbsp;&nbsp;&nbsp;вы&nbsp;&mdash;&nbsp;
  					<a href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/" class="comments_nickname">{$oUserCurrent->getLogin()}</a>  	
  				</span><br /><br />
				<div style="display: none;" id="reply_0"></div>
			</div>
		{else}
			<div class="text">
  				<br />
				Только зарегистрированные и авторизованные пользователи могут оставлять комментарии.
  				<a href="{$DIR_WEB_ROOT}/login/">Авторизуйтесь</a>, пожалуйста, или 
  				<a href="{$DIR_WEB_ROOT}/registration/">зарегистрируйтесь</a>, если не зарегистрированы.<br><br>
			</div>			
		{/if}
			
			
		</div>
	</div>
	
