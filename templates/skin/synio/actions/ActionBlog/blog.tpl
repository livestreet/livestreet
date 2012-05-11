{include file='header.tpl'}
{assign var="oUserOwner" value=$oBlog->getOwner()}
{assign var="oVote" value=$oBlog->getVote()}


<script type="text/javascript">
	jQuery(function($){
		ls.lang.load({lang_load name="blog_fold_info,blog_expand_info"});
	});
</script>


{if $oUserCurrent and $oUserCurrent->isAdministrator()}
	<div id="blog_delete_form" class="modal">
		<header class="modal-header">
			<h3>{$aLang.blog_admin_delete_title}</h3>
			<a href="#" class="close jqmClose"></a>
		</header>
		
		
		<form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST" class="modal-content">
			<p><label for="topic_move_to">{$aLang.blog_admin_delete_move}:</label>
			<select name="topic_move_to" id="topic_move_to" class="input-width-full">
				<option value="-1">{$aLang.blog_delete_clear}</option>
				{if $aBlogs}
					<optgroup label="{$aLang.blogs}">
						{foreach from=$aBlogs item=oBlogDelete}
							<option value="{$oBlogDelete->getId()}">{$oBlogDelete->getTitle()|escape:'html'}</option>
						{/foreach}
					</optgroup>
				{/if}
			</select></p>
			
			<input type="hidden" value="{$LIVESTREET_SECURITY_KEY}" name="security_ls_key" />
			<button class="button button-primary">{$aLang.blog_delete}</button>
		</form>
	</div>
{/if}


<h2 class="page-header">{$oBlog->getTitle()|escape:'html'} {if $oBlog->getType()=='close'} <i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>{/if}</h2>



<div class="blog-mini" id="blog-mini">
	{$iCountBlogUsers} {$iCountBlogUsers|declension:$aLang.reader_declension:'russian'}, 
	{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension:'russian'}
	<div class="fl-r">
		<a href="#" class="link-dotted" onclick="jQuery('#blog-mini').hide(); jQuery('#blog').show(); return false;">О блоге</a>
		<a href="#">RSS</a>
		<button class="button button-small">Читать в ленте</button>
	</div>{*r*}
</div>



