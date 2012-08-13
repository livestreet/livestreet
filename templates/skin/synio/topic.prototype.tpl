{block name="topic_head"}
	{assign var="oBlog" value=$oTopic->getBlog()}
	{assign var="oUser" value=$oTopic->getUser()}
	{assign var="oVote" value=$oTopic->getVote()}
	{assign var="oFavourite" value=$oTopic->getFavourite()}
	{if !isset($sHookPrefix)}{assign var="sHookPrefix" value="topic_"}{/if}
	{if !isset($bTopicPreview)}{assign var="bTopicPreview" value=false}{/if}
{/block}{*/topic_head*}

<!-- Topic {$oTopic->getType()} -->
{block name="topic_body_wrap"}
<article class="{block name="topic_body_class"}topic topic-type-{$oTopic->getType()} js-topic{/block}">
	
	{block name="topic_header_wrap"}
	
	<header class="topic-header">
		
		{block name="topic_header"}
		
		{block name="topic_title_wrap"}
		<h1 class="topic-title word-wrap">
			
			{block name="topic_title"}
			
			{if $bTopicList}
				<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
			{else}
				{$oTopic->getTitle()|escape:'html'}
			{/if}
			
			{block name="topic_title_icons"}
			{if $oTopic->getPublish() == 0}   
				<i class="icon-synio-topic-draft" title="{$aLang.topic_unpublish}"></i>
			{/if}
			{/block}{*/topic_title_icons*}
			
			{/block}{*/topic_title*}
			
		</h1>
		{/block}{*/topic_title_wrap*}
		
		{if !$bTopicPreview}
		{block name="topic_blog_info_wrap"}
		<div class="topic-info">
			{block name="topic_blog_info"}
			<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape:'html'}</a> 
			{if $oBlog->getType() != 'personal'}
				<a href="#" class="blog-list-info" onclick="return ls.infobox.showInfoBlog(this,{$oBlog->getId()});"></a>
			{/if}
			{/block}{*/topic_blog_info*}
		</div>
		{/block}{*/topic_blog_info_wrap*}
		{/if}
		
		{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
			
			{if !$bTopicPreview}
			{block name="topic_actions_wrap"}
			
			<ul class="topic-actions">
				
				{block name="topic_actions"}
				
				{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
					<li class="edit"><i class="icon-synio-actions-edit"></i><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}" class="actions-edit">{$aLang.topic_edit}</a></li>
				{/if}
				
				{if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
					<li class="delete"><i class="icon-synio-actions-delete"></i><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="actions-delete">{$aLang.topic_delete}</a></li>
				{/if}
				
				{/block}{*/topic_actions*}
				
			</ul>
			
			{/block}{*/topic_actions_wrap*}
			{/if}
			
		{/if}
		
		
		{/block}{*/topic_header*}
		
	</header>
	
	{/block}{*/topic_header_wrap*}
	
	
	{block name="topic_content_wrap"}
	<div class="topic-content text">
		
		{block name="topic_content"}
		
		{hook run="{$sHookPrefix}content_begin" topic=$oTopic bTopicList=$bTopicList}
		
		{if $bTopicList}
			{$oTopic->getTextShort()}
			
			{if $oTopic->getTextShort()!=$oTopic->getText()}
				<br/>
				<a href="{$oTopic->getUrl()}#cut" title="{$aLang.topic_read_more}">
					{if $oTopic->getCutText()}
						{$oTopic->getCutText()}
					{else}
						{block name="topic_content_more"}
						{$aLang.topic_read_more} &rarr;
						{/block}{*/topic_content_more*}
					{/if}
				</a>
			{/if}
		{else}
			{$oTopic->getText()}
		{/if}
		
		{hook run="{$sHookPrefix}content_end" topic=$oTopic bTopicList=$bTopicList}
		
		{/block}{*/topic_content*}
		
	</div>
	{/block}{*/topic_content_wrap*}
	
	
	{block name="topic_footer_wrap"}
	<footer class="topic-footer">
		{block name="topic_footer"}
		
		{block name="topic_tags_wrap"}
		<ul class="topic-tags js-favourite-insert-after-form js-favourite-tags-topic-{$oTopic->getId()}">
			<li><i class="icon-synio-tags"></i></li>
			
			{strip}
				{if $oTopic->getTagsArray()}
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li>{if !$smarty.foreach.tags_list.first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
					{/foreach}
				{else}
					<li>{$aLang.topic_tags_empty}</li>
				{/if}
				
				{if $oUserCurrent}
					{if $oFavourite}
						{foreach from=$oFavourite->getTagsArray() item=sTag name=tags_list_user}
							<li class="topic-tags-user js-favourite-tag-user">, <a rel="tag" href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
						{/foreach}
					{/if}
					
					<li class="topic-tags-edit js-favourite-tag-edit" {if !$oFavourite}style="display:none;"{/if}>
						<a href="#" onclick="return ls.favourite.showEditTags({$oTopic->getId()},'topic',this);" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
					</li>
				{/if}
			{/strip}
		</ul>
		{/block}{*/topic_tags_wrap*}
		
		{if !$bTopicPreview}
		{block name="topic_share_wrap"}
		<div class="topic-share" id="topic_share_{$oTopic->getId()}">
			{hookb run="{$sHookPrefix}share" topic=$oTopic bTopicList=$bTopicList}
				<div class="yashare-auto-init" data-yashareTitle="{$oTopic->getTitle()|escape:'html'}" data-yashareLink="{$oTopic->getUrl()}" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus"></div>
			{/hookb}
			<div class="arrow"></div>
			<div class="close" onclick="jQuery('#topic_share_{$oTopic->getId()}').slideToggle();"></div>
		</div>
		{/block}{*/topic_share_wrap*}
		{/if}
		
		{block name="topic_info_wrap"}
		<ul class="topic-info">
			{block name="topic_info"}
			
			{block name="topic_info_author_wrap"}
			<li class="topic-info-author">
				<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
				<a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
			</li>
			{/block}{*/topic_info_author_wrap*}
			
			{block name="topic_info_date_wrap"}
			<li class="topic-info-date">
				<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
					{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
				</time>
			</li>
			{/block}{*/topic_info_date_wrap*}
			
			{if !$bTopicPreview}
			{block name="topic_info_share_wrap"}
			<li class="topic-info-share" data-topic-id="{$oTopic->getId()}"
				onclick="jQuery('#topic_share_{$oTopic->getId()}').slideToggle();"
				><i class="icon-synio-share-blue" title="{$aLang.topic_share}"></i></li>
			{/block}{*/topic_info_share_wrap*}
			{/if}
			
			{if !$bTopicPreview}
			{block name="topic_info_favourite_wrap"}
			<li class="topic-info-favourite" onclick="ls.favourite.toggle({$oTopic->getId()},$('#fav_topic_{$oTopic->getId()}'),'topic');">
				<i id="fav_topic_{$oTopic->getId()}" class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}"></i>
				<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}">{if $oTopic->getCountFavourite()>0}{$oTopic->getCountFavourite()}{/if}</span>
			</li>
			{/block}{*/topic_info_favourite_wrap*}
			{/if}
			
			{if $bTopicList}
				{block name="topic_info_comments_wrap"}
				<li class="topic-info-comments">
					{if $oTopic->getCountCommentNew()}
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}" class="new">
							<i class="icon-synio-comments-green-filled"></i>
							<span>{$oTopic->getCountComment()}</span>
							<span class="count">+{$oTopic->getCountCommentNew()}</span>
						</a>
					{else}
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">
							{if $oTopic->getCountComment()}
								<i class="icon-synio-comments-green-filled"></i>
							{else}
								<i class="icon-synio-comments-blue"></i>
							{/if}
							
							<span>{$oTopic->getCountComment()}</span>
						</a>
					{/if}
				</li>
				{/block}{*/topic_info_comments_wrap*}
			{/if}


			{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
				{assign var="bVoteInfoShow" value=true}
			{/if}
			
			{if !$bTopicPreview}
			{block name="topic_info_vote_wrap"}
			<li class="topic-info-vote">
				
				{block name="topic_info_vote"}
				<div id="vote_area_topic_{$oTopic->getId()}" class="vote-topic
						{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
							{if $oTopic->getRating() > 0}
								vote-count-positive
							{elseif $oTopic->getRating() < 0}
								vote-count-negative
							{elseif $oTopic->getRating() == 0}
								vote-count-zero
							{/if}
						{/if}
						
						{if !$oUserCurrent or ($oUserCurrent && $oTopic->getUserId() != $oUserCurrent->getId())}
							vote-not-self
						{/if}
						
						{if $oVote}
							voted
							
							{if $oVote->getDirection() > 0}
								voted-up
							{elseif $oVote->getDirection() < 0}
								voted-down
							{elseif $oVote->getDirection() == 0}
								voted-zero
							{/if}
						{else}
							not-voted
						{/if}
						
						{if (strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time') && !$oVote) || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId())}
							vote-nobuttons
						{/if}
						
						{if strtotime($oTopic->getDateAdd()) > $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
							vote-not-expired
						{/if}
	
						{if $bVoteInfoShow}js-infobox-vote-topic{/if}">
					
					{block name="topic_info_vote_down"}
					<div class="vote-item vote-down" onclick="ls.vote.vote({$oTopic->getId()},this,-1,'topic');"><span><i></i></span></div>
					{/block}{*/topic_info_vote_down*}
					
					{block name="topic_info_vote_count"}
					<div class="vote-item vote-count" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">
						<span id="vote_total_topic_{$oTopic->getId()}">
							{if $bVoteInfoShow}
								{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
							{else}
								<i onclick="ls.vote.vote({$oTopic->getId()},this,0,'topic');"></i>
							{/if}
						</span>
					</div>
					{/block}{*/topic_info_vote_count*}
					
					{block name="topic_info_vote_up"}
					<div class="vote-item vote-up" onclick="ls.vote.vote({$oTopic->getId()},this,1,'topic');"><span><i></i></span></div>
					{/block}{*/topic_info_vote_up*}
					
					{if $bVoteInfoShow}
						<div id="vote-info-topic-{$oTopic->getId()}" style="display: none;">
							<ul class="vote-topic-info">
								<li><i class="icon-synio-vote-info-up"></i> {$oTopic->getCountVoteUp()}</li>
								<li><i class="icon-synio-vote-info-down"></i> {$oTopic->getCountVoteDown()}</li>
								<li><i class="icon-synio-vote-info-zero"></i> {$oTopic->getCountVoteAbstain()}</li>
								{hook run="{$sHookPrefix}show_vote_stats" topic=$oTopic}
							</ul>
						</div>
					{/if}
					
				</div>
				{/block}{*/topic_info_vote*}
			</li>
			{/block}{*/topic_info_vote_wrap*}
			{/if}
			{hook run="{$sHookPrefix}show_info" topic=$oTopic}
			
			{/block}{*/topic_info*}
		</ul>
		{/block}{*/topic_info_wrap*}

		
		{if !$bTopicList}
			{hook run="{$sHookPrefix}show_end" topic=$oTopic}
		{/if}
		
		{/block}{*/topic_footer*}
	</footer>
	{/block}{*/topic_footer_wrap*}
	
{/block}{*/topic_body_wrap*}
<!-- /Topic -->