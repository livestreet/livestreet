{extends file='topics/topic_base.tpl'}


{block name='icon'}<i class="icon-share-alt" title="{$aLang.topic_link}"></i>{/block}

{block name='content_after'}
	<div class="topic-url">
		<a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl()}</a>
	</div>
{/block}