<div class="blog" id="blog" style="display: none">
	<div class="blog-inner">
		<header class="blog-header">
			{*<div id="vote_area_blog_{$oBlog->getId()}" class="vote {if $oBlog->getRating() > 0}vote-count-positive{elseif $oBlog->getRating() < 0}vote-count-negative{/if} {if $oVote} voted {if $oVote->getDirection()>0}voted-up{elseif $oVote->getDirection()<0}voted-down{/if}{/if}">
				<div class="vote-label">Рейтинг</div>
				<a href="#" class="vote-up" onclick="return ls.vote.vote({$oBlog->getId()},this,1,'blog');"></a>
				<a href="#" class="vote-down" onclick="return ls.vote.vote({$oBlog->getId()},this,-1,'blog');"></a>
				<div id="vote_total_blog_{$oBlog->getId()}" class="vote-count count" title="{$aLang.blog_vote_count}: {$oBlog->getCountVote()}">{$oBlog->getRating()}</div>
			</div>*}
			
			{*<ul class="actions">
				{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
					<li><a href="#" onclick="ls.blog.toggleJoin(this,{$oBlog->getId()}); return false;" class="link-dotted">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</a></li>
				{/if}
				{if $oUserCurrent and ($oUserCurrent->getId()==$oBlog->getOwnerId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() )}
					<li>
						<a href="{router page='blog'}edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}" class="edit">{$aLang.blog_edit}</a></li>
						{if $oUserCurrent->isAdministrator()}
							<li><a href="#" title="{$aLang.blog_delete}" id="blog_delete_show" class="delete">{$aLang.blog_delete}</a>
						{else}
							<a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.blog_delete}" onclick="return confirm('{$aLang.blog_admin_delete_confirm}');" >{$aLang.blog_delete}</a>
						{/if}
					</li>
				{/if}
			</ul>*}
			
			<span class="close" onclick="jQuery('#blog-mini').show(); jQuery('#blog').hide(); return false;"><a href="#" class="link-dotted">Свернуть</a><i class="icon-synio-close"></i></span>
			
			{*r*}
		</header>

		
		<div class="blog-content">
			<p class="blog-description">{$oBlog->getDescription()|nl2br}</p>			
		</div>
		
		<ul class="blog-info">{*r*}
			<li><span>Создан</span> <strong>{date_format date=$oBlog->getDateAdd() format="j F Y"}</strong></li>
			<li><span>Топиков</span> <strong>{$oBlog->getCountTopic()}</strong></li>
			<li><span><a href="{$oBlog->getUrlFull()}users/">Читателей</a></span> <strong>{$iCountBlogUsers}</strong></li>
			<li class="rating"><span>Рейтинг</span> <strong>{$oBlog->getRating()}</strong></li>
		</ul>
		
		{*
		{hook run='blog_info_begin' oBlog=$oBlog}
		<strong>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators}):</strong>							
		<a href="{$oUserOwner->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUserOwner->getLogin()}</a>
		{if $aBlogAdministrators}			
			{foreach from=$aBlogAdministrators item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}  									
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}	
		{/if}<br />		

		
		<strong>{$aLang.blog_user_moderators} ({$iCountBlogModerators}):</strong>
		{if $aBlogModerators}						
			{foreach from=$aBlogModerators item=oBlogUser}  
				{assign var="oUser" value=$oBlogUser->getUser()}									
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}							
		{else}
			{$aLang.blog_user_moderators_empty}
		{/if}<br />
		
		
		<strong>{$aLang.blog_user_readers} ({$iCountBlogUsers}):</strong>
		{if $aBlogUsers}
			{foreach from=$aBlogUsers item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}
			
			{if count($aBlogUsers) < $iCountBlogUsers}
				<br /><a href="{$oBlog->getUrlFull()}users/">{$aLang.blog_user_readers_all}</a>
			{/if}
		{else}
			{$aLang.blog_user_readers_empty}
		{/if}
		{hook run='blog_info_end' oBlog=$oBlog}
		*}
	</div>
	
	<footer class="blog-footer">
		<button class="button button-small">Читать в ленте</button>
		<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="rss">RSS</a>
		
		<div class="admin">{*r*}
			Смотритель — 
			<a href="{router page='profile'}{$oUserOwner->getLogin()}/"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
			<a href="{router page='profile'}{$oUserOwner->getLogin()}/">{$oUserOwner->getLogin()}</a>
		</div>
	</footer>
</div>

{hook run='blog_info' oBlog=$oBlog}

<div class="nav-menu-wrapper">
	<ul class="nav nav-pills">
		<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><a href="{$sMenuSubBlogUrl}">{$aLang.blog_menu_collective_good}</a></li>
		{if $iCountTopicsBlogNew>0}<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{$sMenuSubBlogUrl}new/">{$aLang.blog_menu_collective_new} +{$iCountTopicsBlogNew}</a></li>{/if}
		<li {if $sMenuSubItemSelect=='discussed'}class="active"{/if}><a href="{$sMenuSubBlogUrl}discussed/">{$aLang.blog_menu_collective_discussed}</a></li>
		<li {if $sMenuSubItemSelect=='top'}class="active"{/if}><a href="{$sMenuSubBlogUrl}top/">{$aLang.blog_menu_collective_top}</a></li>
		{hook run='menu_blog_blog_item'}
	</ul>

	{if $sPeriodSelectCurrent}
		<ul class="nav nav-pills">
			<li {if $sPeriodSelectCurrent=='1'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=1">{$aLang.blog_menu_top_period_24h}</a></li>
			<li {if $sPeriodSelectCurrent=='7'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=7">{$aLang.blog_menu_top_period_7d}</a></li>
			<li {if $sPeriodSelectCurrent=='30'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=30">{$aLang.blog_menu_top_period_30d}</a></li>
			<li {if $sPeriodSelectCurrent=='all'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=all">{$aLang.blog_menu_top_period_all}</a></li>
		</ul>
	{/if}
</div>




{if $bCloseBlog}
	{$aLang.blog_close_show}
{else}
	{include file='topic_list.tpl'}
{/if}


{include file='footer.tpl'}