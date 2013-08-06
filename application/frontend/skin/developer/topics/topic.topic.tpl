{**
 * Обычный топик
 *
 * @styles css/topic.css
 *}

{extends file='topics/topic_base.tpl'}


{block name='topic_content_text'}
	{if $bTopicList}
		{$oTopic->getTextShort()}
		
		{if $oTopic->getTextShort() != $oTopic->getText()}
			<br/>
			<a href="{$oTopic->getUrl()}#cut" title="{$aLang.topic_read_more}">
				{$oTopic->getCutText()|default:$aLang.topic_read_more}
			</a>
		{/if}
	{else}
		{$oTopic->getText()}
	{/if}
{/block}