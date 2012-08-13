{extends file="topic.prototype.tpl"}

{block name="topic_title_icons" append}
	<i class="icon-synio-topic-link" title="{$aLang.topic_link}"></i>
{/block}{*/topic_title_icons*}

{block name="topic_content_wrap" append}
	<div class="topic-url">
		<a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl()}</a>
	</div>
{/block}{*/topic_content_wrap*}