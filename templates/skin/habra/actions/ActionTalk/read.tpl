{include file='header.tpl'}

{include file='system_message.tpl'}

<DIV class=blogposts>
	<div class="text">
  		<!--medialand_ru_context_start-->    	
    	<h1 class="blog_headline">
  				<a href="{$DIR_WEB_ROOT}/talk/" class="blog_headline_group">Почтовый ящик</a>&nbsp;&#8594;&nbsp;  				
  				{$oTalk->getTitle()|escape:'html'}  				
  			</h1>
    	<div class="groups_topic_text">
		    {$oTalk->getText()}    
    		<br><br>

			<!--medialand_ru_context_end-->
	     	<div class="info_holder">
				<div class="ball first" id="voter1">
					{date_format date=$oTalk->getDate()}
				</div>								
			<div class="user">
				<a href="{$DIR_WEB_ROOT}/profile/{$oTalk->getUserLogin()}/" title="автор"><span>{$oTalk->getUserLogin()}</span></a>
			</div>
		</div>
	</div>
</div>

<br>


<div id="form_comment" style="display: none;">	
 	<form action="" method="POST">
    	<textarea class="input_comments_reply" name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>    	
    	<input type="submit" name="submit_comment" value="добавить">    	
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
	
	form_comment_reply.value=reply;
	var s=div_form_comment.innerHTML;
	div_form_comment.innerHTML='';	
	div_reply.innerHTML=s;		
	div_reply.style.display='block';	
	currentShowForm=reply;
}
</script>
{/literal}





	<div id="commentsdiv">
		<div class="text_comments"> 
		
		{if count($aComments)}
		
  			<div class="head_comments_holder">
  				<a name="comments"><img src="{$DIR_STATIC_SKIN}/img/comment.gif"></a>
  				<span class="news_page_comments_title">ответы({$oTalk->getCountComment()}):</span>&nbsp;  				
  			</div> 
  			
  			{foreach from=$aComments item=oComment}
  			
			<div class="{if $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}comment_item_self{else}{if $oTalk->getDateLastRead()<=$oComment->getDate()}comment_item_new2{else}comment_item{/if}{/if}" style="margin-left: {$oComment->getLevel()*30}px;">  
				<a name="comment{$oComment->getId()}" href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/"><img class="comments_avatar"   src="{$oComment->getUserProfileAvatarPath(24)}" width="24" height="24" alt="" title="{$oComment->getUserLogin()}" border="0"></a>
   				<div class="service_text_comments_holder">
   					<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/" class="comments_nickname">{$oComment->getUserLogin()}</a>  
   					<span class="comments_date">{date_format date=$oComment->getDate()}</span> 
   					<a href="#comment{$oComment->getId()}" class="small" title=" ссылка ">#</a> 
				</div>   				
   				<div class="comment_text">
         			{$oComment->getText()}
       			</div>       			
       			<div class="comments_reply">
    				<div class="reply_word_holder">(<a href="javascript:showCommentForm({$oComment->getId()});">ответить</a>)</div>
    				<div style="display: none;" id="reply_{$oComment->getId()}"></div>
    			</div>    			
      		</div>
      		
      		{/foreach}
      		{/if}
      			
      				
			<div class="WriteCommentHolder">
  				<img src="{$DIR_STATIC_SKIN}/img/comment.gif"> <a name="comment" href="javascript:showCommentForm(0);" class="news_page_comments_title">ответить</a>
  				<br />
  				<span class="form_note">
  					<a href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/"><img  class="img_border" src="{if $oUserCurrent->getProfileAvatar()}{$oUserCurrent->getProfileAvatarPath(24)}{else}{$DIR_STATIC_SKIN}/img/avatar_24x24.jpg{/if}" width="24" height="24" alt="" title=" это я! " border="0"></a>
  					&nbsp;&nbsp;&nbsp;вы&nbsp;&mdash;&nbsp;
  					<a href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/" class="comments_nickname">{$oUserCurrent->getLogin()}</a>  	
  				</span><br /><br />
				<div style="display: none;" id="reply_0"></div>
			</div>
		
			
			
		</div>
	</div>

{include file='footer.tpl'}

