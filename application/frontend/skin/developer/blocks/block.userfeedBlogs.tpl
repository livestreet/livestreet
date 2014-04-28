{**
 * Выбор блогов для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.userfeed_block_blogs_title}{/block}
{block 'block_type'}activity{/block}

{block 'block_content'}
	<small class="note mb-15">{$aLang.userfeed_settings_note_follow_blogs}</small>

	{if $aUserfeedBlogs}
		{foreach $aUserfeedBlogs as $oBlog}
			{include 'forms/fields/form.field.checkbox.tpl'
					 sFieldInputClasses    = 'js-userfeed-subscribe'
					 sFieldInputAttributes = "data-id=\"{$oBlog->getId()}\""
					 bFieldChecked         = isset($aUserfeedSubscribedBlogs[$oBlog->getId()])
					 sFieldLabel           = "<a href=\"{$oBlog->getUrlFull()}\">{$oBlog->getTitle()|escape}</a>"}
		{/foreach}
	{else}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.userfeed_no_blogs sMods='info'}
	{/if}
{/block}